<?php

namespace App\Filament\Resources\ConsumableTransactionResource\Pages;

use App\Filament\Resources\ConsumableTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumableTransactions extends ListRecords
{
    protected static string $resource = ConsumableTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
