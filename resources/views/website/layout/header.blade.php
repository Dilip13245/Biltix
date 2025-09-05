<!-- ============= Header Section ========================= -->
<header class="project-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-12 d-flex align-items-center justify-content-between gap-sm-2 flex-wrap">
        <div>
          <h1 class="project-title">{{ __('messages.riverside_commercial_complex') }}</h1>
          <div class="project-details d-flex gap-3 flex-wrap">
            <span class="detail-item d-flex align-items-center gap-2">
              <i class="fas fa-calendar-alt icon-start"></i>
              {{ __('messages.start') }}: {{ __('messages.jan_15_2024') }}
            </span>
            <span class="detail-item d-flex align-items-center gap-2">
              <i class="fas fa-flag icon-start"></i>
              {{ __('messages.end') }}: {{ __('messages.dec_20_2024') }}
            </span>
            <span class="detail-item d-flex align-items-center gap-2">
              <i class="fas fa-chart-line icon-start"></i>
              {{ __('messages.progress') }}: 67%
            </span>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2 Wsm_100">
          <div class="status-badge bg2">
            <span class="status-dot"></span>
            {{ __('messages.active') }}
          </div>
          

          
          <!-- Language Switcher -->
          <div class="language-switcher">
            <select onchange="window.location.href='{{ route('lang.switch', '') }}/'+this.value" class="form-select form-select-sm" style="width: auto; min-width: 100px;">
              <option value="en" {{ !is_rtl() ? 'selected' : '' }}>{{ __('messages.english') }}</option>
              <option value="ar" {{ is_rtl() ? 'selected' : '' }}>{{ __('messages.arabic') }}</option>
            </select>
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