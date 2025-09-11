# Missing Relational Fields Analysis

Based on the construction workflow analysis, here are the critical missing fields that require data from different tables and relationships:

---

## 🔗 Cross-Table Relationship Issues

### **1. User Information Missing in APIs**

#### **Current Problem:**
APIs return only `user_id` but screens need full user details

#### **Missing Fields from Users Table:**
```php
// Current API Response:
{
  "assigned_to": 5,
  "created_by": 3,
  "project_manager_id": 2
}

// Required API Response:
{
  "assigned_to": {
    "id": 5,
    "name": "John Smith",
    "role": "site_engineer", 
    "avatar": "avatars/john.jpg",
    "phone": "+1234567890",
    "email": "john@company.com"
  },
  "created_by": {
    "id": 3,
    "name": "Sarah Johnson",
    "role": "project_manager"
  },
  "project_manager": {
    "id": 2,
    "name": "Mike Wilson",
    "role": "project_manager",
    "avatar": "avatars/mike.jpg"
  }
}
```

#### **Required Database Joins:**
```sql
-- Tasks API needs:
LEFT JOIN users AS assigned_user ON tasks.assigned_to = assigned_user.id
LEFT JOIN users AS creator ON tasks.created_by = creator.id

-- Projects API needs:
LEFT JOIN users AS pm ON projects.project_manager_id = pm.id
LEFT JOIN users AS engineer ON projects.technical_engineer_id = engineer.id
```

---

### **2. Project Team Members (Missing Table)**

#### **Current Problem:**
Dashboard shows "+5 more" team members but no API exists

