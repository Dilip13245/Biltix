@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Plans')

@section('content')
    <div class="content-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2>{{ __('messages.plans') }}</h2>
            <p>{{ __('messages.view_markup_plans') }}</p>
        </div>
        <button class="btn orange_btn" data-bs-toggle="modal" data-bs-target="#uploadPlanModal" data-permission="plans:upload" id="uploadPlanBtn" style="display: none;">
            <i class="fas fa-arrow-up"></i>
            {{ __('messages.upload_plan') }}
        </button>
    </div>

    <div id="plansContainer">
        <div class="col-12 text-center py-4">
            <div class="spinner-border" role="status"></div>
            <div class="mt-2">{{ __('messages.loading_plans') }}</div>
        </div>
    </div>

    <div class="CarDs-grid">
        <!-- Plan Card 1 -->
        <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
            <div class="plan-image">
                <img src="{{ asset('website/images/place1.png') }}" alt="Ground Floor Plan">
            </div>
            <div class="carD-details">
                <h3>{{ __('messages.ground_floor_plan') }}</h3>
                <div class="plan-actions d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                        onclick="openPlanViewer('{{ asset('website/images/place1.png') }}', 'Ground Floor Plan', 'Architectural', 'Rev. 3.2', '2.4 MB', '2 days ago')">
                        {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                    </button>
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill" onclick="replacePlan(1)" data-permission="plans:upload" style="display: none;">
                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill rounded-pill" onclick="deletePlan(1)" data-permission="plans:delete" style="display: none;">
                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Plan Card 2 -->
        <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
            <div class="plan-image">
                <img src="{{ asset('website/images/place2.png') }}" alt="Second Floor Plan">
            </div>
            <div class="carD-details">
                <h3>{{ __('messages.second_floor_plan') }}</h3>
                <div class="plan-actions d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                        onclick="openPlanViewer('{{ asset('website/images/place2.png') }}', 'Second Floor Plan', 'Architectural', 'Rev. 2.1', '1.8 MB', '1 week ago')">
                        {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                    </button>
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill" onclick="replacePlan(2)" data-permission="plans:upload" style="display: none;">
                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill rounded-pill" onclick="deletePlan(2)" data-permission="plans:delete" style="display: none;">
                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Plan Card 3 -->
        <div class="CustOm_Card wow fadeInUp" data-wow-delay=".8s">
            <div class="plan-image">
                <img src="{{ asset('website/images/place3.png') }}" alt="Front Elevation">
            </div>
            <div class="carD-details">
                <h3>{{ __('messages.front_elevation') }}</h3>
                <div class="plan-actions d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                        onclick="openPlanViewer('{{ asset('website/images/place3.png') }}', 'Front Elevation', 'Architectural', 'Rev. 1.5', '3.2 MB', '3 days ago')">
                        {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                    </button>
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill" onclick="replacePlan(3)" data-permission="plans:upload" style="display: none;">
                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill rounded-pill" onclick="deletePlan(3)" data-permission="plans:delete" style="display: none;">
                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Plan Card 4 -->
        <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.2s">
            <div class="plan-image">
                <img src="{{ asset('website/images/place4.png') }}" alt="Building Section A-A">
            </div>
            <div class="carD-details">
                <h3>{{ __('messages.building_section') }}</h3>
                <div class="plan-actions d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                        onclick="openPlanViewer('{{ asset('website/images/place4.png') }}', 'Building Section A-A', 'Structural', 'Rev. 2.0', '2.1 MB', '5 days ago')">
                        {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                    </button>
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill" onclick="replacePlan(4)" data-permission="plans:upload" style="display: none;">
                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill rounded-pill" onclick="deletePlan(4)" data-permission="plans:delete" style="display: none;">
                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Plan Card 5 -->
        <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.3s">
            <div class="plan-image">
                <img src="{{ asset('website/images/place5.png') }}" alt="Basement Plan">
            </div>
            <div class="carD-details">
                <h3>{{ __('messages.basement_plan') }}</h3>
                <div class="plan-actions d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                        onclick="openPlanViewer('{{ asset('website/images/place5.png') }}', 'Basement Plan', 'Architectural', 'Rev. 1.8', '1.5 MB', '1 week ago')">
                        {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                    </button>
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill" onclick="replacePlan(5)" data-permission="plans:upload" style="display: none;">
                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill rounded-pill" onclick="deletePlan(5)" data-permission="plans:delete" style="display: none;">
                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Plan Card 6 -->
        <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.6s">
            <div class="plan-image">
                <img src="{{ asset('website/images/place6.png') }}" alt="Roof Plan">
            </div>
            <div class="carD-details">
                <h3>{{ __('messages.roof_plan') }}</h3>
                <div class="plan-actions d-flex gap-2 mt-3">
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                        onclick="openPlanViewer('{{ asset('website/images/place6.png') }}', 'Roof Plan', 'Architectural', 'Rev. 1.2', '1.2 MB', '2 weeks ago')">
                        {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                    </button>
                    <button class="btn btn-primary btn-sm flex-fill rounded-pill" onclick="replacePlan(6)" data-permission="plans:upload" style="display: none;">
                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                    </button>
                    <button class="btn btn-danger btn-sm flex-fill rounded-pill" onclick="deletePlan(6)" data-permission="plans:delete" style="display: none;">
                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @include('website.modals.upload-plan-modal')
    @include('website.modals.plan-viewer-modal')
    @include('website.modals.drawing-modal')

    <script>
        let allPlans = [];
        
        // Load plans from API
        async function loadPlans() {
            try {
                const projectId = getProjectIdFromUrl();
                const response = await api.getPlans({ project_id: projectId });
                
                if (response.code === 200 && response.data) {
                    let plans = response.data.data || response.data;
                    if (!Array.isArray(plans)) {
                        plans = [];
                    }
                    allPlans = plans;
                    displayPlans(plans);
                } else {
                    displayNoPlans();
                }
            } catch (error) {
                console.error('Failed to load plans:', error);
                displayNoPlans();
            }
        }
        
        function displayPlans(plans) {
            const container = document.getElementById('plansContainer');
            
            if (!plans || plans.length === 0) {
                displayNoPlans();
                return;
            }
            
            container.className = 'CarDs-grid';
            container.innerHTML = plans.map((plan, index) => {
                const fileIcon = getFileIcon(plan.file_type);
                const fileSize = formatFileSize(plan.file_size);
                const uploadDate = formatDate(plan.created_at);
                const isImage = isImageFile(plan.file_type);
                
                const fullImagePath = plan.file_path.startsWith('http') ? plan.file_path : `{{ asset('storage') }}/${plan.file_path}`;
                
                return `
                    <div class="CustOm_Card wow fadeInUp" data-wow-delay="${index * 0.2}s">
                        <div class="plan-image">
                            ${isImage ? 
                                `<img src="${fullImagePath}" alt="${plan.title}">` :
                                `<div class="file-icon-container d-flex align-items-center justify-content-center" style="height: 200px; background: #f8f9fa;">
                                    <i class="${fileIcon} fa-4x text-primary"></i>
                                </div>`
                            }
                        </div>
                        <div class="carD-details">
                            <h3>${plan.title}</h3>
                            <div class="plan-actions d-flex gap-2 mt-3">
                                <button class="btn btn-primary btn-sm flex-fill rounded-pill"
                                    onclick="viewPlan(${plan.id})">
                                    {{ __('messages.view_file') }} <i class="fas fa-eye ms-2"></i>
                                </button>
                                ${window.userPermissions && window.userPermissions.canUpload() ? 
                                    `<button class="btn btn-primary btn-sm flex-fill rounded-pill" 
                                        onclick="replacePlan(${plan.id})" data-permission="plans:upload">
                                        {{ __('messages.replace') }} <i class="fas fa-sync ms-2"></i>
                                    </button>` : ''}
                                ${window.userPermissions && window.userPermissions.canDelete() ? 
                                    `<button class="btn btn-danger btn-sm flex-fill rounded-pill" 
                                        onclick="deletePlan(${plan.id})" data-permission="plans:delete">
                                        {{ __('messages.delete') }} <i class="fas fa-trash ms-2"></i>
                                    </button>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        function displayNoPlans() {
            const container = document.getElementById('plansContainer');
            container.className = 'd-flex justify-content-center align-items-center';
            container.style.minHeight = '400px';
            container.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-drafting-compass fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted mb-2">{{ __('messages.no_plans_found') }}</h5>
                    <p class="text-muted">{{ __('messages.upload_plan') }}</p>
                </div>
            `;
        }
        
        function getFileIcon(fileType) {
            const type = fileType?.toLowerCase();
            if (type?.includes('pdf')) return 'fas fa-file-pdf';
            if (type?.includes('dwg')) return 'fas fa-drafting-compass';
            if (type?.includes('image') || type?.includes('jpg') || type?.includes('png')) return 'fas fa-image';
            if (type?.includes('word') || type?.includes('doc')) return 'fas fa-file-word';
            if (type?.includes('excel') || type?.includes('sheet')) return 'fas fa-file-excel';
            return 'fas fa-file';
        }
        
        function getFileTypeName(fileType) {
            const type = fileType?.toLowerCase();
            if (type?.includes('pdf')) return 'PDF Document';
            if (type?.includes('dwg')) return 'AutoCAD Drawing';
            if (type?.includes('image/jpeg') || type?.includes('jpg')) return 'JPEG Image';
            if (type?.includes('image/png') || type?.includes('png')) return 'PNG Image';
            if (type?.includes('image/gif')) return 'GIF Image';
            if (type?.includes('image')) return 'Image File';
            if (type?.includes('wordprocessingml') || type?.includes('msword') || type?.includes('doc')) return 'Word Document';
            if (type?.includes('spreadsheetml') || type?.includes('excel') || type?.includes('xls')) return 'Excel Spreadsheet';
            if (type?.includes('presentationml') || type?.includes('powerpoint') || type?.includes('ppt')) return 'PowerPoint Presentation';
            if (type?.includes('text/plain')) return 'Text File';
            if (type?.includes('application/zip')) return 'ZIP Archive';
            if (type?.includes('application/rar')) return 'RAR Archive';
            return fileType?.split('/').pop()?.toUpperCase() || 'Unknown File';
        }
        
        function isImageFile(fileType) {
            const type = fileType?.toLowerCase();
            return type?.includes('image') || type?.includes('jpg') || type?.includes('jpeg') || type?.includes('png');
        }
        
        function formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('{{ app()->getLocale() }}', { year: 'numeric', month: 'short', day: 'numeric' });
        }
        
        function getProjectIdFromUrl() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }
        
        function viewPlan(planId) {
            const plan = allPlans.find(p => p.id === planId);
            if (plan) {
                openPlanViewer(plan);
            }
        }
        
        // Plan viewer functions - wrapped to avoid conflicts
        const PlanViewer = {
            currentZoom: 1,
            currentPlanData: null,
            isDragging: false,
            startX: 0,
            startY: 0,
            scrollLeft: 0,
            scrollTop: 0
        };
        
        function loadPlanContent(planData) {
            PlanViewer.currentPlanData = planData;
            PlanViewer.currentZoom = 1;
            const container = document.getElementById('planContent');
            const fileType = planData.file_type?.toLowerCase();
            
            // Reset container
            container.innerHTML = '';
            container.style.overflow = 'auto';
            
            // Reset container display properties
            const parentContainer = container.parentElement;
            if (fileType?.includes('pdf')) {
                // Remove centering classes for PDF
                parentContainer.classList.remove('d-flex', 'align-items-center', 'justify-content-center');
                parentContainer.classList.add('d-block');
                parentContainer.style.height = '100%';
            } else {
                // Restore centering classes for other files
                parentContainer.classList.remove('d-block');
                parentContainer.classList.add('d-flex', 'align-items-center', 'justify-content-center');
            }
            
            if (isImageFile(fileType)) {
                // Image files
                container.innerHTML = `<img id="planImage" src="${planData.file_path}" alt="${planData.title}" style="cursor: grab; transition: transform 0.3s; max-width: 100%; height: auto; display: block; margin: 0 auto;">`;
                setupImageInteractions();
            } else if (fileType?.includes('pdf')) {
                // PDF files - use full content area without centering
                container.style.height = '100%';
                container.style.display = 'block';
                container.style.alignItems = 'stretch';
                container.style.justifyContent = 'stretch';
                container.innerHTML = `
                    <iframe id="pdfViewer" 
                            src="${planData.file_path}#toolbar=1&navpanes=1&scrollbar=1&view=FitH&zoom=page-fit" 
                            style="width: 100%; height: 100%; border: none; display: block; margin: 0; padding: 0;"
                            frameborder="0">
                    </iframe>
                `;
            } else if (fileType?.includes('word') || fileType?.includes('doc')) {
                // Word documents - show download options directly
                container.innerHTML = `
                    <div class="text-center p-5 d-flex flex-column justify-content-center align-items-center" style="height: 100%; min-height: 400px;">
                        <i class="${getFileIcon(planData.file_type)} fa-5x text-primary mb-3"></i>
                        <h5>${planData.title}</h5>
                        <p class="text-muted mb-3">{{ __('messages.word_document') }}</p>
                        <p class="text-muted small mb-4">{{ __('messages.word_preview_info') }}</p>
                        <div class="d-flex gap-2">
                            <a href="${planData.file_path}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>{{ __('messages.open_file') }}
                            </a>
                            <a href="${planData.file_path}" download class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>{{ __('messages.download') }}
                            </a>
                        </div>
                    </div>
                `;
            } else if (fileType?.includes('excel') || fileType?.includes('sheet')) {
                // Excel files - show download options
                container.innerHTML = `
                    <div class="text-center p-5 d-flex flex-column justify-content-center align-items-center" style="height: 100%; min-height: 400px;">
                        <i class="${getFileIcon(planData.file_type)} fa-5x text-primary mb-3"></i>
                        <h5>${planData.title}</h5>
                        <p class="text-muted mb-3">{{ __('messages.excel_document') }}</p>
                        <p class="text-muted small mb-4">{{ __('messages.excel_preview_info') }}</p>
                        <div class="d-flex gap-2">
                            <a href="${planData.file_path}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>{{ __('messages.open_file') }}
                            </a>
                            <a href="${planData.file_path}" download class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>{{ __('messages.download') }}
                            </a>
                        </div>
                    </div>
                `;
            } else {
                // Other files - show icon and download link
                container.innerHTML = `
                    <div class="text-center p-5 d-flex flex-column justify-content-center align-items-center" style="height: 100%; min-height: 400px;">
                        <i class="${getFileIcon(planData.file_type)} fa-5x text-primary mb-3"></i>
                        <h5>${planData.title}</h5>
                        <p class="text-muted mb-3">${planData.file_type?.toUpperCase()} {{ __('messages.file') }}</p>
                        <div class="d-flex gap-2">
                            <a href="${planData.file_path}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>{{ __('messages.open_file') }}
                            </a>
                            <a href="${planData.file_path}" download class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>{{ __('messages.download') }}
                            </a>
                        </div>
                    </div>
                `;
            }
        }
        
        function setupImageInteractions() {
            const image = document.getElementById('planImage');
            if (!image) return;
            
            const container = document.getElementById('planViewerContainer');
            
            // Mouse events for dragging
            image.addEventListener('mousedown', (e) => {
                PlanViewer.isDragging = true;
                image.style.cursor = 'grabbing';
                PlanViewer.startX = e.pageX - container.offsetLeft;
                PlanViewer.startY = e.pageY - container.offsetTop;
                PlanViewer.scrollLeft = container.scrollLeft;
                PlanViewer.scrollTop = container.scrollTop;
                e.preventDefault();
            });
            
            image.addEventListener('mouseleave', () => {
                PlanViewer.isDragging = false;
                image.style.cursor = 'grab';
            });
            
            image.addEventListener('mouseup', () => {
                PlanViewer.isDragging = false;
                image.style.cursor = 'grab';
            });
            
            image.addEventListener('mousemove', (e) => {
                if (!PlanViewer.isDragging) return;
                e.preventDefault();
                const x = e.pageX - container.offsetLeft;
                const y = e.pageY - container.offsetTop;
                const walkX = (x - PlanViewer.startX) * 2;
                const walkY = (y - PlanViewer.startY) * 2;
                container.scrollLeft = PlanViewer.scrollLeft - walkX;
                container.scrollTop = PlanViewer.scrollTop - walkY;
            });
            
            // Wheel zoom
            container.addEventListener('wheel', (e) => {
                e.preventDefault();
                const delta = e.deltaY > 0 ? -0.1 : 0.1;
                PlanViewer.currentZoom = Math.max(0.1, Math.min(5, PlanViewer.currentZoom + delta));
                image.style.transform = `scale(${PlanViewer.currentZoom})`;
            });
        }
        
        // Make zoom functions global
        window.zoomIn = function() {
            const image = document.getElementById('planImage');
            const pdfViewer = document.getElementById('pdfViewer');
            
            if (image) {
                PlanViewer.currentZoom = Math.min(PlanViewer.currentZoom * 1.2, 5);
                image.style.transform = `scale(${PlanViewer.currentZoom})`;
                image.style.transformOrigin = 'center center';
            } else if (pdfViewer) {
                // For PDF, try to send zoom command or reload with zoom parameter
                try {
                    const currentSrc = pdfViewer.src;
                    if (currentSrc.includes('#')) {
                        const baseUrl = currentSrc.split('#')[0];
                        pdfViewer.src = baseUrl + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH&zoom=150';
                    }
                } catch (e) {
                    console.log('PDF zoom not supported');
                }
            }
        };
        
        window.zoomOut = function() {
            const image = document.getElementById('planImage');
            const pdfViewer = document.getElementById('pdfViewer');
            
            if (image) {
                PlanViewer.currentZoom = Math.max(PlanViewer.currentZoom / 1.2, 0.1);
                image.style.transform = `scale(${PlanViewer.currentZoom})`;
                image.style.transformOrigin = 'center center';
            } else if (pdfViewer) {
                // For PDF, try to send zoom command or reload with zoom parameter
                try {
                    const currentSrc = pdfViewer.src;
                    if (currentSrc.includes('#')) {
                        const baseUrl = currentSrc.split('#')[0];
                        pdfViewer.src = baseUrl + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH&zoom=75';
                    }
                } catch (e) {
                    console.log('PDF zoom not supported');
                }
            }
        };
        
        window.resetZoom = function() {
            const image = document.getElementById('planImage');
            const pdfViewer = document.getElementById('pdfViewer');
            
            if (image) {
                PlanViewer.currentZoom = 1;
                image.style.transform = `scale(${PlanViewer.currentZoom})`;
                image.style.transformOrigin = 'center center';
            } else if (pdfViewer) {
                // For PDF, reload to reset zoom
                const currentSrc = pdfViewer.src;
                if (currentSrc.includes('#')) {
                    const baseUrl = currentSrc.split('#')[0];
                    pdfViewer.src = baseUrl + '#toolbar=1&navpanes=1&scrollbar=1&view=FitH&zoom=page-width';
                }
            }
        };
        
        window.downloadPlan = function() {
            if (PlanViewer.currentPlanData) {
                const link = document.createElement('a');
                link.href = PlanViewer.currentPlanData.file_path;
                link.download = PlanViewer.currentPlanData.title;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        };
        
        function openPlanViewer(plan) {
            // Fix file path for viewer
            const planWithFullPath = {
                ...plan,
                file_path: plan.file_path.startsWith('http') ? plan.file_path : `{{ asset('storage') }}/${plan.file_path}`
            };
            
            // Populate plan information
            document.getElementById('planInfoName').textContent = plan.title;
            document.getElementById('planInfoType').textContent = plan.drawing_number;
            document.getElementById('planInfoFileType').textContent = getFileTypeName(plan.file_type);
            document.getElementById('planInfoSize').textContent = formatFileSize(plan.file_size);
            document.getElementById('planInfoUpdated').textContent = formatDate(plan.created_at);
            
            // Load content based on file type
            loadPlanContent(planWithFullPath);
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('planViewerModal'));
            modal.show();
        }
        
        async function deletePlan(planId) {
            // Skip permission check - buttons already control access
            
            const plan = allPlans.find(p => p.id === planId);
            const planTitle = plan ? plan.title : 'this plan';
            
            confirmationModal.show({
                title: '{{ __('messages.delete_plan') }}',
                message: `{{ __('messages.confirm_delete_plan_message') }} "${planTitle}"?`,
                icon: 'fas fa-trash text-danger',
                confirmText: '{{ __('messages.delete') }}',
                cancelText: '{{ __('messages.cancel') }}',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        const response = await api.deletePlan({ plan_id: planId });
                        if (response.code === 200) {
                            toastr.success('{{ __('messages.plan_deleted_successfully') }}');
                            loadPlans();
                        } else {
                            toastr.error('{{ __('messages.failed_to_delete_plan') }}');
                        }
                    } catch (error) {
                        console.error('Delete plan error:', error);
                        toastr.error('{{ __('messages.failed_to_delete_plan') }}');
                    }
                }
            });
        }
        
        function replacePlan(planId) {
            // Skip permission check - buttons already control access
            
            // Create hidden file input for single file selection
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.accept = '*/*';
            fileInput.style.display = 'none';
            
            fileInput.onchange = function(e) {
                const file = e.target.files[0];
                if (!file) return;
                
                console.log('File selected for replacement:', file.name);
                
                // Check if file is image
                if (file.type.startsWith('image/')) {
                    // Store data for drawing modal
                    window.replacingPlanId = planId;
                    window.replacingFile = file;
                    
                    // Open drawing modal
                    if (typeof openDrawingModal === 'function') {
                        openDrawingModal({
                            title: 'Plan Replacement Markup',
                            saveButtonText: 'Replace Plan',
                            mode: 'image',
                            onSave: function(markedUpImageData) {
                                replaceWithMarkup(planId, markedUpImageData);
                            }
                        });
                        
                        // Load image to canvas when modal opens
                        const drawingModal = document.getElementById('drawingModal');
                        if (drawingModal) {
                            drawingModal.addEventListener('shown.bs.modal', function() {
                                loadImageToCanvas(file);
                            }, { once: true });
                        }
                    } else {
                        // Fallback to direct replacement
                        replaceDirectly(planId, file);
                    }
                } else {
                    // Direct replacement for non-image files
                    replaceDirectly(planId, file);
                }
                
                // Clean up
                document.body.removeChild(fileInput);
            };
            
            // Add to DOM and trigger click
            document.body.appendChild(fileInput);
            fileInput.click();
        }
        
        async function replaceWithMarkup(planId, markedUpImageData) {
            try {
                const response = await fetch(markedUpImageData);
                const blob = await response.blob();
                
                const formData = new FormData();
                formData.append('plan_id', planId);
                formData.append('file', blob, 'marked_plan.png');
                
                const apiResponse = await api.replacePlan(formData);
                if (apiResponse.code === 200) {
                    const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
                    if (drawingModal) drawingModal.hide();
                    
                    toastr.success('{{ __('messages.plan_replaced_successfully') }}');
                    loadPlans();
                } else {
                    toastr.error('{{ __('messages.failed_to_replace_plan') }}');
                }
            } catch (error) {
                console.error('Replace with markup error:', error);
                toastr.error('{{ __('messages.failed_to_replace_plan') }}');
            }
        }
        
        async function replaceDirectly(planId, file) {
            try {
                const formData = new FormData();
                formData.append('plan_id', planId);
                formData.append('file', file);
                
                console.log('Replacing plan:', planId, 'with file:', file.name);
                const response = await api.replacePlan(formData);
                console.log('Replace response:', response);
                
                if (response.code === 200) {
                    toastr.success('{{ __('messages.plan_replaced_successfully') }}');
                    loadPlans();
                } else {
                    toastr.error('{{ __('messages.failed_to_replace_plan') }}: ' + (response.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Replace directly error:', error);
                toastr.error('{{ __('messages.failed_to_replace_plan') }}: ' + error.message);
            }
        }
        
        // Upload mixed files (images with markup + documents)
        async function uploadMixedFiles(markedUpImageData) {
            try {
                const formData = window.planFormData;
                const { images, documents } = window.allSelectedFiles;
                
                // Remove original files
                formData.delete('files[]');
                
                // Add marked up image
                if (markedUpImageData) {
                    const response = await fetch(markedUpImageData);
                    const blob = await response.blob();
                    formData.append('files[]', blob, 'marked_plan.png');
                }
                
                // Add document files as-is
                documents.forEach((file, index) => {
                    formData.append('files[]', file);
                });
                
                const apiResponse = await api.uploadPlan(formData);
                if (apiResponse.code === 200) {
                    const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
                    if (drawingModal) drawingModal.hide();
                    
                    toastr.success('{{ __('messages.plan_uploaded_successfully') }}');
                    loadPlans();
                } else {
                    toastr.error('{{ __('messages.failed_to_upload_plan') }}');
                }
            } catch (error) {
                console.error('Upload mixed files error:', error);
                toastr.error('{{ __('messages.failed_to_upload_plan') }}');
            }
        }
        
        // Upload Plan Form Handler
        document.addEventListener('DOMContentLoaded', function() {
            // Apply permissions after DOM is loaded
            if (window.permissions && window.permissions.user) {
                window.permissions.applyPermissions();
            }
            
            // Initialize user permissions - contractors have all rights
            window.userPermissions = {
                canUpload: function() {
                    return true; // Contractors have upload rights
                },
                canDelete: function() {
                    return true; // Contractors have delete rights
                }
            };
            
            // Show buttons based on permissions
            setTimeout(() => {
                if (window.userPermissions.canUpload()) {
                    document.getElementById('uploadPlanBtn').style.display = 'inline-block';
                    document.querySelectorAll('[data-permission="plans:upload"]').forEach(btn => {
                        btn.style.display = 'inline-block';
                    });
                }
                
                if (window.userPermissions.canDelete()) {
                    document.querySelectorAll('[data-permission="plans:delete"]').forEach(btn => {
                        btn.style.display = 'inline-block';
                    });
                }
            }, 100);
            
            loadPlans();
            
            const uploadForm = document.getElementById('uploadPlanForm');
            if (uploadForm) {
                console.log('Upload form found, adding event listener');
                uploadForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    console.log('Form submitted');
                    
                    const fileInput = document.getElementById('planFiles');
                    const files = fileInput.files;
                    
                    if (!files || files.length === 0) {
                        toastr.warning('{{ __('messages.please_select_files') }}');
                        return;
                    }
                    
                    // Store form data
                    window.planFormData = new FormData(uploadForm);
                    const projectId = getProjectIdFromUrl();
                    window.planFormData.append('project_id', projectId);
                    
                    // Separate image and document files
                    const imageFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
                    const documentFiles = Array.from(files).filter(file => !file.type.startsWith('image/'));
                    
                    // Store all files for later processing
                    window.allSelectedFiles = { images: imageFiles, documents: documentFiles };
                    
                    if (imageFiles.length > 0) {
                        // Close upload modal
                        const uploadModal = bootstrap.Modal.getInstance(document.getElementById('uploadPlanModal'));
                        if (uploadModal) uploadModal.hide();
                        
                        // Open drawing modal for images
                        setTimeout(() => {
                            // Check if drawing modal function exists
                            if (typeof openDrawingModal === 'function') {
                                openDrawingModal({
                                    title: 'Plan Markup',
                                    saveButtonText: 'Save Plan',
                                    mode: 'image',
                                    onSave: function(markedUpImageData) {
                                        uploadMixedFiles(markedUpImageData);
                                    }
                                });
                            } else {
                                console.error('Drawing modal not available, uploading directly');
                                uploadPlanDirectly();
                            }
                            
                            window.selectedFiles = imageFiles;
                            
                            // Add event listener only if modal exists
                            const drawingModal = document.getElementById('drawingModal');
                            if (drawingModal) {
                                drawingModal.addEventListener('shown.bs.modal', function() {
                                    if (imageFiles.length === 1) {
                                        loadImageToCanvas(imageFiles[0]);
                                    } else {
                                        loadMultipleFiles(imageFiles);
                                    }
                                }, { once: true });
                            }
                        }, 300);
                    } else {
                        // Only documents, upload directly
                        uploadPlanDirectly();
                    }
                });
            }
            
            window.uploadPlanDirectly = async function() {
                try {
                    const response = await api.uploadPlan(window.planFormData);
                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('uploadPlanModal')).hide();
                        toastr.success('{{ __('messages.plan_uploaded_successfully') }}');
                        document.getElementById('uploadPlanForm').reset();
                        loadPlans();
                    } else {
                        toastr.error('{{ __('messages.failed_to_upload_plan') }}');
                    }
                } catch (error) {
                    console.error('Upload plan error:', error);
                    toastr.error('{{ __('messages.failed_to_upload_plan') }}');
                }
            }
        });
    </script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
    <script src="{{ asset('website/js/confirmation-modal.js') }}"></script>

@endsection


