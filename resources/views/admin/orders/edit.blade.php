@extends('layouts.admin')

@section('title', 'Edit Order')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit Order #{{ $order->id }}</h1>
        <div>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
                <i class="fas fa-eye"></i> View Order
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status" class="form-label">Order Status</label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    @foreach ($statuses as $key => $value)
                                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="payment_status" class="form-label">Payment Status</label>
                                <select class="form-select @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                                    @foreach ($paymentStatuses as $key => $value)
                                        <option value="{{ $key }}" {{ $order->payment_status === $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('payment_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tracking_number" class="form-label">Tracking Number</label>
                            <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}">
                            @error('tracking_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Order Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes', $order->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Order Date:</div>
                        <div class="col-6 text-end">{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Customer:</div>
                        <div class="col-6 text-end">{{ $order->user->name }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Email:</div>
                        <div class="col-6 text-end">{{ $order->user->email }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Payment Method:</div>
                        <div class="col-6 text-end">{{ ucfirst($order->payment_method) }}</div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Subtotal:</div>
                        <div class="col-6 text-end">${{ number_format($order->total, 2) }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Shipping:</div>
                        <div class="col-6 text-end">$0.00</div>
                    </div>
                    <div class="row">
                        <div class="col-6 fw-bold">Total:</div>
                        <div class="col-6 text-end fw-bold">${{ number_format($order->total, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Shipping Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Information</h6>
                </div>
                <div class="card-body">
                    <p><strong>Address:</strong><br>{{ $order->shipping_address }}</p>
                    
                    @if ($order->billing_address && $order->billing_address !== $order->shipping_address)
                        <hr>
                        <p><strong>Billing Address:</strong><br>{{ $order->billing_address }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection