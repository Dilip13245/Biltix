// Permanent Button Protection - Integrates with API calls
// Buttons stay disabled until API call completes (success or error)
(function() {
    'use strict';
    
    const activeButtons = new WeakMap(); // Track buttons with active API calls
    const originalContents = new WeakMap(); // Store original button HTML
    const activeApiCalls = new WeakMap(); // Track active API promises per button
    let currentActiveButton = null; // Track the button that triggered the current API call
    
    // Helper function to extract text from button (excluding icons)
    function getButtonText(btn) {
        const clone = btn.cloneNode(true);
        const icons = clone.querySelectorAll('i, svg, img');
        icons.forEach(icon => icon.remove());
        return clone.textContent.trim() || 'Loading...';
    }
    
    // Check if button should be protected
    function shouldProtectButton(btn) {
        // Only protect buttons with api-action-btn class
        if (!btn.classList.contains('api-action-btn')) {
            return false;
        }
        
        // Exclude buttons with data-no-protect attribute
        if (btn.hasAttribute('data-no-protect')) {
            return false;
        }
        
        // Exclude buttons that open modals
        if (btn.hasAttribute('data-bs-toggle') && btn.getAttribute('data-bs-toggle') === 'modal') {
            return false;
        }
        
        return true;
    }
    
    // Check if button is a form submit button
    function isFormSubmitButton(btn) {
        return btn.type === 'submit' || btn.hasAttribute('form');
    }
    
    // Protect button - disable and show loader
    function protectButton(btn) {
        if (!btn || btn.disabled) return false;
        
        // Store original content if not already stored
        if (!originalContents.has(btn)) {
            originalContents.set(btn, btn.innerHTML);
        }
        
        btn.disabled = true;
        activeButtons.set(btn, true);
        
        // Add loader if button doesn't already have one
        if (!btn.innerHTML.includes('fa-spinner')) {
            const buttonText = getButtonText(btn);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + buttonText;
        }
        
        return true;
    }
    
    // Release button - enable and restore original content
    function releaseButton(btn) {
        if (!btn) return;
        
        btn.disabled = false;
        activeButtons.delete(btn);
        activeApiCalls.delete(btn);
        
        // Clear current active button if it matches
        if (currentActiveButton === btn) {
            currentActiveButton = null;
        }
        
        // Restore original content if stored
        if (originalContents.has(btn)) {
            btn.innerHTML = originalContents.get(btn);
            originalContents.delete(btn);
        }
    }
    
    // Public API
    window.protectButton = protectButton;
    window.releaseButton = releaseButton;
    
    // Register button with active API call
    window.registerButtonApiCall = function(btn, promise) {
        // Use provided button or current active button
        const buttonToProtect = btn || currentActiveButton;
        if (!buttonToProtect) return;
        
        // Clear current active button
        currentActiveButton = null;
        
        // Protect the button immediately if not already protected
        if (!activeButtons.has(buttonToProtect)) {
            protectButton(buttonToProtect);
        }
        
        // Store the promise
        activeApiCalls.set(buttonToProtect, promise);
        
        // Release button when API call completes (success or error)
        promise
            .then(() => {
                // Small delay to ensure UI updates are visible
                setTimeout(() => {
                    releaseButton(buttonToProtect);
                }, 100);
            })
            .catch(() => {
                // Release on error too
                setTimeout(() => {
                    releaseButton(buttonToProtect);
                }, 100);
            });
    };
    
    // Get current active button (used by API client)
    window.getCurrentActiveButton = function() {
        return currentActiveButton;
    };
    
    // Auto-protect on click - only for api-action-btn buttons
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        
        // Only protect buttons with api-action-btn class
        if (!shouldProtectButton(btn)) {
            return;
        }
        
        // If button is already disabled or has active API call, prevent click
        if (btn.disabled || activeButtons.has(btn)) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            return false;
        }
        
        // Check if this is a form submit button
        const isSubmitBtn = isFormSubmitButton(btn);
        
        // For form submit buttons, allow the click to proceed normally
        // The form submit event will handle the protection
        if (isSubmitBtn) {
            // Set as current active button so API client can register it
            currentActiveButton = btn;
            // Don't prevent default or stop propagation - let form submit work
            // Protection will happen in the form submit handler
            return; // Exit early, let the form submit event handle it
        } else {
            // For non-form buttons, protect immediately
            protectButton(btn);
            
            // Set as current active button so API client can register it
            currentActiveButton = btn;
            
            // Clear current active button after a short delay if not used
            setTimeout(() => {
                if (currentActiveButton === btn && !activeApiCalls.has(btn)) {
                    // No API call was registered, release button
                    releaseButton(btn);
                    if (currentActiveButton === btn) {
                        currentActiveButton = null;
                    }
                }
            }, 100);
        }
        
        // Note: Button will be released when API call completes via registerButtonApiCall
        // If no API call is registered within 2 seconds, release the button (fallback for non-API actions)
        const fallbackTimeout = setTimeout(() => {
            // Only release if no active API call is registered
            if (activeApiCalls.has(btn)) {
                // API call is registered, don't release
                return;
            }
            // No API call registered, release button
            releaseButton(btn);
            if (currentActiveButton === btn) {
                currentActiveButton = null;
            }
        }, 2000);
        
        // Clear fallback if API call gets registered
        const checkInterval = setInterval(() => {
            if (activeApiCalls.has(btn)) {
                clearTimeout(fallbackTimeout);
                clearInterval(checkInterval);
            }
        }, 100);
        
        // Clean up check interval after 3 seconds
        setTimeout(() => {
            clearInterval(checkInterval);
        }, 3000);
        
    }, true);
    
    // Expose utility functions globally
    window.ButtonProtection = {
        protect: protectButton,
        release: releaseButton,
        registerApiCall: window.registerButtonApiCall,
        isProtected: function(btn) {
            return activeButtons.has(btn) || (btn && btn.disabled);
        }
    };
    
})();
