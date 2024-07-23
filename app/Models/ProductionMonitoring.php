<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class ProductionMonitoring extends Model
{
    use HasFactory, Sushi;

    protected $schema = [
        'process_name' => 'string',
        'model_name' => 'string',
        'target_prod' => 'float',
        'qty_prod' => 'float',
        'started_work_time' => 'datetime',
        'ended_work_time' => 'datetime',
    ];

    public function getRows()
    {

        $dataProductions = [];
        $ProdOutsoles = ProductionOutsole::all();
        foreach ($ProdOutsoles as $prodOutsole) {
            foreach ($prodOutsole->outsoleSizeruns as $outsoleSizerun) {
                $target = TargetPerModel::where('model_name', $prodOutsole->model_name)->first();
                array_push($dataProductions, [
                    'process_name' => 'OUTSOLE',
                    'model_name' => $prodOutsole->model_name,
                    'qty_target' => !empty($target) ? $target->target_per_day : 0,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'percentage' => !empty($target) ? round(($outsoleSizerun->sizerun->qty_total / $target->target_per_day) * 100, 2) : 0,
                    'started_work_time' => $outsoleSizerun->started_work_time,
                    'ended_work_time' => $outsoleSizerun->ended_work_time,
                ]);
            }
        }

        $ProdUppers = ProductionUpper::all();
        foreach ($ProdUppers as $prodUpper) {
            foreach ($prodUpper->upperSizeruns as $upperSizerun) {
                $target = TargetPerModel::where('model_name', $prodUpper->model_name)->first();
                array_push($dataProductions, [
                    'process_name' => 'UPPER',
                    'model_name' => $prodUpper->model_name,
                    'qty_target' => !empty($target) ? $target->target_per_day : 0,
                    'qty_prod' => $upperSizerun->sizerun->qty_total,
                    'percentage' => !empty($target) ? round(($upperSizerun->sizerun->qty_total / $target->target_per_day) * 100, 2) : 0,
                    'started_work_time' => $upperSizerun->started_work_time,
                    'ended_work_time' => $upperSizerun->ended_work_time,
                ]);
            }
        }

        $ProdAssemblies = ProductionAssembly::all();
        foreach ($ProdAssemblies as $prodAssembly) {
            foreach ($prodAssembly->assemblySizeruns as $assemblySizerun) {
                $target = TargetPerModel::where('model_name', $prodAssembly->model_name)->first();
                array_push($dataProductions, [
                    'process_name' => 'ASSEMBLY',
                    'model_name' => $prodAssembly->model_name,
                    'qty_target' => !empty($target) ? $target->target_per_day : 0,
                    'qty_prod' => $assemblySizerun->sizerun->qty_total,
                    'percentage' => !empty($target) ? round(($assemblySizerun->sizerun->qty_total / $target->target_per_day) * 100, 2) : 0,
                    'started_work_time' => $assemblySizerun->started_work_time,
                    'ended_work_time' => $assemblySizerun->ended_work_time,
                ]);
            }
        }

        return $dataProductions;
    }
}