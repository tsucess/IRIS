<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ProjectStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Project $project,
        public string $oldStatus
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'project_status_changed',
            'title'      => 'Project Status Updated',
            'message'    => "Project \"{$this->project->title}\" changed from {$this->oldStatus} to {$this->project->status}.",
            'project_id' => $this->project->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->project->status,
            'url'        => route('projects.show', $this->project->id),
        ];
    }
}
