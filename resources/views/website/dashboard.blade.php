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
    
    .notification-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        width: 380px;
        max-width: calc(100vw - 20px);
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        z-index: 1050;
        border: 1px solid #e9ecef;
    }
    
    @media (max-width: 768px) {
        .notification-dropdown {
            right: -150px;
            width: 300px;
            max-width: calc(100vw - 40px);
        }
    }
    
    @media (max-width: 480px) {
        .notification-dropdown {
            right: -120px;
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
                            <div class="notification-wrapper position-relative">
                                <div class="position-relative MessageBOx text-center" style="cursor: pointer;" onclick="toggleNotifications()">
                                    <img src="{{ asset('website/images/icons/bell.svg') }}" alt="Bell" class="img-fluid notifaction-icon">
                                    <span class="fw-normal fs12 text-white orangebg" id="notificationCount" style="display: none;">0</span>
                                </div>
                                <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
                                    <div class="notification-header">
                                        <span class="fw-bold">{{ __('messages.notifications') }}</span>
                                        <button class="btn btn-sm orange_btn" onclick="markAllAsRead()" style="font-size: 11px; padding: 4px 8px;">{{ __('messages.mark_all_read') }}</button>
                                    </div>
                                    <div class="notification-body" id="notificationList">
                                        <div class="text-center py-3">
                                            <div class="spinner-border spinner-border-sm" role="status"></div>
                                            <span class="ms-2">{{ __('messages.loading') }}...</span>
                                        </div>
                                    </div>
                                    <div class="notification-footer">
                                        <a href="#" class="text-primary">{{ __('messages.view_all_notifications') }}</a>
                                    </div>
                                </div>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center gap-2 gap-md-3" type="button"
                                    id="dropdownMenuButton" data-bs-toggle="dropdown">
                                    <img src="{{ asset('website/images/icons/avtar.svg') }}" alt="user img"
                                        class="User_iMg">
                                    <span class=" text-end">
                                        <h6 class="fs14 fw-medium black_color">
                                            @if(isset($currentUser))
                                                {{ $currentUser->name }}
                                            @else
                                                {{ __('messages.john_smith') }}
                                            @endif
                                        </h6>
                                        <p class="Gray_color fs12 fw-normal">
                                            @if(isset($currentUser))
                                                {{ $currentUser->getRoleDisplayName() }}
                                            @else
                                                {{ __('messages.consultant') }}
                                            @endif
                                        </p>
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
                                    <div class="small_tXt">
                                        @if(isset($currentUser) && $currentUser->getDashboardAccess() === 'assigned_only')
                                            {{ __('messages.assigned_projects') }}
                                        @else
                                            {{ __('messages.active_projects_count') }}
                                        @endif
                                    </div>
                                    <div class="stat-value">...</div>
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
                                    <div class="stat-value">...</div>
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
                                    <div class="stat-value">...</div>
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
                                    <div class="stat-value">...</div>
                                </div>
                                <span class="ms-auto stat-icon bg4"><img
                                        src="{{ asset('website/images/icons/suc.svg') }}" alt="suc"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ongoing Projects Title & Filter -->
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 mt-3 mt-md-4 wow fadeInUp flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">
                        @if(isset($currentUser) && $currentUser->getDashboardAccess() === 'assigned_only')
                            {{ __('messages.assigned_projects') }}
                        @else
                            {{ __('messages.ongoing_projects') }}
                        @endif
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        @can('projects', 'create')
                        <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#createProjectModal">
                            <i class="fas fa-plus"></i>
                            {{ __('messages.new_project') }}
                        </button>
                        @endcan
                        <select class="form-select w-auto" id="statusFilter">
                            <option value="all">{{ __('messages.all_status') }}</option>
                            <option value="active">{{ __('messages.active') }}</option>
                            <option value="completed">{{ __('messages.completed') }}</option>
                        </select>
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
    @endcan

    <script>
        // Load dashboard stats
        async function loadDashboardStats() {
            try {
                const response = await api.getDashboardStats();
                
                if (response.code === 200 && response.data) {
                    const stats = response.data;
                    
                    // Update stat values using more specific selectors
                    const statCards = document.querySelectorAll('.stat-value');
                    if (statCards.length >= 4) {
                        statCards[0].textContent = stats.active_projects || 0;
                        statCards[1].textContent = stats.pending_tasks || 0;
                        statCards[2].textContent = stats.inspections_due || 0;
                        statCards[3].textContent = stats.completed_this_month || 0;
                    }
                }
            } catch (error) {
                console.error('Failed to load dashboard stats:', error);
                // Keep default values on error
            }
        }
        
        // Notification functions
        async function loadNotifications() {
            try {
                const response = await api.getNotifications({ page: 1, limit: 50 });
                
                if (response.code === 200 && response.data) {
                    let notifications = Array.isArray(response.data) ? response.data : (response.data.notifications || response.data.data || []);
                    
                    if (Array.isArray(notifications)) {
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
            notificationList.innerHTML = '<div class="text-center py-4 text-muted"><i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>{{ __('messages.no_notifications') }}</div>';
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
                await api.markNotificationAsRead({ notification_id: notificationId });
                loadNotifications();
            } catch (error) {
                console.error('Failed to mark notification as read:', error);
            }
        }
        
        window.markAllAsRead = async function() {
            try {
                await api.markAllNotificationsAsRead();
                loadNotifications();
                showToast('{{ __('messages.all_notifications_marked_read') }}', 'success');
            } catch (error) {
                console.error('Failed to mark all notifications as read:', error);
                showToast('{{ __('messages.failed_to_mark_notifications') }}', 'error');
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
        
        // Load projects from API
        async function loadProjects(filter = 'all') {
            try {
                const type = filter === 'all' ? '' : (filter === 'active' ? 'ongoing' : 'completed');
                const response = await api.getProjects({ type: type, page: 1, limit: 20 });
                
                if (response.code === 200 && response.data) {
                    displayProjects(response.data);
                } else {
                    displayNoProjects();
                }
            } catch (error) {
                console.error('Failed to load projects:', error);
                displayNoProjects();
            }
        }
        
        function displayProjects(projects) {
            const container = document.getElementById('projectsContainer');
            container.innerHTML = '';
            
            if (!projects || projects.length === 0) {
                displayNoProjects();
                return;
            }
            
            projects.forEach((project, index) => {
                const statusClass = getStatusClass(project.status);
                const statusText = getStatusText(project.status);
                const progressPercent = getRandomProgress(project.status);
                const teamCount = Math.floor(Math.random() * 8) + 2;
                
                const projectCard = `
                    <div class="col-12 col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="${index * 0.4}s">
                        <a href="/website/project/${project.id}/plans">
                            <div class="card project-card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">${project.project_title}</h6>
                                        <span class="badge ${statusClass}">${statusText}</span>
                                    </div>
                                    <div class="text-muted small mb-2">${project.type || 'Construction Project'}</div>
                                    <div class="mb-2">
                                        <div class="d-flex justify-content-between mb-3">
                                            <span class="text-muted small">{{ __('messages.progress') }}</span>
                                            <span class="fw-medium small">${progressPercent}%</span>
                                        </div>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar ${getProgressBarClass(progressPercent)}" style="width: ${progressPercent}%;"></div>
                                        </div>
                                    </div>
                                    <div class="small text-muted mb-2 mb-md-3">
                                        ${project.status === 'completed' ? '{{ __('messages.completed') }}' : '{{ __('messages.due') }}'}: ${formatDate(project.project_due_date)}
                                    </div>
                                    <div>
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar" alt="avatar">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" class="avatar" alt="avatar">
                                        <span class="ms-2 text-muted small">+${teamCount} {{ __('messages.more') }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                `;
                container.innerHTML += projectCard;
            });
        }
        
        function displayNoProjects() {
            const container = document.getElementById('projectsContainer');
            container.innerHTML = '<div class="col-12 text-center py-5"><i class="fas fa-folder-open fa-3x text-muted mb-3"></i><h5 class="text-muted">{{ __('messages.no_projects_found') }}</h5></div>';
        }
        
        function getStatusClass(status) {
            switch(status) {
                case 'completed': return 'bg-green1';
                case 'active': 
                case 'in_progress': 
                case 'planning': return 'bg-orange text-white';
                default: return 'bg-secondary text-white';
            }
        }
        
        function getStatusText(status) {
            switch(status) {
                case 'completed': return '{{ __('messages.completed') }}';
                case 'active': 
                case 'in_progress': 
                case 'planning': return '{{ __('messages.active') }}';
                default: return status;
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
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }
        
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Load stats, notifications and projects on page load
            loadDashboardStats();
            loadNotifications();
            loadProjects();
            
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    loadProjects(this.value);
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
            
            // Reset form when modal opens and load dropdown data
            const createProjectModal = document.getElementById('createProjectModal');
            if (createProjectModal) {
                createProjectModal.addEventListener('show.bs.modal', function() {
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
                
                // Load dropdown data
                loadProjectManagers();
                loadTechnicalEngineers();
                });
            }
            
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
                        // Store form data for later use
                        window.projectFormData = new FormData(createProjectForm);
                        
                        // Close project modal
                        const projectModal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
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
                            
                            // Collect all files for drawing modal
                            let allFiles = [];
                            fileInputs.forEach(input => {
                                if (input.files && input.files.length > 0) {
                                    allFiles = allFiles.concat(Array.from(input.files));
                                }
                            });
                            
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
                    
                    // Remove original files from FormData
                    formData.delete('construction_plans[]');
                    formData.delete('gantt_chart[]');
                    
                    // Handle multiple marked up images
                    if (Array.isArray(markedUpImageData)) {
                        // Multiple files
                        for (let i = 0; i < markedUpImageData.length; i++) {
                            const response = await fetch(markedUpImageData[i]);
                            const blob = await response.blob();
                            formData.append('construction_plans[]', blob, `marked_up_plan_${i + 1}.png`);
                        }
                    } else if (markedUpImageData) {
                        // Single file
                        const response = await fetch(markedUpImageData);
                        const blob = await response.blob();
                        formData.append('construction_plans[]', blob, 'marked_up_plan.png');
                    }
                    
                    // Call create project API
                    const apiResponse = await api.createProject(formData);
                    
                    if (apiResponse.code === 200) {
                        // Close drawing modal
                        const drawingModal = bootstrap.Modal.getInstance(document.getElementById('drawingModal'));
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
                }
            }
            
            // Create project without files
            async function createProjectDirectly() {
                try {
                    const formData = new FormData(document.getElementById('createProjectForm'));
                    const response = await api.createProject(formData);
                    
                    if (response.code === 200) {
                        const projectModal = bootstrap.Modal.getInstance(document.getElementById('createProjectModal'));
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
                }
            }
        });
        
        // Toast notification function using toastr
        function showToast(message, type = 'success') {
            switch(type) {
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
    <script src="{{ asset('website/js/api-config.js') }}"></script>
    <script src="{{ asset('website/js/api-encryption.js') }}"></script>
    <script src="{{ asset('website/js/api-interceptors.js') }}"></script>
    <script src="{{ asset('website/js/api-helpers.js') }}"></script>
    <script src="{{ asset('website/js/api-client.js') }}"></script>
    <script src="{{ asset('website/js/drawing.js') }}"></script>
    <script src="{{ asset('website/js/confirmation-modal.js') }}"></script>
    
    <script src="{{ asset('website/js/universal-auth.js') }}"></script>
    <script src="{{ asset('website/js/rtl-spacing-fix.js') }}"></script>
    <script>
        // Disable auth check on dashboard - Laravel middleware handles it
        window.DISABLE_JS_AUTH_CHECK = true;
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
