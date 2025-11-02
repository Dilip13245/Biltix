// Service Worker for Firebase Cloud Messaging (Web Push Notifications)
// This file must be in the public root directory

// Import Firebase scripts
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.0/firebase-messaging-compat.js');

// Initialize Firebase
try {
    firebase.initializeApp({
        apiKey: "AIzaSyB4sv8PvUvOcyoa1MyShKdFsfGgvbw7_kQ",
        authDomain: "biltix-50deb.firebaseapp.com",
        projectId: "biltix-50deb",
        storageBucket: "biltix-50deb.firebasestorage.app",
        messagingSenderId: "650789159426",
        appId: "1:650789159426:web:f9b8cb324a63b838b25d1b",
        measurementId: "G-BSBQ2HPQFN"
    });
    console.log('[firebase-messaging-sw.js] Firebase app initialized successfully');
} catch (error) {
    console.error('[firebase-messaging-sw.js] Firebase initialization error:', error);
    throw error;
}

// Retrieve Firebase Messaging object
let messaging;
try {
    messaging = firebase.messaging();
    console.log('[firebase-messaging-sw.js] Firebase messaging initialized');
    console.log('[firebase-messaging-sw.js] Messaging object:', typeof messaging);
} catch (error) {
    console.error('[firebase-messaging-sw.js] Messaging initialization error:', error);
    throw error;
}

// Listen for messages from main thread
self.addEventListener('message', function(event) {
    console.log('[firebase-messaging-sw.js] Message received from main thread:', event.data);
    
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    } else if (event.data && event.data.type === 'PING') {
        // Respond to ping to confirm SW is ready
        event.ports[0].postMessage({ 
            type: 'PONG',
            ready: true,
            messagingInitialized: !!messaging 
        });
    }
});

// Handle background messages (when app is not in focus)
messaging.onBackgroundMessage(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    console.log('[firebase-messaging-sw.js] Payload details:', {
        hasNotification: !!payload.notification,
        hasData: !!payload.data,
        notificationTitle: payload.notification?.title,
        notificationBody: payload.notification?.body,
        dataTitle: payload.data?.title,
        dataMessage: payload.data?.message
    });
    
    const notificationTitle = payload.notification?.title || payload.data?.title || 'Biltix Notification';
    const notificationBody = payload.notification?.body || payload.data?.message || payload.data?.body || '';
    
    console.log('[firebase-messaging-sw.js] Notification details:', {
        title: notificationTitle,
        body: notificationBody
    });
    
    // Prepare notification options
    // IMPORTANT: For notifications to show when tab is in background,
    // we must ensure proper notification options
    const notificationOptions = {
        body: notificationBody,
        icon: '/website/images/icons/logo.svg',
        badge: '/website/images/icons/logo.svg',
        image: payload.notification?.image,
        tag: payload.data?.notification_id || 'biltix-notification-' + Date.now(),
        data: payload.data || {},
        requireInteraction: true, // Changed to true to ensure notification stays visible
        silent: false,
        vibrate: [200, 100, 200],
        actions: [],
        timestamp: Date.now(),
        renotify: true, // Re-show notification even if same tag exists
        dir: 'ltr', // Text direction
        lang: 'en'
    };

    // Add action_url or deep_link to data for click handling
    if (payload.data?.action_url) {
        notificationOptions.data.action_url = payload.data.action_url;
        console.log('[firebase-messaging-sw.js] Added action_url:', payload.data.action_url);
    }
    
    if (payload.data?.deep_link) {
        notificationOptions.data.deep_link = payload.data.deep_link;
        notificationOptions.data.click_action = payload.data.deep_link;
        console.log('[firebase-messaging-sw.js] Added deep_link:', payload.data.deep_link);
    }

    console.log('[firebase-messaging-sw.js] Showing notification with options:', {
        title: notificationTitle,
        body: notificationOptions.body,
        tag: notificationOptions.tag,
        hasData: Object.keys(notificationOptions.data).length > 0
    });

    // Show notification and handle any errors
    try {
        const showPromise = self.registration.showNotification(notificationTitle, notificationOptions);
        
        showPromise
            .then(() => {
                console.log('[firebase-messaging-sw.js] ✅ Notification shown successfully');
                console.log('[firebase-messaging-sw.js] Notification should be visible in browser notification area');
                
                // Try to verify notification was actually displayed
                // Note: This might not be available in all browsers
                if (self.registration.getNotifications) {
                    self.registration.getNotifications().then(notifications => {
                        console.log('[firebase-messaging-sw.js] Active notifications count:', notifications.length);
                        notifications.forEach((notif, index) => {
                            console.log(`[firebase-messaging-sw.js] Notification ${index + 1}:`, {
                                title: notif.title,
                                tag: notif.tag,
                                timestamp: notif.timestamp
                            });
                        });
                    });
                }
            })
            .catch((error) => {
                console.error('[firebase-messaging-sw.js] ❌ Error showing notification:', error);
                console.error('[firebase-messaging-sw.js] Error details:', {
                    name: error.name,
                    message: error.message,
                    stack: error.stack
                });
            });
        
        return showPromise;
    } catch (error) {
        console.error('[firebase-messaging-sw.js] ❌ Exception showing notification:', error);
        console.error('[firebase-messaging-sw.js] Exception details:', {
            name: error.name,
            message: error.message,
            stack: error.stack
        });
        return Promise.reject(error);
    }
});

// Handle notification click
self.addEventListener('notificationclick', function(event) {
    console.log('[firebase-messaging-sw.js] Notification click received.', event);
    
    event.notification.close();

    const notificationData = event.notification.data || {};
    const deepLink = notificationData.deep_link;
    const actionUrl = notificationData.action_url;
    
    console.log('[firebase-messaging-sw.js] Notification click data:', {
        deepLink: deepLink,
        actionUrl: actionUrl,
        allData: notificationData
    });
    
    // Determine URL to open
    let urlToOpen = '/dashboard'; // Default
    
    // Prefer action_url for web (it's already a web URL)
    if (actionUrl) {
        urlToOpen = actionUrl.startsWith('/') ? actionUrl : '/' + actionUrl.replace(/^\/+/, '');
        console.log('[firebase-messaging-sw.js] Using action_url:', urlToOpen);
    } else if (deepLink) {
        // Parse deep link and convert to web URL
        const deepLinkObj = parseDeepLink(deepLink);
        urlToOpen = convertDeepLinkToUrl(deepLinkObj);
        console.log('[firebase-messaging-sw.js] Converted deep_link to URL:', urlToOpen);
    }
    
    console.log('[firebase-messaging-sw.js] Opening URL:', urlToOpen);

    // Open or focus the window
    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function(clientList) {
            // Check if there's already a window open
            for (let i = 0; i < clientList.length; i++) {
                const client = clientList[i];
                if (client.url === urlToOpen && 'focus' in client) {
                    return client.focus();
                }
            }
            // If no window is open, open a new one
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});

// Parse deep link URL
function parseDeepLink(deepLink) {
    try {
        const url = new URL(deepLink);
        const params = {};
        url.searchParams.forEach((value, key) => {
            params[key] = value;
        });
        return {
            scheme: url.protocol.replace(':', ''),
            host: url.host,
            path: url.pathname,
            screen: params.screen,
            params: params
        };
    } catch (e) {
        console.error('Error parsing deep link:', e);
        return null;
    }
}

// Convert deep link to web URL
function convertDeepLinkToUrl(deepLinkObj) {
    if (!deepLinkObj || !deepLinkObj.screen) {
        return '/dashboard';
    }

    const screen = deepLinkObj.screen;
    const params = deepLinkObj.params;
    
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
}

