<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('My Orders')]
class MyOrdersPage extends Component
{
    use WithPagination;
    public function render()
    {
        $my_orders = Order::where('user_id', auth()->id())
            ->where('status', '!=', 'Cancelled')
            ->latest()
            ->paginate(10);
        return view('livewire.my-orders-page', ['orders' => $my_orders]);
    }
}
