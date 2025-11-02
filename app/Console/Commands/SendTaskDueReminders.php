<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class SendTaskDueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:task-due-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications for tasks due in 24 hours';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending task due reminders...');

        // Get tasks due in 24 hours (between now and 24 hours from now)
        $now = Carbon::now();
        $in24Hours = Carbon::now()->addHours(24);

        // Get all tasks due in next 24 hours
        $tasks = Task::where('is_active', true)
            ->where('is_deleted', false)
            ->where('status', '!=', 'complete')
            ->whereBetween('due_date', [$now, $in24Hours])
            ->with(['assignedUser', 'project'])
            ->get();

        // Filter out tasks that already have reminders sent today
        $tasks = $tasks->filter(function ($task) {
            $notifications = \App\Models\Notification::where('user_id', $task->assigned_to)
                ->where('type', 'task_due_soon')
                ->whereDate('created_at', Carbon::today())
                ->get();
            
            foreach ($notifications as $notification) {
                $data = $notification->data ?? [];
                if (isset($data['task_id']) && $data['task_id'] == $task->id) {
                    return false; // Already sent
                }
            }
            return true; // Not sent yet
        });

        $sentCount = 0;

        foreach ($tasks as $task) {
            if (!$task->assignedUser || !$task->project) {
                continue;
            }

            $hoursRemaining = Carbon::parse($task->due_date)->diffInHours($now);

            // Send notification to assigned user
            NotificationHelper::send(
                $task->assigned_to,
                'task_due_soon',
                'Task Due Soon',
                "Task '{$task->title}' is due in {$hoursRemaining} hours",
                [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'project_id' => $task->project_id,
                    'project_title' => $task->project->project_title,
                    'due_date' => $task->due_date,
                    'hours_remaining' => $hoursRemaining,
                    'priority' => $task->priority,
                    'action_url' => "/tasks/{$task->id}"
                ],
                'high'
            );

            // Also notify project manager
            if ($task->project->project_manager_id) {
                NotificationHelper::send(
                    $task->project->project_manager_id,
                    'task_due_soon',
                    'Team Task Due Soon',
                    "Task '{$task->title}' assigned to {$task->assignedUser->name} is due in {$hoursRemaining} hours",
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'assigned_to' => $task->assigned_to,
                        'assigned_to_name' => $task->assignedUser->name,
                        'project_id' => $task->project_id,
                        'due_date' => $task->due_date,
                        'hours_remaining' => $hoursRemaining,
                        'action_url' => "/tasks/{$task->id}"
                    ],
                    'medium'
                );
            }

            $sentCount++;
        }

        $this->info("Sent {$sentCount} task due reminders.");
        return Command::SUCCESS;
    }
}

