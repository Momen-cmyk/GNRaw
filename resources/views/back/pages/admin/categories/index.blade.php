@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Categories')

@push('stylesheets')
    @livewireStyles
@endpush

@section('content')
    @livewire('admin.category-manager')
@endsection

@push('scripts')
    @livewireScripts
@endpush

{{-- Old implementation kept for reference
@section('content')
<!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Product Categories</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Categories</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="text-blue h4">All Categories</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="icon-copy fa fa-plus"></i> Add New Category
                    </a>
                </div>
            </div>
        </div>
        <div class="pb-20">
            <table class="table hover nowrap">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Description</th>
                        <th>Products</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                @if ($category->image)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div
                                        style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: none; align-items: center; justify-content: center;">
                                        <i class="icon-copy fa fa-image text-muted"></i>
                                    </div>
                                @else
                                    <div
                                        style="width: 50px; height: 50px; background: #f8f9fa; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                        <i class="icon-copy fa fa-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td><strong>{{ $category->name }}</strong></td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $category->publicProducts()->count() }}</span>
                            </td>
                            <td>{{ $category->sort_order ?? 0 }}</td>
                            <td>
                                @if ($category->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle"
                                        href="#" role="button" data-toggle="dropdown">
                                        <i class="dw dw-more"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                        <a class="dropdown-item"
                                            href="{{ route('admin.categories.edit', $category->id) }}">
                                            <i class="dw dw-edit2"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.delete', $category->id) }}" method="POST"
                                            style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item"
                                                onclick="return confirm('Are you sure you want to delete this category?')">
                                                <i class="dw dw-delete-3"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="icon-copy fa fa-folder-open" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No categories found</p>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary mt-2">
                                    <i class="icon-copy fa fa-plus"></i> Create First Category
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($categories->hasPages())
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    @endif
@endsection
--}}
