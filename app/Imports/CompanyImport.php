<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation
};

class CompanyImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return Company::updateOrCreate(
            ['code' => $row['code']], // unique key
            [
                'name'        => $row['name'],
                'name_short'  => $row['name_short'],
                'address'     => $row['address'],
                'npwp'        => $row['npwp'],
                'phone'       => $row['phone'],
                'email'       => $row['email'],
            ]
        );
    }

    public function rules(): array
    {
        return [
            'code'       => 'required',
            'name'       => 'required',
            'email'      => 'nullable|email',
        ];
    }
}
