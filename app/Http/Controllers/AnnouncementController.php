<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $announcements = Announcement::with('author')
            ->active()
            ->visibleTo($user)
            ->orderByDesc('pinned')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

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

        return redirect()->route('announcements.index')->with('success', 'Announcement posted.');
    }

    public function show(Announcement $announcement)
    {
        abort_unless($announcement->isActive(), 404, 'This announcement has expired.');

        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'body'       => 'required|string',
            'audience'   => 'required|in:all,admins,residents',
            'pinned'     => 'boolean',
            'expires_at' => 'nullable|date',
        ]);

        $validated['pinned'] = $request->boolean('pinned');

        $announcement->update($validated);

        return redirect()->route('announcements.index')->with('success', 'Announcement updated.');
    }

    public function destroy(Announcement $announcement)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $announcement->delete();

        return redirect()->route('announcements.index')->with('success', 'Announcement deleted.');
    }
}
