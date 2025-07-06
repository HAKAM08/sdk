@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Product Details: {{ $product->name }}</h1>
        <div>
            <a href="{{ route('products.show', $product->slug) }}" class="btn btn-info" target="_blank">
                <i class="fas fa-eye"></i> View on Site
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Product
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> {{ $product->id }}</p>
                            <p><strong>Name:</strong> {{ $product->name }}</p>
                            <p><strong>Slug:</strong> {{ $product->slug }}</p>
                            <p><strong>SKU:</strong> {{ $product->sku }}</p>
                            <p><strong>Status:</strong> 
                                @if ($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                            <p><strong>Featured:</strong> 
                                @if ($product->is_featured)
                                    <span class="badge bg-primary">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>
                            <p><strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i') }}</p>
                            <p><strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Regular Price:</strong> ${{ number_format($product->price, 2) }}</p>
                            <p><strong>Sale Price:</strong> 
                                @if ($product->sale_price)
                                    ${{ number_format($product->sale_price, 2) }}
                                    <span class="badge bg-danger">
                                        {{ round((1 - $product->sale_price / $product->price) * 100) }}% OFF
                                    </span>
                                @else
                                    <span class="text-muted">Not on sale</span>
                                @endif
                            </p>
                            <p><strong>Stock Quantity:</strong> 
                                @if ($product->stock_quantity > 10)
                                    <span class="text-success">{{ $product->stock_quantity }}</span>
                                @elseif ($product->stock_quantity > 0)
                                    <span class="text-warning">{{ $product->stock_quantity }} (Low Stock)</span>
                                @else
                                    <span class="text-danger">Out of Stock</span>
                                @endif
                            </p>
                            <p><strong>Categories:</strong></p>
                            <div class="mb-3">
                                @forelse ($product->categories as $category)
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="badge bg-info text-decoration-none mb-1">
                                        {{ $category->name }}
                                    </a>
                                @empty
                                    <span class="text-muted">No categories assigned</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h5>Short Description</h5>
                        <p>{{ $product->short_description }}</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Full Description</h5>
                        <div>{!! $product->description !!}</div>
                    </div>
                </div>
            </div>
            
            <!-- Specifications -->
            @if ($product->specifications && count($product->specifications) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Specifications</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Specification</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->specifications as $spec)
                                    <tr>
                                        <td>{{ $spec['name'] }}</td>
                                        <td>{{ $spec['value'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Order History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order History</h6>
                </div>
                <div class="card-body">
                    @if ($orderItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $item->order_id) }}">#{{ $item->order_id }}</a>
                                            </td>
                                            <td>{{ $item->order->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $item->order->user_id) }}">
                                                    {{ $item->order->user->name }}
                                                </a>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orderItems->links() }}
                        </div>
                    @else
                        <p class="text-muted">This product has not been ordered yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Product Images -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Product Images</h6>
                </div>
                <div class="card-body">
                    @if ($product->image)
                        <div class="mb-3">
                            <h6>Main Image</h6>
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
                        </div>
                    @endif
                    
                    @if ($product->additional_images && count($product->additional_images) > 0)
                        <h6>Additional Images</h6>
                        <div class="row">
                            @foreach ($product->additional_images as $image)
                                <div class="col-6 mb-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Additional image" class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    @if (!$product->image && (!$product->additional_images || count($product->additional_images) === 0))
                        <p class="text-muted">No images available for this product.</p>
                    @endif
                </div>
            </div>
            
            <!-- Related Products -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Related Products</h6>
                </div>
                <div class="card-body">
                    @if ($relatedProducts->count() > 0)
                        <div class="list-group">
                            @foreach ($relatedProducts as $relatedProduct)
                                <a href="{{ route('admin.products.show', $relatedProduct) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            @if ($relatedProduct->image)
                                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}" width="40" height="40" class="me-2">
                                            @endif
                                            {{ $relatedProduct->name }}
                                        </div>
                                        <small>${{ number_format($relatedProduct->price, 2) }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No related products found.</p>
                    @endif
                </div>
            </div>
            
            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Product
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Product
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection