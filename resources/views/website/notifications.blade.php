@extends('website.layout.app')

@section('title', 'Notifications')

@section('content')
<div class="content-header border-0 shadow-none mb-4">
  <h2>{{ __('messages.notifications') }}</h2>
  <p>{{ __('messages.stay_updated_notifications') }}</p>
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
            <div id="notifications-container" style="max-height: 500px; overflow-y: auto; padding-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}: 10px;">
              <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">{{ __('messages.loading') }}</span>
                </div>
                <p class="mt-2 text-muted">{{ __('messages.loading_notifications') }}...</p>
              </div>
            </div>
            <div class="text-center mt-3" id="view-all-btn" style="display: none;">
              <a href="#" onclick="showAllNotifications()" class="text-primary text-decoration-none fw-medium">View All Notifications</a>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</section>
<script>
let currentUserId = {{ auth()->id() ?? 1 }};
let currentPage = 1;
let hasMoreNotifications = true;

function getNotificationIcon(type) {
  const icons = {
    'G': 'fas fa-info-circle',
    'U': 'fas fa-exclamation-triangle', 
    'R': 'fas fa-bell',
    'S': 'fas fa-shield-alt',
    'P': 'fas fa-credit-card',
    'E': 'fas fa-calendar-alt',
    'M': 'fas fa-envelope',
    'A': 'fas fa-user-check'
  };
  return icons[type] || 'fas fa-bell';
}

function formatTimeAgo(dateString) {
  const date = new Date(dateString);
  const now = new Date();
  const diffInSeconds = Math.floor((now - date) / 1000);
  
  if (diffInSeconds < 60) return 'Just now';
  if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' minutes ago';
  if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
  return Math.floor(diffInSeconds / 86400) + ' days ago';
}

async function loadNotifications() {

  try {
    const response = await api.makeRequest('notifications/list', {
      user_id: currentUserId,
      limit: 10,
      page: currentPage
    });
    
    const container = document.getElementById('notifications-container');
    
    if (response.code === 200 && response.data && response.data.data && response.data.data.length > 0) {
      // Sort notifications by created_at (newest first)
      const notifications = response.data.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      
      let html = '';
      notifications.forEach(notification => {
        html += `
          <div class="notification-item notification-${notification.type}">
            <div class="d-flex align-items-start gap-3">
              <div class="notification-icon-wrapper icon-${notification.type}">
                <i class="${getNotificationIcon(notification.type)}"></i>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                  <h6 class="notification-title mb-0">${notification.title}</h6>
                  <small class="notification-time text-muted">${formatTimeAgo(notification.created_at)}</small>
                </div>
                <p class="notification-message mb-0 text-muted">${notification.message}</p>
              </div>
            </div>
          </div>
        `;
      });
      
      if (currentPage === 1) {
        container.innerHTML = html;
      } else {
        container.innerHTML += html;
      }
      
      // Check if there are more notifications
      hasMoreNotifications = notifications.length === 10;
      
      // Show load more button if there are more notifications
      if (hasMoreNotifications) {
        document.getElementById('view-all-btn').innerHTML = '<a href="#" onclick="loadMoreNotifications()" class="text-primary text-decoration-none fw-medium">Load More Notifications</a>';
        document.getElementById('view-all-btn').style.display = 'block';
      } else {
        document.getElementById('view-all-btn').style.display = 'none';
      }
    } else {
      if (currentPage === 1) {
        container.innerHTML = `
          <div class="text-center py-5">
            <div class="mb-3">
              <i class="fas fa-bell-slash text-muted" style="font-size: 48px; opacity: 0.5;"></i>
            </div>
            <h6 class="text-muted mb-2">{{ __('messages.no_notifications') }}</h6>
            <p class="text-muted small mb-0">{{ __('messages.no_notifications_desc') }}</p>
          </div>
        `;
      }
    }
  } catch (error) {
    console.error('Error loading notifications:', error);
    document.getElementById('notifications-container').innerHTML = 
      '<div class="text-center py-4"><p class="text-danger">{{ __('messages.error_loading_notifications') }}</p></div>';
  }
}

function loadMoreNotifications() {
  if (hasMoreNotifications && !window.loadingMore) {
    window.loadingMore = true;
    currentPage++;
    loadNotifications().finally(() => {
      window.loadingMore = false;
    });
  }
}

