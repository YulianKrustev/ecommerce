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

        $productQuantities = collect($productsId)
            ->groupBy('product_id')
            ->map(function ($group) {
                return $group->sum('quantity');
            });

        $this->cart_items = Product::select('id', 'name', 'price', 'images')
        ->whereIn('id', $productQuantities->keys()->toArray())
        ->get() // Execute the query
        ->map(function ($item) use ($productQuantities) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'price' => $item->price,
                'images' => $item->images,
                'quantity' => $productQuantities->get($item->id, 0),
                'total_units_price' => $item->price * $productQuantities->get($item->id, 0),
            ];
        })
            ->toArray();
    }

    public function increaseQty($product_id)
    {
        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function removeItem($product_id)
    {
        $this->cart_items = CartManagement::removeCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }
}
