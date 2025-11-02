# Firebase FCM Setup Guide

## Overview

The notification system has been updated to use Firebase's modern HTTP v1 API with OAuth2 authentication, which replaces the deprecated server key method.

## Current Setup

✅ **Completed:**
- Firebase credentials JSON file saved at `config/firebase-credentials.json`
- Updated `PushNotificationService` to use FCM HTTP v1 API (with legacy fallback)
- Created `FirebaseService` for OAuth2 token generation
- Configuration updated in `config/push.php`

## How It Works

### Dual Mode Support

The system now supports two modes:

1. **FCM HTTP v1 API (Preferred)** - Uses OAuth2 with service account JSON
   - More secure
   - Better error handling
   - Future-proof

2. **Legacy API (Fallback)** - Uses API key from `google-services.json`
   - Works immediately with current setup
   - Uses API key: `AIzaSyB83DruztsdfEFiFsVWf3qdLOBNIXHnTYM`
   - Will be deprecated by Firebase in the future

### Current Behavior

Since you don't have a service account JSON yet, the system will automatically:
- Detect that service account is missing
- Fall back to Legacy API
- Use the API key from `config/firebase-credentials.json`

## Next Steps: Get Service Account JSON (Recommended)

To fully migrate to the new FCM HTTP v1 API, you need a **Service Account JSON** file:

### Steps:

1. **Go to Firebase Console**
   - Visit: https://console.firebase.google.com/
   - Select your project: `biltix-50deb`

2. **Create Service Account**
   - Go to **Project Settings** (gear icon)
   - Click on **Service Accounts** tab
   - Click **Generate New Private Key**
   - Download the JSON file

3. **Save Service Account JSON**
   - Save the downloaded file to: `storage/app/firebase-service-account.json`
   - **IMPORTANT:** Add this path to `.gitignore` (it contains sensitive credentials)

4. **Update .env (Optional)**
   ```env
   FIREBASE_SERVICE_ACCOUNT_PATH=storage/app/firebase-service-account.json
   FIREBASE_PROJECT_ID=biltix-50deb
   ```

5. **Grant Permissions**
   - In Firebase Console, make sure the service account has:
     - **Firebase Cloud Messaging API** enabled
     - **Cloud Messaging** role/permissions

### Service Account JSON Structure

The service account JSON should look like:
```json
{
  "type": "service_account",
  "project_id": "biltix-50deb",
  "private_key_id": "...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "...@...iam.gserviceaccount.com",
  "client_id": "...",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  ...
}
```

## Configuration Files

### 1. Firebase Credentials (`config/firebase-credentials.json`)
- Contains project info and API key
- Used for project identification
- Already configured ✅

### 2. Service Account (Optional - for HTTP v1 API)
- Location: `storage/app/firebase-service-account.json`
- Used for OAuth2 authentication
- Not yet configured (using fallback)

## Environment Variables

Add these to your `.env` file (optional):

```env
# Firebase Configuration
FIREBASE_PROJECT_ID=biltix-50deb
FIREBASE_CREDENTIALS_PATH=config/firebase-credentials.json
FIREBASE_SERVICE_ACCOUNT_PATH=storage/app/firebase-service-account.json

# Legacy API (fallback - will use API key from JSON if not set)
FCM_SERVER_KEY=

# Push Notification Settings
PUSH_NOTIFICATIONS_ENABLED=true
```

## Testing

### Test Legacy API (Current Setup)

```bash
php test_fcm.php [device_token]
```

This will:
- Load API key from `config/firebase-credentials.json`
- Send notification via Legacy API
- Work immediately with your current setup

### Test HTTP v1 API (After Service Account Setup)

Once you add the service account JSON, the system will automatically:
- Detect the service account file
- Use FCM HTTP v1 API instead
- Generate OAuth2 tokens automatically
- Cache tokens for 55 minutes

## Important Notes

⚠️ **Security:**
- Never commit `firebase-service-account.json` to Git
- Add to `.gitignore`:
  ```
  /storage/app/firebase-service-account.json
  ```
- The current `firebase-credentials.json` can be committed (it's for client apps)

⚠️ **Migration:**
- Legacy API (server key) is deprecated by Firebase
- Migrate to service account ASAP to avoid future issues
- The code will work with both, but service account is recommended

## Troubleshooting

### Issue: "Failed to get OAuth2 access token"
- **Solution:** Check that service account JSON exists and is valid
- System will automatically fall back to Legacy API

### Issue: "No API key configured"
- **Solution:** Ensure `config/firebase-credentials.json` exists and contains API key
- Or set `FCM_SERVER_KEY` in `.env`

### Issue: Legacy API not working
- **Check:** API key in `google-services.json` might be invalid
- **Check:** Device token is correct and not expired
- **Check:** Firebase project is active

## Files Changed

1. ✅ `config/firebase-credentials.json` - Added (from your JSON)
2. ✅ `app/Services/FirebaseService.php` - Created (OAuth2 handler)
3. ✅ `app/Services/PushNotificationService.php` - Updated (HTTP v1 + fallback)
4. ✅ `config/push.php` - Updated (new config options)
5. ✅ `test_fcm.php` - Updated (reads from JSON)

## Summary

✅ **What's Working Now:**
- System reads API key from JSON file (no hardcoded keys)
- Legacy API works as fallback
- Automatic detection and fallback logic

📋 **What You Need to Do:**
- Get service account JSON from Firebase Console (recommended)
- System will automatically switch to HTTP v1 API once it's available
- No code changes needed!

