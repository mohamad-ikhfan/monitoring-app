<?php

namespace App\Filament\Resources\ProductionUpperResource\Pages;

use App\Filament\Resources\ProductionUpperResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewProductionUpper extends ViewRecord
{
    protected static string $resource = ProductionUpperResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = [];

        $prod = $this->getModel()::first();

        $data['select_release'] = $prod->spkRelease->id;

        foreach ($prod->spkRelease->spkReleasePoItems()->get() as $spk) {
            $sizerun = array_slice($spk->poItem->sizerun->toArray(), 1, 24);
            foreach ($sizerun as $key => $value) {
                if (!empty($value)) {
                    if (isset($array_spk[$key])) {
                        $array_spk[$key] += intval($value);
                    } else {
                        $array_spk[$key] = intval($value);
                    }
                }
            }
        }

        if (isset($array_spk)) {
            foreach ($prod->upperSizeruns()->get() as $upper) {
                $sizerun_upper = array_slice($upper->sizerun->toArray(), 1, 24);
                $data['inputs'][] = $sizerun_upper;

                foreach ($sizerun_upper as $key => $value) {
                    if (!empty($value)) {
                        $array_spk[$key] -= intval($value);
                    }
                }
            }

            foreach ($array_spk as $key => $value) {
                $data['spk'][$key] = $value;
            }
        }

        return $data;
    }
}