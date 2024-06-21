<?php

namespace App\Filament\Superadmin\Resources;

use Filament\Forms;
use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\Users;
use App\Models\Survice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Superadmin\Resources\UsersResource\Pages;
use App\Filament\Superadmin\Resources\UsersResource\RelationManagers;

class UsersResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.users.label');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('filament-panels::layout.actions.table.name.label')),
                Forms\Components\TextInput::make('email')->unique(table: User::class)->label(__('filament-panels::layout.actions.table.email.label')),
                Forms\Components\TextInput::make('password')
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state))
                ->label(__('filament-panels::layout.actions.table.password.label')),

                Select::make('role_id')
                ->label(__('filament-panels::layout.actions.table.role.label'))
                ->options(Role::all()->pluck('name', 'id'))
                ->searchable(),

                Select::make('survice_id')
                ->label(__('filament-panels::layout.actions.sidebar.survice.label'))
                ->options(Survice::all()->pluck('name_ar', 'id'))
                ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('filament-panels::layout.actions.table.name.label'))->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('filament-panels::layout.actions.table.email.label'))->searchable(),
                Tables\Columns\TextColumn::make('role.name')->label(__('filament-panels::layout.actions.table.role.label'))->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUsers::route('/create'),
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.users.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.users.label');
    }
}
