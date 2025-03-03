<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\User;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class RecentActivityChart extends ChartWidget
{
    protected static ?string $heading = 'Activity Over the Last Two Weeks';

    protected int | string | array $columnSpan = 2;
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->modify('-2 week'),
                end: now(),
            )
            ->perDay()
            ->count();

        $userData = Trend::model(User::class)
            ->between(
                start: now()->modify('-2 week'),
                end: now(),
            )
            ->perDay()
            ->count();


        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
                [
                    'label' => 'Users',
                    'backgroundColor' => '#CAFFCD',
                    'borderColor' => 'green',
                    'data' => $userData->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => (new \DateTime($value->date))->format('D jS')),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
