@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Team Members')

@section('content')
    <div class="content-header border-0 shadow-none mb-4 d-flex align-items-center justify-content-between gap-2 flex-wrap">
        <div>
            <h2>{{ __('messages.team_members') }}</h2>
            <p>{{ __('messages.view_team_details') }}</p>
        </div>
        <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap">
            <form class="serchBar position-relative serchBar2">
                <input class="form-control " type="search" placeholder="{{ __('messages.search_members') }}"
                    aria-label="Search">
                <span class="search_icon"><img src="{{ asset('website/images/icons/search.svg') }}" alt="search"></span>
            </form>
            <!-- Filter Button -->
            <button class="btn filter-btn d-flex align-items-center  px-3 py-2 bg4">
                <svg width="17" height="14" viewBox="0 0 17 14" class="{{ margin_end(2) }}" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M0.512337 0.715625C0.718587 0.278125 1.15609 0 1.64046 0H15.1405C15.6248 0 16.0623 0.278125 16.2686 0.715625C16.4748 1.15313 16.4123 1.66875 16.1061 2.04375L10.3905 9.02812V13C10.3905 13.3781 10.178 13.725 9.83734 13.8938C9.49671 14.0625 9.09359 14.0281 8.79046 13.8L6.79046 12.3C6.53734 12.1125 6.39046 11.8156 6.39046 11.5V9.02812L0.671712 2.04063C0.368587 1.66875 0.302962 1.15 0.512337 0.715625Z"
                        fill="#374151" />
                </svg>
                <span class="text-black">>{{ __('messages.filter') }}</span>
            </button>
        </div>
    </div>
    <section class="px-md-4">
        <div class="container-fluid">
            <div class="row g-4 ">
                <div class="col-xxl-3 col-xl-4  col-lg-6 col-md-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="0s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.total_members') }}</div>
                                <div class="stat-value">36</div>
                            </div>
                            <span class="ms-auto stat-icon bg1">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M4.5 0C5.16304 0 5.79893 0.263392 6.26777 0.732233C6.73661 1.20107 7 1.83696 7 2.5C7 3.16304 6.73661 3.79893 6.26777 4.26777C5.79893 4.73661 5.16304 5 4.5 5C3.83696 5 3.20107 4.73661 2.73223 4.26777C2.26339 3.79893 2 3.16304 2 2.5C2 1.83696 2.26339 1.20107 2.73223 0.732233C3.20107 0.263392 3.83696 0 4.5 0ZM16 0C16.663 0 17.2989 0.263392 17.7678 0.732233C18.2366 1.20107 18.5 1.83696 18.5 2.5C18.5 3.16304 18.2366 3.79893 17.7678 4.26777C17.2989 4.73661 16.663 5 16 5C15.337 5 14.7011 4.73661 14.2322 4.26777C13.7634 3.79893 13.5 3.16304 13.5 2.5C13.5 1.83696 13.7634 1.20107 14.2322 0.732233C14.7011 0.263392 15.337 0 16 0ZM0 9.33438C0 7.49375 1.49375 6 3.33437 6H4.66875C5.16562 6 5.6375 6.10938 6.0625 6.30312C6.02187 6.52812 6.00313 6.7625 6.00313 7C6.00313 8.19375 6.52812 9.26562 7.35625 10C7.35 10 7.34375 10 7.33437 10H0.665625C0.3 10 0 9.7 0 9.33438ZM12.6656 10C12.6594 10 12.6531 10 12.6438 10C13.475 9.26562 13.9969 8.19375 13.9969 7C13.9969 6.7625 13.975 6.53125 13.9375 6.30312C14.3625 6.10625 14.8344 6 15.3313 6H16.6656C18.5063 6 20 7.49375 20 9.33438C20 9.70312 19.7 10 19.3344 10H12.6656ZM7 7C7 6.20435 7.31607 5.44129 7.87868 4.87868C8.44129 4.31607 9.20435 4 10 4C10.7956 4 11.5587 4.31607 12.1213 4.87868C12.6839 5.44129 13 6.20435 13 7C13 7.79565 12.6839 8.55871 12.1213 9.12132C11.5587 9.68393 10.7956 10 10 10C9.20435 10 8.44129 9.68393 7.87868 9.12132C7.31607 8.55871 7 7.79565 7 7ZM4 15.1656C4 12.8656 5.86562 11 8.16562 11H11.8344C14.1344 11 16 12.8656 16 15.1656C16 15.625 15.6281 16 15.1656 16H4.83437C4.375 16 4 15.6281 4 15.1656Z"
                                        fill="#4477C4" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4  col-lg-6 col-md-6 col-md-6 col-12 wow fadeInUp" data-wow-delay=".4s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.site_engineers') }}</div>
                                <div class="stat-value text-success">18</div>
                            </div>
                            <span class="ms-auto stat-icon bg-green1">
                                <svg width="20" height="16" viewBox="0 0 20 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3 4C3 2.93913 3.42143 1.92172 4.17157 1.17157C4.92172 0.421427 5.93913 0 7 0C8.06087 0 9.07828 0.421427 9.82843 1.17157C10.5786 1.92172 11 2.93913 11 4C11 5.06087 10.5786 6.07828 9.82843 6.82843C9.07828 7.57857 8.06087 8 7 8C5.93913 8 4.92172 7.57857 4.17157 6.82843C3.42143 6.07828 3 5.06087 3 4ZM0 15.0719C0 11.9937 2.49375 9.5 5.57188 9.5H8.42813C11.5063 9.5 14 11.9937 14 15.0719C14 15.5844 13.5844 16 13.0719 16H0.928125C0.415625 16 0 15.5844 0 15.0719ZM19.5312 5.53125L15.5312 9.53125C15.2375 9.825 14.7625 9.825 14.4719 9.53125L12.4719 7.53125C12.1781 7.2375 12.1781 6.7625 12.4719 6.47188C12.7656 6.18125 13.2406 6.17813 13.5312 6.47188L15 7.94063L18.4688 4.46875C18.7625 4.175 19.2375 4.175 19.5281 4.46875C19.8188 4.7625 19.8219 5.2375 19.5281 5.52812L19.5312 5.53125Z"
                                        fill="#16A34A" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4  col-lg-6 col-md-6 col-md-6 col-12 wow fadeInUp" data-wow-delay=".8s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.contractors') }}</div>
                                <div class="stat-value orange_color">12</div>
                            </div>
                            <span class="ms-auto stat-icon bg2">
                                <svg width="18" height="16" viewBox="0 0 18 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M18 16H0V0H18V16Z" stroke="#E5E7EB" />
                                    <path
                                        d="M8 1C7.44688 1 7 1.44687 7 2V2.07188V5.18437C7 5.35938 6.85938 5.5 6.68437 5.5C6.57187 5.5 6.46563 5.44062 6.40938 5.34062L4.90938 2.71875C2.59375 3.85938 1 6.24375 1 9V11H17V8.925C16.9719 6.2 15.3844 3.85 13.0906 2.71875L11.5906 5.34062C11.5344 5.44062 11.4281 5.5 11.3156 5.5C11.1406 5.5 11 5.35938 11 5.18437V2.07188V2C11 1.44687 10.5531 1 10 1H8ZM0.51875 12C0.23125 12 0 12.2312 0 12.5188C0 12.6656 0.0625 12.8062 0.18125 12.8906C0.859375 13.3875 3.49375 15 9 15C14.5063 15 17.1406 13.3875 17.8188 12.8906C17.9375 12.8031 18 12.6656 18 12.5188C18 12.2312 17.7688 12 17.4813 12H0.51875Z"
                                        fill="#F58D2E" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4  col-lg-6 col-md-6 col-md-6 col-12 wow fadeInUp" data-wow-delay="1.2s">
                    <div class="card h-100 B_shadow">
                        <div class="card-body d-flex align-items-center p-md-4">
                            <div>
                                <div class="small_tXt fw-medium">{{ __('messages.consultants') }}</div>
                                <div class="stat-value text-blue">6</div>
                            </div>
                            <span class="ms-auto stat-icon bg-blue1">
                                <svg width="14" height="16" viewBox="0 0 14 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_861_3263)">
                                        <path
                                            d="M7 8C5.93913 8 4.92172 7.57857 4.17157 6.82843C3.42143 6.07828 3 5.06087 3 4C3 2.93913 3.42143 1.92172 4.17157 1.17157C4.92172 0.421427 5.93913 0 7 0C8.06087 0 9.07828 0.421427 9.82843 1.17157C10.5786 1.92172 11 2.93913 11 4C11 5.06087 10.5786 6.07828 9.82843 6.82843C9.07828 7.57857 8.06087 8 7 8ZM6.53438 11.225L5.95312 10.2563C5.75313 9.92188 5.99375 9.5 6.38125 9.5H7H7.61562C8.00313 9.5 8.24375 9.925 8.04375 10.2563L7.4625 11.225L8.50625 15.0969L9.63125 10.5063C9.69375 10.2531 9.9375 10.0875 10.1906 10.1531C12.3813 10.7031 14 12.6844 14 15.0406C14 15.5719 13.5687 16 13.0406 16H8.92188C8.85625 16 8.79688 15.9875 8.74063 15.9656L8.75 16H5.25L5.25938 15.9656C5.20312 15.9875 5.14062 16 5.07812 16H0.959375C0.43125 16 0 15.5687 0 15.0406C0 12.6812 1.62188 10.7 3.80938 10.1531C4.0625 10.0906 4.30625 10.2563 4.36875 10.5063L5.49375 15.0969L6.5375 11.225H6.53438Z"
                                            fill="#9333EA" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_861_3263">
                                            <path d="M0 0H14V16H0V0Z" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row  wow fadeInUp" data-wow-delay="0.9s">
                <div class="col-12 mt-4">
                    <div class="card B_shadow">
                        <div class="card-body py-md-4 px-0">
                            <div class="snag-list">
                                <div class="items_listing mb-4 px-md-4 px-2 pt-2">
                                    <h5 class="fw-semibold black_color">{{ __('messages.team_directory') }}</h5>
                                </div>
                                <!-- ------team-member-item------------ -->
                                <div
                                    class="team-member-item items_listing d-flex  gap-3 mb-4 px-md-4 px-2 pt-2 justify-content-between align-items-center flex-column flex-sm-row">
                                    <div class="d-flex align-items-center gap-3 align-self-start">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=48&amp;h=48&amp;fit=crop&amp;crop=face"
                                            alt="team-avatar" class="team-avatar">
                                        <div>
                                            <h5 class="fw-semibold fs14">John Smith</h5>
                                            <p class="text-muted small  fs14">{{ __('messages.project_manager') }}</p>
                                            <p class="text-muted small fs12">BuildCorp Construction</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 align-self-end">
                                        <span class="badge badge1">{{ __('messages.active') }}</span>
                                        <span class="badge bg2 orange_color">Contractor</span>
                                        <a href="#"><i class="fas fa-chevron-right text-muted"></i></a>
                                    </div>
                                </div>
                                <div
                                    class="team-member-item items_listing d-flex  gap-3 mb-4 px-md-4 px-2 pt-2 justify-content-between align-items-center flex-column flex-sm-row">
                                    <div class="d-flex align-items-center gap-3 align-self-start">
                                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=48&h=48&fit=crop&crop=face"
                                            alt="team-avatar" class="team-avatar">
                                        <div>
                                            <h5 class="fw-semibold fs14">Sarah Johnson</h5>
                                            <p class="text-muted small  fs14">Structural Engineer</p>
                                            <p class="text-muted small fs12">TechConsult Engineering</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 align-self-end">
                                        <span class="badge badge1">{{ __('messages.active') }}</span>
                                        <span class="badge bg-blue1">Contractor</span>
                                        <a href="#"><i class="fas fa-chevron-right text-muted"></i></a>
                                    </div>
                                </div>
                                <div
                                    class="team-member-item items_listing d-flex  gap-3 mb-4 px-md-4 px-2 pt-2 justify-content-between align-items-center flex-column flex-sm-row">
                                    <div class="d-flex align-items-center gap-3 align-self-start">
                                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=48&h=48&fit=crop&crop=face"
                                            alt="team-avatar" class="team-avatar">
                                        <div>
                                            <h5 class="fw-semibold fs14">Mike Chen</h5>
                                            <p class="text-muted small  fs14">{{ __('messages.site_supervisor') }}</p>
                                            <p class="text-muted small fs12">BuildCorp Construction</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 align-self-end">
                                        <span class="badge badge1">{{ __('messages.active') }}</span>
                                        <span class="badge bg2 orange_color">Contractor</span>
                                        <a href="#"><i class="fas fa-chevron-right text-muted"></i></a>
                                    </div>
                                </div>
                                <div
                                    class="team-member-item items_listing d-flex  gap-3 mb-4 px-md-4 px-2 pt-2 justify-content-between align-items-center flex-column flex-sm-row">
                                    <div class="d-flex align-items-center gap-3 align-self-start">
                                        <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=48&h=48&fit=crop&crop=face  "
                                            alt="team-avatar" class="team-avatar">
                                        <div>
                                            <h5 class="fw-semibold fs14">Lisa Wang</h5>
                                            <p class="text-muted small  fs14">Architect</p>
                                            <p class="text-muted small fs12">DesignStudio Architects</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 align-self-end">
                                        <span class="badge badge4">Away</span>
                                        <span class="badge bg2 orange_color">Contractor</span>
                                        <a href="#"><i class="fas fa-chevron-right text-muted"></i></a>
                                    </div>
                                </div>
                                <div
                                    class="team-member-item items_listing d-flex  gap-3 mb-4 px-md-4 px-2 pt-2 justify-content-between align-items-center flex-column flex-sm-row">
                                    <div class="d-flex align-items-center gap-3 align-self-start">
                                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=48&amp;h=48&amp;fit=crop&amp;crop=face"
                                            alt="team-avatar" class="team-avatar">
                                        <div>
                                            <h5 class="fw-semibold fs14">David Brown</h5>
                                            <p class="text-muted small  fs14">{{ __('messages.safety_officer') }}</p>
                                            <p class="text-muted small fs12">SafetyFirst Corps</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3 align-self-end">
                                        <span class="badge badge1">{{ __('messages.active') }}</span>
                                        <span class="badge bg2 orange_color">Contractor</span>
                                        <a href="#"><i class="fas fa-chevron-right text-muted"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search functionality
            const searchInput = document.querySelector('input[type="search"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const memberItems = document.querySelectorAll('.team-member-item');

                    memberItems.forEach(item => {
                        const memberName = item.querySelector('h5').textContent.toLowerCase();
                        const memberRole = item.querySelector('.text-muted.small.fs14').textContent
                            .toLowerCase();
                        const memberCompany = item.querySelector('.text-muted.small.fs12')
                            .textContent.toLowerCase();

                        if (memberName.includes(searchTerm) || memberRole.includes(searchTerm) ||
                            memberCompany.includes(searchTerm)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }

            // Filter button functionality
            const filterBtn = document.querySelector('.filter-btn');
            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    alert(
                        'Filter options:\n\n• By {{ __('messages.role') }} (Engineer, Manager, etc.)\n• By Company\n• By Status (Active, Away)\n• By Department\n\nAdvanced filtering would be implemented here.');
                });
            }

            // Team member detail view
            const memberLinks = document.querySelectorAll('.team-member-item a');
            memberLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const memberItem = this.closest('.team-member-item');
                    const name = memberItem.querySelector('h5').textContent;
                    const role = memberItem.querySelector('.text-muted.small.fs14').textContent;
                    const company = memberItem.querySelector('.text-muted.small.fs12').textContent;
                    const status = memberItem.querySelector('.badge').textContent;

                    alert(
                        `Team Member Details:\n\nName: ${name}\n{{ __('messages.role') }}: ${role}\nCompany: ${company}\nStatus: ${status}\n\nDetailed profile view would open here with:\n• {{ __('messages.contact') }} information\n• Project assignments\n• Performance metrics\n• Work schedule`);
                });
            });
        });
    </script>

@endsection
