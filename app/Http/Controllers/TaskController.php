<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $tasks = $project->tasks()->with('assignee')->latest()->paginate(10);

        return view('tasks.index', compact('project', 'tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        $users = User::all();

        return view('tasks.create', compact('project', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $task = $project->tasks()->create($validated);

            Log::info('Task created successfully', [
                'task_id'    => $task->id,
                'project_id' => $project->id,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('tasks.index', $project)->with('success', 'Task created.');
        } catch (\Exception $e) {
            Log::error('Task creation failed', [
                'project_id' => $project->id,
                'error'      => $e->getMessage(),
                'user_id'    => auth()->id(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create task. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project, Task $task)
    {
        // Only load necessary fields for user dropdown
        $users = User::select('id', 'firstname', 'lastname')
            ->orderBy('firstname')
            ->limit(100)
            ->get();

        return view('tasks.edit', compact('project', 'task', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $task->update($validated);

            Log::info('Task updated successfully', [
                'task_id'    => $task->id,
                'project_id' => $project->id,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('tasks.index', $project)->with('success', 'Task updated.');
        } catch (\Exception $e) {
            Log::error('Task update failed', [
                'task_id'    => $task->id,
                'project_id' => $project->id,
                'error'      => $e->getMessage(),
                'user_id'    => auth()->id(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update task. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, Task $task)
    {
        try {
            $taskTitle = $task->title;
            $task->delete();

            Log::info('Task deleted successfully', [
                'task_title' => $taskTitle,
                'project_id' => $project->id,
                'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('tasks.index', $project)->with('success', 'Task deleted.');
        } catch (\Exception $e) {
            Log::error('Task deletion failed', [
                'task_id'    => $task->id,
                'project_id' => $project->id,
                'error'      => $e->getMessage(),
                'user_id'    => auth()->id(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete task. Please try again.');
        }
    }
}
