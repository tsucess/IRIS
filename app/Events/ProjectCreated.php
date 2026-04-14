<?php

namespace App\Events;

use App\Models\Project;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Project $project) {}

    public function broadcastOn(): array
    {
        return [new Channel('projects')];
    }

    public function broadcastAs(): string
    {
        return 'project.created';
    }

    public function broadcastWith(): array
    {
        return [
            'project_id'  => $this->project->id,
            'title'        => $this->project->title,
            'status'       => $this->project->status,
            'street_id'    => $this->project->street_id,
            'created_at'   => $this->project->created_at->toDateTimeString(),
        ];
    }
}
