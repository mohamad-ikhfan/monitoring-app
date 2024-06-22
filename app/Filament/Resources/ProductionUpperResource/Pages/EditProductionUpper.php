<?php

namespace App\Filament\Resources\ProductionUpperResource\Pages;

use App\Filament\Resources\ProductionUpperResource;
use App\Models\Sizerun;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProductionUpper extends EditRecord
{
    protected static string $resource = ProductionUpperResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

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
                $data['inputs'][] = $upper->sizerun->toArray();

                foreach (array_slice($upper->sizerun->toArray(), 1, 24) as $key => $value) {
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $model = $record->first()->upperSizeruns();
        if ($model->get()->count() == count($data['inputs'])) {
            foreach ($data['inputs'] as $input) {
                $sizerun_input = array_slice($input, 1, 24);
                $sizerun = Sizerun::find($input['id']);
                $sizerun->update($sizerun_input);
            }
        } else {
            $sizerun_id = [];
            foreach ($data['inputs'] as $input) {
                array_push($sizerun_id, $input['id']);
            }

            foreach ($model
                ->whereNotIn('sizerun_id', $sizerun_id)
                ->get() as $upper) {
                $sizerun = Sizerun::find($upper->sizerun_id);
                $sizerun->delete();
            }
        }

        return $record;
    }
}