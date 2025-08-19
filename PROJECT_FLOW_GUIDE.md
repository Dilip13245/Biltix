# Biltix - Complete Project Flow Guide

## 🚀 **STEP-BY-STEP PROJECT FLOW**

### **PHASE 1: USER ONBOARDING**

#### **Step 1: App Launch & Language Selection**
**Figma Files:** `Splash-*.pdf`, `choose language.pdf`
- User opens app → 4 splash screens showing app features
- Language selection: English/Arabic (RTL support)
- App explains construction project management benefits

#### **Step 2: User Registration**
**Figma Files:** `register.pdf`, `register-1.pdf`, `register-2.pdf`
- **Screen 1:** Basic Info
  - Full Name, Email, Phone, Password
- **Screen 2:** Professional Details  
  - Company Name, Designation, Role Selection
  - Roles: Contractor, Consultant, Site Engineer, Project Manager, Stakeholder
  - Total Employee Count
- **Screen 3:** Optional Membership
  - Member Number, Member Name (for professional associations)

#### **Step 3: Login System**
**Figma Files:** `login.pdf`
- Email/Password authentication
- Remember me option
- Forgot password functionality

---

### **PHASE 2: DASHBOARD & PROJECT OVERVIEW**

#### **Step 4: Main Dashboard**
**Figma Files:** `home.pdf`, `home-2.pdf`
**Website:** `dashboard.blade.php`
- **Quick Stats Cards:**
  - Active Projects: 12
  - Pending Reviews: 8  
  - Inspections Due: 5
  - Completed This Month: 3
- **Project Cards Grid:** Visual project overview with progress bars
- **Filters:** All Status, Active, Completed, On Hold
- **Search:** Global search across projects

---

### **PHASE 3: PROJECT CREATION**

#### **Step 5: Create New Project (5-Step Process)**
**Figma Files:** `create project-1.pdf` to `create project-5.pdf`

**Step 5.1: Basic Information**
- Project Name, Description
- Type: Residential, Commercial, Industrial, Renovation
- Location, Start Date, End Date

**Step 5.2: Client & Financial Details**
- Client Name, Email, Phone, Address
- Budget, Contract Details

**Step 5.3: Team Assignment**
- Select Project Manager
- Assign Team Members with roles
- Set access permissions

**Step 5.4: Project Phases Setup**
- Define phases: Foundation, Structure, Roofing, Interior, Finishing
- Set phase timelines and dependencies
- Budget allocation per phase

**Step 5.5: Final Settings & Confirmation**
- Notification preferences
- Document sharing settings
- Project summary review

---

### **PHASE 4: PROJECT MANAGEMENT**

#### **Step 6: Project Dashboard**
**Figma Files:** `project progress-*.pdf`
**Website:** `project-progress.blade.php`
- **Overall Progress:** 67% completion with visual indicators
- **Active Workers:** 148 staff members
- **Days Remaining:** 45 days to completion
- **Phase Breakdown:** Foundation 100%, Structure 85%, etc.
- **Manpower Details:** Engineers: 3, Foremen: 2, Laborers: 25

#### **Step 7: Plans Management**
**Figma Files:** `plans.pdf`, `drawing markup*.pdf`
**Website:** `plans.blade.php`
- **Plan Upload:** Support for PDF, DWG, JPG, PNG
- **Plan Categories:** Ground Floor, Second Floor, Elevations, Sections
- **Version Control:** Rev. 3.2, Rev. 2.1 with file sizes
- **Drawing Markup Tool:**
  - Annotation tools for inspections, snags, tasks
  - Collaborative commenting system
  - Save/share marked-up plans

---

### **PHASE 5: TASK MANAGEMENT**

#### **Step 8: Task Creation & Management**
**Figma Files:** `tasks*.pdf`, `task created*.pdf`, `task details*.pdf`
**Website:** `tasks.blade.php`
- **Task Creation:**
  - Title: "Pour Concrete Slab", "Steel Reinforcement Inspection"
  - Priority: Low, Medium, High, Critical
  - Assignee selection, Due dates
- **Task Status:** Pending, In Progress, Completed
- **Task Views:** List view, Calendar view, Kanban board
- **Progress Tracking:** Percentage completion with photos

---

### **PHASE 6: INSPECTION SYSTEM**

#### **Step 9: Inspection Management**
**Figma Files:** `inspection*.pdf`, `new inspection*.pdf`, `inspection complete*.pdf`
**Website:** `inspections.blade.php`
- **Create Inspection:**
  - Template selection: Safety, Quality, Structural, Electrical
  - Inspector assignment, Schedule coordination
- **Inspection Process:**
  - Dynamic checklist with pass/fail options
  - Photo capture for each checkpoint
  - Real-time scoring and results
