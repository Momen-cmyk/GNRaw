@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Product Management')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Product Management</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('supplier.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Products
                        </li>
                </nav>
            </div>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Product Listings</h4>
                    <a href="{{ route('supplier.products.create') }}" class="btn btn-primary">
                        <i class="icon-copy dw dw-add"></i> Add New Product
                    </a>
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

                <!-- Filter and Search -->
                <form method="GET" action="{{ route('supplier.products') }}" id="filterForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search products..." name="search"
                                    value="{{ $search }}" id="searchInput">
                                <button class="btn btn-outline-secondary" type="submit" id="searchBtn">
                                    <i class="icon-copy dw dw-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="status" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending Review
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="category" id="categoryFilter">
                                <option value="">All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->name }}" {{ $category === $cat->name ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Products Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="productsTable">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>CAS Number</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $product->product_name }}</h6>
                                                <small
                                                    class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-info">{{ ucfirst($product->product_category ?? 'N/A') }}</span>
                                    </td>
                                    <td>{{ $product->cas_number ?? 'N/A' }}</td>
                                    <td>
                                        @if ($product->status == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($product->status == 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @else
                                            <span class="badge bg-warning">Pending Review</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('supplier.products.edit', $product->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Edit">
                                                <i class="icon-copy dw dw-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-info"
                                                onclick="viewProduct({{ $product->id }})" title="View">
                                                <i class="icon-copy dw dw-eye"></i>
                                            </button>
                                            <form action="{{ route('supplier.products.delete', $product->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="icon-copy dw dw-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="icon-copy dw dw-box1" style="font-size: 48px; opacity: 0.3;"></i>
                                            <p class="mt-2">No products found. <a
                                                    href="{{ route('supplier.products.create') }}">Add your first
                                                    product</a></p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product View Modal -->
    <div class="modal fade" id="productViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product Details</h5>
                    <button type="button" class="btn-close" id="closeModalXBtn"></button>
                </div>
                <div class="modal-body" id="productDetails">
                    <!-- Product details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="closeModalBtn">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Check if Bootstrap is loaded
        console.log('Bootstrap available:', typeof bootstrap !== 'undefined');

        // Store modal instance globally
        let productModal = null;

        // Function to close modal manually
        function closeModalManually() {
            const modal = document.getElementById('productViewModal');
            modal.classList.remove('show');
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
        }

        // Add event listeners for close buttons
        document.addEventListener('DOMContentLoaded', function() {
            const closeBtn = document.getElementById('closeModalBtn');
            const closeXBtn = document.getElementById('closeModalXBtn');
            const modal = document.getElementById('productViewModal');

            // Close modal when clicking outside
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        console.log('Clicked outside modal');
                        if (productModal) {
                            productModal.hide();
                        } else {
                            closeModalManually();
                        }
                    }
                });
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    console.log('Close button clicked');
                    if (productModal) {
                        productModal.hide();
                    } else {
                        closeModalManually();
                    }
                });
            }

            if (closeXBtn) {
                closeXBtn.addEventListener('click', function() {
                    console.log('X button clicked');
                    if (productModal) {
                        productModal.hide();
                    } else {
                        closeModalManually();
                    }
                });
            }
        });

        function viewProduct(productId) {
            console.log('Opening product modal for ID:', productId);

            // Show loading state
            document.getElementById('productDetails').innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading product details...</p>
        </div>
    `;

            // Show the modal
            if (typeof bootstrap !== 'undefined') {
                productModal = new bootstrap.Modal(document.getElementById('productViewModal'));
                productModal.show();
                console.log('Modal opened with Bootstrap');
            } else {
                // Fallback: show modal manually
                const modal = document.getElementById('productViewModal');
                modal.classList.add('show');
                modal.style.display = 'block';
                document.body.classList.add('modal-open');
                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(backdrop);
                console.log('Modal opened manually (Bootstrap not available)');
            }

            // Make AJAX call to get product details
            fetch(`/supplier/products/${productId}/details`, {
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
                        const product = data.product;
                        const comments = data.comments || [];
                        const documents = data.documents || [];

                        let commentsHtml = '';
                        if (comments.length > 0) {
                            commentsHtml = `
                            <div class="mt-4">
                                <h6><i class="fa fa-comments"></i> Admin Comments</h6>
                                <div class="comments-section">
                                    ${comments.map(comment => `
                                                                <div class="comment-item border rounded p-3 mb-3 ${comment.is_urgent ? 'border-danger bg-light' : ''}">
                                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                                        <div class="d-flex align-items-center">
                                                                            <strong>${comment.admin_name}</strong>
                                                                            ${comment.is_urgent ? '<span class="badge bg-danger ms-2">Urgent</span>' : ''}
                                                                            ${!comment.is_read ? '<span class="badge bg-warning ms-2">New</span>' : ''}
                                                                        </div>
                                                                    </div>
                                                                    <p class="mb-2">${comment.comment}</p>
                                                                    <small class="text-muted">
                                                                        <i class="fa fa-clock"></i> ${comment.created_at}
                                                                        ${comment.read_at ? ` | <i class="fa fa-eye"></i> Read ${comment.read_at}` : ''}
                                                                    </small>
                                                                </div>
                                                            `).join('')}
                                </div>
                            </div>
                        `;
                        } else {
                            commentsHtml = `
                            <div class="mt-4">
                                <div class="text-center text-muted py-3">
                                    <i class="fa fa-comments fa-2x mb-2"></i>
                                    <p>No admin comments yet.</p>
                                </div>
                            </div>
                        `;
                        }

                        document.getElementById('productDetails').innerHTML = `
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Product Name</h6>
                                <p class="text-muted">${product.product_name}</p>

                                <h6>Category</h6>
                                <p class="text-muted">${product.product_category}</p>

                                <h6>CAS Number</h6>
                                <p class="text-muted">${product.cas_number || 'N/A'}</p>

                                <h6>Molecular Formula</h6>
                                <p class="text-muted">${product.molecular_formula || 'N/A'}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Status</h6>
                                <p>
                                    ${product.is_approved ?
                                        '<span class="badge bg-success">Approved</span>' :
                                        '<span class="badge bg-warning">Pending Approval</span>'
                                    }
                                </p>

                                <h6>Availability</h6>
                                <p>
                                    ${product.is_available ?
                                        '<span class="badge bg-success">Available</span>' :
                                        '<span class="badge bg-secondary">Unavailable</span>'
                                    }
                                </p>

                                <h6>MOQ</h6>
                                <p class="text-muted">${product.moq} kg</p>

                                <h6>Created</h6>
                                <p class="text-muted">${new Date(product.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>

                        <div class="mt-3">
                            <h6>Description</h6>
                            <p class="text-muted">${product.description || 'No description provided'}</p>
                        </div>

                        <div class="mt-3">
                            <h6>Specifications</h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0">${product.specifications}</pre>
                            </div>
                        </div>

                        ${documents.length > 0 ? `
                                                <div class="mt-4">
                                                    <h6><i class="fa fa-file"></i> Product Documents</h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm">
                                                            <thead>
                                                                <tr>
                                                                    <th>Type</th>
                                                                    <th>Name</th>
                                                                    <th>Status</th>
                                                                    <th>Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                ${documents.map(doc => `
                                                <tr>
                                                    <td><span class="badge bg-info">${doc.document_type}</span></td>
                                                    <td>${doc.document_name}</td>
                                                    <td>
                                                        ${doc.is_verified ?
                                                            '<span class="badge bg-success">Verified</span>' :
                                                            '<span class="badge bg-warning">Pending</span>'
                                                        }
                                                    </td>
                                                    <td>
                                                        <a href="${doc.view_url}" target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                            <i class="fa fa-eye"></i> View
                                                        </a>
                                                        <a href="${doc.download_url}" download="${doc.document_name}" class="btn btn-sm btn-outline-success">
                                                            <i class="fa fa-download"></i> Download
                                                        </a>
                                                    </td>
                                                </tr>
                                            `).join('')}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            ` : ''}

                        ${commentsHtml}
                    `;
                    } else {
                        document.getElementById('productDetails').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fa fa-exclamation-triangle"></i>
                            Error loading product details. Please try again.
                        </div>
                    `;
                    }
                })
                .catch(error => {
                    console.error('Error loading product details:', error);
                    document.getElementById('productDetails').innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fa fa-exclamation-triangle"></i>
                        <h6>Error loading product details</h6>
                        <p>${error.message}</p>
                        <button onclick="viewProduct(${productId})" class="btn btn-sm btn-outline-danger">Retry</button>
                    </div>
                `;
                });
        }

        // Auto-submit form on filter change
        document.getElementById('statusFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        document.getElementById('categoryFilter').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });
    </script>
@endpush
