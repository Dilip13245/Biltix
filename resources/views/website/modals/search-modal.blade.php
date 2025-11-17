<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true"
    data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <style>
                    #searchModal .modal-header .btn-close {
                        position: static !important;
                        right: auto !important;
                        top: auto !important;
                        margin: 0 !important;
                    }

                    #searchModal .modal-header {
                        position: relative !important;
                    }

                    /* Mirror search icon in RTL - all search icons in modal */
                    [dir="rtl"] #searchModal .fa-search,
                    [dir="rtl"] #searchModal i.fa-search {
                        transform: scaleX(-1);
                    }
                </style>
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title" id="searchModalLabel">
                            {{ __('messages.search_projects') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="{{ __('messages.close') }}"></button>
                    </div>
                @else
                    <h5 class="modal-title" id="searchModalLabel">
                        {{ __('messages.search_projects') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="{{ __('messages.close') }}"></button>
                @endif
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-lg" id="searchInput"
                            placeholder="{{ __('messages.search_by_project') }}" autofocus maxlength="100">
                        <button class="btn orange_btn api-action-btn" type="button" onclick="performSearch()">
                            {{ __('messages.search') }}
                        </button>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">{{ __('messages.project_type') }}</label>
                        <div class="custom-combo-dropdown position-relative">
                            <input type="text" class="form-control Input_control" id="searchType"
                                placeholder="{{ __('messages.all_types') }}" autocomplete="off" maxlength="50">
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                            @if (!is_rtl())
                                <i class="fas fa-times clear-selection d-none" id="clearSearchTypeSelection"
                                    style="position: absolute; right: 35px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999; z-index: 10;"
                                    onclick="clearSearchTypeSelection()"></i>
                            @else
                                <i class="fas fa-times clear-selection d-none" id="clearSearchTypeSelection"
                                    style="position: absolute; left: 35px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999; z-index: 10;"
                                    onclick="clearSearchTypeSelection()"></i>
                            @endif
                            <div class="dropdown-options" id="searchTypeDropdown">
                                <div class="dropdown-option" data-value="">{{ __('messages.all_types') }}</div>
                                <div class="dropdown-option" data-value="{{ __('messages.villa') }}">
                                    {{ __('messages.villa') }}</div>
                                <div class="dropdown-option" data-value="{{ __('messages.tower') }}">
                                    {{ __('messages.tower') }}</div>
                                <div class="dropdown-option" data-value="{{ __('messages.hospital') }}">
                                    {{ __('messages.hospital') }}</div>
                                <div class="dropdown-option" data-value="{{ __('messages.commercial') }}">
                                    {{ __('messages.commercial') }}</div>
                                <div class="dropdown-option" data-value="{{ __('messages.residential') }}">
                                    {{ __('messages.residential') }}</div>
                                <div class="dropdown-option" data-value="{{ __('messages.industrial') }}">
                                    {{ __('messages.industrial') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">{{ __('messages.status') }}</label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select-trigger" onclick="toggleCustomDropdown('searchStatus')">
                                <span id="searchStatusText">{{ __('messages.all_status') }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="custom-select-options" id="searchStatusOptions">
                                <div class="custom-select-option" data-value=""
                                    onclick="selectCustomOption('searchStatus', '', '{{ __('messages.all_status') }}')">
                                    {{ __('messages.all_status') }}
                                </div>
                                <div class="custom-select-option" data-value="ongoing"
                                    onclick="selectCustomOption('searchStatus', 'ongoing', '{{ __('messages.active') }}')">
                                    {{ __('messages.active') }}
                                </div>
                                <div class="custom-select-option" data-value="completed"
                                    onclick="selectCustomOption('searchStatus', 'completed', '{{ __('messages.completed') }}')">
                                    {{ __('messages.completed') }}
                                </div>
                            </div>
                            <input type="hidden" id="searchStatus" value="">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">{{ __('messages.progress') }}</label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select-trigger" onclick="toggleCustomDropdown('searchProgress')">
                                <span id="searchProgressText">{{ __('messages.any_progress') }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="custom-select-options" id="searchProgressOptions">
                                <div class="custom-select-option" data-value=""
                                    onclick="selectCustomOption('searchProgress', '', '{{ __('messages.any_progress') }}')">
                                    {{ __('messages.any_progress') }}
                                </div>
                                <div class="custom-select-option" data-value="0-25"
                                    onclick="selectCustomOption('searchProgress', '0-25', '0-25%')">
                                    0-25%
                                </div>
                                <div class="custom-select-option" data-value="26-50"
                                    onclick="selectCustomOption('searchProgress', '26-50', '26-50%')">
                                    26-50%
                                </div>
                                <div class="custom-select-option" data-value="51-75"
                                    onclick="selectCustomOption('searchProgress', '51-75', '51-75%')">
                                    51-75%
                                </div>
                                <div class="custom-select-option" data-value="76-100"
                                    onclick="selectCustomOption('searchProgress', '76-100', '76-100%')">
                                    76-100%
                                </div>
                            </div>
                            <input type="hidden" id="searchProgress" value="">
                        </div>
                    </div>
                </div>

                <style>
                    /* Custom Combo Dropdown Styles */
                    .custom-combo-dropdown {
                        position: relative;
                    }

                    .custom-combo-dropdown .dropdown-arrow {
                        position: absolute;
                        right: 12px;
                        top: 50%;
                        transform: translateY(-50%);
                        pointer-events: none;
                        color: #6c757d;
                        font-size: 12px;
                        z-index: 5;
                    }

                    [dir="rtl"] .custom-combo-dropdown .dropdown-arrow {
                        right: auto;
                        left: 12px;
                    }

                    .custom-combo-dropdown input.Input_control {
                        padding: 10px 35px 10px 12px !important;
                        cursor: pointer;
                        height: auto !important;
                        min-height: 42px !important;
                        line-height: 1.5 !important;
                        border: 1px solid #dee2e6 !important;
                        border-radius: 8px !important;
                        font-size: 1rem !important;
                        box-sizing: border-box !important;
                    }

                    [dir="rtl"] .custom-combo-dropdown input.Input_control {
                        padding: 10px 12px 10px 35px !important;
                    }

                    .dropdown-options {
                        position: absolute;
                        top: 100%;
                        left: 0;
                        right: 0;
                        background: white;
                        border: 1px solid #dee2e6;
                        border-radius: 8px;
                        margin-top: 4px;
                        max-height: 200px;
                        overflow-y: auto;
                        z-index: 1000;
                        display: none;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    }

                    .dropdown-options.show {
                        display: block;
                    }

                    .dropdown-option {
                        padding: 10px 12px;
                        cursor: pointer;
                        transition: background-color 0.2s;
                    }

                    .dropdown-option:hover {
                        background-color: #f8f9fa;
                    }

                    .dropdown-option.selected {
                        background-color: #fff5f0;
                        color: #F58D2E;
                        font-weight: 500;
                    }

                    /* Scrollbar styling */
                    .dropdown-options::-webkit-scrollbar {
                        width: 6px;
                    }

                    .dropdown-options::-webkit-scrollbar-track {
                        background: #f1f1f1;
                        border-radius: 4px;
                    }

                    .dropdown-options::-webkit-scrollbar-thumb {
                        background: #F58D2E;
                        border-radius: 4px;
                    }

                    .dropdown-options::-webkit-scrollbar-thumb:hover {
                        background: #e07a1f;
                    }

                    /* Custom Select Wrapper (for Status and Progress) */
                    .custom-select-wrapper {
                        position: relative;
                    }
                    
                    .custom-select-trigger {
                        padding: 10px 12px;
                        border: 1px solid #dee2e6;
                        border-radius: 8px;
                        background: white;
                        cursor: pointer;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        transition: all 0.2s;
                    }
                    
                    .custom-select-trigger:hover {
                        border-color: #F58D2E;
                    }
                    
                    .custom-select-trigger i {
                        color: #6c757d;
                        font-size: 12px;
                        transition: transform 0.2s;
                        flex-shrink: 0;
                    }
                    
                    .custom-select-trigger.active i {
                        transform: rotate(180deg);
                    }
                    
                    .custom-select-options {
                        position: absolute;
                        top: calc(100% + 4px);
                        left: 0;
                        right: 0;
                        background: white;
                        border: 1px solid #dee2e6;
                        border-radius: 8px;
                        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                        max-height: 200px;
                        overflow-y: auto;
                        z-index: 1050;
                        display: none;
                    }
                    
                    .custom-select-options.show {
                        display: block;
                    }
                    
                    .custom-select-option {
                        padding: 10px 12px;
                        cursor: pointer;
                        transition: background-color 0.2s;
                    }
                    
                    .custom-select-option:hover {
                        background-color: #f8f9fa;
                    }
                    
                    .custom-select-option.selected {
                        background-color: #fff5f0;
                        color: #F58D2E;
                        font-weight: 500;
                    }
                    
                    /* RTL Support */
                    [dir="rtl"] .custom-select-trigger {
                        flex-direction: row;
                        justify-content: space-between;
                    }

                    [dir="rtl"] .custom-select-trigger span {
                        text-align: right;
                        flex: 1;
                    }

                    [dir="rtl"] .custom-select-trigger i {
                        flex-shrink: 0;
                    }
                    
                    /* Scrollbar styling */
                    .custom-select-options::-webkit-scrollbar {
                        width: 6px;
                    }
                    
                    .custom-select-options::-webkit-scrollbar-track {
                        background: #f1f1f1;
                        border-radius: 4px;
                    }
                    
                    .custom-select-options::-webkit-scrollbar-thumb {
                        background: #F58D2E;
                        border-radius: 4px;
                    }
                    
                    .custom-select-options::-webkit-scrollbar-thumb:hover {
                        background: #e07a1f;
                    }
                </style>

                <div id="searchResults" class="mt-4">
                    <h6 class="fw-bold mb-3">{{ __('messages.search_results') }}</h6>
                    <div id="resultsContainer" style="max-height: 500px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <p>{{ __('messages.enter_search_terms') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    style="background-color: gray; border-color: gray; padding: 0.7rem 1.5rem;"
                    data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                <button type="button" class="btn orange_btn api-action-btn" onclick="clearSearch()">
                    {{ __('messages.clear_filters') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    async function performSearch() {
        const searchTerm = document.getElementById('searchInput').value.trim();
        const searchTypeInput = document.getElementById('searchType');
        const searchType = searchTypeInput ? searchTypeInput.value.trim() : '';
        const searchStatus = document.getElementById('searchStatus').value;
        const searchProgress = document.getElementById('searchProgress').value;
        
        // Allow search if any criteria is provided (including typing in searchType)
        // Don't prevent search if user is typing in searchType field
        if (!searchTerm && !searchType && !searchStatus && !searchProgress) {
            document.getElementById('resultsContainer').innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <p>{{ __('messages.enter_search_terms') }}</p>
                </div>
            `;
            return;
        }
        
        // Show loading
        document.getElementById('resultsContainer').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border" role="status"></div>
                <div class="mt-2">{{ __('messages.searching') }}...</div>
            </div>
        `;
        
        try {
            const searchParams = {
                page: 1,
                limit: 50 // Increased limit to get more results for filtering
            };
            
            // If searchType is provided, also include it in search parameter for API
            // This way API can search in type field as well
            if (searchTerm) {
                searchParams.search = searchTerm;
            } else if (searchType) {
                // If only searchType is provided, use it as search parameter
                // API searches in type field when search parameter is provided
                searchParams.search = searchType;
            }
            
            // Note: searchStatus is used for status filtering
            if (searchStatus) {
                searchParams.status = searchStatus;
            }
            
            const response = await api.getProjects(searchParams);
            
            if (response.code === 200 && response.data) {
                // Ensure we have an array to work with
                let filteredProjects = Array.isArray(response.data) ? response.data : 
                                     (Array.isArray(response.data.data) ? response.data.data : []);
                
                // Additional client-side filtering by project type if searchType was provided
                // This ensures exact/partial matching even if API search didn't match exactly
                if (searchType && searchTerm) {
                    // If both searchTerm and searchType are provided, do additional filtering
                    filteredProjects = filteredProjects.filter(project => {
                        if (!project.type) return false;
                        const projectType = project.type.toLowerCase();
                        const searchTypeLower = searchType.toLowerCase();
                        // Exact match or contains match
                        return projectType === searchTypeLower || projectType.includes(searchTypeLower);
                    });
                } else if (searchType && !searchTerm) {
                    // If only searchType is provided, filter by type
                    filteredProjects = filteredProjects.filter(project => {
                        if (!project.type) return false;
                        const projectType = project.type.toLowerCase();
                        const searchTypeLower = searchType.toLowerCase();
                        // Exact match or contains match
                        return projectType === searchTypeLower || projectType.includes(searchTypeLower);
                    });
                }
                
                // Filter by progress if selected
                if (searchProgress) {
                    const [min, max] = searchProgress.split('-').map(Number);
                    filteredProjects = filteredProjects.filter(project => {
                        const progress = getConsistentProgress(project);
                        return progress >= min && progress <= max;
                    });
                }
                
                displaySearchResults(filteredProjects);
            } else {
                displaySearchResults([]);
            }
        } catch (error) {
            console.error('Search failed:', error);
            document.getElementById('resultsContainer').innerHTML = `
                <div class="text-center text-danger py-4">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i>
                    <p>{{ __('messages.search_error') }}</p>
                </div>
            `;
        }
    }

    // Format date function - same as dashboard
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        } catch (e) {
            return dateString;
        }
    }

    function displaySearchResults(projects) {
        const container = document.getElementById('resultsContainer');

        if (projects.length === 0) {
            container.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-search fa-3x mb-3"></i>
                    <p>{{ __('messages.no_projects_found') }}</p>
                </div>
            `;
            return;
        }

        // Create a wrapper div for list layout (one card per row)
        const resultsWrapper = document.createElement('div');

        projects.forEach((project, index) => {
            const projectCard = document.createElement('div');
            projectCard.className = 'mb-3';

            projectCard.innerHTML = `
                <div class="card project-card h-100" style="position: relative; cursor: pointer;" onclick="window.location.href='/website/project/${project.id}/plans'">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-start justify-content-between mb-3">
                            <a href="/website/project/${project.id}/plans" class="text-decoration-none" style="flex: 1; min-width: 0; padding-right: 12px;" onclick="event.stopPropagation();">
                                <h6 class="mb-0 fw-semibold" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${project.project_title || ''}">${project.project_title || 'N/A'}</h6>
                            </a>
                            <div class="dropdown" style="flex-shrink: 0;" onclick="event.stopPropagation();">
                                <i class="fas fa-ellipsis-v" style="color: #4A90E2; cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item text-danger api-action-btn" href="#" onclick="event.preventDefault(); event.stopPropagation(); deleteProjectFromSearch(${project.id});"><i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <a href="/website/project/${project.id}/plans" class="text-decoration-none" onclick="event.stopPropagation();">
                            <hr style="border-color: #e0e0e0; margin: 12px 0;">
                            <div class="mb-2 d-flex align-items-center" style="gap: 6px;">
                                <i class="fas fa-building" style="color: #4A90E2; font-size: 16px; flex-shrink: 0;"></i>
                                <span class="text-muted" style="font-size: 14px;">${project.type || 'N/A'}</span>
                            </div>
                            <div class="mb-2 d-flex align-items-center" style="min-width: 0; gap: 6px;">
                                <i class="fas fa-map-marker-alt" style="color: #4A90E2; font-size: 16px; flex-shrink: 0;"></i>
                                <span class="text-muted" style="font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; flex: 1; min-width: 0;">${project.project_location || 'N/A'}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <i class="far fa-calendar-alt" style="color: #4A90E2; font-size: 16px; flex-shrink: 0;"></i>
                                    <span class="text-muted" style="font-size: 13px;">{{ __('messages.due_date') }}: ${formatDate(project.project_due_date)}</span>
                                </div>
                                <div class="d-flex align-items-center" style="gap: 6px;">
                                    <i class="far fa-id-badge" style="color: #4A90E2; font-size: 16px; flex-shrink: 0;"></i>
                                    <span class="text-muted" style="font-size: 13px;">${project.project_code || 'N/A'}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            `;

            resultsWrapper.appendChild(projectCard);

            // Initialize Bootstrap dropdown
            const dropdownIcon = projectCard.querySelector('[data-bs-toggle="dropdown"]');
            if (dropdownIcon && typeof bootstrap !== 'undefined') {
                new bootstrap.Dropdown(dropdownIcon);
            }
        });

        container.innerHTML = '';
        container.appendChild(resultsWrapper);
    }

    function deleteProjectFromSearch(projectId) {
        if (!confirm('{{ __('messages.delete_project_warning') }}')) {
            return;
        }

        const deleteBtn = event.target.closest('.api-action-btn');
        if (deleteBtn) {
            deleteBtn.disabled = true;
            const originalHTML = deleteBtn.innerHTML;
            deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('messages.deleting') }}...';
            
            const userId = typeof currentUserId !== 'undefined' ? currentUserId : {{ auth()->id() ?? 1 }};
            
            api.deleteProject({
                project_id: projectId,
                user_id: userId
            }).then(response => {
                if (response.code === 200) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success('{{ __('messages.project_deleted_successfully') }}');
                    }
                    // Remove the project card from search results
                    const projectCard = document.querySelector(
                        `[onclick*="/website/project/${projectId}/plans"]`);
                    if (projectCard) {
                        projectCard.closest('.mb-3').remove();
                    }
                    // If no projects left, show no results message
                    const container = document.getElementById('resultsContainer');
                    if (container && container.querySelectorAll('.mb-3').length === 0) {
                        container.innerHTML = `
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-search fa-3x mb-3"></i>
                                <p>{{ __('messages.no_projects_found') }}</p>
                            </div>
                        `;
                    }
                } else {
                    if (typeof toastr !== 'undefined') {
                        toastr.error(response.message || '{{ __('messages.failed_to_delete_project') }}');
                    }
                    deleteBtn.disabled = false;
                    deleteBtn.innerHTML = originalHTML;
                }
            }).catch(error => {
                console.error('Error deleting project:', error);
                if (typeof toastr !== 'undefined') {
                    toastr.error('{{ __('messages.error_deleting_project') }}');
                }
                deleteBtn.disabled = false;
                deleteBtn.innerHTML = originalHTML;
            });
        }
    }

    function getConsistentProgress(project) {
        if (project.status === 'completed') return 100;
        // Generate consistent progress based on project ID
        const seed = project.id || 1;
        return ((seed * 17) % 80) + 20; // Always between 20-99%
    }
    
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        
        // Clear search type combo dropdown
        const searchTypeInput = document.getElementById('searchType');
        if (searchTypeInput) {
            searchTypeInput.value = '';
            searchTypeInput.readOnly = false;
            searchTypeInput.style.cursor = 'text';
            searchTypeInput.style.backgroundColor = '';
            const clearBtn = document.getElementById('clearSearchTypeSelection');
            if (clearBtn) {
                clearBtn.classList.add('d-none');
            }
            const dropdown = document.getElementById('searchTypeDropdown');
            if (dropdown) {
                dropdown.querySelectorAll('.dropdown-option').forEach(option => {
                    option.style.display = 'block';
                    option.classList.remove('selected');
                });
            }
        }
        
        document.getElementById('searchStatus').value = '';
        document.getElementById('searchProgress').value = '';
        
        document.getElementById('searchStatusText').textContent = '{{ __('messages.all_status') }}';
        document.getElementById('searchProgressText').textContent = '{{ __('messages.any_progress') }}';
        
        document.querySelectorAll('.custom-select-option.selected').forEach(el => el.classList.remove('selected'));
        
        document.getElementById('resultsContainer').innerHTML = `
            <div class="text-center text-muted py-4">
                <i class="fas fa-search fa-3x mb-3"></i>
                <p>{{ __('messages.enter_search_terms') }}</p>
            </div>
        `;
    }
    
    function toggleCustomDropdown(id) {
        const optionsEl = document.getElementById(id + 'Options');
        const triggerEl = optionsEl.previousElementSibling;
        
        document.querySelectorAll('.custom-select-options.show').forEach(el => {
            if (el.id !== id + 'Options') {
                el.classList.remove('show');
                el.previousElementSibling.classList.remove('active');
            }
        });
        
        optionsEl.classList.toggle('show');
        triggerEl.classList.toggle('active');
    }
    
    function selectCustomOption(id, value, text) {
        document.getElementById(id).value = value;
        document.getElementById(id + 'Text').textContent = text;
        
        const optionsContainer = document.getElementById(id + 'Options');
        optionsContainer.querySelectorAll('.custom-select-option').forEach(el => {
            el.classList.remove('selected');
            if (el.getAttribute('data-value') === value) {
                el.classList.add('selected');
            }
        });
        
        optionsContainer.classList.remove('show');
        optionsContainer.previousElementSibling.classList.remove('active');
        
        performSearch();
    }
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-select-wrapper')) {
            document.querySelectorAll('.custom-select-options.show').forEach(el => {
                el.classList.remove('show');
                el.previousElementSibling.classList.remove('active');
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Search Type Combo Dropdown
        const searchTypeInput = document.getElementById('searchType');
        const searchTypeDropdown = document.getElementById('searchTypeDropdown');
        const clearSearchTypeBtn = document.getElementById('clearSearchTypeSelection');

        // Function to clear search type selection
        window.clearSearchTypeSelection = function() {
            if (searchTypeInput) {
                searchTypeInput.readOnly = false;
                searchTypeInput.value = '';
                searchTypeInput.style.cursor = 'text';
                searchTypeInput.style.backgroundColor = '';
                if (clearSearchTypeBtn) {
                    clearSearchTypeBtn.classList.add('d-none');
                }
                // Show all options again
                if (searchTypeDropdown) {
                    searchTypeDropdown.querySelectorAll('.dropdown-option').forEach(option => {
                        option.style.display = 'block';
                        option.classList.remove('selected');
                    });
                }
            }
        };

        if (searchTypeInput && searchTypeDropdown) {
            // Show dropdown on input click
            searchTypeInput.addEventListener('click', function() {
                searchTypeDropdown.classList.add('show');
            });

            // Prevent typing when readonly (selected from dropdown)
            searchTypeInput.addEventListener('keydown', function(e) {
                if (this.readOnly) {
                    // Allow only backspace/delete to clear selection
                    if (e.key === 'Backspace' || e.key === 'Delete') {
                        e.preventDefault();
                        window.clearSearchTypeSelection();
                    } else {
                        // Prevent all other keys when readonly
                        e.preventDefault();
                    }
                }
            });

            // Prevent typing when readonly
            searchTypeInput.addEventListener('keypress', function(e) {
                if (this.readOnly) {
                    e.preventDefault();
                }
            });

            // Prevent paste when readonly
            searchTypeInput.addEventListener('paste', function(e) {
                if (this.readOnly) {
                    e.preventDefault();
                }
            });

            // Monitor input value changes to detect manual clearing
            searchTypeInput.addEventListener('input', function() {
                if (this.readOnly) {
                    return;
                }

                // If input becomes empty, remove readonly state
                if (!this.value.trim()) {
                    this.readOnly = false;
                    this.style.cursor = 'text';
                    this.style.backgroundColor = '';
                    if (clearSearchTypeBtn) {
                        clearSearchTypeBtn.classList.add('d-none');
                    }
                }

                // Filter options
                const filter = this.value.toLowerCase();
                const options = searchTypeDropdown.querySelectorAll('.dropdown-option');

                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(filter)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });

                // Trigger search when typing (debounced) - but only if there's a value
                if (this.value.trim()) {
                    clearTimeout(window.searchTypeTimeout);
                    window.searchTypeTimeout = setTimeout(() => {
                        performSearch();
                    }, 500);
                } else {
                    // If input is cleared, also trigger search to show all results
                    clearTimeout(window.searchTypeTimeout);
                    window.searchTypeTimeout = setTimeout(() => {
                        performSearch();
                    }, 300);
                }
            });

            // Select option on click
            searchTypeDropdown.querySelectorAll('.dropdown-option').forEach(option => {
                option.addEventListener('click', function() {
                    const selectedValue = this.getAttribute('data-value');
                    searchTypeInput.value = selectedValue;
                    
                    if (selectedValue) {
                        // Make readonly when value is selected from dropdown
                        searchTypeInput.readOnly = true;
                        searchTypeInput.style.cursor = 'pointer';
                        searchTypeInput.style.backgroundColor = '#f8f9fa';
                        if (clearSearchTypeBtn) {
                            clearSearchTypeBtn.classList.remove('d-none');
                            const isRTL = document.documentElement.dir === 'rtl' || document
                                .documentElement.getAttribute('dir') === 'rtl';
                            if (isRTL) {
                                clearSearchTypeBtn.style.left = '35px';
                                clearSearchTypeBtn.style.right = 'auto';
                            } else {
                                clearSearchTypeBtn.style.right = '35px';
                                clearSearchTypeBtn.style.left = 'auto';
                            }
                        }
                    } else {
                        // Empty value - allow editing
                        searchTypeInput.readOnly = false;
                        searchTypeInput.style.cursor = 'text';
                        searchTypeInput.style.backgroundColor = '';
                        if (clearSearchTypeBtn) {
                            clearSearchTypeBtn.classList.add('d-none');
                        }
                    }
                    
                    // Update selected state
                    searchTypeDropdown.querySelectorAll('.dropdown-option').forEach(opt => {
                        opt.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    
                    searchTypeDropdown.classList.remove('show');
                    
                    // Trigger search
                    performSearch();
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchTypeInput.contains(e.target) && !searchTypeDropdown.contains(e.target) && 
                    !clearSearchTypeBtn?.contains(e.target)) {
                    searchTypeDropdown.classList.remove('show');
                }
            });
        }

        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500);
            });
        }
        
        const searchModal = document.getElementById('searchModal');
        if (searchModal) {
            searchModal.addEventListener('hidden.bs.modal', function() {
                clearSearch();
            });
        }
    });
</script>
