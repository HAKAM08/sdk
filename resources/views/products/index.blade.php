@extends('layouts.app')

@section('title', 'Shop Fishing Tackle')

@section('content')
    <!-- Products Header -->
    <section class="bg-blue-700 text-white py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Shop Fishing Tackle</h1>
            <p class="text-lg">Browse our premium selection of fishing gear and equipment.</p>
        </div>
    </section>

    <!-- Products Content -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Categories</h2>
                        <ul class="space-y-2">
                            @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('categories.show', $category->slug) }}" class="text-blue-700 hover:text-blue-900 font-medium">{{ $category->name }}</a>
                                    @if($category->children->count() > 0)
                                        <ul class="ml-4 mt-2 space-y-1">
                                            @foreach($category->children as $child)
                                                <li>
                                                    <a href="{{ route('categories.show', $child->slug) }}" class="text-gray-700 hover:text-blue-700">{{ $child->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Filter Products</h2>
                        <form action="{{ route('products.index') }}" method="GET">
                            @if(request()->has('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif

                            <div class="mb-4">
                                <label for="search" class="block text-gray-700 mb-2">Search</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" class="w-full px-4 py-2 border rounded-md">
                            </div>

                            <div class="mb-4">
                                <label for="sort" class="block text-gray-700 mb-2">Sort By</label>
                                <select id="sort" name="sort" class="w-full px-4 py-2 border rounded-md">
                                    <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                                    <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="direction" class="block text-gray-700 mb-2">Direction</label>
                                <select id="direction" name="direction" class="w-full px-4 py-2 border rounded-md">
                                    <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                                    <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                </select>
                            </div>

                            <button type="submit" class="w-full bg-blue-700 text-white py-2 px-4 rounded-md hover:bg-blue-800 transition">Apply Filters</button>
                        </form>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="lg:w-3/4">
                    @if(request()->has('search') || request()->has('category'))
                        <div class="mb-6">
                            <h2 class="text-xl font-bold">
                                @if(request()->has('category'))
                                    Category: {{ ucfirst(request('category')) }}
                                @endif
                                @if(request()->has('search'))
                                    @if(request()->has('category')) | @endif
                                    Search: "{{ request('search') }}"
                                @endif
                            </h2>
                            <a href="{{ route('products.index') }}" class="text-blue-700 hover:text-blue-900">Clear filters</a>
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($products as $product)
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

                        <div class="mt-8">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <h3 class="text-xl font-bold mb-2">No products found</h3>
                            <p class="text-gray-600 mb-4">We couldn't find any products matching your criteria.</p>
                            <a href="{{ route('products.index') }}" class="inline-block bg-blue-700 text-white px-6 py-2 rounded-md hover:bg-blue-800 transition">View All Products</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection