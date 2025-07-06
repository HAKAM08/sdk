@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Order #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Order
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Information -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Order Date:</div>
                        <div class="col-md-8">{{ $order->created_at ? $order->created_at->format('F d, Y h:i A') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Status:</div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Payment Method:</div>
                        <div class="col-md-8">{{ ucfirst($order->payment_method) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Payment Status:</div>
                        <div class="col-md-8">
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Tracking Number:</div>
                        <div class="col-md-8">{{ $order->tracking_number ?: 'Not available' }}</div>
                    </div>
                    @if ($order->notes)
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Notes:</div>
                        <div class="col-md-8">{{ $order->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Name:</div>
                        <div class="col-md-8">{{ $order->user->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Email:</div>
                        <div class="col-md-8">{{ $order->user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Phone:</div>
                        <div class="col-md-8">{{ $order->user->phone ?: 'Not provided' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Shipping Address:</div>
                        <div class="col-md-8">{{ $order->shipping_address ?: 'Not provided' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Billing Address:</div>
                        <div class="col-md-8">{{ $order->billing_address ?: 'Same as shipping' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Order Items</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if ($item->product && $item->product->image)
                                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product_name }}" width="50" class="me-3">
                                        @endif
                                        <div>
                                            <div>{{ $item->product_name }}</div>
                                            @if ($item->product)
                                                <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Subtotal:</th>
                            <th>${{ number_format($order->total, 2) }}</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Shipping:</th>
                            <th>$0.00</th>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th>${{ number_format($order->total, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection