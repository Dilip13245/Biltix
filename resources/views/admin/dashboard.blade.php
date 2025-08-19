@extends('admin.layout.app')

@section('title', __('messages.dashboard'))
@section('page-title', __('messages.dashboard'))

@section('content')
    <!-- Welcome Card -->
    <div class="welcome-card">
        <h2 class="h3 mb-2">{{ __('messages.welcome_back', ['name' => session('admin_user.name', 'Admin')]) }}</h2>
        <p class="mb-0 opacity-75">{{ __('messages.whats_happening') }}</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="h2 mb-1 text-primary">24</h3>
                        <p class="mb-0 text-muted">{{ __('messages.total_projects') }}</p>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-project-diagram fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="h2 mb-1 text-success">18</h3>
                        <p class="mb-0 text-muted">{{ __('messages.active_projects') }}</p>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="h2 mb-1 text-warning">6</h3>
                        <p class="mb-0 text-muted">{{ __('messages.pending_reviews') }}</p>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h3 class="h2 mb-1 text-info">42</h3>
                        <p class="mb-0 text-muted">{{ __('messages.total_users') }}</p>
                    </div>
                    <div class="text-info">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Projects & Quick Actions -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('messages.recent_projects') }}</h5>
                    <a href="#" class="btn btn-primary btn-sm">{{ __('messages.view_all') }}</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">{{ __('messages.project_name') }}</th>
                                    <th class="border-0">{{ __('messages.status') }}</th>
                                    <th class="border-0">{{ __('messages.progress') }}</th>
                                    <th class="border-0">{{ __('messages.due_date') }}</th>
                                    <th class="border-0">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px; font-weight: 600;">
                                                DC
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ __('messages.downtown_office_complex') }}</h6>
                                                <small class="text-muted">{{ __('messages.commercial_building') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">{{ __('messages.active') }}</span></td>
                                    <td>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar" style="width: 75%"></div>
                                        </div>
                                        <small class="text-muted">75%</small>
                                    </td>
                                    <td>Dec 15, 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">{{ __('messages.view') }}</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info text-white rounded d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px; font-weight: 600;">
                                                RT
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ __('messages.residential_tower_a') }}</h6>
                                                <small class="text-muted">{{ __('messages.residential_complex') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">{{ __('messages.pending') }}</span></td>
                                    <td>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: 45%"></div>
                                        </div>
                                        <small class="text-muted">45%</small>
                                    </td>
                                    <td>Mar 20, 2025</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">{{ __('messages.view') }}</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success text-white rounded d-flex align-items-center justify-content-center me-3"
                                                style="width: 40px; height: 40px; font-weight: 600;">
                                                SM
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ __('messages.shopping_mall_renovation') }}</h6>
                                                <small class="text-muted">{{ __('messages.renovation_project') }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">{{ __('messages.completed') }}</span></td>
                                    <td>
                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                        <small class="text-muted">100%</small>
                                    </td>
                                    <td>Nov 30, 2024</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">{{ __('messages.view') }}</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>{{ __('messages.new_project') }}
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-users me-2"></i>{{ __('messages.add_team_member') }}
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-tasks me-2"></i>{{ __('messages.create_task') }}
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-file-alt me-2"></i>{{ __('messages.generate_report') }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('messages.recent_activity') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 32px; height: 32px; flex-shrink: 0;">
                            <i class="fas fa-check fa-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium">{{ __('messages.project_completed') }}</p>
                            <small class="text-muted">2 {{ __('messages.hours_ago') }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 32px; height: 32px; flex-shrink: 0;">
                            <i class="fas fa-plus fa-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium">{{ __('messages.new_team_member_added') }}</p>
                            <small class="text-muted">4 {{ __('messages.hours_ago') }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 32px; height: 32px; flex-shrink: 0;">
                            <i class="fas fa-exclamation-triangle fa-sm"></i>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-medium">{{ __('messages.task_deadline_approaching') }}</p>
                            <small class="text-muted">6 {{ __('messages.hours_ago') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
