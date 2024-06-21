<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\User;
use App\Models\Survice;
use App\Models\Subsurvice;
use App\Models\Minisurvice;
use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class FirstStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {

        $total = Reservation::where('paid','=',1)->sum('total_price');
        $tax = Reservation::where('paid','=',1)->sum('tax_price');

        $totalwithouttax = $total - $tax ;
        $profit = ($totalwithouttax * 15 ) / 100 ;


        return [
            Card::make(__('filament-panels::layout.actions.dashboard.TotalSurvice.label'), Survice::count())
            ->description(__('filament-panels::layout.actions.dashboard.TotalSurvice.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.dashboard.TotalSubSurvice.label'), Subsurvice::count())
            ->description(__('filament-panels::layout.actions.dashboard.TotalSubSurvice.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.dashboard.TotalMiniSurvice.label'), Minisurvice::count())
            ->description(__('filament-panels::layout.actions.dashboard.TotalMiniSurvice.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.dashboard.Reservations.label'), Reservation::count())
            ->description(__('filament-panels::layout.actions.dashboard.Reservations.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.dashboard.users.label'), User::where('role_id','=',1)->count())
            ->description(__('filament-panels::layout.actions.dashboard.users.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.table.total_amount.label'),Reservation::where('paid','=',1)->sum('total_price'))
            ->description(__('filament-panels::layout.actions.table.total_amount.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.table.profit.label'),$profit)
            ->description(__('filament-panels::layout.actions.table.profit.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
        ];
    }
}
