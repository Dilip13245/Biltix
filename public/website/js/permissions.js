// Frontend Permission System
class PermissionManager {
    constructor() {
        this.user = null;
        this.permissions = {};
        this.loadUserData();
    }
    
    loadUserData() {
        // Get user from session storage or localStorage
        const userData = sessionStorage.getItem('user') || localStorage.getItem('user');
        if (userData) {
            this.user = JSON.parse(userData);
            this.loadPermissions();
        }
    }
    
    loadPermissions() {
        if (!this.user || !this.user.role) return;
        
        // Load permissions from config (you can also get this from API)
        const rolePermissions = {
            'consultant': {
                'projects': ['create', 'edit', 'delete', 'view'],
                'tasks': ['create', 'assign', 'update', 'complete', 'delete', 'view'],
                'plans': ['upload', 'markup', 'annotate', 'view', 'delete'],
                'files': ['upload', 'download', 'delete', 'view'],
                'snags': ['create', 'assign', 'resolve', 'comment', 'view'],
                'inspections': ['create', 'conduct', 'approve', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['add', 'remove', 'assign', 'view']
            },
            'contractor': {
                'projects': ['create', 'edit', 'delete', 'view'],
                'tasks': ['create', 'assign', 'update_status', 'view'], // Limited
                'plans': ['upload', 'markup', 'annotate', 'view', 'delete'],
                'files': ['upload', 'download', 'delete', 'view'],
                'snags': ['create', 'assign', 'resolve', 'comment', 'view'],
                'inspections': ['create', 'conduct', 'approve', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['add', 'remove', 'assign', 'view']
            },
            'project_manager': {
                'projects': ['create', 'edit', 'delete', 'view'],
                'tasks': ['create', 'assign', 'update', 'complete', 'delete', 'view'],
                'plans': ['upload', 'markup', 'annotate', 'view', 'delete'],
                'files': ['upload', 'download', 'delete', 'view'],
                'snags': ['create', 'assign', 'resolve', 'comment', 'view'],
                'inspections': ['create', 'conduct', 'approve', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['add', 'remove', 'assign', 'view']
            },
            'site_engineer': {
                'projects': [], // No Access
                'tasks': ['view_assigned'], // View assigned only
                'plans': ['annotate', 'view'], // Limited
                'files': ['upload_logs', 'view'], // Limited
                'snags': ['create', 'view'], // Submit only
                'inspections': ['create', 'view'], // Submit only
                'daily_logs': ['create', 'edit', 'view'], // Full
                'team': ['view']
            },
            'stakeholder': {
                'projects': [], // No Access
                'tasks': ['view'], // View-only
                'plans': ['view'], // View-only
                'files': ['download', 'view'], // View-only
                'snags': ['view'], // View-only
                'inspections': ['view'], // View-only
                'daily_logs': ['view'], // View-only
                'team': ['view']
            }
        };
        
        this.permissions = rolePermissions[this.user.role] || {};
    }
    
    can(module, action) {
        if (!this.permissions[module]) return false;
        return this.permissions[module].includes(action);
    }
    
    cannot(module, action) {
        return !this.can(module, action);
    }
    
    hasRole(role) {
        if (!this.user) return false;
        if (Array.isArray(role)) {
            return role.includes(this.user.role);
        }
        return this.user.role === role;
    }
    
    // UI Helper methods
    showElement(selector) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => el.style.display = '');
    }
    
    hideElement(selector) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => el.style.display = 'none');
    }
    
    enableElement(selector) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => el.disabled = false);
    }
    
    disableElement(selector) {
        const elements = document.querySelectorAll(selector);
        elements.forEach(el => el.disabled = true);
    }
    
    // Apply permissions to UI elements
    applyPermissions() {
        // Hide/show elements based on permissions
        document.querySelectorAll('[data-permission]').forEach(element => {
            const permission = element.getAttribute('data-permission');
            const [module, action] = permission.split(':');
            
            if (this.cannot(module, action)) {
                element.style.display = 'none';
            }
        });
        
        // Disable buttons based on permissions
        document.querySelectorAll('[data-permission-disable]').forEach(element => {
            const permission = element.getAttribute('data-permission-disable');
            const [module, action] = permission.split(':');
            
            if (this.cannot(module, action)) {
                element.disabled = true;
                element.classList.add('disabled');
            }
        });
        
        // Show role-specific content
        document.querySelectorAll('[data-role]').forEach(element => {
            const roles = element.getAttribute('data-role').split(',');
            
            if (!this.hasRole(roles)) {
                element.style.display = 'none';
            }
        });
    }
}

// Global instance
window.permissions = new PermissionManager();

// Auto-apply permissions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (window.permissions.user) {
        window.permissions.applyPermissions();
    }
});

// Helper functions for easy access
window.userCan = function(module, action) {
    return window.permissions.can(module, action);
};

window.userCannot = function(module, action) {
    return window.permissions.cannot(module, action);
};

window.userHasRole = function(role) {
    return window.permissions.hasRole(role);
};