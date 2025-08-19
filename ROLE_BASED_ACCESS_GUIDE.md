# Biltix - Role-Based Access Control (RBAC) Guide

## 🎯 **ROLE-BASED ACCESS CONTROL SYSTEM**

### **5 Main User Roles:**
1. **Contractor** - Full project control
2. **Consultant** - Review & approval 
3. **Site Engineer** - Field operations
4. **Project Manager** - Project oversight
5. **Stakeholder** - Read-only access

---

## **📋 PERMISSION MATRIX**

### **Contractor Permissions:**
```json
{
  "projects": ["create", "edit", "delete", "view"],
  "tasks": ["create", "edit", "assign", "delete", "view"],
  "inspections": ["create", "view", "assign"],
  "snags": ["create", "edit", "resolve", "assign", "view"],
  "plans": ["upload", "markup", "approve", "view"],
  "team": ["add", "remove", "assign", "view"],
  "daily_logs": ["create", "edit", "view"],
  "files": ["upload", "delete", "view"],
  "photos": ["upload", "delete", "view"],
  "reports": ["generate", "view"]
}
```

### **Site Engineer Permissions:**
```json
{
  "projects": ["view"],
  "tasks": ["update", "complete", "view"],
  "inspections": ["conduct", "complete", "view"],
  "snags": ["create", "update", "view"],
  "plans": ["view", "markup"],
  "team": ["view"],
  "daily_logs": ["create", "edit", "view"],
  "files": ["upload", "view"],
  "photos": ["upload", "view"],
  "reports": ["view"]
}
```

### **Consultant Permissions:**
```json
{
  "projects": ["view", "comment"],
  "tasks": ["view", "comment"],
  "inspections": ["create", "approve", "view"],
  "snags": ["review", "approve", "view"],
  "plans": ["markup", "approve", "view"],
  "team": ["view"],
  "daily_logs": ["view"],
  "files": ["view"],
  "photos": ["view"],
  "reports": ["view"]
}
```

### **Project Manager Permissions:**
```json
{
  "projects": ["view", "edit"],
  "tasks": ["assign", "track", "view"],
  "inspections": ["schedule", "review", "view"],
  "snags": ["assign", "track", "view"],
  "plans": ["view", "approve"],
  "team": ["view", "coordinate"],
  "daily_logs": ["view"],
  "files": ["view"],
  "photos": ["view"],
  "reports": ["generate", "view"]
}
```

### **Stakeholder Permissions:**
```json
{
  "projects": ["view"],
  "tasks": ["view"],
  "inspections": ["view"],
  "snags": ["view"],
  "plans": ["view"],
  "team": ["view"],
  "daily_logs": ["view"],
  "files": ["view"],
  "photos": ["view"],
  "reports": ["view"]
}
```

---

## **🔧 BACKEND IMPLEMENTATION**

### **1. Database Setup**
```sql
-- Users table already has role field
users:
- role (contractor, consultant, site_engineer, project_manager, stakeholder)

-- Role permissions table
role_permissions:
- id (PK)
- role_name (contractor, consultant, etc.)
- module_name (projects, tasks, inspections, etc.)
- permissions (JSON: ["create", "edit", "view"])
```

### **2. Laravel Middleware**
```php
// app/Http/Middleware/CheckPermission.php
class CheckPermission
{
    public function handle($request, Closure $next, $module, $action)
    {
        $user = $request->user();
        
        // Get user's role permissions
        $permissions = $this->getUserPermissions($user->role, $module);
        
        // Check if user has required permission
        if (!in_array($action, $permissions)) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Insufficient permissions.',
                'data' => []
            ], 403);
        }
        
        return $next($request);
    }
    
    private function getUserPermissions($role, $module)
    {
        $rolePermissions = [
            'contractor' => [
                'projects' => ['create', 'edit', 'delete', 'view'],
                'tasks' => ['create', 'edit', 'assign', 'delete', 'view'],
                'inspections' => ['create', 'view', 'assign'],
                'snags' => ['create', 'edit', 'resolve', 'assign', 'view'],
                'plans' => ['upload', 'markup', 'approve', 'view'],
            ],
            'site_engineer' => [
                'projects' => ['view'],
                'tasks' => ['update', 'complete', 'view'],
                'inspections' => ['conduct', 'complete', 'view'],
                'snags' => ['create', 'update', 'view'],
                'plans' => ['view', 'markup'],
            ],
            'consultant' => [
                'projects' => ['view', 'comment'],
                'tasks' => ['view', 'comment'],
                'inspections' => ['create', 'approve', 'view'],
                'snags' => ['review', 'approve', 'view'],
                'plans' => ['markup', 'approve', 'view'],
            ],
            'project_manager' => [
                'projects' => ['view', 'edit'],
                'tasks' => ['assign', 'track', 'view'],
                'inspections' => ['schedule', 'review', 'view'],
                'snags' => ['assign', 'track', 'view'],
                'plans' => ['view', 'approve'],
            ],
            'stakeholder' => [
                'projects' => ['view'],
                'tasks' => ['view'],
                'inspections' => ['view'],
                'snags' => ['view'],
                'plans' => ['view'],
            ]
        ];
        
        return $rolePermissions[$role][$module] ?? [];
    }
}
```

