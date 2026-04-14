<?php

use App\Models\Task;
use App\Notifications\DeadlineReminder;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Send deadline reminders for tasks due in the next 2 days.
 * Runs daily via the scheduler.
 */
Artisan::command('iris:send-deadline-reminders', function () {
    $tasks = Task::with('assignee')
        ->whereNotNull('assigned_to')
        ->whereNotNull('due_date')
        ->where('due_date', '>=', now()->toDateString())
        ->where('due_date', '<=', now()->addDays(2)->toDateString())
        ->where('status', '!=', 'Completed')
        ->get();

    foreach ($tasks as $task) {
        $task->assignee?->notify(new DeadlineReminder($task));
    }

    $this->info("Deadline reminders sent for {$tasks->count()} task(s).");
})->purpose('Send deadline reminder notifications for upcoming task due dates');

// Register the daily schedule
Schedule::command('iris:send-deadline-reminders')->dailyAt('08:00');
Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();
