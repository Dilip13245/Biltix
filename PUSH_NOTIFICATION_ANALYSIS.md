# 📱 Biltix Push Notification Analysis & Implementation Guide

## Overview
This document analyzes all events/actions in the Biltix system that should trigger push notifications and outlines the complete implementation strategy.

---

## 🗄️ Database Structure

### Current Notification Table
```sql
notifications:
- id (PK)
- user_id (FK users)
- type (enum: task_assigned, inspection_due, snag_reported, project_update, system)
- title (string)
- message (text)
- data (JSON, nullable) -- additional data like IDs, links
- is_read (boolean, default: false)
- read_at (nullable)
- priority (enum: low, medium, high) -- default: medium
- is_active (boolean, default: true)
- is_deleted (boolean, default: false)
- created_at
- updated_at
```

### User Device Table (for Push Tokens)
```sql
user_devices:
- id (PK)
- user_id (FK users)
- token (string) -- API authentication token
- device_type (enum: A, I, W) -- Android, iOS, Web
- device_token (string, nullable) -- FCM/APNS push token ⚠️ ADD THIS
- ip_address
- uuid
- os_version
- device_model
- app_version
- is_active
- is_deleted
```

**⚠️ ACTION REQUIRED:** Add `device_token` field to `user_devices` table if not exists.

---

## 🔔 Complete Notification Event List

### 1. PROJECT MANAGEMENT NOTIFICATIONS

#### 1.1 Project Created
**Event:** New project created  
**Trigger:** `ProjectController@create`  
**Recipients:**
- Project Manager (if assigned)
- Technical Engineer (if assigned)
- Creator (if different from PM/Engineer)

**Notification:**
```json
{
  "type": "project_created",
  "title": "New Project Created",
  "message": "Project '{project_title}' has been created by {creator_name}",
  "priority": "high",
  "data": {
    "project_id": 123,
    "project_code": "PRJ-2024-001",
    "project_title": "Downtown Office Complex",
    "creator_id": 45,
    "creator_name": "John Smith",
    "action_url": "/projects/{project_id}"
  }
}
```

---

#### 1.2 Project Updated
**Event:** Project details modified  
**Trigger:** `ProjectController@update`  
**Recipients:**
- Project Manager
- Technical Engineer
- All team members of the project

**Notification:**
```json
{
  "type": "project_updated",
  "title": "Project Updated",
  "message": "Project '{project_title}' has been updated",
  "priority": "medium",
  "data": {
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "updated_by": 45,
    "updated_fields": ["start_date", "budget"],
    "action_url": "/projects/{project_id}"
  }
}
```

---

#### 1.3 Project Status Changed
**Event:** Project status changed (planning → active → completed → on_hold)  
**Trigger:** `ProjectController@update` (when status changes)  
**Recipients:**
- Project Manager
- Technical Engineer
- All team members
- Stakeholders (if any)

**Notification:**
```json
{
  "type": "project_status_changed",
  "title": "Project Status Changed",
  "message": "Project '{project_title}' status changed to {new_status}",
  "priority": "high",
  "data": {
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "old_status": "planning",
    "new_status": "active",
    "changed_by": 45,
    "action_url": "/projects/{project_id}"
  }
}
```

---

#### 1.4 Project Phase Created
**Event:** New phase added to project  
**Trigger:** `ProjectController@createPhase`  
**Recipients:**
- Project Manager
- Technical Engineer
- All team members of the project

**Notification:**
```json
{
  "type": "phase_created",
  "title": "New Phase Created",
  "message": "New phase '{phase_title}' added to project '{project_title}'",
  "priority": "medium",
  "data": {
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "phase_id": 56,
    "phase_title": "Foundation",
    "created_by": 45,
    "action_url": "/projects/{project_id}/phases/{phase_id}"
  }
}
```

---

#### 1.5 Phase Progress Updated
**Event:** Phase progress percentage updated  
**Trigger:** `ProjectController@updatePhaseProgress`  
**Recipients:**
- Project Manager
- Technical Engineer
- All team members of the project

