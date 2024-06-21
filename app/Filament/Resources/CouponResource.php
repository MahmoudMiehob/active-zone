<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Coupon;
use App\Models\Region;
use Filament\Forms\Form;
use App\Models\Subsurvice;
use Filament\Tables\Table;
use App\Models\Minisurvice;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CouponResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CouponResource\RelationManagers;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function getNavigationLabel(): string
    {
        return __('filament-panels::layout.actions.sidebar.coupon.label');
    }

    public static function form(Form $form): Form
    {
        $userId = auth()->user()->id;
        return $form
            ->schema([
                Select::make('type')
                ->options([
                    'fix' => 'fix',
                    'percent' => 'percent',
                ])
                ->label(__('filament-panels::layout.actions.table.type.label')),
                Forms\Components\TextInput::make('coupon')->label(__('filament-panels::layout.actions.table.coupon.label')),
                Forms\Components\TextInput::make('value')->label(__('filament-panels::layout.actions.table.value.label')),
                Forms\Components\DatePicker::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Forms\Components\DatePicker::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Select::make('minisurvice_id')->label(__('filament-panels::layout.actions.table.minisurvice.label'))->options(Minisurvice::all()->where('provider_id','=',$userId)->pluck('name_ar', 'id'))->searchable(),
                Select::make('region_id')->label(__('filament-panels::layout.actions.table.region.label'))->options(Region::all()->pluck('name_ar', 'id'))->searchable(),
                Hidden::make('provider_id')->default(auth()->user()->id),
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
                Tables\Columns\TextColumn::make('type')->label(__('filament-panels::layout.actions.table.type.label'))->searchable(),
                Tables\Columns\TextColumn::make('coupon')->label(__('filament-panels::layout.actions.table.coupon.label'))->searchable(),
                Tables\Columns\TextColumn::make('value')->label(__('filament-panels::layout.actions.table.value.label'))->searchable(),
                Tables\Columns\TextColumn::make('start_at')->label(__('filament-panels::layout.actions.table.start_at.label')),
                Tables\Columns\TextColumn::make('end_at')->label(__('filament-panels::layout.actions.table.end_at.label')),
                Tables\Columns\TextColumn::make('minisurvice.name_ar')->label(__('filament-panels::layout.actions.table.minisurvice.label'))->searchable(),
                Tables\Columns\TextColumn::make('region.name_ar')->label(__('filament-panels::layout.actions.table.region.label'))->searchable(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
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
