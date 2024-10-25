<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class SuccessPage extends Component
{
    #[Url]
    public $session_id;

    public function render()
    {
        $latest_order = Order::with('address')
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->first();

        $discount = 0;
        $subtotal = 0;

        if ($this->session_id) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $session_info = Session::retrieve($this->session_id);

            if ($session_info->payment_status != 'paid') {
                $latest_order->payment_status = 'failed';
                $latest_order->status = 'cancelled';
                $latest_order->save();

                return redirect()->route('cancel');
            } else {
                if ($session_info->payment_status == 'paid') {
                    $latest_order->payment_status = 'paid';
                    $latest_order->save();
                    $cart = CartManagement::fetchCartItems();
                    CartManagement::clearCartItems();

                    DB::transaction(function () use ($cart) {
                        foreach ($cart as $item) {
                            $product = Product::where('id', $item['product_id'])->with('sizes.size')->first();
                            $size = $product->sizes->firstWhere('size.name', $item['size']);
                            $size->decrement('quantity', $item['quantity']);

                            if ($size->quantity < 1) {
                                $inStock = $product->sizes->contains(function ($productSize) {
                                    return $productSize->quantity > 0;
                                });

                                if (!$inStock) {
                                    $product->update(['in_stock' => false]);
                                }
                            }
                        }
                    });
                    $this->dispatch('cartUpdated');
                    session()->forget(['voucher_discount', 'voucher_name', 'subtotal_with_discount', 'voucher_code']);
                    Mail::to(request()->user())->send(new OrderPlaced($latest_order));
                    $discount = $session_info->total_details->amount_discount / 100;
                    $subtotal = $session_info->amount_subtotal / 100;
                }
            }
        }

        return view('livewire.success-page', [
            'order' => $latest_order,
            'discount' => $discount,
            'subtotal' => $subtotal,
        ]);
    }

    public function redirectToHome()
    {
        return redirect()->route('home');
    }
}
