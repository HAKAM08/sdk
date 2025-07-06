@extends('layouts.app')

@section('title', 'My Orders')

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
            <div class="bg-white rounded-lg shadow-md p-6">
                <h1 class="text-2xl font-bold mb-6">My Orders</h1>
                
                @if($orders->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-gray-900">#{{ $order->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($order->status == 'completed') bg-green-100 text-green-800
                                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="text-sm text-gray-900">${{ number_format($order->total, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('user.orders.show', $order->id) }}" class="text-blue-700 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600 mb-4">You haven't placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" class="bg-blue-700 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-800 transition">Start Shopping</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection