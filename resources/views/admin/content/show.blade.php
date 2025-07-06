@extends('layouts.admin')

@section('title', 'View Content')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $content->title }}</h1>
        <div>
            <a href="{{ route('content.show', $content->slug) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-eye"></i> View on Site
            </a>
            <a href="{{ route('admin.content.edit', $content->id) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('admin.content.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Details</h6>
                </div>
                <div class="card-body">
                    @if($content->featured_image)
                        <div class="text-center mb-4">
                            <img src="{{ asset($content->featured_image) }}" alt="{{ $content->title }}" class="img-fluid rounded" style="max-height: 400px;">
                        </div>
                    @endif

                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="badge badge-{{ $content->type == 'tip' ? 'info' : ($content->type == 'guide' ? 'primary' : 'warning') }} mr-2">
                                    {{ ucfirst($content->type) }}
                                </span>
                                <span class="badge badge-success">
                                    Published
                                </span>
                            </div>
                            <small class="text-muted">Created: {{ $content->created_at ? $content->created_at->format('M d, Y') : 'N/A' }}</small>
                        </div>
                    </div>

                    <div class="content-body">
                        {!! $content->content !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Content Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Author</h6>
                        <p>{{ $content->user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Created At</h6>
                        <p>{{ $content->created_at ? $content->created_at->format('F d, Y H:i') : 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Last Updated</h6>
                        <p>{{ $content->updated_at ? $content->updated_at->format('F d, Y H:i') : 'N/A' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Status</h6>
                        <p>
                            <span class="badge badge-success">
                                Published
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Type</h6>
                        <p>
                            <span class="badge badge-{{ $content->type == 'tip' ? 'info' : ($content->type == 'guide' ? 'primary' : 'warning') }}">
                                {{ ucfirst($content->type) }}
                            </span>
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Slug</h6>
                        <p class="text-muted">{{ $content->slug }}</p>
                    </div>
                </div>
            </div>

            @if($content->relatedProducts->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Related Products</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($content->relatedProducts as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="40" class="mr-2">
                                        @endif
                                        {{ $product->name }}
                                    </div>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">SEO Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="font-weight-bold">Meta Title</h6>
                        <p class="text-muted">{{ $content->meta_title ?: 'Not set' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Meta Description</h6>
                        <p class="text-muted">{{ $content->meta_description ?: 'Not set' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="font-weight-bold">Meta Keywords</h6>
                        <p class="text-muted">{{ $content->meta_keywords ?: 'Not set' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection