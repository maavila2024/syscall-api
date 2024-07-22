<?php

namespace App\Http\Controllers\ExcelImport;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TasksImport;
use App\Http\Controllers\Controller;


class ExcelImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new TasksImport, $request->file('file'));

        return response()->json(['message' => 'Importação realizada com sucesso!']);
    }
}
