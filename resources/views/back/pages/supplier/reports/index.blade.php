@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Reports')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h4>Reports & Analytics</h4>
                </div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="card-box pd-20">
        <div class="text-center py-5">
            <i class="icon-copy fa fa-chart-bar" style="font-size: 64px; opacity: 0.3;"></i>
            <h4 class="mt-3">Advanced Reports & Analytics</h4>
            <p class="text-muted">This section will contain detailed sales reports, inventory analytics, and business
                insights.</p>
            <p class="text-muted">Coming soon!</p>
        </div>
    </div>
@endsection
