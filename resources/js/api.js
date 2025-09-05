/**
 * API Helper for Frontend JavaScript
 * Handles AJAX calls to the website API endpoints
 */

class ApiClient {
    constructor() {
        this.baseUrl = window.location.origin;
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.language = document.documentElement.lang || 'en';
    }

    async request(method, endpoint, data = null) {
        const url = `${this.baseUrl}/${endpoint.replace(/^\//, '')}`;
        
        const options = {
            method: method.toUpperCase(),
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': this.csrfToken,
                'Accept-Language': this.language,
            },
        };

        if (data && method.toUpperCase() !== 'GET') {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            const result = await response.json();
            
            if (!response.ok) {
                throw new Error(result.message || 'Request failed');
            }
            
            return result;
        } catch (error) {
            console.error('API Request failed:', error);
            throw error;
        }
    }

    // HTTP Methods
    async get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.request('GET', url);
    }

    async post(endpoint, data = {}) {
        return this.request('POST', endpoint, data);
    }

    async put(endpoint, data = {}) {
        return this.request('PUT', endpoint, data);
    }

    async delete(endpoint, data = {}) {
        return this.request('DELETE', endpoint, data);
    }

    // Auth Methods
    async login(credentials) {
        return this.post('api/login', credentials);
    }

    async signup(userData) {
        return this.post('api/signup', userData);
    }

    async logout() {
        return this.post('api/logout');
    }

    async getUserProfile() {
        return this.get('api/profile');
    }

    // Project Methods
    async getProjects(params = {}) {
        return this.get('api/projects', params);
    }

    async createProject(projectData) {
        return this.post('api/projects', projectData);
    }

    async getProjectDetails(projectId) {
        return this.get(`api/projects/${projectId}`);
    }

    async updateProject(projectId, projectData) {
        return this.put(`api/projects/${projectId}`, projectData);
    }

    async deleteProject(projectId) {
        return this.delete(`api/projects/${projectId}`);
    }

    // Task Methods
    async getTasks(params = {}) {
        return this.get('api/tasks', params);
    }

    async createTask(taskData) {
        return this.post('api/tasks', taskData);
    }

    async updateTaskStatus(taskId, status) {
        return this.put(`api/tasks/${taskId}/status`, { status });
    }

    async deleteTask(taskId) {
        return this.delete(`api/tasks/${taskId}`);
    }

    // Utility Methods
    showSuccess(message) {
        // You can customize this based on your notification system
        if (typeof toastr !== 'undefined') {
            toastr.success(message);
        } else {
            alert(message);
        }
    }

    showError(message) {
        // You can customize this based on your notification system
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }

    handleResponse(response) {
        if (response.success) {
            if (response.message) {
                this.showSuccess(response.message);
            }
            return response.data;
        } else {
            this.showError(response.message || 'An error occurred');
            throw new Error(response.message);
        }
    }
}

// Create global instance
window.api = new ApiClient();

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ApiClient;
}