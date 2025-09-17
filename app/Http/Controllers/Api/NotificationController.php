<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $page = $request->input('page', 1);
            $limit = $request->input('limit', 20);
            $type = $request->input('type');

            $query = Notification::where('user_id', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0);

            if ($type) {
                $query->where('type', $type);
            }

            $notifications = $query->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            $data = [
                'data' => $notifications->items()
            ];

            return $this->toJsonEnc($data, trans('api.notifications.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function markRead(Request $request)
    {
        try {
            $notification_id = $request->input('notification_id');
            $user_id = $request->input('user_id');

            $notification = Notification::where('id', $notification_id)
                ->where('user_id', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$notification) {
                return $this->toJsonEnc([], trans('api.notifications.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $notification->is_read = true;
            $notification->read_at = now();
            $notification->save();

            return $this->toJsonEnc($notification, trans('api.notifications.marked_read'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function markAllRead(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            Notification::where('user_id', $user_id)
                ->where('is_read', false)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            return $this->toJsonEnc([], trans('api.notifications.all_marked_read'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $notification_id = $request->input('notification_id');
            $user_id = $request->input('user_id');

            $notification = Notification::where('id', $notification_id)
                ->where('user_id', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->first();

            if (!$notification) {
                return $this->toJsonEnc([], trans('api.notifications.not_found'), Config::get('constant.NOT_FOUND'));
            }

            $notification->is_deleted = true;
            $notification->save();

            return $this->toJsonEnc([], trans('api.notifications.deleted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getCount(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            $unreadCount = Notification::where('user_id', $user_id)
                ->where('is_read', false)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $totalCount = Notification::where('user_id', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->count();

            $data = [
                'unread_count' => $unreadCount,
                'total_count' => $totalCount
            ];

            return $this->toJsonEnc($data, trans('api.notifications.count_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            $user_id = $request->input('user_id');

            Notification::where('user_id', $user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->update(['is_deleted' => 1]);

            return $this->toJsonEnc([], trans('api.notifications.all_deleted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function settings(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            
            // Return notification settings
            $settings = [
                'push_notifications' => true,
                'email_notifications' => true,
                'task_notifications' => true,
                'inspection_notifications' => true,
                'snag_notifications' => true,
                'project_notifications' => true
            ];

            return $this->toJsonEnc($settings, trans('api.notifications.settings_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}