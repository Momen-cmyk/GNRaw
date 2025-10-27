@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Notifications')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Notifications</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Notifications
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 col-sm-12 text-end">
                @if ($notifications->count() > 0)
                    <button type="button" class="btn btn-outline-primary" onclick="markAllNotificationsAsRead()"
                        id="markAllReadBtnPage">
                        <i class="fa fa-check"></i> Mark All as Read
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <h4 class="mb-4">All Notifications</h4>

                @if ($notifications->count() > 0)
                    <div class="notifications-list">
                        @foreach ($notifications as $notification)
                            <div
                                class="notification-item border rounded p-3 mb-3 {{ !$notification->is_read ? 'unread' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="notification-icon me-3">
                                                <i
                                                    class="fa {{ $notification->action_icon }} text-{{ $notification->action_color }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="notification-title mb-0">{{ $notification->title }}</h6>
                                                <div class="notification-badges">
                                                    @if (!$notification->is_read)
                                                        <span class="badge bg-primary me-2">New</span>
                                                    @endif
                                                    <span class="badge bg-{{ $notification->action_color }} me-2">
                                                        {{ ucfirst($notification->action) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="notification-message mb-2">{{ $notification->message }}</p>

                                        @if ($notification->data)
                                            <div class="notification-data mb-2">
                                                @if (isset($notification->data['supplier_name']))
                                                    <small class="text-muted">
                                                        <i class="fa fa-building"></i> Supplier:
                                                        {{ $notification->data['supplier_name'] }}
                                                    </small>
                                                @endif
                                                @if (isset($notification->data['product_name']))
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fa fa-box"></i> Product:
                                                        {{ $notification->data['product_name'] }}
                                                    </small>
                                                @endif
                                            </div>

                                            @if (isset($notification->data['product_id']) && $notification->data['product_id'])
                                                <div class="notification-actions mb-2">
                                                    <a href="{{ route('admin.products.show', $notification->data['product_id']) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye"></i> View Product Details
                                                    </a>
                                                    @if ($notification->action === 'added')
                                                        <a href="{{ route('admin.products.show', $notification->data['product_id']) }}#approve"
                                                            class="btn btn-sm btn-outline-success">
                                                            <i class="fa fa-check"></i> Review & Approve
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif

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
                                            <form action="{{ route('admin.notifications.mark_read', $notification->id) }}"
                                                method="POST" class="d-inline">
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

                    <div class="d-flex justify-content-center">
                        {{ $notifications->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-bell-slash fa-3x text-muted mb-3"></i>
                        <h5>No Notifications</h5>
                        <p class="text-muted">You don't have any notifications yet. When suppliers add or update products,
                            you'll see them here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .notification-item {
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .notification-item.unread {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3 !important;
        }

        .notification-title {
            font-weight: 600;
            color: #333;
        }

        .notification-message {
            color: #666;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 12px;
            color: #999;
        }

        .notification-data {
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 4px;
            border-left: 3px solid #007bff;
        }

        .badge {
            font-size: 10px;
            padding: 4px 8px;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f8f9fa;
        }

        .notification-badges {
            margin-top: 4px;
        }

        .notification-actions {
            margin-top: 8px;
        }

        .notification-actions .btn {
            font-size: 11px;
            padding: 4px 8px;
            margin-right: 5px;
        }

        .notification-item.unread .notification-icon {
            background-color: #e3f2fd;
        }

        .notification-item.unread .notification-title {
            font-weight: 700;
        }
    </style>

    <script>
        function markAllNotificationsAsRead() {
            console.log('Marking all admin notifications as read from page...');

            // Disable the button and show loading state
            const btn = document.getElementById('markAllReadBtnPage');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Marking...';
            }

            fetch('/admin/notifications/mark-all-read', {
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