**Notification:**
```json
{
  "type": "phase_progress_updated",
  "title": "Phase Progress Updated",
  "message": "Phase '{phase_title}' progress updated to {progress}%",
  "priority": "low",
  "data": {
    "project_id": 123,
    "phase_id": 56,
    "phase_title": "Foundation",
    "old_progress": 50,
    "new_progress": 75,
    "updated_by": 45,
    "action_url": "/projects/{project_id}/phases/{phase_id}"
  }
}
```

---

#### 1.6 Milestone Due Date Extended
**Event:** Milestone due date extended  
**Trigger:** `ProjectController@updateMilestoneDueDate`  
**Recipients:**
- Project Manager
- Technical Engineer
- Team members assigned to milestone

**Notification:**
```json
{
  "type": "milestone_extended",
  "title": "Milestone Extended",
  "message": "Milestone '{milestone_title}' due date extended to {new_due_date}",
  "priority": "medium",
  "data": {
    "project_id": 123,
    "phase_id": 56,
    "milestone_id": 78,
    "milestone_title": "Foundation Complete",
    "old_due_date": "2024-03-01",
    "new_due_date": "2024-03-15",
    "extended_by": 45,
    "action_url": "/projects/{project_id}/phases/{phase_id}"
  }
}
```

---

### 2. TASK MANAGEMENT NOTIFICATIONS

#### 2.1 Task Assigned
**Event:** Task assigned to user  
**Trigger:** `TaskController@create`, `TaskController@assignBulk`  
**Recipients:**
- Assigned user
- Task creator (if different)

**Notification:**
```json
{
  "type": "task_assigned",
  "title": "New Task Assigned",
  "message": "You have been assigned task '{task_title}' in project '{project_title}'",
  "priority": "high",
  "data": {
    "task_id": 234,
    "task_number": "TASK-001",
    "task_title": "Install electrical wiring - Floor 5",
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "assigned_to": 67,
    "assigned_by": 45,
    "due_date": "2024-02-15",
    "priority": "high",
    "action_url": "/tasks/{task_id}"
  }
}
```

---

#### 2.2 Task Status Changed
**Event:** Task status updated (todo → in_progress → completed)  
**Trigger:** `TaskController@changeStatus`  
**Recipients:**
- Task creator
- Project Manager
- All task followers/collaborators

**Notification:**
```json
{
  "type": "task_status_changed",
  "title": "Task Status Updated",
  "message": "Task '{task_title}' status changed to {new_status}",
  "priority": "medium",
  "data": {
    "task_id": 234,
    "task_title": "Install electrical wiring - Floor 5",
    "old_status": "todo",
    "new_status": "in_progress",
    "changed_by": 67,
    "project_id": 123,
    "action_url": "/tasks/{task_id}"
  }
}
```

---

#### 2.3 Task Comment Added
**Event:** Comment added to task  
**Trigger:** `TaskController@addComment`  
**Recipients:**
- Task assignee
- Task creator
- All users who commented on the task
- @Mentioned users (if mention feature exists)

**Notification:**
```json
{
  "type": "task_comment",
  "title": "New Comment on Task",
  "message": "{commenter_name} commented on task '{task_title}'",
  "priority": "medium",
  "data": {
    "task_id": 234,
    "task_title": "Install electrical wiring - Floor 5",
    "comment_id": 345,
    "commenter_id": 89,
    "commenter_name": "Sarah Johnson",
    "comment_preview": "First 100 chars of comment...",
    "project_id": 123,
    "action_url": "/tasks/{task_id}#comment_{comment_id}"
  }
}
```

---

#### 2.4 Task Progress Updated
**Event:** Task progress percentage updated  
**Trigger:** `TaskController@updateProgress`  
**Recipients:**
- Task creator
- Project Manager

**Notification:**
```json
{
  "type": "task_progress_updated",
  "title": "Task Progress Updated",
  "message": "Task '{task_title}' progress updated to {progress}%",
  "priority": "low",
  "data": {
    "task_id": 234,
    "task_title": "Install electrical wiring - Floor 5",
    "old_progress": 30,
    "new_progress": 60,
    "updated_by": 67,
    "project_id": 123,
    "action_url": "/tasks/{task_id}"
  }
}
```

---

#### 2.5 Task Due Date Approaching
**Event:** Task due in 24 hours / 48 hours / 7 days (Scheduled Job)  
**Trigger:** Scheduled cron job  
**Recipients:**
- Task assignee
- Project Manager

**Notification:**
```json
{
  "type": "task_due_soon",
  "title": "Task Due Soon",
  "message": "Task '{task_title}' is due in {hours} hours",
  "priority": "high",
  "data": {
    "task_id": 234,
    "task_title": "Install electrical wiring - Floor 5",
    "due_date": "2024-02-15 10:00:00",
    "hours_remaining": 24,
    "project_id": 123,
    "action_url": "/tasks/{task_id}"
  }
}
```

---

#### 2.6 Task Overdue
**Event:** Task past due date (Scheduled Job - daily check)  
**Trigger:** Scheduled cron job  
**Recipients:**
- Task assignee
- Project Manager
- Task creator

**Notification:**
```json
{
  "type": "task_overdue",
  "title": "Task Overdue",
  "message": "Task '{task_title}' is overdue by {days} days",
  "priority": "high",
  "data": {
    "task_id": 234,
    "task_title": "Install electrical wiring - Floor 5",
    "due_date": "2024-02-15",
    "days_overdue": 3,
    "project_id": 123,
    "action_url": "/tasks/{task_id}"
  }
}
```

---

### 3. INSPECTION MANAGEMENT NOTIFICATIONS

#### 3.1 Inspection Created
**Event:** New inspection scheduled  
**Trigger:** `InspectionController@create`  
**Recipients:**
- Inspected By user (if assigned)
- Project Manager
- Technical Engineer
- All team members

**Notification:**
```json
{
  "type": "inspection_created",
  "title": "New Inspection Scheduled",
  "message": "New {category} inspection scheduled for project '{project_title}'",
  "priority": "high",
  "data": {
    "inspection_id": 456,
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "category": "Safety",
    "inspected_by": 67,
    "scheduled_by": 45,
    "action_url": "/inspections/{inspection_id}"
  }
}
```

---

#### 3.2 Inspection Started
**Event:** Inspection started  
**Trigger:** `InspectionController@startInspection`  
**Recipients:**
- Project Manager
- Inspection creator
- Technical Engineer

**Notification:**
```json
{
  "type": "inspection_started",
  "title": "Inspection Started",
  "message": "{inspector_name} has started the {category} inspection",
  "priority": "medium",
  "data": {
    "inspection_id": 456,
    "project_id": 123,
    "category": "Safety",
    "inspector_id": 67,
    "inspector_name": "Mike Davis",
    "action_url": "/inspections/{inspection_id}"
  }
}
```

---

#### 3.3 Inspection Completed
**Event:** Inspection completed  
**Trigger:** `InspectionController@complete`  
**Recipients:**
- Project Manager
- Technical Engineer
- Inspection creator
- All team members (for visibility)

**Notification:**
```json
{
  "type": "inspection_completed",
  "title": "Inspection Completed",
  "message": "{category} inspection has been completed by {inspector_name}",
  "priority": "high",
  "data": {
    "inspection_id": 456,
    "project_id": 123,
    "category": "Safety",
    "result": "pass", // pass, fail, conditional
    "inspector_id": 67,
    "inspector_name": "Mike Davis",
    "completed_at": "2024-02-10 14:30:00",
    "action_url": "/inspections/{inspection_id}"
  }
}
```

---

#### 3.4 Inspection Approved
**Event:** Inspection approved  
**Trigger:** `InspectionController@approve`  
**Recipients:**
- Inspector
- Project Manager
- Inspection creator

**Notification:**
```json
{
  "type": "inspection_approved",
  "title": "Inspection Approved",
  "message": "{category} inspection has been approved by {approver_name}",
  "priority": "high",
  "data": {
    "inspection_id": 456,
    "project_id": 123,
    "category": "Safety",
    "approver_id": 45,
    "approver_name": "John Smith",
    "action_url": "/inspections/{inspection_id}"
  }
}
```

