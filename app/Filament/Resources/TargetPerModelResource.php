<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TargetPerModelResource\Pages;
use App\Filament\Resources\TargetPerModelResource\RelationManagers;
use App\Models\PoItem;
use App\Models\TargetPerModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TargetPerModelResource extends Resource
{
    protected static ?string $model = TargetPerModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('model_name')
                            ->options(PoItem::all()->pluck('model_name', 'model_name'))
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('target_perday')
                            ->required()
                            ->numeric()
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model_name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('target_perday')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTargetPerModels::route('/'),
        ];
    }
}