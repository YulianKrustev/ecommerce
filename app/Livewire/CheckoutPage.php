<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

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

        $cart_items = CartManagement::fetchCartItems();
        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['unit_price'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                        'images' => ['storage'. array_slice($item['images'], -1)[0]],
                    ],
                ],
                'quantity' => $item['quantity']
            ];
        }

        $subtotal = CartManagement::calculateTotalPrice($cart_items);
        $shipping_cost = $subtotal < 50 ? 5 : 0;
        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->total_price = $subtotal + $shipping_cost;
        $order->payment_method = 'stripe';
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'eur';
        $order->shipping_cost = $shipping_cost;
        $order->shipping_method = 'Little Sailors Malta';
        $order->notes = 'Order placed by ' .auth()->user()->name;

        $address = New Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->address = $this->street_address;
        $address->city = $this->city;
        $address->zip_code = $this->zip_code;

        if ($shipping_cost > 0) {
            $shipping_rates = [
                [
                    'shipping_rate_data' => [
                        'type' => 'fixed_amount',
                        'fixed_amount' => [
                            'amount' => $shipping_cost * 100,
                            'currency' => 'eur',
                        ],
                        'display_name' => 'Standard Shipping',
                        'delivery_estimate' => [
                            'minimum' => [
                                'unit' => 'business_day',
                                'value' => 1,
                            ],
                            'maximum' => [
                                'unit' => 'business_day',
                                'value' => 2,
                            ],
                        ],
                    ],
                ],
            ];
        } else {
            $shipping_rates = []; // No shipping rates if shipping is free
        }

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => auth()->user()->email,
            'line_items' => $line_items,
            'shipping_options' => $shipping_rates,
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        $redirect_url = $sessionCheckout->url;
        $order->save();
        $address->order_id = $order->id;
        $address->save();
        $order->items()->createMany($cart_items);
        CartManagement::clearCartItems();
        Mail::to(request()->user())->send(new OrderPlaced($order));
        return redirect($redirect_url);
    }
    public function render()
    {
        $cart_items = CartManagement::fetchCartItems();
        $subtotal = CartManagement::calculateTotalPrice($cart_items);
        $shipping_cost = $subtotal < 50 ? 5 : 0;
        $total = $subtotal + $shipping_cost;
        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'total' => $total,
            'shipping_cost' => $shipping_cost,
            'subtotal' => $subtotal,
        ]);
    }
}
