# Biltix - Final Perfect Database Design

## 🎯 ACTUAL DATA FIELDS FROM FIGMA & WEBSITE ANALYSIS

### 1. USERS & AUTHENTICATION
```sql
-- Core Users Table (Based on Registration Flow)
users:
- id (PK)
- name (Full Name from registration)
- email (unique)
- phone
- password (hashed)
- role (contractor, consultant, site_engineer, project_manager, stakeholder)
- designation (from registration step 2)
- company_name (from registration step 2)
- employee_count (from registration step 2)
- member_number (optional from registration step 3)
- member_name (optional from registration step 3)
- profile_image
- is_active (boolean, default: true)
- language (en, ar)
- timezone
- last_login_at
- created_at
- updated_at

-- Admin Users (Separate system)
admins:
- id (PK)
- name
- email (unique)
- password (hashed)
- role (super_admin, admin)
- avatar
- last_login_at
- is_active (boolean, default: true)
- created_at
- updated_at
```

### 2. PROJECTS & PHASES
```sql
-- Projects (From Create Project Flow - 5 Steps)
projects:
- id (PK)
- name (Step 1)
- description (Step 1)
- type (residential, commercial, industrial, renovation - Step 1)
- location (Step 1)
- start_date (Step 1)
- end_date (Step 1)
- client_name (Step 2)
- client_email (Step 2)
- client_phone (Step 2)
- client_address (Step 2)
- budget (Step 2)
- actual_cost (decimal 15,2, default: 0)
- project_manager_id (FK users - Step 3)
- created_by (FK users)
- status (planning, active, on_hold, completed, cancelled)
- progress_percentage (integer, default: 0)
- images (JSON)
- is_active (boolean, default: true)
- created_at
- updated_at

-- Project Phases (Foundation, Structure, Roofing, Interior, Finishing)
project_phases:
- id (PK)
- project_id (FK projects)
- name (foundation, structure, roofing, interior, finishing)
- description
- phase_order (integer)
- start_date
- end_date
- progress_percentage (integer, default: 0)
- status (pending, active, completed)
- budget_allocated (decimal 15,2)
- actual_cost (decimal 15,2, default: 0)
- created_at
- updated_at

-- Project Team Members
project_team_members:
- id (PK)
- project_id (FK projects)
- user_id (FK users)
- role_in_project (project_manager, site_engineer, supervisor, worker, inspector, consultant)
- company_name
- assigned_at
- is_active (boolean, default: true)
- created_at
- updated_at
```

### 3. TASKS MANAGEMENT
```sql
-- Tasks (From Task Creation & Details)
tasks:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- title
- description
- status (pending, in_progress, completed, cancelled)
- priority (low, medium, high, critical)
- assigned_to (FK users)
- created_by (FK users)
- start_date
- due_date
- completed_at (nullable)
- progress_percentage (integer, default: 0)
- location
- attachments (JSON)
- created_at
- updated_at

-- Task Comments
task_comments:
- id (PK)
- task_id (FK tasks)
- user_id (FK users)
- comment (text)
- attachments (JSON)
- created_at
- updated_at
```

### 4. INSPECTIONS SYSTEM
```sql
-- Inspection Templates
inspection_templates:
- id (PK)
- name
- category (safety, quality, structural, electrical, plumbing)
- checklist_items (JSON)
- is_active (boolean, default: true)
- created_by (FK users)
- created_at
- updated_at

-- Inspections
inspections:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- template_id (FK inspection_templates)
- title
- description
- status (scheduled, in_progress, completed, failed)
- scheduled_date
- started_at (nullable)
- completed_at (nullable)
- inspector_id (FK users)
- location
- overall_result (pass, fail, conditional_pass)
- score_percentage (decimal 5,2)
- notes (text)
- images (JSON)
- created_at
- updated_at

-- Inspection Results
inspection_results:
- id (PK)
- inspection_id (FK inspections)
- item_name
- result (pass, fail, na)
- notes (text)
- images (JSON)
- created_at
- updated_at
```

### 5. SNAG MANAGEMENT
```sql
-- Snag Categories
snag_categories:
- id (PK)
- name
- color_code (varchar 7)
- is_active (boolean, default: true)
- created_at
- updated_at

-- Snags (From Snag List & Reports)
snags:
- id (PK)
- project_id (FK projects)
- category_id (FK snag_categories)
- title
- description
- location
- priority (low, medium, high, critical)
- status (open, assigned, in_progress, resolved, closed)
- reported_by (FK users)
- assigned_to (FK users, nullable)
- due_date (nullable)
- resolved_at (nullable)
- resolved_by (FK users, nullable)
- resolution_notes (text)
- images_before (JSON)
- images_after (JSON)
- cost_impact (decimal 10,2, default: 0)
- created_at
- updated_at

-- Snag Comments
snag_comments:
- id (PK)
- snag_id (FK snags)
- user_id (FK users)
- comment (text)
- status_changed_to (nullable)
- created_at
- updated_at
```

### 6. PLANS & DRAWINGS
```sql
-- Plans (From Plans Page)
plans:
- id (PK)
- project_id (FK projects)
- title (Ground Floor Plan, Second Floor Plan, etc.)
- plan_type (architectural, structural, electrical, plumbing)
- file_name
- file_path
- file_size (integer)
- file_type (pdf, dwg, jpg, png)
- version (Rev. 3.2, Rev. 2.1, etc.)
- uploaded_by (FK users)
- status (draft, approved)
- thumbnail_path
- created_at
- updated_at

-- Plan Markups (Drawing Markup Tool)
plan_markups:
- id (PK)
- plan_id (FK plans)
- user_id (FK users)
- markup_type (inspection, snag, task, general)
- markup_data (JSON) -- coordinates, shapes, annotations
- title
- description
- status (active, resolved)
- created_at
- updated_at
```

