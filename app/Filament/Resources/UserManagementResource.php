<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserManagementResource\Pages;
use App\Filament\Resources\UserManagementResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserManagementResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'User Managements';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->integer()
                            ->unique(ignoreRecord: true)
                            ->afterStateUpdated(function (Forms\Set $set, $state): void {
                                $set('password', bcrypt($state));
                            }),

                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->string(),

                        Forms\Components\Hidden::make('password')
                            ->hiddenOn('edit'),

                        Forms\Components\Select::make('role')
                            ->options(['admin' => 'Admin', 'monitorig' => 'Monitoring', 'spk' => 'SPK', 'outsole' => 'Outsole', 'upper' => 'Upper', 'assembly' => 'Assembly'])
                            ->required()
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->defaultImageUrl(function ($record) {
                        $nameParts = explode(' ', trim($record->name));
                        $firstName = array_shift($nameParts);
                        $lastName = array_pop($nameParts);
                        $initial = (
                            mb_substr($firstName, 0, 1) .
                            mb_substr($lastName, 0, 1)
                        );
                        return "https://ui-avatars.com/api/?name=$initial&color=FFFFFF&background=09090b";
                    })
                    ->circular(),
                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('role'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hiddenLabel(),
                Tables\Actions\DeleteAction::make()
                    ->hiddenLabel(),
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
            'index' => Pages\ListUserManagement::route('/'),
            'create' => Pages\CreateUserManagement::route('/create'),
            'edit' => Pages\EditUserManagement::route('/{record}/edit'),
        ];
    }
}
