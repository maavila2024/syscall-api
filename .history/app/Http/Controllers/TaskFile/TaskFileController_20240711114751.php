<?php

namespace App\Http\Controllers\TaskFile;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskFile\TaskFileStoreRequest;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TaskFileController extends Controller
{
    public function index()
    {
        return response()->json(TaskFile::paginate(10));
    }

    public function store(TaskFileStoreRequest $request)
    {
        Log::info('Request Data:', $request->all());

        $files = $request->validated();
        $files = $request->file('files');
        $taskFiles = [];

        foreach ($files as $file) {
            $path = $file->store('tasks/files', 'public');
            $taskFile = TaskFile::create([
                'task_id' => $request->task_id,
                'path' => $path,
                'name' => $file->getClientOriginalName(),
            ]);
            $taskFiles[] = $taskFile;
        }

        return response()->json($taskFiles, 201);
    }

    // public function store(TaskFileStoreRequest $request)
    // {
    //     Log::info('Request Data:', $request->all());
    //     $input = $request->validated();
    //     $taskFiles = [];
    //     if ($request->hasfile('files')) {

    //         foreach ($input['files'] as $file) {

    //             if ($file->isValid()) {

    //                 $taskFile = TaskFile::create([
    //                     'path' => $file->store('tasks/files', 'public'),
    //                     'name' => $file->getClientOriginalName(),
    //                     'task_id' => $request['task_id']
    //                 ]);
    //                 $taskFiles[] = $taskFile;
    //             }
    //         }

    //         return response()->json($taskFiles);
    //     }
    // }

    public function destroy(TaskFile $taskFile)
    {

        $file = $taskFile->find($photo);

        if (!$photo) {
        }

        if (Storage::disk('public')->exists($photo->photo)) {
            Storage::disk('public')->delete($photo->photo);
        }
        $photo->delete();
    }
}
