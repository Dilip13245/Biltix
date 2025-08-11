@extends('admin.layout.app')

@section('title', 'Profile')
@section('page-title', 'My Profile')

@section('content')
<!-- Profile Header -->
<div class="profile-header mb-4">
    <div class="profile-cover">
        <div class="profile-info">
            <div class="profile-avatar">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name ?? 'Admin') }}&size=120&background=ff6b35&color=fff" 
                     alt="Profile" class="avatar-img">
                <div class="avatar-badge">
                    <i class="fas fa-crown"></i>
                </div>
            </div>
            <div class="profile-details">
                <h2 class="profile-name">{{ $admin->name ?? 'Admin User' }}</h2>
                <p class="profile-role">
                    <i class="fas fa-shield-alt me-2"></i>Super Administrator
                </p>
                <div class="profile-meta">
                    <span class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        Joined {{ $admin->created_at ? $admin->created_at->format('M Y') : 'Recently' }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-clock"></i>
                        Last active {{ $admin->last_login ? $admin->last_login->diffForHumans() : 'Recently' }}
                    </span>
                </div>
            </div>
            <div class="profile-actions">
                <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Profile
                </a>
                <button class="btn btn-outline-light">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="row g-4">
    <!-- Left Column -->
    <div class="col-lg-4">
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-address-card me-2"></i>Contact Information
                </h5>
            </div>
            <div class="card-body">
                <div class="contact-list">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <label>Email Address</label>
                            <span>{{ $admin->email ?? 'admin@biltix.com' }}</span>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <label>Phone Number</label>
                            <span>{{ $admin->phone ?? '+1 (555) 123-4567' }}</span>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <label>Location</label>
                            <span>{{ $admin->location ?? 'New York, USA' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Quick Stats
                </h5>
            </div>
            <div class="card-body">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-icon bg-primary">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <div class="stat-content">
                            <h4>25</h4>
                            <p>Projects</p>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h4>156</h4>
                            <p>Users</p>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-info">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="stat-content">
                            <h4>89</h4>
                            <p>Reports</p>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stat-content">
                            <h4>342</h4>
                            <p>Tasks</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right Column -->
    <div class="col-lg-8">
        <!-- Account Details -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2"></i>Account Details
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Full Name</label>
                            <div class="detail-value">
                                <i class="fas fa-user me-2"></i>
                                {{ $admin->name ?? 'Admin User' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>User ID</label>
                            <div class="detail-value">
                                <i class="fas fa-hashtag me-2"></i>
                                #{{ $admin->id ?? '001' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Role & Permissions</label>
                            <div class="detail-value">
                                <span class="role-badge">
                                    <i class="fas fa-crown me-2"></i>Super Admin
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Account Status</label>
                            <div class="detail-value">
                                <span class="status-badge status-active">
                                    <i class="fas fa-check-circle me-2"></i>Active
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Member Since</label>
                            <div class="detail-value">
                                <i class="fas fa-calendar-plus me-2"></i>
                                {{ $admin->created_at ? $admin->created_at->format('F j, Y') : 'January 1, 2024' }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="detail-item">
                            <label>Last Login</label>
                            <div class="detail-value">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                {{ $admin->last_login ? $admin->last_login->format('M j, Y g:i A') : 'Today at 2:30 PM' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Activity Timeline -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Recent Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="activity-timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Logged into admin panel</h6>
                            <p>Successfully authenticated and accessed dashboard</p>
                            <span class="timeline-time">2 hours ago</span>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Created new project</h6>
                            <p>Added "Residential Complex Phase 2" to project list</p>
                            <span class="timeline-time">1 day ago</span>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Updated profile information</h6>
                            <p>Modified contact details and preferences</p>
                            <span class="timeline-time">3 days ago</span>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Generated monthly report</h6>
                            <p>Created comprehensive project status report</p>
                            <span class="timeline-time">1 week ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

