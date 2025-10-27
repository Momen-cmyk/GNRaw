@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Supplier Details')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Supplier Details</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs" id="supplierTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details"
                                type="button" role="tab" aria-controls="details" aria-selected="true">
                                <i class="fa fa-info-circle"></i> Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="products-tab" data-bs-toggle="tab" data-bs-target="#products"
                                type="button" role="tab" aria-controls="products" aria-selected="false">
                                <i class="fa fa-box"></i> Products <span
                                    class="badge bg-primary ms-1">{{ $supplier->products->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="supplierTabsContent">
                        <!-- Details Tab -->
                        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                            <div class="row mt-3">
                                <!-- Company Information -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title">Company Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Company Name:</strong></td>
                                                    <td>{{ $supplier->company_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Company Activity:</strong></td>
                                                    <td>{{ ucfirst(str_replace('_', ' & ', $supplier->company_activity)) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Number of Employees:</strong></td>
                                                    <td>{{ $supplier->employee_range_display }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Description:</strong></td>
                                                    <td>{{ $supplier->company_description ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title">Contact Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>Contact Person:</strong></td>
                                                    <td>{{ $supplier->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Email:</strong></td>
                                                    <td>{{ $supplier->email }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Username:</strong></td>
                                                    <td>{{ $supplier->username }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Phone:</strong></td>
                                                    <td>{{ $supplier->phone ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Status:</strong></td>
                                                    <td>
                                                        @if ($supplier->approval_status === 'pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($supplier->approval_status === 'approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Documents Section -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title">Submitted Documents</h6>
                                        </div>
                                        <div class="card-body">
                                            @if ($supplier->documents->count() > 0)
                                                <div class="row">
                                                    @foreach ($supplier->documents as $document)
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card">
                                                                <div class="card-body text-center">
                                                                    <i class="fa fa-file-pdf fa-3x text-danger mb-2"></i>
                                                                    <h6 class="card-title">
                                                                        {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                                                    </h6>
                                                                    <p class="text-muted small">
                                                                        {{ $document->created_at->format('M d, Y') }}</p>
                                                                    <a href="{{ $document->file_url }}" target="_blank"
                                                                        class="btn btn-sm btn-primary">
                                                                        <i class="fa fa-download"></i> View Document
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted text-center">No documents submitted</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Approval Actions -->
                            @if ($supplier->approval_status === 'pending')
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title">Approval Actions</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex gap-2">
                                                    <form method="POST"
                                                        action="{{ route('admin.suppliers.approve', $supplier->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success"
                                                            onclick="return confirm('Approve this supplier?')">
                                                            <i class="fa fa-check"></i> Approve Supplier
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif ($supplier->approval_status === 'rejected')
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title">Re-approval Actions</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted mb-3">This supplier was previously rejected. You can
                                                    approve them if they have addressed the issues.</p>
                                                <div class="d-flex gap-2">
                                                    <form method="POST"
                                                        action="{{ route('admin.suppliers.approve', $supplier->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success"
                                                            onclick="return confirm('Approve this previously rejected supplier?')">
                                                            <i class="fa fa-check"></i> Approve Supplier
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($supplier->approval_status === 'rejected' && $supplier->rejection_reason)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="card-title">Rejection Reason</h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted">{{ $supplier->rejection_reason }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <!-- End Details Tab -->

                        <!-- Products Tab -->
                        <div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products-tab">
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Supplier Products</h5>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="refreshProducts()">
                                            <i class="fa fa-refresh"></i> Refresh
                                        </button>
                                        <a href="{{ route('admin.products') }}?supplier={{ $supplier->id }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fa fa-external-link"></i> View All Products
                                        </a>
                                    </div>
                                </div>

                                <div id="productsContent">
                                    <div class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading products...</span>
                                        </div>
                                        <p class="mt-2">Loading products...</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Products Tab -->
                    </div>
                    <!-- End Tab Content -->
                </div>
            </div>
        </div>
    </div>


    <script>
        let productsLoaded = false;

        // Load products when products tab is clicked
        document.getElementById('products-tab').addEventListener('click', function() {
            if (!productsLoaded) {
                loadSupplierProducts();
            }
        });

        function loadSupplierProducts() {
            const productsContent = document.getElementById('productsContent');

            // Show loading state
            productsContent.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading products...</span>
                    </div>
                    <p class="mt-2">Loading products...</p>
                </div>
            `;

            // Make AJAX call to get supplier products
            fetch(`/admin/suppliers/{{ $supplier->id }}/products`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        displayProducts(data.products);
                        productsLoaded = true;
                    } else {
                        throw new Error(data.message || 'Failed to load products');
                    }
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    productsContent.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        <strong>Error loading products:</strong> ${error.message}
                        <button class="btn btn-sm btn-outline-danger ms-2" onclick="loadSupplierProducts()">
                            <i class="fa fa-refresh"></i> Retry
                        </button>
                    </div>
                `;
                });
        }

        function displayProducts(products) {
            const productsContent = document.getElementById('productsContent');

            if (products.length === 0) {
                productsContent.innerHTML = `
                    <div class="text-center py-5">
                        <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                        <h5>No Products Found</h5>
                        <p class="text-muted">This supplier hasn't added any products yet.</p>
                    </div>
                `;
                return;
            }

            let productsHtml = `
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            products.forEach(product => {
                let statusBadge = '';
                if (product.is_approved === 1) {
                    statusBadge = product.is_available ?
                        '<span class="badge bg-success">Active</span>' :
                        '<span class="badge bg-secondary">Inactive</span>';
                } else if (product.is_approved === 0) {
                    statusBadge = '<span class="badge bg-danger">Rejected</span>';
                } else {
                    statusBadge = '<span class="badge bg-warning">Pending</span>';
                }

                productsHtml += `
                    <tr>
                        <td>
                            <div>
                                <h6 class="mb-0">${product.product_name}</h6>
                                <small class="text-muted">${product.description ? product.description.substring(0, 50) + '...' : 'No description'}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">${product.product_category}</span>
                            ${product.subcategory ? `<br><small class="text-muted">${product.subcategory}</small>` : ''}
                        </td>
                        <td>${statusBadge}</td>
                        <td>${new Date(product.created_at).toLocaleDateString()}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="/admin/products/${product.id}" class="btn btn-sm btn-outline-primary" title="View Details">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="/admin/products/${product.id}/edit" class="btn btn-sm btn-outline-secondary" title="Edit">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });

            productsHtml += `
                        </tbody>
                    </table>
                </div>
            `;

            productsContent.innerHTML = productsHtml;
        }

        function refreshProducts() {
            productsLoaded = false;
            loadSupplierProducts();
        }
    </script>
@endsection
