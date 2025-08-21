# Biltix - Complete API Development Guide

## 🚀 **API STRUCTURE & DEVELOPMENT ROADMAP**

### **BASE API SETUP**
```
Base URL: https://yourdomain.com/api/v1/
Headers: 
- Content-Type: application/json
- Authorization: Bearer {token}
```

---

## **PHASE 1: AUTHENTICATION APIs**

### **1.1 User Registration API**
**Endpoint:** `POST /api/v1/register`
**Figma Reference:** `register.pdf`, `register-1.pdf`, `register-2.pdf`

```json
// Request Body
{
  "name": "John Smith",
  "email": "john@example.com", 
  "phone": "+1234567890",
  "password": "password123",
  "company_name": "BuildCorp Construction",
  "designation": "Site Engineer",
  "role": "site_engineer",
  "employee_count": 50,
  "member_number": "ENG001", // optional
  "member_name": "John Smith" // optional
}

// Response
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Smith",
      "email": "john@example.com",
      "role": "site_engineer"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### **1.2 User Login API**
**Endpoint:** `POST /api/v1/login`
**Figma Reference:** `login.pdf`

```json
// Request Body
{
  "email": "john@example.com",
  "password": "password123"
}

// Response
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Smith",
      "email": "john@example.com",
      "role": "site_engineer",
      "company_name": "BuildCorp Construction"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### **1.3 Profile Management APIs**
```json
// GET /api/v1/profile - Get user profile
// PUT /api/v1/profile - Update profile
// POST /api/v1/logout - Logout user
```

---

## **PHASE 2: DASHBOARD APIs**

### **2.1 Dashboard Stats API**
**Endpoint:** `GET /api/v1/dashboard`
**Figma Reference:** `home.pdf`, `home-2.pdf`

```json
// Response
{
  "success": true,
  "data": {
    "stats": {
      "active_projects": 12,
      "pending_reviews": 8,
      "inspections_due": 5,
      "completed_this_month": 3
    },
    "recent_projects": [
      {
        "id": 1,
        "name": "Downtown Office Complex",
        "progress": 68,
        "status": "active",
        "due_date": "2024-12-15",
        "team_count": 7
      }
    ]
  }
}
```

---

## **PHASE 3: PROJECT MANAGEMENT APIs**

### **3.1 Create Project API**
**Endpoint:** `POST /api/v1/projects`
**Figma Reference:** `create project-1.pdf` to `create project-5.pdf`

```json
// Request Body (5-step data combined)
{
  // Step 1: Basic Info
  "name": "Downtown Office Complex",
  "description": "Modern office building construction",
  "type": "commercial",
  "location": "Downtown Area",
  "start_date": "2024-01-15",
  "end_date": "2024-12-15",
  
  // Step 2: Client Details
  "client_name": "ABC Corporation",
  "client_email": "client@abc.com",
  "client_phone": "+1234567890",
  "budget": 5000000,
  
  // Step 3: Team Assignment
  "project_manager_id": 2,
  "team_members": [3, 4, 5],
  
  // Step 4: Phases
  "phases": [
    {
      "name": "Foundation",
      "start_date": "2024-01-15",
      "end_date": "2024-03-15",
      "budget_allocated": 1000000
    }
  ]
}

// Response
{
  "success": true,
  "message": "Project created successfully",
  "data": {
    "project": {
      "id": 1,
      "name": "Downtown Office Complex",
      "status": "planning",
      "progress_percentage": 0
    }
  }
}
```

### **3.2 Project List API**
**Endpoint:** `GET /api/v1/projects`

```json
// Query Parameters: ?status=active&search=office
// Response
{
  "success": true,
  "data": {
    "projects": [
      {
        "id": 1,
        "name": "Downtown Office Complex",
        "type": "commercial",
        "status": "active",
        "progress_percentage": 68,
        "project_manager": "John Smith",
        "team_count": 7,
        "images": ["image1.jpg", "image2.jpg"]
      }
    ],
    "pagination": {
      "current_page": 1,
      "total_pages": 5,
      "total_count": 25
    }
  }
}
```

### **3.3 Project Details API**
**Endpoint:** `GET /api/v1/projects/{id}`
**Figma Reference:** `project progress-*.pdf`

