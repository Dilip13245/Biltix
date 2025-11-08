<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;

class SendNotificationToProjectTeamJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $projectId;
    public $type;
    public $title;
    public $message;
    public $data;
    public $priority;
    public $excludeUserIds;

    /**
     * Create a new job instance.
     *
     * @param int $projectId
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     * @param array $excludeUserIds
     */
    public function __construct($projectId, $type, $title, $message, $data = [], $priority = 'medium', $excludeUserIds = [])
    {
        $this->projectId = $projectId;
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
        $this->priority = $priority;
        $this->excludeUserIds = $excludeUserIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendNotificationToProjectTeamJob: Processing notification', [
                'type' => $this->type,
                'project_id' => $this->projectId,
                'title' => $this->title
            ]);

            // Call the actual notification sending logic
            NotificationHelper::sendToProjectTeamSync(
                $this->projectId,
                $this->type,
                $this->title,
                $this->message,
                $this->data,
                $this->priority,
                $this->excludeUserIds
            );

            Log::info('SendNotificationToProjectTeamJob: Notification sent successfully', [
                'type' => $this->type
            ]);
        } catch (\Exception $e) {
            Log::error('SendNotificationToProjectTeamJob: Failed to send notification', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('SendNotificationToProjectTeamJob: Job failed permanently', [
            'type' => $this->type,
            'error' => $exception->getMessage()
        ]);
    }
}

