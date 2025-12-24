<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use Filament\Actions\Action;
use Filament\Forms;

// use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;


class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
            ->label('Import Employees')
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                Forms\Components\FileUpload::make('file')
                    ->label('Excel File')
                    ->required()
                    ->acceptedFileTypes(['application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']),
            ])
            ->action(function (array $data) {
                $filePath = storage_path('app/public/' . $data['file']);

                if (! file_exists($filePath)) {
                    throw new \Exception("File tidak ditemukan: $filePath");
                }

                Excel::import(new EmployeesImport, $filePath);
                Notification::make()
                ->title('Import Berhasil!')
                ->success()
                ->send();
            }),

        Actions\Action::make('export')
            ->label('Export Employees')
            ->icon('heroicon-o-arrow-down-tray')
            ->action(function () {
                return Excel::download(new EmployeesExport, 'employees.xlsx');
            }),
            Actions\CreateAction::make(),
            Actions\Action::make('import_preview')
    ->label('Import Employee (Preview)')
    ->icon('heroicon-o-arrow-up-tray')
    ->form([
        Forms\Components\FileUpload::make('file')
            ->required()
            ->acceptedFileTypes([
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]),
    ])
    ->action(function (array $data) {

        // Kirim file ke backend untuk preview
        $preview = Http::attach(
            'file',
            file_get_contents($data['file']),
            'import.xlsx'
        )->post(route('employee.import.preview'));

        $rows = $preview->json()['data'];

        // Tampilkan modal preview dalam tabel HTML
        $table = view('import-preview.table', ['rows' => $rows])->render();

        Notification::make()
            ->title('Preview Import')
            ->body($table)
            ->success()
            ->send();
    }),

        ];
    }
}
