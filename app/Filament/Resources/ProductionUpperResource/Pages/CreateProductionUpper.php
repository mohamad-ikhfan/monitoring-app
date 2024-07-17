<?php

namespace App\Filament\Resources\ProductionUpperResource\Pages;

use App\Filament\Resources\ProductionUpperResource;
use App\Models\Sizerun;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProductionUpper extends CreateRecord
{
    protected static string $resource = ProductionUpperResource::class;

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

        $prodUpper = static::getModel()::create([
            'spk_release_id' => $data['select_release'],
            'model_name' => $data['select_model'],
        ]);

        $prodUpper->upperSizeruns()->create([
            'sizerun_id' => $sizerun->id,
            'started_work_time' => $started_work_time,
            'ended_work_time' => $ended_work_time
        ]);

        return $prodUpper;
    }
}
