@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Orders')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Order Management</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Orders</li>
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

    <!-- Orders Table -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">All Orders</h4>
        </div>
        <div class="pb-20">
            <table class="table hover data-table-export nowrap">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>
                                {{ $order->customer->name }}<br>
                                <small class="text-muted">{{ $order->customer->email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ $order->items->count() }} items</td>
                            <td><strong>{{ number_format($order->total, 2) }} {{ $order->currency }}</strong></td>
                            <td>{!! $order->status_label !!}</td>
                            <td>{!! $order->payment_status_label !!}</td>
                            <td>
                                <a href="{{ route('supplier.orders.view', $order->id) }}" class="btn btn-sm btn-info">
                                    <i class="icon-copy dw dw-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="icon-copy dw dw-shopping-cart" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No orders found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($orders->hasPages())
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @endif
@endsection