---

#### 3.5 Inspection Due Reminder
**Event:** Inspection due in 24 hours (Scheduled Job)  
**Trigger:** Scheduled cron job  
**Recipients:**
- Inspector
- Project Manager

**Notification:**
```json
{
  "type": "inspection_due",
  "title": "Inspection Due Soon",
  "message": "{category} inspection is scheduled in {hours} hours",
  "priority": "high",
  "data": {
    "inspection_id": 456,
    "project_id": 123,
    "category": "Safety",
    "scheduled_date": "2024-02-11 10:00:00",
    "hours_remaining": 24,
    "action_url": "/inspections/{inspection_id}"
  }
}
```

---

### 4. SNAG MANAGEMENT NOTIFICATIONS

#### 4.1 Snag Reported
**Event:** New snag reported  
**Trigger:** `SnagController@create`  
**Recipients:**
- Assigned user (if assigned)
- Project Manager
- Technical Engineer
- Snag reporter (confirmation)

**Notification:**
```json
{
  "type": "snag_reported",
  "title": "New Snag Reported",
  "message": "Snag '{snag_title}' reported in project '{project_title}'",
  "priority": "high",
  "data": {
    "snag_id": 567,
    "snag_number": "SNG-001",
    "snag_title": "Electrical outlet not working in Room 205",
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "location": "Building A, Floor 2, Room 205",
    "reported_by": 67,
    "assigned_to": 89, // nullable
    "status": "todo",
    "action_url": "/snags/{snag_id}"
  }
}
```

---

#### 4.2 Snag Assigned
**Event:** Snag assigned to user  
**Trigger:** `SnagController@assign`  
**Recipients:**
- Assigned user
- Project Manager

**Notification:**
```json
{
  "type": "snag_assigned",
  "title": "Snag Assigned to You",
  "message": "Snag '{snag_title}' has been assigned to you",
  "priority": "high",
  "data": {
    "snag_id": 567,
    "snag_title": "Electrical outlet not working in Room 205",
    "project_id": 123,
    "assigned_by": 45,
    "action_url": "/snags/{snag_id}"
  }
}
```

---

#### 4.3 Snag Comment Added
**Event:** Comment added to snag  
**Trigger:** `SnagController@addComment`  
**Recipients:**
- Snag assignee
- Snag reporter
- Project Manager
- All users who commented on the snag

**Notification:**
```json
{
  "type": "snag_comment",
  "title": "New Comment on Snag",
  "message": "{commenter_name} commented on snag '{snag_title}'",
  "priority": "medium",
  "data": {
    "snag_id": 567,
    "snag_title": "Electrical outlet not working in Room 205",
    "comment_id": 678,
    "commenter_id": 89,
    "commenter_name": "Sarah Johnson",
    "comment_preview": "Will fix this today...",
    "project_id": 123,
    "action_url": "/snags/{snag_id}#comment_{comment_id}"
  }
}
```

---

#### 4.4 Snag Resolved
**Event:** Snag marked as resolved  
**Trigger:** `SnagController@resolve`  
**Recipients:**
- Snag reporter
- Project Manager
- Technical Engineer

**Notification:**
```json
{
  "type": "snag_resolved",
  "title": "Snag Resolved",
  "message": "Snag '{snag_title}' has been resolved by {resolver_name}",
  "priority": "medium",
  "data": {
    "snag_id": 567,
    "snag_title": "Electrical outlet not working in Room 205",
    "project_id": 123,
    "resolver_id": 89,
    "resolver_name": "Sarah Johnson",
    "resolved_at": "2024-02-10 16:00:00",
    "action_url": "/snags/{snag_id}"
  }
}
```

---

### 5. TEAM MANAGEMENT NOTIFICATIONS

#### 5.1 Team Member Added
**Event:** User added to project team  
**Trigger:** `TeamController@addMember`, `TeamController@assignProject`  
**Recipients:**
- New team member
- Project Manager
- All existing team members (optional - can be configurable)

**Notification:**
```json
{
  "type": "team_member_added",
  "title": "Added to Project Team",
  "message": "You have been added to project '{project_title}' team as {role_in_project}",
  "priority": "high",
  "data": {
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "role_in_project": "Site Engineer",
    "added_by": 45,
    "added_by_name": "John Smith",
    "action_url": "/projects/{project_id}/team"
  }
}
```

---

#### 5.2 Team Member Removed
**Event:** User removed from project team  
**Trigger:** `TeamController@removeMember`  
**Recipients:**
- Removed team member
- Project Manager

**Notification:**
```json
{
  "type": "team_member_removed",
  "title": "Removed from Project Team",
  "message": "You have been removed from project '{project_title}' team",
  "priority": "medium",
  "data": {
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "removed_by": 45,
    "action_url": "/projects"
  }
}
```

---

#### 5.3 Team Role Updated
**Event:** Team member role changed  
**Trigger:** `TeamController@updateRole`  
**Recipients:**
- Team member whose role changed
- Project Manager

**Notification:**
```json
{
  "type": "team_role_updated",
  "title": "Team Role Updated",
  "message": "Your role in project '{project_title}' has been changed to {new_role}",
  "priority": "medium",
  "data": {
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "old_role": "Site Engineer",
    "new_role": "Technical Engineer",
    "updated_by": 45,
    "action_url": "/projects/{project_id}/team"
  }
}
```

---

### 6. PLAN/DOCUMENT MANAGEMENT NOTIFICATIONS

#### 6.1 Plan Uploaded
**Event:** New plan/drawing uploaded  
**Trigger:** `PlanController@upload`  
**Recipients:**
- Project Manager
- Technical Engineer
- All team members

**Notification:**
```json
{
  "type": "plan_uploaded",
  "title": "New Plan Uploaded",
  "message": "New plan '{plan_title}' uploaded to project '{project_title}'",
  "priority": "medium",
  "data": {
    "plan_id": 789,
    "plan_title": "Ground Floor Plan - Rev 3.2",
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "uploaded_by": 45,
    "action_url": "/projects/{project_id}/plans/{plan_id}"
  }
}
```

---

#### 6.2 Plan Approved
**Event:** Plan approved  
**Trigger:** `PlanController@approve`  
**Recipients:**
- Plan uploader
- Project Manager
- Technical Engineer

**Notification:**
```json
{
  "type": "plan_approved",
  "title": "Plan Approved",
  "message": "Plan '{plan_title}' has been approved by {approver_name}",
  "priority": "medium",
  "data": {
    "plan_id": 789,
    "plan_title": "Ground Floor Plan - Rev 3.2",
    "project_id": 123,
    "approver_id": 45,
    "approver_name": "John Smith",
    "action_url": "/projects/{project_id}/plans/{plan_id}"
  }
}
```

---

#### 6.3 Plan Markup Added
**Event:** Markup/annotation added to plan  
**Trigger:** `PlanController@addMarkup`  
**Recipients:**
- Plan uploader
- Project Manager
- @Mentioned users (if mention feature exists)

**Notification:**
```json
{
  "type": "plan_markup_added",
  "title": "Plan Markup Added",
  "message": "{markup_author} added a markup to plan '{plan_title}'",
  "priority": "low",
  "data": {
    "plan_id": 789,
    "plan_title": "Ground Floor Plan - Rev 3.2",
    "markup_id": 890,
    "markup_author_id": 67,
    "markup_author": "Mike Davis",
    "project_id": 123,
    "action_url": "/projects/{project_id}/plans/{plan_id}#markup_{markup_id}"
  }
}
```

---

### 7. FILE MANAGEMENT NOTIFICATIONS

#### 7.1 File Uploaded
**Event:** New file uploaded  
**Trigger:** `FileController@upload`  
**Recipients:**
- Project Manager (if file shared with project)
- Team members (if file shared with team)
- Specific users (if file shared with specific users)

**Notification:**
```json
{
  "type": "file_uploaded",
  "title": "New File Uploaded",
  "message": "{uploader_name} uploaded '{file_name}' to {location}",
  "priority": "low",
  "data": {
    "file_id": 901,
    "file_name": "Contract Document.pdf",
    "file_category": "Documents",
    "project_id": 123,
    "uploaded_by": 45,
    "uploaded_by_name": "John Smith",
    "shared_with": "team", // team, project, specific_users
    "action_url": "/files/{file_id}"
  }
}
```

---

#### 7.2 File Shared
**Event:** File shared with user(s)  
**Trigger:** `FileController@share`  
**Recipients:**
- Shared users

**Notification:**
```json
{
  "type": "file_shared",
  "title": "File Shared with You",
  "message": "{sharer_name} shared '{file_name}' with you",
  "priority": "medium",
  "data": {
    "file_id": 901,
    "file_name": "Contract Document.pdf",
    "sharer_id": 45,
    "sharer_name": "John Smith",
    "action_url": "/files/{file_id}"
  }
}
```

---

### 8. DAILY LOG NOTIFICATIONS

#### 8.1 Daily Log Created
**Event:** Daily log entry created  
**Trigger:** `DailyLogController@create`  
**Recipients:**
- Project Manager
- Technical Engineer

**Notification:**
```json
{
  "type": "daily_log_created",
  "title": "Daily Log Entry Created",
  "message": "Daily log for {log_date} created for project '{project_title}'",
  "priority": "low",
  "data": {
    "log_id": 1001,
    "project_id": 123,
    "project_title": "Downtown Office Complex",
    "log_date": "2024-02-10",
    "logged_by": 67,
    "logged_by_name": "Mike Davis",
    "action_url": "/projects/{project_id}/daily-logs/{log_id}"
  }
}
```

---

### 9. COMMENT/ACTIVITY NOTIFICATIONS

#### 9.1 Task Comment Mention
**Event:** User mentioned in task comment  
**Trigger:** `TaskController@addComment` (if @mention detected)  
**Recipients:**
- Mentioned user(s)

**Notification:**
```json
{
  "type": "task_mention",
  "title": "Mentioned in Task Comment",
  "message": "{commenter_name} mentioned you in a comment on task '{task_title}'",
  "priority": "high",
  "data": {
    "task_id": 234,
    "task_title": "Install electrical wiring - Floor 5",
    "comment_id": 345,
    "commenter_id": 89,
    "commenter_name": "Sarah Johnson",
    "project_id": 123,
    "action_url": "/tasks/{task_id}#comment_{comment_id}"
  }
}
```

---

### 10. SYSTEM NOTIFICATIONS

#### 10.1 Account Created
**Event:** New user account created  
**Trigger:** `AuthController@signup`  
**Recipients:**
- New user

**Notification:**
```json
{
  "type": "account_created",
  "title": "Welcome to Biltix!",
  "message": "Your account has been successfully created. Welcome to Biltix!",
  "priority": "low",
  "data": {
    "user_id": 123,
    "user_name": "John Smith",
    "action_url": "/dashboard"
  }
}
```

---

#### 10.2 Password Reset
**Event:** Password reset requested  
**Trigger:** `AuthController@resetPassword`  
**Recipients:**
- User who requested reset

**Notification:**
```json
{
  "type": "password_reset",
  "title": "Password Reset Request",
  "message": "Your password reset OTP is {otp}. Valid for 10 minutes.",
  "priority": "high",
  "data": {
    "user_id": 123,
    "otp": "123456",
    "expires_at": "2024-02-10 15:10:00",
    "action_url": "/reset-password"
  }
}
```

---

#### 10.3 OTP Sent
**Event:** OTP sent for verification  
**Trigger:** `AuthController@sendOtp`  
**Recipients:**
- User who requested OTP

**Notification:**
```json
{
  "type": "otp_sent",
  "title": "Verification OTP",
  "message": "Your verification OTP is {otp}. Valid for 10 minutes.",
  "priority": "high",
  "data": {
    "user_id": 123,
    "otp": "123456",
    "purpose": "password_reset", // password_reset, signup_verification
    "expires_at": "2024-02-10 15:10:00"
  }
}
```

---

## 📊 Notification Priority Levels

### High Priority (Immediate Push)
- Task assigned
- Task overdue
- Task due soon (< 24 hours)
- Snag reported
- Snag assigned
- Inspection created
- Inspection due soon
- Inspection completed
- Inspection approved
- Project status changed
- Project created
- Team member added
- @Mentions
- Password reset/OTP

### Medium Priority (Normal Push)
- Task status changed
- Task comment added
- Task progress updated
- Snag comment added
- Snag resolved
- Project updated
- Phase created
- Plan uploaded
- Plan approved
- File shared
- Team role updated

### Low Priority (Batch/Quiet Push)
- Phase progress updated
- Task progress updated
- Plan markup added
- File uploaded
- Daily log created
- Account created
- System updates

---

## 👥 Notification Recipient Rules

### General Rules:
1. **Task Assignments:** Assigned user always receives notification
2. **Project Updates:** All project team members receive notification
3. **Comments:** Original creator + all previous commenters receive notification
4. **@Mentions:** Only mentioned users receive notification
5. **Status Changes:** Creator + assignee + project manager receive notification
6. **Approvals:** Original submitter + approver receive notification

### Role-Based Notifications:
- **Project Manager:** Receives notifications for all project-related events
- **Technical Engineer:** Receives notifications for technical issues, inspections, plans
- **Site Engineer:** Receives notifications for tasks, snags, daily logs assigned to them
- **Consultant:** Receives notifications for inspections, plans, approvals
- **Stakeholder:** Receives read-only notifications for major project updates

---

## 🛠️ Implementation Requirements

### 1. Database Changes

**Migration Required:**
```php
// Add device_token to user_devices table
Schema::table('user_devices', function (Blueprint $table) {
    $table->string('device_token')->nullable()->after('device_type');
    $table->enum('push_notification_enabled', ['0', '1'])->default('1')->after('device_token');
});
```

**Update Notification Type Enum:**
```php
// Expand notification types
$table->enum('type', [
    'task_assigned', 'task_status_changed', 'task_comment', 'task_progress_updated',
    'task_due_soon', 'task_overdue', 'task_mention',
    'inspection_created', 'inspection_started', 'inspection_completed', 
    'inspection_approved', 'inspection_due',
    'snag_reported', 'snag_assigned', 'snag_comment', 'snag_resolved',
    'project_created', 'project_updated', 'project_status_changed',
    'phase_created', 'phase_progress_updated', 'milestone_extended',
    'team_member_added', 'team_member_removed', 'team_role_updated',
    'plan_uploaded', 'plan_approved', 'plan_markup_added',
    'file_uploaded', 'file_shared',
    'daily_log_created',
    'account_created', 'password_reset', 'otp_sent',
    'system'
])->change();
```

### 2. Service Class Structure

**Create:** `app/Services/PushNotificationService.php`

```php
<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;

class PushNotificationService
{
    /**
     * Send push notification to user(s)
     */
    public function send($userIds, $type, $title, $message, $data = [], $priority = 'medium')
    {
        // 1. Save notification to database
        // 2. Get user device tokens
        // 3. Send push notification via FCM/APNS
        // 4. Handle failures
    }

    /**
     * Send to project team members
     */
    public function sendToProjectTeam($projectId, $type, $title, $message, $data = [], $priority = 'medium')
    {
        // Get all team members of project
        // Send notification to each
    }

    /**
     * Send via FCM (Firebase Cloud Messaging)
     */
    private function sendFCM($deviceTokens, $title, $message, $data = [])
    {
        // Implement FCM push
    }

    /**
     * Send via APNS (Apple Push Notification Service)
     */
    private function sendAPNS($deviceTokens, $title, $message, $data = [])
    {
        // Implement APNS push
    }
}
```

### 3. Helper/Notification Service

**Create:** `app/Helpers/NotificationHelper.php`

```php
<?php

namespace App\Helpers;

use App\Services\PushNotificationService;
use App\Models\Notification;

class NotificationHelper
{
    /**
     * Send notification and push
     */
    public static function send($userIds, $type, $title, $message, $data = [], $priority = 'medium')
    {
        $pushService = new PushNotificationService();
        
        // Save to database
        $notifications = [];
        foreach ($userIds as $userId) {
            $notification = Notification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'data' => $data,
                'priority' => $priority,
                'is_read' => false,
                'is_active' => true
            ]);
            $notifications[] = $notification;
        }
        
        // Send push notification
        $pushService->send($userIds, $type, $title, $message, $data, $priority);
        
        return $notifications;
    }
}
```

### 4. Integration Points

**Add to Controllers:**
```php
// Example: TaskController@create
use App\Helpers\NotificationHelper;

// After task created successfully:
$recipients = [$request->assigned_to];
if ($request->user_id != $request->assigned_to) {
    $recipients[] = $request->user_id;
}

NotificationHelper::send(
    $recipients,
    'task_assigned',
    'New Task Assigned',
    "You have been assigned task '{$taskDetails->title}'",
    [
        'task_id' => $taskDetails->id,
        'task_number' => $taskDetails->task_number,
        'task_title' => $taskDetails->title,
        'project_id' => $taskDetails->project_id,
        'due_date' => $taskDetails->due_date,
        'priority' => $taskDetails->priority,
    ],
    'high'
);
```

---

## 📅 Scheduled Jobs (Cron Tasks)

### Required Scheduled Tasks:

1. **Task Due Reminder** (Every hour)
   - Check tasks due in 24 hours
   - Send notification to assignee

2. **Task Overdue Check** (Daily at 9 AM)
   - Check tasks past due date
   - Send notification to assignee + PM

3. **Inspection Due Reminder** (Every hour)
   - Check inspections due in 24 hours
   - Send notification to inspector + PM

4. **Notification Cleanup** (Weekly)
   - Delete read notifications older than 30 days
   - Archive old notifications

**Create:** `app/Console/Commands/SendTaskDueReminders.php`
**Create:** `app/Console/Commands/SendTaskOverdueNotifications.php`
**Create:** `app/Console/Commands/SendInspectionDueReminders.php`

---

## 🔧 Firebase Cloud Messaging (FCM) Setup

### Required:
1. Firebase project setup
2. Server key for FCM API
3. iOS APNS certificates
4. Android app configuration

### Configuration:
```php
// config/push.php
return [
    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY'),
        'api_url' => 'https://fcm.googleapis.com/fcm/send',
    ],
    'apns' => [
        'certificate_path' => env('APNS_CERTIFICATE_PATH'),
        'passphrase' => env('APNS_PASSPHRASE'),
        'environment' => env('APNS_ENVIRONMENT', 'sandbox'), // sandbox or production
    ],
];
```

---

## 📝 Summary

### Total Notification Types: **40+**

**Breakdown by Module:**
- Projects: 6 notifications
- Tasks: 6 notifications
- Inspections: 5 notifications
- Snags: 4 notifications
- Team: 3 notifications
- Plans: 3 notifications
- Files: 2 notifications
- Daily Logs: 1 notification
- Comments: 1 notification
- System: 3 notifications

### Priority Distribution:
- High Priority: 15 notifications
- Medium Priority: 15 notifications
- Low Priority: 10+ notifications

### Implementation Checklist:
- [ ] Add `device_token` field to `user_devices` table
- [ ] Update notification type enum
- [ ] Create `PushNotificationService` class
- [ ] Create `NotificationHelper` class
- [ ] Integrate notifications into all controllers
- [ ] Set up Firebase Cloud Messaging
- [ ] Set up Apple Push Notification Service
- [ ] Create scheduled jobs for reminders
- [ ] Test push notifications on Android
- [ ] Test push notifications on iOS
- [ ] Implement notification settings/preferences

---

**This comprehensive guide covers all events that should trigger push notifications in your Biltix construction management system.**

