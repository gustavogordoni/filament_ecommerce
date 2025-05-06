<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;

use Filament\Resources\Components\Tab;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
          null => Tab::make('Todos'),  
          'new' => Tab::make('Novos')->query(fn ($query) => $query->where('status', 'new')),
          'processing' => Tab::make('Processando')->query(fn ($query) => $query->where('status', 'processing')),
          'shipped' => Tab::make('Enviados')->query(fn ($query) => $query->where('status', 'shipped')),
          'delivered' => Tab::make('Entregues')->query(fn ($query) => $query->where('status', 'delivered')),          
          'canceled' => Tab::make('Cancelados')->query(fn ($query) => $query->where('status', 'cancelled')),
        ];
    }

}
