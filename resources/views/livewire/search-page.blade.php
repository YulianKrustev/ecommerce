 <section class="products-grid shop-checkout container mb-4 pb-4 pt-12">
        <div class="mw-930 ">
            <h1 class="page-title">Search</h1>
        </div>

        <div class="row mb-14">

            <div class="mb-10">
                <div class="relative border-3 border-orange-600 rounded-lg">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-6 h-6 text-black" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z">
                            </path>
                        </svg>
                    </div>

                    <form method="GET">
                        <input wire:model.live="search"
                               type="search" name="search" placeholder="Search"
                               class="p-6 pl-12 text-black text-lg w-full border border-orange-600 rounded-lg"> <!-- Ensure border and rounded class are here -->
                    </form>
                </div>
            </div>


        @forelse($results as $product)
                <div wire:key="{{ $product->id }}" class="w-full sm:w-1/2 md:w-1/3 lg:w-1/4 pt-10">
                    <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
                        <div class="pc__img-wrapper">
                            <a wire:navigate href="/{{ $product->slug }}">
                                <img loading="lazy" src="{{ asset('storage/' . $product->first_image) }}"
                                     width="330" height="400"
                                     alt="{{ $product->name }}" class="pc__img">
                            </a>
                        </div>

                        <div class="pc__info relative">
                            <h2 class="pc__title">
                                <a wire:navigate
                                   href="/{{ $product->slug }}">{{ $product->name }}</a>
                            </h2>
                            <div class="product-card__price flex items-center">
                            <span
                                class="money price text-secondary">{{ Number::currency($product->price, "EUR") }}</span>
                            </div>

                        </div>
                    </div>
                </div>

            @empty
                <span class="text-center py-10 text-2xl font-semibold text-slate-600 mb-82">
                Sorry, no products found that match your search criteria!
            </span>
            @endforelse
        </div>
        <div class="pagination">
            {{ $results->links() }}
        </div>
    </section>
