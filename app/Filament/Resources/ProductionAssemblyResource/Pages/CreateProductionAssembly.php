<?php

namespace App\Filament\Resources\ProductionAssemblyResource\Pages;

use App\Filament\Resources\ProductionAssemblyResource;
use App\Models\Sizerun;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProductionAssembly extends CreateRecord
{
    protected static string $resource = ProductionAssemblyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $data['input']['qty_total'] = array_sum($data['input']);

        $sizerun = Sizerun::create($data['input']);

        return static::getModel()::first()->assemblySizeruns()->create([
            'sizerun_id' => $sizerun->id
        ]);
    }
}