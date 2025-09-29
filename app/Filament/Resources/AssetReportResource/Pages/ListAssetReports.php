<?php

namespace App\Filament\Resources\AssetReportResource\Pages;

use App\Filament\Resources\AssetReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAssetReports extends ListRecords
{
    protected static string $resource = AssetReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
