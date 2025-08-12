<!-- ============= Header Section ========================= -->
<header class="project-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-12 d-flex align-items-center justify-content-between gap-sm-2 flex-wrap">
        <div>
          <h1 class="project-title">Riverside Commercial Complex</h1>
          <div class="project-details">
            <span class="detail-item">
              <i class="fas fa-calendar-alt"></i>
              Start: Jan 15, 2024
            </span>
            <span class="detail-item">
              <i class="fas fa-flag"></i>
              End: Dec 20, 2024
            </span>
            <span class="detail-item">
              <i class="fas fa-chart-line"></i>
              Progress: 67%
            </span>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between gap-2 Wsm_100">
          <div class="status-badge bg2">
            <span class="status-dot"></span>
            Active
          </div>
          <!-- Language Switcher -->
          <div class="btn-group" role="group" aria-label="Language switcher">
            <a href="{{ route('locale.switch', 'en') }}" class="btn btn-outline-secondary btn-sm {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
            <a href="{{ route('locale.switch', 'ar') }}" class="btn btn-outline-secondary btn-sm {{ app()->getLocale() === 'ar' ? 'active' : '' }}">AR</a>
          </div>
          <!-- Mobile Menu Toggle Button -->
          <button class="mobile-menu-toggle d-lg-none" id="mobileMenuToggle">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</header>