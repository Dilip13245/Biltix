@extends('website.layout.app')

@section('title', 'Project Progress')

@section('content')
    <!-- GOOGLE MAPS -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places,marker&language={{ app()->getLocale() }}&callback=Function.prototype">
    </script>

    <style>
        #projectLocationMap {
            width: 100%;
            height: 350px;
            border-radius: 12px;
            border: 2px solid #e9ecef;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .pac-container {
            border-radius: 8px;
            margin-top: 5px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-family: 'Poppins', sans-serif;
            z-index: 9999 !important;
        }

        .location-display {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        #locationEditMode {
            display: none;
        }

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

        .custom-combo-dropdown input {
            padding-right: 35px;
            cursor: pointer;
        }

        [dir="rtl"] .custom-combo-dropdown input {
            padding-right: 0.75rem;
            padding-left: 35px;
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

        .clear-selection {
            transition: all 0.2s ease;
            font-size: 14px;
        }

        .clear-selection:hover {
            color: #F58D2E !important;
            transform: translateY(-50%) scale(1.1);
        }
    </style>
    <div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
        <div>
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
            <!-- Project Phases Cards -->
            <div class="row g-4" id="phasesContainer">
                <!-- Dynamic phases will be loaded here -->
            </div>
            <div class="row mt-4 wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-start">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4">
                                        <h5 class="fw-semibold black_color mb-0">{{ __('messages.project_timeline') }}</h5>
                                        @can('projects', 'edit')
                                            <button class="btn btn-sm btn-outline-primary" id="editProjectBtn"
                                                onclick="toggleProjectEdit()">
                                                <i class="fas fa-edit me-1"></i>{{ __('messages.edit_project') }}
                                            </button>
                                        @endcan
                                    </div>
                                    <!-- Overall Project Progress (Simple line like phases) -->
                                    <div class="mb-3 mb-md-4">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted small fw-medium">Completion Percentages</span>
                                            <span class="text-primary fw-semibold" id="overallProjectProgress">0%</span>
                                        </div>
                                        <div class="progress" style="height:12px; border-radius: 6px;">
                                            <div class="progress-bar" 
                                                 role="progressbar" 
                                                 id="overallProjectProgressBar"
                                                 style="width: 0%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%); border-radius: 6px;" 
                                                 aria-valuenow="0" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">
                                            </div>
                                        </div>
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
                                            <div class="mb-2 mb-md-3" style="display: none;">
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
                                            <div class="mb-2 mb-md-3" style="display: none;">
                                                <span
                                                    class="text-muted small black_color">{{ __('messages.project_manager') }}</span>
                                                <div class="fw-medium">
                                                    <span id="projectManager">{{ __('messages.loading') }}...</span>
                                                </div>
                                            </div>
                                            <div class="mb-2 mb-md-3" style="display: none;">
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
                                        <button onclick="redirectToTimeline()"
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
                                            onclick="window.location.href='/website/project/'+currentProjectId+'/daily-logs'"
                                            class="btn btn-outline-primary d-flex align-items-center gap-2 rounded-5 svg-hover-white">
                                            <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
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
                                        <button class="btn btn-danger d-flex align-items-center gap-2 py-md-2 api-action-btn"
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

            <!-- Project Location Map Section -->
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.0s">
                <div class="col-12">
                    <div class="card B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-semibold black_color mb-0">{{ __('messages.project_location') }}</h5>
                                @can('projects', 'edit')
                                    <button class="btn btn-sm btn-outline-primary" onclick="toggleLocationEdit(event)">
                                        <i class="fas fa-edit {{ margin_end(1) }}"></i>{{ __('messages.edit_location') }}
                                    </button>
                                @endcan
                            </div>

                            <!-- View Mode -->
                            <div id="locationViewMode">
                                <div class="location-display">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <span class="fw-medium"
                                            id="displayLocation">{{ __('messages.loading') }}...</span>
                                    </div>
                                    <div class="text-muted" style="font-size: 12px;" id="displayCoordinates"></div>
                                </div>
                                <div id="projectLocationMap"></div>
                            </div>

                            <!-- Edit Mode -->
                            <div id="locationEditMode" class="location-edit-mode">
                                <div class="mb-3">
                                    <div class="map-search-wrapper position-relative">
                                        <input type="text" class="form-control" id="editProjectLocation"
                                            placeholder="{{ __('messages.search_location') }}">
                                        <i class="fas fa-search position-absolute"
                                            style="{{ is_rtl() ? 'left' : 'right' }}: 12px; top: 50%; transform: translateY(-50%); color: #6c757d; pointer-events: none;"></i>
                                    </div>
                                    <input type="hidden" id="editLatitude">
                                    <input type="hidden" id="editLongitude">
                                </div>
                                <div id="editLocationMap"
                                    style="width: 100%; height: 350px; border-radius: 12px; border: 2px solid #e9ecef;">
                                </div>
                                <div class="location-info mt-2" id="editLocationInfo" style="display: none;">
                                    <div class="bg-light p-2 rounded">
                                        <small class="text-muted" id="editCoordinatesDisplay"></small>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <button class="btn btn-success api-action-btn" onclick="saveLocationEdit()">
                                        <i class="fas fa-save {{ margin_end(1) }}"></i>{{ __('messages.save') }}
                                    </button>
                                    <button class="btn btn-secondary" onclick="cancelLocationEdit()">
                                        {{ __('messages.cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Project Overview Section - Commented Out
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
            --}}
            {{-- Ongoing Activities and Manpower & Equipment Sections - Commented Out
            <div class="row mt-4 wow fadeInUp" data-wow-delay="1.3s">
                <div class="col-12 col-lg-6 mb-4 mb-lg-0">
                    <div class="card h-100 B_shadow">
                        <div class="card-body p-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-semibold black_color mb-0 ">{{ __('messages.ongoing_activities') }}</h5>
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
            --}}
            {{-- Safety Category Section - Commented Out
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
            --}}
        </div>
    </div>
    <script>
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
                    user_id: {{ auth()->id() ?? 1 }}
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

        // Get project ID from controller
        let currentProjectId = {{ $project->id ?? 1 }};

        // Google Maps variables
        let projectMap, projectMarker;
        let editMap, editMarker, editAutocomplete;
        let currentProjectData = null;
        const saudiArabia = {
            lat: 23.8859,
            lng: 45.0792
        };
        let mapInitialized = false;

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



        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        let extendTimeout = {};

        async function extendMilestone(milestoneId) {
            // Clear existing timeout for this milestone
            if (extendTimeout[milestoneId]) {
                clearTimeout(extendTimeout[milestoneId]);
            }

            // Debounce API calls
            extendTimeout[milestoneId] = setTimeout(async () => {
                const extensionInput = document.getElementById(`ext_${milestoneId}`);
                const extensionDays = parseInt(extensionInput.value) || 0;

                if (extensionDays < 0 || extensionDays > 3650) {
                    toastr.error('Extension days must be between 0 and 3650');
                    extensionInput.value = Math.min(Math.max(extensionDays, 0), 3650);
                    return;
                }

                try {
                    const response = await api.makeRequest('projects/extend_milestone', {
                        milestone_id: milestoneId,
                        user_id: {{ auth()->id() ?? 1 }},
                        extension_days: extensionDays
                    });

                    if (response.code === 200) {
                        loadPhases(true); // Force reload after extension
                        if (extensionDays > 0) {
                            alert(
                                `{{ __('messages.milestone_extended_successfully') }}\n{{ __('messages.extended_by') }}: ${extensionDays} {{ __('messages.days') }}`
                            );
                        } else {
                            alert('{{ __('messages.extension_reset_successfully') }}');
                        }
                    } else {
                        alert('{{ __('messages.failed_to_extend_milestone') }}: ' + response.message);
                    }
                } catch (error) {
                    console.error('Error extending milestone:', error);
                    alert('{{ __('messages.error_extending_milestone') }}');
                }
            }, 500); // 500ms debounce
        }

        async function quickExtend(milestoneId, days) {
            const extensionInput = document.getElementById(`ext_${milestoneId}`);
            const currentExtension = parseInt(extensionInput.value) || 0;
            const newExtension = currentExtension + days;

            extensionInput.value = newExtension;
            await extendMilestone(milestoneId);
        }

        // Initialize project location map
        function initProjectLocationMap(lat, lng, address) {
            if (!lat || !lng) return;

            try {
                const location = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng)
                };

                projectMap = new google.maps.Map(document.getElementById('projectLocationMap'), {
                    center: location,
                    zoom: 15,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true
                });

                projectMarker = new google.maps.Marker({
                    position: location,
                    map: projectMap,
                    title: address
                });

                mapInitialized = true;
            } catch (error) {
                console.error('Error initializing map:', error);
            }
        }

        // Initialize edit location map
        function initEditLocationMap() {
            try {
                const center = currentProjectData?.latitude && currentProjectData?.longitude ? {
                        lat: parseFloat(currentProjectData.latitude),
                        lng: parseFloat(currentProjectData.longitude)
                    } :
                    saudiArabia;

                editMap = new google.maps.Map(document.getElementById('editLocationMap'), {
                    center: center,
                    zoom: currentProjectData?.latitude ? 15 : 6,
                    mapTypeControl: true,
                    streetViewControl: false,
                    fullscreenControl: true
                });

                if (currentProjectData?.latitude && currentProjectData?.longitude) {
                    editMarker = new google.maps.Marker({
                        position: center,
                        map: editMap,
                        animation: google.maps.Animation.DROP,
                        draggable: true
                    });

                    editMarker.addListener('dragend', function(event) {
                        updateEditLocation(event.latLng);
                    });
                }

                const input = document.getElementById('editProjectLocation');
                editAutocomplete = new google.maps.places.Autocomplete(input, {
                    componentRestrictions: {
                        country: 'sa'
                    },
                    fields: ['formatted_address', 'geometry', 'name']
                });

                editAutocomplete.addListener('place_changed', function() {
                    const place = editAutocomplete.getPlace();
                    if (!place.geometry) {
                        showToast('{{ __('messages.no_location_found') }}', 'error');
                        return;
                    }
                    updateEditMapLocation(place.geometry.location, place.formatted_address || place.name);
                });

                editMap.addListener('click', function(event) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        location: event.latLng
                    }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            updateEditMapLocation(event.latLng, results[0].formatted_address);
                            document.getElementById('editProjectLocation').value = results[0]
                                .formatted_address;
                        }
                    });
                });
            } catch (error) {
                console.error('Error initializing edit map:', error);
            }
        }

        function updateEditMapLocation(location, address) {
            if (editMarker) editMarker.setMap(null);

            editMarker = new google.maps.Marker({
                position: location,
                map: editMap,
                animation: google.maps.Animation.DROP,
                draggable: true
            });

            editMap.setCenter(location);
            editMap.setZoom(15);

            document.getElementById('editLatitude').value = location.lat();
            document.getElementById('editLongitude').value = location.lng();
            document.getElementById('editCoordinatesDisplay').textContent =
                `{{ __('messages.lat') }}: ${location.lat().toFixed(6)}, {{ __('messages.lng') }}: ${location.lng().toFixed(6)}`;
            document.getElementById('editLocationInfo').style.display = 'block';

            editMarker.addListener('dragend', function(event) {
                updateEditLocation(event.latLng);
            });
        }

        function updateEditLocation(latLng) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                location: latLng
            }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    document.getElementById('editProjectLocation').value = results[0].formatted_address;
                    document.getElementById('editLatitude').value = latLng.lat();
                    document.getElementById('editLongitude').value = latLng.lng();
                    document.getElementById('editCoordinatesDisplay').textContent =
                        `{{ __('messages.lat') }}: ${latLng.lat().toFixed(6)}, {{ __('messages.lng') }}: ${latLng.lng().toFixed(6)}`;
                }
            });
        }

        function toggleLocationEdit(event) {
            event.preventDefault();

            if (typeof google === 'undefined') {
                showToast('{{ __('messages.map_loading_error') }}', 'error');
                return;
            }

            const viewMode = document.getElementById('locationViewMode');
            const editMode = document.getElementById('locationEditMode');

            viewMode.style.display = 'none';
            editMode.style.display = 'block';

            if (currentProjectData) {
                document.getElementById('editProjectLocation').value = currentProjectData.project_location || '';
                document.getElementById('editLatitude').value = currentProjectData.latitude || '';
                document.getElementById('editLongitude').value = currentProjectData.longitude || '';
            }

            setTimeout(() => {
                if (!editMap) {
                    initEditLocationMap();
                } else {
                    google.maps.event.trigger(editMap, 'resize');
                    if (currentProjectData?.latitude && currentProjectData?.longitude) {
                        const center = {
                            lat: parseFloat(currentProjectData.latitude),
                            lng: parseFloat(currentProjectData.longitude)
                        };
                        editMap.setCenter(center);
                    }
                }
            }, 100);
        }

        function cancelLocationEdit() {
            document.getElementById('locationViewMode').style.display = 'block';
            document.getElementById('locationEditMode').style.display = 'none';
        }

        async function saveLocationEdit() {
            const location = document.getElementById('editProjectLocation').value.trim();
            const latitude = document.getElementById('editLatitude').value;
            const longitude = document.getElementById('editLongitude').value;

            if (!location || !latitude || !longitude) {
                showToast('{{ __('messages.please_select_location') }}', 'warning');
                return;
            }

            try {
                const response = await api.updateProject({
                    project_id: currentProjectId,
                    project_location: location,
                    latitude: latitude,
                    longitude: longitude
                });

                if (response.code === 200) {
                    showToast('{{ __('messages.location_updated_successfully') }}', 'success');
                    cancelLocationEdit();
                    loadProjectData();
                } else {
                    showToast(response.message || '{{ __('messages.failed_to_update_location') }}', 'error');
                }
            } catch (error) {
                console.error('Error updating location:', error);
                showToast('{{ __('messages.error_updating_location') }}', 'error');
            }
        }

        let isEditMode = false;
        let originalValues = {};

        function toggleProjectEdit() {
            isEditMode = true;

            // Store original values
            originalValues = {
                projectName: document.getElementById('projectName').textContent,
                // companyName: document.getElementById('companyName').textContent, // Hidden
                projectType: document.getElementById('projectType').textContent,
                // projectManager: document.getElementById('projectManager').textContent, // Hidden
                // siteEngineer: document.getElementById('siteEngineer').textContent // Hidden
            };

            // Make fields editable
            makeFieldEditable('projectName', 'text');
            // makeFieldEditable('companyName', 'text'); // Hidden
            makeFieldEditable('projectType', 'combo-dropdown');
            // makeFieldEditable('projectManager', 'dropdown', 'Project Manager'); // Hidden
            // makeFieldEditable('siteEngineer', 'dropdown', 'Site Engineer'); // Hidden

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

                // Set maxlength based on field
                const maxLengths = {
                    'projectName': 255,
                    'companyName': 255,
                    'projectType': 100,
                    'projectLocation': 500
                };
                input.maxLength = maxLengths[fieldId] || 255;

                fieldElement.innerHTML = '';
                fieldElement.appendChild(input);
            } else if (type === 'combo-dropdown') {
                // Create combo dropdown for project type
                const wrapper = document.createElement('div');
                wrapper.className = 'custom-combo-dropdown position-relative';
                wrapper.style.width = '100%';
                wrapper.style.maxWidth = '300px';

                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                input.className = 'form-control Input_control';
                input.id = fieldId + '_input';
                input.placeholder = '{{ __('messages.select_type') }}';
                input.autocomplete = 'off';
                input.maxLength = 50;

                const dropdownArrow = document.createElement('i');
                dropdownArrow.className = 'fas fa-chevron-down dropdown-arrow';

                const clearBtn = document.createElement('i');
                clearBtn.className = 'fas fa-times clear-selection d-none';
                clearBtn.id = 'clear' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1) + 'Selection';
                clearBtn.style.cssText = 'position: absolute; right: 35px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #999; z-index: 10;';
                if (document.documentElement.dir === 'rtl' || document.documentElement.getAttribute('dir') === 'rtl') {
                    clearBtn.style.left = '35px';
                    clearBtn.style.right = 'auto';
                }
                clearBtn.onclick = function() {
                    input.readOnly = false;
                    input.value = '';
                    input.style.cursor = 'text';
                    input.style.backgroundColor = '';
                    clearBtn.classList.add('d-none');
                    dropdown.querySelectorAll('.dropdown-option').forEach(option => {
                        option.style.display = 'block';
                    });
                };

                const dropdown = document.createElement('div');
                dropdown.className = 'dropdown-options';
                dropdown.id = fieldId + 'Dropdown';

                // Add dropdown options
                const options = [
                    '{{ __('messages.villa') }}',
                    '{{ __('messages.tower') }}',
                    '{{ __('messages.hospital') }}',
                    '{{ __('messages.commercial') }}',
                    '{{ __('messages.residential') }}',
                    '{{ __('messages.industrial') }}'
                ];

                options.forEach(optionText => {
                    const option = document.createElement('div');
                    option.className = 'dropdown-option';
                    option.setAttribute('data-value', optionText);
                    option.textContent = optionText;
                    option.addEventListener('click', function() {
                        input.value = this.getAttribute('data-value');
                        input.readOnly = true;
                        input.style.cursor = 'pointer';
                        input.style.backgroundColor = '#f8f9fa';
                        clearBtn.classList.remove('d-none');
                        const isRTL = document.documentElement.dir === 'rtl' || document.documentElement.getAttribute('dir') === 'rtl';
                        if (isRTL) {
                            clearBtn.style.left = '35px';
                            clearBtn.style.right = 'auto';
                        } else {
                            clearBtn.style.right = '35px';
                            clearBtn.style.left = 'auto';
                        }
                        dropdown.classList.remove('show');
                    });
                    dropdown.appendChild(option);
                });

                wrapper.appendChild(input);
                wrapper.appendChild(dropdownArrow);
                wrapper.appendChild(clearBtn);
                wrapper.appendChild(dropdown);

                // Check if input has value and make it readonly
                if (currentValue) {
                    input.readOnly = true;
                    input.style.cursor = 'pointer';
                    input.style.backgroundColor = '#f8f9fa';
                    clearBtn.classList.remove('d-none');
                }

                // Show dropdown on input click
                input.addEventListener('click', function() {
                    dropdown.classList.add('show');
                });

                // Filter options on input
                input.addEventListener('input', function() {
                    if (this.readOnly) return;

                    if (!this.value.trim()) {
                        this.readOnly = false;
                        this.style.cursor = 'text';
                        this.style.backgroundColor = '';
                        clearBtn.classList.add('d-none');
                    }

                    const filter = this.value.toLowerCase();
                    dropdown.querySelectorAll('.dropdown-option').forEach(option => {
                        const text = option.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    dropdown.classList.add('show');
                });

                // Prevent typing when readonly
                input.addEventListener('keydown', function(e) {
                    if (this.readOnly) {
                        if (e.key === 'Backspace' || e.key === 'Delete') {
                            e.preventDefault();
                            clearBtn.onclick();
                        } else {
                            e.preventDefault();
                        }
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!wrapper.contains(e.target)) {
                        dropdown.classList.remove('show');
                    }
                });

                fieldElement.innerHTML = '';
                fieldElement.appendChild(wrapper);
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
                            new SearchableDropdown(selectElement, {
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
            saveBtn.className = 'btn btn-sm btn-success api-action-btn';
            saveBtn.id = 'saveProjectBtn';
            saveBtn.innerHTML = '<i class="fas fa-save me-1"></i>{{ __('messages.save') }}';
            saveBtn.onclick = saveProjectChanges;

            // Create cancel button
            const cancelBtn = document.createElement('button');
            cancelBtn.className = 'btn btn-sm btn-secondary';
            cancelBtn.id = 'cancelProjectBtn';
            cancelBtn.innerHTML = '{{ __('messages.cancel') }}';
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
                // const companyNameInput = document.getElementById('companyName_input'); // Hidden
                const projectTypeInput = document.getElementById('projectType_input');
                // const projectManagerInput = document.getElementById('projectManager_input'); // Hidden
                // const siteEngineerInput = document.getElementById('siteEngineer_input'); // Hidden

                if (projectNameInput && projectNameInput.value.trim()) {
                    updateData.project_title = projectNameInput.value.trim();
                }
                // if (companyNameInput && companyNameInput.value.trim()) { // Hidden
                //     updateData.contractor_name = companyNameInput.value.trim();
                // }
                if (projectTypeInput && projectTypeInput.value.trim()) {
                    updateData.type = projectTypeInput.value.trim();
                }
                // if (projectManagerInput && projectManagerInput.value) { // Hidden
                //     updateData.project_manager_id = projectManagerInput.value;
                // }
                // if (siteEngineerInput && siteEngineerInput.value) { // Hidden
                //     updateData.technical_engineer_id = siteEngineerInput.value;
                // }

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
            // document.getElementById('companyName').textContent = originalValues.companyName; // Hidden
            document.getElementById('projectType').textContent = originalValues.projectType;
            // document.getElementById('projectManager').textContent = originalValues.projectManager; // Hidden
            // document.getElementById('siteEngineer').textContent = originalValues.siteEngineer; // Hidden

            // Show edit button again
            const btn = document.getElementById('editProjectBtn');
            btn.style.display = 'inline-block';

            // Remove button container
            const btnContainer = document.getElementById('editButtonsContainer');
            if (btnContainer) btnContainer.remove();
        }

        // Load project data and make edit buttons functional
        document.addEventListener('DOMContentLoaded', function() {
            // Set current date on button
            const currentDateBtn = document.getElementById('currentDateBtn');
            if (currentDateBtn) {
                const today = new Date();
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                currentDateBtn.textContent = today.toLocaleDateString('en-US', options);
            }

            loadProjectData();
            loadPhases();
        });

        let phasesCache = null;
        let lastLoadTime = 0;
        const CACHE_DURATION = 30000; // 30 seconds

        async function loadPhases(forceReload = false) {
            const now = Date.now();

            // Use cache if available and not expired
            if (!forceReload && phasesCache && (now - lastLoadTime) < CACHE_DURATION) {
                renderPhases(phasesCache);
                return;
            }

            try {
                const response = await api.makeRequest('projects/list_phases', {
                    project_id: currentProjectId,
                    user_id: {{ auth()->id() ?? 1 }}
                });

                if (response.code === 200 && response.data) {
                    phasesCache = response.data;
                    lastLoadTime = now;
                    renderPhases(response.data);
                    
                    // Reload project data to update overall progress after phases are loaded
                    loadProjectData();
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
                // Use time-based progress
                const progress = phase.time_progress || 0;

                const totalDays = phase.milestones ? phase.milestones.reduce((sum, m) => sum + (m.days || 0), 0) :
                    0;
                const hasExtensions = phase.has_extensions || false;
                const extensionDays = phase.total_extension_days || 0;

                // Determine badge based on time progress and extensions
                let badgeClass, badgeText;
                if (progress >= 100) {
                    badgeClass = 'badge1';
                    badgeText = 'Completed';
                } else if (hasExtensions) {
                    badgeClass = 'badge3'; // Warning for extensions
                    badgeText = 'Extended';
                } else {
                    // Default to "In Progress" for all phases (including new ones with 0 progress)
                    badgeClass = 'badge4';
                    badgeText = 'In Progress';
                }

                // Progress bar color based on status
                let progressColor;
                if (progress >= 100) {
                    progressColor = 'bg-success';
                } else if (hasExtensions) {
                    progressColor = 'linear-gradient(90deg, #ff6b35 0%, #f7931e 100%)';
                } else {
                    progressColor = 'linear-gradient(90deg, #4477C4 0%, #F58D2E 100%)';
                }

                return `
                    <div class="col-12 col-md-4 wow fadeInUp" data-wow-delay="${index * 0.4}s">
                        <div class="card h-100 B_shadow" style="cursor: pointer;" onclick="openPhaseModal('${phase.title}', ${phase.id})">
                            <div class="card-body p-md-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="fw-semibold black_color mb-0">${phase.title}</h5>
                                    <span class="badge ${badgeClass}">${badgeText}</span>
                                </div>
                                <div class="mb-3">
                                    <div class="progress position-relative" style="height:12px;">
                                        <!-- Extended days progress bar commented out - only showing status-based progress -->
                                        <!-- ${hasExtensions ? `
                                                        Original timeline progress - COMMENTED OUT
                                                        <div class="progress-bar" role="progressbar" 
                                                            style="width: ${Math.min(progress, 100)}%; background: linear-gradient(90deg, #4477C4 0%, #F58D2E 100%);"
                                                            aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                                        Extension area (lighter color)
                                                        <div class="progress-bar" role="progressbar" 
                                                            style="width: ${Math.max(0, Math.min(100 - progress, extensionDays / (totalDays / 100)))}%; background: rgba(255, 193, 7, 0.3); border-left: 2px solid #ffc107;"
                                                            aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        Extension indicator
                                                        <div class="position-absolute top-0 h-100 d-flex align-items-center" style="left: ${Math.min(progress, 100)}%; transform: translateX(-50%);">
                                                            <div style="width: 2px; height: 100%; background: #ffc107;"></div>
                                                        </div>
                                                        <div class="position-absolute top-0 end-0 h-100 d-flex align-items-center" style="padding-right: 4px;">
                                                            <i class="fas fa-clock text-warning" title="Extended by ${extensionDays} days" style="font-size: 10px;"></i>
                                                        </div>
                                                    ` : `
                                                        Normal progress bar
                                                        <div class="progress-bar" role="progressbar" 
                                                            style="width: ${Math.min(progress, 100)}%; background: ${progressColor};"
                                                            aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                                    `} -->
                                        <!-- Status-based progress bar only -->
                                        <div class="progress-bar" role="progressbar" 
                                            style="width: ${Math.min(progress, 100)}%; background: ${progressColor};"
                                            aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">${Math.round(progress)}% Time Progress</small>
                                        <small class="text-muted">${totalDays}${extensionDays > 0 ? ` (+${extensionDays})` : ''} days</small>
                                    </div>
                                    <!-- Extended timeline info commented out -->
                                    <!-- ${hasExtensions ? `
                                                    <div class="mt-1">
                                                        <small class="text-warning">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Original: ${Math.round(progress)}% | Extended timeline: ${Math.round((progress * totalDays) / (totalDays + extensionDays))}%
                                                        </small>
                                                    </div>
                                                ` : ''} -->
                                </div>
                                <div class="small text-muted">
                                    ${phase.milestones ? phase.milestones.map(milestone => {
                                        const isOverdue = milestone.is_overdue;
                                        const isExtended = milestone.is_extended;
                                        const overdueClass = isOverdue ? 'text-danger' : '';
                                        const extendedIcon = isExtended ? '<i class="fas fa-clock text-warning ms-1" style="font-size: 10px;"></i>' : '';
                                        
                                        return `
                                                        <div class="d-flex justify-content-between align-items-center ${overdueClass} mb-1">
                                                            <span>• ${milestone.milestone_name}${milestone.days ? ` - ${milestone.days} days` : ''}${extendedIcon}</span>
                                                        </div>
                                                    `;
                                    }).join('') : '<div>No milestones defined</div>'}
                                </div>
                                ${hasExtensions ? `
                                                <div class="mt-2">
                                                    <small class="text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Extended by ${extensionDays} day${extensionDays !== 1 ? 's' : ''}
                                                    </small>
                                                </div>
                                            ` : ''}

                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        async function loadProjectData() {
            try {
                const response = await api.getProjectDetails({
                    project_id: currentProjectId,
                    user_id: {{ auth()->id() ?? 1 }}
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

                    // Update overall project progress (simple line like phases)
                    const overallProgress = project.progress_percentage !== undefined ? Math.round(project.progress_percentage) : 0;
                    const overallProgressElement = document.getElementById('overallProjectProgress');
                    const overallProgressBar = document.getElementById('overallProjectProgressBar');
                    
                    if (overallProgressElement) {
                        overallProgressElement.textContent = overallProgress + '%';
                    }
                    
                    if (overallProgressBar) {
                        overallProgressBar.style.width = overallProgress + '%';
                        overallProgressBar.setAttribute('aria-valuenow', overallProgress);
                    }

                    // Update location display and map
                    if (project.latitude && project.longitude) {
                        document.getElementById('displayLocation').textContent = project.project_location || 'N/A';
                        document.getElementById('displayCoordinates').textContent =
                            `{{ __('messages.lat') }}: ${parseFloat(project.latitude).toFixed(6)}, {{ __('messages.lng') }}: ${parseFloat(project.longitude).toFixed(6)}`;

                        // Initialize map after a delay to ensure Google Maps is loaded
                        setTimeout(() => {
                            if (typeof google !== 'undefined' && google.maps && !mapInitialized) {
                                initProjectLocationMap(project.latitude, project.longitude, project
                                    .project_location);
                            }
                        }, 500);
                    } else {
                        document.getElementById('displayLocation').textContent = project.project_location ||
                            '{{ __('messages.no_location_set') }}';
                        document.getElementById('displayCoordinates').textContent =
                            '{{ __('messages.coordinates_not_available') }}';
                        document.getElementById('projectLocationMap').innerHTML =
                            '<div class="alert alert-info m-3">{{ __('messages.no_coordinates_available') }}</div>';
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
            const elements = ['projectName', 'companyName', 'projectType', 'projectManager', 'siteEngineer', 'startDate',
                'endDate'
            ];
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
                // Create simple dropdown wrapper
                const dropdownWrapper = document.createElement('div');
                dropdownWrapper.className = 'dropdown d-inline-block';
                dropdownWrapper.style.minWidth = '200px';

                // Create dropdown button
                const dropdownButton = document.createElement('button');
                dropdownButton.className = 'btn btn-outline-secondary dropdown-toggle';
                dropdownButton.type = 'button';
                dropdownButton.setAttribute('data-bs-toggle', 'dropdown');
                dropdownButton.textContent = 'Loading users...';

                // Create dropdown menu
                const dropdownMenu = document.createElement('ul');
                dropdownMenu.className = 'dropdown-menu';
                dropdownMenu.style.maxHeight = '200px';
                dropdownMenu.style.overflowY = 'auto';
                dropdownMenu.innerHTML = '<li><span class="dropdown-item-text">Loading users...</span></li>';

                dropdownWrapper.appendChild(dropdownButton);
                dropdownWrapper.appendChild(dropdownMenu);

                inputElement = dropdownWrapper;

                // Load users for dropdown
                loadUsersForSimpleDropdown(dropdownButton, dropdownMenu, fieldLabel, currentValue);
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
            saveBtn.className = 'btn btn-success btn-sm ms-2 api-action-btn';

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

                if (inputElement.classList.contains('dropdown')) {
                    // For modern dropdown, get selected data from button
                    const button = inputElement.querySelector('button');
                    newId = button.dataset.selectedId;
                    newValue = button.dataset.selectedName;

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
                        [apiField]: inputElement.classList.contains('dropdown') ? newId : newValue
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

        async function loadUsersForSimpleDropdown(buttonElement, menuElement, fieldLabel, currentValue) {
            try {
                let response;
                if (fieldLabel === 'Project Manager') {
                    response = await api.getProjectManagers();
                } else if (fieldLabel === 'Site Engineer') {
                    response = await api.getTechnicalEngineers();
                }

                if (response.code === 200 && response.data) {
                    buttonElement.textContent = `Select ${fieldLabel}`;
                    menuElement.innerHTML = '';

                    response.data.forEach(user => {
                        const listItem = document.createElement('li');
                        const link = document.createElement('a');
                        link.className = 'dropdown-item';
                        link.href = '#';
                        link.textContent = user.name;
                        link.dataset.userId = user.id;
                        link.dataset.userName = user.name;

                        if (user.name === currentValue) {
                            link.classList.add('active');
                            buttonElement.textContent = user.name;
                        }

                        link.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();

                            // Remove active class from all items
                            menuElement.querySelectorAll('.dropdown-item').forEach(item => item
                                .classList.remove('active'));

                            // Add active class to clicked item
                            link.classList.add('active');

                            // Update button text and data
                            buttonElement.textContent = user.name;
                            buttonElement.dataset.selectedId = user.id;
                            buttonElement.dataset.selectedName = user.name;

                            // Close dropdown
                            const dropdown = bootstrap.Dropdown.getInstance(buttonElement);
                            if (dropdown) dropdown.hide();
                        });

                        listItem.appendChild(link);
                        menuElement.appendChild(listItem);
                    });
                } else {
                    buttonElement.textContent = 'No users found';
                    menuElement.innerHTML = '<li><span class="dropdown-item-text">No users found</span></li>';
                }
            } catch (error) {
                console.error('Failed to load users:', error);
                buttonElement.textContent = 'Error loading users';
                menuElement.innerHTML = '<li><span class="dropdown-item-text">Error loading users</span></li>';
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
                                    <div class="row g-2">
                                        <div class="col-8">
                                            <input type="text" class="form-control Input_control"
                                                name="milestones[0][milestone_name]"
                                                placeholder="{{ __('messages.milestone_name') }}" required>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control Input_control"
                                                name="milestones[0][days]" placeholder="{{ __('messages.days') }}"
                                                min="1" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMilestone()"
                                    data-no-protect>
                                    <i class="fas fa-plus me-1"></i>{{ __('messages.add_milestone') }}
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="removeLastMilestoneBtn"
                                    onclick="removeLastMilestone()" style="display: none;" data-no-protect>
                                    <i class="fas fa-trash me-1"></i>{{ __('messages.remove_milestone') }}
                                </button>
                            </div>
                            <small
                                class="text-muted d-block mt-2">{{ __('messages.dates_calculated_automatically') }}</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="padding: 0.7rem 1.5rem;">Cancel</button>
                    <button type="submit" form="createPhaseForm" class="btn orange_btn api-action-btn">
                        {{ __('messages.create_phase') }}
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
                <div class="row g-2">
                    <div class="col-8">
                        <input type="text" class="form-control Input_control" name="milestones[${milestoneIndex}][milestone_name]" 
                            placeholder="{{ __('messages.milestone_name') }}" required>
                    </div>
                    <div class="col-4">
                        <input type="number" class="form-control Input_control" name="milestones[${milestoneIndex}][days]" 
                            placeholder="{{ __('messages.days') }}" min="1" required>
                    </div>
                </div>
            `;
            container.appendChild(newMilestone);
            milestoneIndex++;

            // Show delete button if more than 1 milestone
            const removeBtn = document.getElementById('removeLastMilestoneBtn');
            if (container.children.length > 1 && removeBtn) {
                removeBtn.style.display = 'inline-block';
            }
        }

        function removeLastMilestone() {
            const container = document.getElementById('milestonesContainer');
            const milestones = container.querySelectorAll('.milestone-item');

            if (milestones.length > 1) {
                // Remove the last milestone (bottom one)
                milestones[milestones.length - 1].remove();

                // Hide delete button if only 1 milestone left
                const removeBtn = document.getElementById('removeLastMilestoneBtn');
                if (milestones.length === 2 && removeBtn) {
                    removeBtn.style.display = 'none';
                }
            }
        }

        // Create Phase Form Handler
        document.addEventListener('DOMContentLoaded', function() {
            const createPhaseForm = document.getElementById('createPhaseForm');
            if (createPhaseForm) {
                console.log('Phase form found, attaching submit handler');
                
                // Also attach click handler to the submit button as backup
                const submitBtn = document.querySelector('button[form="createPhaseForm"]');
                if (submitBtn) {
                    submitBtn.addEventListener('click', function(e) {
                        console.log('Submit button clicked');
                        // Don't prevent default - let form submit
                    });
                }
                
                createPhaseForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    console.log('Form submit event triggered');

                    // Get submit button (it's outside the form, using form attribute)
                    const btn = document.querySelector('button[form="createPhaseForm"]');
                    console.log('Submit button:', btn);
                    
                    // Check if button is already disabled (protection system handles this)
                    if (btn && btn.disabled) {
                        console.log('Button already disabled, returning');
                        return;
                    }
                    
                    // Manually protect button here since form submit might bypass button protection
                    if (btn && window.protectButton) {
                        window.protectButton(btn);
                    }

                    const formData = new FormData(createPhaseForm);
                    const title = formData.get('title');

                    // Validate title
                    if (!title || !title.trim()) {
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Please enter a phase title.');
                        } else {
                            alert('Please enter a phase title.');
                        }
                        return;
                    }

                    // Collect milestones - find inputs by their actual name attribute, not by index
                    const milestones = [];
                    const milestoneItems = document.querySelectorAll('.milestone-item');

                    milestoneItems.forEach((item) => {
                        // Find inputs by their name pattern, not by index
                        const nameInput = item.querySelector('input[name*="[milestone_name]"]');
                        const daysInput = item.querySelector('input[name*="[days]"]');

                        if (nameInput && nameInput.value.trim()) {
                            milestones.push({
                                milestone_name: nameInput.value.trim(),
                                days: daysInput && daysInput.value ? parseInt(daysInput.value) : null
                            });
                        }
                    });

                    try {
                        // Make API call and pass button element for automatic protection
                        const response = await api.makeRequest('projects/create_phase', {
                            project_id: currentProjectId,
                            user_id: {{ auth()->id() ?? 1 }},
                            title: title.trim(),
                            milestones: milestones.length > 0 ? milestones : null
                        }, 'POST', btn);

                        if (response.code === 200) {
                            // Close modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById('createPhaseModal'));
                            if (modal) modal.hide();

                            // Show success message
                            if (typeof toastr !== 'undefined') {
                                toastr.success('Phase created successfully!');
                            } else {
                                alert('Phase "' + title + '" created successfully!');
                            }

                            // Reset form
                            createPhaseForm.reset();
                            milestoneIndex = 1;
                            resetMilestonesContainer();

                            // Reload phases
                            loadPhases(true);
                        } else {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(response.message || 'Failed to create phase');
                            } else {
                                alert('Error creating phase: ' + (response.message || 'Unknown error'));
                            }
                        }
                    } catch (error) {
                        console.error('Error creating phase:', error);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('Error creating phase. Please try again.');
                        } else {
                            alert('Error creating phase. Please try again.');
                        }
                    }
                    // Note: Button will be automatically released by button protection system when API call completes
                });
            }
        });

        function resetMilestonesContainer() {
            const container = document.getElementById('milestonesContainer');
            container.innerHTML = `
                <div class="milestone-item mb-2">
                    <div class="row g-2">
                        <div class="col-8">
                            <input type="text" class="form-control Input_control" name="milestones[0][milestone_name]" 
                                placeholder="{{ __('messages.milestone_name') }}" required>
                        </div>
                        <div class="col-4">
                            <input type="number" class="form-control Input_control" name="milestones[0][days]" 
                                placeholder="{{ __('messages.days') }}" min="1" required>
                        </div>
                    </div>
                </div>
            `;
            milestoneIndex = 1;

            // Hide delete button on reset
            const removeBtn = document.getElementById('removeLastMilestoneBtn');
            if (removeBtn) {
                removeBtn.style.display = 'none';
            }
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





    @include('website.modals.add-safety-checklist-modal')

    <!-- Delete Project Confirmation Modal -->
    <div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel"
        aria-hidden="true">
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
                            {{ __('messages.cancel') }}
                        </button>
                        <button type="button" class="btn btn-danger px-4 api-action-btn" id="confirmDeleteBtn"
                            onclick="confirmDeleteProject()">
                            <i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                    submitBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin {{ margin_end(2) }}"></i>{{ __('messages.creating') }}...';
                    submitBtn.disabled = true;

                    // Simulate checklist creation
                    setTimeout(() => {
                        alert('{{ __('messages.safety_checklist_created_successfully') }}');
                        bootstrap.Modal.getInstance(document.getElementById(
                            'addSafetyChecklistModal')).hide();
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
