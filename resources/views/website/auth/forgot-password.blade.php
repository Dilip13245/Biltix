<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Forgot Password') }} - Biltix</title>
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
            width: 550px;
            height: 570px;
            margin: 0 auto;
        }
        .auth-card {
            background: white;
            border-radius: 16px;
            border: 1px solid #F3F4F6;
            box-shadow: 0px 10px 15px 0px #0000001A, 0px 4px 6px 0px #0000001A;
            width: 500px;
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
                max-width: 500px;
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
        .subtitle {
            color: #6b7280;
            font-size: 14px;
            text-align: center;
            margin-bottom: 32px;
            font-weight: 400;
        }
        .steps-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            width: 100%;
            margin: 0 0 40px 0;
            overflow: hidden;
        }
        .step-item {
            display: flex;
            align-items: center;
            gap: 4px;
            flex: 0 0 auto;
            min-width: fit-content;
        }
        .step {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 16px;
            flex-shrink: 0;
        }
        .step.active {
            background: #4A90E2;
            color: white;
        }
        .step.inactive {
            background: #e5e7eb;
            color: #9ca3af;
        }
        .step-line {
            flex: 1;
            height: 4px;
            border-radius: 4px;
            margin: 0 4px;
            min-width: 30px;
        }
        .step-line.active {
            background: #4A90E2;
        }
        .step-line.inactive {
            background: #e5e7eb;
        }
        .step-label {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 500;
            white-space: nowrap;
            min-width: fit-content;
        }
        .step-label.active {
            color: #4A90E2;
        }
        @media (max-width: 576px) {
            .step {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }
            .step-line {
                margin: 0 4px;
                min-width: 20px;
            }
            .step-label {
                font-size: 10px;
            }
            .step-item {
                gap: 4px;
            }
        }
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
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
            {{ is_rtl() ? 'padding: 15px 16px 15px 48px;' : 'padding: 15px 48px 15px 16px;' }}
            font-size: 16px;
            height: 50px;
            background: white;
            color: #111827;
            transition: all 0.2s ease;
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
        .otp-inputs {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 20px 0;
        }
        .otp-input {
            width: 50px;
            height: 50px;
            text-align: center;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
        }
        .otp-input:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }
        .btn-primary {
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
        .btn-primary:hover {
            background: #3366B3;
            color: white;
        }
        .resend-text {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 16px;
        }
        .resend-link {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
        }
        .resend-link:hover {
            color: #357ABD;
        }
        .back-text {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 20px;
            width: 100%;
        }
        .back-text a {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
        }
        .back-text a:hover {
            color: #357ABD;
        }
        .footer-text {
            text-align: center;
            margin-top: 48px;
            color: #9ca3af;
            font-size: 12px;
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
                            <div class="logo-container">
                                <img src="{{ asset('website/images/icons/logo.svg') }}" alt="Logo" style="height: 60px; margin-bottom: 16px;">
                                <div class="subtitle">{{ __('auth.reset_password') }}</div>
                            </div>

                            <!-- Steps -->
                            <div class="steps-container">
                                <div class="step-item">
                                    <div class="step active" id="step1">1</div>
                                    <div class="step-label active" id="step1-label">{{ __('auth.mobile') }}</div>
                                </div>
                                <div class="step-line inactive" id="line1"></div>
                                <div class="step-item">
                                    <div class="step inactive" id="step2">2</div>
                                    <div class="step-label" id="step2-label">{{ __('auth.otp') }}</div>
                                </div>
                                <div class="step-line inactive" id="line2"></div>
                                <div class="step-item">
                                    <div class="step inactive" id="step3">3</div>
                                    <div class="step-label" id="step3-label">{{ __('auth.new_password') }}</div>
                                </div>
                            </div>

                            <!-- Form -->
                            <form id="forgotPasswordForm">
                                <!-- Step 1: Mobile Number -->
                                <div class="form-step active" id="form-step-1">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.mobile_number') }}</label>
                                        <div class="position-relative">
                                            <input type="tel" class="form-control" id="mobileNumber" placeholder="{{ __('auth.enter_mobile') }}">
                                            <i class="fas fa-phone input-icon"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 2: OTP Verification -->
                                <div class="form-step" id="form-step-2">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.enter_otp') }}</label>
                                        <p class="text-muted small">{{ __('auth.otp_sent_to') }} <span id="displayMobile"></span></p>
                                        <div class="otp-inputs">
                                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 1)">
                                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 2)">
                                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 3)">
                                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 4)">
                                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 5)">
                                            <input type="text" class="otp-input" maxlength="1" oninput="moveToNext(this, 6)">
                                        </div>
                                        <div class="resend-text">
                                            {{ __('auth.didnt_receive_code') }} <a href="#" class="resend-link" onclick="resendOTP()">{{ __('auth.resend_otp') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: New Password -->
                                <div class="form-step" id="form-step-3">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.new_password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="newPassword" placeholder="{{ __('auth.enter_new_password') }}">
                                            <i class="fas fa-eye input-icon" style="cursor: pointer;" onclick="togglePassword('newPassword', this)"></i>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.confirm_password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="confirmPassword" placeholder="{{ __('auth.confirm_new_password') }}">
                                            <i class="fas fa-eye input-icon" style="cursor: pointer;" onclick="togglePassword('confirmPassword', this)"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <button type="button" class="btn btn-primary" id="actionBtn" onclick="nextStep()">{{ __('auth.send_otp') }}</button>

                                <!-- Back to Login Link -->
                                <div class="back-text">
                                    {{ __('auth.remember_password') }} <a href="/login">{{ __('auth.back_to_login') }}</a>
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
        let currentStep = 1;
        const totalSteps = 3;

        function nextStep() {
            if (currentStep === 1) {
                const mobile = document.getElementById('mobileNumber').value;
                if (!mobile) {
                    alert('Please enter your mobile number');
                    return;
                }
                document.getElementById('displayMobile').textContent = mobile;
                // Here you would send OTP to mobile
                console.log('Sending OTP to:', mobile);
            } else if (currentStep === 2) {
                const otp = getOTPValue();
                if (otp.length !== 6) {
                    alert('Please enter complete OTP');
                    return;
                }
                // Here you would verify OTP
                console.log('Verifying OTP:', otp);
            } else if (currentStep === 3) {
                const newPass = document.getElementById('newPassword').value;
                const confirmPass = document.getElementById('confirmPassword').value;
                if (!newPass || !confirmPass) {
                    alert('Please fill both password fields');
                    return;
                }
                if (newPass !== confirmPass) {
                    alert('Passwords do not match');
                    return;
                }
                // Here you would update password
                console.log('Updating password');
                alert('Password updated successfully!');
                window.location.href = '/login';
                return;
            }

            if (currentStep < totalSteps) {
                // Hide current step
                document.getElementById(`form-step-${currentStep}`).classList.remove('active');
                document.getElementById(`form-step-${currentStep}`).classList.add('d-none');
                
                // Update step indicators
                document.getElementById(`step${currentStep + 1}`).classList.add('active');
                document.getElementById(`step${currentStep + 1}`).classList.remove('inactive');
                document.getElementById(`step${currentStep + 1}-label`).classList.add('active');
                document.getElementById(`line${currentStep}`).classList.add('active');
                document.getElementById(`line${currentStep}`).classList.remove('inactive');
                
                // Show next step
                currentStep++;
                document.getElementById(`form-step-${currentStep}`).classList.add('active');
                document.getElementById(`form-step-${currentStep}`).classList.remove('d-none');
                
                // Update button text
                const actionBtn = document.getElementById('actionBtn');
                if (currentStep === 2) {
                    actionBtn.textContent = '{{ __('auth.verify_otp') }}';
                } else if (currentStep === 3) {
                    actionBtn.textContent = '{{ __('auth.reset_password_btn') }}';
                }
            }
        }

        function moveToNext(current, position) {
            if (current.value.length === 1 && position < 6) {
                const nextInput = current.parentNode.children[position];
                nextInput.focus();
            }
        }

        function getOTPValue() {
            const otpInputs = document.querySelectorAll('.otp-input');
            let otp = '';
            otpInputs.forEach(input => otp += input.value);
            return otp;
        }

        function resendOTP() {
            const mobile = document.getElementById('mobileNumber').value;
            console.log('Resending OTP to:', mobile);
            alert('OTP sent successfully!');
        }

        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
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