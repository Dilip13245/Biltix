{{-- User Info Script - Updates header with user data --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateUserInfo();
});

function updateUserInfo() {
    const userStr = sessionStorage.getItem('user') || localStorage.getItem('user') || '{}';
    const user = JSON.parse(userStr);
    
    if (user.name) {
        const userNameElement = document.getElementById('userName');
        if (userNameElement) {
            userNameElement.textContent = user.name;
        }
    }
}
</script>