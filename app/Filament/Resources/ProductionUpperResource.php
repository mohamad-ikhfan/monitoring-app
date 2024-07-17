<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductionUpperResource\Pages;
use App\Filament\Resources\ProductionUpperResource\RelationManagers;
use App\Models\PoItem;
use App\Models\ProductionUpper;
use App\Models\SpkRelease;
use App\Models\SpkReleasePoItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductionUpperResource extends Resource
{
    protected static ?string $model = ProductionUpper::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Working days')
                            ->schema([
                                Forms\Components\DatePicker::make('production_date')
                                    ->native(false)
                                    ->displayFormat('l, d-F-Y')
                                    ->firstDayOfWeek(7)
                                    ->locale('en')
                                    ->closeOnDateSelection()
                                    ->default(now())
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\TimePicker::make('started_work_time')
                                    ->native(false)
                                    ->seconds(false)
                                    ->displayFormat('h:i a')
                                    ->locale('en')
                                    ->closeOnDateSelection()
                                    ->default(now()->createFromTime(06, 30, 00))
                                    ->required(),

                                Forms\Components\TimePicker::make('ended_work_time')
                                    ->native(false)
                                    ->seconds(false)
                                    ->displayFormat('h:i a')
                                    ->locale('en')
                                    ->closeOnDateSelection()
                                    ->default(now())
                                    ->required(),
                            ])
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('Spk Release')
                            ->schema([
                                Forms\Components\Select::make('select_release')
                                    ->options(function (): array {
                                        $options = [];
                                        $spkReleases = SpkRelease::all()->pluck('release', 'id');
                                        foreach ($spkReleases as $key => $value) {
                                            $options[$key] = now()->parse($value)->format('m/d Y');
                                        }
                                        return $options;
                                    })
                                    ->columnSpanFull()
                                    ->required()
                                    ->live()
                                    ->disabledOn('edit'),

                                Forms\Components\Select::make('select_model')
                                    ->options(function (Forms\Get $get) {
                                        $releaseId = $get('select_release');
                                        $release = SpkRelease::find($releaseId);

                                        $options = [];
                                        foreach ($release->spkReleasePoItems as $value) {
                                            $options[$value->poItem->model_name] = $value->poItem->model_name;
                                        }
                                        return array_unique($options);
                                    })
                                    ->columnSpanFull()
                                    ->required()
                                    ->hidden(fn (Forms\Get $get) => empty($get('select_release')))
                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                        $set("spk", null);
                                        $set("input", null);

                                        $releaseId = $get('select_release');
                                        $release = SpkRelease::find($releaseId);

                                        $prodUppers = ProductionUpper::where('spk_release_id', $releaseId)
                                            ->where('model_name', $state)
                                            ->get();

                                        foreach ($release->spkReleasePoItems as $spk) {
                                            if ($state == $spk->poItem->model_name) {
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
                                        }

                                        if (isset($array_spk)) {
                                            foreach ($prodUppers as $prodOutsole) {
                                                foreach ($prodOutsole->upperSizeruns as $outsole) {
                                                    $sizerun_outsole = array_slice($outsole->sizerun->toArray(), 1, 24);
                                                    foreach ($sizerun_outsole as $key => $value) {
                                                        if (!empty($value)) {
                                                            $array_spk[$key] -= intval($value);
                                                        }
                                                    }
                                                }
                                            }

                                            foreach ($array_spk as $key => $value) {
                                                $set("spk.$key", $value);
                                            }
                                        }
                                    })
                                    ->live()
                                    ->disabledOn('edit')
                            ]),
                    ]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('SPK Sizerun')
                            ->schema([
                                Forms\Components\TextInput::make('spk.size_3t')
                                    ->label('3T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_4')
                                    ->label('4')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_4t')
                                    ->label('4T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_5')
                                    ->label('5')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_5t')
                                    ->label('5T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_6')
                                    ->label('6')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_6t')
                                    ->label('6T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_7')
                                    ->label('7')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_7t')
                                    ->label('7T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_8')
                                    ->label('8')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_8t')
                                    ->label('8T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_9')
                                    ->label('9')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_9t')
                                    ->label('9T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_10')
                                    ->label('10')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_10t')
                                    ->label('10T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_11')
                                    ->label('11')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_11t')
                                    ->label('11T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_12')
                                    ->label('12')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_12t')
                                    ->label('12T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_13')
                                    ->label('13')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_13t')
                                    ->label('13T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_14')
                                    ->label('14')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_14t')
                                    ->label('14T')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                                Forms\Components\TextInput::make('spk.size_15')
                                    ->label('15')
                                    ->readOnly()
                                    ->hidden(fn ($state) => !is_int($state)),
                            ])
                            ->columns(8),

                        Forms\Components\Fieldset::make('Input productions')
                            ->schema([
                                Forms\Components\TextInput::make('inputs.size_3t')
                                    ->label('3T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_3t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_3t'))),
                                Forms\Components\TextInput::make('inputs.size_4')
                                    ->label('4')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_4'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_4'))),
                                Forms\Components\TextInput::make('inputs.size_4t')
                                    ->label('4T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_4t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_4t'))),
                                Forms\Components\TextInput::make('inputs.size_5')
                                    ->label('5')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_5'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_5'))),
                                Forms\Components\TextInput::make('inputs.size_5t')
                                    ->label('5T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_5t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_5t'))),
                                Forms\Components\TextInput::make('inputs.size_6')
                                    ->label('6')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_6'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_6'))),
                                Forms\Components\TextInput::make('inputs.size_6t')
                                    ->label('6T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_6t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_6t'))),
                                Forms\Components\TextInput::make('inputs.size_7')
                                    ->label('7')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_7'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_7'))),
                                Forms\Components\TextInput::make('inputs.size_7t')
                                    ->label('7T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_7t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_7t'))),
                                Forms\Components\TextInput::make('inputs.size_8')
                                    ->label('8')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_8'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_8'))),
                                Forms\Components\TextInput::make('inputs.size_8t')
                                    ->label('8T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_8t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_8t'))),
                                Forms\Components\TextInput::make('inputs.size_9')
                                    ->label('9')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_9'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_9'))),
                                Forms\Components\TextInput::make('inputs.size_9t')
                                    ->label('9T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_9t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_9t'))),
                                Forms\Components\TextInput::make('inputs.size_10')
                                    ->label('10')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_10'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_10'))),
                                Forms\Components\TextInput::make('inputs.size_10t')
                                    ->label('10T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_10t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_10t'))),
                                Forms\Components\TextInput::make('inputs.size_11')
                                    ->label('11')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_11'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_11'))),
                                Forms\Components\TextInput::make('inputs.size_11t')
                                    ->label('11T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_11t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_11t'))),
                                Forms\Components\TextInput::make('inputs.size_12')
                                    ->label('12')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_12'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_12'))),
                                Forms\Components\TextInput::make('inputs.size_12t')
                                    ->label('12T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_12t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_12t'))),
                                Forms\Components\TextInput::make('inputs.size_13')
                                    ->label('13')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_13'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_13'))),
                                Forms\Components\TextInput::make('inputs.size_13t')
                                    ->label('13T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_13t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_13t'))),
                                Forms\Components\TextInput::make('inputs.size_14')
                                    ->label('14')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_14'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_14'))),
                                Forms\Components\TextInput::make('inputs.size_14t')
                                    ->label('14T')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_14t'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_14t'))),
                                Forms\Components\TextInput::make('inputs.size_15')
                                    ->label('15')
                                    ->minValue(0)
                                    ->maxValue(fn (Forms\Get $get) => $get('spk.size_15'))
                                    ->numeric()
                                    ->hidden(fn (Forms\Get $get) => !is_int($get('spk.size_15'))),
                            ])
                            ->columns(8)
                    ])
                    ->hidden(fn ($state) => empty($state['select_release'])),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'asc')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('spkRelease.release')
                    ->label('Release')
                    ->date('m/d Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('model_name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('working_day')
                    ->state(fn ($record) => $record->upperSizeruns()->count() == 0 ? 0 : range(1, $record->upperSizeruns()->count()))
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('production_date')
                    ->state(fn ($record) => $record->upperSizeruns->first()->started_work_time)
                    ->date('d-F-Y')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.started_work_time')
                    ->label('Started time')
                    ->date('h:i a')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.ended_work_time')
                    ->label('Ended time')
                    ->date('h:i a')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.qty_total')
                    ->label('Qty Production')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_3t')
                    ->label('3T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_4')
                    ->label('4')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_4t')
                    ->label('4T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_5')
                    ->label('5')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_5t')
                    ->label('5T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_6')
                    ->label('6')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_6t')
                    ->label('6T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_7')
                    ->label('7')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_7t')
                    ->label('7T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_8')
                    ->label('8')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_8t')
                    ->label('8T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_9')
                    ->label('9')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_9t')
                    ->label('9T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_10')
                    ->label('10')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_10t')
                    ->label('10T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_11')
                    ->label('11')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_11t')
                    ->label('11T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_12')
                    ->label('12')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_12t')
                    ->label('12T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_13')
                    ->label('13')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_13t')
                    ->label('13T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_14')
                    ->label('14')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_14t')
                    ->label('14T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('upperSizeruns.sizerun.size_15')
                    ->label('15')
                    ->numeric()
                    ->listWithLineBreaks(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('release')
                    ->relationship('spkRelease', 'release'),

                Tables\Filters\SelectFilter::make('model_name')
                    ->options(ProductionUpper::all()->pluck('model_name', 'model_name'))
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel(),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->action(function (Model $record): void {
                        $upperSizes = $record->upperSizeruns();
                        foreach ($upperSizes->get() as $upperSize) {
                            $upperSize->sizerun()->delete();
                        }
                        $upperSizes->delete();
                        $record->delete();
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
            'index' => Pages\ListProductionUppers::route('/'),
            'create' => Pages\CreateProductionUpper::route('/create'),
            'edit' => Pages\EditProductionUpper::route('/{record}/edit'),
            'view' => Pages\ViewProductionUpper::route('/{record}'),
        ];
    }
}
