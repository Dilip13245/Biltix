# Biltix - Complete Database Design

## 📊 Database Schema Design

### 1. USERS & AUTHENTICATION

```sql
-- Enhanced Users Table
users:
- id (PK)
- name
- email (unique)
- phone
- password
- role (contractor, consultant, site_engineer, project_manager, stakeholder)
- designation
- company_name
- employee_count
- member_number (nullable)
- member_name (nullable)
- profile_image (nullable)
- is_active (boolean, default: true)
- email_verified_at (nullable)
- last_login_at (nullable)
- created_at
- updated_at

-- Role Permissions (JSON stored in users or separate table)
role_permissions:
- id (PK)
- role_name
- permissions (JSON)
- created_at
- updated_at
```

### 2. PROJECTS & PHASES

```sql
-- Projects Table
projects:
- id (PK)
- name
- description
- type (residential, commercial, industrial, renovation)
- status (planning, active, on_hold, completed, cancelled)
- priority (low, medium, high, critical)
- start_date
- end_date
- budget (decimal 15,2)
- actual_cost (decimal 15,2, default: 0)
- location
- address (text)
- latitude (decimal 10,8, nullable)
- longitude (decimal 11,8, nullable)
- client_name
- client_email
- client_phone
- client_address (text)
- project_manager_id (FK users)
- created_by (FK users)
- progress_percentage (integer, default: 0)
- is_active (boolean, default: true)
- images (JSON, nullable)
- documents (JSON, nullable)
- settings (JSON, nullable)
- deleted_at (nullable)
- created_at
- updated_at

-- Project Phases
project_phases:
- id (PK)
- project_id (FK projects)
- name
- description (text)
- phase_order (integer)
- start_date
- end_date
- estimated_duration_days
- actual_duration_days (nullable)
- progress_percentage (integer, default: 0)
- status (pending, active, completed, on_hold)
- budget_allocated (decimal 15,2, default: 0)
- actual_cost (decimal 15,2, default: 0)
- dependencies (JSON, nullable) -- other phase IDs
- created_by (FK users)
- created_at
- updated_at

-- Project Team Members (Many-to-Many)
project_team_members:
- id (PK)
- project_id (FK projects)
- user_id (FK users)
- role_in_project (project_manager, site_engineer, supervisor, worker, inspector)
- assigned_at
- assigned_by (FK users)
- is_active (boolean, default: true)
- permissions (JSON, nullable)
- created_at
- updated_at
```

### 3. TASKS MANAGEMENT

```sql
-- Tasks Table
tasks:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- parent_task_id (FK tasks, nullable) -- for subtasks
- title
- description (text)
- task_type (general, inspection, snag_fix, milestone)
- status (pending, in_progress, review, completed, cancelled)
- priority (low, medium, high, critical)
- assigned_to (FK users)
- assigned_by (FK users)
- created_by (FK users)
- start_date
- due_date
- completed_at (nullable)
- estimated_hours (decimal 5,2, nullable)
- actual_hours (decimal 5,2, nullable)
- progress_percentage (integer, default: 0)
- location (nullable)
- attachments (JSON, nullable)
- tags (JSON, nullable)
- dependencies (JSON, nullable) -- other task IDs
- deleted_at (nullable)
- created_at
- updated_at

-- Task Comments
task_comments:
- id (PK)
- task_id (FK tasks)
- user_id (FK users)
- comment (text)
- attachments (JSON, nullable)
- created_at
- updated_at

-- Task Time Logs
task_time_logs:
- id (PK)
- task_id (FK tasks)
- user_id (FK users)
- start_time
- end_time (nullable)
- duration_minutes (integer, nullable)
- description (text, nullable)
- created_at
- updated_at
```

### 4. INSPECTIONS SYSTEM

```sql
-- Inspection Templates
inspection_templates:
- id (PK)
- name
- description (text)
- category (safety, quality, structural, electrical, plumbing)
- checklist_items (JSON) -- array of checklist items with criteria
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
- description (text)
- inspection_type (scheduled, random, complaint_based)
- status (scheduled, in_progress, completed, failed, cancelled)
- scheduled_date
- started_at (nullable)
- completed_at (nullable)
- inspector_id (FK users)
- assigned_by (FK users)
- location
- overall_result (pass, fail, conditional_pass)
- score_percentage (decimal 5,2, nullable)
- notes (text, nullable)
- images (JSON, nullable)
- attachments (JSON, nullable)
- created_at
- updated_at

-- Inspection Results (Individual Checklist Items)
inspection_results:
- id (PK)
- inspection_id (FK inspections)
- checklist_item_id (from template JSON)
- item_name
- item_description
- result (pass, fail, na, conditional)
- notes (text, nullable)
- images (JSON, nullable)
- corrective_action_required (boolean, default: false)
- corrective_action_notes (text, nullable)
- created_at
- updated_at
```

