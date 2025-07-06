@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Shopping Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cartItems) > 0)
        <form action="{{ route('cart.update') }}" method="POST">
            @csrf
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left">Product</th>
                            <th class="py-3 px-4 text-center">Price</th>
                            <th class="py-3 px-4 text-center">Quantity</th>
                            <th class="py-3 px-4 text-center">Subtotal</th>
                            <th class="py-3 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($cartItems as $item)
                            <tr>
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <img src="{{ $item['product']->image ? asset('storage/' . $item['product']->image) : asset('images/placeholder.png') }}" 
                                            alt="{{ $item['product']->name }}" class="w-16 h-16 object-cover rounded">
                                        <div class="ml-4">
                                            <a href="{{ route('products.show', $item['product']->slug) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                                                {{ $item['product']->name }}
                                            </a>
                                            @if($item['product']->category)
                                                <p class="text-gray-500 text-sm">{{ $item['product']->category->name }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    @if($item['product']->sale_price)
                                        <span class="text-red-600 font-medium">${{ number_format($item['product']->sale_price, 2) }}</span>
                                        <span class="text-gray-400 line-through text-sm ml-2">${{ number_format($item['product']->price, 2) }}</span>
                                    @else
                                        <span class="font-medium">${{ number_format($item['product']->price, 2) }}</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <input type="number" name="quantities[{{ $item['id'] }}]" value="{{ $item['quantity'] }}" 
                                        min="1" max="{{ $item['product']->stock }}" class="w-16 text-center border rounded py-1 px-2">
                                    <div class="text-sm text-gray-500 mt-1">In stock: {{ $item['product']->stock }}</div>
                                </td>
                                <td class="py-4 px-4 text-center font-medium">
                                    ${{ number_format($item['subtotal'], 2) }}
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <a href="{{ route('cart.remove', $item['id']) }}" class="text-red-600 hover:text-red-800">
                                        <svg class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
                <div class="flex space-x-2 mb-4 md:mb-0">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded">
                        Update Cart
                    </button>
                    <a href="{{ route('cart.clear') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded">
                        Clear Cart
                    </a>
                </div>
                <div class="bg-gray-100 p-4 rounded">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">Subtotal:</span>
                        <span class="font-bold">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium">Shipping:</span>
                        <span>Calculated at checkout</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                        <span class="font-bold text-lg">Total:</span>
                        <span class="font-bold text-lg">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </form>

        <div class="flex justify-between items-center">
            <a href="{{ route('products.index') }}" class="text-blue-600 hover:text-blue-800">
                <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Continue Shopping
            </a>
            <a href="{{ route('checkout') }}" class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-medium">
                Proceed to Checkout
            </a>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
            <p class="text-gray-600 mb-6">Looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium">
                Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection