<!-- Team Members Modal -->
<div class="modal fade" id="teamMembersModal" tabindex="-1" aria-labelledby="teamMembersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #teamMembersModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #teamMembersModal .modal-header {
                        position: relative !important;
                    }

                    /* Validation error messages styling */
                    #teamMemberForm .invalid-feedback {
                        display: block;
                        color: #dc3545;
                        font-size: 0.875rem;
                        margin-top: 0.25rem;
                    }

                    #teamMemberForm .form-control.is-invalid {
                        border-color: #dc3545;
                        padding-right: calc(1.5em + 0.75rem);
                        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
                        background-repeat: no-repeat;
                        background-position: right calc(0.375em + 0.1875rem) center;
                        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                    }

                    /* Password toggle icon */
                    #togglePassword {
                        z-index: 10;
                        transition: color 0.2s ease;
                    }

                    #togglePassword:hover {
                        color: #495057;
                    }

                    /* RTL support for validation */
                    [dir="rtl"] #teamMemberForm .form-control.is-invalid {
                        padding-right: 0.75rem;
                        padding-left: calc(1.5em + 0.75rem);
                        background-position: left calc(0.375em + 0.1875rem) center;
                    }

                    [dir="rtl"] #teamMemberForm .invalid-feedback {
                        text-align: right;
                    }

                    /* RTL support for password toggle */
                    [dir="rtl"] #member_password {
                        padding-right: 0.75rem !important;
                        padding-left: 2.5rem !important;
                    }

                    [dir="rtl"] #togglePassword {
                        right: auto !important;
                        left: 0.75rem !important;
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="teamMembersModalLabel">
                            <i class="fas fa-users me-2"></i>{{ __('messages.my_team') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="teamMembersModalLabel">
                        <i class="fas fa-users me-2"></i>{{ __('messages.my_team') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <!-- Add Member Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ __('messages.team_members_list') }}</h6>
                    @can('team_management', 'create')
                        <button class="btn btn-sm orange_btn" onclick="showAddMemberForm()">
                            {{ __('messages.add_new_member') }}
                        </button>
                    @endcan
                </div>

                <!-- Add Member Form (Hidden by default) -->
                <div id="addMemberForm" class="card mb-3" style="display: none;">
                    <div class="card-body">
                        <h6 class="mb-3">{{ __('messages.add_new_member') }}</h6>
                        <form id="teamMemberForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_name') }} *</label>
                                    <input type="text" class="form-control Input_control" id="member_name">
                                    <div class="invalid-feedback" id="member_name_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_email') }} *</label>
                                    <input type="email" class="form-control Input_control" id="member_email">
                                    <div class="invalid-feedback" id="member_email_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.phone_number') }} *</label>
                                    <input type="text" class="form-control Input_control" id="member_phone" placeholder="+966 50 1234567" inputmode="numeric">
                                    <div class="invalid-feedback" id="member_phone_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_password') }} *</label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control Input_control" id="member_password"
                                            value="Default@123" disabled
                                            style="padding-right: 2.5rem;">
                                        <i class="fas fa-eye-slash position-absolute" id="togglePassword"
                                            style="right: 0.75rem; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6c757d;"></i>
                                    </div>
                                    <div class="invalid-feedback" id="member_password_error"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_role') }} *</label>
                                    <div class="custom-combo-dropdown position-relative">
                                        <input type="text" class="form-control Input_control" id="member_role"
                                            name="member_role" placeholder="{{ __('messages.select') }}"
                                            autocomplete="off" readonly style="cursor: pointer;">
                                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                                        <div class="dropdown-options" id="roleDropdown">
                                            <div class="text-center py-2">
                                                <div class="spinner-border spinner-border-sm"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invalid-feedback" id="member_role_error"></div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn orange_btn api-action-btn">
                                    {{ __('messages.add_member') }}
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="hideAddMemberForm()">
                                    {{ __('messages.cancel') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Members List -->
                <div id="teamMembersList">
                    <div class="text-center py-4">
                        <div class="spinner-border" role="status"></div>
                        <div class="mt-2">{{ __('messages.loading_team_members') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Format role name: convert underscores to spaces and capitalize each word
    function formatRoleName(role) {
        if (!role) return '';
        return role
            .replace(/_/g, ' ')
            .split(' ')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');
    }

    // Load roles from API
    async function loadUserRoles() {
        try {
            const response = await api.makeRequest('general/user_roles', {}, 'GET');

            const roleDropdown = document.getElementById('roleDropdown');
            if (response.code === 200 && response.data) {
                roleDropdown.innerHTML = response.data.map(role =>
                    `<div class="dropdown-option" data-value="${role.id}">${formatRoleName(role.id)}</div>`
                ).join('');

                // Attach click handlers
                roleDropdown.querySelectorAll('.dropdown-option').forEach(option => {
                    option.addEventListener('click', function() {
                        document.getElementById('member_role').value = this.getAttribute(
                            'data-value');
                        document.getElementById('member_role').classList.remove('is-invalid');
                        document.getElementById('member_role_error').textContent = '';
                        roleDropdown.classList.remove('show');
                    });
                });
            }
        } catch (error) {
            console.error('Failed to load roles:', error);
            document.getElementById('roleDropdown').innerHTML =
                '<div class="text-center py-2 text-danger">Failed to load roles</div>';
        }
    }

    // Custom Role Dropdown Handler
    const roleInput = document.getElementById('member_role');
    const roleDropdown = document.getElementById('roleDropdown');

    if (roleInput && roleDropdown) {
        roleInput.addEventListener('click', function() {
            roleDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!roleInput.contains(e.target) && !roleDropdown.contains(e.target)) {
                roleDropdown.classList.remove('show');
            }
        });
    }

    // Load roles when modal opens
    document.getElementById('teamMembersModal').addEventListener('shown.bs.modal', function() {
        loadUserRoles();
    }, {
        once: true
    });

    // Show/Hide Add Member Form
    function showAddMemberForm() {
        document.getElementById('addMemberForm').style.display = 'block';
        document.getElementById('member_name').focus();
    }

    function hideAddMemberForm() {
        document.getElementById('addMemberForm').style.display = 'none';
        document.getElementById('teamMemberForm').reset();
        document.querySelectorAll('#teamMemberForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('#teamMemberForm .invalid-feedback').forEach(el => el.textContent = '');
    }

    // Phone number formatting and validation
    const phoneInput = document.getElementById('member_phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            // Remove non-numeric characters except +
            let value = this.value.replace(/[^0-9+]/g, '');
            
            // Ensure + is only at the beginning
            if (value.includes('+')) {
                const parts = value.split('+');
                value = '+' + parts.join('');
            }
            
            this.value = value;
            this.classList.remove('is-invalid');
            document.getElementById('member_phone_error').textContent = '';
        });
    }

    // Clear validation errors on input
    ['member_name', 'member_email', 'member_password'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                document.getElementById(fieldId + '_error').textContent = '';
            });
        }
    });

    // Clear role validation error when role is selected
    document.getElementById('member_role').addEventListener('change', function() {
        this.classList.remove('is-invalid');
        document.getElementById('member_role_error').textContent = '';
    });

    // Password visibility toggle
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('member_password');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            // Toggle password visibility
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Toggle eye icon
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }


    // Load Team Members
    async function loadTeamMembers() {
        try {
            const response = await api.makeRequest('team_management/list_members', {
                user_id: {{ auth()->id() ?? 1 }}
            });

            const container = document.getElementById('teamMembersList');

            if (response.code === 200 && response.data) {
                const members = response.data.members || [];

                if (members.length === 0) {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('messages.no_members_found') }}</h5>
                            <p class="text-muted">{{ __('messages.add_your_first_team_member') }}</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = `
                    <div class="row g-3">
                        ${members.map(member => `
                            <div class="col-12">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="fas fa-user-circle fa-lg text-primary"></i>
                                                <h6 class="mb-0 fw-bold">${member.name}</h6>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="dropdown">
                                                    <i class="fas fa-share-alt"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li><a class="dropdown-item" href="https://wa.me/?text=${encodeURIComponent(`Team Member Credentials\n\nName: ${member.name}\nEmail: ${member.email}${member.phone ? `\nPhone: ${member.phone}` : ''}\nPassword: Default@123`)}"><i class="fab fa-whatsapp text-success me-2"></i>WhatsApp</a></li>
                                                    <li><a class="dropdown-item" href="mailto:?subject=Team Member Credentials&body=${encodeURIComponent(`Name: ${member.name}\nEmail: ${member.email}${member.phone ? `\nPhone: ${member.phone}` : ''}\nPassword: Default@123`)}"><i class="fas fa-envelope text-primary me-2"></i>Email</a></li>
                                                    ${member.phone ? `<li><a class="dropdown-item" href="sms:${member.phone}?body=${encodeURIComponent(`Team Member Credentials\n\nName: ${member.name}\nEmail: ${member.email}\nPassword: Default@123`)}"><i class="fas fa-sms text-info me-2"></i>SMS</a></li>` : ''}
                                                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); copyToClipboard('Team Member Credentials\\n\\nName: ${member.name}\\nEmail: ${member.email}${member.phone ? `\\nPhone: ${member.phone}` : ''}\\nPassword: Default@123');"><i class="fas fa-copy text-secondary me-2"></i>{{ __('messages.copy') }}</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 text-muted small">
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-envelope"></i>
                                                <span>${member.email}</span>
                                            </div>
                                            ${member.phone ? `
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-phone"></i>
                                                <span>${member.phone}</span>
                                            </div>
                                            ` : ''}
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-briefcase text-warning"></i>
                                                <span>${formatRoleName(member.role)}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-lock"></i>
                                                <span>••••••••</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('messages.failed_to_load_team_members') }}
                    </div>
                `;
            }
        } catch (error) {
            console.error('Failed to load team members:', error);
            document.getElementById('teamMembersList').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle me-2"></i>
                    {{ __('messages.error_loading_data') }}
                </div>
            `;
        }
    }

    // Get Role Display Name
    function getRoleDisplayName(role) {
        const roles = {
            'contractor': '{{ __('messages.contractor') }}',
            'consultant': '{{ __('messages.consultant') }}',
            'site_engineer': '{{ __('messages.site_engineer') }}',
            'project_manager': '{{ __('messages.project_manager') }}',
            'stakeholder': '{{ __('messages.stakeholder') }}'
        };
        return roles[role] || role;
    }

    // Add Team Member Form Submit
    document.getElementById('teamMemberForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        // Clear previous errors
        document.querySelectorAll('#teamMemberForm .is-invalid').forEach(el => el.classList.remove(
            'is-invalid'));
        document.querySelectorAll('#teamMemberForm .invalid-feedback').forEach(el => el.textContent = '');

        // Get form values
        const name = document.getElementById('member_name').value.trim();
        const email = document.getElementById('member_email').value.trim();
        const phone = document.getElementById('member_phone').value.trim();
        const password = 'Default@123';
        const role = document.getElementById('member_role').value;

        let isValid = true;

        // Validate name
        if (!name) {
            document.getElementById('member_name').classList.add('is-invalid');
            document.getElementById('member_name_error').textContent =
                '{{ __('messages.name_is_required') }}';
            isValid = false;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email) {
            document.getElementById('member_email').classList.add('is-invalid');
            document.getElementById('member_email_error').textContent =
                '{{ __('messages.email_is_required') }}';
            isValid = false;
        } else if (!emailRegex.test(email)) {
            document.getElementById('member_email').classList.add('is-invalid');
            document.getElementById('member_email_error').textContent =
                '{{ __('messages.valid_email_required') }}';
            isValid = false;
        }

        // Validate phone
        const phoneRegex = /^\+?[0-9]{7,15}$/;
        if (!phone) {
            document.getElementById('member_phone').classList.add('is-invalid');
            document.getElementById('member_phone_error').textContent =
                '{{ __('messages.phone_is_required') }}';
            isValid = false;
        } else if (!phoneRegex.test(phone)) {
            document.getElementById('member_phone').classList.add('is-invalid');
            document.getElementById('member_phone_error').textContent =
                '{{ __('messages.valid_phone_required') }}';
            isValid = false;
        }

        // Validate role
        if (!role) {
            document.getElementById('member_role').classList.add('is-invalid');
            document.getElementById('member_role_error').textContent =
                '{{ __('messages.role_is_required') }}';
            isValid = false;
        }

        if (!isValid) {
            return;
        }

        if (!isValid) {
            return;
        }

        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __('messages.adding') }}...';

        try {
            const response = await api.makeRequest('team_management/add_member', {
                user_id: {{ auth()->id() ?? 1 }},
                name: name,
                email: email,
                phone: phone,
                password: password,
                role: role
            });

            if (response.code === 200) {
                showToast(response.message || '{{ __('messages.member_added_successfully') }}', 'success');
                hideAddMemberForm();
                loadTeamMembers();
            } else {
                showToast(response.message || '{{ __('messages.failed_to_add_team_member') }}', 'error');

                // Show validation errors
                if (response.message && response.message.includes('email')) {
                    document.getElementById('member_email').classList.add('is-invalid');
                }
            }
        } catch (error) {
            console.error('Failed to add team member:', error);
            showToast('{{ __('messages.failed_to_add_team_member') }}', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '{{ __('messages.add_member') }}';
        }
    });

    // Load team members when modal opens
    document.getElementById('teamMembersModal').addEventListener('shown.bs.modal', function() {
        loadTeamMembers();
        hideAddMemberForm();
    });

    // Share credentials function
    window.shareCredentialsWithPassword = async function(name, email, phone, password) {
        const text = `Team Member Credentials\n\nName: ${name}\nEmail: ${email}${phone ? `\nPhone: ${phone}` : ''}\nPassword: ${password}`;
        const encodedText = encodeURIComponent(text);
        
        const dropdown = `
            <div class="dropdown d-inline-block">
                <button class="btn btn-sm btn-success dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-share-alt me-1"></i>{{ __('messages.share') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><a class="dropdown-item" href="https://wa.me/?text=${encodedText}" target="_blank"><i class="fab fa-whatsapp text-success me-2"></i>WhatsApp</a></li>
                    <li><a class="dropdown-item" href="mailto:?subject=Team Member Credentials&body=${encodedText}"><i class="fas fa-envelope text-primary me-2"></i>Email</a></li>
                    ${phone ? `<li><a class="dropdown-item" href="sms:${phone}?body=${encodedText}"><i class="fas fa-sms text-info me-2"></i>SMS</a></li>` : ''}
                    <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); copyToClipboard('${text.replace(/'/g, "\\'").replace(/\n/g, '\\n')}');"><i class="fas fa-copy text-secondary me-2"></i>{{ __('messages.copy') }}</a></li>
                </ul>
            </div>
        `;
        
        Swal.fire({
            title: '{{ __('messages.share_credentials') }}',
            html: dropdown,
            showConfirmButton: false,
            showCloseButton: true
        });
    };

    window.copyToClipboard = function(text) {
        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('{{ __('messages.credentials_copied') }}', 'success');
            }).catch(() => {
                fallbackCopy(text);
            });
        } else {
            fallbackCopy(text);
        }
    }

    function fallbackCopy(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showToast('{{ __('messages.credentials_copied') }}', 'success');
        } catch (err) {
            showToast('{{ __('messages.failed_to_copy') }}', 'error');
        }
        document.body.removeChild(textarea);
    }
</script>
