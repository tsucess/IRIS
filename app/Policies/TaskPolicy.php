<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Admins bypass all checks.
     */
    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /** View tasks — must be a project member. */
    public function viewAny(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }

    /** View a specific task — must be a project member. */
    public function view(User $user, Task $task): bool
    {
        return $task->project->users->contains($user->id);
    }

    /** Create task — must be a project member. */
    public function create(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }

    /** Update task — assignee OR project member. */
    public function update(User $user, Task $task): bool
    {
        return $task->assigned_to === $user->id
            || $task->project->users->contains($user->id);
    }

    /** Delete task — project member only. */
    public function delete(User $user, Task $task): bool
    {
        return $task->project->users->contains($user->id);
    }
}
