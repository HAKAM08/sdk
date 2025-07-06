@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add New Ad Space</h1>
        <a href="{{ route('admin.adspaces.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Ad Spaces
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
            <h6 class="m-0 font-weight-bold text-primary">Ad Space Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.adspaces.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="position">Position <span class="text-danger">*</span></label>
                        <select class="form-control" id="position" name="position" required>
                            <option value="">Select Position</option>
                            <option value="left" {{ old('position') == 'left' ? 'selected' : '' }}>Left</option>
                            <option value="right" {{ old('position') == 'right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="link">Link URL</label>
                    <input type="text" class="form-control" id="link" name="link" value="{{ old('link') }}" placeholder="https://example.com">
                    <small class="form-text text-muted">Where users will be directed when they click on the ad.</small>
                </div>
                
                <div class="form-group">
                    <label for="image">Ad Image <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="image" name="image" required>
                        <label class="custom-file-label" for="image">Choose file...</label>
                    </div>
                    <small class="form-text text-muted">Recommended size: 300x600 pixels for side ads. Max file size: 2MB.</small>
                </div>
                
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_active">Active</label>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">Save Ad Space</button>
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