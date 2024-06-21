<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RatingResource\Pages;
use App\Filament\Resources\RatingResource\RelationManagers;
use App\Models\Rating;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RatingResource extends Resource
{
    protected static ?string $model = Rating::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.rating.label');
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

        $userId = auth()->user()->id;

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($userId) {
                $query->where('provider_id', $userId);
            })
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label(__('filament-panels::layout.actions.table.username.label'))->searchable(),
                Tables\Columns\TextColumn::make('comment')->label(__('filament-panels::layout.actions.table.comment.label'))->searchable(),
                Tables\Columns\TextColumn::make('rating')->label(__('filament-panels::layout.actions.table.rating.label'))->searchable(),
                Tables\Columns\TextColumn::make('minisurvice.name_en')->label(__('filament-panels::layout.actions.table.minisurvice.label'))->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListRatings::route('/'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.rating.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.rating.label');
    }
}