```json
// Response
{
  "success": true,
  "data": {
    "project": {
      "id": 1,
      "name": "Downtown Office Complex",
      "progress_percentage": 67,
      "active_workers": 148,
      "days_remaining": 45,
      "phases": [
        {
          "name": "Foundation",
          "progress_percentage": 100,
          "status": "completed"
        },
        {
          "name": "Structure", 
          "progress_percentage": 85,
          "status": "active"
        }
      ],
      "manpower": {
        "engineers": 3,
        "foremen": 2,
        "laborers": 25
      }
    }
  }
}
```

---

## **PHASE 4: TASK MANAGEMENT APIs**

### **4.1 Create Task API**
**Endpoint:** `POST /api/v1/tasks`
**Figma Reference:** `tasks.pdf`, `task created*.pdf`

```json
// Request Body
{
  "project_id": 1,
  "title": "Pour Concrete Slab",
  "description": "Pour slab for level 2, ensure curing compound is ready",
  "priority": "medium",
  "assigned_to": 3,
  "due_date": "2024-03-26",
  "location": "Level 2"
}

// Response
{
  "success": true,
  "message": "Task created successfully",
  "data": {
    "task": {
      "id": 1,
      "title": "Pour Concrete Slab",
      "status": "pending",
      "progress_percentage": 0
    }
  }
}
```

### **4.2 Task List API**
**Endpoint:** `GET /api/v1/tasks`

```json
// Query Parameters: ?project_id=1&status=pending&assigned_to=3
// Response
{
  "success": true,
  "data": {
    "tasks": [
      {
        "id": 1,
        "title": "Pour Concrete Slab",
        "status": "pending",
        "priority": "medium",
        "due_date": "2024-03-26",
        "assigned_to": "John Smith",
        "progress_percentage": 25
      }
    ]
  }
}
```

### **4.3 Update Task API**
**Endpoint:** `PUT /api/v1/tasks/{id}`

```json
// Request Body
{
  "status": "in_progress",
  "progress_percentage": 50,
  "notes": "Work started, materials delivered"
}
```

---

## **PHASE 5: INSPECTION MANAGEMENT APIs**

### **5.1 Create Inspection API**
**Endpoint:** `POST /api/v1/inspections`
**Figma Reference:** `new inspection*.pdf`

```json
// Request Body
{
  "project_id": 1,
  "template_id": 1,
  "title": "Foundation Safety Inspection",
  "scheduled_date": "2024-03-20",
  "inspector_id": 4,
  "location": "Foundation Area"
}

// Response
{
  "success": true,
  "message": "Inspection scheduled successfully",
  "data": {
    "inspection": {
      "id": 1,
      "title": "Foundation Safety Inspection",
      "status": "scheduled"
    }
  }
}
```

### **5.2 Inspection Templates API**
**Endpoint:** `GET /api/v1/inspection-templates`

```json
// Response
{
  "success": true,
  "data": {
    "templates": [
      {
        "id": 1,
        "name": "Safety Inspection",
        "category": "safety",
        "checklist_items": [
          {
            "id": "safety_001",
            "name": "PPE Check",
            "description": "All workers wearing proper PPE"
          }
        ]
      }
    ]
  }
}
```

### **5.3 Complete Inspection API**
**Endpoint:** `POST /api/v1/inspections/{id}/complete`
**Figma Reference:** `inspection complete*.pdf`

```json
// Request Body
{
  "results": [
    {
      "item_id": "safety_001",
      "result": "pass",
      "notes": "All workers properly equipped",
      "images": ["safety1.jpg", "safety2.jpg"]
    }
  ],
  "overall_result": "pass",
  "score_percentage": 95,
  "inspector_notes": "Excellent safety compliance"
}
```

---

## **PHASE 6: SNAG MANAGEMENT APIs**

### **6.1 Create Snag API**
**Endpoint:** `POST /api/v1/snags`
**Figma Reference:** `new snag report*.pdf`

