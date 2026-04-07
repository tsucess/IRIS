<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Street;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['street', 'users'])->latest()->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        // Only load id and name for dropdowns (performance optimization)
        $streets = Street::select('id', 'name')->orderBy('name')->get();
        $users = User::select('id', 'firstname', 'lastname', 'email')
            ->orderBy('firstname')
            ->limit(100) // Limit to prevent loading thousands of users
            ->get();

        return view('projects.create', compact('streets', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string',
            'street_id' => 'nullable|exists:streets,id',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $project = Project::create($data);
            $project->users()->sync($data['user_ids'] ?? []);

            Log::info('Project created successfully', [
                'project_id' => $project->id,
                'title'      => $project->title,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('projects.index')->with('success', 'Project created.');
        } catch (\Exception $e) {
            Log::error('Project creation failed', [
                'error'   => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create project. Please try again.')
                ->withInput();
        }
    }

    public function edit(Project $project)
    {
        // Load project with relationships to avoid N+1
        $project->load(['users', 'street']);

        // Only load necessary fields for dropdowns
        $streets = Street::select('id', 'name')->orderBy('name')->get();
        $users = User::select('id', 'firstname', 'lastname', 'email')
            ->orderBy('firstname')
            ->limit(100)
            ->get();

        return view('projects.edit', compact('project', 'streets', 'users'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string',
            'street_id' => 'nullable|exists:streets,id',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            $project->update($data);
            $project->users()->sync($data['user_ids'] ?? []);

            Log::info('Project updated successfully', [
                'project_id' => $project->id,
                'title'      => $project->title,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('projects.index')->with('success', 'Project updated.');
        } catch (\Exception $e) {
            Log::error('Project update failed', [
                'project_id' => $project->id,
                'error'      => $e->getMessage(),
                'user_id'    => auth()->id(),
                'trace'      => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update project. Please try again.')
                ->withInput();
        }
    }

    public function show(Project $project)
    {
        $tasks = $project->tasks()->with('assignees')->latest()->get();
        $users = User::select('id', 'firstname', 'lastname', 'email')
            ->orderBy('firstname')
            ->limit(100)
            ->get();

        return view('projects.show', compact('project', 'tasks', 'users'));
    }

    public function addTask(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|string',
            'due_date' => 'nullable|date',
            'assigned_to' => 'array',
        ]);

        $task = $project->tasks()->create($data);

        if (! empty($data['assigned_to'])) {
            $task->assignees()->sync($data['assigned_to']);
        }

        return response()->json([
            'success' => true,
            'task' => $task->load('assignees'),
        ]);
    }

    public function updateTask(Request $request, Project $project, Task $task)
    {
        $request->headers->set('Accept', 'application/json');

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|string',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'array',
        ]);

        $task->update($data);
        $task->assignees()->sync($data['assigned_to'] ?? []);

        return response()->json([
            'success' => true,
            'task'    => $task->load('assignees'),
        ]);
    }

    public function deleteTask(Project $project, Task $task)
    {
        $task->delete();

        return response()->json(['success' => true]);
    }

    public function getTask(Project $project, Task $task)
    {
        return response()->json([
            'task' => $task,
            'assignees' => $task->assignees->pluck('id'),
        ]);
    }

    // Assign multiple users to project
    public function assignUsers(Request $request, Project $project)
    {
        $validated = $request->validate([
            'users' => 'required|array',
            'users.*' => 'exists:users,id',
        ]);

        $project->users()->attach($validated['users']);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Users assigned successfully.');
    }

    // Remove a user from project
    public function removeUser(Project $project, User $user)
    {
        $project->users()->detach($user->id);

        return redirect()->route('projects.show', $project)
            ->with('success', 'User removed successfully.');
    }

    public function destroy(Project $project)
    {
        try {
            $projectTitle = $project->title;
            $project->delete();

            Log::info('Project deleted successfully', [
                'project_title' => $projectTitle,
                'deleted_by'    => auth()->id(),
            ]);

            return redirect()->route('projects.index')->with('success', 'Project deleted.');
        } catch (\Exception $e) {
            Log::error('Project deletion failed', [
                'project_id' => $project->id,
                'error'      => $e->getMessage(),
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete project. It may have associated tasks.');
        }
    }
}
