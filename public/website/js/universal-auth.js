// Universal Auth System - Works everywhere without issues
window.UniversalAuth = {
    // Storage keys
    SESSION_KEY: 'biltix_session',
    REMEMBER_KEY: 'biltix_remember',

    // Login function
    login(userData, rememberMe = false, skipRedirect = false) {
        const authData = {
            user: userData,
            token: userData.token,
            user_id: userData.id,
            timestamp: Date.now()
        };

        if (rememberMe) {
            localStorage.setItem(this.REMEMBER_KEY, JSON.stringify(authData));
            sessionStorage.removeItem(this.SESSION_KEY);
        } else {
            sessionStorage.setItem(this.SESSION_KEY, JSON.stringify(authData));
            // Set browser session marker for non-remember-me
            sessionStorage.setItem('browser_session_active', 'true');
            localStorage.removeItem(this.REMEMBER_KEY);
        }

        // Only redirect if not skipped (caller will handle redirect after session setup)
        if (!skipRedirect) {
            window.location.href = '/dashboard';
        }
    },

    // Logout function
    logout() {
        sessionStorage.removeItem(this.SESSION_KEY);
        localStorage.removeItem(this.REMEMBER_KEY);
        // Don't redirect here - let caller handle redirect after all operations complete
    },



    // Check if logged in
    isLoggedIn() {
        // For session-only login, only check sessionStorage
        const sessionData = sessionStorage.getItem(this.SESSION_KEY);
        console.log('Session data:', sessionData ? 'exists' : 'null');
        if (sessionData) {
            try {
                const auth = JSON.parse(sessionData);
                console.log('Session auth valid, staying logged in');
                return !!(auth && auth.token && auth.user_id);
            } catch (e) {
                sessionStorage.removeItem(this.SESSION_KEY);
                return false;
            }
        }

        // For remember me, check localStorage
        const rememberData = localStorage.getItem(this.REMEMBER_KEY);
        console.log('Remember data:', rememberData ? 'exists' : 'null');
        if (rememberData) {
            try {
                const auth = JSON.parse(rememberData);
                // Check if expired (30 days)
                if (Date.now() - auth.timestamp > 30 * 24 * 60 * 60 * 1000) {
                    localStorage.removeItem(this.REMEMBER_KEY);
                    console.log('Remember me expired, logging out');
                    return false;
                }
                console.log('Remember me valid, staying logged in');
                return !!(auth && auth.token && auth.user_id);
            } catch (e) {
                localStorage.removeItem(this.REMEMBER_KEY);
                return false;
            }
        }

        console.log('No auth data found, should redirect to login');
        return false;
    },

    // Get current user
    getUser() {
        // Check sessionStorage first
        const sessionData = sessionStorage.getItem(this.SESSION_KEY);
        if (sessionData) {
            try {
                const auth = JSON.parse(sessionData);
                return auth.user;
            } catch (e) {
                return null;
            }
        }

        // Check localStorage for remember me
        const rememberData = localStorage.getItem(this.REMEMBER_KEY);
        if (rememberData) {
            try {
                const auth = JSON.parse(rememberData);
                if (Date.now() - auth.timestamp <= 30 * 24 * 60 * 60 * 1000) {
                    return auth.user;
                }
            } catch (e) {
                return null;
            }
        }

        return null;
    },

    // Get token
    getToken() {
        // Check sessionStorage first
        const sessionData = sessionStorage.getItem(this.SESSION_KEY);
        if (sessionData) {
            try {
                const auth = JSON.parse(sessionData);
                return auth.token;
            } catch (e) {
                return null;
            }
        }

        // Check localStorage for remember me
        const rememberData = localStorage.getItem(this.REMEMBER_KEY);
        if (rememberData) {
            try {
                const auth = JSON.parse(rememberData);
                if (Date.now() - auth.timestamp <= 30 * 24 * 60 * 60 * 1000) {
                    return auth.token;
                }
            } catch (e) {
                return null;
            }
        }

        return null;
    },

    // Get user ID
    getUserId() {
        // Check sessionStorage first
        const sessionData = sessionStorage.getItem(this.SESSION_KEY);
        if (sessionData) {
            try {
                const auth = JSON.parse(sessionData);
                return auth.user_id;
            } catch (e) {
                return null;
            }
        }

        // Check localStorage for remember me
        const rememberData = localStorage.getItem(this.REMEMBER_KEY);
        if (rememberData) {
            try {
                const auth = JSON.parse(rememberData);
                if (Date.now() - auth.timestamp <= 30 * 24 * 60 * 60 * 1000) {
                    return auth.user_id;
                }
            } catch (e) {
                return null;
            }
        }

        return null;
    },

    // Restore Laravel session from remember me data
    async restoreSessionFromRememberMe() {
        const rememberData = localStorage.getItem(this.REMEMBER_KEY);
        if (!rememberData) {
            return false;
        }

        try {
            const auth = JSON.parse(rememberData);

            // Check if expired (30 days)
            if (Date.now() - auth.timestamp > 30 * 24 * 60 * 60 * 1000) {
                localStorage.removeItem(this.REMEMBER_KEY);
                return false;
            }

            // Check if we have valid token and user data
            if (!auth.token || !auth.user_id || !auth.user) {
                return false;
            }

            // Verify session is actually missing by checking current session
            try {
                const checkResponse = await fetch('/auth/check-session', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    }
                });

                if (checkResponse.ok) {
                    const checkResult = await checkResponse.json();
                    if (checkResult.authenticated) {
                        console.log('Laravel session is valid, no restoration needed');
                        return true;
                    }
                }
            } catch (e) {
                console.log('Session check failed, proceeding with restoration');
            }

            // Session is missing, restore it
            console.log('Restoring Laravel session from remember me data...');
            const sessionData = {
                user_id: auth.user_id,
                token: auth.token,
                user: auth.user,
                remember_me: true
            };

            const sessionResponse = await fetch('/auth/set-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify(sessionData)
            });

            if (sessionResponse.ok) {
                const sessionResult = await sessionResponse.json();
                if (sessionResult.success) {
                    console.log('Laravel session restored successfully from remember me');
                    // Reload page to apply restored session
                    window.location.reload();
                    return true;
                }
            }

            console.log('Failed to restore Laravel session');
            return false;
        } catch (error) {
            console.error('Error restoring session from remember me:', error);
            return false;
        }
    }
};

