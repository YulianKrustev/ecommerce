<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
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

        $productQuantities = collect($productsId)
            ->groupBy('product_id')
            ->map(function ($group) {
                return $group->sum('quantity');
            });

        $this->cart_items = Product::select('id', 'name', 'price', 'images')
            ->whereIn('id', $productQuantities->keys()->toArray()) // Use the keys (product IDs)
            ->get();


        $this->cart_items->each(function ($item) use ($productQuantities) {
            // Attach the corresponding summed quantity to each product item
            $item->quantity = $productQuantities->get($item->id, 0); // Default to 0 if not found
        });
    }

    public function removeItem($product_id)
    {
        $this->fetchCartItems();
        $this->cart_items = CartManagement::removeCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }
}
