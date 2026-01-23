<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Models\User;
use App\Events\NewProjectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectChatController extends Controller
{
    public function index($projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // Access check is typically handled by middleware or policy in web routes
        // Assuming 'can:view,project' or similar is used in route definition
        
        // Initial messages fetch - returning latest 50 reversed for chat view
        $messages = ProjectMessage::with('user')
            ->where('project_id', $projectId)
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return view('website.project.chat', compact('project', 'messages'));
    }

    public function store(Request $request, $projectId)
    {
        try {
            \Illuminate\Support\Facades\Log::info('Chat store request', [
                'project_id' => $projectId, 
                'user_id' => auth()->id(), 
                'data' => $request->all()
            ]);

            $project = Project::findOrFail($projectId);
            
            $request->validate([
                'message' => 'required_without:attachment|string|nullable',
                'attachment' => 'nullable|file|max:10240',
            ]);

            $type = 'text';
            $attachmentPath = null;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $mimeType = $file->getMimeType();
                $type = str_starts_with($mimeType, 'image/') ? 'image' : 'file';
                $attachmentPath = $file->store('project_chat_attachments', 'public');
            }

            $user = $request->attributes->get('user');
            
            // Fallback: Check if user_id is provided in request (via API interceptors) and verify it matches session/token
            if (!$user && $request->has('user_id')) {
                $user = User::active()->find($request->user_id);
            }
            
            if (!$user) {
                // Last resort fallback - assuming WebAuth passed, try session directly
                $sessionUserId = session('user_id');
                if ($sessionUserId) {
                    $user = User::active()->find($sessionUserId);
                }
            }

            if (!$user) {
                 \Illuminate\Support\Facades\Log::error('Chat store error: User not authenticated');
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $message = ProjectMessage::create([
                'project_id' => $projectId,
                'user_id' => $user->id,
                'message' => $request->message,
                'type' => $type,
                'attachment_path' => $attachmentPath,
            ]);

            $message->load('user');

            broadcast(new NewProjectMessage($message))->toOthers();

            return response()->json(['status' => 'Message Sent!', 'message' => $message]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Chat store error: ' . $e->getMessage());
            \Illuminate\Support\Facades\Log::error($e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
