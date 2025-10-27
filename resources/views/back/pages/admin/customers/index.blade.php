@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Customers Management')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Customers Management</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Customers Management
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">All Customers</h4>
                    <p class="mb-30">Manage customer accounts and view their activity</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="customersTable">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Registration Date</th>
                                <th>Last Activity</th>
                                <th>Inquiries Count</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers ?? [] as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($customer->profile_picture)
                                                <img src="{{ asset('storage/' . $customer->profile_picture) }}"
                                                    alt="{{ $customer->name }}" class="rounded-circle me-2" width="40"
                                                    height="40"
                                                    onerror="this.src='{{ asset('images/users/default-user.png') }}'">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 40px; height: 40px;">
                                                    {{ substr($customer->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $customer->name }}</strong>
                                                <br>
                                                <small class="text-muted">ID: #{{ $customer->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $customer->phone ?? 'N/A' }}</td>
                                    <td>{{ $customer->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if ($customer->last_login_at)
                                            <span class="text-success">
                                                <i class="fa fa-circle text-success me-1"></i>
                                                {{ $customer->last_login_at->diffForHumans() }}
                                            </span>
                                        @else
                                            <span class="text-muted">
                                                <i class="fa fa-circle text-muted me-1"></i>
                                                Never
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $customer->inquiries_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">Active</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-customer-details"
                                            data-customer-id="{{ $customer->id }}">
                                            <i class="fa fa-eye"></i> View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No customers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerDetailsModalLabel">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="customerDetailsContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading customer details...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .box-shadow {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .border-radius-5 {
            border-radius: 5px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
        }

        .inquiry-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .product-item {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            background: #fff;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle customer details button clicks
            document.querySelectorAll('.view-customer-details').forEach(button => {
                button.addEventListener('click', function() {
                    const customerId = this.dataset.customerId;
                    loadCustomerDetails(customerId);
                });
            });

            function loadCustomerDetails(customerId) {
                const modal = new bootstrap.Modal(document.getElementById('customerDetailsModal'));
                const content = document.getElementById('customerDetailsContent');

                // Show loading state
                content.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading customer details...</p>
                    </div>
                `;

                modal.show();

                // Load customer details via AJAX
                fetch(`/admin/customers/${customerId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            content.innerHTML = `
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Customer Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Name:</strong> ${data.customer.name}</p>
                                                <p><strong>Email:</strong> ${data.customer.email}</p>
                                                <p><strong>Phone:</strong> ${data.customer.phone || 'N/A'}</p>
                                                <p><strong>Registration:</strong> ${data.customer.registration_date}</p>
                                                <p><strong>Last Activity:</strong> ${data.customer.last_activity || 'Never'}</p>
                                                <p><strong>Total Inquiries:</strong> ${data.inquiries.length}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Product Inquiries (${data.inquiries.length})</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        ${data.inquiries.length > 0 ?
                                                            data.inquiries.map(inquiry => `
                                                                        <div class="inquiry-item">
                                                                            <div class="d-flex justify-content-between align-items-start">
                                                                                <div>
                                                                                    <h6 class="mb-1">${inquiry.product_name}</h6>
                                                                                    <p class="mb-1 text-muted">${inquiry.message}</p>
                                                                                    <small class="text-muted">
                                                                                        Submitted: ${inquiry.created_at} |
                                                                                        Status: <span class="badge ${inquiry.status === 'pending' ? 'badge-warning' : 'badge-success'}">${inquiry.status}</span>
                                                                                    </small>
                                                                                </div>
                                                                                <div>
                                                                                    <span class="badge badge-info">${inquiry.supplier_name}</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    `).join('') :
                                                            '<p class="text-muted">No inquiries submitted</p>'
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        } else {
                            content.innerHTML = `
                                <div class="alert alert-danger">
                                    <i class="fa fa-exclamation-triangle"></i>
                                    Error loading customer details: ${data.message}
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i>
                                An error occurred while loading customer details.
                            </div>
                        `;
                    });
            }
        });
    </script>
@endpush
