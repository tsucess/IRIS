<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Task $task) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'task_assigned',
            'title'       => 'New Task Assigned',
            'message'     => "You have been assigned to task: \"{$this->task->title}\"",
            'task_id'     => $this->task->id,
            'project_id'  => $this->task->project_id,
            'url'         => route('tasks.edit', [$this->task->project_id, $this->task->id]),
        ];
    }
}