#### **Missing Table Structure:**
```sql
CREATE TABLE project_team_members (
  id INT PRIMARY KEY,
  project_id INT,
  user_id INT,
  role VARCHAR(50), -- 'manager', 'engineer', 'inspector'
  assigned_date DATE,
  is_active BOOLEAN,
  FOREIGN KEY (project_id) REFERENCES projects(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

#### **Required API Response:**
```php
// Projects API should include:
{
  "team_members": [
    {"id": 1, "name": "John", "role": "Site Engineer", "avatar": "john.jpg"},
    {"id": 2, "name": "Sarah", "role": "Inspector", "avatar": "sarah.jpg"}
  ],
  "team_count": 7,
  "team_roles": {
    "project_managers": 1,
    "site_engineers": 4, 
    "inspectors": 2
  }
}
```

---

### **3. Task Dependencies (Missing Table)**

#### **Current Problem:**
No way to track task prerequisites (e.g., "Pour concrete" depends on "Complete excavation")

#### **Missing Table Structure:**
```sql
CREATE TABLE task_dependencies (
  id INT PRIMARY KEY,
  task_id INT,
  depends_on_task_id INT,
  dependency_type VARCHAR(50), -- 'blocks', 'requires', 'follows'
  FOREIGN KEY (task_id) REFERENCES tasks(id),
  FOREIGN KEY (depends_on_task_id) REFERENCES tasks(id)
);
```

#### **Required API Response:**
```php
// Tasks API should include:
{
  "dependencies": {
    "blocked_by": [
      {"id": 15, "title": "Complete excavation", "status": "in_progress"}
    ],
    "blocks": [
      {"id": 18, "title": "Install plumbing", "status": "pending"}
    ]
  },
  "can_start": false, // Based on dependencies
  "dependency_status": "waiting_for_excavation"
}
```

---

### **4. Assignment History (Missing Table)**

#### **Current Problem:**
No tracking of who assigned what to whom and when

#### **Missing Table Structure:**
```sql
CREATE TABLE assignment_history (
  id INT PRIMARY KEY,
  assignable_type VARCHAR(50), -- 'task', 'inspection', 'snag'
  assignable_id INT,
  assigned_by INT,
  assigned_to INT,
  assigned_at TIMESTAMP,
  status VARCHAR(50), -- 'pending', 'accepted', 'rejected', 'completed'
  notes TEXT,
  FOREIGN KEY (assigned_by) REFERENCES users(id),
  FOREIGN KEY (assigned_to) REFERENCES users(id)
);
```

#### **Required API Response:**
```php
// Tasks/Inspections/Snags APIs should include:
{
  "assignment_history": [
    {
      "assigned_by": {"name": "Sarah Johnson", "role": "Project Manager"},
      "assigned_to": {"name": "John Smith", "role": "Site Engineer"},
      "assigned_at": "2024-03-20 09:30:00",
      "status": "accepted",
      "notes": "Urgent - complete by EOD"
    }
  ],
  "current_assignee": {
    "name": "John Smith",
    "accepted_at": "2024-03-20 10:15:00",
    "status": "in_progress"
  }
}
```

---

### **5. File Attachments Relationship**

#### **Current Problem:**
Tasks, Inspections, Snags need file attachments but no proper relationship

#### **Missing Table Structure:**
```sql
CREATE TABLE attachments (
  id INT PRIMARY KEY,
  attachable_type VARCHAR(50), -- 'task', 'inspection', 'snag', 'daily_log'
  attachable_id INT,
  file_name VARCHAR(255),
  file_path VARCHAR(500),
  file_size INT,
  file_type VARCHAR(100),
  uploaded_by INT,
  uploaded_at TIMESTAMP,
  FOREIGN KEY (uploaded_by) REFERENCES users(id)
);
```

#### **Required API Response:**
```php
// All APIs should include:
{
  "attachments": [
    {
      "id": 1,
      "file_name": "foundation_photo.jpg",
      "file_size": "2.4MB",
      "file_type": "image/jpeg",
      "uploaded_by": {"name": "John Smith"},
      "uploaded_at": "2024-03-20 14:30:00",
      "download_url": "/api/files/download/1"
    }
  ],
  "attachments_count": 3
}
```

---

### **6. Comments/Communication System**

#### **Current Problem:**
No cross-role communication system for tasks/issues

#### **Missing Table Structure:**
```sql
CREATE TABLE comments (
  id INT PRIMARY KEY,
  commentable_type VARCHAR(50), -- 'task', 'inspection', 'snag', 'project'
  commentable_id INT,
  user_id INT,
  comment TEXT,
  parent_comment_id INT, -- For replies
  created_at TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (parent_comment_id) REFERENCES comments(id)
);
```

#### **Required API Response:**
```php
// Tasks/Inspections/Snags APIs should include:
{
  "comments": [
    {
      "id": 1,
      "user": {"name": "Sarah Johnson", "role": "Project Manager", "avatar": "sarah.jpg"},
      "comment": "Please prioritize this task",
      "created_at": "2024-03-20 11:00:00",
      "replies": [
        {
          "user": {"name": "John Smith", "role": "Site Engineer"},
          "comment": "Will complete by tomorrow",
          "created_at": "2024-03-20 11:30:00"
        }
      ]
    }
  ],
  "comments_count": 5
}
```

---

### **7. Inspection Checklists (Missing Tables)**

#### **Current Problem:**
Inspections need predefined checklists but no structure exists

#### **Missing Table Structures:**
```sql
CREATE TABLE inspection_templates (
  id INT PRIMARY KEY,
  name VARCHAR(255), -- 'Safety Inspection', 'Quality Check'
  category VARCHAR(100), -- 'safety', 'quality', 'compliance'
  created_by INT,
  is_active BOOLEAN
);

CREATE TABLE inspection_checklist_items (
  id INT PRIMARY KEY,
  template_id INT,
  item_text TEXT,
  is_required BOOLEAN,
  order_index INT,
  FOREIGN KEY (template_id) REFERENCES inspection_templates(id)
);

