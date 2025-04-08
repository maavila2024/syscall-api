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

    // public function store(InteractionFileStoreRequest $request)
    // {
    //     Log::info('Request Data:', $request->all());

    //     $files = $request->file('files');
    //     $interactionFiles = [];

    //     foreach ($files as $file) {
    //         // Envia o arquivo para o S3
    //         $path = Storage::disk('s3')->put('interactions/files', $file);

    //         // Cria o registro no banco de dados
    //         $interactionFile = InteractionFile::create([
    //             'interaction_id' => $request->interaction_id,
    //             'path' => $path, // Caminho retornado pelo S3
    //             'name' => $file->getClientOriginalName(),
    //         ]);

    //         $interactionFiles[] = $interactionFile;
    //     }

    //     return response()->json($interactionFiles, 201);
    // }


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

    public function store(InteractionFileStoreRequest $request)
    {
        // Log inicial para depuração
        Log::info('Request Data:', $request->all());

        $files = $request->file('files');
        $interactionFiles = [];

        // Verifica se os arquivos estão presentes
        if (!$files || empty($files)) {
            return response()->json(['error' => 'No files provided'], 400);
        }

        foreach ($files as $file) {
            try {
                // Verifica se o arquivo é válido
                if (!$file->isValid()) {
                    Log::warning('Invalid file upload attempt:', ['name' => $file->getClientOriginalName()]);
                    return response()->json(['error' => 'Invalid file upload'], 400);
                }

                // Log do nome do arquivo que está sendo enviado
                Log::info('Uploading file:', ['name' => $file->getClientOriginalName()]);

                // Envia o arquivo para o S3
                $path = Storage::disk('s3')->put('interactions/files', $file, 'public');

                // Verifica se o upload foi bem-sucedido
                if (!$path) {
                    Log::error('Failed to upload file to S3:', ['name' => $file->getClientOriginalName()]);
                    return response()->json(['error' => 'Failed to upload file'], 500);
                }

                Log::info('File uploaded to S3:', ['path' => $path]);

                // Cria o registro no banco de dados
                $interactionFile = InteractionFile::create([
                    'interaction_id' => $request->interaction_id,
                    'path' => $path, // Caminho retornado pelo S3
                    'name' => $file->getClientOriginalName(),
                ]);

                $interactionFiles[] = $interactionFile;
            } catch (\Exception $e) {
                // Log do erro
                Log::error('Error processing file upload:', [
                    'file_name' => $file->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ]);

                return response()->json([
                    'error' => 'An error occurred while uploading the file',
                    'details' => $e->getMessage(),
                ], 500);
            }
        }

        // Retorno com os arquivos processados
        return response()->json($interactionFiles, 201);
    }


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
