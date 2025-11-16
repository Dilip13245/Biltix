@extends('website.layout.app')

@section('title', 'Daily Log Role Descriptions')

@section('content')
    <div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2>{{ __('messages.daily_logs') }}</h2>
        </div>
    </div>
    <section class="px-md-4">
        <div class="container-fluid">
            <!-- Role Tabs -->
            <div class="row mb-4">
                <div class="col-12">
                    <ul class="nav nav-tabs gap-2 gap-md-3" id="roleTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="engineers-tab" data-bs-toggle="tab" data-bs-target="#engineers" type="button" role="tab" aria-controls="engineers" aria-selected="true" data-role="engineer">
                                {{ __('messages.engineers') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="foreman-tab" data-bs-toggle="tab" data-bs-target="#foreman" type="button" role="tab" aria-controls="foreman" aria-selected="false" data-role="foreman">
                                {{ __('messages.foreman') }}
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="labourers-tab" data-bs-toggle="tab" data-bs-target="#labourers" type="button" role="tab" aria-controls="labourers" aria-selected="false" data-role="labourer">
                                {{ __('messages.labourers') }}
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content" id="roleTabsContent">
                <!-- Engineers Tab -->
                <div class="tab-pane fade show active" id="engineers" role="tabpanel" aria-labelledby="engineers-tab">
                    <div class="card B_shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 {{ text_align() }}">
                                <i class="fas fa-user-tie {{ margin_end(2) }}"></i>{{ __('messages.engineers_tasks_performed') }}
                            </h5>
                            <button class="btn btn-sm orange_btn" onclick="openAddModal('engineer')">
                                <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_logs') }}
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="engineersList" class="role-descriptions-list">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">{{ __('messages.loading') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Foreman Tab -->
                <div class="tab-pane fade" id="foreman" role="tabpanel" aria-labelledby="foreman-tab">
                    <div class="card B_shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 {{ text_align() }}">
                                <i class="fas fa-hard-hat {{ margin_end(2) }}"></i>{{ __('messages.foreman_tasks_performed') }}
                            </h5>
                            <button class="btn btn-sm orange_btn" onclick="openAddModal('foreman')">
                                <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_logs') }}
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="foremanList" class="role-descriptions-list">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">{{ __('messages.loading') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Labourers Tab -->
                <div class="tab-pane fade" id="labourers" role="tabpanel" aria-labelledby="labourers-tab">
                    <div class="card B_shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 {{ text_align() }}">
                                <i class="fas fa-users {{ margin_end(2) }}"></i>{{ __('messages.labourers_tasks_performed') }}
                            </h5>
                            <button class="btn btn-sm orange_btn" onclick="openAddModal('labourer')">
                                <i class="fas fa-plus {{ margin_end(1) }}"></i>{{ __('messages.add_logs') }}
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="labourersList" class="role-descriptions-list">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">{{ __('messages.loading') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('website.modals.add-role-description-modal')

    <style>
        .role-descriptions-list {
            min-height: 200px;
        }
        .role-descriptions-list ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .role-descriptions-list li {
            padding: 12px 16px;
            border-bottom: 1px solid #e9ecef;
            {{ text_align() }}: left;
        }
        .role-descriptions-list li:last-child {
            border-bottom: none;
        }
        .role-descriptions-list li:hover {
            background-color: #f8f9fa;
        }
        [dir="rtl"] .role-descriptions-list li {
            text-align: right;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .content-header {
                flex-direction: column;
                align-items: flex-start !important;
            }
            .nav-tabs {
                flex-wrap: wrap;
            }
            .nav-tabs .nav-item {
                flex: 1;
                min-width: 100px;
            }
            .card-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 0.5rem;
            }
            .card-header .btn {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .nav-tabs .nav-item {
                flex: 1 1 100%;
            }
            .card-header h5 {
                font-size: 1rem;
            }
        }
    </style>

    <script>
        let currentProjectId = null;
        let currentUserId = null;
        let currentRole = 'engineer';

        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }

        document.addEventListener('DOMContentLoaded', function() {
            currentProjectId = getProjectIdFromUrl();
            currentUserId = window.UniversalAuth ? UniversalAuth.getUserId() : {{ auth()->id() ?? 1 }};

            // Load initial data
            loadRoleDescriptions('engineer');

            // Listen to tab changes
            const tabButtons = document.querySelectorAll('#roleTabs button[data-bs-toggle="tab"]');
            tabButtons.forEach(button => {
                button.addEventListener('shown.bs.tab', function(event) {
                    const role = event.target.getAttribute('data-role');
                    currentRole = role;
                    loadRoleDescriptions(role);
                });
            });
        });

        function loadRoleDescriptions(role) {
            // Map role to container ID
            const roleContainerMap = {
                'engineer': 'engineersList',
                'foreman': 'foremanList',
                'labourer': 'labourersList'
            };
            const containerId = roleContainerMap[role] || role + 'List';
            const listContainer = document.getElementById(containerId);
            if (!listContainer) return;

            listContainer.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">{{ __('messages.loading') }}...</p></div>';

            if (typeof api === 'undefined' || !api.listRoleDescriptions) {
                listContainer.innerHTML = '<div class="text-center py-4 text-muted"><p>{{ __('messages.api_not_available') }}</p></div>';
                return;
            }

            api.listRoleDescriptions({
                project_id: currentProjectId
            }).then(response => {
                if (response.code === 200 && response.data) {
                    const descriptions = response.data.filter(item => item.role === role);
                    displayRoleDescriptions(role, descriptions);
                } else {
                    listContainer.innerHTML = '<div class="text-center py-4 text-muted"><p>{{ __('messages.no_descriptions_found') }}</p></div>';
                }
            }).catch(error => {
                console.error('Error loading role descriptions:', error);
                listContainer.innerHTML = '<div class="text-center py-4 text-danger"><p>{{ __('messages.error_loading_data') }}</p></div>';
            });
        }

        function displayRoleDescriptions(role, descriptions) {
            // Map role to container ID
            const roleContainerMap = {
                'engineer': 'engineersList',
                'foreman': 'foremanList',
                'labourer': 'labourersList'
            };
            const containerId = roleContainerMap[role] || role + 'List';
            const listContainer = document.getElementById(containerId);
            if (!listContainer) return;

            if (!descriptions || descriptions.length === 0) {
                listContainer.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-inbox fa-3x mb-3 d-block"></i><p>{{ __('messages.no_descriptions_found') }}</p></div>';
                return;
            }

            let html = '<ul>';
            descriptions.forEach(desc => {
                html += `<li>${escapeHtml(desc.description)}</li>`;
            });
            html += '</ul>';

            listContainer.innerHTML = html;
        }

        function openAddModal(role) {
            currentRole = role;
            const modal = new bootstrap.Modal(document.getElementById('addRoleDescriptionModal'));
            document.getElementById('roleInput').value = role;
            document.getElementById('roleDisplay').textContent = getRoleDisplayName(role);
            document.getElementById('addRoleDescriptionForm').reset();
            document.getElementById('descriptionsContainer').innerHTML = '';
            addDescriptionField();
            updateFieldButtons();
            modal.show();
        }

        function getRoleDisplayName(role) {
            const roleNames = {
                'engineer': '{{ __('messages.engineers') }}',
                'foreman': '{{ __('messages.foreman') }}',
                'labourer': '{{ __('messages.labourers') }}'
            };
            return roleNames[role] || role;
        }

        function addDescriptionField() {
            const container = document.getElementById('descriptionsContainer');
            const fieldCount = container.children.length;
            const fieldHtml = `
                <div class="description-field mb-3" data-index="${fieldCount}">
                    <textarea class="form-control Input_control description-input" rows="3" 
                        placeholder="{{ __('messages.enter_description') }}" 
                        maxlength="1000" required></textarea>
                    <div class="invalid-feedback"></div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', fieldHtml);
            
            // Update buttons - show both Add More and Remove if there are multiple fields
            updateFieldButtons();
        }
        
        function updateFieldButtons() {
            const container = document.getElementById('descriptionsContainer');
            const fieldCount = container.children.length;
            const buttonContainer = document.getElementById('fieldButtonsContainer');
            
            if (buttonContainer) {
                let buttonsHtml = `
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDescriptionField()">
                        {{ __('messages.add_more') }}
                    </button>
                `;
                
                if (fieldCount > 1) {
                    buttonsHtml += `
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeLastField()">
                            {{ __('messages.remove') }}
                        </button>
                    `;
                }
                
                buttonContainer.innerHTML = buttonsHtml;
            }
        }
        
        function removeLastField() {
            const container = document.getElementById('descriptionsContainer');
            if (container.children.length > 1) {
                container.lastElementChild.remove();
                updateFieldButtons();
            }
        }


        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Form submission
        document.getElementById('addRoleDescriptionForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = this;
            // Find submit button - it's in modal footer with form attribute
            const submitBtn = document.querySelector('button[form="addRoleDescriptionForm"]') || 
                             form.querySelector('button[type="submit"]');
            if (!submitBtn) {
                console.error('Submit button not found');
                return;
            }
            const descriptions = [];
            
            // Collect all descriptions
            const descriptionInputs = form.querySelectorAll('.description-input');
            descriptionInputs.forEach(input => {
                const value = input.value.trim();
                if (value) {
                    descriptions.push(value);
                }
            });

            if (descriptions.length === 0) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __('messages.please_add_at_least_one_description') }}');
                } else {
                    alert('{{ __('messages.please_add_at_least_one_description') }}');
                }
                return;
            }

            // Validate all fields
            let isValid = true;
            descriptionInputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __('messages.please_fill_all_fields') }}');
                } else {
                    alert('{{ __('messages.please_fill_all_fields') }}');
                }
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __('messages.adding') }}...';

            try {
                const response = await api.addRoleDescription({
                    user_id: currentUserId,
                    project_id: currentProjectId,
                    role: currentRole,
                    descriptions: descriptions
                });

                if (response.code === 200) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('{{ __('messages.descriptions_added_successfully') }}');
                    } else {
                        alert('{{ __('messages.descriptions_added_successfully') }}');
                    }
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addRoleDescriptionModal'));
                    modal.hide();
                    loadRoleDescriptions(currentRole);
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || '{{ __('messages.error_adding_descriptions') }}');
                    } else {
                        alert(response.message || '{{ __('messages.error_adding_descriptions') }}');
                    }
                }
            } catch (error) {
                console.error('Error adding descriptions:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __('messages.error_adding_descriptions') }}');
                } else {
                    alert('{{ __('messages.error_adding_descriptions') }}');
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '{{ __('messages.add_log') }}';
            }
        });
    </script>
@endsection

