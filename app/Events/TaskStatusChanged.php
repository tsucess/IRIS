<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('project.'.$this->task->project_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'task.status.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'task_id'   => $this->task->id,
            'title'     => $this->task->title,
            'status'    => $this->task->status,
            'priority'  => $this->task->priority,
            'project_id'=> $this->task->project_id,
        ];
    }
}