### **3. Route Protection**
```php
// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    
    // Projects - Only contractors can create/edit/delete
    Route::post('projects', [ProjectController::class, 'store'])
        ->middleware('permission:projects,create');
    
    Route::put('projects/{id}', [ProjectController::class, 'update'])
        ->middleware('permission:projects,edit');
    
    Route::delete('projects/{id}', [ProjectController::class, 'destroy'])
        ->middleware('permission:projects,delete');
    
    // All roles can view projects
    Route::get('projects', [ProjectController::class, 'index'])
        ->middleware('permission:projects,view');
    
    // Tasks - Different permissions for different roles
    Route::post('tasks', [TaskController::class, 'store'])
        ->middleware('permission:tasks,create');
    
    Route::put('tasks/{id}', [TaskController::class, 'update'])
        ->middleware('permission:tasks,edit');
    
    // Inspections - Site engineers can conduct, consultants can approve
    Route::post('inspections/{id}/complete', [InspectionController::class, 'complete'])
        ->middleware('permission:inspections,conduct');
    
    Route::post('inspections/{id}/approve', [InspectionController::class, 'approve'])
        ->middleware('permission:inspections,approve');
});
```

### **4. Controller Level Checks**
```php
// app/Http/Controllers/ProjectController.php
class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Project::query();
        
        // Role-based data filtering
        switch ($user->role) {
            case 'contractor':
                // Contractors see all projects they created
                $query->where('created_by', $user->id);
                break;
                
            case 'project_manager':
                // Project managers see projects they manage
                $query->where('project_manager_id', $user->id);
                break;
                
            case 'site_engineer':
                // Site engineers see projects they're assigned to
                $query->whereHas('teamMembers', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
                break;
                
            case 'consultant':
                // Consultants see projects they're consulting on
                $query->whereHas('teamMembers', function($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->where('role_in_project', 'consultant');
                });
                break;
                
            case 'stakeholder':
                // Stakeholders see projects they have access to
                $query->whereHas('teamMembers', function($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
                break;
        }
        
        $projects = $query->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => ['projects' => $projects]
        ]);
    }
    
    public function store(Request $request)
    {
        // Only contractors can create projects
        if ($request->user()->role !== 'contractor') {
            return response()->json([
                'success' => false,
                'message' => 'Only contractors can create projects'
            ], 403);
        }
        
        // Create project logic...
    }
}
```

---

## **📱 FRONTEND IMPLEMENTATION**

### **1. Role-Based UI Components**
```javascript
// Mobile App - React Native
const TaskCard = ({ task, userRole }) => {
  const canEdit = ['contractor', 'site_engineer'].includes(userRole);
  const canAssign = ['contractor', 'project_manager'].includes(userRole);
  
  return (
    <View>
      <Text>{task.title}</Text>
      
      {canEdit && (
        <Button title="Edit Task" onPress={() => editTask(task.id)} />
      )}
      
      {canAssign && (
        <Button title="Assign" onPress={() => assignTask(task.id)} />
      )}
      
      {userRole === 'site_engineer' && (
        <Button title="Update Progress" onPress={() => updateProgress(task.id)} />
      )}
    </View>
  );
};
```

### **2. API Request with Role Check**
```javascript
// API Service
class ApiService {
  async createProject(projectData) {
    const user = await this.getCurrentUser();
    
    if (user.role !== 'contractor') {
      throw new Error('Only contractors can create projects');
    }
    
    return this.post('/api/v1/projects', projectData);
  }
  
  async updateTask(taskId, updateData) {
    const user = await this.getCurrentUser();
    const allowedRoles = ['contractor', 'site_engineer'];
    
    if (!allowedRoles.includes(user.role)) {
      throw new Error('You do not have permission to update tasks');
    }
    
    return this.put(`/api/v1/tasks/${taskId}`, updateData);
  }
}
```

---

## **🔄 REAL-WORLD EXAMPLES**

### **Example 1: Task Assignment Flow**
```
1. Contractor creates task → ✅ Allowed
2. Contractor assigns to Site Engineer → ✅ Allowed
3. Site Engineer updates progress → ✅ Allowed
4. Consultant tries to delete task → ❌ Blocked
5. Stakeholder tries to edit task → ❌ Blocked
```

### **Example 2: Inspection Approval Flow**
```
1. Site Engineer conducts inspection → ✅ Allowed
2. Site Engineer submits results → ✅ Allowed
3. Consultant reviews and approves → ✅ Allowed
4. Stakeholder views approved inspection → ✅ Allowed
5. Project Manager tries to conduct inspection → ❌ Blocked
```

### **Example 3: Snag Resolution Flow**
```
1. Site Engineer reports snag → ✅ Allowed
2. Contractor assigns to team member → ✅ Allowed
3. Team member updates resolution → ✅ Allowed
4. Consultant approves resolution → ✅ Allowed
5. Stakeholder views resolved snag → ✅ Allowed
```

---

## **🛡️ SECURITY BEST PRACTICES**

### **1. Always Check on Backend**
```php
// Never trust frontend - always verify on server
public function updateTask(Request $request, $id)
{
    $user = $request->user();
    $task = Task::findOrFail($id);
    
    // Check if user can edit this specific task
    if (!$this->canUserEditTask($user, $task)) {
        return response()->json(['message' => 'Access denied'], 403);
    }
    
    // Update logic...
}

private function canUserEditTask($user, $task)
{
    // Contractors can edit any task in their projects
    if ($user->role === 'contractor' && $task->project->created_by === $user->id) {
        return true;
    }
    
    // Site engineers can edit tasks assigned to them
    if ($user->role === 'site_engineer' && $task->assigned_to === $user->id) {
        return true;
    }
    
    return false;
}
```

### **2. Database Level Security**
```php
// Use Laravel Policies
class TaskPolicy
{
    public function update(User $user, Task $task)
    {
        return in_array($user->role, ['contractor', 'site_engineer']) &&
               ($task->assigned_to === $user->id || $task->project->created_by === $user->id);
    }
}

// In Controller
public function update(Request $request, Task $task)
{
    $this->authorize('update', $task);
    // Update logic...
}
```

This system ensures that each user role has exactly the right permissions they need, nothing more, nothing less. The backend enforces all security rules, while the frontend provides a smooth user experience by showing/hiding features based on roles.