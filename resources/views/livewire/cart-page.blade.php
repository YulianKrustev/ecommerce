@php
    $isApplied = session('voucher_discount') > 0;
 @endphp
<div class="pb-14 mb-14">
    <div class="mb-4 pb-4"></div>
    <section class="shop-checkout container">
        <h1 class="page-title">Cart</h1>
        <div class="checkout-steps">
            <a wire:navigate href="\cart" class="checkout-steps__item active">
                <span class="checkout-steps__item-number">01</span>
                <span class="checkout-steps__item-title">
            <span>Shopping Bag</span>
            <em>Manage Your Items List</em>
          </span>
            </a>
            <a class="checkout-steps__item">
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
        <div class="shopping-cart">
            <div class="cart-table__wrapper">
                <table class="cart-table">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th></th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($cart_items as $item)

                        <tr wire:key="{{ $item['product_id'] }}">
                            <td>
                                <div class="shopping-cart__product-item">
                                    <a wire:navigate href="/products/{{ $item['slug'] }}">
                                        <img loading="lazy"
                                             src="{{ url('storage', array_slice($item['images'], -1)[0]) }}" width="120"
                                             height="120" alt="{{ $item['name'] }}"/>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <div class="shopping-cart__product-item__detail">
                                    <a wire:navigate href="/products/{{ $item['slug'] }}">
                                        <h2 class="text-lg">{{ $item['name'] }}</h2>
                                    </a>
                                    <ul class="shopping-cart__product-item__options">
                                        <li>Color: Yellow</li>
                                        <li>Size: L</li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="shopping-cart__product-price">{{ Number::currency($item['unit_price'], 'EUR') }}</span>
                            </td>
                            <td>
                                <div class="qty-control position-relative">
                                    <input type="number" name="quantity" value="{{$item['quantity']}}" min="1"
                                           class="qty-control__number text-center">
                                    <div wire:click="decreaseQty({{ $item['product_id'] }})"
                                         class="qty-control__reduce {{ $item['quantity'] == 1 ? 'cursor-not-allowed' : '' }}">
                                        -
                                    </div>
                                    <div wire:click="increaseQty({{$item['product_id']}})"
                                         class="qty-control__increase">+
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span
                                    class="shopping-cart__subtotal">{{ Number::currency($item['unit_price'] * $item['quantity'], 'EUR') }}</span>
                            </td>
                            <td>
                                <a wire:click="removeConfirmation({{ $item['product_id'] }})" href="#"
                                   class="remove-cart">
                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z"/>
                                        <path
                                            d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-2xl font-semibold text-slate-500">No items
                                available in cart!
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @if($cart_items)
                    <div class="cart-table-footer">
                        <form wire:submit.prevent="{{ $isApplied ? 'removeVoucher' : 'applyVoucher' }}" class="position-relative bg-body">
                            <input class="form-control" type="text" wire:model="voucherCode" placeholder="Coupon Code">
                            <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                                   value=" {{ $isApplied ? 'REMOVE' : 'APPLY' }} COUPON">
                            @error('voucherCode') <span class="error">{{ $message }}</span> @enderror
                        </form>
                        @if ($message)
                            <p>{{ $message }}</p>
                        @endif
                    </div>
                @endif
            </div>
            <div class="shopping-cart__totals-wrapper">
                <div class="sticky-content">
                    <div class="shopping-cart__totals">
                        <h3>Cart Totals</h3>
                        <table class="cart-totals">
                            <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td class="text-right {{ $isApplied ? 'line-through' : '' }}">{{ Number::currency($total_units_price, 'EUR') }}</td>
                            </tr>
                            @if($isApplied)
                                <tr>
                                    <th>Discount</th>
                                    <td class="text-right">- {{ Number::currency(session('voucher_discount'), 'EUR') }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>Shipping</th>
                                <td class="text-right">{{ $shipping_cost == 5 ? Number::currency($shipping_cost, 'EUR') : "Free Shipping" }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td class="text-right">{{ $isApplied ? Number::currency(max($price_with_shipping - session('voucher_discount'), 5), 'EUR') : Number::currency($price_with_shipping, 'EUR') }}</td>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                    <div class="mobile_fixed-btn_wrapper">
                        @if($cart_items)
                            <div class="button-wrapper container">
                                <button wire:navigate href="/checkout"
                                        class="btn btn-primary btn-checkout">PROCEED TO
                                    CHECKOUT
                                </button>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>
<script>
    window.addEventListener('show-remove-confirmation', event => {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                popup: "custom-swal-modal",
                confirmButton: "custom-confirm-button",
                cancelButton: "custom-cancel-button"
            },
            buttonsStyling: false
        });

        swalWithBootstrapButtons.fire({
            title: "Are you sure?",
            text: "",
            showCancelButton: true,
            confirmButtonText: "Yes",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
            @this.call('removeItem')
                ;
            }
        });
    });
</script>
