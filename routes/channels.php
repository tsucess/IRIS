<?php

use App\Models\Project;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Private channel: user can only listen to their own channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Private channel: only members of a project can listen to project events
Broadcast::channel('project.{projectId}', function ($user, $projectId) {
    $project = Project::find($projectId);
    if (! $project) {
        return false;
    }

    return $project->users->contains($user->id) || $user->isAdmin();
});
