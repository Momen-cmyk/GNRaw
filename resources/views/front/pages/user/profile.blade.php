@extends('front.layout.app')
@section('title', 'Profile - ' . ($user->name ?? 'User'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Profile Settings</h4>
                        <p class="text-muted mb-0">Manage your account information</p>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fa fa-check-circle"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('user.update_profile') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">Company</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <small class="text-muted">
                                                <i class="fa fa-info-circle"></i>
                                                Member since {{ $user->created_at->format('M d, Y') }}
                                            </small>
                                        </div>
                                        <div>
                                            <a href="{{ route('user.dashboard') }}" class="btn btn-secondary me-2">
                                                <i class="fa fa-arrow-left"></i> Back to Dashboard
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-save"></i> Update Profile
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Account Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Account Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Phone:</strong> {{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Company:</strong> {{ $user->company_name ?? 'Not provided' }}</p>
                                <p><strong>Last Login:</strong>
                                    {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Never' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
