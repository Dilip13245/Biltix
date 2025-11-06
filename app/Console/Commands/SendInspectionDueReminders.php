<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inspection;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SendInspectionDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:inspection-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications for inspections due in 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending inspection due reminders...');

        // Get inspections scheduled in next 24 hours
        $now = Carbon::now();
        $in24Hours = Carbon::now()->addHours(24);

        // Get inspections that are open and might need reminders
        // Note: If scheduled_date field exists, use it; otherwise use created_at + reasonable timeframe
        $inspectionsQuery = Inspection::where('is_active', true)
            ->where('is_deleted', false)
            ->where('status', 'todo')
            ->with(['inspectedBy', 'project']);
        
        // Check if scheduled_date column exists
        if (Schema::hasColumn('inspections', 'scheduled_date')) {
            $inspectionsQuery->whereNotNull('scheduled_date')
                           ->whereBetween('scheduled_date', [$now, $in24Hours]);
        } else {
            // Fallback: Use created_at and remind if inspection was created recently and is still open
            // Only remind if inspection was created in last 48 hours and is still open
            $hours48Ago = Carbon::now()->subHours(48);
            $inspectionsQuery->where('created_at', '>=', $hours48Ago)
                           ->where('created_at', '<=', $in24Hours);
        }
        
        $inspections = $inspectionsQuery->get();

        // Filter out inspections that already have reminders sent today
        $inspections = $inspections->filter(function ($inspection) {
            if (!$inspection->inspected_by) {
                return false;
            }
            $notifications = \App\Models\Notification::where('user_id', $inspection->inspected_by)
                ->where('type', 'inspection_due')
                ->whereDate('created_at', Carbon::today())
                ->get();
            
            foreach ($notifications as $notification) {
                $data = $notification->data ?? [];
                if (isset($data['inspection_id']) && $data['inspection_id'] == $inspection->id) {
                    return false; // Already sent
                }
            }
            return true; // Not sent yet
        });

        $sentCount = 0;

        foreach ($inspections as $inspection) {
            if (!$inspection->project) {
                continue;
            }

            // Calculate hours remaining
            $hoursRemaining = 24;
            if (Schema::hasColumn('inspections', 'scheduled_date') && $inspection->scheduled_date) {
                $hoursRemaining = Carbon::parse($inspection->scheduled_date)->diffInHours($now);
            } elseif ($inspection->created_at) {
                // Fallback: Use created_at + 48 hours as due time
                $dueTime = Carbon::parse($inspection->created_at)->addHours(48);
                $hoursRemaining = $dueTime->diffInHours($now);
            }

            // Notify inspector
            if ($inspection->inspected_by) {
                NotificationHelper::send(
                    $inspection->inspected_by,
                    'inspection_due',
                    'Inspection Due Soon',
                    "{$inspection->category} inspection is scheduled in {$hoursRemaining} hours",
                    [
                        'inspection_id' => $inspection->id,
                        'project_id' => $inspection->project_id,
                        'project_title' => $inspection->project->project_title,
                        'category' => $inspection->category,
                        'scheduled_date' => $inspection->scheduled_date,
                        'hours_remaining' => $hoursRemaining,
                        'action_url' => "/inspections/{$inspection->id}"
                    ],
                    'high'
                );
            }

            // Notify project manager
            if ($inspection->project->project_manager_id) {
                NotificationHelper::send(
                    $inspection->project->project_manager_id,
                    'inspection_due',
                    'Inspection Due Soon',
                    "{$inspection->category} inspection is scheduled in {$hoursRemaining} hours",
                    [
                        'inspection_id' => $inspection->id,
                        'project_id' => $inspection->project_id,
                        'category' => $inspection->category,
                        'scheduled_date' => $inspection->scheduled_date,
                        'hours_remaining' => $hoursRemaining,
                        'action_url' => "/inspections/{$inspection->id}"
                    ],
                    'high'
                );
            }

            $sentCount++;
        }

        $this->info("Sent {$sentCount} inspection due reminders.");
        return Command::SUCCESS;
    }
}

