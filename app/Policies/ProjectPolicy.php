<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Admins can do everything.
     */
    public function before(User $user): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /** Any authenticated user can view the list. */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /** Only project members can view a project. */
    public function view(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }

    /** Any verified user can create a project. */
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    /** Only project members can edit a project. */
    public function update(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }

    /** Only project members can delete a project. */
    public function delete(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }

    /** Assign users — project member only. */
    public function assignUsers(User $user, Project $project): bool
    {
        return $project->users->contains($user->id);
    }
}
