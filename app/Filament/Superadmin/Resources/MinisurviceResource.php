<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\MinisurviceResource\Pages;
use App\Filament\Superadmin\Resources\MinisurviceResource\RelationManagers;
use App\Models\Minisurvice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MinisurviceResource extends Resource
{
    protected static ?string $model = Minisurvice::class;

    protected static ?string $navigationGroup = 'survice';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.minisurvice.label');
    }
    public static function canCreate(): bool
    {
        return false;
    }
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
                Tables\Columns\TextColumn::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label'))->searchable(),
                Tables\Columns\ImageColumn::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))->square()->disk('survice_images'),
                Tables\Columns\TextColumn::make('description_ar')->label(__('filament-panels::layout.actions.table.description_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('description_en')->label(__('filament-panels::layout.actions.table.description_en.label'))->searchable(),
                Tables\Columns\TextColumn::make('address_ar')->label(__('filament-panels::layout.actions.table.address_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('address_en')->label(__('filament-panels::layout.actions.table.address_en.label'))->searchable(),
                Tables\Columns\TextColumn::make('baby_price')->label(__('filament-panels::layout.actions.table.baby_price.label'))->searchable(),
                Tables\Columns\TextColumn::make('adult_price')->label(__('filament-panels::layout.actions.table.adult_price.label'))->searchable(),
                Tables\Columns\TextColumn::make('points')->label(__('filament-panels::layout.actions.table.points.label'))->searchable(),
                Tables\Columns\TextColumn::make('tax')->label(__('filament-panels::layout.actions.table.tax.label')),
                Tables\Columns\TextColumn::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Tables\Columns\TextColumn::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Tables\Columns\TextColumn::make('survice.name_en')->label(__('filament-panels::layout.actions.table.survice.label'))->searchable(),
                Tables\Columns\TextColumn::make('country.name')->label(__('filament-panels::layout.actions.table.country.label'))->searchable(),
                Tables\Columns\TextColumn::make('region.name')->label(__('filament-panels::layout.actions.table.region.label'))->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListMinisurvices::route('/'),
            //'create' => Pages\CreateMinisurvice::route('/create'),
            //'edit' => Pages\EditMinisurvice::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.minisurvice.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.minisurvice.label');
    }
}