CREATE TABLE inspection_results (
  id INT PRIMARY KEY,
  inspection_id INT,
  checklist_item_id INT,
  status VARCHAR(50), -- 'pass', 'fail', 'na'
  notes TEXT,
  photo_path VARCHAR(500),
  FOREIGN KEY (inspection_id) REFERENCES inspections(id),
  FOREIGN KEY (checklist_item_id) REFERENCES inspection_checklist_items(id)
);
```

#### **Required API Response:**
```php
// Inspections API should include:
{
  "template": {
    "name": "Safety Inspection",
    "category": "safety"
  },
  "checklist_items": [
    {
      "id": 1,
      "text": "Hard hats worn by all workers",
      "is_required": true,
      "status": "pass",
      "notes": "All workers compliant",
      "photo": "safety_photo1.jpg"
    }
  ],
  "completion_percentage": 80,
  "passed_items": 12,
  "failed_items": 2,
  "total_items": 15
}
```

---

### **8. Project Progress Calculation**

#### **Current Problem:**
Dashboard shows progress percentage but no calculation logic

#### **Missing Calculation Logic:**
```php
// Required API: GET /api/v1/projects/{id}/progress_calculation
{
  "overall_progress": 68,
  "calculation_method": "task_based", // or 'milestone_based', 'manual'
  "progress_breakdown": {
    "completed_tasks": 15,
    "total_tasks": 22,
    "task_progress": 68,
    "milestone_progress": 70,
    "weighted_progress": 68
  },
  "phase_progress": [
    {"phase": "Foundation", "progress": 100, "weight": 20},
    {"phase": "Structure", "progress": 45, "weight": 50},
    {"phase": "Finishing", "progress": 0, "weight": 30}
  ]
}
```

---

### **9. Notification Recipients (Missing Logic)**

#### **Current Problem:**
No system to determine who gets what notifications

#### **Missing Table Structure:**
```sql
CREATE TABLE notification_rules (
  id INT PRIMARY KEY,
  event_type VARCHAR(100), -- 'task_assigned', 'task_completed', 'snag_reported'
  recipient_role VARCHAR(50), -- 'project_manager', 'site_engineer', 'contractor'
  notification_method VARCHAR(50), -- 'in_app', 'email', 'sms'
  is_active BOOLEAN
);

CREATE TABLE user_notification_preferences (
  id INT PRIMARY KEY,
  user_id INT,
  event_type VARCHAR(100),
  method VARCHAR(50),
  is_enabled BOOLEAN,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
```

---

### **10. Role-Based Data Filtering**

#### **Current Problem:**
All users see same data regardless of role

#### **Required API Logic:**
```php
// Dashboard API should filter based on user role:

// For Site Engineer:
{
  "my_tasks": [...], // Only tasks assigned to this user
  "my_inspections": [...], // Only inspections assigned to this user
  "my_projects": [...] // Only projects where user is team member
}

// For Project Manager:
{
  "managed_projects": [...], // Projects where user is PM
  "team_tasks": [...], // All tasks in managed projects
  "pending_approvals": [...] // Items waiting for approval
}

// For Contractor:
{
  "owned_projects": [...], // All projects created by this user
  "company_projects": [...], // All projects in same company
  "financial_summary": [...] // Budget and cost data
}
```

---

## 🔧 Required Database Changes

### **New Tables Needed:**
1. `project_team_members` - Track team assignments
2. `task_dependencies` - Task prerequisite relationships  
3. `assignment_history` - Track all assignments
4. `attachments` - File attachments for all entities
5. `comments` - Communication system
6. `inspection_templates` - Predefined checklists
7. `inspection_checklist_items` - Checklist items
8. `inspection_results` - Inspection outcomes
9. `notification_rules` - Notification logic
10. `user_notification_preferences` - User preferences

### **Modified APIs Needed:**
1. **Projects API** - Add team members, progress calculation
2. **Tasks API** - Add user details, dependencies, comments
3. **Inspections API** - Add checklists, results
4. **Dashboard API** - Add role-based filtering
5. **Notifications API** - Add recipient logic
6. **Users API** - Add role-based permissions

### **New APIs Needed:**
1. `GET /api/v1/projects/{id}/team` - Team management
2. `POST /api/v1/assignments/track` - Assignment tracking
3. `GET /api/v1/dashboard/role_specific` - Role-based dashboard
4. `POST /api/v1/communications/send_message` - Cross-role messaging
5. `GET /api/v1/inspections/templates` - Inspection templates
6. `POST /api/v1/attachments/upload` - File attachment system

---

## 📊 Impact Analysis

### **High Impact (Critical for Workflow):**
- User information in all APIs (names, roles, avatars)
- Project team members tracking
- Assignment history and tracking
- File attachments system

### **Medium Impact (Important for Features):**
- Task dependencies
- Comments/communication system
- Inspection checklists
- Progress calculation logic

### **Low Impact (Nice to Have):**
- Advanced notification preferences
- Detailed role permissions
- Advanced reporting features

This analysis shows that the current API structure is missing critical relational data that's essential for the construction workflow to function properly.