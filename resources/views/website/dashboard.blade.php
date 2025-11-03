<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Construction Project Dashboard">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.dashboard') }}</title>
    <!-- FAVICON -->
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ----ANIMATION CSS-- -->
    <link rel="stylesheet" href="{{ asset('website/css/animate.css') }}">
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />

    <!-- RESPONSIVE CSS -->
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />

    <!-- TOASTR CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/toastr-custom.css') }}">

    <!-- GOOGLE MAPS -->
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places,marker&language={{ app()->getLocale() }}&callback=Function.prototype">
    </script>

    <style>
        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: #F58D2E;
            color: white;
        }

        .step-line {
            width: 60px;
            height: 2px;
            background: #e9ecef;
        }

        .upload-zone {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .upload-zone:hover {
            border-color: #F58D2E;
            background: #fff5f0;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 141, 46, 0.15);
        }

        .selected-files {
            text-align: left;
        }

        .file-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 6px;
            margin-bottom: 8px;
        }

        .file-item i {
            margin-right: 8px;
            color: #6c757d;
        }

        .notification-dropdown {
            position: absolute;
            top: 100%;
            width: 380px;
            max-width: calc(100vw - 20px);
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            z-index: 1050;
            border: 1px solid #e9ecef;
        }

        /* LTR Layout */
        [dir="ltr"] .notification-dropdown {
            right: 0;
        }

        /* RTL Layout */
        [dir="rtl"] .notification-dropdown {
            left: 0;
        }

        @media (max-width: 768px) {
            [dir="ltr"] .notification-dropdown {
                right: -150px;
                width: 300px;
                max-width: calc(100vw - 40px);
            }

            [dir="rtl"] .notification-dropdown {
                left: -150px;
                width: 300px;
                max-width: calc(100vw - 40px);
            }
        }

        @media (max-width: 480px) {
            [dir="ltr"] .notification-dropdown {
                right: -120px;
                width: 280px;
            }

            [dir="rtl"] .notification-dropdown {
                left: -120px;
                width: 280px;
            }
        }

        .notification-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
            border-radius: 12px 12px 0 0;
        }

        .notification-body {
            max-height: 300px;
            overflow-y: auto;
            padding: 0;
        }

        .load-more-btn {
            padding: 8px 16px;
            text-align: center;
            color: #F58D2E;
            cursor: pointer;
            border-top: 1px solid #f0f0f0;
            font-size: 12px;
        }

        .load-more-btn:hover {
            background-color: #f8f9fa;
        }

        .notification-footer {
            padding: 12px 16px;
            border-top: 1px solid #f0f0f0;
            text-align: center;
            background: white;
            border-radius: 0 0 12px 12px;
        }

        .notification-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: background-color 0.2s;
            word-wrap: break-word;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }

        .notification-item.unread {
            background-color: #fff5f0;
            border-left: 3px solid #F58D2E;
        }

        /* Calendar icon positioning for RTL */
        [dir="rtl"] .vanilla-calendar-wrapper .fa-calendar-alt {
            left: 2.5rem !important;
            right: auto !important;
        }

        [dir="rtl"] .vanilla-calendar-wrapper input {
            padding-left: 3.5rem !important;
            padding-right: 0.75rem !important;
        }

        [dir="ltr"] .vanilla-calendar-wrapper input {
            padding-right: 2.5rem !important;
            padding-left: 0.75rem !important;
        }

        /* Move validation icon to avoid overlap with calendar icon */
        [dir="rtl"] .vanilla-calendar-wrapper .is-invalid {
            background-position: left 0.5rem center !important;
            padding-left: 3.5rem !important;
            padding-right: 0.75rem !important;
        }

        [dir="ltr"] .vanilla-calendar-wrapper .is-invalid {
            background-position: right 2.5rem center !important;
            padding-right: 2.25rem !important;
            padding-left: 0.75rem !important;
        }

        /* Google Maps Styles */
        #map {
            width: 100%;
            height: 450px;
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

        .pac-item {
            padding: 10px;
            cursor: pointer;
            border-top: 1px solid #e9ecef;
        }

        .pac-item:hover {
            background-color: #fff5f0;
        }

        .pac-icon {
            margin-top: 5px;
        }

        .map-search-wrapper {
            position: relative;
            margin-bottom: 15px;
        }

        .map-search-wrapper .form-control {
            padding-right: 40px;
        }

        [dir="rtl"] .map-search-wrapper .form-control {
            padding-right: 0.75rem;
            padding-left: 40px;
        }

        .map-search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            pointer-events: none;
        }

        [dir="rtl"] .map-search-icon {
            right: auto;
            left: 12px;
        }

        .location-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 13px;
        }

        .location-info .badge {
            font-size: 11px;
            padding: 4px 8px;
        }

        /* Search icon RTL support */
        .serchBar .fa-search {
            left: 12px;
        }

        [dir="rtl"] .serchBar .fa-search {
            left: auto;
            right: 12px;
        }

        .serchBar input {
            padding-left: 40px;
        }

        [dir="rtl"] .serchBar input {
            padding-left: 0.75rem;
            padding-right: 40px;
        }

        /* Custom Combo Dropdown */
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

        .dropdown-option:active {
            background-color: #e9ecef;
        }
    </style>





</head>

