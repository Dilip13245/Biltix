<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Forgot Password') }} - Biltix</title>
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
            {{ is_rtl() ? 'padding: 15px 16px 15px 48px; text-align: right; direction: rtl;' : 'padding: 15px 48px 15px 16px;' }}
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
        
        .form-control.error {
            border-color: #ef4444;
        }
        
        .otp-input.error {
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
        .resend-link.disabled {
            color: #9ca3af;
            cursor: not-allowed;
            pointer-events: none;
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
                <span>{{ is_rtl() ? 'العربية' : 'English' }}</span>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">English</a></li>
                <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
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
                                            <input type="tel" class="form-control" id="mobileNumber" placeholder="{{ __('auth.enter_mobile') }}" required>
                                            <i class="fas fa-phone input-icon"></i>
                                        </div>
                                        <div class="error-message" id="mobileError"></div>
                                    </div>
                                </div>

                                <!-- Step 2: OTP Verification -->
                                <div class="form-step" id="form-step-2">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.enter_otp') }}</label>
                                        <p class="text-muted small">{{ __('auth.otp_sent_to') }} <span id="displayMobile"></span></p>
                                        <div class="otp-inputs">
                                            <input type="text" class="otp-input" maxlength="1" data-index="0" pattern="[0-9]">
                                            <input type="text" class="otp-input" maxlength="1" data-index="1" pattern="[0-9]">
                                            <input type="text" class="otp-input" maxlength="1" data-index="2" pattern="[0-9]">
                                            <input type="text" class="otp-input" maxlength="1" data-index="3" pattern="[0-9]">
                                            <input type="text" class="otp-input" maxlength="1" data-index="4" pattern="[0-9]">
                                            <input type="text" class="otp-input" maxlength="1" data-index="5" pattern="[0-9]">
                                        </div>
                                        <div class="error-message text-center" id="otpError"></div>
                                        <div class="resend-text">
                                            {{ __('auth.didnt_receive_code') }} <a href="#" class="resend-link" id="resendLink" onclick="resendOTP()">{{ __('auth.resend_otp') }}</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Step 3: New Password -->
                                <div class="form-step" id="form-step-3">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.new_password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="newPassword" placeholder="{{ __('auth.enter_new_password') }}" required minlength="6">
                                            <i class="fas fa-eye input-icon" style="cursor: pointer;" onclick="togglePassword('newPassword', this)"></i>
                                        </div>
                                        <div class="error-message" id="newPasswordError"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.confirm_password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" id="confirmPassword" placeholder="{{ __('auth.confirm_new_password') }}" required>
                                            <i class="fas fa-eye input-icon" style="cursor: pointer;" onclick="togglePassword('confirmPassword', this)"></i>
                                        </div>
                                        <div class="error-message" id="confirmPasswordError"></div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script></script>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script>
        let currentStep = 1;
        const totalSteps = 3;
        let userPhone = '';
        let resendTimer = null;
        let resendCountdown = 0;

        function validateStep(step) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control, .otp-input').forEach(el => el.classList.remove('error'));
            
            if (step === 1) {
                const mobile = document.getElementById('mobileNumber');
                
                const phoneValue = mobile.value.trim();
                if (!phoneValue) {
                    showError(mobile, 'mobileError', '{{ __('auth.mobile_required') }}');
                    isValid = false;
                } else if (/\s/.test(phoneValue)) {
                    showError(mobile, 'mobileError', '{{ __('auth.mobile_spaces') }}');
                    isValid = false;
                } else if (!isValidPhone(phoneValue)) {
                    showError(mobile, 'mobileError', '{{ __('auth.mobile_invalid') }}');
                    isValid = false;
                }
                
            } else if (step === 2) {
                const otp = getOTPValue();
                const otpInputs = document.querySelectorAll('.otp-input');
                
                if (!otp.trim()) {
                    otpInputs.forEach(input => input.classList.add('error'));
                    showErrorMessage('otpError', '{{ __('auth.otp_required') }}');
                    isValid = false;
                } else if (otp.length !== 6) {
                    otpInputs.forEach(input => input.classList.add('error'));
                    showErrorMessage('otpError', '{{ __('auth.otp_incomplete') }}');
                    isValid = false;
                } else if (!/^\d{6}$/.test(otp)) {
                    otpInputs.forEach(input => input.classList.add('error'));
                    showErrorMessage('otpError', '{{ __('auth.otp_invalid') }}');
                    isValid = false;
                }
                
            } else if (step === 3) {
                const newPassword = document.getElementById('newPassword');
                const confirmPassword = document.getElementById('confirmPassword');
                
                if (!newPassword.value.trim()) {
                    showError(newPassword, 'newPasswordError', '{{ __('auth.new_password_required') }}');
                    isValid = false;
                } else if (newPassword.value.length < 8) {
                    showError(newPassword, 'newPasswordError', '{{ __('auth.new_password_min') }}');
                    isValid = false;
                } else if (newPassword.value.length > 128) {
                    showError(newPassword, 'newPasswordError', '{{ __('auth.new_password_max') }}');
                    isValid = false;
                } else if (!isStrongPassword(newPassword.value)) {
                    showError(newPassword, 'newPasswordError', '{{ __('auth.new_password_strong') }}');
                    isValid = false;
                }
                
                if (!confirmPassword.value.trim()) {
                    showError(confirmPassword, 'confirmPasswordError', '{{ __('auth.confirm_new_password_required') }}');
                    isValid = false;
                } else if (newPassword.value !== confirmPassword.value) {
                    showError(confirmPassword, 'confirmPasswordError', '{{ __('auth.new_passwords_mismatch') }}');
                    isValid = false;
                }
            }
            
            return isValid;
        }
        
        function showError(fieldElement, errorElementId, message) {
            fieldElement.classList.add('error');
            const errorElement = document.getElementById(errorElementId);
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
        
        function showErrorMessage(errorElementId, message) {
            const errorElement = document.getElementById(errorElementId);
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
        
        function isValidPhone(phone) {
            const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
            const phoneRegex = /^[\+]?[1-9]\d{9,14}$/;
            return phoneRegex.test(cleanPhone) && !/[a-zA-Z]/.test(cleanPhone);
        }
        
        function isStrongPassword(password) {
            const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/;
            return strongRegex.test(password);
        }
        
        async function nextStep() {
            if (!validateStep(currentStep)) {
                return;
            }
            
            if (currentStep === 1) {
                const mobile = document.getElementById('mobileNumber').value;
                
                try {
                    const response = await api.sendOtp({
                        phone: mobile,
                        type: 'forgot'
                    });
                    
                    if (response.code === 200) {
                        userPhone = mobile;
                        document.getElementById('displayMobile').textContent = mobile;
                        startResendTimer();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                        return;
                    }
                } catch (error) {
                    toastr.error(error.message || 'Connection error. Please try again.');
                    return;
                }
                
            } else if (currentStep === 2) {
                const otp = getOTPValue();
                
                try {
                    const response = await api.verifyOtp({
                        phone: userPhone,
                        otp: otp,
                        type: 'forgot'
                    });
                    
                    if (response.code === 200) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                        return;
                    }
                } catch (error) {
                    toastr.error(error.message || 'Connection error. Please try again.');
                    return;
                }
                
            } else if (currentStep === 3) {
                const newPass = document.getElementById('newPassword').value;
                const confirmPass = document.getElementById('confirmPassword').value;
                
                try {
                    const response = await api.resetPassword({
                        phone: userPhone,
                        new_password: newPass,
                        confirm_password: confirmPass
                    });
                    
                    if (response.code === 200) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 1000);
                        return;
                    } else {
                        toastr.error(response.message);
                        return;
                    }
                } catch (error) {
                    toastr.error(error.message || 'Connection error. Please try again.');
                    return;
                }
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

        // Industry-level OTP input handling
        document.addEventListener('DOMContentLoaded', function() {
            const otpInputs = document.querySelectorAll('.otp-input');
            
            otpInputs.forEach((input, index) => {
                // Handle input event
                input.addEventListener('input', function(e) {
                    const value = e.target.value;
                    
                    // Only allow numbers
                    if (!/^[0-9]$/.test(value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    // Move to next input if current is filled
                    if (value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });
                
                // Handle keydown for backspace
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace') {
                        if (!e.target.value && index > 0) {
                            otpInputs[index - 1].focus();
                            otpInputs[index - 1].value = '';
                        }
                    }
                });
                
                // Handle paste event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedData = e.clipboardData.getData('text');
                    const digits = pastedData.replace(/\D/g, '').slice(0, 6);
                    
                    if (digits.length > 0) {
                        // Clear all inputs first
                        otpInputs.forEach(inp => inp.value = '');
                        
                        // Fill inputs with pasted digits
                        for (let i = 0; i < digits.length && i < otpInputs.length; i++) {
                            otpInputs[i].value = digits[i];
                        }
                        
                        // Focus on next empty input or last filled input
                        const nextEmptyIndex = digits.length < otpInputs.length ? digits.length : otpInputs.length - 1;
                        otpInputs[nextEmptyIndex].focus();
                    }
                });
                
                // Handle focus event
                input.addEventListener('focus', function(e) {
                    e.target.select();
                });
                
                // Handle click event
                input.addEventListener('click', function(e) {
                    e.target.focus();
                    e.target.select();
                });
            });
        });

        function getOTPValue() {
            const otpInputs = document.querySelectorAll('.otp-input');
            let otp = '';
            otpInputs.forEach(input => otp += input.value);
            return otp;
        }

        function startResendTimer() {
            resendCountdown = 30;
            const resendLink = document.getElementById('resendLink');
            resendLink.classList.add('disabled');
            
            resendTimer = setInterval(() => {
                resendLink.textContent = `{{ __('auth.resend_otp') }} (${resendCountdown}s)`;
                resendCountdown--;
                
                if (resendCountdown < 0) {
                    clearInterval(resendTimer);
                    resendLink.textContent = '{{ __('auth.resend_otp') }}';
                    resendLink.classList.remove('disabled');
                }
            }, 1000);
        }
        
        async function resendOTP() {
            if (resendCountdown > 0) return;
            
            if (!userPhone) {
                toastr.warning('{{ __('auth.enter_mobile') }}');
                return;
            }
            
            try {
                const response = await api.sendOtp({
                    phone: userPhone,
                    type: 'forgot'
                });
                
                if (response.code === 200) {
                    startResendTimer();
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            } catch (error) {
                toastr.error(error.message || 'Connection error. Please try again.');
            }
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