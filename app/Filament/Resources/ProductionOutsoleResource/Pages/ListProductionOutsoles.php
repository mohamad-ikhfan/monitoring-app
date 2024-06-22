<?php

namespace App\Filament\Resources\ProductionOutsoleResource\Pages;

use App\Filament\Resources\ProductionOutsoleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionOutsoles extends ListRecords
{
    protected static string $resource = ProductionOutsoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
