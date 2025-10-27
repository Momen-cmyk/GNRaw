@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Products Management')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Products Management</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Products Management
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('admin.products') }}" class="row g-3" id="searchForm">
                        <div class="col-lg-4 col-md-6">
                            <label for="search" class="form-label fw-semibold">
                                <i class="fa fa-search me-1"></i>Search Products
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="search" name="search"
                                    value="{{ $search }}"
                                    placeholder="Search by product name, CAS number, or category...">
                                @if ($search)
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                                        <i class="fa fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fa fa-filter me-1"></i>Status
                            </label>
                            <select class="form-select" id="status" name="status">
                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending Approval
                                </option>
                                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label for="supplier" class="form-label fw-semibold">
                                <i class="fa fa-building me-1"></i>Supplier
                            </label>
                            <select class="form-select" id="supplier" name="supplier">
                                <option value="">All Suppliers</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}"
                                        {{ $supplier_filter == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->company_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="fa fa-cog me-1"></i>Actions
                            </label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fa fa-search me-1"></i>Search
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="clearAllFilters()">
                                    <i class="fa fa-refresh me-1"></i>Clear
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th width="25%">Product</th>
                                    <th width="20%">Supplier</th>
                                    <th width="12%">Category</th>
                                    <th width="8%">MOQ</th>
                                    <th width="8%">Status</th>
                                    <th width="12%">Approval</th>
                                    <th width="8%">Created</th>
                                    <th width="7%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($product->product_images && count($product->product_images) > 0)
                                                    <img src="{{ asset('storage/' . $product->product_images[0]) }}"
                                                        alt="{{ $product->product_name }}" class="rounded me-3"
                                                        style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="fa fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold text-dark">{{ $product->product_name }}</h6>
                                                    @if ($product->cas_number)
                                                        <small class="text-muted d-block">CAS:
                                                            {{ $product->cas_number }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-1 fw-semibold">{{ $product->supplier->company_name }}</h6>
                                                <small class="text-muted d-block">{{ $product->supplier->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info fs-6">{{ $product->product_category }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $product->moq }} kg</span>
                                        </td>
                                        <td>
                                            @if ($product->status === 'active')
                                                <span class="badge bg-success fs-6">Active</span>
                                            @else
                                                <span class="badge bg-secondary fs-6">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->is_approved)
                                                <span class="badge bg-success fs-6">Approved</span>
                                                @if ($product->approvedBy)
                                                    <br><small class="text-muted d-block">by
                                                        {{ $product->approvedBy->name }}</small>
                                                @endif
                                            @else
                                                <span class="badge bg-warning fs-6">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $product->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <a href="{{ route('admin.products.show', $product->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                @if (!$product->is_approved)
                                                    <form action="{{ route('admin.products.approve', $product->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            title="Approve Product"
                                                            onclick="return confirm('Are you sure you want to approve this product?')">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        title="Reject Product" data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $product->id }}">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                @endif

                                                <form action="{{ route('admin.products.toggle_status', $product->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-sm {{ $product->status === 'active' ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $product->status === 'active' ? 'Deactivate' : 'Activate' }} Product"
                                                        onclick="return confirm('Are you sure you want to {{ $product->status === 'active' ? 'deactivate' : 'activate' }} this product?')">
                                                        <i
                                                            class="fa {{ $product->status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $product->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Product</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.products.reject', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejection_reason{{ $product->id }}"
                                                                class="form-label">Rejection Reason</label>
                                                            <textarea class="form-control" id="rejection_reason{{ $product->id }}" name="rejection_reason" rows="3"
                                                                required placeholder="Please provide a reason for rejection..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject
                                                            Product</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa fa-box fa-4x mb-4 text-muted"></i>
                                                <h5 class="mb-3">No Products Found</h5>
                                                <p class="mb-4">No products match your search criteria. Try adjusting
                                                    your filters or search terms.</p>
                                                <a href="{{ route('admin.products') }}" class="btn btn-primary">
                                                    <i class="fa fa-refresh me-2"></i>View All Products
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mt-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-primary-light">
                            <i class="fa fa-box text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $products->total() }}</h3>
                            <p class="text-muted mb-0">Total Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-warning-light">
                            <i class="fa fa-clock text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $products->where('is_approved', false)->count() }}</h3>
                            <p class="text-muted mb-0">Pending Approval</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-success-light">
                            <i class="fa fa-check-circle text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $products->where('is_approved', true)->count() }}</h3>
                            <p class="text-muted mb-0">Approved Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-info-light">
                            <i class="fa fa-play-circle text-info"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $products->where('status', 'active')->count() }}</h3>
                            <p class="text-muted mb-0">Active Products</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            font-weight: 600;
            font-size: 0.9rem;
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-group .btn {
            margin-right: 0.25rem;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .d-flex.flex-wrap.gap-1 .btn {
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .d-flex.flex-wrap.gap-1 .btn:last-child {
            margin-right: 0;
        }

        .product-image img {
            border: 2px solid #e9ecef;
            transition: border-color 0.2s ease;
        }

        .product-image img:hover {
            border-color: #007bff;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table-responsive {
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .row.mb-3 {
            margin-bottom: 1.5rem !important;
        }

        .row.mt-4 {
            margin-top: 2rem !important;
        }

        /* Search Bar Styling */
        .input-group .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }

        .input-group .input-group-text {
            border-color: #ced4da;
            background-color: #f8f9fa;
        }

        .input-group .form-control:focus+.input-group-text {
            border-color: #007bff;
            background-color: #e3f2fd;
        }

        .input-group .form-control {
            border-color: #ced4da;
            transition: all 0.3s ease;
        }

        .input-group .form-control:hover {
            border-color: #adb5bd;
        }

        .input-group .btn-outline-secondary {
            border-color: #ced4da;
            color: #6c757d;
        }

        .input-group .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
        }

        .form-label {
            color: #495057;
            margin-bottom: 0.5rem;
        }

        .form-label i {
            color: #6c757d;
        }

        .btn.flex-fill {
            min-width: 120px;
        }

        .d-flex.gap-2 .btn {
            min-width: 100px;
        }

        .card.border-0.shadow-sm {
            border-radius: 0.5rem;
        }

        .card-body.p-4 {
            background-color: #f8f9fa;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .d-flex.gap-2 {
                flex-direction: column;
            }

            .d-flex.gap-2 .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }

            .d-flex.gap-2 .btn:last-child {
                margin-bottom: 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Auto-submit form on filter change
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        document.getElementById('supplier').addEventListener('change', function() {
            this.form.submit();
        });

        // Clear search input
        function clearSearch() {
            document.getElementById('search').value = '';
            document.getElementById('searchForm').submit();
        }

        // Clear all filters and search
        function clearAllFilters() {
            document.getElementById('search').value = '';
            document.getElementById('status').value = 'all';
            document.getElementById('supplier').value = '';
            document.getElementById('searchForm').submit();
        }

        // Add Enter key support for search
        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('searchForm').submit();
            }
        });

        // Add search loading state
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i>Searching...';
                submitBtn.disabled = true;
            }
        });

        // Add search suggestions on focus
        document.getElementById('search').addEventListener('focus', function(e) {
            this.placeholder = 'Type product name, CAS number, or category...';
        });

        document.getElementById('search').addEventListener('blur', function(e) {
            this.placeholder = 'Search by product name, CAS number, or category...';
        });
    </script>
@endpush
