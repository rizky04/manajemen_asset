<?php

namespace App\Exports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithMapping
};

class CompanyExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Company::all();
    }

    public function headings(): array
    {
        return [
            'Code',
            'Name',
            'Short Name',
            'Address',
            'NPWP',
            'Phone',
            'Email',
        ];
    }

    public function map($company): array
    {
        return [
            $company->code,
            $company->name,
            $company->name_short,
            $company->address,
            $company->npwp,
            $company->phone,
            $company->email,
        ];
    }
}
