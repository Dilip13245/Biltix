<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\PlanFeature;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ==================================
        // BASIC PLAN - 300 USD/Year
        // ==================================
        $basicPlan = SubscriptionPlan::updateOrCreate(
            ['name' => 'basic'],
            [
                'display_name' => 'Basic Plan',
                'price' => 300.00,
                'billing_period' => 'yearly',
                'currency' => 'USD',
                'description' => 'Essential features for small teams and individual contractors',
                'max_projects' => 5,
                'max_team_members' => 3,
                'is_default' => true,
                'is_active' => true,
                'sort_order' => 1
            ]
        );

        // Basic Plan Features (Based on your requirements)
        $basicFeatures = [
            // Auth & Profile (Always included)
            ['feature_key' => 'auth.register', 'feature_name' => 'Register', 'module' => 'auth', 'action' => 'register'],
            ['feature_key' => 'auth.login', 'feature_name' => 'Login', 'module' => 'auth', 'action' => 'login'],
            ['feature_key' => 'auth.password_reset', 'feature_name' => 'Password Reset', 'module' => 'auth', 'action' => 'password_reset'],
            
            // Dashboard & Profile
            ['feature_key' => 'dashboard.view', 'feature_name' => 'Dashboard', 'module' => 'dashboard', 'action' => 'view'],
            ['feature_key' => 'profile.view', 'feature_name' => 'View Profile', 'module' => 'profile', 'action' => 'view'],
            ['feature_key' => 'profile.edit', 'feature_name' => 'Edit Profile', 'module' => 'profile', 'action' => 'edit'],
            
            // Notifications & Support
            ['feature_key' => 'notifications.view', 'feature_name' => 'Notifications', 'module' => 'notifications', 'action' => 'view'],
            ['feature_key' => 'help_support.view', 'feature_name' => 'Help & Support', 'module' => 'help_support', 'action' => 'view'],
            
            // Projects (Basic)
            ['feature_key' => 'projects.create', 'feature_name' => 'Create Project', 'module' => 'projects', 'action' => 'create'],
            ['feature_key' => 'projects.view', 'feature_name' => 'View Projects', 'module' => 'projects', 'action' => 'view'],
            ['feature_key' => 'projects.edit', 'feature_name' => 'Edit Projects', 'module' => 'projects', 'action' => 'edit'],
            
            // Plans (Upload only)
            ['feature_key' => 'plans.upload', 'feature_name' => 'Upload Plans', 'module' => 'plans', 'action' => 'upload'],
            ['feature_key' => 'plans.view', 'feature_name' => 'View Plans', 'module' => 'plans', 'action' => 'view'],
            
            // Tasks (Basic)
            ['feature_key' => 'tasks.create', 'feature_name' => 'Create Tasks', 'module' => 'tasks', 'action' => 'create'],
            ['feature_key' => 'tasks.view', 'feature_name' => 'View Tasks', 'module' => 'tasks', 'action' => 'view'],
            ['feature_key' => 'tasks.edit', 'feature_name' => 'Edit Tasks', 'module' => 'tasks', 'action' => 'edit'],
            ['feature_key' => 'tasks.assign', 'feature_name' => 'Assign Tasks', 'module' => 'tasks', 'action' => 'assign'],
            
            // Progress & Reports
            ['feature_key' => 'progress.view', 'feature_name' => 'Progress Tracking', 'module' => 'progress', 'action' => 'view'],
            ['feature_key' => 'reports.view', 'feature_name' => 'View Reports', 'module' => 'reports', 'action' => 'view'],
            ['feature_key' => 'reports.generate', 'feature_name' => 'Generate Reports', 'module' => 'reports', 'action' => 'generate'],
            
            // Snags
            ['feature_key' => 'snags.create', 'feature_name' => 'Create Snags', 'module' => 'snags', 'action' => 'create'],
            ['feature_key' => 'snags.view', 'feature_name' => 'View Snags', 'module' => 'snags', 'action' => 'view'],
            ['feature_key' => 'snags.edit', 'feature_name' => 'Edit Snags', 'module' => 'snags', 'action' => 'edit'],
            ['feature_key' => 'snags.assign', 'feature_name' => 'Assign Snags', 'module' => 'snags', 'action' => 'assign'],
            
            // Inspections
            ['feature_key' => 'inspections.create', 'feature_name' => 'Create Inspections', 'module' => 'inspections', 'action' => 'create'],
            ['feature_key' => 'inspections.view', 'feature_name' => 'View Inspections', 'module' => 'inspections', 'action' => 'view'],
            ['feature_key' => 'inspections.edit', 'feature_name' => 'Edit Inspections', 'module' => 'inspections', 'action' => 'edit'],
            
            // Daily Logs
            ['feature_key' => 'daily_logs.create', 'feature_name' => 'Create Daily Logs', 'module' => 'daily_logs', 'action' => 'create'],
            ['feature_key' => 'daily_logs.view', 'feature_name' => 'View Daily Logs', 'module' => 'daily_logs', 'action' => 'view'],
            ['feature_key' => 'daily_logs.edit', 'feature_name' => 'Edit Daily Logs', 'module' => 'daily_logs', 'action' => 'edit'],
            
            // File Archive
            ['feature_key' => 'files.archive', 'feature_name' => 'File Archive', 'module' => 'files', 'action' => 'archive'],
            ['feature_key' => 'files.view', 'feature_name' => 'View Files', 'module' => 'files', 'action' => 'view'],
        ];

        foreach ($basicFeatures as $feature) {
            PlanFeature::updateOrCreate(
                ['plan_id' => $basicPlan->id, 'feature_key' => $feature['feature_key']],
                $feature + ['is_active' => true]
            );
        }

        // ==================================
        // PRO PLAN - 468 USD/Year
        // ==================================
        $proPlan = SubscriptionPlan::updateOrCreate(
            ['name' => 'pro'],
            [
                'display_name' => 'Pro Plan',
                'price' => 468.00,
                'billing_period' => 'yearly',
                'currency' => 'USD',
                'description' => 'Advanced features for growing teams with unlimited projects',
                'max_projects' => null, // Unlimited
                'max_team_members' => null, // Unlimited
                'is_default' => false,
                'is_active' => true,
                'sort_order' => 2
            ]
        );

        // Pro Plan Features (All Basic + Additional)
        $proFeatures = array_merge($basicFeatures, [
            // Team Management (Pro Only)
            ['feature_key' => 'team.add_member', 'feature_name' => 'Add Team Member', 'module' => 'team', 'action' => 'add_member'],
            ['feature_key' => 'team.edit_member', 'feature_name' => 'Edit Team Member', 'module' => 'team', 'action' => 'edit_member'],
            ['feature_key' => 'team.delete_member', 'feature_name' => 'Delete Team Member', 'module' => 'team', 'action' => 'delete_member'],
            ['feature_key' => 'team.view', 'feature_name' => 'View Team', 'module' => 'team', 'action' => 'view'],
            
            // Location
            ['feature_key' => 'location.view', 'feature_name' => 'Location Tracking', 'module' => 'location', 'action' => 'view'],
            
            // Timeline & Milestones
            ['feature_key' => 'timeline.view', 'feature_name' => 'Timeline', 'module' => 'timeline', 'action' => 'view'],
            ['feature_key' => 'timeline.edit', 'feature_name' => 'Edit Timeline', 'module' => 'timeline', 'action' => 'edit'],
            ['feature_key' => 'milestones.create', 'feature_name' => 'Set Milestones', 'module' => 'milestones', 'action' => 'create'],
            ['feature_key' => 'milestones.view', 'feature_name' => 'View Milestones', 'module' => 'milestones', 'action' => 'view'],
            ['feature_key' => 'milestones.edit', 'feature_name' => 'Edit Milestones', 'module' => 'milestones', 'action' => 'edit'],
            
            // Drawing & Markup
            ['feature_key' => 'plans.markup', 'feature_name' => 'Drawing & Markup Tool', 'module' => 'plans', 'action' => 'markup'],
            
            // File Upload
            ['feature_key' => 'files.upload', 'feature_name' => 'File Upload', 'module' => 'files', 'action' => 'upload'],
            ['feature_key' => 'files.delete', 'feature_name' => 'Delete Files', 'module' => 'files', 'action' => 'delete'],
            
            // Gallery
            ['feature_key' => 'gallery.view', 'feature_name' => 'Project Gallery', 'module' => 'gallery', 'action' => 'view'],
            ['feature_key' => 'gallery.upload', 'feature_name' => 'Upload to Gallery', 'module' => 'gallery', 'action' => 'upload'],
            
            // Tasks Library
            ['feature_key' => 'tasks.library', 'feature_name' => 'Tasks Library', 'module' => 'tasks', 'action' => 'library'],
            
            // Chat
            ['feature_key' => 'chat.access', 'feature_name' => 'Chat', 'module' => 'chat', 'action' => 'access'],
            ['feature_key' => 'chat.send', 'feature_name' => 'Send Messages', 'module' => 'chat', 'action' => 'send'],
            
            // Gantt Chart
            ['feature_key' => 'gantt.view', 'feature_name' => 'Gantt Chart', 'module' => 'gantt', 'action' => 'view'],
            
            // Meetings
            ['feature_key' => 'meetings.create', 'feature_name' => 'Create Meetings', 'module' => 'meetings', 'action' => 'create'],
            ['feature_key' => 'meetings.view', 'feature_name' => 'View Meetings', 'module' => 'meetings', 'action' => 'view'],
            ['feature_key' => 'meetings.edit', 'feature_name' => 'Edit Meetings', 'module' => 'meetings', 'action' => 'edit'],
        ]);

        foreach ($proFeatures as $feature) {
            PlanFeature::updateOrCreate(
                ['plan_id' => $proPlan->id, 'feature_key' => $feature['feature_key']],
                $feature + ['is_active' => true]
            );
        }

        $this->command->info('Subscription plans seeded successfully!');
        $this->command->info("Basic Plan: {$basicPlan->id} with " . count($basicFeatures) . " features");
        $this->command->info("Pro Plan: {$proPlan->id} with " . count($proFeatures) . " features");
    }
}
