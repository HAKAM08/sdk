@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-green-600 text-white p-6 text-center">
            <svg class="h-16 w-16 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h1 class="text-3xl font-bold mb-2">Order Confirmed!</h1>
            <p class="text-xl">Thank you for your purchase</p>
        </div>
        
        <div class="p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4">Order Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Order Number:</p>
                        <p class="font-medium">#{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Date:</p>
                        <p class="font-medium">{{ $order->created_at->format('F j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total:</p>
                        <p class="font-medium">${{ number_format($order->total, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Payment Method:</p>
                        <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4">Shipping Information</h2>
                <p>{{ $order->shipping_address }}</p>
            </div>
            
            <div class="mb-6">
                <h2 class="text-2xl font-bold mb-4">Order Summary</h2>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-3 px-4 text-left">Product</th>
                                <th class="py-3 px-4 text-center">Quantity</th>
                                <th class="py-3 px-4 text-center">Price</th>
                                <th class="py-3 px-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-4 px-4">{{ $item->product_name }}</td>
                                    <td class="py-4 px-4 text-center">{{ $item->quantity }}</td>
                                    <td class="py-4 px-4 text-center">${{ number_format($item->price, 2) }}</td>
                                    <td class="py-4 px-4 text-right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="py-3 px-4 text-right font-medium">Total:</td>
                                <td class="py-3 px-4 text-right font-bold">${{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-gray-600 mb-4">A confirmation email has been sent to your email address.</p>
                <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded-lg font-medium inline-block">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection