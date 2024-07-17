<?php

namespace App\Filament\Resources\ProductionAssemblyResource\Pages;

use App\Filament\Resources\ProductionAssemblyResource;
use App\Models\ProductionAssembly;
use App\Models\StockUpperOutsoleByModel;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductionAssembly extends ViewRecord
{
    protected static string $resource = ProductionAssemblyResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = [];

        $prod = $this->getModel()::first();

        $data['production_date'] = $prod->assemblySizeruns->first()->started_work_time;
        $data['started_work_time'] = $prod->assemblySizeruns->first()->started_work_time;
        $data['ended_work_time'] = $prod->assemblySizeruns->first()->ended_work_time;
        $data['select_model'] = $prod->model_name;

        $stockUpperOutsoles = StockUpperOutsoleByModel::where('model_name', $prod->model_name)->get();

        foreach ($stockUpperOutsoles as $stockUpperOutsole) {
            if ($stockUpperOutsole->model_name == $prod->model_name) {
                foreach ($stockUpperOutsole->toArray() as $size => $qty) {
                    if ($qty != null && $size != 'id' && $size != 'model_name') {
                        if (isset($data['sizerun'][$size])) {
                            $data['sizerun'][$size] += intval($qty);
                        } else {
                            $data['sizerun'][$size] = intval($qty);
                        }
                    }
                }
            }
        }

        foreach ($prod->assemblySizeruns as $assembly) {
            $data['inputs'] = array_slice($assembly->sizerun->toArray(), 1, 24);
        }

        $assemblyByModels = ProductionAssembly::where('model_name', $prod->model_name)->get();
        $allProductions = [];
        foreach ($assemblyByModels as $assemblyByReleaseModel) {
            foreach ($assemblyByReleaseModel->assemblySizeruns->map(fn ($v) => $v->sizerun) as  $allSizerun) {
                foreach (array_slice($allSizerun->toArray(), 1, 24) as $size => $qty) {
                    if ($qty != null) {
                        if (isset($allProductions[$size])) {
                            $allProductions[$size] += intval($qty);
                        } else {
                            $allProductions[$size] = intval($qty);
                        }
                    }
                }
            }
        }

        foreach ($allProductions as $size => $qty) {
            if (isset($data['sizerun'][$size])) {
                $data['sizerun'][$size] = intval($qty);
            }
        }

        return $data;
    }
}
