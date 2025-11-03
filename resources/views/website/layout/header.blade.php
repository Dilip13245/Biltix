<!-- ============= Header Section ========================= -->
<style>
.status-badge-active {
    background: #E8F5E8;
    color: #2E7D32;
    padding: 10px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(76, 175, 80, 0.2);
}

.status-badge-completed {
    background: #E3F2FD;
    color: #1565C0;
    padding: 10px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(33, 150, 243, 0.2);
}

.status-badge-planning {
    background: #FFF3E0;
    color: #E65100;
    padding: 10px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(255, 152, 0, 0.2);
}

.status-badge-hold {
    background: #FFEBEE;
    color: #C62828;
    padding: 10px 16px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 8px rgba(244, 67, 54, 0.2);
}

.status-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.8;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>
<header class="project-header">
  <div class="container-fluid">
    <div class="row align-items-center">
      <div class="col-12 d-flex align-items-center justify-content-between gap-sm-2 flex-wrap">
        <div>
          <h1 class="project-title" id="headerProjectTitle">{{ __('messages.loading') }}...</h1>
          <div class="project-details d-flex gap-3 flex-wrap">
            <span class="detail-item d-flex align-items-center gap-2">
              <i class="fas fa-calendar-alt icon-start"></i>
              {{ __('messages.start') }}: <span id="headerStartDate">{{ __('messages.loading') }}...</span>
            </span>
            <span class="detail-item d-flex align-items-center gap-2">
              <i class="fas fa-flag icon-start"></i>
              {{ __('messages.end') }}: <span id="headerEndDate">{{ __('messages.loading') }}...</span>
            </span>
            <span class="detail-item d-flex align-items-center gap-2">
              <i class="fas fa-chart-line icon-start"></i>
              {{ __('messages.progress') }}: <span id="headerProgress">0%</span>
            </span>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2 Wsm_100">
          {{-- <div class="status-badge-active" id="headerStatusBadge">
            <span class="status-dot"></span>
            <span id="headerStatus">{{ __('messages.loading') }}...</span>
          </div> --}}
          

          
          <!-- Language Switcher -->
          <div class="language-switcher">
            <select onchange="window.location.href='{{ route('lang.switch', '') }}/'+this.value" class="form-select form-select-sm language-select" style="width: auto; min-width: 100px;">
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