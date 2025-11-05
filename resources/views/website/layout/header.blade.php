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

/* Language Dropdown - Custom Header Dropdown */
.custom-header-dropdown {
    position: relative;
    display: inline-block;
}

.custom-header-dropdown-btn {
    position: relative;
}

.custom-header-dropdown-btn::after {
    content: '';
    display: inline-block;
    margin-left: 8px;
    vertical-align: middle;
    border-top: 4px solid;
    border-right: 4px solid transparent;
    border-bottom: 0;
    border-left: 4px solid transparent;
    transition: transform 0.2s ease;
}

.custom-header-dropdown.active .custom-header-dropdown-btn::after {
    transform: rotate(180deg);
}

[dir="rtl"] .custom-header-dropdown-btn::after {
    margin-left: 0;
    margin-right: 8px;
}

.custom-header-dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    min-width: 100%;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    display: none;
    list-style: none;
    padding: 4px 0;
    margin: 0;
}

.custom-header-dropdown.active .custom-header-dropdown-menu {
    display: block;
}

.custom-header-dropdown-item {
    display: block;
    padding: 8px 16px;
    color: #212529;
    text-decoration: none;
    transition: background-color 0.15s ease;
    white-space: nowrap;
}

.custom-header-dropdown-item:hover {
    background-color: #f8f9fa;
    color: #212529;
}

.custom-header-dropdown-item:focus {
    background-color: #f8f9fa;
    color: #212529;
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
          

          
          <!-- Language Toggle - Custom Dropdown -->
          <div class="custom-header-dropdown" id="langDropdownWrapper">
            <button class="btn btn-outline-primary btn-sm custom-header-dropdown-btn" type="button"
              id="langDropdownBtn">
              <span id="currentLang">{{ is_rtl() ? 'العربية' : 'English' }}</span>
            </button>
            <ul class="custom-header-dropdown-menu" id="langDropdownMenu">
              <li><a class="custom-header-dropdown-item"
                  href="{{ route('lang.switch', 'en') }}">English</a></li>
              <li><a class="custom-header-dropdown-item"
                  href="{{ route('lang.switch', 'ar') }}">العربية</a></li>
            </ul>
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

<script>
// Custom Header Dropdowns Handler (Language only)
(function() {
    function initCustomHeaderDropdowns() {
        // Initialize Language Dropdown
        const langBtn = document.getElementById('langDropdownBtn');
        const langWrapper = document.getElementById('langDropdownWrapper');
        const langMenu = document.getElementById('langDropdownMenu');

        if (langBtn && langWrapper && langMenu) {
            langBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                const isActive = langWrapper.classList.contains('active');

                // Close all other language dropdowns (if any)
                document.querySelectorAll('.custom-header-dropdown.active').forEach(d => {
                    if (d !== langWrapper) {
                        d.classList.remove('active');
                    }
                });

                // Toggle current dropdown
                if (isActive) {
                    langWrapper.classList.remove('active');
                } else {
                    langWrapper.classList.add('active');
                }
            });
        }

        // Close dropdowns when clicking outside - use capture phase
        document.addEventListener('click', function(e) {
            // If clicking inside the language dropdown, don't close it
            if (langWrapper && langWrapper.contains(e.target)) {
                return;
            }

            // Close language dropdown if clicking outside
            if (langWrapper) {
                langWrapper.classList.remove('active');
            }
        }, true); // Capture phase - runs before bubbling phase
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCustomHeaderDropdowns);
    } else {
        initCustomHeaderDropdowns();
    }
})();
</script>