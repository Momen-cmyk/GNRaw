@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Shipping')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Shipping Management</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Shipping</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Pending Shipments -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">Pending Shipments</h4>
            <p class="text-muted">Orders ready to be shipped</p>
        </div>
        <div class="pb-20">
            <table class="table hover nowrap">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Shipping Address</th>
                        <th>Items</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingShipments as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>
                                {{ $order->customer->name }}<br>
                                <small class="text-muted">{{ $order->shipping_phone }}</small>
                            </td>
                            <td>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_state }}
                                {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </td>
                            <td>{{ $order->items->count() }} items</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{!! $order->status_label !!}</td>
                            <td>
                                <a href="{{ route('supplier.orders.view', $order->id) }}" class="btn btn-sm btn-primary">
                                    <i class="icon-copy dw dw-truck"></i> Ship Now
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="icon-copy fa fa-truck" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No pending shipments</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination for Pending -->
    @if ($pendingShipments->hasPages())
        <div class="d-flex justify-content-center mb-30">
            {{ $pendingShipments->links() }}
        </div>
    @endif

    <!-- Recently Shipped Orders -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">Recently Shipped Orders</h4>
        </div>
        <div class="pb-20">
            <table class="table hover nowrap">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Tracking #</th>
                        <th>Carrier</th>
                        <th>Shipped Date</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($shippedOrders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer->name }}</td>
                            <td>
                                @if ($order->tracking_number)
                                    <code>{{ $order->tracking_number }}</code>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $order->carrier ?? 'N/A' }}</td>
                            <td>{{ $order->shipped_at ? $order->shipped_at->format('M d, Y H:i') : 'N/A' }}</td>
                            <td>{!! $order->status_label !!}</td>
                            <td>
                                <a href="{{ route('supplier.orders.view', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="icon-copy dw dw-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <p>No shipped orders yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
