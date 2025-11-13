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

/* Notification Styles */
.notification-wrapper {
    position: relative;
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    width: 380px;
    max-width: calc(100vw - 20px);
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    z-index: 9999 !important;
    border: 1px solid #e9ecef;
}

/* LTR Layout */
[dir="ltr"] .notification-dropdown {
    right: 0;
}

/* RTL Layout */
[dir="rtl"] .notification-dropdown {
    left: 0;
}

@media (max-width: 768px) {
    [dir="ltr"] .notification-dropdown {
        left: 0;
        right: auto;
        width: calc(100vw - 20px);
        max-width: 350px;
    }

    [dir="rtl"] .notification-dropdown {
        right: 0;
        left: auto;
        width: calc(100vw - 20px);
        max-width: 350px;
    }
}

@media (max-width: 480px) {
    [dir="ltr"] .notification-dropdown {
        left: 0;
        right: auto;
        width: calc(100vw - 20px);
        max-width: 320px;
    }

    [dir="rtl"] .notification-dropdown {
        right: 0;
        left: auto;
        width: calc(100vw - 20px);
        max-width: 320px;
    }
}

.notification-header {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    border-radius: 12px 12px 0 0;
}

.notification-body {
    max-height: 300px;
    overflow-y: auto;
    padding: 0;
}

.notification-footer {
    padding: 12px 16px;
    border-top: 1px solid #f0f0f0;
    text-align: center;
    background: white;
    border-radius: 0 0 12px 12px;
}

.notification-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f8f9fa;
    cursor: pointer;
    transition: background-color 0.2s;
    word-wrap: break-word;
}

.notification-item:hover {
    background-color: #f8f9fa;
}

.notification-item.unread {
    background-color: #fff5f0;
    border-left: 3px solid #F58D2E;
}

[dir="rtl"] .notification-item.unread {
    border-left: none;
    border-right: 3px solid #F58D2E;
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
          
          <!-- Notification Icon -->
          <div class="notification-wrapper position-relative">
            <div class="position-relative MessageBOx text-center" style="cursor: pointer;"
              onclick="toggleNotifications()">
              <img src="{{ asset('website/images/icons/bell.svg') }}" alt="Bell"
                class="img-fluid notifaction-icon">
              <span class="fw-normal fs12 text-white orangebg" id="notificationCount"
                style="display: none;">0</span>
            </div>
            <div class="notification-dropdown" id="notificationDropdown" style="display: none;">
              <div class="notification-header">
                <span class="fw-bold">{{ __('messages.notifications') }}</span>
                <button class="btn btn-sm btn-danger api-action-btn"
                  onclick="deleteAllNotifications()"
                  style="font-size: 11px; padding: 4px 8px;">{{ __('messages.delete_all') }}</button>
              </div>
              <div class="notification-body" id="notificationList">
                <div class="text-center py-3">
                  <div class="spinner-border spinner-border-sm" role="status"></div>
                  <span class="ms-2">{{ __('messages.loading') }}...</span>
                </div>
              </div>
              <div class="notification-footer">
                <a href="#" onclick="viewAllNotifications(); return false;"
                  class="text-primary">{{ __('messages.view_all_notifications') }}</a>
              </div>
            </div>
          </div>

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

// Notification Functions
async function loadNotifications() {
    try {
        const response = await api.getNotifications({
            page: 1,
            limit: 50
        });

        if (response.code === 200 && response.data) {
            let notifications = Array.isArray(response.data) ? response.data : (response.data.notifications ||
                response.data.data || []);

            if (Array.isArray(notifications)) {
                // Filter out deleted notifications if deleted field exists
                notifications = notifications.filter(n => !n.deleted && !n.is_deleted);

                displayNotifications(notifications);
                const unreadCount = notifications.filter(n => !n.is_read).length;
                console.log('Total notifications:', notifications.length);
                console.log('Unread notifications:', unreadCount);
                updateNotificationCount(unreadCount);
            } else {
                displayNoNotifications();
            }
        } else {
            displayNoNotifications();
        }
    } catch (error) {
        console.error('Failed to load notifications:', error);
        displayNoNotifications();
    }
}

function displayNotifications(notifications) {
    const notificationList = document.getElementById('notificationList');
    if (!notifications || notifications.length === 0) {
        displayNoNotifications();
        return;
    }

    notificationList.innerHTML = '';
    notifications.forEach(notification => {
        const div = document.createElement('div');
        div.className = `notification-item ${!notification.is_read ? 'unread' : ''}`;
        div.onclick = () => markAsRead(notification.id);
        div.innerHTML = `
            <div class="d-flex align-items-start">
                <div class="flex-grow-1">
                    <div class="fw-medium mb-1">${notification.title || 'Notification'}</div>
                    <div class="small text-muted mb-1">${notification.message || notification.content || ''}</div>
                    <div style="font-size: 11px; color: #6c757d;">${formatNotificationTime(notification.created_at)}</div>
                </div>
                ${!notification.is_read ? '<div class="ms-2"><div class="rounded-circle" style="width: 8px; height: 8px; background: #F58D2E;"></div></div>' : ''}
            </div>
        `;
        notificationList.appendChild(div);
    });
}

function displayNoNotifications() {
    const notificationList = document.getElementById('notificationList');
    notificationList.innerHTML =
        '<div class="text-center py-4 text-muted"><i class="fas fa-bell-slash fa-2x mb-2 d-block"></i>{{ __('messages.no_notifications') }}</div>';
    updateNotificationCount(0);
}

window.toggleNotifications = function() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown.style.display === 'none' || dropdown.style.display === '') {
        dropdown.style.display = 'block';
        loadNotifications();
        document.addEventListener('click', closeNotificationsOutside);
    } else {
        dropdown.style.display = 'none';
        document.removeEventListener('click', closeNotificationsOutside);
    }
};

