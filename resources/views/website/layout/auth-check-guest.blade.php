{{-- Guest Auth Check - Only for login/register pages --}}
<script>
// Redirect authenticated users away from guest pages
document.addEventListener('DOMContentLoaded', function() {
    // Small delay to ensure logout has cleared session
    setTimeout(() => {
        const userId = sessionStorage.getItem('user_id') || localStorage.getItem('user_id');
        const token = sessionStorage.getItem('token') || localStorage.getItem('token');
        
        if (userId && token) {
            // User is logged in, redirect to dashboard
            window.location.replace('/dashboard');
        }
    }, 100);
});
</script>