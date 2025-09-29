<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.my_profile') }}</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }
        .error-message.show {
            display: block;
        }
        .form-control.error {
            border-color: #ef4444;
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="content_wraper F_poppins">
        <!-- Header -->
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 d-flex align-items-center justify-content-between gap-2">
                        <a class="navbar-brand" href="{{ route('dashboard') }}">
                            <img src="{{ asset('website/images/icons/logo.svg') }}" alt="logo" class="img-fluid">
                            <span class="Head_title fw-bold {{ margin_start(3) }} fs24 d-none d-lg-inline-block">{{ __('auth.my_profile') }}</span>
                        </a>
                        <div class="d-flex align-items-center justify-content-end gap-md-4 gap-3 w-100 flex-wrap">
                            <!-- Language Toggle -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-globe {{ margin_end(2) }}"></i>
                                    <span>{{ is_rtl() ? 'العربية' : 'English' }}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">🇸🇦 العربية</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center gap-2 gap-md-3" type="button" data-bs-toggle="dropdown">
                                    <img src="{{ asset('website/images/icons/avtar.svg') }}" alt="user img" class="User_iMg">
                                    <span class="{{ is_rtl() ? 'text-start' : 'text-end' }}">
                                        <h6 class="fs14 fw-medium black_color">
                                            @if (isset($currentUser))
                                                {{ $currentUser->name }}
                                            @else
                                                {{ __('messages.john_smith') }}
                                            @endif
                                        </h6>
                                        <p class="Gray_color fs12 fw-normal">
                                            @if (isset($currentUser))
                                                {{ $currentUser->getRoleDisplayName() }}
                                            @else
                                                {{ __('messages.consultant') }}
                                            @endif
                                        </p>
                                    </span>
                                    <img src="{{ asset('website/images/icons/arrow-up.svg') }}" alt="arrow" class="arrow">
                                </a>
                                <ul class="dropdown-menu {{ is_rtl() ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('website.profile') }}">
                                        <i class="fas fa-user"></i> {{ __('auth.my_profile') }}
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="#" onclick="event.preventDefault(); logout();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('auth.logout') }}
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Profile Content -->
        <section class="Dashboard_sec">
            <div class="container-fluid">
                <!-- Profile Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                    <h5 class="fw-bold mb-0">{{ __('auth.my_profile') }}</h5>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left {{ margin_end(2) }}"></i>{{ __('auth.back_to_dashboard') }}
                    </a>
                </div>

                <!-- Profile Section -->
                <div class="row justify-content-center">
                    <div class="col-12 col-xl-10">
                        <!-- Profile Header Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="position-relative">
                                            <img src="{{ asset('website/images/profile image.png') }}" alt="Profile" class="rounded-3" style="width: 120px; height: 120px; object-fit: cover;">
                                            <div class="position-absolute bottom-0 {{ is_rtl() ? 'start-0' : 'end-0' }} bg-success rounded-circle border border-3 border-white" style="width: 25px; height: 25px;"></div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h2 class="fw-bold mb-1 text-dark" id="userName">{{ __('messages.loading') }}...</h2>
                                        <p class="text-muted mb-2 fs-5" id="userRole">{{ __('messages.loading') }}...</p>
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <div class="d-flex align-items-center text-muted">
                                                <i class="fas fa-building {{ margin_end(2) }}"></i>
                                                <span id="companyName">{{ __('messages.loading') }}...</span>
                                            </div>
                                            {{-- <div class="d-flex gap-2 flex-wrap">
                                                <button class="btn orange_btn btn-sm" onclick="toggleEditMode()">
                                                    <i class="fas fa-edit {{ margin_end(2) }}"></i>{{ __('auth.edit_profile') }}
                                                </button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteAccount()">
                                                    <i class="fas fa-trash {{ margin_end(2) }}"></i>{{ __('auth.delete_account') }}
                                                </button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Profile Details Cards -->
                        <div class="row g-4">
                            <!-- Contact Information -->
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-0 pb-0">
                                        <h5 class="fw-bold mb-0"><i class="fas fa-address-card text-primary {{ margin_end(2) }}"></i>{{ __('auth.contact_information') }}</h5>
                                    </div>
                                    <div class="card-body pt-3">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <i class="fas fa-phone text-success {{ margin_end(3) }} fs-4"></i>
                                                    <div>
                                                        <small class="text-muted d-block">{{ __('auth.mobile_number') }}</small>
                                                        <h6 class="mb-0 text-dark" id="userPhone">{{ __('messages.loading') }}...</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <i class="fas fa-envelope text-danger {{ margin_end(3) }} fs-4"></i>
                                                    <div>
                                                        <small class="text-muted d-block">{{ __('auth.email_address') }}</small>
                                                        <h6 class="mb-0 text-dark" id="userEmail">{{ __('messages.loading') }}...</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Company Statistics -->
                            <div class="col-12 col-lg-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-transparent border-0 pb-0">
                                        <h5 class="fw-bold mb-0"><i class="fas fa-chart-bar text-info {{ margin_end(2) }}"></i>{{ __('auth.company_statistics') }}</h5>
                                    </div>
                                    <div class="card-body pt-3">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <i class="fas fa-users text-primary {{ margin_end(3) }} fs-4"></i>
                                                    <div>
                                                        <small class="text-muted d-block">{{ __('auth.total_employees') }}</small>
                                                        <h6 class="mb-0 text-dark" id="employeeCount">0</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <i class="fas fa-user-friends text-warning {{ margin_end(3) }} fs-4"></i>
                                                    <div>
                                                        <small class="text-muted d-block">{{ __('auth.team_members') }}</small>
                                                        <h6 class="mb-0 text-dark" id="memberCount">0</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Edit Profile Modal -->
                <div class="modal fade" id="editProfileModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header {{ is_rtl() ? 'justify-content-end' : '' }}">
                                <h5 class="modal-title {{ is_rtl() ? 'order-2' : '' }}"><i class="fas fa-edit {{ margin_end(2) }}"></i>{{ __('auth.edit_profile') }}</h5>
                                <button type="button" class="btn-close {{ is_rtl() ? 'order-1' : '' }}" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label fw-medium">{{ __('auth.full_name') }}</label>
                                        <input type="text" class="form-control" id="editUserName" placeholder="{{ __('auth.enter_full_name') }}" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
                                        <div class="error-message" id="editNameError"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label fw-medium">{{ __('auth.company_name') }}</label>
                                        <input type="text" class="form-control" id="editCompanyName" placeholder="{{ __('auth.enter_company_name') }}" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
                                        <div class="error-message" id="editCompanyError"></div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium">{{ __('auth.mobile_number') }}</label>
                                        <input type="tel" class="form-control" id="editUserPhone" placeholder="{{ __('auth.enter_mobile') }}" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
                                        <div class="error-message" id="editPhoneError"></div>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <label class="form-label fw-medium">{{ __('auth.email_address') }}</label>
                                        <input type="email" class="form-control" id="editUserEmail" placeholder="{{ __('auth.enter_email') }}" style="{{ is_rtl() ? 'text-align: right;' : '' }}">
                                        <div class="error-message" id="editEmailError"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times {{ margin_end(2) }}"></i>{{ __('auth.cancel') }}
                                </button>
                                <button type="button" class="btn btn-success" onclick="saveProfile()">
                                    <i class="fas fa-save {{ margin_end(2) }}"></i>{{ __('auth.save_changes') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    
    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 {{ is_rtl() ? 'start-0' : 'end-0' }} p-3" id="toastContainer"></div>

    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>
    <script src="{{ asset('website/js/confirmation-modal.js') }}"></script>

    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script src="{{ asset('website/js/wow.js') }}"></script>
    <script src="{{ asset('website/js/custom.js') }}"></script>
    <script src="{{ asset('website/js/universal-auth.js') }}"></script>
    <script src="{{ asset('website/js/rtl-spacing-fix.js') }}"></script>
    <script>
        // Disable auth check on profile - Laravel middleware handles it
        window.DISABLE_JS_AUTH_CHECK = true;
    </script>
    
    <script>
        // Load user profile data
        document.addEventListener('DOMContentLoaded', async function() {
            // Check if we have valid session data (both storages)
            const userId = UniversalAuth.getUserId();
            const token = UniversalAuth.getToken();
            
            if (!userId || !token) {
                console.log('No session data, redirecting to login');
                window.location.href = '/login';
                return;
            }
            
            try {
                const response = await api.getProfile({});
                
                if (response.code === 200) {
                    const userData = response.data;
                    
                    // Update profile information
                    document.getElementById('userName').textContent = userData.name || 'N/A';
                    document.getElementById('userRole').textContent = userData.role || 'N/A';
                    document.getElementById('companyName').textContent = userData.company_name || 'N/A';
                    document.getElementById('userPhone').textContent = userData.phone || 'N/A';
                    document.getElementById('userEmail').textContent = userData.email || 'N/A';
                    document.getElementById('employeeCount').textContent = userData.total_employees || '0';
                    document.getElementById('memberCount').textContent = userData.total_members || '0';
                } else {
                    toastr.error(response.message || '{{ __('auth.profile_load_failed') }}');
                }
            } catch (error) {
                console.error('Profile load error:', error);
                toastr.error('{{ __('auth.profile_load_failed') }}');
            }
        });
        
        let isEditMode = false;
        
        function toggleEditMode() {
            // Populate edit fields with current values
            document.getElementById('editUserName').value = document.getElementById('userName').textContent;
            document.getElementById('editCompanyName').value = document.getElementById('companyName').textContent;
            document.getElementById('editUserPhone').value = document.getElementById('userPhone').textContent;
            document.getElementById('editUserEmail').value = document.getElementById('userEmail').textContent;
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
            modal.show();
        }
        
        function cancelEdit() {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
            if (modal) modal.hide();
        }
        
        // Validation functions
        function clearEditErrors() {
            document.querySelectorAll('.error-message').forEach(el => {
                el.classList.remove('show');
                el.textContent = '';
            });
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('error'));
        }
        
        function showEditError(fieldId, errorId, message) {
            const field = document.getElementById(fieldId);
            const errorEl = document.getElementById(errorId);
            if (field) field.classList.add('error');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.classList.add('show');
            }
        }
        
        function isValidName(name) {
            return /^[a-zA-Z\s\-\'\.\.]+$/.test(name) && !/\d/.test(name) && !/[<>"&\\]/.test(name);
        }
        
        function isValidProfessionalEmail(email) {
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email) || email.length > 255) return false;
            const domain = email.split('@')[1]?.toLowerCase();
            const testDomains = ['example.com', 'example.org', 'test.com', 'localhost', 'domain.com'];
            return !testDomains.includes(domain);
        }
        
        function isDisposableEmail(email) {
            const disposableDomains = [
                'tempmail.org', '10minutemail.com', 'guerrillamail.com', 'mailinator.com', 
                'temp-mail.org', 'throwaway.email', 'yopmail.com', 'maildrop.cc',
                'sharklasers.com', 'example.com', 'example.org', 'test.com'
            ];
            const domain = email.split('@')[1]?.toLowerCase();
            return disposableDomains.includes(domain);
        }
        
        function isValidInternationalPhone(phone) {
            const cleanPhone = phone.replace(/[\s\-\(\)]/g, '');
            const phoneRegex = /^[\+]?[1-9]\d{9,14}$/;
            return phoneRegex.test(cleanPhone) && !/[a-zA-Z]/.test(cleanPhone);
        }
        
        function isGenericCompanyName(companyName) {
            const genericNames = [
                'company', 'business', 'construction', 'contractor', 'consultant', 
                'corp', 'inc', 'ltd', 'llc', 'firm', 'enterprise', 'group',
                'organization', 'services', 'solutions', 'pvt', 'private'
            ];
            const nameLower = companyName.toLowerCase().trim();
            return genericNames.some(generic => 
                nameLower === generic || nameLower.includes(generic)
            );
        }
        
        function validateEditProfile() {
            clearEditErrors();
            let isValid = true;
            
            // Name validation
            const name = document.getElementById('editUserName').value.trim();
            if (!name) {
                showEditError('editUserName', 'editNameError', '{{ __('auth.full_name_required') }}');
                isValid = false;
            } else if (name.length < 2) {
                showEditError('editUserName', 'editNameError', '{{ __('auth.full_name_min') }}');
                isValid = false;
            } else if (name.length > 100) {
                showEditError('editUserName', 'editNameError', '{{ __('auth.full_name_max') }}');
                isValid = false;
            } else if (!isValidName(name)) {
                showEditError('editUserName', 'editNameError', '{{ __('auth.full_name_invalid') }}');
                isValid = false;
            }
            
            // Company name validation
            const companyName = document.getElementById('editCompanyName').value.trim();
            if (!companyName) {
                showEditError('editCompanyName', 'editCompanyError', '{{ __('auth.company_name_required') }}');
                isValid = false;
            } else if (companyName.length < 2) {
                showEditError('editCompanyName', 'editCompanyError', '{{ __('auth.company_name_min') }}');
                isValid = false;
            } else if (companyName.length > 200) {
                showEditError('editCompanyName', 'editCompanyError', '{{ __('auth.company_name_max') }}');
                isValid = false;
            } else if (!/^[a-zA-Z0-9\s\-\&\.\_\,\(\)]+$/.test(companyName)) {
                showEditError('editCompanyName', 'editCompanyError', '{{ __('auth.company_name_invalid') }}');
                isValid = false;
            }
            
            // Phone validation
            const phone = document.getElementById('editUserPhone').value.trim();
            if (!phone) {
                showEditError('editUserPhone', 'editPhoneError', '{{ __('auth.phone_required') }}');
                isValid = false;
            } else if (/\s/.test(phone)) {
                showEditError('editUserPhone', 'editPhoneError', '{{ __('auth.phone_spaces') }}');
                isValid = false;
            } else if (!isValidInternationalPhone(phone)) {
                showEditError('editUserPhone', 'editPhoneError', '{{ __('auth.phone_invalid') }}');
                isValid = false;
            }
            
            // Email validation
            const email = document.getElementById('editUserEmail').value.trim();
            if (!email) {
                showEditError('editUserEmail', 'editEmailError', '{{ __('auth.email_required') }}');
                isValid = false;
            } else if (!isValidProfessionalEmail(email)) {
                showEditError('editUserEmail', 'editEmailError', '{{ __('auth.email_invalid') }}');
                isValid = false;
            } else if (isDisposableEmail(email)) {
                showEditError('editUserEmail', 'editEmailError', '{{ __('auth.email_disposable') }}');
                isValid = false;
            }
            
            return isValid;
        }
        
        async function saveProfile() {
            if (!validateEditProfile()) {
                return;
            }
            
            try {
                const data = {
                    user_id: UniversalAuth.getUserId(),
                    name: document.getElementById('editUserName').value.trim(),
                    company_name: document.getElementById('editCompanyName').value.trim(),
                    phone: document.getElementById('editUserPhone').value.trim(),
                    email: document.getElementById('editUserEmail').value.trim()
                };
                
                const response = await api.updateProfile(data);
                
                if (response.code === 200) {
                    // Update display values
                    document.getElementById('userName').textContent = data.name;
                    document.getElementById('companyName').textContent = data.company_name;
                    document.getElementById('userPhone').textContent = data.phone;
                    document.getElementById('userEmail').textContent = data.email;
                    
                    toastr.success(response.message);
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                    if (modal) modal.hide();
                } else {
                    toastr.error(response.message);
                }
            } catch (error) {
                console.error('Profile update error:', error);
                toastr.error('{{ __('auth.profile_update_failed') }}');
            }
        }
        
        async function logout() {
            confirmationModal.show({
                title: '{{ __('auth.logout_confirmation') }}',
                message: '{{ __('auth.logout_confirmation_message') }}',
                icon: 'fas fa-sign-out-alt text-warning',
                confirmText: '{{ __('auth.logout') }}',
                cancelText: '{{ __('auth.cancel') }}',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        // Call API logout
                        await api.logout({});
                        
                        // Clear Laravel session first
                        await fetch('/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            }
                        });
                        
                        // Clear browser storage
                        UniversalAuth.logout();
                        
                        toastr.success('{{ __('auth.logged_out_successfully') }}');
                        
                        // Force redirect and prevent back navigation
                        setTimeout(() => {
                            window.location.replace('/login');
                            // Prevent back button
                            history.pushState(null, null, '/login');
                            window.addEventListener('popstate', function() {
                                history.pushState(null, null, '/login');
                            });
                        }, 500);
                        
                    } catch (error) {
                        console.error('Logout failed:', error);
                        UniversalAuth.logout();
                        window.location.replace('/login');
                    }
                }
            });
        }
        
        async function deleteAccount() {
            confirmationModal.show({
                title: '{{ __('auth.delete_account') }}',
                message: '{{ __('auth.confirm_delete_account') }}',
                icon: 'fas fa-trash text-danger',
                confirmText: '{{ __('auth.delete_account') }}',
                cancelText: '{{ __('auth.cancel') }}',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        const response = await api.deleteAccount({ user_id: UniversalAuth.getUserId() });
                        
                        if (response.code === 200) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                UniversalAuth.logout();
                            }, 2000);
                        } else {
                            toastr.error(response.message);
                        }
                    } catch (error) {
                        console.error('Delete account error:', error);
                        toastr.error('{{ __('auth.account_delete_failed') }}');
                    }
                }
            });
        }
    </script>
    
    @include('website.includes.permission-error-handler')
</body>
</html>