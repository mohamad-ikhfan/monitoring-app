<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\ProductionMonitoring;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TargetProductionTable extends BaseWidget
{
    protected static ?string $heading = 'Percentage Production Target';

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductionMonitoring::query())
            ->defaultSort('percentage')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('process_name'),
                Tables\Columns\TextColumn::make('model_name'),
                Tables\Columns\TextColumn::make('qty_target')
                    ->label('Target')
                    ->numeric(),
                Tables\Columns\TextColumn::make('qty_prod')
                    ->label('Qty Production')
                    ->numeric(),
                Tables\Columns\TextColumn::make('percentage')
                    ->label('Percentage')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->badge()
                    ->color(function ($state) {
                        switch ($state) {
                            case 0:
                                $color = 'danger';
                                break;
                            case 100:
                                $color = 'success';
                                break;
                            default:
                                $color = 'warning';
                                break;
                        }
                        return $color;
                    }),
            ]);
    }
}