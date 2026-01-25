<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProjectChat;
use App\Models\ProjectChatReadStatus;
use App\Models\TeamMember;
use App\Events\NewChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function sendMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'message' => 'nullable|string',
                'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,webm,ogg,mov,mp3,wav,m4a,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar|max:51200',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Check if user is team member or project creator
            $isMember = TeamMember::where('project_id', $request->project_id)
                ->where('user_id', $request->user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->exists();
            
            $isCreator = \App\Models\Project::where('id', $request->project_id)
                ->where('created_by', $request->user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->exists();

            if (!$isMember && !$isCreator) {
                return $this->toJsonEnc([], trans('api.chat.not_authorized'), Config::get('constant.ERROR'));
            }

            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $filename = time() . '_' . $file->getClientOriginalName();
                $attachmentPath = $file->storeAs('chat_attachments', $filename, 'public');
            }

            $chat = ProjectChat::create([
                'project_id' => $request->project_id,
                'user_id' => $request->user_id,
                'message' => $request->message ?? '',
                'attachment' => $attachmentPath,
            ]);

            $chat->load('user:id,name,profile_image');

            // Add attachment URL
            if ($chat->attachment) {
                $chat->attachment = asset('storage/' . $chat->attachment);
            }

            // Broadcast event
            broadcast(new NewChatMessage($chat));

            return $this->toJsonEnc($chat, trans('api.chat.message_sent'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getMessages(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
                'limit' => 'nullable|integer',
                'page' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            // Check if user is team member or project creator
            $isMember = TeamMember::where('project_id', $request->project_id)
                ->where('user_id', $request->user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->exists();
            
            $isCreator = \App\Models\Project::where('id', $request->project_id)
                ->where('created_by', $request->user_id)
                ->where('is_active', 1)
                ->where('is_deleted', 0)
                ->exists();

            if (!$isMember && !$isCreator) {
                return $this->toJsonEnc([], trans('api.chat.not_authorized'), Config::get('constant.ERROR'));
            }

            $limit = $request->input('limit', 50);
            $page = $request->input('page', 1);

            $messages = ProjectChat::where('project_id', $request->project_id)
                ->where('is_deleted', 0)
                ->with('user:id,name,profile_image')
                ->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            $messages->getCollection()->transform(function ($message) {
                if ($message->user && $message->user->profile_image) {
                    // Check if profile_image already contains full URL
                    if (strpos($message->user->profile_image, 'http') === 0) {
                        // Already a full URL, use as is
                        $message->user->profile_image = $message->user->profile_image;
                    } else {
                        // Relative path, add storage prefix
                        $message->user->profile_image = asset('storage/profile/' . $message->user->profile_image);
                    }
                }
                if ($message->attachment) {
                    $message->attachment = asset('storage/' . $message->attachment);
                }
                return $message;
            });

            return $this->toJsonEnc($messages, trans('api.chat.messages_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function markAsRead(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'chat_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            ProjectChatReadStatus::updateOrCreate(
                ['chat_id' => $request->chat_id, 'user_id' => $request->user_id],
                ['read_at' => now()]
            );

            return $this->toJsonEnc([], trans('api.chat.marked_as_read'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function deleteMessage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'chat_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $chat = ProjectChat::where('id', $request->chat_id)
                ->where('user_id', $request->user_id)
                ->first();

            if (!$chat) {
                return $this->toJsonEnc([], trans('api.chat.message_not_found'), Config::get('constant.NOT_FOUND'));
            }

            $chat->is_deleted = true;
            $chat->save();

            return $this->toJsonEnc([], trans('api.chat.message_deleted'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }

    public function getUnreadCount(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer',
                'project_id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return $this->validateResponse($validator->errors());
            }

            $unreadCount = ProjectChat::where('project_id', $request->project_id)
                ->where('user_id', '!=', $request->user_id)
                ->where('is_deleted', 0)
                ->whereDoesntHave('readStatus', function ($query) use ($request) {
                    $query->where('user_id', $request->user_id);
                })
                ->count();

            return $this->toJsonEnc(['unread_count' => $unreadCount], trans('api.chat.unread_count_retrieved'), Config::get('constant.SUCCESS'));
        } catch (\Exception $e) {
            return $this->toJsonEnc([], $e->getMessage(), Config::get('constant.ERROR'));
        }
    }
}
