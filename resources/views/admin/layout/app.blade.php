<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
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
        }
        
        .main-content {
            margin-inline-start: var(--sidebar-width);
            min-height: 100vh;
        }
        
        .top-navbar {
            height: var(--header-height);
            background: white;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .content-wrapper {
            padding: 2rem;
        }
        
        .nav-link {
            color: #6c757d;
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: all 0.2s;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background-color: #e3f2fd;
            color: #1976d2;
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
            color: #1976d2;
            text-decoration: none;
        }
        
        .logo:hover {
            color: #1976d2;
        }
        
        .sidebar-footer {
            margin-top: auto;
            padding: 1rem 0;
            border-top: 1px solid #e9ecef;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px solid #e9ecef;
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .welcome-card {
            background: linear-gradient(135deg, #1976d2 0%, #42a5f5 100%);
            color: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.5rem;
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
        }
        
        .badge {
            font-weight: 500;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
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
        <nav class="top-navbar d-flex align-items-center justify-content-between px-4">
            <div class="d-flex align-items-center">
                <button class="btn btn-light d-md-none me-3" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="h4 mb-0">@yield('page-title', __('messages.dashboard'))</h1>
            </div>
            
            <div class="d-flex align-items-center gap-3">
                <!-- Language Switcher -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
                    <button class="btn btn-light position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="dropdown-header">{{ __('messages.notifications') }}</li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i>{{ __('messages.new_project_created') }}</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.task_deadline_approaching') }}</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-check-circle me-2"></i>{{ __('messages.project_completed') }}</a></li>
                    </ul>
                </div>
                
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
            if (window.innerWidth > 768) closeSidebar();
        });
    </script>
</body>
</html>
