<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;

class OrderDetailPage extends Component
{
    public $order_id;

    public function mount($order_id)
    {
        $this->order_id = $order_id;
    }
    public function render()
    {
        $order = Order::with('items', 'address')->find($this->order_id);
        $order_items = $order->items;
        $address = $order->address;

        return view('livewire.order-detail-page', [
            'order_items' => $order_items,
            'address' => $address,
            'order' => $order,
        ]);
    }
}
