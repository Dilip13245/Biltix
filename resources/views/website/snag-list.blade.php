@extends('website.layout.app')

@section('title', 'Snag List')

@section('content')
    <style>
        select.searchable-select {
            opacity: 0;
            transition: opacity 0.2s;
        }

        select.searchable-select.initialized,
        .searchable-dropdown {
            opacity: 1;
        }
        
        .custom-filter-dropdown {
            width: 100%;
        }
        
        .row.align-items-end > div {
            display: flex;
            flex-direction: column;
        }
    </style>
    <div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2>{{ __('messages.snag_list') }}</h2>
            <p>{{ __('messages.view_manage_snags') }}</p>
        </div>
        @can('snags', 'create')
            <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addSnagModal">
                <i class="fas fa-plus"></i>
                {{ __('messages.add_new_snag') }}
            </button>
        @endcan
    </div>
    <section class="px-md-4">
        <div class="container-fluid ">
            <div class="row  wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body px-md-3 py-md-4">
                            <div class="row align-items-end">
                                <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                                    <label class="fw-medium mb-2">{{ __('messages.status') }}</label>
                                    <div class="custom-filter-dropdown" id="statusFilterWrapper">
                                        <div class="custom-filter-btn" id="statusFilterBtn">{{ __('messages.all_status') }}</div>
                                        <div class="custom-filter-options" id="statusFilterOptions">
                                            <div class="custom-filter-option selected" data-value="all">{{ __('messages.all_status') }}</div>
                                            <div class="custom-filter-option" data-value="todo">{{ __('messages.todo') }}</div>
                                            <div class="custom-filter-option" data-value="in_progress">{{ __('messages.in_progress') }}</div>
                                            <div class="custom-filter-option" data-value="complete">{{ __('messages.complete') }}</div>
                                            <div class="custom-filter-option" data-value="approve">{{ __('messages.approve') }}</div>
                                        </div>
                                        <select id="statusFilter" style="display: none;">
                                            <option value="all">{{ __('messages.all_status') }}</option>
                                            <option value="todo">{{ __('messages.todo') }}</option>
                                            <option value="in_progress">{{ __('messages.in_progress') }}</option>
                                            <option value="complete">{{ __('messages.complete') }}</option>
                                            <option value="approve">{{ __('messages.approve') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-6 col-sm-6 col-12 mt-3 mt-md-0">
                                    <label class="fw-medium mb-2">{{ __('messages.search') }}</label>
                                    <form class="serchBar position-relative serchBar2">
                                        @if (app()->getLocale() == 'ar')
                                            <input class="form-control" type="search" id="searchInput"
                                                placeholder="{{ __('messages.search_snags') }}" aria-label="Search"
                                                dir="auto" style="padding-left: 45px; padding-right: 15px;"
                                                maxlength="100">
                                            <span class="search_icon"
                                                style="left: 15px; right: auto; pointer-events: none;"><img
                                                    src="{{ asset('website/images/icons/search.svg') }}"
                                                    alt="search"></span>
                                        @else
                                            <input class="form-control" type="search" id="searchInput"
                                                placeholder="{{ __('messages.search_snags') }}" aria-label="Search"
                                                dir="auto" style="padding-right: 45px;" maxlength="100">
                                            <span class="search_icon" style="right: 15px; pointer-events: none;"><img
                                                    src="{{ asset('website/images/icons/search.svg') }}"
                                                    alt="search"></span>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div class="CarDs-grid" id="snagsContainer">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('website.modals.add-snag-modal')
    @include('website.modals.snag-details-modal')
    @include('website.modals.drawing-modal')

    <script>
        let allSnags = [];
        let allUsers = [];

        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }

        document.addEventListener('DOMContentLoaded', async function() {
            // Check if API is available before loading
            if (typeof api !== 'undefined' && api.getSnags) {
                loadSnags();
                await loadUsers();
                await loadPhases();
                setupFilters();
                setupAddSnagForm();
            } else {
                console.error('API not available');
                showError('{{ __('messages.api_not_available') }}');
            }
        });

        async function loadSnags() {
            try {
                showLoading();
                const projectId = getProjectIdFromUrl();

                const requestData = {
                    project_id: projectId
                };

                const response = await api.getSnags(requestData);

                if (response.code === 200) {
                    allSnags = response.data.data || [];
                    displaySnags(allSnags);
                } else {
                    showError('Failed to load snags: ' + response.message);
                }
            } catch (error) {
                console.error('Error loading snags:', error);
                showError('Failed to load snags');
            }
        }

        function showLoading() {
            const container = document.getElementById('snagsContainer');
            container.style.display = 'flex';
            container.style.justifyContent = 'center';
            container.style.alignItems = 'center';
            container.style.minHeight = '400px';
            container.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                    <p class="mt-2 text-muted">{{ __('messages.loading') }}...</p>
                </div>
            `;
        }

        async function loadUsers() {
            try {
                const response = await api.getProjectTeamMembers({
                    project_id: getProjectIdFromUrl(),
                    user_id: {{ auth()->id() ?? 1 }}
                });

                if (response.code === 200) {
                    const users = response.data || [];
                    const assignedToSelect = document.getElementById('assignedTo');

                    if (assignedToSelect) {
                        assignedToSelect.innerHTML = '<option value="">{{ __('messages.select_user') }}</option>';

                        users.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.name + (user.role_in_project ? ` (${user.role_in_project})` : '');
                            assignedToSelect.appendChild(option);
                        });


                    }
                }
            } catch (error) {
                console.error('Error loading project team members:', error);
            }
        }

        async function loadPhases() {
            try {
                const response = await api.listPhases({
                    project_id: getProjectIdFromUrl()
                });

                if (response.code === 200) {
                    const phases = response.data || [];
                    const phaseSelect = document.getElementById('phaseSelect');

                    if (phaseSelect) {
                        phaseSelect.innerHTML = '<option value="">{{ __('messages.select_phase') }}</option>';

                        phases.forEach(phase => {
                            const option = document.createElement('option');
                            option.value = phase.id;
                            option.textContent = phase.title || phase.name;
                            phaseSelect.appendChild(option);
                        });


                    }
                }
            } catch (error) {
                console.error('Error loading phases:', error);
            }
        }

        function displaySnags(snags) {
            const container = document.getElementById('snagsContainer');

            if (snags.length === 0) {
                container.style.display = 'flex';
                container.style.justifyContent = 'center';
                container.style.alignItems = 'center';
                container.style.minHeight = '400px';
                container.innerHTML = `
                    <div class="text-center text-muted">
                        <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                        <h5>{{ __('messages.no_snags_found') }}</h5>
                        <p>{{ __('messages.create_first_snag') }}</p>
                    </div>
                `;
                return;
            }

            // Reset container styles for grid layout
            container.style.display = '';
            container.style.justifyContent = '';
            container.style.alignItems = '';
            container.style.minHeight = '';

            container.innerHTML = snags.map(snag => {
                const statusBadge = getStatusBadge(snag.status);
                const imageDisplay = snag.image_urls && snag.image_urls.length > 0 ?
                    `<img src="${snag.image_urls[0]}" alt="Snag" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px;">` :
                    `<span class="stat-icon bg2 ms-0"><i class="fas fa-exclamation-triangle" style="color: #F58D2E;"></i></span>`;

                return `
                    <div class="CustOm_Card wow fadeInUp">
                        <div class="carD-details p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-start gap-3 flex-grow-1" style="min-width: 0;">
                                    ${imageDisplay}
                                    <div class="flex-grow-1" style="min-width: 0;">
                                        <h5 class="mb-2 fw-semibold" style="word-wrap: break-word; overflow-wrap: break-word; margin-right: 10px;">${snag.snag_number} - ${snag.title}</h5>
                                        <div class="d-flex gap-2 mb-0">
                                            ${statusBadge}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-2">
                                    <a href="#" class="text-secondary" title="View Details" onclick="viewSnagDetails(${snag.id})">
                                        <i class="fas fa-eye fa-lg"></i>
                                    </a>
                                </div>
                            </div>
                            <p class="mb-3 text-muted">${snag.description || 'No description provided'}</p>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="fas fa-user me-1"></i> ${snag.reported_by}</span>
                                <span><i class="fas fa-calendar-alt me-1"></i> ${snag.date}</span>
                                <span><i class="fas fa-map-marker-alt me-1"></i> ${snag.location}</span>
                                ${snag.assigned_to ? `<span><i class="fas fa-user-check me-1"></i> ${snag.assigned_to}</span>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getStatusBadge(status) {
            const statusMap = {
                'todo': {
                    class: 'badge5'
                },
                'in_progress': {
                    class: 'badge4'
                },
                'complete': {
                    class: 'badge1'
                },
                'approve': {
                    class: 'badge1'
                }
            };

            const statusInfo = statusMap[status.toLowerCase()] || statusMap['todo'];
            return `<span class="badge ${statusInfo.class}">${status}</span>`;
        }

        function setupFilters() {
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');

            if (statusFilter) statusFilter.addEventListener('change', filterSnags);
            if (searchInput) searchInput.addEventListener('input', filterSnags);
        }

        function filterSnags() {
            const statusValue = document.getElementById('statusFilter').value;
            const searchValue = document.getElementById('searchInput').value.toLowerCase();

            const filtered = allSnags.filter(snag => {
                const matchesStatus = statusValue === 'all' || snag.status.toLowerCase() === statusValue;
                const matchesSearch = !searchValue ||
                    snag.title.toLowerCase().includes(searchValue) ||
                    snag.description.toLowerCase().includes(searchValue) ||
                    snag.location.toLowerCase().includes(searchValue);

                return matchesStatus && matchesSearch;
            });

            displaySnags(filtered);
        }

        function setupAddSnagForm() {
            const addSnagForm = document.getElementById('addSnagForm');
            if (addSnagForm) {
                addSnagForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const fileInput = document.getElementById('snagPhotos');

                    if (fileInput.files && fileInput.files.length > 0) {
                        // Store files and open drawing modal
                        window.selectedFiles = fileInput.files;

                        openDrawingModal({
                            title: 'Image Markup',
                            saveButtonText: 'Save Snag',
                            mode: 'image',
                            onSave: function(imageData) {
                                saveSnagWithMarkup(imageData);
                            }
                        });

                        // Load images after modal is shown
                        document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
                            if (window.selectedFiles.length === 1) {
                                loadImageToCanvas(window.selectedFiles[0]);
                            } else {
                                loadMultipleFiles(window.selectedFiles);
                            }
                        }, {
                            once: true
                        });
                    } else {
                        // No images, direct API call
                        saveSnagWithoutMarkup();
                    }
                });
            }
        }

        async function saveSnagWithMarkup(imageData) {
            try {
                const formData = new FormData();

                formData.append('user_id', {{ auth()->id() ?? 1 }});
                formData.append('project_id', getProjectIdFromUrl());
                formData.append('title', document.getElementById('snagTitle').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('location', document.getElementById('location').value);

                const phaseId = document.getElementById('phaseSelect').value;
                if (phaseId) {
                    formData.append('phase_id', phaseId);
                }

                const assignedTo = document.getElementById('assignedTo').value;
                if (assignedTo) {
                    formData.append('assigned_to', assignedTo);
                }

                // Convert markup to blob and append
                if (Array.isArray(imageData)) {
                    imageData.forEach((data, index) => {
                        if (typeof data === 'string') {
                            const blob = dataURLtoBlob(data);
                            formData.append('images[]', blob, `markup_${index}.png`);
                        } else if (data instanceof File) {
                            formData.append('images[]', data, data.name);
                        }
                    });
                } else {
                    const blob = dataURLtoBlob(imageData);
                    formData.append('images[]', blob, 'markup.png');
                }

                const response = await api.createSnag(formData);

                if (response.code === 200) {
                    // Close modals
                    const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
                    if (drawingModal) drawingModal.hide();

                    const addSnagModal = bootstrap.Modal.getInstance(document.getElementById('addSnagModal'));
                    if (addSnagModal) addSnagModal.hide();

                    toastr.success('Snag with markup saved successfully!');
                    document.getElementById('addSnagForm').reset();
                    loadSnags();
                } else {
                    toastr.error('Failed to create snag: ' + response.message);
                }
            } catch (error) {
                console.error('Error creating snag:', error);
                toastr.error('Failed to create snag');
            }
        }

        async function saveSnagWithoutMarkup() {
            try {
                const createBtn = document.getElementById('createSnagBtn');
                const originalText = createBtn.innerHTML;
                createBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
                createBtn.disabled = true;

                const formData = new FormData();

                formData.append('user_id', {{ auth()->id() ?? 1 }});
                formData.append('project_id', getProjectIdFromUrl());
                formData.append('title', document.getElementById('snagTitle').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('location', document.getElementById('location').value);

                const phaseId = document.getElementById('phaseSelect').value;
                if (phaseId) {
                    formData.append('phase_id', phaseId);
                }

                const assignedTo = document.getElementById('assignedTo').value;
                if (assignedTo) {
                    formData.append('assigned_to', assignedTo);
                }

                const response = await api.createSnag(formData);

                if (response.code === 200) {
                    const addSnagModal = bootstrap.Modal.getInstance(document.getElementById('addSnagModal'));
                    if (addSnagModal) addSnagModal.hide();

                    toastr.success('Snag created successfully!');
                    document.getElementById('addSnagForm').reset();
                    loadSnags();
                } else {
                    toastr.error('Failed to create snag: ' + response.message);
                }
            } catch (error) {
                console.error('Error creating snag:', error);
                toastr.error('Failed to create snag');
            } finally {
                const createBtn = document.getElementById('createSnagBtn');
                createBtn.innerHTML = '<i class="fas fa-save me-2"></i>{{ __('messages.create_snag') }}';
                createBtn.disabled = false;
            }
        }

        function dataURLtoBlob(dataURL) {
            // If it's already a File object, return it as is
            if (dataURL instanceof File) {
                return dataURL;
            }

            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);
            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }
            return new Blob([u8arr], {
                type: mime
            });
        }

        async function viewSnagDetails(snagId) {
            try {
                const modal = new bootstrap.Modal(document.getElementById('snagDetailsModal'));
                modal.show();

                const response = await api.getSnagDetails({
                    snag_id: snagId,
                    user_id: {{ auth()->id() ?? 1 }}
                });

                if (response.code === 200) {
                    displaySnagDetails(response.data);
                } else {
                    document.getElementById('snagDetailsContent').innerHTML = `
                        <div class="text-center text-danger py-4">
                            <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                            <p>Failed to load snag details</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading snag details:', error);
            }
        }

        function displaySnagDetails(snag) {
            const currentUserId = {{ auth()->id() ?? 1 }};
            const isAssignedUser = snag.assigned_to_id && parseInt(snag.assigned_to_id) === parseInt(currentUserId);
            const isCompleted = snag.status.toLowerCase() === 'complete';
            const isApproved = snag.status.toLowerCase() === 'approve';
            const canComment = !isApproved;
            const hasCommented = snag.has_comment || false;

            const imagesHtml = snag.image_urls && snag.image_urls.length > 0 ?
                `<div class="card B_shadow h-100">
                     <div class="card-body">
                         <h6 class="fw-semibold black_color mb-3"><i class="fas fa-images orange_color me-2"></i>{{ __('messages.images') }}</h6>
                         <div class="row g-2">
                           ${snag.image_urls.map(url => `
                                 <div class="col-6">
                                   <img src="${url}" alt="Snag" class="img-fluid rounded cursor-pointer" style="height: 120px; width: 100%; object-fit: cover;" onclick="window.open('${url}', '_blank')">
                                 </div>
                               `).join('')}
                         </div>
                     </div>
                   </div>` :
                `<div class="card B_shadow h-100">
                     <div class="card-body text-center">
                         <i class="fas fa-image fa-3x text-muted mb-3"></i>
                         <p class="text-muted mb-0">{{ __('messages.no_images_uploaded') }}</p>
                     </div>
                   </div>`;

            document.getElementById('snagDetailsContent').innerHTML = `
                <!-- Snag Header -->
                <div class="card B_shadow mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-semibold black_color mb-2">${snag.snag_number} - ${snag.title}</h5>
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="badge ${getStatusBadgeClass(snag.status)}">${snag.status}</span>
                                    <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i>${snag.date}</small>
                                    <small class="text-muted"><i class="fas fa-user me-1"></i>${snag.reported_by}</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <select class="form-select mb-2" id="snagStatusSelect" onchange="changeSnagStatus()" ${isApproved || !isAssignedUser ? 'disabled' : ''}>
                                    <option value="todo">{{ __('messages.todo') }}</option>
                                    <option value="in_progress">{{ __('messages.in_progress') }}</option>
                                    <option value="complete">{{ __('messages.complete') }}</option>
                                    ${isApproved ? '<option value="approve">{{ __('messages.approve') }}</option>' : ''}
                                </select>
                                ${isCompleted && isAssignedUser && !isApproved ? `
                                        <button class="btn btn-success" onclick="resolveSnag(${snag.id})">
                                            <i class="fas fa-check me-2"></i>{{ __('messages.mark_resolved') }}
                                        </button>
                                    ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Snag Details -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card B_shadow h-100">
                            <div class="card-body">
                                <h6 class="fw-semibold black_color mb-3"><i class="fas fa-info-circle orange_color me-2"></i>{{ __('messages.details') }}</h6>
                                <div class="mb-3">
                                    <label class="small_tXt fw-medium">{{ __('messages.location') }}</label>
                                    <p class="mb-0">${snag.location}</p>
                                </div>
                                <div class="mb-3">
                                    <label class="small_tXt fw-medium">{{ __('messages.description') }}</label>
                                    <p class="mb-0">${snag.description || 'No description provided'}</p>
                                </div>
                                ${snag.assigned_to ? `
                                        <div class="mb-0">
                                            <label class="small_tXt fw-medium">{{ __('messages.assigned_to') }}</label>
                                            <p class="mb-0"><i class="fas fa-user-check me-1 text-primary"></i>${snag.assigned_to}</p>
                                        </div>
                                    ` : ''}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        ${imagesHtml}
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="card B_shadow mt-4">
                    <div class="card-body">
                        <h6 class="fw-semibold black_color mb-3">
                            <i class="fas fa-comments orange_color me-2"></i>{{ __('messages.comments') }}
                            ${snag.comment ? `<span class="badge bg-light text-dark ms-2">1</span>` : ''}
                        </h6>
                        
                        ${snag.comment ? `
                                <div class="comment-item border rounded p-3 mb-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <small class="fw-medium black_color">{{ __('messages.comment') }}</small>
                                                <br>
                                                <small class="text-muted">${snag.date}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mb-0 ms-5">${snag.comment}</p>
                                </div>
                            ` : `
                                <div class="text-center py-4">
                                    <i class="fas fa-comment-slash fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">{{ __('messages.no_comments_yet') }}</p>
                                </div>
                            `}
                        
                        ${canComment && !hasCommented && isAssignedUser ? `
                                <div class="mt-4 pt-3 border-top" id="commentSection">
                                    <label class="fw-medium mb-2 black_color">{{ __('messages.add_comment') }}</label>
                                    <textarea class="form-control mb-3" id="commentText" rows="3" placeholder="{{ __('messages.enter_comment') }}"></textarea>
                                    <button class="btn orange_btn" onclick="addComment(${snag.id})">
                                        <i class="fas fa-paper-plane me-2"></i>{{ __('messages.add_comment') }}
                                    </button>
                                </div>
                            ` : ''}
                        ${!isAssignedUser && !isApproved ? `
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>{{ __('messages.only_assigned_user_can_modify') }}
                                </div>
                            ` : ''}
                    </div>
                </div>
            `;

            setTimeout(() => {
                const statusSelect = document.getElementById('snagStatusSelect');
                if (statusSelect) {
                    statusSelect.value = snag.status.toLowerCase();
                }
            }, 100);

            window.currentSnagDetails = snag;
        }

        function getStatusBadgeClass(status) {
            const statusMap = {
                'todo': 'badge5',
                'in_progress': 'badge4',
                'complete': 'badge1',
                'approve': 'badge1'
            };
            return statusMap[status.toLowerCase()] || 'badge5';
        }

        async function addComment(snagId) {
            const commentText = document.getElementById('commentText').value.trim();
            if (!commentText) {
                toastr.warning('{{ __('messages.please_enter_comment') }}');
                return;
            }

            try {
                const response = await api.updateSnag({
                    snag_id: snagId,
                    user_id: {{ auth()->id() ?? 1 }},
                    comment: commentText
                });

                if (response.code === 200) {
                    toastr.success('{{ __('messages.comment_added_success') }}');

                    // Refresh snag details to show new comment
                    const detailsResponse = await api.getSnagDetails({
                        snag_id: snagId,
                        user_id: {{ auth()->id() ?? 1 }}
                    });
                    if (detailsResponse.code === 200) {
                        displaySnagDetails(detailsResponse.data);
                    }

                    loadSnags();
                } else {
                    toastr.error('{{ __('messages.failed_add_comment') }}');
                }
            } catch (error) {
                console.error('Error adding comment:', error);
                toastr.error('{{ __('messages.failed_add_comment') }}');
            }
        }

        async function resolveSnag(snagId) {
            try {
                const response = await api.resolveSnag({
                    snag_id: snagId,
                    user_id: {{ auth()->id() ?? 1 }}
                });

                if (response.code === 200) {
                    toastr.success('{{ __('messages.snag_resolved_success') }}');
                    bootstrap.Modal.getInstance(document.getElementById('snagDetailsModal')).hide();
                    loadSnags();
                } else {
                    toastr.error('{{ __('messages.failed_resolve_snag') }}');
                }
            } catch (error) {
                console.error('Error resolving snag:', error);
                toastr.error('{{ __('messages.failed_resolve_snag') }}');
            }
        }

        function showError(message) {
            const container = document.getElementById('snagsContainer');
            container.style.display = 'flex';
            container.style.justifyContent = 'center';
            container.style.alignItems = 'center';
            container.style.minHeight = '400px';
            container.innerHTML = `
                <div class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                    <h5>Error</h5>
                    <p>${message}</p>
                </div>
            `;
        }


        async function changeSnagStatus() {
            if (!window.currentSnagDetails) return;

            const newStatus = document.getElementById('snagStatusSelect').value;

            try {
                const response = await api.updateSnag({
                    snag_id: window.currentSnagDetails.id,
                    user_id: {{ auth()->id() ?? 1 }},
                    status: newStatus
                });

                if (response.code === 200) {
                    window.currentSnagDetails.status = newStatus;
                    loadSnags();
                    toastr.success('{{ __('messages.snag_updated_successfully') }}');
                } else {
                    toastr.error(response.message || '{{ __('messages.failed_to_update_snag') }}');
                    document.getElementById('snagStatusSelect').value = window.currentSnagDetails.status;
                }
            } catch (error) {
                console.error('Error updating snag status:', error);
                toastr.error('{{ __('messages.error_updating_snag') }}');
                document.getElementById('snagStatusSelect').value = window.currentSnagDetails.status;
            }
        }
    </script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>

@endsection
