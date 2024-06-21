<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\TaxResource\Pages;
use App\Filament\Superadmin\Resources\TaxResource\RelationManagers;
use App\Models\Tax;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaxResource extends Resource
{
    protected static ?string $model = Tax::class;

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.table.tax.label');
    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->label(__('filament-panels::layout.actions.table.name.label')),
                Forms\Components\TextInput::make('value')->label(__('filament-panels::layout.actions.table.value.label')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('filament-panels::layout.actions.table.name.label'))->searchable(),
                Tables\Columns\TextColumn::make('value')->label(__('filament-panels::layout.actions.table.value.label')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
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
            'index' => Pages\ListTaxes::route('/'),
            'create' => Pages\CreateTax::route('/create'),
            'edit' => Pages\EditTax::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.table.tax.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.table.tax.label');
    }
}
