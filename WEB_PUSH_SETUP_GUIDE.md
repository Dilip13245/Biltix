# Web Push Notification Setup Guide

## ✅ Implementation Complete

Web push notification support has been added to the Biltix website!

---

## 📁 Files Created

1. **`public/firebase-config.js`** - Firebase web configuration
2. **`public/firebase-messaging-sw.js`** - Service worker for handling push notifications
3. **`public/website/js/web-push-manager.js`** - JavaScript manager for web push
4. **API Endpoint**: `/api/v1/auth/register_device` - Register web push tokens

---

## 🔧 Setup Steps

### Step 1: Get Firebase Web App Config

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project: **biltix-50deb**
3. Click the gear icon ⚙️ → **Project Settings**
4. Scroll down to **Your apps** section
5. Click **Web** icon (</>) or **Add app** → **Web**
6. Register your web app (if not done)
7. Copy the Firebase config

### Step 2: Get VAPID Key

1. In Firebase Console → **Project Settings**
2. Go to **Cloud Messaging** tab
3. Scroll to **Web Push certificates** section
4. Click **Generate key pair** (if not exists)
5. Copy the **Key pair** value

### Step 3: Update Firebase Config

Edit `public/firebase-config.js`:

```javascript
window.FIREBASE_CONFIG = {
    apiKey: "YOUR_API_KEY",
    authDomain: "biltix-50deb.firebaseapp.com",
    projectId: "biltix-50deb",
    storageBucket: "biltix-50deb.firebasestorage.app",
    messagingSenderId: "650789159426",
    appId: "YOUR_WEB_APP_ID", // From Firebase Console
    vapidKey: "YOUR_VAPID_KEY" // From Firebase Console > Cloud Messaging
};
```

### Step 4: Update Service Worker

Edit `public/firebase-messaging-sw.js` and update:
- Firebase config with your web app ID

---

## ✅ How It Works

### Automatic Initialization

1. When user logs in, `WebPushManager` automatically:
   - Checks browser support
   - Requests notification permission
   - Registers service worker
   - Gets FCM token
   - Saves token to server

### Notification Flow

1. **Server sends notification** → FCM
2. **FCM delivers** to browser
3. **Service worker** receives notification
4. **Browser shows** notification
5. **User clicks** → Opens relevant page

---

## 🔔 Features

### ✅ Foreground Notifications
- Shows browser notification + toast when app is open
- Handles clicks and navigation

### ✅ Background Notifications
- Shows notification when browser is closed/minimized
- Service worker handles display
- Clicks open the website

### ✅ Deep Linking
- Automatically converts deep links to web URLs
- Navigates to correct pages (tasks, projects, etc.)

---

## 📱 Browser Support

- ✅ Chrome/Edge (Desktop & Mobile)
- ✅ Firefox (Desktop)
- ✅ Safari (iOS 16.4+)
- ❌ Safari (Desktop - Limited support)
- ✅ Opera

---

## 🧪 Testing

### Test 1: Check Permission
```javascript
// In browser console
Notification.permission
// Should be 'granted', 'denied', or 'default'
```

### Test 2: Check Token
```javascript
// In browser console
WebPushManager.getToken()
// Should return FCM token string
```

### Test 3: Send Test Notification
Send a notification from your server and check:
1. Browser notification appears
2. Click opens correct page
3. Toast notification shows (if app is open)

---

## 🔍 Troubleshooting

### Issue: "Firebase is not loaded"
**Solution:** Make sure Firebase scripts are loaded before `web-push-manager.js`

### Issue: "VAPID key not configured"
**Solution:** Get VAPID key from Firebase Console and update `firebase-config.js`

### Issue: "Service worker registration failed"
**Solution:** 
- Check browser console for errors
- Ensure service worker file is accessible at `/firebase-messaging-sw.js`
- Check HTTPS (required for service workers)

### Issue: "Permission denied"
**Solution:**
- User must manually grant permission
- Some browsers require user interaction to request permission
- Check browser notification settings

### Issue: "Token not saved"
**Solution:**
- Check user is logged in
- Check API endpoint `/api/v1/auth/register_device` is working
- Check browser console for errors

---

## 📝 API Endpoint

### Register/Update Device Token

**Endpoint:** `POST /api/v1/auth/register_device`

**Headers:**
```
api-key: biltix_api_key_2024
token: {user_auth_token}
Content-Type: application/json
```

**Body:**
```json
{
  "user_id": 1,
  "device_type": "W",
  "device_token": "FCM_TOKEN_HERE",
  "ip_address": "",
  "uuid": "web_1234567890",
  "os_version": "Windows",
  "device_model": "Chrome 120",
  "app_version": "web-1.0.0",
  "push_notification_enabled": true
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Device registered successfully",
  "data": {
    "device_id": 123,
    "device_type": "W",
    "push_notification_enabled": true
  }
}
```

---

## 🎯 Next Steps

1. ✅ Get Firebase web app config
2. ✅ Get VAPID key
3. ✅ Update `firebase-config.js`
4. ✅ Update `firebase-messaging-sw.js`
5. ✅ Test notification sending
6. ✅ Test notification clicking
7. ✅ Verify deep linking works

---

## 📊 Current Status

✅ **Server-Side**: Complete
- PushNotificationService handles web push (device_type: 'W')
- API endpoint to register tokens
- Deep linking support

✅ **Client-Side**: Complete
- Service worker registered
- Push manager initialized
- Auto-registration on login

⏳ **Required**: Firebase Configuration
- Need web app config from Firebase Console
- Need VAPID key from Firebase Console

Once Firebase config is added, web push notifications will work automatically! 🎉

