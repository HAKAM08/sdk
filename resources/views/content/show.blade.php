@extends('layouts.app')

@section('title', $content->title)

@section('content')
    <!-- Content Detail Page -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('content.index') }}" class="hover:text-blue-700">Fishing Tips & Guides</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">{{ $content->title }}</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="lg:w-2/3">
                    <article class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- Featured Image -->
                        @if($content->featured_image)
                            <div class="h-64 md:h-96 bg-gray-200">
                                <img src="{{ $content->featured_image }}" alt="{{ $content->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="p-6">
                            <!-- Content Type Badge -->
                            <div class="mb-4">
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
                                    @if($content->type == 'guide')
                                        Fishing Guide
                                    @elseif($content->type == 'seasonal')
                                        Seasonal Tip
                                    @elseif($content->type == 'quick_tip')
                                        Quick Tip
                                    @endif
                                </span>
                                <span class="text-gray-500 text-sm">{{ $content->created_at->format('F d, Y') }}</span>
                            </div>

                            <!-- Content Title -->
                            <h1 class="text-3xl font-bold mb-6">{{ $content->title }}</h1>

                            <!-- Author Info -->
                            @if($content->user)
                                <div class="flex items-center mb-6">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold mr-3">
                                        {{ substr($content->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $content->user->name }}</p>
                                        <p class="text-sm text-gray-600">Author</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Content Body -->
                            <div class="prose max-w-none">
                                {!! $content->content !!}
                            </div>

                            <!-- Social Sharing -->
                            <div class="mt-8 pt-6 border-t border-gray-200">
                                <h3 class="text-lg font-semibold mb-3">Share this article</h3>
                                <div class="flex space-x-4">
                                    <a href="#" class="text-blue-600 hover:text-blue-800">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-blue-400 hover:text-blue-600">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 10.054 10.054 0 01-3.127 1.184 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-green-600 hover:text-green-800">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm-1.25 16.518l-4.5-4.319 1.396-1.435 3.078 2.937 6.105-6.218 1.421 1.409-7.5 7.626z"/>
                                        </svg>
                                    </a>
                                    <a href="#" class="text-blue-700 hover:text-blue-900">
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>

                    <!-- Related Products -->
                    @if($content->relatedProducts->count() > 0)
                        <div class="mt-8">
                            <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                @foreach($content->relatedProducts as $product)
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                                        <div class="md:flex">
                                            <div class="md:w-1/3">
                                                <div class="h-32 md:h-full bg-gray-200">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center text-gray-500">
                                                            <svg class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="md:w-2/3 p-4">
                                                <h3 class="font-semibold text-lg mb-2">
                                                    <a href="{{ route('products.show', $product->slug) }}" class="text-gray-800 hover:text-blue-700">
                                                        {{ $product->name }}
                                                    </a>
                                                </h3>
                                                <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->short_description, 80) }}</p>
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
                                                        View Product
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:w-1/3">
                    <!-- Content Types -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Content Types</h2>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('content.index') }}" class="block py-2 text-gray-700 hover:text-blue-700">
                                    All Content
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('content.index', ['type' => 'guide']) }}" 
                                   class="block py-2 {{ $content->type == 'guide' ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    Fishing Guides
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('content.index', ['type' => 'seasonal']) }}" 
                                   class="block py-2 {{ $content->type == 'seasonal' ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    Seasonal Tips
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('content.index', ['type' => 'quick_tip']) }}" 
                                   class="block py-2 {{ $content->type == 'quick_tip' ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    Quick Tips
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- More From This Category -->
                    @if($relatedContent->count() > 0)
                        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                            <h2 class="text-xl font-bold mb-4">
                                More 
                                @if($content->type == 'guide')
                                    Fishing Guides
                                @elseif($content->type == 'seasonal')
                                    Seasonal Tips
                                @elseif($content->type == 'quick_tip')
                                    Quick Tips
                                @endif
                            </h2>
                            <div class="space-y-4">
                                @foreach($relatedContent as $related)
                                    <div class="border-b border-gray-200 pb-4 last:border-b-0 last:pb-0">
                                        <a href="{{ route('content.show', $related->slug) }}" class="block hover:text-blue-700">
                                            <h3 class="font-semibold mb-1">{{ $related->title }}</h3>
                                        </a>
                                        <p class="text-sm text-gray-600 mb-2">{{ $related->created_at->format('M d, Y') }}</p>
                                        <p class="text-sm text-gray-700">{{ Str::limit(strip_tags($related->content), 100) }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-center">
                                <a href="{{ route('content.index', ['type' => $content->type]) }}" class="text-blue-700 hover:text-blue-900 font-semibold">
                                    View All
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Newsletter Signup -->
                    <div class="bg-blue-50 rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Get Fishing Tips</h2>
                        <p class="text-gray-700 mb-4">Subscribe to our newsletter and receive the latest fishing tips, guides, and product recommendations.</p>
                        <form action="#" method="POST" class="space-y-4">
                            <div>
                                <input type="email" placeholder="Your email address" class="w-full border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <button type="submit" class="w-full bg-blue-700 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-800 transition">
                                Subscribe
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection