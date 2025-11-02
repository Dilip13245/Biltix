// Web Push Notification Manager for Biltix Website
// Handles Firebase Cloud Messaging for web browsers

class WebPushManager {
    constructor() {
        this.messaging = null;
        this.token = null;
        this.storedToken = null;
        this.isSupported = this.checkSupport();
        this.isInitialized = false;
    }

    // Check if browser supports push notifications
    checkSupport() {
        if (!('Notification' in window)) {
            console.log('[WebPush] This browser does not support notifications');
            return false;
        }
        
        if (!('serviceWorker' in navigator)) {
            console.log('[WebPush] This browser does not support service workers');
            return false;
        }

        // Check if Firebase is available
        if (typeof firebase === 'undefined') {
            console.log('[WebPush] Firebase is not loaded');
            return false;
        }

        return true;
    }

    // Initialize Firebase Messaging
    async initialize() {
        console.log('[WebPush] initialize() method called');
        
        if (!this.isSupported) {
            console.log('[WebPush] Push notifications not supported');
            return false;
        }

        if (this.isInitialized) {
            console.log('[WebPush] Already initialized');
            return true;
        }

        try {
            console.log('[WebPush] Checking Firebase availability...');
            console.log('[WebPush] Firebase object:', typeof firebase);
            console.log('[WebPush] FIREBASE_CONFIG:', window.FIREBASE_CONFIG);
            
            // Initialize Firebase if not already initialized
            if (!firebase.apps || firebase.apps.length === 0) {
                console.log('[WebPush] Initializing Firebase app...');
                const config = window.FIREBASE_CONFIG || {
                    apiKey: "AIzaSyB4sv8PvUvOcyoa1MyShKdFsfGgvbw7_kQ",
                    authDomain: "biltix-50deb.firebaseapp.com",
                    projectId: "biltix-50deb",
                    storageBucket: "biltix-50deb.firebasestorage.app",
                    messagingSenderId: "650789159426"
                };
                firebase.initializeApp(config);
                console.log('[WebPush] Firebase app initialized');
            } else {
                console.log('[WebPush] Firebase app already initialized');
            }

            // Get Firebase Messaging instance
            this.messaging = firebase.messaging();
            console.log('[WebPush] Firebase messaging instance obtained');

            // Request notification permission
            console.log('[WebPush] Requesting notification permission...');
            const permission = await Notification.requestPermission();
            console.log('[WebPush] Notification permission:', permission);
            
            if (permission === 'granted') {
                console.log('[WebPush] ✅ Notification permission granted');
                
                // Register service worker
                let registration;
                try {
                    registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                    console.log('[WebPush] ✅ Service Worker registered:', registration.scope);
                    
                    // Wait for service worker to be ready
                    if (registration.installing) {
                        console.log('[WebPush] Service Worker installing, waiting...');
                        await new Promise((resolve) => {
                            registration.installing.addEventListener('statechange', function() {
                                if (this.state === 'activated') {
                                    console.log('[WebPush] Service Worker activated');
                                    resolve();
                                }
                            });
                        });
                    } else if (registration.waiting) {
                        console.log('[WebPush] Service Worker waiting, activating...');
                        registration.waiting.postMessage({ type: 'SKIP_WAITING' });
                        await new Promise((resolve) => {
                            registration.waiting.addEventListener('statechange', function() {
                                if (this.state === 'activated') {
                                    console.log('[WebPush] Service Worker activated');
                                    resolve();
                                }
                            });
                        });
                    } else if (registration.active) {
                        console.log('[WebPush] Service Worker already active');
                        // Even if active, wait a bit for it to be fully initialized
                        await new Promise(resolve => setTimeout(resolve, 500));
                    }

                    // Wait for service worker to be controlling the page
                    await navigator.serviceWorker.ready;
                    console.log('[WebPush] Service Worker is ready and controlling');
                    
                    // Ping service worker to verify it's fully initialized
                    try {
                        await new Promise((resolve, reject) => {
                            const channel = new MessageChannel();
                            channel.port1.onmessage = (event) => {
                                if (event.data && event.data.type === 'PONG') {
                                    console.log('[WebPush] Service Worker responded to ping:', event.data);
                                    resolve(event.data);
                                } else {
                                    reject(new Error('Unexpected response from service worker'));
                                }
                            };
                            
                            registration.active.postMessage({ type: 'PING' }, [channel.port2]);
                            
                            // Timeout after 2 seconds
                            setTimeout(() => reject(new Error('Service worker ping timeout')), 2000);
                        });
                    } catch (pingError) {
                        console.warn('[WebPush] Service worker ping failed (this may be okay):', pingError);
                    }
                    
                } catch (swError) {
                    console.error('[WebPush] ❌ Service Worker registration failed:', swError);
                    return false;
                }

                // Wait longer to ensure service worker is fully ready and controlling
                console.log('[WebPush] Waiting for service worker to be fully ready...');
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                // Verify service worker is controlling the page
                const reg = await navigator.serviceWorker.getRegistration();
                if (!reg || !reg.active) {
                    console.error('[WebPush] ❌ Service worker not active, waiting longer...');
                    await new Promise(resolve => setTimeout(resolve, 3000));
                }

                // Get FCM token with retry logic
                console.log('[WebPush] Attempting to get FCM token...');
                console.log('[WebPush] VAPID key from config:', {
                    hasKey: !!window.FIREBASE_CONFIG?.vapidKey,
                    keyLength: window.FIREBASE_CONFIG?.vapidKey?.length || 0,
                    keyPreview: window.FIREBASE_CONFIG?.vapidKey ? window.FIREBASE_CONFIG.vapidKey.substring(0, 20) + '...' : 'missing'
                });
                
                let tokenResult = null;
                let retries = 3;
                let lastError = null;
                
                for (let i = 0; i < retries; i++) {
                    try {
                        console.log(`[WebPush] Attempt ${i + 1}/${retries} to get token...`);
                        
                        // Verify service worker is ready before each attempt
                        const registration = await navigator.serviceWorker.ready;
                        if (!registration || !registration.active) {
                            console.error('[WebPush] ❌ Service worker not active, skipping attempt');
                            await new Promise(resolve => setTimeout(resolve, 2000));
                            continue;
                        }
                        
                        // Get VAPID key
                        const vapidKey = window.FIREBASE_CONFIG?.vapidKey;
                        if (!vapidKey) {
                            console.error('[WebPush] ❌ VAPID key not found in config');
                            break;
                        }
                        
                        console.log('[WebPush] Calling Firebase getToken() with VAPID key (length:', vapidKey.length, ')');
                        
                        // Call Firebase getToken directly
                        try {
                            tokenResult = await this.messaging.getToken({ 
                                vapidKey: vapidKey,
                                serviceWorkerRegistration: registration
                            });
                            
                            if (tokenResult) {
                                console.log('[WebPush] ✅ Token obtained successfully!');
                                console.log('[WebPush] Token preview:', tokenResult.substring(0, 30) + '...');
                                console.log('[WebPush] Token length:', tokenResult.length);
                                break;
                            } else {
                                console.warn('[WebPush] ⚠️ getToken() returned null');
                            }
                        } catch (error) {
                            console.error(`[WebPush] ❌ Attempt ${i + 1} failed:`, error);
                            console.error('[WebPush] Error details:', {
                                name: error.name,
                                message: error.message,
                                code: error.code
                            });
                            lastError = error;
                            
                            // If it's a VAPID key error, don't retry with same key
                            if (error.message && error.message.includes('applicationServerKey')) {
                                console.error('[WebPush] ❌ VAPID key is invalid. Please check Firebase Console for correct key.');
                                break; // Don't retry with invalid key
                            }
                        }
                        
                    } catch (tokenError) {
                        lastError = tokenError;
                        console.error(`[WebPush] ❌ Error on attempt ${i + 1}:`, tokenError);
                    }
                    
                    // Wait before retry
                    if (i < retries - 1 && !tokenResult) {
                        const delay = 2000 * (i + 1);
                        console.log(`[WebPush] Waiting ${delay}ms before retry...`);
                        await new Promise(resolve => setTimeout(resolve, delay));
                    }
                }
                
                if (!tokenResult) {
                    console.error('[WebPush] ❌ Failed to get FCM token after', retries, 'attempts');
                    if (lastError) {
                        console.error('[WebPush] Last error:', lastError);
                        if (lastError.message && lastError.message.includes('applicationServerKey')) {
                            console.error('[WebPush] ❌ The VAPID key is invalid.');
                            console.error('[WebPush] To fix this:');
                            console.error('[WebPush] 1. Go to Firebase Console: https://console.firebase.google.com/');
                            console.error('[WebPush] 2. Select your project: biltix-50deb');
                            console.error('[WebPush] 3. Go to Project Settings > Cloud Messaging');
                            console.error('[WebPush] 4. Under "Web Push certificates", copy the Key pair');
                            console.error('[WebPush] 5. Update the vapidKey in firebase-config.js');
                        }
                    }
                    return false;
                }
                
                // Save token to server
                console.log('[WebPush] Saving token to server...');
                const saveResult = await this.saveTokenToServer(tokenResult);
                if (saveResult) {
                    console.log('[WebPush] ✅ Token saved to server successfully');
                    // Store token for refresh checking
                    this.token = tokenResult;
                    this.storedToken = tokenResult;
                } else {
                    console.warn('[WebPush] ⚠️ Token obtained but failed to save to server');
                }

                // Listen for token refresh (if available in compat mode)
                if (typeof this.messaging.onTokenRefresh === 'function') {
                    this.messaging.onTokenRefresh(() => {
                        console.log('[WebPush] Token refresh event triggered');
                        this.getToken().then(token => {
                            if (token) {
                                this.saveTokenToServer(token);
                            }
                        });
                    });
                    console.log('[WebPush] Token refresh listener registered');
                } else {
                    console.log('[WebPush] onTokenRefresh not available in compat mode, will check token periodically');
                    // In compat mode, we can check for token refresh manually
                    // Periodically check if token changed (every 5 minutes)
                    setInterval(async () => {
                        try {
                            const currentToken = await this.messaging.getToken({ 
                                vapidKey: window.FIREBASE_CONFIG?.vapidKey 
                            });
                            if (currentToken && currentToken !== this.storedToken) {
                                console.log('[WebPush] Token refreshed detected');
                                this.storedToken = currentToken;
                                await this.saveTokenToServer(currentToken);
                            }
                        } catch (error) {
                            console.warn('[WebPush] Error checking token refresh:', error);
                        }
                    }, 5 * 60 * 1000); // Check every 5 minutes
                }

                // Handle foreground messages (if available)
                // IMPORTANT: onMessage fires even when tab is open but not focused
                // So we need to always show browser notification
                if (typeof this.messaging.onMessage === 'function') {
                    this.messaging.onMessage((payload) => {
                        console.log('[WebPush] Message received in foreground:', payload);
                        console.log('[WebPush] Tab visibility state:', document.visibilityState);
                        console.log('[WebPush] Document hidden:', document.hidden);
                        
                        // Always show browser notification, regardless of tab visibility
                        // This ensures notification shows when user is on another tab
                        this.showForegroundNotification(payload);
                    });
                    console.log('[WebPush] Foreground message listener registered');
                } else {
                    console.log('[WebPush] onMessage not available in compat mode');
                    console.warn('[WebPush] Foreground notifications might not work properly in compat mode');
                }

                this.isInitialized = true;
                console.log('[WebPush] ✅ Initialization complete!');
                return true;
            } else {
                console.log('[WebPush] ❌ Notification permission denied by user:', permission);
                return false;
            }
        } catch (error) {
            console.error('[WebPush] Initialization error:', error);
            return false;
        }
    }

    // Get FCM token and save to server
    async getToken() {
        console.log('[WebPush] getToken() called');
        
        try {
            if (!this.messaging) {
                console.error('[WebPush] ❌ ERROR: Messaging not initialized in getToken()');
                return null;
            }
            
            console.log('[WebPush] ✅ Step 1 passed: Messaging object exists');
            console.log('[WebPush] Step 2: Check VAPID key...');

            // Get VAPID key from config
            const vapidKey = window.FIREBASE_CONFIG?.vapidKey;
            console.log('[WebPush] VAPID key check:', {
                hasVapidKey: !!vapidKey,
                vapidKeyLength: vapidKey ? vapidKey.length : 0,
                vapidKeyPreview: vapidKey ? vapidKey.substring(0, 30) + '...' : 'missing',
                vapidKeyFull: vapidKey ? vapidKey : 'NOT SET'
            });
            
            if (!vapidKey) {
                console.error('[WebPush] ❌ ERROR: VAPID key not configured in firebase-config.js');
                console.error('[WebPush] FIREBASE_CONFIG:', window.FIREBASE_CONFIG);
                return null;
            }

            console.log('[WebPush] ✅ Step 2 passed: VAPID key found (length:', vapidKey.length, ')');
            console.log('[WebPush] Step 3: Verify service worker registration...');
            
            // Verify service worker registration before requesting token
            console.log('[WebPush] Waiting for service worker to be ready...');
            const registration = await navigator.serviceWorker.ready;
            console.log('[WebPush] Service worker ready, checking status...');
            
            if (!registration || !registration.active) {
                console.error('[WebPush] ❌ ERROR: Service worker not active when requesting token');
                console.error('[WebPush] Registration:', registration);
                console.error('[WebPush] Registration.active:', registration?.active);
                return null;
            }
            
            console.log('[WebPush] ✅ Step 3 passed: Service worker is active');
            console.log('[WebPush] Service worker details:', {
                scope: registration.scope,
                origin: window.location.origin,
                scriptURL: registration.active.scriptURL,
                state: registration.active.state
            });
            
            console.log('[WebPush] Step 4: Check messaging.getToken method...');
            
            try {
                // Check if messaging object has the getToken method
                if (typeof this.messaging.getToken !== 'function') {
                    console.error('[WebPush] ❌ messaging.getToken is not a function');
                    console.error('[WebPush] Messaging object:', this.messaging);
                    console.error('[WebPush] Available methods:', Object.keys(this.messaging));
                    return null;
                }
                
                console.log('[WebPush] ✅ Step 4 passed: messaging.getToken is available');
                console.log('[WebPush] Step 5: Prepare token options...');
                
                // Prepare token options - try with serviceWorkerRegistration first
                // Firebase compat mode sometimes requires this
                const tokenOptions = { 
                    vapidKey: vapidKey,
                    serviceWorkerRegistration: registration
                };
                
                console.log('[WebPush] Token options prepared:', { 
                    hasVapidKey: !!tokenOptions.vapidKey,
                    vapidKeyLength: tokenOptions.vapidKey ? tokenOptions.vapidKey.length : 0,
                    vapidKeyValue: tokenOptions.vapidKey ? tokenOptions.vapidKey.substring(0, 50) + '...' : 'missing',
                    hasServiceWorkerRegistration: !!tokenOptions.serviceWorkerRegistration,
                    swRegistrationScope: tokenOptions.serviceWorkerRegistration?.scope
                });
                
                // Try getting token - try with serviceWorkerRegistration first
                console.log('[WebPush] Step 6: Calling messaging.getToken()...');
                console.log('[WebPush] Attempt 1: With serviceWorkerRegistration...');
                try {
                    this.token = await this.messaging.getToken(tokenOptions);
                    console.log('[WebPush] ✅ Attempt 1 completed, token:', this.token ? 'received' : 'null');
                } catch (error1) {
                    console.error('[WebPush] ❌ Attempt 1 failed:', error1);
                    console.error('[WebPush] Error details:', {
                        name: error1.name,
                        message: error1.message,
                        code: error1.code,
                        stack: error1.stack
                    });
                    
                    // Try without serviceWorkerRegistration if first attempt fails
                    console.log('[WebPush] Attempt 2: Without serviceWorkerRegistration...');
                    const simpleOptions = { vapidKey: vapidKey };
                    try {
                        this.token = await this.messaging.getToken(simpleOptions);
                        console.log('[WebPush] ✅ Attempt 2 succeeded, token:', this.token ? 'received' : 'null');
                    } catch (error2) {
                        console.error('[WebPush] ❌ Attempt 2 also failed:', error2);
                        console.error('[WebPush] Both attempts failed');
                        console.error('[WebPush] Error 1:', error1);
                        console.error('[WebPush] Error 2:', error2);
                        throw error2; // Throw the last error
                    }
                }
                
                console.log('[WebPush] Step 6 completed. Final token result:', this.token ? 'has token' : 'null');
                
                if (this.token) {
                    console.log('[WebPush] ✅ FCM Token obtained:', this.token.substring(0, 20) + '...');
                    console.log('[WebPush] Full token length:', this.token.length);
                    
                    // Save token to server
                    const saveResult = await this.saveTokenToServer(this.token);
                    if (saveResult) {
                        console.log('[WebPush] ✅ Token saved to server successfully');
                    } else {
                        console.warn('[WebPush] ⚠️ Token obtained but failed to save to server');
                    }
                    
                    return this.token;
                } else {
                    console.error('[WebPush] ❌ No registration token available from FCM');
                    console.error('[WebPush] This might mean:');
                    console.error('[WebPush] 1. Service worker registration issue');
                    console.error('[WebPush] 2. VAPID key mismatch');
                    console.error('[WebPush] 3. Firebase project configuration issue');
                    console.error('[WebPush] 4. Service worker not properly initialized');
                    
                    // Additional debugging
                    const allRegistrations = await navigator.serviceWorker.getRegistrations();
                    console.error('[WebPush] All service worker registrations:', allRegistrations.length);
                    allRegistrations.forEach((reg, index) => {
                        console.error(`[WebPush] Registration ${index}:`, {
                            scope: reg.scope,
                            active: reg.active ? reg.active.scriptURL : null,
                            state: reg.active ? reg.active.state : 'none'
                        });
                    });
                    
                    console.log('[WebPush] Returning null - no token obtained');
                    return null;
                }
            } catch (tokenError) {
                console.error('[WebPush] ❌ Exception in inner try block:', tokenError);
                console.error('[WebPush] Error details:', {
                    name: tokenError.name,
                    message: tokenError.message,
                    code: tokenError.code,
                    stack: tokenError.stack
                });
                
                // Check if error is related to service worker
                if (tokenError.message && tokenError.message.includes('service-worker')) {
                    console.error('[WebPush] ❌ Service worker related error detected');
                    try {
                        const registration = await navigator.serviceWorker.getRegistration();
                        console.error('[WebPush] Current registration:', registration ? {
                            scope: registration.scope,
                            active: registration.active ? registration.active.scriptURL : null
                        } : 'none');
                    } catch (e) {
                        console.error('[WebPush] Could not get registration:', e);
                    }
                }
                
                // Check if error is related to VAPID key
                if (tokenError.message && (tokenError.message.includes('vapid') || tokenError.message.includes('key'))) {
                    console.error('[WebPush] ❌ VAPID key related error - check Firebase console for correct VAPID key');
                }
                
                console.log('[WebPush] Returning null due to exception');
                return null;
            }
        } catch (error) {
            console.error('[WebPush] ❌ Exception in getToken() outer try:', error);
            console.error('[WebPush] Error stack:', error.stack);
            console.log('[WebPush] Returning null due to outer exception');
            return null;
        } finally {
            console.log('[WebPush] ========== getToken() END ==========');
        }
    }

    // Save FCM token to server
    async saveTokenToServer(token) {
        try {
            const userId = window.UniversalAuth?.getUserId() || 
                          (sessionStorage.getItem('user_id') || localStorage.getItem('user_id'));
            
            if (!userId) {
                console.log('[WebPush] User not logged in, skipping token save');
                return false;
            }

            const authToken = window.UniversalAuth?.getToken() || 
                            (sessionStorage.getItem('token') || localStorage.getItem('token'));
            
            if (!authToken) {
                console.error('[WebPush] No auth token available, cannot save device token');
                return false;
            }

            // Get device info
            const deviceInfo = this.getDeviceInfo();
            const uuid = this.getOrCreateUUID();

            const requestData = {
                user_id: parseInt(userId),
                device_type: 'W', // Web
                device_token: token,
                ip_address: deviceInfo.ip || '',
                uuid: uuid,
                os_version: deviceInfo.os,
                device_model: deviceInfo.browser,
                app_version: 'web-1.0.0',
                push_notification_enabled: true
            };

            console.log('[WebPush] Saving token to server:', {
                user_id: requestData.user_id,
                device_type: requestData.device_type,
                token_preview: token.substring(0, 20) + '...',
                has_auth_token: !!authToken
            });

            const apiBaseUrl = window.API_CONFIG?.BASE_URL || `${window.location.origin}/api/v1`;
            const response = await fetch(`${apiBaseUrl}/auth/register_device`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'api-key': window.API_CONFIG?.API_KEY || 'biltix_api_key_2024',
                    'token': authToken
                },
                body: JSON.stringify(requestData)
            });

