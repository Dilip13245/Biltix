{{-- User Info Script - Updates header with user data --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    updateUserInfo();
});

function updateUserInfo() {
    const user = JSON.parse(sessionStorage.getItem('user') || '{}');
    
    if (user.name) {
        const userNameElement = document.getElementById('userName');
        if (userNameElement) {
            userNameElement.textContent = user.name;
        }
    }
}
</script>