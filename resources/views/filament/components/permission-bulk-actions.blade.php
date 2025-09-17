<div class="mb-4 p-4 border rounded-lg">
    <div class="flex flex-wrap gap-2">
        <div class="text-sm font-medium mb-2 w-full">
            {{ __('Quick Actions:') }}
        </div>
        <button type="button" 
                onclick="selectAllPermissions()" 
                class="inline-flex items-center px-3 py-2 text-xs font-medium border rounded-md hover:opacity-75">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ __('Select All') }}
        </button>
        
        <button type="button" 
                onclick="unselectAllPermissions()" 
                class="inline-flex items-center px-3 py-2 text-xs font-medium border rounded-md hover:opacity-75">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ __('Unselect All') }}
        </button>
        
        <button type="button" 
                onclick="selectPermissionsByType('create')" 
                class="inline-flex items-center px-3 py-2 text-xs font-medium border rounded-md hover:opacity-75">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            {{ __('All Create') }}
        </button>
        
        <button type="button" 
                onclick="selectPermissionsByType('view')" 
                class="inline-flex items-center px-3 py-2 text-xs font-medium border rounded-md hover:opacity-75">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
            {{ __('All View') }}
        </button>
        
        <button type="button" 
                onclick="selectPermissionsByType('update')" 
                class="inline-flex items-center px-3 py-2 text-xs font-medium border rounded-md hover:opacity-75">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            {{ __('All Update') }}
        </button>
        
        <button type="button" 
                onclick="selectPermissionsByType('delete')" 
                class="inline-flex items-center px-3 py-2 text-xs font-medium border rounded-md hover:opacity-75">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
            {{ __('All Delete') }}
        </button>
    </div>
</div>

<script>
function selectAllPermissions() {
    // Wait for DOM to be ready
    setTimeout(() => {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][wire\\:model*="permissions"]');
        checkboxes.forEach(checkbox => {
            if (!checkbox.checked) {
                checkbox.click();
            }
        });
    }, 100);
}

function unselectAllPermissions() {
    setTimeout(() => {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][wire\\:model*="permissions"]');
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                checkbox.click();
            }
        });
    }, 100);
}

function selectPermissionsByType(type) {
    setTimeout(() => {
        const checkboxes = document.querySelectorAll('input[type="checkbox"][wire\\:model*="permissions"]');
        checkboxes.forEach(checkbox => {
            const label = checkbox.closest('label') || checkbox.parentElement.querySelector('span');
            if (label) {
                const labelText = label.textContent.toLowerCase();
                if (labelText.includes(type) && !checkbox.checked) {
                    checkbox.click();
                }
            }
        });
    }, 100);
}
</script>