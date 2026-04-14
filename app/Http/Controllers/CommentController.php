<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'body'             => 'required|string|max:2000',
            'commentable_type' => 'required|in:project,task',
            'commentable_id'   => 'required|integer',
        ]);

        $typeMap = [
            'project' => \App\Models\Project::class,
            'task'    => \App\Models\Task::class,
        ];

        $modelClass = $typeMap[$request->commentable_type];
        $model      = $modelClass::findOrFail($request->commentable_id);

        $model->comments()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);

        return back()->with('success', 'Comment posted.');
    }

    public function destroy(Comment $comment)
    {
        abort_unless(
            auth()->user()->isAdmin() || $comment->user_id === auth()->id(),
            403
        );

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }
}
