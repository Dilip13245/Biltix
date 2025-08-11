@extends('admin.layout.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-card">
            <div class="welcome-content">
                <h2 class="welcome-title">Welcome back, {{ session('admin_user.name', 'Admin') }}! 👋</h2>
                <p class="welcome-subtitle">Here's what's happening with your projects today.</p>
            </div>
            <div class="welcome-stats">
                <div class="welcome-stat-item">
                    <span class="welcome-stat-number">{{ $stats['total_projects'] ?? 0 }}</span>
                    <span class="welcome-stat-label">Total Projects</span>
                </div>
                <div class="welcome-stat-item">
                    <span class="welcome-stat-number">{{ $stats['active_projects'] ?? 0 }}</span>
                    <span class="welcome-stat-label">Active</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-primary">
            <div class="stat-card-icon">
                <i class="fas fa-project-diagram"></i>
            </div>
            <div class="stat-card-content">
                <h3 class="stat-number">{{ $stats['total_projects'] ?? 0 }}</h3>
                <p class="stat-label">Total Projects</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% from last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-success">
            <div class="stat-card-icon">
                <i class="fas fa-play-circle"></i>
            </div>
            <div class="stat-card-content">
                <h3 class="stat-number">{{ $stats['active_projects'] ?? 0 }}</h3>
                <p class="stat-label">Active Projects</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+8% from last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-info">
            <div class="stat-card-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-card-content">
                <h3 class="stat-number">{{ $stats['total_users'] ?? 0 }}</h3>
                <p class="stat-label">Total Users</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5% from last month</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-card-warning">
            <div class="stat-card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-card-content">
                <h3 class="stat-number">{{ $stats['pending_tasks'] ?? 0 }}</h3>
                <p class="stat-label">Pending Tasks</p>
                <div class="stat-trend">
                    <i class="fas fa-arrow-down"></i>
                    <span>-3% from last month</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row g-4">
    <!-- Recent Projects -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-project-diagram me-2"></i>Recent Projects
                    </h5>
                    <button class="btn btn-primary btn-sm no-loading">
                        <i class="fas fa-eye me-2"></i>View All
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="project-list">
                    @if(isset($recent_projects) && count($recent_projects) > 0)
                        @foreach($recent_projects as $project)
                        <div class="project-item">
                            <div class="project-info">
                                <div class="project-avatar">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="project-details">
                                    <h6 class="project-name">{{ $project['name'] ?? 'Project Name' }}</h6>
                                    <p class="project-meta">Created 2 days ago • 5 team members</p>
                                </div>
                            </div>
                            <div class="project-status">
                                <span class="status-badge status-{{ $project['status'] ?? 'active' }}">
                                    {{ ucfirst($project['status'] ?? 'active') }}
                                </span>
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $project['progress'] ?? 75 }}%"></div>
                                    </div>
                                    <span class="progress-text">{{ $project['progress'] ?? 75 }}%</span>
                                </div>
                            </div>
                            <div class="project-actions">
                                <button class="btn btn-sm btn-outline-primary no-loading">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary no-loading">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="project-item">
                            <div class="project-info">
                                <div class="project-avatar">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="project-details">
                                    <h6 class="project-name">Sample Construction Project</h6>
                                    <p class="project-meta">Created 2 days ago • 5 team members</p>
                                </div>
                            </div>
                            <div class="project-status">
                                <span class="status-badge status-active">Active</span>
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 75%"></div>
                                    </div>
                                    <span class="progress-text">75%</span>
                                </div>
                            </div>
                            <div class="project-actions">
                                <button class="btn btn-sm btn-outline-primary no-loading">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary no-loading">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        <div class="project-item">
                            <div class="project-info">
                                <div class="project-avatar">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div class="project-details">
                                    <h6 class="project-name">Residential Complex</h6>
                                    <p class="project-meta">Created 1 week ago • 8 team members</p>
                                </div>
                            </div>
                            <div class="project-status">
                                <span class="status-badge status-pending">Pending</span>
                                <div class="progress-wrapper">
                                    <div class="progress">
                                        <div class="progress-bar" style="width: 45%"></div>
                                    </div>
                                    <span class="progress-text">45%</span>
                                </div>
                            </div>
                            <div class="project-actions">
                                <button class="btn btn-sm btn-outline-primary no-loading">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary no-loading">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <button class="quick-action-btn">
                        <div class="action-icon bg-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="action-content">
                            <h6>New Project</h6>
                            <p>Create a new construction project</p>
                        </div>
                    </button>
                    
                    <button class="quick-action-btn">
                        <div class="action-icon bg-success">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="action-content">
                            <h6>Add User</h6>
                            <p>Invite new team member</p>
                        </div>
                    </button>
                    
                    <button class="quick-action-btn">
                        <div class="action-icon bg-info">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="action-content">
                            <h6>Generate Report</h6>
                            <p>Create project analytics report</p>
                        </div>
                    </button>
                    
                    <button class="quick-action-btn">
                        <div class="action-icon bg-warning">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div class="action-content">
                            <h6>Settings</h6>
                            <p>Configure system preferences</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon bg-success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Task completed</strong> in Project Alpha</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-info">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>New user</strong> John Doe joined</p>
                            <span class="activity-time">4 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>Deadline approaching</strong> for Beta Project</p>
                            <span class="activity-time">6 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="activity-item">
                        <div class="activity-icon bg-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="activity-content">
                            <p><strong>New project</strong> Gamma created</p>
                            <span class="activity-time">1 day ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

