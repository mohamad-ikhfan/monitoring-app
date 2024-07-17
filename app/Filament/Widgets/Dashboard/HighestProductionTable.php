<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\ProductionResult;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class HighestProductionTable extends BaseWidget
{
    protected static ?string $heading = 'Highest Production Model Status';

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductionResult::query())
            ->defaultSort('status_production_global', 'desc')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('model_name'),

                Tables\Columns\ColumnGroup::make('Production Outsole', [
                    Tables\Columns\TextColumn::make('outsole_qty_total')
                        ->label('Qty')
                        ->numeric(),
                    ProgressColumn::make('status_outsole')
                        ->label('Status')
                        ->progress(fn ($record) => round($record->outsole_qty_total / $record->spk_total_order * 100)),
                ])
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),

                Tables\Columns\ColumnGroup::make('Production Upper', [
                    Tables\Columns\TextColumn::make('upper_qty_total')
                        ->label('Qty')
                        ->numeric(),
                    ProgressColumn::make('status_upper')
                        ->label('Status')
                        ->progress(fn ($record) => round($record->upper_qty_total / $record->spk_total_order * 100)),
                ])
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),

                Tables\Columns\ColumnGroup::make('Production Assembly', [
                    Tables\Columns\TextColumn::make('assembly_qty_total')
                        ->label('Qty')
                        ->numeric(),
                    ProgressColumn::make('status_assembly')
                        ->label('Status')
                        ->progress(fn ($record) => round($record->assembly_qty_total / $record->spk_total_order * 100)),
                ])
                    ->alignment(Alignment::Center)
                    ->wrapHeader(),

                ProgressColumn::make('status_production_global')
                    ->label('Status Production Global')
                    ->progress(fn ($record) => round(($record->outsole_qty_total + $record->upper_qty_total + $record->assembly_qty_total) / ($record->spk_total_order * 3) * 100)),
            ]);
    }
}
