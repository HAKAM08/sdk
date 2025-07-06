@extends('layouts.app')

@section('title', $product->name)

@section('styles')
    <style>
        /* Additional styles for product page */
        .attribute-option {
            display: inline-block;
            margin-right: 8px;
            margin-bottom: 8px;
        }
        
        .attribute-label {
            transition: all 0.2s ease-in-out;
        }
        
        .attribute-radio:checked + .attribute-label {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }
    </style>
@endsection

@section('content')
    <!-- Product Detail -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('products.index') }}" class="hover:text-blue-700">Products</a>
                    @if($product->categories->count() > 0)
                        <span class="mx-2">/</span>
                        <a href="{{ route('categories.show', $product->categories->first()->slug) }}" class="hover:text-blue-700">{{ $product->categories->first()->name }}</a>
                    @endif
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">{{ $product->name }}</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Product Images -->
                <div class="lg:w-1/2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-4">
                        <div id="main-image-container" class="h-96 bg-gray-100 relative">
                            @if($product->image)
                                <img id="main-product-image" src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-contain cursor-zoom-in">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-500">
                                    <svg class="h-24 w-24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/>
                                    </svg>
                                </div>
                            @endif
                            <!-- Zoom lens -->
                            <div id="lens" class="hidden absolute border-2 border-gray-300 bg-white bg-opacity-25 pointer-events-none"></div>
                        </div>
                        <!-- Zoomed image result -->
                        <div id="zoom-result" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-75 z-50 flex items-center justify-center">
                            <div class="relative w-full max-w-4xl max-h-screen p-4">
                                <img id="zoomed-image" src="" alt="Zoomed image" class="max-w-full max-h-[80vh] object-contain mx-auto">
                                <button id="close-zoom" class="absolute top-4 right-4 bg-white rounded-full p-2 shadow-lg">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                                <div class="absolute bottom-4 left-0 right-0 flex justify-center">
                                    <button id="prev-image" class="bg-white rounded-full p-2 shadow-lg mr-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                    </button>
                                    <button id="next-image" class="bg-white rounded-full p-2 shadow-lg ml-4">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="gallery-container" class="grid grid-cols-4 gap-2">
                        @if($product->image)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden gallery-item" data-image="{{ asset($product->image) }}">
                                <div class="h-24 bg-gray-100">
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover cursor-pointer border-2 border-blue-500">
                                </div>
                            </div>
                        @endif
                        @if($product->gallery_images && count($product->gallery_images) > 0)
                            @foreach($product->gallery_images as $index => $image)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden gallery-item" data-image="{{ asset($image) }}">
                                    <div class="h-24 bg-gray-100">
                                        <img src="{{ asset($image) }}" alt="{{ $product->name }} - Image {{ $index + 1 }}" class="w-full h-full object-cover cursor-pointer">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="lg:w-1/2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="mb-4">
                            @foreach($product->categories as $category)
                                <a href="{{ route('categories.show', $category->slug) }}" class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2 mb-2 hover:bg-blue-200 transition">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>

                        <h1 class="text-3xl font-bold mb-4">{{ $product->name }}</h1>

                        <div class="mb-6">
                            @if($product->sale_price)
                                <span class="text-gray-500 line-through text-xl">${{ number_format($product->price, 2) }}</span>
                                <span class="text-blue-700 font-bold text-3xl ml-2">${{ number_format($product->sale_price, 2) }}</span>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full ml-2">
                                    {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                                </span>
                            @else
                                <span class="text-blue-700 font-bold text-3xl">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>

                        <div class="mb-6">
                            <p class="text-gray-700 text-lg mb-4">{{ $product->short_description }}</p>
                            
                            <div class="flex items-center mb-4">
                                <span class="text-gray-700 mr-2">Availability:</span>
                                @if($product->stock > 0)
                                    <span class="text-green-600 font-medium">In Stock ({{ $product->stock }} available)</span>
                                @else
                                    <span class="text-red-600 font-medium">Out of Stock</span>
                                @endif
                            </div>

                            
                            @if($product->attributeValues->count() > 0)
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-3">Product Options</h3>
                                    @php
                                        $attributes = $product->attributeValues->groupBy(function($attributeValue) {
                                            return $attributeValue->attribute->name;
                                        });
                                    @endphp

                                    @foreach($attributes as $attributeName => $values)
                                        <div class="mb-4">
                                            <label class="block text-gray-700 mb-2">{{ $attributeName }}</label>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($values as $value)
                                                    <div class="border border-gray-300 rounded-md px-4 py-2 cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                                                        {{ $value->value }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center mb-6">
                                @if($product->stock > 0)
                                    <div class="mr-4">
                                        <label for="quantity" class="block text-gray-700 mb-2">Quantity</label>
                                        <div class="flex items-center border border-gray-300 rounded-md">
                                            <button class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition" onclick="decrementQuantity()">-</button>
                                            <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="w-12 text-center border-0 focus:ring-0" onchange="updateCartQuantity(this.value)">
                                            <button class="px-3 py-2 text-gray-600 hover:bg-gray-100 transition" onclick="incrementQuantity()">+</button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-grow">
                                        @auth
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <input type="hidden" name="quantity" id="cart_quantity" value="1">
                                                
                                                @if($product->attribute_values && $product->attribute_values->count() > 0)
                                                    @php
                                                        $groupedAttributes = $product->attribute_values ? $product->attribute_values->groupBy(function($item) {
                                                            return $item->attribute->name;
                                                        }) : collect();
                                                    @endphp
                                                    
                                                    @foreach($groupedAttributes as $attributeName => $values)
                                                        <input type="hidden" name="attributes[{{ Str::slug($attributeName) }}]" id="selected_{{ Str::slug($attributeName) }}" value="{{ $values->first()->id }}">
                                                    @endforeach
                                                @endif
                                                
                                                <button type="submit" class="w-full bg-blue-700 text-white py-3 px-6 rounded-md font-semibold hover:bg-blue-800 transition">
                                                    Add to Cart
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="block w-full bg-blue-700 text-white py-3 px-6 rounded-md font-semibold hover:bg-blue-800 transition text-center">
                                                Login to Add to Cart
                                            </a>
                                            <p class="text-sm text-gray-600 mt-2 text-center">You must be logged in to add items to your cart</p>
                                        @endauth
                                    </div>
                                @else
                                    <div class="flex-grow">
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                            <p>This product is currently out of stock. Please check back later.</p>
                                        </div>
                                        <button disabled class="w-full mt-4 bg-gray-400 text-white py-3 px-6 rounded-md font-semibold cursor-not-allowed">
                                            Out of Stock
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description -->
            <div class="mt-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Product Description</h2>
                    <div class="prose max-w-none">
                        {!! $product->description !!}
                    </div>
                </div>
            </div>

            <!-- Product Reviews -->
            <div class="mt-12">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold mb-6">Customer Reviews</h2>
                    
                    @if($product->reviews->count() > 0)
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="flex items-center mr-4">
                                    @php
                                        $avgRating = $product->reviews->avg('rating');
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-5 w-5 {{ $i <= round($avgRating) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-gray-700">{{ number_format($avgRating, 1) }} out of 5 ({{ $product->reviews->count() }} {{ Str::plural('review', $product->reviews->count()) }})</span>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @foreach($product->reviews as $review)
                                <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                                    <div class="flex items-center mb-2">
                                        <div class="flex items-center mr-4">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            @endfor
                                        </div>
                                        <span class="font-semibold">{{ $review->user ? $review->user->name : 'Anonymous' }}</span>
                                        <span class="text-gray-500 text-sm ml-4">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <p class="text-gray-700">{{ $review->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-600 mb-4">There are no reviews yet for this product.</p>
                            @auth
                                <button id="show-review-form" class="bg-blue-700 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-800 transition">Be the first to review</button>
                            @else
                                <a href="{{ route('login') }}?redirect={{ url()->current() }}" class="bg-blue-700 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-800 transition">Login to review</a>
                            @endauth
                        </div>
                    @endif
                    
                    @auth
                    <div id="review-form" class="mt-8 {{ $product->reviews->count() > 0 ? '' : 'hidden' }}">
                        <h3 class="text-xl font-semibold mb-4">Write a Review</h3>
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-gray-700 mb-2">Rating</label>
                                <div class="flex items-center">
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="mr-2 cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="sr-only peer" {{ old('rating') == $i ? 'checked' : '' }}>
                                                <svg class="h-8 w-8 text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                                @error('rating')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="comment" class="block text-gray-700 mb-2">Your Review</label>
                                <textarea id="comment" name="comment" rows="4" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-700 text-white py-2 px-6 rounded-md font-semibold hover:bg-blue-800 transition">Submit Review</button>
                        </form>
                    </div>
                    @endauth
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts->count() > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                                <a href="{{ route('products.show', $relatedProduct->slug) }}">
                                    <div class="h-48 bg-gray-200">
                                        @if($relatedProduct->image)
                                            <img src="{{ $relatedProduct->image }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover">
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
                                    <a href="{{ route('products.show', $relatedProduct->slug) }}" class="block">
                                        <h3 class="font-semibold text-lg mb-2 hover:text-blue-700">{{ $relatedProduct->name }}</h3>
                                    </a>
                                    <div class="flex justify-between items-center">
                                        <div>
                                            @if($relatedProduct->sale_price)
                                                <span class="text-gray-500 line-through text-sm">${{ number_format($relatedProduct->price, 2) }}</span>
                                                <span class="text-blue-700 font-bold ml-2">${{ number_format($relatedProduct->sale_price, 2) }}</span>
                                            @else
                                                <span class="text-blue-700 font-bold">${{ number_format($relatedProduct->price, 2) }}</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-blue-700 hover:text-blue-900">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script>
        // Quantity increment/decrement functions
        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const cartQuantityInput = document.getElementById('cart_quantity');
            const maxQuantity = parseInt(quantityInput.getAttribute('max'));
            let currentValue = parseInt(quantityInput.value);
            
            if (currentValue < maxQuantity) {
                currentValue++;
                quantityInput.value = currentValue;
                cartQuantityInput.value = currentValue;
            }
        }
        
        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const cartQuantityInput = document.getElementById('cart_quantity');
            let currentValue = parseInt(quantityInput.value);
            
            if (currentValue > 1) {
                currentValue--;
                quantityInput.value = currentValue;
                cartQuantityInput.value = currentValue;
            }
        }
        
        function updateCartQuantity(value) {
            const cartQuantityInput = document.getElementById('cart_quantity');
            if (cartQuantityInput) {
                cartQuantityInput.value = value;
            }
        }
        
        // Show review form when button is clicked
        document.addEventListener('DOMContentLoaded', function() {
            const showReviewFormBtn = document.getElementById('show-review-form');
            const reviewForm = document.getElementById('review-form');
            
            // Handle product attribute selection
            const attributeRadios = document.querySelectorAll('.attribute-radio');
            const attributeLabels = document.querySelectorAll('.attribute-label');
            
            // Add active class to initially checked radio buttons' labels
            attributeRadios.forEach(radio => {
                if (radio.checked) {
                    radio.nextElementSibling.classList.add('border-blue-500', 'bg-blue-50');
                }
                
                radio.addEventListener('change', function() {
                    // Get the attribute name from the radio button's name
                    const attributeName = this.name.replace('attribute_', '');
                    
                    // Update the hidden input with the selected value
                    const hiddenInput = document.getElementById('selected_' + attributeName);
                    if (hiddenInput) {
                        hiddenInput.value = this.value;
                    }
                    
                    // Update styling for all radio buttons in this group
                    document.querySelectorAll(`[name="${this.name}"]`).forEach(groupRadio => {
                        const label = groupRadio.nextElementSibling;
                        if (groupRadio.checked) {
                            label.classList.add('border-blue-500', 'bg-blue-50');
                        } else {
                            label.classList.remove('border-blue-500', 'bg-blue-50');
                        }
                    });
                });
            });
            
            if (showReviewFormBtn && reviewForm) {
                showReviewFormBtn.addEventListener('click', function() {
                    reviewForm.classList.remove('hidden');
                    showReviewFormBtn.classList.add('hidden');
                });
            }
            
            // Image gallery and zoom functionality
            // Elements
            const mainImage = document.getElementById('main-product-image');
            const zoomResult = document.getElementById('zoom-result');
            const zoomedImage = document.getElementById('zoomed-image');
            const closeZoom = document.getElementById('close-zoom');
            const prevButton = document.getElementById('prev-image');
            const nextButton = document.getElementById('next-image');
            const galleryItems = document.querySelectorAll('.gallery-item');
            const lens = document.getElementById('lens');
            const mainImageContainer = document.getElementById('main-image-container');
            
            // Variables
            let currentImageIndex = 0;
            const images = Array.from(galleryItems).map(item => item.dataset.image);
            
            // Initialize
            if (mainImage && images.length > 0) {
                mainImage.src = images[0];
                zoomedImage.src = images[0];
            }
            
            // Gallery functionality - change main image when clicking thumbnails
            galleryItems.forEach((item, index) => {
                item.addEventListener('click', function() {
                    // Update main image
                    mainImage.src = this.dataset.image;
                    zoomedImage.src = this.dataset.image;
                    currentImageIndex = index;
                    
                    // Update active thumbnail border
                    galleryItems.forEach(gi => {
                        gi.querySelector('img').classList.remove('border-2', 'border-blue-500');
                    });
                    this.querySelector('img').classList.add('border-2', 'border-blue-500');
                });
            });
            
            // Zoom functionality
            if (mainImage) {
                // Open zoom view on main image click
                mainImage.addEventListener('click', function() {
                    zoomResult.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent scrolling when zoom is open
                });
                
                // Hover zoom effect
                mainImage.addEventListener('mousemove', function(e) {
                    // Only show lens when not in full zoom mode
                    if (zoomResult.classList.contains('hidden')) {
                        lens.classList.remove('hidden');
                        
                        // Calculate position
                        const rect = mainImageContainer.getBoundingClientRect();
                        const x = e.clientX - rect.left;
                        const y = e.clientY - rect.top;
                        
                        // Lens size (adjust as needed)
                        const lensWidth = 100;
                        const lensHeight = 100;
                        
                        // Position the lens
                        lens.style.width = lensWidth + 'px';
                        lens.style.height = lensHeight + 'px';
                        lens.style.left = Math.max(0, Math.min(x - lensWidth/2, rect.width - lensWidth)) + 'px';
                        lens.style.top = Math.max(0, Math.min(y - lensHeight/2, rect.height - lensHeight)) + 'px';
                    }
                });
                
                // Hide lens when mouse leaves
                mainImage.addEventListener('mouseleave', function() {
                    lens.classList.add('hidden');
                });
            }
            
            // Close zoom view
            if (closeZoom) {
                closeZoom.addEventListener('click', function() {
                    zoomResult.classList.add('hidden');
                    document.body.style.overflow = ''; // Restore scrolling
                });
                
                // Also close when clicking outside the image
                zoomResult.addEventListener('click', function(e) {
                    if (e.target === zoomResult) {
                        zoomResult.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
            }
            
            // Navigation in zoom view
            if (prevButton && nextButton && images.length > 1) {
                prevButton.addEventListener('click', function() {
                    currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
                    zoomedImage.src = images[currentImageIndex];
                    updateGallerySelection();
                });
                
                nextButton.addEventListener('click', function() {
                    currentImageIndex = (currentImageIndex + 1) % images.length;
                    zoomedImage.src = images[currentImageIndex];
                    updateGallerySelection();
                });
                
                // Keyboard navigation
                document.addEventListener('keydown', function(e) {
                    if (!zoomResult.classList.contains('hidden')) {
                        if (e.key === 'ArrowLeft') {
                            prevButton.click();
                        } else if (e.key === 'ArrowRight') {
                            nextButton.click();
                        } else if (e.key === 'Escape') {
                            closeZoom.click();
                        }
                    }
                });
            }
            
            // Helper function to update gallery selection
            function updateGallerySelection() {
                galleryItems.forEach((item, index) => {
                    const img = item.querySelector('img');
                    if (index === currentImageIndex) {
                        img.classList.add('border-2', 'border-blue-500');
                        mainImage.src = images[currentImageIndex];
                    } else {
                        img.classList.remove('border-2', 'border-blue-500');
                    }
                });
            }
        });
        
        function incrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const cartQuantityInput = document.getElementById('cart_quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            
            if (currentValue < maxValue) {
                const newValue = currentValue + 1;
                quantityInput.value = newValue;
                cartQuantityInput.value = newValue;
            }
        }

        function decrementQuantity() {
            const quantityInput = document.getElementById('quantity');
            const cartQuantityInput = document.getElementById('cart_quantity');
            const currentValue = parseInt(quantityInput.value);
            
            if (currentValue > 1) {
                const newValue = currentValue - 1;
                quantityInput.value = newValue;
                cartQuantityInput.value = newValue;
            }
        }

        // Update hidden cart quantity when the visible quantity input changes
        document.getElementById('quantity').addEventListener('change', function() {
            const quantityInput = document.getElementById('quantity');
            const cartQuantityInput = document.getElementById('cart_quantity');
            const currentValue = parseInt(quantityInput.value);
            const maxValue = parseInt(quantityInput.getAttribute('max'));
            
            // Ensure the value is within valid range
            if (currentValue < 1) {
                quantityInput.value = 1;
                cartQuantityInput.value = 1;
            } else if (currentValue > maxValue) {
                quantityInput.value = maxValue;
                cartQuantityInput.value = maxValue;
            } else {
                cartQuantityInput.value = currentValue;
            }
        });
    </script>
@endsection