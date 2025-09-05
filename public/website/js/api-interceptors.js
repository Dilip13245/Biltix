// API Interceptors for common functionality
class ApiInterceptors {
    static beforeRequest(config) {
        // Add loading spinner
        this.showLoading();
        
        // Add auth token if available
        const token = sessionStorage.getItem('token');
        console.log('Token from sessionStorage:', token ? 'exists' : 'missing');
        if (token) {
            config.headers['token'] = ApiEncryption.isEncryptionEnabled() 
                ? ApiEncryption.encrypt(token) 
                : token;
            console.log('Token added to headers');
        }
        
        // Encrypt API key if encryption enabled
        if (ApiEncryption.isEncryptionEnabled()) {
            config.headers['api-key'] = ApiEncryption.encrypt(config.headers['api-key']);
        }
        
        // Add user_id to data if available
        const user = JSON.parse(sessionStorage.getItem('user') || '{}');
        if (user.id && config.data) {
            config.data.user_id = user.id;
            console.log('User ID added to data:', user.id);
        }
        
        console.log('Final request config:', config);
        return config;
    }
    
    static afterResponse(response) {
        // Hide loading spinner
        this.hideLoading();
        
        // Log all API responses
        console.log('API Response:', response);
        
        // Handle common responses
        if (response.code === 401) {
            console.log('Got 401 response, calling handleUnauthorized');
            console.log('Current session data before clearing - user_id:', sessionStorage.getItem('user_id'), 'token:', sessionStorage.getItem('token'));
            this.handleUnauthorized();
            return;
        }
        
        if (response.code === 500) {
            this.showError(API_CONFIG.MESSAGES.SERVER_ERROR);
            return;
        }
        
        return response;
    }
    
    static handleUnauthorized() {
        console.log('API returned 401, redirecting to login');
        sessionStorage.clear();
        window.location.href = '/login';
    }
    
    static showLoading() {
        document.body.style.cursor = 'wait';
    }
    
    static hideLoading() {
        document.body.style.cursor = 'default';
    }
    
    static showError(message) {
        // Use your notification system
        console.error(message);
    }
}