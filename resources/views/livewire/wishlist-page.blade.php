<section class="products-grid shop-checkout container mb-4 pb-4 pt-12">
    <div class="mw-930 ">
        <h1 class="page-title">WISHLIST</h1>
    </div>

    <div class="row mb-14">
        @forelse($wishlistItems as $product)
            <div wire:key="{{ $product->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                    <div class="pc__img-wrapper">
                        <a wire:navigate href="/products/{{ $product->product->slug }}">
                            <img loading="lazy" src="{{ asset('storage/' . $product->product->first_image) }}"
                                 width="330" height="400"
                                 alt="{{ $product->product->name }}" class="pc__img">
                        </a>
                    </div>

                    <div class="pc__info relative">
                        <h2 class="pc__title">
                            <a wire:navigate
                               href="/products/{{ $product->product->slug }}">{{ $product->product->name }}</a>
                        </h2>
                        <div class="product-card__price flex items-center">
                            <span
                                class="money price text-secondary">{{ Number::currency($product->product->price, "EUR") }}</span>
                        </div>

                        <div wire:click='addToCart({{ $product->product->id }})'
                             class="anim_appear-bottom absolute bottom-0 left-0 flex items-center bg-body relative">
                            <button id="add_to_cart_wishlist" class="btn-link btn-link_lg me-4 text-uppercase fw-medium"
                                    data-aside="cartDrawer" title="Add To Cart">Add To Cart
                            </button>


                            <button
                                wire:click.prevent="{{ $wishlistItems->contains('product_id', $product->product->id) ? 'removeFromWishlist('.$product->id.')' : 'addToWishlist('.$product->product->id.')' }}"
                                class="pc__btn-wl bg-transparent border-0 absolute right-2" title="Add To Wishlist">
                                <svg id="wishlist-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_heart_fill"/>
                                </svg>
                            </button>
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
