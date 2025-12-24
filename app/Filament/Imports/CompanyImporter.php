<?php

namespace App\Filament\Imports;

use App\Models\Company;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\Rule;

class CompanyImporter extends Importer
{
    protected static ?string $model = Company::class;

    public static function getColumns(): array
    {
        return [

            ImportColumn::make('code')
            ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('name')
                ->rules(['nullable', 'string', 'max:255']),

                ImportColumn::make('name_short')
                ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('address')
                ->rules(['nullable', 'string']),

                ImportColumn::make('npwp')
                ->rules(['nullable', 'string']),

            ImportColumn::make('phone')
                ->rules(['nullable', 'string', 'max:20']),

            ImportColumn::make('email')
                ->rules([
                    'nullable',
                    'email',
                    'max:255',
                ]),
        ];
    }

    public function resolveRecord(): ?Company
    {
        return Company::firstOrNew([
            // Update existing records, matching them by `$this->data['column_name']`
            'email' => $this->data['email'],
        ]);

        return new Company();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your company import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
