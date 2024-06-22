<?php

namespace App\Filament\Resources\SpkReleaseResource\Pages;

use App\Filament\Resources\SpkReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CreateSpkRelease extends CreateRecord
{
    protected static string $resource = SpkReleaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Model
    {
        $spkReleased = static::getModel()::create([
            'release' => $data['release'],
            'planning_start_outsole' => $data['planning_start_outsole'],
            'planning_start_upper' => $data['planning_start_upper'],
            'planning_start_assembly' => $data['planning_start_assembly'],
            'planning_finished_assembly' => $data['planning_finished_assembly'],
        ]);

        foreach ($data['po_items'] as $value) {
            $spkReleased->spkReleasePoItems()->create([
                'po_item_id' => $value,
            ]);
        }

        $planning_start_outsole = (new Carbon)->parse($data['planning_start_outsole']);
        $planning_start_upper = (new Carbon)->parse($data['planning_start_upper']);
        $planning_start_assembly = (new Carbon)->parse($data['planning_start_assembly']);
        $planning_finished_assembly = (new Carbon)->parse($data['planning_finished_assembly']);

        $target_day_outsole_to_upper = $planning_start_outsole->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $planning_start_upper);
        $target_day_upper_to_assembly = $planning_start_upper->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $planning_start_assembly);
        $target_day_assembly_finished = $planning_start_assembly->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekend();
        }, $planning_finished_assembly);

        $qty_total = intval(str_replace(',', '', $data['qty_total']));
        $target_qty_perday_outsole = round($qty_total / $target_day_outsole_to_upper);
        $target_qty_perday_upper = round($qty_total / $target_day_upper_to_assembly);
        $target_qty_perday_assembly = round($qty_total / $target_day_assembly_finished);

        $spkReleased->productionOutsoles()->create([
            'target_days' => $target_day_outsole_to_upper,
            'target_qty_perday' => $target_qty_perday_outsole
        ]);

        $spkReleased->productionUppers()->create([
            'target_days' => $target_day_upper_to_assembly,
            'target_qty_perday' => $target_qty_perday_upper
        ]);

        $spkReleased->productionAssemblies()->create([
            'target_days' => $target_day_assembly_finished,
            'target_qty_perday' => $target_qty_perday_assembly
        ]);

        return $spkReleased;
    }
}