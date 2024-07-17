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
        $data['inputs']['qty_total'] = array_sum($data['inputs']);

        $sizerun = Sizerun::create($data['inputs']);

        $started_work_time = now()->parse($data['production_date'] . ' ' . $data['started_work_time'] . ':00');
        $ended_work_time = now()->parse($data['production_date'] . ' ' . $data['ended_work_time'] . ':00');

        $prodAssembly = static::getModel()::create([
            'model_name' => $data['select_model'],
        ]);

        $prodAssembly->assemblySizeruns()->create([
            'sizerun_id' => $sizerun->id,
            'started_work_time' => $started_work_time,
            'ended_work_time' => $ended_work_time
        ]);

        return $prodAssembly;
    }
}
