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
        
        $data = [
            'project' => $project,
            'report_type' => $request->report_type,
            'report_number' => $reportNumber,
            'date_range' => $dateRange,
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
        
        // Handle attachment upload
        $attachmentPath = null;
        if ($request->hasFile('general_remarks_attachment')) {
            $file = $request->file('general_remarks_attachment');
            $fileName = 'attachment_' . time() . '_' . $file->getClientOriginalName();
            $attachmentPath = $file->storeAs('reports/attachments', $fileName, 'public');
        }

        // Add general remarks to report data for PDF
        $reportData['general_remarks'] = $request->general_remarks;
        $reportData['general_remarks_attachment'] = $attachmentPath;
        
        // Add signature data
        $creator = User::find($request->user_id);
        $reportData['site_engineer_name'] = $creator ? $creator->name : 'N/A';
        $reportData['signature_date'] = now()->format('d/m/Y');
        
        // Add locale for bilingual support
        $reportData['locale'] = app()->getLocale();
        $reportData['is_rtl'] = app()->getLocale() === 'ar';

        $fileName = 'report_' . $request->project_id . '_' . time() . '.pdf';
        $filePath = 'reports/' . $fileName;
        
        // Ensure reports directory exists
        if (!file_exists(storage_path('app/public/reports'))) {
            mkdir(storage_path('app/public/reports'), 0755, true);
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
}
