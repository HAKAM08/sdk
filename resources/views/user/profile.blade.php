@extends('layouts.app')

@section('title', 'My Profile')

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
                        <a href="{{ route('user.orders') }}" class="block py-2 px-4 hover:bg-gray-100 rounded-md transition">
                            My Orders
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('user.profile') }}" class="block py-2 px-4 bg-blue-100 text-blue-700 rounded-md font-medium">
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
                <h1 class="text-2xl font-bold mb-6">My Profile</h1>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('user.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 font-medium mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="phone" class="block text-gray-700 font-medium mb-2">Phone Number (Optional)</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('phone') border-red-500 @enderror">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="address" class="block text-gray-700 font-medium mb-2">Address (Optional)</label>
                        <textarea name="address" id="address" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-700 text-white py-2 px-6 rounded-md font-semibold hover:bg-blue-800 transition">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection