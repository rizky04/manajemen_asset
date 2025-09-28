<?php

namespace App\Filament\Resources\ConsumableTransactionResource\Pages;

use App\Filament\Resources\ConsumableTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumableTransaction extends EditRecord
{
    protected static string $resource = ConsumableTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
