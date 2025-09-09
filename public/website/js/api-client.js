// Global API Client
class ApiClient {
    constructor() {
        this.baseUrl = API_CONFIG.BASE_URL;
        this.apiKey = API_CONFIG.API_KEY;
    }

    async makeRequest(endpoint, data = {}, method = 'POST') {
        const config = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'api-key': this.apiKey,
                'language': document.documentElement.lang || 'en'
            },
            data: data
        };
        
        console.log('Making API request to:', `${this.baseUrl}/${endpoint}`);
        console.log('Request data:', data);
        
        // Apply interceptors
        const interceptorResult = ApiInterceptors.beforeRequest(config);
        
        // If interceptor returns null, don't make the request
        if (interceptorResult === null) {
            return { code: 401, message: 'No token available', data: {} };
        }
        
        try {
            const fetchOptions = {
                method: config.method,
                headers: config.headers
            };
            
            // Only add body for non-GET requests
            if (config.method !== 'GET') {
                fetchOptions.body = JSON.stringify(config.data);
            }
            
            const response = await fetch(`${this.baseUrl}/${endpoint}`, fetchOptions);
            
            console.log('Response status:', response.status);
            
            const result = await response.json();
            console.log('API Response:', result);
            
            // Don't throw error for HTTP status, let the API response handle it
            return ApiInterceptors.afterResponse(result) || result;
        } catch (error) {
            console.error('API Request failed:', error);
            ApiInterceptors.hideLoading();
            throw error;
        }
    }
    
    async makeFormDataRequest(endpoint, formData) {
        const config = {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'api-key': this.apiKey,
                'language': document.documentElement.lang || 'en'
                // Don't set Content-Type for FormData - browser will set it with boundary
            }
        };
        
        console.log('Making FormData API request to:', `${this.baseUrl}/${endpoint}`);
        
        // Apply interceptors for FormData
        ApiInterceptors.beforeFormDataRequest(config, formData);
        
        try {
            const response = await fetch(`${this.baseUrl}/${endpoint}`, {
                method: config.method,
                headers: config.headers,
                body: formData
            });
            
            console.log('Response status:', response.status);
            
            const result = await response.json();
            console.log('API Response:', result);
            
            return ApiInterceptors.afterResponse(result) || result;
        } catch (error) {
            console.error('FormData API Request failed:', error);
            ApiInterceptors.hideLoading();
            throw error;
        }
    }

    // Auth methods
    async login(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.AUTH.LOGIN, data);
    }

    async signup(data) {
        return this.makeRequest('auth/signup', data);
    }

    async sendOtp(data) {
        return this.makeRequest('auth/send_otp', data);
    }

    async verifyOtp(data) {
        return this.makeRequest('auth/verify_otp', data);
    }

    async resetPassword(data) {
        return this.makeRequest('auth/reset_password', data);
    }

    async logout(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.AUTH.LOGOUT, data);
    }

    async getProfile(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.AUTH.PROFILE, data);
    }

    async updateProfile(data) {
        return this.makeRequest('auth/update_profile', data);
    }

    async deleteAccount(data) {
        return this.makeRequest('auth/delete_account', data);
    }

    async validateSignupStep(data) {
        return this.makeRequest('auth/validate_signup_step', data);
    }

    // Project methods
    async getProjects(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.PROJECTS.LIST, data);
    }

    async createProject(data) {
        // Handle FormData for file uploads
        if (data instanceof FormData) {
            return this.makeFormDataRequest('projects/create', data);
        }
        return this.makeRequest(API_CONFIG.ENDPOINTS.PROJECTS.CREATE, data);
    }
    
    async getDashboardStats() {
        return this.makeRequest('projects/dashboard_stats', {});
    }
    
    // User methods - For dropdowns
    async getProjectManagers() {
        return this.makeRequest('users/project-managers', {}, 'GET');
    }
    
    async getTechnicalEngineers() {
        return this.makeRequest('users/technical-engineers', {}, 'GET');
    }
    
    async getUsersByRole(role) {
        return this.makeRequest(`users/by-role?role=${role}`, {}, 'GET');
    }

    async getProjectDetails(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.PROJECTS.DETAILS, data);
    }

    // Task methods
    async getTasks(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.TASKS.LIST, data);
    }

    async createTask(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.TASKS.CREATE, data);
    }

    async updateTaskStatus(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.TASKS.UPDATE_STATUS, data);
    }

    // Utility methods
    showSuccess(message) {
        // Add your notification logic
        console.log('Success:', message);
    }

    showError(message) {
        // Add your notification logic
        console.error('Error:', message);
    }
}

// Global instance
window.api = new ApiClient();