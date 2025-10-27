@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Blog Categories')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Blog Categories</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Blog Categories
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-right">
                <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> Add New Category
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Level</th>
                                <th>Parent</th>
                                <th>Posts</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        @if ($category->description)
                                            <br><small
                                                class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($category->level == 1)
                                            <span class="badge bg-primary">Parent</span>
                                        @elseif($category->level == 2)
                                            <span class="badge bg-info">Category</span>
                                        @else
                                            <span class="badge bg-secondary">Sub-Category</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($category->parent)
                                            {{ $category->parent->name }}
                                        @else
                                            <span class="text-muted">Root</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $category->posts()->count() }}</span>
                                    </td>
                                    <td>
                                        @if ($category->is_active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.blog-categories.edit', $category->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.blog-categories.destroy', $category->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this category?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fa fa-folder-open" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2">No blog categories found. <a
                                                    href="{{ route('admin.blog-categories.create') }}">Create your first
                                                    category</a></p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
