@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Orders</h1>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-flex flex-wrap align-items-center">
                <div class="input-group mb-2 mb-md-0 me-md-3" style="max-width: 300px;">
                    <input type="text" class="form-control" name="search" placeholder="Search by order # or customer" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                <div class="d-flex flex-wrap align-items-center">
                    <div class="me-2 mb-2 mb-md-0">
                        <select name="status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="me-2 mb-2 mb-md-0">
                        <select name="payment_status" class="form-select" onchange="this.form.submit()">
                            <option value="">All Payment Statuses</option>
                            @foreach($paymentStatuses as $value => $label)
                                <option value="{{ $value }}" {{ request('payment_status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <span class="me-2">Sort by:</span>
                        <select name="sort" class="form-select me-2" onchange="this.form.submit()">
                            <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Date</option>
                            <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Order #</option>
                            <option value="total" {{ request('sort') == 'total' ? 'selected' : '' }}>Total</option>
                            <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Status</option>
                            <option value="payment_status" {{ request('sort') == 'payment_status' ? 'selected' : '' }}>Payment</option>
                        </select>
                        <select name="direction" class="form-select" onchange="this.form.submit()">
                            <option value="desc" {{ request('direction', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>${{ number_format($order->total, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection