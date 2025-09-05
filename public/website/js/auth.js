// Auth specific functions
async function handleLogin(formElement) {
    const formData = new FormData(formElement);
    const data = {
        email: formData.get('email'),
        password: formData.get('password'),
        device_type: 'W'
    };

    try {
        const response = await api.login(data);
        
        if (response.code === 200) {
            sessionStorage.setItem('user', JSON.stringify(response.data));
            sessionStorage.setItem('token', response.data.token);
            api.showSuccess('Login successful!');
            window.location.href = '/dashboard';
        } else {
            api.showError(response.message);
        }
    } catch (error) {
        api.showError('Connection error');
    }
}

async function handleLogout() {
    const user = JSON.parse(sessionStorage.getItem('user') || '{}');
    
    try {
        await api.logout({ user_id: user.id });
        sessionStorage.clear();
        window.location.href = '/login';
    } catch (error) {
        console.error('Logout error:', error);
        sessionStorage.clear();
        window.location.href = '/login';
    }
}