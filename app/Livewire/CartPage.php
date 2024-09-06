<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Models\Voucher;
use Illuminate\Support\Number;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;
use function PHPUnit\Framework\isNull;


#[Title('Cart | Little Sailors Malta')]
class CartPage extends Component
{
    use LivewireAlert;
    public $cart_items = [];

    public $shipping_cost = 0;
    public $total_units_price;
    public $price_with_shipping;
    public $removeId;
    public $voucherCode;
    public $discount = 0;

    public $message = '';

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

        if ($this->discount > 0) {
            $this->recalculateVoucher();
        }

        $this->addShippingCost();
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function decreaseQty($product_id)
    {
        $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);

        if (session('voucher_discount') && empty($this->cart_items)) {
            $this->removeVoucher();
        } elseif (session('voucher_discount')){
            $this->recalculateVoucher();
        }

        $this->addShippingCost();
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));
    }

    public function removeItem()
    {
        $this->cart_items = CartManagement::removeCartItem($this->removeId, $this->cart_items);
        $this->total_units_price = CartManagement::calculateTotalPrice($this->cart_items);

        if (session('voucher_discount') && empty($this->cart_items)) {
            $this->removeVoucher();
        } elseif (session('voucher_discount')) {
            $this->recalculateVoucher();
        }

        $this->addShippingCost();
        $this->dispatch('update-cart-count', total_count: count($this->cart_items));

        $this->alert('success', 'Product removed successfully!', [
            'position' => 'top-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }

    public function addShippingCost() {
        if (($this->total_units_price - session('voucher_discount') ?? 0) < 50) {
            $this->shipping_cost = 5;
        } else {
            $this->shipping_cost = 0;
        }
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

    public function applyVoucher()
    {
        $this->validate([
            'voucherCode' => 'required|string|min:8|max:12'
        ]);

        $voucher = Voucher::where('code', $this->voucherCode)->first();
        $cart_items = CartManagement::fetchCartItems();
        $subtotal = CartManagement::calculateTotalPrice($cart_items);

        if (!$voucher || !$voucher->isValid()) {
            $this->message = 'This voucher is invalid or has expired.';
            $this->discount = 0;
            return;
        }

        // Check if the order meets the minimum value requirement
        if ($subtotal < $voucher->min_order_value) {
            $this->message = 'This voucher requires a minimum order value of ' . Number::currency(number_format($voucher->min_order_value, 2), "EUR");
            $this->discount = 0;
            return;
        }

        // Check if the voucher is single-use and has already been used by this user
        if ($voucher->single_use && $voucher->users()->where('user_id', auth()->id())->exists()) {
            $this->message = 'You have already used this voucher.';
            $this->discount = 0;
            return;
        }

        // Calculate the discount
        if ($voucher->discount_amount) {
            $this->discount = $voucher->discount_amount;
        } elseif ($voucher->discount_percentage) {
            $this->discount = ($voucher->discount_percentage / 100) * $subtotal;
        }

        $subtotalWithDiscount = number_format($subtotal - $this->discount, 2);

        session([
            'voucher_discount' => $this->discount,
            'voucher_name' => $voucher->name,
            'subtotal_with_discount' => $subtotalWithDiscount,
            'voucher_code' => $this->voucherCode

        ]);

        // Mark the voucher as used by this user only if it's not already used
        if (!$voucher->users()->where('user_id', auth()->id())->exists()) {
            $voucher->users()->attach(auth()->id());
        }

        $this->addShippingCost();
        $this->message = 'Voucher applied successfully!';
    }


    public function removeVoucher()
    {
        $this->discount = 0;

        $voucherCode = session('voucher_code');
        $voucher = Voucher::where('code', $voucherCode)->first();

        if ($voucher && $voucher->users()->where('user_id', auth()->id())->exists()) {
            $voucher->users()->detach(auth()->id());
        }

        session()->forget(['voucher_discount', 'voucher_name', 'subtotal_with_discount', 'voucher_code']);

        $this->message = 'Voucher removed successfully!';
    }

    private function recalculateVoucher()
    {
        $voucherCode = session('voucher_code');

        if (!$voucherCode) {
            $this->discount = 0;
            $this->message = 'No voucher applied.';
            return;
        }

        $voucher = Voucher::where('code', $voucherCode)->first();

        if (!$voucher || !$voucher->isValid()) {
            $this->discount = 0;
            session()->forget(['voucher_discount', 'subtotal_with_discount', 'voucher_code', 'subtotal_with_discount']);
            $this->message = 'The voucher is no longer valid.';
            return;
        }

        $subtotal = CartManagement::calculateTotalPrice($this->cart_items);

        if ($subtotal < $voucher->min_order_value) {
            $this->discount = 0;
            session()->forget(['voucher_discount', 'subtotal_with_discount', 'voucher_code', 'subtotal_with_discount']);
            $this->message = 'The order no longer meets the minimum value for this voucher.';
            return;
        }

        if ($voucher->discount_amount) {
            $this->discount = $voucher->discount_amount;
        } elseif ($voucher->discount_percentage) {
            $this->discount = ($voucher->discount_percentage / 100) * $subtotal;
        }

        $subtotalWithDiscount = number_format($subtotal - $this->discount, 2);

        session([
            'voucher_discount' => $this->discount,
            'subtotal_with_discount' => $subtotalWithDiscount
        ]);
    }
}
