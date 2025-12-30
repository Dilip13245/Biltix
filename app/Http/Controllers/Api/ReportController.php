<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectReport;
use App\Models\Task;
use App\Models\Inspection;
use App\Models\ProjectSafetyItem;
use App\Models\Meeting;
use App\Models\ProjectQualityWork;
use App\Models\ProjectMaterialAdequacy;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Spatie\Browsershot\Browsershot;
use App\Models\User;

class ReportController extends Controller
{
    use ApiResponseTrait;

    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'report_type' => 'required|in:daily,weekly,monthly',
            'week' => 'required_if:report_type,weekly',
            'month' => 'required_if:report_type,monthly',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $project = Project::with(['owner', 'contractor'])->find($request->project_id);
        
        $dateRange = $this->getDateRange($request->report_type, $request->week, $request->month);
        
        $reportNumber = 'RPT-' . $project->id . '-' . date('Ymd-His');
        
        $phases = \App\Models\ProjectPhase::where('project_id', $request->project_id)
            ->with('milestones')
            ->get()
            ->map(function ($phase) {
                $totalDays = $phase->milestones->sum('days') ?? 0;
                $phase->time_progress = $totalDays > 0 ? min(100, (now()->diffInDays($phase->created_at) / $totalDays) * 100) : 0;
                return $phase;
            });
        
        $overallProgress = $phases->count() > 0 ? $phases->avg('time_progress') : 0;
        
