// Global API Client
class ApiClient {
    constructor() {
        this.baseUrl = API_CONFIG.BASE_URL;
        this.apiKey = API_CONFIG.API_KEY;
    }

    async makeRequest(endpoint, data = {}) {
        const config = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'api-key': this.apiKey,
                'language': document.documentElement.lang || 'en'
            },
            data: data
        };
        
        // Apply interceptors
        ApiInterceptors.beforeRequest(config);
        
        try {
            const response = await fetch(`${this.baseUrl}/${endpoint}`, {
                method: config.method,
                headers: config.headers,
                body: JSON.stringify(config.data)
            });
            
            const result = await response.json();
            return ApiInterceptors.afterResponse(result) || result;
        } catch (error) {
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

    // Project methods
    async getProjects(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.PROJECTS.LIST, data);
    }

    async createProject(data) {
        return this.makeRequest(API_CONFIG.ENDPOINTS.PROJECTS.CREATE, data);
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