<?php

return [
    'auth' => [
        // Validation Messages
        'email_required' => 'Email is required',
        'email_invalid' => 'Please enter a valid email address',
        'email_unique' => 'This email is already registered',
        'phone_number_required' => 'Phone number is required',
        'phone_number_unique' => 'This phone number is already registered',
        'name_required' => 'Name is required',
        'password_required' => 'Password is required',
        'password_min' => 'Password must be at least 6 characters',
        'role_required' => 'Role is required',
        'company_name_required' => 'Company name is required',
        'device_type_required' => 'Device type is required',
        'type_required' => 'Type is required',
        'type_invalid' => 'Invalid type provided',
        'otp_required' => 'OTP is required',
        'new_password_required' => 'New password is required',
        'new_password_min' => 'New password must be at least 6 characters',
        'confirm_password_required' => 'Confirm password is required',
        'confirm_password_mismatch' => 'Passwords do not match',
        'user_id_required' => 'User ID is required',

        // Success Messages
        'signup_success' => 'Account created successfully',
        'login_success' => 'Login successful',
        'otp_sent' => 'OTP sent successfully',
        'otp_verified_success' => 'OTP verified successfully',
        'password_reset_success' => 'Password reset successfully',
        'profile_updated_success' => 'Profile updated successfully',
        'logout_success' => 'Logout successful',
        'account_deleted' => 'Account deleted successfully',
        'user_profile_retrieved' => 'User profile retrieved successfully',

        // Error Messages
        'signup_error' => 'Failed to create account',
        'user_not_available' => 'User not found',
        'invalid_password' => 'Invalid password',
        'user_not_found_or_inactive' => 'User not found or inactive',
        'user_already_exists' => 'User already exists',
        'user_inactive_contact_admin' => 'User account is inactive, contact admin',
        'invalid_otp' => 'Invalid OTP',
        'otp_email_failed' => 'Failed to send OTP email',
        'password_same_as_old' => 'New password cannot be same as old password',
        'user_not_found' => 'User not found',
        'invalid_user' => 'Invalid user',
        'account_not_active' => 'Account is not active',
    ],

    'general' => [
        'success' => 'Success',
        'error' => 'Error occurred',
        'not_found' => 'Not found',
        'unauthorized' => 'Unauthorized access',
        'validation_error' => 'Validation error',
        'internal_error' => 'Internal server error',
    ],

    'projects' => [
        'created_success' => 'Project created successfully',
        'updated_success' => 'Project updated successfully',
        'deleted_success' => 'Project deleted successfully',
        'not_found' => 'Project not found',
        'list_retrieved' => 'Projects list retrieved successfully',
        'details_retrieved' => 'Project details retrieved successfully',
        'stats_retrieved' => 'Project statistics retrieved successfully',
    ],

    'tasks' => [
        'created_success' => 'Task created successfully',
        'updated_success' => 'Task updated successfully',
        'deleted_success' => 'Task deleted successfully',
        'status_changed' => 'Task status changed successfully',
        'comment_added' => 'Comment added successfully',
        'progress_updated' => 'Task progress updated successfully',
        'not_found' => 'Task not found',
        'list_retrieved' => 'Tasks list retrieved successfully',
        'details_retrieved' => 'Task details retrieved successfully',
    ],

    'inspections' => [
        'created_success' => 'Inspection created successfully',
        'completed_success' => 'Inspection completed successfully',
        'approved_success' => 'Inspection approved successfully',
        'not_found' => 'Inspection not found',
        'templates_retrieved' => 'Inspection templates retrieved successfully',
        'list_retrieved' => 'Inspections list retrieved successfully',
        'results_retrieved' => 'Inspection results retrieved successfully',
    ],

    'snags' => [
        'created_success' => 'Snag reported successfully',
        'updated_success' => 'Snag updated successfully',
        'resolved_success' => 'Snag resolved successfully',
        'assigned_success' => 'Snag assigned successfully',
        'comment_added' => 'Comment added successfully',
        'not_found' => 'Snag not found',
        'list_retrieved' => 'Snags list retrieved successfully',
        'categories_retrieved' => 'Snag categories retrieved successfully',
    ],

    'plans' => [
        'uploaded_success' => 'Plan uploaded successfully',
        'deleted_success' => 'Plan deleted successfully',
        'markup_added' => 'Markup added successfully',
        'approved_success' => 'Plan approved successfully',
        'not_found' => 'Plan not found',
        'list_retrieved' => 'Plans list retrieved successfully',
        'markups_retrieved' => 'Plan markups retrieved successfully',
    ],

    'files' => [
        'uploaded_success' => 'File uploaded successfully',
        'deleted_success' => 'File deleted successfully',
        'not_found' => 'File not found',
        'list_retrieved' => 'Files list retrieved successfully',
        'categories_retrieved' => 'File categories retrieved successfully',
    ],

    'notifications' => [
        'marked_read' => 'Notification marked as read',
        'all_marked_read' => 'All notifications marked as read',
        'deleted_success' => 'Notification deleted successfully',
        'settings_retrieved' => 'Notification settings retrieved successfully',
        'settings_updated' => 'Notification settings updated successfully',
        'list_retrieved' => 'Notifications list retrieved successfully',
    ],
];

// Auth translations
return [
    'token_not_found' => 'Token not found',
    'invalid_token' => 'Invalid token',
    'invalid_api_key' => 'Invalid API key',
    'api_key_not_found' => 'API key not found',
];