### 5. SNAG MANAGEMENT

```sql
-- Snag Categories
snag_categories:
- id (PK)
- name
- description (text, nullable)
- color_code (varchar 7) -- hex color
- is_active (boolean, default: true)
- created_at
- updated_at

-- Snags
snags:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- category_id (FK snag_categories)
- title
- description (text)
- location
- priority (low, medium, high, critical)
- status (open, assigned, in_progress, resolved, verified, closed)
- reported_by (FK users)
- assigned_to (FK users, nullable)
- assigned_by (FK users, nullable)
- due_date (nullable)
- resolved_at (nullable)
- resolved_by (FK users, nullable)
- verified_at (nullable)
- verified_by (FK users, nullable)
- resolution_notes (text, nullable)
- images_before (JSON, nullable)
- images_after (JSON, nullable)
- cost_impact (decimal 10,2, default: 0)
- time_impact_hours (integer, default: 0)
- related_task_id (FK tasks, nullable)
- related_inspection_id (FK inspections, nullable)
- created_at
- updated_at

-- Snag Comments
snag_comments:
- id (PK)
- snag_id (FK snags)
- user_id (FK users)
- comment (text)
- status_changed_to (nullable) -- if this comment changed status
- attachments (JSON, nullable)
- created_at
- updated_at
```

### 6. PLANS & DRAWINGS

```sql
-- Plans
plans:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- title
- description (text, nullable)
- plan_type (architectural, structural, electrical, plumbing, mechanical, site_plan)
- file_name
- file_path
- file_size (integer)
- file_type (pdf, dwg, jpg, png)
- version (varchar 10, default: '1.0')
- revision_notes (text, nullable)
- is_current_version (boolean, default: true)
- uploaded_by (FK users)
- approved_by (FK users, nullable)
- approved_at (nullable)
- status (draft, review, approved, superseded)
- thumbnail_path (nullable)
- created_at
- updated_at

-- Plan Markups
plan_markups:
- id (PK)
- plan_id (FK plans)
- user_id (FK users)
- markup_type (inspection, snag, task, general, approval)
- related_id (nullable) -- ID of related inspection/snag/task
- markup_data (JSON) -- coordinates, shapes, annotations
- title
- description (text, nullable)
- status (active, resolved, archived)
- created_at
- updated_at

-- Plan Comments
plan_comments:
- id (PK)
- plan_id (FK plans)
- markup_id (FK plan_markups, nullable)
- user_id (FK users)
- comment (text)
- coordinates (JSON, nullable) -- x, y position on plan
- created_at
- updated_at
```

### 7. FILES & DOCUMENTS

```sql
-- File Categories
file_categories:
- id (PK)
- name
- description (text, nullable)
- icon (nullable)
- is_active (boolean, default: true)
- created_at
- updated_at

-- Files
files:
- id (PK)
- project_id (FK projects)
- category_id (FK file_categories)
- related_type (nullable) -- task, inspection, snag, etc.
- related_id (nullable) -- ID of related entity
- name
- original_name
- file_path
- file_size (integer)
- file_type
- mime_type
- description (text, nullable)
- tags (JSON, nullable)
- uploaded_by (FK users)
- is_public (boolean, default: false)
- download_count (integer, default: 0)
- created_at
- updated_at
```

### 8. PHOTO GALLERY

```sql
-- Photos
photos:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- related_type (nullable) -- task, inspection, snag, daily_log, progress
- related_id (nullable)
- title (nullable)
- description (text, nullable)
- file_name
- file_path
- thumbnail_path
- file_size (integer)
- taken_at
- taken_by (FK users)
- location (nullable)
- latitude (decimal 10,8, nullable)
- longitude (decimal 11,8, nullable)
- weather_conditions (nullable)
- tags (JSON, nullable)
- is_before_photo (boolean, default: false)
- is_after_photo (boolean, default: false)
- before_photo_id (FK photos, nullable)
- created_at
- updated_at
```

### 9. DAILY LOGS & MANPOWER

```sql
-- Daily Logs
daily_logs:
- id (PK)
- project_id (FK projects)
- phase_id (FK project_phases, nullable)
- log_date
- logged_by (FK users)
- weather_conditions
- temperature (decimal 4,1, nullable)
- work_hours_start
- work_hours_end
- total_work_hours (decimal 4,2)
- work_performed (text)
- materials_used (JSON, nullable)
- equipment_used (JSON, nullable)
- issues_encountered (text, nullable)
- safety_incidents (text, nullable)
- notes (text, nullable)
- images (JSON, nullable)
- created_at
- updated_at

-- Manpower Logs
manpower_logs:
- id (PK)
- daily_log_id (FK daily_logs)
- worker_type (engineer, foreman, laborer, operator, supervisor)
- count (integer)
- hours_worked (decimal 4,2)
- tasks_performed (text, nullable)
- notes (text, nullable)
- created_at
- updated_at

-- Equipment Logs
equipment_logs:
- id (PK)
- daily_log_id (FK daily_logs)
- equipment_name
- equipment_type (excavator, crane, mixer, truck, generator)
- operator_name (nullable)
- hours_used (decimal 4,2)
- fuel_consumed (decimal 6,2, nullable)
- maintenance_notes (text, nullable)
- status (operational, maintenance, breakdown)
- created_at
- updated_at
```

### 10. NOTIFICATIONS

```sql
-- Notifications
notifications:
- id (PK)
- user_id (FK users)
- type (task_assigned, inspection_due, snag_reported, project_update, system_alert)
- title
- message (text)
- data (JSON, nullable) -- additional data like IDs, links
- related_type (nullable) -- project, task, inspection, snag
- related_id (nullable)
- is_read (boolean, default: false)
- read_at (nullable)
- action_url (nullable)
- priority (low, medium, high)
- expires_at (nullable)
- created_at
- updated_at

-- Notification Settings
notification_settings:
- id (PK)
- user_id (FK users)
- notification_type
- email_enabled (boolean, default: true)
- push_enabled (boolean, default: true)
- sms_enabled (boolean, default: false)
- created_at
- updated_at
```

### 11. CHANGE ORDERS & APPROVALS

```sql
-- Change Orders
change_orders:
- id (PK)
- project_id (FK projects)
- title
- description (text)
- reason (text)
- submitted_by (FK users)
- submitted_at
- status (submitted, under_review, approved, rejected, implemented)
- reviewed_by (FK users, nullable)
- reviewed_at (nullable)
- approved_by (FK users, nullable)
- approved_at (nullable)
- cost_impact (decimal 15,2, default: 0)
- time_impact_days (integer, default: 0)
- priority (low, medium, high, critical)
- attachments (JSON, nullable)
- approval_notes (text, nullable)
- rejection_reason (text, nullable)
- implemented_at (nullable)
- created_at
- updated_at
```

### 12. SYSTEM TABLES

```sql
-- Activity Logs
activity_logs:
- id (PK)
- user_id (FK users)
- action
- description
- model_type (nullable) -- Project, Task, etc.
- model_id (nullable)
- old_values (JSON, nullable)
- new_values (JSON, nullable)
- ip_address
- user_agent
- created_at

-- Settings
settings:
- id (PK)
- key
- value (text)
- type (string, integer, boolean, json)
- description (text, nullable)
- is_public (boolean, default: false)
- created_at
- updated_at
```

## 🔗 Key Relationships & Indexes

### Primary Relationships:
1. Users → Projects (project_manager_id)
2. Projects → Project Phases (1:many)
3. Projects → Tasks (1:many)
4. Projects → Inspections (1:many)
5. Projects → Snags (1:many)
6. Projects → Plans (1:many)
7. Projects → Files (1:many)
8. Projects → Photos (1:many)
9. Projects → Daily Logs (1:many)

### Important Indexes:
- users(email, role, is_active)
- projects(status, project_manager_id, created_at)
- tasks(project_id, assigned_to, status, due_date)
- inspections(project_id, inspector_id, status, scheduled_date)
- snags(project_id, status, priority, assigned_to)
- notifications(user_id, is_read, created_at)
- activity_logs(user_id, model_type, model_id, created_at)

This database design supports all features identified in the Figma analysis with proper normalization, relationships, and performance optimization.