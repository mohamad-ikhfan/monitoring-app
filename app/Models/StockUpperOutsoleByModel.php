<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sushi\Sushi;

class StockUpperOutsoleByModel extends Model
{
    use HasFactory, Sushi;

    protected $schema = [
        "model_name" => 'string',
        "size_3t" => 'integer',
        "size_4" => 'integer',
        "size_4t" => 'integer',
        "size_5" => 'integer',
        "size_5t" => 'integer',
        "size_6" => 'integer',
        "size_6t" => 'integer',
        "size_7" => 'integer',
        "size_7t" => 'integer',
        "size_8" => 'integer',
        "size_8t" => 'integer',
        "size_9" => 'integer',
        "size_9t" => 'integer',
        "size_10" => 'integer',
        "size_10t" => 'integer',
        "size_11" => 'integer',
        "size_11t" => 'integer',
        "size_12" => 'integer',
        "size_12t" => 'integer',
        "size_13" => 'integer',
        "size_13t" => 'integer',
        "size_14" => 'integer',
        "size_14t" => 'integer',
        "size_15" => 'integer',
    ];

    public function getRows()
    {
        $rows = [];

        $models = DB::table('production_outsoles')
            ->join('production_uppers', 'production_outsoles.model_name', 'production_uppers.model_name')
            ->get()
            ->map(fn ($v) => $v->model_name)
            ->toArray();
        foreach (array_unique($models) as $model) {
            $outsoles = ProductionOutsole::where('model_name', $model)
                ->first()
                ->outsoleSizeruns
                ->map(fn ($v) => array_slice($v->sizerun->toArray(), 1, 24));

            foreach ($outsoles as $outsole) {
                $outsoleSizeruns = [];
                foreach ($outsole as $size => $qty) {
                    if (isset($outsoleSizeruns[$size])) {
                        $outsoleSizeruns[$size] += intval($qty);
                    } else {
                        $outsoleSizeruns[$size] = intval($qty);
                    }
                }
            }

            $uppers = ProductionUpper::where('model_name', $model)
                ->first()
                ->upperSizeruns
                ->map(fn ($v) => array_slice($v->sizerun->toArray(), 1, 24));

            foreach ($uppers as $upper) {
                $upperSizeruns = [];
                foreach ($upper as $size => $qty) {
                    if (isset($upperSizeruns[$size])) {
                        $upperSizeruns[$size] += intval($qty);
                    } else {
                        $upperSizeruns[$size] = intval($qty);
                    }
                }
            }

            $sizeruns = [
                "size_3t" => 0,
                "size_4" => 0,
                "size_4t" => 0,
                "size_5" => 0,
                "size_5t" => 0,
                "size_6" => 0,
                "size_6t" => 0,
                "size_7" => 0,
                "size_7t" => 0,
                "size_8" => 0,
                "size_8t" => 0,
                "size_9" => 0,
                "size_9t" => 0,
                "size_10" => 0,
                "size_10t" => 0,
                "size_11" => 0,
                "size_11t" => 0,
                "size_12" => 0,
                "size_12t" => 0,
                "size_13" => 0,
                "size_13t" => 0,
                "size_14" => 0,
                "size_14t" => 0,
                "size_15" => 0,
            ];

            foreach ($sizeruns as $size => $qty) {
                if ($outsoleSizeruns[$size] >= $outsoleSizeruns[$size]) {
                    $sizeruns[$size] = $outsoleSizeruns[$size];
                } else {
                    $sizeruns[$size] = $outsoleSizeruns[$size];
                }
            }
            $sizeruns += ['model_name' => $model];
            $rows[] = $sizeruns;
        }

        return $rows;
    }
}