function closeNotificationsOutside(event) {
    const wrapper = document.querySelector('.notification-wrapper');
    if (!wrapper.contains(event.target)) {
        document.getElementById('notificationDropdown').style.display = 'none';
        document.removeEventListener('click', closeNotificationsOutside);
    }
}

function updateNotificationCount(count) {
    const countBadge = document.getElementById('notificationCount');
    if (countBadge) {
        if (count > 0) {
            countBadge.textContent = count > 99 ? '99+' : count;
            countBadge.style.display = 'block';
        } else {
            countBadge.style.display = 'none';
        }
    }
}

async function markAsRead(notificationId) {
    try {
        await api.markNotificationAsRead({
            notification_id: notificationId
        });
        loadNotifications();
    } catch (error) {
        console.error('Failed to mark notification as read:', error);
    }
}

window.deleteAllNotifications = async function() {
    try {
        await api.deleteAllNotifications();
        loadNotifications();
        if (typeof showToast !== 'undefined') {
            showToast('{{ __('messages.all_notifications_deleted') }}', 'success');
        } else if (typeof toastr !== 'undefined') {
            toastr.success('{{ __('messages.all_notifications_deleted') }}');
        }
    } catch (error) {
        console.error('Failed to delete all notifications:', error);
        if (typeof showToast !== 'undefined') {
            showToast('{{ __('messages.failed_to_delete_notifications') }}', 'error');
        } else if (typeof toastr !== 'undefined') {
            toastr.error('{{ __('messages.failed_to_delete_notifications') }}');
        }
    }
};

window.viewAllNotifications = function() {
    // Get project ID from URL
    function getProjectIdFromUrl() {
        const pathParts = window.location.pathname.split('/');
        const projectIndex = pathParts.indexOf('project');
        return projectIndex !== -1 && pathParts[projectIndex + 1] ? pathParts[projectIndex + 1] : 1;
    }
    
    const projectId = getProjectIdFromUrl();
    window.location.href = `/website/project/${projectId}/notifications`;
};

function formatNotificationTime(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return '{{ __('messages.just_now') }}';
    if (diffMins < 60) return `${diffMins} {{ __('messages.minutes_ago') }}`;
    if (diffHours < 24) return `${diffHours} {{ __('messages.hours_ago') }}`;
    if (diffDays < 7) return `${diffDays} {{ __('messages.days_ago') }}`;
    
    return date.toLocaleDateString('{{ app()->getLocale() }}', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Load notification count on page load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof api !== 'undefined') {
        loadNotifications();
    }
});
</script>