<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Address;
use Livewire\Component;
use App\Models\OrderItem;
use Livewire\Attributes\Title;

#[Title('Order Detail - Ecommerce')]
class MyOrderDetailPage extends Component
{
    public $orderId;

    public function mount($orderId){
        $this->orderId = $orderId;
    }

    public function render()
    {
        $orderItems = OrderItem::with('product')->where('order_id', $this->orderId)->get();
        $address = Address::where('order_id', $this->orderId)->first();
        $order = Order::where('id', $this->orderId)->first();
        
        return view('livewire.my-order-detail-page', [
            'orderItems' => $orderItems,
            'address' => $address,
            'order' => $order,
        ]);
    }
}
