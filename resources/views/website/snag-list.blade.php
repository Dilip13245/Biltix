@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Snag List')

@section('content')
    <div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2>{{ __('messages.snag_list') }}</h2>
            <p>{{ __('messages.view_manage_snags') }}</p>
        </div>
        @can('snags', 'create')
            <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addSnagModal"
                data-permission="snags:create">
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
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                                    <label class="fw-medium mb-2">{{ __('messages.status') }}</label>
                                    <select class="form-select w-100" id="statusFilter">
                                        <option value="">{{ __('messages.all_status') }}</option>
                                        <option value="open">{{ __('messages.open') }}</option>
                                        <option value="assigned">{{ __('messages.assigned') }}</option>
                                        <option value="in_progress">{{ __('messages.in_progress') }}</option>
                                        <option value="resolved">{{ __('messages.resolved') }}</option>
                                        <option value="closed">{{ __('messages.closed') }}</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                                    <label class="fw-medium mb-2">{{ __('messages.category') }}</label>
                                    <select class="form-select w-100" id="categoryFilter">
                                        <option value="">{{ __('messages.all_categories') }}</option>
                                        <option value="electrical">{{ __('messages.electrical') }}</option>
                                        <option value="mechanical">{{ __('messages.mechanical') }}</option>
                                        <option value="plumbing">{{ __('messages.plumbing') }}</option>
                                        <option value="structural">{{ __('messages.structural') }}</option>
                                        <option value="finishing">{{ __('messages.finishing') }}</option>
                                        <option value="safety">{{ __('messages.safety') }}</option>
                                        <option value="hvac">{{ __('messages.hvac') }}</option>
                                        <option value="other">{{ __('messages.other') }}</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mt-3 mt-md-0">
                                    <label class="fw-medium mb-2">{{ __('messages.search') }}</label>
                                    <form class="serchBar position-relative serchBar2">
                                        <input class="form-control" type="search" id="searchInput"
                                            placeholder="{{ __('messages.search_snags') }}" aria-label="Search">
                                        <span class="search_icon"><img src="{{ asset('website/images/icons/search.svg') }}"
                                                alt="search"></span>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-4">
                    <div id="snagsContainer">
                        <div class="col-12 text-center py-4">
                            <div class="spinner-border" role="status"></div>
                            <div class="mt-2">{{ __('messages.loading') }}...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('website.modals.add-snag-modal')
    @include('website.modals.edit-snag-modal')
    @include('website.modals.drawing-modal')

    <script>
        // Store all snags for filtering
        let allSnags = [];
        let filteredSnags = [];

        // Load snags from API
        async function loadSnags() {
            try {
                const projectId = getProjectIdFromUrl();
                const response = await api.getSnags({
                    project_id: projectId
                });

                if (response.code === 200 && response.data) {
                    // Handle different response formats
                    let snags = response.data;
                    if (response.data.data) {
                        snags = response.data.data;
                    }
                    if (!Array.isArray(snags)) {
                        snags = [];
                    }
                    allSnags = snags;
                    applyFilters();
                } else {
                    allSnags = [];
                    displayNoSnags();
                }
            } catch (error) {
                console.error('Failed to load snags:', error);
                allSnags = [];
                displayNoSnags();
            }
        }

        // Apply all filters and search
        function applyFilters() {
            const statusFilter = document.getElementById('statusFilter')?.value || '';
            const categoryFilter = document.getElementById('categoryFilter')?.value || '';

            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';

            filteredSnags = allSnags.filter(snag => {
                // Status filter
                if (statusFilter && snag.status?.toLowerCase().replace(/\s+/g, '_') !== statusFilter
                    .toLowerCase()) {
                    return false;
                }

                // Category filter
                if (categoryFilter && snag.category?.toLowerCase() !== categoryFilter.toLowerCase()) {
                    return false;
                }



                // Search filter
                if (searchTerm) {
                    const title = (snag.title || '').toLowerCase();
                    const description = (snag.description || '').toLowerCase();
                    const location = (snag.location || '').toLowerCase();
                    const createdBy = (snag.created_by || '').toLowerCase();

                    if (!title.includes(searchTerm) &&
                        !description.includes(searchTerm) &&
                        !location.includes(searchTerm) &&
                        !createdBy.includes(searchTerm)) {
                        return false;
                    }
                }

                return true;
            });

            displaySnags(filteredSnags);
        }

        function displaySnags(snags) {
            const container = document.getElementById('snagsContainer');

            if (!snags || snags.length === 0) {
                displayNoSnags();
                return;
            }

            // Reset container for grid display
            container.className = 'CarDs-grid';
            container.style.minHeight = 'auto';

            container.innerHTML = snags.map((snag, index) => {
                const priorityClass = getPriorityClass(snag.priority);
                const statusClass = getStatusClass(snag.status);
                const icon = getSnagIcon(snag.category || snag.type);
                const priorityText = (snag.priority || 'medium').charAt(0).toUpperCase() + (snag.priority ||
                    'medium').slice(1);
                const statusText = (snag.status || 'open').replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());

                return `
                    <div class="CustOm_Card wow fadeInUp" data-wow-delay="${index * 0.2}s">
                        <div class="carD-details p-4">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-start gap-3">
                                    <span class="stat-icon bg2 ms-0">
                                        ${icon}
                                    </span>
                                    <div>
                                        <h5 class="mb-2 fw-semibold">${snag.title || snag.description}</h5>
                                        <div class="d-flex gap-2 mb-2">
                                            <span class="badge ${priorityClass}">${priorityText}</span>
                                            <span class="badge ${statusClass}">${statusText}</span>
                                        </div>
                                    </div>
                                </div>
                                ${window.userPermissions && window.userPermissions.canUpdate('snags') ? 
                                    `<a href="#" class="text-secondary" title="Edit" onclick="editSnag(${snag.id})"><i class="fas fa-pen-to-square fa-lg"></i></a>` : 
                                    ''}
                            </div>
                            <p class="mb-3 text-muted">${snag.description || 'No description provided'}</p>
                            <div class="d-flex flex-wrap gap-3 text-muted small">
                                <span><i class="fas fa-user me-1"></i> ${snag.created_by || 'Unknown'}</span>
                                <span><i class="fas fa-calendar-alt me-1"></i> ${formatDate(snag.created_at)}</span>
                                <span><i class="fas fa-building me-1"></i> ${snag.location || 'No location'}</span>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function displayNoSnags() {
            const container = document.getElementById('snagsContainer');
            container.className = 'd-flex justify-content-center align-items-center';
            container.style.minHeight = '400px';
            container.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted mb-2">{{ __('messages.no_snags_found') }}</h5>
                    <p class="text-muted">{{ __('messages.add_new_snag') }}</p>
                </div>
            `;
        }

        function getPriorityClass(priority) {
            switch (priority?.toLowerCase()) {
                case 'high':
                case 'urgent':
                    return 'badge2';
                case 'medium':
                    return 'badge3';
                case 'low':
                    return 'badge1';
                default:
                    return 'badge3';
            }
        }

        function getStatusClass(status) {
            switch (status?.toLowerCase()) {
                case 'open':
                    return 'badge5';
                case 'assigned':
                    return 'badge3';
                case 'in_progress':
                case 'in progress':
                    return 'badge4';
                case 'resolved':
                    return 'badge2';
                case 'closed':
                    return 'badge1';
                default:
                    return 'badge5';
            }
        }

        function getSnagIcon(category) {
            switch (category?.toLowerCase()) {
                case 'electrical':
                    return `<svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.4091 1.81829C11.6165 1.33665 11.4618 0.774146 11.0364 0.464771C10.611 0.155396 10.0309 0.183521 9.63368 0.528052L0.633684 8.40305C0.282121 8.71243 0.155559 9.20813 0.320793 9.64407C0.486028 10.08 0.907903 10.3753 1.37548 10.3753H5.2954L2.59189 16.6823C2.38446 17.164 2.53915 17.7265 2.96454 18.0359C3.38993 18.3452 3.97001 18.3171 4.36728 17.9726L13.3673 10.0976C13.7188 9.78821 13.8454 9.29251 13.6802 8.85657C13.5149 8.42063 13.0966 8.12883 12.6255 8.12883H8.70556L11.4091 1.81829Z" fill="#F58D2E" /></svg>`;
                case 'plumbing':
                    return `<svg width="14" height="19" viewBox="0 0 14 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7 18.25C3.27344 18.25 0.25 15.2266 0.25 11.5C0.25 8.29375 4.82734 2.27852 6.10703 0.661328C6.31797 0.397656 6.63086 0.25 6.96836 0.25H7.03164C7.36914 0.25 7.68203 0.397656 7.89297 0.661328C9.17266 2.27852 13.75 8.29375 13.75 11.5C13.75 15.2266 10.7266 18.25 7 18.25ZM3.625 12.0625C3.625 11.7531 3.37188 11.5 3.0625 11.5C2.75312 11.5 2.5 11.7531 2.5 12.0625C2.5 14.2387 4.26133 16 6.4375 16C6.74687 16 7 15.7469 7 15.4375C7 15.1281 6.74687 14.875 6.4375 14.875C4.88359 14.875 3.625 13.6164 3.625 12.0625Z" fill="#F58D2E" /></svg>`;
                default:
                    return `<svg width="22" height="19" viewBox="0 0 22 19" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.4121 8.59961C14.4207 8.76836 13.366 8.47305 12.5996 7.70664L11.2602 6.36719C10.7328 5.83984 10.4375 5.12969 10.4375 4.38438V3.95898L7.63555 2.42969C7.44922 2.32773 7.3332 2.12734 7.34375 1.91289C7.3543 1.69844 7.48086 1.50859 7.67773 1.4207L9.33711 0.682422C9.98398 0.397656 10.6836 0.25 11.3938 0.25H12.0301C13.3203 0.25 14.5613 0.742187 15.5 1.62461L17.068 3.10117C17.9188 3.90273 18.2352 5.05938 18.0031 6.12461L18.5586 6.68359L18.8398 6.40234C19.1703 6.07188 19.7047 6.07188 20.0316 6.40234L20.8754 7.24609C21.2059 7.57656 21.2059 8.11094 20.8754 8.43789L17.7816 11.5316C17.4512 11.8621 16.9168 11.8621 16.5898 11.5316L15.7461 10.6879C15.4156 10.3574 15.4156 9.82305 15.7461 9.49609L16.0273 9.21484L15.4121 8.59961ZM1.83828 13.5074L10.0473 6.66953C10.1703 6.8418 10.3109 7.00703 10.4621 7.16172L11.8016 8.50117C12.0125 8.71211 12.2375 8.89492 12.4766 9.05312L5.61758 17.2867C5.10781 17.8984 4.35195 18.25 3.55742 18.25C2.07383 18.25 0.875 17.0477 0.875 15.5676C0.875 14.773 1.23008 14.0172 1.83828 13.5074Z" fill="#F58D2E" /></svg>`;
            }
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }

        let snagCategories = [];

        // Load categories from API
        async function loadSnagCategories() {
            try {
                const response = await api.getSnagCategories();
                if (response.code === 200 && response.data) {
                    snagCategories = response.data;
                    populateCategoryDropdown();
                }
            } catch (error) {
                console.error('Failed to load categories:', error);
            }
        }

        function populateCategoryDropdown() {
            const select = document.getElementById('editIssueType');
            if (select && snagCategories.length > 0) {
                // Keep the default option
                const defaultOption = select.querySelector('option[value=""]');
                select.innerHTML = '';
                if (defaultOption) select.appendChild(defaultOption);

                // Add categories from API
                snagCategories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.name.toLowerCase();
                    option.textContent = category.name;
                    select.appendChild(option);
                });
            }
        }

        function getCategoryIdByName(categoryName) {
            const category = snagCategories.find(cat => cat.name.toLowerCase() === categoryName.toLowerCase());
            return category ? category.id : null;
        }

        // Edit snag functionality
        async function editSnag(snagId) {
            try {
                // Find snag data from allSnags array
                const snag = allSnags.find(s => s.id == snagId);
                if (!snag) {
                    toastr.error('Snag not found');
                    return;
                }

                console.log('Snag data:', snag);
                console.log('Category field:', snag.category);
                console.log('Category_id field:', snag.category_id);
                console.log('All snag fields:', Object.keys(snag));

                // Ensure categories are loaded first
                if (snagCategories.length === 0) {
                    await loadSnagCategories();
                }

                // Show modal
                const editModal = new bootstrap.Modal(document.getElementById('editSnagModal'));
                editModal.show();

                // Populate after modal and categories are ready
                setTimeout(() => {
                    populateCategoryDropdown();

                    document.getElementById('editSnagId').value = snag.id;
                    document.getElementById('editIssueType').value = snag.category || '';
                    document.getElementById('editDescription').value = snag.description || '';
                    document.getElementById('editLocation').value = snag.location || '';
                    document.getElementById('editPriority').value = snag.priority?.toLowerCase() || 'medium';
                    document.getElementById('editStatus').value = snag.status?.toLowerCase().replace(/\s+/g,
                        '_') || 'open';
                }, 100);
            } catch (error) {
                console.error('Error opening edit modal:', error);
                toastr.error('Failed to open edit form');
            }
        }

        // Create snag with marked up images
        async function createSnagWithMarkup(markedUpImageData) {
            try {
                // Get the stored form data
                const formData = window.snagFormData;

                // Remove original photos from FormData
                formData.delete('photos[]');

                // Add marked up image
                if (markedUpImageData) {
                    const response = await fetch(markedUpImageData);
                    const blob = await response.blob();
                    formData.append('photos[]', blob, 'marked_snag.png');
                }

                console.log('Creating snag with markup...');
                const apiResponse = await api.createSnag(formData);
                console.log('Create snag response:', apiResponse);

                if (apiResponse.code === 200) {
                    // Close drawing modal
                    const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
                    if (drawingModal) drawingModal.hide();

                    toastr.success('Snag with markup saved successfully!');
                    document.getElementById('addSnagForm').reset();
                    loadSnags();
                } else {
                    toastr.error('Failed to save snag: ' + (apiResponse.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Create snag with markup error:', error);
                toastr.error('Failed to save snag. Please try again.');
            }
        }

        // Add Snag Form Handler
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize user permissions
            window.userPermissions = {
                canUpdate: function(resource) {
                    // Check if user has resolve permission for snags (which includes edit)
                    @can('snags', 'resolve')
                        if (resource === 'snags') return true;
                    @endcan
                    return false;
                }
            };
            
            // Load snags and categories on page load
            loadSnags();
            loadSnagCategories();

            // Add filter event listeners
            const statusFilter = document.getElementById('statusFilter');
            const categoryFilter = document.getElementById('categoryFilter');

            const searchInput = document.getElementById('searchInput');

            if (statusFilter) {
                statusFilter.addEventListener('change', applyFilters);
            }

            if (categoryFilter) {
                categoryFilter.addEventListener('change', applyFilters);
            }



            if (searchInput) {
                // Debounce search input
                let searchTimeout;
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(applyFilters, 300);
                });
            }
            const addSnagForm = document.getElementById('addSnagForm');
            if (addSnagForm) {
                addSnagForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const fileInput = document.getElementById('snagPhotos');
                    console.log('Form submitted, files:', fileInput.files);

                    if (fileInput.files && fileInput.files.length > 0) {
                        console.log('Files found, opening drawing modal');

                        // Store form data for later use
                        window.snagFormData = new FormData(addSnagForm);
                        const projectId = getProjectIdFromUrl();
                        window.snagFormData.append('project_id', projectId);
                        window.snagFormData.append('title', window.snagFormData.get('description') ||
                            'New Snag');
                        window.snagFormData.append('category', window.snagFormData.get('issue_type'));
                        window.snagFormData.append('priority', 'medium');
                        window.snagFormData.append('status', 'open');

                        // Close snag modal first
                        const addSnagModal = bootstrap.Modal.getInstance(document.getElementById(
                            'addSnagModal'));
                        if (addSnagModal) addSnagModal.hide();

                        // Open drawing modal after a short delay
                        setTimeout(() => {
                            openDrawingModal({
                                title: 'Image Markup',
                                saveButtonText: 'Save Snag',
                                mode: 'image',
                                onSave: function(markedUpImageData) {
                                    createSnagWithMarkup(markedUpImageData);
                                }
                            });

                            // Store files for drawing modal
                            window.selectedFiles = Array.from(fileInput.files);

                            // Load images when modal is shown
                            document.getElementById('drawingModal').addEventListener(
                                'shown.bs.modal',
                                function() {
                                    if (window.selectedFiles.length === 1) {
                                        loadImageToCanvas(window.selectedFiles[0]);
                                    } else {
                                        loadMultipleFiles(window.selectedFiles);
                                    }
                                }, {
                                    once: true
                                });
                        }, 300);

                    } else {
                        console.log('No files, saving without markup');
                        saveSnagWithoutMarkup();
                    }
                });
            }

            async function saveSnagWithoutMarkup() {
                try {
                    const form = document.getElementById('addSnagForm');
                    const formData = new FormData(form);
                    const projectId = getProjectIdFromUrl();

                    // Debug: Log form data
                    console.log('Form data before API call:');
                    console.log('Project ID:', projectId);
                    console.log('Issue Type:', formData.get('issue_type'));
                    console.log('Description:', formData.get('description'));
                    console.log('Location:', formData.get('location'));

                    // Add required fields
                    formData.append('project_id', projectId);
                    formData.append('title', formData.get('description') || 'New Snag');
                    formData.append('category', formData.get('issue_type'));
                    formData.append('priority', 'medium');
                    formData.append('status', 'open');

                    // Debug: Log all FormData entries
                    console.log('Complete FormData entries:');
                    for (let [key, value] of formData.entries()) {
                        console.log(key + ':', value);
                    }

                    const response = await api.createSnag(formData);
                    console.log('Create snag response:', response);

                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('addSnagModal')).hide();
                        toastr.success('Snag added successfully!');
                        form.reset();
                        loadSnags(); // Reload snags
                    } else {
                        console.error('API Error:', response);
                        toastr.error('Failed to create snag: ' + (response.message || 'Unknown error'));
                    }
                } catch (error) {
                    console.error('Error creating snag:', error);
                    toastr.error('Failed to create snag. Please try again.');
                }
            }

            // Edit Snag Form Handler
            const editSnagForm = document.getElementById('editSnagForm');
            if (editSnagForm) {
                editSnagForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    try {
                        const formData = new FormData(editSnagForm);
                        const snagId = formData.get('snag_id');

                        // Prepare update data (excluding category and location)
                        const updateData = {
                            snag_id: snagId,
                            description: formData.get('description'),
                            priority: formData.get('priority'),
                            status: formData.get('status'),
                            title: formData.get('description') || 'Updated Snag'
                        };

                        console.log('Updating snag with data:', updateData);

                        const response = await api.updateSnag(updateData);
                        console.log('Update snag response:', response);

                        if (response.code === 200) {
                            bootstrap.Modal.getInstance(document.getElementById('editSnagModal'))
                                .hide();
                            toastr.success('{{ __('messages.snag_updated_successfully') }}');
                            console.log('Reloading snags after update...');
                            await loadSnags(); // Reload snags
                            console.log('Snags reloaded successfully');
                        } else {
                            toastr.error('{{ __('messages.failed_to_update_snag') }}: ' + (response
                                .message || 'Unknown error'));
                        }
                    } catch (error) {
                        console.error('Error updating snag:', error);
                        toastr.error('{{ __('messages.failed_to_update_snag') }}');
                    }
                });
            }

            // Clear filters function
            window.clearAllFilters = function() {
                document.getElementById('statusFilter').value = '';
                document.getElementById('categoryFilter').value = '';

                document.getElementById('searchInput').value = '';
                applyFilters();
            };

            // Add clear filters button if needed
            const filtersContainer = document.querySelector('.card-body');
            if (filtersContainer) {
                const clearButton = document.createElement('div');
                clearButton.className = 'col-lg-3 col-md-4 col-sm-6 col-6 d-flex align-items-end';
                clearButton.innerHTML = `
        <button class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="clearAllFilters()">
            <i class="fas fa-times me-1"></i>{{ __('messages.clear_filters') }}
        </button>
    `;
                filtersContainer.querySelector('.row').appendChild(clearButton);
            }

        });
    </script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>

@endsection

@push('scripts')
    <!-- API Dependencies -->
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
@endpush
