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

    // public function store(InteractionFileStoreRequest $request)
    // {
    //     Log::info('Request Data:', $request->all());

    //     $files = $request->validated();
    //     $files = $request->file('files');
    //     $interactionFiles = [];

    //     foreach ($files as $file) {
    //         $path = $file->store('interactions/files', 'public');
    //         $interactionFile = InteractionFile::create([
    //             'interaction_id' => $request->interaction_id,
    //             'path' => $path,
    //             'name' => $file->getClientOriginalName(),
    //         ]);
    //         $interactionFiles[] = $interactionFile;
    //     }

    //     return response()->json($interactionFiles, 201);
    // }

    public function store(InteractionFileStoreRequest $request)
    {
        Log::info('Iniciando upload de arquivo para interaction:', [
            'request' => $request->all(),
            'disk_config' => [
                'driver' => config('filesystems.disks.s3.driver'),
                'bucket' => config('filesystems.disks.s3.bucket'),
                'region' => config('filesystems.disks.s3.region'),
                'url' => config('filesystems.disks.s3.url')
            ]
        ]);

        try {
            $files = $request->file('files');
            $interactionFiles = [];

            foreach ($files as $file) {
                Log::info('Processando arquivo:', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);

                // Armazena o arquivo no S3
                $path = $file->store('interactions/files', 's3');
                
                Log::info('Arquivo armazenado:', [
                    'path' => $path,
                    'url' => Storage::disk('s3')->url($path)
                ]);

                $interactionFile = InteractionFile::create([
                    'interaction_id' => $request->interaction_id,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                ]);

                // Adiciona a URL do arquivo antes de retornar
                $interactionFile->file_url = Storage::disk('s3')->url($path);
                $interactionFiles[] = $interactionFile;
            }

            return response()->json($interactionFiles, 201);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar arquivo:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erro ao salvar arquivo: ' . $e->getMessage()], 500);
        }
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
        // Verifica se o arquivo existe no S3
        if (Storage::disk('s3')->exists($interactionFile->path)) {
            // Exclui o arquivo do S3
            Storage::disk('s3')->delete($interactionFile->path);
        }

        // Exclui o registro do banco de dados
        $interactionFile->delete();

        return response()->json(['message' => 'File deleted successfully.'], 200);
    }


    // public function destroy(InteractionFile $interactionFile)
    // {

    //     $file = $interactionFile->find($photo);

    //     if (!$photo) {
    //     }

    //     if (Storage::disk('public')->exists($photo->photo)) {
    //         Storage::disk('public')->delete($photo->photo);
    //     }
    //     $photo->delete();
    // }
}
