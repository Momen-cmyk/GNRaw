@extends('back.layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Certificates Management')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="title">
                    <h4>Certificates Management</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Certificates Management
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">All Supplier Certificates</h4>
                    <p class="mb-30">Manage and verify supplier certificates</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="certificatesTable">
                        <thead>
                            <tr>
                                <th>Supplier</th>
                                <th>Certificate Type</th>
                                <th>Certificate Name</th>
                                <th>Upload Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6" class="text-center text-muted">No certificates available</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Certificate Statistics</h4>
                    <p class="mb-30">Overview of certificate types</p>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="text-primary">0</h3>
                            <p class="text-muted">ISO Certificates</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="text-success">0</h3>
                            <p class="text-muted">GMP Certificates</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="text-info">0</h3>
                            <p class="text-muted">WC Certificates</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <h3 class="text-warning">0</h3>
                            <p class="text-muted">Manufacturing</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 mb-30">
            <div class="bg-white pd-20 box-shadow border-radius-5">
                <div class="title">
                    <h4 class="text-blue">Recent Activity</h4>
                    <p class="mb-30">Latest certificate uploads</p>
                </div>
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">No recent activity</h6>
                            <small class="text-muted">-</small>
                        </div>
                        <p class="mb-1 text-muted">No certificates uploaded recently</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .box-shadow {
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .border-radius-5 {
            border-radius: 5px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
@endpush
