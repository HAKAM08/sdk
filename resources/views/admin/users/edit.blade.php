@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Edit User: {{ $user->name }}</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('is_admin') is-invalid @enderror" type="checkbox" id="is_admin" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_admin">
                                    Administrator
                                </label>
                                <small class="d-block text-muted">Administrators have full access to manage the store</small>
                                @error('is_admin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                        </div>
                    </form>
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
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Registered:</div>
                        <div class="col-6 text-end">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Last Login:</div>
                        <div class="col-6 text-end">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Orders:</div>
                        <div class="col-6 text-end">{{ $user->orders_count }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6 text-muted">Total Spent:</div>
                        <div class="col-6 text-end">${{ number_format($user->total_spent, 2) }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Danger Zone -->
            @if (auth()->id() !== $user->id)
            <div class="card shadow border-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Delete User
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="card shadow border-warning">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">Note</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">You cannot delete your own account.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Password strength validation
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        
        if (!password) return; // Skip if empty (keeping current password)
        
        let strengthMeter;
        if (!this.nextElementSibling.classList.contains('password-strength')) {
            strengthMeter = document.createElement('div');
            strengthMeter.className = 'password-strength mt-2';
            this.parentNode.insertBefore(strengthMeter, this.nextElementSibling);
        } else {
            strengthMeter = this.nextElementSibling;
        }
        
        // Check password strength
        let strength = 0;
        if (password.length >= 8) strength += 1;
        if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
        if (password.match(/\d/)) strength += 1;
        if (password.match(/[^a-zA-Z\d]/)) strength += 1;
        
        // Update strength meter
        let strengthText = '';
        let strengthClass = '';
        
        switch (strength) {
            case 0:
            case 1:
                strengthText = 'Weak';
                strengthClass = 'text-danger';
                break;
            case 2:
                strengthText = 'Fair';
                strengthClass = 'text-warning';
                break;
            case 3:
                strengthText = 'Good';
                strengthClass = 'text-info';
                break;
            case 4:
                strengthText = 'Strong';
                strengthClass = 'text-success';
                break;
        }
        
        strengthMeter.innerHTML = `<small class="${strengthClass}">Password strength: ${strengthText}</small>`;
    });
    
    // Password confirmation validation
    document.getElementById('password_confirmation').addEventListener('input', function() {
        const password = document.getElementById('password').value;
        const confirmation = this.value;
        
        if (confirmation && password !== confirmation) {
            this.setCustomValidity('Passwords do not match');
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endsection