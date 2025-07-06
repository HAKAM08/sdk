<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SDK-AquaPro') }} - @yield('title', 'Premium Fishing Tackle')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Custom Styles -->
    @yield('styles')
    
    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <header class="bg-blue-900 text-white">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="text-2xl font-bold">SDK-AquaPro</a>

                    <!-- Navigation -->
                    <nav class="hidden md:flex space-x-6">
                        <a href="{{ route('home') }}" class="hover:text-blue-300">Home</a>
                        <a href="{{ route('products.index') }}" class="hover:text-blue-300">Shop</a>
                        <a href="{{ route('categories.index') }}" class="hover:text-blue-300">Categories</a>
                        <a href="{{ route('fishing-map.index') }}" class="hover:text-blue-300">Fishing Map</a>
                        <a href="{{ route('content.index') }}" class="hover:text-blue-300">Fishing Tips</a>
                    </nav>
                    
                    <!-- Auth Navigation -->
                    <div class="hidden md:flex items-center space-x-4">
                        <!-- Cart Icon -->
                        <a href="{{ route('cart.index') }}" class="relative text-white hover:text-blue-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if(Session::has('cart') && count(Session::get('cart')) > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ count(Session::get('cart')) }}</span>
                            @endif
                        </a>
                        
                        @auth
                            <span class="text-sm">Welcome, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm hover:text-blue-300">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm hover:text-blue-300">Login</a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm">Register</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button id="mobile-menu-button" class="md:hidden text-white focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Menu (hidden by default) -->
                <div id="mobile-menu" class="md:hidden hidden mt-4 pb-2">
                    <a href="{{ route('home') }}" class="block py-2 hover:text-blue-300">Home</a>
                    <a href="{{ route('products.index') }}" class="block py-2 hover:text-blue-300">Shop</a>
                    <a href="{{ route('categories.index') }}" class="block py-2 hover:text-blue-300">Categories</a>
                    <a href="{{ route('fishing-map.index') }}" class="block py-2 hover:text-blue-300">Fishing Map</a>
                    <a href="{{ route('content.index') }}" class="block py-2 hover:text-blue-300">Fishing Tips</a>
                    
                    <!-- Cart Link -->
                    <a href="{{ route('cart.index') }}" class="block py-2 hover:text-blue-300 flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Cart
                        @if(Session::has('cart') && count(Session::get('cart')) > 0)
                            <span class="ml-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ count(Session::get('cart')) }}</span>
                        @endif
                    </a>
                    
                    <div class="mt-4 pt-4 border-t border-blue-800">
                        @auth
                            <span class="block py-2 text-sm">Welcome, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 text-sm hover:text-blue-300">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block py-2 hover:text-blue-300">Login</a>
                            <a href="{{ route('register') }}" class="block py-2 hover:text-blue-300">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            <!-- Flash Messages -->
            @if(session('success') || session('error') || session('info'))
                <div class="container mx-auto px-4 py-4">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('info') }}</span>
                            <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                                <svg class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            @endif
            
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-blue-900 text-white py-8">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">SDK-AquaPro</h3>
                        <p class="mb-4">Premium fishing tackle for all your angling adventures.</p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-white hover:text-blue-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.675 0h-21.35c-.732 0-1.325.593-1.325 1.325v21.351c0 .731.593 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.671c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.73 0 1.323-.593 1.323-1.325v-21.35c0-.732-.593-1.325-1.325-1.325z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-blue-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-white hover:text-blue-300">
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('home') }}" class="hover:text-blue-300">Home</a></li>
                            <li><a href="{{ route('products.index') }}" class="hover:text-blue-300">Shop</a></li>
                            <li><a href="{{ route('categories.index') }}" class="hover:text-blue-300">Categories</a></li>
                            <li><a href="{{ route('fishing-map.index') }}" class="hover:text-blue-300">Fishing Map</a></li>
                            <li><a href="{{ route('content.index') }}" class="hover:text-blue-300">Fishing Tips</a></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-xl font-bold mb-4">Categories</h3>
                        <ul class="space-y-2">
                            @foreach(\App\Models\Category::whereNull('parent_id')->take(5)->get() as $category)
                                <li><a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-300">{{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Contact Info -->
                     <div>
                        <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                        <ul class="space-y-2">
                            <li class="flex items-start">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span><p><a href="https://www.google.com/maps/place/53+Rue+Pierre+Parent,+Casablanca+20250/@33.5942465,-7.6066474,946m/data=!3m2!1e3!4b1!4m9!1m2!2m1!1s53,+rue+pierre+parent+-+casablanca+rc+afficher+le+rc!3m5!1s0xda7cd636422bde7:0x99fcfd3bdbe27ada!8m2!3d33.5942465!4d-7.6066474!15sCjQ1MywgcnVlIHBpZXJyZSBwYXJlbnQgLSBjYXNhYmxhbmNhIHJjIGFmZmljaGVyIGxlIHJjkgEQZ2VvY29kZWRfYWRkcmVzc-ABAA?entry=ttu&g_ep=EgoyMDI1MDYzMC4wIKXMDSoASAFQAw%3D%3D" class="text-white d-block">53,Pierre Parent,Casablanca</a></p></span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>                <p>Phone: <a href="tel:05 22 30 92 27" class="text-white d-block">05 22 30 92 27</a></p>
</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span>  <p>Contact: <a href="mailto:Sdk-aqua@contact.com" class="text-white d-block">Sdk-aqua@contact.com</a></p></span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-blue-800 text-center">
                    <p>&copy; {{ date('Y') }} SDK-AquaPro. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Custom Scripts -->
    @yield('scripts')
</body>
</html>