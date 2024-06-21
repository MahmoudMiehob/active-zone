<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\SurviceResource\Pages;
use App\Filament\Superadmin\Resources\SurviceResource\RelationManagers;
use App\Models\Survice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurviceResource extends Resource
{
    protected static ?string $model = Survice::class;

    protected static ?string $navigationGroup = 'survice';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.survice.label');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label')),
                Forms\Components\TextInput::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label')),
                Forms\Components\FileUpload::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))->disk('survice_images'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_ar')->label(__('filament-panels::layout.actions.table.name_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('name_en')->label(__('filament-panels::layout.actions.table.name_en.label'))->searchable(),
                Tables\Columns\ImageColumn::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))
                ->square()->disk('survice_images'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurvices::route('/'),
            'create' => Pages\CreateSurvice::route('/create'),
            'edit' => Pages\EditSurvice::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.survice.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.survice.label');
    }
}
