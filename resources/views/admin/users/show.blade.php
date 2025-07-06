@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>User Details: {{ $user->name }}</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>ID:</strong> {{ $user->id }}</p>
                            <p><strong>Name:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                            <p><strong>Role:</strong> 
                                @if ($user->is_admin)
                                    <span class="badge bg-danger">Administrator</span>
                                @else
                                    <span class="badge bg-primary">Customer</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Registered:</strong> {{ $user->created_at ? $user->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                            <p><strong>Last Updated:</strong> {{ $user->updated_at ? $user->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                            <p><strong>Last Login:</strong> 
                                @if ($user->last_login_at)
                                    {{ \Carbon\Carbon::parse($user->last_login_at)->format('M d, Y H:i') }}
                                @else
                                    <span class="text-muted">Never</span>
                                @endif
                            </p>
                            <p><strong>Total Orders:</strong> {{ $orderCount }}</p>
                            <p><strong>Total Spent:</strong> ${{ number_format($totalSpent, 2) }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h5>Address</h5>
                        @if ($user->address)
                            <p>{{ $user->address }}</p>
                        @else
                            <p class="text-muted">No address provided</p>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Order History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order History</h6>
                </div>
                <div class="card-body">
                    @if ($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>#{{ $order->id }}</td>
                                            <td>{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                                            <td>{{ $order->items->sum('quantity') }}</td>
                                            <td>${{ number_format($order->total, 2) }}</td>
                                            <td>
                                                @if ($order->status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($order->status == 'processing')
                                                    <span class="badge bg-info">Processing</span>
                                                @elseif ($order->status == 'shipped')
                                                    <span class="badge bg-primary">Shipped</span>
                                                @elseif ($order->status == 'delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @elseif ($order->status == 'cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->payment_status == 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif ($order->payment_status == 'pending')
                                                    <span class="badge bg-warning">Pending</span>
                                                @elseif ($order->payment_status == 'failed')
                                                    <span class="badge bg-danger">Failed</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($order->payment_status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <p class="text-muted">This user has not placed any orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- User Stats -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Total Orders</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $orderCount }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Total Spent</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalSpent, 2) }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Avg. Order Value</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                ${{ $orderCount > 0 ? number_format($totalSpent / $orderCount, 2) : '0.00' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Days as Member</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $user->created_at ? $user->created_at->diffInDays(now()) : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    @if ($recentActivity->count() > 0)
                        <div class="timeline">
                            @foreach ($recentActivity as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">
                                            @if ($activity->type == 'order')
                                                Placed Order #{{ $activity->id }}
                                            @elseif ($activity->type == 'login')
                                                Logged In
                                            @elseif ($activity->type == 'profile_update')
                                                Updated Profile
                                            @endif
                                        </h6>
                                        <p class="timeline-date">{{ $activity->created_at ? $activity->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No recent activity recorded.</p>
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
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                        @if (auth()->id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash"></i> Delete User
                                </button>
                            </form>
                        @else
                            <button class="btn btn-danger w-100" disabled>
                                <i class="fas fa-trash"></i> Cannot Delete Your Own Account
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Timeline styling */
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 20px;
    }
    
    .timeline-marker {
        position: absolute;
        left: -30px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #4e73df;
        top: 5px;
    }
    
    .timeline-content {
        padding-bottom: 10px;
    }
    
    .timeline-title {
        margin-bottom: 5px;
    }
    
    .timeline-date {
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@endsection