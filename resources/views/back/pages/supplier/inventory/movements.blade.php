@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Inventory Movements')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Inventory Movements History</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('supplier.inventory') }}">Inventory</a></li>
                        <li class="breadcrumb-item active">Movements</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Movements Table -->
    <div class="card-box mb-30">
        <div class="pd-20">
            <h4 class="text-blue h4">Stock Movement History</h4>
        </div>
        <div class="pb-20">
            <table class="table hover data-table-export nowrap">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>Reference</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <strong>{{ $movement->product->product_name }}</strong>
                            </td>
                            <td>
                                @php
                                    $typeColors = [
                                        'purchase' => 'success',
                                        'sale' => 'primary',
                                        'return' => 'info',
                                        'adjustment' => 'warning',
                                        'damage' => 'danger',
                                        'expired' => 'secondary',
                                        'transfer' => 'info',
                                        'reserve' => 'warning',
                                        'release' => 'success',
                                    ];
                                    $color = $typeColors[$movement->movement_type] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($movement->movement_type) }}</span>
                            </td>
                            <td>
                                @if (in_array($movement->movement_type, ['purchase', 'return', 'release']))
                                    <span class="text-success">+{{ $movement->quantity }}</span>
                                @else
                                    <span class="text-danger">-{{ $movement->quantity }}</span>
                                @endif
                            </td>
                            <td>{{ $movement->quantity_before }}</td>
                            <td><strong>{{ $movement->quantity_after }}</strong></td>
                            <td>{{ $movement->reference_number ?? 'N/A' }}</td>
                            <td>{{ Str::limit($movement->notes, 30) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="icon-copy fa fa-history" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">No movements recorded yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if ($movements->hasPages())
        <div class="d-flex justify-content-center">
            {{ $movements->links() }}
        </div>
    @endif
@endsection
