<?php

namespace App\Filament\Resources\ProductionUpperResource\Pages;

use App\Filament\Resources\ProductionUpperResource;
use App\Models\ProductionUpper;
use App\Models\SpkRelease;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductionUpper extends ViewRecord
{
    protected static string $resource = ProductionUpperResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = [];

        $prod = $this->getModel()::first();

        $data['production_date'] = $prod->upperSizeruns->first()->started_work_time;
        $data['started_work_time'] = $prod->upperSizeruns->first()->started_work_time;
        $data['ended_work_time'] = $prod->upperSizeruns->first()->ended_work_time;
        $data['select_release'] = $prod->spk_release_id;
        $data['select_model'] = $prod->model_name;

        $spkReleasePoItems = SpkRelease::find($prod->spk_release_id)->spkReleasePoItems->map(fn ($v) => $v->poItem);

        foreach ($spkReleasePoItems as $poItem) {
            if ($poItem->model_name == $prod->model_name) {
                foreach (array_slice($poItem->sizerun->toArray(), 1, 24) as $size => $qty) {
                    if ($qty != null) {
                        if (isset($data['spk'][$size])) {
                            $data['spk'][$size] += intval($qty);
                        } else {
                            $data['spk'][$size] = intval($qty);
                        }
                    }
                }
            }
        }

        foreach ($prod->upperSizeruns as $upper) {
            $data['inputs'] = array_slice($upper->sizerun->toArray(), 1, 24);
        }

        $upperByReleaseModels = ProductionUpper::where(['spk_release_id' => $prod->spk_release_id, 'model_name' => $prod->model_name])->get();
        $allProductions = [];
        foreach ($upperByReleaseModels as $upperByReleaseModel) {
            foreach ($upperByReleaseModel->upperSizeruns->map(fn ($v) => $v->sizerun) as  $allSizerun) {
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
            if (isset($data['spk'][$size])) {
                $data['spk'][$size] -= intval($qty);
            }
        }

        return $data;
    }
}
