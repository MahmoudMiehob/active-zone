<?php

namespace App\Filament\Superadmin\Widgets;

use Flowframe\Trend\Trend;
use App\Models\Reservation;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class ReservationsChart extends ChartWidget
{
    protected static ?string $heading = null;

    public function __construct()
    {
        self::setHeading();
    }

    protected static function setHeading(): void
    {
        self::$heading = __('filament-panels::layout.actions.dashboard.Reservations.label');
    }
    protected function getData(): array
    {
        $data = Trend::model(Reservation::class)
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();

    return [
        'datasets' => [
            [
                'label' => __('filament-panels::layout.actions.dashboard.Reservations.label'),
                'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn(TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
