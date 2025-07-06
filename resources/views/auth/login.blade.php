@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="py-4 px-6 bg-gray-800 text-white">
            <h2 class="text-2xl font-bold text-center">Login</h2>
        </div>

        <form method="POST" action="{{ route('login') }}" class="py-4 px-6">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
                <input id="password" type="password" name="password" required
                    class="appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-between mb-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a href="{{ route('register') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Need an account?
                </a>
            </div>
        </form>
    </div>
</div>
@endsection