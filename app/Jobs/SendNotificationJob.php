<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\NotificationHelper;
use Illuminate\Support\Facades\Log;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $userIds;
    public $type;
    public $title;
    public $message;
    public $data;
    public $priority;

    /**
     * Create a new job instance.
     *
     * @param array|int $userIds
     * @param string $type
     * @param string $title
     * @param string $message
     * @param array $data
     * @param string $priority
     */
    public function __construct($userIds, $type, $title, $message, $data = [], $priority = 'medium')
    {
        $this->userIds = $userIds;
        $this->type = $type;
        $this->title = $title;
        $this->message = $message;
        $this->data = $data;
        $this->priority = $priority;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('SendNotificationJob: Processing notification', [
                'type' => $this->type,
                'user_ids' => $this->userIds,
                'title' => $this->title
            ]);

            // Call the actual notification sending logic
            NotificationHelper::sendSync(
                $this->userIds,
                $this->type,
                $this->title,
                $this->message,
                $this->data,
                $this->priority
            );

            Log::info('SendNotificationJob: Notification sent successfully', [
                'type' => $this->type
            ]);
        } catch (\Exception $e) {
            Log::error('SendNotificationJob: Failed to send notification', [
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
        Log::error('SendNotificationJob: Job failed permanently', [
            'type' => $this->type,
            'error' => $exception->getMessage()
        ]);
    }
}

