<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMessage;
use App\Events\NewProjectMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProjectChatController extends Controller
{
    /**
     * Get list of messages for a project
     */
    public function index(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        
        // Authorization check (can be middleware, but simple check here)
        $user = $request->user();
        if (!$this->canAccessProject($user, $project)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = ProjectMessage::with('user:id,name,profile_image')
            ->where('project_id', $projectId)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Store a new message
     */
    public function store(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);
        $user = $request->user();

        if (!$this->canAccessProject($user, $project)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'message' => 'required_without:attachment|string|nullable',
            'attachment' => 'nullable|file|max:10240', // 10MB max
            'type' => 'required|in:text,image,file',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'project_id' => $projectId,
            'user_id' => $user->id,
            'message' => $request->message,
            'type' => $request->type,
        ];

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('project_chat_attachments', 'public');
            $data['attachment_path'] = $path;
        }

        $message = ProjectMessage::create($data);
        
        // Load user relationship for broadcasting
        $message->load('user:id,name,profile_image');

        // Broadcast event
        broadcast(new NewProjectMessage($message))->toOthers();

        return response()->json($message, 201);
    }

    private function canAccessProject($user, $project)
    {
        return $user->id === $project->project_manager_id 
            || $user->id === $project->created_by
            || $project->teamMembers()->where('user_id', $user->id)->exists();
    }
}
