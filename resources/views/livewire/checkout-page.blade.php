@php
    $isApplied = session('voucher_discount') > 0;
@endphp
<div class="pb-14 mb-14">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h1 class="page-title">Shipping and Checkout</h1>
        <div class="checkout-steps">
            <a wire:navigate href="/cart" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
            </a>
            <a wire:navigate href="/checkout" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">02</span>
                <span class="checkout-steps__item-title">
            <span>Shipping and Checkout</span>
            <em>Checkout Your Items List</em>
          </span>
            </a>
            <a class="checkout-steps__item">
                <span class="checkout-steps__item-number">03</span>
                <span class="checkout-steps__item-title">
            <span>Confirmation</span>
            <em>Review And Submit Your Order</em>
          </span>
            </a>
        </div>

        <form name="checkout-form" wire:submit.prevent="placeOrder()">
            <div class="checkout-form">
                <div class="billing-info__wrapper">
                    <div class="row">
                        <div class="col-6">
                            <h2>SHIPPING DETAILS</h2>
                        </div>
                        <div class="col-6">
                        </div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input wire:model="first_name" type="text" class="form-control" name="first_name" id="first_name" required="">
                                <label for="first_name">First Name *</label>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input wire:model="last_name" type="text" class="form-control" name="last_name" id="last_name" required="">
                                <label for="last_name">Last Name *</label>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating my-3">
                                <input wire:model="phone" type="text" class="form-control" name="phone" required="">
                                <label for="phone">Phone *</label>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating my-3">
                                <input wire:model="zip_code" type="text" class="form-control" name="zip_code" required="">
                                <label for="zip">Zip Code *</label>
                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating mt-3 mb-3">
                                <input wire:model="district" type="text" class="form-control" name="district" required="">
                                <label for="state">District *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-floating my-3">
                                <input wire:model="city" type="text" class="form-control" name="city" required="">
                                <label for="city">Town / City *</label>

                            </div>
                        </div>
                        <div class="">
                            <div class="form-floating my-3">
                                <input wire:model="street_address"  type="text" class="form-control" name="address" required="">
                                <label for="address">Address *</label>
                                <span class="text-danger"></span>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="checkout__totals-wrapper">
                    <div class="sticky-content">
                        <div class="checkout__totals">
                            <h3>Your Order</h3>
                            <table class="checkout-cart-items">
                                <thead>
                                <tr>
                                    <th>PRODUCT</th>
                                    <th align="right">SUBTOTAL</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($cart_items as $item)
                                    <tr wire:key="{{ $item['product_id'] }}">
                                        <td>
                                            {{ $item['name'] }}, {{ $item['size'] }}, {{ $item['color'] }}<br>
                                            Qty: {{ $item['quantity'] }}
                                        </td>

                                        <td class="text-right align-top">
                                            {{ Number::currency($item['quantity'] * $item['unit_price'] , 'EUR') }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <table class="checkout-totals">
                                <tbody>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td class="text-right">{{ Number::currency($subtotal, 'EUR') }}</td>
                                </tr>
                                @if($isApplied)
                                    <tr>
                                        <th>DISCOUNT</th>
                                        <td class="text-right">- {{ Number::currency(session('voucher_discount'), 'EUR') }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>SHIPPING</th>
                                    <td class="text-right">{{ $shipping_cost == 5 ? Number::currency($shipping_cost, 'EUR') : "Free Shipping" }}</td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td class="text-right">{{ session('voucher_discount') > 0 ? Number::currency($total - session('voucher_discount'), 'EUR') : Number::currency($total, 'EUR')  }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="checkout__payment-methods">
                            <div>
                                <input wire:model="payment_method" name="payment_method" required id="select_payment_stripe" type="radio" value="stripe">
                                <label class="form-check-label" for="select_payment_stripe">
                                    PAY WITH CARD
                                </label>
                            </div>
                                <div class="policy-text">
                                Your personal data will be used to process your order, support your experience throughout this
                                website, and for other purposes described in our <a wire:navigate href="/privacy-policy" target="_blank">privacy
                                    policy</a>.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-checkout"> PLACE ORDER</button>

                    </div>

                </div>
            </div>
        </form>
    </section>
</div>













