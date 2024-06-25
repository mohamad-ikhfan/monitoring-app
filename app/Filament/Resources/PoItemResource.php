<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PoItemResource\Pages;
use App\Filament\Resources\PoItemResource\RelationManagers;
use App\Models\PoItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PoItemResource extends Resource
{
    protected static ?string $model = PoItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('spk_released')
                    ->state(fn ($record) => $record->spkReleasePoItem()->exists())
                    ->boolean(),

                Tables\Columns\TextColumn::make('po_number')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('model_name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('cgac')
                    ->sortable()
                    ->date('m/d'),

                Tables\Columns\TextColumn::make('destination')
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->sortable(),

                Tables\Columns\TextColumn::make('sizerun.qty_total')
                    ->label('Qty Order')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_3t')
                    ->label('3T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_4')
                    ->label('4')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_4t')
                    ->label('4T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_5')
                    ->label('5')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_5t')
                    ->label('5T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_6')
                    ->label('6')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_6t')
                    ->label('6T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_7')
                    ->label('7')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_7t')
                    ->label('7T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_8')
                    ->label('8')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_8t')
                    ->label('8T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_9')
                    ->label('9')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_9t')
                    ->label('9T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_10')
                    ->label('10')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_10t')
                    ->label('10T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_11')
                    ->label('11')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_11t')
                    ->label('11T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_12')
                    ->label('12')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_12t')
                    ->label('12T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_13')
                    ->label('13')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_13t')
                    ->label('13T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_14')
                    ->label('14')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_14t')
                    ->label('14T')
                    ->numeric(),

                Tables\Columns\TextColumn::make('sizerun.size_15')
                    ->label('15')
                    ->numeric(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPoItems::route('/'),
            'edit' => Pages\EditPoItem::route('/{record}/edit'),
        ];
    }
}