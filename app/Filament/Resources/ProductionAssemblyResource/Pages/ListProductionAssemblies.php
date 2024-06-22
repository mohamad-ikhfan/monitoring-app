<?php

namespace App\Filament\Resources\ProductionAssemblyResource\Pages;

use App\Filament\Resources\ProductionAssemblyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductionAssemblies extends ListRecords
{
    protected static string $resource = ProductionAssemblyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}