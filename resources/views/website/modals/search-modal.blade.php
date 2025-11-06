<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
                <h5 class="modal-title" id="searchModalLabel">
                    <i class="fas fa-search {{ margin_end(2) }}"></i>{{ __('messages.search_projects') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="custom-select-wrapper">
                            <div class="custom-select-trigger" onclick="toggleCustomDropdown('searchType')">
                                <span id="searchTypeText">{{ __('messages.all_types') }}</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="custom-select-options" id="searchTypeOptions">
                                <div class="custom-select-option" data-value="" onclick="selectCustomOption('searchType', '', '{{ __('messages.all_types') }}')">
                                    {{ __('messages.all_types') }}
                                </div>
                                <div class="custom-select-option" data-value="villa" onclick="selectCustomOption('searchType', 'villa', '{{ __('messages.villa') }}')">
                                    {{ __('messages.villa') }}
                                </div>
                                <div class="custom-select-option" data-value="tower" onclick="selectCustomOption('searchType', 'tower', '{{ __('messages.tower') }}')">
                                    {{ __('messages.tower') }}
                                </div>
                                <div class="custom-select-option" data-value="hospital" onclick="selectCustomOption('searchType', 'hospital', '{{ __('messages.hospital') }}')">
                                    {{ __('messages.hospital') }}
                                </div>
                                <div class="custom-select-option" data-value="commercial" onclick="selectCustomOption('searchType', 'commercial', '{{ __('messages.commercial') }}')">
                                    {{ __('messages.commercial') }}
                                </div>
                                <div class="custom-select-option" data-value="residential" onclick="selectCustomOption('searchType', 'residential', '{{ __('messages.residential') }}')">
                                    {{ __('messages.residential') }}
                                </div>
                                <div class="custom-select-option" data-value="industrial" onclick="selectCustomOption('searchType', 'industrial', '{{ __('messages.industrial') }}')">
                                    {{ __('messages.industrial') }}
                                </div>
                            </div>
                            <input type="hidden" id="searchType" value="">
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
                                <div class="custom-select-option" data-value="" onclick="selectCustomOption('searchStatus', '', '{{ __('messages.all_status') }}')">
                                    {{ __('messages.all_status') }}
                                </div>
                                <div class="custom-select-option" data-value="ongoing" onclick="selectCustomOption('searchStatus', 'ongoing', '{{ __('messages.active') }}')">
                                    {{ __('messages.active') }}
                                </div>
                                <div class="custom-select-option" data-value="completed" onclick="selectCustomOption('searchStatus', 'completed', '{{ __('messages.completed') }}')">
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
                                <div class="custom-select-option" data-value="" onclick="selectCustomOption('searchProgress', '', '{{ __('messages.any_progress') }}')">
                                    {{ __('messages.any_progress') }}
                                </div>
                                <div class="custom-select-option" data-value="0-25" onclick="selectCustomOption('searchProgress', '0-25', '0-25%')">
                                    0-25%
                                </div>
                                <div class="custom-select-option" data-value="26-50" onclick="selectCustomOption('searchProgress', '26-50', '26-50%')">
                                    26-50%
                                </div>
                                <div class="custom-select-option" data-value="51-75" onclick="selectCustomOption('searchProgress', '51-75', '51-75%')">
                                    51-75%
                                </div>
                                <div class="custom-select-option" data-value="76-100" onclick="selectCustomOption('searchProgress', '76-100', '76-100%')">
                                    76-100%
                                </div>
                            </div>
                            <input type="hidden" id="searchProgress" value="">
                        </div>
                    </div>
                </div>

                <style>
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
                        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
                        flex-direction: row-reverse;
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
                    <div id="resultsContainer" style="max-height: 400px; overflow-y: auto;">
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-search fa-3x mb-3"></i>
                            <p>{{ __('messages.enter_search_terms') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="background-color: gray; border-color: gray; padding: 0.7rem 1.5rem;"
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
        const searchType = document.getElementById('searchType').value;
        const searchStatus = document.getElementById('searchStatus').value;
        const searchProgress = document.getElementById('searchProgress').value;
        
        // Prevent search if no criteria provided
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
                limit: 20
            };
            
            // Send search term only, handle type filtering separately
            if (searchTerm) {
                searchParams.search = searchTerm;
            }
            
            if (searchStatus) {
                searchParams.type = searchStatus;
            }
            
            const response = await api.getProjects(searchParams);
            
            if (response.code === 200 && response.data) {
                // Ensure we have an array to work with
                let filteredProjects = Array.isArray(response.data) ? response.data : 
                                     (Array.isArray(response.data.data) ? response.data.data : []);
                
                // Filter by project type if selected
                if (searchType) {
                    filteredProjects = filteredProjects.filter(project => 
                        project.type && project.type.toLowerCase() === searchType.toLowerCase()
                    );
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

        const resultsHTML = projects.map(project => {
            const statusClass = project.status === 'completed' ? 'bg-success' : 'bg-primary';
            const progress = getConsistentProgress(project);
            
            return `
                <div class="card mb-3" style="cursor: pointer;" onclick="window.location.href='/website/project/${project.id}/plans'">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="fw-bold mb-1">${project.project_title}</h6>
                                <small class="text-muted">${project.type || 'Construction'} • ${project.project_location || 'N/A'}</small>
                            </div>
                            {{-- <div class="col-md-3">
                                <span class="badge ${statusClass}">${project.status}</span>
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: ${progress}%"></div>
                                </div>
                                <small class="text-muted">${progress}% {{ __('messages.complete') }}</small>
                            </div> --}}
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = resultsHTML;
    }

    function getConsistentProgress(project) {
        if (project.status === 'completed') return 100;
        // Generate consistent progress based on project ID
        const seed = project.id || 1;
        return ((seed * 17) % 80) + 20; // Always between 20-99%
    }
    
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchType').value = '';
        document.getElementById('searchStatus').value = '';
        document.getElementById('searchProgress').value = '';
        
        document.getElementById('searchTypeText').textContent = '{{ __('messages.all_types') }}';
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