            console.log('[WebPush] Device registration HTTP status:', response.status);

            if (!response.ok) {
                const errorText = await response.text();
                console.error('[WebPush] HTTP error response:', errorText);
                return false;
            }

            const result = await response.json();
            console.log('[WebPush] Device registration response:', result);
            
            // Check response format: {code: 200, message: "...", data: {...}}
            if (result.code === 200) {
                console.log('[WebPush] ✅ Token saved to server successfully', result.data);
                localStorage.setItem('web_push_token', token);
                localStorage.setItem('web_push_registered', 'true');
                return true;
            } else {
                console.error('[WebPush] ❌ Failed to save token:', result.message || 'Unknown error', result);
                return false;
            }
        } catch (error) {
            console.error('[WebPush] Exception saving token to server:', error);
            return false;
        }
    }

    // Show notification when app is in foreground
    showForegroundNotification(payload) {
        const notificationTitle = payload.notification?.title || payload.data?.title || 'Biltix Notification';
        const notificationBody = payload.notification?.body || payload.data?.message || '';
        const notificationData = payload.data || {};

        // Check if tab is visible or hidden
        const isTabVisible = document.visibilityState === 'visible';
        const isTabActive = !document.hidden;

        console.log('[WebPush] Showing foreground notification', {
            isTabVisible: isTabVisible,
            isTabActive: isTabActive,
            visibilityState: document.visibilityState
        });

        // ALWAYS show browser notification (even if tab is open but in background)
        // This ensures notification shows when user is on another tab
        if ('Notification' in window && Notification.permission === 'granted') {
            try {
                const notification = new Notification(notificationTitle, {
                    body: notificationBody,
                    icon: '/website/images/icons/logo.svg',
                    badge: '/website/images/icons/logo.svg',
                    tag: notificationData.notification_id || 'biltix-notification',
                    data: notificationData,
                    requireInteraction: false,
                    silent: false
                });

                console.log('[WebPush] Browser notification created', {
                    title: notificationTitle,
                    tag: notificationData.notification_id || 'biltix-notification'
                });

                // Handle notification click
                notification.onclick = function(event) {
                    event.preventDefault();
                    const data = event.currentTarget.data || {};
                    const deepLink = data.deep_link;
                    const actionUrl = data.action_url;

                    let urlToOpen = '/dashboard';
                    if (deepLink) {
                        urlToOpen = WebPushManager.convertDeepLinkToUrl(deepLink);
                    } else if (actionUrl) {
                        urlToOpen = actionUrl;
                    }

                    console.log('[WebPush] Notification clicked, opening:', urlToOpen);
                    window.focus();
                    window.location.href = urlToOpen;
                    notification.close();
                };
            } catch (error) {
                console.error('[WebPush] Error creating browser notification:', error);
            }
        }

        // Only show toast notification if tab is visible and active
        // This prevents toast from showing when user is on another tab
        if (isTabVisible && isTabActive && typeof toastr !== 'undefined') {
            toastr.info(notificationBody, notificationTitle, {
                timeOut: 5000,
                onclick: function() {
                    const data = payload.data || {};
                    const deepLink = data.deep_link;
                    const actionUrl = data.action_url;
                    
                    let urlToOpen = '/dashboard';
                    if (deepLink) {
                        urlToOpen = WebPushManager.convertDeepLinkToUrl(deepLink);
                    } else if (actionUrl) {
                        urlToOpen = actionUrl;
                    }
                    
                    window.location.href = urlToOpen;
                }
            });
        } else {
            console.log('[WebPush] Skipping toast notification - tab not visible/active');
        }
    }

    // Convert deep link to web URL
    static convertDeepLinkToUrl(deepLink) {
        try {
            const url = new URL(deepLink);
            const params = {};
            url.searchParams.forEach((value, key) => {
                params[key] = value;
            });

            const screen = params.screen;
            
            switch (screen) {
                case 'task':
                    return params.task_id ? `/tasks/${params.task_id}` : '/tasks';
                case 'snag':
                    return params.snag_id ? `/snags/${params.snag_id}` : '/snags';
                case 'project':
                    return params.project_id ? `/projects/${params.project_id}` : '/projects';
                case 'inspection':
                    return params.inspection_id ? `/inspections/${params.inspection_id}` : '/inspections';
                case 'team':
                    return params.project_id ? `/projects/${params.project_id}/team` : '/projects';
                case 'plan':
                    return params.plan_id ? `/plans/${params.plan_id}` : '/plans';
                case 'file':
                    return params.file_id ? `/files/${params.file_id}` : '/files';
                case 'daily_log':
                    return params.project_id ? `/projects/${params.project_id}/daily-logs` : '/projects';
                case 'dashboard':
                default:
                    return '/dashboard';
            }
        } catch (e) {
            console.error('[WebPush] Error converting deep link:', e);
            return '/dashboard';
        }
    }

    // Get device information
    getDeviceInfo() {
        const userAgent = navigator.userAgent;
        let browser = 'Unknown';
        let os = 'Unknown';

        // Detect browser
        if (userAgent.indexOf('Chrome') > -1) browser = 'Chrome';
        else if (userAgent.indexOf('Firefox') > -1) browser = 'Firefox';
        else if (userAgent.indexOf('Safari') > -1) browser = 'Safari';
        else if (userAgent.indexOf('Edge') > -1) browser = 'Edge';

        // Detect OS
        if (userAgent.indexOf('Windows') > -1) os = 'Windows';
        else if (userAgent.indexOf('Mac') > -1) os = 'macOS';
        else if (userAgent.indexOf('Linux') > -1) os = 'Linux';
        else if (userAgent.indexOf('Android') > -1) os = 'Android';
        else if (userAgent.indexOf('iOS') > -1) os = 'iOS';

        return {
            browser: `${browser} ${navigator.appVersion.match(/\d+\.\d+/)?.[0] || ''}`,
            os: os,
            ip: '' // Will be detected by server
        };
    }

    // Get or create UUID for this browser
    getOrCreateUUID() {
        let uuid = localStorage.getItem('biltix_device_uuid');
        if (!uuid) {
            uuid = 'web_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
            localStorage.setItem('biltix_device_uuid', uuid);
        }
        return uuid;
    }

    // Request permission and initialize
    async requestPermission() {
        if (!this.isSupported) {
            alert('Your browser does not support push notifications');
            return false;
        }

        return await this.initialize();
    }

    // Check if permission is granted
    isPermissionGranted() {
        return Notification.permission === 'granted';
    }

    // Get current token
    getToken() {
        return this.token;
    }
}

// Global instance
window.WebPushManager = new WebPushManager();

// Auto-initialize when DOM is ready (if user is logged in)
function initializeWebPush() {
    console.log('[WebPush] initializeWebPush called');
    
    // Check if user is logged in
    const userId = window.UniversalAuth?.getUserId() || 
                  (sessionStorage.getItem('user_id') || localStorage.getItem('user_id'));
    
    console.log('[WebPush] Checking user login status:', {
        userId: userId,
        universalAuth: window.UniversalAuth ? 'available' : 'not available',
        sessionStorage: sessionStorage.getItem('user_id'),
        localStorage: localStorage.getItem('user_id')
    });
    
    if (!userId) {
        console.log('[WebPush] User not logged in, skipping initialization');
        return;
    }

    // Check if already initialized
    if (window.WebPushManager.isInitialized) {
        console.log('[WebPush] Already initialized, skipping');
        return;
    }
    
    console.log('[WebPush] Starting initialization process...');

    // Wait for Firebase to be loaded
    let attempts = 0;
    const maxAttempts = 10;
    
    const checkFirebase = setInterval(() => {
        attempts++;
        
        if (typeof firebase !== 'undefined') {
            clearInterval(checkFirebase);
            window.WebPushManager.initialize().then(success => {
                if (success) {
                    console.log('[WebPush] Push notifications initialized successfully');
                } else {
                    console.log('[WebPush] Failed to initialize push notifications');
                }
            }).catch(error => {
                console.error('[WebPush] Initialization error:', error);
            });
        } else if (attempts >= maxAttempts) {
            clearInterval(checkFirebase);
            console.warn('[WebPush] Firebase not loaded after', maxAttempts, 'attempts');
        }
    }, 500); // Check every 500ms
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initializeWebPush);

// Also initialize if DOMContentLoaded already fired
if (document.readyState === 'loading') {
    // DOM hasn't finished loading
    document.addEventListener('DOMContentLoaded', initializeWebPush);
} else {
    // DOM already loaded
    setTimeout(initializeWebPush, 500);
}

// Listen for login events (in case user logs in after page load)
window.addEventListener('storage', function(e) {
    if (e.key === 'biltix_session' || e.key === 'user_id') {
        console.log('[WebPush] Auth storage changed, re-checking initialization');
        setTimeout(initializeWebPush, 1000);
    }
});

// Expose initialization function globally for manual triggering
window.initializeWebPush = initializeWebPush;

