<?php

namespace App\Http\Controllers\InteractionFile;

use App\Http\Controllers\Controller;
use App\Http\Requests\InteractionFile\InteractionFileStoreRequest;
use App\Models\InteractionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InteractionFileController extends Controller
{
    public function index()
    {
        return response()->json(InteractionFile::paginate(10));
    }

    public function store(InteractionFileStoreRequest $request)
    {
        Log::info('Request Data:', $request->all());

        $files = $request->validated();
        $files = $request->file('files');
        $interactionFiles = [];

        foreach ($files as $file) {
            $path = $file->store('interactions/files', 'public');
            $interactionFile = InteractionFile::create([
                'interaction_id' => $request->interaction_id,
                'path' => $path,
                'name' => $file->getClientOriginalName(),
            ]);
            $interactionFiles[] = $interactionFile;
        }

        return response()->json($interactionFiles, 201);
    }

    // public function store(InteractionFileStoreRequest $request)
    // {
    //     Log::info('Request Data:', $request->all());
    //     $input = $request->validated();
    //     $interactionFiles = [];
    //     if ($request->hasfile('files')) {

    //         foreach ($input['files'] as $file) {

    //             if ($file->isValid()) {

    //                 $interactionFile = InteractionFile::create([
    //                     'path' => $file->store('interactions/files', 'public'),
    //                     'name' => $file->getClientOriginalName(),
    //                     'interaction_id' => $request['interaction_id']
    //                 ]);
    //                 $interactionFiles[] = $interactionFile;
    //             }
    //         }

    //         return response()->json($interactionFiles);
    //     }
    // }

    public function destroy(InteractionFile $interactionFile)
    {

        $file = $interactionFile->find($photo);

        if (!$photo) {
        }

        if (Storage::disk('public')->exists($photo->photo)) {
            Storage::disk('public')->delete($photo->photo);
        }
        $photo->delete();
    }
}