- **Inspection Results:**
  - Overall result: Pass/Fail/Conditional
  - Detailed report generation
  - Action items for failed items

---

### **PHASE 7: SNAG MANAGEMENT**

#### **Step 10: Snag Reporting & Resolution**
**Figma Files:** `snag*.pdf`, `new snag report*.pdf`, `all snags*.pdf`
**Website:** `snag-list.blade.php`
- **Snag Reporting:**
  - Title: "Electrical outlet not working in Room 205"
  - Location, Priority level, Photo evidence
- **Snag Tracking:**
  - Status: Open, Assigned, In Progress, Resolved, Closed
  - Assignment to responsible party
  - Before/after photos for resolution
- **Snag Categories:** Electrical, Plumbing, Structural, Finishing

---

### **PHASE 8: DAILY OPERATIONS**

#### **Step 11: Daily Logs & Manpower**
**Figma Files:** `daily tasks performed*.pdf`
**Website:** `daily-logs.blade.php`
- **Daily Statistics:**
  - Active Equipment: 24
  - Staff Present: 42
  - Tasks Completed: 18
- **Equipment Logs:**
  - Equipment ID: EXC-001
  - Type: Excavator, Operator: John Smith
  - Status: Active, Hours Used: 6.5, Location: Zone A
- **Staff Logs:** Engineers, Foremen, Laborers with hours worked
- **Task Logs:** Daily task completion tracking

#### **Step 12: Team Management**
**Figma Files:** `peoples.pdf`, `assign new people.pdf`
**Website:** `team-members.blade.php`
- **Team Directory:**
  - Total Members: 36
  - Site Engineers: 18, Contractors: 12, Consultants: 6
- **Member Profiles:**
  - Name, Role, Company, Status (Active/Away)
  - Contact information, Project assignments

---

### **PHASE 9: FILE & PHOTO MANAGEMENT**

#### **Step 13: Document Management**
**Figma Files:** `files.pdf`
**Website:** File management system
- **File Categories:** Documents, Drawings, Photos, Reports
- **File Operations:** Upload, Download, View, Share
- **Version Control:** Automatic versioning with history
- **Search:** Full-text search across all documents

#### **Step 14: Photo Gallery**
**Figma Files:** `photos.pdf`
**Website:** Photo gallery system
- **Photo Organization:** Date-wise, Phase-wise grouping
- **Photo Types:** Progress photos, Before/after comparisons
- **Metadata:** Location, timestamp, photographer
- **Bulk Operations:** Mass upload, tagging, sharing

---

### **PHASE 10: NOTIFICATIONS & COMMUNICATION**

#### **Step 15: Notification System**
**Figma Files:** `notifications.pdf`
- **Notification Types:**
  - Task assignments, Inspection due, Snag reports
  - Progress updates, System alerts
- **Multi-channel Delivery:** In-app, Email, SMS
- **Priority Levels:** Critical, High, Medium, Low
- **Action Buttons:** Direct action from notifications

---

### **PHASE 11: REPORTING & ANALYTICS**

#### **Step 16: Progress Reports**
**Figma Files:** `project progress*.pdf`, `foundation progress*.pdf`
- **Progress Visualization:** Gantt charts, Progress bars
- **Phase Reports:** Individual phase completion status
- **Resource Reports:** Manpower, Equipment utilization
- **Quality Reports:** Inspection results, Snag statistics
- **Client Reports:** Executive summaries, Photo documentation

---

## 🎯 **ROLE-BASED WORKFLOW**

### **Contractor Workflow:**
1. Create projects → Assign teams → Monitor progress → Generate reports

### **Site Engineer Workflow:**
1. Daily logs → Conduct inspections → Report snags → Update tasks

### **Project Manager Workflow:**
1. Track progress → Coordinate teams → Review reports → Client communication

### **Consultant Workflow:**
1. Review plans → Approve inspections → Markup drawings → Provide feedback

### **Stakeholder Workflow:**
1. View progress → Review reports → Monitor milestones → Receive updates

---

## 📱 **MOBILE APP vs WEB PLATFORM**

### **Mobile App Features:**
- Field data entry, Photo capture, GPS tracking
- Offline capability, Push notifications
- Drawing markup tools, Voice annotations

### **Web Platform Features:**
- Comprehensive reporting, Bulk operations
- Advanced analytics, Document management
- Admin controls, System settings

---

## 🔄 **DATA SYNCHRONIZATION**

### **Real-time Sync:**
- All data syncs between mobile and web instantly
- Conflict resolution for concurrent edits
- Offline data cached and synced when connected

### **Integration Points:**
- Single database for all platforms
- Unified user authentication
- Consistent role-based permissions

This flow ensures seamless project management from initial setup to project completion, with all stakeholders having appropriate access and functionality based on their roles.