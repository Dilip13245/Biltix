@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Notifications')

@section('content')
<div class="content-header border-0 shadow-none mb-4">
  <h2>Notifications</h2>
  <p>Stay updated with project notifications and important updates</p>
</div>
<section class="px-md-4">
  <div class="container-fluid">
    <div class="row">
      <!-- {{ __("messages.recent_notifications") }} Section -->
      <div class="col-12 mb-4 wow fadeInUp" data-wow-delay="0.3s">
        <div class="card B_shadow h-100">
          <h5 class="fw-semibold black_color border-bottom pb-3 px-md-4 px-2 py-3 py-md-4"><svg width="16"
              height="19" class="me-1" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_861_3899)">
                <g clip-path="url(#clip1_861_3899)">
                  <path
                    d="M7.8753 0.25C7.25304 0.25 6.7503 0.752734 6.7503 1.375V2.05C4.1839 2.57031 2.2503 4.84141 2.2503 7.5625V8.22344C2.2503 9.87578 1.6421 11.4719 0.545226 12.7094L0.285069 13.0012C-0.0102433 13.3316 -0.0805558 13.8063 0.0987411 14.2105C0.278038 14.6148 0.682335 14.875 1.1253 14.875H14.6253C15.0683 14.875 15.4691 14.6148 15.6519 14.2105C15.8347 13.8063 15.7609 13.3316 15.4655 13.0012L15.2054 12.7094C14.1085 11.4719 13.5003 9.8793 13.5003 8.22344V7.5625C13.5003 4.84141 11.5667 2.57031 9.0003 2.05V1.375C9.0003 0.752734 8.49757 0.25 7.8753 0.25ZM9.46788 17.5926C9.88976 17.1707 10.1253 16.5977 10.1253 16H7.8753H5.6253C5.6253 16.5977 5.86085 17.1707 6.28273 17.5926C6.7046 18.0145 7.27765 18.25 7.8753 18.25C8.47296 18.25 9.04601 18.0145 9.46788 17.5926Z"
                    fill="#4477C4" />
                </g>
              </g>
              <defs>
                <clipPath id="clip0_861_3899">
                  <rect width="15.75" height="18" fill="white" transform="translate(0 0.25)" />
                </clipPath>
                <clipPath id="clip1_861_3899">
                  <path d="M0 0.25H15.75V18.25H0V0.25Z" fill="white" />
                </clipPath>
              </defs>
            </svg>
            {{ __("messages.recent_notifications") }}
          </h5>
          <div class="card-body p-md-4">
            <div class="notification-card notification-info">
              <div class="d-flex align-items-start gap-3">
                <div class="notification-icon icon-info">
                  <i class="fas fa-info"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1">Project Update: Tower Construction Phase 2</h6>
                  <p class="mb-1 text-muted">Foundation work has been completed ahead of
                    schedule</p>
                  <small class="text-muted">2 hours ago</small>
                </div>
              </div>
            </div>

            <div class="notification-card notification-warning">
              <div class="d-flex align-items-start gap-3">
                <div class="notification-icon icon-warning">
                  <i class="fas fa-exclamation"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1">Document Review Required</h6>
                  <p class="mb-1 text-muted">Structural drawings need your review and
                    approval</p>
                  <small class="text-muted">4 hours ago</small>
                </div>
              </div>
            </div>

            <div class="notification-card notification-success">
              <div class="d-flex align-items-start gap-3">
                <div class="notification-icon icon-success">
                  <i class="fas fa-check"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1">Quality Inspection Passed</h6>
                  <p class="mb-1 text-muted">Electrical installation has passed quality
                    inspection</p>
                  <small class="text-muted">6 hours ago</small>
                </div>
              </div>
            </div>

            <div class="notification-card notification-secondary">
              <div class="d-flex align-items-start gap-3">
                <div class="notification-icon icon-secondary">
                  <i class="fas fa-calendar"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1">Meeting Scheduled</h6>
                  <p class="mb-1 text-muted">Weekly progress review meeting tomorrow at
                    10:00 AM</p>
                  <small class="text-muted">1 day ago</small>
                </div>
              </div>
            </div>

            <div class="notification-card notification-danger">
              <div class="d-flex align-items-start gap-3">
                <div class="notification-icon icon-danger">
                  <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="mb-1">{{ __("messages.weather") }} Alert</h6>
                  <p class="mb-1 text-muted">Heavy rain expected this week, outdoor work
                    may be delayed</p>
                  <small class="text-muted">2 days ago</small>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <a href="#" onclick="showAllNotifications()" class="text-primary text-decoration-none fw-medium">View All Notifications</a>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>
<script>
function showAllNotifications() {
  alert('All notifications page would open here.');
}
</script>

@endsection