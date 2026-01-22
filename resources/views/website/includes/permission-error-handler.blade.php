{{-- Permission Error Handler Component --}}
@if(session('permission_denied') || session('subscription_limit') || session('error'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        const errorMessage = '{{ session('error') }}';
        
        // Show toast notification
        if (typeof showToast === 'function') {
            showToast(errorMessage, 'error');
        } else if (typeof toastr !== 'undefined') {
            toastr.error(errorMessage);
        } else {
            alert(errorMessage);
        }
    }, 500);
});
</script>
@endif