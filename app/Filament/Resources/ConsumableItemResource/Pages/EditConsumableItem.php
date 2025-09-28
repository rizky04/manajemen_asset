<?php

namespace App\Filament\Resources\ConsumableItemResource\Pages;

use App\Filament\Resources\ConsumableItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumableItem extends EditRecord
{
    protected static string $resource = ConsumableItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
