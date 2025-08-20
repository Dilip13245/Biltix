<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        try {
            $query = Notification::active()->where('user_id', $request->user_id);

            if ($request->is_read !== null) {
                $query->where('is_read', $request->is_read);
            }

            $notifications = $query->orderBy('created_at', 'desc')
                                 ->paginate($request->limit ?? 20);

            return $this->toJsonEnc($notifications, trans('api.notifications.list_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function markRead(Request $request)
    {
        try {
            $notification = Notification::active()
                                      ->where('user_id', $request->user_id)
                                      ->find($request->notification_id);

            if (!$notification) {
                return $this->toJsonEnc([], 'Notification not found', Config::get('constant.NOT_FOUND'));
            }

            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

            return $this->toJsonEnc([], trans('api.notifications.marked_read'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function markAllRead(Request $request)
    {
        try {
            Notification::active()
                       ->where('user_id', $request->user_id)
                       ->where('is_read', false)
                       ->update([
                           'is_read' => true,
                           'read_at' => now(),
                       ]);

            return $this->toJsonEnc([], trans('api.notifications.all_marked_read'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function delete(Request $request)
    {
        try {
            $notification = Notification::active()
                                      ->where('user_id', $request->user_id)
                                      ->find($request->notification_id);

            if (!$notification) {
                return $this->toJsonEnc([], 'Notification not found', Config::get('constant.NOT_FOUND'));
            }

            $notification->update(['is_deleted' => true]);

            return $this->toJsonEnc([], trans('api.notifications.deleted_success'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}