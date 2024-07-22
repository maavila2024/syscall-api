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
        'file' => 'required|file|mimes:xlsx,csv'
    ]);

    try {
        Excel::import(new TasksImport, $request->file('file')->store('temp'));
        return back()->with('success', 'Tasks imported successfully.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error importing tasks: ' . $e->getMessage());
    }
}

}
