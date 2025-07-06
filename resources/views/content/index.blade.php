@extends('layouts.app')

@section('title', 'Fishing Tips & Guides')

@section('content')
    <!-- Content Index Page -->
    <section class="py-12">
        <div class="container mx-auto px-4">
            <!-- Breadcrumbs -->
            <div class="mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-blue-700">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">Fishing Tips & Guides</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Sidebar -->
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Content Types</h2>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('content.index') }}" 
                                   class="block py-2 {{ !request('type') ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    All Content
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('content.index', ['type' => 'guide']) }}" 
                                   class="block py-2 {{ request('type') == 'guide' ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    Fishing Guides
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('content.index', ['type' => 'seasonal']) }}" 
                                   class="block py-2 {{ request('type') == 'seasonal' ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    Seasonal Tips
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('content.index', ['type' => 'quick_tip']) }}" 
                                   class="block py-2 {{ request('type') == 'quick_tip' ? 'text-blue-700 font-semibold' : 'text-gray-700 hover:text-blue-700' }}">
                                    Quick Tips
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-bold mb-4">Search</h2>
                        <form action="{{ route('content.index') }}" method="GET">
                            @if(request('type'))
                                <input type="hidden" name="type" value="{{ request('type') }}">
                            @endif
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search content..." 
                                       class="w-full border border-gray-300 rounded-md py-2 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <button type="submit" class="absolute right-2 top-2 text-gray-500 hover:text-blue-700">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:w-3/4">
                    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                        <h1 class="text-3xl font-bold mb-2">
                            @if(request('type') == 'guide')
                                Fishing Guides
                            @elseif(request('type') == 'seasonal')
                                Seasonal Fishing Tips
                            @elseif(request('type') == 'quick_tip')
                                Quick Fishing Tips
                            @else
                                Fishing Tips & Guides
                            @endif
                        </h1>
                        
                        <p class="text-gray-700 mb-0">
                            @if(request('type') == 'guide')
                                Comprehensive guides to help you master various fishing techniques and approaches.
                            @elseif(request('type') == 'seasonal')
                                Seasonal advice to help you adapt your fishing strategy throughout the year.
                            @elseif(request('type') == 'quick_tip')
                                Quick and practical tips to improve your fishing experience.
                            @else
                                Explore our collection of fishing knowledge, tips, and guides to enhance your angling skills.
                            @endif
                        </p>
                    </div>

                    @if(request('search'))
                        <div class="mb-6">
                            <p class="text-gray-700">Search results for: <span class="font-semibold">"{{ request('search') }}"</span></p>
                        </div>
                    @endif

                    <!-- Content List -->
                    @if($content->count() > 0)
                        <div class="space-y-8">
                            @foreach($content as $contentItem)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden transition transform hover:-translate-y-1 hover:shadow-lg">
                                    <div class="md:flex">
                                        <div class="md:w-1/3">
                                            <div class="h-48 md:h-full bg-gray-200">
                                                @if($contentItem->featured_image)
                                                    <img src="{{ $contentItem->featured_image }}" alt="{{ $contentItem->title }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-500 bg-blue-50">
                                                        <svg class="h-16 w-16" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5-7l-3 3.72L9 13l-3 4h12l-4-5z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="md:w-2/3 p-6">
                                            <div class="mb-2">
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full mr-2">
                                                    @if($contentItem->type == 'guide')
                                                        Fishing Guide
                                                    @elseif($contentItem->type == 'seasonal')
                                                        Seasonal Tip
                                                    @elseif($contentItem->type == 'quick_tip')
                                                        Quick Tip
                                                    @endif
                                                </span>
                                                <span class="text-gray-500 text-sm">{{ $contentItem->created_at->format('M d, Y') }}</span>
                                            </div>
                                            
                                            <h2 class="text-xl font-bold mb-3">
                                                <a href="{{ route('content.show', $contentItem->slug) }}" class="text-gray-800 hover:text-blue-700">
                                                    {{ $contentItem->title }}
                                                </a>
                                            </h2>
                                            
                                            <div class="text-gray-600 mb-4">
                                                {!! Str::limit(strip_tags($contentItem->content), 200) !!}
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    @if($contentItem->relatedProducts->count() > 0)
                                                        <span class="text-sm text-gray-600">Related Products: {{ $contentItem->relatedProducts->count() }}</span>
                                                    @endif
                                                </div>
                                                <a href="{{ route('content.show', $contentItem->slug) }}" class="inline-block bg-blue-700 text-white py-2 px-4 rounded-md font-semibold hover:bg-blue-800 transition">
                                                    Read More
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $content->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="bg-white rounded-lg shadow-md p-8 text-center">
                            <svg class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-700 mb-2">No content found</h3>
                            <p class="text-gray-600">
                                @if(request('search'))
                                    No content matches your search criteria. Try different keywords or browse our categories.
                                @else
                                    There is no content available in this category yet. Please check back later.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection