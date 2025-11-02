# Notification Deep Linking Guide

## ✅ Implementation Complete

All push notifications now include proper deep linking support for Android app navigation.

---

## 🔗 Deep Link Structure

### Format
```
biltix://open?screen={screen_type}&{id_param}={id_value}
```

### Examples

#### Task Notifications
```
biltix://open?screen=task&task_id=123&project_id=456
```

#### Snag Notifications
```
biltix://open?screen=snag&snag_id=789&project_id=456
```

#### Project Notifications
```
biltix://open?screen=project&project_id=456
```

#### Team Notifications
```
biltix://open?screen=team&project_id=456
```

#### Inspection Notifications
```
biltix://open?screen=inspection&inspection_id=321&project_id=456
```

#### Plan Notifications
```
biltix://open?screen=plan&plan_id=654&project_id=456
```

#### File Notifications
```
biltix://open?screen=file&file_id=987&project_id=456
```

#### Default (Dashboard)
```
biltix://open?screen=dashboard
```

---

## 📱 Android App Payload Structure

Every notification now includes:

### Data Payload Fields:
```json
{
  "notification_type": "task_assigned",
  "notification_id": "42",
  "deep_link": "biltix://open?screen=task&task_id=123&project_id=456",
  "click_action": "FLUTTER_NOTIFICATION_CLICK",
  "android_channel_id": "default",
  "project_id": "456",
  "task_id": "123",
  "task_title": "Install electrical wiring",
  "task_number": "TASK-001",
  "assigned_to": "67",
  "assigned_by": "45",
  "due_date": "2024-02-15",
  "priority": "high",
  "action_url": "/tasks/123"
}
```

### Key Fields:
- **`notification_id`**: Database notification ID (for marking as read)
- **`deep_link`**: Deep link URL for app navigation
- **`click_action`**: Intent action for handling notification clicks
- **`notification_type`**: Type of notification (for app routing logic)
- **`action_url`**: Web URL fallback (for reference)

---

## 🎯 Supported Notification Types

All these types automatically generate proper deep links:

### Task Notifications
- ✅ `task_assigned`
- ✅ `task_status_changed`
- ✅ `task_comment`
- ✅ `task_due_soon`
- ✅ `task_overdue`

### Snag Notifications
- ✅ `snag_reported`
- ✅ `snag_assigned`
- ✅ `snag_resolved`
- ✅ `snag_comment`

### Inspection Notifications
- ✅ `inspection_created`
- ✅ `inspection_due`
- ✅ `inspection_completed`
- ✅ `inspection_approved`

### Project Notifications
- ✅ `project_created`
- ✅ `project_status_changed`
- ✅ `project_updated`

### Team Notifications
- ✅ `team_member_added`
- ✅ `team_member_removed`
- ✅ `team_role_updated`

### Plan Notifications
- ✅ `plan_uploaded`
- ✅ `plan_approved`
- ✅ `plan_markup_added`

### File Notifications
- ✅ `file_uploaded`
- ✅ `file_shared`

### Other Notifications
- ✅ `daily_log_created`
- ✅ `account_created`
- ✅ `password_reset`
- ✅ `otp_sent`

---

## 🔧 How It Works

### Automatic Deep Link Generation

1. **NotificationHelper::prepareNotificationPayload()** is called automatically
2. It analyzes the notification type and data
3. Generates appropriate deep link based on:
   - Notification type
   - Available IDs (task_id, snag_id, project_id, etc.)
   - Fallback to action_url if available
   - Default to dashboard if nothing matches

### Deep Link Priority

1. **Direct ID Match**: If `task_id` exists → `screen=task&task_id={id}`
2. **Action URL Parse**: If `action_url` exists → Parse and convert
3. **Default**: `screen=dashboard`

---

## 📲 Android App Integration

### Required: Handle Deep Links

Your Android app needs to:

1. **Register Intent Filter** (AndroidManifest.xml):
```xml
<intent-filter>
    <action android:name="android.intent.action.VIEW" />
    <category android:name="android.intent.category.DEFAULT" />
    <category android:name="android.intent.category.BROWSABLE" />
    <data android:scheme="biltix" />
</intent-filter>
```

2. **Handle Notification Click** (FCM Service):
```dart
// Flutter example
FirebaseMessaging.onMessageOpenedApp.listen((RemoteMessage message) {
  String? deepLink = message.data['deep_link'];
  if (deepLink != null) {
    // Navigate to screen based on deep link
    Navigator.pushNamed(context, parseDeepLink(deepLink));
  }
});
```

3. **Handle Background Notification Click**:
```dart
FirebaseMessaging.instance.getInitialMessage().then((RemoteMessage? message) {
  if (message != null && message.data.containsKey('deep_link')) {
    String deepLink = message.data['deep_link'];
    // Navigate when app opens from notification
  }
});
```

---

## ✅ Verification Checklist

### Server-Side ✅
- [x] Deep link helper function created
- [x] All notification types supported
- [x] Payload preparation automatic
- [x] notification_id included in payload
- [x] Deep link in data payload
- [x] click_action configured
- [x] Works with FCM HTTP v1 API
- [x] Works with FCM Legacy API (fallback)

### Required: Android App
- [ ] Register `biltix://` scheme in AndroidManifest
- [ ] Implement deep link parser
- [ ] Handle notification clicks
- [ ] Navigate to correct screens
- [ ] Test all notification types

---

## 🧪 Testing

### Test Deep Link Generation

```php
// Test deep link generation
$deepLink = NotificationHelper::generateDeepLink('task_assigned', [
    'task_id' => 123,
    'project_id' => 456
]);
// Result: biltix://open?screen=task&task_id=123&project_id=456
```

### Test Notification Payload

When sending a notification, check logs:
```
[FCM] Notification saved to database
deep_link: biltix://open?screen=task&task_id=123&project_id=456
```

### Verify Payload in Android App

In your FCM message handler, log the data:
```dart
print('Notification Data: ${message.data}');
print('Deep Link: ${message.data['deep_link']}');
print('Notification ID: ${message.data['notification_id']}');
```

---

## 📝 Notification Payload Example

### Complete Example
```json
{
  "notification": {
    "title": "New Task Assigned",
    "body": "You have been assigned task 'Install electrical wiring' in project"
  },
  "data": {
    "notification_type": "task_assigned",
    "notification_id": "42",
    "deep_link": "biltix://open?screen=task&task_id=123&project_id=456",
    "click_action": "FLUTTER_NOTIFICATION_CLICK",
    "android_channel_id": "default",
    "task_id": "123",
    "task_number": "TASK-001",
    "task_title": "Install electrical wiring",
    "project_id": "456",
    "project_title": "Downtown Office Complex",
    "assigned_to": "67",
    "assigned_by": "45",
    "due_date": "2024-02-15",
    "priority": "high",
    "action_url": "/tasks/123"
  }
}
```

---

## 🚀 Summary

✅ **All notifications now automatically include:**
- Deep link URLs for app navigation
- Notification IDs for tracking
- Click actions for handling
- All relevant IDs (task_id, project_id, etc.)
- Proper data structure for Android app

✅ **No changes needed in existing notification calls** - The system automatically enhances all notifications with deep linking.

✅ **Works with both:**
- FCM HTTP v1 API (Service Account) ✅
- FCM Legacy API (Server Key fallback) ✅

---

## 📞 Next Steps

1. **Android App**: Implement deep link handler
2. **Testing**: Send test notifications and verify deep links
3. **Documentation**: Update Android app documentation with deep link handling

The server-side is complete and ready! 🎉

