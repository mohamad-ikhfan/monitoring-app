<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpkReleaseResource\Pages;
use App\Filament\Resources\SpkReleaseResource\RelationManagers;
use App\Models\PoItem;
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
use Illuminate\Support\Facades\DB;

class SpkReleaseResource extends Resource
{
    protected static ?string $model = SpkRelease::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?int $sort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Fieldset::make('SPK Schedule')
                            ->schema([
                                Forms\Components\DatePicker::make('release')
                                    ->native(false)
                                    ->displayFormat('m/d Y')
                                    ->firstDayOfWeek(7)
                                    ->locale('en')
                                    ->closeOnDateSelection()
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Fieldset::make('Planning start production')
                                    ->schema([
                                        Forms\Components\DatePicker::make('planning_start_outsole')
                                            ->native(false)
                                            ->displayFormat('m/d Y')
                                            ->firstDayOfWeek(7)
                                            ->locale('en')
                                            ->closeOnDateSelection()
                                            ->required(),

                                        Forms\Components\DatePicker::make('planning_start_upper')
                                            ->native(false)
                                            ->displayFormat('m/d Y')
                                            ->firstDayOfWeek(7)
                                            ->locale('en')
                                            ->closeOnDateSelection()
                                            ->required(),

                                        Forms\Components\DatePicker::make('planning_start_assembly')
                                            ->native(false)
                                            ->displayFormat('m/d Y')
                                            ->firstDayOfWeek(7)
                                            ->locale('en')
                                            ->closeOnDateSelection()
                                            ->required(),
                                    ]),
                            ]),

                        Forms\Components\Fieldset::make('Selected PO Items')
                            ->schema([
                                Forms\Components\Select::make('po_items')
                                    ->options(function (PoItem $poItem, array $state): array {
                                        $options = [];
                                        $po_item_ids = SpkReleasePoItem::whereNotIn('po_item_id', $state)->get('po_item_id')->toArray();
                                        foreach ($poItem->whereNotIn('id', $po_item_ids)->get() as $value) {
                                            $sizerun = $value->sizerun;
                                            $cgac = now()->parse($value->cgac)->format('m/d Y');
                                            $qty = number_format($sizerun->qty_total);
                                            $options[$value->id] = '
                                                    <table class="text-left">
                                                    <tr>
                                                        <th>PO Number</th>
                                                        <td>:</td>
                                                        <td>' . $value->po_number . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Model Name</th>
                                                        <td>: </td>
                                                        <td>' . $value->model_name . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>CGac</th>
                                                        <td>: </td>
                                                        <td>' . $cgac . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Destination</th>
                                                        <td>: </td>
                                                        <td>' . $value->destination . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gender</th>
                                                        <td>: </td>
                                                        <td>' . $value->gender . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Qty</th>
                                                        <td>: </td>
                                                        <td>' . $qty . '</td>
                                                    </tr>
                                                    </table>
                                                    ';
                                        }
                                        return $options;
                                    })
                                    ->allowHtml()
                                    ->searchable()
                                    ->searchingMessage('Searching po item or model name...')
                                    ->noSearchResultsMessage('No po number or model name match your search.')
                                    ->getSearchResultsUsing(function (string $search): array {
                                        $options = [];
                                        $po_item_ids = SpkReleasePoItem::get('po_item_id')->toArray();
                                        $po_items = PoItem::whereNotIn('id', $po_item_ids)
                                            ->where('po_number', 'like', "%{$search}%")
                                            ->orWhere('model_name', 'like', "%{$search}%")
                                            ->limit(50)->get();
                                        foreach ($po_items as $value) {
                                            $sizerun = $value->sizerun;
                                            $cgac = now()->parse($value->cgac)->format('m/d Y');
                                            $qty = number_format($sizerun->qty_total);
                                            $options[$value->id] = '
                                                    <table class="text-left">
                                                    <tr>
                                                        <th>PO Number</th>
                                                        <td>:</td>
                                                        <td>' . $value->po_number . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Model Name</th>
                                                        <td>: </td>
                                                        <td>' . $value->model_name . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>CGac</th>
                                                        <td>: </td>
                                                        <td>' . $cgac . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Destination</th>
                                                        <td>: </td>
                                                        <td>' . $value->destination . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gender</th>
                                                        <td>: </td>
                                                        <td>' . $value->gender . '</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Qty</th>
                                                        <td>: </td>
                                                        <td>' . $qty . '</td>
                                                    </tr>
                                                    </table>
                                                    ';
                                        }
                                        return $options;
                                    })
                                    ->multiple()
                                    ->required()
                                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                                        $total_collect = collect();

                                        $poItems = PoItem::whereIn('id', $state)->get();
                                        foreach ($poItems as $value) {
                                            $total_collect->push(['model' => $value->model_name, 'qty' => $value->sizerun->qty_total]);
                                        }

                                        $total_quantities = [];
                                        foreach ($total_collect->groupBy('model') as $key => $value) {
                                            array_push($total_quantities, ['model' => $key, 'qty' => number_format($total_collect->where('model', $key)->sum('qty'))]);
                                        }
                                        $set('total_quantities', $total_quantities);
                                    })
                                    ->live(),

