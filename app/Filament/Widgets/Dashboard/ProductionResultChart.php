<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\ProductionAssembly;
use App\Models\ProductionOutsole;
use App\Models\ProductionResult;
use App\Models\ProductionUpper;
use App\Models\StockUpperOutsoleByModel;
use Filament\Widgets\ChartWidget;

class ProductionResultChart extends ChartWidget
{
    protected static ?string $heading = 'Production Results';

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $model_names = [];
        $data_outsoles = [];
        $data_uppers = [];
        $data_assemblies = [];

        $stockUpperOutsoleByModels = StockUpperOutsoleByModel::all()->pluck('model_name')->toArray();

        foreach (array_unique($stockUpperOutsoleByModels) as $modelName) {
            $model_names[] = $modelName;
            $productionOutsoles = ProductionOutsole::where('model_name', $modelName)->get();
            foreach ($productionOutsoles as $productionOutsole) {
                foreach ($productionOutsole->outsoleSizeruns->map(fn ($v) => $v->sizerun) as $outsoleSizerun) {
                    $data_outsoles[] = intval($outsoleSizerun->qty_total);
                }
            }

            $productionUppers = ProductionUpper::where('model_name', $modelName)->get();
            foreach ($productionUppers as $productionUpper) {
                foreach ($productionUpper->upperSizeruns->map(fn ($v) => $v->sizerun) as $upperSizerun) {
                    $data_uppers[] = intval($upperSizerun->qty_total);
                }
            }

            $productionAssemblies = ProductionAssembly::where('model_name', $modelName)->get();
            foreach ($productionAssemblies as $productionAssembly) {
                foreach ($productionAssembly->assemblySizeruns->map(fn ($v) => $v->sizerun) as $assemblySizerun) {
                    $data_assemblies[] = intval($assemblySizerun->qty_total);
                }
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'PRODUCTION OUTSOLE',
                    'data' => $data_outsoles,
                    'backgroundColor' => 'rgba(0, 0, 255,  0.2)',
                    'borderColor' => 'rgba(0, 0, 255, 1.0)',
                    'tension' => 0.3,
                    'fill' => '-1',
                ],
                [
                    'label' => 'PRODUCTION UPPER',
                    'data' => $data_uppers,
                    'backgroundColor' => 'rgba(0, 255, 0, 0.2)',
                    'borderColor' => 'rgba(0, 255, 0, 1.0)',
                    'tension' => 0.3,
                    'fill' => '-1',
                ],
                [
                    'label' => 'PRODUCTION ASSEMBLY',
                    'data' => $data_assemblies,
                    'backgroundColor' => 'rgba(255, 128, 0, 0.2)',
                    'borderColor' => 'rgba(255, 128, 0, 1.0)',
                    'tension' => 0.3,
                    'fill' => '-1',
                ],
            ],
            'labels' => $model_names,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
