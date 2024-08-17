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
        $this->cart_items = CartManagement::fetchCartItems();
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
    }

    public function render()
    {
        return view('livewire.cart-page');
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
