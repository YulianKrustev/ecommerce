@push('meta')
    <!-- Meta Description -->
    <meta name="description" content="Browse a wide selection of high-quality, stylish products for children. Discover the latest trends in children's fashion, from clothing to accessories.">

    <!-- Meta Keywords -->
    <meta name="keywords" content="children's clothing, kids fashion, baby clothes, children's accessories, quality kids wear">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="Shop | {{ config('app.name') }}">
    <meta property="og:description" content="Discover a range of quality products for children at {{ config('app.name') }}. Explore top styles, colors, and accessories.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('storage/assets/site_logo.png') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
@endpush

@push('title')
    Shop | {{ config('app.name') }}
@endpush
    <section class="shop-main container flex pt-4 pt-xl-5">

        <!-- Sidebar (Visible only on larger screens) -->
        <div class="shop-sidebar side-sticky bg-body hidden lg:block" id="shopFilter">
            <div class="pt-4 pt-lg-0"></div>
            <div class="accordion" id="categories-list">
                <div class="accordion-item mb-4 pb-3">
                    <h5 class="accordion-header" id="accordion-heading-1">CATEGORIES</h5>
                    <div id="accordion-filter-1" aria-labelledby="accordion-heading-1"
                         data-bs-parent="#categories-list">
                        <div class="accordion-body px-0 pb-0 pt-3">
                            <ul class="list list-inline mb-0">
                                @foreach($categories as $category)
                                    <li class="list-item" wire:key="{{ $category->id }}">
                                        <label for="{{ $category->slug }}"
                                               class="flex items-center dark:text-gray-400 ">
                                            <input type="checkbox" wire:model.live="selected_categories"
                                                   id="{{ $category->slug }}" value="{{ $category->id }}"
                                                   class="w-4 h-4 mr-2">
                                            <span class="menu-link py-1"> {{ $category->name }} </span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion" id="color-filters">
                <div class="accordion-item mb-4 pb-3">
                    <h5 class="accordion-header" id="accordion-heading-1">COLORS</h5>
                    <div id="accordion-filter-2" aria-labelledby="accordion-heading-1" data-bs-parent="#color-filters">
                        <div class="accordion-body px-0 pb-0">
                            <ul class="list list-inline mb-0 flex space-x-2">
                                @foreach($colors as $color)
                                    <li class="list-item" wire:key="{{ $color->id }}">
                                        <button
                                            wire:click="selectColor({{ $color->id }})"
                                            class="swatch-color p-1 m-1 rounded-full hover:opacity-40  {{ $selectedColor == $color->id ? 'opacity-50 border' : '' }}"
                                            style="background-color: {{ $color->hex_code }};" type="button">
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion" id="size-filters">
                <div class="accordion-item mb-4 pb-3">
                    <h5 class="accordion-header" id="accordion-heading-size">SIZES</h5>
                    <div id="accordion-filter-size" aria-labelledby="accordion-heading-size"
                         data-bs-parent="#size-filters">
                        <div class="accordion-body px-0 pb-0">
                            <div class="d-flex flex-wrap">
                                @foreach($sizes as $size)
                                    <button wire:key="{{ $size->id }}"
                                            wire:click="selectSize({{ $size->id }})"
                                            class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 {{ $size->id == $selectedSize ? 'btn-primary' : '' }}"
                                            type="button">{{ $size->name }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion" id="status-filters">
                <div class="accordion-item mb-4 pb-3">
                    <h5 class="accordion-header">STATUS</h5>
                    <div class="accordion-body px-0 pb-0">
                        <ul class="list list-inline mb-0">
                            <li class="list-item">
                                <label for="in_stock" class="flex items-center dark:text-gray-400 ">
                                    <input wire:model.live="in_stock" type="checkbox" id="in_stock" value="1"
                                           class="w-4 h-4 mr-2">
                                    <span class="menu-link py-1"> In Stock </span>
                                </label>
                            </li>
                            <li class="list-item">
                                <label for="on_sale" class="flex items-center dark:text-gray-400 ">
                                    <input wire:model.live="on_sale" type="checkbox" id="on_sale" value="1"
                                           class="w-4 h-4 mr-2">
                                    <span class="menu-link py-1"> On Sale </span>
                                </label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @if(!empty($special_offers))
                <div class="accordion" id="status-filters">
                    <div class="accordion-item mb-4 pb-3">
                        <h5 class="accordion-header">SPECIAL OFFERS</h5>
                        <div class="accordion-body px-0 pb-0">
                            <ul class="list list-inline mb-0">
                                @foreach($special_offers as $special_offer)
                                    <li class="list-item" wire:key="{{ $special_offer->id }}">
                                            <input wire:model.live="selected_sale" type="checkbox" id="{{ $special_offer->slug }}" value="{{ $special_offer->id }}"
                                                   class="w-4 h-4 mr-2">
                                            <span class="menu-link py-1"> {{ $special_offer->name }} </span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Filters Modal for small screens -->
        <div id="hs-filters-modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-75 " tabindex="-1">
            <div class="flex justify-center items-center min-h-screen">
                <div class="relative w-full">
                    <div class="relative bg-white shadow dark:bg-gray-700 mt-2 w-full min-h-svh">
                        <div class="flex items-center justify-between p-5 border-b rounded-t dark:border-gray-600">
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="mb-4">
                                <h5 class="text-lg font-semibold mb-3">CATEGORIES</h5>
                                <ul class="list list-inline mb-0">
                                    @foreach($categories as $category)
                                        <li class="list-item" wire:key="{{ $category->id }}">
                                            <input type="checkbox" wire:model="temp_selected_categories"
                                                   id="{{ $category->slug }}" value="{{ $category->id }}"
                                                   class="w-4 h-4 mr-2">
                                            <span class="menu-link py-1"> {{ $category->name }} </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-4">
                                <h5 class="text-lg font-semibold mb-3">COLORS</h5>
                                <ul class="list list-inline mb-0 flex space-x-2">
                                    @foreach($colors as $color)
                                        <li class="list-item" wire:key="{{ $color->id }}">
                                            <button wire:click="selectColor({{ $color->id }})"
                                                    class="swatch-color p-1 m-1 rounded-full hover:opacity-40  {{ $selectedColor == $color->id ? 'opacity-50 border' : '' }}"
                                                    style="background-color: {{ $color->hex_code }};"
                                                    type="button"></button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-4">
                                <h5 class="text-lg font-semibold mb-3">SIZES</h5>
                                <div class="flex flex-wrap">
                                    @foreach($sizes as $size)

                                        <button wire:key="{{ $size->id }}"
                                                wire:click="selectSize({{ $size->id }})"
                                                class="swatch-size btn btn-sm btn-outline-light mb-3 me-3 {{ $size->id == $selectedSize ? 'btn-primary' : '' }}"
                                                type="button">
                                            {{ $size->name }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                            <div class="mb-4">
                                <h5 class="text-lg font-semibold mb-3">STATUS</h5>
                                <ul class="list list-inline mb-0">
                                    <li class="list-item">
                                        <input wire:model="temp_in_stock" type="checkbox" id="in_stock" value="1"
                                               class="w-4 h-4 mr-2">
                                        <span class="menu-link py-1"> In Stock </span>
                                    </li>
                                    <li class="list-item">
                                        <input wire:model="temp_on_sale" type="checkbox" id="on_sale" value="1"
                                               class="w-4 h-4 mr-2">
                                        <span class="menu-link py-1"> On Sale </span>
                                    </li>
                                </ul>
                            </div>
                            <div class="mb-4">
                                <h5 class="text-lg font-semibold mb-3">SPECIAL OFFERS</h5>
                                <ul class="list list-inline mb-0">
                                    @foreach($special_offers as $special_offer)
                                        <li class="list-item" wire:key="{{ $special_offer->id }}">
                                            <input type="checkbox" wire:model="temp_selected_sale"
                                                   id="{{ $special_offer->slug }}" value="{{ $special_offer->id }}"
                                                   class="w-4 h-4 mr-2">
                                            <span class="menu-link py-1"> {{ $special_offer->name }} </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t">
                            <button wire:click="applyModalFilters" type="button"
                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50"
                                    onclick="closeFiltersModal()">Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shop-list flex-grow-1">
            <div class="mb-3 pb-2 pb-xl-3"></div>
            <div class="flex justify-content-between mb-4 pb-md-2">
                <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                    <a wire:navigate href="/" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                    <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                    <a wire:navigate href="/shop" class="menu-link menu-link_us-s text-uppercase fw-medium">The
                        Shop</a>
                </div>
                <div class="shop-acs flex items-center justify-content-between justify-content-md-end flex-grow-1">
                    <select wire:model.live="sort"
                            class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0"
                            aria-label="Sort Items" name="total-number">
                        <option value="latest">Sort by latest</option>
                        <option value="price">Sort by Price</option>
                    </select>
                </div>
                <div class="shop-filter flex items-center order-0 order-md-3 d-lg-none">
                    <button class="btn-link btn-link_f flex items-center ps-0 js-open-aside" aria-haspopup="dialog"
                            aria-expanded="false" aria-controls="hs-filters-modal" onclick="openFiltersModal()">
                        <svg class="inline-block align-middle me-2" width="14" height="10" viewBox="0 0 14 10"
                             fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_filter"></use>
                        </svg>
                        <span class="text-uppercase fw-medium inline-block align-middle">Filter</span>
                    </button>
                </div>
            </div>

            @if($products->isNotEmpty())
                <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">
                    @foreach($products as $product)
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

                                    <div

                                        class="anim_appear-bottom bottom-0 left-0 flex items-center bg-body relative">

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
                    @endforeach
                </div>
            @else
                <span class="text-center flex justify-center py-10 text-2xl font-semibold text-slate-600 mb-82">
                No products found!
            </span>
            @endif
            <div class="pagination">
                {{ $products->links() }}
            </div>
        </div>

    </section>

<script>
    function openFiltersModal() {
        document.getElementById('hs-filters-modal').classList.remove('hidden');
    }

    function closeFiltersModal() {
        document.getElementById('hs-filters-modal').classList.add('hidden');
    }
</script>

<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "CollectionPage",
      "name": "Products Collection",
      "description": "Explore a wide selection of quality products for children. Filter by category, color, and size to find the perfect fit.",
      "url": "{{ url()->current() }}",
  "image": "{{ asset('storage/assets/site_logo.png') }}",
  "breadcrumb": {
    "@type": "BreadcrumbList",
    "itemListElement": [
      { "@type": "ListItem", "position": 1, "name": "Home", "item": "{{ url('/') }}" },
      { "@type": "ListItem", "position": 2, "name": "Shop", "item": "{{ url('/shop') }}" }
    ]
  },
  "hasPart": [
    @foreach($products as $product)
        {
          "@type": "Product",
          "name": "{{ $product->name }}",
      "image": "{{ asset('storage/' . $product->first_image) }}",
      "description": "{{ $product->meta_description }}",
      "sku": "{{ $product->sku }}",
      "offers": {
        "@type": "Offer",
        "url": "{{ url($product->slug) }}",
        "priceCurrency": "EUR",
        "price": "{{ $product->on_sale ? $product->on_sale_price : $product->price }}",
        "availability": "{{ $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "itemCondition": "https://schema.org/NewCondition"
      },
      "category": "{{ $product->categories->first()->name }}"
    }@if(!$loop->last),@endif
    @endforeach
    ]
  }
</script>
