{{-- Auth Check Script - Include in all protected pages --}}
<script>
// Check authentication status on page load
document.addEventListener('DOMContentLoaded', function() {
    // Only check auth on protected pages
    const currentPath = window.location.pathname;
    const publicPaths = ['/login', '/register', '/forgot-password', '/'];
    
    if (!publicPaths.includes(currentPath)) {
        checkAuthStatus();
    }
});

function checkAuthStatus() {
    const userId = sessionStorage.getItem('user_id');
    const token = sessionStorage.getItem('token');
    
    if (!userId || !token) {
        // No session data, redirect to login
        sessionStorage.clear();
        localStorage.clear();
        window.location.replace('/login');
        return;
    }
    

}

// Auto logout function
function logout() {
    if (confirm('Are you sure you want to logout?')) {
        fetch('/auth/logout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            sessionStorage.clear();
            toastr.success('Logged out successfully');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1000);
        })
        .catch(error => {
            console.error('Logout failed:', error);
            // Force logout even if API fails
            sessionStorage.clear();
            window.location.href = '/login';
        });
    }
}

// Session timeout handler
let sessionTimeout;
function resetSessionTimeout() {
    clearTimeout(sessionTimeout);
    // Set timeout for 2 hours (7200000 ms)
    sessionTimeout = setTimeout(() => {
        toastr.warning('Session expired. Please login again.');
        setTimeout(() => {
            sessionStorage.clear();
            window.location.href = '/login';
        }, 2000);
    }, 7200000);
}

// Reset timeout on user activity
document.addEventListener('click', resetSessionTimeout);
document.addEventListener('keypress', resetSessionTimeout);
document.addEventListener('scroll', resetSessionTimeout);

// Initialize session timeout
resetSessionTimeout();
</script>