// API Configuration
window.API_CONFIG = {
    BASE_URL: 'http://localhost:8000/api/v1',
    API_KEY: 'biltix_api_key_2024',
    TIMEOUT: 30000,
    ENCRYPTION_ENABLED: false, // Set to true for encryption
    
    ENDPOINTS: {
        AUTH: {
            LOGIN: 'auth/login',
            LOGOUT: 'auth/logout',
            PROFILE: 'auth/get_user_profile'
        },
        PROJECTS: {
            LIST: 'projects/list',
            CREATE: 'projects/create',
            DETAILS: 'projects/details'
        },
        TASKS: {
            LIST: 'tasks/list',
            CREATE: 'tasks/create',
            UPDATE_STATUS: 'tasks/change_status'
        }
    },
    
    MESSAGES: {
        CONNECTION_ERROR: 'Connection error. Please check your internet.',
        UNAUTHORIZED: 'Session expired. Please login again.',
        SERVER_ERROR: 'Server error. Please try again later.'
    }
};