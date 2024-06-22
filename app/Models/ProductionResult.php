<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ProductionResult extends Model
{
    use HasFactory, Sushi;

    protected $schema = [
        'id' => 'integer',
        'model_name' => 'string',
        'outsole_qty_total' => 'float',
        'upper_qty_total' => 'float',
        'assembly_qty_total' => 'float',
        'spk_total_order' => 'float',
        'qty_target_outsole_perday' => 'float',
        'qty_actual_outsole_perday' => 'float',
        'qty_target_upper_perday' => 'float',
        'qty_actual_upper_perday' => 'float',
        'qty_target_assembly_perday' => 'float',
        'qty_actual_assembly_perday' => 'float',
        'status_production_global' => 'float'
    ];

    public function getRows()
    {
        $rows = [];

        $spk_releases = SpkRelease::all();

        $spkReleaseModels = [];

        $spkTotalOrder = [];
        $qtyTotalOutsole = [];
        $qtyTotalUpper = [];
        $qtyTotalAssembly = [];

        $targetPerdayOutsole = [];
        $targetPerdayUpper = [];
        $targetPerdayAssembly = [];

        $qty_target_outsole = 0;
        $qty_actual_outsole = 0;

        $qty_target_upper = 0;
        $qty_actual_upper = 0;

        $qty_target_assembly = 0;
        $qty_actual_assembly = 0;

        foreach ($spk_releases as $spk_release) {
            foreach ($spk_release->spkReleasePoItems()->get() as $spkReleasePoItem) {
                $spkReleaseModels[$spk_release->id] = $spkReleasePoItem->poItem->model_name;
                $spkTotalOrder[$spkReleasePoItem->poItem->model_name][] = $spkReleasePoItem->poItem->sizerun->qty_total;
            }
        }

        foreach ($spkReleaseModels as $spkReleaseId => $modelName) {
            $production_outsoles = ProductionOutsole::all();
            foreach ($production_outsoles as $production_outsole) {
                $spkOutsole = $production_outsole->spkRelease()->find($spkReleaseId);
                if ($spkOutsole) {
                    $qtyOutsole = 0;
                    $arrayQtyOutsole = [];
                    foreach ($production_outsole->outsoleSizeruns()->get() as $outsoleSizerun) {
                        $qtyOutsole += $outsoleSizerun->sizerun->qty_total;
                        $arrayQtyOutsole[] = $outsoleSizerun->sizerun->qty_total;
                    }
                    $qtyTotalOutsole[$modelName][] = $qtyOutsole;

                    $targetPerdayOutsole[$modelName]['target'][] = ['day' => $production_outsole->target_days, 'qty' => $production_outsole->target_qty_perday];

                    $targetPerdayOutsole[$modelName]['actual'][] = ['day' => count($arrayQtyOutsole), 'qty' => $arrayQtyOutsole];
                }
            }

            $production_uppers = ProductionUpper::all();
            foreach ($production_uppers as $production_upper) {
                $spkUpper = $production_upper->spkRelease()->find($spkReleaseId);
                if ($spkUpper) {
                    $qtyUpper = 0;
                    $arrayQtyUpper = [];
                    foreach ($production_upper->upperSizeruns()->get() as $upperSizerun) {
                        $qtyUpper += $upperSizerun->sizerun->qty_total;
                        $arrayQtyUpper[] = $upperSizerun->sizerun->qty_total;
                    }
                    $qtyTotalUpper[$modelName][] = $qtyUpper;

                    $targetPerdayUpper[$modelName]['target'][] = ['day' => $production_upper->target_days, 'qty' => $production_upper->target_qty_perday];
                    $targetPerdayUpper[$modelName]['actual'][] = ['day' => count($arrayQtyUpper), 'qty' => $arrayQtyUpper];
                }
            }

            $production_assemblies = ProductionAssembly::all();
            foreach ($production_assemblies as $production_assembly) {
                $spkAssembly = $production_assembly->spkRelease()->find($spkReleaseId);
                if ($spkAssembly) {
                    $qtyAssembly = 0;
                    $arrayQtyAssembly = [];
                    foreach ($production_assembly->assemblySizeruns()->get() as $assemblySizerun) {
                        $qtyAssembly += $assemblySizerun->sizerun->qty_total;
                        $arrayQtyAssembly[] = $assemblySizerun->sizerun->qty_total;
                    }
                    $qtyTotalAssembly[$modelName][] = $qtyAssembly;

                    $targetPerdayAssembly[$modelName]['target'][] = ['day' => $production_assembly->target_days, 'qty' => $production_assembly->target_qty_perday];
                    $targetPerdayAssembly[$modelName]['actual'][] = ['day' => count($arrayQtyAssembly), 'qty' => $arrayQtyAssembly];
                }
            }
        }

        $model_names = array_values(array_unique($spkReleaseModels));

        foreach ($model_names as $model_name) {
            $qty_target_outsole = 0;
            $qty_actual_outsole = 0;

            $qty_target_upper = 0;
            $qty_actual_upper = 0;

            $qty_target_assembly = 0;
            $qty_actual_assembly = 0;

            $outsole_qty_total = array_sum($qtyTotalOutsole[$model_name]);
            $upper_qty_total = array_sum($qtyTotalUpper[$model_name]);
            $assembly_qty_total = array_sum($qtyTotalAssembly[$model_name]);
            $spk_total_order = array_sum($spkTotalOrder[$model_name]);
            $status_production_global = round(($outsole_qty_total + $upper_qty_total + $assembly_qty_total) / 3);

            foreach ($targetPerdayOutsole[$model_name]['target'] as $key => $target) {
                $actual = $targetPerdayOutsole[$model_name]['actual'][$key];
                $qty_target_outsole += $target['qty'];
                $qty_actual_outsole += count($actual['qty']) > 0 ? array_sum($actual['qty']) / count($actual['qty']) : 0;
            }

            foreach ($targetPerdayUpper[$model_name]['target'] as $key => $target) {
                $actual = $targetPerdayUpper[$model_name]['actual'][$key];
                $qty_target_upper += $target['qty'];
                $qty_actual_upper += count($actual['qty']) > 0 ? array_sum($actual['qty']) / count($actual['qty']) : 0;
            }

            foreach ($targetPerdayAssembly[$model_name]['target'] as $key => $target) {
                $actual = $targetPerdayAssembly[$model_name]['actual'][$key];
                $qty_target_assembly += $target['qty'];
                $qty_actual_assembly += count($actual['qty']) > 0 ? array_sum($actual['qty']) / count($actual['qty']) : 0;
            }


            $rows[] = [
                'model_name' => $model_name,
                'outsole_qty_total' => $outsole_qty_total,
                'upper_qty_total' => $upper_qty_total,
                'assembly_qty_total' => $assembly_qty_total,
                'spk_total_order' => $spk_total_order,
                'qty_target_outsole_perday' => $qty_target_outsole,
                'qty_actual_outsole_perday' => round($qty_actual_outsole),
                'qty_target_upper_perday' => $qty_target_upper,
                'qty_actual_upper_perday' => round($qty_actual_upper),
                'qty_target_assembly_perday' => $qty_target_assembly,
                'qty_actual_assembly_perday' => round($qty_actual_assembly),
                'status_production_global' => $status_production_global
            ];
        }

        return $rows;
    }
}