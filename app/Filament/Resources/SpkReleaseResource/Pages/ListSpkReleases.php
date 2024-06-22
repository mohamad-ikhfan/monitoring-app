<?php

namespace App\Filament\Resources\SpkReleaseResource\Pages;

use App\Filament\Resources\SpkReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpkReleases extends ListRecords
{
    protected static string $resource = SpkReleaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
