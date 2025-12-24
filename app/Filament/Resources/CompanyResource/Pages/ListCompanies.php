<?php

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CompanyImport;
use App\Exports\CompanyExport;
// use App\Filament\Imports\CompanyImporter;

class ListCompanies extends ListRecords
{
    protected static string $resource = CompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            // ================= IMPORT =================
            Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel')
                        ->required()
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ]),
                ])
                ->action(function (array $data) {
                    Excel::import(new CompanyImport, $data['file']);

                    Notification::make()
                        ->title('Import Company berhasil')
                        ->success()
                        ->send();
                }),

            // ================= EXPORT =================
            Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('warning')
                ->action(fn () =>
                    Excel::download(new CompanyExport, 'companies.xlsx')
                ),
            // Actions\ImportAction::make()
            // ->importer(CompanyImporter::class)
            // ->label('Import Company')
            // ->color('success')
            // ->icon('heroicon-o-arrow-up-tray'),
        ];
    }
}
