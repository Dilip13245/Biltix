<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Login') }} - Biltix</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            {{ is_rtl() ? 'text-align: right;' : '' }}
        }
        .form-control:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
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

                            <!-- Login Form -->
                            <form action="/dashboard" method="GET">
                                <!-- Email Address -->
                                <div class="mb-3">
                                    <label class="form-label">{{ __('auth.email_address') }}</label>
                                    <div class="position-relative">
                                        <input type="email" class="form-control" placeholder="{{ __('auth.enter_email') }}">
                                        <i class="fas fa-envelope input-icon"></i>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label class="form-label">{{ __('auth.password') }}</label>
                                    <div class="position-relative">
                                        <input type="password" class="form-control" placeholder="{{ __('auth.enter_password') }}">
                                        <i class="fas fa-eye input-icon" style="cursor: pointer;" onclick="togglePassword(this)"></i>
                                    </div>
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
                                <button type="submit" class="btn btn-signin">{{ __('auth.sign_in') }}</button>

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
    </script>
</body>
</html>