<body>
    <div class="content_wraper F_poppins">

        <!-- =============Header start========================= -->
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class=" col-12 d-flex align-items-center justify-content-between gap-2">
                        <a class="navbar-brand" href="#">
                            <img src="{{ asset('website/images/icons/logo.svg') }}" alt="logo"
                                class="img-fluid"><span
                                class="Head_title fw-bold ms-3 fs24 d-none d-lg-inline-block">{{ __('messages.project_dashboard') }}</span>
                        </a>
                        <div class=" d-flex align-items-center justify-content-end gap-md-4 gap-3 w-100 flex-wrap ">
                            <!-- Language Toggle -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-globe me-2"></i>
                                    <span id="currentLang">{{ is_rtl() ? 'العربية' : 'English' }}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇺🇸
                                            English</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">🇸🇦
                                            العربية</a></li>
                                </ul>
                            </div>

                            <form class="d-none d-md-block serchBar position-relative">
                                <i class="fas fa-search position-absolute top-50 translate-middle-y"
                                    style="color: #6c757d; pointer-events: none;"></i>
                                <input class="form-control" type="search"
                                    placeholder="{{ __('messages.search_projects') }}" aria-label="Search"
                                    data-bs-toggle="modal" data-bs-target="#searchModal" readonly>
                            </form>
                            <div class="notification-wrapper position-relative">
                                <div class="position-relative MessageBOx text-center" style="cursor: pointer;"
                                    onclick="toggleNotifications()">
                                    <img src="{{ asset('website/images/icons/bell.svg') }}" alt="Bell"
                                        class="img-fluid notifaction-icon">
                                    <span class="fw-normal fs12 text-white orangebg" id="notificationCount"
                                        style="display: none;">0</span>
                                </div>
                                <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
                                    <div class="notification-header">
                                        <span class="fw-bold">{{ __('messages.notifications') }}</span>
                                        <button class="btn btn-sm btn-danger" onclick="deleteAllNotifications()"
                                            style="font-size: 11px; padding: 4px 8px;">{{ __('messages.delete_all') }}</button>
                                    </div>
                                    <div class="notification-body" id="notificationList">
                                        <div class="text-center py-3">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                            <span class="ms-2">{{ __('messages.loading') }}...</span>
                                        </div>
                                    </div>
                                    {{-- <div class="notification-footer">
                                        <a href="#"
                                            class="text-primary">{{ __('messages.view_all_notifications') }}</a>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center gap-2 gap-md-3" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    <img id="headerProfileImage" src="{{ asset('website/images/icons/avatar.jpg') }}" alt="user img"
                                        class="User_iMg">
                                    <span class=" text-end">
                                        <h6 class="fs14 fw-medium black_color">
                                            @if (isset($currentUser))
                                                {{ $currentUser->name }}
                                            @else
                                                {{ __('messages.john_smith') }}
                                            @endif
                                        </h6>
                                        <p class="Gray_color fs12 fw-normal">
                                            @if (isset($currentUser))
                                                {{ $currentUser->getRoleDisplayName() }}
                                            @else
                                                {{ __('messages.consultant') }}
                                            @endif
                                        </p>
                                    </span>
                                    <img src="{{ asset('website/images/icons/arrow-up.svg') }}" alt="user img"
                                        class="arrow">
                                </a>
                                <ul class="dropdown-menu {{ is_rtl() ? 'dropdown-menu-start' : 'dropdown-menu-end' }}"
                                    aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item d-flex align-items-center gap-2"
                                            href="{{ route('website.profile') }}">
                                            <i class="fas fa-user"></i>
                                            {{ __('auth.my_profile') }}
                                        </a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger"
                                            href="#" onclick="logout()">
                                            <i class="fas fa-sign-out-alt"></i>
                                            {{ __('auth.logout') }}
                                        </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- =============Header End========================= -->

        <!-- ======================main content start=============================== -->
        <section class="Dashboard_sec">
            <div class="container-fluid">
                <!-- Top Stats Cards -->
                <div class="row g-3 mb-4 ">
                    <div class="col-12 col-sm-6 col-md-4 wow fadeInUp" data-wow-delay="0s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.total_projects') }}</div>
                                    <div class="stat-value">{{ $stats['total_projects'] ?? 0 }}</div>
                                </div>
                                <span class="ms-auto stat-icon bg1"><img
                                        src="{{ asset('website/images/icons/share.svg') }}" alt="share"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 wow fadeInUp" data-wow-delay=".4s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.total_tasks') }}</div>
                                    <div class="stat-value">{{ $stats['total_tasks'] ?? 0 }}</div>
                                </div>
                                <span class="ms-auto stat-icon bg2"><img
                                        src="{{ asset('website/images/icons/clock.svg') }}" alt="clock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 wow fadeInUp" data-wow-delay=".8s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.pending_tasks') }}</div>
                                    <div class="stat-value">{{ $stats['total_pending_tasks'] ?? 0 }}</div>
                                </div>
                                <span class="ms-auto stat-icon"><img
                                        src="{{ asset('website/images/icons/calendar.svg') }}" alt="calendar"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Projects Title & Filter -->
                <div
                    class="d-flex justify-content-between align-items-center mb-3 mb-md-4 mt-3 mt-md-4 wow fadeInUp flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">
                        @if (isset($currentUser) && $currentUser->getDashboardAccess() === 'assigned_only')
                            {{ __('messages.assigned_projects') }}
                        @else
                            {{ __('messages.ongoing_projects') }}
                        @endif
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        @can('projects', 'create')
                            <button class="btn orange_btn py-2" data-bs-toggle="modal"
                                data-bs-target="#createProjectModal">
                                <i class="fas fa-plus"></i>
                                {{ __('messages.new_project') }}
                            </button>
                        @endcan
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center gap-2"
                                type="button" id="statusFilterDropdown" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-filter"></i>
                                <span id="statusFilterText">{{ __('messages.all_status') }}</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="statusFilterDropdown">
                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                        onclick="setStatusFilter('all', '{{ __('messages.all_status') }}')"><i
                                            class="fas fa-list text-secondary"></i>{{ __('messages.all_status') }}</a>
                                </li>
                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                        onclick="setStatusFilter('active', '{{ __('messages.active') }}')"><i
                                            class="fas fa-play-circle text-success"></i>{{ __('messages.active') }}</a>
                                </li>
                                <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                                        onclick="setStatusFilter('completed', '{{ __('messages.completed') }}')"><i
                                            class="fas fa-check-circle text-primary"></i>{{ __('messages.completed') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Projects Cards -->
                <div class="row g-4" id="projectsContainer">
                    <div class="col-12 text-center py-4">
                        <div class="spinner-border" role="status"></div>
                        <div class="mt-2">{{ __('messages.loading_projects') }}...</div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('website.modals.search-modal')
    @include('website.modals.drawing-modal')



    <!-- Create Project Modal -->
    @can('projects', 'create')
        <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header {{ is_rtl() ? 'justify-content-end' : '' }}">
                        <h5 class="modal-title {{ is_rtl() ? 'order-2' : '' }}" id="createProjectModalLabel">
                            <i class="fas fa-plus {{ margin_end(2) }}"></i>{{ __('messages.create_new_project') }}
                        </h5>
                        <button type="button" class="btn-close {{ is_rtl() ? 'order-1 me-auto' : '' }}"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Step Progress -->
                        <div class="d-flex justify-content-center mb-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="step-indicator active" id="step1-indicator">1</div>
                                <div class="step-line"></div>
                                <div class="step-indicator" id="step2-indicator">2</div>
                                <div class="step-line"></div>
                                <div class="step-indicator" id="step3-indicator">3</div>
                            </div>
                        </div>

                        <form id="createProjectForm">
                            @csrf

                            <!-- Step 1: Basic Info -->
                            <div class="step-content" id="step1">
                                <h6 class="mb-3">{{ __('messages.basic_information') }}</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="project_title"
                                                class="form-label fw-medium">{{ __('messages.project_name') }} *</label>
                                            <input type="text" class="form-control Input_control" id="project_title"
                                                name="project_title" required maxlength="100"
                                                placeholder="{{ __('messages.project_name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none;">
                                        <div class="mb-3">
                                            <label for="contractor_name"
                                                class="form-label fw-medium">{{ __('messages.contractor_name') }}
                                                *</label>
                                            <input type="text" class="form-control Input_control" id="contractor_name"
                                                name="contractor_name" maxlength="100"
                                                placeholder="{{ __('messages.contractor_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" style="display: none;">
                                        <div class="mb-3">
                                            <label for="project_manager_id"
                                                class="form-label fw-medium">{{ __('messages.assign_project_manager') }}</label>
                                            <select class="form-select Input_control" id="project_manager_id"
                                                name="project_manager_id">
                                                <option value="">{{ __('messages.select_manager') }}</option>
                                                <option value="1">John Smith</option>
                                                <option value="2">Sarah Johnson</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="display: none;">
                                        <div class="mb-3">
                                            <label for="technical_engineer_id"
                                                class="form-label fw-medium">{{ __('messages.assign_technical_engineer') }}</label>
                                            <select class="form-select Input_control" id="technical_engineer_id"
                                                name="technical_engineer_id">
                                                <option value="">{{ __('messages.select_engineer') }}</option>
                                                <option value="1">Mike Wilson</option>
                                                <option value="2">Lisa Brown</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Project Details -->
                            <div class="step-content d-none" id="step2">
                                <h6 class="mb-3">{{ __('messages.project_details') }}</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="type"
                                                class="form-label fw-medium">{{ __('messages.project_type_example') }}</label>
                                            <div class="custom-combo-dropdown position-relative">
                                                <input type="text" class="form-control Input_control" id="type"
                                                    name="type" placeholder="{{ __('messages.select_type') }}"
                                                    required autocomplete="off" maxlength="50">
                                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                                                <div class="dropdown-options" id="typeDropdown">
                                                    <div class="dropdown-option" data-value="{{ __('messages.villa') }}">
                                                        {{ __('messages.villa') }}</div>
                                                    <div class="dropdown-option" data-value="{{ __('messages.tower') }}">
                                                        {{ __('messages.tower') }}</div>
                                                    <div class="dropdown-option"
                                                        data-value="{{ __('messages.hospital') }}">
                                                        {{ __('messages.hospital') }}</div>
                                                    <div class="dropdown-option"
                                                        data-value="{{ __('messages.commercial') }}">
                                                        {{ __('messages.commercial') }}</div>
                                                    <div class="dropdown-option"
                                                        data-value="{{ __('messages.residential') }}">
                                                        {{ __('messages.residential') }}</div>
                                                    <div class="dropdown-option"
                                                        data-value="{{ __('messages.industrial') }}">
                                                        {{ __('messages.industrial') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="project_start_date"
                                                class="form-label fw-medium">{{ __('messages.project_start_date') }}
                                                *</label>
                                            @include('website.includes.date-picker', [
                                                'id' => 'project_start_date',
                                                'name' => 'project_start_date',
                                                'placeholder' => __('messages.select_start_date'),
                                                'minDate' => date('Y-m-d'),
                                                'required' => true,
                                            ])
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="project_due_date"
                                                class="form-label fw-medium">{{ __('messages.project_due_date') }}
                                                *</label>
                                            @include('website.includes.date-picker', [
                                                'id' => 'project_due_date',
                                                'name' => 'project_due_date',
                                                'placeholder' => __('messages.select_due_date'),
                                                'minDate' => date('Y-m-d'),
                                                'required' => true,
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="project_location"
                                                class="form-label fw-medium">{{ __('messages.project_location') }}
                                                *</label>
                                            <div class="map-search-wrapper">
                                                <input type="text" class="form-control Input_control"
                                                    id="project_location" name="project_location" required
                                                    placeholder="{{ __('messages.search_location') }}" maxlength="200">
                                                <i class="fas fa-search map-search-icon"></i>
                                            </div>
                                            <input type="hidden" id="latitude" name="latitude">
                                            <input type="hidden" id="longitude" name="longitude">
                                            <div id="map" class="mt-2"></div>
                                            <div class="location-info" id="locationInfo" style="display: none;">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <span class="badge bg-success {{ margin_end(2) }}"><i
                                                                class="fas fa-map-marker-alt {{ margin_end(1) }}"></i>{{ __('messages.selected') }}</span>
                                                        <span id="selectedLocation" class="fw-medium"></span>
                                                    </div>
                                                </div>
                                                <div class="mt-2 text-muted" style="font-size: 12px;">
                                                    <i class="fas fa-info-circle {{ margin_end(1) }}"></i>
                                                    <span id="coordinatesDisplay"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: File Uploads -->
                            <div class="step-content d-none" id="step3">
                                <h6 class="mb-3">{{ __('messages.upload_files') }}</h6>
                                <div class="mb-4">
                                    <label
                                        class="form-label fw-medium mb-3">{{ __('messages.upload_construction_plans') }}</label>
                                    <div class="upload-zone p-4 text-center position-relative"
                                        onclick="document.getElementById('construction_plans').click()">
                                        <div class="upload-icon mb-3">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="mb-2">{{ __('messages.upload_your_files') }} 📎</h6>
                                        <p class="text-muted mb-3">{{ __('messages.drag_drop_or_click') }}</p>
                                        <small
                                            class="text-muted d-block">{{ __('messages.supported_formats_construction') }}</small>
                                        <input type="file" class="d-none" id="construction_plans"
                                            name="construction_plans[]" multiple accept=".pdf,.docx,.jpg,.jpeg,.png"
                                            onchange="showSelectedFiles(this, 'construction-files')">
                                        <div id="construction-files" class="selected-files mt-3"></div>
                                    </div>
                                    <div id="construction-notes-container" class="mt-3" style="display: none;">
                                        <label
                                            class="form-label fw-medium">{{ __('messages.add_notes_for_files') }}</label>
                                        <div id="construction-notes-list"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label
                                        class="form-label fw-medium mb-3">{{ __('messages.upload_gantt_chart') }}</label>
                                    <div class="upload-zone p-4 text-center position-relative"
                                        onclick="document.getElementById('gantt_chart').click()">
                                        <div class="upload-icon mb-3">
                                            <i class="fas fa-cloud-upload-alt fa-3x text-success"></i>
                                        </div>
                                        <h6 class="mb-2">{{ __('messages.upload_your_files') }} 📎</h6>
                                        <p class="text-muted mb-3">{{ __('messages.drag_drop_or_click') }}</p>
                                        <small
                                            class="text-muted d-block">{{ __('messages.supported_formats_gantt') }}</small>
                                        <input type="file" class="d-none" id="gantt_chart" name="gantt_chart[]"
                                            multiple accept=".pdf,.docx,.jpg,.jpeg,.png"
                                            onchange="showSelectedFiles(this, 'gantt-files')">
                                        <div id="gantt-files" class="selected-files mt-3"></div>
                                    </div>
                                    <div id="gantt-notes-container" class="mt-3" style="display: none;">
                                        <label
                                            class="form-label fw-medium">{{ __('messages.add_notes_for_files') }}</label>
                                        <div id="gantt-notes-list"></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                        <button type="button" class="btn orange_btn d-none" id="prevBtn" onclick="changeStep(-1)">
                            <i
                                class="fas {{ is_rtl() ? 'fa-arrow-right' : 'fa-arrow-left' }} {{ is_rtl() ? 'ms-2' : 'me-2' }}"></i>{{ __('messages.previous') }}
                        </button>
                        <button type="button" class="btn orange_btn" id="nextBtn" onclick="changeStep(1)">
                            {{ __('messages.next') }}<i
                                class="fas {{ is_rtl() ? 'fa-arrow-right' : 'fa-arrow-right' }} {{ is_rtl() ? 'me-2' : 'ms-2' }}"></i>
                        </button>
                        <button type="submit" form="createProjectForm" class="btn orange_btn d-none" id="submitBtn">
                            <i class="fas fa-plus me-2"></i>{{ __('messages.create') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

    <script>
        // Stats loaded from controller

        // Load user profile image
        async function loadUserProfileImage() {
            try {
                const response = await api.getProfile({});
                if (response.code === 200 && response.data && response.data.profile_image) {
                    const headerImg = document.getElementById('headerProfileImage');
                    if (headerImg) {
                        headerImg.src = response.data.profile_image;
                        headerImg.onerror = function() {
                            this.src = '{{ asset('website/images/icons/avatar.jpg') }}';
                        };
                    }
                }
            } catch (error) {
                console.error('Failed to load profile image:', error);
            }
        }

        // Notification functions
        async function loadNotifications() {
            try {
                const response = await api.getNotifications({
                    page: 1,
                    limit: 50
                });

                if (response.code === 200 && response.data) {
                    let notifications = Array.isArray(response.data) ? response.data : (response.data.notifications ||
                        response.data.data || []);

                    if (Array.isArray(notifications)) {
                        // Filter out deleted notifications if deleted field exists
                        notifications = notifications.filter(n => !n.deleted && !n.is_deleted);

                        displayNotifications(notifications);
                        const unreadCount = notifications.filter(n => !n.is_read).length;
                        console.log('Total notifications:', notifications.length);
                        console.log('Unread notifications:', unreadCount);
                        updateNotificationCount(unreadCount);
                    } else {
                        displayNoNotifications();
                    }
                } else {
                    displayNoNotifications();
                }
            } catch (error) {
                console.error('Failed to load notifications:', error);
                displayNoNotifications();
            }
        }

        function displayNotifications(notifications) {
            const notificationList = document.getElementById('notificationList');
            if (!notifications || notifications.length === 0) {
                displayNoNotifications();
                return;
            }

            notificationList.innerHTML = '';
            notifications.forEach(notification => {
                const div = document.createElement('div');
                div.className = `notification-item ${!notification.is_read ? 'unread' : ''}`;
                div.onclick = () => markAsRead(notification.id);
                div.innerHTML = `
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <div class="fw-medium mb-1">${notification.title || 'Notification'}</div>
                            <div class="small text-muted mb-1">${notification.message || notification.content || ''}</div>
                            <div style="font-size: 11px; color: #6c757d;">${formatNotificationTime(notification.created_at)}</div>
                        </div>
                        ${!notification.is_read ? '<div class="ms-2"><div class="rounded-circle" style="width: 8px; height: 8px; background: #F58D2E;"></div></div>' : ''}
                    </div>
                `;
                notificationList.appendChild(div);
            });
        }

        function displayNoNotifications() {
            const notificationList = document.getElementById('notificationList');
            notificationList.innerHTML =
                '<div class="text-center py-4 text-muted"><i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>{{ __('messages.no_notifications') }}</div>';
            updateNotificationCount(0);
        }

        window.toggleNotifications = function() {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown.style.display === 'none') {
                dropdown.style.display = 'block';
                loadNotifications();
                document.addEventListener('click', closeNotificationsOutside);
            } else {
                dropdown.style.display = 'none';
                document.removeEventListener('click', closeNotificationsOutside);
            }
        };

        function closeNotificationsOutside(event) {
            const wrapper = document.querySelector('.notification-wrapper');
            if (!wrapper.contains(event.target)) {
                document.getElementById('notificationDropdown').style.display = 'none';
                document.removeEventListener('click', closeNotificationsOutside);
            }
        }

        function updateNotificationCount(count) {
            const countBadge = document.getElementById('notificationCount');
            if (countBadge) {
                if (count > 0) {
                    countBadge.textContent = count > 99 ? '99+' : count;
                    countBadge.style.display = 'block';
                } else {
                    countBadge.style.display = 'none';
                }
            }
        }

        async function markAsRead(notificationId) {
            try {
                await api.markNotificationAsRead({
                    notification_id: notificationId
                });
                loadNotifications();
            } catch (error) {
                console.error('Failed to mark notification as read:', error);
            }
        }

        window.deleteAllNotifications = async function() {
            try {
                await api.deleteAllNotifications();
                loadNotifications();
                showToast('{{ __('messages.all_notifications_deleted') }}', 'success');
            } catch (error) {
                console.error('Failed to delete all notifications:', error);
                showToast('{{ __('messages.failed_to_delete_notifications') }}', 'error');
            }
        };

        function formatNotificationTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffInMinutes = Math.floor((now - date) / (1000 * 60));
            if (diffInMinutes < 1) return '{{ __('messages.just_now') }}';
            if (diffInMinutes < 60) return `${diffInMinutes}{{ __('messages.minutes_ago') }}`;
            if (diffInMinutes < 1440) return `${Math.floor(diffInMinutes / 60)}{{ __('messages.hours_ago') }}`;
            return date.toLocaleDateString();
        }

        // Pagination variables
        let currentPage = 1;
        let isLoading = false;
        let hasMoreProjects = true;
        let currentFilter = 'all';
        let allProjects = [];

        // Load projects from API with pagination
        async function loadProjects(filter = 'all', reset = true) {
            if (isLoading) return;

            try {
                isLoading = true;

                if (reset) {
                    currentPage = 1;
                    allProjects = [];
                    hasMoreProjects = true;
                    currentFilter = filter;
                    document.getElementById('projectsContainer').innerHTML =
                        '<div class="col-12 text-center py-4"><div class="spinner-border" role="status"></div><div class="mt-2">Loading projects...</div></div>';
                }

                const type = filter === 'all' ? '' : (filter === 'active' ? 'ongoing' : 'completed');
                const response = await api.getProjects({
                    type: type,
                    page: currentPage,
                    limit: 10
                });

                if (response.code === 200 && response.data) {
                    const newProjects = Array.isArray(response.data.data) ? response.data.data : (Array.isArray(response
                        .data) ? response.data : []);

                    if (newProjects.length === 0) {
                        hasMoreProjects = false;
                        if (reset && allProjects.length === 0) {
                            displayNoProjects();
                        }
                        return;
                    }

                    allProjects = reset ? newProjects : [...allProjects, ...newProjects];
                    displayProjects(allProjects, reset);

                    if (newProjects.length < 10) {
                        hasMoreProjects = false;
                    }

                    currentPage++;
                } else if (response.code === 403) {
                    // Permission denied
                    if (reset) {
                        displayPermissionDenied();
                        showToast(
                            '{{ __('messages.permission_denied_for_module', ['module' => __('messages.projects')]) }}',
                            'error');
                    }
                    hasMoreProjects = false;
                } else {
                    if (reset) {
                        displayNoProjects();
                    }
                    hasMoreProjects = false;
                }
            } catch (error) {
                console.error('Failed to load projects:', error);
                if (reset) {
                    displayNoProjects();
                }
                hasMoreProjects = false;
            } finally {
                isLoading = false;
            }
        }

        function displayProjects(projects, reset = true) {
            const container = document.getElementById('projectsContainer');

            if (reset) {
                container.innerHTML = '';
            }

            if (!projects || projects.length === 0) {
                if (reset) {
                    displayNoProjects();
                }
                return;
            }

            const newProjects = reset ? projects : projects.slice(-10);

            newProjects.forEach((project, index) => {
                if (!reset && container.querySelector(`[data-project-id="${project.id}"]`)) {
                    return;
                }

                const statusClass = getStatusClass(project.status);
                const statusText = getStatusText(project.status);
                const progressPercent = getRandomProgress(project.status);

                const projectCard = document.createElement('div');
                projectCard.className = 'col-12 col-md-6 col-lg-4 wow fadeInUp';
                projectCard.setAttribute('data-project-id', project.id);
                projectCard.setAttribute('data-wow-delay', `${index * 0.1}s`);

                projectCard.innerHTML = `
                    <div class="card project-card h-100" style="position: relative;">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <a href="/website/project/${project.id}/plans" class="text-decoration-none" style="flex: 1; min-width: 0; padding-right: 12px;">
                                    <h6 class="mb-0 fw-semibold" style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; word-break: break-word;" title="${project.project_title}">${project.project_title}</h6>
                                </a>
                                <div class="dropdown" style="flex-shrink: 0;">
                                    <i class="fas fa-ellipsis-v" style="color: #4A90E2; cursor: pointer;" data-bs-toggle="dropdown" aria-expanded="false"></i>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); deleteProjectFromDashboard(${project.id});"><i class="fas fa-trash me-2"></i>{{ __('messages.delete') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <a href="/website/project/${project.id}/plans" class="text-decoration-none">
                                <hr style="border-color: #e0e0e0; margin: 12px 0;">
                                <div class="mb-2">
                                    <i class="fas fa-building" style="color: #4A90E2; font-size: 16px; width: 20px;"></i>
                                    <span class="text-muted ms-2" style="font-size: 14px;">${project.type || 'N/A'}</span>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-map-marker-alt" style="color: #4A90E2; font-size: 16px; width: 20px;"></i>
                                    <span class="text-muted ms-2" style="font-size: 14px;">${project.project_location || 'N/A'}</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div>
                                        <i class="far fa-calendar-alt" style="color: #4A90E2; font-size: 16px; width: 20px;"></i>
                                        <span class="text-muted ms-2" style="font-size: 13px;">Due date: ${formatDate(project.project_due_date)}</span>
                                    </div>
                                    <div>
                                        <i class="far fa-id-badge" style="color: #4A90E2; font-size: 16px; width: 20px;"></i>
                                        <span class="text-muted ms-2" style="font-size: 13px;">${project.project_code || 'N/A'}</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                `;

                container.appendChild(projectCard);
                
                // Initialize Bootstrap dropdown
                const dropdownIcon = projectCard.querySelector('[data-bs-toggle="dropdown"]');
                if (dropdownIcon && typeof bootstrap !== 'undefined') {
                    new bootstrap.Dropdown(dropdownIcon);
                }
            });
        }

        function displayNoProjects() {
            const container = document.getElementById('projectsContainer');
            container.innerHTML =
                '<div class="col-12 text-center py-5"><i class="fas fa-folder-open fa-3x text-muted mb-3"></i><h5 class="text-muted">{{ __('messages.no_projects_found') }}</h5></div>';
        }

        function displayPermissionDenied() {
            const container = document.getElementById('projectsContainer');
            container.innerHTML =
                '<div class="col-12 text-center py-5"><i class="fas fa-lock fa-3x text-warning mb-3"></i><h5 class="text-muted">{{ __('messages.permission_denied_for_module', ['module' => __('messages.projects')]) }}</h5><p class="text-muted">{{ __('messages.contact_admin_for_access') }}</p></div>';
        }

        function getStatusClass(status) {
            switch (status) {
                case 'completed':
                    return 'bg-green1';
                case 'active':
                case 'in_progress':
                case 'planning':
                    return 'bg-orange text-white';
                default:
                    return 'bg-secondary text-white';
            }
        }

        function getStatusText(status) {
            switch (status) {
                case 'completed':
                    return '{{ __('messages.completed') }}';
                case 'active':
                case 'in_progress':
                case 'planning':
                    return '{{ __('messages.active') }}';
                default:
                    return status;
            }
        }

        function getRandomProgress(status) {
            if (status === 'completed') return 100;
            return Math.floor(Math.random() * 80) + 20;
        }

        function getProgressBarClass(percent) {
            if (percent === 100) return 'bg-green';
            if (percent >= 70) return '';
            if (percent >= 40) return 'bg-yellow';
            return 'bg-blue';
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

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Custom Combo Dropdown Handler
            const typeInput = document.getElementById('type');
            const typeDropdown = document.getElementById('typeDropdown');

            if (typeInput && typeDropdown) {
                // Show dropdown on input click
                typeInput.addEventListener('click', function() {
                    typeDropdown.classList.toggle('show');
                });

                // Filter options on input
                typeInput.addEventListener('input', function() {
                    const filter = this.value.toLowerCase();
                    const options = typeDropdown.querySelectorAll('.dropdown-option');

                    options.forEach(option => {
                        const text = option.textContent.toLowerCase();
                        if (text.includes(filter)) {
                            option.style.display = 'block';
                        } else {
                            option.style.display = 'none';
                        }
                    });

                    typeDropdown.classList.add('show');
                });

                // Select option on click
                typeDropdown.querySelectorAll('.dropdown-option').forEach(option => {
                    option.addEventListener('click', function() {
                        typeInput.value = this.getAttribute('data-value');
                        typeDropdown.classList.remove('show');
                    });
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!typeInput.contains(e.target) && !typeDropdown.contains(e.target)) {
                        typeDropdown.classList.remove('show');
                    }
                });
            }

            // Load notifications, profile image and projects on page load
            loadUserProfileImage();
            loadNotifications();
            loadProjects('all', true);

            // Setup date picker listeners for vanilla calendar
            setTimeout(() => {
                const startDateInput = document.getElementById('project_start_date');
                if (startDateInput) {
                    startDateInput.addEventListener('change', function() {
                        if (this.value) {
                            // Parse date string directly to avoid timezone issues
                            const dateParts = this.value.split('-');
                            const year = parseInt(dateParts[0]);
                            const month = parseInt(dateParts[1]) - 1; // Month is 0-indexed
                            const day = parseInt(dateParts[2]);

                            const startDate = new Date(year, month, day);
                            const nextDay = new Date(year, month, day + 1);

                            // Update due date minimum and starting month
                            const dueDateInput = document.getElementById('project_due_date');
                            if (dueDateInput) {
                                dueDateInput.value = '';
                                window.dueDateMinDate = nextDay;
                                window.dueDateStartMonth =
                                    nextDay; // Set calendar to open at this month
                            }
                        }
                    });
                }
            }, 500);

            // Status filter functionality is now handled by setStatusFilter function

            // Add setStatusFilter function to global scope
            window.setStatusFilter = function(value, text) {
                document.getElementById('statusFilterText').textContent = text;
                loadProjects(value, true);
            };

            // Infinite scroll implementation
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if (hasMoreProjects && !isLoading) {
                        const scrollPosition = window.innerHeight + window.scrollY;
                        const documentHeight = document.documentElement.offsetHeight;

                        if (scrollPosition >= documentHeight - 200) {
                            loadProjects(currentFilter, false);
                        }
                    }
                }, 100);
            });



            // Multi-step form variables
            let currentStep = 1;
            const totalSteps = 3;

            // Initialize Google Maps
            let map, marker, autocomplete;
            const saudiArabia = {
                lat: 23.8859,
                lng: 45.0792
            };

            function initMap() {
                try {
                    map = new google.maps.Map(document.getElementById('map'), {
                        center: saudiArabia,
                        zoom: 6,
                        mapTypeControl: true,
                        streetViewControl: false,
                        fullscreenControl: true
                    });

                    const input = document.getElementById('project_location');
                    autocomplete = new google.maps.places.Autocomplete(input, {
                        componentRestrictions: {
                            country: 'sa'
                        },
                        fields: ['formatted_address', 'geometry', 'name']
                    });

                    autocomplete.addListener('place_changed', function() {
                        const place = autocomplete.getPlace();
                        if (!place.geometry) {
                            showToast('{{ __('messages.no_location_found') }}', 'error');
                            return;
                        }
                        updateMapLocation(place.geometry.location, place.formatted_address || place.name);
                    });

                    map.addListener('click', function(event) {
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({
                            location: event.latLng
                        }, function(results, status) {
                            if (status === 'OK' && results[0]) {
                                updateMapLocation(event.latLng, results[0].formatted_address);
                                document.getElementById('project_location').value = results[0]
                                    .formatted_address;
                            }
                        });
                    });
                } catch (error) {
                    console.error('Map initialization error:', error);
                    document.getElementById('map').innerHTML =
                        '<div class="alert alert-warning m-3"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.map_loading_error') }}</div>';
                }
            }

            function updateMapLocation(location, address) {
                if (marker) marker.setMap(null);

                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    animation: google.maps.Animation.DROP,
                    draggable: true
                });

                map.setCenter(location);
                map.setZoom(15);

                document.getElementById('latitude').value = location.lat();
                document.getElementById('longitude').value = location.lng();
                document.getElementById('selectedLocation').textContent = address;
                document.getElementById('coordinatesDisplay').textContent =
                    `{{ __('messages.lat') }}: ${location.lat().toFixed(6)}, {{ __('messages.lng') }}: ${location.lng().toFixed(6)}`;
                document.getElementById('locationInfo').style.display = 'block';

                marker.addListener('dragend', function(event) {
                    const geocoder = new google.maps.Geocoder();
                    geocoder.geocode({
                        location: event.latLng
                    }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            document.getElementById('project_location').value = results[0]
                                .formatted_address;
                            document.getElementById('latitude').value = event.latLng.lat();
                            document.getElementById('longitude').value = event.latLng.lng();
                            document.getElementById('selectedLocation').textContent = results[0]
                                .formatted_address;
                            document.getElementById('coordinatesDisplay').textContent =
                                `{{ __('messages.lat') }}: ${event.latLng.lat().toFixed(6)}, {{ __('messages.lng') }}: ${event.latLng.lng().toFixed(6)}`;
                        }
                    });
                });
            }

            const createProjectModal = document.getElementById('createProjectModal');
            if (createProjectModal) {
                createProjectModal.addEventListener('shown.bs.modal', function() {
                    setTimeout(() => {
                        if (typeof google !== 'undefined' && !map) {
                            initMap();
                        }
                    }, 300);
                });
            }

            // Step navigation function
            window.changeStep = function(direction) {
                const currentStepEl = document.getElementById(`step${currentStep}`);
                const currentIndicator = document.getElementById(`step${currentStep}-indicator`);

                // Validate current step before proceeding
                if (direction === 1 && !validateStep(currentStep)) {
                    return;
                }

                // Hide current step
                currentStepEl.classList.add('d-none');
                currentIndicator.classList.remove('active');

                // Update step
                currentStep += direction;

                // Show new step
                const newStepEl = document.getElementById(`step${currentStep}`);
                const newIndicator = document.getElementById(`step${currentStep}-indicator`);
                newStepEl.classList.remove('d-none');
                newIndicator.classList.add('active');

                // Update buttons
                updateButtons();
            };

            function validateStep(step) {
                const requiredFields = document.querySelectorAll(
                    `#step${step} input[required], #step${step} select[required]`);
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    showToast('Please fill in all required fields.', 'error');
                }

                return isValid;
            }

            function updateButtons() {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                const submitBtn = document.getElementById('submitBtn');

                // Show/hide previous button
                if (currentStep === 1) {
                    prevBtn.classList.add('d-none');
                } else {
                    prevBtn.classList.remove('d-none');
                }

                // Show/hide next/submit button
                if (currentStep === totalSteps) {
                    nextBtn.classList.add('d-none');
                    submitBtn.classList.remove('d-none');
                } else {
                    nextBtn.classList.remove('d-none');
                    submitBtn.classList.add('d-none');
                }
            }

            // Reset form when modal opens and load dropdown data
            function resetProjectModal() {
                currentStep = 1;
                // Hide all steps except first
                for (let i = 2; i <= totalSteps; i++) {
                    document.getElementById(`step${i}`).classList.add('d-none');
                    document.getElementById(`step${i}-indicator`).classList.remove('active');
                }
                // Show first step
                document.getElementById('step1').classList.remove('d-none');
                document.getElementById('step1-indicator').classList.add('active');
                updateButtons();

                // Reset form
                document.getElementById('createProjectForm').reset();
                
                // Clear validation errors
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

                // Reset map and location
                document.getElementById('project_location').value = '';
                document.getElementById('latitude').value = '';
                document.getElementById('longitude').value = '';
                document.getElementById('locationInfo').style.display = 'none';
                if (marker) {
                    marker.setMap(null);
                    marker = null;
                }
                if (map) {
                    map.setCenter(saudiArabia);
                    map.setZoom(6);
                }

                // Clear file displays
                document.getElementById('construction-files').innerHTML = '';
                document.getElementById('gantt-files').innerHTML = '';
                document.getElementById('construction-notes-container').style.display = 'none';
                document.getElementById('gantt-notes-container').style.display = 'none';
                document.getElementById('construction-notes-list').innerHTML = '';
                document.getElementById('gantt-notes-list').innerHTML = '';

                // Load dropdown data
                loadProjectManagers();
                loadTechnicalEngineers();
            }

            createProjectModal.addEventListener('show.bs.modal', resetProjectModal);
            createProjectModal.addEventListener('hidden.bs.modal', resetProjectModal);

            // Create Project Form Handler
            const createProjectForm = document.getElementById('createProjectForm');
            if (createProjectForm) {
                createProjectForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Protect button
                    const btn = document.querySelector('#createProjectModal .btn.orange_btn');
                    if (btn && btn.disabled) return;
                    if (btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
                    }

                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    let hasFiles = false;

                    fileInputs.forEach(input => {
                        if (input.files && input.files.length > 0) {
                            hasFiles = true;
                        }
                    });

                    if (hasFiles) {
                        // Store form data for later use
                        window.projectFormData = new FormData(createProjectForm);

                        // Close project modal
                        const projectModal = bootstrap.Modal.getInstance(document.getElementById(
                            'createProjectModal'));
                        if (projectModal) projectModal.hide();

                        // Open drawing modal for markup
                        setTimeout(() => {
                            openDrawingModal({
                                title: @json(__('messages.markup_project_files')),
                                saveButtonText: @json(__('messages.create')),
                                mode: 'image',
                                onSave: function(markedUpImageData) {
                                    // Create project with marked up images
                                    createProjectWithMarkup(markedUpImageData);
                                }
                            });

                            // Collect only image files for drawing modal
                            let allFiles = [];
                            fileInputs.forEach(input => {
                                if (input.files && input.files.length > 0) {
                                    const imageFiles = Array.from(input.files).filter(
                                        file =>
                                        file.type.startsWith('image/'));
                                    allFiles = allFiles.concat(imageFiles);
                                }
                            });

                            window.selectedFiles = allFiles;

                            if (allFiles.length > 0) {
                                document.getElementById('drawingModal').addEventListener(
                                    'shown.bs.modal',
                                    function() {
                                        if (allFiles.length === 1) {
                                            loadImageToCanvas(allFiles[0]);
                                        } else {
                                            loadMultipleFiles(allFiles);
                                        }
                                    }, {
                                        once: true
                                    });
                            } else {
                                // No images to markup, create project directly
                                createProjectDirectly();
                            }
                        }, 300);
                    } else {
                        // No files, create project directly
                        createProjectDirectly();
                    }
                });
            }

            // Show selected files function
            window.showSelectedFiles = function(input, containerId) {
                const container = document.getElementById(containerId);
                container.innerHTML = '';

                if (input.files && input.files.length > 0) {
                    Array.from(input.files).forEach(file => {
                        const fileItem = document.createElement('div');
                        fileItem.className = 'file-item';

                        const icon = getFileIcon(file.name);
                        const size = (file.size / 1024 / 1024).toFixed(2);

                        fileItem.innerHTML = `
                            <i class="${icon}"></i>
                            <div class="flex-grow-1">
                                <div class="fw-medium">${file.name}</div>
                                <small class="text-muted">${size} MB</small>
                            </div>
                        `;

                        container.appendChild(fileItem);
                    });

                    // Show notes section for all files
                    showNotesForImages(input, containerId);
                }
            };

            // Show smart notes section - only for selected files
            function showNotesForImages(input, containerId) {
                const notesContainerId = containerId.replace('-files', '-notes-container');
                const notesListId = containerId.replace('-files', '-notes-list');
                const notesContainer = document.getElementById(notesContainerId);
                const notesList = document.getElementById(notesListId);

                if (!notesContainer || !notesList) return;

                // Clear previous notes
                notesList.innerHTML = '';

                // Show smart notes interface for multiple files
                if (input.files && input.files.length > 0) {
                    notesContainer.style.display = 'block';

                    if (input.files.length > 3) {
                        // Smart interface for many files
                        const smartInterface = document.createElement('div');
                        smartInterface.innerHTML = `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>${input.files.length} {{ __('messages.selected_files') }}.</strong> {{ __('messages.select_files_for_description') }}:
                            </div>
                            <div class="row g-2" style="max-height: 200px; overflow-y: auto;">
                                ${Array.from(input.files).map((file, index) => {
                                    const fileIcon = file.type.startsWith('image/') ? 'fas fa-image text-success' : 'fas fa-file text-primary';
                                    return `
                                                            <div class="col-12">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" id="file_${containerId}_${index}" 
                                                                        onchange="toggleFileDescription('${containerId}', ${index})">
                                                                    <label class="form-check-label d-flex align-items-center" for="file_${containerId}_${index}">
                                                                        <i class="${fileIcon} me-2"></i>
                                                                        <span class="text-truncate">${file.name}</span>
                                                                    </label>
                                                                </div>
                                                                <div class="ms-4 mt-2" id="desc_${containerId}_${index}" style="display: none;">
                                                                    <textarea class="form-control form-control-sm" name="file_notes_${containerId}_${index}" 
                                                                        placeholder="{{ __('messages.add_note_for_this_image') }}" rows="2"></textarea>
                                                                </div>
                                                            </div>
                                                        `;
                                }).join('')}
                            </div>
                        `;
                        notesList.appendChild(smartInterface);
                    } else {
                        // Regular interface for few files
                        Array.from(input.files).forEach((file, index) => {
                            const noteItem = document.createElement('div');
                            noteItem.className = 'mb-3';
                            const fileIcon = file.type.startsWith('image/') ? 'fas fa-image text-success' :
                                'fas fa-file text-primary';
                            noteItem.innerHTML = `
                                <div class="d-flex align-items-center mb-2">
                                    <i class="${fileIcon} me-2"></i>
                                    <span class="fw-medium">${file.name}</span>
                                </div>
                                <textarea class="form-control" name="file_notes_${containerId}_${index}" 
                                    placeholder="{{ __('messages.add_note_for_this_image') }}" rows="2"></textarea>
                            `;
                            notesList.appendChild(noteItem);
                        });
                    }
                } else {
                    notesContainer.style.display = 'none';
                }
            }

            // Toggle description input for selected files
            window.toggleFileDescription = function(containerId, index) {
                const checkbox = document.getElementById(`file_${containerId}_${index}`);
                const descContainer = document.getElementById(`desc_${containerId}_${index}`);

                if (checkbox.checked) {
                    descContainer.style.display = 'block';
                    const textarea = descContainer.querySelector('textarea');
                    if (textarea) textarea.focus();
                } else {
                    descContainer.style.display = 'none';
                    const textarea = descContainer.querySelector('textarea');
                    if (textarea) textarea.value = '';
                }
            };

            function getFileIcon(filename) {
                const ext = filename.split('.').pop().toLowerCase();
                switch (ext) {
                    case 'pdf':
                        return 'fas fa-file-pdf text-danger';
                    case 'docx':
                    case 'doc':
                        return 'fas fa-file-word text-primary';
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        return 'fas fa-file-image text-success';
                    default:
                        return 'fas fa-file text-secondary';
                }
            }

            // Load project managers for dropdown
            async function loadProjectManagers() {
                try {
                    const response = await api.getProjectManagers();
                    const select = document.getElementById('project_manager_id');

                    // Clear existing options except first
                    select.innerHTML = '<option value="">{{ __('messages.select_manager') }}</option>';

                    if (response.code === 200 && response.data) {
                        response.data.forEach(manager => {
                            const option = document.createElement('option');
                            option.value = manager.id;
                            option.textContent = `${manager.name} - ${manager.company_name || ''}`;
                            select.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Failed to load project managers:', error);
                    showToast('Failed to load project managers', 'error');
                }
            }

            // Load technical engineers for dropdown
            async function loadTechnicalEngineers() {
                try {
                    const response = await api.getTechnicalEngineers();
                    const select = document.getElementById('technical_engineer_id');

                    // Clear existing options except first
                    select.innerHTML = '<option value="">{{ __('messages.select_engineer') }}</option>';

                    if (response.code === 200 && response.data) {
                        response.data.forEach(engineer => {
                            const option = document.createElement('option');
                            option.value = engineer.id;
                            option.textContent = `${engineer.name} - ${engineer.company_name || ''}`;
                            select.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Failed to load technical engineers:', error);
                    showToast('Failed to load technical engineers', 'error');
                }
            }

            // Create project with marked up images
            async function createProjectWithMarkup(markedUpImageData) {
                try {
                    // Get the stored form data
                    const formData = window.projectFormData;

                    // Store original files (both image and non-image) before clearing
                    const originalFiles = {
                        construction_plans: [],
                        gantt_chart: []
                    };

                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    fileInputs.forEach(input => {
                        if (input.files && input.files.length > 0) {
                            Array.from(input.files).forEach(file => {
                                if (input.name === 'construction_plans[]') {
                                    originalFiles.construction_plans.push(file);
                                } else if (input.name === 'gantt_chart[]') {
                                    originalFiles.gantt_chart.push(file);
                                }
                            });
                        }
                    });

                    // Remove original files from FormData
                    formData.delete('construction_plans[]');
                    formData.delete('gantt_chart[]');

                    // Add back non-image files
                    originalFiles.construction_plans.forEach(file => {
                        if (!file.type.startsWith('image/')) {
                            formData.append('construction_plans[]', file);
                        }
                    });
                    originalFiles.gantt_chart.forEach(file => {
                        if (!file.type.startsWith('image/')) {
                            formData.append('gantt_chart[]', file);
                        }
                    });

                    // Collect file notes
                    const fileNotes = {};
                    const noteInputs = document.querySelectorAll('textarea[name^="file_notes_"]');
                    noteInputs.forEach(input => {
                        if (input.value.trim()) {
                            fileNotes[input.name] = input.value.trim();
                        }
                    });

                    // Add file notes to form data
                    if (Object.keys(fileNotes).length > 0) {
                        formData.append('file_notes', JSON.stringify(fileNotes));
                    }

                    // Handle marked up images and original images
                    if (Array.isArray(markedUpImageData)) {
                        // Multiple files - need to match with original files
                        const imageFiles = [];
                        fileInputs.forEach(input => {
                            if (input.files && input.files.length > 0) {
                                Array.from(input.files).forEach(file => {
                                    if (file.type.startsWith('image/')) {
                                        imageFiles.push({
                                            file,
                                            inputName: input.name
                                        });
                                    }
                                });
                            }
                        });

                        for (let i = 0; i < markedUpImageData.length; i++) {
                            if (markedUpImageData[i] && imageFiles[i]) {
                                const targetInput = imageFiles[i].inputName;

                                if (typeof markedUpImageData[i] === 'string' && markedUpImageData[i].startsWith(
                                        'data:')) {
                                    // This is a canvas data URL (marked up image)
                                    const response = await fetch(markedUpImageData[i]);
                                    const blob = await response.blob();
                                    formData.append(targetInput, blob, `marked_up_${i + 1}.png`);
                                } else if (markedUpImageData[i] instanceof File) {
                                    // This is an original file (no drawing)
                                    formData.append(targetInput, markedUpImageData[i]);
                                }
                            }
                        }
                    } else if (markedUpImageData) {
                        // Single file - find which input it came from
                        let targetInput = 'construction_plans[]';
                        fileInputs.forEach(input => {
                            if (input.files && input.files.length > 0) {
                                Array.from(input.files).forEach(file => {
                                    if (file.type.startsWith('image/')) {
                                        targetInput = input.name;
                                    }
                                });
                            }
                        });

                        if (typeof markedUpImageData === 'string' && markedUpImageData.startsWith('data:')) {
                            // Single marked up image
                            const response = await fetch(markedUpImageData);
                            const blob = await response.blob();
                            formData.append(targetInput, blob, 'marked_up_image.png');
                        } else if (markedUpImageData instanceof File) {
                            // Single original file
                            formData.append(targetInput, markedUpImageData);
                        }
                    }

                    // Call create project API
                    const apiResponse = await api.createProject(formData);

                    if (apiResponse.code === 200) {
                        // Close drawing modal
                        const drawingModal = bootstrap.Modal.getInstance(document.getElementById(
                            'drawingModal'));
                        if (drawingModal) drawingModal.hide();

                        showToast(apiResponse.message || @json(__('messages.project_created_with_markup')), 'success');
                        document.getElementById('createProjectForm').reset();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(apiResponse.message || 'Failed to create project', 'error');
                    }
                } catch (error) {
                    console.error('Create project with markup error:', error);
                    showToast('Failed to create project. Please try again.', 'error');
                } finally {
                    // Reset buttons
                    const btn = document.querySelector('#createProjectModal .btn.orange_btn');
                    const drawingBtn = document.getElementById('saveDrawingBtn');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-plus me-2"></i>Create Project';
                    }
                    if (drawingBtn) {
                        drawingBtn.disabled = false;
                        drawingBtn.innerHTML =
                            '<i class="fas fa-save me-2"></i><span id="saveButtonText">Save</span>';
                    }
                }
            }

            // Create project without files
            async function createProjectDirectly() {
                try {
                    const formData = new FormData(document.getElementById('createProjectForm'));

                    // Collect file notes even for direct creation
                    const fileNotes = {};
                    const noteInputs = document.querySelectorAll('textarea[name^="file_notes_"]');
                    noteInputs.forEach(input => {
                        if (input.value.trim()) {
                            fileNotes[input.name] = input.value.trim();
                        }
                    });

                    // Add file notes to form data
                    if (Object.keys(fileNotes).length > 0) {
                        formData.append('file_notes', JSON.stringify(fileNotes));
                    }

                    const response = await api.createProject(formData);

                    if (response.code === 200) {
                        const projectModal = bootstrap.Modal.getInstance(document.getElementById(
                            'createProjectModal'));
                        if (projectModal) projectModal.hide();

                        showToast(response.message || 'Project created successfully!', 'success');
                        document.getElementById('createProjectForm').reset();
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(response.message || 'Failed to create project', 'error');
                    }
                } catch (error) {
                    console.error('Create project error:', error);
                    showToast('Failed to create project. Please try again.', 'error');
                } finally {
                    // Reset button
                    const btn = document.querySelector('#createProjectModal .btn.orange_btn');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-plus me-2"></i>Create Project';
                    }
                }
            }
        });

        // Delete project from dashboard
        function deleteProjectFromDashboard(projectId) {
            confirmationModal.show({
                title: '{{ __('messages.delete_project') }}',
                message: '{{ __('messages.delete_project_warning') }}',
                icon: 'fas fa-exclamation-triangle text-danger',
                confirmText: '{{ __('messages.delete') }}',
                cancelText: '{{ __('messages.cancel') }}',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        const response = await api.makeRequest('projects/delete', {
                            project_id: projectId,
                            user_id: {{ auth()->id() ?? 1 }}
                        });

                        if (response.code === 200) {
                            showToast(response.message || '{{ __('messages.project_deleted_successfully') }}', 'success');
                            loadProjects(currentFilter, true);
                        } else {
                            showToast(response.message || '{{ __('messages.failed_to_delete_project') }}', 'error');
                        }
                    } catch (error) {
                        console.error('Error deleting project:', error);
                        showToast(error.message || '{{ __('messages.error_deleting_project') }}', 'error');
                    }
                }
            });
        }

        // Toast notification function using toastr
        function showToast(message, type = 'success') {
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
        }
    </script>

    <!-- =======================Main content End============================== -->



    <!-- ============= JavaScript Files ========================= -->
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/js/wow.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('website/js/toastr-config.js') }}"></script>

    <!-- SIMPLE DRAWING LIBRARY -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/4.1.7/signature_pad.umd.min.js"></script>

    <script src="{{ asset('website/js/custom.js') }}"></script>
    <script src="{{ asset('website/js/button-protection.js') }}"></script>
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
    <script src="{{ asset('website/js/confirmation-modal.js') }}"></script>

    <script src="{{ asset('website/js/universal-auth.js') }}"></script>
    <script src="{{ asset('website/js/rtl-spacing-fix.js') }}"></script>
    <script src="{{ asset('website/js/profile-image-sync.js') }}"></script>
    
    <!-- Firebase Web Push Notifications -->
    <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js"></script>
    <script src="{{ asset('firebase-config.js') }}"></script>
    <script src="{{ asset('website/js/web-push-manager.js') }}"></script>
    
    <script>
        // Disable auth check on dashboard - Laravel middleware handles it
        window.DISABLE_JS_AUTH_CHECK = true;
        
        // Initialize web push notifications on dashboard
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[Dashboard] Page loaded, checking for web push initialization');
            
            // Wait a bit for all scripts to load and user data to be available
            setTimeout(function() {
                if (typeof window.initializeWebPush === 'function') {
                    console.log('[Dashboard] Triggering web push initialization');
                    window.initializeWebPush();
                } else {
                    console.warn('[Dashboard] initializeWebPush function not available');
                }
            }, 2000);
        });
    </script>

    <script>
        // Logout function
        async function logout() {
            confirmationModal.show({
                title: '{{ __('auth.logout_confirmation') }}',
                message: '{{ __('auth.logout_confirmation_message') }}',
                icon: 'fas fa-sign-out-alt text-warning',
                confirmText: '{{ __('auth.logout') }}',
                cancelText: '{{ __('messages.cancel') }}',
                confirmClass: 'btn-danger',
                onConfirm: async () => {
                    try {
                        // Call API logout
                        await api.logout({});

                        // Clear browser storage
                        UniversalAuth.logout();

                        // Clear Laravel session
                        fetch('/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.getAttribute('content')
                            }
                        });

                        toastr.success('{{ __('auth.logged_out_successfully') }}');

                        // Force redirect
                        setTimeout(() => {
                            window.location.replace('/login');
                        }, 500);

                    } catch (error) {
                        console.error('Logout failed:', error);
                        sessionStorage.clear();
                        localStorage.clear();
                        window.location.replace('/login');
                    }
                }
            });
        }
    </script>

    @include('website.includes.permission-error-handler')

</body>

</html>
