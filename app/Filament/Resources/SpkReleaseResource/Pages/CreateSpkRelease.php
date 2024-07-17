<?php

namespace App\Filament\Resources\SpkReleaseResource\Pages;

use App\Filament\Resources\SpkReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CreateSpkRelease extends CreateRecord
{
    protected static string $resource = SpkReleaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $spkReleased = static::getModel()::create([
            'release' => $data['release'],
            'planning_start_outsole' => $data['planning_start_outsole'],
            'planning_start_upper' => $data['planning_start_upper'],
            'planning_start_assembly' => $data['planning_start_assembly'],
        ]);

        foreach ($data['po_items'] as $value) {
            $spkReleased->spkReleasePoItems()->create([
                'po_item_id' => $value,
            ]);
        }

        return $spkReleased;
    }
}
