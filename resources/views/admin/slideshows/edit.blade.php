@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Slideshow</h1>
        <a href="{{ route('admin.slideshows.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Slideshows
        </a>
    </div>

    <!-- Alert Messages -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Slideshow Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.slideshows.update', $slideshow->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $slideshow->title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="order">Display Order <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="order" name="order" value="{{ old('order', $slideshow->order) }}" min="1" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $slideshow->description) }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="link">Link URL</label>
                    <input type="text" class="form-control" id="link" name="link" value="{{ old('link', $slideshow->link) }}" placeholder="https://example.com">
                    <small class="form-text text-muted">Where users will be directed when they click on the slideshow.</small>
                </div>
                
                <div class="form-group">
                    <label for="image">Slideshow Image</label>
                    @if($slideshow->image)
                        <div class="mb-2">
                            <img src="{{ $slideshow->image }}" alt="{{ $slideshow->title }}" style="max-width: 300px; max-height: 200px;" class="img-thumbnail">
                        </div>
                    @endif
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image">
                        <label class="custom-file-label" for="image">Choose new image (leave empty to keep current)</label>
                    </div>
                    <small class="form-text text-muted">Recommended size: 1920x600 pixels. Max file size: 2MB.</small>
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', $slideshow->is_active) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Update Slideshow</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show filename in file input
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
</script>
@endsection