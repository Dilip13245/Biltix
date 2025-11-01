// Simple Global Button Protection - Allow first click, block duplicates
(function() {
    'use strict';
    
    const clickCounts = new WeakMap();
    
    // Protect any button manually
    window.protectButton = function(btn) {
        if (!btn || btn.disabled) return false;
        btn.disabled = true;
        return true;
    };
    
    // Release button manually
    window.releaseButton = function(btn) {
        if (!btn) return;
        btn.disabled = false;
        clickCounts.delete(btn);
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
        
        // Disable after first click
        setTimeout(() => {
            btn.disabled = true;
        }, 10);
        
        // Auto-release after 3 seconds
        setTimeout(() => {
            btn.disabled = false;
            clickCounts.delete(btn);
        }, 3000);
        
    }, true);
    
})();
