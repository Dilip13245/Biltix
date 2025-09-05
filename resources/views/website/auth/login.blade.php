<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('Login') }} - Biltix</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .auth-container {
            width: 448px;
            height: 570px;
            margin: 0 auto;
        }
        .auth-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #F3F4F6;
            box-shadow: 0px 10px 15px 0px #0000001A, 0px 4px 6px 0px #0000001A;
            width: 400px;
            min-height: 522px;
            padding: 33px;
            margin: 0 auto;
        }
        .form-content {
            width: 334px;
            height: auto;
            margin: 0 auto;
        }
        @media (max-width: 768px) {
            .auth-container {
                width: 100%;
                height: auto;
                padding: 16px;
            }
            .auth-card {
                width: 100%;
                height: auto;
                max-width: 400px;
                padding: 24px;
            }
            .form-content {
                width: 100%;
                height: auto;
            }
        }
        @media (max-width: 576px) {
            .auth-card {
                padding: 20px;
            }
        }
        .logo-container {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo {
            width: 56px;
            height: 56px;
            background: #FF8C42;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 16px;
        }
        .logo-text {
            color: #4A90E2;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .form-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 15px 48px 15px 16px;
            font-size: 16px;
            height: 50px;
            background: white;
            color: #111827;
            transition: all 0.2s ease;
            {{ is_rtl() ? 'text-align: right; direction: rtl;' : '' }}
        }
        .form-control:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
        }
        
        .form-control.error {
            border-color: #ef4444;
        }
        

        
        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        .form-control::placeholder {
            color: #9ca3af;
        }
        .input-icon {
            position: absolute;
            {{ is_rtl() ? 'left: 16px;' : 'right: 16px;' }}
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 10;
            font-size: 16px;
        }
        .form-control {
            {{ is_rtl() ? 'padding: 15px 16px 15px 48px; text-align: right;' : 'padding: 15px 48px 15px 16px;' }}
        }
        .btn-signin {
            background: #4477C4;
            border: none;
            border-radius: 12px;
            padding: 0;
            font-size: 16px;
            font-weight: 600;
            width: 334px;
            height: 48px;
            color: white;
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-signin:hover {
            background: #3366B3;
            color: white;
        }
        .form-check-input {
            width: 16px;
            height: 16px;
        }
        .form-check-label {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 0;
        }
        .forgot-link {
            color: #4A90E2;
            text-decoration: none;
            font-size: 14px;
        }
        .forgot-link:hover {
            color: #357ABD;
        }
        .register-text {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 20px;
            width: 100%;
        }
        .footer-text {
            text-align: center;
            margin-top: 48px;
            color: #9ca3af;
            font-size: 12px;
        }
        .register-text a {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
        }
        .register-text a:hover {
            color: #357ABD;
        }

    </style>
</head>
<body>
    <!-- Language Toggle - Top Right -->
    <div class="position-fixed top-0 {{ is_rtl() ? 'start-0' : 'end-0' }} p-3" style="z-index: 1050;">
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-globe {{ margin_end(1) }}"></i>
                <span>{{ is_rtl() ? 'العربية' : 'English' }}</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a></li>
                <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">🇸🇦 العربية</a></li>
            </ul>
        </div>
    </div>
    
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-12">
                <div class="auth-container">
                    <div class="auth-card">
                        <div class="form-content">
                            <!-- Logo -->
                            <div class="logo-container" style="margin-bottom: 50px; margin-top: 20px;">
                                <img src="{{ asset('website/images/icons/logo.svg') }}" alt="Logo" style="height: 80px;">
                            </div>

                            <!-- Error/Success Messages -->
                            <div id="errorMessage" class="alert alert-danger d-none mb-3"></div>
                            <div id="successMessage" class="alert alert-success d-none mb-3"></div>

                            <!-- Login Form -->
                            <form id="loginForm" novalidate>
                                <!-- Email Address -->
                                <div class="mb-3">
                                    <label class="form-label">{{ __('auth.email_address') }}</label>
                                    <div class="position-relative">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="{{ __('auth.enter_email') }}" required {{ is_rtl() ? 'dir="rtl"' : '' }}>
                                        <i class="fas fa-envelope input-icon"></i>
                                    </div>
                                    <div class="error-message" id="emailError"></div>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label class="form-label">{{ __('auth.password') }}</label>
                                    <div class="position-relative">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="{{ __('auth.enter_password') }}" required minlength="6" {{ is_rtl() ? 'dir="rtl"' : '' }}>
                                        <i class="fas fa-eye input-icon" style="cursor: pointer;" onclick="togglePassword(this)"></i>
                                    </div>
                                    <div class="error-message" id="passwordError"></div>
                                </div>

                                <!-- Remember Me & Forgot Password -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">{{ __('auth.remember_me') }}</label>
                                    </div>
                                    <a href="/forgot-password" class="forgot-link">{{ __('auth.forgot_password_question') }}</a>
                                </div>

                                <!-- Sign In Button -->
                                <button type="submit" class="btn btn-signin" id="loginBtn">
                                    <span id="loginSpinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                    {{ __('auth.sign_in') }}
                                </button>

                                <!-- Register Link -->
                                <div class="register-text">
                                    {{ __('auth.dont_have_account') }} <a href="/register">{{ __('auth.register_here') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="footer-text">
                        {{ __('auth.footer_text') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script>
        function togglePassword(icon) {
            const input = icon.previousElementSibling;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
        
        function showMessage(message, type = 'error') {
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');
            
            if (type === 'success') {
                successDiv.textContent = message;
                successDiv.classList.remove('d-none');
                errorDiv.classList.add('d-none');
            } else {
                errorDiv.textContent = message;
                errorDiv.classList.remove('d-none');
                successDiv.classList.add('d-none');
            }
        }
        
        function validateForm() {
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            
            let isValid = true;
            
            // Reset errors
            email.classList.remove('error');
            password.classList.remove('error');
            emailError.classList.remove('show');
            passwordError.classList.remove('show');
            
            // Email validation
            if (!email.value.trim()) {
                email.classList.add('error');
                emailError.textContent = '{{ __('validation.email_required') }}';
                emailError.classList.add('show');
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                email.classList.add('error');
                emailError.textContent = '{{ __('validation.email_invalid') }}';
                emailError.classList.add('show');
                isValid = false;
            }
            
            // Password validation
            if (!password.value.trim()) {
                password.classList.add('error');
                passwordError.textContent = '{{ __('validation.password_required') }}';
                passwordError.classList.add('show');
                isValid = false;
            } else if (password.value.length < 6) {
                password.classList.add('error');
                passwordError.textContent = '{{ __('validation.password_min') }}';
                passwordError.classList.add('show');
                isValid = false;
            }
            
            return isValid;
        }
        
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                return;
            }
            
            const loginBtn = document.getElementById('loginBtn');
            const spinner = document.getElementById('loginSpinner');
            
            loginBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            const formData = new FormData(this);
            const data = {
                email: formData.get('email'),
                password: formData.get('password'),
                device_type: 'W'
            };
            
            try {
                // Use your existing API system
                const response = await api.login(data);
                
                if (response.code === 200) {
                    // Store session data
                    console.log('Login successful, storing session data:', response.data);
                    sessionStorage.setItem('user', JSON.stringify(response.data));
                    sessionStorage.setItem('user_id', response.data.id);
                    sessionStorage.setItem('token', response.data.token);
                    console.log('Session data stored - user_id:', response.data.id, 'token:', response.data.token);
                    
                    // Set Laravel session via direct endpoint call
                    const sessionData = {
                        user_id: response.data.id,
                        token: response.data.token,
                        user: response.data
                    };
                    
                    const sessionResponse = await fetch('/auth/set-session', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                        },
                        body: JSON.stringify(sessionData)
                    });
                    
                    console.log('Session setup response:', sessionResponse.status);
                    
                    if (sessionResponse.ok) {
                        // Verify session data is still there
                        console.log('Final session check - user_id:', sessionStorage.getItem('user_id'), 'token:', sessionStorage.getItem('token'));
                        toastr.success(response.message);
                        window.location.href = '/dashboard';
                    } else {
                        toastr.error('Session setup failed');
                    }
                } else {
                    toastr.error(response.message);
                }
            } catch (error) {
                toastr.error(error.message || 'Connection error. Please try again.');
            } finally {
                loginBtn.disabled = false;
                spinner.classList.add('d-none');
            }
        });
    </script>
</body>
</html>