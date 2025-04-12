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
        Log::info('Iniciando upload de arquivo:', [
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
            $taskFiles = [];

            foreach ($files as $file) {
                Log::info('Processando arquivo:', [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize()
                ]);

                $path = $file->store('tasks/files', 's3');
                
                Log::info('Arquivo armazenado:', [
                    'path' => $path,
                    'url' => Storage::disk('s3')->url($path)
                ]);

                $taskFile = TaskFile::create([
                    'task_id' => $request->task_id,
                    'path' => $path,
                    'name' => $file->getClientOriginalName(),
                ]);

                // Adiciona a URL do arquivo antes de retornar
                $taskFile->file_url = Storage::disk('s3')->url($path);
                $taskFiles[] = $taskFile;
            }

            return response()->json(['data' => $taskFiles], 201);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar arquivo:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Erro ao salvar arquivo: ' . $e->getMessage()], 500);
        }
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

    public function destroy($id)
    {
        $file = TaskFile::findOrFail($id);

        // Apaga o arquivo do storage
        Storage::delete($file->path);

        // Apaga o registro do banco de dados
        $file->delete();

        return response()->json(['message' => 'File deleted successfully.']);
    }

    public function show($taskId)
    {
        try {
            $taskFiles = TaskFile::where('task_id', $taskId)->get();
            
            foreach ($taskFiles as $file) {
                $file->file_url = Storage::disk('s3')->url($file->path);
                Log::info('URL do arquivo TaskFile:', [
                    'path' => $file->path,
                    'url' => $file->file_url
                ]);
            }
            
            return response()->json(['data' => $taskFiles]);
        } catch (\Exception $e) {
            Log::error('Erro ao buscar arquivos da task:', [
                'error' => $e->getMessage(),
                'taskId' => $taskId
            ]);
            return response()->json(['error' => 'Erro ao buscar arquivos'], 500);
        }
    }
}
