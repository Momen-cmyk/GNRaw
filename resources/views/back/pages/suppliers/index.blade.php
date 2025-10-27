@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Supplier Management')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Supplier Management</h5>
                    <div class="card-tools">
                        <a href="{{ route('admin.suppliers.index', ['status' => 'pending']) }}"
                            class="btn btn-warning btn-sm">
                            <i class="fa fa-clock"></i> Pending
                            ({{ $suppliers->where('approval_status', 'pending')->count() }})
                        </a>
                        <a href="{{ route('admin.suppliers.index', ['status' => 'approved']) }}"
                            class="btn btn-success btn-sm">
                            <i class="fa fa-check"></i> Approved
                            ({{ $suppliers->where('approval_status', 'approved')->count() }})
                        </a>
                        <a href="{{ route('admin.suppliers.index', ['status' => 'rejected']) }}"
                            class="btn btn-danger btn-sm">
                            <i class="fa fa-times"></i> Rejected
                            ({{ $suppliers->where('approval_status', 'rejected')->count() }})
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.suppliers.index') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search suppliers..." value="{{ $search }}">
                                    <input type="hidden" name="status" value="{{ $status }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('admin.suppliers.index') }}">
                                <div class="input-group">
                                    <select name="status" class="form-control">
                                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending
                                        </option>
                                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved
                                        </option>
                                        <option value="rejected" {{ $status == 'rejected' ? 'selected' : '' }}>Rejected
                                        </option>
                                    </select>
                                    <input type="hidden" name="search" value="{{ $search }}">
                                    <button class="btn btn-secondary" type="submit">Filter</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Suppliers Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Contact</th>
                                    <th>Activity</th>
                                    <th>Employees</th>
                                    <th>Status</th>
                                    <th>Applied</th>
                                    <th>Documents</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <span
                                                        class="avatar-title bg-primary rounded-circle">{{ substr($supplier->company_name, 0, 1) }}</span>
                                                </div>
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
                                            <span
                                                class="badge bg-info">{{ ucfirst(str_replace('_', ' & ', $supplier->company_activity)) }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $supplier->employee_range_display }}</small>
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
                                            <span class="badge bg-secondary">{{ $supplier->documents->count() }}
                                                files</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                @if ($supplier->approval_status === 'pending')
                                                    <form method="POST"
                                                        action="{{ route('admin.suppliers.approve', $supplier->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Approve this supplier?')">
                                                            <i class="fa fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                @elseif ($supplier->approval_status === 'rejected')
                                                    <form method="POST"
                                                        action="{{ route('admin.suppliers.approve', $supplier->id) }}"
                                                        class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success"
                                                            onclick="return confirm('Approve this previously rejected supplier?')">
                                                            <i class="fa fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No suppliers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $suppliers->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
