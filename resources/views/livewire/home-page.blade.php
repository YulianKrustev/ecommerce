@push('meta')
    <!-- Meta Title -->
    <title>{{ config('app.name') }} | High-Quality Baby & Children's Clothing</title>

    <!-- Meta Description -->
    <meta name="description" content="Discover premium baby and children's clothing, accessories, and toys at {{ config('app.name') }}. Shop sustainable and affordable products for your little ones.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="baby clothing, kids clothing, children's accessories, children's toys, sustainable baby clothing, Malta, online store for children">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="{{ config('app.name') }} | High-Quality Baby & Children's Clothing">
    <meta property="og:description" content="Shop premium children's clothing and accessories at {{ config('app.name') }}. Affordable, sustainable, and stylish options for your little ones.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

<div class="container">
    <!-- Slider -->
    <div data-hs-carousel='{
    "loadingClasses": "opacity-0",
    "isAutoPlay": true
  }' class="relative">
        <div class="hs-carousel relative overflow-hidden h-[57dvh] bg-white rounded-lg">
            <div
                class="hs-carousel-body absolute top-0 bottom-0 start-0 flex  transition-transform duration-700 opacity-0 h-full">
                @foreach($carouselImages as $item)
                    <div class="hs-carousel-slide ">
                        <div class="flex justify-around p-1">
                            <div class=" max-w-md z-10">
                                <h2 class="text-5xl font-bold text-black mb-4">{{ $item['title'] }}</h2>
                                <p class="text-lg text-gray-700 mb-6">test</p>
                                <a wire:navigate href="/shop?selected_categories[0]={{ $item['category_id'] }}" class="btn-link btn-link_lg default-underline fw-medium animated-button">
                                    Shop Now
                                </a>
                            </div>
                            <span class="self-center text-4xl text-gray-800 transition duration-700">
                            <img src="{{ "/storage/" . $item['image_path'] }}" alt="{{ $item['alt_text'] }}">
                        </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- End Slider -->

    <div class=" mw-1620 bg-white border-radius-10">
        <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
        <section class="category-carousel container">
            <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">Categories</h2>
            <div class="flex justify-center">
                <div class="w-1/12 flex items-center pb-10">
                    <div class="w-full text-right">
                        <button onclick="prev()" class="p-2 rounded-full bg-white border-gray-100 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="sliderContainer" class="w-10/12 overflow-hidden">
                    <ul id="slider" class="flex w-full items-center justify-between">
                        @foreach($categories as $category)
                            <li wire:key="category-{{ $category->id }}" class="w-80 pr-4 flex justify-center">
                                <a wire:navigate href="/shop?selected_categories[0]={{ $category->id }}">
                                    <div class="p-2 h-full w-40">
                                        <img class="h-40 min-w-40 object-cover rounded-full hover:opacity-70"
                                             src="{{ url('/storage/' . $category->image) }}"
                                             alt="{{ $category->image_alt }}">
                                        <h3 class="mt-2 text-lg font-bold text-gray-700 text-center">{{ $category->name }}</h3>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="w-1/12 flex items-center pb-10">
                    <div class="w-full text-left">
                        <button onclick="next()" class="p-2 rounded-full bg-white border-gray-100 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="category-banner container">
        <div class="row">
            <div class="col-md-6">
                <div class="category-banner__item border-radius-10 mb-5">
                    <a wire:navigate href="/shop?selected_categories[0]=3"
                       class="">
                    <img loading="lazy" class="h-auto hover:opacity-70" src="{{ asset('/storage/home/Baby boy shop now.webp') }}"
                         width="690" height="665"
                         alt="Baby boy shop now"/>
                    {{--                    <div class="category-banner__item-mark">--}}
                    {{--                        Starting at $19--}}
                    {{--                    </div>--}}
                    <div class="category-banner__item-content">
                        <h3 class="mb-0">Baby Boy</h3>
                        <a wire:navigate href="/shop?selected_categories[0]=3"
                           class="btn-link default-underline text-uppercase fw-medium">Shop Now</a>
                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="category-banner__item border-radius-10 mb-5">
                    <a wire:navigate href="/shop?selected_categories[0]=5">
                    <img loading="lazy" class="h-auto hover:opacity-70" src="{{ asset('/storage/home/Baby girl shop now.webp') }}"
                         width="690" height="665"
                         alt=""/>
                    {{--                    <div class="category-banner__item-mark">--}}
                    {{--                        Starting at $19--}}
                    {{--                    </div>--}}
                    <div class="category-banner__item-content">
                        <h3 class="mb-0">Baby Girl</h3>
                        <a wire:navigate href="/shop?selected_categories[0]=5"
                           class="btn-link default-underline text-uppercase fw-medium">Shop Now</a>
                    </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
    <section class="products-grid container">
        <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Products</h2>
        <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
            @forelse($products as $product)
                <div wire:key="{{ $product->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4">
                    <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                        <div class="pc__img-wrapper {{ $product->in_stock ? '' : 'grayscale' }}">
                            <a wire:navigate href="/{{ $product->slug }}">
                                <img loading="lazy" src="{{ asset('storage/' . $product->first_image) }}"
                                     width="330" height="400"
                                     alt="{{ $product->name }}" class="pc__img">
                                @if($product->on_sale)
                                    <div class="pc-labels position-absolute top-0 start-0 w-100 d-flex justify-content-between">
                                        <div class="pc-labels__right ms-auto">
                                            <span class="pc-label pc-label_sale d-block text-white">-{{ (int)$product->specialOffers[0]->discount_percentage }}%</span>
                                        </div>
                                    </div>
                                @endif
                            </a>
                            <button id="add-to-cart-btn"
                                    class="pc__atc btn anim_appear-bottom btn absolute border-0 text-uppercase fw-medium"
                                    aria-haspopup="dialog" aria-expanded="false"
                                    aria-controls="modal-{{ $product->id }}"
                                    data-hs-overlay="#modal-{{ $product->id }}" data-aside="cartDrawer"
                                    title="Add To Cart" style="background-color: #FF7F50; color: white;">
                                @if($product->in_stock)
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
                                        class="pc__category text-sm">{{ $product->categories->first()->name }}</span>

                                    @if($wishlistItems->contains('product_id', $product->id) )
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
                                    @else
                                        <button
                                            wire:click.prevent="addToWishlist({{ $product->id }})"
                                            class="pc__btn-wl bg-transparent border-0 absolute right-2"
                                            title="Add To Wishlist">
                                            <svg id="wishlist-icon" width="20" height="20" viewBox="0 0 20 20"
                                                 fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_heart"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <h2 class="pc__title">
                                <a wire:navigate
                                   href="/{{ $product->slug }}">{{ $product->name }}</a>
                            </h2>
                            <div class="product-card__price flex items-center">
                                <div class="product-card__price flex items-center">
                                    @if($product->on_sale)
                                        <span
                                            class="current-price line-through text-gray-600 mr-1 opacity-80 decoration-orange-700">{{ Number::currency($product->price, "EUR") }}</span>
                                        <span
                                            class="current-price">{{ Number::currency($product->on_sale_price, "EUR") }}</span>
                                    @else
                                        <span
                                            class="current-price">{{ Number::currency($product->price, "EUR") }}</span>
                                    @endif
                                </div>
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
                                                @foreach($product->sizes as $productSize)
                                                    <button
                                                        data-hs-overlay="#modal-{{ $product->id }}"
                                                        type="button"
                                                        class="swatch-size btn btn-sm btn-outline-light mb-3 me-1 {{ $productSize->quantity ? '' : 'cursor-not-allowed opacity-50' }}"
                                                        wire:key="product-{{ $product->id }}-size-{{ $productSize->size->id }}"
                                                        @if($product->in_stock)
                                                            wire:click="addToCart({{ $product->id }}, {{ $productSize->size->id }})"
                                                        @else
                                                            wire:click="notifyMeWhenAvailable({{ $product->id }}, {{ $productSize->size->id }})"
                                                        @endif
                                                        @if(!$productSize->quantity && $product->in_stock) disabled @endif
                                                    >
                                                        {{ $productSize->size->name }}
                                                    </button>
                                                @endforeach
                                            </div>
                                            <div
                                                class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                                                <button type="button"
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700"
                                                        data-hs-overlay="#modal-{{ $product->id }}">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <span class="text-center flex justify-center py-10 text-2xl font-semibold text-slate-600 mb-82">
                No items found!
            </span>
            @endforelse
        </div>
        <div class="pagination">
            {{ $products->links() }}
        </div>
    </section>
    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const button = document.querySelector(".animated-button");
        setTimeout(() => {
            button.classList.add("show");
        }, 500);
    });

    // Category Slider
    let sliderContainer = document.getElementById('sliderContainer');
    let slider = document.getElementById('slider');
    let cards = slider.getElementsByTagName('li');

    let elementsToShow = calculateElementsToShow();
    let cardWidth = calculateCardWidth();
    let currentOffset = 0;

    // Apply initial card width and set animation
    setCardWidths();
    slider.style.transition = 'margin-left 0.5s ease'; // Adding animation to sliding

    window.addEventListener('resize', () => {
        elementsToShow = calculateElementsToShow();
        cardWidth = calculateCardWidth();
        setCardWidths();
        // Reset the position if resizing changes the layout
        slider.style.marginLeft = `0px`;
        currentOffset = 0;
    });

    function calculateElementsToShow() {
        const width = document.body.clientWidth;
        if (width < 767) return 1;
        if (width < 990) return 2;
        if (width < 1200) return 3;
        if (width < 1500) return 4;
        return 5;
    }

    function calculateCardWidth() {
        const sliderContainerWidth = sliderContainer.clientWidth;
        return sliderContainerWidth / elementsToShow;
    }

    function setCardWidths() {
        // Set the width of the slider and its elements dynamically
        slider.style.width = cards.length * cardWidth + 'px';
        for (let index = 0; index < cards.length; index++) {
            cards[index].style.width = cardWidth + 'px';
        }
    }

    function next() {
        const maxOffset = -(cards.length - elementsToShow) * cardWidth;
        if (currentOffset > maxOffset) {
            currentOffset -= cardWidth;
            slider.style.marginLeft = currentOffset + 'px';
        }
    }

    function prev() {
        if (currentOffset < 0) {
            currentOffset += cardWidth;
            slider.style.marginLeft = currentOffset + 'px';
        }
    }
</script>

<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "{{ config('app.name') }}",
  "url": "{{ config('app.url') }}",
  "description": "Discover high-quality baby and children's clothing, accessories, and toys. Shop {{ config('app.name') }} for affordable, sustainable products.",
  "publisher": {
    "@type": "Organization",
    "name": "{{ config('app.name') }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ url('storage/assets/site_logo.png') }}"
    }
  },
  "image": "{{ url('storage/assets/site_logo.png') }}",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  },
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ config('app.url') }}/shop?search={search_term_string}",
    "query-input": "required name=search_term_string"
  },
  "sameAs": [
    "{{ config('app.social.facebook') }}",
    "{{ config('app.social.instagram') }}"
  ]
}
</script>
