@extends('layouts.auth')

@section('title', 'Register - PSARECO')

@section('content')
    <div class="login-container">
        <div class="login-card">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/PSARECO_logo.png') }}" alt="PSARECO Logo" style="width: 80px; cursor: pointer;">
                </a>
                <h3 class="mt-2" style="color: var(--primary);">Create Account</h3>
                <p class="text-muted">Register as Farmer Member</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="floating-field mb-3">
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder=" " value="{{ old('name') }}" required >
                    <label for="name"> <i class="fas fa-user"></i> Full Name </label>

                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="floating-field mb-3">
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                           placeholder=" " value="{{ old('email') }}" required autocomplete="off">
                    <label for="email"> <i class="fas fa-envelope"></i> Email Address </label>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="floating-field mb-3">

                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder=" " required autocomplete="new-password" >

                        <button type="button" class="btn btn-outline-secondary border border-secondary-200" id="togglePassword" title="Show/Hide" style="border-top-right-radius: 20%; border-bottom-right-radius: 20%;">
                            <i class="fas fa-eye"></i>
                        </button>

                        <label for="password">
                            <i class="fas fa-lock"></i> Password
                        </label>
                    </div>

                    <small class="text-muted text-sm d-block mt-1 ms-4">
                        Minimum 6 characters
                    </small>

                    <!-- Password Strength Indicator -->
                    <div class="mt-2">
                        <div class="progress" style="height: 5px;">
                            <div id="passwordStrengthBar" class="progress-bar" style="width: 0%;"></div>
                        </div>

                        <small id="passwordStrengthText" class="text-muted"></small>
                    </div>

                    @error('password')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="floating-field mb-3">
                    <div class="input-group">
                        <input type="password" name="password_confirmation" id="confirmPassword" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder=" " required autocomplete="new-password" >
                        <label for="confirmPassword">
                            <i class="fas fa-check-circle"></i> Confirm Password
                        </label>
                        <button type="button" class="btn btn-outline-secondary border border-secondary-200" id="toggleConfirmPassword" title="Show/Hide" >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <small id="passwordMatchText" class="text-muted d-block mt-1 ms-4"></small>

                    @error('password_confirmation')
                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="alert alert-warning" style="display: none;">
                    <i class="fas fa-clock"></i> <strong>Registration requires approval!</strong><br>
                    <small>After registration, an administrator needs to approve your account before you can login.</small>
                </div>

                <button type="submit" class="btn btn-success w-100 py-2">
                    <i class="fas fa-user-plus"></i> Register as Farmer
                </button>
            </form>

            <div class="text-center mt-3">
                <p class="text-muted small">Already have an account?  <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Login</a></p>
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
        function setupPasswordToggle(toggleButtonId, inputFieldId) {
            const toggleBtn = document.getElementById(toggleButtonId);
            const field = document.getElementById(inputFieldId);

            if (toggleBtn && field) {
                toggleBtn.addEventListener('click', function () {
                    const type = field.getAttribute('type') === 'password' ? 'text' : 'password';
                    field.setAttribute('type', type);

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
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            let strength = 0;
            const strengthBar = document.getElementById('passwordStrengthBar');
            const strengthText = document.getElementById('passwordStrengthText');

            if (!password) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'progress-bar';
                strengthText.textContent = '';
                return;
            }

            // Length check
            if (password.length >= 6) strength += 20;
            if (password.length >= 8) strength += 20;
            if (password.length >= 12) strength += 10;

            // Uppercase letters
            if (/[A-Z]/.test(password)) strength += 15;

            // Lowercase letters
            if (/[a-z]/.test(password)) strength += 15;

            // Numbers
            if (/[0-9]/.test(password)) strength += 10;

            // Special characters
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;

            // Update bar
            strengthBar.style.width = Math.min(strength, 100) + '%';

            // Update text and color
            if (strength < 30) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = '🔴 Weak';
                strengthText.style.color = '#dc3545';
            } else if (strength < 60) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = '🟡 Fair';
                strengthText.style.color = '#ffc107';
            } else if (strength < 80) {
                strengthBar.className = 'progress-bar bg-info';
                strengthText.textContent = '🔵 Good';
                strengthText.style.color = '#17a2b8';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = '🟢 Strong';
                strengthText.style.color = '#28a745';
            }
        }

        // Check if passwords match
        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const matchText = document.getElementById('passwordMatchText');

            if (!password || !confirmPassword) {
                matchText.textContent = '';
                matchText.className = 'text-muted d-block mt-1 ms-4';
                return;
            }

            if (password === confirmPassword) {
                matchText.textContent = '✓ Passwords match';
                matchText.className = 'text-success d-block mt-1 ms-4';
            } else {
                matchText.textContent = '✗ Passwords do not match';
                matchText.className = 'text-danger d-block mt-1 ms-4';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function () {
            setupPasswordToggle('togglePassword', 'password');
            setupPasswordToggle('toggleConfirmPassword', 'confirmPassword');

            const passwordField = document.getElementById('password');
            const confirmPasswordField = document.getElementById('confirmPassword');

            if (passwordField) {
                passwordField.addEventListener('input', function () {
                    checkPasswordStrength(this.value);
                    checkPasswordMatch();
                });
            }

            if (confirmPasswordField) {
                confirmPasswordField.addEventListener('input', checkPasswordMatch);
            }

            // Check initial password if filled
            if (passwordField && passwordField.value) {
                checkPasswordStrength(passwordField.value);
            }

            // Check initial password match if both fields are filled
            if (passwordField && confirmPasswordField && passwordField.value && confirmPasswordField.value) {
                checkPasswordMatch();
            }
        });
    </script>
@endpush
