<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Mail\OrderPlaced;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Success - Little Sailors Malta')]
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
                $latest_order->save();

                return redirect()->route('cancel');
            } else {
                if ($session_info->payment_status == 'paid') {
                    $latest_order->payment_status = 'paid';
                    $latest_order->save();
                    CartManagement::clearCartItems();
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
