// API Helper Functions
class ApiHelpers {
    // Form data to object
    static formToObject(form) {
        const formData = new FormData(form);
        const obj = {};
        for (let [key, value] of formData.entries()) {
            obj[key] = value;
        }
        return obj;
    }
    
    // Handle API response with notifications
    static handleResponse(response, successCallback = null, errorCallback = null) {
        if (response.code === 200) {
            this.showSuccess(response.message || 'Success');
            if (successCallback) successCallback(response.data);
        } else {
            this.showError(response.message || 'Error occurred');
            if (errorCallback) errorCallback(response);
        }
    }
    
    // Smart message handler - uses backend message or fallback
    static showApiMessage(response, type = 'success', fallbackMessage = null) {
        const message = response.message || fallbackMessage || 'Operation completed';
        
        switch(type) {
            case 'success':
                this.showSuccess(message);
                break;
            case 'error':
                this.showError(message);
                break;
            case 'warning':
                this.showWarning(message);
                break;
            case 'info':
                this.showInfo(message);
                break;
        }
    }
    
    // Show success notification
    static showSuccess(message) {
        if (typeof toastr !== 'undefined') {
            toastr.success(message);
        } else {
            alert(message);
        }
    }
    
    // Show error notification
    static showError(message) {
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }
    
    // Show warning notification
    static showWarning(message) {
        if (typeof toastr !== 'undefined') {
            toastr.warning(message);
        } else {
            alert(message);
        }
    }
    
    // Show info notification
    static showInfo(message) {
        if (typeof toastr !== 'undefined') {
            toastr.info(message);
        } else {
            alert(message);
        }
    }
    
    // Validate required fields
    static validateForm(form, requiredFields) {
        const data = this.formToObject(form);
        const missing = requiredFields.filter(field => !data[field]);
        
        if (missing.length > 0) {
            this.showError(`Required fields: ${missing.join(', ')}`);
            return false;
        }
        return true;
    }
    
    // Format date for API
    static formatDate(date) {
        return new Date(date).toISOString().split('T')[0];
    }
    
    // Get user from session
    static getCurrentUser() {
        return JSON.parse(sessionStorage.getItem('user') || '{}');
    }
    
    // Check if user is logged in
    static isLoggedIn() {
        return !!sessionStorage.getItem('token');
    }
}

window.ApiHelpers = ApiHelpers;