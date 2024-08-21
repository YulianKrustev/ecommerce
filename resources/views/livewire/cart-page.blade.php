<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4 min-h-[60vh]">
        <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
        <div class="flex flex-col md:flex-row gap-4">
            <div class="md:w-3/4">
                <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
                    <table class="w-full">
                        <thead>
                        <tr>
                            <th class="text-left font-semibold">Product</th>
                            <th class="text-left font-semibold">Price</th>
                            <th class="text-left font-semibold">Quantity</th>
                            <th class="text-left font-semibold">Total</th>
                            <th class="text-left font-semibold">Remove</th>
                        </tr>
                        </thead>
                        <tbody>

                        @forelse($cart_items as $item)

                            <tr wire:key="{{ $item['product_id'] }}">
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <a wire:navigate href="/products/{{ $item['slug'] }}" class="">
                                            <img class="h-16 w-16 mr-4"
                                                 src="{{ url('storage', array_slice($item['images'], -1)[0]) }}"
                                                 alt="{{ $item['name'] }}">

                                        </a>
                                        <span class="font-semibold ml-3">{{ $item['name'] }}</span>
                                    </div>
                                </td>
                                <td class="py-4">{{ Number::currency($item['unit_price'], 'EUR') }}</td>
                                <td class="py-4">
                                    <div class="flex items-center">
                                        <button
                                            wire:click="decreaseQty({{ $item['product_id'] }})"
                                            class="border rounded-md py-2 px-4 mr-2 {{ $item['quantity'] == 1 ? 'cursor-not-allowed' : '' }}"
                                            {{ $item['quantity'] == 1 ? 'disabled' : '' }}
                                        >-
                                        </button>
                                        <span class="text-center w-8">{{$item['quantity']}}</span>
                                        <button wire:click="increaseQty({{$item['product_id']}})"
                                                class="border rounded-md py-2 px-4 ml-2">+
                                        </button>
                                    </div>
                                </td>
                                <td class="py-4">{{ Number::currency($item['unit_price'] * $item['quantity'], 'EUR') }}</td>
                                <td>
                                    <button wire:click.prevent="removeConfirmation({{ $item['product_id'] }})" ;
                                            class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">
                                        <span wire:loading.remove
                                              wire:target="removeConfirmation({{ $item['product_id'] }})">Remove</span>
                                        <span wire:loading
                                              wire:target="removeConfirmation({{ $item['product_id'] }})">Removing...</span>
                                    </button>

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
                </div>
            </div>
            <div class="md:w-1/4">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold mb-4">Summary</h2>
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>{{ Number::currency($total_units_price, 'EUR') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span>{{ Number::currency($shipping_cost, 'EUR') }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Taxes</span>
                        <span>{{ Number::currency(0, 'EUR') }}</span>
                    </div>
                    <hr class="my-2">
                    <div class="flex justify-between mb-2">
                        <span class="font-semibold">Total</span>
                        <span class="font-semibold">{{ Number::currency($price_with_shipping, 'EUR') }}</span>
                    </div>
                    @if($cart_items)
                        <a wire:click="performAction" href="/checkout"
                           class="bg-blue-500 text-white py-2 block text-center px-4 rounded-lg mt-4 w-full"><span
                                wire:loading.remove wire:target="performAction">Checkout</span><span wire:loading
                                                                                                     wire:target="performAction">Processing...</span></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
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




