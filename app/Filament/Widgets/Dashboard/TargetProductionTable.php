<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\ProductionResult;
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
            ->query(ProductionResult::query())
            ->striped()
            ->columns([
                // Tables\Columns\TextColumn::make('model_name'),

                // Tables\Columns\ColumnGroup::make('Production Outsole', [
                //     Tables\Columns\TextColumn::make('qty_target_outsole_perday')
                //         ->label('Target')
                //         ->numeric(),
                //     Tables\Columns\TextColumn::make('qty_actual_outsole_perday')
                //         ->label('Actual')
                //         ->numeric(),
                //     Tables\Columns\TextColumn::make('percentage_outsole')
                //         ->label('Percentage')
                //         ->state(fn ($record) => $record->qty_actual_outsole_perday > 0 ? round(($record->qty_actual_outsole_perday / $record->qty_target_outsole_perday) * 100, 2) : 0)
                //         ->formatStateUsing(fn ($state) => $state . '%')
                //         ->badge()
                //         ->color(function ($state) {
                //             switch ($state) {
                //                 case 0:
                //                     $color = 'danger';
                //                     break;
                //                 case 100:
                //                     $color = 'success';
                //                     break;
                //                 default:
                //                     $color = 'warning';
                //                     break;
                //             }
                //             return $color;
                //         }),
                // ])
                //     ->alignment(Alignment::Center)
                //     ->wrapHeader(),

                // Tables\Columns\ColumnGroup::make('Production Upper', [
                //     Tables\Columns\TextColumn::make('qty_target_upper_perday')
                //         ->label('Target')
                //         ->numeric(),
                //     Tables\Columns\TextColumn::make('qty_actual_upper_perday')
                //         ->label('Actual')
                //         ->numeric(),
                //     Tables\Columns\TextColumn::make('percentage_upper')
                //         ->label('Percentage')
                //         ->state(fn ($record) => $record->qty_actual_upper_perday > 0 ? round(($record->qty_actual_upper_perday / $record->qty_target_upper_perday) * 100, 2) : 0)
                //         ->formatStateUsing(fn ($state) => $state . '%')
                //         ->badge()
                //         ->color(function ($state) {
                //             switch ($state) {
                //                 case 0:
                //                     $color = 'danger';
                //                     break;
                //                 case 100:
                //                     $color = 'success';
                //                     break;
                //                 default:
                //                     $color = 'warning';
                //                     break;
                //             }
                //             return $color;
                //         }),
                // ])
                //     ->alignment(Alignment::Center)
                //     ->wrapHeader(),

                // Tables\Columns\ColumnGroup::make('Production Assembly', [
                //     Tables\Columns\TextColumn::make('qty_target_assembly_perday')
                //         ->label('Target')
                //         ->numeric(),
                //     Tables\Columns\TextColumn::make('qty_actual_assembly_perday')
                //         ->label('Actual')
                //         ->numeric(),
                //     Tables\Columns\TextColumn::make('percentage_assembly')
                //         ->label('Percentage')
                //         ->state(fn ($record) => $record->qty_actual_assembly_perday > 0 ? round(($record->qty_actual_assembly_perday / $record->qty_target_assembly_perday) * 100, 2) : 0)
                //         ->formatStateUsing(fn ($state) => $state . '%')
                //         ->badge()
                //         ->color(function ($state) {
                //             switch ($state) {
                //                 case 0:
                //                     $color = 'danger';
                //                     break;
                //                 case 100:
                //                     $color = 'success';
                //                     break;
                //                 default:
                //                     $color = 'warning';
                //                     break;
                //             }
                //             return $color;
                //         }),
                // ])
                //     ->alignment(Alignment::Center)
                //     ->wrapHeader(),

                // Tables\Columns\TextColumn::make('percentage_global')
                //     ->label('Percentage Global')
                //     ->state(function ($record) {
                //         $target = $record->qty_target_outsole_perday + $record->qty_target_upper_perday + $record->qty_target_assembly_perday;
                //         $actual = $record->qty_actual_outsole_perday + $record->qty_actual_upper_perday + $record->qty_actual_assembly_perday;
                //         return $actual > 0 ? round(($actual / $target) * 100, 2) : 0;
                //     })
                //     ->formatStateUsing(fn ($state) => $state . '%')
                //     ->badge()
                //     ->color(function ($state) {
                //         switch ($state) {
                //             case 0:
                //                 $color = 'danger';
                //                 break;
                //             case 100:
                //                 $color = 'success';
                //                 break;
                //             default:
                //                 $color = 'warning';
                //                 break;
                //         }
                //         return $color;
                //     }),
            ]);
    }
}
