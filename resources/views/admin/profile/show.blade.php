@extends('admin.layout.app')

@section('title', __('messages.profile'))
@section('page-title', __('messages.profile'))

@section('content')
<!-- Profile Header -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="profile-avatar">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(session('admin_user.name', 'Admin')) }}&size=80&background=f58d2e&color=fff" 
                         alt="{{ __('messages.profile') }}" class="rounded-circle">
                </div>
            </div>
            <div class="col">
                <h3 class="mb-1">{{ session('admin_user.name', 'Admin User') }}</h3>
                <p class="text-muted mb-2">{{ __('messages.super_administrator') }}</p>
                <div class="d-flex gap-3 text-sm text-muted">
                    <span><i class="fas fa-calendar me-1"></i>{{ __('messages.joined') }} {{ __('messages.january_2024') }}</span>
                    <span><i class="fas fa-clock me-1"></i>{{ __('messages.last_active') }} {{ __('messages.today') }}</span>
                </div>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>{{ __('messages.edit_profile') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Profile Content -->
<div class="row">
    <!-- Contact Information -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-address-card me-2"></i>{{ __('messages.contact_information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="contact-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="contact-icon me-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <label class="form-label mb-1">{{ __('messages.email_address') }}</label>
                            <div class="fw-medium">admin@biltix.com</div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-item mb-3">
                    <div class="d-flex align-items-center">
                        <div class="contact-icon me-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <label class="form-label mb-1">{{ __('messages.phone_number') }}</label>
                            <div class="fw-medium">+1 (555) 123-4567</div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="d-flex align-items-center">
                        <div class="contact-icon me-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <label class="form-label mb-1">{{ __('messages.location') }}</label>
                            <div class="fw-medium">{{ __('messages.new_york_usa') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>{{ __('messages.quick_stats') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="stat-item text-center">
                            <div class="stat-icon mx-auto mb-2">
                                <i class="fas fa-project-diagram"></i>
                            </div>
                            <h4 class="mb-1">25</h4>
                            <p class="text-muted mb-0">{{ __('messages.projects') }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item text-center">
                            <div class="stat-icon mx-auto mb-2">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="mb-1">156</h4>
                            <p class="text-muted mb-0">{{ __('messages.users') }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item text-center">
                            <div class="stat-icon mx-auto mb-2">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <h4 class="mb-1">89</h4>
                            <p class="text-muted mb-0">{{ __('messages.reports') }}</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-item text-center">
                            <div class="stat-icon mx-auto mb-2">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h4 class="mb-1">342</h4>
                            <p class="text-muted mb-0">{{ __('messages.tasks') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Account Details & Activity -->
    <div class="col-lg-8">
        <!-- Account Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-circle me-2"></i>{{ __('messages.account_details') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.full_name') }}</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user me-2 text-muted"></i>
                            <span class="fw-medium">{{ session('admin_user.name', 'Admin User') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.user_id') }}</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-hashtag me-2 text-muted"></i>
                            <span class="fw-medium">#001</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.role_permissions') }}</label>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary">
                                <i class="fas fa-crown me-1"></i>{{ __('messages.super_admin') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.account_status') }}</label>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>{{ __('messages.active') }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.member_since') }}</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-calendar-plus me-2 text-muted"></i>
                            <span class="fw-medium">{{ __('messages.january_1_2024') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label">{{ __('messages.last_login') }}</label>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-sign-in-alt me-2 text-muted"></i>
                            <span class="fw-medium">{{ __('messages.today_at_2_30_pm') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>{{ __('messages.recent_activity') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="activity-list">
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon me-3">
                            <i class="fas fa-sign-in-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ __('messages.logged_into_admin_panel') }}</h6>
                            <p class="text-muted mb-1">{{ __('messages.successfully_authenticated') }}</p>
                            <small class="text-muted">2 {{ __('messages.hours_ago') }}</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon me-3 bg-success">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ __('messages.created_new_project') }}</h6>
                            <p class="text-muted mb-1">{{ __('messages.added_residential_complex') }}</p>
                            <small class="text-muted">1 {{ __('messages.day_ago') }}</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start mb-3">
                        <div class="activity-icon me-3 bg-info">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ __('messages.updated_profile_information') }}</h6>
                            <p class="text-muted mb-1">{{ __('messages.modified_contact_details') }}</p>
                            <small class="text-muted">3 {{ __('messages.days_ago') }}</small>
                        </div>
                    </div>
                    
                    <div class="activity-item d-flex align-items-start">
                        <div class="activity-icon me-3 bg-warning">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ __('messages.generated_monthly_report') }}</h6>
                            <p class="text-muted mb-1">{{ __('messages.created_comprehensive_report') }}</p>
                            <small class="text-muted">1 {{ __('messages.week_ago') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-icon {
    width: 40px;
    height: 40px;
    background: rgba(245, 141, 46, 0.1);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
}

.activity-icon {
    width: 36px;
    height: 36px;
    background: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    flex-shrink: 0;
}

.activity-icon.bg-success { background: var(--success); }
.activity-icon.bg-info { background: var(--info); }
.activity-icon.bg-warning { background: var(--warning); }

.text-sm { font-size: 0.875rem; }
</style>
@endsection