                                Forms\Components\Repeater::make('total_quantities')
                                    ->schema([
                                        Forms\Components\TextInput::make('model')
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('qty')
                                            ->readOnly()
                                            ->default(0),
                                    ])
                                    ->orderable(false)
                                    ->addable(false)
                                    ->deletable(false)
                            ])
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->defaultGroup(Tables\Grouping\Group::make('release')->date())
            ->columns([
                Tables\Columns\TextColumn::make('release')
                    ->date('m/d Y')
                    ->searchable(query: function (Builder $query, string $search) {
                        $dateClause = env('DB_CONNECTION') === 'sqlite' ? 'strftime("%m/%d %Y",release)' : 'date_format(release, "%m/%d %Y")';
                        return $query->where(DB::raw($dateClause), 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('planning_start_outsole')
                    ->date('d-F-Y'),

                Tables\Columns\TextColumn::make('planning_start_upper')
                    ->date('d-F-Y'),

                Tables\Columns\TextColumn::make('planning_start_assembly')
                    ->date('d-F-Y'),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.po_number')
                    ->label('PO Number')
                    ->listWithLineBreaks()
                    ->searchable(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.model_name')
                    ->label('Model Name')
                    ->listWithLineBreaks()
                    ->searchable(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.cgac')
                    ->label('CGac')
                    ->date('m/d')
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.destination')
                    ->label('Destination')
                    ->listWithLineBreaks()
                    ->searchable(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.gender')
                    ->label('Gender')
                    ->listWithLineBreaks()
                    ->searchable(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.qty_total')
                    ->label('Qty Total')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_3t')
                    ->label('3T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_4')
                    ->label('4')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_4t')
                    ->label('4T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_5')
                    ->label('5')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_5t')
                    ->label('5T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_6')
                    ->label('6')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_6t')
                    ->label('6T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_7')
                    ->label('7')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_7t')
                    ->label('7T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_8')
                    ->label('8')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_8t')
                    ->label('8T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_9')
                    ->label('9')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_9t')
                    ->label('9T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_10')
                    ->label('10')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_10t')
                    ->label('10T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_11')
                    ->label('11')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_11t')
                    ->label('11T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_12')
                    ->label('12')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_12t')
                    ->label('12T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_13')
                    ->label('13')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_13t')
                    ->label('13T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_14')
                    ->label('14')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_14t')
                    ->label('14T')
                    ->numeric()
                    ->listWithLineBreaks(),

                Tables\Columns\TextColumn::make('spkReleasePoItems.poItem.sizerun.size_15')
                    ->label('15')
                    ->numeric()
                    ->listWithLineBreaks()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel(),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel()
                    ->action(function (Model $record): void {
                        $record->spkReleasePoItems()->delete();
                        $record->productionOutsoles()->delete();
                        $record->productionUppers()->delete();
                        $record->productionAssemblies()->delete();
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
            'index' => Pages\ListSpkReleases::route('/'),
            'create' => Pages\CreateSpkRelease::route('/create'),
            'edit' => Pages\EditSpkRelease::route('/{record}/edit'),
            'view' => Pages\ViewSpkRelease::route('/{record}'),
        ];
    }
}