```json
// Request Body
{
  "project_id": 1,
  "title": "Electrical outlet not working in Room 205",
  "description": "Power outlet on north wall not functioning",
  "location": "Building A, Floor 2, Room 205",
  "priority": "high",
  "category_id": 1,
  "images_before": ["snag1.jpg", "snag2.jpg"]
}

// Response
{
  "success": true,
  "message": "Snag reported successfully",
  "data": {
    "snag": {
      "id": 1,
      "title": "Electrical outlet not working in Room 205",
      "status": "open",
      "snag_number": "SNG-2024-001"
    }
  }
}
```

### **6.2 Snag List API**
**Endpoint:** `GET /api/v1/snags`
**Figma Reference:** `all snags*.pdf`

```json
// Query Parameters: ?project_id=1&status=open&priority=high
// Response
{
  "success": true,
  "data": {
    "snags": [
      {
        "id": 1,
        "title": "Electrical outlet not working in Room 205",
        "status": "in_progress",
        "priority": "high",
        "reported_by": "John Smith",
        "assigned_to": "Mike Wilson",
        "created_at": "2024-01-15"
      }
    ]
  }
}
```

### **6.3 Resolve Snag API**
**Endpoint:** `POST /api/v1/snags/{id}/resolve`

```json
// Request Body
{
  "resolution_notes": "Replaced faulty outlet and tested",
  "images_after": ["fixed1.jpg", "fixed2.jpg"],
  "actual_cost": 150,
  "actual_time_hours": 2
}
```

---

## **PHASE 7: PLANS MANAGEMENT APIs**

### **7.1 Upload Plan API**
**Endpoint:** `POST /api/v1/plans`
**Figma Reference:** `plans.pdf`

```json
// Request Body (multipart/form-data)
{
  "project_id": 1,
  "title": "Ground Floor Plan",
  "plan_type": "architectural",
  "version": "Rev. 3.2",
  "file": [uploaded file]
}

// Response
{
  "success": true,
  "message": "Plan uploaded successfully",
  "data": {
    "plan": {
      "id": 1,
      "title": "Ground Floor Plan",
      "file_size": "2.4 MB",
      "thumbnail_url": "thumbnails/plan1.jpg"
    }
  }
}
```

### **7.2 Plan List API**
**Endpoint:** `GET /api/v1/plans`

```json
// Query Parameters: ?project_id=1&plan_type=architectural
// Response
{
  "success": true,
  "data": {
    "plans": [
      {
        "id": 1,
        "title": "Ground Floor Plan",
        "plan_type": "architectural",
        "version": "Rev. 3.2",
        "file_size": "2.4 MB",
        "updated_at": "2 days ago",
        "thumbnail_url": "thumbnails/plan1.jpg"
      }
    ]
  }
}
```

### **7.3 Plan Markup API**
**Endpoint:** `POST /api/v1/plans/{id}/markups`
**Figma Reference:** `drawing markup*.pdf`

```json
// Request Body
{
  "markup_type": "inspection",
  "title": "Safety Issue",
  "description": "Missing safety barrier",
  "markup_data": {
    "coordinates": {"x": 150, "y": 200},
    "shape": "circle",
    "color": "#ff0000"
  }
}
```

---

## **PHASE 8: DAILY OPERATIONS APIs**

### **8.1 Create Daily Log API**
**Endpoint:** `POST /api/v1/daily-logs`
**Figma Reference:** `daily tasks performed*.pdf`

```json
// Request Body
{
  "project_id": 1,
  "log_date": "2024-01-15",
  "weather_conditions": "Sunny, 25°C",
  "work_performed": "Foundation concrete pouring completed",
  "equipment_logs": [
    {
      "equipment_id": "EXC-001",
      "equipment_type": "excavator",
      "operator_name": "John Smith",
      "status": "active",
      "hours_used": 6.5,
      "location": "Zone A"
    }
  ],
  "staff_logs": [
    {
      "staff_type": "engineers",
      "count": 3,
      "hours_worked": 8
    }
  ]
}
```

### **8.2 Daily Log Stats API**
**Endpoint:** `GET /api/v1/daily-logs/stats`

```json
// Query Parameters: ?project_id=1&date=2024-01-15
// Response
{
  "success": true,
  "data": {
    "stats": {
      "active_equipment": 24,
      "staff_present": 42,
      "tasks_completed": 18
    },
    "equipment_logs": [...],
    "staff_logs": [...],
    "task_logs": [...]
  }
}
```