        $data = [
            'project' => $project,
            'report_type' => $request->report_type,
            'report_number' => $reportNumber,
            'date_range' => $dateRange,
            'progress_percentage' => $overallProgress,
            'phases' => $phases,
            'tasks' => Task::where('project_id', $request->project_id)
                ->whereIn('status', ['completed', 'approve'])
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->with('assignedUser:id,name')
                ->get(),
            'inspections' => Inspection::where('project_id', $request->project_id)
                ->whereIn('status', ['completed', 'approved'])
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'snags' => \App\Models\Snag::where('project_id', $request->project_id)
                ->whereIn('status', ['completed', 'approve'])
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->with('comments')
                ->get(),
            'activities' => \App\Models\ProjectActivity::where('project_id', $request->project_id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'manpower_equipment' => \App\Models\ProjectManpowerEquipment::where('project_id', $request->project_id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'safety_items' => ProjectSafetyItem::where('project_id', $request->project_id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'meetings' => Meeting::where('project_id', $request->project_id)
                ->whereBetween('date', [$dateRange['start'], $dateRange['end']])
                ->with('attendees:id,name')
                ->get(),
            'overdue_tasks' => Task::where('project_id', $request->project_id)
                ->where('due_date', '<', now())
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'raw_materials' => \App\Models\RawMaterial::where('project_id', $request->project_id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'quality_work' => ProjectQualityWork::where('project_id', $request->project_id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
            'material_adequacy' => ProjectMaterialAdequacy::where('project_id', $request->project_id)
                ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
                ->get(),
        ];

        return $this->successResponse(__('api.reports.generated_success'), $data);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'report_type' => 'required|in:daily,weekly,monthly',
            'report_data' => 'required',
            'user_id' => 'required|exists:users,id',
            'date_range' => 'nullable|string',
            'general_remarks' => 'nullable|string',
            'general_remarks_attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $reportData = json_decode($request->report_data, true);
        
        $attachmentPath = null;
        if ($request->hasFile('general_remarks_attachment')) {
            $file = $request->file('general_remarks_attachment');
            $fileName = 'attachment_' . time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('reports/attachments', $fileName, 'public');
        }

        $reportData['general_remarks'] = $request->general_remarks;
        $reportData['general_remarks_attachment'] = $attachmentPath;
        
        $creator = User::find($request->user_id);
        $reportData['site_engineer_name'] = $creator ? $creator->name : 'N/A';
        $reportData['signature_date'] = now()->format('d/m/Y');
        
        $reportData['locale'] = app()->getLocale();
        $reportData['is_rtl'] = app()->getLocale() === 'ar';

        $fileName = 'report_' . $request->project_id . '_' . time() . '.pdf';
        $reportType = strtolower($request->report_type);
        $filePath = 'project_files/reports/' . $reportType . '/' . $fileName;
        
        $reportDir = storage_path('app/public/project_files/reports/' . $reportType);
        if (!file_exists($reportDir)) {
            mkdir($reportDir, 0755, true);
        }
        
        try {
            Browsershot::html(view('pdf.report', $reportData)->render())
            ->format('A4')
            ->margins(0, 0, 0, 0)
            ->showBackground()
            ->timeout(60)
                ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->save(storage_path('app/public/' . $filePath));
        } catch (\Exception $e) {
            return $this->errorResponse('PDF generation failed: ' . $e->getMessage(), [], 500);
        }

        $report = ProjectReport::create([
            'project_id' => $request->project_id,
            'report_type' => $request->report_type,
            'date_range' => $request->date_range,
            'file_path' => $filePath,
            'generated_by' => $request->user_id,
            'general_remarks' => $request->general_remarks,
            'general_remarks_attachment' => $attachmentPath,
        ]);

        $this->createReportFileRecord($request->project_id, $fileName, $filePath, $request->user_id, $request->report_type, $request->date_range);

        return $this->successResponse(__('api.reports.saved_success'), $report);
    }

    public function history(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $reports = ProjectReport::where('project_id', $request->project_id)
            ->with('generatedBy:id,name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($report) {
                $report->file_url = url('storage/' . $report->file_path);
                return $report;
            });

        return $this->successResponse(__('api.reports.history_retrieved'), $reports);
    }

    public function share(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'file_path' => 'required|string',
            'recipient_ids' => 'required|array',
            'recipient_ids.*' => 'integer|exists:users,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(__('api.general.validation_error'), $validator->errors(), 422);
        }

        $project = Project::find($request->project_id);
        $sender = User::find($request->user_id);
        $recipients = User::whereIn('id', $request->recipient_ids)->get();
        $fileUrl = url('storage/' . $request->file_path);
        $fileName = basename($request->file_path);
        $senderName = $sender->name ?? 'Project Team';

        $successCount = 0;
        $failedCount = 0;

        foreach ($recipients as $recipient) {
            try {
                Mail::send('emails.report-share', [
                    'recipient_name' => $recipient->name,
                    'project_title' => $project->project_title,
                    'file_url' => $fileUrl,
                    'file_name' => $fileName,
                    'sender_name' => $senderName,
                ], function ($message) use ($recipient, $project) {
                    $message->to($recipient->email)
                        ->subject("Report Shared: {$project->project_title}")
                        ->from(config('mail.from.address'), config('mail.from.name'));
                });

                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
            }
        }

        return $this->successResponse(__('api.reports.shared_success'), [
            'recipients_count' => count($recipients),
            'success_count' => $successCount,
            'failed_count' => $failedCount,
            'recipients' => $recipients->pluck('name', 'id'),
        ]);
    }

    private function getDateRange($type, $week = null, $month = null)
    {
        if ($type === 'daily') {
            return [
                'start' => now()->startOfDay(),
                'end' => now()->endOfDay(),
            ];
        } elseif ($type === 'weekly' && $week) {
            $date = \Carbon\Carbon::parse($week);
            return [
                'start' => $date->copy()->startOfWeek(),
                'end' => $date->copy()->endOfWeek(),
            ];
        } elseif ($type === 'monthly' && $month) {
            $date = \Carbon\Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            return [
                'start' => $date->copy()->startOfMonth(),
                'end' => $date->copy()->endOfMonth(),
            ];
        }
    }

    private function createReportFileRecord($projectId, $fileName, $filePath, $userId, $reportType, $dateRange)
    {
        $folderNames = [
            'daily' => 'Daily Report',
            'weekly' => 'Weekly Report',
            'monthly' => 'Monthly Report'
        ];
        
        $folderName = $folderNames[strtolower($reportType)] ?? ucfirst($reportType) . ' Report';
        
        $folder = \App\Models\FileFolder::firstOrCreate(
            ['project_id' => $projectId, 'name' => $folderName],
            ['created_by' => $userId]
        );

        \App\Models\File::create([
            'project_id' => $projectId,
            'category_id' => 1,
            'folder_id' => $folder->id,
            'name' => $fileName,
            'original_name' => $fileName,
            'file_path' => $filePath,
            'file_size' => filesize(storage_path('app/public/' . $filePath)),
            'file_type' => 'application/pdf',
            'description' => $folderName . ' - ' . ($dateRange ?? now()->format('Y-m-d')),
            'uploaded_by' => $userId,
            'is_active' => true,
            'is_deleted' => false,
        ]);
    }
}
