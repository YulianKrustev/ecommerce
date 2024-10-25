
<div>
    <marquee bgcolor="#ffe79a" >Welcome to Little Sailors Malta! Enjoy our special offers and discounts! 🚢 Free shipping on orders over €50! 🌞 Shop Now and Save!</marquee>
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
            <nav id="hs-navbar-example" class="hs-collapse hidden overflow-hidden flex flex-col w-full absolute top-16 left-0 bg-white z-40 transition-all duration-300 min-h-svh">
                <div class="container p-6">
                    <form action="#" method="GET" class="search-field position-relative mt-2 mb-2">
                        <div class="position-relative">
                            <input id="searchInputMobile" class="search-field__input search-popup__input w-100 fw-medium inline"
                                   type="text" name="search-keyword" placeholder="Search products">

                            <a wire:navigate id="searchLinkMobile"
                               class="absolute right-2 top-1/2 transform -translate-y-1/2"
                               href="/shop">
                                <svg class="block w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_search" />
                                </svg>
                            </a>
                            <button class="btn-icon btn-close-lg search-popup__reset pb-0 me-2" type="reset"></button>
                        </div>

                        <div class="position-absolute start-0 top-100 m-0 w-100">
                            <div class="search-result"></div>
                        </div>
                    </form>
                </div>

                <div class="container">
                    <ul class="space-y-4">
                        <li><a wire:navigate href="/" class="text-l font-semibold">HOME</a></li>
                        <li><a wire:navigate href="/shop" class="text-l font-semibold">SHOP</a></li>
                        <li><a wire:navigate href="/cart" class="text-l font-semibold">CART</a></li>
                        <li><a wire:navigate href="/about" class="text-l font-semibold">ABOUT</a></li>
                        <li><a wire:navigate href="/contact" class="text-l font-semibold">CONTACT</a></li>
                    </ul>
                </div>

                <div class="border-t mt-2 ">
                    <div class="container mt-4 mb-2 pb-1 flex items-center">
                        <a wire:navigate href="/my-account">
                            <svg class="inline-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <use href="#icon_user" />
                            </svg>
                            <span class="inline-block ml-2 text-uppercase text-sm font-medium">My Account</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <header id="header" class="header header-fullwidth header-transparent-bg mb-2">
        <div class="container">
            <div class="header-desk header-desk_type_1">
                <div class="logo">
                    <a wire:navigate href="/">
                        <img src="{{ url('/storage/' . $settings?->site_logo) }}" alt="Little Sailors Malta Logo" class="logo__image block" />
                    </a>
                </div>

                <nav class="navigation">
                    <ul class="navigation__list list-unstyled flex">
                        <li class="navigation__item">
                            <a wire:navigate href="/" class="navigation__link">Home</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate href="/shop" class="navigation__link">Shop</a>
                        </li>
                        <li class="navigation__item">
                            <a wire:navigate  href="/shop?on_sale=true" class="navigation__link">Sale</a>
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
                            <button class="mt-2" type="button" onclick="toggleSearchModal()">
                                <svg class="block w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_search" />
                                </svg>
                            </button>

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
        <div id="search-nav" class="hidden absolute" style="width: 95%" role="dialog" tabindex="-1">
            <div class="search-popup ">
                <div class="search-field container">
                    <p class="text-uppercase text-secondary fw-medium mb-4">What are you looking for?</p>
                    <div class="relative w-full">

                            <input id="searchInputDesktop" class="search-field__input search-popup__input w-100 fw-medium inline"
                                   type="text" name="search-keyword" placeholder="Search products">

                            <a wire:navigate id="searchLinkDesktop"
                               class="absolute right-2 top-1/2 transform -translate-y-1/2"
                               href="/shop">
                                <svg class="block w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <use href="#icon_search" />
                                </svg>
                            </a>

                    </div>

                    <div class="search-popup__results">
                        <div class="sub-menu search-suggestion">
                            <h6 class="sub-menu__title fs-base">Quicklinks</h6>
                            <ul class="sub-menu__list list-unstyled">
                                @foreach($categories as $category)
                                    <li class="sub-menu__item"><a wire:navigate href="/shop?selected_categories[0]={{ $category->id }}" class="menu-link menu-link_us-s">{{ $category->name }}</a></li>

                                @endforeach
                            </ul>
                        </div>

                        <div class="search-result row row-cols-5"></div>
                    </div>
                </div>
            </div>

        </div>

    </header>
</div>

<script>
    function toggleSearchModal() {
        var modal = document.getElementById('search-nav');
        if (modal.classList.contains('hidden')) {
            modal.classList.remove('hidden');
        } else {
            modal.classList.add('hidden');
        }
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('search-nav');
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }
</script>

<script>
    searchInputDesktop.addEventListener('input', function () {
        searchLinkDesktop.href = `/shop?search=${encodeURIComponent(searchInputDesktop.value)}`;
    });

    searchInputDesktop.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            let searchInputDesktop = document.getElementById('searchInputDesktop');
            let searchLinkDesktop = document.getElementById('searchLinkDesktop');

            if (searchInputDesktop && searchInputDesktop.value.trim()) {
                event.preventDefault();
                searchLinkDesktop.setAttribute('href', `/shop?search=${encodeURIComponent(searchInputDesktop.value)}`);
                searchLinkDesktop.click();
            }
        }
    });
</script>

<script>
    searchInputMobile.addEventListener('input', function () {
        searchLinkMobile.href = `/shop?search=${encodeURIComponent(searchInputMobile.value)}`;
    });

    searchInputMobile.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            let searchInputMobile = document.getElementById('searchInputMobile');
            let searchLinkMobile = document.getElementById('searchLinkMobile');

            if (searchInputMobile && searchInputMobile.value.trim()) {
                event.preventDefault();
                searchLinkMobile.setAttribute('href', `/shop?search=${encodeURIComponent(searchInputMobile.value)}`);
                searchLinkMobile.click();
            }
        }
    });
</script>