---

## **PHASE 9: TEAM MANAGEMENT APIs**

### **9.1 Team Members API**
**Endpoint:** `GET /api/v1/team-members`
**Figma Reference:** `peoples.pdf`

```json
// Query Parameters: ?project_id=1&role=site_engineer&status=active
// Response
{
  "success": true,
  "data": {
    "stats": {
      "total_members": 36,
      "site_engineers": 18,
      "contractors": 12,
      "consultants": 6
    },
    "members": [
      {
        "id": 1,
        "name": "John Smith",
        "role": "Project Manager",
        "company_name": "BuildCorp Construction",
        "status": "active",
        "role_type": "contractor",
        "avatar_url": "avatars/john.jpg"
      }
    ]
  }
}
```

### **9.2 Assign Team Member API**
**Endpoint:** `POST /api/v1/projects/{id}/assign-member`

```json
// Request Body
{
  "user_id": 3,
  "role_in_project": "site_engineer",
  "permissions": ["view_tasks", "update_progress"]
}
```

---

## **PHASE 10: FILE & PHOTO MANAGEMENT APIs**

### **10.1 Upload Files API**
**Endpoint:** `POST /api/v1/files`
**Figma Reference:** `files.pdf`

```json
// Request Body (multipart/form-data)
{
  "project_id": 1,
  "category_id": 1,
  "files": [uploaded files],
  "description": "Project documentation"
}
```

### **10.2 Photo Gallery API**
**Endpoint:** `GET /api/v1/photos`
**Figma Reference:** `photos.pdf`

```json
// Query Parameters: ?project_id=1&date=2024-01-15&phase_id=1
// Response
{
  "success": true,
  "data": {
    "photos": [
      {
        "id": 1,
        "title": "Foundation Progress",
        "file_path": "photos/foundation1.jpg",
        "thumbnail_path": "thumbnails/foundation1.jpg",
        "taken_at": "2024-01-15 10:30:00",
        "taken_by": "John Smith",
        "location": "Foundation Area"
      }
    ]
  }
}
```

---

## **PHASE 11: NOTIFICATIONS API**

### **11.1 Notifications API**
**Endpoint:** `GET /api/v1/notifications`
**Figma Reference:** `notifications.pdf`

```json
// Response
{
  "success": true,
  "data": {
    "notifications": [
      {
        "id": 1,
        "type": "task_assigned",
        "title": "New Task Assigned",
        "message": "You have been assigned: Pour Concrete Slab",
        "is_read": false,
        "priority": "medium",
        "created_at": "2024-01-15 09:00:00",
        "data": {
          "task_id": 1,
          "project_id": 1
        }
      }
    ]
  }
}
```

### **11.2 Mark Notification Read API**
**Endpoint:** `PUT /api/v1/notifications/{id}/read`

---

## **🔧 BACKEND IMPLEMENTATION TIPS**

### **Laravel API Structure:**
```php
// Routes (api.php)
Route::prefix('v1')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('tasks', TaskController::class);
        // ... more routes
    });
});
```

### **Controller Example:**
```php
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::with(['projectManager', 'teamMembers'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->paginate(10);
            
        return response()->json([
            'success' => true,
            'data' => [
                'projects' => $projects->items(),
                'pagination' => [
                    'current_page' => $projects->currentPage(),
                    'total_pages' => $projects->lastPage(),
                    'total_count' => $projects->total()
                ]
            ]
        ]);
    }
}
```

### **Validation Example:**
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'required|in:residential,commercial,industrial,renovation',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'budget' => 'required|numeric|min:0'
    ]);
    
    $project = Project::create($validated);
    
    return response()->json([
        'success' => true,
        'message' => 'Project created successfully',
        'data' => ['project' => $project]
    ], 201);
}
```

### **Error Handling:**
```php
// In Handler.php
public function render($request, Throwable $exception)
{
    if ($request->expectsJson()) {
        return response()->json([
            'success' => false,
            'message' => $exception->getMessage(),
            'data' => []
        ], 500);
    }
    
    return parent::render($request, $exception);
}
```

This guide provides everything needed to build a complete API backend that matches the Figma designs and supports all the features shown in the project flow.