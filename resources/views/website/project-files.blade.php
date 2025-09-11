@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Files')

@section('content')
    <div class="content-header border-0 shadow-none mb-4 d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2>{{ __('messages.project_files') }}</h2>
            <p>{{ __('messages.view_access_files') }}</p>
        </div>
        <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap">
            <!-- Filter Button -->
            <button class="filter-btn d-flex align-items-center border rounded-3 px-3 py-2 bg-light">
                <svg width="17" height="14" class="{{ margin_end(2) }}" viewBox="0 0 17 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M0.606331 0.715625C0.812581 0.278125 1.25008 0 1.73446 0H15.2345C15.7188 0 16.1563 0.278125 16.3626 0.715625C16.5688 1.15313 16.5063 1.66875 16.2001 2.04375L10.4845 9.02812V13C10.4845 13.3781 10.272 13.725 9.93133 13.8938C9.59071 14.0625 9.18758 14.0281 8.88446 13.8L6.88446 12.3C6.63133 12.1125 6.48446 11.8156 6.48446 11.5V9.02812L0.765706 2.04063C0.462581 1.66875 0.396956 1.15 0.606331 0.715625Z"
                        fill="#4477C4" />
                </svg>
                <span class="text-black">{{ __('messages.filter') }}</span>
            </button>
            <!-- Sort Button -->
            <button class="sort-btn d-flex align-items-center border rounded-3 px-3 py-2 bg-light">
                <svg width="11" height="14" class="{{ margin_end(2) }}" viewBox="0 0 11 14" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M4.38729 0.293945C4.77791 -0.0966797 5.41229 -0.0966797 5.80291 0.293945L9.80291 4.29395C10.0904 4.58145 10.1748 5.00957 10.0185 5.38457C9.86229 5.75957 9.49979 6.00332 9.09354 6.00332H1.09354C0.690412 6.00332 0.324787 5.75957 0.168537 5.38457C0.0122866 5.00957 0.0997865 4.58145 0.384162 4.29395L4.38416 0.293945H4.38729ZM4.38729 13.7096L0.387287 9.70957C0.0997865 9.42207 0.0154115 8.99395 0.171662 8.61895C0.327912 8.24395 0.690411 8.0002 1.09666 8.0002H9.09354C9.49666 8.0002 9.86229 8.24395 10.0185 8.61895C10.1748 8.99395 10.0873 9.42207 9.80291 9.70957L5.80291 13.7096C5.41229 14.1002 4.77791 14.1002 4.38729 13.7096Z"
                        fill="#4477C4" />
                </svg>
                <span class="text-black">{{ __('messages.sort') }}</span>
            </button>
            <!-- Upload Button -->
            @can('files', 'upload')
            <input type="file" id="fileUploadInput" accept=".pdf,.doc,.docx,.xls,.xlsx,.dwg,.jpg,.jpeg,.png"
                style="display: none;" onchange="handleFileUpload(this)">
            <button class="btn orange_btn" onclick="document.getElementById('fileUploadInput').click()" data-permission="files:upload">
                <i class="fas fa-arrow-up me-2"></i>
                {{ __('messages.upload_file') }}
            </button>
            @endcan
        </div>
    </div>
    <section class="px-md-4">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12 wow fadeInUp" data-wow-delay="0s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4 justify-content-between">
                            <div>
                                <div class="small_tXt">{{ __('messages.total_files') }}</div>
                                <div class="stat-value" id="totalFilesCount">...</div>
                            </div>
                            <span class="{{ margin_start() }}-auto stat-icon bg1">
                                <svg width="12" height="16" viewBox="0 0 12 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_861_2984)">
                                        <path
                                            d="M0 2C0 0.896875 0.896875 0 2 0H7V4C7 4.55312 7.44688 5 8 5H12V14C12 15.1031 11.1031 16 10 16H2C0.896875 16 0 15.1031 0 14V2ZM12 4H8V0L12 4Z"
                                            fill="#4477C4" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_861_2984">
                                            <path d="M0 0H12V16H0V0Z" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4 justify-content-between">
                            <div>
                                <div class="small_tXt">{{ __('messages.drawings') }}</div>
                                <div class="stat-value" id="drawingsCount">...</div>
                            </div>
                            <span class="{{ margin_start() }}-auto stat-icon bg2">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11 3C11 3.44688 10.9031 3.87187 10.725 4.25625L12.375 7.10625C11.6344 7.89687 10.6812 8.48438 9.60938 8.78125L8 6L5.875 9.67188C6.54688 9.88437 7.25938 10 8.00313 10C10.2125 10 12.1844 8.97812 13.4688 7.375C13.8156 6.94375 14.4438 6.875 14.875 7.21875C15.3062 7.5625 15.375 8.19375 15.0312 8.625C13.3781 10.6812 10.8438 12 8 12C6.89375 12 5.83125 11.8 4.85313 11.4344L3.08437 14.4906C2.9375 14.7438 2.71875 14.95 2.45625 15.0813L0.725 15.9469C0.56875 16.025 0.384375 16.0156 0.2375 15.925C0.090625 15.8344 0 15.6719 0 15.5V13.7688C0 13.5063 0.06875 13.2469 0.203125 13.0156L2.07812 9.775C1.67813 9.425 1.30625 9.04062 0.975 8.625C0.628125 8.19375 0.7 7.56563 1.13125 7.21875C1.5625 6.87187 2.19062 6.94375 2.5375 7.375C2.71562 7.59688 2.90625 7.80625 3.10625 8.00313L5.275 4.25625C5.1 3.875 5 3.45 5 3C5 1.34375 6.34375 0 8 0C9.65625 0 11 1.34375 11 3ZM11.6562 12.3094C12.675 11.9094 13.6094 11.3469 14.4344 10.6562L15.8 13.0156C15.9313 13.2437 16.0031 13.5031 16.0031 13.7688V15.5C16.0031 15.6719 15.9125 15.8344 15.7656 15.925C15.6187 16.0156 15.4344 16.025 15.2781 15.9469L13.5469 15.0813C13.2844 14.95 13.0656 14.7438 12.9187 14.4906L11.6562 12.3094ZM8 4C8.26522 4 8.51957 3.89464 8.70711 3.70711C8.89464 3.51957 9 3.26522 9 3C9 2.73478 8.89464 2.48043 8.70711 2.29289C8.51957 2.10536 8.26522 2 8 2C7.73478 2 7.48043 2.10536 7.29289 2.29289C7.10536 2.48043 7 2.73478 7 3C7 3.26522 7.10536 3.51957 7.29289 3.70711C7.48043 3.89464 7.73478 4 8 4Z"
                                        fill="#F58D2E" />
                                </svg>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12  wow fadeInUp" data-wow-delay=".8s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4 justify-content-between">
                            <div>
                                <div class="small_tXt">{{ __('messages.documents') }}</div>
                                <div class="stat-value" id="documentsCount">...</div>
                            </div>
                            <span class="{{ margin_start() }}-auto stat-icon bg1">
                                <svg width="12" height="16" viewBox="0 0 12 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2 0C0.896875 0 0 0.896875 0 2V14C0 15.1031 0.896875 16 2 16H10C11.1031 16 12 15.1031 12 14V5H8C7.44688 5 7 4.55312 7 4V0H2ZM8 0V4H12L8 0ZM3.5 8H8.5C8.775 8 9 8.225 9 8.5C9 8.775 8.775 9 8.5 9H3.5C3.225 9 3 8.775 3 8.5C3 8.225 3.225 8 3.5 8ZM3.5 10H8.5C8.775 10 9 10.225 9 10.5C9 10.775 8.775 11 8.5 11H3.5C3.225 11 3 10.775 3 10.5C3 10.225 3.225 10 3.5 10ZM3.5 12H8.5C8.775 12 9 12.225 9 12.5C9 12.775 8.775 13 8.5 13H3.5C3.225 13 3 12.775 3 12.5C3 12.225 3.225 12 3.5 12Z"
                                        fill="#4477C4" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3 col-md-4 col-md-6 col-12  wow fadeInUp" data-wow-delay="1.2s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4 justify-content-between">
                            <div>
                                <div class="small_tXt">{{ __('messages.pdfs') }}</div>
                                <div class="stat-value" id="pdfsCount">...</div>
                            </div>
                            <span class="{{ margin_start() }}-auto stat-icon bg2">
                                <svg width="16" height="14" viewBox="0 0 16 14" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M0 2C0 0.896875 0.896875 0 2 0H14C15.1031 0 16 0.896875 16 2V7.7625C15.4688 7.2875 14.7688 7 14 7H2C1.23125 7 0.53125 7.2875 0 7.7625V2ZM2 8H14C15.1031 8 16 8.89688 16 10V12C16 13.1031 15.1031 14 14 14H2C0.896875 14 0 13.1031 0 12V10C0 8.89688 0.896875 8 2 8ZM10 12C10.2652 12 10.5196 11.8946 10.7071 11.7071C10.8946 11.5196 11 11.2652 11 11C11 10.7348 10.8946 10.4804 10.7071 10.2929C10.5196 10.1054 10.2652 10 10 10C9.73478 10 9.48043 10.1054 9.29289 10.2929C9.10536 10.4804 9 10.7348 9 11C9 11.2652 9.10536 11.5196 9.29289 11.7071C9.48043 11.8946 9.73478 12 10 12ZM14 11C14 10.7348 13.8946 10.4804 13.7071 10.2929C13.5196 10.1054 13.2652 10 13 10C12.7348 10 12.4804 10.1054 12.2929 10.2929C12.1054 10.4804 12 10.7348 12 11C12 11.2652 12.1054 11.5196 12.2929 11.7071C12.4804 11.8946 12.7348 12 13 12C13.2652 12 13.5196 11.8946 13.7071 11.7071C13.8946 11.5196 14 11.2652 14 11Z"
                                        fill="#F58D2E" />
                                </svg>

                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-lg-12 mb-4 mt-4">
                    <div class="card B_shadow">
                        <div class="card-body card-body py-md-4 px-0">
                            <h5 class="fw-semibold  black_color px-md-4 px-2 mb-4">{{ __('messages.recent_files') }}</h5>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('messages.file_name') }}</th>
                                            <th>{{ __('messages.type') }}</th>
                                            <th>{{ __('messages.upload_date') }}</th>
                                            <th>{{ __('messages.size') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody id="filesTableBody">
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <div class="spinner-border" role="status"></div>
                                                <div class="mt-2">{{ __('messages.loading_files') }}...</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        function handleFileUpload(input) {
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileSize = (file.size / (1024 * 1024)).toFixed(2);
                alert(
                    `File "${file.name}" selected for upload!\n\nFile Size: ${fileSize} MB\n\nFile would be uploaded to project files.`
                    );
                // Reset input for next upload
                input.value = '';
            }
        }

        // Load project files from API
        async function loadProjectFiles() {
            try {
                const projectId = getProjectIdFromUrl();
                const response = await api.getFiles({ project_id: projectId });
                
                if (response.code === 200 && response.data && response.data.data) {
                    let files = response.data.data;
                    files = applyClientSideFilters(files);
                    files = applyClientSideSorting(files);
                    displayFiles(files);
                    updateFileCounts(files);
                } else {
                    displayNoFiles();
                }
            } catch (error) {
                console.error('Failed to load files:', error);
                displayNoFiles();
            }
        }
        
        function applyClientSideFilters(files) {
            return files.filter(file => {
                if (currentFilters.fileType) {
                    const fileExt = getFileExtension(file.file_type);
                    if (fileExt !== currentFilters.fileType) return false;
                }
                
                if (currentFilters.dateRange) {
                    const fileDate = new Date(file.created_at);
                    const now = new Date();
                    
                    switch(currentFilters.dateRange) {
                        case 'today':
                            if (fileDate.toDateString() !== now.toDateString()) return false;
                            break;
                        case 'week':
                            const weekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
                            if (fileDate < weekAgo) return false;
                            break;
                        case 'month':
                            const monthAgo = new Date(now.getFullYear(), now.getMonth() - 1, now.getDate());
                            if (fileDate < monthAgo) return false;
                            break;
                    }
                }
                
                if (currentFilters.sizeRange && file.file_size) {
                    const sizeMB = file.file_size / (1024 * 1024);
                    switch(currentFilters.sizeRange) {
                        case 'small':
                            if (sizeMB >= 1) return false;
                            break;
                        case 'medium':
                            if (sizeMB < 1 || sizeMB > 10) return false;
                            break;
                        case 'large':
                            if (sizeMB <= 10) return false;
                            break;
                    }
                }
                
                return true;
            });
        }
        
        function applyClientSideSorting(files) {
            return files.sort((a, b) => {
                let aVal, bVal;
                
                switch(currentSort.field) {
                    case 'name':
                        aVal = (a.original_name || a.name || '').toLowerCase();
                        bVal = (b.original_name || b.name || '').toLowerCase();
                        break;
                    case 'created_at':
                        aVal = new Date(a.created_at);
                        bVal = new Date(b.created_at);
                        break;
                    case 'file_size':
                        aVal = a.file_size || 0;
                        bVal = b.file_size || 0;
                        break;
                    default:
                        return 0;
                }
                
                if (aVal < bVal) return currentSort.direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return currentSort.direction === 'asc' ? 1 : -1;
                return 0;
            });
        }
        
        function displayFiles(files) {
            const tbody = document.getElementById('filesTableBody');
            
            if (!files || files.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><i class="fas fa-folder-open fa-3x text-muted mb-3"></i><h5 class="text-muted">{{ __('messages.no_files_found') }}</h5></td></tr>';
                return;
            }
            
            tbody.innerHTML = files.map(file => {
                const fileIcon = getFileIcon(file.file_type);
                const fileSize = formatFileSize(file.file_size);
                const uploadDate = formatDate(file.created_at);
                
                return `
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                ${fileIcon}
                                <span class="fw-medium text-black">${file.original_name || file.name}</span>
                            </div>
                        </td>
                        <td>${getFileTypeDisplay(file.file_type)}</td>
                        <td>${uploadDate}</td>
                        <td>${fileSize}</td>
                    </tr>
                `;
            }).join('');
        }
        
        function displayNoFiles() {
            const tbody = document.getElementById('filesTableBody');
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4"><i class="fas fa-folder-open fa-3x text-muted mb-3"></i><h5 class="text-muted">{{ __('messages.no_files_found') }}</h5></td></tr>';
        }
        
        function updateFileCounts(files) {
            const totalFiles = files.length;
            const drawings = files.filter(f => ['dwg', 'dxf'].includes(getFileExtension(f.file_type))).length;
            const documents = files.filter(f => ['doc', 'docx', 'txt'].includes(getFileExtension(f.file_type))).length;
            const pdfs = files.filter(f => getFileExtension(f.file_type) === 'pdf').length;
            
            document.getElementById('totalFilesCount').textContent = totalFiles;
            document.getElementById('drawingsCount').textContent = drawings;
            document.getElementById('documentsCount').textContent = documents;
            document.getElementById('pdfsCount').textContent = pdfs;
        }
        
        function getFileIcon(fileType) {
            const ext = getFileExtension(fileType);
            switch(ext) {
                case 'pdf':
                    return '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z" fill="#FEE2E2"/><path d="M8 10C8 8.89688 8.89688 8 10 8H15V12C15 12.5531 15.4469 13 16 13H20V17.5H13.5C12.3969 17.5 11.5 18.3969 11.5 19.5V24H10C8.89688 24 8 23.1031 8 22V10ZM20 12H16V8L20 12Z" fill="#DC2626"/></svg>';
                case 'doc':
                case 'docx':
                    return '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z" fill="#DBEAFE"/><path d="M12 8C10.8969 8 10 8.89688 10 10V22C10 23.1031 10.8969 24 12 24H20C21.1031 24 22 23.1031 22 22V13H18C17.4469 13 17 12.5531 17 12V8H12ZM18 8V12H22L18 8Z" fill="#2563EB"/></svg>';
                case 'jpg':
                case 'jpeg':
                case 'png':
                    return '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z" fill="#F3E8FF"/><path d="M8 11C8 9.89688 8.89688 9 10 9H22C23.1031 9 24 9.89688 24 11V21C24 22.1031 23.1031 23 22 23H10C8.89688 23 8 22.1031 8 21V11Z" fill="#9333EA"/></svg>';
                default:
                    return '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M24 0C28.4183 0 32 3.58172 32 8V24C32 28.4183 28.4183 32 24 32H8C3.58172 32 0 28.4183 0 24V8C0 3.58172 3.58172 0 8 0H24Z" fill="#F3F4F6"/></svg>';
            }
        }
        
        function getFileExtension(fileType) {
            if (fileType.includes('/')) {
                return fileType.split('/')[1].toLowerCase();
            }
            return fileType.toLowerCase();
        }
        
        function getFileTypeDisplay(fileType) {
            const ext = getFileExtension(fileType);
            return ext.toUpperCase();
        }
        
        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }
        
        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }

        // Filter and sort variables (global scope)
        let currentFilters = { fileType: '', dateRange: '', sizeRange: '' };
        let currentSort = { field: 'created_at', direction: 'desc' };

        document.addEventListener('DOMContentLoaded', function() {
            // Load files on page load
            loadProjectFiles();
            
            // Filter button functionality
            const filterBtn = document.querySelector('.filter-btn');
            if (filterBtn) {
                filterBtn.addEventListener('click', showFilterModal);
            }

            // Sort button functionality
            const sortBtn = document.querySelector('.sort-btn');
            if (sortBtn) {
                sortBtn.addEventListener('click', showSortModal);
            }
            
            function showFilterModal() {
                const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                
                let headerHTML = '';
                if (isRtl) {
                    headerHTML = `
                        <div class="modal-header" style="position: relative !important; padding: 1rem 1rem 1rem 1rem !important; min-height: 60px !important; display: flex !important; align-items: center !important;">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" style="position: absolute !important; left: 1rem !important;"></button>
                            <h5 class="modal-title" style="position: absolute !important; right: 1rem !important; margin: 0 !important;">{{ __('messages.filter_files') }}</h5>
                        </div>`;
                } else {
                    headerHTML = `
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('messages.filter_files') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>`;
                }
                
                modal.innerHTML = `
                    <div class="modal-dialog">
                        <div class="modal-content">
                            ` + headerHTML + `
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-medium">{{ __('messages.file_type') }}</label>
                                    <select class="form-select" id="filterFileType">
                                        <option value="">{{ __('messages.all_types') }}</option>
                                        <option value="pdf">PDF</option>
                                        <option value="png">PNG</option>
                                        <option value="jpg">JPG</option>
                                        <option value="doc">DOC/DOCX</option>
                                        <option value="dwg">DWG</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">{{ __('messages.upload_date') }}</label>
                                    <select class="form-select" id="filterDate">
                                        <option value="">{{ __('messages.all_dates') }}</option>
                                        <option value="today">{{ __('messages.today') }}</option>
                                        <option value="week">{{ __('messages.this_week') }}</option>
                                        <option value="month">{{ __('messages.this_month') }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-medium">{{ __('messages.file_size') }}</label>
                                    <select class="form-select" id="filterSize">
                                        <option value="">{{ __('messages.all_sizes') }}</option>
                                        <option value="small">{{ __('messages.less_than_1mb') }}</option>
                                        <option value="medium">{{ __('messages.1_to_10mb') }}</option>
                                        <option value="large">{{ __('messages.more_than_10mb') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="clearFilters()">{{ __('messages.clear') }}</button>
                                <button type="button" class="btn orange_btn" onclick="applyFilters()">{{ __('messages.apply') }}</button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('hidden.bs.modal', () => modal.remove());
            }
            
            function showSortModal() {
                const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                
                let headerHTML = '';
                if (isRtl) {
                    headerHTML = `
                        <div class="modal-header" style="position: relative !important; padding: 1rem 1rem 1rem 1rem !important; min-height: 60px !important; display: flex !important; align-items: center !important;">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" style="position: absolute !important; left: 1rem !important;"></button>
                            <h5 class="modal-title" style="position: absolute !important; right: 1rem !important; margin: 0 !important;">{{ __('messages.sort_files') }}</h5>
                        </div>`;
                } else {
                    headerHTML = `
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('messages.sort_files') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>`;
                }
                
                modal.innerHTML = `
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            ` + headerHTML + `
                            <div class="modal-body p-0">
                                <div class="list-group list-group-flush">
                                    <button class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4" onclick="applySorting('name', 'asc')">
                                        <i class="fas fa-sort-alpha-down ` + (isRtl ? 'ms-2' : 'me-2') + ` text-primary"></i>
                                        <span>{{ __('messages.name_a_z') }}</span>
                                    </button>
                                    <button class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4" onclick="applySorting('name', 'desc')">
                                        <i class="fas fa-sort-alpha-up ` + (isRtl ? 'ms-2' : 'me-2') + ` text-primary"></i>
                                        <span>{{ __('messages.name_z_a') }}</span>
                                    </button>
                                    <button class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4" onclick="applySorting('created_at', 'desc')">
                                        <i class="fas fa-calendar-alt ` + (isRtl ? 'ms-2' : 'me-2') + ` text-success"></i>
                                        <span>{{ __('messages.newest_first') }}</span>
                                    </button>
                                    <button class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4" onclick="applySorting('created_at', 'asc')">
                                        <i class="fas fa-calendar-alt ` + (isRtl ? 'ms-2' : 'me-2') + ` text-muted"></i>
                                        <span>{{ __('messages.oldest_first') }}</span>
                                    </button>
                                    <button class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4" onclick="applySorting('file_size', 'desc')">
                                        <i class="fas fa-weight-hanging ` + (isRtl ? 'ms-2' : 'me-2') + ` text-warning"></i>
                                        <span>{{ __('messages.largest_first') }}</span>
                                    </button>
                                    <button class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4" onclick="applySorting('file_size', 'asc')">
                                        <i class="fas fa-feather-alt ` + (isRtl ? 'ms-2' : 'me-2') + ` text-info"></i>
                                        <span>{{ __('messages.smallest_first') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();
                modal.addEventListener('hidden.bs.modal', () => modal.remove());
            }
            
            window.applyFilters = function() {
                currentFilters.fileType = document.getElementById('filterFileType').value;
                currentFilters.dateRange = document.getElementById('filterDate').value;
                currentFilters.sizeRange = document.getElementById('filterSize').value;
                bootstrap.Modal.getInstance(document.querySelector('.modal.show')).hide();
                loadProjectFiles();
            };
            
            window.clearFilters = function() {
                currentFilters = { fileType: '', dateRange: '', sizeRange: '' };
                bootstrap.Modal.getInstance(document.querySelector('.modal.show')).hide();
                loadProjectFiles();
            };
            
            window.applySorting = function(field, direction) {
                currentSort.field = field;
                currentSort.direction = direction;
                bootstrap.Modal.getInstance(document.querySelector('.modal.show')).hide();
                loadProjectFiles();
            };

            // View file buttons
            const viewButtons = document.querySelectorAll('.btn.orange_btn');
            viewButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const fileName = row.querySelector('.fw-medium').textContent;
                    const fileType = row.cells[1].textContent;
                    const fileSize = row.cells[3].textContent;

                    // Simulate file viewing
                    if (fileType === 'PDF') {
                        alert(
                            `Opening PDF Viewer for: ${fileName}\n\nFile would open in:\n• Built-in PDF viewer\n• {{ __('messages.download') }} option available\n• {{ __('messages.print') }} and share options`
                        );
                    } else if (fileType === 'DWG') {
                        alert(
                            `Opening CAD Viewer for: ${fileName}\n\nFile would open in:\n• AutoCAD Web viewer\n• Zoom and pan capabilities\n• Layer management\n• Measurement tools`
                        );
                    } else if (fileType === 'JPG') {
                        alert(
                            `Opening Image Viewer for: ${fileName}\n\nFile would open in:\n• Full-screen image viewer\n• Zoom and pan\n• {{ __('messages.download') }} option`
                        );
                    } else {
                        alert(
                            `Opening ${fileType} file: ${fileName}\n\nFile Size: ${fileSize}\n\nFile would be downloaded or opened in appropriate application.`
                        );
                    }
                });
            });
        });
    </script>

@endsection

@push('scripts')
    <!-- API Dependencies -->
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
@endpush
