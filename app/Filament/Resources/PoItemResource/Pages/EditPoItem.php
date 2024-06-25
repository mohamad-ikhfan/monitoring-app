<?php

namespace App\Filament\Resources\PoItemResource\Pages;

use App\Filament\Resources\PoItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPoItem extends EditRecord
{
    protected static string $resource = PoItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
