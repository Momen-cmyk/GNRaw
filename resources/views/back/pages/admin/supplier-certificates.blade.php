@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Supplier Certificates Management')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Supplier Certificates Management</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Supplier Certificates
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
                    <h4 class="text-blue">Suppliers Overview</h4>
                    <p class="mb-30">Click on a supplier row to view detailed information</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="suppliersTable">
                        <thead>
                            <tr>
                                <th>Supplier Name</th>
                                <th>Email</th>
                                <th>Last Active</th>
                                <th>Products Count</th>
                                <th>Certificates Count</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers ?? [] as $supplier)
                                <tr class="supplier-row" data-supplier-id="{{ $supplier->id }}" style="cursor: pointer;">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($supplier->profile_picture)
                                                <img src="{{ asset('storage/' . $supplier->profile_picture) }}"
                                                    alt="{{ $supplier->company_name }}" class="rounded-circle me-2"
                                                    width="40" height="40"
                                                    onerror="this.src='{{ asset('images/users/default-user.png') }}'">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                    style="width: 40px; height: 40px;">
                                                    {{ substr($supplier->company_name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $supplier->company_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $supplier->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $supplier->email }}</td>
                                    <td>
                                        <span class="text-muted">
                                            <i class="fa fa-circle text-muted me-1"></i>
                                            {{ $supplier->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $supplier->products_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-warning">{{ $supplier->certificates_count ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @if ($supplier->approval_status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif ($supplier->approval_status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-details"
                                            data-supplier-id="{{ $supplier->id }}">
                                            <i class="fa fa-eye"></i> View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">No suppliers found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Supplier Details Modal -->
    <div class="modal fade" id="supplierDetailsModal" tabindex="-1" aria-labelledby="supplierDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="supplierDetailsModalLabel">Supplier Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="supplierDetailsContent">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading supplier details...</p>
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

        .supplier-row:hover {
            background-color: #f8f9fa;
        }

        .badge {
            font-size: 0.75em;
        }

        .certificate-item {
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
            // Handle supplier row clicks
            document.querySelectorAll('.supplier-row').forEach(row => {
                row.addEventListener('click', function(e) {
                    if (!e.target.closest('.view-details')) {
                        const supplierId = this.dataset.supplierId;
                        loadSupplierDetails(supplierId);
                    }
                });
            });

            // Handle view details button clicks
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const supplierId = this.dataset.supplierId;
                    loadSupplierDetails(supplierId);
                });
            });

            function loadSupplierDetails(supplierId) {
                const modal = new bootstrap.Modal(document.getElementById('supplierDetailsModal'));
                const content = document.getElementById('supplierDetailsContent');

                // Show loading state
                content.innerHTML = `
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading supplier details...</p>
                    </div>
                `;

                modal.show();

                // Load supplier details via AJAX
                fetch(`/admin/suppliers/${supplierId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            content.innerHTML = `
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h5>Supplier Information</h5>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Company:</strong> ${data.supplier.company_name}</p>
                                                <p><strong>Contact Person:</strong> ${data.supplier.name}</p>
                                                <p><strong>Email:</strong> ${data.supplier.email}</p>
                                                <p><strong>Phone:</strong> ${data.supplier.phone || 'N/A'}</p>
                                                <p><strong>Status:</strong>
                                                    <span class="badge ${data.supplier.approval_status === 'approved' ? 'badge-success' : data.supplier.approval_status === 'rejected' ? 'badge-danger' : 'badge-warning'}">
                                                        ${data.supplier.approval_status === 'approved' ? 'Approved' : data.supplier.approval_status === 'rejected' ? 'Rejected' : 'Pending'}
                                                    </span>
                                                </p>
                                                <p><strong>Member Since:</strong> ${data.supplier.created_at}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Certificates (${data.certificates.length})</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        ${data.certificates.length > 0 ?
                                                            data.certificates.map(cert => `
                                                                            <div class="certificate-item">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <div>
                                                                                        <h6 class="mb-1">${cert.certificate_type}</h6>
                                                                                        <p class="mb-1 text-muted">${cert.certificate_name}</p>
                                                                                        <small class="text-muted">Uploaded: ${cert.created_at}</small>
                                                                                    </div>
                                                                                    <div>
                                                                                        <a href="/storage/${cert.file_path}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                                            <i class="fa fa-download"></i> View
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `).join('') :
                                                            '<p class="text-muted">No certificates uploaded</p>'
                                                        }
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5>Products (${data.products.length})</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        ${data.products.length > 0 ?
                                                            data.products.map(product => `
                                                                            <div class="product-item">
                                                                                <div class="d-flex justify-content-between align-items-center">
                                                                                    <div>
                                                                                        <h6 class="mb-1">${product.product_name}</h6>
                                                                                        <p class="mb-1 text-muted">${product.product_category}</p>
                                                                                        <small class="text-muted">MOQ: ${product.moq} Kg</small>
                                                                                    </div>
                                                                                    <div>
                                                                                        <span class="badge ${product.is_approved ? 'badge-success' : 'badge-warning'}">
                                                                                            ${product.is_approved ? 'Approved' : 'Pending'}
                                                                                        </span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        `).join('') :
                                                            '<p class="text-muted">No products added</p>'
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
                                    Error loading supplier details: ${data.message}
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        content.innerHTML = `
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i>
                                An error occurred while loading supplier details.
                            </div>
                        `;
                    });
            }
        });
    </script>
@endpush
