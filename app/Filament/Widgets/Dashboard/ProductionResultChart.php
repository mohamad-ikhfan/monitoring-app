<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\ProductionResult;
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

        foreach (ProductionResult::all() as $value) {
            $model_names[] = $value->model_name;
            $data_outsoles[] = $value->outsole_qty_total;
            $data_uppers[] = $value->upper_qty_total;
            $data_assemblies[] = $value->assembly_qty_total;
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