### 7. FILES & DOCUMENTS
```sql
-- File Categories
file_categories:
- id (PK)
- name (Documents, Drawings, Photos, Reports)
- icon
- is_active (boolean, default: true)
- created_at
- updated_at

-- Files (From Files Page)
files:
- id (PK)
- project_id (FK projects)
- category_id (FK file_categories)
- name
- original_name
- file_path
- file_size (integer)
- file_type (PDF, DOC, XLS, DWG, JPG, etc.)
- uploaded_by (FK users)
- upload_date
- is_public (boolean, default: false)
- created_at
- updated_at
```

### 8. PHOTO GALLERY
```sql
-- Photos (From Photo Gallery)
photos:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- title
- description
- file_name
- file_path
- thumbnail_path
- file_size (integer)
- taken_at
- taken_by (FK users)
- location
- tags (JSON)
- is_before_photo (boolean, default: false)
- is_after_photo (boolean, default: false)
- created_at
- updated_at
```

### 9. DAILY LOGS & MANPOWER
```sql
-- Daily Logs (From Daily Logs Page)
daily_logs:
- id (PK)
- project_id (FK projects)
- log_date
- logged_by (FK users)
- weather_conditions
- work_performed (text)
- issues_encountered (text)
- notes (text)
- images (JSON)
- created_at
- updated_at

-- Equipment Logs (From Equipment Tab)
equipment_logs:
- id (PK)
- daily_log_id (FK daily_logs)
- equipment_id (EXC-001, etc.)
- equipment_type (excavator, crane, mixer)
- operator_name
- status (active, maintenance, idle)
- hours_used (decimal 4,2)
- location (Zone A, Zone B, etc.)
- created_at
- updated_at

-- Staff Logs (From Staff Tab)
staff_logs:
- id (PK)
- daily_log_id (FK daily_logs)
- staff_type (engineers, foremen, laborers)
- count (integer)
- hours_worked (decimal 4,2)
- tasks_performed (text)
- created_at
- updated_at
```

### 10. TEAM MANAGEMENT
```sql
-- Team Members (From Team Members Page)
team_members:
- id (PK)
- user_id (FK users)
- project_id (FK projects)
- name
- role (Project Manager, Structural Engineer, Site Supervisor, etc.)
- company_name (BuildCorp Construction, TechConsult Engineering, etc.)
- status (active, away)
- role_type (contractor, consultant, site_engineer, stakeholder)
- avatar_url
- contact_info (JSON)
- created_at
- updated_at
```

### 11. NOTIFICATIONS
```sql
-- Notifications
notifications:
- id (PK)
- user_id (FK users)
- type (task_assigned, inspection_due, snag_reported, project_update)
- title
- message (text)
- data (JSON)
- is_read (boolean, default: false)
- read_at (nullable)
- priority (low, medium, high)
- created_at
- updated_at
```

### 12. PROGRESS TRACKING
```sql
-- Progress Reports (From Project Progress Page)
progress_reports:
- id (PK)
- project_id (FK projects)
- report_date
- overall_completion (67%, etc.)
- active_workers (148, etc.)
- days_remaining (45, etc.)
- phase_progress (JSON) -- Foundation: 100%, Structure: 85%, etc.
- ongoing_activities (JSON)
- manpower_equipment (JSON) -- Engineers: 3, Foremen: 2, etc.
- safety_checklist (JSON)
- created_by (FK users)
- created_at
- updated_at
```

## 🔄 ROLE-BASED ACCESS CONTROL

### Role Permissions Matrix:
```json
{
  "contractor": {
    "projects": "create_edit_delete",
    "tasks": "create_edit_assign",
    "inspections": "create_view",
    "snags": "create_edit_resolve",
    "plans": "upload_markup",
    "team": "manage_assign",
    "reports": "generate_view"
  },
  "consultant": {
    "projects": "view_comment",
    "tasks": "view_comment",
    "inspections": "create_approve",
    "snags": "review_approve",
    "plans": "markup_approve",
    "team": "view",
    "reports": "view"
  },
  "site_engineer": {
    "projects": "view",
    "tasks": "update_complete",
    "inspections": "conduct_complete",
    "snags": "report_update",
    "plans": "view_markup",
    "daily_logs": "create_edit",
    "photos": "upload_manage"
  },
  "project_manager": {
    "projects": "view_edit",
    "tasks": "assign_track",
    "inspections": "schedule_review",
    "snags": "assign_track",
    "plans": "view_approve",
    "team": "view_coordinate",
    "reports": "generate_view"
  },
  "stakeholder": {
    "projects": "view",
    "tasks": "view",
    "inspections": "view_results",
    "snags": "view",
    "plans": "view",
    "reports": "view"
  }
}
```

## 📊 KEY INDEXES FOR PERFORMANCE
```sql
-- Primary Indexes
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_manager ON projects(project_manager_id);
CREATE INDEX idx_tasks_assigned ON tasks(assigned_to, status);
CREATE INDEX idx_tasks_project ON tasks(project_id, status);
CREATE INDEX idx_inspections_project ON inspections(project_id, status);
CREATE INDEX idx_snags_project ON snags(project_id, status);
CREATE INDEX idx_notifications_user ON notifications(user_id, is_read);
CREATE INDEX idx_daily_logs_project_date ON daily_logs(project_id, log_date);
```

This database design is based on actual data fields found in the Figma designs and website code, ensuring perfect compatibility with the mobile app and web platform.