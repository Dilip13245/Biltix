<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Role-Based Permissions Matrix
    |--------------------------------------------------------------------------
    | Based on role_wise_access.txt - Crystal clear permissions for each role
    */
    
    'roles' => [
        'consultant' => [
            'auth' => ['register', 'login', 'profile_edit'],
            'dashboard' => ['view', 'navigate'],
            'profile' => ['view', 'edit'],
            'projects' => ['create', 'edit', 'delete', 'view'],
            'plans' => ['upload', 'markup', 'annotate', 'view', 'delete'],
            'files' => ['upload', 'download', 'delete', 'view'],
            'progress' => ['track', 'update', 'view'],
            'tasks' => ['create', 'assign', 'update', 'complete', 'delete', 'view'],
            'snags' => ['create', 'assign', 'resolve', 'comment', 'view'],
            'inspections' => ['create', 'conduct', 'approve', 'view'],
            'daily_logs' => ['create', 'edit', 'view'],
            'team' => ['add', 'remove', 'assign', 'view'],
            'notifications' => ['view', 'mark_read', 'delete'],
            'timeline' => ['view', 'edit']
        ],
        
        'contractor' => [
            'auth' => ['register', 'login', 'profile_edit'],
            'dashboard' => ['view', 'navigate'],
            'profile' => ['view', 'edit'],
            'projects' => ['create', 'edit', 'delete', 'view'],
            'plans' => ['upload', 'markup', 'annotate', 'view', 'delete'],
            'files' => ['upload', 'download', 'delete', 'view'],
            'progress' => ['track', 'update', 'view'],
            'tasks' => ['create', 'assign', 'update_status', 'view'], // Limited: update status only
            'snags' => ['create', 'assign', 'resolve', 'comment', 'view'],
            'inspections' => ['create', 'conduct', 'approve', 'view'],
            'daily_logs' => ['create', 'edit', 'view'],
            'team' => ['add', 'remove', 'assign', 'view'],
            'notifications' => ['view', 'mark_read', 'delete'],
            'timeline' => ['view', 'edit']
        ],
        
        'project_manager' => [
            'auth' => ['login', 'profile_edit'], // Login-only
            'dashboard' => ['view', 'navigate'],
            'profile' => ['view', 'edit'],
            'projects' => ['create', 'edit', 'delete', 'view'],
            'plans' => ['upload', 'markup', 'annotate', 'view', 'delete'],
            'files' => ['upload', 'download', 'delete', 'view'],
            'progress' => ['track', 'update', 'view'],
            'tasks' => ['create', 'assign', 'update', 'complete', 'delete', 'view'],
            'snags' => ['create', 'assign', 'resolve', 'comment', 'view'],
            'inspections' => ['create', 'conduct', 'approve', 'view'],
            'daily_logs' => ['create', 'edit', 'view'],
            'team' => ['add', 'remove', 'assign', 'view'],
            'notifications' => ['view', 'mark_read', 'delete'],
            'timeline' => ['view', 'edit']
        ],
        
        'site_engineer' => [
            'auth' => ['login', 'profile_edit_limited'],
            'dashboard' => ['view', 'navigate'], // Full dashboard access
            'profile' => ['view', 'edit_limited'],
            'projects' => ['view'], // Can view all projects
            'plans' => ['annotate', 'view'], // Can view and annotate plans
            'files' => ['upload_logs', 'download', 'view'], // Can view and download all files
            'progress' => ['view'], // Can view all progress
            'tasks' => ['view'], // Can view all tasks
            'snags' => ['create', 'view'], // Can view all snags and create
            'inspections' => ['create', 'view'], // Can view all inspections and create
            'daily_logs' => ['create', 'edit', 'view'], // Full access to daily logs
            'team' => ['view'], // Can view all team members
            'notifications' => ['view', 'mark_read', 'delete'],
            'timeline' => ['view'] // Can view all timelines
        ],
        
        'stakeholder' => [
            'auth' => ['login'], // Login-only
            'dashboard' => ['view'], // View-only
            'profile' => ['view'], // View-only
            'projects' => [], // No Access
            'plans' => ['view'], // View-only
            'files' => ['download', 'view'], // View-only
            'progress' => ['view'], // View-only
            'tasks' => ['view'], // View-only
            'snags' => ['view'], // View-only
            'inspections' => ['view'], // View-only
            'daily_logs' => ['view'], // View-only
            'team' => ['view'], // View-only
            'notifications' => ['view', 'mark_read', 'delete'],
            'timeline' => ['view'] // View-only
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Registration Permissions
    |--------------------------------------------------------------------------
    */
    'can_register' => ['consultant', 'contractor'],
    'login_only' => ['project_manager', 'site_engineer', 'stakeholder'],
    
    /*
    |--------------------------------------------------------------------------
    | Dashboard Access Levels
    |--------------------------------------------------------------------------
    */
    'dashboard_access' => [
        'full' => ['consultant', 'contractor', 'project_manager', 'site_engineer'],
        'assigned_only' => [],
        'view_only' => ['stakeholder']
    ]
];