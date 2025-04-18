<?php

namespace App\Filament\Widgets;

use App\Models\Products\Product;
use App\Models\Order;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuickFacts extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Sales', Order::where('order_status_id', 2)->get()->sum('price')),
            Stat::make('Total Products', Product::count()),
            Stat::make('Total Orders', Order::count()),
            Stat::make('Total Users', User::count())
        ];
    }
}