// Universal page handler - works on ALL pages
document.addEventListener('DOMContentLoaded', function () {
    // Skip auth check if disabled (Laravel middleware handles it)
    if (window.DISABLE_JS_AUTH_CHECK) {
        console.log('JavaScript auth check disabled - Laravel middleware handling auth');

        // Check for remember me data first (before browser session check)
        const hasRememberMeData = localStorage.getItem(UniversalAuth.REMEMBER_KEY);

        if (hasRememberMeData) {
            console.log('Remember me data found, checking if Laravel session needs restoration...');
            // Try to restore Laravel session if it's expired
            // Remember me users should stay logged in even after browser close/reopen
            // Wait for restoration to complete before proceeding
            UniversalAuth.restoreSessionFromRememberMe().then(restored => {
                if (!restored) {
                    console.log('Session restoration failed, but remember me data exists - checking if session is valid...');
                    // If restoration failed, check if session is actually valid
                    // This handles the case where cookie wasn't set but localStorage exists
                    fetch('/auth/check-session', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json'
                        }
                    }).then(response => {
                        if (response.ok) {
                            return response.json();
                        }
                        return { authenticated: false };
                    }).then(result => {
                        if (!result.authenticated) {
                            // Session is missing, try to restore it again
                            console.log('Session not authenticated, attempting restoration...');
                            UniversalAuth.restoreSessionFromRememberMe();
                        }
                    }).catch(err => {
                        console.error('Error checking session:', err);
                    });
                }
            }).catch(err => {
                console.error('Error during session restoration:', err);
            });
        } else {
            // Only check browser session for non-remember-me users
            const browserSessionActive = sessionStorage.getItem('browser_session_active');

            if (!browserSessionActive) {
                // New browser session and no remember me data, clear Laravel session
                console.log('New browser session detected (no remember me), clearing server session');
                fetch('/auth/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                    }
                }).then(() => {
                    window.location.href = '/login';
                }).catch(() => {
                    window.location.href = '/login';
                });
            }
        }
        return;
    }

    const currentPath = window.location.pathname;
    console.log('Current page:', currentPath);

    const publicPages = ['/login', '/register', '/forgot-password'];
    const isPublicPage = publicPages.includes(currentPath);

    if (isPublicPage) {
        // Public page - allow access, no redirects
        console.log('Public page loaded, no auth check needed');
    } else {
        // Protected page - check auth
        const isLoggedIn = UniversalAuth.isLoggedIn();
        console.log('Protected page auth check result:', isLoggedIn);

        if (!isLoggedIn) {
            console.log('Not logged in, redirecting to login...');
            window.location.href = '/login';
        } else {
            console.log('Logged in, access granted');
        }
    }
});