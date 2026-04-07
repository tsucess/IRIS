<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks for a project
     */
    public function index(Request $request, Project $project)
    {
        $query = $project->tasks();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by assigned user
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Include relationships
        if ($request->has('with')) {
            $with = explode(',', $request->with);
            $query->with($with);
        }

        $perPage = $request->get('per_page', 15);
        $tasks = $query->paginate($perPage);

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created task
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

            \Log::info('Task created via API', [
                'task_id' => $task->id,
                'project_id' => $project->id,
                'created_by' => auth()->id(),
            ]);

            return new TaskResource($task->load('project', 'assignee'));
        } catch (\Exception $e) {
            \Log::error('API task creation failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to create task',
                'error' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Display the specified task
     */
    public function show(Project $project, Task $task)
    {
        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            return response()->json([
                'message' => 'Task not found in this project',
            ], 404);
        }

        $task->load(['project', 'assignee']);
        
        return new TaskResource($task);
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, Project $project, Task $task)
    {
        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            return response()->json([
                'message' => 'Task not found in this project',
            ], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $task->update($validated);

            \Log::info('Task updated via API', [
                'task_id' => $task->id,
                'project_id' => $project->id,
                'updated_by' => auth()->id(),
            ]);

            return new TaskResource($task->load('project', 'assignee'));
        } catch (\Exception $e) {
            \Log::error('API task update failed', [
                'task_id' => $task->id,
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to update task',
                'error' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Remove the specified task
     */
    public function destroy(Project $project, Task $task)
    {
        // Ensure task belongs to project
        if ($task->project_id !== $project->id) {
            return response()->json([
                'message' => 'Task not found in this project',
            ], 404);
        }

        try {
            $task->delete();

            \Log::info('Task deleted via API', [
                'task_id' => $task->id,
                'project_id' => $project->id,
                'deleted_by' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Task deleted successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('API task deletion failed', [
                'task_id' => $task->id,
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to delete task',
                'error' => 'An error occurred',
            ], 500);
        }
    }
}

