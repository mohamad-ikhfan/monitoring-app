<?php

namespace App\Filament\Resources\ProductionOutsoleResource\Pages;

use App\Filament\Resources\ProductionOutsoleResource;
use App\Models\OutsoleProductionSize;
use App\Models\Sizerun;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProductionOutsole extends EditRecord
{
    protected static string $resource = ProductionOutsoleResource::class;

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
            $i = 0;
            foreach ($prod->outsoleSizeruns()->get() as $outsole) {
                $data['inputs'][$i] = $outsole->sizerun->toArray();
                $data['inputs'][$i] += ['outsole_id' => $outsole->id];
                $data['inputs'][$i] += ['working_date' => $outsole->started_work_time];
                $data['inputs'][$i] += ['started_work_time' => $outsole->started_work_time];
                $data['inputs'][$i] += ['ended_work_time' => $outsole->ended_work_time];

                foreach (array_slice($outsole->sizerun->toArray(), 1, 24) as $key => $value) {
                    if (!empty($value)) {
                        $array_spk[$key] -= intval($value);
                    }
                }
                $i++;
            }

            foreach ($array_spk as $key => $value) {
                $data['spk'][$key] = $value;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $model = $record->first()->outsoleSizeruns();
        if ($model->get()->count() == count($data['inputs'])) {
            foreach ($data['inputs'] as $input) {
                $sizerun_input = array_slice($input, 1, 24);
                $sizerun = Sizerun::find($input['id']);
                $sizerun->update($sizerun_input);

                $started_work_time = now()->parse($input['working_date'] . ' ' . $input['started_work_time'] . ':00');
                $ended_work_time = now()->parse($input['working_date'] . ' ' . $input['ended_work_time'] . ':00');

                $outsole = OutsoleProductionSize::find($input['outsole_id']);
                $outsole->update([
                    'started_work_time' => $started_work_time,
                    'ended_work_time' => $ended_work_time
                ]);
            }
        } else {
            $sizerun_id = [];
            foreach ($data['inputs'] as $input) {
                array_push($sizerun_id, $input['id']);
            }

            foreach ($model
                ->whereNotIn('sizerun_id', $sizerun_id)
                ->get() as $outsole) {
                $sizerun = Sizerun::find($outsole->sizerun_id);
                $sizerun->delete();
                $outsole->delete();
            }
        }

        return $record;
    }
}