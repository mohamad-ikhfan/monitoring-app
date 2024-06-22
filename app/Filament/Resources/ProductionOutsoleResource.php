<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionOutsoleResource\Pages;
use App\Filament\Resources\ProductionOutsoleResource\RelationManagers;
use App\Models\PoItem;
use App\Models\ProductionOutsole;
use App\Models\SpkReleasePoItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionOutsoleResource extends Resource
{
    protected static ?string $model = ProductionOutsole::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Spk Release')
                            ->schema([
                                Forms\Components\Select::make('select_release')
                                    ->options(function (): array {
                                        $options = [];
                                        $prod_outsole = ProductionOutsole::all()->pluck('spkRelease.release', 'id');
                                        foreach ($prod_outsole as $key => $value) {
                                            $options[$key] = now()->parse($value)->format('m/d Y');
                                        }
                                        return $options;
                                    })
                                    ->columnSpanFull()
                                    ->required()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        $prod = ProductionOutsole::find($state);
                                        if ($prod) {
                                            foreach ($prod->spkRelease->spkReleasePoItems()->get() as $spk) {
                                                $sizerun = array_slice($spk->poItem->sizerun->toArray(), 1, 24);
                                                foreach ($sizerun as $key => $value) {
                                                    if (!empty($value)) {
                                                        if (isset($array_spk[$key])) {
                                                            $array_spk[$key] += intval($value);
                                                        } else {
                                                            $array_spk[$key] = intval($value);
                                                        }
                                                    }
                                                }
                                            }

                                            if (isset($array_spk)) {
                                                foreach ($prod->outsoleSizeruns()->get() as $outsole) {
                                                    $sizerun_outsole = array_slice($outsole->sizerun->toArray(), 1, 24);
                                                    foreach ($sizerun_outsole as $key => $value) {
                                                        if (!empty($value)) {
                                                            $array_spk[$key] -= intval($value);
                                                        }
                                                    }
                                                }

                                                foreach ($array_spk as $key => $value) {
                                                    $set("spk.$key", $value);
                                                    $set("input.$key", 0);
                                                }
                                            }
                                        }
                                    })
                                    ->live()
                                    ->disabledOn('edit'),

                                Forms\Components\Grid::make(10)
                                    ->schema([
                                        Forms\Components\TextInput::make('spk.size_3t')
                                            ->label('3T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_4')
                                            ->label('4')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_4t')
                                            ->label('4T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_5')
                                            ->label('5')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_5t')
                                            ->label('5T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_6')
                                            ->label('6')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_6t')
                                            ->label('6T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_7')
                                            ->label('7')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_7t')
                                            ->label('7T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_8')
                                            ->label('8')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_8t')
                                            ->label('8T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_9')
                                            ->label('9')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_9t')
                                            ->label('9T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_10')
                                            ->label('10')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_10t')
                                            ->label('10T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_11')
                                            ->label('11')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_11t')
                                            ->label('11T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_12')
                                            ->label('12')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_12t')
                                            ->label('12T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_13')
                                            ->label('13')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_13t')
                                            ->label('13T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_14')
                                            ->label('14')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_14t')
                                            ->label('14T')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                        Forms\Components\TextInput::make('spk.size_15')
                                            ->label('15')
                                            ->readOnly()
                                            ->hidden(fn ($state) => !isset($state)),
                                    ])
                                    ->hidden(fn ($state) => empty($state['select_release']))
                            ]),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Input qty sizerun')
                            ->schema([
                                Forms\Components\TextInput::make('input.size_3t')
                                    ->label('3T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_3t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_4')
                                    ->label('4')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_4'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_4t')
                                    ->label('4T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_4t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_5')
                                    ->label('5')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_5'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_5t')
                                    ->label('5T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_5t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_6')
                                    ->label('6')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_6'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_6t')
                                    ->label('6T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_6t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_7')
                                    ->label('7')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_7'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_7t')
                                    ->label('7T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_7t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_8')
                                    ->label('8')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_8'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_8t')
                                    ->label('8T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_8t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_9')
                                    ->label('9')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_9'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_9t')
                                    ->label('9T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_9t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_10')
                                    ->label('10')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_10'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_10t')
                                    ->label('10T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_10t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_11')
                                    ->label('11')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_11'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_11t')
                                    ->label('11T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_11t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_12')
                                    ->label('12')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_12'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_12t')
                                    ->label('12T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_12t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_13')
                                    ->label('13')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_13'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_13t')
                                    ->label('13T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_13t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_14')
                                    ->label('14')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_14'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_14t')
                                    ->label('14T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_14t'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                                Forms\Components\TextInput::make('input.size_15')
                                    ->label('15')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_15'))
                                    ->numeric()
                                    ->hidden(fn ($state) => $state === null),
                            ])
                            ->columns(10)
                            ->hidden(fn ($state) => empty($state['select_release']))
                    ])
                    ->hiddenOn(['view', 'edit']),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Input qty sizerun')
                            ->schema([
                                Forms\Components\Repeater::make('inputs')
                                    ->hiddenLabel()
                                    ->schema([
                                        Forms\Components\Hidden::make('id'),
                                        Forms\Components\TextInput::make('size_3t')
                                            ->label('3T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_3t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_4')
                                            ->label('4')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_4'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_4t')
                                            ->label('4T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_4t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_5')
                                            ->label('5')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_5'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_5t')
                                            ->label('5T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_5t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_6')
                                            ->label('6')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_6'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_6t')
                                            ->label('6T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_6t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_7')
                                            ->label('7')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_7'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_7t')
                                            ->label('7T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_7t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_8')
                                            ->label('8')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_8'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_8t')
                                            ->label('8T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_8t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_9')
                                            ->label('9')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_9'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_9t')
                                            ->label('9T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_9t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_10')
                                            ->label('10')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_10'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_10t')
                                            ->label('10T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_10t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_11')
                                            ->label('11')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_11'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_11t')
                                            ->label('11T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_11t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_12')
                                            ->label('12')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_12'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_12t')
                                            ->label('12T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_12t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_13')
                                            ->label('13')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_13'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_13t')
                                            ->label('13T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_13t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_14')
                                            ->label('14')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_14'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_14t')
                                            ->label('14T')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_14t'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                        Forms\Components\TextInput::make('size_15')
                                            ->label('15')
                                            ->minValue(0)
                                            ->maxValue(fn (Forms\Get $get) => $get('spk.size_15'))
                                            ->numeric()
                                            ->hidden(fn ($state) => $state === null),
                                    ])
                                    ->addable(false)
                                    ->columns(10)
                            ])
                            ->columns(1)
                    ])
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('spkRelease.planning_start_outsole', 'asc')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('spkRelease.release')
                    ->label('Release')
                    ->date('m/d Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('spkRelease.spkReleasePoItems.poItem.model_name')
                    ->label('Model Name')
                    ->formatStateUsing(fn ($state) => explode(',', $state)[0])
                    ->searchable(),

                Tables\Columns\TextColumn::make('spkRelease.planning_start_outsole')
                    ->label('Plan Start Outsole')
                    ->date('d-F-Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_days'),

                Tables\Columns\TextColumn::make('target_qty_perday')
                    ->numeric(),

                Tables\Columns\TextColumn::make('working_day')
                    ->state(fn ($record) => $record->outsoleSizeruns()->count() == 0 ? 0 : range(1, $record->outsoleSizeruns()->count()))
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.qty_total')
                    ->label('Qty Total')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_3t')
                    ->label('3T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_4')
                    ->label('4')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_4t')
                    ->label('4T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_5')
                    ->label('5')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_5t')
                    ->label('5T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_6')
                    ->label('6')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_6t')
                    ->label('6T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_7')
                    ->label('7')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_7t')
                    ->label('7T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_8')
                    ->label('8')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_8t')
                    ->label('8T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_9')
                    ->label('9')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_9t')
                    ->label('9T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_10')
                    ->label('10')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_10t')
                    ->label('10T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_11')
                    ->label('11')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_11t')
                    ->label('11T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_12')
                    ->label('12')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_12t')
                    ->label('12T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_13')
                    ->label('13')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_13t')
                    ->label('13T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_14')
                    ->label('14')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_14t')
                    ->label('14T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('outsoleSizeruns.sizerun.size_15')
                    ->label('15')
                    ->numeric()
                    ->listWithLineBreaks(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('release')
                    ->relationship('spkRelease', 'release'),

                Tables\Filters\SelectFilter::make('model_name')
                    ->options(function (ProductionOutsole $productionOutsole) {
                        $options = [];
                        foreach ($productionOutsole->all() as $outsole) {
                            foreach ($outsole->spkRelease->spkReleasePoItems()->get() as $spk) {
                                $options[$spk->poItem->model_name] = $spk->poItem->model_name;
                            }
                        }
                        return $options;
                    })
                    ->modifyQueryUsing(function (Builder $query, $data) {
                        if (!empty($data['value'])) {
                            $spk_ids = [];
                            $po_items = PoItem::where('model_name', $data['value'])->get();
                            foreach ($po_items as $po_item) {
                                $spk_po_item = SpkReleasePoItem::where('po_item_id', $po_item->id);
                                if ($spk_po_item->count() > 0) {
                                    $spk_ids[] = $spk_po_item->first()->spk_release_id;
                                }
                            }
                            $query->whereIn('spk_release_id', $spk_ids);
                        }
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel(),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->action(function (Model $record): void {
                        $outsoleSizes = $record->outsoleSizeruns();
                        foreach ($outsoleSizes->get() as $outsoleSize) {
                            $outsoleSize->sizerun()->delete();
                        }
                        -$outsoleSizes->delete();
                    }),
            ], position: Tables\Enums\ActionsPosition::BeforeColumns);
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
            'index' => Pages\ListProductionOutsoles::route('/'),
            'create' => Pages\CreateProductionOutsole::route('/create'),
            'edit' => Pages\EditProductionOutsole::route('/{record}/edit'),
            'view' => Pages\ViewProductionOutsole::route('/{record}'),
        ];
    }
}