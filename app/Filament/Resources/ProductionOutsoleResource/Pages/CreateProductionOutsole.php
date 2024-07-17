<?php

namespace App\Filament\Resources\ProductionOutsoleResource\Pages;

use App\Filament\Resources\ProductionOutsoleResource;
use App\Models\Sizerun;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProductionOutsole extends CreateRecord
{
    protected static string $resource = ProductionOutsoleResource::class;

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

        $prodOutsole = static::getModel()::create([
            'spk_release_id' => $data['select_release'],
            'model_name' => $data['select_model'],
        ]);

        $prodOutsole->outsoleSizeruns()->create([
            'sizerun_id' => $sizerun->id,
            'started_work_time' => $started_work_time,
            'ended_work_time' => $ended_work_time
        ]);

        return $prodOutsole;
    }
}
