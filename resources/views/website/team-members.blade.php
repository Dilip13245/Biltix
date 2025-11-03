@extends('website.layout.app')

@section('title', 'Team Members')

@section('content')
    <style>
        select.searchable-select { opacity: 0; transition: opacity 0.2s; }
        select.searchable-select.initialized, .searchable-dropdown { opacity: 1; }
    </style>
    <div class="content-header border-0 shadow-none mb-4 d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2>{{ __('messages.team_members') }}</h2>
            <p>{{ __('messages.view_team_details') }}</p>
        </div>
        <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap">
            <form class="serchBar position-relative serchBar2">
                <input class="form-control pe-5" type="search" placeholder="{{ __('messages.search_members') }}"
                    aria-label="Search" id="searchInput" maxlength="100">
                <span class="search_icon position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;">
                    <img src="{{ asset('website/images/icons/search.svg') }}" alt="search" style="width: 16px; height: 16px;">
                </span>
            </form>
            <div class="dropdown">
                <button class="btn filter-btn d-flex align-items-center px-3 py-2 bg4" type="button" data-bs-toggle="dropdown">
                    <svg width="17" height="14" viewBox="0 0 17 14" class="me-2" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.512337 0.715625C0.718587 0.278125 1.15609 0 1.64046 0H15.1405C15.6248 0 16.0623 0.278125 16.2686 0.715625C16.4748 1.15313 16.4123 1.66875 16.1061 2.04375L10.3905 9.02812V13C10.3905 13.3781 10.178 13.725 9.83734 13.8938C9.49671 14.0625 9.09359 14.0281 8.79046 13.8L6.79046 12.3C6.53734 12.1125 6.39046 11.8156 6.39046 11.5V9.02812L0.671712 2.04063C0.368587 1.66875 0.302962 1.15 0.512337 0.715625Z"
                            fill="#374151" />
                    </svg>
                    <span class="text-black">{{ __('messages.filter') }}</span>
                </button>
                <ul class="dropdown-menu p-3" style="min-width: 250px;">
                    <li>
                        <label class="form-label small fw-semibold">{{ __('messages.filter_by_role') }}</label>
                        <select class="form-select form-select-sm searchable-select" id="roleFilter">
                            <option value="">{{ __('messages.all_roles') }}</option>
                            <option value="contractor">{{ __('messages.contractor') }}</option>
                            <option value="engineer">{{ __('messages.engineer') }}</option>
                            <option value="consultant">{{ __('messages.consultant') }}</option>
                            <option value="manager">{{ __('messages.manager') }}</option>
                            <option value="architect">{{ __('messages.architect') }}</option>
                            <option value="supervisor">{{ __('messages.supervisor') }}</option>
                        </select>
                    </li>
                </ul>
            </div>
            @can('team', 'create')
                <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addMemberModal" onclick="if(!this.disabled){this.disabled=true;setTimeout(()=>{this.disabled=false;},3000);}">
                    <i class="fas fa-plus"></i>
                    {{ __('messages.add_member') }}
                </button>
            @endcan
        </div>
    </div>
    
    <section class="px-md-4">
        <div class="container-fluid">
            <div class="row g-4" id="statsContainer">
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="0s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.total_members') }}</div>
                                <div class="stat-value" id="totalMembers">0</div>
                            </div>
                            <span class="ms-auto stat-icon bg1">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.5 0C5.16304 0 5.79893 0.263392 6.26777 0.732233C6.73661 1.20107 7 1.83696 7 2.5C7 3.16304 6.73661 3.79893 6.26777 4.26777C5.79893 4.73661 5.16304 5 4.5 5C3.83696 5 3.20107 4.73661 2.73223 4.26777C2.26339 3.79893 2 3.16304 2 2.5C2 1.83696 2.26339 1.20107 2.73223 0.732233C3.20107 0.263392 3.83696 0 4.5 0ZM16 0C16.663 0 17.2989 0.263392 17.7678 0.732233C18.2366 1.20107 18.5 1.83696 18.5 2.5C18.5 3.16304 18.2366 3.79893 17.7678 4.26777C17.2989 4.73661 16.663 5 16 5C15.337 5 14.7011 4.73661 14.2322 4.26777C13.7634 3.79893 13.5 3.16304 13.5 2.5C13.5 1.83696 13.7634 1.20107 14.2322 0.732233C14.7011 0.263392 15.337 0 16 0ZM0 9.33438C0 7.49375 1.49375 6 3.33437 6H4.66875C5.16562 6 5.6375 6.10938 6.0625 6.30312C6.02187 6.52812 6.00313 6.7625 6.00313 7C6.00313 8.19375 6.52812 9.26562 7.35625 10C7.35 10 7.34375 10 7.33437 10H0.665625C0.3 10 0 9.7 0 9.33438ZM12.6656 10C12.6594 10 12.6531 10 12.6438 10C13.475 9.26562 13.9969 8.19375 13.9969 7C13.9969 6.7625 13.975 6.53125 13.9375 6.30312C14.3625 6.10625 14.8344 6 15.3313 6H16.6656C18.5063 6 20 7.49375 20 9.33438C20 9.70312 19.7 10 19.3344 10H12.6656ZM7 7C7 6.20435 7.31607 5.44129 7.87868 4.87868C8.44129 4.31607 9.20435 4 10 4C10.7956 4 11.5587 4.31607 12.1213 4.87868C12.6839 5.44129 13 6.20435 13 7C13 7.79565 12.6839 8.55871 12.1213 9.12132C11.5587 9.68393 10.7956 10 10 10C9.20435 10 8.44129 9.68393 7.87868 9.12132C7.31607 8.55871 7 7.79565 7 7ZM4 15.1656C4 12.8656 5.86562 11 8.16562 11H11.8344C14.1344 11 16 12.8656 16 15.1656C16 15.625 15.6281 16 15.1656 16H4.83437C4.375 16 4 15.6281 4 15.1656Z" fill="#4477C4" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.site_engineers') }}</div>
                                <div class="stat-value text-success" id="siteEngineers">0</div>
                            </div>
                            <span class="ms-auto stat-icon bg-green1">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 4C3 2.93913 3.42143 1.92172 4.17157 1.17157C4.92172 0.421427 5.93913 0 7 0C8.06087 0 9.07828 0.421427 9.82843 1.17157C10.5786 1.92172 11 2.93913 11 4C11 5.06087 10.5786 6.07828 9.82843 6.82843C9.07828 7.57857 8.06087 8 7 8C5.93913 8 4.92172 7.57857 4.17157 6.82843C3.42143 6.07828 3 5.06087 3 4ZM0 15.0719C0 11.9937 2.49375 9.5 5.57188 9.5H8.42813C11.5063 9.5 14 11.9937 14 15.0719C14 15.5844 13.5844 16 13.0719 16H0.928125C0.415625 16 0 15.5844 0 15.0719ZM19.5312 5.53125L15.5312 9.53125C15.2375 9.825 14.7625 9.825 14.4719 9.53125L12.4719 7.53125C12.1781 7.2375 12.1781 6.7625 12.4719 6.47188C12.7656 6.18125 13.2406 6.17813 13.5312 6.47188L15 7.94063L18.4688 4.46875C18.7625 4.175 19.2375 4.175 19.5281 4.46875C19.8188 4.7625 19.8219 5.2375 19.5281 5.52812L19.5312 5.53125Z" fill="#16A34A" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay=".8s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.contractors') }}</div>
                                <div class="stat-value orange_color" id="contractors">0</div>
                            </div>
                            <span class="ms-auto stat-icon bg2">
                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 16H0V0H18V16Z" stroke="#E5E7EB" />
                                    <path d="M8 1C7.44688 1 7 1.44687 7 2V2.07188V5.18437C7 5.35938 6.85938 5.5 6.68437 5.5C6.57187 5.5 6.46563 5.44062 6.40938 5.34062L4.90938 2.71875C2.59375 3.85938 1 6.24375 1 9V11H17V8.925C16.9719 6.2 15.3844 3.85 13.0906 2.71875L11.5906 5.34062C11.5344 5.44062 11.4281 5.5 11.3156 5.5C11.1406 5.5 11 5.35938 11 5.18437V2.07188V2C11 1.44687 10.5531 1 10 1H8ZM0.51875 12C0.23125 12 0 12.2312 0 12.5188C0 12.6656 0.0625 12.8062 0.18125 12.8906C0.859375 13.3875 3.49375 15 9 15C14.5063 15 17.1406 13.3875 17.8188 12.8906C17.9375 12.8031 18 12.6656 18 12.5188C18 12.2312 17.7688 12 17.4813 12H0.51875Z" fill="#F58D2E" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="1.2s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.consultants') }}</div>
                                <div class="stat-value text-blue" id="consultants">0</div>
                            </div>
                            <span class="ms-auto stat-icon bg-blue1">
                                <svg width="14" height="16" viewBox="0 0 14 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_861_3263)">
                                        <path d="M7 8C5.93913 8 4.92172 7.57857 4.17157 6.82843C3.42143 6.07828 3 5.06087 3 4C3 2.93913 3.42143 1.92172 4.17157 1.17157C4.92172 0.421427 5.93913 0 7 0C8.06087 0 9.07828 0.421427 9.82843 1.17157C10.5786 1.92172 11 2.93913 11 4C11 5.06087 10.5786 6.07828 9.82843 6.82843C9.07828 7.57857 8.06087 8 7 8ZM6.53438 11.225L5.95312 10.2563C5.75313 9.92188 5.99375 9.5 6.38125 9.5H7H7.61562C8.00313 9.5 8.24375 9.925 8.04375 10.2563L7.4625 11.225L8.50625 15.0969L9.63125 10.5063C9.69375 10.2531 9.9375 10.0875 10.1906 10.1531C12.3813 10.7031 14 12.6844 14 15.0406C14 15.5719 13.5687 16 13.0406 16H8.92188C8.85625 16 8.79688 15.9875 8.74063 15.9656L8.75 16H5.25L5.25938 15.9656C5.20312 15.9875 5.14062 16 5.07812 16H0.959375C0.43125 16 0 15.5687 0 15.0406C0 12.6812 1.62188 10.7 3.80938 10.1531C4.0625 10.0906 4.30625 10.2563 4.36875 10.5063L5.49375 15.0969L6.5375 11.225H6.53438Z" fill="#9333EA" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_861_3263">
                                            <path d="M0 0H14V16H0V0Z" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row gy-4 card_wraPper mt-4" id="teamContainer">
                <!-- Loading state -->
                <div class="col-12 text-center" id="loadingState">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">{{ __('messages.loading') }}</span>
                    </div>
                    <p class="mt-2 text-muted">{{ __('messages.loading') }}</p>
                </div>

                <!-- No members state -->
                <div class="col-12 text-center d-none" id="noMembersState">
                    <div class="py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('messages.no_team_members_found') }}</h5>
                        <p class="text-muted">{{ __('messages.add_first_team_member') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('website.modals.add-member-modal')

    <script>
        let currentMembers = [];
        let currentProjectId = null;
        let currentUserId = null;

        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }

        document.addEventListener('DOMContentLoaded', function() {
            currentProjectId = getProjectIdFromUrl();
            currentUserId = {{ auth()->id() ?? 1 }};

            loadTeamMembers();
            setupAddMemberForm();
            setupSearchAndFilter();
            
            if (typeof initSearchableDropdowns === 'function') {
                initSearchableDropdowns();
            }
            document.querySelectorAll('.searchable-select').forEach(el => el.classList.add('initialized'));
        });

        async function loadTeamMembers() {
            try {
                showLoading();

                const requestData = {
                    project_id: currentProjectId
                };

                const [membersResponse, usersResponse] = await Promise.all([
                    api.listMembers(requestData),
                    api.getAllUsers()
                ]);

                if (membersResponse.code === 200) {
                    currentMembers = membersResponse.data || [];
                    const allUsers = usersResponse.code === 200 ? usersResponse.data : [];
                    updateStats(currentMembers, allUsers);
                    renderTeamMembers(currentMembers);
                } else {
                    console.error('Failed to load team members:', membersResponse.message);
                    showNoMembers();
                }
            } catch (error) {
                console.error('Error loading team members:', error);
                showNoMembers();
            }
        }

        function renderTeamMembers(members) {
            const container = document.getElementById('teamContainer');
            const loadingState = document.getElementById('loadingState');
            const noMembersState = document.getElementById('noMembersState');

            if (!container) return;

            if (loadingState) loadingState.classList.add('d-none');

            if (!members || members.length === 0) {
                const memberCards = container.querySelectorAll('.col-md-6:not(#loadingState):not(#noMembersState), .col-lg-4');
                memberCards.forEach(card => card.remove());
                if (noMembersState) noMembersState.classList.remove('d-none');
                return;
            }

            if (noMembersState) noMembersState.classList.add('d-none');

            const membersHtml = members.map((member, index) => {
                const delay = (index * 0.4).toFixed(1);
                
                const profileImage = member.user?.profile_image;
                const imageHtml = profileImage 
                    ? `<img src="${profileImage}" alt="${member.user.name}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">`
                    : `<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;"><i class="fas fa-user" style="font-size: 24px;"></i></div>`;
                
                return `
                    <div class="col-lg-4 col-md-6 mb-4 wow fadeInUp" data-wow-delay="${delay}s">
                        <div class="card h-100 B_shadow team-member-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="me-3">
                                        ${imageHtml}
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="fw-semibold mb-1">${member.user ? member.user.name : 'Unknown User'}</h5>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <p class="text-muted small mb-2">${member.user?.company_name || 'Unknown Company'}</p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <span class="badge bg2 orange_color">${member.role_in_project}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            const memberCards = container.querySelectorAll('.col-md-6:not(#loadingState):not(#noMembersState), .col-lg-4');
            memberCards.forEach(card => card.remove());

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = membersHtml;

            while (tempDiv.firstChild) {
                container.insertBefore(tempDiv.firstChild, loadingState || noMembersState || null);
            }
        }

        function setupAddMemberForm() {
            const addMemberForm = document.getElementById('addMemberForm');
            if (addMemberForm) {
                addMemberForm.addEventListener('submit', handleMemberSubmit);
            }
        }

        // Simple button protection
        function protectMemberButton(btn) {
            if (btn.disabled) return;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            setTimeout(function() {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-user-plus me-2"></i>{{ __("messages.add_member") }}';
            }, 5000);
        }

        async function handleMemberSubmit(e) {
            e.preventDefault();
            
            // Protect button
            const submitBtn = document.getElementById('memberSubmitBtn');
            if (submitBtn) protectMemberButton(submitBtn);

            try {
                const formData = new FormData();
                
                formData.append('user_id', currentUserId);
                formData.append('project_id', currentProjectId);
                formData.append('member_user_id', document.getElementById('memberSelect').value);
                formData.append('role_in_project', document.getElementById('roleDisplay').value);

                const response = await api.addMember(formData);

                if (response.code === 200) {
                    const addMemberModal = bootstrap.Modal.getInstance(document.getElementById('addMemberModal'));
                    if (addMemberModal) addMemberModal.hide();
                    
                    toastr.success('{{ __('messages.member_added_successfully') }}');
                    document.getElementById('addMemberForm').reset();
                    loadTeamMembers();
                } else {
                    toastr.error('{{ __('messages.failed_to_add_member') }}: ' + response.message);
                }
            } catch (error) {
                console.error('Error adding member:', error);
                toastr.error('{{ __('messages.failed_to_add_member') }}');
            }
        }

        function showLoading() {
            const loadingState = document.getElementById('loadingState');
            const noMembersState = document.getElementById('noMembersState');
            if (loadingState) loadingState.classList.remove('d-none');
            if (noMembersState) noMembersState.classList.add('d-none');
        }

        function showNoMembers() {
            const loadingState = document.getElementById('loadingState');
            const noMembersState = document.getElementById('noMembersState');
            if (loadingState) loadingState.classList.add('d-none');
            if (noMembersState) noMembersState.classList.remove('d-none');
        }

        function updateStats(members, allUsers) {
            // Count by role from current project members only
            const roleCounts = {};
            members.forEach(member => {
                if (member.user) {
                    const role = member.user.role || 'unknown';
                    roleCounts[role] = (roleCounts[role] || 0) + 1;
                }
            });

            // Update total members (project members only)
            const totalEl = document.getElementById('totalMembers');
            if (totalEl) totalEl.textContent = members.length;
            
            // Update role-specific counts
            const engineersEl = document.getElementById('siteEngineers');
            if (engineersEl) engineersEl.textContent = roleCounts['site_engineer'] || 0;
            
            const contractorsEl = document.getElementById('contractors');
            if (contractorsEl) contractorsEl.textContent = roleCounts['contractor'] || 0;
            
            const consultantsEl = document.getElementById('consultants');
            if (consultantsEl) consultantsEl.textContent = roleCounts['consultant'] || 0;
            
            // Update stats container with all roles dynamically
            updateDynamicRoleStats(roleCounts, members.length);
        }
        
        function updateDynamicRoleStats(roleCounts, totalActiveUsers) {
            const statsContainer = document.getElementById('statsContainer');
            const existingCards = statsContainer.querySelectorAll('.col-xxl-3');
            
            // Keep first card (total), update others dynamically
            const firstCard = existingCards[0];
            statsContainer.innerHTML = '';
            statsContainer.appendChild(firstCard);
            
            // Add role cards dynamically
            let index = 1;
            for (const [role, count] of Object.entries(roleCounts)) {
                if (count > 0) {
                    const delay = (index * 0.4).toFixed(1);
                    const roleCard = createRoleCard(role, count, delay);
                    statsContainer.insertAdjacentHTML('beforeend', roleCard);
                    index++;
                }
            }
        }
        
        function createRoleCard(role, count, delay) {
            const roleDisplay = role.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const colors = {
                'site_engineer': { bg: 'bg-green1', text: 'text-success', icon: '#16A34A' },
                'contractor': { bg: 'bg2', text: 'orange_color', icon: '#F58D2E' },
                'consultant': { bg: 'bg-blue1', text: 'text-blue', icon: '#9333EA' },
                'project_manager': { bg: 'bg1', text: 'text-primary', icon: '#4477C4' }
            };
            const color = colors[role] || { bg: 'bg1', text: 'text-primary', icon: '#4477C4' };
            
            return `
                <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="${delay}s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">${roleDisplay}</div>
                                <div class="stat-value ${color.text}">${count}</div>
                            </div>
                            <span class="ms-auto stat-icon ${color.bg}">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 8C5.93913 8 4.92172 7.57857 4.17157 6.82843C3.42143 6.07828 3 5.06087 3 4C3 2.93913 3.42143 1.92172 4.17157 1.17157C4.92172 0.421427 5.93913 0 7 0C8.06087 0 9.07828 0.421427 9.82843 1.17157C10.5786 1.92172 11 2.93913 11 4C11 5.06087 10.5786 6.07828 9.82843 6.82843C9.07828 7.57857 8.06087 8 7 8ZM0 15.0719C0 11.9937 2.49375 9.5 5.57188 9.5H8.42813C11.5063 9.5 14 11.9937 14 15.0719C14 15.5844 13.5844 16 13.0719 16H0.928125C0.415625 16 0 15.5844 0 15.0719Z" fill="${color.icon}" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            `;
        }

        function setupSearchAndFilter() {
            const searchInput = document.getElementById('searchInput');
            const roleFilter = document.getElementById('roleFilter');
            
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    filterMembers();
                });
            }
            
            if (roleFilter) {
                roleFilter.addEventListener('change', function() {
                    filterMembers();
                });
            }
        }

        function filterMembers() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const roleFilter = document.getElementById('roleFilter').value.toLowerCase();

            const filteredMembers = currentMembers.filter(member => {
                const name = member.user ? member.user.name.toLowerCase() : '';
                const role = member.user ? member.user.role_name.toLowerCase() : '';
                const projectRole = member.role_in_project.toLowerCase();

                const matchesSearch = name.includes(searchTerm) || role.includes(searchTerm) || projectRole.includes(searchTerm);
                const matchesRole = !roleFilter || role.includes(roleFilter) || projectRole.includes(roleFilter);

                return matchesSearch && matchesRole;
            });

            renderTeamMembers(filteredMembers);
        }

        async function editMember(memberId) {
            // Implementation for editing member
            console.log('Edit member:', memberId);
        }

        async function removeMember(memberId) {
            if (confirm('{{ __('messages.confirm_remove_member') }}')) {
                // Implementation for removing member
                console.log('Remove member:', memberId);
            }
        }
    </script>
    


@endsection