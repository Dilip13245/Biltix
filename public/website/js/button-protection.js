// Simple Global Button Protection - Allow first click, block duplicates
(function() {
    'use strict';
    
    const clickCounts = new WeakMap();
    const originalContents = new WeakMap();
    
    // Helper function to extract text from button (excluding icons)
    function getButtonText(btn) {
        const clone = btn.cloneNode(true);
        const icons = clone.querySelectorAll('i, svg, img');
        icons.forEach(icon => icon.remove());
        return clone.textContent.trim() || 'Loading...';
    }
    
    // Protect any button manually
    window.protectButton = function(btn) {
        if (!btn || btn.disabled) return false;
        
        // Store original content if not already stored
        if (!originalContents.has(btn)) {
            originalContents.set(btn, btn.innerHTML);
        }
        
        btn.disabled = true;
        
        // Add loader if button doesn't already have one
        if (!btn.innerHTML.includes('fa-spinner')) {
            const buttonText = getButtonText(btn);
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + buttonText;
        }
        
        return true;
    };
    
    // Release button manually
    window.releaseButton = function(btn) {
        if (!btn) return;
        btn.disabled = false;
        clickCounts.delete(btn);
        
        // Restore original content if stored
        if (originalContents.has(btn)) {
            btn.innerHTML = originalContents.get(btn);
            originalContents.delete(btn);
        }
    };
    
    // Auto-protect on click - allow first, block rest
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        
        // Get click count
        const count = clickCounts.get(btn) || 0;
        
        // If already clicked once, block
        if (count > 0 && !btn.disabled) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            return false;
        }
        
        // Increment click count
        clickCounts.set(btn, count + 1);
        
        // Store original content before modifying
        if (!originalContents.has(btn)) {
            originalContents.set(btn, btn.innerHTML);
        }
        
        // Disable after first click
        setTimeout(() => {
            if (btn && !btn.disabled) {
                btn.disabled = true;
                
                // Add loader if button doesn't already have one
                if (!btn.innerHTML.includes('fa-spinner')) {
                    const buttonText = getButtonText(btn);
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>' + buttonText;
                }
            }
        }, 10);
        
        // Auto-release after 1.5 seconds
        setTimeout(() => {
            if (btn) {
                btn.disabled = false;
                clickCounts.delete(btn);
                
                // Restore original content if stored
                if (originalContents.has(btn)) {
                    btn.innerHTML = originalContents.get(btn);
                    originalContents.delete(btn);
                }
            }
        }, 1500);
        
    }, true);
    
})();
