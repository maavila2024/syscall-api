<?php

namespace App\Http\Controllers\InteractionFile;

use App\Http\Controllers\Controller;
use App\Http\Requests\InteractionFile\InteractionFileStoreRequest;
use App\Models\InteractionFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InteractionFileController extends Controller
{
    public function index()
    {
        return response()->json(InteractionFile::paginate(10));
    }

    public function store(InteractionFileStoreRequest $request)
    {
        $input = $request->validated();

        if ($request->hasfile('files')) {

            foreach ($input['files'] as $file) {

                if ($file->isValid()) {

                    $interactionFile = InteractionFile::create([
                        'path' => $file->store('interactions/files', 'public'),
                        'name' => $file->getClientOriginalName(),
                        'interaction_id' => $request['interaction_id']
                    ]);
                }
            }

            return response()->json($interactionFile);
        }
    }

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
