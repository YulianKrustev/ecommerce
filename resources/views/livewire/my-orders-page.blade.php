@push('title')
    My Orders | {{ config('app.name') }}
@endpush
@push('meta')
    <meta name="robots" content="noindex, nofollow">
@endpush
<div class="pb-14 mb-14">
    <div class="mb-4 pb-4"></div>
    <section class="my-account container">
        <h2 class="page-title">Orders</h2>
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
                <div class=" wg-table table-all-user">
                    <div class="table-responsive">
                        <table class=" table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="text-center">Order</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Order Status</th>
                                <th class="text-center">Order Date</th>

                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($orders as $order)
                                @php
                                    $status = match ($order->status) {
                                        'new' => '<span class=" py-1 px-3 rounded text-black shadow-xl">New</span>',
                                        'processing' => '<span class="bg-yellow-500 py-1 px-3 rounded text-white shadow">Processing</span>',
                                        'shipped' => '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Shipped</span>',
                                        'delivered' => '<span class="bg-green-600 py-1 px-3 rounded text-white shadow">Delivered</span>',
                                        'cancelled' => '<span style="background-color: rgb(255 127 80)" class="py-1 px-3 rounded text-white shadow">Cancelled</span>',
                                        default => '<span class="bg-red-700 py-1 px-3 rounded text-white shadow">Unknown Payment Status</span>',
                                    };

//                                     $payment_status = match ($order->payment_status) {
//                                        'paid' => '<span class="bg-green-500 py-1 px-3 rounded text-white shadow">Paid</span>',
//                                        'failed' => '<span class="bg-red-600 py-1 px-3 rounded text-white shadow">Failed</span>',
//                                        'pending' => '<span class="py-1 px-3 rounded text-black shadow-xl">Pending</span>',
//                                        default => '<span class="bg-red-700 py-1 px-3 rounded text-white shadow">Unknown Payment Status</span>',
//                                    };
                                @endphp

                                <tr wire:key='{{ $order->id }}'>
                                    <td class="text-center"> {{ $order->id }}</td>
                                    <td class="text-center">{{ $order->address->full_name }}</td>
                                    <td class="text-center"> {{ Number::currency($order->total_price, 'EUR') }}</td>

                                    <td class="text-center">
                                        <span class="">{!! $status !!}</span>
                                    </td>
                                    {{--                                    <td class="text-center">--}}
                                    {{--                                        <span class="">{!! $payment_status !!}</span>--}}
                                    {{--                                    </td>--}}
                                    <td class="text-center">{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        <a wire:navigate href="/my-orders/{{ $order->id }}">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <use href="#icon_view"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-2xl font-semibold text-slate-600">
                                        You have no orders in your account.
                                    </td>
                                </tr>
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">

                </div>
            </div>
            {{ $orders->links() }}
        </div>
    </section>
</div>
