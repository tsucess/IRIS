<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ResourceAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResourceAllocationController extends Controller
{
    /**
     * Overview across all projects (admins + project managers).
     */
    public function overview(Request $request)
    {
        $user = auth()->user();

        $build = function () use ($user, $request) {
            $q = ResourceAllocation::query();

            if (! $user->isAdmin()) {
                $projectIds = $user->projects()->pluck('projects.id');
                $q->whereIn('project_id', $projectIds);
            }
            if ($request->filled('type')) {
                $q->where('resource_type', $request->type);
            }
            if ($request->filled('status')) {
                $q->where('status', $request->status);
            }

            return $q;
        };

        $allocations = $build()->with(['project', 'allocatedBy'])
            ->latest()->paginate(15)->withQueryString();

        $summary = [
            'total_allocated' => (float) $build()->sum('allocated_amount'),
            'total_used'      => (float) $build()->sum('used_amount'),
        ];
        $summary['total_remaining'] = max(0, $summary['total_allocated'] - $summary['total_used']);

        return view('allocations.overview', compact('allocations', 'summary'));
    }

    public function index(Project $project)
    {
        $this->authorizeProjectAccess($project);

        $allocations = $project->allocations()->with('allocatedBy')->latest()->paginate(15);

        return view('allocations.index', compact('project', 'allocations'));
    }

    public function create(Project $project)
    {
        $this->authorizeProjectManagement($project);

        return view('allocations.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $this->authorizeProjectManagement($project);

        $data = $this->validateAllocation($request);
        $data['project_id']   = $project->id;
        $data['allocated_by'] = auth()->id();

        try {
            ResourceAllocation::create($data);
            Log::info('Resource allocation created', [
                'project_id' => $project->id, 'created_by' => auth()->id(),
            ]);

            return redirect()->route('projects.allocations.index', $project)
                ->with('success', 'Allocation created.');
        } catch (\Exception $e) {
            Log::error('Resource allocation creation failed', [
                'project_id' => $project->id, 'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to create allocation.')->withInput();
        }
    }

    public function edit(Project $project, ResourceAllocation $allocation)
    {
        $this->authorizeProjectManagement($project);
        $this->ensureBelongsTo($project, $allocation);

        return view('allocations.edit', compact('project', 'allocation'));
    }

    public function update(Request $request, Project $project, ResourceAllocation $allocation)
    {
        $this->authorizeProjectManagement($project);
        $this->ensureBelongsTo($project, $allocation);

        $data = $this->validateAllocation($request);

        try {
            $allocation->update($data);
            Log::info('Resource allocation updated', [
                'allocation_id' => $allocation->id, 'updated_by' => auth()->id(),
            ]);

            return redirect()->route('projects.allocations.index', $project)
                ->with('success', 'Allocation updated.');
        } catch (\Exception $e) {
            Log::error('Resource allocation update failed', [
                'allocation_id' => $allocation->id, 'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to update allocation.')->withInput();
        }
    }

    public function destroy(Project $project, ResourceAllocation $allocation)
    {
        $this->authorizeProjectManagement($project);
        $this->ensureBelongsTo($project, $allocation);

        $allocation->delete();
        Log::info('Resource allocation deleted', [
            'allocation_id' => $allocation->id, 'deleted_by' => auth()->id(),
        ]);

        return redirect()->route('projects.allocations.index', $project)
            ->with('success', 'Allocation deleted.');
    }

    private function validateAllocation(Request $request): array
    {
        return $request->validate([
            'resource_type'    => 'required|in:funds,materials,manpower,equipment,other',
            'name'             => 'required|string|max:255',
            'unit'             => 'nullable|string|max:50',
            'allocated_amount' => 'required|numeric|min:0',
            'used_amount'      => 'nullable|numeric|min:0',
            'status'           => 'required|in:planned,approved,in_use,depleted,cancelled',
            'allocated_at'     => 'nullable|date',
            'notes'            => 'nullable|string|max:1000',
        ]);
    }

    private function authorizeProjectAccess(Project $project): void
    {
        $user = auth()->user();
        if ($user->isAdmin()) return;
        if ($project->users()->where('users.id', $user->id)->exists()) return;
        abort(403, 'You do not have access to this project.');
    }

    private function authorizeProjectManagement(Project $project): void
    {
        $user = auth()->user();
        if ($user->isAdmin()) return;
        if ($user->role === 'project_manager'
            && $project->users()->where('users.id', $user->id)->exists()) return;
        abort(403, 'Only project managers (members) or admins may manage allocations.');
    }

    private function ensureBelongsTo(Project $project, ResourceAllocation $allocation): void
    {
        abort_if($allocation->project_id !== $project->id, 404);
    }
}
