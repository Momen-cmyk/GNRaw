@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Order Details')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Order Details - {{ $order->order_number }}</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('supplier.orders') }}">Orders</a></li>
                        <li class="breadcrumb-item active">{{ $order->order_number }}</li>
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

    <div class="row">
        <!-- Order Information -->
        <div class="col-md-8 mb-30">
            <div class="card-box pd-20 height-100-p">
                <div class="d-flex justify-content-between align-items-center mb-20">
                    <h4 class="text-blue h4">Order Information</h4>
                    <div>
                        {!! $order->status_label !!}
                        {!! $order->payment_status_label !!}
                    </div>
                </div>

                <!-- Order Items -->
                <h5 class="mb-10">Order Items</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->product_name }}</strong><br>
                                    <small class="text-muted">{{ $item->product_sku }}</small>
                                </td>
                                <td>{{ number_format($item->unit_price, 2) }} {{ $order->currency }}</td>
                                <td>{{ $item->quantity }} {{ $item->unit_of_measure }}</td>
                                <td><strong>{{ number_format($item->total, 2) }} {{ $order->currency }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Subtotal:</strong></td>
                            <td><strong>{{ number_format($order->subtotal, 2) }} {{ $order->currency }}</strong></td>
                        </tr>
                        @if ($order->tax > 0)
                            <tr>
                                <td colspan="3" class="text-right">Tax:</td>
                                <td>{{ number_format($order->tax, 2) }} {{ $order->currency }}</td>
                            </tr>
                        @endif
                        @if ($order->shipping_cost > 0)
                            <tr>
                                <td colspan="3" class="text-right">Shipping:</td>
                                <td>{{ number_format($order->shipping_cost, 2) }} {{ $order->currency }}</td>
                            </tr>
                        @endif
                        @if ($order->discount > 0)
                            <tr>
                                <td colspan="3" class="text-right">Discount:</td>
                                <td>-{{ number_format($order->discount, 2) }} {{ $order->currency }}</td>
                            </tr>
                        @endif>
                        <tr class="bg-light">
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td><strong class="text-primary">{{ number_format($order->total, 2) }}
                                    {{ $order->currency }}</strong></td>
                        </tr>
                    </tfoot>
                </table>

                <!-- Customer Notes -->
                @if ($order->customer_notes)
                    <div class="mt-20">
                        <h5>Customer Notes</h5>
                        <p class="text-muted">{{ $order->customer_notes }}</p>
                    </div>
                @endif>
            </div>
        </div>

        <!-- Order Details Sidebar -->
        <div class="col-md-4 mb-30">
            <!-- Customer Information -->
            <div class="card-box pd-20 mb-20">
                <h5 class="mb-15">Customer Information</h5>
                <p><strong>Name:</strong> {{ $order->customer->name }}</p>
                <p><strong>Email:</strong> {{ $order->customer->email }}</p>
            </div>

            <!-- Shipping Information -->
            <div class="card-box pd-20 mb-20">
                <h5 class="mb-15">Shipping Address</h5>
                <p>{{ $order->shipping_name }}<br>
                    {{ $order->shipping_address }}<br>
                    {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                    {{ $order->shipping_country }}<br>
                    <strong>Phone:</strong> {{ $order->shipping_phone }}
                </p>
            </div>

            <!-- Tracking Information -->
            <div class="card-box pd-20 mb-20">
                <h5 class="mb-15">Tracking Information</h5>
                @if ($order->tracking_number)
                    <p><strong>Tracking #:</strong> {{ $order->tracking_number }}</p>
                    <p><strong>Carrier:</strong> {{ $order->carrier }}</p>
                    @if ($order->shipped_at)
                        <p><strong>Shipped:</strong> {{ $order->shipped_at->format('M d, Y H:i') }}</p>
                    @endif
                    @if ($order->delivered_at)
                        <p><strong>Delivered:</strong> {{ $order->delivered_at->format('M d, Y H:i') }}</p>
                    @endif
                @else
                    <p class="text-muted">No tracking information yet</p>
                @endif
            </div>

            <!-- Update Status Form -->
            <div class="card-box pd-20">
                <h5 class="mb-15">Update Order Status</h5>
                <form action="{{ route('supplier.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed
                            </option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing
                            </option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered
                            </option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Tracking Number</label>
                        <input type="text" name="tracking_number" class="form-control"
                            value="{{ $order->tracking_number }}">
                    </div>
                    <div class="form-group">
                        <label>Carrier</label>
                        <input type="text" name="carrier" class="form-control" value="{{ $order->carrier }}"
                            placeholder="e.g., FedEx, DHL">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Update Status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
