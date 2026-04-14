<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Models\User;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $query = Complaint::with(['user', 'assignedAdmin'])->latest();

        if (! auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        $complaints = $query->paginate(15);

        return view('complaints.index', compact('complaints'));
    }

    public function create()
    {
        return view('complaints.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|in:road,water,electricity,sanitation,security,noise,other',
            'priority'    => 'required|in:low,medium,high,urgent',
        ]);

        $validated['user_id'] = auth()->id();

        Complaint::create($validated);

        return redirect()->route('complaints.index')->with('success', 'Complaint submitted successfully.');
    }

    public function show(Complaint $complaint)
    {
        $this->authorizeAccess($complaint);
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        return view('complaints.show', compact('complaint', 'admins'));
    }

    public function edit(Complaint $complaint)
    {
        $this->authorizeAccess($complaint);
        abort_unless($complaint->isOpen(), 403, 'Only open complaints can be edited.');

        return view('complaints.edit', compact('complaint'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $this->authorizeAccess($complaint);

        if (auth()->user()->isAdmin()) {
            $validated = $request->validate([
                'status'      => 'required|in:open,in_review,resolved,rejected',
                'assigned_to' => 'nullable|exists:users,id',
                'admin_notes' => 'nullable|string',
            ]);

            if ($validated['status'] === 'resolved') {
                $validated['resolved_at'] = now();
            }

            $complaint->update($validated);

            return redirect()->route('complaints.show', $complaint)->with('success', 'Complaint updated.');
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|in:road,water,electricity,sanitation,security,noise,other',
            'priority'    => 'required|in:low,medium,high,urgent',
        ]);

        $complaint->update($validated);

        return redirect()->route('complaints.index')->with('success', 'Complaint updated.');
    }

    public function destroy(Complaint $complaint)
    {
        $this->authorizeAccess($complaint);
        $complaint->delete();

        return redirect()->route('complaints.index')->with('success', 'Complaint deleted.');
    }

    private function authorizeAccess(Complaint $complaint): void
    {
        if (! auth()->user()->isAdmin() && $complaint->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
