<?php
declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Filament\Resources\OrderResource\Widgets\RecentOrdersTable;
use Filament\Pages\Dashboard as BaseDashboard;


class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }
}
