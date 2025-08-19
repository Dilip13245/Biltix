<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>@yield('title', __('messages.admin_panel')) - Biltix</title>
    
    <!-- Bootstrap CSS (RTL/LTR) -->
    <link rel="stylesheet" href="{{ bootstrap_css() }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 280px;
            --header-height: 70px;
            --primary-color: #1976d2;
            --border-radius: 12px;
            --box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        .sidebar {
            position: fixed;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            border-inline-end: 1px solid #e9ecef;
            z-index: 1000;
            overflow-y: auto;
            transition: var(--transition);
        }
        
        .main-content {
            margin-inline-start: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }
        
        .top-navbar {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 999;
            padding: 0 1.5rem;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .nav-link {
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: var(--transition);
            text-decoration: none;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background-color: #e3f2fd;
            color: var(--primary-color);
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .menu-title {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #adb5bd;
            padding: 1rem 1.5rem 0.5rem;
            margin-top: 1rem;
        }
        
        .menu-title:first-child {
            margin-top: 0;
        }
        
        .logo {
            padding: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .logo:hover {
            color: var(--primary-color);
        }
        
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 0;
            border-top: 1px solid #e9ecef;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            transition: var(--transition);
            height: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--box-shadow);
        }
        
        .welcome-card {
            background: linear-gradient(135deg, var(--primary-color) 0%, #42a5f5 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .badge {
            font-weight: 500;
            border-radius: 6px;
        }
        
        .dropdown-menu {
            border-radius: 8px;
            border: 1px solid #e9ecef;
            box-shadow: var(--box-shadow);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            font-size: 0.875rem;
            padding: 1rem 0.75rem;
        }
        
        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        
        /* Mobile Responsive */
        @media (max-width: 1199.98px) {
            .content-wrapper {
                padding: 1.5rem;
            }
            
            .stat-card {
                padding: 1.25rem;
            }
        }
        
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            [dir="rtl"] .sidebar {
                transform: translateX(100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-inline-start: 0;
            }
            
            .content-wrapper {
                padding: 1.25rem;
            }
            
            .top-navbar .h4 {
                font-size: 1.25rem;
            }
        }
        
        @media (max-width: 767.98px) {
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
            
            .content-wrapper {
                padding: 1rem;
            }
            
            .welcome-card {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }
            
            .welcome-card .h3 {
                font-size: 1.5rem;
            }
            
            .stat-card {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .stat-card .h2 {
                font-size: 1.75rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .btn {
                font-size: 0.875rem;
                padding: 0.5rem 1rem;
            }
            
            .top-navbar {
                padding: 0 1rem;
            }
            
            .top-navbar .gap-3 {
                gap: 0.75rem !important;
            }
            
            .dropdown-menu {
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 575.98px) {
            .content-wrapper {
                padding: 0.75rem;
            }
            
            .welcome-card {
                padding: 1.25rem;
                text-align: center;
            }
            
            .welcome-card .h3 {
                font-size: 1.25rem;
            }
            
            .stat-card {
                text-align: center;
            }
            
            .stat-card .d-flex {
                flex-direction: column;
                text-align: center;
            }
            
            .stat-card .text-primary,
            .stat-card .text-success,
            .stat-card .text-warning,
            .stat-card .text-info {
                margin-top: 0.5rem;
                order: -1;
            }
            
            .table-responsive {
                font-size: 0.8rem;
            }
            
            .table th,
            .table td {
                padding: 0.5rem 0.25rem;
            }
            
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .top-navbar .h4 {
                font-size: 1.1rem;
            }
            
            .sidebar {
                width: 100%;
                max-width: 280px;
            }
            
            .nav-link {
                padding: 0.75rem 1rem;
            }
            
            .menu-title {
                padding: 0.75rem 1rem 0.25rem;
            }
        }
        
        @media (max-width: 374.98px) {
            .content-wrapper {
                padding: 0.5rem;
            }
            
            .welcome-card {
                padding: 1rem;
            }
            
            .stat-card {
                padding: 0.75rem;
            }
            
            .card-header,
            .card-body {
                padding: 0.75rem;
            }
            
            .top-navbar {
                height: 60px;
            }
            
            .top-navbar .h4 {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Sidebar -->
    <nav class="sidebar d-flex flex-column" id="sidebar">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="logo d-flex align-items-center text-decoration-none">
                <i class="fas fa-cube me-2"></i>
                <span>{{ __('messages.admin_panel') }}</span>
            </a>
            
            <div class="menu-title">{{ __('messages.main_menu') }}</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                       href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home me-3"></i>
                        <span>{{ __('messages.dashboard') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="fas fa-project-diagram me-3"></i>
                        <span>{{ __('messages.projects') }}</span>
                        <span class="badge bg-primary ms-auto">12</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="fas fa-users me-3"></i>
                        <span>{{ __('messages.team_members') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="fas fa-tasks me-3"></i>
                        <span>{{ __('messages.tasks') }}</span>
                        <span class="badge bg-warning ms-auto">5</span>
                    </a>
                </li>
            </ul>
            
            <div class="menu-title">{{ __('messages.analytics') }}</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="fas fa-chart-line me-3"></i>
                        <span>{{ __('messages.analytics') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="fas fa-file-alt me-3"></i>
                        <span>{{ __('messages.reports') }}</span>
                    </a>
                </li>
            </ul>
            
            <div class="menu-title">{{ __('messages.account') }}</div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" 
                       href="{{ route('admin.profile.show') }}">
                        <i class="fas fa-user-circle me-3"></i>
                        <span>{{ __('messages.profile') }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="#">
                        <i class="fas fa-cog me-3"></i>
                        <span>{{ __('messages.settings') }}</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer mt-auto">
            <a class="nav-link d-flex align-items-center text-danger" href="{{ route('admin.logout') }}">
                <i class="fas fa-sign-out-alt me-3"></i>
                <span>{{ __('messages.logout') }}</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-lg-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="h4 mb-0">@yield('page-title', __('messages.dashboard'))</h1>
            </div>
            
            <div class="d-flex align-items-center gap-2 gap-md-3">
                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-globe me-2"></i>
                        <span class="d-none d-sm-inline">{{ is_rtl() ? 'العربية' : 'English' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a></li>
                        <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">🇸🇦 العربية</a></li>
                    </ul>
                </div>
                
                <!-- Notifications -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 280px;">
                        <li class="dropdown-header">{{ __('messages.notifications') }}</li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>{{ __('messages.new_project_created') }}</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.task_deadline_approaching') }}</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-check-circle me-2"></i>{{ __('messages.project_completed') }}</a></li>
                    </ul>
                </div>
                
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-2"></i>
                        <span class="d-none d-md-inline">{{ session('admin_user.name', 'Admin') }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.profile.show') }}"><i class="fas fa-user me-2"></i>{{ __('messages.profile') }}</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>{{ __('messages.settings') }}</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('messages.logout') }}</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Content -->
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        }
        
        function closeSidebar() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        }
        
        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);
        
        // Close sidebar on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeSidebar();
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 991) closeSidebar();
        });
        
        // Auto-close sidebar on navigation (mobile)
        document.addEventListener('click', function(e) {
            if (e.target.matches('.sidebar a[href]') && window.innerWidth <= 991) {
                setTimeout(closeSidebar, 100);
            }
        });
    </script>
</body>
</html>