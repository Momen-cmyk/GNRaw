@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Reports & Analytics')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Reports & Analytics</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Reports & Analytics
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date"
                                value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date"
                                value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-refresh"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5 height-100-p">
                <div class="icon bg-warning text-white">
                    <i class="fa fa-users"></i>
                </div>
                <div class="content">
                    <h5 class="text-muted">New Suppliers</h5>
                    <h3 class="text-warning">{{ number_format($totalSuppliers) }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="{{ $suppliersGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa fa-level-{{ $suppliersGrowth >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($suppliersGrowth), 1) }}%
                        </span>
                        from previous period
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5 height-100-p">
                <div class="icon bg-success text-white">
                    <i class="fa fa-check-circle"></i>
                </div>
                <div class="content">
                    <h5 class="text-muted">Approved Suppliers</h5>
                    <h3 class="text-success">{{ number_format($approvedSuppliers) }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="{{ $approvedSuppliersGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa fa-level-{{ $approvedSuppliersGrowth >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($approvedSuppliersGrowth), 1) }}%
                        </span>
                        from previous period
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5 height-100-p">
                <div class="icon bg-info text-white">
                    <i class="fa fa-user-plus"></i>
                </div>
                <div class="content">
                    <h5 class="text-muted">New Customers</h5>
                    <h3 class="text-info">{{ number_format($totalCustomers) }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="{{ $customersGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa fa-level-{{ $customersGrowth >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($customersGrowth), 1) }}%
                        </span>
                        from previous period
                    </p>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5 height-100-p">
                <div class="icon bg-primary text-white">
                    <i class="fa fa-box"></i>
                </div>
                <div class="content">
                    <h5 class="text-muted">New Products</h5>
                    <h3 class="text-primary">{{ number_format($totalProducts) }}</h3>
                    <p class="mb-0 text-muted">
                        <span class="{{ $productsGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                            <i class="fa fa-level-{{ $productsGrowth >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($productsGrowth), 1) }}%
                        </span>
                        from previous period
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Products Overview</h4>
                    <p class="mb-30">Monthly product approvals (Last 12 months)</p>
                </div>
                <div id="products-chart" style="height: 300px;">
                    @if ($monthlyProducts->count() > 0)
                        <canvas id="monthlyProductsChart"></canvas>
                    @else
                        <div class="d-flex align-items-center justify-content-center h-100">
                            <div class="text-center">
                                <i class="fa fa-chart-line fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No product data available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Top Categories</h4>
                    <p class="mb-30">Most active product categories</p>
                </div>
                <div class="list-group">
                    @forelse($topCategories as $category)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-semibold">{{ Str::limit($category->name, 30) }}</span>
                                <br>
                                <small class="text-muted">{{ $category->public_products_count }} products</small>
                            </div>
                            <span class="badge bg-primary">{{ $category->public_products_count }}</span>
                        </div>
                    @empty
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="text-muted">No categories available</span>
                            <span class="badge badge-secondary">0</span>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Recent Inquiries</h4>
                    <p class="mb-30">Latest customer inquiries</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentInquiries as $inquiry)
                                <tr>
                                    <td>{{ $inquiry->customer->name ?? 'Unknown' }}</td>
                                    <td>{{ Str::limit($inquiry->product_name, 30) }}</td>
                                    <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $inquiry->status === 'pending' ? 'warning' : ($inquiry->status === 'responded' ? 'success' : 'secondary') }}">
                                            {{ ucfirst($inquiry->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No inquiries available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Supplier Performance</h4>
                    <p class="mb-30">Top performing suppliers by products</p>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Total Products</th>
                                <th>Approved</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplierPerformance as $supplier)
                                <tr>
                                    <td>{{ Str::limit($supplier->company_name ?? $supplier->name, 20) }}</td>
                                    <td>{{ $supplier->products_count }}</td>
                                    <td>{{ $supplier->approved_products_count }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $supplier->approval_status === 'approved' ? 'success' : ($supplier->approval_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($supplier->approval_status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No supplier data available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row">
        <div class="col-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Inquiry Statistics</h4>
                    <p class="mb-30">Customer inquiry trends</p>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-primary">{{ number_format($totalInquiries) }}</h3>
                            <p class="text-muted">Total Inquiries</p>
                            <span class="{{ $inquiriesGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fa fa-level-{{ $inquiriesGrowth >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($inquiriesGrowth), 1) }}%
                            </span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-warning">{{ \App\Models\ClientInquiry::where('status', 'pending')->count() }}
                            </h3>
                            <p class="text-muted">Pending</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-success">
                                {{ \App\Models\ClientInquiry::where('status', 'responded')->count() }}</h3>
                            <p class="text-muted">Responded</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center">
                            <h3 class="text-secondary">{{ \App\Models\ClientInquiry::where('status', 'closed')->count() }}
                            </h3>
                            <p class="text-muted">Closed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @if ($monthlyProducts->count() > 0)
            // Monthly Products Chart
            const ctx = document.getElementById('monthlyProductsChart').getContext('2d');
            const monthlyProductsData = @json($monthlyProducts);

            // Fill in missing months with 0
            const months = [];
            const values = [];
            const currentDate = new Date();

            for (let i = 11; i >= 0; i--) {
                const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                const monthKey = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
                months.push(date.toLocaleDateString('en-US', {
                    month: 'short',
                    year: 'numeric'
                }));
                values.push(monthlyProductsData[monthKey] || 0);
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Products Approved',
                        data: values,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        @endif
    </script>
@endpush

@push('styles')
    <style>
        .icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .content h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .content h5 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .box-shadow {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .border-radius-5 {
            border-radius: 5px;
        }

        .height-100-p {
            height: 100%;
        }
    </style>
@endpush