function showAllNotifications() {
  alert('All notifications page would open here.');
}

// Load notifications when page loads
document.addEventListener('DOMContentLoaded', function() {
  loadNotifications();
  
  // Add infinite scroll to notifications container
  const container = document.getElementById('notifications-container');
  container.addEventListener('scroll', function() {
    if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
      if (hasMoreNotifications) {
        loadMoreNotifications();
      }
    }
  });
});

// Add CSS for notification cards with RTL support
const style = document.createElement('style');
style.textContent = `
  .notification-item {
    padding: 16px;
    margin-bottom: 8px;
    border-radius: 12px;
    background: #ffffff;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }
  .notification-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #4477C4;
  }
  [dir="rtl"] .notification-item::before {
    left: auto;
    right: 0;
  }
  .notification-item:hover {
    background: #f8f9fa;
    box-shadow: 0 4px 12px rgba(68, 119, 196, 0.15);
    transform: translateY(-2px);
  }
  .notification-item.notification-G::before {
    background: linear-gradient(135deg, #4477C4, #5a8fd8);
  }
  .notification-item.notification-U::before {
    background: linear-gradient(135deg, #dc3545, #e85d75);
  }
  .notification-item.notification-R::before {
    background: linear-gradient(135deg, #ffc107, #ffcd39);
  }
  .notification-item.notification-S::before {
    background: linear-gradient(135deg, #28a745, #34ce57);
  }
  .notification-item.notification-P::before {
    background: linear-gradient(135deg, #6f42c1, #8a63d2);
  }
  .notification-item.notification-E::before {
    background: linear-gradient(135deg, #fd7e14, #ff922b);
  }
  .notification-item.notification-M::before {
    background: linear-gradient(135deg, #20c997, #2dd4aa);
  }
  .notification-item.notification-A::before {
    background: linear-gradient(135deg, #17a2b8, #1fc7d4);
  }
  .notification-icon-wrapper {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
  }
  .icon-G {
    background: linear-gradient(135deg, rgba(68, 119, 196, 0.1), rgba(90, 143, 216, 0.15));
    color: #4477C4;
  }
  .icon-U {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(232, 93, 117, 0.15));
    color: #dc3545;
  }
  .icon-R {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(255, 205, 57, 0.15));
    color: #ffc107;
  }
  .icon-S {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(52, 206, 87, 0.15));
    color: #28a745;
  }
  .icon-P {
    background: linear-gradient(135deg, rgba(111, 66, 193, 0.1), rgba(138, 99, 210, 0.15));
    color: #6f42c1;
  }
  .icon-E {
    background: linear-gradient(135deg, rgba(253, 126, 20, 0.1), rgba(255, 146, 43, 0.15));
    color: #fd7e14;
  }
  .icon-M {
    background: linear-gradient(135deg, rgba(32, 201, 151, 0.1), rgba(45, 212, 170, 0.15));
    color: #20c997;
  }
  .icon-A {
    background: linear-gradient(135deg, rgba(23, 162, 184, 0.1), rgba(31, 199, 212, 0.15));
    color: #17a2b8;
  }
  .notification-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
    line-height: 1.4;
  }
  .notification-message {
    font-size: 13px;
    line-height: 1.5;
    color: #6c757d;
  }
  .notification-time {
    font-size: 11px;
    font-weight: 500;
    color: #adb5bd;
    white-space: nowrap;
  }
  #notifications-container {
    scrollbar-width: thin;
    scrollbar-color: #4477C4 #f1f1f1;
  }
  #notifications-container::-webkit-scrollbar {
    width: 6px;
  }
  #notifications-container::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
  }
  #notifications-container::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #4477C4, #5a8fd8);
    border-radius: 3px;
  }
  #notifications-container::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #365a94, #4a7bc8);
  }
  @media (max-width: 768px) {
    .notification-item {
      padding: 12px;
    }
    .notification-icon-wrapper {
      width: 38px;
      height: 38px;
      font-size: 16px;
    }
    .notification-title {
      font-size: 13px;
    }
    .notification-message {
      font-size: 12px;
    }
  }
`;
document.head.appendChild(style);
</script>

@endsection