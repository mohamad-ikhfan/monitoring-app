<?php

namespace App\Filament\Resources\SpkReleaseResource\Pages;

use App\Filament\Resources\SpkReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class EditSpkRelease extends EditRecord
{
    protected static string $resource = SpkReleaseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $spkRelease = $this->getModel()::first();
        $data['po_items'] = [];
        $data['qty_total'] = 0;
        foreach ($spkRelease->spkReleasePoItems()->get() as $poItem) {
            array_push($data['po_items'], $poItem->po_item_id);
            $data['qty_total'] += $poItem->poItem->sizerun->qty_total;
        }
        $data['qty_total'] = number_format($data['qty_total']);
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'release' => $data['release'],
            'planning_start_outsole' => $data['planning_start_outsole'],
            'planning_start_upper' => $data['planning_start_upper'],
            'planning_start_assembly' => $data['planning_start_assembly'],
            'planning_finished_assembly' => $data['planning_finished_assembly'],
        ]);

        $record->spkReleasePoItems()->delete();

        foreach ($data['po_items'] as $value) {
            $record->spkReleasePoItems()->create([
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

        $record->productionOutsoles()->update([
            'target_days' => $target_day_outsole_to_upper,
            'target_qty_perday' => $target_qty_perday_outsole
        ]);

        $record->productionUppers()->update([
            'target_days' => $target_day_upper_to_assembly,
            'target_qty_perday' => $target_qty_perday_upper
        ]);

        $record->productionAssemblies()->update([
            'target_days' => $target_day_assembly_finished,
            'target_qty_perday' => $target_qty_perday_assembly
        ]);

        return $record;
    }
}