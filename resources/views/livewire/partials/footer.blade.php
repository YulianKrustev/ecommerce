<footer class="w-full">
    <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 lg:pt-20 mx-auto ">
        <!-- Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 pt-4" id="footer-top">
            <div class="">
                <a href="/" aria-label="Site Logo" class="block">
                    <img src="{{ url('storage/assets/LS logo BW.png') }}" alt="Site Logo" class="h-40 mb-4">
                </a>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold">Product</h4>
                <div class="mt-3 grid space-y-3">
                    <p><a class="inline-flex gap-x-2 " href="/categories">Categories</a></p>
                    <p><a class="inline-flex gap-x-2 " href="/products">All Products</a></p>
                    <p><a class="inline-flex gap-x-2 " href="/products">Featured Products</a></p>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-1">
                <h4 class="font-semibold text-black">Company</h4>

                <div class="mt-3 grid space-y-3">
                    <p><a class="inline-flex gap-x-2 " href="#">About us</a></p>
                    <p><a class="inline-flex gap-x-2 " href="#">Blog</a></p>

                    <p><a class="inline-flex gap-x-2" href="#">Customers</a></p>
                </div>
            </div>
            <!-- End Col -->

            <div class="col-span-2">
                <h4 class="font-semibold ">Stay up to date</h4>

                <form>

                    <div class="mt-4 flex flex-col items-center gap-2 sm:flex-row sm:gap-3 bg-white rounded-lg p-2 border border-black ">
                        <div class="w-full">
                            <input type="text" id="hero-input" name="hero-input" class="py-3 px-4 block w-full border-transparent rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-transparent dark:text-gray-400 dark:focus:ring-gray-600" placeholder="Enter your email">
                        </div>
                        <a class="w-full sm:w-auto whitespace-nowrap p-3 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg disabled:pointer-events-none" id="subscribe-button" href="#">
                            Subscribe
                        </a>
                    </div>

                </form>
            </div>
            <!-- End Col -->
        </div>
        <!-- End Grid -->


    </div>
    <div class="mt-4 sm:mt-12 grid gap-y-2 sm:gap-y-0 sm:flex sm:justify-center sm:items-center text-center" id="footer-bottom">
        <div class="sm:mr-4">
            <p class="text-md font-semibold text-black">© 2024 {{ $settings->site_name }}. All rights reserved.</p>
        </div>
        <!-- End Col -->

        <!-- Social Brands -->
        <div class="sm:ml-4">
            <a class="w-10 h-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent text-black hover:bg-white/10 disabled:opacity-50 disabled:pointer-events-none focus:outline-none focus:ring-1 focus:ring-gray-600" href="#">
                <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                </svg>
            </a>
        </div>
        <!-- End Social Brands -->
    </div>

</footer>
