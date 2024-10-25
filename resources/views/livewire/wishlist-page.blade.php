@push('title')
    Wishlist | {{ config('app.name') }}
@endpush
@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush

<section class="products-grid shop-checkout container mb-4 pb-4 pt-12">
    @php
        use Illuminate\Support\Facades\Auth;if (!Auth::id()) {
                    return redirect('login');
                }
    @endphp
    <div class="mw-930 ">
        <h1 class="page-title">WISHLIST</h1>
    </div>

    <div class="row mb-14">
        @forelse($wishlistItems as $product)
            <div wire:key="{{ $product->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                    <div class="pc__img-wrapper {{ $product->product->in_stock ? '' : 'grayscale' }}">
                        <a wire:navigate href="/{{ $product->product->slug }}">
                            <img loading="lazy" src="{{ asset('storage/' . $product->product->first_image) }}"
                                 width="330" height="400"
                                 alt="{{ $product->product->name }}" class="pc__img">
                            @if($product->product->on_sale)
                                <div class="pc-labels position-absolute top-0 start-0 w-100 d-flex justify-content-between">
                                    <div class="pc-labels__right ms-auto">
                                        <span class="pc-label pc-label_sale d-block text-white">-{{ (int)$product->product->specialOffers[0]->discount_percentage }}%</span>
                                    </div>
                                </div>
                            @endif
                        </a>
                        <button id="add-to-cart-btn"
                                class="pc__atc btn anim_appear-bottom absolute border-0 text-uppercase fw-medium"
                                aria-haspopup="dialog" aria-expanded="false"
                                aria-controls="modal-{{ $product->id }}"
                                data-hs-overlay="#modal-{{ $product->id }}" data-aside="cartDrawer"
                                title="Add To Cart" style=" background-color: #FF7F50; color: white;">
                            @if($product->product->in_stock)
                                Add To Cart
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4 mb-1 inline">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>
                                availability
                            @endif
                        </button>
                    </div>

                    <div class="pc__info relative">
                        <div class="product-card__price flex items-center">
                            <div class="product-card__price flex items-center">
                                    <span
                                        class="pc__category text-sm">{{ $product->product->categories->first()->name }}</span>

                                <button
                                    wire:click.prevent="removeFromWishlist({{ $product->id }})"
                                    class="pc__btn-wl bg-transparent border-0 absolute right-2"
                                    title="Add To Wishlist">
                                    <svg id="wishlist-icon" width="20" height="20" viewBox="0 0 20 20"
                                         fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_heart_fill"/>
                                    </svg>
                                </button>
                            </div>
                        </div>                        <h2 class="pc__title">
                            <a wire:navigate
                               href="/{{ $product->product->slug }}">{{ $product->product->name }}</a>
                        </h2>
                        <div class="product-card__price flex items-center">
                            @if($product->product->on_sale)
                                <span
                                    class="current-price line-through text-gray-600 mr-1 opacity-80 decoration-orange-700">{{ Number::currency($product->product->price, "EUR") }}</span>
                                <span
                                    class="current-price">{{ Number::currency($product->product->on_sale_price, "EUR") }}</span>
                            @else
                                <span
                                    class="current-price">{{ Number::currency($product->product->price, "EUR") }}</span>
                            @endif
                        </div>

                        <div class="anim_appear-bottom bottom-0 left-0 flex items-center bg-body relative">

                            <div id="modal-{{ $product->id }}"
                                 class="flex justify-center items-center hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto "
                                 role="dialog" tabindex="-1" aria-labelledby="hs-scale-animation-modal-label">
                                <div
                                    class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
                                    <div class="w-full flex flex-col bg-white border shadow-sm">
                                        <div
                                            class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                                            <h3 id="hs-scale-animation-modal-label"
                                                class="font-bold text-gray-800 dark:text-white">
                                                Choose Size
                                            </h3>
                                            <button type="button"
                                                    class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600"
                                                    aria-label="Close"
                                                    data-hs-overlay="#modal-{{ $product->id }}">
                                                <span class="sr-only">Close</span>
                                                <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg"
                                                     width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor" stroke-width="2"
                                                     stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 6 6 18"></path>
                                                    <path d="m6 6 12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="p-4 overflow-y-auto">
                                            @foreach($product->product->sizes as $productSize)
                                                <button
                                                    data-hs-overlay="#modal-{{ $product->id }}"
                                                    type="button"
                                                    class="swatch-size btn btn-sm btn-outline-light mb-3 me-1 {{ $productSize->quantity ? '' : 'cursor-not-allowed opacity-50' }}"
                                                    wire:key="product-{{ $product->id }}-size-{{ $productSize->size->id }}"
                                                    @if($product->product->in_stock)
                                                        wire:click="addToCart({{ $product->product->id }}, {{ $productSize->size->id }})"
                                                    @else
                                                        wire:click="notifyMeWhenAvailable({{ $product->product->id }}, {{ $productSize->size->id }})"
                                                    @endif
                                                    @if(!$productSize->quantity && $product->product->in_stock) disabled @endif
                                                >
                                                    {{ $productSize->size->name }}
                                                </button>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <span class="text-center py-10 text-2xl font-semibold text-slate-600 mb-82">
                No items available in wishlist!
            </span>
        @endforelse
    </div>
    <div class="pagination">
        {{ $wishlistItems->links() }}
    </div>
</section>

