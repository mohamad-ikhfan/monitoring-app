<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\ProductionMonitoring;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductionMonitoringTable extends BaseWidget
{
    protected static ?string $heading = 'Production Monitoring';

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductionMonitoring::query())
            ->defaultSort('started_work_time', 'desc')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('process_name'),
                Tables\Columns\TextColumn::make('model_name'),
                Tables\Columns\TextColumn::make('qty_prod')
                    ->numeric(),
                Tables\Columns\TextColumn::make('started_work_time')
                    ->date('d-m-Y h:i a'),
                Tables\Columns\TextColumn::make('ended_work_time')
                    ->date('d-m-Y h:i a'),
            ]);
    }
}
