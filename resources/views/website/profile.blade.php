<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.my_profile') }}</title>
    <link rel="icon" href="{{ asset('website/images/icons/logo.svg') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ bootstrap_css() }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('website/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('website/css/responsive.css') }}" />
</head>
<body>
    <div class="content_wraper F_poppins">
        <!-- Header -->
        <header class="project-header">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-12 d-flex align-items-center justify-content-between gap-2">
                        <a class="navbar-brand" href="{{ route('dashboard') }}">
                            <img src="{{ asset('website/images/icons/logo.svg') }}" alt="logo" class="img-fluid">
                            <span class="Head_title fw-bold ms-3 fs24 d-none d-lg-inline-block">{{ __('auth.my_profile') }}</span>
                        </a>
                        <div class="d-flex align-items-center justify-content-end gap-md-4 gap-3 w-100 flex-wrap">
                            <!-- Language Toggle -->
                            <div class="dropdown">
                                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-globe me-2"></i>
                                    <span>{{ is_rtl() ? 'العربية' : 'English' }}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'ar') }}">🇸🇦 العربية</a></li>
                                </ul>
                            </div>
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center gap-2 gap-md-3" type="button" data-bs-toggle="dropdown">
                                    <img src="{{ asset('website/images/icons/avtar.svg') }}" alt="user img" class="User_iMg">
                                    <span class="text-end">
                                        <h6 class="fs14 fw-medium black_color">John Smith</h6>
                                        <p class="Gray_color fs12 fw-normal">{{ __('auth.consultant') }}</p>
                                    </span>
                                    <img src="{{ asset('website/images/icons/arrow-up.svg') }}" alt="arrow" class="arrow">
                                </a>
                                <ul class="dropdown-menu {{ is_rtl() ? 'dropdown-menu-start' : 'dropdown-menu-end' }}">
                                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('website.profile') }}">
                                        <i class="fas fa-user"></i> {{ __('auth.my_profile') }}
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('auth.logout') }}
                                    </a></li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Profile Content -->
        <section class="Dashboard_sec">
            <div class="container-fluid">
                <!-- Profile Header -->
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 mt-3 mt-md-4 wow fadeInUp flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">{{ __('auth.my_profile') }}</h5>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn orange_btn py-2">
                            <i class="fas fa-edit me-2"></i>Edit Profile
                        </button>
                        <button class="btn btn-outline-secondary py-2">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Info Card -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="card stat-card">
                            <div class="card-body d-flex align-items-center p-4">
                                <div class="d-flex align-items-center gap-4 w-100">
                                    <div class="position-relative">
                                        <img src="{{ asset('website/images/icons/avtar.svg') }}" alt="Profile" class="User_iMg" style="width: 80px; height: 80px;">
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white" style="width: 20px; height: 20px;"></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 class="fw-bold mb-1 black_color">Peter Parker</h4>
                                        <p class="Gray_color fs14 mb-1">{{ __('auth.consultant') }}</p>
                                        <p class="Gray_color fs12 mb-0"><i class="fas fa-building me-2"></i>Summit Construction Co.</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-success px-3 py-2"><i class="fas fa-check me-1"></i>Active</span>
                                        <span class="badge bg-info px-3 py-2"><i class="fas fa-calendar me-1"></i>2023</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp" data-wow-delay="0s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('auth.employees') }}</div>
                                    <div class="stat-value">92</div>
                                </div>
                                <span class="ms-auto stat-icon bg1"><i class="fas fa-users"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp" data-wow-delay=".4s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">{{ __('auth.team_members') }}</div>
                                    <div class="stat-value">15</div>
                                </div>
                                <span class="ms-auto stat-icon bg2"><i class="fas fa-user-friends"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp" data-wow-delay=".8s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">Active Projects</div>
                                    <div class="stat-value">8</div>
                                </div>
                                <span class="ms-auto stat-icon"><i class="fas fa-project-diagram"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-3 col-xxl-2 cutom_col wow fadeInUp" data-wow-delay="1.2s">
                        <div class="card stat-card h-100">
                            <div class="card-body d-flex align-items-center">
                                <div>
                                    <div class="small_tXt">Completed</div>
                                    <div class="stat-value">24</div>
                                </div>
                                <span class="ms-auto stat-icon bg4"><i class="fas fa-trophy"></i></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact & Activity Section -->
                <div class="row g-4">
                    <div class="col-12 col-lg-6 wow fadeInUp" data-wow-delay="0s">
                        <div class="card project-card h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-address-card me-2 text-primary"></i>Contact Information</h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="stat-icon"><i class="fas fa-phone"></i></span>
                                            <div>
                                                <div class="fw-medium">+1 (88) 452 - 203</div>
                                                <small class="text-muted">{{ __('auth.mobile_number') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="stat-icon bg4"><i class="fas fa-envelope"></i></span>
                                            <div>
                                                <div class="fw-medium">peter.parker@summit.com</div>
                                                <small class="text-muted">{{ __('auth.email_id') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="stat-icon bg2"><i class="fas fa-map-marker-alt"></i></span>
                                            <div>
                                                <div class="fw-medium">New York, USA</div>
                                                <small class="text-muted">Location</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-6 wow fadeInUp" data-wow-delay=".4s">
                        <div class="card project-card h-100">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-chart-line me-2 text-success"></i>Recent Activity</h6>
                                <div class="timeline">
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="stat-icon bg1" style="width: 35px; height: 35px; font-size: 14px;"><i class="fas fa-check"></i></span>
                                        <div>
                                            <div class="fw-medium small">Completed Downtown Office inspection</div>
                                            <small class="text-muted">2 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="stat-icon bg2" style="width: 35px; height: 35px; font-size: 14px;"><i class="fas fa-file"></i></span>
                                        <div>
                                            <div class="fw-medium small">Uploaded new project plans</div>
                                            <small class="text-muted">5 hours ago</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-3 mb-3">
                                        <span class="stat-icon" style="width: 35px; height: 35px; font-size: 14px;"><i class="fas fa-users"></i></span>
                                        <div>
                                            <div class="fw-medium small">Added 3 new team members</div>
                                            <small class="text-muted">1 day ago</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start gap-3">
                                        <span class="stat-icon bg4" style="width: 35px; height: 35px; font-size: 14px;"><i class="fas fa-star"></i></span>
                                        <div>
                                            <div class="fw-medium small">Received 5-star project rating</div>
                                            <small class="text-muted">3 days ago</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/js/custom.js') }}"></script>
</body>
</html>