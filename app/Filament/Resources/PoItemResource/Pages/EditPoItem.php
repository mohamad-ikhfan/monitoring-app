<?php

namespace App\Filament\Resources\PoItemResource\Pages;

use App\Filament\Resources\PoItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPoItem extends EditRecord
{
    protected static string $resource = PoItemResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['sizerun'] = $this->getModel()::first()->sizerun->toArray();
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->first()->sizerun()->update($data['sizerun']);
        unset($data['sizerun']);
        $record->update($data);
        return $record;
    }
}