@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Supplier Dashboard')
@section('content')

    <!-- Success Message -->
    @if (session('success'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Message -->
    @if (session('error'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Info Message -->
    @if (session('info'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fa fa-info-circle me-2"></i> {{ session('info') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Warning Message -->
    @if (session('warning'))
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-primary-light">
                            <i class="fa fa-archive text-primary"></i>
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
                            <i class="fa fa-clock-o text-warning"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $stats['pending_products'] ?? 0 }}</h3>
                            <p class="text-muted mb-0">Pending Approval</p>
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

    <!-- Comments for Supplier Section -->
    @if (($unreadCommentsCount ?? 0) > 0)
        <div class="row mt-4">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fa fa-comments"></i> Comments for Supplier
                            @if ($urgentCommentsCount > 0)
                                <span class="badge bg-danger ms-2">{{ $urgentCommentsCount }} Urgent</span>
                            @endif
                            <span class="badge bg-warning ms-2">{{ $unreadCommentsCount }} Unread</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="comments-list">
                            @foreach ($recentComments as $comment)
                                <div
                                    class="comment-item {{ $comment->is_urgent ? 'border-danger border-start border-3' : '' }} p-3 mb-3 rounded">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fa fa-cube"></i> {{ $comment->product->product_name }}
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fa fa-user-tie"></i> {{ $comment->admin->name ?? 'Admin' }}
                                                <span class="mx-2">•</span>
                                                <i class="fa fa-clock"></i>
                                                {{ $comment->created_at->format('M d, Y H:i') }}
                                                @if ($comment->is_urgent)
                                                    <span class="badge bg-danger ms-2">Urgent</span>
                                                @endif
                                            </small>
                                        </div>
                                        <button class="btn btn-sm btn-primary"
                                            onclick="markCommentRead({{ $comment->id }})"
                                            data-comment-id="{{ $comment->id }}">
                                            <i class="fa fa-check"></i> Mark as Read
                                        </button>
                                    </div>
                                    <div class="comment-content">
                                        <p class="mb-0">{{ $comment->comment }}</p>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('supplier.products.edit', $comment->product->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i> View Product
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if (count($recentComments) < $unreadCommentsCount)
                            <div class="text-center mt-3">
                                <p class="text-muted">
                                    Showing {{ count($recentComments) }} of {{ $unreadCommentsCount }} unread comments
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('styles')
    <style>
        .comment-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            background-color: #e9ecef;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .comment-item.border-danger {
            background-color: #fff5f5;
        }

        .comment-content {
            color: #495057;
            line-height: 1.6;
        }

        [data-theme="dark"] .comment-item {
            background-color: #2c3e50;
            border-color: #34495e;
        }

        [data-theme="dark"] .comment-item:hover {
            background-color: #34495e;
        }

        [data-theme="dark"] .comment-content {
            color: #ecf0f1;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function markCommentRead(commentId) {
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                '{{ csrf_token() }}';

            // Send AJAX request to mark comment as read
            fetch('{{ url('/supplier/products') }}/' + commentId + '/mark-comment-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'POST'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove the comment from the view
                        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                        if (commentElement) {
                            commentElement.closest('.comment-item').style.opacity = '0.5';
                            commentElement.closest('.comment-item').style.transition = 'opacity 0.3s';
                            setTimeout(() => {
                                commentElement.closest('.comment-item').remove();
                                // Reload page to update counts
                                location.reload();
                            }, 300);
                        }
                    } else {
                        alert('Failed to mark comment as read');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
        }
    </script>
@endpush
