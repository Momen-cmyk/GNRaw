@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Product Details')

@section('content')
    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Product Details</h4>
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
                            {{ $product->product_name }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-end">
                <a href="{{ route('admin.products') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to Products
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Product Information -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Product Name</h6>
                            <p class="text-muted">{{ $product->product_name }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Category</h6>
                            <p class="text-muted">{{ $product->product_category }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>CAS Number</h6>
                            <p class="text-muted">{{ $product->cas_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Molecular Formula</h6>
                            <p class="text-muted">{{ $product->molecular_formula ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Molecular Weight</h6>
                            <p class="text-muted">{{ $product->molecular_weight ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Minimum Order Quantity (MOQ)</h6>
                            <p class="text-muted">{{ $product->moq }} kg</p>
                        </div>
                        <div class="col-12">
                            <h6>Description</h6>
                            <p class="text-muted">{{ $product->description ?? 'No description provided' }}</p>
                        </div>
                        <div class="col-12">
                            <h6>Specifications</h6>
                            <div class="bg-light p-3 rounded">
                                <pre class="mb-0">{{ $product->specifications }}</pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            @if ($product->product_images && count($product->product_images) > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Product Images</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($product->product_images as $image)
                                <div class="col-md-4 mb-3">
                                    <img src="{{ asset('storage/' . $image) }}" alt="{{ $product->product_name }}"
                                        class="img-fluid rounded"
                                        style="max-height: 200px; width: 100%; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Product Documents -->
            @if ($product->documents && count($product->documents) > 0)
                <div class="card mt-3">
                    <div class="card-header">
                        <h5 class="card-title">Product Documents</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Document Name</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->documents as $document)
                                        <tr>
                                            <td>
                                                <span class="badge bg-info">{{ $document->document_type }}</span>
                                            </td>
                                            <td>{{ $document->document_name }}</td>
                                            <td>
                                                @if ($document->is_verified)
                                                    <span class="badge bg-success">Verified</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('document.viewer', $document->file_path) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary me-1">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                <a href="{{ asset('storage/' . $document->file_path) }}"
                                                    download="{{ $document->document_name }}"
                                                    class="btn btn-sm btn-outline-success">
                                                    <i class="fa fa-download"></i> Download
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Supplier Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Supplier Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if ($product->supplier->picture)
                            <img src="{{ $product->supplier->picture }}" alt="{{ $product->supplier->company_name }}"
                                class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fa fa-building fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <h6 class="text-center">{{ $product->supplier->company_name }}</h6>
                    <p class="text-muted text-center">{{ $product->supplier->email }}</p>

                    <div class="mt-3">
                        <h6>Contact Person</h6>
                        <p class="text-muted">{{ $product->supplier->name }}</p>

                        <h6>Phone</h6>
                        <p class="text-muted">{{ $product->supplier->phone ?? 'N/A' }}</p>

                        <h6>Company Activity</h6>
                        <p class="text-muted">{{ $product->supplier->company_activity ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Product Status & Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Status & Actions</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Current Status</h6>
                        @if ($product->status === 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6>Approval Status</h6>
                        @if ($product->is_approved)
                            <span class="badge bg-success">Approved</span>
                            @if ($product->approvedBy)
                                <br><small class="text-muted">by {{ $product->approvedBy->name }}</small>
                                <br><small class="text-muted">{{ $product->approved_at->format('M d, Y H:i') }}</small>
                            @endif
                        @else
                            <span class="badge bg-warning">Pending Approval</span>
                        @endif
                    </div>

                    @if ($product->trashed())
                        <div class="mb-3">
                            <h6>Product Status</h6>
                            <span class="badge bg-danger">Draft (Deleted by Supplier)</span>
                            <br><small class="text-muted">Moved to draft on
                                {{ $product->deleted_at->format('M d, Y H:i') }}</small>
                        </div>
                    @endif

                    @if (!$product->trashed())
                        <div class="d-grid gap-2">
                            @if (!$product->is_approved)
                                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100"
                                        onclick="return confirm('Are you sure you want to approve this product?')">
                                        <i class="fa fa-check"></i> Approve Product
                                    </button>
                                </form>

                                <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                    <i class="fa fa-times"></i> Reject Product
                                </button>
                            @endif

                            <form action="{{ route('admin.products.toggle_status', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="btn {{ $product->status === 'active' ? 'btn-warning' : 'btn-success' }} w-100"
                                    onclick="return confirm('Are you sure you want to {{ $product->status === 'active' ? 'deactivate' : 'activate' }} this product?')">
                                    <i class="fa {{ $product->status === 'active' ? 'fa-pause' : 'fa-play' }}"></i>
                                    {{ $product->status === 'active' ? 'Deactivate' : 'Activate' }} Product
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i>
                            <strong>Draft Product:</strong> This product has been deleted by the supplier and is now in
                            draft status.
                            You can only view details or permanently delete it.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title">Product Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $product->created_at->format('M d') }}</h4>
                            <small class="text-muted">Created</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">{{ $product->updated_at->format('M d') }}</h4>
                            <small class="text-muted">Last Updated</small>
                        </div>
                    </div>
                </div>
            </div>

            @if (!$product->trashed())
                <!-- Admin Comments for Supplier -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Comments for Supplier</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addCommentModal">
                            <i class="fa fa-plus"></i> Add Comment
                        </button>
                    </div>
                    <div class="card-body">
                        @if ($product->comments->count() > 0)
                            <div class="comments-list">
                                @foreach ($product->comments as $comment)
                                    <div
                                        class="comment-item border rounded p-3 mb-3 {{ $comment->is_urgent ? 'border-danger bg-light' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center">
                                                <strong>{{ $comment->admin->name }}</strong>
                                                @if ($comment->is_urgent)
                                                    <span class="badge bg-danger ms-2">Urgent</span>
                                                @endif
                                                @if (!$comment->is_read_by_supplier)
                                                    <span class="badge bg-warning ms-2">Unread</span>
                                                @endif
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="fa fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if ($comment->admin_id === Auth::id())
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.comments.delete', $comment->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item text-danger"
                                                                    onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                    <i class="fa fa-trash"></i> Delete
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <p class="mb-2">{{ $comment->comment }}</p>
                                        <small class="text-muted">
                                            <i class="fa fa-clock"></i> {{ $comment->created_at->format('M d, Y H:i') }}
                                            @if ($comment->is_read_by_supplier && $comment->read_at)
                                                | <i class="fa fa-eye"></i> Read
                                                {{ $comment->read_at->format('M d, Y H:i') }}
                                            @endif
                                        </small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fa fa-comments fa-3x mb-3"></i>
                                <p>No comments yet. Add a comment to communicate with the supplier.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required
                                placeholder="Please provide a detailed reason for rejection..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (!$product->trashed())
        <!-- Add Comment Modal -->
        <div class="modal fade" id="addCommentModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Comment for Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.products.comments.add', $product->id) }}" method="POST"
                        id="addCommentForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="comment" class="form-label">Comment</label>
                                <textarea class="form-control" id="comment" name="comment" rows="4" required
                                    placeholder="Write your comment for the supplier..."></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_urgent" name="is_urgent"
                                        value="1">
                                    <label class="form-check-label" for="is_urgent">
                                        Mark as urgent
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submitCommentBtn">Add Comment</button>
                        </div>
                    </form>

                    <script>
                        document.getElementById('addCommentForm').addEventListener('submit', function(e) {
                            console.log('Form submission started');
                            const submitBtn = document.getElementById('submitCommentBtn');
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Adding...';
                        });

                        // Close modal on successful submission
                        @if (session('success'))
                            const modal = bootstrap.Modal.getInstance(document.getElementById('addCommentModal'));
                            if (modal) {
                                modal.hide();
                            }
                            // Clear form
                            document.getElementById('addCommentForm').reset();
                        @endif
                    </script>
                </div>
            </div>
        </div>
    @endif
@endsection
