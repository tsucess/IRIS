<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        $query = Project::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by street
        if ($request->has('street_id')) {
            $query->where('street_id', $request->street_id);
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

        // Include counts
        if ($request->has('with_counts')) {
            $query->withCount(['tasks']);
        }

        // Date range filter
        if ($request->filled('start_after')) {
            $query->where('start_date', '>=', $request->start_after);
        }
        if ($request->filled('end_before')) {
            $query->where('end_date', '<=', $request->end_before);
        }

        // Sorting: ?sort_by=title&sort_dir=asc
        $sortBy  = in_array($request->get('sort_by'), ['title', 'status', 'start_date', 'end_date', 'created_at'])
            ? $request->get('sort_by') : 'created_at';
        $sortDir = $request->get('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $perPage  = min((int) $request->get('per_page', 15), 100);
        $projects = $query->paginate($perPage);

        return ProjectResource::collection($projects)
            ->additional([
                'meta' => [
                    'sort_by'  => $sortBy,
                    'sort_dir' => $sortDir,
                ],
            ]);
    }

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
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
            $project = Project::create($validated);
            
            if (isset($validated['user_ids'])) {
                $project->users()->sync($validated['user_ids']);
            }

            \Log::info('Project created via API', [
                'project_id' => $project->id,
                'created_by' => auth()->id(),
            ]);

            return new ProjectResource($project->load('users', 'street'));
        } catch (\Exception $e) {
            \Log::error('API project creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to create project',
                'error' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $project->load(['users', 'street', 'tasks'])
                ->loadCount('tasks');
        
        return new ProjectResource($project);
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
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
            $project->update($validated);
            
            if (isset($validated['user_ids'])) {
                $project->users()->sync($validated['user_ids']);
            }

            \Log::info('Project updated via API', [
                'project_id' => $project->id,
                'updated_by' => auth()->id(),
            ]);

            return new ProjectResource($project->load('users', 'street'));
        } catch (\Exception $e) {
            \Log::error('API project update failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to update project',
                'error' => 'An error occurred',
            ], 500);
        }
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();

            \Log::info('Project deleted via API', [
                'project_id' => $project->id,
                'deleted_by' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Project deleted successfully',
            ]);
        } catch (\Exception $e) {
            \Log::error('API project deletion failed', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'message' => 'Failed to delete project',
                'error' => 'An error occurred',
            ], 500);
        }
    }
}

