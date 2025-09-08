// RTL Icon Spacing Fix - Clean JavaScript approach
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're in RTL mode
    if (document.documentElement.getAttribute('dir') === 'rtl') {
        
        // Find all FontAwesome icons
        const icons = document.querySelectorAll('.fas, .far, .fab, i.fas, i.far, i.fab');
        
        icons.forEach(function(icon) {
            // Skip if icon has explicit no-margin classes
            if (icon.classList.contains('me-0') || icon.classList.contains('mx-0') || icon.classList.contains('m-0')) {
                return;
            }
            
            // Check if icon is in a button with gap property
            const buttonParent = icon.closest('.orange_btn');
            if (buttonParent) {
                // Orange buttons use gap, don't add extra spacing
                return;
            }
            
            // Add consistent spacing for all other icons
            icon.style.marginLeft = '0.25rem';
            icon.style.marginRight = '0';
            icon.style.paddingLeft = '0';
            icon.style.paddingRight = '0';
        });
        
        console.log('RTL icon spacing applied to', icons.length, 'icons');
    }
});