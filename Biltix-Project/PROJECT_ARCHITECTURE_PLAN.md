# 🏗️ Biltix Project Management System - Complete Architecture Plan

## 📋 Overview
यह एक construction project management system है जहाम:
- **Dashboard**: सभी projects की list
- **Project Details**: किसी specific project की complete information
- **Project Pages**: Plans, Tasks, Inspections, etc.

---

## 🗄️ Database Structure

### 1. **Projects Table**
```sql
CREATE TABLE projects (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    type VARCHAR(100), -- Commercial, Residential, Industrial
    status ENUM('active', 'completed', 'on_hold', 'cancelled'),
    progress INT DEFAULT 0, -- 0-100%
    start_date DATE,
    end_date DATE,
    budget DECIMAL(15,2),
    location VARCHAR(255),
    client_name VARCHAR(255),
    project_manager_id BIGINT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2. **Project Plans Table**
```sql
CREATE TABLE project_plans (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT,
    plan_name VARCHAR(255),
    plan_type VARCHAR(100), -- Architectural, Structural, Electrical
    file_path VARCHAR(500),
    version VARCHAR(50),
    status ENUM('draft', 'approved', 'rejected'),
    uploaded_by BIGINT,
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
```

### 3. **Tasks Table**
```sql
CREATE TABLE tasks (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT,
    title VARCHAR(255),
    description TEXT,
    status ENUM('pending', 'in_progress', 'completed', 'on_hold'),
    priority ENUM('low', 'medium', 'high', 'urgent'),
    assigned_to BIGINT,
    start_date DATE,
    due_date DATE,
    completion_date DATE,
    progress INT DEFAULT 0,
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
```

### 4. **Inspections Table**
```sql
CREATE TABLE inspections (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT,
    inspection_type VARCHAR(100), -- Foundation, Structural, Electrical
    scheduled_date DATETIME,
    inspector_name VARCHAR(255),
    status ENUM('scheduled', 'completed', 'failed', 'rescheduled'),
    notes TEXT,
    report_file VARCHAR(500),
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
```

### 5. **Daily Logs Table**
```sql
CREATE TABLE daily_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT,
    log_date DATE,
    weather VARCHAR(100),
    work_description TEXT,
    workers_count INT,
    materials_used TEXT,
    issues TEXT,
    logged_by BIGINT,
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
```

### 6. **Project Files Table**
```sql
CREATE TABLE project_files (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT,
    file_name VARCHAR(255),
    file_path VARCHAR(500),
    file_type VARCHAR(100), -- Document, Image, Video
    category VARCHAR(100), -- Plans, Reports, Photos
    uploaded_by BIGINT,
    file_size BIGINT,
    created_at TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
```

### 7. **Team Members Table**
```sql
CREATE TABLE project_team_members (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    project_id BIGINT,
    user_id BIGINT,
    role VARCHAR(100), -- Manager, Engineer, Worker, Supervisor
    joined_date DATE,
    status ENUM('active', 'inactive'),
    FOREIGN KEY (project_id) REFERENCES projects(id)
);
```

---

## 🎯 Laravel Models

### 1. **Project Model**
```php
class Project extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'status', 'progress',
        'start_date', 'end_date', 'budget', 'location', 'client_name'
    ];

    // Relationships
    public function plans() {
        return $this->hasMany(ProjectPlan::class);
    }
    
    public function tasks() {
        return $this->hasMany(Task::class);
    }
    
    public function inspections() {
        return $this->hasMany(Inspection::class);
    }
    
    public function dailyLogs() {
        return $this->hasMany(DailyLog::class);
    }
    
    public function files() {
        return $this->hasMany(ProjectFile::class);
    }
    
    public function teamMembers() {
        return $this->hasMany(ProjectTeamMember::class);
    }
}
```

---

## 🔄 Controller Structure

### **HomeController Methods**

```php
class HomeController extends Controller
{
    // Dashboard - सभी projects
    public function dashboard()
    {
        $projects = Project::with(['tasks', 'teamMembers'])
                          ->where('status', 'active')
                          ->get();
        
        $stats = [
            'active_projects' => Project::where('status', 'active')->count(),
            'pending_reviews' => Task::where('status', 'pending')->count(),
            'inspections_due' => Inspection::where('scheduled_date', '>=', now())->count(),
            'completed_this_month' => Project::where('status', 'completed')
                                           ->whereMonth('updated_at', now()->month)
                                           ->count()
        ];
        
        return view('website.dashboard', compact('projects', 'stats'));
    }

    // Project specific pages
    public function plans($project_id)
    {
        $project = Project::with('plans')->findOrFail($project_id);
        return view('website.plans', compact('project'));
    }

    public function tasks($project_id)
    {
        $project = Project::with(['tasks.assignedUser'])->findOrFail($project_id);
        return view('website.tasks', compact('project'));
    }

    public function inspections($project_id)
    {
        $project = Project::with('inspections')->findOrFail($project_id);
        return view('website.inspections', compact('project'));
    }
    
    // ... अन्य methods
}
```

---

## 🎨 View Structure

### **Dashboard View** (`dashboard.blade.php`)
```php
@extends('website.layout.app')

@section('content')
<!-- Stats Cards -->
<div class="stats-section">
    <div class="stat-card">Active Projects: {{ $stats['active_projects'] }}</div>
    <div class="stat-card">Pending Reviews: {{ $stats['pending_reviews'] }}</div>
    <!-- ... -->
</div>

<!-- Projects Grid -->
<div class="projects-grid">
    @foreach($projects as $project)
        <div class="project-card">
            <a href="{{ route('website.project.plans', $project->id) }}">
                <h6>{{ $project->name }}</h6>
                <p>{{ $project->type }}</p>
                <div class="progress-bar">{{ $project->progress }}%</div>
                <span class="status">{{ $project->status }}</span>
            </a>
        </div>
    @endforeach
</div>
@endsection
```

### **Project Header** (`layout/project-header.blade.php`)
```php
<header class="project-header">
    <div class="project-info">
        <h1>{{ $project->name }}</h1>
        <div class="project-details">
            <span>Start: {{ $project->start_date }}</span>
            <span>End: {{ $project->end_date }}</span>
            <span>Progress: {{ $project->progress }}%</span>
        </div>
    </div>
    <div class="project-status">
        <span class="status-badge">{{ $project->status }}</span>
    </div>
</header>
```

### **Sidebar Navigation** (`layout/sidebar.blade.php`)
```php
<nav class="sidebar">
    <ul>
        <li><a href="{{ route('website.dashboard') }}">Projects</a></li>
        
        @if(isset($project))
            <li><a href="{{ route('website.project.plans', $project->id) }}">Plans</a></li>
            <li><a href="{{ route('website.project.tasks', $project->id) }}">Tasks</a></li>
            <li><a href="{{ route('website.project.inspections', $project->id) }}">Inspections</a></li>
            <!-- ... अन्य links -->
        @endif
    </ul>
</nav>
```

---

## 🛣️ Routing Strategy

```php
// Main dashboard
Route::get('/website/dashboard', [HomeController::class, 'dashboard'])->name('website.dashboard');

// Project specific routes
Route::group(['prefix' => 'website/project/{project_id}'], function () {
    Route::get('/plans', [HomeController::class, 'plans'])->name('website.project.plans');
    Route::get('/tasks', [HomeController::class, 'tasks'])->name('website.project.tasks');
    Route::get('/inspections', [HomeController::class, 'inspections'])->name('website.project.inspections');
    // ... अन्य routes
});
```

---

## 📊 Data Flow

### **1. Dashboard Page**
```
User visits /website/dashboard
↓
Controller fetches all projects + stats from database
↓
View displays project cards with basic info
↓
User clicks on project → redirects to /website/project/{id}/plans
```

### **2. Project Pages**
```
User visits /website/project/1/plans
↓
Controller fetches project with related plans
↓
Header shows project details (name, dates, progress)
↓
Sidebar shows project-specific navigation
↓
Main content shows plans data
```

---

## 🔧 Implementation Steps

### **Phase 1: Database Setup**
1. Create migrations for all tables
2. Create models with relationships
3. Create seeders for sample data

### **Phase 2: Basic Structure**
1. Update controllers to use database
2. Create proper view layouts
3. Implement project header component

### **Phase 3: Features**
1. Add file upload functionality
2. Implement search and filters
3. Add user authentication
4. Create admin panel

### **Phase 4: Advanced Features**
1. Real-time notifications
2. Progress tracking
3. Report generation
4. Mobile responsiveness

---

## 💡 Key Benefits

1. **Scalable**: Easy to add new project types and features
2. **Maintainable**: Clear separation of concerns
3. **User-friendly**: Intuitive navigation and data presentation
4. **Flexible**: Can handle different types of construction projects

---

## 🚀 Next Steps

1. **Create migrations** for database tables
2. **Update models** with proper relationships  
3. **Modify controllers** to use real data
4. **Create project header** component
5. **Test the complete flow**

यह architecture आपकी पूरी website को handle कर सकता है और future में भी easily extend हो सकता है।