<div class="mb-14 pb-14">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h1 class="page-title">Order's Details</h1>
        <div class="row">
            <div class="col-lg-2">
                <ul class="account-nav">
                    <li><a wire:navigate href="/my-account" class="menu-link menu-link_us-s">Dashboard</a></li>
                    <li><a wire:navigate href="/my-orders" class="menu-link menu-link_us-s">Orders</a></li>
                    <li><a wire:navigate href="/wishlist" class="menu-link menu-link_us-s">Wishlist</a></li>
                    <li><a wire:navigate href="/logout" class="menu-link menu-link_us-s">Logout</a></li>
                </ul>
            </div>

            <div class="col-lg-10">
                <div class="wg-box mt-5 mb-5">
                    <div class="row">
                        <div class="col-6">
                            <h2>Details</h2>
                        </div>
                        <div class="col-6 text-right">
                            <a wire:navigate class="btn btn-primary btn-addtocart mb-5 " href="/my-orders">Back</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-transaction">
                            <tbody>
                            <tr>
                                <th>Order</th>
                                <th>Subtotal</th>
                                <th>Shipping</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Order Date</th>
                                <th>Order Status</th>
                            </tr>
                            <tr>

                                <td>{{ $order->id }}</td>
                                <td>{{ Number::currency($order->total_price - $order->shipping_cost + $order->discount, 'EUR') }}</td>
                                <td>{{ $order->shipping_cost > 0 ? Number::currency($order->shipping_cost, 'EUR') : "FREE"}}</td>
                                <td>{{ Number::currency($order->discount, 'EUR') }}</td>
                                <td>{{ Number::currency($order->total_price, 'EUR') }}</td>

                                <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                <td colspan="5">
                                    @php
                                        $status = match ($order->status) {
                                            'new' => '<span class="bg-blue-500 py-1 px-3 rounded text-white shadow">New</span>',
                                            'processing' => '<span class="bg-yellow-500 py-1 px-3 rounded text-white shadow">Processing</span>',
                                            'shipped' => '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Shipped</span>',
                                            'delivered' => '<span class="bg-green-700 py-1 px-3 rounded text-white shadow">Delivered</span>',
                                            'cancelled' => '<span class="bg-red-700 py-1 px-3 rounded text-white shadow">Cancelled</span>',
                                            default => '<span class="bg-red-700 py-1 px-3 rounded text-white shadow">Unknown Payment Status</span>',
                                        };
                                    @endphp
                                    {!! $status !!}
                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="wg-box wg-table table-all-user">
                    <div class="row">
                        <div class="col-6">
                            <h2>Items</h2>
                        </div>
                        <div class="col-6 text-right">

                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th class="text-center">Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Options</th>
                                <th class="text-center">Return</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($order_items as $item)
                                <tr wire:key="{{ $item->id }}">
                                    <td class="pname">
                                        <div class="name">
                                            <a wire:navigate href="/products/{{ $item->product['slug'] }}"
                                               target="_blank"
                                               class="body-title-2"><span>{{ $item->product->name }}</span></a>
                                        </div>
                                        <a wire:navigate href="/products/{{ $item->product['slug'] }}" target="_blank">
                                            <div class="image">
                                                <img src="{{ url('storage', $item->product->first_image) }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="image w-3/5 ">
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center">{{ Number::currency($item->unit_price, 'EUR') }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">SHT01245</td>
                                    <td class="text-center">Color</td>
                                    <td class="text-center">No</td>
                                    <td class="text-center">
                                        <a wire:navigate href="/products/{{ $item->product['slug'] }}" target="_blank">
                                            <div class="list-icon-function view-icon">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <use href="#icon_view"/>
                                                </svg>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="wg-box mt-5 mb-5 ml-2">
                            <h2>Shipping Address</h2>
                            <div class="my-account__address-item col-md-6">
                                <div class="my-account__address-item__detail">
                                    <p>{{ $address->full_name }}</p>
                                    <p>{{ $address->address }}</p>
                                    <p>{{ $address->state }}</p>
                                    <p>{{ $address->zip_code }}</p>
                                    <p>{{ $address->phone }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>
