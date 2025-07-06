@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Category: {{ $category->name }}</h1>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Categories
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}">
                    <small class="text-muted">Leave empty to auto-generate from name</small>
                    @error('slug')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="parent_id" class="form-label">Parent Category</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                        <option value="">None (Top Level Category)</option>
                        @foreach ($categories as $parentCategory)
                            @if ($parentCategory->id !== $category->id && !in_array($parentCategory->id, $childrenIds))
                                <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                    <small class="text-muted">A category cannot be its own parent or child</small>
                    @error('parent_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Category Image</label>
                    @if ($category->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="img-thumbnail" style="max-height: 150px;">
                        </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                    <small class="text-muted">Recommended size: 800x600 pixels. Leave empty to keep current image.</small>
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input @error('is_active') is-invalid @enderror" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $category->is_active) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="display_order" class="form-label">Display Order</label>
                    <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $category->display_order) }}">
                    <small class="text-muted">Lower numbers appear first</small>
                    @error('display_order')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    @if ($category->products_count > 0 || $category->children_count > 0)
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
        </div>
        <div class="card-body">
            <div class="alert alert-warning">
                <h5 class="alert-heading">Warning!</h5>
                <p>This category has {{ $category->products_count }} products and {{ $category->children_count }} subcategories associated with it.</p>
                <p>Deleting this category will:</p>
                <ul>
                    <li>Remove all product associations with this category</li>
                    <li>Move all subcategories to the parent category (if any) or make them top-level categories</li>
                </ul>
            </div>
            
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Category
                </button>
            </form>
        </div>
    </div>
    @else
    <div class="card shadow mt-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-danger">Danger Zone</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Category
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('input', function() {
        const nameValue = this.value;
        const slugField = document.getElementById('slug');
        
        // Only update slug if it's empty or hasn't been manually edited
        if (!slugField.value || slugField._autoGenerated) {
            slugField.value = nameValue
                .toLowerCase()
                .replace(/[^\w\s-]/g, '') // Remove special chars
                .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
                .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
            slugField._autoGenerated = true;
        }
    });
    
    document.getElementById('slug').addEventListener('input', function() {
        // Mark as manually edited
        this._autoGenerated = false;
    });
</script>
@endsection