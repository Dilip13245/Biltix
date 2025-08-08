@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Notifications')

@section('content')
<div class="content-header border-0 shadow-none mb-4">
  <h2>Notifications & Support</h2>
  <p>Stay updated with project notifications and get help when needed</p>
</div>
<section class="px-md-4">
  <div class="container-fluid">
    <div class="row">
      <!-- Recent Notifications Section -->
      <div class="col-xl-6 mb-4 wow fadeInLeft" data-wow-delay="0.9s">
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
            Recent Notifications
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
                  <h6 class="mb-1">Weather Alert</h6>
                  <p class="mb-1 text-muted">Heavy rain expected this week, outdoor work
                    may be delayed</p>
                  <small class="text-muted">2 days ago</small>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <a href="#" class="text-primary text-decoration-none fw-medium">View All Notifications</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Help & Support Section -->
      <div class="col-xl-6 mb-4 wow fadeInRight" data-wow-delay="0.9s">
        <div class="card B_shadow h-100">
          <div class="border-bottom pb-3 px-md-4 px-2 py-3 py-md-4">
            <h5 class="fw-semibold black_color mb-3">
              <svg width="18" height="19" class="me-1" viewBox="0 0 18 19" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M9 1.9375C4.96055 1.9375 1.6875 5.21055 1.6875 9.25V10.6562C1.6875 11.1238 1.31133 11.5 0.84375 11.5C0.376172 11.5 0 11.1238 0 10.6562V9.25C0 4.27891 4.02891 0.25 9 0.25C13.9711 0.25 18 4.27891 18 9.25V14.316C18 16.0246 16.6148 17.4098 14.9027 17.4098L11.025 17.4062C10.7332 17.909 10.1883 18.25 9.5625 18.25H8.4375C7.50586 18.25 6.75 17.4941 6.75 16.5625C6.75 15.6309 7.50586 14.875 8.4375 14.875H9.5625C10.1883 14.875 10.7332 15.216 11.025 15.7188L14.9062 15.7223C15.6832 15.7223 16.3125 15.093 16.3125 14.316V9.25C16.3125 5.21055 13.0395 1.9375 9 1.9375ZM5.0625 7.5625H5.625C6.24727 7.5625 6.75 8.06523 6.75 8.6875V12.625C6.75 13.2473 6.24727 13.75 5.625 13.75H5.0625C3.82148 13.75 2.8125 12.741 2.8125 11.5V9.8125C2.8125 8.57148 3.82148 7.5625 5.0625 7.5625ZM12.9375 7.5625C14.1785 7.5625 15.1875 8.57148 15.1875 9.8125V11.5C15.1875 12.741 14.1785 13.75 12.9375 13.75H12.375C11.7527 13.75 11.25 13.2473 11.25 12.625V8.6875C11.25 8.06523 11.7527 7.5625 12.375 7.5625H12.9375Z"
                  fill="#4477C4" />
              </svg>
              Help &amp; Support
            </h5>
            <p class="text-muted">Need assistance? Send us a message and we'll get back to you.
            </p>
          </div>
          <div class="card-body p-md-4">
            <form>
              <div class="mb-3">
                <label for="fullName" class="form-label fw-medium">Full Name</label>
                <input type="text" class="form-control Input_control" id="fullName"
                  placeholder="Enter your full name">
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input type="email" class="form-control Input_control" id="email"
                  placeholder="Enter your email address">
              </div>

              <div class="mb-4">
                <label for="message" class="form-label fw-medium">Message</label>
                <textarea class="form-control Input_control" id="message" rows="4"
                  placeholder="Describe your issue or question in detail..."></textarea>
              </div>

              <button type="submit" class="btn orange_btn w-100">
                <i class="fas fa-paper-plane me-2"></i>Submit Request
              </button>
            </form>

            <div class="quick-help-links">
              <h6 class="fw-bold mb-3">Quick Help</h6>
              <a href="#"><i class="fas fa-question-circle"></i> Frequently Asked Questions</a>
              <a href="#"><i class="fas fa-file-alt"></i> User Documentation</a>
              <a href="#"><i class="fas fa-video"></i> Video Tutorials</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection