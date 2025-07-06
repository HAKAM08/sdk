@extends('layouts.app')

@section('title', 'Order #' . $order->id)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Sidebar -->
        <div class="md:w-1/4">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-4">My Account</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('user.dashboard') }}" class="block py-2 px-4 hover:bg-gray-100 rounded-md transition">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.orders') }}" class="block py-2 px-4 bg-blue-100 text-blue-700 rounded-md font-medium">
                            My Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile') }}" class="block py-2 px-4 hover:bg-gray-100 rounded-md transition">
                            Profile
                        </a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left py-2 px-4 hover:bg-gray-100 rounded-md transition">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="md:w-3/4">
            <!-- Order Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Order #{{ $order->id }}</h1>
                    <a href="{{ route('user.orders') }}" class="text-blue-700 hover:text-blue-900">
                        &larr; Back to Orders
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Order Date</h3>
                        <p>{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Status</h3>
                        <p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Total</h3>
                        <p class="text-lg font-bold">${{ number_format($order->total, 2) }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Order Details -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-6">Order Details</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                                @if($item->product)
                                                    <div class="text-sm text-blue-700">
                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="hover:underline">View Product</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${{ number_format($item->price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right font-medium">Total:</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold mb-6">Shipping Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Shipping Address</h3>
                        <p>{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Billing Address</h3>
                        <p>{{ $order->billing_address }}</p>
                    </div>
                </div>
                
                @if($order->tracking_number)
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Tracking Number</h3>
                        <p>{{ $order->tracking_number }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Payment Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold mb-6">Payment Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Payment Method</h3>
                        <p>{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Payment Status</h3>
                        <p>
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                    </div>
                </div>
                
                @if($order->notes)
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Order Notes</h3>
                        <p>{{ $order->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection