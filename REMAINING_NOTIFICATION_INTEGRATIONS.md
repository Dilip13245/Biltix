# Remaining Notification Integrations Guide

This file contains all remaining notification integrations that need to be added to controllers.

## Implementation Instructions

### 1. InspectionController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `create()` method (after line 72):
```php
// Send notifications for inspection creation
$project = \App\Models\Project::find($request->project_id);
$recipients = [];
if ($inspectionDetails->inspected_by) {
    $recipients[] = $inspectionDetails->inspected_by;
}

if ($project) {
    NotificationHelper::sendToProjectTeam(
        $project->id,
        'inspection_created',
        'New Inspection Scheduled',
        "New {$inspectionDetails->category} inspection scheduled for project '{$project->project_title}'",
        [
            'inspection_id' => $inspectionDetails->id,
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'category' => $inspectionDetails->category,
            'created_by' => $request->user_id,
            'action_url' => "/inspections/{$inspectionDetails->id}"
        ],
        'high',
        array_merge([$request->user_id], $recipients)
    );
}
```

#### In `startInspection()` method (after line 371):
```php
// Send notification for inspection started
$project = \App\Models\Project::find($inspection->project_id);
$inspector = \App\Models\User::find($user_id);
if ($project && $inspector) {
    NotificationHelper::sendToProjectManagers(
        $project->id,
        'inspection_started',
        'Inspection Started',
        "{$inspector->name} has started the {$inspection->category} inspection",
        [
            'inspection_id' => $inspection->id,
            'project_id' => $project->id,
            'category' => $inspection->category,
            'inspector_id' => $user_id,
            'inspector_name' => $inspector->name,
            'action_url' => "/inspections/{$inspection->id}"
        ],
        'medium'
    );
}
```

#### In `complete()` method (after line 267):
```php
// Send notification for inspection completed
$project = \App\Models\Project::find($inspection->project_id);
$inspector = \App\Models\User::find($user_id);
if ($project && $inspector) {
    NotificationHelper::sendToProjectTeam(
        $project->id,
        'inspection_completed',
        'Inspection Completed',
        "{$inspection->category} inspection has been completed by {$inspector->name}",
        [
            'inspection_id' => $inspection->id,
            'project_id' => $project->id,
            'category' => $inspection->category,
            'inspector_id' => $user_id,
            'inspector_name' => $inspector->name,
            'completed_at' => now()->toDateTimeString(),
            'action_url' => "/inspections/{$inspection->id}"
        ],
        'high',
        [$user_id]
    );
}
```

#### In `approve()` method (after line 293):
```php
// Send notification for inspection approved
$project = \App\Models\Project::find($inspection->project_id);
$approver = \App\Models\User::find($user_id);
if ($project && $approver) {
    NotificationHelper::send(
        [$inspection->created_by, $inspection->inspected_by],
        'inspection_approved',
        'Inspection Approved',
        "{$inspection->category} inspection has been approved by {$approver->name}",
        [
            'inspection_id' => $inspection->id,
            'project_id' => $project->id,
            'category' => $inspection->category,
            'approver_id' => $user_id,
            'approver_name' => $approver->name,
            'action_url' => "/inspections/{$inspection->id}"
        ],
        'high',
        [$user_id]
    );
}
```

### 2. SnagController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `create()` method (after line 87):
```php
// Send notifications for snag reporting
$project = \App\Models\Project::find($request->project_id);
$reporter = \App\Models\User::find($request->user_id);

$recipients = [];
if ($snagDetails->assigned_to) {
    $recipients[] = $snagDetails->assigned_to;
}

if ($project) {
    NotificationHelper::sendToProjectManagers(
        $project->id,
        'snag_reported',
        'New Snag Reported',
        "Snag '{$snagDetails->title}' reported in project '{$project->project_title}'",
        [
            'snag_id' => $snagDetails->id,
            'snag_number' => $snagDetails->snag_number,
            'snag_title' => $snagDetails->title,
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'location' => $snagDetails->location,
            'reported_by' => $request->user_id,
            'assigned_to' => $snagDetails->assigned_to,
            'status' => $snagDetails->status,
            'action_url' => "/snags/{$snagDetails->id}"
        ],
        'high',
        [$request->user_id]
    );

    // Notify assigned user if different from reporter
    if ($snagDetails->assigned_to && $snagDetails->assigned_to != $request->user_id) {
        NotificationHelper::send(
            $snagDetails->assigned_to,
            'snag_assigned',
            'Snag Assigned to You',
            "Snag '{$snagDetails->title}' has been assigned to you",
            [
                'snag_id' => $snagDetails->id,
                'snag_title' => $snagDetails->title,
                'project_id' => $project->id,
                'assigned_by' => $request->user_id,
                'action_url' => "/snags/{$snagDetails->id}"
            ],
            'high'
        );
    }
}
```

#### In `assign()` method (add after assignment):
```php
// Send notification for snag assignment
$project = \App\Models\Project::find($snag->project_id);
if ($project && $assignedTo != $snag->reported_by) {
    NotificationHelper::send(
        $assignedTo,
        'snag_assigned',
        'Snag Assigned to You',
        "Snag '{$snag->title}' has been assigned to you",
        [
            'snag_id' => $snag->id,
            'snag_title' => $snag->title,
            'project_id' => $project->id,
            'assigned_by' => $user_id,
            'action_url' => "/snags/{$snag->id}"
        ],
        'high'
    );
}
```

#### In `addComment()` method:
```php
// Send notifications for snag comment (similar to task comment)
$recipients = [$snag->reported_by];
if ($snag->assigned_to) {
    $recipients[] = $snag->assigned_to;
}

$project = \App\Models\Project::find($snag->project_id);
if ($project && $project->project_manager_id) {
    $recipients[] = $project->project_manager_id;
}

$commenter = \App\Models\User::find($user_id);
NotificationHelper::send(
    array_unique(array_diff($recipients, [$user_id])),
    'snag_comment',
    'New Comment on Snag',
    "{$commenter->name} commented on snag '{$snag->title}'",
    [
        'snag_id' => $snag->id,
        'snag_title' => $snag->title,
        'comment_id' => $snagComment->id,
        'commenter_id' => $user_id,
        'commenter_name' => $commenter->name,
        'project_id' => $snag->project_id,
        'action_url' => "/snags/{$snag->id}#comment_{$snagComment->id}"
    ],
    'medium'
);
```

#### In `resolve()` method:
```php
// Send notification for snag resolution
$project = \App\Models\Project::find($snag->project_id);
$resolver = \App\Models\User::find($user_id);
if ($project && $resolver) {
    $recipients = [$snag->reported_by];
    if ($project->project_manager_id) {
        $recipients[] = $project->project_manager_id;
    }
    if ($project->technical_engineer_id) {
        $recipients[] = $project->technical_engineer_id;
    }

    NotificationHelper::send(
        array_unique(array_diff($recipients, [$user_id])),
        'snag_resolved',
        'Snag Resolved',
        "Snag '{$snag->title}' has been resolved by {$resolver->name}",
        [
            'snag_id' => $snag->id,
            'snag_title' => $snag->title,
            'project_id' => $project->id,
            'resolver_id' => $user_id,
            'resolver_name' => $resolver->name,
            'resolved_at' => now()->toDateTimeString(),
            'action_url' => "/snags/{$snag->id}"
        ],
        'medium'
    );
}
```

### 3. TeamController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `addMember()` method (after line 87):
```php
// Send notification for team member added
$project = \App\Models\Project::find($request->project_id);
$addedBy = \App\Models\User::find($request->user_id);
$newMember = \App\Models\User::find($request->member_user_id);

if ($project && $addedBy && $newMember) {
    // Notify new member
    NotificationHelper::send(
        $request->member_user_id,
        'team_member_added',
        'Added to Project Team',
        "You have been added to project '{$project->project_title}' team as {$request->role_in_project}",
        [
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'role_in_project' => $request->role_in_project,
            'added_by' => $request->user_id,
            'added_by_name' => $addedBy->name,
            'action_url' => "/projects/{$project->id}/team"
        ],
        'high'
    );

    // Notify project team
    NotificationHelper::sendToProjectTeam(
        $project->id,
        'team_member_added',
        'New Team Member Added',
        "{$newMember->name} has been added to project team",
        [
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'member_id' => $request->member_user_id,
            'member_name' => $newMember->name,
            'role_in_project' => $request->role_in_project,
            'added_by' => $request->user_id,
            'action_url' => "/projects/{$project->id}/team"
        ],
        'medium',
        [$request->member_user_id, $request->user_id]
    );
}
```

#### In `removeMember()` method (after line 113):
```php
// Send notification for team member removed
$project = \App\Models\Project::find($teamMember->project_id);
$removedMember = \App\Models\User::find($teamMember->user_id);

if ($project && $removedMember) {
    NotificationHelper::send(
        $teamMember->user_id,
        'team_member_removed',
        'Removed from Project Team',
        "You have been removed from project '{$project->project_title}' team",
        [
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'removed_by' => $user_id,
            'action_url' => "/projects"
        ],
        'medium'
    );
}
```

#### In `updateRole()` method (after line 199):
```php
// Send notification for role update
$project = \App\Models\Project::find($teamMember->project_id);
if ($project) {
    NotificationHelper::send(
        $teamMember->user_id,
        'team_role_updated',
        'Team Role Updated',
        "Your role in project '{$project->project_title}' has been changed to {$request->role_in_project}",
        [
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'old_role' => $teamMember->role_in_project,
            'new_role' => $request->role_in_project,
            'updated_by' => $user_id,
            'action_url' => "/projects/{$project->id}/team"
        ],
        'medium'
    );
}
```

