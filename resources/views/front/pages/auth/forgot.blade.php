@extends('front.layout.app')
@section('title', 'Forgot Password - GNRAW')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Reset Your Password</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('fail'))
                            <div class="alert alert-danger">{{ session('fail') }}</div>
                        @endif

                        <p class="text-muted">Enter your email address and we'll send you a link to reset your password.</p>

                        <form action="{{ route('user.send_password_reset_link') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Send Reset Link</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
