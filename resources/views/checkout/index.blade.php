@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex flex-col md:flex-row gap-8">
        <!-- Checkout Form -->
        <div class="md:w-2/3">
            <h1 class="text-3xl font-bold mb-6">Checkout</h1>
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                
                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Customer Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                            <input type="text" id="name" value="{{ $user->name }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                            <input type="email" id="email" value="{{ $user->email }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-2">Phone</label>
                            <input type="text" id="phone" value="{{ $user->phone }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" readonly>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Address -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Shipping Address</h2>
                    
                    <div class="mb-4">
                        <label for="shipping_address" class="block text-gray-700 font-medium mb-2">Address</label>
                        <textarea id="shipping_address" name="shipping_address" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shipping_address') border-red-500 @enderror">{{ old('shipping_address', $user->address) }}</textarea>
                        @error('shipping_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Billing Address -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Billing Address</h2>
                    
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="same_as_shipping" class="mr-2">
                            <label for="same_as_shipping" class="text-gray-700">Same as shipping address</label>
                        </div>
                        
                        <textarea id="billing_address" name="billing_address" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('billing_address') border-red-500 @enderror">{{ old('billing_address', $user->address) }}</textarea>
                        @error('billing_address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Payment Method</h2>
                    
                    <div class="mb-4">
                        <div class="flex items-center mb-2">
                            <input type="radio" id="credit_card" name="payment_method" value="credit_card" checked class="mr-2">
                            <label for="credit_card" class="text-gray-700">Credit Card</label>
                        </div>
                        
                        <div class="flex items-center">
                            <input type="radio" id="paypal" name="payment_method" value="paypal" class="mr-2">
                            <label for="paypal" class="text-gray-700">PayPal</label>
                        </div>
                        
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Order Notes -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-bold mb-4">Order Notes (Optional)</h2>
                    
                    <div class="mb-4">
                        <textarea id="notes" name="notes" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <div class="flex justify-between items-center">
                    <a href="{{ route('cart.index') }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Cart
                    </a>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-medium">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Order Summary -->
        <div class="md:w-1/3">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                <h2 class="text-xl font-bold mb-4">Order Summary</h2>
                
                <div class="divide-y divide-gray-200">
                    @foreach($cartItems as $item)
                        <div class="py-3 flex justify-between">
                            <div>
                                <p class="font-medium">{{ $item['product']->name }}</p>
                                <p class="text-gray-600 text-sm">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-medium">${{ number_format($item['subtotal'], 2) }}</p>
                        </div>
                    @endforeach
                </div>
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Shipping</span>
                        <span>Free</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sameAsShippingCheckbox = document.getElementById('same_as_shipping');
        const shippingAddressField = document.getElementById('shipping_address');
        const billingAddressField = document.getElementById('billing_address');
        
        sameAsShippingCheckbox.addEventListener('change', function() {
            if (this.checked) {
                billingAddressField.value = shippingAddressField.value;
                billingAddressField.disabled = true;
            } else {
                billingAddressField.disabled = false;
            }
        });
        
        shippingAddressField.addEventListener('input', function() {
            if (sameAsShippingCheckbox.checked) {
                billingAddressField.value = this.value;
            }
        });
    });
</script>
@endsection