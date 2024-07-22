<?php

namespace App\Filament\Resources\TargetPerModelResource\Pages;

use App\Filament\Resources\TargetPerModelResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTargetPerModels extends ManageRecords
{
    protected static string $resource = TargetPerModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
