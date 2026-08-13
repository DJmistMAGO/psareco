@extends('layouts.auth')

@section('title', 'PSARECO Login')

@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" style="width: 80px; cursor: pointer;">
                </a>
                <h2 class="mt-2" style="color: var(--primary); font-weight: 700;">PSARECO</h2>
                <p class="text-muted fw-bold" style="font-size: 0.9rem;">Farm Resource Management System</p>
                <p class="small text-muted" style="font-size: 0.75rem;">Machinery Scheduling & Inventory Monitoring</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #dc3545; border-radius: 0.375rem;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong><i class="fas fa-exclamation-circle me-2"></i>Login Error</strong>
                            @foreach ($errors->all() as $error)
                                <div class="mt-2" style="font-size: 0.95rem;">{{ $error }}</div>
                            @endforeach
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-left: 4px solid #198754; border-radius: 0.375rem;">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong><i class="fas fa-check-circle me-2"></i>Success</strong>
                            <div class="mt-2" style="font-size: 0.95rem;">{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="floating-field mb-3">
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                               placeholder="" value="{{ old('email') }}" required autocomplete="off">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </div>

                <div class="floating-field mb-3">
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror"
                                   placeholder="" required autocomplete="current-password">
                            <button type="button" class="btn btn-outline-secondary border border-secondary-200" id="togglePassword" title="Show/Hide" style="border-top-right-radius: 20%; border-bottom-right-radius: 20%;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        </div>
                        @error('password')
                            <div class="invalid-feedback" style="display: block;">{{ $message }}</div>
                        @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-semibold" style="font-size: 1rem;">
                    <i class="fas fa-sign-in-alt me-2"></i> Login
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="text-muted small">Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Register here</a></p>
            </div>

            <hr class="my-4">

            <div class="text-center">
                <p class="text-muted small italic"><i class="fas fa-copyright"></i> <span>2026</span> PSARECO Cooperative</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

        // Password toggle functionality
        const togglePasswordBtn = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        if (togglePasswordBtn && passwordField) {
            togglePasswordBtn.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);

                const icon = this.querySelector('i');
                if (type === 'text') {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        }
    </script>
@endpush
