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
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="teamMembersModalLabel">
                            <i class="fas fa-users me-2"></i>{{ __('messages.my_team') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="teamMembersModalLabel">
                        <i class="fas fa-users me-2"></i>{{ __('messages.my_team') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <!-- Add Member Button -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">{{ __('messages.team_members_list') }}</h6>
                    <button class="btn btn-sm orange_btn" onclick="showAddMemberForm()">
                        {{ __('messages.add_new_member') }}
                    </button>
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
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_email') }} *</label>
                                    <input type="email" class="form-control Input_control" id="member_email">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_password') }} *</label>
                                    <input type="password" class="form-control Input_control" id="member_password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('messages.member_role') }} *</label>
                                    <div class="custom-combo-dropdown position-relative">
                                        <input type="text" class="form-control Input_control" id="member_role" name="member_role" placeholder="{{ __('messages.select') }}" autocomplete="off" readonly style="cursor: pointer;">
                                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                                        <div class="dropdown-options" id="roleDropdown">
                                            <div class="text-center py-2"><div class="spinner-border spinner-border-sm"></div></div>
                                        </div>
                                    </div>
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
    // Load roles from API
    async function loadUserRoles() {
        try {
            const response = await api.makeRequest('general/user_roles', {}, 'GET');
            
            const roleDropdown = document.getElementById('roleDropdown');
            if (response.code === 200 && response.data) {
                roleDropdown.innerHTML = response.data.map(role => 
                    `<div class="dropdown-option" data-value="${role.id}">${role.id}</div>`
                ).join('');
                
                // Attach click handlers
                roleDropdown.querySelectorAll('.dropdown-option').forEach(option => {
                    option.addEventListener('click', function() {
                        document.getElementById('member_role').value = this.getAttribute('data-value');
                        document.getElementById('member_role').classList.remove('is-invalid');
                        roleDropdown.classList.remove('show');
                    });
                });
            }
        } catch (error) {
            console.error('Failed to load roles:', error);
            document.getElementById('roleDropdown').innerHTML = '<div class="text-center py-2 text-danger">Failed to load roles</div>';
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
    }, { once: true });

    // Show/Hide Add Member Form
    function showAddMemberForm() {
        document.getElementById('addMemberForm').style.display = 'block';
        document.getElementById('member_name').focus();
    }

    function hideAddMemberForm() {
        document.getElementById('addMemberForm').style.display = 'none';
        document.getElementById('teamMemberForm').reset();
        document.querySelectorAll('#teamMemberForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));
    }

    // Load Team Members
    async function loadTeamMembers() {
        try {
            const response = await api.makeRequest('auth/list_team_members', {
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
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="fas fa-user-circle fa-lg text-primary"></i>
                                            <h6 class="mb-0 fw-bold">${member.name}</h6>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 text-muted small">
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-envelope"></i>
                                                <span>${member.email}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="fas fa-briefcase text-warning"></i>
                                                <span>${member.role}</span>
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
        document.querySelectorAll('#teamMemberForm .is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Get form values
        const name = document.getElementById('member_name').value.trim();
        const email = document.getElementById('member_email').value.trim();
        const password = document.getElementById('member_password').value;
        const role = document.getElementById('member_role').value;

        let isValid = true;

        // Validate name
        if (!name) {
            document.getElementById('member_name').classList.add('is-invalid');
            isValid = false;
        }

        // Validate email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            document.getElementById('member_email').classList.add('is-invalid');
            isValid = false;
        }

        // Validate password (min 8 chars, uppercase, lowercase, number, special char)
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
        if (!password || !passwordRegex.test(password)) {
            document.getElementById('member_password').classList.add('is-invalid');
            isValid = false;
        }

        // Validate role
        if (!role) {
            document.getElementById('member_role').classList.add('is-invalid');
            isValid = false;
        }

        if (!isValid) {
            showToast('{{ __('messages.please_fill_required_fields') }}', 'error');
            return;
        }

        const btn = this.querySelector('button[type="submit"]');
        if (btn.disabled) return;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __('messages.adding') }}...';

        try {
            const response = await api.makeRequest('auth/add_team_member', {
                user_id: {{ auth()->id() ?? 1 }},
                name: name,
                email: email,
                phone: email,
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
</script>
