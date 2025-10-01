@extends('website.layout.app')

@section('title', 'Project Progress')

@section('content')
    <div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
            <h2>{{ __('messages.project_progress') }}</h2>
            <p>{{ __('messages.track_project_phases') }}</p>
        </div>
        @can('projects', 'create')
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
            <div class="row g-4" id="phasesContainer">
                <!-- Dynamic phases will be loaded here -->
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-semibold  mb-3 mb-md-4 black_color">{{ __('messages.project_timeline') }}
                                    </h5>
                                    <div class="row gy-3 gx-5">
                                        <div class="col-12 col-md-6">
                                            <div class="mb-2 mb-md-3">
                                                <span
                                                    class="text-muted small black_color">{{ __('messages.project_name') }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-2">
                                                    <span id="projectName">{{ __('messages.loading') }}...</span>
                                                    @can('projects', 'edit')
                                                        <a href="#" class="text-primary ms-2 d-flex align-items-center"
                                                            title="Edit"><img
                                                                src="{{ asset('website/images/icons/edit.svg') }}"
                                                                alt="edit" style="width: 16px; height: 16px;"></a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span
                                                    class="text-muted small black_color">{{ __('messages.company_name') }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-2">
                                                    <span id="companyName">{{ __('messages.loading') }}...</span>
                                                    @can('projects', 'edit')
                                                        <a href="#" class="text-primary ms-2 d-flex align-items-center"
                                                            title="Edit"><img
                                                                src="{{ asset('website/images/icons/edit.svg') }}"
                                                                alt="edit" style="width: 16px; height: 16px;"></a>
                                                    @endcan
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
                                                <div class="fw-medium d-flex align-items-center gap-2">
                                                    <span id="projectType">{{ __('messages.loading') }}...</span>
                                                    @can('projects', 'edit')
                                                        <a href="#" class="text-primary ms-2 d-flex align-items-center"
                                                            title="Edit"><img
                                                                src="{{ asset('website/images/icons/edit.svg') }}"
                                                                alt="edit" style="width: 16px; height: 16px;"></a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span
                                                    class="text-muted small black_color">{{ __('messages.project_manager') }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-2">
                                                    <span id="projectManager">{{ __('messages.loading') }}...</span>
                                                    @can('projects', 'edit')
                                                        <a href="#" class="text-primary ms-2 d-flex align-items-center"
                                                            title="Edit"><img
                                                                src="{{ asset('website/images/icons/edit.svg') }}"
                                                                alt="edit" style="width: 16px; height: 16px;"></a>
                                                    @endcan
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3">
                                                <span
                                                    class="text-muted small black_color">{{ __('messages.site_engineer') }}</span>
                                                <div class="fw-medium d-flex align-items-center gap-2">
                                                    <span id="siteEngineer">{{ __('messages.loading') }}...</span>
                                                    @can('projects', 'edit')
                                                        <a href="#" class="text-primary ms-2 d-flex align-items-center"
                                                            title="Edit"><img
                                                                src="{{ asset('website/images/icons/edit.svg') }}"
                                                                alt="edit" style="width: 16px; height: 16px;"></a>
                                                    @endcan
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-4 d-flex flex-wrap gap-2">
                                        <button
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12 13.8778L6 12.1621V0.121509L12 1.83713V13.8778ZM13 13.8403V1.76213L16.9719 0.171509C17.4656 -0.0253659 18 0.337134 18 0.868384V11.3309C18 11.6371 17.8125 11.9121 17.5281 12.0278L13 13.8371V13.8403ZM0.471875 1.97151L5 0.162134V12.2371L1.02813 13.8278C0.534375 14.0246 0 13.6621 0 13.1309V2.66838C0 2.36213 0.1875 2.08713 0.471875 1.97151Z"
                                                    fill="#4477C4" />
                                            </svg>
                                            {{ __('messages.view_plan') }}
                                        </button>
                                        <button
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="21" height="16" viewBox="0 0 21 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
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
                                        <button
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3.23438 1V2H1.73438C0.90625 2 0.234375 2.67188 0.234375 3.5V5H14.2344V3.5C14.2344 2.67188 13.5625 2 12.7344 2H11.2344V1C11.2344 0.446875 10.7875 0 10.2344 0C9.68125 0 9.23438 0.446875 9.23438 1V2H5.23438V1C5.23438 0.446875 4.7875 0 4.23438 0C3.68125 0 3.23438 0.446875 3.23438 1ZM14.2344 6H0.234375V14.5C0.234375 15.3281 0.90625 16 1.73438 16H12.7344C13.5625 16 14.2344 15.3281 14.2344 14.5V6Z"
                                                    fill="#4477C4" />
                                            </svg>
                                            {{ __('messages.may_25_2025') }}
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
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.2s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <h5 class="fw-semibold  mb-3 mb-md-4 black_color">{{ __('messages.project_overview') }}</h5>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __('messages.foundation') }}</h6>
                                    <h6 class="text-muted fw-medium">100%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 100%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __('messages.structure') }}</h6>
                                    <h6 class="text-muted fw-medium">85%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 85%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%); "
                                        aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __('messages.roofing') }}</h6>
                                    <h6 class="text-muted fw-medium">60%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 60%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __('messages.interior') }}</h6>
                                    <h6 class="text-muted fw-medium">25%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 25%; background: linear-gradient(90deg, #4477C4 0%, #f59e42 100%);"
                                        aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            <div class="mb-2 mb-md-3">
                                <div class="mb-2 d-flex justify-content-between align-items-center gap-2">
                                    <h6 class="text-muted fw-medium">{{ __('messages.finishing') }}</h6>
                                    <h6 class="text-muted fw-medium">5%</h6>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: 5%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%);"
                                        aria-valuenow="5" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.3s">
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-semibold black_color mb-0 ">{{ __('messages.ongoing_activities') }}</h5>
                                {{-- <a href="#" class="text-primary" title="Edit"><img
                                        src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a> --}}
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="d-flex align-items-center mb-2">
                                    <span class="{{ margin_end(2) }}"
                                        style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    Concrete pouring on Basement Level 2
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="{{ margin_end(2) }}"
                                        style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    Formwork preparation for Ground Floor columns
                                </li>
                                <li class="d-flex align-items-center mb-2">
                                    <span class="{{ margin_end(2) }}"
                                        style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    Electrical conduit layout in progress on Level 1
                                </li>
                                <li class="d-flex align-items-center">
                                    <span class="{{ margin_end(2) }}"
                                        style="color:#F58D2E; font-size:1.2em;">&#9679;</span>
                                    QA/QC inspection for rebar placement
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4 fs-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-semibold black_color mb-0 fs-5">{{ __('messages.manpower_equipment') }}</h5>
                                {{-- <a href="#" class="text-primary" title="Edit"><img
                                        src="{{ asset('website/images/icons/edit.svg') }}" alt="edit"></a> --}}
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __('messages.engineers') }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">3</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __('messages.foremen') }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">2</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __('messages.laborers') }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">25</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __('messages.excavators') }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">2
                                                    {{ __('messages.units') }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __('messages.concrete_mixers') }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">1
                                                    {{ __('messages.unit') }}</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted fw-medium">{{ __('messages.cranes') }}</td>
                                            <td class="text-end"><a href="#"
                                                    class="text-primary fw-semibold text-decoration-none">1
                                                    {{ __('messages.unit') }}</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
                                <h5 class="fw-semibold black_color mb-0 ">{{ __('messages.safety_category') }}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('website.safety-checklist') }}"
                                        class="btn btn-primary  d-flex align-items-center gap-2 btnsm">
                                        <i class="fas fa-eye"></i> {{ __('messages.view_checklist') }}
                                    </a>
                                    <button class="btn btn-primary d-flex align-items-center gap-2 btnsm" 
                                        data-bs-toggle="modal" data-bs-target="#addSafetyChecklistModal">
                                        <i class="fas fa-plus"></i> {{ __('messages.add_checklist') }}
                                    </button>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        {{ __('messages.ppe_check') }} Completed
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg5">
                                        <span class="me-3 text-warning" style="font-size:1.3em;">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                        One unsafe scaffolding identified and corrected
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        Fire extinguisher checks completed
                                    </div>
                                </li>
                                <li class="mb-2">
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        Toolbox Talk Conducted (Topic: Working at Heights)
                                    </div>
                                </li>
                                <li>
                                    <div class="d-flex align-items-center p-3 rounded bg4">
                                        <span class="me-3 text-success" style="font-size:1.3em;">
                                            <i class="fas fa-check-circle"></i>
                                        </span>
                                        No incidents reported
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function deleteProject() {
            if (confirm('Are you sure you want to delete this project? This action cannot be undone.')) {
                alert('Project deletion functionality would be implemented here.');
            }
        }

        // Get project ID from controller
        let currentProjectId = {{ $project->id ?? 1 }};
        
        // Global variables for phase management
        let currentPhaseId = null;
        
        // Global function for opening phase modal
        function openPhaseModal(phaseName, phaseId) {
            currentPhaseId = phaseId;
            document.getElementById('phaseModalTitle').textContent = phaseName + ' - Management';
            const modal = new bootstrap.Modal(document.getElementById('phaseNavigationModal'));
            modal.show();
        }
        
        function redirectToInspections() {
            window.location.href = `/website/project/${currentProjectId}/phase-inspections?phase_id=${currentPhaseId}`;
        }

        function redirectToTasks() {
            window.location.href = `/website/project/${currentProjectId}/phase-tasks?phase_id=${currentPhaseId}`;
        }

        function redirectToSnags() {
            window.location.href = `/website/project/${currentProjectId}/phase-snags?phase_id=${currentPhaseId}`;
        }

        function redirectToTimeline() {
            window.location.href = `/website/project/${currentProjectId}/phase-timeline?phase_id=${currentPhaseId}`;
        }
        
        // Load project data and make edit buttons functional
        document.addEventListener('DOMContentLoaded', function() {
            loadProjectData();
            loadPhases();
            
            const editButtons = document.querySelectorAll('a[title="Edit"]');
            editButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    editProjectField(this);
                });
            });
            
            // Make manpower links functional
            const manpowerLinks = document.querySelectorAll('.table a');
            manpowerLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const role = this.closest('tr').querySelector('td').textContent;
                    const count = this.textContent;
                    alert(
                        `${role}: ${count}\n\nDetailed view would show:\n- Individual assignments\n- Work schedules\n- Performance metrics`
                    );
                });
            });
        });
        
        async function loadPhases() {
            try {
                const response = await api.makeRequest('projects/list_phases', {
                    project_id: currentProjectId,
                    user_id: {{ auth()->id() ?? 1 }}
                });
                
                if (response.code === 200 && response.data) {
                    renderPhases(response.data);
                } else {
                    console.error('Failed to load phases:', response.message);
                }
            } catch (error) {
                console.error('Error loading phases:', error);
            }
        }
        
        function renderPhases(phases) {
            const container = document.getElementById('phasesContainer');
            
            if (!phases || phases.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-layer-group fa-3x mb-3"></i>
                            <h5>No phases created yet</h5>
                            <p>Create your first project phase to get started</p>
                        </div>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = phases.map((phase, index) => {
                const progress = Math.round(phase.progress_percentage || 0); // Use actual progress from database
                const totalDays = phase.milestones ? phase.milestones.reduce((sum, m) => sum + (m.days || 0), 0) : 0;
                const badgeClass = progress === 100 ? 'badge1' : progress > 0 ? 'badge4' : 'badge2';
                const badgeText = progress === 100 ? 'Completed' : progress > 0 ? 'In Progress' : 'Pending';
                const progressColor = progress === 100 ? 'bg-success' : 'linear-gradient(90deg, #4477C4 0%, #F58D2E 100%)';
                
                return `
                    <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay="${index * 0.4}s">
                        <div class="card h-100 B_shadow" style="cursor: pointer;" onclick="openPhaseModal('${phase.title}', ${phase.id})">
                            <div class="card-body p-md-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-semibold black_color mb-0">${phase.title}</h5>
                                    <span class="badge ${badgeClass}">${badgeText}</span>
                                </div>
                                <div class="mb-3">
                                    <div class="progress" style="height:8px;">
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: ${progress}%; background: ${progressColor};"
                                            aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">${progress}% Complete</small>
                                        <small class="text-muted">${totalDays} days</small>
                                    </div>
                                </div>
                                <div class="small text-muted">
                                    ${phase.milestones ? phase.milestones.map(milestone => 
                                        `<div>• ${milestone.milestone_name}${milestone.days ? ` - ${milestone.days} days` : ''}</div>`
                                    ).join('') : '<div>No milestones defined</div>'}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        async function loadProjectData() {
            try {
                const response = await api.getProjectDetails({
                    project_id: currentProjectId
                });
                
                if (response.code === 200 && response.data) {
                    const project = response.data;
                    
                    // Update project details
                    document.getElementById('projectName').textContent = project.project_title || 'N/A';
                    document.getElementById('companyName').textContent = project.contractor_name || 'N/A';
                    document.getElementById('projectType').textContent = project.type || 'N/A';
                    document.getElementById('projectManager').textContent = project.project_manager_name || 'N/A';
                    document.getElementById('siteEngineer').textContent = project.technical_engineer_name || 'N/A';
                    
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
            const elements = ['projectName', 'companyName', 'projectType', 'projectManager', 'siteEngineer', 'startDate', 'endDate'];
            elements.forEach(id => {
                const element = document.getElementById(id);
                if (element) element.textContent = 'Error loading data';
            });
        }

        async function editProjectField(editButton) {
            const fieldElement = editButton.previousElementSibling;
            const currentValue = fieldElement.textContent.trim();
            const fieldLabel = editButton.closest('.mb-2, .mb-md-3').querySelector('.text-muted').textContent;
            
            let inputElement;
            
            // Check if this field needs a dropdown
            if (fieldLabel === 'Project Manager' || fieldLabel === 'Site Engineer') {
                // Create dropdown for user selection
                inputElement = document.createElement('select');
                inputElement.className = 'form-select form-select-sm d-inline-block';
                inputElement.style.width = 'auto';
                inputElement.style.minWidth = '200px';
                
                // Add loading option
                inputElement.innerHTML = '<option>Loading users...</option>';
                
                // Load users for dropdown
                loadUsersForDropdown(inputElement, fieldLabel, currentValue);
            } else {
                // Create regular input field
                inputElement = document.createElement('input');
                inputElement.type = 'text';
                inputElement.value = currentValue;
                inputElement.className = 'form-control form-control-sm d-inline-block';
                inputElement.style.width = 'auto';
                inputElement.style.minWidth = '200px';
            }
            
            // Create save and cancel buttons
            const saveBtn = document.createElement('button');
            saveBtn.innerHTML = '<i class="fas fa-check"></i>';
            saveBtn.className = 'btn btn-success btn-sm ms-2';
            
            const cancelBtn = document.createElement('button');
            cancelBtn.innerHTML = '<i class="fas fa-times"></i>';
            cancelBtn.className = 'btn btn-secondary btn-sm ms-1';
            
            // Replace text with input/select
            fieldElement.innerHTML = '';
            fieldElement.appendChild(inputElement);
            fieldElement.appendChild(saveBtn);
            fieldElement.appendChild(cancelBtn);
            
            // Hide edit button
            editButton.style.display = 'none';
            
            // Focus input
            inputElement.focus();
            
            // Cancel function
            const cancelEdit = () => {
                fieldElement.textContent = currentValue;
                editButton.style.display = 'flex';
            };
            
            // Save function
            const saveEdit = async () => {
                let newValue, newId;
                
                if (inputElement.tagName === 'SELECT') {
                    // For dropdown, get selected option text and value
                    const selectedOption = inputElement.options[inputElement.selectedIndex];
                    newValue = selectedOption.text;
                    newId = selectedOption.value;
                    
                    if (!newId || newValue === currentValue) {
                        cancelEdit();
                        return;
                    }
                } else {
                    // For regular input
                    newValue = inputElement.value.trim();
                    if (!newValue || newValue === currentValue) {
                        cancelEdit();
                        return;
                    }
                }
                
                // Map field labels to API field names
                const fieldMapping = {
                    'Project Name': 'project_title',
                    'Company Name': 'contractor_name', 
                    'Project Type': 'type',
                    'Project Manager': 'project_manager_id',
                    'Site Engineer': 'technical_engineer_id'
                };
                
                const apiField = fieldMapping[fieldLabel];
                if (!apiField) {
                    cancelEdit();
                    return;
                }
                
                // Show loading
                saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                saveBtn.disabled = true;
                
                try {
                    const updateData = {
                        project_id: currentProjectId,
                        [apiField]: inputElement.tagName === 'SELECT' ? newId : newValue
                    };
                    
                    const response = await api.updateProject(updateData);
                    
                    if (response.code === 200) {
                        fieldElement.textContent = newValue;
                        editButton.style.display = 'flex';
                    } else {
                        cancelEdit();
                    }
                } catch (error) {
                    console.error('Update failed:', error);
                    cancelEdit();
                }
            };
            
            // Event listeners
            saveBtn.addEventListener('click', saveEdit);
            cancelBtn.addEventListener('click', cancelEdit);
            inputElement.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') saveEdit();
                if (e.key === 'Escape') cancelEdit();
            });
        }
        
        async function loadUsersForDropdown(selectElement, fieldLabel, currentValue) {
            try {
                let response;
                if (fieldLabel === 'Project Manager') {
                    response = await api.getProjectManagers();
                } else if (fieldLabel === 'Site Engineer') {
                    response = await api.getTechnicalEngineers();
                }
                
                if (response.code === 200 && response.data) {
                    selectElement.innerHTML = '<option value="">Select ' + fieldLabel + '</option>';
                    
                    response.data.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        
                        // Select current user if matches
                        if (user.name === currentValue) {
                            option.selected = true;
                        }
                        
                        selectElement.appendChild(option);
                    });
                } else {
                    selectElement.innerHTML = '<option>No users found</option>';
                }
            } catch (error) {
                console.error('Failed to load users:', error);
                selectElement.innerHTML = '<option>Error loading users</option>';
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createPhaseForm">
                        @csrf
                        <input type="hidden" name="project_id" value="1">
                        <input type="hidden" name="created_by" value="{{ auth()->id() ?? 1 }}">

                        <div class="mb-3">
                            <label for="title" class="form-label fw-medium">{{ __('messages.phase_title') }}</label>
                            <input type="text" class="form-control Input_control" id="title" name="title"
                                required placeholder="e.g., Foundation Work, Structure Phase, Finishing">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">{{ __('messages.milestones') }}</label>
                            <div id="milestonesContainer">
                                <div class="milestone-item mb-2">
                                    <div class="row">
                                        <div class="col-8">
                                            <input type="text" class="form-control Input_control"
                                                name="milestones[0][milestone_name]"
                                                placeholder="{{ __('messages.milestone_name') }}" required>
                                        </div>
                                        <div class="col-3">
                                            <input type="number" class="form-control Input_control"
                                                name="milestones[0][days]" placeholder="{{ __('messages.days') }}"
                                                min="1">
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
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMilestone()">
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
          placeholder="Milestone name" required>
      </div>
      <div class="col-3">
        <input type="number" class="form-control Input_control" name="milestones[${milestoneIndex}][days]" 
          placeholder="Days" min="1">
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
                createPhaseForm.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(createPhaseForm);
                    const title = formData.get('title');
                    
                    // Collect milestones
                    const milestones = [];
                    const milestoneItems = document.querySelectorAll('.milestone-item');
                    
                    milestoneItems.forEach((item, index) => {
                        const nameInput = item.querySelector(`input[name="milestones[${index}][milestone_name]"]`);
                        const daysInput = item.querySelector(`input[name="milestones[${index}][days]"]`);
                        
                        if (nameInput && nameInput.value.trim()) {
                            milestones.push({
                                milestone_name: nameInput.value.trim(),
                                days: daysInput && daysInput.value ? parseInt(daysInput.value) : null
                            });
                        }
                    });

                    if (title.trim()) {
                        try {
                            const response = await api.makeRequest('projects/create_phase', {
                                project_id: currentProjectId,
                                user_id: {{ auth()->id() ?? 1 }},
                                title: title,
                                milestones: milestones.length > 0 ? milestones : null
                            });
                            
                            if (response.code === 200) {
                                // Close modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById('createPhaseModal'));
                                if (modal) modal.hide();
                                
                                // Show success message
                                alert('Phase "' + title + '" created successfully!');
                                
                                // Reset form
                                createPhaseForm.reset();
                                milestoneIndex = 1;
                                resetMilestonesContainer();
                                
                                // Reload phases
                                loadPhases();
                            } else {
                                alert('Error creating phase: ' + response.message);
                            }
                        } catch (error) {
                            console.error('Error creating phase:', error);
                            alert('Error creating phase. Please try again.');
                        }
                    }
                });
            }
        });
        
        function resetMilestonesContainer() {
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
                                placeholder="Days" min="1">
                        </div>
                        <div class="col-1">
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeMilestone(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
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
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="card h-100 border-primary" style="cursor: pointer;"
                                onclick="redirectToInspections()">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-clipboard-check fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">{{ __('messages.inspections') }}</h5>
                                    <p class="card-text text-muted">{{ __('messages.manage_phase_inspections') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-success" style="cursor: pointer;" onclick="redirectToTasks()">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-tasks fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">{{ __('messages.tasks') }}</h5>
                                    <p class="card-text text-muted">{{ __('messages.manage_phase_tasks') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-warning" style="cursor: pointer;" onclick="redirectToSnags()">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">{{ __('messages.snag_list') }}</h5>
                                    <p class="card-text text-muted">{{ __('messages.manage_phase_issues') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100 border-info" style="cursor: pointer;" onclick="redirectToTimeline()">
                                <div class="card-body text-center p-4">
                                    <i class="fas fa-chart-line fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">{{ __('messages.project_timeline') }}</h5>
                                    <p class="card-text text-muted">{{ __('messages.view_project_timeline') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@include('website.modals.add-safety-checklist-modal')

<script>
// Add Safety Checklist Form Handler for Project Progress Page
document.addEventListener('DOMContentLoaded', function() {
  const addSafetyChecklistForm = document.getElementById('addSafetyChecklistForm');
  if (addSafetyChecklistForm) {
    addSafetyChecklistForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const submitBtn = document.querySelector('#addSafetyChecklistModal .btn.orange_btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.creating') }}...';
      submitBtn.disabled = true;
      
      // Simulate checklist creation
      setTimeout(() => {
        alert('{{ __('messages.safety_checklist_created_successfully') }}');
        bootstrap.Modal.getInstance(document.getElementById('addSafetyChecklistModal')).hide();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        addSafetyChecklistForm.reset();
        
        // Reset safety items to default
        document.getElementById('safetyItems').innerHTML = `
          <div class="input-group mb-2">
            <input type="text" class="form-control" name="items[]" placeholder="{{ __('messages.safety_item_placeholder') }}">
            <button class="btn btn-outline-danger" type="button" onclick="removeItem(this)">
              <i class="fas fa-times"></i>
            </button>
          </div>
        `;
        
        location.reload();
      }, 2000);
    });
  }
});
</script>

@endsection
