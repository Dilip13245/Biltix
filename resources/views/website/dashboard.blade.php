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
    
    /* Toast Notifications */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    .custom-toast {
        min-width: 300px;
        border: none;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .toast-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }
    .toast-error {
        background: linear-gradient(135deg, #dc3545, #e74c3c);
        color: white;
    }
    .toast-warning {
        background: linear-gradient(135deg, #ffc107, #fd7e14);
        color: white;
    }
    .toast-info {
        background: linear-gradient(135deg, #17a2b8, #007bff);
        color: white;
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
                                <input class="form-control" type="search"
                                    placeholder="{{ __('messages.search_projects') }}" aria-label="Search"
                                    data-bs-toggle="modal" data-bs-target="#searchModal" readonly>
                            </form>
                            <div class="position-relative MessageBOx text-center" style="cursor: pointer;"><img
                                    src="{{ asset('website/images/icons/bell.svg') }}" alt="Bell"
                                    class="img-fluid notifaction-icon"><span
                                    class="fw-normal fs12 text-white orangebg">3</span>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center gap-2 gap-md-3" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    <img src="{{ asset('website/images/icons/avtar.svg') }}" alt="user img"
                                        class="User_iMg">
                                    <span class=" text-end">
                                        <h6 class="fs14 fw-medium black_color">{{ __('messages.john_smith') }}</h6>
                                        <p class="Gray_color fs12 fw-normal">{{ __('messages.consultant') }}</p>
                                    </span>
                                    <img src="{{ asset('website/images/icons/arrow-up.svg') }}" alt="user img"
                                        class="arrow">
                                </a>
                                <ul class="dropdown-menu {{ is_rtl() ? 'dropdown-menu-start' : 'dropdown-menu-end' }}" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('website.profile') }}">
                                        <i class="fas fa-user"></i>
                                        {{ __('auth.my_profile') }}
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="#" onclick="logout()">
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
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp"
                        data-wow-delay="0s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.active_projects_count') }}</div>
                                    <div class="stat-value">12</div>
                                </div>
                                <span class="ms-auto stat-icon bg1"><img
                                        src="{{ asset('website/images/icons/share.svg') }}" alt="share"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp"
                        data-wow-delay=".4s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.pending_reviews') }}</div>
                                    <div class="stat-value">8</div>
                                </div>
                                <span class="ms-auto stat-icon bg2"><img
                                        src="{{ asset('website/images/icons/clock.svg') }}" alt="clock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp"
                        data-wow-delay=".8s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.inspections_due') }}</div>
                                    <div class="stat-value">5</div>
                                </div>
                                <span class="ms-auto stat-icon"><img
                                        src="{{ asset('website/images/icons/calendar.svg') }}" alt="calendar"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp"
                        data-wow-delay="1.2s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('messages.completed_this_month') }}</div>
                                    <div class="stat-value">3</div>
                                </div>
                                <span class="ms-auto stat-icon bg4"><img
                                        src="{{ asset('website/images/icons/suc.svg') }}" alt="suc"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Projects Title & Filter -->
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 mt-3 mt-md-4 wow fadeInUp flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">{{ __('messages.ongoing_projects') }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                            <i class="fas fa-plus"></i>
                            {{ __('messages.new_project') }}
                        </button>
                        <select class="form-select w-auto" id="statusFilter">
                            <option value="all">{{ __('messages.all_status') }}</option>
                            <option value="active">{{ __('messages.active') }}</option>
                            <option value="completed">{{ __('messages.completed') }}</option>
                        </select>
                    </div>
                </div>

                <!-- Ongoing Projects Cards -->
                <div class="row g-4">
                    <!-- Project Card 1 -->
                    <div class="col-12 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="0s">
                        <a href="{{ route('website.project.plans', 1) }}">
                            <div class="card project-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">{{ __('messages.downtown_office_complex') }}</h6>
                                        <span class="badge bg-orange text-white">{{ __('messages.active') }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ __('messages.commercial_building') }}</div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted small">{{ __('messages.progress') }}</span>
                                            <span class="fw-medium small">68%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" style="width: 68%;"></div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2 mb-md-3">{{ __('messages.due') }}: Dec 15, 2024
                                    </div>
                                    <div>
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <span class="ms-2 text-muted small">+5 {{ __('messages.more') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Project Card 2 -->
                    <div class="col-12 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".4s">
                        <a href="{{ route('website.project.plans', 2) }}">
                            <div class="card project-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">{{ __('messages.residential_tower_a') }}</h6>
                                        <span class="badge bg-orange text-white">{{ __('messages.active') }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ __('messages.residential_complex') }}</div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted small">{{ __('messages.progress') }}</span>
                                            <span class="fw-medium small">45%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-yellow" style="width: 45%;"></div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2 mb-md-3">{{ __('messages.due') }}: Mar 20, 2025
                                    </div>
                                    <div>
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <span class="ms-2 text-muted small">+3 {{ __('messages.more') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Project Card 3 -->
                    <div class="col-12 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay=".8s">
                        <a href="{{ route('website.project.plans', 3) }}">
                            <div class="card project-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">{{ __('messages.shopping_mall_renovation') }}</h6>
                                        <span class="badge bg-green1">{{ __('messages.completed') }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ __('messages.renovation_project') }}</div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted small">{{ __('messages.progress') }}</span>
                                            <span class="fw-medium small">100%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-green" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2 mb-md-3">{{ __('messages.completed') }}: Nov 30,
                                        2024</div>
                                    <div>
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <span class="ms-2 text-muted small">+2 {{ __('messages.more') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Project Card 4 -->
                    <div class="col-12 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="1.2s">
                        <a href="{{ route('website.project.plans', 4) }}">
                            <div class="card project-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">{{ __('messages.industrial_warehouse') }}</h6>
                                        <span class="badge bg-orange text-white">{{ __('messages.active') }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ __('messages.industrial_building') }}</div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted small">{{ __('messages.progress') }}</span>
                                            <span class="fw-medium small">32%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-blue" style="width: 32%;"></div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2 mb-md-3">{{ __('messages.due') }}: Jun 10, 2025
                                    </div>
                                    <div>
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <span class="ms-2 text-muted small">+4 {{ __('messages.more') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Project Card 5 -->
                    <div class="col-12 col-md-6 col-lg-4  wow fadeInUp" data-wow-delay="1.6s">
                        <a href="{{ route('website.project.plans', 5) }}">
                            <div class="card project-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">{{ __('messages.hospital_extension') }}</h6>
                                        <span class="badge bg-orange text-white">{{ __('messages.active') }}</span>
                                    </div>
                                    <div class="text-muted small mb-2">{{ __('messages.healthcare_facility') }}</div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted small">{{ __('messages.progress') }}</span>
                                            <span class="fw-medium small">78%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-blue" style="width: 78%;"></div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2 mb-md-3">{{ __('messages.due') }}: Jun 10, 2025
                                    </div>
                                    <div>
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar"
                                            alt="avatar">
                                        <span class="ms-2 text-muted small">+4 {{ __('messages.more') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('website.modals.search-modal')
    @include('website.modals.drawing-modal')
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Create Project Modal -->
    <div class="modal fade" id="createProjectModal" tabindex="-1" aria-labelledby="createProjectModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header {{ is_rtl() ? 'justify-content-end' : '' }}">
            <h5 class="modal-title {{ is_rtl() ? 'order-2' : '' }}" id="createProjectModalLabel">
              <i class="fas fa-plus {{ margin_end(2) }}"></i>{{ __('messages.create_new_project') }}
            </h5>
            <button type="button" class="btn-close {{ is_rtl() ? 'order-1 me-auto' : '' }}" data-bs-dismiss="modal" aria-label="Close"></button>
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
                      <label for="project_title" class="form-label fw-medium">{{ __('messages.project_name') }} *</label>
                      <input type="text" class="form-control Input_control" id="project_title" name="project_title" required
                        placeholder="{{ __('messages.project_name') }}">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="contractor_name" class="form-label fw-medium">{{ __('messages.contractor_name') }} *</label>
                      <input type="text" class="form-control Input_control" id="contractor_name" name="contractor_name" required
                        placeholder="{{ __('messages.contractor_name') }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="project_manager_id" class="form-label fw-medium">{{ __('messages.assign_project_manager') }}</label>
                      <select class="form-select Input_control" id="project_manager_id" name="project_manager_id">
                        <option value="">{{ __('messages.select_manager') }}</option>
                        <option value="1">John Smith</option>
                        <option value="2">Sarah Johnson</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="technical_engineer_id" class="form-label fw-medium">{{ __('messages.assign_technical_engineer') }}</label>
                      <select class="form-select Input_control" id="technical_engineer_id" name="technical_engineer_id">
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
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="type" class="form-label fw-medium">{{ __('messages.project_type_example') }}</label>
                      <select class="form-select Input_control" id="type" name="type" required>
                        <option value="">{{ __('messages.select_type') }}</option>
                        <option value="villa">{{ __('messages.villa') }}</option>
                        <option value="tower">{{ __('messages.tower') }}</option>
                        <option value="hospital">{{ __('messages.hospital') }}</option>
                        <option value="commercial">{{ __('messages.commercial') }}</option>
                        <option value="residential">{{ __('messages.residential') }}</option>
                        <option value="industrial">{{ __('messages.industrial') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="project_location" class="form-label fw-medium">{{ __('messages.project_location') }} *</label>
                      <input type="text" class="form-control Input_control" id="project_location" name="project_location" required
                        placeholder="{{ __('messages.location') }}">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="project_start_date" class="form-label fw-medium">{{ __('messages.project_start_date') }} *</label>
                      @include('website.includes.date-picker', [
                        'id' => 'project_start_date',
                        'name' => 'project_start_date',
                        'placeholder' => __('messages.select_start_date'),
                        'minDate' => date('Y-m-d'),
                        'required' => true
                      ])
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="mb-3">
                      <label for="project_due_date" class="form-label fw-medium">{{ __('messages.project_due_date') }} *</label>
                      @include('website.includes.date-picker', [
                        'id' => 'project_due_date',
                        'name' => 'project_due_date',
                        'placeholder' => __('messages.select_due_date'),
                        'minDate' => date('Y-m-d'),
                        'required' => true
                      ])
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Step 3: File Uploads -->
              <div class="step-content d-none" id="step3">
                <h6 class="mb-3">{{ __('messages.upload_files') }}</h6>
                <div class="mb-4">
                  <label class="form-label fw-medium mb-3">{{ __('messages.upload_construction_plans') }}</label>
                  <div class="upload-zone p-4 text-center position-relative" onclick="document.getElementById('construction_plans').click()">
                    <div class="upload-icon mb-3">
                      <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                    </div>
                    <h6 class="mb-2">{{ __('messages.upload_your_files') }} 📎</h6>
                    <p class="text-muted mb-3">{{ __('messages.drag_drop_or_click') }}</p>
                    <small class="text-muted d-block">{{ __('messages.supported_formats_construction') }}</small>
                    <input type="file" class="d-none" id="construction_plans" name="construction_plans[]" 
                      multiple accept=".pdf,.docx,.jpg,.jpeg,.png" onchange="showSelectedFiles(this, 'construction-files')">
                    <div id="construction-files" class="selected-files mt-3"></div>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-medium mb-3">{{ __('messages.upload_gantt_chart') }}</label>
                  <div class="upload-zone p-4 text-center position-relative" onclick="document.getElementById('gantt_chart').click()">
                    <div class="upload-icon mb-3">
                      <i class="fas fa-cloud-upload-alt fa-3x text-success"></i>
                    </div>
                    <h6 class="mb-2">{{ __('messages.upload_your_files') }} 📎</h6>
                    <p class="text-muted mb-3">{{ __('messages.drag_drop_or_click') }}</p>
                    <small class="text-muted d-block">{{ __('messages.supported_formats_gantt') }}</small>
                    <input type="file" class="d-none" id="gantt_chart" name="gantt_chart[]" 
                      multiple accept=".pdf,.docx,.jpg,.jpeg,.png" onchange="showSelectedFiles(this, 'gantt-files')">
                    <div id="gantt-files" class="selected-files mt-3"></div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
            <button type="button" class="btn btn-outline-primary d-none" id="prevBtn" onclick="changeStep(-1)">
              <i class="fas fa-arrow-left me-2"></i>{{ __('messages.previous') }}
            </button>
            <button type="button" class="btn orange_btn" id="nextBtn" onclick="changeStep(1)">
              {{ __('messages.next') }}<i class="fas fa-arrow-right ms-2"></i>
            </button>
            <button type="submit" form="createProjectForm" class="btn orange_btn d-none" id="submitBtn">
              <i class="fas fa-plus me-2"></i>{{ __('messages.create') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const filterValue = this.value.toLowerCase();
                    const projectCards = document.querySelectorAll('.row.g-4 > div');

                    projectCards.forEach(card => {
                        const statusBadge = card.querySelector('.badge');
                        if (statusBadge) {
                            const cardStatus = statusBadge.textContent.toLowerCase();
                            if (filterValue === 'all' || cardStatus.includes(filterValue)) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                });
            }

            // Notification bell click
            const notificationBell = document.querySelector('.MessageBOx');
            if (notificationBell) {
                notificationBell.addEventListener('click', function() {
                    showToast('Notification panel would open here showing recent updates.', 'info');
                });
            }
            
            // Multi-step form variables
            let currentStep = 1;
            const totalSteps = 3;
            
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
                const requiredFields = document.querySelectorAll(`#step${step} input[required], #step${step} select[required]`);
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
            
            // Reset form when modal opens
            document.getElementById('createProjectModal').addEventListener('show.bs.modal', function() {
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
            });
            
            // Create Project Form Handler
            const createProjectForm = document.getElementById('createProjectForm');
            if (createProjectForm) {
                createProjectForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    let hasFiles = false;
                    
                    fileInputs.forEach(input => {
                        if (input.files && input.files.length > 0) {
                            hasFiles = true;
                        }
                    });
                    
                    if (hasFiles) {
                        // Store form data
                        window.projectFormData = new FormData(createProjectForm);
                        
                        // Close project modal
                        const projectModal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
                        if (projectModal) projectModal.hide();
                        
                        // Open drawing modal for file markup
                        setTimeout(() => {
                            openDrawingModal({
                                title: @json(__('messages.markup_project_files')),
                                saveButtonText: @json(__('messages.create')),
                                mode: 'image',
                                onSave: function(imageData) {
                                    // Close drawing modal
                                    const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
                                    if (drawingModal) drawingModal.hide();
                                    
                                    showToast(@json(__('messages.project_created_with_markup')), 'success');
                                    setTimeout(() => location.reload(), 1500);
                                }
                            });
                            
                            // Collect all files from both inputs
                            let allFiles = [];
                            fileInputs.forEach(input => {
                                if (input.files && input.files.length > 0) {
                                    allFiles = allFiles.concat(Array.from(input.files));
                                }
                            });
                            
                            // Store all files globally for drawing modal
                            window.selectedFiles = allFiles;
                            
                            if (allFiles.length > 0) {
                                document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
                                    if (allFiles.length === 1) {
                                        loadImageToCanvas(allFiles[0]);
                                    } else {
                                        loadMultipleFiles(allFiles);
                                    }
                                }, { once: true });
                            }
                        }, 300);
                    } else {
                        // No files, create project directly
                        const projectModal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
                        if (projectModal) projectModal.hide();
                        
                        showToast('Project created successfully!', 'success');
                        createProjectForm.reset();
                        setTimeout(() => location.reload(), 1500);
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
                }
            };
            
            function getFileIcon(filename) {
                const ext = filename.split('.').pop().toLowerCase();
                switch(ext) {
                    case 'pdf': return 'fas fa-file-pdf text-danger';
                    case 'docx': case 'doc': return 'fas fa-file-word text-primary';
                    case 'jpg': case 'jpeg': case 'png': return 'fas fa-file-image text-success';
                    default: return 'fas fa-file text-secondary';
                }
            }
        });
        
        // Toast notification function
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            const toastId = 'toast-' + Date.now();
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-times-circle',
                warning: 'fas fa-exclamation-triangle',
                info: 'fas fa-info-circle'
            };
            
            const toastHtml = `
                <div class="toast custom-toast toast-${type}" id="${toastId}" role="alert">
                    <div class="toast-body d-flex align-items-center p-3">
                        <i class="${icons[type]} me-2 fa-lg"></i>
                        <div class="flex-grow-1">${message}</div>
                        <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: type === 'error' ? 5000 : 3000
            });
            
            toast.show();
            
            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
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
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
    <script src="{{ asset('website/js/confirmation-modal.js') }}"></script>
    
    @include('website.layout.auth-check')
    
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
                        sessionStorage.clear();
                        localStorage.clear();
                        
                        // Clear Laravel session
                        await fetch('/auth/logout', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
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


</body>

</html>
