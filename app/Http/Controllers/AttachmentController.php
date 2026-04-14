<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file'            => 'required|file|max:10240', // 10 MB max
            'attachable_type' => 'required|string|in:project,task',
            'attachable_id'   => 'required|integer',
        ]);

        $typeMap = [
            'project' => \App\Models\Project::class,
            'task'    => \App\Models\Task::class,
        ];

        $modelClass = $typeMap[$request->attachable_type];
        $model      = $modelClass::findOrFail($request->attachable_id);

        $file  = $request->file('file');
        $path  = $file->store('attachments/'.$request->attachable_type.'/'.$model->id, 'local');

        $model->attachments()->create([
            'user_id'   => auth()->id(),
            'filename'  => $file->getClientOriginalName(),
            'disk'      => 'local',
            'path'      => $path,
            'mime_type' => $file->getMimeType(),
            'size'      => $file->getSize(),
        ]);

        return back()->with('success', 'File attached successfully.');
    }

    public function download(Attachment $attachment)
    {
        abort_unless(
            auth()->user()->isAdmin() || $attachment->user_id === auth()->id(),
            403
        );

        return Storage::disk($attachment->disk)
            ->download($attachment->path, $attachment->filename);
    }

    public function destroy(Attachment $attachment)
    {
        abort_unless(
            auth()->user()->isAdmin() || $attachment->user_id === auth()->id(),
            403
        );

        $attachment->deleteFile();
        $attachment->delete();

        return back()->with('success', 'Attachment deleted.');
    }
}
