<footer>
        <div class="container mx-auto py-8">
            <div class=" xl:pl-28 lg:pl-20 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6" id="footer">
                <div class="mb-4 md:mb-0">
                    <h6 class="font-semibold uppercase">Company</h6>
                    <ul class="space-y-2 mt-2 pl-8">
                        <li><a wire:navigate href="/about">About Us</a></li>
                        <li><a wire:navigate href="/blog">Blog</a></li>
                        <li><a wire:navigate href="/contact">Contact Us</a></li>
                        <li><a wire:navigate href="/privacy-policy">Privacy Policy</a></li>
                        <li><a wire:navigate href="/terms-and-conditions">Terms & Conditions</a></li>
                    </ul>
                </div>

                <div class="mb-4 md:mb-0">
                    <h6 class="font-semibold uppercase">Shop</h6>
                    <ul class="space-y-2 mt-2 pl-8">
                        @foreach($categories as $category)
                            <li><a wire:navigate
                                   href="/shop?selected_categories[0]={{ $category->id }}">{{ $category->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="mb-4 md:mb-0">
                    <h6 class="font-semibold uppercase">Help</h6>
                    <ul class="space-y-2 mt-2 pl-8">
                        <li><a wire:navigate href="/size-guide">Size Guide</a></li>
                        <li><a wire:navigate href="my-account">My Account</a></li>
                        <li><a wire:navigate href="/return-policy">Return Policy</a></li>
                        <li><a wire:navigate href="/gift-card">Gift Card</a></li>
                        <li><a wire:navigate href="/payments">Payments</a></li>
                    </ul>
                </div>

                <div class="lg:col-span-2 mb-4 md:mb-0 pr-10 sm:pr-0" id="logo-social-footer">
                    <div class=" flex justify-start md:justify-center sm:justify-start">
                        <a wire:navigate href="/">
                            <img loading="lazy" src="{{ url('storage/assets/LS logo BW.png') }}" alt="Little Sailors Malta Logo"
                                 class=" w-8/12  lg:w-5/12  md:w-5/6 sm:w-4/6  mx-auto md:mx-0"/>
                        </a>
                    </div>
                    <div class="mt-2 flex justify-center md:justify-center">
                        <a href="#" class="footer__social-link d-block ps-0 ">
                            <svg class="flex-shrink-0 w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                 fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4" id="footer-bottom">
            <div class="container mx-auto flex flex-col md:flex-row items-center justify-between text-base font-medium">
                <span class="text-center">© {{ date('Y') }} {{ $settings?->site_name }}. All rights reserved.</span>
                <div class="flex space-x-2">
                    <img src="{{ url('storage/stripe.png') }}" alt="Stripe Logo" class="h-24 mx-auto md:mx-0"/>
                </div>
            </div>
        </div>

    <a id="scrollTop" href="#top"></a>
</footer>
