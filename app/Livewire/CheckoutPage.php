<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;
#[Title('Checkout')]
class CheckoutPage extends Component
{
    public $first_name;
    public $last_name;
    public $phone;
    public $street_address;
    public $city;
    public $zip_code;

    public $cuntry;
    public $payment_method;

    public function placeOrder()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'street_address' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required',
        ]);
    }
    public function render()
    {
        $cart_items = CartManagement::fetchCartItems();
        $total = CartManagement::calculateTotalPrice($cart_items);
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'total' => $total
        ]);
    }
}
