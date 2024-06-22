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
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getRows()
    {

        $dataProductions = [];
        $ProdOutsoles = ProductionOutsole::all();
        foreach ($ProdOutsoles as $prodOutsole) {
            foreach ($prodOutsole->outsoleSizeruns()->get() as $outsoleSizerun) {
                array_push($dataProductions, [
                    'process_name' => 'OUTSOLE',
                    'model_name' => $prodOutsole->spkRelease->spkReleasePoItems()->first()->poItem->model_name,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'created_at' => $outsoleSizerun->sizerun->created_at,
                    'updated_at' => $outsoleSizerun->sizerun->updated_at,
                ]);
            }
        }

        $ProdUppers = ProductionUpper::all();
        foreach ($ProdUppers as $prodUpper) {
            foreach ($prodUpper->upperSizeruns()->get() as $outsoleSizerun) {
                array_push($dataProductions, [
                    'process_name' => 'UPPER',
                    'model_name' => $prodUpper->spkRelease->spkReleasePoItems()->first()->poItem->model_name,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'created_at' => $outsoleSizerun->sizerun->created_at,
                    'updated_at' => $outsoleSizerun->sizerun->updated_at,
                ]);
            }
        }

        $ProdAssemblies = ProductionAssembly::all();
        foreach ($ProdAssemblies as $prodAssembly) {
            foreach ($prodAssembly->assemblySizeruns()->get() as $outsoleSizerun) {
                array_push($dataProductions, [
                    'process_name' => 'ASSEMBLY',
                    'model_name' => $prodAssembly->spkRelease->spkReleasePoItems()->first()->poItem->model_name,
                    'qty_prod' => $outsoleSizerun->sizerun->qty_total,
                    'created_at' => $outsoleSizerun->sizerun->created_at,
                    'updated_at' => $outsoleSizerun->sizerun->updated_at,
                ]);
            }
        }

        return $dataProductions;
    }
}