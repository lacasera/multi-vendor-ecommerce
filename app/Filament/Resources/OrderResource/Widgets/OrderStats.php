<?php

declare(strict_types=1);

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();
        $orderStatsQuery = Order::forSupplier($userId);

        return [
            Stat::make('Total Orders', $orderStatsQuery->count()),
            Stat::make('Pending Orders', $orderStatsQuery->status('pending')->count()),
            Stat::make('Completed Orders', $orderStatsQuery->status('completed')->count()),
            Stat::make('Failed Orders', $orderStatsQuery->status('failed')->count()),
            Stat::make('Products Count', Product::forSupplier($userId)->count()),
        ];
    }
}
