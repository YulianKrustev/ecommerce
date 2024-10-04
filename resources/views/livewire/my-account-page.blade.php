<div class="pb-14 mb-14">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container mx-auto">
        <h1 class="page-title">My Account</h1>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <div class="lg:col-span-3 col-span-1">
                <ul class="account-nav">
                    <li><a wire:navigate href="/my-account" class="menu-link menu-link_us-s">Dashboard</a></li>
                    <li><a wire:navigate href="/my-orders" class="menu-link menu-link_us-s">Orders</a></li>
                    <li><a wire:navigate href="/wishlist" class="menu-link menu-link_us-s">Wishlist</a></li>
                    <li><a wire:navigate href="/logout" class="menu-link menu-link_us-s">Logout</a></li>
                </ul>
            </div>
            <div class="lg:col-span-9 col-span-1">
                <div class="page-content my-account__dashboard">
                    <p>Hello <strong style="color: rgb(255 127 80)">{{ Auth::user()->name }}</strong></p>
                    <p>
                        From your account dashboard, you can view your
                        <a class="underline-link" href="account_orders.html">recent orders</a>,
                        manage your
                        <a class="underline-link" href="account_edit_address.html">shipping addresses</a>,
                        and
                        <a class="underline-link" href="account_edit.html">edit your password and account details</a>.
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
