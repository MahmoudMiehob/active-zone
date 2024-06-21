<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Minisurvice;
use App\Models\Reservation;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class AdminWidgets extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = auth()->user()->id;

        $total = Reservation::where('paid','=',1)->where('provider_id',$userId)->sum('total_price');
        $tax = Reservation::where('paid','=',1)->where('provider_id',$userId)->sum('tax_price');

        $totalwithouttax = $total - $tax ;
        $profit = ($totalwithouttax * 85 ) / 100 ;


        return [
            Card::make(__('filament-panels::layout.actions.dashboard.TotalMiniSurvice.label'), Minisurvice::where('provider_id','=',$userId)->count())
            ->description(__('filament-panels::layout.actions.dashboard.TotalMiniSurvice.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.dashboard.Reservations.label'), Reservation::where('provider_id',$userId)->count())
            ->description(__('filament-panels::layout.actions.dashboard.Reservations.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.widget.totalaccept.label'), Reservation::where('provider_id',$userId)->where('status','=','accept')->count())
            ->description(__('filament-panels::layout.actions.widget.totalaccept.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.widget.totalends.label'), Reservation::where('provider_id',$userId)->where('status','=','end')->count())
            ->description(__('filament-panels::layout.actions.widget.totalends.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
            Card::make(__('filament-panels::layout.actions.table.profit.label'), $profit)
            ->description(__('filament-panels::layout.actions.table.profit.label'))
            ->chart([2,10,3,12,1,14,10,1,2,10]),
        ];
    }
}
