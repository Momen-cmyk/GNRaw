@extends('back.layout.supplier-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Supplier Profile')

@section('content')
    <div class="page-header">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="title">
                    <h4>Profile</h4>
                </div>
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('supplier.dashboard') }}">Home</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Profile
                        </li>
                </nav>
            </div>
        </div>
    </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <div class="text-center">
                    <img src="{{ Auth::user()->picture }}" alt="Profile Picture" class="rounded-circle mb-3" width="120"
                        height="120">
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3">Supplier Account</p>

                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-success">Active</span>
                    </div>

                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#profilePictureModal">
                        <i class="icon-copy dw dw-edit"></i>Change Picture</button>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-lg-8 col-md-12 mb-30">
            <div class="card-box height-100-p pd-20">
                <h4 class="mb-3">Account Information</h4>

                <form action="{{ route('supplier.update_profile') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" value="{{ Auth::user()->username }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" value="{{ Auth::user()->email }}"
                                required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Type</label>
                            <input type="text" class="form-control" value="Supplier" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Status</label>
                            <input type="text" class="form-control" value="Active" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Member Since</label>
                            <input type="text" class="form-control"
                                value="{{ Auth::user()->created_at->format('M d, Y') }}" readonly>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h5 class="mb-3">Business Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name"
                                value="{{ Auth::user()->company_name ?? '' }}" placeholder="Enter company name">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Company Activity</label>
                            <select class="form-control" name="company_activity">
                                <option value="">Select activity</option>
                                <option value="manufacturing"
                                    {{ (Auth::user()->company_activity ?? '') == 'manufacturing' ? 'selected' : '' }}>
                                    Manufacturing</option>
                                <option value="trading"
                                    {{ (Auth::user()->company_activity ?? '') == 'trading' ? 'selected' : '' }}>Trading
                                </option>
                                <option value="manufacturing_trading"
                                    {{ (Auth::user()->company_activity ?? '') == 'manufacturing_trading' ? 'selected' : '' }}>
                                    Manufacturing & Trading</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone"
                                value="{{ Auth::user()->phone ?? '' }}" placeholder="Enter phone number">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Number of Employees</label>
                            <input type="text" class="form-control"
                                value="{{ Auth::user()->employee_range_display ?? 'N/A' }}" readonly>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Company Description</label>
                            <textarea class="form-control" name="company_description" rows="3"
                                placeholder="Describe your company and products">{{ Auth::user()->company_description ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="icon-copy dw dw-check"></i> Update Profile
                            </button>
                            <a href="{{ route('supplier.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="icon-copy dw dw-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div class="modal fade" id="profilePictureModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="profilePictureForm" action="{{ route('supplier.upload_profile_picture') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select New Picture</label>
                            <input type="file" class="form-control" name="profilePictureFile" accept="image/*"
                                required>
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 2MB</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Upload Picture</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card-box {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-control:read-only {
            background-color: #f8f9fa;
            opacity: 1;
        }

        .badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Handle profile picture upload
            $('#profilePictureForm').on('submit', function(e) {
                e.preventDefault();

                var form = this;
                var formData = new FormData(form);
                var submitBtn = $(form).find('button[type="submit"]');
                var originalText = submitBtn.html();

                // Disable submit button and show loading
                submitBtn.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Uploading...');

                $.ajax({
                    url: $(form).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 1) {
                            // Update the profile picture on the page
                            $('img[alt="Profile Picture"]').attr('src', response.image_path);

                            // Update header dropdown images
                            $('.user-info-dropdown .user-icon img').attr('src', response
                                .image_path);
                            $('.user-info-header .user-avatar img').attr('src', response
                                .image_path);

                            // Show success message
                            $().notifa({
                                vers: 1,
                                cssClss: 'success',
                                html: response.message,
                                delay: 2500
                            });
                            // Close modal and reset form
                            $('#profilePictureModal').modal('hide');
                            $(form)[0].reset();
                        } else {
                            $().notifa({
                                vers: 1,
                                cssClss: 'error',
                                html: response.message,
                                delay: 2500
                            });
                        }
                    },
                    error: function(xhr) {
                        var response = xhr.responseJSON;
                        if (response && response.message) {
                            alert(response.message);
                        } else {
                            alert('An error occurred while uploading the picture.');
                        }
                    },
                    complete: function() {
                        // Re-enable submit button
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
