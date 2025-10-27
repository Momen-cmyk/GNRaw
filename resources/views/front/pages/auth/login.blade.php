@extends('front.layout.app')
@section('title', 'Login - GNRAW')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Login to Your Account</h4>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if (session('fail'))
                            <div class="alert alert-danger">{{ session('fail') }}</div>
                        @endif

                        <form action="{{ route('user.login_handler') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="login_id" class="form-label">Email or Username</label>
                                <input type="text" class="form-control @error('login_id') is-invalid @enderror"
                                    id="login_id" name="login_id" value="{{ old('login_id') }}" required>
                                @error('login_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </form>

                        <div class="text-center mt-3">
                            <a href="{{ route('user.forgot') }}" class="text-decoration-none">Forgot your password?</a>
                        </div>

                        <hr>

                        <div class="text-center">
                            <p>Don't have an account? <a href="{{ route('register') }}"
                                    class="text-decoration-none">Register here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
