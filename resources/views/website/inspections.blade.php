@extends('website.layout.app')

@section('title', 'Inspections')

@section('content')
    <div class="content-header">
        <h2>{{ __('messages.inspections') }}</h2>
        <p>{{ __('messages.manage_track_inspections') }}</p>
    </div>
    <div class="container-fluid ">
        <div class="row g-4 px-md-4 wow fadeInUp" data-wow-delay="1.2s">
            <!-- Create New Inspection -->
            <div class="col-lg-6">
                <div class="card h-100 B_shadow">
                    <div class="card-body p-md-4">
                        <h5 class="mb-3 mb-md-4 fw-semibold black_color">{{ __('messages.create_new_inspection') }}</h5>
                        @can('inspections', 'create')
                            <button class="btn  orange_btn w-100 mb-3 mb-md-4 justify-content-center py-3" data-bs-toggle="modal"
                                data-bs-target="#createInspectionModal" data-permission="inspections:create">
                                <i class="fas fa-plus"></i>
                                {{ __('messages.create_inspection') }}
                            </button>
                        @endcan
                        <div
                            class="file_upload_card d-flex flex-column align-items-center justify-content-center  text-muted">
                            <svg width="28" height="37" viewBox="0 0 28 37" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_861_2044)">
                                    <g clip-path="url(#clip1_861_2044)">
                                        <path
                                            d="M14 0.5C11.0609 0.5 8.55781 2.37734 7.63672 5H5C2.51797 5 0.5 7.01797 0.5 9.5V32C0.5 34.482 2.51797 36.5 5 36.5H23C25.482 36.5 27.5 34.482 27.5 32V9.5C27.5 7.01797 25.482 5 23 5H20.3633C19.4422 2.37734 16.9391 0.5 14 0.5ZM14 5C14.5967 5 15.169 5.23705 15.591 5.65901C16.0129 6.08097 16.25 6.65326 16.25 7.25C16.25 7.84674 16.0129 8.41903 15.591 8.84099C15.169 9.26295 14.5967 9.5 14 9.5C13.4033 9.5 12.831 9.26295 12.409 8.84099C11.9871 8.41903 11.75 7.84674 11.75 7.25C11.75 6.65326 11.9871 6.08097 12.409 5.65901C12.831 5.23705 13.4033 5 14 5ZM5.5625 19.625C5.5625 19.1774 5.74029 18.7482 6.05676 18.4318C6.37322 18.1153 6.80245 17.9375 7.25 17.9375C7.69755 17.9375 8.12678 18.1153 8.44324 18.4318C8.75971 18.7482 8.9375 19.1774 8.9375 19.625C8.9375 20.0726 8.75971 20.5018 8.44324 20.8182C8.12678 21.1347 7.69755 21.3125 7.25 21.3125C6.80245 21.3125 6.37322 21.1347 6.05676 20.8182C5.74029 20.5018 5.5625 20.0726 5.5625 19.625ZM12.875 18.5H21.875C22.4937 18.5 23 19.0063 23 19.625C23 20.2437 22.4937 20.75 21.875 20.75H12.875C12.2563 20.75 11.75 20.2437 11.75 19.625C11.75 19.0063 12.2563 18.5 12.875 18.5ZM5.5625 26.375C5.5625 25.9274 5.74029 25.4982 6.05676 25.1818C6.37322 24.8653 6.80245 24.6875 7.25 24.6875C7.69755 24.6875 8.12678 24.8653 8.44324 25.1818C8.75971 25.4982 8.9375 25.9274 8.9375 26.375C8.9375 26.8226 8.75971 27.2518 8.44324 27.5682C8.12678 27.8847 7.69755 28.0625 7.25 28.0625C6.80245 28.0625 6.37322 27.8847 6.05676 27.5682C5.74029 27.2518 5.5625 26.8226 5.5625 26.375ZM11.75 26.375C11.75 25.7563 12.2563 25.25 12.875 25.25H21.875C22.4937 25.25 23 25.7563 23 26.375C23 26.9937 22.4937 27.5 21.875 27.5H12.875C12.2563 27.5 11.75 26.9937 11.75 26.375Z"
                                            fill="#9CA3AF" />
                                    </g>
                                </g>
                                <defs>
                                    <clipPath id="clip0_861_2044">
                                        <rect width="27" height="36" fill="white" transform="translate(0.5 0.5)" />
                                    </clipPath>
                                    <clipPath id="clip1_861_2044">
                                        <path d="M0.5 0.5H27.5V36.5H0.5V0.5Z" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                            <div class="fw-normal mb-1 mt-2">{{ __('messages.start_new_inspection') }}</div>
                            <small>{{ __('messages.click_button_above') }}</small>
                        </div>
                    </div>
                </div>

            </div>
            <!-- Previous Inspections -->
            <div class="col-lg-6">
                <div class="card h-100 B_shadow">
                    <div class="card-body p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold black_color mb-0">{{ __('messages.previous_inspections') }}</h5>
                            {{-- <div class="dropdown">
                                <button class="btn filter-btn d-flex align-items-center px-3 py-2 bg4" type="button"
                                    data-bs-toggle="dropdown">
                                    <svg width="17" height="14" viewBox="0 0 17 14" class="me-2" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M0.512337 0.715625C0.718587 0.278125 1.15609 0 1.64046 0H15.1405C15.6248 0 16.0623 0.278125 16.2686 0.715625C16.4748 1.15313 16.4123 1.66875 16.1061 2.04375L10.3905 9.02812V13C10.3905 13.3781 10.178 13.725 9.83734 13.8938C9.49671 14.0625 9.09359 14.0281 8.79046 13.8L6.79046 12.3C6.53734 12.1125 6.39046 11.8156 6.39046 11.5V9.02812L0.671712 2.04063C0.368587 1.66875 0.302962 1.15 0.512337 0.715625Z"
                                            fill="#374151" />
                                    </svg>
                                    <span class="text-black">{{ __('messages.filter') }}</span>
                                </button>
                                <ul class="dropdown-menu" style="min-width: 200px; z-index: 1050;" id="categoryFilterMenu">
                                    <li><a class="dropdown-item active" href="#"
                                            data-value="all">{{ __('messages.all_categories') }}</a></li>
                                </ul>
                            </div> --}}
                        </div>
                        <div class="table-responsive" style="height: 400px; overflow-y: auto;">
                            <table class="table align-middle mb-0">
                                <thead class="sticky-top bg-white">
                                    <tr>
                                        <th class="small text-muted">{{ __('messages.title') }}</th>
                                        <th class="small text-muted">{{ __('messages.date') }}</th>
                                        <th class="small text-muted">{{ __('messages.status') }}</th>
                                        <th class="small text-muted">{{ __('messages.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="inspectionsTableBody">
                                    <tr id="loadingRow">
                                        <td colspan="4" class="text-center py-4">
                                            <i class="fas fa-spinner fa-spin"></i> {{ __('messages.loading') }}...
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
    @include('website.modals.create-inspection-modal')
    @include('website.modals.inspection-details-modal')
    @include('website.modals.drawing-modal')

    <script>
        let allInspections = [];

        document.addEventListener('DOMContentLoaded', function() {
            // Check if API is available before loading
            if (typeof api !== 'undefined' && api.getInspections) {
                loadInspections();
            } else {
                console.warn('API not available, using fallback');
                showError('{{ __('messages.api_not_available') }}');
            }

            // Set project_id and load phases for modal when create button is clicked
            const createBtn = document.querySelector('[data-bs-target="#createInspectionModal"]');
            if (createBtn) {
                createBtn.addEventListener('click', async function() {
                    const projectId = getCurrentProjectId();
                    const modalProjectId = document.getElementById('modalProjectId');
                    if (modalProjectId) {
                        modalProjectId.value = projectId;
                    }

                    // Load phases
                    try {
                        const response = await api.listPhases({
                            project_id: projectId
                        });
                        const phaseSelect = document.getElementById('phaseSelect');

                        if (response.code === 200 && phaseSelect) {
                            phaseSelect.innerHTML =
                                '<option value="">{{ __('messages.select_phase') }}</option>';
                            response.data.forEach(phase => {
                                phaseSelect.innerHTML +=
                                    `<option value="${phase.id}">${phase.title || phase.name}</option>`;
                            });
                        }
                    } catch (error) {
                        console.error('Error loading phases:', error);
                    }
                });
            }

            // Filter functionality - setup click handlers for dropdown items
            setupCategoryFilterHandlers();
        });

        function getCurrentProjectId() {
            const pathParts = window.location.pathname.split('/');
            const projectIndex = pathParts.indexOf('project');
            return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
        }

        async function loadInspections() {
            try {
                const projectId = getCurrentProjectId();

                const requestData = {
                    project_id: projectId
                };

                const response = await api.getInspections(requestData);

                if (response.code === 200) {
                    allInspections = response.data.data || [];
                    displayInspections(allInspections);
                    updateCategoryFilter();
                } else {
                    showError('Failed to load inspections: ' + response.message);
                }
            } catch (error) {
                console.error('Error loading inspections:', error);
                showError('Failed to load inspections');
            }
        }

        function displayInspections(inspections) {
            const tbody = document.getElementById('inspectionsTableBody');

            if (inspections.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            <i class="fas fa-clipboard-list"></i><br>
                            {{ __('messages.no_inspections_found') }}
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = inspections.map(inspection => {
                const statusBadge = getStatusBadge(inspection.status);
                const date = new Date(inspection.created_at).toLocaleDateString();
                const title = inspection.category || 'Inspection';
                const subtitle = inspection.phase_name || inspection.description || '-';

                return `
                    <tr>
                        <td class="border-0">
                            <div class="fw-semibold">${title}</div>
                            <div class="small text-muted">${subtitle}</div>
                        </td>
                        <td class="border-0">${date}</td>
                        <td class="border-0">${statusBadge}</td>
                        <td class="border-0">
                            <a href="#" onclick="viewInspection(${inspection.id})" class="text-primary small">{{ __('messages.view') }}</a>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function getStatusBadge(status) {
            const statusMap = {
                'todo': {
                    class: 'badge3',
                    icon: 'hourglass-half',
                    text: '{{ __('messages.todo') }}'
                },
                'in_progress': {
                    class: 'badge3',
                    icon: 'hourglass-half',
                    text: '{{ __('messages.in_progress') }}'
                },
                'completed': {
                    class: 'badge1',
                    icon: 'check-circle',
                    text: '{{ __('messages.completed') }}'
                },
                'failed': {
                    class: 'badge2 text-danger',
                    icon: 'times-circle',
                    text: '{{ __('messages.failed') }}'
                }
            };

            const statusInfo = statusMap[status] || statusMap['todo'];
            return `<span class="badge ${statusInfo.class} d-inline-flex align-items-center gap-1">
                <i class="fas fa-${statusInfo.icon}"></i> ${statusInfo.text}
            </span>`;
        }

        function setupCategoryFilterHandlers() {
            const menu = document.getElementById('categoryFilterMenu');
            if (!menu) return;

            menu.addEventListener('click', function(e) {
                if (e.target.classList.contains('dropdown-item')) {
                    e.preventDefault();
                    const value = e.target.getAttribute('data-value');

                    // Update active state
                    menu.querySelectorAll('.dropdown-item').forEach(item => item.classList.remove('active'));
                    e.target.classList.add('active');

                    filterInspections(value);
                }
            });
        }

        function updateCategoryFilter() {
            const menu = document.getElementById('categoryFilterMenu');
            if (!menu) return;

            const categories = [...new Set(allInspections.map(i => i.category).filter(cat => cat))];

            menu.innerHTML = `
                <li><a class="dropdown-item active" href="#" data-value="all">{{ __('messages.all_categories') }}</a></li>
                ${categories.map(cat => `<li><a class="dropdown-item" href="#" data-value="${cat}">${cat}</a></li>`).join('')}
            `;

            setupCategoryFilterHandlers();
        }

        function filterInspections(filterValue) {
            if (filterValue === 'all') {
                displayInspections(allInspections);
            } else {
                const filtered = allInspections.filter(inspection =>
                    inspection.category.toLowerCase().includes(filterValue.toLowerCase())
                );
                displayInspections(filtered);
            }
        }

        function viewInspection(inspectionId) {
            // Open the inspection details modal
            if (typeof openInspectionDetails === 'function') {
                openInspectionDetails(inspectionId);
            } else {
                console.warn('openInspectionDetails function not found');
                // Fallback - show basic alert
                alert('Inspection details functionality not implemented yet');
            }
        }

        function showError(message) {
            const tbody = document.getElementById('inspectionsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="4" class="text-center py-4 text-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${message}
                    </td>
                </tr>
            `;
        }

        // Fix dataURLtoBlob function for drawing functionality
        function dataURLtoBlob(dataURL) {
            // Handle File objects
            if (dataURL instanceof File) {
                return dataURL;
            }

            // Handle base64 strings
            if (typeof dataURL === 'string' && dataURL.includes(',')) {
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

            // Return as-is if not a recognized format
            return dataURL;
        }
    </script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>

@endsection
