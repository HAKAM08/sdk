@extends('layouts.app')

@section('title', 'Categories')

@section('content')
    <!-- Categories Page -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">Categories</span>
                </div>
            </div>

            <h1 class="text-3xl font-bold mb-8">Product Categories</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($categories as $category)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-bold mb-4">
                                <a href="{{ route('categories.show', $category->slug) }}" class="text-blue-700 hover:text-blue-900">
                                    {{ $category->name }}
                                </a>
                            </h2>
                            
                            <p class="text-gray-600 mb-6">{{ $category->description }}</p>
                            
                            @if($category->children->count() > 0)
                                <div class="mb-4">
                                    <h3 class="text-lg font-semibold mb-2">Subcategories:</h3>
                                    <ul class="space-y-1">
                                        @foreach($category->children as $child)
                                            <li>
                                                <a href="{{ route('categories.show', $child->slug) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            <a href="{{ route('categories.show', $category->slug) }}" class="inline-block bg-blue-700 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-800 transition">
                                Browse Products
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection