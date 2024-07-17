<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ProductionResult extends Model
{
    use HasFactory, Sushi;

    protected $schema = [
        'model_name' => 'string',
        'outsole_qty_total' => 'float',
        'upper_qty_total' => 'float',
        'assembly_qty_total' => 'float',
        'spk_total_order' => 'float',
    ];

    public function getRows()
    {
        $rows = [];

        $stockUpperOutsoleByModels = StockUpperOutsoleByModel::all()->pluck('model_name')->toArray();

        foreach (array_unique($stockUpperOutsoleByModels) as $modelName) {
            $model_name = $modelName;
            $outsole_qty_total = 0;
            $upper_qty_total = 0;
            $assembly_qty_total = 0;
            $spk_total_order = 0;

            $productionOutsoles = ProductionOutsole::where('model_name', $modelName)->get();
            foreach ($productionOutsoles as $productionOutsole) {
                foreach ($productionOutsole->outsoleSizeruns->map(fn ($v) => $v->sizerun) as $outsoleSizerun) {
                    $outsole_qty_total += intval($outsoleSizerun->qty_total);
                }
            }

            $productionUppers = ProductionUpper::where('model_name', $modelName)->get();
            foreach ($productionUppers as $productionUpper) {
                foreach ($productionUpper->upperSizeruns->map(fn ($v) => $v->sizerun) as $upperSizerun) {
                    $upper_qty_total += intval($upperSizerun->qty_total);
                }
            }

            $productionAssemblies = ProductionAssembly::where('model_name', $modelName)->get();
            foreach ($productionAssemblies as $productionAssembly) {
                foreach ($productionAssembly->assemblySizeruns->map(fn ($v) => $v->sizerun) as $assemblySizerun) {
                    $assembly_qty_total += intval($assemblySizerun->qty_total);
                }
            }

            $spkReleases = SpkRelease::all();
            foreach ($spkReleases as $spkRelease) {
                foreach ($spkRelease->spkReleasePoItems->map(fn ($v) => $v->poItem) as $poItem) {
                    if ($poItem->model_name == $model_name) {
                        $spk_total_order += $poItem->sizerun->qty_total;
                    }
                }
            }

            $rows[] = [
                'model_name' => $model_name,
                'outsole_qty_total' => $outsole_qty_total,
                'upper_qty_total' => $upper_qty_total,
                'assembly_qty_total' => $assembly_qty_total,
                'spk_total_order' => $spk_total_order,
            ];
        }

        return $rows;
    }
}
