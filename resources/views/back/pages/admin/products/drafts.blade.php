@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Draft Products')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Draft Products</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.products') }}">Products</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Draft Products
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-end">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-primary">
                    <i class="fa fa-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form method="GET" action="{{ route('admin.products.drafts') }}" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search products..."
                                    value="{{ $search }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="supplier">
                                    <option value="">All Suppliers</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}"
                                            {{ $selectedSupplier == $supplier->id ? 'selected' : '' }}>
                                            {{ $supplier->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->name }}"
                                            {{ $selectedCategory == $category->name ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fa fa-search"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($draftProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Supplier</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Deleted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($draftProducts as $product)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $product->product_name }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <img src="{{ asset('images/users/' . ($product->supplier->profile_photo ?? 'default-avatar.png')) }}"
                                                        alt="{{ $product->supplier->company_name }}" class="rounded-circle"
                                                        style="width: 30px; height: 30px; object-fit: cover;"
                                                        onerror="this.src='/images/users/default-avatar.png'">
                                                </div>
                                                <div>
                                                    <strong>{{ $product->supplier->company_name }}</strong>
                                                    <br><small class="text-muted">{{ $product->supplier->name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $product->product_category }}</span>
                                            @if ($product->subcategory)
                                                <br><small class="text-muted">{{ $product->subcategory }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->is_approved === 1)
                                                <span class="badge bg-success">Approved</span>
                                            @elseif ($product->is_approved === 0)
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $product->deleted_at->format('M d, Y H:i') }}
                                                <br><span class="text-danger">Draft</span>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fa fa-eye"></i> View Details
                                                </a>
                                                <form action="{{ route('admin.products.permanent_delete', $product->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Permanently Delete"
                                                        onclick="return confirm('Are you sure you want to permanently delete this product? This action cannot be undone!')">
                                                        <i class="fa fa-trash"></i> Delete Forever
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $draftProducts->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-file-alt fa-3x text-muted mb-3"></i>
                        <h5>No Draft Products</h5>
                        <p class="text-muted">There are no draft products at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
        }

        .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        .btn-group .btn {
            margin-right: 2px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
@endsection
