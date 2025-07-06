@extends('layouts.admin')

@section('title', 'Edit Content')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Content</h1>
        <a href="{{ route('admin.content.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Content List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Content Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.content.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $content->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="content">Content <span class="text-danger">*</span></label>
                            <textarea class="form-control summernote @error('content') is-invalid @enderror" id="content" name="content" rows="10">{{ old('content', $content->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="type">Content Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Select Type</option>
                                <option value="tip" {{ old('type', $content->type) == 'tip' ? 'selected' : '' }}>Fishing Tip</option>
                                <option value="guide" {{ old('type', $content->type) == 'guide' ? 'selected' : '' }}>Fishing Guide</option>
                                <option value="seasonal" {{ old('type', $content->type) == 'seasonal' ? 'selected' : '' }}>Seasonal Information</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="featured_image">Featured Image</label>
                            @if($content->featured_image)
                                <div class="mb-2">
                                    <img src="{{ asset($content->featured_image) }}" alt="{{ $content->title }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control-file @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image">
                            <small class="form-text text-muted">Recommended size: 1200x800 pixels</small>
                            @error('featured_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Content is always published -->

                        <div class="form-group">
                            <label for="related_products">Related Products</label>
                            <select class="form-control select2 @error('related_products') is-invalid @enderror" id="related_products" name="related_products[]" multiple>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ in_array($product->id, old('related_products', $selectedProducts)) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('related_products')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" id="meta_title" name="meta_title" value="{{ old('meta_title', $content->meta_title) }}">
                            @error('meta_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $content->meta_description) }}</textarea>
                            @error('meta_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $content->meta_keywords) }}">
                            @error('meta_keywords')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Content
                    </button>
                    <a href="{{ route('admin.content.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4 mt-4">
        <div class="card-header py-3 bg-danger text-white">
            <h6 class="m-0 font-weight-bold">Danger Zone</h6>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="text-danger">Delete this content</h5>
                    <p class="text-muted">Once deleted, this content cannot be recovered.</p>
                </div>
                <form action="{{ route('admin.content.destroy', $content->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this content? This action cannot be undone.')">
                        <i class="fas fa-trash"></i> Delete Content
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        // Initialize Select2 for better multi-select experience
        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: "Select related products",
                allowClear: true
            });
        }
    });
</script>
@endsection