@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-box icon-box-sm bg-primary-light">
                            <i class="fa fa-users text-primary"></i>
                        </div>
                        <div class="ms-3">
                            <h3 class="mb-0">{{ $totalSuppliers ?? 0 }}</h3>
                            <p class="text-muted mb-0">Total Suppliers</p>
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
                            <h3 class="mb-0">{{ $pendingSuppliers ?? 0 }}</h3>
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
                            <h3 class="mb-0">{{ $approvedSuppliers ?? 0 }}</h3>
                            <p class="text-muted mb-0">Approved Suppliers</p>
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
                            <h3 class="mb-0">{{ $totalInquiries ?? 0 }}</h3>
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
                    <h5 class="card-title">Recent Supplier Applications</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Contact</th>
                                    <th>Status</th>
                                    <th>Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentSuppliers ?? [] as $supplier)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <h6 class="mb-0">{{ $supplier->company_name }}</h6>
                                                    <small class="text-muted">{{ $supplier->company_activity }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <h6 class="mb-0">{{ $supplier->name }}</h6>
                                                <small class="text-muted">{{ $supplier->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($supplier->approval_status === 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($supplier->approval_status === 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $supplier->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                                    class="btn btn-sm btn-outline-primary">View</a>
                                                @if ($supplier->approval_status === 'pending')
                                                    <form action="{{ route('admin.suppliers.approve', $supplier->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Are you sure you want to approve this supplier?')">Approve</button>
                                                    </form>
                                                @elseif ($supplier->approval_status === 'approved')
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectModal{{ $supplier->id }}">
                                                        Reject
                                                    </button>
                                                @elseif ($supplier->approval_status === 'rejected')
                                                    <form action="{{ route('admin.suppliers.approve', $supplier->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Are you sure you want to approve this previously rejected supplier?')">Approve</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No supplier applications found
                                        </td>
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
                    <h5 class="card-title">Recent Client Inquiries</h5>
                </div>
                <div class="card-body">
                    @forelse($recentInquiries ?? [] as $inquiry)
                        <div class="d-flex align-items-start mb-3">
                            <div class="avatar-sm me-3">
                                <span class="avatar-title bg-info rounded-circle">{{ substr($inquiry->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $inquiry->name }}</h6>
                                <p class="text-muted mb-1">{{ Str::limit($inquiry->message, 50) }}</p>
                                <small class="text-muted">{{ $inquiry->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No inquiries found</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal for Approved Suppliers -->
    @foreach ($recentSuppliers ?? [] as $supplier)
        @if ($supplier->approval_status === 'approved')
            <div class="modal fade" id="rejectModal{{ $supplier->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.suppliers.reject', $supplier->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Reject Supplier</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to reject <strong>{{ $supplier->company_name }}</strong>?</p>
                                <div class="mb-3">
                                    <label for="rejection_reason" class="form-label">Rejection Reason</label>
                                    <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3" required
                                        placeholder="Please provide a reason for rejection..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Reject Supplier</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
