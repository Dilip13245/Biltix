<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ dir_class() }}">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Construction Project Dashboard">
    <meta name="keywords" content="HTML,CSS,XML,JavaScript">
    <meta name="author" content="John Doe">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#">{{ __('messages.action') }}</a></li>
                                    <li><a class="dropdown-item" href="#">{{ __('messages.another_action') }}</a>
                                    </li>
                                    <li><a class="dropdown-item"
                                            href="#">{{ __('messages.something_else_here') }}</a></li>
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
                <div class="d-flex justify-content-between align-items-center mb-3 mb-md-4 mt-3 mt-md-4 wow fadeInUp">
                    <h5 class="fw-bold mb-0">{{ __('messages.ongoing_projects') }}</h5>
                    <select class="form-select w-auto" id="statusFilter">
                        <option value="all">{{ __('messages.all_status') }}</option>
                        <option value="active">{{ __('messages.active') }}</option>
                        <option value="completed">{{ __('messages.completed') }}</option>
                    </select>
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
                    alert('Notification panel would open here showing recent updates.');
                });
            }
        });
    </script>

    <!-- =======================Main content End============================== -->



    <!-- ============= JavaScript Files ========================= -->
    <script src="{{ asset('website/bootstrap-5.3.1-dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('website/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('website/js/wow.js') }}"></script>
    <script src="{{ asset('website/js/custom.js') }}"></script>


</body>

</html>
