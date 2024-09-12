<div>
    <div>
        <div class="header-mobile sticky top-0 z-50 bg-white">
            <div class="container flex items-center h-full justify-between">
                <!-- Mobile Menu Toggle Button -->
                <button class="hs-collapse-toggle block relative z-50" aria-controls="hs-navbar-example" aria-expanded="false" aria-label="Toggle navigation" data-hs-collapse="#hs-navbar-example">
                    <svg class="nav-icon" width="25" height="18" viewBox="0 0 25 18" xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_nav" />
                    </svg>
                </button>

                <!-- Logo -->
                <div class="logo">
                    <a wire:navigate href="/">
                        <img src="{{ url('/storage/' . $settings?->site_logo ) }}" alt="Little Sailors Malta Logo" class="logo__image block" />
                    </a>
                </div>

                <!-- Cart Icon -->
                <a wire:navigate href="/cart" class="header-tools__item header-tools__cart">
                    <svg class="block" width="20" height="20" viewBox="0 0 20 20" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_cart" />
                    </svg>
                    <span class="cart-amount block position: absolute js-cart-items-count">{{ $total_count }}</span>
                </a>
            </div>

            <!-- Mobile Navigation -->
            <nav id="hs-navbar-example" class="hs-collapse hidden overflow-hidden flex flex-col w-full absolute top-16 left-0 bg-white z-40 transition-all duration-300">
                <div class="container p-6">
                    <form action="#" method="GET" class="mb-3">
                        <div class="relative">
                            <input class="search-field__input w-full border rounded-sm p-2 pr-10" type="text" name="search-keyword" placeholder="Search products" />
                            <button class="absolute right-2 top-1/2 transform -translate-y-1/2" type="submit">
                                <svg class="block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_search" />
                                </svg>
                            </button>
                        </div>
                    </form>

                    <ul class="space-y-4">
                        <li><a href="index.html" class="text-l font-semibold block">Home</a></li>
                        <li><a href="shop.html" class="text-l font-semibold block">Shop</a></li>
                        <li><a href="cart.html" class="text-l font-semibold block">Cart</a></li>
                        <li><a href="about.html" class="text-l font-semibold block">About</a></li>
                        <li><a href="contact.html" class="text-l font-semibold block">Contact</a></li>
                    </ul>
                </div>

                <div class="border-t mt-auto pb-2">
                    <div class="container mt-4 mb-2 pb-1 flex items-center">
                        <svg class="inline-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_user" />
                        </svg>
                        <span class="inline-block ml-2 text-uppercase text-sm font-medium">My Account</span>
                    </div>

                    <ul class="container flex flex-wrap mb-0">
                        <li class="mr-4">
                            <a href="#" class="block">
                                <svg class="svg-icon svg-icon_facebook" width="9" height="15" viewBox="0 0 9 15" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_facebook" />
                                </svg>
                            </a>
                        </li>
                        <li class="mr-4">
                            <a href="#" class="block">
                                <svg class="svg-icon svg-icon_twitter" width="14" height="13" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_twitter" />
                                </svg>
                            </a>
                        </li>
                        <li class="mr-4">
                            <a href="#" class="block">
                                <svg class="svg-icon svg-icon_instagram" width="14" height="13" viewBox="0 0 14 13" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_instagram" />
                                </svg>
                            </a>
                        </li>
                        <li class="mr-4">
                            <a href="#" class="block">
                                <svg class="svg-icon svg-icon_youtube" width="16" height="11" viewBox="0 0 16 11" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.0117 1.8584C14.8477 1.20215 14.3281 0.682617 13.6992 0.518555C12.5234 0.19043 7.875 0.19043 7.875 0.19043C7.875 0.19043 3.19922 0.19043 2.02344 0.518555C1.39453 0.682617 0.875 1.20215 0.710938 1.8584C0.382812 3.00684 0.382812 5.46777 0.382812 5.46777C0.382812 5.46777 0.382812 7.90137 0.710938 9.07715C0.875 9.7334 1.39453 10.2256 2.02344 10.3896C3.19922 10.6904 7.875 10.6904 7.875 10.6904C7.875 10.6904 12.5234 10.6904 13.6992 10.3896C14.3281 10.2256 14.8477 9.7334 15.0117 9.07715C15.3398 7.90137 15.3398 5.46777 15.3398 5.46777C15.3398 5.46777 15.3398 3.00684 15.0117 1.8584ZM6.34375 7.68262V3.25293L10.2266 5.46777L6.34375 7.68262Z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="block">
                                <svg class="svg-icon svg-icon_pinterest" width="14" height="15" viewBox="0 0 14 15" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_pinterest" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>


    <header id="header" class="header header-fullwidth header-transparent-bg">
        <div class="container">
            <div class="header-desk header-desk_type_1">
                <div class="logo">
                    <a href="/">
                        <img src="{{ url('/storage/' . $settings?->site_logo) }}" alt="Little Sailors Malta Logo" class="logo__image block" />
                    </a>
                </div>

                <nav class="navigation">
                    <ul class="navigation__list list-unstyled flex">
                        <li class="navigation__item">
                            <a wire:navigate href="/" class="navigation__link">Home</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate href="/products" class="navigation__link">Shop</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate  href="/products?on_sale=true" class="navigation__link">Sale</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate href="/about" class="navigation__link">About</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate  href="/blog" class="navigation__link">Blog</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate href="/contact" class="navigation__link">Contact</a>
                        </li>
                    </ul>
                </nav>

                <div class="header-tools flex items-center">
                    <div class="header-tools__item relative">
                        <div id="search-icon" class="cursor-pointer">
                            <a wire:navigate href="/search">
                                <svg class="block w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_search" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="header-tools__item hover-container">
                        <a wire:navigate href="{{ Auth::check() ? '/my-account' : '/login' }}" class="header-tools__item">
                            <svg class="block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_user" />
                            </svg>
                        </a>
                    </div>

                    <a wire:navigate href="/wishlist" class="header-tools__item">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_heart" />
                        </svg>
                    </a>

                    <a wire:navigate href="/cart" class="header-tools__item header-tools__cart">
                        <svg class="block" width="20" height="20" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_cart" />
                        </svg>
                        <span class="cart-amount block position: absolute js-cart-items-count">{{ $total_count }}</span>
                    </a>
                </div>
            </div>
        </div>
    </header>
</div>


