<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\Size;
use App\Models\Voucher;
use Illuminate\Support\Facades\Log;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutPage extends Component
{
    use LivewireAlert;
    public $first_name;
    public $last_name;

    public $phone;
    public $street_address;
    public $city;
    public $zip_code;
    public $district;
    public $cuntry;
    public $payment_method;

    public $shipping_cost;


    public function checkAvailbaleSizeQuantity($cart_items)
    {
        foreach ($cart_items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $sizeId = Size::where('name', $item['size'])->first()->id;
            $availableQuantity = $product->sizes->where('size_id', $sizeId)->first()->quantity;

            if ($item['quantity'] > $availableQuantity) {
                return [$item['name'], $item['size'], $availableQuantity];
            }
        }
        return false;
    }

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
            'district' => 'required',
        ]);

        $cart_items = CartManagement::fetchCartItems();

        $productsAvailable = $this->checkAvailbaleSizeQuantity($cart_items);

        if ($productsAvailable) {
            $errors = session()->get('errors', new \Illuminate\Support\MessageBag);
            $errors->add('increaseQty', "There are only $productsAvailable[2] items available in $productsAvailable[0], size: $productsAvailable[1]." );

            session()->flash('errors', $errors);

            return $this->redirect('/cart');
        }

        if (empty($cart_items)) {
            $this->alert('error', 'Your cart is empty!', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);

            return $this->redirect('/cart');
        }

        $line_items = [];

        foreach ($cart_items as $item) {
            $line_items[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['unit_price'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                        'images' => [url('storage/' . array_slice($item['images'], -1)[0])],
                        'description' => 'Size: ' . $item['size'] . ', Color: ' . $item['color'],
                    ],
                ],
                'quantity' => $item['quantity']
            ];
        }

        $subtotal = CartManagement::calculateTotalPrice($cart_items);

        $order = new Order();
        $order->user_id = auth()->user()->id;
        $order->total_price = $subtotal + $this->shipping_cost - session('voucher_discount') ?? '';
        $order->payment_method = 'stripe';
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'eur';
        $order->shipping_cost = $this->shipping_cost;
        $order->shipping_method = 'Little Sailors Malta';
        $order->discount = session('voucher_discount') ?? 0;

        $address = new Address();
        $address->first_name = $this->first_name;
        $address->last_name = $this->last_name;
        $address->phone = $this->phone;
        $address->address = $this->street_address;
        $address->city = $this->city;
        $address->zip_code = $this->zip_code;
        $address->district = $this->district;

        $order->notes = $address->first_name . ' ' . $address->last_name . "\n"
            . $address->phone . "\n"
            . $address->address . "\n"
            . $address->city . "\n"
            . $address->zip_code . "\n"
            . $address->district . "\n"
            . (session('voucher_name') ? 'Used voucher: ' . session('voucher_name') . ' -' . session('voucher_discount') . '€' : '');


        $shipping_rates = [
            [
                'shipping_rate_data' => [
                    'type' => 'fixed_amount',
                    'fixed_amount' => [
                        'amount' => $this->shipping_cost > 0 ? $this->shipping_cost * 100 : 0,
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

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $coupon = null;

        if (session('voucher_code')) {
            $voucher = Voucher::where('code', session('voucher_code'))->first();

            if ($voucher) {
                if ($voucher->discount_amount) {
                    $coupon = \Stripe\Coupon::create([
                        'amount_off' => $voucher->discount_amount * 100, // Convert to cents
                        'currency' => 'eur',
                        'duration' => 'once',
                    ]);
                } elseif ($voucher->discount_percentage) {
                    $coupon = \Stripe\Coupon::create([
                        'percent_off' => $voucher->discount_percentage,
                        'duration' => 'once',
                    ]);
                }
            }
        }

        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => auth()->user()->email,
            'line_items' => $line_items,
            'shipping_options' => $shipping_rates,
            'mode' => 'payment',
            'discounts' => $coupon ? [['coupon' => $coupon->id]] : [],
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout')
        ]);

        $redirect_url = $sessionCheckout->url;

        try {
            $order->save();
            $address->order_id = $order->id;
            $address->save();
            $order->items()->createMany($cart_items);

        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            $this->alert('error', 'Something went wrong, please try again.', [
                'position' => 'top-end',
                'timer' => 3000,
                'toast' => true,
            ]);

            return redirect()->route('login')->with('error', 'Something went wrong, please try again.');
        }

        $this->reset();
        return redirect($redirect_url);
    }

    public function render()
    {
        $latest_order = Order::where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        if (isset($latest_order) && $latest_order->payment_status == 'pending') {
            $latest_order->payment_status = 'failed';
            $latest_order->status = 'cancelled';
            $latest_order->save();
        }

        $cart_items = CartManagement::fetchCartItems();
        $subtotal = CartManagement::calculateTotalPrice($cart_items);

        $this->shipping_cost = $subtotal - (session('voucher_discount') ?? '0') < 50 ? 5 : 0;
        $total = $subtotal + $this->shipping_cost;

        return view('livewire.checkout-page', [
            'cart_items' => $cart_items,
            'total' => $total,
            'subtotal' => $subtotal,
        ]);
    }
}
