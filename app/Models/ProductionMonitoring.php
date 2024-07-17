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
                array_push($dataProductions, [
                    'process_name' => 'OUTSOLE',
                    'model_name' => $prodOutsole->model_name,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'started_work_time' => $outsoleSizerun->started_work_time,
                    'ended_work_time' => $outsoleSizerun->ended_work_time,
                ]);
            }
        }

        $ProdUppers = ProductionUpper::all();
        foreach ($ProdUppers as $prodUpper) {
            foreach ($prodUpper->upperSizeruns as $outsoleSizerun) {
                array_push($dataProductions, [
                    'process_name' => 'UPPER',
                    'model_name' => $prodUpper->model_name,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'started_work_time' => $outsoleSizerun->started_work_time,
                    'ended_work_time' => $outsoleSizerun->ended_work_time,
                ]);
            }
        }

        $ProdAssemblies = ProductionAssembly::all();
        foreach ($ProdAssemblies as $prodAssembly) {
            foreach ($prodAssembly->assemblySizeruns as $outsoleSizerun) {
                array_push($dataProductions, [
                    'process_name' => 'ASSEMBLY',
                    'model_name' => $prodAssembly->model_name,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'started_work_time' => $outsoleSizerun->started_work_time,
                    'ended_work_time' => $outsoleSizerun->ended_work_time,
                ]);
            }
        }

        return $dataProductions;
    }
}
