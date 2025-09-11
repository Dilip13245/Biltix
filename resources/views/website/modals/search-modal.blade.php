<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
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
                            placeholder="{{ __('messages.search_by_project') }}" autofocus>
                        <button class="btn orange_btn" type="button" onclick="performSearch()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label fw-medium">{{ __('messages.project_type') }}</label>
                        <select class="form-select" id="searchType">
                            <option value="">{{ __('messages.all_types') }}</option>
                            <option value="villa">{{ __('messages.villa') }}</option>
                            <option value="tower">{{ __('messages.tower') }}</option>
                            <option value="hospital">{{ __('messages.hospital') }}</option>
                            <option value="commercial">{{ __('messages.commercial') }}</option>
                            <option value="residential">{{ __('messages.residential') }}</option>
                            <option value="industrial">{{ __('messages.industrial') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">{{ __('messages.status') }}</label>
                        <select class="form-select" id="searchStatus">
                            <option value="">{{ __('messages.all_status') }}</option>
                            <option value="ongoing">{{ __('messages.active') }}</option>
                            <option value="completed">{{ __('messages.completed') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-medium">{{ __('messages.progress') }}</label>
                        <select class="form-select" id="searchProgress">
                            <option value="">{{ __('messages.any_progress') }}</option>
                            <option value="0-25">0-25%</option>
                            <option value="26-50">26-50%</option>
                            <option value="51-75">51-75%</option>
                            <option value="76-100">76-100%</option>
                        </select>
                    </div>
                </div>

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
                <button type="button" class="btn orange_btn" style="background-color: gray"
                    data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                <button type="button" class="btn orange_btn" onclick="clearSearch()">
                    <i class="fas fa-times {{ margin_end(2) }}"></i>{{ __('messages.clear_filters') }}
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
                // Apply client-side filtering for type and progress
                let filteredProjects = response.data;
                
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
                            <div class="col-md-3">
                                <span class="badge ${statusClass}">${project.status}</span>
                            </div>
                            <div class="col-md-3">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: ${progress}%"></div>
                                </div>
                                <small class="text-muted">${progress}% {{ __('messages.complete') }}</small>
                            </div>
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
        performSearch();

        document.getElementById('resultsContainer').innerHTML = `
    <div class="text-center text-muted py-4">
      <i class="fas fa-search fa-3x mb-3"></i>
      <p>{{ __('messages.enter_search_terms') }}</p>
    </div>
  `;
    }

    // Auto-search with debounce
    document.addEventListener('DOMContentLoaded', function() {
        let searchTimeout;
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500);
            });
        }

        const filters = ['searchType', 'searchStatus', 'searchProgress'];
        filters.forEach(filterId => {
            const element = document.getElementById(filterId);
            if (element) {
                element.addEventListener('change', performSearch);
            }
        });
    });
</script>
