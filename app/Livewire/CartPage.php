<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;


#[Title('Cart - Little Sailors Malta')]
class CartPage extends Component
{
    public $cart_items = [];

    public $shipping_cost = 0;
    public $total_units_price;

    public $price_with_shipping;

    public $removeId;

    protected $listeners = ['removeConfirmed' => 'removeItem'];

    public function mount()
    {
        $this->cart_items = CartManagement::fetchCartItems();
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->addShippingCost();
    }

    public function render()
    {
        return view('livewire.cart-page');
    }

    public function increaseQty($product_id)
    {
        $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->addShippingCost();
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->addShippingCost();
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function removeItem()
    {
        $this->cart_items = CartManagement::removeCartItem($this->removeId, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);
        $this->addShippingCost();
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function addShippingCost() {
        if ($this->total_units_price && $this->total_units_price < 50) {
            $this->shipping_cost = 5;
        } else {
            $this->shipping_cost = 0;
        }

        // Calculate the final total including the shipping cost
        $this->price_with_shipping = $this->total_units_price + $this->shipping_cost;
    }

    public function performAction()
    {
        sleep(2);
        return redirect('/checkout');
    }

    public function removeConfirmation($productId)
    {
        $this->removeId = $productId;
        $this->dispatch('show-remove-confirmation');
    }
}
