<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DeadlineReminder extends Notification implements ShouldQueue
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
            'type'       => 'deadline_reminder',
            'title'      => '⏰ Task Deadline Approaching',
            'message'    => "Task \"{$this->task->title}\" is due on {$this->task->due_date->format('M d, Y')}.",
            'task_id'    => $this->task->id,
            'project_id' => $this->task->project_id,
            'due_date'   => $this->task->due_date->toDateString(),
            'url'        => route('tasks.edit', [$this->task->project_id, $this->task->id]),
        ];
    }
}
