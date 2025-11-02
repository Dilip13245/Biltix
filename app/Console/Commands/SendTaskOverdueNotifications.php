<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Helpers\NotificationHelper;
use Carbon\Carbon;

class SendTaskOverdueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:task-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications for overdue tasks (daily check)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks...');

        $now = Carbon::now();

        // Get all overdue tasks
        $tasks = Task::where('is_active', true)
            ->where('is_deleted', false)
            ->where('status', '!=', 'complete')
            ->where('due_date', '<', $now)
            ->with(['assignedUser', 'project', 'createdBy'])
            ->get();

        // Filter out tasks that already have overdue notifications sent today
        $tasks = $tasks->filter(function ($task) {
            $notifications = \App\Models\Notification::where('user_id', $task->assigned_to)
                ->where('type', 'task_overdue')
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

            $daysOverdue = Carbon::parse($task->due_date)->diffInDays($now);

            // Send notification to assigned user
            NotificationHelper::send(
                $task->assigned_to,
                'task_overdue',
                'Task Overdue',
                "Task '{$task->title}' is overdue by {$daysOverdue} day(s)",
                [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'project_id' => $task->project_id,
                    'project_title' => $task->project->project_title,
                    'due_date' => $task->due_date,
                    'days_overdue' => $daysOverdue,
                    'priority' => $task->priority,
                    'action_url' => "/tasks/{$task->id}"
                ],
                'high'
            );

            // Notify project manager
            if ($task->project->project_manager_id) {
                NotificationHelper::send(
                    $task->project->project_manager_id,
                    'task_overdue',
                    'Team Task Overdue',
                    "Task '{$task->title}' assigned to {$task->assignedUser->name} is overdue by {$daysOverdue} day(s)",
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'assigned_to' => $task->assigned_to,
                        'assigned_to_name' => $task->assignedUser->name,
                        'project_id' => $task->project_id,
                        'days_overdue' => $daysOverdue,
                        'action_url' => "/tasks/{$task->id}"
                    ],
                    'high'
                );
            }

            // Notify task creator if different
            if ($task->created_by && $task->created_by != $task->assigned_to) {
                NotificationHelper::send(
                    $task->created_by,
                    'task_overdue',
                    'Task Overdue',
                    "Task '{$task->title}' you created is overdue by {$daysOverdue} day(s)",
                    [
                        'task_id' => $task->id,
                        'task_title' => $task->title,
                        'assigned_to' => $task->assigned_to,
                        'project_id' => $task->project_id,
                        'days_overdue' => $daysOverdue,
                        'action_url' => "/tasks/{$task->id}"
                    ],
                    'medium'
                );
            }

            $sentCount++;
        }

        $this->info("Sent {$sentCount} overdue task notifications.");
        return Command::SUCCESS;
    }
}

