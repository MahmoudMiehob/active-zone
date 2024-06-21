<?php

namespace App\Filament\Superadmin\Resources;

use App\Filament\Superadmin\Resources\CouponResource\Pages;
use App\Filament\Superadmin\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.coupon.label');
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
                Tables\Columns\TextColumn::make('type')->label(__('filament-panels::layout.actions.table.type.label'))->searchable(),
                Tables\Columns\TextColumn::make('coupon')->label(__('filament-panels::layout.actions.table.coupon.label'))->searchable(),
                Tables\Columns\TextColumn::make('value')->label(__('filament-panels::layout.actions.table.value.label'))->searchable(),
                Tables\Columns\TextColumn::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Tables\Columns\TextColumn::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Tables\Columns\TextColumn::make('minisurvice.name_en')->label(__('filament-panels::layout.actions.table.minisurvice.label'))->searchable(),
                Tables\Columns\TextColumn::make('region.name')->label(__('filament-panels::layout.actions.table.region.label'))->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            //'create' => Pages\CreateCoupon::route('/create'),
            //'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.coupon.label');
    }
    public static function getPluralModelLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.coupon.label');
    }
}
