@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Product: {{ $product->name }}</h1>
        <div>
            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-eye"></i> View on Site
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $product->slug) }}">
                            <small class="text-muted">Leave empty to auto-generate from name</small>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                            <small class="text-muted">Brief summary shown in product listings</small>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Images -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Images</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="image" class="form-label">Main Image</label>
                            @if ($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                            <small class="text-muted">Recommended size: 800x800 pixels. Leave empty to keep current image.</small>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="additional_images" class="form-label">Additional Images</label>
                            @if ($product->additional_images && count($product->additional_images) > 0)
                                <div class="row mb-2">
                                    @foreach ($product->additional_images as $index => $image)
                                        <div class="col-md-3 mb-2">
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Additional image {{ $index + 1 }}" class="img-thumbnail" style="max-height: 100px;">
                                                <div class="form-check position-absolute" style="top: 5px; right: 5px;">
                                                    <input class="form-check-input" type="checkbox" id="remove_image_{{ $index }}" name="remove_images[]" value="{{ $image }}">
                                                    <label class="form-check-label" for="remove_image_{{ $index }}">
                                                        <span class="badge bg-danger">Remove</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" class="form-control @error('additional_images') is-invalid @enderror" id="additional_images" name="additional_images[]" multiple>
                            <small class="text-muted">You can select multiple files. Recommended size: 800x800 pixels</small>
                            @error('additional_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('additional_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Specifications -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Specifications</h6>
                        <button type="button" class="btn btn-sm btn-primary" id="add-spec-btn">
                            <i class="fas fa-plus"></i> Add Specification
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="specifications-container">
                            <!-- Specifications will be added here dynamically -->
                            @if (old('specs'))
                                @foreach (old('specs') as $index => $spec)
                                    <div class="row mb-2 spec-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="specs[{{ $index }}][name]" placeholder="Name (e.g. Weight)" value="{{ $spec['name'] ?? '' }}">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="specs[{{ $index }}][value]" placeholder="Value (e.g. 5.2 oz)" value="{{ $attributeValue->value }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-spec-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @elseif ($product->attributeValues->count() > 0)
                                @foreach ($product->attributeValues as $index => $attributeValue)
                                    <div class="row mb-2 spec-row">
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="specs[{{ $index }}][name]" placeholder="Name (e.g. Weight)" value="{{ $attributeValue->attribute->name }}">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" class="form-control" name="specs[{{ $index }}][value]" placeholder="Value (e.g. 5.2 oz)" value="{{ $spec['value'] ?? '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger remove-spec-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row mb-2 spec-row">
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="specs[0][name]" placeholder="Name (e.g. Weight)">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" class="form-control" name="specs[0][value]" placeholder="Value (e.g. 5.2 oz)">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-spec-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Pricing & Inventory -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Pricing & Inventory</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="price" class="form-label">Regular Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sale_price" class="form-label">Sale Price ($)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}">
                            <small class="text-muted">Leave empty if not on sale</small>
                            @error('sale_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="sku" class="form-label">SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}">
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock Quantity</label>
                            <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}">
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('featured') is-invalid @enderror" type="checkbox" id="featured" name="featured" value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="featured">
                                    Featured Product
                                </label>
                                @error('featured')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        

                    </div>
                </div>
                
                <!-- Categories -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Categories</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Select Categories</label>
                            <div class="categories-list">
                                @php
                                    $selectedCategories = old('categories', $product->categories->pluck('id')->toArray());
                                @endphp
                                
                                @foreach ($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="category_{{ $category->id }}" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category_{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                    @if ($category->children->count() > 0)
                                        <div class="ms-4">
                                            @foreach ($category->children as $childCategory)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="category_{{ $childCategory->id }}" name="categories[]" value="{{ $childCategory->id }}" {{ in_array($childCategory->id, $selectedCategories) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="category_{{ $childCategory->id }}">
                                                        {{ $childCategory->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="d-grid gap-2 mb-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                </div>
                
            </div>
        </div>
    </form>
    
    <!-- Danger Zone -->
    <div class="card shadow border-danger mt-4">
        <div class="card-header py-3 bg-danger text-white">
            <h6 class="m-0 font-weight-bold">Danger Zone</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete Product
                </button>
            </form>
        </div>
    </div>
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
    
    // Add/remove specification fields
    document.getElementById('add-spec-btn').addEventListener('click', function() {
        const container = document.getElementById('specifications-container');
        const specRows = container.querySelectorAll('.spec-row');
        const newIndex = specRows.length;
        
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 spec-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" class="form-control" name="specs[${newIndex}][name]" placeholder="Name (e.g. Weight)">
            </div>
            <div class="col-md-5">
                <input type="text" class="form-control" name="specs[${newIndex}][value]" placeholder="Value (e.g. 5.2 oz)">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-spec-btn">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
        
        container.appendChild(newRow);
        
        // Add event listener to the new remove button
        newRow.querySelector('.remove-spec-btn').addEventListener('click', function() {
            newRow.remove();
        });
    });
    
    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-spec-btn').forEach(button => {
        button.addEventListener('click', function() {
            this.closest('.spec-row').remove();
        });
    });
    
    // Validate sale price is less than regular price
    document.querySelector('form').addEventListener('submit', function(e) {
        const price = parseFloat(document.getElementById('price').value);
        const salePrice = document.getElementById('sale_price').value;
        
        if (salePrice && parseFloat(salePrice) >= price) {
            e.preventDefault();
            alert('Sale price must be less than regular price');
        }
    });
</script>
@endsection