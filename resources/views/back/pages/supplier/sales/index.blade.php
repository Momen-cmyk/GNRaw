@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Sales')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Sales Dashboard</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Sales</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Sales Statistics -->
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ number_format($totalSales, 2) }} USD</div>
                        <div class="font-14 text-secondary weight-500">Total Sales</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" style="color: #09cc7f"><i class="icon-copy fa fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ $totalOrders }}</div>
                        <div class="font-14 text-secondary weight-500">Total Orders</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" style="color: #ff5b5b"><i class="icon-copy fa fa-shopping-cart"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">
                            {{ $totalOrders > 0 ? number_format($totalSales / $totalOrders, 2) : '0.00' }} USD</div>
                        <div class="font-14 text-secondary weight-500">Average Order Value</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" style="color: #00cccc"><i class="icon-copy fa fa-chart-line"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">Recent Orders</h4>
        </div>
        <div class="pb-20">
            <table class="table hover data-table-export nowrap">
                <thead>
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong></td>
                            <td>{{ $order->customer->name }}</td>
                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                            <td>{{ number_format($order->total, 2) }} {{ $order->currency }}</td>
                            <td>{!! $order->status_label !!}</td>
                            <td>{!! $order->payment_status_label !!}</td>
                            <td>
                                <a href="{{ route('supplier.orders.view', $order->id) }}" class="btn btn-sm btn-primary">
                                    <i class="icon-copy dw dw-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="icon-copy dw dw-shopping-cart" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No orders yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
