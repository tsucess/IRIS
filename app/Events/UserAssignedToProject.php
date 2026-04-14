<?php

namespace App\Events;

use App\Models\Project;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserAssignedToProject implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Project $project,
        public User $user
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.'.$this->user->id)];
    }

    public function broadcastAs(): string
    {
        return 'project.assigned';
    }

    public function broadcastWith(): array
    {
        return [
            'project_id'   => $this->project->id,
            'project_title' => $this->project->title,
            'user_id'      => $this->user->id,
            'user_name'    => $this->user->full_name,
        ];
    }
}
