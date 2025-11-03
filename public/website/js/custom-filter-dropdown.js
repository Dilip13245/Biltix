/**
 * Initialize custom filter dropdown
 * @param {string} selectId - ID of the hidden select element
 * @param {string} wrapperId - ID of the wrapper div
 * @param {string} btnId - ID of the button element
 * @param {string} optionsId - ID of the options container
 */
function initCustomDropdown(selectId, wrapperId, btnId, optionsId) {
    const wrapper = document.getElementById(wrapperId);
    const btn = document.getElementById(btnId);
    const options = document.getElementById(optionsId);
    const select = document.getElementById(selectId);
    
    if (!wrapper || !btn || !options || !select) return;
    
    // Toggle dropdown
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const isActive = wrapper.classList.contains('active');
        
        // Close all other dropdowns
        document.querySelectorAll('.custom-filter-dropdown.active').forEach(d => d.classList.remove('active'));
        
        if (!isActive) {
            wrapper.classList.add('active');
        }
    });
    
    // Handle option selection
    options.querySelectorAll('.custom-filter-option').forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const text = this.textContent;
            
            // Update button text
            btn.textContent = text;
            
            // Update select value
            select.value = value;
            
            // Update selected state
            options.querySelectorAll('.custom-filter-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            // Close dropdown
            wrapper.classList.remove('active');
            
            // Trigger change event
            select.dispatchEvent(new Event('change'));
        });
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!wrapper.contains(e.target)) {
            wrapper.classList.remove('active');
        }
    });
}

/**
 * Initialize all custom filter dropdowns on page
 * Automatically finds all elements with .custom-filter-dropdown class
 */
function initAllCustomDropdowns() {
    document.querySelectorAll('.custom-filter-dropdown').forEach(wrapper => {
        const wrapperId = wrapper.id;
        const btn = wrapper.querySelector('.custom-filter-btn');
        const options = wrapper.querySelector('.custom-filter-options');
        const select = wrapper.querySelector('select');
        
        if (!btn || !options || !select || !wrapperId) return;
        
        const btnId = btn.id || `${wrapperId}_btn`;
        const optionsId = options.id || `${wrapperId}_options`;
        const selectId = select.id || `${wrapperId}_select`;
        
        // Set IDs if not present
        if (!btn.id) btn.id = btnId;
        if (!options.id) options.id = optionsId;
        if (!select.id) select.id = selectId;
        
        initCustomDropdown(selectId, wrapperId, btnId, optionsId);
    });
}

// Auto-initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllCustomDropdowns);
} else {
    initAllCustomDropdowns();
}
