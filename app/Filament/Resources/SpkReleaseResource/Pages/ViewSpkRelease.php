<?php

namespace App\Filament\Resources\SpkReleaseResource\Pages;

use App\Filament\Resources\SpkReleaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpkRelease extends ViewRecord
{
    protected static string $resource = SpkReleaseResource::class;

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
}