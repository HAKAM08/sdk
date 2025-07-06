@extends('layouts.app')

@section('title', 'Premium Fishing Tackle')

@section('styles')
<style>
    /* Initial state */
.map-hidden {
    display: none;
    opacity: 0;
}

/* Visible with animation */
.map-visible {
    display: block;
    animation: slideDown 0.8s ease-out forwards;
}

@keyframes slideDown {
    0% {
        opacity: -1;
        transform: translateY(-20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

    /* Slideshow Styles */
    .slideshow-container {
        position: relative;
        overflow: hidden;
        width: 100%;
        height: 400px; /* Fixed height for stability */
        margin: 0 auto;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .slideshow-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
        transform: scale(0.95);
    }
    
    .slideshow-slide.active {
        opacity: 1;
        transform: scale(1);
        z-index: 1;
    }
    
    .slideshow-slide-content {
        height: 100%;
        display: flex;
        align-items: center;
    }
    
    .slideshow-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Enhanced slideshow controls */
    #prev-slide, #next-slide {
        z-index: 10;
        width: 40px;
        height: 40px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        color: #333;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
    }
    
    #prev-slide {
        left: 15px;
    }
    
    #next-slide {
        right: 15px;
    }
    
    #prev-slide:hover, #next-slide:hover {
        background-color: white;
        transform: translateY(-50%) scale(1.1);
    }
    
    /* Enhanced slideshow indicators */
    .slideshow-indicator {
        cursor: pointer;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.7);
        transition: all 0.3s ease;
        margin: 0 5px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    
    .slideshow-indicator:hover {
        transform: scale(1.2);
        background-color: white;
    }
    
    /* Ad spaces styling */
    .ad-space {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 8px;
        overflow: hidden;
        height: 100%;
        background-color: white;
    }
    
    .ad-space:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .ad-space img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .slideshow-side .ad-space img {
        height: 250px; /* Taller image for vertical ad spaces */
    }
    
    .ad-space-container {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }
    
    .slideshow-section {
        display: flex;
        gap: 20px;
        align-items: stretch;
    }
    
    .slideshow-main {
        flex: 1;
    }
    
    .slideshow-side {
        width: 160px;
        display: none;
        height: 400px; /* Match the slideshow height */
    }
    
    @media (min-width: 1024px) {
        .slideshow-side {
            display: block;
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="relative">
        @if($slideshows->count() > 0)
            <div class="relative">
                <div class="bg-blue-700 text-white">
                    <div class="container mx-auto px-4 py-16 md:py-24">
                        <div class="max-w-2xl">
                            <h1 class="text-4xl md:text-5xl font-bold mb-4">Premium Fishing Tackle for Every Angler</h1>
                            <p class="text-xl mb-8">Discover top-quality fishing gear designed for performance and reliability.</p>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('products.index') }}" class="bg-white text-blue-700 px-6 py-3 rounded-md font-semibold hover:bg-blue-50 transition">Shop Now</a>
                                <a href="{{ route('content.index') }}" class="bg-transparent border-2 border-white px-6 py-3 rounded-md font-semibold hover:bg-white hover:text-blue-700 transition">Fishing Tips</a>
                            </div>
                        </div>
                    </div>
                </div>
                
    <!-- Slideshow Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Featured Fishing Spots</h2>
            @if($slideshows->count() > 0)
                <div class="slideshow-section">
                    <!-- Left Ad Space (visible on large screens) -->
                    <div class="slideshow-side">
                        @if($leftAdSpace)
                            <div class="ad-space h-full">
                                <a href="{{ $leftAdSpace->link }}" class="block h-full">
                                    <img src="{{ $leftAdSpace->image }}" alt="{{ $leftAdSpace->title }}" class="w-full object-cover">
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg text-blue-700">{{ $leftAdSpace->title }}</h3>
                                        @if($leftAdSpace->description)
                                            <p class="text-gray-600 text-sm">{{ $leftAdSpace->description }}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Main Slideshow -->
                    <div class="slideshow-main">
                        <div class="slideshow-container relative overflow-hidden mx-auto shadow-lg">
                            @foreach($slideshows as $index => $slide)
                                <div class="slideshow-slide {{ $index === 0 ? 'active' : '' }}" data-slide-index="{{ $index }}">
                                    <img src="{{ $slide->image }}" alt="{{ $slide->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                                        <div class="p-6 text-white">
                                            <h3 class="text-2xl font-bold mb-2">{{ $slide->title }}</h3>
                                            <p class="mb-4">{{ $slide->description }}</p>
                                            @if($slide->link)
                                                <a href="{{ $slide->link }}" class="inline-block bg-blue-700 text-white px-4 py-2 rounded-md font-semibold hover:bg-blue-800 transition">Learn More</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            
                            <!-- Slideshow Controls -->
                            <button id="prev-slide">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                </svg>
                            </button>
                            <button id="next-slide">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </button>

                            <!-- Slideshow Indicators -->
                            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2">
                                @foreach($slideshows as $index => $slide)
                                    <button class="slideshow-indicator {{ $index === 0 ? 'bg-opacity-100' : 'bg-opacity-50' }}" data-slide-index="{{ $index }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Ad Space (visible on large screens) -->
                    <div class="slideshow-side">
                        @if($rightAdSpace)
                            <div class="ad-space h-full">
                                <a href="{{ $rightAdSpace->link }}" class="block h-full">
                                    <img src="{{ $rightAdSpace->image }}" alt="{{ $rightAdSpace->title }}" class="w-full object-cover">
                                    <div class="p-4">
                                        <h3 class="font-semibold text-lg text-blue-700">{{ $rightAdSpace->title }}</h3>
                                        @if($rightAdSpace->description)
                                            <p class="text-gray-600 text-sm">{{ $rightAdSpace->description }}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Mobile Ad Spaces (visible on small screens) -->
               
              
            @else
                <div class="bg-gray-100 p-8 rounded-lg text-center">
                    <p class="text-gray-600">No slideshow content available at the moment.</p>
                </div>
            @endif
        </div>
    </section>
                
                <!-- Ad Spaces integrated with slideshow above -->
            </div>
        @else
            <!-- Fallback Hero Section if no slideshows -->
            <div class="bg-blue-700 text-white">
                <div class="container mx-auto px-4 py-16 md:py-24">
                    <div class="max-w-2xl">
                        <h1 class="text-4xl md:text-5xl font-bold mb-4">Premium Fishing Tackle for Every Angler</h1>
                        <p class="text-xl mb-8">Discover top-quality fishing gear designed for performance and reliability.</p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('products.index') }}" class="bg-white text-blue-700 px-6 py-3 rounded-md font-semibold hover:bg-blue-50 transition">Shop Now</a>
                            <a href="{{ route('content.index') }}" class="bg-transparent border-2 border-white px-6 py-3 rounded-md font-semibold hover:bg-white hover:text-blue-700 transition">Fishing Tips</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <!-- Featured Categories -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Shop by Category</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @foreach($mainCategories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="group">
                        <div class="bg-gray-100 rounded-lg p-6 text-center transition transform group-hover:-translate-y-1 group-hover:shadow-lg">
                            <div class="text-blue-700 mb-4">
                                <svg class="h-12 w-12 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-5-9h10v2H7z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-lg">{{ $category->name }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Featured Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($featuredProducts as $product)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="h-48 bg-gray-200">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1">
                                @foreach($product->categories as $category)
                                    <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-700">
                                        {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <h3 class="font-semibold text-lg mb-2 hover:text-blue-700">{{ $product->name }}</h3>
                            </a>
                            <p class="text-gray-600 text-sm mb-4">{{ $product->short_description }}</p>
                            <div class="flex justify-between items-center">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-gray-500 line-through text-sm">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-blue-700 font-bold ml-2">${{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        <span class="text-blue-700 font-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="text-blue-700 hover:text-blue-900">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-700 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-800 transition">View All Products</a>
            </div>
        </div>
    </section>

    <!-- Latest Products -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Latest Arrivals</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                @foreach($latestProducts->take(4) as $product)
                    <div class="bg-gray-50 rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                        <a href="{{ route('products.show', $product->slug) }}">
                            <div class="h-48 bg-gray-200">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500">
                                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1">
                                @foreach($product->categories as $category)
                                    <a href="{{ route('categories.show', $category->slug) }}" class="hover:text-blue-700">
                                        {{ $category->name }}{{ !$loop->last ? ', ' : '' }}
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <h3 class="font-semibold text-lg mb-2 hover:text-blue-700">{{ $product->name }}</h3>
                            </a>
                            <div class="flex justify-between items-center">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-gray-500 line-through text-sm">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-blue-700 font-bold ml-2">${{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        <span class="text-blue-700 font-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <a href="{{ route('products.show', $product->slug) }}" class="text-blue-700 hover:text-blue-900">
                                    View
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Fishing Tips -->
    <section class="py-12 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Fishing Tips & Guides</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($latestContent as $content)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                        <a href="{{ route('content.show', $content->slug) }}">
                            <div class="h-48 bg-gray-200">
                                @if($content->featured_image)
                                    <img src="{{ $content->featured_image }}" alt="{{ $content->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-500 bg-blue-50">
                                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-2-7H7v-2h10v2zm-2 4H7v-2h8v2z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <div class="text-xs text-gray-500 mb-1 uppercase">
                                {{ ucfirst($content->type) }}
                            </div>
                            <a href="{{ route('content.show', $content->slug) }}" class="block">
                                <h3 class="font-semibold text-lg mb-2 hover:text-blue-700">{{ $content->title }}</h3>
                            </a>
                            <a href="{{ route('content.show', $content->slug) }}" class="text-blue-700 hover:text-blue-900 inline-flex items-center">
                                Read More
                                <svg class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('content.index') }}" class="inline-block bg-blue-700 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-800 transition">View All Tips & Guides</a>
            </div>
        </div>
    </section>


    <!-- Customer Reviews Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-2">What Our Customers Say</h2>
                <p class="text-gray-600">Read reviews from our satisfied anglers</p>
            </div>
            
            @if($positiveReviews->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($positiveReviews as $review)
                        <div class="bg-gray-50 rounded-lg p-6 shadow-sm hover:shadow-md transition">
                            <div class="flex items-center mb-4">
                                <div class="flex-shrink-0 mr-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-700 font-bold">
                                        {{ strtoupper(substr($review->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <h4 class="font-semibold">{{ $review->user->name }}</h4>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                            </svg>
                                        @endfor
                                        <span class="text-gray-500 text-sm ml-1">({{ $review->rating }}/5)</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-600 mb-4">"{{ Str::limit($review->comment, 150) }}"</p>
                            <div class="text-sm text-gray-700 mb-3">
                                <span class="font-medium">Product:</span> {{ $review->product->name }}
                            </div>
                            <div class="flex items-center justify-between">
                                <a href="{{ route('products.show', $review->product) }}" class="text-sm text-blue-700 hover:underline flex items-center">
                                    <span>View Product</span>
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </a>
                                <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">No reviews available yet. Be the first to share your experience!</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-gray-100 py-16">
        <div class="container mx-auto px-4">
            <div class="max-w-xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Join Our Fishing Community</h2>
                <p class="text-gray-600 mb-8">Subscribe to our newsletter for exclusive deals, fishing tips, and new product alerts.</p>
                <form class="flex flex-col sm:flex-row gap-2">
                    <input type="email" placeholder="Your email address" class="flex-grow px-4 py-3 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-700 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-800 transition">Subscribe</button>
                </form>
            </div>
        </div>
        <!-- View Location Section -->
<section class="bg-white py-16">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-6">Visit Our Store</h2>
        <button id="viewLocationBtn" class="bg-blue-700 text-white px-6 py-3 rounded-md font-semibold hover:bg-blue-800 transition">
            View Location
        </button>
        <div id="mapContainer" class="mt-8 hidden">
            <iframe 
                src="https://maps.google.com/maps?width=600&height=400&hl=en&q=53%20Rue%20Pierre%20Parent&t=&z=14&ie=UTF8&iwloc=B&output=embed"
                width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

    </section>
    
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const button = document.getElementById('viewLocationBtn');
        const mapContainer = document.getElementById('mapContainer');

        button.addEventListener('click', () => {
            if (mapContainer.classList.contains('map-hidden')) {
                mapContainer.classList.remove('map-hidden');
                mapContainer.classList.add('map-visible');
            } else {
                mapContainer.classList.remove('map-visible');
                mapContainer.classList.add('map-hidden');
            }
        });
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const viewLocationBtn = document.getElementById('viewLocationBtn');
        const mapContainer = document.getElementById('mapContainer');

        viewLocationBtn.addEventListener('click', function () {
            mapContainer.classList.toggle('hidden');
            viewLocationBtn.textContent = mapContainer.classList.contains('hidden') 
                ? 'Hide Map' 
                : 'View Location';
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Slideshow functionality
        const slides = document.querySelectorAll('.slideshow-slide');
        const indicators = document.querySelectorAll('.slideshow-indicator');
        const prevButton = document.getElementById('prev-slide');
        const nextButton = document.getElementById('next-slide');
        let currentSlideIndex = 0;
        let slideInterval;
        
        // Skip slideshow setup if no slides
        if (slides.length === 0) return;
        
        // Function to show a specific slide
        function showSlide(index) {
            // Calculate the new index with wrapping
            const newIndex = (index + slides.length) % slides.length;
            
            // If it's the same slide, do nothing
            if (newIndex === currentSlideIndex) return;
            
            // Hide the current slide (prepare for transition)
            slides[currentSlideIndex].classList.remove('active');
            
            // Update indicators
            indicators.forEach((indicator, i) => {
                if (i === newIndex) {
                    indicator.classList.add('bg-opacity-100');
                    indicator.classList.remove('bg-opacity-50');
                } else {
                    indicator.classList.remove('bg-opacity-100');
                    indicator.classList.add('bg-opacity-50');
                }
            });
            
            // Update the current slide index
            currentSlideIndex = newIndex;
            
            // Show the new slide with animation
            setTimeout(() => {
                slides[currentSlideIndex].classList.add('active');
            }, 50);
        }
        
        // Initialize the slideshow
        function initSlideshow() {
            // Make sure all slides are properly set up
            slides.forEach((slide, index) => {
                if (index === 0) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
            
            // Start the automatic slideshow
            startSlideInterval();
        }
        
        // Function to start the automatic slideshow interval
        function startSlideInterval() {
            // Clear any existing interval
            clearInterval(slideInterval);
            
            // Set a new interval
            slideInterval = setInterval(() => {
                showSlide(currentSlideIndex + 1);
            }, 6000); // Change slide every 6 seconds for better user experience
        }
        
        // Event listeners for controls
        prevButton.addEventListener('click', (e) => {
            e.preventDefault();
            showSlide(currentSlideIndex - 1);
            startSlideInterval(); // Reset the interval
        });
        
        nextButton.addEventListener('click', (e) => {
            e.preventDefault();
            showSlide(currentSlideIndex + 1);
            startSlideInterval(); // Reset the interval
        });
        
        // Event listeners for indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', (e) => {
                e.preventDefault();
                showSlide(index);
                startSlideInterval(); // Reset the interval
            });
        });
        
        // Pause slideshow when hovering over it
        const slideshowContainer = document.querySelector('.slideshow-container');
        slideshowContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        slideshowContainer.addEventListener('mouseleave', () => {
            startSlideInterval();
        });
        
        // Add touch support for mobile devices
        let touchStartX = 0;
        let touchEndX = 0;
        
        slideshowContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        
        slideshowContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {passive: true});
        
        function handleSwipe() {
            const swipeThreshold = 50; // Minimum distance for a swipe
            if (touchEndX < touchStartX - swipeThreshold) {
                // Swipe left - next slide
                showSlide(currentSlideIndex + 1);
                startSlideInterval();
            } else if (touchEndX > touchStartX + swipeThreshold) {
                // Swipe right - previous slide
                showSlide(currentSlideIndex - 1);
                startSlideInterval();
            }
        }
        
        // Initialize the slideshow
        initSlideshow();
    });
</script>
@endsection
