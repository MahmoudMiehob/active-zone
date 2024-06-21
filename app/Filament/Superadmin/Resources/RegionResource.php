<?php

namespace App\Filament\Superadmin\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Region;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Superadmin\Resources\RegionResource\Pages;
use App\Filament\Superadmin\Resources\RegionResource\RelationManagers;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label'))->required()->autofocus(),
                Forms\Components\TextInput::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label'))->required(),
                Select::make('country_id')->label(__('filament-panels::layout.actions.table.country.label'))->options(Country::all()->pluck('name', 'id'))->searchable(),
                Hidden::make('provider_id')->default(auth()->user()->id),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label'))->searchable(),
                Tables\Columns\TextColumn::make('country.name')->label(__('filament-panels::layout.actions.table.country.label'))->searchable(),
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
            'index' => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit' => Pages\EditRegion::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.table.region.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.table.region.label');
    }
}
