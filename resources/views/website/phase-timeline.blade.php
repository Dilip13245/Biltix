<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phase Timeline</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/toastr-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
</head>

<body data-phase-id="{{ request()->get('phase_id', 1) }}">
    <div class="content_wraper F_poppins">
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 d-flex align-items-center justify-content-between gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <button class="btn btn-outline-primary" onclick="history.back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h4 class="mb-0">{{ __('messages.project_timeline') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <div class="mt-4">
                <h2>{{ __('messages.project_progress') }}</h2>
                <p>{{ __('messages.track_project_phases') }}</p>
            </div>
            @can('phases', 'create')
                <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#createPhaseModal">
                    <i class="fas fa-plus"></i>
                    {{ __('messages.create_phase') }}
                </button>
            @endcan
        </div>
        <div class="px-md-4">
            <div class="container-fluid">
                <!-- Commented out original stats cards
            <div class="row g-4">
                Project Completion Card
                Active Workers Card
                Days Remaining Card
            </div>
            -->

                <!-- Project Phases Cards -->
                {{-- <div class="row g-4">
                <!-- Foundation Phase -->
                <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay="0s">
                    <div class="card h-100 B_shadow" style="cursor: pointer;" onclick="openPhaseModal('Foundation Phase')">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-semibold black_color mb-0">{{ __("messages.foundation_phase") }}</h5>
                                <span class="badge badge1">{{ __("messages.completed") }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">100% Complete</small>
                                    <small class="text-muted">30 days</small>
                                </div>
                            </div>
                            <div class="small text-muted">
                                <div>• {{ __("messages.site_preparation") }} - 10 {{ __("messages.days") }}</div>
                                <div>• {{ __("messages.excavation_work") }} - 8 {{ __("messages.days") }}</div>
                                <div>• {{ __("messages.foundation_pouring") }} - 12 {{ __("messages.days") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Structure Phase -->
                <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 B_shadow" style="cursor: pointer;" onclick="openPhaseModal('Structure Phase')">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-semibold black_color mb-0">{{ __("messages.structure_phase") }}</h5>
                                <span class="badge badge4">{{ __("messages.in_progress") }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar" style="width: 65%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%);" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">65% Complete</small>
                                    <small class="text-muted">45 days</small>
                                </div>
                            </div>
                            <div class="small text-muted">
                                <div>• {{ __("messages.column_construction") }} - 20 {{ __("messages.days") }}</div>
                                <div>• {{ __("messages.beam_installation") }} - 15 {{ __("messages.days") }}</div>
                                <div>• {{ __("messages.slab_work") }} - 10 {{ __("messages.days") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Finishing Phase -->
                <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay=".8s">
                    <div class="card h-100 B_shadow" style="cursor: pointer;" onclick="openPhaseModal('Finishing Phase')">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="fw-semibold black_color mb-0">{{ __("messages.finishing_phase") }}</h5>
                                <span class="badge badge2">{{ __("messages.pending") }}</span>
                            </div>
                            <div class="mb-3">
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between mt-1">
                                    <small class="text-muted">0% Complete</small>
                                    <small class="text-muted">25 days</small>
                                </div>
                            </div>
                            <div class="small text-muted">
                                <div>• {{ __("messages.interior_work") }} - 12 {{ __("messages.days") }}</div>
                                <div>• {{ __("messages.painting_tiling") }} - 8 {{ __("messages.days") }}</div>
                                <div>• {{ __("messages.final_touches") }} - 5 {{ __("messages.days") }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
                <div class="row mt-4 wow fadeInUp" data-wow-delay="0.9s">
                    <div class="col-12">
                        <div class="card B_shadow">
                            <div class="card-body p-md-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-start">
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                                            <h5 class="fw-semibold black_color mb-0">{{ __('messages.project_details') }}</h5>
                                            @can('projects', 'edit')
                                                <button class="btn btn-sm btn-outline-primary" id="editProjectBtn" onclick="toggleProjectEdit()">
                                                    <i class="fas fa-edit me-1"></i>{{ __('messages.edit_project') }}
                                                </button>
                                            @endcan
                                        </div>
                                        <div class="row gy-3 gx-5">
                                            <div class="col-12 col-md-6">
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.project_location') }}</span>
                                                    <div class="fw-medium">
                                                        <span id="projectLocation">{{ __('messages.loading') }}...</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.project_name') }}</span>
                                                    <div class="fw-medium">
                                                        <span id="projectName">{{ __('messages.loading') }}...</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.company_name') }}</span>
                                                    <div class="fw-medium">
                                                        <span id="companyName">{{ __('messages.loading') }}...</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.start_date') }}</span>
                                                    <div class="fw-medium" id="startDate">{{ __('messages.loading') }}...</div>
                                                </div>
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.end_date') }}</span>
                                                    <div class="fw-medium" id="endDate">{{ __('messages.loading') }}...</div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.project_type') }}</span>
                                                    <div class="fw-medium">
                                                        <span id="projectType">{{ __('messages.loading') }}...</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.project_manager') }}</span>
                                                    <div class="fw-medium">
                                                        <span id="projectManager">{{ __('messages.loading') }}...</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2 mb-md-3">
                                                    <span
                                                        class="text-muted small black_color">{{ __('messages.site_engineer') }}</span>
                                                    <div class="fw-medium">
                                                        <span id="siteEngineer">{{ __('messages.loading') }}...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 d-flex flex-wrap gap-2">
                                            <button onclick="window.location.href='/website/project/'+currentProjectId+'/plans'"
                                                class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                                <svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 13.8778L6 12.1621V0.121509L12 1.83713V13.8778ZM13 13.8403V1.76213L16.9719 0.171509C17.4656 -0.0253659 18 0.337134 18 0.868384V11.3309C18 11.6371 17.8125 11.9121 17.5281 12.0278L13 13.8371V13.8403ZM0.471875 1.97151L5 0.162134V12.2371L1.02813 13.8278C0.534375 14.0246 0 13.6621 0 13.1309V2.66838C0 2.36213 0.1875 2.08713 0.471875 1.97151Z"
                                                        fill="#4477C4" />
                                                </svg>
                                                {{ __('messages.view_plan') }}
                                            </button>
                                            <button onclick="openTimelineModal()"
                                                class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                                <svg width="21" height="16" viewBox="0 0 21 16"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g clip-path="url(#clip0_861_1919)">
                                                        <path
                                                            d="M4.0625 2.25C4.26141 2.25 4.45218 2.32902 4.59283 2.46967C4.73348 2.61032 4.8125 2.80109 4.8125 3C4.8125 3.19891 4.73348 3.38968 4.59283 3.53033C4.45218 3.67098 4.26141 3.75 4.0625 3.75C3.86359 3.75 3.67282 3.67098 3.53217 3.53033C3.39152 3.38968 3.3125 3.19891 3.3125 3C3.3125 2.80109 3.39152 2.61032 3.53217 2.46967C3.67282 2.32902 3.86359 2.25 4.0625 2.25ZM5.0625 5.29063C5.94688 4.90625 6.5625 4.025 6.5625 3C6.5625 1.61875 5.44375 0.5 4.0625 0.5C2.68125 0.5 1.5625 1.61875 1.5625 3C1.5625 4.025 2.17812 4.90625 3.0625 5.29063V7H1.0625C0.509375 7 0.0625 7.44688 0.0625 8C0.0625 8.55313 0.509375 9 1.0625 9H9.0625V10.7094C8.17813 11.0938 7.5625 11.975 7.5625 13C7.5625 14.3813 8.68125 15.5 10.0625 15.5C11.4438 15.5 12.5625 14.3813 12.5625 13C12.5625 11.975 11.9469 11.0938 11.0625 10.7094V9H19.0625C19.6156 9 20.0625 8.55313 20.0625 8C20.0625 7.44688 19.6156 7 19.0625 7H17.0625V5.29063C17.9469 4.90625 18.5625 4.025 18.5625 3C18.5625 1.61875 17.4438 0.5 16.0625 0.5C14.6812 0.5 13.5625 1.61875 13.5625 3C13.5625 4.025 14.1781 4.90625 15.0625 5.29063V7H5.0625V5.29063ZM15.3125 3C15.3125 2.80109 15.3915 2.61032 15.5322 2.46967C15.6728 2.32902 15.8636 2.25 16.0625 2.25C16.2614 2.25 16.4522 2.32902 16.5928 2.46967C16.7335 2.61032 16.8125 2.80109 16.8125 3C16.8125 3.19891 16.7335 3.38968 16.5928 3.53033C16.4522 3.67098 16.2614 3.75 16.0625 3.75C15.8636 3.75 15.6728 3.67098 15.5322 3.53033C15.3915 3.38968 15.3125 3.19891 15.3125 3ZM10.0625 12.25C10.2614 12.25 10.4522 12.329 10.5928 12.4697C10.7335 12.6103 10.8125 12.8011 10.8125 13C10.8125 13.1989 10.7335 13.3897 10.5928 13.5303C10.4522 13.671 10.2614 13.75 10.0625 13.75C9.86359 13.75 9.67282 13.671 9.53217 13.5303C9.39152 13.3897 9.3125 13.1989 9.3125 13C9.3125 12.8011 9.39152 12.6103 9.53217 12.4697C9.67282 12.329 9.86359 12.25 10.0625 12.25Z"
                                                            fill="#4477C4" />
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_861_1919">
                                                            <path d="M0.0625 0H20.0625V16H0.0625V0Z" fill="white" />
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                                {{ __('messages.timeline') }}
                                            </button>
                                            <button onclick="window.location.href='/website/project/'+currentProjectId+'/daily-logs'"
                                                class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                                <svg width="15" height="16" viewBox="0 0 15 16"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M3.23438 1V2H1.73438C0.90625 2 0.234375 2.67188 0.234375 3.5V5H14.2344V3.5C14.2344 2.67188 13.5625 2 12.7344 2H11.2344V1C11.2344 0.446875 10.7875 0 10.2344 0C9.68125 0 9.23438 0.446875 9.23438 1V2H5.23438V1C5.23438 0.446875 4.7875 0 4.23438 0C3.68125 0 3.23438 0.446875 3.23438 1ZM14.2344 6H0.234375V14.5C0.234375 15.3281 0.90625 16 1.73438 16H12.7344C13.5625 16 14.2344 15.3281 14.2344 14.5V6Z"
                                                        fill="#4477C4" />
                                                </svg>
                                                <span id="currentDateBtn"></span>
                                            </button>

                                        </div>
                                    </div>
                                    @can('projects', 'delete')
                                        <div class="ms-auto mt-3 mt-md-0">
                                            <button class="btn btn-danger d-flex align-items-center gap-2 py-md-2"
                                                onclick="deleteProject()">
                                                <i class="fas fa-trash"></i> {{ __('messages.delete_project') }}
                                            </button>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row mt-4 wow fadeInUp" data-wow-delay="1.2s">
                    <div class="col-12">
                        <div class="card B_shadow">
                            <div class="card-body p-md-4">
                                <h5 class="fw-semibold black_color mb-3 mb-md-4">{{ __('messages.project_overview') }}
                                </h5>
                                <div id="phaseProgressContainer">
                                    <!-- Phase progress will be rendered from server data -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row mt-4 wow fadeInUp" data-wow-delay="1.3s">
                    <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                        <div class="card h-100 B_shadow">
                            <div class="card-body p-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold black_color mb-0">{{ __('messages.ongoing_activities') }}
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary"
                                            onclick="openActivitiesModal()">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_new') }}
                                        </button>
                                        <button class="btn btn-sm orange_btn" onclick="openActivitiesUpdateModal()">
                                            <i class="fas fa-edit"></i> {{ __('messages.update') }}
                                        </button>
                                    </div>
                                </div>
                                <div id="activitiesContainer">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">{{ __('messages.loading') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6">
                        <div class="card h-100 B_shadow">
                            <div class="card-body p-md-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="fw-semibold black_color mb-0">{{ __('messages.manpower_equipment') }}
                                    </h5>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="openManpowerModal()">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_new') }}
                                        </button>
                                        <button class="btn btn-sm orange_btn" onclick="openManpowerUpdateModal()">
                                            <i class="fas fa-edit"></i> {{ __('messages.update') }}
                                        </button>
                                    </div>
                                </div>
                                <div id="manpowerContainer">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">{{ __('messages.loading') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 wow fadeInUp" data-wow-delay="1.4s">
                    <div class="col-12">
                        <div class="card B_shadow">
                            <div class="card-body p-md-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                    <h5 class="fw-semibold black_color mb-0">{{ __('messages.safety_category') }}</h5>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('website.safety-checklist') }}"
                                            class="btn btn-primary d-flex align-items-center gap-2 btnsm">
                                            <i class="fas fa-eye"></i> {{ __('messages.view_checklist') }}
                                        </a>
                                        <button class="btn btn-primary d-flex align-items-center gap-2 btnsm"
                                            onclick="openSafetyModal()">
                                            <i class="fas fa-plus"></i> {{ __('messages.add_new') }}
                                        </button>
                                        <button class="btn orange_btn d-flex align-items-center gap-2 btnsm"
                                            onclick="openSafetyUpdateModal()">
                                            <i class="fas fa-edit"></i> {{ __('messages.update') }}
                                        </button>
                                    </div>
                                </div>
                                <div id="safetyContainer">
                                    <div class="text-center py-3">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">{{ __('messages.loading') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let currentProjectId = getProjectIdFromUrl();
            let currentUserId = {{ auth()->id() ?? 1 }};

            function getProjectIdFromUrl() {
                const pathParts = window.location.pathname.split('/');
                const projectIndex = pathParts.indexOf('project');
                return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
            }

            function getCurrentPhaseId() {
                const urlParams = new URLSearchParams(window.location.search);
                const pagePhaseId = document.body.getAttribute('data-phase-id');
                return urlParams.get('phase_id') || pagePhaseId || sessionStorage.getItem('current_phase_id') || '1';
            }

            function deleteProject() {
                if (confirm('{{ __('messages.confirm_delete_project') }}')) {
                    alert('{{ __('messages.project_deletion_functionality') }}');
                }
            }

            // Load all data on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Set current date on button
                const currentDateBtn = document.getElementById('currentDateBtn');
                if (currentDateBtn) {
                    const today = new Date();
                    const options = { year: 'numeric', month: 'short', day: 'numeric' };
                    currentDateBtn.textContent = today.toLocaleDateString('en-US', options);
                }
                
                loadProjectData();
                loadActivities();
                loadManpowerEquipment();
                loadSafetyItems();
                renderPhaseProgressFromServer();
            });

            // Project data and edit functionality
            let currentProjectData = null;
            let isEditMode = false;
            let originalValues = {};

            async function loadProjectData() {
                try {
                    const response = await api.getProjectDetails({
                        project_id: currentProjectId
                    });

                    if (response.code === 200 && response.data) {
                        const project = response.data;
                        currentProjectData = project;

                        // Update project details
                        document.getElementById('projectName').textContent = project.project_title || 'N/A';
                        document.getElementById('companyName').textContent = project.contractor_name || 'N/A';
                        document.getElementById('projectType').textContent = project.type || 'N/A';
                        document.getElementById('projectManager').textContent = project.project_manager_name || 'N/A';
                        document.getElementById('siteEngineer').textContent = project.technical_engineer_name || 'N/A';
                        document.getElementById('projectLocation').textContent = project.project_location || 'N/A';

                        // Format and update dates
                        if (project.project_start_date) {
                            document.getElementById('startDate').textContent = formatDate(project.project_start_date);
                        }
                        if (project.project_due_date) {
                            document.getElementById('endDate').textContent = formatDate(project.project_due_date);
                        }
                    } else {
                        console.error('Failed to load project data:', response.message);
                        showErrorState();
                    }
                } catch (error) {
                    console.error('Error loading project data:', error);
                    showErrorState();
                }
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            }

            function showErrorState() {
                const elements = ['projectName', 'companyName', 'projectType', 'projectManager', 'siteEngineer', 'projectLocation', 'startDate', 'endDate'];
                elements.forEach(id => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = 'Error loading data';
                });
            }

            function toggleProjectEdit() {
                isEditMode = true;
                
                // Store original values
                originalValues = {
                    projectName: document.getElementById('projectName').textContent,
                    companyName: document.getElementById('companyName').textContent,
                    projectType: document.getElementById('projectType').textContent,
                    projectManager: document.getElementById('projectManager').textContent,
                    siteEngineer: document.getElementById('siteEngineer').textContent
                };
                
                // Make fields editable
                makeFieldEditable('projectName', 'text');
                makeFieldEditable('companyName', 'text');
                makeFieldEditable('projectType', 'text');
                makeFieldEditable('projectManager', 'dropdown', 'Project Manager');
                makeFieldEditable('siteEngineer', 'dropdown', 'Site Engineer');
                
                // Show save and cancel buttons
                showSaveButton();
            }

            function makeFieldEditable(fieldId, type, label = '') {
                const fieldElement = document.getElementById(fieldId);
                const currentValue = fieldElement.textContent.trim();
                
                if (type === 'text') {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = currentValue;
                    input.className = 'form-control Input_control';
                    input.style.width = '100%';
                    input.style.maxWidth = '300px';
                    input.id = fieldId + '_input';
                    fieldElement.innerHTML = '';
                    fieldElement.appendChild(input);
                } else if (type === 'dropdown') {
                    const select = document.createElement('select');
                    select.className = 'form-select Input_control searchable-select';
                    select.style.width = '100%';
                    select.style.maxWidth = '300px';
                    select.id = fieldId + '_input';
                    select.innerHTML = '<option value="">Loading...</option>';
                    fieldElement.innerHTML = '';
                    fieldElement.appendChild(select);
                    
                    // Load dropdown options
                    loadDropdownOptions(select, label, currentValue);
                }
            }

            async function loadDropdownOptions(selectElement, label, currentValue) {
                try {
                    let response;
                    if (label === 'Project Manager') {
                        response = await api.getProjectManagers();
                    } else if (label === 'Site Engineer') {
                        response = await api.getTechnicalEngineers();
                    }
                    
                    if (response.code === 200 && response.data) {
                        selectElement.innerHTML = '<option value="">Select ' + label + '</option>';
                        response.data.forEach(user => {
                            const option = document.createElement('option');
                            option.value = user.id;
                            option.textContent = user.name;
                            if (user.name === currentValue) {
                                option.selected = true;
                            }
                            selectElement.appendChild(option);
                        });
                        
                        // Initialize searchable dropdown
                        setTimeout(() => {
                            if (typeof SearchableDropdown !== 'undefined') {
                                selectElement.searchableDropdown = new SearchableDropdown(selectElement, {
                                    placeholder: 'Search ' + label + '...'
                                });
                            }
                        }, 200);
                    }
                } catch (error) {
                    console.error('Failed to load options:', error);
                    selectElement.innerHTML = '<option value="">Error loading options</option>';
                }
            }

            function showSaveButton() {
                const btn = document.getElementById('editProjectBtn');
                
                // Create button container
                const btnContainer = document.createElement('div');
                btnContainer.className = 'd-flex gap-2 align-items-center';
                btnContainer.id = 'editButtonsContainer';
                
                // Create save button
                const saveBtn = document.createElement('button');
                saveBtn.className = 'btn btn-sm btn-success';
                saveBtn.id = 'saveProjectBtn';
                saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>{{ __('messages.save') }}';
                saveBtn.onclick = saveProjectChanges;
                
                // Create cancel button
                const cancelBtn = document.createElement('button');
                cancelBtn.className = 'btn btn-sm btn-secondary';
                cancelBtn.id = 'cancelProjectBtn';
                cancelBtn.innerHTML = '<i class="fas fa-times me-1"></i>{{ __('messages.cancel') }}';
                cancelBtn.onclick = cancelProjectEdit;
                
                // Add buttons to container
                btnContainer.appendChild(saveBtn);
                btnContainer.appendChild(cancelBtn);
                
                // Replace edit button with button container
                btn.style.display = 'none';
                btn.parentElement.appendChild(btnContainer);
            }

            async function saveProjectChanges() {
                const saveBtn = document.getElementById('saveProjectBtn');
                saveBtn.disabled = true;
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __('messages.saving') }}...';
                
                try {
                    const updateData = {
                        project_id: currentProjectId
                    };
                    
                    // Get values from inputs
                    const projectNameInput = document.getElementById('projectName_input');
                    const companyNameInput = document.getElementById('companyName_input');
                    const projectTypeInput = document.getElementById('projectType_input');
                    const projectManagerInput = document.getElementById('projectManager_input');
                    const siteEngineerInput = document.getElementById('siteEngineer_input');
                    
                    if (projectNameInput && projectNameInput.value.trim()) {
                        updateData.project_title = projectNameInput.value.trim();
                    }
                    if (companyNameInput && companyNameInput.value.trim()) {
                        updateData.contractor_name = companyNameInput.value.trim();
                    }
                    if (projectTypeInput && projectTypeInput.value.trim()) {
                        updateData.type = projectTypeInput.value.trim();
                    }
                    if (projectManagerInput && projectManagerInput.value) {
                        updateData.project_manager_id = projectManagerInput.value;
                    }
                    if (siteEngineerInput && siteEngineerInput.value) {
                        updateData.technical_engineer_id = siteEngineerInput.value;
                    }
                    
                    const response = await api.updateProject(updateData);
                    
                    if (response.code === 200) {
                        showToast('{{ __('messages.project_updated_successfully') }}', 'success');
                        isEditMode = false;
                        loadProjectData();
                        
                        // Show edit button again
                        const editBtn = document.getElementById('editProjectBtn');
                        editBtn.style.display = 'inline-block';
                        
                        // Remove button container
                        const btnContainer = document.getElementById('editButtonsContainer');
                        if (btnContainer) btnContainer.remove();
                    } else {
                        showToast(response.message || '{{ __('messages.failed_to_update_project') }}', 'error');
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>{{ __('messages.save') }}';
                    }
                } catch (error) {
                    console.error('Error updating project:', error);
                    showToast('{{ __('messages.error_updating_project') }}', 'error');
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>{{ __('messages.save') }}';
                }
            }

            function cancelProjectEdit() {
                isEditMode = false;
                
                // Restore original values
                document.getElementById('projectName').textContent = originalValues.projectName;
                document.getElementById('companyName').textContent = originalValues.companyName;
                document.getElementById('projectType').textContent = originalValues.projectType;
                document.getElementById('projectManager').textContent = originalValues.projectManager;
                document.getElementById('siteEngineer').textContent = originalValues.siteEngineer;
                
                // Show edit button again
                const btn = document.getElementById('editProjectBtn');
                btn.style.display = 'inline-block';
                
                // Remove button container
                const btnContainer = document.getElementById('editButtonsContainer');
                if (btnContainer) btnContainer.remove();
            }

            function deleteProject() {
                const modal = new bootstrap.Modal(document.getElementById('deleteProjectModal'));
                modal.show();
            }

            async function confirmDeleteProject() {
                const btn = document.getElementById('confirmDeleteBtn');
                const originalText = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>{{ __('messages.deleting') }}...';

                try {
                    const response = await api.makeRequest('projects/delete', {
                        project_id: currentProjectId,
                        user_id: currentUserId
                    });

                    if (response.code === 200) {
                        showToast(response.message || '{{ __('messages.project_deleted_successfully') }}', 'success');
                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 1000);
                    } else {
                        showToast(response.message || '{{ __('messages.failed_to_delete_project') }}', 'error');
                        btn.disabled = false;
                        btn.innerHTML = originalText;
                    }
                } catch (error) {
                    console.error('Error deleting project:', error);
                    showToast(error.message || '{{ __('messages.error_deleting_project') }}', 'error');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            }

            // Toast notification function
            function showToast(message, type = 'success') {
                if (typeof toastr !== 'undefined') {
                    switch (type) {
                        case 'success':
                            toastr.success(message);
                            break;
                        case 'error':
                            toastr.error(message);
                            break;
                        case 'warning':
                            toastr.warning(message);
                            break;
                        case 'info':
                            toastr.info(message);
                            break;
                        default:
                            toastr.success(message);
                    }
                } else {
                    alert(message);
                }
            }

            // Activities Functions
            async function loadActivities() {
                try {
                    const response = await api.listActivities({
                        project_id: currentProjectId
                    });
                    if (response.code === 200) {
                        renderActivities(response.data || []);
                    } else {
                        showError('activitiesContainer', '{{ __('messages.failed_to_load_activities') }}');
                    }
                } catch (error) {
                    showError('activitiesContainer', '{{ __('messages.error_loading_activities') }}');
                }
            }

            function renderActivities(activities) {
                const container = document.getElementById('activitiesContainer');
                if (activities.length === 0) {
                    container.innerHTML = `
      <div class="text-center py-3 text-muted">
        <i class="fas fa-tasks fa-2x mb-2"></i>
        <p>{{ __('messages.no_activities_found') }}</p>
      </div>
    `;
                    return;
                }

                // Store activities globally for bulk update
                window.currentActivities = activities;

                container.innerHTML = `
    <ul class="list-unstyled mb-0">
      ${activities.map(activity => `
                                        <li class="d-flex align-items-center mb-2">
                                          <span class="{{ margin_end(2) }}" style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                          <span class="flex-grow-1 text-wrap">${activity.description}</span>
                                        </li>
                                      `).join('')}
    </ul>
  `;
            }

            // Manpower Equipment Functions
            async function loadManpowerEquipment() {
                try {
                    const response = await api.listManpowerEquipment({
                        project_id: currentProjectId
                    });
                    if (response.code === 200) {
                        renderManpowerEquipment(response.data || []);
                    } else {
                        showError('manpowerContainer', '{{ __('messages.failed_to_load_manpower') }}');
                    }
                } catch (error) {
                    showError('manpowerContainer', '{{ __('messages.error_loading_manpower') }}');
                }
            }

            function renderManpowerEquipment(items) {
                const container = document.getElementById('manpowerContainer');
                if (items.length === 0) {
                    container.innerHTML = `
      <div class="text-center py-3 text-muted">
        <i class="fas fa-users fa-2x mb-2"></i>
        <p>{{ __('messages.no_manpower_found') }}</p>
      </div>
    `;
                    return;
                }

                // Store manpower globally for bulk update
                window.currentManpower = items;

                container.innerHTML = `
    <div class="table-responsive">
      <table class="table table-borderless mb-0">
        <tbody>
          ${items.map(item => `
                                            <tr>
                                              <td class="text-muted fw-medium text-wrap" style="max-width: 200px;">${item.category}</td>
                                              <td class="text-end">
                                                <span class="text-primary fw-semibold">${item.count}</span>
                                              </td>
                                            </tr>
                                          `).join('')}
        </tbody>
      </table>
    </div>
  `;
            }

            // Safety Items Functions
            async function loadSafetyItems() {
                try {
                    const response = await api.listSafetyItems({
                        project_id: currentProjectId
                    });
                    if (response.code === 200) {
                        renderSafetyItems(response.data || []);
                    } else {
                        showError('safetyContainer', '{{ __('messages.failed_to_load_safety') }}');
                    }
                } catch (error) {
                    showError('safetyContainer', '{{ __('messages.error_loading_safety') }}');
                }
            }

            function renderSafetyItems(items) {
                const container = document.getElementById('safetyContainer');
                if (items.length === 0) {
                    container.innerHTML = `
      <div class="text-center py-3 text-muted">
        <i class="fas fa-shield-alt fa-2x mb-2"></i>
        <p>{{ __('messages.no_safety_items_found') }}</p>
      </div>
    `;
                    return;
                }

                // Store safety items globally for bulk update
                window.currentSafetyItems = items;

                container.innerHTML = `
    <ul class="list-unstyled mb-0">
      ${items.map(item => `
                                        <li class="mb-2">
                                          <div class="d-flex align-items-center p-3 rounded bg4">
                                            <span class="{{ margin_end(3) }} text-success" style="font-size:1.3em;">
                                              <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span class="flex-grow-1 text-wrap">${item.checklist_item}</span>
                                          </div>
                                        </li>
                                      `).join('')}
    </ul>
  `;
            }

            function showError(containerId, message) {
                document.getElementById(containerId).innerHTML = `
    <div class="text-center py-3 text-danger">
      <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
      <p>${message}</p>
    </div>
  `;
            }

            // Phase Progress Functions - Using server-side data
            function renderPhaseProgressFromServer() {
                const phases = @json($phases ?? []);
                renderPhaseProgress(phases);
            }

            function renderPhaseProgress(phases) {
                const container = document.getElementById('phaseProgressContainer');

                if (phases.length === 0) {
                    container.innerHTML = `
      <div class="text-center py-3 text-muted">
        <i class="fas fa-layer-group fa-2x mb-2"></i>
        <p>{{ __('messages.no_phases_found') }}</p>
      </div>
    `;
                    return;
                }

                container.innerHTML = phases.map(phase => {
                    const progress = Math.round(phase.time_progress || 0);
                    return `
      <div class="mb-3 phase-progress-item" data-phase-id="${phase.id}">
        <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
          <h6 class="text-muted fw-medium mb-0">${phase.title}</h6>
          <span class="progress-value text-primary fw-semibold">${progress}%</span>
        </div>
        <div class="position-relative">
          <div class="progress" style="height:12px; border-radius: 6px;">
            <div class="progress-bar" role="progressbar" 
              style="width: ${progress}%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%); border-radius: 6px;" 
              aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <input type="range" class="form-range progress-slider position-absolute" 
            min="0" max="100" value="${progress}" 
            data-phase-id="${phase.id}" 
            style="top: 0; left: 0; width: 100%; height: 12px; opacity: 0; cursor: pointer;" 
            oninput="updateProgressLive(this)" 
            onchange="saveProgress(this)">
        </div>
      </div>
    `;
                }).join('');
            }

            function updateProgressLive(slider) {
                const phaseItem = slider.closest('.phase-progress-item');
                const progressBar = phaseItem.querySelector('.progress-bar');
                const progressValue = phaseItem.querySelector('.progress-value');
                const value = Math.round(slider.value);

                progressBar.style.width = value + '%';
                progressBar.setAttribute('aria-valuenow', value);
                progressValue.textContent = value + '%';
            }

            async function saveProgress(slider) {
                const phaseId = slider.getAttribute('data-phase-id');
                const progress = Math.round(slider.value);
                const phaseItem = slider.closest('.phase-progress-item');
                const progressValue = phaseItem.querySelector('.progress-value');

                // Show saving indicator
                const originalText = progressValue.textContent;
                progressValue.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                try {
                    const response = await api.makeRequest('projects/update_phase_progress', {
                        phase_id: phaseId,
                        user_id: currentUserId,
                        progress_percentage: progress
                    });

                    if (response.code === 200) {
                        progressValue.textContent = progress + '%';
                        // Show brief success indicator
                        progressValue.innerHTML = `<i class="fas fa-check text-success"></i> ${progress}%`;
                        setTimeout(() => {
                            progressValue.textContent = progress + '%';
                        }, 1000);

                        // Reload phases in project-progress page if available
                        if (window.loadPhases) {
                            setTimeout(() => window.loadPhases(), 500);
                        }
                    } else {
                        progressValue.textContent = originalText;
                        toastr.error(response.message || '{{ __('messages.failed_to_update_progress') }}');
                    }
                } catch (error) {
                    progressValue.textContent = originalText;
                    toastr.error('{{ __('messages.error_updating_progress') }}');
                }
            }
        </script>

        <!-- Create Phase Modal -->
        <div class="modal fade" id="createPhaseModal" tabindex="-1" aria-labelledby="createPhaseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPhaseModalLabel">
                            <i class="fas fa-layer-group me-2"></i>{{ __('messages.create_phase') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="createPhaseForm">
                            @csrf
                            <input type="hidden" name="project_id" value="1">
                            <input type="hidden" name="created_by" value="{{ auth()->id() ?? 1 }}">

                            <div class="mb-3">
                                <label for="title"
                                    class="form-label fw-medium">{{ __('messages.phase_title') }}</label>
                                <input type="text" class="form-control Input_control" id="title"
                                    name="title" required maxlength="100"
                                    placeholder="e.g., Foundation Work, Structure Phase, Finishing">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('messages.milestones') }}</label>
                                <div id="milestonesContainer">
                                    <div class="milestone-item mb-2">
                                        <div class="row">
                                            <div class="col-8">
                                                <input type="text" class="form-control Input_control"
                                                    name="milestones[0][milestone_name]" maxlength="80"
                                                    placeholder="{{ __('messages.milestone_name') }}" required>
                                            </div>
                                            <div class="col-3">
                                                <input type="number" class="form-control Input_control"
                                                    name="milestones[0][days]"
                                                    placeholder="{{ __('messages.days') }}" min="1" required>
                                            </div>
                                            <div class="col-1">
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    onclick="removeMilestone(this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm"
                                    onclick="addMilestone()">
                                    <i class="fas fa-plus me-1"></i>{{ __('messages.add_milestone') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="createPhaseForm" class="btn orange_btn">
                            <i class="fas fa-plus me-2"></i>{{ __('messages.create_phase') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let milestoneIndex = 1;

            function addMilestone() {
                const container = document.getElementById('milestonesContainer');
                const newMilestone = document.createElement('div');
                newMilestone.className = 'milestone-item mb-2';
                newMilestone.innerHTML = `
    <div class="row">
      <div class="col-8">
        <input type="text" class="form-control Input_control" name="milestones[${milestoneIndex}][milestone_name]" 
          placeholder="Milestone name" maxlength="80" required>
      </div>
      <div class="col-3">
        <input type="number" class="form-control Input_control" name="milestones[${milestoneIndex}][days]" 
          placeholder="Days" min="1" required>
      </div>
      <div class="col-1">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeMilestone(this)">
          <i class="fas fa-trash"></i>
        </button>
      </div>
    </div>
  `;
                container.appendChild(newMilestone);
                milestoneIndex++;
            }

            function removeMilestone(button) {
                const container = document.getElementById('milestonesContainer');
                if (container.children.length > 1) {
                    button.closest('.milestone-item').remove();
                }
            }

            // Create Phase Form Handler
            document.addEventListener('DOMContentLoaded', function() {
                const createPhaseForm = document.getElementById('createPhaseForm');
                if (createPhaseForm) {
                    createPhaseForm.addEventListener('submit', function(e) {
                        e.preventDefault();

                        const title = document.getElementById('title').value;
                        const milestones = document.querySelectorAll('.milestone-item');

                        if (title.trim() && milestones.length > 0) {
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'createPhaseModal'));
                            if (modal) modal.hide();

                            alert('Phase "' + title + '" with ' + milestones.length +
                                ' milestone(s) created successfully!');

                            // Reset form
                            createPhaseForm.reset();
                            milestoneIndex = 1;

                            // Reset milestones container
                            const container = document.getElementById('milestonesContainer');
                            container.innerHTML = `
          <div class="milestone-item mb-2">
            <div class="row">
              <div class="col-8">
                <input type="text" class="form-control Input_control" name="milestones[0][milestone_name]" 
                  placeholder="Milestone name" required>
              </div>
              <div class="col-3">
                <input type="number" class="form-control Input_control" name="milestones[0][days]" 
                  placeholder="Days" min="1" required>
              </div>
              <div class="col-1">
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeMilestone(this)">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
        `;

                            // Reload page to show new phase
                            location.reload();
                        }
                    });
                }
            });
        </script>

        <!-- Phase Navigation Modal -->
        <div class="modal fade" id="phaseNavigationModal" tabindex="-1" aria-labelledby="phaseNavigationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="phaseNavigationModalLabel">
                            <i class="fas fa-layer-group me-2"></i><span id="phaseModalTitle">Phase Management</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card h-100 border-primary" style="cursor: pointer;"
                                    onclick="redirectToInspections()">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-clipboard-check fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">{{ __('messages.inspections') }}</h5>
                                        <p class="card-text text-muted">{{ __('messages.manage_phase_inspections') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-success" style="cursor: pointer;"
                                    onclick="redirectToTasks()">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                                        <h5 class="card-title">{{ __('messages.tasks') }}</h5>
                                        <p class="card-text text-muted">{{ __('messages.manage_phase_tasks') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-warning" style="cursor: pointer;"
                                    onclick="redirectToSnags()">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h5 class="card-title">{{ __('messages.snag_list') }}</h5>
                                        <p class="card-text text-muted">{{ __('messages.manage_phase_issues') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card h-100 border-info" style="cursor: pointer;"
                                    onclick="redirectToTimeline()">
                                    <div class="card-body text-center p-4">
                                        <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                                        <h5 class="card-title">{{ __('messages.project_details') }}</h5>
                                        <p class="card-text text-muted">{{ __('messages.view_project_timeline') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openPhaseModal(phaseName) {
                document.getElementById('phaseModalTitle').textContent = phaseName + ' - Management';
                const modal = new bootstrap.Modal(document.getElementById('phaseNavigationModal'));
                modal.show();
            }

            function openTimelineModal() {
                loadPhaseTimeline();
                const modal = new bootstrap.Modal(document.getElementById('timelineModal'));
                modal.show();
            }

            async function loadPhaseTimeline() {
                try {
                    const currentPhaseId = getCurrentPhaseId();
                    const response = await api.makeRequest('projects/list_phases', {
                        project_id: currentProjectId,
                        user_id: currentUserId
                    });

                    if (response.code === 200 && response.data && response.data.length > 0) {
                        let phaseToShow = response.data.find(phase => phase.id == currentPhaseId);
                        if (!phaseToShow) {
                            phaseToShow = response.data[0]; // Show first phase if current not found
                        }
                        renderPhaseTimeline([phaseToShow]);
                    } else {
                        document.getElementById('timelineContent').innerHTML = '<div class="text-center py-4 text-muted">No phases found</div>';
                    }
                } catch (error) {
                    console.error('Error loading timeline:', error);
                    document.getElementById('timelineContent').innerHTML = '<div class="text-center py-4 text-danger">Error loading timeline</div>';
                }
            }

            let extendTimeout = {};

            async function extendMilestone(milestoneId) {
                if (extendTimeout[milestoneId]) {
                    clearTimeout(extendTimeout[milestoneId]);
                }

                extendTimeout[milestoneId] = setTimeout(async () => {
                    const extensionInput = document.getElementById(`ext_${milestoneId}`);
                    const extensionDays = parseInt(extensionInput.value) || 0;
                    
                    if (extensionDays < 0 || extensionDays > 3650) {
                        alert('Extension days must be between 0 and 3650');
                        extensionInput.value = Math.min(Math.max(extensionDays, 0), 3650);
                        return;
                    }

                    try {
                        const response = await api.makeRequest('projects/extend_milestone', {
                            milestone_id: milestoneId,
                            user_id: currentUserId,
                            extension_days: extensionDays
                        });

                        if (response.code === 200) {
                            loadPhaseTimeline();
                            if (extensionDays > 0) {
                                alert(`Milestone extended by ${extensionDays} days`);
                            } else {
                                alert('Extension reset successfully');
                            }
                        } else {
                            alert('Failed to extend milestone: ' + response.message);
                        }
                    } catch (error) {
                        console.error('Error extending milestone:', error);
                        alert('Error extending milestone');
                    }
                }, 500);
            }

            async function quickExtend(milestoneId, days) {
                const extensionInput = document.getElementById(`ext_${milestoneId}`);
                const currentExtension = parseInt(extensionInput.value) || 0;
                const newExtension = currentExtension + days;

                extensionInput.value = newExtension;
                await extendMilestone(milestoneId);
            }

            function renderPhaseTimeline(phases) {
                const container = document.getElementById('timelineContent');
                
                if (!phases || phases.length === 0) {
                    container.innerHTML = '<div class="text-center py-4 text-muted">No phases found</div>';
                    return;
                }

                container.innerHTML = phases.map((phase, index) => {
                    const totalDays = phase.milestones ? phase.milestones.reduce((sum, m) => sum + (m.days || 0), 0) : 0;
                    const extensionDays = phase.total_extension_days || 0;
                    const progress = phase.time_progress || 0;
                    
                    // Determine badge based on time progress and extensions
                let badgeClass, badgeText;
                if (progress >= 100) {
                    badgeClass = 'badge1';
                    badgeText = 'Completed';
                } else if (extensionDays > 0) {
                    badgeClass = 'badge3';
                    badgeText = 'Extended';
                } else if (progress > 0) {
                    badgeClass = 'badge4';
                    badgeText = 'In Progress';
                } else {
                    badgeClass = 'badge2';
                    badgeText = 'Pending';
                }

                return `
                        <div class="timeline-phase mb-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="timeline-marker me-3">
                                    <div class="timeline-dot bg-primary"></div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="mb-0 fw-semibold">${phase.title}</h5>
                                        <span class="badge ${badgeClass}">${badgeText}</span>
                                    </div>
                                    <div class="text-muted small mb-1">${Math.round(progress)}% Time Progress</div>
                                    <div class="d-flex align-items-center gap-3 text-muted small">
                                        <span><i class="fas fa-calendar me-1"></i>${totalDays}${extensionDays > 0 ? ` (+${extensionDays})` : ''} days</span>
                                    </div>
                                    ${extensionDays > 0 ? `
                                        <div class="mt-1">
                                            <small class="text-warning">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Original: ${Math.round(progress)}% | Extended timeline: ${Math.round((progress * totalDays) / (totalDays + extensionDays))}%
                                            </small>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                            <div class="timeline-milestones ps-5">
                                ${phase.milestones ? phase.milestones.map(milestone => {
                                    const isExtended = milestone.is_extended;
                                    const isOverdue = milestone.is_overdue;
                                    return `
                                        <div class="milestone-item py-2 px-3 mb-2 rounded ${isOverdue ? 'bg-danger bg-opacity-10' : 'bg-light'}">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="fas fa-circle text-primary" style="font-size: 8px;"></i>
                                                    <span class="${isOverdue ? 'text-danger fw-medium' : ''}">${milestone.milestone_name}${milestone.days ? ` - ${milestone.days} days` : ''}</span>
                                                    ${isExtended ? '<i class="fas fa-clock text-warning ms-1" style="font-size: 10px;"></i>' : ''}
                                                </div>
                                                <div class="text-muted small">
                                                    ${milestone.days || 0} days${milestone.extension_days > 0 ? ` (+${milestone.extension_days})` : ''}
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mt-2">
                                                <span class="text-muted small">Extend:</span>
                                                <div class="input-group" style="width: 80px;">
                                                    <input type="number" class="form-control form-control-sm" 
                                                        style="font-size: 11px; text-align: center;" 
                                                        value="${milestone.extension_days || 0}" 
                                                        min="0" max="999" 
                                                        id="ext_${milestone.id}" 
                                                        onchange="extendMilestone(${milestone.id})">
                                                    <span class="input-group-text" style="font-size: 10px; padding: 2px 4px;">d</span>
                                                </div>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                        style="font-size: 10px; padding: 2px 6px;" 
                                                        onclick="quickExtend(${milestone.id}, 1)">+1</button>
                                                    <button class="btn btn-outline-secondary btn-sm" 
                                                        style="font-size: 10px; padding: 2px 6px;" 
                                                        onclick="quickExtend(${milestone.id}, 7)">+7</button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }).join('') : '<div class="text-muted small">No milestones defined</div>'}
                                ${extensionDays > 0 ? `
                                    <div class="mt-2">
                                        <small class="text-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Extended by ${extensionDays} day${extensionDays !== 1 ? 's' : ''}
                                        </small>
                                    </div>
                                ` : ''}
                                <div class="mt-2 d-flex gap-1 flex-wrap">
                                    <small class="text-muted me-2">Quick extend:</small>
                                    ${phase.milestones && phase.milestones.length === 1 ? `
                                        <button class="btn btn-outline-warning btn-sm" style="font-size: 10px; padding: 1px 4px;" 
                                            onclick="quickExtend(${phase.milestones[0].id}, 1)">+1d</button>
                                        <button class="btn btn-outline-warning btn-sm" style="font-size: 10px; padding: 1px 4px;" 
                                            onclick="quickExtend(${phase.milestones[0].id}, 3)">+3d</button>
                                        <button class="btn btn-outline-warning btn-sm" style="font-size: 10px; padding: 1px 4px;" 
                                            onclick="quickExtend(${phase.milestones[0].id}, 7)">+7d</button>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            function redirectToInspections() {
                window.location.href = '/website/phase-inspections';
            }

            function redirectToTasks() {
                window.location.href = '/website/phase-tasks';
            }

            function redirectToSnags() {
                window.location.href = '/website/phase-snags';
            }

            function redirectToTimeline() {
                window.location.href = '/website/phase-timeline';
            }
        </script>

        <!-- Timeline Modal -->
        <div class="modal fade" id="timelineModal" tabindex="-1" aria-labelledby="timelineModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <style>
                          #timelineModal .modal-header .btn-close {
                            position: static !important;
                            right: auto !important;
                            top: auto !important;
                            margin: 0 !important;
                          }
                          #timelineModal .modal-header {
                            position: relative !important;
                          }
                        </style>
                        @if(app()->getLocale() == 'ar')
                        <div class="d-flex justify-content-between align-items-center w-100">
                          <h5 class="modal-title" id="timelineModalLabel">
                            {{ __('messages.project_timeline') }}<i class="fas fa-chart-line ms-2"></i>
                          </h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        @else
                        <h5 class="modal-title" id="timelineModalLabel">
                            <i class="fas fa-chart-line me-2"></i>{{ __('messages.project_timeline') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        @endif
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div id="timelineContent">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">{{ __('messages.loading') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .timeline-marker {
                position: relative;
            }
            .timeline-dot {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                position: relative;
            }
            .timeline-phase:not(:last-child) .timeline-marker::after {
                content: '';
                position: absolute;
                top: 20px;
                {{ is_rtl() ? 'right' : 'left' }}: 50%;
                transform: translateX(-50%);
                width: 2px;
                height: 60px;
                background: #e9ecef;
            }
            .milestone-item {
                border: 1px solid #e9ecef;
                transition: all 0.2s ease;
            }
            .milestone-item:hover {
                border-color: #4477C4;
                box-shadow: 0 2px 4px rgba(68, 119, 196, 0.1);
            }
            [dir="rtl"] .timeline-milestones {
                padding-right: 3rem !important;
                padding-left: 0 !important;
            }
            [dir="rtl"] .timeline-marker {
                margin-left: 1rem;
                margin-right: 0;
            }
            .text-wrap {
                word-wrap: break-word;
                overflow-wrap: break-word;
                word-break: break-word;
                hyphens: auto;
                max-width: 100%;
            }
        </style>

        @include('website.modals.project-progress-modals')

        <!-- Delete Project Confirmation Modal -->
        <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0 pb-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center px-4 pb-4">
                        <div class="mb-3">
                            <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="fw-bold mb-3">{{ __('messages.delete_project') }}?</h5>
                        <p class="text-muted mb-4">{{ __('messages.delete_project_warning') }}</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>{{ __('messages.cancel') }}
                            </button>
                            <button type="button" class="btn btn-danger px-4" id="confirmDeleteBtn" onclick="confirmDeleteProject()">
                                <i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities Update Modal -->
        <div class="modal fade" id="activitiesUpdateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.update') }} {{ __('messages.ongoing_activities') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="activitiesUpdateContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn orange_btn" onclick="saveActivitiesUpdate()">
                            <i class="fas fa-save me-2"></i>{{ __('messages.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Manpower Update Modal -->
        <div class="modal fade" id="manpowerUpdateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.update') }} {{ __('messages.manpower_equipment') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="manpowerUpdateContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn orange_btn" onclick="saveManpowerUpdate()">
                            <i class="fas fa-save me-2"></i>{{ __('messages.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Safety Update Modal -->
        <div class="modal fade" id="safetyUpdateModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('messages.update') }} {{ __('messages.safety_category') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="safetyUpdateContainer"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn orange_btn" onclick="saveSafetyUpdate()">
                            <i class="fas fa-save me-2"></i>{{ __('messages.update') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Modal Functions
            function openActivitiesModal() {
                document.getElementById('activitiesModalTitle').textContent = '{{ __('messages.add_activity') }}';
                document.getElementById('activitiesSaveBtn').textContent = '{{ __('messages.save') }}';
                document.getElementById('activitiesForm').reset();
                document.getElementById('activityId').value = '';
                // Reset to single field
                const container = document.getElementById('modalActivitiesContainer');
                container.innerHTML = `
        <div class="activity-field mb-2">
            <label class="form-label small fw-medium mb-1">{{ __('messages.description') }}</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="description[]" 
                    placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150" required>
                <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)" style="display:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
                document.getElementById('addMoreActivityBtn').style.display = 'inline-block';
                new bootstrap.Modal(document.getElementById('activitiesModal')).show();
            }

            function openManpowerModal() {
                document.getElementById('manpowerModalTitle').textContent = '{{ __('messages.add_manpower_equipment') }}';
                document.getElementById('manpowerSaveBtn').textContent = '{{ __('messages.save') }}';
                document.getElementById('manpowerForm').reset();
                document.getElementById('manpowerId').value = '';
                // Reset to single field
                const container = document.getElementById('modalManpowerContainer');
                container.innerHTML = `
        <div class="manpower-field mb-2">
            <div class="row g-2">
                <div class="col-7">
                    <label class="form-label small fw-medium mb-1">{{ __('messages.category') }}</label>
                    <input type="text" class="form-control form-control-sm" name="category[]" 
                        placeholder="{{ __('messages.enter_category') }}" maxlength="50" required>
                </div>
                <div class="col-3">
                    <label class="form-label small fw-medium mb-1">{{ __('messages.count') }}</label>
                    <input type="number" class="form-control form-control-sm" name="count[]" 
                        placeholder="0" min="0" required>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-field" onclick="removeField(this)" style="display:none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
                document.getElementById('addMoreManpowerBtn').style.display = 'inline-block';
                new bootstrap.Modal(document.getElementById('manpowerModal')).show();
            }

            function openSafetyModal() {
                document.getElementById('safetyModalTitle').textContent = '{{ __('messages.add_safety_item') }}';
                document.getElementById('safetySaveBtn').textContent = '{{ __('messages.save') }}';
                document.getElementById('safetyForm').reset();
                document.getElementById('safetyId').value = '';
                // Reset to single field
                const container = document.getElementById('modalSafetyContainer');
                container.innerHTML = `
        <div class="safety-field mb-2">
            <label class="form-label small fw-medium mb-1">{{ __('messages.checklist_item') }}</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="checklist_item[]" 
                    placeholder="{{ __('messages.enter_safety_item') }}" maxlength="120" required>
                <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)" style="display:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
                document.getElementById('addMoreSafetyBtn').style.display = 'inline-block';
                new bootstrap.Modal(document.getElementById('safetyModal')).show();
            }

            // Dynamic Field Functions
            function addActivityField() {
                const container = document.getElementById('modalActivitiesContainer');
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'activity-field mb-3';
                fieldDiv.innerHTML = `
        <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="description[]" 
                placeholder="{{ __('messages.enter_activity_description') }}" maxlength="150" required>
            <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
                container.appendChild(fieldDiv);
                updateRemoveButtons('modalActivitiesContainer');
            }

            function addManpowerField() {
                const container = document.getElementById('modalManpowerContainer');
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'manpower-field mb-3';
                fieldDiv.innerHTML = `
        <div class="row g-2">
            <div class="col-7">
                <input type="text" class="form-control form-control-sm" name="category[]" 
                    placeholder="{{ __('messages.enter_category') }}" maxlength="50" required>
            </div>
            <div class="col-3">
                <input type="number" class="form-control form-control-sm" name="count[]" 
                    placeholder="0" min="0" required>
            </div>
            <div class="col-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-field" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
                container.appendChild(fieldDiv);
                updateRemoveButtons('modalManpowerContainer');
            }

            function addSafetyField() {
                const container = document.getElementById('modalSafetyContainer');
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'safety-field mb-3';
                fieldDiv.innerHTML = `
        <div class="input-group input-group-sm">
            <input type="text" class="form-control form-control-sm" name="checklist_item[]" 
                placeholder="{{ __('messages.enter_safety_item') }}" maxlength="120" required>
            <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
                container.appendChild(fieldDiv);
                updateRemoveButtons('modalSafetyContainer');
            }

            function removeField(button) {
                const fieldDiv = button.closest('.activity-field, .manpower-field, .safety-field');
                const container = fieldDiv.parentNode;
                fieldDiv.remove();
                updateRemoveButtons(container.id);
            }

            function updateRemoveButtons(containerId) {
                const container = document.getElementById(containerId);
                const fields = container.children;
                for (let i = 0; i < fields.length; i++) {
                    const removeBtn = fields[i].querySelector('.remove-field');
                    if (removeBtn) {
                        removeBtn.style.display = fields.length > 1 ? 'block' : 'none';
                    }
                }
            }

            // Save Functions
            async function saveActivity() {
                const form = document.getElementById('activitiesForm');
                const activityId = document.getElementById('activityId').value;
                const descriptions = Array.from(form.querySelectorAll('input[name="description[]"]'))
                    .map(input => input.value.trim())
                    .filter(desc => desc);

                if (descriptions.length === 0) {
                    toastr.error('{{ __('messages.please_enter_description') }}');
                    return;
                }

                const saveBtn = document.getElementById('activitiesSaveBtn');
                const originalText = saveBtn.textContent;
                saveBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';

                try {
                    let response;
                    if (activityId && descriptions.length === 1) {
                        response = await api.updateActivity({
                            activity_id: activityId,
                            description: descriptions[0]
                        });
                    } else {
                        response = await api.addActivity({
                            project_id: currentProjectId,
                            user_id: currentUserId,
                            descriptions: descriptions
                        });
                    }

                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('activitiesModal')).hide();
                        loadActivities();
                        toastr.success(response.message || '{{ __('messages.activities_saved_successfully') }}');
                    } else {
                        toastr.error(response.message || '{{ __('messages.failed_to_save_activity') }}');
                    }
                } catch (error) {
                    toastr.error('{{ __('messages.error_saving_activity') }}');
                } finally {
                    saveBtn.textContent = originalText;
                }
            }

            async function saveManpower() {
                const form = document.getElementById('manpowerForm');
                const itemId = document.getElementById('manpowerId').value;
                const categories = Array.from(form.querySelectorAll('input[name="category[]"]')).map(input => input.value
                    .trim());
                const counts = Array.from(form.querySelectorAll('input[name="count[]"]')).map(input => parseInt(input
                    .value));

                const validItems = [];
                for (let i = 0; i < categories.length; i++) {
                    if (categories[i] && !isNaN(counts[i]) && counts[i] >= 0) {
                        validItems.push({
                            category: categories[i],
                            count: counts[i]
                        });
                    }
                }

                if (validItems.length === 0) {
                    toastr.error('{{ __('messages.please_enter_valid_data') }}');
                    return;
                }

                const saveBtn = document.getElementById('manpowerSaveBtn');
                const originalText = saveBtn.textContent;
                saveBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';

                try {
                    let response;
                    if (itemId && validItems.length === 1) {
                        response = await api.updateManpowerEquipment({
                            item_id: itemId,
                            category: validItems[0].category,
                            count: validItems[0].count
                        });
                    } else {
                        response = await api.addManpowerEquipment({
                            project_id: currentProjectId,
                            user_id: currentUserId,
                            items: validItems
                        });
                    }

                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('manpowerModal')).hide();
                        loadManpowerEquipment();
                        toastr.success(response.message || '{{ __('messages.manpower_items_saved_successfully') }}');
                    } else {
                        toastr.error(response.message || '{{ __('messages.failed_to_save_manpower') }}');
                    }
                } catch (error) {
                    toastr.error('{{ __('messages.error_saving_manpower') }}');
                } finally {
                    saveBtn.textContent = originalText;
                }
            }

            async function saveSafetyItem() {
                const form = document.getElementById('safetyForm');
                const itemId = document.getElementById('safetyId').value;
                const checklistItems = Array.from(form.querySelectorAll('input[name="checklist_item[]"]'))
                    .map(input => input.value.trim())
                    .filter(item => item);

                if (checklistItems.length === 0) {
                    toastr.error('{{ __('messages.please_enter_safety_item') }}');
                    return;
                }

                const saveBtn = document.getElementById('safetySaveBtn');
                const originalText = saveBtn.textContent;
                saveBtn.innerHTML =
                    '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.saving') }}';

                try {
                    let response;
                    if (itemId && checklistItems.length === 1) {
                        response = await api.updateSafetyItem({
                            item_id: itemId,
                            checklist_item: checklistItems[0]
                        });
                    } else {
                        response = await api.addSafetyItem({
                            project_id: currentProjectId,
                            user_id: currentUserId,
                            checklist_items: checklistItems
                        });
                    }

                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('safetyModal')).hide();
                        loadSafetyItems();
                        toastr.success(response.message || '{{ __('messages.safety_items_saved_successfully') }}');
                    } else {
                        toastr.error(response.message || '{{ __('messages.failed_to_save_safety_item') }}');
                    }
                } catch (error) {
                    toastr.error('{{ __('messages.error_saving_safety_item') }}');
                } finally {
                    saveBtn.textContent = originalText;
                }
            }

            // Edit Functions
            function editActivity(id, description) {
                document.getElementById('activitiesModalTitle').textContent = '{{ __('messages.edit_activity') }}';
                document.getElementById('activitiesSaveBtn').textContent = '{{ __('messages.update') }}';
                document.getElementById('activityId').value = id;

                // Set the first input field value
                const container = document.getElementById('modalActivitiesContainer');
                container.innerHTML = `
        <div class="activity-field mb-3">
            <label class="form-label small fw-medium mb-1">{{ __('messages.description') }}</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control form-control-sm" name="description[]" 
                    value="${description}" placeholder="{{ __('messages.enter_activity_description') }}" required>
                <button type="button" class="btn btn-sm btn-outline-danger remove-field" onclick="removeField(this)" style="display:none;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;

                document.getElementById('addMoreActivityBtn').style.display = 'none';
                new bootstrap.Modal(document.getElementById('activitiesModal')).show();
            }

            function editManpower(id, category, count) {
                document.getElementById('manpowerModalTitle').textContent = '{{ __('messages.edit_manpower_equipment') }}';
                document.getElementById('manpowerSaveBtn').textContent = '{{ __('messages.update') }}';
                document.getElementById('manpowerId').value = id;

                // Set the first input field values
                const container = document.getElementById('modalManpowerContainer');
                container.innerHTML = `
        <div class="manpower-field mb-3">
            <div class="row g-2">
                <div class="col-7">
                    <label class="form-label small fw-medium mb-1">{{ __('messages.category') }}</label>
                    <input type="text" class="form-control form-control-sm" name="category[]" 
                        value="${category}" placeholder="{{ __('messages.enter_category') }}" required>
                </div>
                <div class="col-3">
                    <label class="form-label small fw-medium mb-1">{{ __('messages.count') }}</label>
                    <input type="number" class="form-control form-control-sm" name="count[]" 
                        value="${count}" placeholder="0" min="0" required>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-field" onclick="removeField(this)" style="display:none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

                document.getElementById('addMoreManpowerBtn').style.display = 'none';
                new bootstrap.Modal(document.getElementById('manpowerModal')).show();
            }

            // Activities Update Modal
            function openActivitiesUpdateModal() {
                if (!window.currentActivities || window.currentActivities.length === 0) {
                    toastr.warning('No activities to update');
                    return;
                }

                const modal = new bootstrap.Modal(document.getElementById('activitiesUpdateModal'));
                const container = document.getElementById('activitiesUpdateContainer');

                container.innerHTML = window.currentActivities.map(activity => `
        <div class="mb-2">
            <input type="hidden" name="activity_id[]" value="${activity.id}">
            <input type="text" class="form-control" name="activity_description[]" 
                value="${activity.description}" placeholder="Activity description" maxlength="150">
        </div>
    `).join('');

                modal.show();
            }

            // Manpower Update Modal
            function openManpowerUpdateModal() {
                if (!window.currentManpower || window.currentManpower.length === 0) {
                    toastr.warning('No manpower items to update');
                    return;
                }

                const modal = new bootstrap.Modal(document.getElementById('manpowerUpdateModal'));
                const container = document.getElementById('manpowerUpdateContainer');

                container.innerHTML = window.currentManpower.map(item => `
        <div class="row mb-2">
            <div class="col-6">
                <input type="hidden" name="manpower_id[]" value="${item.id}">
                <input type="text" class="form-control" name="manpower_category[]" 
                    value="${item.category}" placeholder="Category" maxlength="50">
            </div>
            <div class="col-6">
                <input type="number" class="form-control" name="manpower_count[]" 
                    value="${item.count}" placeholder="Count" min="0">
            </div>
        </div>
    `).join('');

                modal.show();
            }

            // Safety Update Modal
            function openSafetyUpdateModal() {
                if (!window.currentSafetyItems || window.currentSafetyItems.length === 0) {
                    toastr.warning('No safety items to update');
                    return;
                }

                const modal = new bootstrap.Modal(document.getElementById('safetyUpdateModal'));
                const container = document.getElementById('safetyUpdateContainer');

                container.innerHTML = window.currentSafetyItems.map(item => `
        <div class="mb-2">
            <input type="hidden" name="safety_id[]" value="${item.id}">
            <input type="text" class="form-control" name="safety_item[]" 
                value="${item.checklist_item}" placeholder="Safety item" maxlength="120">
        </div>
    `).join('');

                modal.show();
            }

            // Save Functions
            async function saveActivitiesUpdate() {
                const ids = Array.from(document.querySelectorAll('input[name="activity_id[]"]')).map(input => parseInt(input
                    .value));
                const descriptions = Array.from(document.querySelectorAll('input[name="activity_description[]"]')).map(
                    input => input.value);

                const activities = ids.map((id, index) => ({
                    id,
                    description: descriptions[index]
                }));

                try {
                    const response = await api.updateActivity({
                        activities
                    });
                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('activitiesUpdateModal')).hide();
                        loadActivities();
                        toastr.success('Activities updated successfully!');
                    }
                } catch (error) {
                    toastr.error('Failed to update activities');
                }
            }

            async function saveManpowerUpdate() {
                const ids = Array.from(document.querySelectorAll('input[name="manpower_id[]"]')).map(input => parseInt(input
                    .value));
                const categories = Array.from(document.querySelectorAll('input[name="manpower_category[]"]')).map(input =>
                    input.value);
                const counts = Array.from(document.querySelectorAll('input[name="manpower_count[]"]')).map(input =>
                    parseInt(input.value));

                const manpower = ids.map((id, index) => ({
                    id,
                    category: categories[index],
                    count: counts[index]
                }));

                try {
                    const response = await api.updateManpowerEquipment({
                        manpower
                    });
                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('manpowerUpdateModal')).hide();
                        loadManpowerEquipment();
                        toastr.success('Manpower updated successfully!');
                    }
                } catch (error) {
                    toastr.error('Failed to update manpower');
                }
            }

            async function saveSafetyUpdate() {
                const ids = Array.from(document.querySelectorAll('input[name="safety_id[]"]')).map(input => parseInt(input
                    .value));
                const items = Array.from(document.querySelectorAll('input[name="safety_item[]"]')).map(input => input
                    .value);

                const safety_items = ids.map((id, index) => ({
                    id,
                    checklist_item: items[index]
                }));

                try {
                    const response = await api.updateSafetyItem({
                        safety_items
                    });
                    if (response.code === 200) {
                        bootstrap.Modal.getInstance(document.getElementById('safetyUpdateModal')).hide();
                        loadSafetyItems();
                        toastr.success('Safety items updated successfully!');
                    }
                } catch (error) {
                    toastr.error('Failed to update safety items');
                }
            }
        </script>

    </div>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/universal-auth.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script src="{{ asset('website/js/button-protection.js') }}"></script>
    <script src="{{ asset('website/js/searchable-dropdown.js') }}"></script>

    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>
</body>

</html>
