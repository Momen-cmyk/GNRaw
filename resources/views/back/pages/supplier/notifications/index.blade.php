@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Notifications')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Notifications</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">All Notifications</h5>
                    @if ($notifications->count() > 0)
                        <button type="button" class="btn btn-sm btn-outline-primary" id="markAllReadBtnPage"
                            onclick="markAllNotificationsAsRead()">
                            <i class="fa fa-check"></i> Mark All as Read
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if ($notifications->count() > 0)
                        <div class="notifications-list">
                            @foreach ($notifications as $notification)
                                @php
                                    $productId = $notification->data['product_id'] ?? null;
                                    $isClickable = $productId && $notification->type === 'comment_added';
                                @endphp

                                <div class="notification-item border rounded p-3 mb-3 {{ !$notification->is_read ? 'unread' : '' }} {{ $notification->data['is_urgent'] ?? false ? 'urgent' : '' }} {{ $isClickable ? 'clickable-notification' : '' }}"
                                    @if ($isClickable) onclick="viewProductWithComments({{ $productId }})"
                                        style="cursor: pointer;"
                                        title="Click to view product comments" @endif>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="notification-title mb-0">
                                                    {{ $notification->title }}
                                                    @if ($isClickable)
                                                        <i class="fa fa-external-link-alt ms-2 text-muted"
                                                            style="font-size: 12px;"></i>
                                                    @endif
                                                </h6>
                                                @if (!$notification->is_read)
                                                    <span class="badge bg-primary ms-2">New</span>
                                                @endif
                                                @if ($notification->data['is_urgent'] ?? false)
                                                    <span class="badge bg-danger ms-2">Urgent</span>
                                                @endif
                                            </div>
                                            <p class="notification-message mb-2">{{ $notification->message }}</p>
                                            <small class="notification-time text-muted">
                                                <i class="fa fa-clock"></i>
                                                {{ $notification->created_at->format('M d, Y H:i') }}
                                                @if ($notification->is_read && $notification->read_at)
                                                    | <i class="fa fa-eye"></i> Read
                                                    {{ $notification->read_at->format('M d, Y H:i') }}
                                                @endif
                                            </small>
                                        </div>
                                        <div class="notification-actions">
                                            @if (!$notification->is_read)
                                                <form
                                                    action="{{ route('supplier.notifications.mark_read', $notification->id) }}"
                                                    method="POST" class="d-inline" onclick="event.stopPropagation();">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                                        <i class="fa fa-check"></i> Mark Read
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fa fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5>No Notifications</h5>
                            <p class="text-muted">You don't have any notifications yet. When admins add comments to your
                                products, you'll see them here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .clickable-notification {
            transition: all 0.3s ease;
        }

        .clickable-notification:hover {
            background-color: #f8f9fa;
            border-color: #007bff;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .unread.clickable-notification:hover {
            background-color: #e3f2fd;
        }

        .urgent.clickable-notification:hover {
            background-color: #ffebee;
            border-color: #dc3545;
        }
    </style>

    <script>
        function viewProductWithComments(productId) {
            console.log('Opening product with comments for ID:', productId);

            // Show loading state
            const notification = event.currentTarget;
            const originalContent = notification.innerHTML;
            notification.innerHTML = `
                <div class="text-center p-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Opening product comments...</p>
                </div>
            `;

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
                        // Open the product modal with comments
                        openProductModal(data.product, data.comments, data.documents);
                    } else {
                        throw new Error(data.message || 'Failed to load product details');
                    }
                })
                .catch(error => {
                    console.error('Error loading product details:', error);
                    // Restore original content
                    notification.innerHTML = originalContent;
                    alert('Error loading product details: ' + error.message);
                });
        }

        function openProductModal(product, comments, documents) {
            // Create modal HTML
            const modalHtml = `
                <div class="modal fade" id="productCommentsModal" tabindex="-1" aria-labelledby="productCommentsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="productCommentsModalLabel">
                                    <i class="fa fa-comments"></i> Product Comments - ${product.product_name}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <h6>Product Information</h6>
                                        <p><strong>Name:</strong> ${product.product_name}</p>
                                        <p><strong>Category:</strong> ${product.product_category}</p>
                                        <p><strong>Status:</strong>
                                            ${product.is_approved === 1 ?
                                                '<span class="badge bg-success">Approved</span>' :
                                                (product.is_approved === 0 ?
                                                    '<span class="badge bg-danger">Rejected</span>' :
                                                    '<span class="badge bg-warning">Pending</span>'
                                                )
                                            }
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Product Images</h6>
                                        <div class="row">
                                            ${product.images && product.images.length > 0 ? product.images.map(image => `
                                                    <div class="col-4 mb-2">
                                                        <img src="${image.image_path}" alt="${product.product_name}" class="img-fluid rounded thumbnail">
                                                    </div>
                                                `).join('') : '<p>No images available.</p>'}
                                        </div>
                                    </div>
                                </div>

                                <div class="comments-section">
                                    <h6><i class="fa fa-comments"></i> Admin Comments</h6>
                                    ${comments && comments.length > 0 ? `
                                            <div class="comments-list">
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
                                        ` : `
                                            <div class="text-center text-muted py-3">
                                                <i class="fa fa-comments fa-2x mb-2"></i>
                                                <p>No admin comments yet.</p>
                                            </div>
                                        `}
                                </div>

                                ${documents && documents.length > 0 ? `
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="/supplier/products" class="btn btn-primary">
                                    <i class="fa fa-list"></i> View All Products
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Remove existing modal if any
            const existingModal = document.getElementById('productCommentsModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('productCommentsModal'));
            modal.show();

            // Clean up modal when hidden
            document.getElementById('productCommentsModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }

        function markAllNotificationsAsRead() {
            console.log('Marking all notifications as read from page...');

            // Disable the button and show loading state
            const btn = document.getElementById('markAllReadBtnPage');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Marking...';
            }

            fetch('/supplier/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Mark all read response:', data);
                    if (data.success) {
                        console.log('Successfully marked all notifications as read');
                        console.log('Updated count:', data.updated_count);

                        // Show success message
                        alert(
                            `Successfully marked ${data.updated_count} notification${data.updated_count > 1 ? 's' : ''} as read!`
                            );

                        // Reload the page to show updated notifications
                        window.location.reload();
                    } else {
                        console.error('Failed to mark all notifications as read:', data);
                        alert('Failed to mark notifications as read. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error marking all notifications as read:', error);
                    alert('Error marking notifications as read: ' + error.message);
                })
                .finally(() => {
                    // Re-enable the button
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fa fa-check"></i> Mark All as Read';
                    }
                });
        }
    </script>
@endsection
