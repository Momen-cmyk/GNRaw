@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Inventory')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Inventory Management</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Inventory</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Inventory Statistics -->
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark">{{ $inventory->sum('quantity_available') }}</div>
                        <div class="font-14 text-secondary weight-500">Total Stock</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" style="color: #09cc7f"><i class="icon-copy fa fa-boxes"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark text-warning">{{ $lowStockItems }}</div>
                        <div class="font-14 text-secondary weight-500">Low Stock Items</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" style="color: #ffb74d"><i class="icon-copy fa fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 mb-20">
            <div class="card-box height-100-p widget-style3">
                <div class="d-flex flex-wrap">
                    <div class="widget-data">
                        <div class="weight-700 font-24 text-dark text-danger">{{ $outOfStockItems }}</div>
                        <div class="font-14 text-secondary weight-500">Out of Stock</div>
                    </div>
                    <div class="widget-icon">
                        <div class="icon" style="color: #ff5b5b"><i class="icon-copy fa fa-times-circle"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <!-- Inventory Table -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="text-blue h4">Inventory List</h4>
                <a href="{{ route('supplier.inventory.movements') }}" class="btn btn-sm btn-outline-primary">
                    <i class="icon-copy fa fa-history"></i> View Movements
                </a>
            </div>
        </div>
        <div class="pb-20">
            <table class="table hover data-table-export nowrap">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Available</th>
                        <th>Reserved</th>
                        <th>Sold</th>
                        <th>Min Level</th>
                        <th>Reorder Point</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th class="datatable-nosort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventory as $item)
                        <tr class="{{ $item->isLowStock() ? 'bg-light-warning' : '' }}">
                            <td>
                                <strong>{{ $item->product->product_name }}</strong><br>
                                <small class="text-muted">{{ $item->product->product_category }}</small>
                            </td>
                            <td>
                                <strong>{{ $item->quantity_available }}</strong>
                                @if ($item->isOutOfStock())
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($item->isLowStock())
                                    <span class="badge bg-warning">Low Stock</span>
                                @endif
                            </td>
                            <td>{{ $item->quantity_reserved }}</td>
                            <td>{{ $item->quantity_sold }}</td>
                            <td>{{ $item->min_stock_level }}</td>
                            <td>{{ $item->reorder_point }}</td>
                            <td>{{ $item->warehouse_location ?? 'N/A' }}</td>
                            <td>
                                @if ($item->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-toggle="modal"
                                    data-target="#updateInventoryModal-{{ $item->id }}">
                                    <i class="icon-copy dw dw-edit2"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Update Inventory Modal -->
                        <div class="modal fade" id="updateInventoryModal-{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Update Inventory - {{ $item->product->product_name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="{{ route('supplier.inventory.update') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="inventory_id" value="{{ $item->id }}">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Movement Type</label>
                                                <select name="movement_type" class="form-control" required>
                                                    <option value="purchase">Purchase (Add Stock)</option>
                                                    <option value="adjustment">Adjustment</option>
                                                    <option value="damage">Damage</option>
                                                    <option value="expired">Expired</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Quantity</label>
                                                <input type="number" name="quantity" class="form-control" required
                                                    min="1">
                                            </div>
                                            <div class="form-group">
                                                <label>Notes</label>
                                                <textarea name="notes" class="form-control" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Inventory</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="icon-copy fa fa-boxes" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No inventory records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($inventory->hasPages())
        <div class="d-flex justify-content-center">
            {{ $inventory->links() }}
        </div>
    @endif
@endsection