### 4. PlanController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `upload()` method (after line 61):
```php
// Send notification for plan upload
foreach ($uploadedPlans as $plan) {
    $project = \App\Models\Project::find($plan->project_id);
    if ($project) {
        NotificationHelper::sendToProjectTeam(
            $project->id,
            'plan_uploaded',
            'New Plan Uploaded',
            "New plan '{$plan->title}' uploaded to project '{$project->project_title}'",
            [
                'plan_id' => $plan->id,
                'plan_title' => $plan->title,
                'project_id' => $project->id,
                'project_title' => $project->project_title,
                'uploaded_by' => $request->user_id,
                'action_url' => "/projects/{$project->id}/plans/{$plan->id}"
            ],
            'medium',
            [$request->user_id]
        );
    }
}
```

#### In `approve()` method:
```php
// Send notification for plan approval
$project = \App\Models\Project::find($plan->project_id);
$approver = \App\Models\User::find($user_id);
if ($project && $approver) {
    NotificationHelper::send(
        [$plan->uploaded_by],
        'plan_approved',
        'Plan Approved',
        "Plan '{$plan->title}' has been approved by {$approver->name}",
        [
            'plan_id' => $plan->id,
            'plan_title' => $plan->title,
            'project_id' => $project->id,
            'approver_id' => $user_id,
            'approver_name' => $approver->name,
            'action_url' => "/projects/{$project->id}/plans/{$plan->id}"
        ],
        'medium'
    );
}
```

### 5. FileController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `upload()` method:
```php
// Send notification for file upload (if shared with project/team)
if ($projectId) {
    $project = \App\Models\Project::find($projectId);
    if ($project) {
        NotificationHelper::sendToProjectTeam(
            $project->id,
            'file_uploaded',
            'New File Uploaded',
            "New file '{$fileData['original_name']}' uploaded to project",
            [
                'file_id' => $file->id,
                'file_name' => $fileData['original_name'],
                'project_id' => $project->id,
                'uploaded_by' => $user_id,
                'action_url' => "/files/{$file->id}"
            ],
            'low',
            [$user_id]
        );
    }
}
```

#### In `share()` method:
```php
// Send notification for file sharing
$sharedWith = $request->input('shared_with', []); // array of user IDs
$file = \App\Models\File::find($fileId);
$sharer = \App\Models\User::find($user_id);

if ($file && $sharer && !empty($sharedWith)) {
    NotificationHelper::send(
        $sharedWith,
        'file_shared',
        'File Shared with You',
        "{$sharer->name} shared '{$file->original_name}' with you",
        [
            'file_id' => $file->id,
            'file_name' => $file->original_name,
            'sharer_id' => $user_id,
            'sharer_name' => $sharer->name,
            'action_url' => "/files/{$file->id}"
        ],
        'medium'
    );
}
```

### 6. DailyLogController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `create()` method (after line 41):
```php
// Send notification for daily log creation
$project = \App\Models\Project::find($request->project_id);
$logger = \App\Models\User::find($request->user_id);
if ($project && $logger) {
    NotificationHelper::sendToProjectManagers(
        $project->id,
        'daily_log_created',
        'Daily Log Entry Created',
        "Daily log for {$request->log_date} created for project '{$project->project_title}'",
        [
            'log_id' => $dailyLogDetails->id,
            'project_id' => $project->id,
            'project_title' => $project->project_title,
            'log_date' => $request->log_date,
            'logged_by' => $request->user_id,
            'logged_by_name' => $logger->name,
            'action_url' => "/projects/{$project->id}/daily-logs/{$dailyLogDetails->id}"
        ],
        'low',
        [$request->user_id]
    );
}
```

### 7. AuthController

Add `use App\Helpers\NotificationHelper;` at the top.

#### In `signup()` method (after user created):
```php
// Send welcome notification
NotificationHelper::send(
    $userDetails->id,
    'account_created',
    'Welcome to Biltix!',
    "Your account has been successfully created. Welcome to Biltix!",
    [
        'user_id' => $userDetails->id,
        'user_name' => $userDetails->name,
        'action_url' => "/dashboard"
    ],
    'low'
);
```

#### In `sendOtp()` method (after OTP sent):
```php
// Send OTP notification
NotificationHelper::send(
    $user->id,
    'otp_sent',
    'Verification OTP',
    "Your verification OTP is {$otp}. Valid for 10 minutes.",
    [
        'user_id' => $user->id,
        'otp' => $otp,
        'purpose' => $request->purpose ?? 'verification',
        'expires_at' => now()->addMinutes(10)->toDateTimeString()
    ],
    'high'
);
```

#### In `resetPassword()` method (after OTP sent):
```php
// Send password reset notification
NotificationHelper::send(
    $user->id,
    'password_reset',
    'Password Reset Request',
    "Your password reset OTP is {$otp}. Valid for 10 minutes.",
    [
        'user_id' => $user->id,
        'otp' => $otp,
        'expires_at' => now()->addMinutes(10)->toDateTimeString(),
        'action_url' => "/reset-password"
    ],
    'high'
);
```

## Next Steps

After adding these integrations:
1. Test each notification trigger
2. Verify push notifications work on Android/iOS
3. Test scheduled jobs for reminders
4. Monitor notification delivery

