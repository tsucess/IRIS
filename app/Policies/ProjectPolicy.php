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

    /** Only project managers (and admins via before) can create a project. */
    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail() && $user->role === 'project_manager';
    }

    /** Only project managers who are project members can edit a project. */
    public function update(User $user, Project $project): bool
    {
        return $user->role === 'project_manager'
            && $project->users->contains($user->id);
    }

    /** Only project managers who are project members can delete a project. */
    public function delete(User $user, Project $project): bool
    {
        return $user->role === 'project_manager'
            && $project->users->contains($user->id);
    }

    /** Only project managers who are project members can assign users. */
    public function assignUsers(User $user, Project $project): bool
    {
        return $user->role === 'project_manager'
            && $project->users->contains($user->id);
    }
}
