# FCM Server Key Setup Guide

## Current Issue: 404 Error

You're getting a **404 error** because the API key in `google-services.json` is a **CLIENT API KEY**, not a **SERVER KEY**. These are different:

- ❌ **Client API Key** (in google-services.json) - For Android/iOS apps, cannot be used for server-side FCM
- ✅ **Server Key** - For server applications to send push notifications

## Solution Options

### Option 1: Get FCM Server Key (Quick Fix - Legacy API)

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project: **biltix-50deb**
3. Click the gear icon ⚙️ → **Project Settings**
4. Go to **Cloud Messaging** tab
5. Under **Cloud Messaging API (Legacy)**, you'll find:
   - **Server key** - Copy this value
6. Add to your `.env` file:
   ```env
   FCM_SERVER_KEY=your_server_key_here
   ```
7. The system will automatically use this instead of the client API key

⚠️ **Note:** Firebase is deprecating server keys. This is a temporary solution.

---

### Option 2: Use Service Account JSON (Recommended - HTTP v1 API)

This is the modern, recommended approach:

1. Go to [Firebase Console](https://console.firebase.google.com/)
2. Select your project: **biltix-50deb**
3. Click the gear icon ⚙️ → **Project Settings**
4. Go to **Service Accounts** tab
5. Click **Generate New Private Key**
6. Download the JSON file
7. Save it to: `storage/app/firebase-service-account.json`
8. The system will automatically detect it and use FCM HTTP v1 API

✅ **Benefits:**
- More secure (OAuth2)
- Future-proof (not deprecated)
- Better error handling

---

## Which Option to Choose?

**If you need notifications working NOW:**
- Use **Option 1** (Server Key) - Quick setup, works immediately

**If you want a long-term solution:**
- Use **Option 2** (Service Account) - Recommended, future-proof

**You can also use both:**
- The system will prefer Service Account (HTTP v1 API) if available
- Falls back to Server Key (Legacy API) if Service Account not available

---

## After Setup

Once you've added either:
- `FCM_SERVER_KEY` in `.env` (Option 1), OR
- `firebase-service-account.json` file (Option 2)

The notification system will automatically:
- Detect the new configuration
- Use the appropriate API method
- Start sending notifications successfully

No code changes needed!

---

## Verify Setup

Check your logs after sending a notification. You should see:

**For Server Key (Legacy API):**
```
[FCM] Using Legacy API with server key
[FCM] FCM Legacy notification sent successfully
```

**For Service Account (HTTP v1 API):**
```
[FCM] Sending FCM v1 API request
[FCM] FCM v1 notification sent successfully
```

---

## Troubleshooting

### Still getting 404?
- ✅ Check that you're using **Server Key**, not Client API Key
- ✅ Verify `FCM_SERVER_KEY` is in `.env` file
- ✅ Make sure `.env` is loaded (run `php artisan config:clear`)

### Getting 401 Unauthorized?
- ✅ Server key might be invalid or expired
- ✅ Get a fresh Server Key from Firebase Console

### Getting "No server key configured"?
- ✅ Add `FCM_SERVER_KEY=...` to your `.env` file
- ✅ Or add Service Account JSON file

---

## Quick Reference

```env
# .env file - Option 1 (Legacy API)
FCM_SERVER_KEY=AAAAxxxxxxxxxxxxx:APA91bFxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

# OR add Service Account JSON to:
# storage/app/firebase-service-account.json (Option 2)
```

