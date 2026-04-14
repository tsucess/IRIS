<?php

namespace App\Http\Controllers;

use App\Imports\ResidentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ResidentImportController extends Controller
{
    public function showForm()
    {
        return view('imports.residents');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new ResidentsImport();

        Excel::import($import, $request->file('file'));

        $message = "Import complete: {$import->imported} residents imported, {$import->skipped} skipped.";

        if (count($import->errors) > 0) {
            $message .= ' Errors: '.implode('; ', array_slice($import->errors, 0, 5));
        }

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
