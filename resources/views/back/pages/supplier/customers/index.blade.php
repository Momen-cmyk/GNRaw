@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Customers')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Customer Management</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">Your Customers</h4>
            <p class="text-muted">Customers who have ordered from your products</p>
        </div>
        <div class="pb-20">
            <table class="table hover data-table-export nowrap">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total Orders</th>
                        <th>Total Spent</th>
                        <th>Last Order</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td><strong>{{ $customer->name }}</strong></td>
                            <td>{{ $customer->email }}</td>
                            <td>{{ $customer->phone ?? 'N/A' }}</td>
                            <td>{{ $customer->orders->count() }}</td>
                            <td>
                                <strong>{{ number_format($customer->orders->sum('total'), 2) }} USD</strong>
                            </td>
                            <td>
                                @if ($customer->orders->first())
                                    {{ $customer->orders->first()->created_at->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info"
                                    onclick="viewCustomerDetails({{ $customer->id }})">
                                    <i class="icon-copy dw dw-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="icon-copy fa fa-users" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No customers yet</p>
                                <p class="text-muted">Customers will appear here once they place orders</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($customers->hasPages())
        <div class="d-flex justify-content-center">
            {{ $customers->links() }}
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        function viewCustomerDetails(customerId) {
            // Placeholder for customer details modal or page
            alert('Customer details view - Coming soon!');
        }
    </script>
@endpush
