@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Supplier Dashboard')
@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-primary-light">
                            <i class="fa fa-box text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $stats['total_products'] ?? 0 }}</h3>
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
                        <div class="icon-box icon-box-sm bg-success-light">
                            <i class="fa fa-check-circle text-success"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $stats['active_products'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Active Products</p>
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
                            <h3 class="mb-0">{{ $stats['pending_products'] ?? 0 }}</h3>
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
                        <div class="icon-box icon-box-sm bg-info-light">
                            <i class="fa fa-envelope text-info"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $stats['total_inquiries'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Client Inquiries</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-8 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Products</h5>
                    <div class="card-tools">
                        <a href="{{ route('supplier.products.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa fa-plus"></i> Add Product
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentProducts ?? [] as $product)
                                    <tr>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $product->product_name }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $product->category }}</span>
                                            @if ($product->subcategory)
                                                <br><small class="text-muted">{{ $product->subcategory }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($product->is_approved)
                                                @if ($product->is_available)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->last_updated->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('supplier.products.edit', $product->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                    action="{{ route('supplier.products.delete', $product->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        onclick="return confirm('Delete this product?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('supplier.products.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Product
                        </a>
                        <a href="{{ route('supplier.products') }}" class="btn btn-outline-primary">
                            <i class="fa fa-list"></i> Manage Products
                        </a>
                        <a href="{{ route('supplier.profile') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-user"></i> Update Profile
                        </a>
                        <a href="{{ route('supplier.settings') }}" class="btn btn-outline-secondary">
                            <i class="fa fa-cog"></i> Settings
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Account Status</h5>
                </div>
                <div class="card-body">
                    @php
                        $supplier = Auth::guard('supplier')->user();
                    @endphp
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">Status:</span>
                        @if ($supplier->approval_status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif($supplier->approval_status === 'pending')
                            <span class="badge bg-warning">Pending Review</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="me-2">Active:</span>
                        @if ($supplier->is_active)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </div>
                    @if ($supplier->approval_status === 'rejected' && $supplier->rejection_reason)
                        <div class="mt-3">
                            <strong>Rejection Reason:</strong>
                            <p class="text-muted small">{{ $supplier->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
