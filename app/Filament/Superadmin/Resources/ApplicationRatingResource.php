<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\ApplicationRatingResource\Pages;
use App\Filament\Superadmin\Resources\ApplicationRatingResource\RelationManagers;
use App\Models\ApplicationRating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationRatingResource extends Resource
{
    protected static ?string $model = ApplicationRating::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.ratingapp.label');
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
                Tables\Columns\TextColumn::make('user.name')->label(__('filament-panels::layout.actions.table.username.label'))->searchable(),
                Tables\Columns\TextColumn::make('comment')->label(__('filament-panels::layout.actions.table.comment.label'))->searchable(),
                Tables\Columns\TextColumn::make('rating')->label(__('filament-panels::layout.actions.table.rating.label'))->searchable(),
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
            'index' => Pages\ListApplicationRatings::route('/'),
            //'create' => Pages\CreateApplicationRating::route('/create'),
            //'edit' => Pages\EditApplicationRating::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.ratingapp.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.ratingapp.label');
    }
}
