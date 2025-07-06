@extends('layouts.admin')

@section('title', 'Manage Products')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Products</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Product
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex flex-wrap align-items-center">
                <div class="input-group mb-2 mb-md-0 me-md-3" style="max-width: 300px;">
                    <input type="text" class="form-control" name="search" placeholder="Search by name, SKU, or description" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2">Sort by:</span>
                    <select name="sort" class="form-select me-2" onchange="this.form.submit()">
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                        <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stock</option>
                        <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>ID</option>
                    </select>
                    <select name="direction" class="form-select" onchange="this.form.submit()">
                        <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" width="50">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->sku }}</td>
                                <td>
                                    @if ($product->sale_price)
                                        <span class="text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-danger">${{ number_format($product->sale_price, 2) }}</span>
                                    @else
                                        ${{ number_format($product->price, 2) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($product->stock_quantity > 10)
                                        <span class="badge bg-success">{{ $product->stock_quantity }}</span>
                                    @elseif ($product->stock_quantity > 0)
                                        <span class="badge bg-warning">{{ $product->stock_quantity }}</span>
                                    @else
                                        <span class="badge bg-danger">Out of stock</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($product->categories as $category)
                                        <span class="badge bg-info">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection