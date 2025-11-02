# Web Push Notification Testing Guide

## 🔍 Where to Check Notifications

### Windows 10/11
1. **System Tray (Taskbar)** - Bottom right corner
   - Click on notification icon (speaker/time area)
   - Or press `Windows Key + A` to open Action Center
   - Look for "Biltix" or your website notifications

2. **Browser Notification Area**
   - **Chrome/Edge**: Top right corner (next to address bar)
   - **Firefox**: Top right corner notification icon
   - Click the notification to open

### Browser Settings to Check
1. **Chrome/Edge**:
   - Click lock icon (🔒) in address bar
   - Click "Site settings"
   - Check "Notifications" is set to "Allow"

2. **Firefox**:
   - Click website icon in address bar
   - Check "Notifications" permission

## 🧪 Manual Test Notification

Open browser console (F12) and run:
```javascript
new Notification('Test Notification', {
    body: 'If you see this, notifications are working!',
    icon: '/website/images/icons/logo.svg'
});
```

## 📍 Notification Location by Browser

### Chrome/Edge (Windows)
- **Location**: Top-right corner of browser window
- **System Tray**: Bottom-right of screen (Windows)
- **Action Center**: Windows key + A

### Firefox (Windows)
- **Location**: Top-right corner
- **System Tray**: Bottom-right of screen

### All Browsers
- Look for **small popup** in browser corner
- May also appear in **Windows Notification Center** (Action Center)

## ✅ Verification Steps

1. **Check Permission**:
   ```javascript
   // In console
   Notification.permission
   // Should return 'granted'
   ```

2. **Check Service Worker**:
   - F12 → Application tab → Service Workers
   - Should see `firebase-messaging-sw.js` active

3. **Check Console Logs**:
   - Look for: `[firebase-messaging-sw.js] ✅ Notification shown successfully`

4. **Check Browser Settings**:
   - Make sure notifications are not blocked
   - Check browser "Do Not Disturb" mode is OFF

## 🐛 Troubleshooting

### If notification not showing:
1. Check browser notification settings
2. Check Windows notification settings
3. Check if "Do Not Disturb" is enabled
4. Try manual test notification (code above)
5. Check console for errors

