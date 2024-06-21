<?php

namespace App\Filament\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Reservation;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class OrderChart extends ChartWidget
{
    //protected static ?string $heading = null;

    public function __construct()
    {
        self::setHeading();
    }

    protected static function setHeading(): void
    {
        self::$heading = __('filament-panels::layout.actions.widget.order.label');
    }
    protected function getData(): array
    {

        $userId = auth()->user()->id;

        $data = Trend::query(Reservation::where('provider_id','=',$userId))
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();

    return [
        'datasets' => [
            [
                'label' => __('filament-panels::layout.actions.widget.order.label'),
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
