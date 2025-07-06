@extends('layouts.app')

@section('title', $category->name)

@section('content')
    <!-- Category Detail Page -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('categories.index') }}" class="hover:text-blue-700">Categories</a>
                    @if($category->parent)
                        <span class="mx-2">/</span>
                        <a href="{{ route('categories.show', $category->parent->slug) }}" class="hover:text-blue-700">{{ $category->parent->name }}</a>
                    @endif
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">{{ $category->name }}</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Categories</h2>
                        <ul class="space-y-2">
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('categories.show', $cat->slug) }}" 
                                       class="block py-2 {{ $cat->id === $category->id ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                        {{ $cat->name }}
                                    </a>
                                    
                                    @if($cat->children->count() > 0)
                                        <ul class="pl-4 space-y-1 mt-1">
                                            @foreach($cat->children as $child)
                                                <li>
                                                    <a href="{{ route('categories.show', $child->slug) }}" 
                                                       class="block py-1 {{ $child->id === $category->id ? 'text-blue-700 font-semibold' : 'text-gray-600 hover:text-blue-700' }}">
                                                        {{ $child->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h1 class="text-3xl font-bold mb-4">{{ $category->name }}</h1>
                        <p class="text-gray-700 mb-6">{{ $category->description }}</p>
                        
                        @if($category->children->count() > 0)
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold mb-4">Subcategories</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($category->children as $child)
                                        <a href="{{ route('categories.show', $child->slug) }}" class="block bg-gray-100 hover:bg-blue-100 p-4 rounded-lg transition">
                                            <h3 class="font-semibold text-blue-700">{{ $child->name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($child->description, 60) }}</p>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Products -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Products</h2>
                            
                            <div class="flex items-center">
                                <span class="text-gray-600 mr-2">Sort by:</span>
                                <select id="sort" class="border border-gray-300 rounded-md text-gray-700 py-1 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                    <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                    <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price (Low to High)</option>
                                    <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price (High to Low)</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                </select>
                            </div>
                        </div>

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
                                            <div class="mb-2">
                                                @foreach($product->categories->take(2) as $productCategory)
                                                    <a href="{{ route('categories.show', $productCategory->slug) }}" class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-1 hover:bg-blue-200 transition">
                                                        {{ $productCategory->name }}
                                                    </a>
                                                @endforeach
                                            </div>
                                            
                                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                                <h3 class="font-semibold text-lg mb-2 hover:text-blue-700">{{ $product->name }}</h3>
                                            </a>
                                            
                                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($product->short_description, 100) }}</p>
                                            
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
                                {{ $products->links() }}
                            </div>
                        @else
                            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                                <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-700 mb-2">No products found</h3>
                                <p class="text-gray-600">There are no products available in this category yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.getElementById('sort').addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', this.value);
            window.location.href = url.toString();
        });
    </script>
@endsection