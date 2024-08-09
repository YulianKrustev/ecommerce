<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - eCommerce')]
class CartPage extends Component
{
    public $cart_items = [];
    public $total_units_price;

    public function mount()
    {
        $this->fetchCartItems();
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
    }
    public function render()
    {
        return view('livewire.cart-page');
    }

    private function fetchCartItems()
    {
        $productsId = CartManagement::getCartItemsFromCookie();

        if (!empty($productIds)){
            $productIds = array_column($productsId, 'product_id');

            $this->cart_items = Product::select('id', 'name', 'price', 'images')
                ->whereIn('id', $productIds)
                ->get();

            $this->cart_items->each(function ($item) use ($productIds) {
                $item->quantity = collect($productIds)->firstWhere('product_id', $item->id)['quantity'];
            });
        }



    }
}
