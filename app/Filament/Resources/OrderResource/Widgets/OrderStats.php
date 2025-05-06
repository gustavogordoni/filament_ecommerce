<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Novos', Order::query()->where('status', 'new')->count()),
            Stat::make('Processando', Order::query()->where('status', 'processing')->count()),
            Stat::make('Enviados', Order::query()->where('status', 'shipped')->count()),

            Stat::make('Preço Médio', Number::currency(Order::query()->avg('grand_total'), 'BRL')),
        ];
    }
}
