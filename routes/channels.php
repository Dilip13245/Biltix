<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Project;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    return Project::where('id', $projectId)
        ->where(function ($query) use ($user) {
            $query->where('project_manager_id', $user->id)
                  ->orWhere('created_by', $user->id)
                  ->orWhereHas('teamMembers', function ($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })->exists();
});
