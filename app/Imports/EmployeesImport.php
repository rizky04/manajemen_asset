<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;

class EmployeesImport implements ToModel
{
    public function model(array $row)
    {
        return new Employee([
            'company_id'    => $row[0],
            'name'          => $row[1],
            'department_id' => $row[2],
            'position'      => $row[3],
            'email'         => $row[4],
            'phone'         => $row[5],
        ]);
    }
}
