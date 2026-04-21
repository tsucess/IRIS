<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Admin management listing — all announcements including expired, with stats.
     */
    public function manage()
    {
        $all          = Announcement::with('author')->latest()->paginate(20);
        $totalCount   = Announcement::count();
        $activeCount  = Announcement::active()->count();
        $pinnedCount  = Announcement::where('pinned', true)->count();
        $expiredCount = Announcement::whereNotNull('expires_at')
                            ->where('expires_at', '<', now())->count();

        return view('announcements.manage', compact(
            'all', 'totalCount', 'activeCount', 'pinnedCount', 'expiredCount'
        ));
    }

    /**
     * Public listing — accessible to guests and authenticated users.
     */
    public function index()
    {
        $user = auth()->user(); // may be null for guests

        $announcements = Announcement::with('author')
            ->active()
            ->visibleTo($user)
            ->orderByDesc('pinned')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Public detail view — accessible to guests and authenticated users.
     * Guests may only view announcements whose audience is 'all'.
     */
    public function show(Announcement $announcement)
    {
        abort_unless($announcement->isActive(), 404, 'This announcement has expired or does not exist.');

        $user = auth()->user();

        // Guests can only view announcements addressed to everyone
        if ($user === null && $announcement->audience !== 'all') {
            abort(404);
        }

        // Logged-in non-admins cannot view admin-only announcements
        if ($user !== null && $announcement->audience === 'admins' && ! $user->isAdmin()) {
            abort(403);
        }

        return view('announcements.show', compact('announcement'));
    }

    // ─── Admin-only actions ───────────────────────────────────────────────

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'audience'   => 'required|in:all,admins,residents',
            'pinned'     => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['pinned']  = $request->boolean('pinned');

        Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement posted successfully.');
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'audience'   => 'required|in:all,admins,residents',
            'pinned'     => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $validated['pinned'] = $request->boolean('pinned');

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted.');
    }

    /**
     * Toggle the pinned state of an announcement.
     */
    public function togglePin(Announcement $announcement)
    {
        $announcement->update(['pinned' => ! $announcement->pinned]);

        $label = $announcement->fresh()->pinned ? 'pinned' : 'unpinned';

        return back()->with('success', "Announcement {$label} successfully.");
    }
}
