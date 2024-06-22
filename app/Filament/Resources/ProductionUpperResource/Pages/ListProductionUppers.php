<?php

namespace App\Filament\Resources\ProductionUpperResource\Pages;

use App\Filament\Resources\ProductionUpperResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionUppers extends ListRecords
{
    protected static string $resource = ProductionUpperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}