@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New User
        </a>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex flex-wrap align-items-center">
                <div class="input-group mb-2 mb-md-0 me-md-3" style="max-width: 300px;">
                    <input type="text" class="form-control" name="search" placeholder="Search by name or email" value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2">Sort by:</span>
                    <select name="sort" class="form-select me-2" onchange="this.form.submit()">
                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>Date</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>Email</option>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if ($user->is_admin)
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-info">Customer</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (auth()->id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection