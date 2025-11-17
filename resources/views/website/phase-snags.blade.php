<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Snag List</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/toastr-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/custom-filter-dropdown.css') }}" />
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

        .row.align-items-end>div {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>

<body data-phase-id="{{ request()->get('phase_id', 1) }}">
    <div class="content_wraper F_poppins">
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-start">
                    <div class="col-12">
                        <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <button class="btn btn-outline-primary" onclick="history.back()">
                                    <i class="fas {{ is_rtl() ? 'fa-arrow-right' : 'fa-arrow-left' }}"></i>
                                </button>
                                <div>
                                    <h4 class="mb-1">{{ __('messages.snag_list') }}</h4>
                                    <p class="text-muted small mb-0">{{ __('messages.view_manage_snags') }}</p>
                                </div>
                            </div>
                            @can('snags', 'create')
                                <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addSnagModal"
                                    onclick="if(!this.disabled){this.disabled=true;setTimeout(()=>{this.disabled=false;},3000);}">
                                    <i class="fas fa-plus"></i>
                                    {{ __('messages.add_new_snag') }}
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </header>
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
                                            <div class="custom-filter-btn" id="statusFilterBtn">
                                                {{ __('messages.all_status') }}</div>
                                            <div class="custom-filter-options" id="statusFilterOptions">
                                                <div class="custom-filter-option selected" data-value="all">
                                                    {{ __('messages.all_status') }}</div>
                                                <div class="custom-filter-option" data-value="todo">
                                                    {{ __('messages.todo') }}</div>
                                                <div class="custom-filter-option" data-value="complete">
                                                    {{ __('messages.complete') }}</div>
                                                <div class="custom-filter-option" data-value="approve">
                                                    {{ __('messages.approve') }}</div>
                                            </div>
                                            <select id="statusFilter" style="display: none;">
                                                <option value="all">{{ __('messages.all_status') }}</option>
                                                <option value="todo">{{ __('messages.todo') }}</option>
                                                <option value="complete">{{ __('messages.complete') }}</option>
                                                <option value="approve">{{ __('messages.approve') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 mt-3 mt-md-0">
                                        <label class="fw-medium mb-2">{{ __('messages.search') }}</label>
                                        <form class="serchBar position-relative serchBar2">
                                                <input class="form-control" type="search" id="searchInput"
                                                    placeholder="{{ __('messages.search_snags') }}"
                                                aria-label="{{ __('messages.search') }}"
                                                dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}" maxlength="100">
                                            <span class="search_icon"><img
                                                        src="{{ asset('website/images/icons/search.svg') }}"
                                                        alt="search"></span>
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

            function getCurrentPhaseId() {
                const urlParams = new URLSearchParams(window.location.search);
                const pagePhaseId = document.body.getAttribute('data-phase-id');
                return urlParams.get('phase_id') || pagePhaseId || sessionStorage.getItem('current_phase_id') || '1';
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadSnags();
                setupFilters();
                setupAddSnagForm();
                setupModalUserLoading();

                if (typeof initSearchableDropdowns === 'function') {
                    initSearchableDropdowns();
                }
                document.querySelectorAll('.searchable-select').forEach(el => el.classList.add('initialized'));
            });

            function getProjectIdFromUrl() {
                const pathParts = window.location.pathname.split('/');
                const projectIndex = pathParts.indexOf('project');
                return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
            }

            async function loadSnags() {
                try {
                    showLoading();
                    const projectId = getProjectIdFromUrl();
                    const phaseId = getCurrentPhaseId();
                    // console.log('Loading snags for project:', projectId, 'phase:', phaseId);

                    const requestData = {
                        project_id: projectId,
                        phase_id: phaseId
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

            function setupModalUserLoading() {
                // Setup snag details modal - hide approve button when modal opens
                const snagDetailsModal = document.getElementById('snagDetailsModal');
                if (snagDetailsModal) {
                    snagDetailsModal.addEventListener('show.bs.modal', function() {
                        // Hide approve button immediately when modal starts to open
                        const approveBtn = document.getElementById('approveSnagBtn');
                        if (approveBtn) {
                            approveBtn.style.display = 'none';
                        }
                    });
                }

                const addSnagModal = document.getElementById('addSnagModal');
                if (addSnagModal) {
                    addSnagModal.addEventListener('show.bs.modal', async function() {
                        // Hide phase dropdown since this is a phase page
                        const phaseContainer = document.getElementById('phaseSelectContainer');
                        const phaseSelect = document.getElementById('phaseSelect');
                        if (phaseContainer) {
                            phaseContainer.style.display = 'none';
                        }
                        if (phaseSelect) {
                            phaseSelect.removeAttribute('required');
                        }

                        try {
                            const response = await api.getAllUsers();
                            const assignedSelect = document.getElementById('assignedTo');

                            if (response.code === 200 && assignedSelect) {
                                assignedSelect.innerHTML =
                                    '<option value="">{{ __('messages.select_user') }}</option>';
                                response.data.forEach(user => {
                                    assignedSelect.innerHTML +=
                                        `<option value="${user.id}">${user.name}</option>`;
                                });

                                // Initialize searchable dropdown after loading options
                                setTimeout(() => {
                                    if (typeof SearchableDropdown !== 'undefined') {
                                        if (!assignedSelect.searchableDropdown) {
                                            assignedSelect.searchableDropdown = new SearchableDropdown(
                                                assignedSelect);
                                        } else {
                                            assignedSelect.searchableDropdown.updateOptions();
                                        }
                                    }
                                    // Re-setup form handlers after modal is shown (override modal script)
                                    setupAddSnagForm();
                                }, 100);
                            }
                        } catch (error) {
                            console.error('Error loading users:', error);
                        }
                    });

                    // Also setup when modal is fully shown
                    addSnagModal.addEventListener('shown.bs.modal', function() {
                        // Re-setup form handlers to ensure they work
                        setTimeout(() => {
                            setupAddSnagForm();
                        }, 50);
                    });
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

                const viewDetailsText = '{{ __('messages.view_details') }}';
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
                                        <h5 class="mb-2 fw-semibold" style="word-wrap: break-word; overflow-wrap: break-word;">${snag.snag_number} - ${snag.title}</h5>
                                        <div class="d-flex gap-2 mb-0">
                                            ${statusBadge}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-2">
                                    <a href="#" class="text-secondary" title="${viewDetailsText}" onclick="viewSnagDetails(${snag.id})">
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
                        class: 'badge3',
                        text: '{{ __('messages.todo') }}'
                    },
                    'complete': {
                        class: 'badge1',
                        text: '{{ __('messages.complete') }}'
                    },
                    'approve': {
                        class: 'badge1',
                        text: '{{ __('messages.approve') }}'
                    }
                };

                const statusInfo = statusMap[status.toLowerCase()] || statusMap['todo'];
                return `<span class="badge ${statusInfo.class}">${statusInfo.text}</span>`;
            }

            function getPriorityBadge(priority) {
                const priorityMap = {
                    'low': {
                        class: 'badge1',
                        text: '{{ __('messages.low') }}'
                    },
                    'medium': {
                        class: 'badge3',
                        text: '{{ __('messages.medium') }}'
                    },
                    'high': {
                        class: 'badge2',
                        text: '{{ __('messages.high') }}'
                    },
                    'critical': {
                        class: 'badge2',
                        text: '{{ __('messages.critical') }}'
                    }
                };

                const priorityInfo = priorityMap[priority] || priorityMap['medium'];
                return `<span class="badge ${priorityInfo.class}">${priorityInfo.text}</span>`;
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

                // Form submit handler (same as snag-list.blade.php)
                if (addSnagForm) {
                    // Use capture phase and stop immediately to override other handlers
                    addSnagForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        processSnagForm();
                        return false;
                    }, true); // Use capture phase
                }

                // Button click handler - directly process form (override modal script)
                const createBtn = document.getElementById('createSnagBtn');
                if (createBtn) {
                    // Remove any existing onclick
                    createBtn.onclick = null;

                    // Add our handler with capture to run first
                    createBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        processSnagForm();
                        return false;
                    }, true); // Use capture phase
                }
            }

            function processSnagForm() {
                // Validate form first
                if (typeof validateSnagForm === 'function') {
                    if (!validateSnagForm()) {
                        return false;
                    }
                }

                const fileInput = document.getElementById('snagPhotos');

                if (fileInput && fileInput.files && fileInput.files.length > 0) {
                    // Store files and open drawing modal
                    window.selectedFiles = fileInput.files;

                    if (typeof openDrawingModal === 'function') {
                        openDrawingModal({
                            title: 'Image Markup',
                            saveButtonText: 'Save Snag',
                            mode: 'image',
                            onSave: function(imageData) {
                                saveSnagWithMarkup(imageData);
                            }
                        });

                        // Load images after modal is shown
                        const drawingModal = document.getElementById('drawingModal');
                        if (drawingModal) {
                            drawingModal.addEventListener('shown.bs.modal', function() {
                                if (window.selectedFiles.length === 1) {
                                    if (typeof loadImageToCanvas === 'function') {
                                        loadImageToCanvas(window.selectedFiles[0]);
                                    }
                                } else {
                                    if (typeof loadMultipleFiles === 'function') {
                                        loadMultipleFiles(window.selectedFiles);
                                    }
                                }
                            }, {
                                once: true
                            });
                        }
                    } else {
                        console.error('openDrawingModal function not found');
                        toastr.error('Drawing modal not available');
                    }
                } else {
                    // No images, direct API call
                    saveSnagWithoutMarkup();
                }
            }

            // Reset form submission function
            function resetFormSubmission(formId) {
                const form = document.getElementById(formId);
                if (form) {
                    // Find the submit button
                    const submitBtn = form.querySelector('button[type="submit"]') || document.getElementById('createSnagBtn');
                    if (submitBtn) {
                        // Use the global releaseButton function if available
                        if (typeof window.releaseButton === 'function') {
                            window.releaseButton(submitBtn);
                        } else {
                            // Fallback: manually reset button
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '{{ __('messages.create_snag') }}';
                        }
                    }
                }
            }

            async function saveSnagWithMarkup(imageData) {
                const createBtn = document.getElementById('createSnagBtn');
                try {
                    const formData = new FormData();

                    formData.append('user_id', {{ auth()->id() ?? 1 }});
                    formData.append('project_id', getProjectIdFromUrl());
                    formData.append('phase_id', getCurrentPhaseId());
                    formData.append('title', document.getElementById('snagTitle').value);
                    formData.append('description', document.getElementById('description').value);
                    formData.append('location', document.getElementById('location').value);

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

                        // Reset button state
                        if (createBtn) {
                            createBtn.disabled = false;
                            createBtn.innerHTML = '{{ __('messages.create_snag') }}';
                        }
                    } else {
                        // Reset button state on error
                        if (createBtn) {
                            createBtn.disabled = false;
                            createBtn.innerHTML = '{{ __('messages.create_snag') }}';
                        }
                        toastr.error('Failed to create snag: ' + (response.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error creating snag:', error);
                    // Reset button state on error
                    if (createBtn) {
                        createBtn.disabled = false;
                        createBtn.innerHTML = '{{ __('messages.create_snag') }}';
                    }
                    toastr.error('Failed to create snag: ' + (error.message || 'Unknown error'));
                }
            }

            async function saveSnagWithoutMarkup() {
                const createBtn = document.getElementById('createSnagBtn');
                try {
                    const formData = new FormData();

                    formData.append('user_id', {{ auth()->id() ?? 1 }});
                    formData.append('project_id', getProjectIdFromUrl());
                    formData.append('phase_id', getCurrentPhaseId());
                    formData.append('title', document.getElementById('snagTitle').value);
                    formData.append('description', document.getElementById('description').value);
                    formData.append('location', document.getElementById('location').value);

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

                        // Reset button state
                        if (createBtn) {
                            createBtn.disabled = false;
                            createBtn.innerHTML = '{{ __('messages.create_snag') }}';
                        }
                    } else {
                        // Reset button state on error
                        if (createBtn) {
                            createBtn.disabled = false;
                            createBtn.innerHTML = '{{ __('messages.create_snag') }}';
                        }
                        toastr.error('Failed to create snag: ' + (response.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error creating snag:', error);
                    // Reset button state on error
                    if (createBtn) {
                        createBtn.disabled = false;
                        createBtn.innerHTML = '{{ __('messages.create_snag') }}';
                    }
                    toastr.error('Failed to create snag: ' + (error.message || 'Unknown error'));
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

                    console.log('=== SNAG DETAILS API RESPONSE ===');
                    console.log('Response:', response);
                    console.log('Response Data:', response.data);
                    console.log('Comments in Response:', response.data ? response.data.comments : 'No data');
                    console.log('===============================');

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
                // Hide approve button immediately when modal opens
                const approveBtn = document.getElementById('approveSnagBtn');
                if (approveBtn) {
                    approveBtn.style.display = 'none';
                }

                const currentUserId = {{ auth()->id() ?? 1 }};
                const snagStatus = snag.status ? String(snag.status).toLowerCase().trim() : 'todo';
                const isAssignedUser = snag.assigned_to_id && parseInt(snag.assigned_to_id) === parseInt(currentUserId);
                const isCompleted = snagStatus === 'complete';
                const isApproved = snagStatus === 'approve';
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

                // Comments are now handled in the main template

                console.log('=== SNAG DETAILS DEBUG ===');
                console.log('Current User ID:', currentUserId);
                console.log('Snag Comment:', snag.comment);
                console.log('Has Comment:', snag.has_comment);
                console.log('Has Commented:', hasCommented);
                console.log('Can Comment:', canComment);
                console.log('========================');

                document.getElementById('snagDetailsContent').innerHTML = `
                <!-- Snag Header -->
                <div class="card B_shadow mb-4">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-semibold black_color mb-2">${snag.snag_number} - ${snag.title}</h5>
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="badge ${getStatusBadgeClass(snag.status)}">${getStatusText(snag.status)}</span>
                                    <small class="text-muted"><i class="fas fa-calendar-alt me-1"></i>${snag.date}</small>
                                    <small class="text-muted"><i class="fas fa-user me-1"></i>${snag.reported_by}</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <select class="form-select mb-2" id="snagStatusSelect" onchange="changeSnagStatus()" ${isApproved || !isAssignedUser ? 'disabled' : ''}>
                                    <option value="todo">{{ __('messages.todo') }}</option>
                                    <option value="complete">{{ __('messages.complete') }}</option>
                                    ${isApproved ? '<option value="approve">{{ __('messages.approve') }}</option>' : ''}
                                </select>
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
                                                        <button class="btn orange_btn api-action-btn" onclick="addComment(${snag.id})">
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
                    // Show/hide approve button based on status and permissions (same as tasks)
                    updateApproveButtonVisibility();
                }, 100);

                window.currentSnagDetails = snag;
            }

            function updateApproveButtonVisibility() {
                const approveBtn = document.getElementById('approveSnagBtn');
                if (!approveBtn) return;

                const snag = window.currentSnagDetails;
                if (!snag || !snag.status) {
                    approveBtn.style.display = 'none';
                    return;
                }

                const currentUserId = {{ auth()->id() ?? 1 }};
                const snagStatus = String(snag.status).toLowerCase().trim();
                const isAssignedUser = snag.assigned_to_id && parseInt(snag.assigned_to_id) === parseInt(currentUserId);
                const isCompleted = snagStatus === 'complete';
                const isApproved = snagStatus === 'approve';

                // Show button ONLY if: status is exactly 'complete', user is assigned, and not already approved
                if (isCompleted && isAssignedUser && !isApproved) {
                    approveBtn.style.display = 'inline-block';
                } else {
                    approveBtn.style.display = 'none';
                }
            }

            function getStatusBadgeClass(status) {
                const statusMap = {
                    'todo': 'badge3',
                    'complete': 'badge1',
                    'approve': 'badge1'
                };
                return statusMap[status.toLowerCase()] || 'badge3';
            }

            function getStatusText(status) {
                const statusTexts = {
                    'todo': '{{ __('messages.todo') }}',
                    'complete': '{{ __('messages.complete') }}',
                    'approve': '{{ __('messages.approve') }}'
                };
                return statusTexts[status.toLowerCase()] || status;
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
                const currentStatus = window.currentSnagDetails.status;

                // Frontend validation: prevent going backwards
                // If status is 'complete', cannot change to 'todo'
                if (currentStatus === 'complete' && newStatus === 'todo') {
                    toastr.error('{{ __('messages.cannot_change_to_todo_from_complete') }}');
                    document.getElementById('snagStatusSelect').value = currentStatus;
                    return;
                }

                // If status is 'approve', cannot change to 'todo' or 'complete'
                if (currentStatus === 'approve' && (newStatus === 'todo' || newStatus === 'complete')) {
                    toastr.error('{{ __('messages.cannot_change_from_approve') }}');
                    document.getElementById('snagStatusSelect').value = currentStatus;
                    return;
                }

                try {
                    const response = await api.updateSnag({
                        snag_id: window.currentSnagDetails.id,
                        user_id: {{ auth()->id() ?? 1 }},
                        status: newStatus
                    });

                    if (response.code === 200) {
                        window.currentSnagDetails.status = newStatus;
                        // Update status badge in modal
                        const statusBadge = document.querySelector('#snagDetailsModal .badge');
                        if (statusBadge) {
                            statusBadge.textContent = getStatusText(newStatus);
                            statusBadge.className = `badge ${getStatusBadgeClass(newStatus)}`;
                        }
                        // Update approve button visibility based on new status
                        updateApproveButtonVisibility();
                        loadSnags();
                        toastr.success(response.message || '{{ __('messages.snag_updated_successfully') }}');
                    } else {
                        // Show backend error message in toastr
                        const errorMessage = response.message || '{{ __('messages.failed_to_update_snag') }}';
                        toastr.error(errorMessage);
                        document.getElementById('snagStatusSelect').value = window.currentSnagDetails.status;
                    }
                } catch (error) {
                    console.error('Error updating snag status:', error);
                    toastr.error(error.message || '{{ __('messages.error_updating_snag') }}');
                    document.getElementById('snagStatusSelect').value = window.currentSnagDetails.status;
                }
            }

            async function approveSnag() {
                const snag = window.currentSnagDetails;
                if (!snag || !snag.id) {
                    toastr.error('{{ __('messages.snag_not_found') }}');
                    return;
                }

                try {
                    const response = await api.approveSnag({
                        snag_id: snag.id,
                        user_id: {{ auth()->id() ?? 1 }}
                    });

                    if (response.code === 200) {
                        // Update current snag details
                        if (window.currentSnagDetails) {
                            window.currentSnagDetails.status = 'approve';
                        }

                        // Update status badge in modal
                        const statusBadge = document.querySelector('#snagDetailsModal .badge');
                        if (statusBadge) {
                            statusBadge.textContent = getStatusText('approve');
                            statusBadge.className = `badge ${getStatusBadgeClass('approve')}`;
                        }

                        // Update status select
                        const statusSelect = document.getElementById('snagStatusSelect');
                        if (statusSelect) {
                            statusSelect.value = 'approve';
                            statusSelect.disabled = true;
                        }

                        // Hide approve button
                        updateApproveButtonVisibility();

                        // Reload snags list
                        loadSnags();

                        // Show success message
                        toastr.success(response.message || '{{ __('messages.snag_approved_successfully') }}');
                    } else {
                        toastr.error(response.message || '{{ __('messages.failed_to_approve_snag') }}');
                    }
                } catch (error) {
                    console.error('Error approving snag:', error);
                    toastr.error(error.message || '{{ __('messages.error_approving_snag') }}');
                }
            }
        </script>

        <!-- Load API scripts first -->
        <script src="{{ asset('website/js/api-config.js') }}"></script>
        <script src="{{ asset('website/js/api-encryption.js') }}"></script>
        <script src="{{ asset('website/js/universal-auth.js') }}"></script>
        <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
        <script src="{{ asset('website/js/api-client.js') }}"></script>
        <script src="{{ asset('website/js/drawing.js') }}"></script>
        <script src="{{ asset('website/js/searchable-dropdown.js') }}"></script>
        <script src="{{ asset('website/js/custom-filter-dropdown.js') }}"></script>
        <script src="{{ asset('website/js/button-protection.js') }}"></script>

    </div>
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById('addSnagModal');
            if (!modal) {
                console.warn("addSnagModal not found.");
                return;
            }

            modal.addEventListener('shown.bs.modal', async function() {
                const select = document.getElementById('assignedTo');
                if (!select) {
                    console.warn("assignedTo select not found inside modal.");
                    return;
                }

                // Prevent reloading if already populated
                if (select.options.length > 1) return;

                try {
                    const response = await api.getAllUsers();
                    if (response.code === 200 && Array.isArray(response.data)) {
                        select.innerHTML =
                            '<option value="">{{ __('messages.select_user') }}</option>';
                        response.data.forEach(user => {
                            const opt = document.createElement("option");
                            opt.value = user.id;
                            opt.textContent = user.name;
                            select.appendChild(opt);
                        });

                        // Initialize SearchableDropdown safely
                        if (window.SearchableDropdown) {
                            if (!select.searchableDropdown) {
                                select.searchableDropdown = new SearchableDropdown(select);
                            } else {
                                select.searchableDropdown.updateOptions();
                            }
                        }

                        // Smooth fade-in effect
                        select.classList.add("initialized");
                    } else {
                        console.warn("User API returned invalid data", response);
                    }
                } catch (error) {
                    console.error("Error loading users:", error);
                }
            });
        });
    </script>

</body>

</html>
