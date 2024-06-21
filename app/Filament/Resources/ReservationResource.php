<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Reservation;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReservationResource\Pages;
use App\Filament\Resources\ReservationResource\RelationManagers;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                Select::make('status')
                ->options([
                    'wait' => 'انتظار',
                    'accept' => 'موافقة',
                    'end' => 'انهاء',
                ])
                ->label(__('filament-panels::layout.actions.dashboard.status.label')),
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
                Tables\Columns\TextColumn::make('user.name')->label(__('filament-panels::layout.actions.table.name.label'))->searchable(),
                Tables\Columns\TextColumn::make('user.email')->label(__('filament-panels::layout.actions.table.email.label'))->searchable(),
                Tables\Columns\TextColumn::make('age')->label(__('filament-panels::layout.actions.table.age.label'))->searchable(),
                Tables\Columns\TextColumn::make('start_time')->label(__('filament-panels::layout.actions.table.start_time.label'))->searchable(),
                Tables\Columns\TextColumn::make('end_time')->label(__('filament-panels::layout.actions.table.end_time.label')),
                Tables\Columns\TextColumn::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Tables\Columns\TextColumn::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Tables\Columns\TextColumn::make('coupon')->label(__('filament-panels::layout.actions.table.coupon.label'))->searchable(),
                Tables\Columns\TextColumn::make('baby_number')->label(__('filament-panels::layout.actions.table.baby_number.label'))->searchable(),
                Tables\Columns\TextColumn::make('adult_number')->label(__('filament-panels::layout.actions.table.adult_number.label'))->searchable(),
                Tables\Columns\TextColumn::make('baby_price')->label(__('filament-panels::layout.actions.table.baby_price.label'))->searchable(),
                Tables\Columns\TextColumn::make('adult_price')->label(__('filament-panels::layout.actions.table.adult_price.label'))->searchable(),
                Tables\Columns\TextColumn::make('total_price')->label(__('filament-panels::layout.actions.table.points.label'))->searchable(),
                Tables\Columns\TextColumn::make('tax_price')->label(__('filament-panels::layout.actions.table.tax.label')),
                Tables\Columns\TextColumn::make('status')->label(__('filament-panels::layout.actions.dashboard.status.label')),

                Tables\Columns\TextColumn::make('minisurvice.name_ar')->label(__('filament-panels::layout.actions.table.minisurvice.label'))->searchable(),
                Tables\Columns\TextColumn::make('region.name_ar')->label(__('filament-panels::layout.actions.table.region.label'))->searchable(),
            ])
            ->filters([

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
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.dashboard.Reservations.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.dashboard.Reservations.label');
    }

}
