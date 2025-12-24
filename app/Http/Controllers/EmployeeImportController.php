<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmployeesImport;
use App\Models\Employee;

class EmployeeImportController extends Controller
{
    public function preview(Request $request)
    {
        $file = $request->file('file');

        $rows = Excel::toArray(new EmployeesImport, $file)[0];

        // Simpan sementara ke session
        session(['employee_import_preview' => $rows]);

        return response()->json([
            'data' => $rows,
        ]);
    }

    public function confirm()
    {
        $rows = session('employee_import_preview', []);

        foreach ($rows as $row) {
            Employee::create([
                'company_id'    => $row[0],
                'name'          => $row[1],
                'department_id' => $row[2],
                'position'      => $row[3],
                'email'         => $row[4],
                'phone'         => $row[5],
            ]);
        }

        // Hapus preview
        session()->forget('employee_import_preview');

        return response()->json([
            'message' => 'Import berhasil disimpan ke database.'
        ]);
    }
}
