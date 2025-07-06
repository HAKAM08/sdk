@extends('layouts.admin')

@section('title', 'Manage Content')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Content</h1>
        <a href="{{ route('admin.content.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Content
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Content List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($content as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    @if ($item->featured_image)
                                        <img src="{{ asset($item->featured_image) }}" alt="{{ $item->title }}" width="50">
                                    @else
                                        <span class="text-muted">No image</span>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>
                                    <span class="badge badge-{{ $item->type == 'tip' ? 'info' : ($item->type == 'guide' ? 'primary' : 'warning') }}">
                                        {{ ucfirst($item->type) }}
                                    </span>
                                </td>
                                <td>{{ $item->user->name }}</td>
                                <td>
                                    <span class="badge badge-success">Published</span>
                                </td>
                                <td>{{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('content.show', $item->slug) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.content.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.content.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this content?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No content found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $content->links() }}
            </div>
        </div>
    </div>
</div>
@endsection