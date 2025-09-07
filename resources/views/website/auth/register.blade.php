<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('auth.register') }} - Biltix</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/toastr-custom.css') }}">
    <style>
        body {
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            padding: 32px;
            width: 100%;
            max-width: 672px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .auth-card {
                padding: 24px;
                margin: 0 auto;
                max-width: 100%;
            }
        }

        @media (max-width: 576px) {
            .auth-card {
                padding: 20px;
                margin: 0;
            }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 32px;
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
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 8px;
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
            margin: 0 16px;
        }

        .step-line.active {
            background: #4A90E2;
        }

        .step-line.inactive {
            background: #e5e7eb;
        }

        .step-label {
            font-size: 12px;
            color: #9ca3af;
            font-weight: 500;
            white-space: nowrap;
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
                width: 40px;
                margin: 0 8px;
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
            display: block;
        }

        .form-control,
        .form-select {
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 15px 16px;
            font-size: 16px;
            height: 50px;
            background: white;
            color: #111827;
            transition: all 0.2s ease;
            {{ is_rtl() ? 'text-align: right; direction: rtl;' : '' }}
        }

        .input-icon {
            position: absolute;
            {{ is_rtl() ? 'left: 16px;' : 'right: 16px;' }} top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            z-index: 10;
            font-size: 16px;
        }
        
        .input-icon.clickable {
            pointer-events: auto;
            cursor: pointer;
        }
        
        .input-icon.clickable:hover {
            color: #4A90E2;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
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

        .row .col-md-6 {
            padding-left: 7.5px;
            padding-right: 7.5px;
        }

        .button-container {
            width: 608px;
            height: 73px;
            margin: 0 auto;
            border-top: 1px solid #E5E7EB;
            padding-top: 25px;
            display: flex;
            align-items: flex-start;
        }

        .btn-next {
            background: #4A90E2;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            height: 48px;
            transition: all 0.2s ease;
        }

        .btn-next:hover {
            background: #357ABD;
            color: white;
        }

        .login-container {
            width: 608px;
            height: 24px;
            margin: 24px auto 32px auto;
        }

        .login-text {
            color: #6b7280;
            font-size: 14px;
            width: 265px;
            height: 24px;
            margin-left: 170.59px;
        }

        @media (max-width: 768px) {
            .button-container {
                width: calc(100% - 64px);
                margin: 0 32px;
            }

            .login-container {
                width: calc(100% - 64px);
                margin: 24px 32px 32px 32px;
            }

            .login-text {
                margin: 0 auto;
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 576px) {
            .button-container {
                width: 100%;
                margin: 0;
                padding: 25px 0 0 0;
            }

            .login-container {
                width: 100%;
                margin: 24px 0 32px 0;
            }
        }

        .login-text a {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
        }

        .login-text a:hover {
            color: #357ABD;
        }

        .add-more-btn {
            background: transparent;
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 16px;
            color: #9ca3af;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            margin-top: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .add-more-btn:hover {
            border-color: #4A90E2;
            color: #4A90E2;
        }

        .add-more-btn i {
            margin-left: 8px;
            font-size: 16px;
        }

        .member-row {
            margin-bottom: 15px;
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
            <div class="col-12 col-sm-11 col-md-10 col-lg-8 col-xl-6">
                <div class="auth-card">
                    <!-- Logo -->
                    <div class="logo-container">
                        <img src="{{ asset('website/images/icons/logo.svg') }}" alt="Logo"
                            style="height: 60px; margin-bottom: 16px;">
                        <div class="subtitle">{{ __('auth.join_platform') }}</div>
                    </div>

                    <!-- Steps -->
                    <div class="steps-container">
                        <div class="step-item">
                            <div class="step active" id="step1">1</div>
                            <div class="step-label active" id="step1-label">{{ __('auth.user_details') }}</div>
                        </div>
                        <div class="step-line inactive" id="line1"></div>
                        <div class="step-item">
                            <div class="step inactive" id="step2">2</div>
                            <div class="step-label" id="step2-label">{{ __('auth.company') }}</div>
                        </div>
                        <div class="step-line inactive" id="line2"></div>
                        <div class="step-item">
                            <div class="step inactive" id="step3">3</div>
                            <div class="step-label" id="step3-label">{{ __('auth.team') }}</div>
                        </div>
                    </div>

                    <!-- Registration Form -->
                    <form id="registrationForm" action="/dashboard" method="GET">
                        <!-- Step 1: User Details -->
                        <div class="form-step active" id="form-step-1">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.full_name') }}</label>
                                        <input type="text" class="form-control" name="full_name" id="full_name"
                                            placeholder="{{ __('auth.enter_full_name') }}" required minlength="2">
                                    </div>
                                    <div class="error-message" id="fullNameError"></div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.email_address') }}</label>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="{{ __('auth.enter_email') }}" required>
                                    </div>
                                    <div class="error-message" id="emailError"></div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.phone_number') }}</label>
                                        <input type="tel" class="form-control" name="phone" id="phone"
                                            placeholder="{{ __('auth.enter_phone') }}" required>
                                    </div>
                                    <div class="error-message" id="phoneError"></div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="{{ __('auth.create_password') }}" required minlength="6">
                                            <i class="fas fa-eye input-icon clickable" onclick="togglePassword('password', this)"></i>
                                        </div>
                                    </div>
                                    <div class="error-message" id="passwordError"></div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.confirm_password') }}</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                                                placeholder="{{ __('auth.confirm_password_placeholder') }}" required>
                                            <i class="fas fa-eye input-icon clickable" onclick="togglePassword('confirm_password', this)"></i>
                                        </div>
                                    </div>
                                    <div class="error-message" id="confirmPasswordError"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Company Details -->
                        <div class="form-step d-none" id="form-step-2">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.company_name') }}</label>
                                        <input type="text" class="form-control" name="company_name" id="company_name"
                                            placeholder="{{ __('auth.enter_company_name') }}" required>
                                    </div>
                                    <div class="error-message" id="companyNameError"></div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.total_employees') }}</label>
                                        <input type="number" class="form-control" name="employee_count" id="employee_count"
                                            placeholder="{{ __('auth.enter_employee_count') }}" required min="1">
                                    </div>
                                    <div class="error-message" id="employeeCountError"></div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('auth.designation') }}</label>
                                        <div class="position-relative">
                                            <select class="form-select" name="designation" id="designation" required
                                                style="{{ is_rtl() ? 'padding-left: 48px; padding-right: 16px;' : 'padding-right: 48px; padding-left: 16px;' }}">
                                                <option value="">{{ __('auth.select_designation') }}</option>
                                                <option value="contractor">{{ __('auth.contractor') }}</option>
                                                <option value="consultant">{{ __('auth.consultant') }}</option>
                                                {{-- <option value="site_engineer">{{ __('auth.site_engineer') }}</option>
                                                <option value="project_manager">{{ __('auth.project_manager') }}
                                                </option>
                                                <option value="stakeholder">{{ __('auth.stakeholder') }}</option> --}}
                                            </select>
                                            <i class="fas fa-chevron-down input-icon"></i>
                                        </div>
                                        <div class="error-message" id="designationError"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Team Members -->
                        <div class="form-step d-none" id="form-step-3">
                            <div id="membersContainer">
                                <div class="member-row">
                                    <div class="row g-3">
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('auth.member_name') }}</label>
                                                <input type="text" class="form-control" name="members[0][name]"
                                                    placeholder="{{ __('auth.enter_member_name') }}">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('auth.phone_number') }}</label>
                                                <input type="tel" class="form-control" name="members[0][phone]"
                                                    placeholder="{{ __('auth.enter_member_phone') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="button" class="add-more-btn" onclick="addMemberRow()">
                                    {{ __('auth.add_more_members') }}
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Button Container -->
                        <div class="button-container">
                            <button type="button" class="btn btn-next" id="nextBtn"
                                onclick="nextStep()">{{ __('auth.next') }}</button>
                        </div>

                        <!-- Login Link Container -->
                        <div class="login-container">
                            <div class="login-text">
                                {{ __('auth.already_have_account') }} <a
                                    href="/login">{{ __('auth.login_here') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>
    </script>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script>
        let currentStep = 1;
        let memberCount = 1;
        
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

        function validateStep(step) {
            let isValid = true;

            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control, .form-select').forEach(el => el.classList.remove('error'));

            if (step === 1) {
                const fullName = document.getElementById('full_name');
                const email = document.getElementById('email');
                const phone = document.getElementById('phone');
                const password = document.getElementById('password');
                const confirmPassword = document.getElementById('confirm_password');

                // Full name validation
                if (!fullName.value.trim()) {
                    showError(fullName, 'fullNameError', '{{ __('validation.full_name_required') }}');
                    isValid = false;
                } else if (fullName.value.trim().length < 2) {
                    showError(fullName, 'fullNameError', '{{ __('validation.full_name_min') }}');
                    isValid = false;
                }

                // Email validation
                if (!email.value.trim()) {
                    showError(email, 'emailError', '{{ __('validation.email_required') }}');
                    isValid = false;
                } else if (!isValidEmail(email.value)) {
                    showError(email, 'emailError', '{{ __('validation.email_invalid') }}');
                    isValid = false;
                }

                // Phone validation
                if (!phone.value.trim()) {
                    showError(phone, 'phoneError', '{{ __('validation.phone_required') }}');
                    isValid = false;
                } else if (!isValidPhone(phone.value)) {
                    showError(phone, 'phoneError', '{{ __('validation.phone_invalid') }}');
                    isValid = false;
                }

                // Password validation
                if (!password.value.trim()) {
                    showError(password, 'passwordError', '{{ __('validation.password_required') }}');
                    isValid = false;
                } else if (password.value.length < 6) {
                    showError(password, 'passwordError', '{{ __('validation.password_min') }}');
                    isValid = false;
                }

                // Confirm password validation
                if (!confirmPassword.value.trim()) {
                    showError(confirmPassword, 'confirmPasswordError', '{{ __('validation.password_confirm_required') }}');
                    isValid = false;
                } else if (password.value !== confirmPassword.value) {
                    showError(confirmPassword, 'confirmPasswordError', '{{ __('validation.password_mismatch') }}');
                    isValid = false;
                }

            } else if (step === 2) {
                const companyName = document.getElementById('company_name');
                const employeeCount = document.getElementById('employee_count');
                const designation = document.getElementById('designation');

                // Company name validation
                if (!companyName.value.trim()) {
                    showError(companyName, 'companyNameError', '{{ __('validation.company_name_required') }}');
                    isValid = false;
                }

                // Employee count validation
                if (!employeeCount.value.trim()) {
                    showError(employeeCount, 'employeeCountError', '{{ __('validation.employee_count_required') }}');
                    isValid = false;
                } else if (parseInt(employeeCount.value) < 1) {
                    showError(employeeCount, 'employeeCountError', '{{ __('validation.employee_count_min') }}');
                    isValid = false;
                }

                // Designation validation
                if (!designation.value) {
                    showError(designation, 'designationError', '{{ __('validation.designation_required') }}');
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

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function isValidPhone(phone) {
            const phoneRegex = /^[+]?[0-9]{10,15}$/;
            return phoneRegex.test(phone.replace(/\s+/g, ''));
        }

        function nextStep() {
            if (!validateStep(currentStep)) {
                return;
            }

            if (currentStep < 3) {
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
                if (currentStep === 3) {
                    document.getElementById('nextBtn').textContent = '{{ __('auth.register') }}';
                    document.getElementById('nextBtn').onclick = submitForm;
                }
            }
        }

        function addMemberRow() {
            const container = document.getElementById('membersContainer');
            const newRow = document.createElement('div');
            newRow.className = 'member-row';
            newRow.innerHTML = `
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.member_name') }}</label>
                            <input type="text" class="form-control" name="members[${memberCount}][name]" placeholder="{{ __('auth.enter_member_name') }}">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.phone_number') }}</label>
                            <input type="tel" class="form-control" name="members[${memberCount}][phone]" placeholder="{{ __('auth.enter_member_phone') }}">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(newRow);
            memberCount++;
        }

        async function submitForm() {
            const form = document.getElementById('registrationForm');
            const formData = new FormData(form);

            // Prepare signup data
            const data = {
                name: formData.get('full_name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                password: formData.get('password'),
                role: formData.get('designation') || 'contractor',
                company_name: formData.get('company_name'),
                designation: formData.get('designation'),
                employee_count: formData.get('employee_count'),
                device_type: 'W'
            };

            // Collect members
            const members = [];
            for (let i = 0; i < memberCount; i++) {
                const memberName = formData.get(`members[${i}][name]`);
                const memberPhone = formData.get(`members[${i}][phone]`);
                if (memberName && memberPhone) {
                    members.push({
                        member_name: memberName,
                        member_phone: memberPhone
                    });
                }
            }
            if (members.length > 0) {
                data.members = members;
            }

            try {
                const response = await api.signup(data);

                if (response.code === 200) {
                    // Store session data
                    sessionStorage.setItem('user', JSON.stringify(response.data));
                    sessionStorage.setItem('user_id', response.data.id);
                    sessionStorage.setItem('token', response.data.token);

                    // Set Laravel session
                    const sessionData = {
                        user_id: response.data.id,
                        token: response.data.token,
                        user: response.data
                    };

                    const sessionResponse = await fetch('/auth/set-session', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute(
                                'content')
                        },
                        body: JSON.stringify(sessionData)
                    });

                    if (sessionResponse.ok) {
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
            }
        }
    </script>
</body>

</html>
