// Profile Image Sync - Automatically sync user profile image across all pages
(function() {
    'use strict';

    // Function to update all profile images on the page
    async function syncProfileImage() {
        try {
            // Check if API is available
            if (typeof api === 'undefined' || typeof api.getProfile !== 'function') {
                console.log('[ProfileSync] API not available yet, will retry...');
                return;
            }

            const response = await api.getProfile({});
            
            if (response.code === 200 && response.data && response.data.profile_image) {
                const profileImageUrl = response.data.profile_image;
                
                // Update all profile images on the page
                const profileImages = document.querySelectorAll('#profileImage, #headerProfileImage, .user-profile-image');
                
                profileImages.forEach(img => {
                    img.src = profileImageUrl;
                    img.onerror = function() {
                        // Fallback to default avatar
                        const defaultAvatar = img.id === 'profileImage' 
                            ? '/website/images/profile image.png' 
                            : '/website/images/icons/avatar.jpg';
                        this.src = defaultAvatar;
                    };
                });
                
                console.log('[ProfileSync] Profile images synced successfully');
            }
        } catch (error) {
            console.error('[ProfileSync] Failed to sync profile image:', error);
        }
    }

    // Auto-sync on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(syncProfileImage, 1000);
        });
    } else {
        setTimeout(syncProfileImage, 1000);
    }

    // Expose function globally for manual sync
    window.syncProfileImage = syncProfileImage;
})();
