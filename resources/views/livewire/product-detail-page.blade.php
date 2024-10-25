@push('meta')
    <!-- Meta Description -->
    <meta name="description" content="{{ $product->meta_description ?? 'Explore quality and comfort for your child with this unique product.' }}">

    <!-- Meta Keywords -->
    <meta name="keywords" content="{{ $product->meta_keywords ? implode(', ', $product->meta_keywords) : 'baby clothing, children clothing, quality kids clothing' }}">

    <!-- Open Graph Tags for Social Sharing -->
    <meta property="og:title" content="{{ $product->name }} | {{ config('app.name') }}">
    <meta property="og:description" content="{{ $product->meta_description ?? 'Explore quality and comfort for your child with this unique product.' }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ url($product->slug) }}">
    <meta property="og:image" content="{{ asset('storage/' . $product->first_image) }}">
    <meta property="product:price:amount" content="{{ $product->on_sale ? $product->on_sale_price : $product->price }}">
    <meta property="product:price:currency" content="EUR">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url($product->slug) }}">
@endpush

@push('title')
    {{ $product->name }} | {{ config('app.name') }}
@endpush
<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="product-single container">
        <div class="row">
            <div class="w-1/2 mb-8 md:w-1/2 md:mb-0" wire:ignore>
                <div class="sticky top-0 z-50 overflow-hidden ">
                    <div class="relative mb-6 lg:mb-10 lg:h-2/4 ">
                        <button onclick="plusDivs(-1)" type="button" class="absolute left-2 top-1/2 p-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-full text-gray-800 hover:bg-white shadow-lg disabled:opacity-50 disabled:pointer-events-none">
                            <div class="swiper-button-prev"><svg width="9" height="11" viewBox="0 0 7 11"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_prev_sm" />
                                </svg></div>
                        </button>
                        @foreach(array_reverse ( $product->images) as $key => $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" class="object-cover w-full lg:h-full mySlides">
                            @if($product->on_sale)
                                <div class="pc-labels position-absolute top-0 start-0 w-100 d-flex justify-content-between">
                                    <div class="pc-labels__right ms-auto">
                                        <span class="pc-label pc-label_sale d-block text-white">-{{ (int)$product->specialOffers[0]->discount_percentage }}%</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        <button onclick="plusDivs(1)" type="button" class="absolute right-2 top-1/2 p-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-full hover:bg-white text-gray-800 shadow-lg disabled:opacity-50 disabled:pointer-events-none">
                            <div class="swiper-button-next"><svg width="9" height="11" viewBox="0 0 7 11"
                                                                 xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_next_sm" />
                                </svg></div>

                        </button>

                    </div>
                    <div class="flex flex-wrap">
                        @foreach($product->images as $key => $image)
                            <div class="w-1/2 p-2 sm:w-1/4" style="order: {{ count($product->images) - $key }}">
                                <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->name }}" onclick="currentDiv({{ count($product->images) - $key }})"
                                     class="object-cover w-full lg:h-20 cursor-pointer hover:border hover:border-orange-500">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="d-flex justify-content-between mb-4 pb-md-2">
                    <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                        <a wire:navigate href="/" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                        <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                        <a wire:navigate href="/shop" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
                    </div><!-- /.breadcrumb -->

                </div>
                <h1 class="product-single__name">{{ $product->name }}</h1>
                <div class="product-single__price">
                    @if($product->on_sale)
                        <span class="current-price">{{ Number::currency($product->on_sale_price, "EUR") }}</span>
                        <span class="current-price line-through text-gray-600">{{ Number::currency($product->price, "EUR") }}</span>
                    @else
                        <span class="current-price">{{ Number::currency($product->price, "EUR") }}</span>
                    @endif
                </div>


                <div name="addtocart-form">
                    <span class="text-sm">SELECT COLOR:</span>
                    <div class="flex space-x-2 mb-4">
                                <a wire:navigate href="{{ url('/' . $product->slug) }}" class="mt-2 size-button flex rounded-full items-center justify-center w-8 h-8 border border-gray-300 transition-colors duration-200 focus:outline-none" style="background-color: {{ $product->color->hex_code }}"></a>

                            @if(!empty($product->relatedProducts))

                                @foreach($product->relatedProducts as $related_product)
                                    @if($product->id != $related_product->id)
                                            <a wire:navigate href="{{ url('/' . $related_product->slug) }}" class="mt-2 size-button rounded-full flex items-center justify-center w-8 h-8 border border-gray-300 transition-colors duration-200 focus:outline-none" style="background-color: {{ $related_product->color->hex_code }}"></a>
                                    @endif

                                @endforeach
                            @endif

                    </div>

                    <span class="text-sm">SELECT SIZE:</span>
                    <div class="flex space-x-2 mb-4">

                        @foreach($product->sizes as $productSize)
                            <button
                                type="button"
                                class="swatch-size btn btn-sm btn-outline-light mb-3 me-1 {{ $productSize->quantity == 0 && $product->in_stock ? 'disabled' : '' }} {{ $productSize->size->id == $selectedSize ? 'btn-primary' : '' }}"
                                wire:click="selectSize({{ $productSize->size->id }})"
                            >
                                {{ $productSize->size->name }}
                            </button>
                        @endforeach

                    </div>

                    @error('selectedSize')
                    <div class="text-red-500 text-sm mb-4 mt-0">{{ $message }}</div>
                    @enderror

                    <div class="product-single__addtocart">
                        <div class="py-3 px-3 inline-block bg-white border" data-hs-input-number='{ "min": 1 }'>
                            <div class="flex items-center gap-x-1.5">
                                <button wire:click="decreaseQty()" type="button" class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium  bg-white text-gray-800 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none " tabindex="-1" aria-label="Decrease" data-hs-input-number-decrement="{}">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                    </svg>
                                </button>
                                <input readonly  class="p-0 w-6 bg-transparent border-0 text-gray-800 text-center focus:ring-0 [&::-webkit-inner-spin-button]:appearance-none [&::-webkit-outer-spin-button]:appearance-none" style="-moz-appearance: textfield;" type="number" aria-roledescription="Number field" value="{{ $quantity }}" data-hs-input-number-input="">
                                <button wire:click="increaseQty()" type="button" class="size-6 inline-flex justify-center items-center gap-x-2 text-sm font-medium bg-white text-gray-800 hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none" tabindex="-1" aria-label="Increase" data-hs-input-number-increment="">
                                    <svg class="shrink-0 size-3.5" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"></path>
                                        <path d="M12 5v14"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <button
                            @if($product->in_stock)
                                wire:click="addToCart({{ $product->id }}, {{ $productSize->size->id }})"
                            @else
                                wire:click="notifyMeWhenAvailable({{ $product->id }})"
                            @endif
                            class="btn btn-primary btn-addtocart js-open-aside"
                                data-aside="cartDrawer"><span wire:loading.remove wire:target="addToCart({{ $product->id }})">{{ $product->in_stock ? "Add to cart" : "Watch availability" }}</span>
                            <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span></button>
                        </button>
                    </div>

                </div>
                <div class="product-single__addtolinks">
                    @if($wishlistItems->contains('product_id', $product->id) )
                        <a wire:click.prevent="removeFromWishlist({{ $product->id }})" href="#" class="menu-link menu-link_us-s add-to-wishlist">
                            <svg width="16" height="16" viewBox="0 0 20 20"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_heart_fill"/>
                            </svg>
                            <span>Remove from Wishlist</span></a>
                    @else
                        <a wire:click.prevent="addToWishlist({{ $product->id }})" href="#" class="menu-link menu-link_us-s add-to-wishlist">
                            <svg width="16" height="16" viewBox="0 0 20 20"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_heart"/>
                            </svg>
                            <span>Add to Wishlist</span></a>
                    @endif

                </div>
                <div class="product-single__meta-info">
                    <div class="meta-item">
                        <label>SKU:</label>
                        <span>{{ $product->sku }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Categories:</label>
                        @foreach($product->categories as $key => $category)

                            <a href="/shop?selected_categories[0]={{ $category->id }}"><span>{{ $key == 0 ? '' : "|" }} {{ $category->name }}</span></a>
                        @endforeach

                    </div>
                    <div class="meta-item">
                        <label>Tags:</label>
                        @foreach($product->tags as  $key => $tag)
                            <span>{{ $key == 0 ? '' : "|" }}{{ $tag->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="product-single__details-tab">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab"
                       href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Description</a>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab-description" role="tabpanel"
                     aria-labelledby="tab-description-tab">
                    <div class="product-single__description">
                        <h3 class="block-title mb-4">Sed do eiusmod tempor incididunt ut labore</h3>
                        <p class="content">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                            tempor
                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                            exercitation ullamco
                            laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in
                            voluptate
                            velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                            proident, sunt
                            in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis
                            iste natus
                            error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae
                            ab illo
                            inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>
                        <div class="row">
                            <div class="col-lg-6">
                                <h3 class="block-title">Why choose product?</h3>
                                <ul class="list text-list">
                                    <li>Creat by cotton fibric with soft and smooth</li>
                                    <li>Simple, Configurable (e.g. size, color, etc.), bundled</li>
                                    <li>Downloadable/Digital Products, Virtual Products</li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <h3 class="block-title">Sample Number List</h3>
                                <ol class="list text-list">
                                    <li>Create Store-specific attrittbutes on the fly</li>
                                    <li>Simple, Configurable (e.g. size, color, etc.), bundled</li>
                                    <li>Downloadable/Digital Products, Virtual Products</li>
                                </ol>
                            </div>
                        </div>
                        <h3 class="block-title mb-0">Lining</h3>
                        <p class="content">100% Polyester, Main: 100% Polyester.</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-additional-info" role="tabpanel"
                     aria-labelledby="tab-additional-info-tab">
                    <div class="product-single__addtional-info">
                        <div class="item">
                            <label class="h6">Weight</label>
                            <span>1.25 kg</span>
                        </div>
                        <div class="item">
                            <label class="h6">Dimensions</label>
                            <span>90 x 60 x 90 cm</span>
                        </div>
                        <div class="item">
                            <label class="h6">Size</label>
                            <span>XS, S, M, L, XL</span>
                        </div>
                        <div class="item">
                            <label class="h6">Color</label>
                            <span>Black, Orange, White</span>
                        </div>
                        <div class="item">
                            <label class="h6">Storage</label>
                            <span>Relaxed fit shirt-style dress with a rugged</span>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="tab-reviews" role="tabpanel" aria-labelledby="tab-reviews-tab">
                    <h2 class="product-single__reviews-title">Reviews</h2>
                    <div class="product-single__reviews-list">
                        <div class="product-single__reviews-item">
                            <div class="customer-avatar">
                                <img loading="lazy" src="assets/images/avatar.jpg" alt=""/>
                            </div>
                            <div class="customer-review">
                                <div class="customer-name">
                                    <h6>Janice Miller</h6>
                                    <div class="reviews-group d-flex">
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="review-date">April 06, 2023</div>
                                <div class="review-text">
                                    <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo
                                        minus id quod
                                        maxime placeat facere possimus, omnis voluptas assumenda est…</p>
                                </div>
                            </div>
                        </div>
                        <div class="product-single__reviews-item">
                            <div class="customer-avatar">
                                <img loading="lazy" src="assets/images/avatar.jpg" alt=""/>
                            </div>
                            <div class="customer-review">
                                <div class="customer-name">
                                    <h6>Benjam Porter</h6>
                                    <div class="reviews-group d-flex">
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                                            <use href="#icon_star"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="review-date">April 06, 2023</div>
                                <div class="review-text">
                                    <p>Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo
                                        minus id quod
                                        maxime placeat facere possimus, omnis voluptas assumenda est…</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-single__review-form">
                        <form name="customer-review-form">
                            <h5>Be the first to review “Message Cotton T-Shirt”</h5>
                            <p>Your email address will not be published. Required fields are marked *</p>
                            <div class="select-star-rating">
                                <label>Your rating *</label>
                                <span class="star-rating">
                    <svg class="star-rating__star-icon" width="12" height="12" fill="#ccc" viewBox="0 0 12 12"
                         xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z"/>
                    </svg>
                    <svg class="star-rating__star-icon" width="12" height="12" fill="#ccc" viewBox="0 0 12 12"
                         xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z"/>
                    </svg>
                    <svg class="star-rating__star-icon" width="12" height="12" fill="#ccc" viewBox="0 0 12 12"
                         xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z"/>
                    </svg>
                    <svg class="star-rating__star-icon" width="12" height="12" fill="#ccc" viewBox="0 0 12 12"
                         xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z"/>
                    </svg>
                    <svg class="star-rating__star-icon" width="12" height="12" fill="#ccc" viewBox="0 0 12 12"
                         xmlns="http://www.w3.org/2000/svg">
                      <path
                          d="M11.1429 5.04687C11.1429 4.84598 10.9286 4.76562 10.7679 4.73884L7.40625 4.25L5.89955 1.20312C5.83929 1.07589 5.72545 0.928571 5.57143 0.928571C5.41741 0.928571 5.30357 1.07589 5.2433 1.20312L3.73661 4.25L0.375 4.73884C0.207589 4.76562 0 4.84598 0 5.04687C0 5.16741 0.0870536 5.28125 0.167411 5.3683L2.60491 7.73884L2.02902 11.0871C2.02232 11.1339 2.01563 11.1741 2.01563 11.221C2.01563 11.3951 2.10268 11.5558 2.29688 11.5558C2.39063 11.5558 2.47768 11.5223 2.56473 11.4754L5.57143 9.89509L8.57813 11.4754C8.65848 11.5223 8.75223 11.5558 8.84598 11.5558C9.04018 11.5558 9.12054 11.3951 9.12054 11.221C9.12054 11.1741 9.12054 11.1339 9.11384 11.0871L8.53795 7.73884L10.9688 5.3683C11.0558 5.28125 11.1429 5.16741 11.1429 5.04687Z"/>
                    </svg>
                  </span>
                                <input type="hidden" id="form-input-rating" value=""/>
                            </div>
                            <div class="mb-4">
                  <textarea id="form-input-review" class="form-control form-control_gray" placeholder="Your Review"
                            cols="30" rows="8"></textarea>
                            </div>
                            <div class="form-label-fixed mb-4">
                                <label for="form-input-name" class="form-label">Name *</label>
                                <input id="form-input-name" class="form-control form-control-md form-control_gray">
                            </div>
                            <div class="form-label-fixed mb-4">
                                <label for="form-input-email" class="form-label">Email address *</label>
                                <input id="form-input-email" class="form-control form-control-md form-control_gray">
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input form-check-input_fill" type="checkbox" value=""
                                       id="remember_checkbox">
                                <label class="form-check-label" for="remember_checkbox">
                                    Save my name, email, and website in this browser for the next time I comment.
                                </label>
                            </div>
                            <div class="form-action">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    var slideIndex = 1;
    showDivs(slideIndex);

    function plusDivs(n) {
        showDivs(slideIndex += n);
    }

    function currentDiv(n) {
        showDivs(slideIndex = n);
    }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        if (n > x.length) { slideIndex = 1 }
        if (n < 1) { slideIndex = x.length }
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
        x[slideIndex - 1].style.display = "block";
    }
</script>

<!-- Structured Data Schema (JSON-LD) -->
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Product",
      "name": "{{ $product->name }}",
      "image": [
        "{{ asset('storage/' . $product->first_image) }}"
      ],
      "description": "{{ $product->meta_description ?? 'Explore quality and comfort for your child with this unique product.' }}",
      "sku": "{{ $product->sku }}",
      "brand": {
        "@type": "Brand",
        "name": "{{ config('app.name') }}"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ url($product->slug) }}",
        "priceCurrency": "EUR",
        "price": "{{ $product->on_sale ? $product->on_sale_price : $product->price }}",
        "itemCondition": "https://schema.org/NewCondition",
        "availability": "{{ $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
        "seller": {
          "@type": "Organization",
          "name": "{{ config('app.name') }}"
        }
      },
      "additionalType": [
        "https://schema.org/Product",
        "https://schema.org/Thing"
      ],
      "breadcrumb": {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {
            "@type": "ListItem",
            "position": 1,
            "name": "Home",
            "item": "{{ config('app.url') }}"
          },
          {
            "@type": "ListItem",
            "position": 2,
            "name": "Shop",
            "item": "{{ url('/shop') }}"
          },
          {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $product->name }}",
            "item": "{{ url($product->slug) }}"
          }
        ]
      },
      "sameAs": [
        "{{ config('app.social.facebook') }}",
        "{{ config('app.social.instagram') }}"
      ]
    }
</script>

