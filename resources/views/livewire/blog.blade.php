@push('meta')
    <meta name="description" content="Blog">
@endpush
<section class="products-grid shop-checkout container mb-4 pb-4 pt-12">
    <div class="mw-930 ">
        <h1 class="page-title">BLOG</h1>
    </div>

    <div class="row mb-14">
        @forelse($posts as $post)
            <div wire:key="{{ $post->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                    <div class="pc__img-wrapper">
                        <a wire:navigate href="/blog/{{ $post->slug }}">
                            <img loading="lazy" src="{{ asset('storage/' . $post->image) }}"
                                 width="330" height="400"
                                 alt="{{ $post->name }}" class="pc__img">
                        </a>
                    </div>

                    <div class="pc__info relative">
                        <h2 class="pc__title">
                            <a wire:navigate
                               href="/blog/{{ $post->slug }}">{{ $post->title }}</a>
                        </h2>


{{--                        <div wire:click='addToCart({{ $product->product->id }})'--}}
{{--                             class="anim_appear-bottom absolute bottom-0 left-0 flex items-center bg-body relative">--}}
{{--                            <button id="add_to_cart_wishlist" class="btn-link btn-link_lg me-4 text-uppercase fw-medium"--}}
{{--                                    data-aside="cartDrawer" title="Add To Cart">Add To Cart--}}
{{--                            </button>--}}


{{--                            <button--}}
{{--                                wire:click.prevent="{{ $wishlistItems->contains('product_id', $product->product->id) ? 'removeFromWishlist('.$product->id.')' : 'addToWishlist('.$product->product->id.')' }}"--}}
{{--                                class="pc__btn-wl bg-transparent border-0 absolute right-2" title="Add To Wishlist">--}}
{{--                                <svg id="wishlist-icon" width="20" height="20" viewBox="0 0 20 20" fill="none"--}}
{{--                                     xmlns="http://www.w3.org/2000/svg">--}}
{{--                                    <use href="#icon_heart_fill"/>--}}
{{--                                </svg>--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>


        @empty
            <span class="text-center py-10 text-2xl font-semibold text-slate-600 mb-82">
                No posts available in Blog!
            </span>
        @endforelse
    </div>
    <div class="pagination">
        {{ $posts->links() }}
    </div>
</section>
