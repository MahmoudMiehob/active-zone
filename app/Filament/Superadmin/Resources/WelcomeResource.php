<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\WelcomeResource\Pages;
use App\Filament\Superadmin\Resources\WelcomeResource\RelationManagers;
use App\Models\Welcome;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WelcomeResource extends Resource
{
    protected static ?string $model = Welcome::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.welcome.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title_ar')->label(__('filament-panels::layout.actions.table.title_ar.label')),
                Forms\Components\TextInput::make('title_en')->label(__('filament-panels::layout.actions.table.title_en.label')),
                Forms\Components\TextInput::make('text_ar')->label(__('filament-panels::layout.actions.table.text_ar.label')),
                Forms\Components\TextInput::make('text_en')->label(__('filament-panels::layout.actions.table.text_en.label')),
                Forms\Components\FileUpload::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))->disk('survice_images'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title_ar')->label(__('filament-panels::layout.actions.table.title_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('title_en')->label(__('filament-panels::layout.actions.table.title_en.label'))->searchable(),
                Tables\Columns\TextColumn::make('text_ar')->label(__('filament-panels::layout.actions.table.text_ar.label'))->searchable(),
                Tables\Columns\TextColumn::make('text_en')->label(__('filament-panels::layout.actions.table.text_en.label'))->searchable(),
                Tables\Columns\ImageColumn::make('imagepath')->label(__('filament-panels::layout.actions.table.image.label'))->square()->disk('survice_images'),

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
            'index' => Pages\ListWelcomes::route('/'),
            'create' => Pages\CreateWelcome::route('/create'),
            'edit' => Pages\EditWelcome::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.welcome.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.welcome.label');
    }
}
