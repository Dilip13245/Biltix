# Biltix Mobile App - Figma Analysis & Database Design

## 📱 App Flow Analysis

### 1. AUTHENTICATION FLOW
**Files Analyzed:** `Splash-*.pdf`, `login.pdf`, `register*.pdf`, `choose language.pdf`

#### Splash Screens (Onboarding)
- Multi-step introduction screens
- Language selection (English/Arabic)
- App feature highlights

#### Registration Process
- **Step 1:** Basic Info
  - Full Name
  - Email
  - Phone Number
  - Password
- **Step 2:** Company Details
  - Company Name
  - Designation/Role Selection (Contractor, Consultant, Site Engineer, Project Manager, Stakeholder)
  - Total Employee Count
- **Step 3:** Optional Member Details
  - Member Number
  - Member Name

#### Login
- Email/Password authentication
- Remember me option
- Forgot password functionality

### 2. DASHBOARD & HOME
**Files Analyzed:** `home.pdf`, `home-2.pdf`

#### Main Dashboard Features
- Project overview cards with status badges
- Quick stats (Active Projects, Pending Tasks, etc.)
- Recent activity feed
- Quick action buttons
- Project status filters (Ongoing, Completed, On Hold)

### 3. PROJECT MANAGEMENT
**Files Analyzed:** `create project-*.pdf`, `edit project-*.pdf`

#### Project Creation (Multi-Step)
- **Step 1:** Basic Project Info
  - Project Name
  - Project Type (Residential, Commercial, Industrial)
  - Location
  - Start/End Dates
- **Step 2:** Client Details
  - Client Name
  - Contact Information
  - Budget Information
- **Step 3:** Team Assignment
  - Project Manager Selection
  - Team Member Assignment
- **Step 4:** Project Settings
  - Permissions & Access Levels
  - Notification Settings
- **Step 5:** Confirmation & Summary

#### Project Editing
- All creation fields editable
- Status management
- Progress tracking
- Team reassignment

### 4. PLANS MANAGEMENT
**Files Analyzed:** `plans.pdf`, `drawing markup*.pdf`

#### Plans Features
- Plan listing with thumbnails
- Upload new plans
- View/Download plans
- **Drawing Markup Tool:**
  - Annotation tools
  - Comment system
  - Markup for different purposes:
    - Inspection markups
    - Snag markups
    - Task markups
  - Save/Share markups

### 5. INSPECTION SYSTEM
**Files Analyzed:** `inspection*.pdf`, `new inspection*.pdf`, `inspection complete*.pdf`

#### Inspection Management
- **Create New Inspection:**
  - Inspection Type Selection
  - Area/Location Selection
  - Checklist Template Selection
  - Inspector Assignment
- **Inspection Process:**
  - Dynamic checklist with pass/fail options
  - Photo capture for each item
  - Comments/Notes for each checkpoint
  - Overall inspection status
- **Inspection Completion:**
  - Summary report generation
  - Status update (Passed/Failed)
  - Action items creation
  - Notification to stakeholders

### 6. TASK MANAGEMENT
**Files Analyzed:** `tasks*.pdf`, `all tasks*.pdf`, `task details*.pdf`, `task created*.pdf`

#### Task Features
- **Task Creation:**
  - Task Title & Description
  - Priority Level (Low, Medium, High, Critical)
  - Assignee Selection
  - Due Date
  - Task Category
  - Attachments
- **Task Management:**
  - Status tracking (Pending, In Progress, Completed)
  - Progress percentage
  - Time tracking
  - Comment system
  - File attachments
- **Task Views:**
  - List view with filters
  - Calendar view
  - Kanban board view

### 7. SNAG MANAGEMENT
**Files Analyzed:** `snag*.pdf`, `all snags*.pdf`, `new snag report*.pdf`

#### Snag System
- **Snag Reporting:**
  - Title & Description
  - Location/Area
  - Priority Level
  - Photo Evidence
  - Category Selection
- **Snag Tracking:**
  - Status (Open, In Progress, Resolved, Closed)
  - Assignment to responsible party
  - Due date for resolution
  - Progress updates
- **Snag Resolution:**
  - Resolution notes
  - Before/After photos
  - Verification process
  - Sign-off system

### 8. PROGRESS TRACKING
**Files Analyzed:** `project progress*.pdf`, `foundation progress*.pdf`, `timeline.pdf`

#### Progress Features
- **Overall Project Progress:**
  - Percentage completion
  - Phase-wise breakdown
  - Timeline visualization
  - Milestone tracking
- **Phase Management:**
  - Phase creation (`new phase.pdf`, `phase created.pdf`)
  - Phase progress tracking
  - Dependencies management
- **Visual Progress:**
  - Gantt chart view
  - Progress photos
  - Before/After comparisons

### 9. DAILY LOGS & MANPOWER
**Files Analyzed:** `daily tasks performed*.pdf`

#### Daily Logging System
- **Personnel Tracking:**
  - Engineers count & activities
  - Foremen count & activities  
  - Laborers count & activities
- **Work Performed:**
  - Task descriptions
  - Hours worked
  - Materials used
  - Equipment utilized
- **Daily Reports:**
  - Weather conditions
  - Issues encountered
  - Progress made
  - Photos of work

### 10. FILE MANAGEMENT
**Files Analyzed:** `files.pdf`

#### File System
- **File Categories:**
  - Documents (PDF, DOC, XLS)
  - Drawings (DWG, PDF)
  - Photos (JPG, PNG)
  - Reports
- **File Operations:**
  - Upload/Download
  - View/Preview
  - Share/Send
  - Version control
  - Search functionality

### 11. PHOTO GALLERY
**Files Analyzed:** `photos.pdf`

#### Gallery Features
- **Photo Organization:**
  - Date-wise grouping
  - Category-wise sorting
  - Project phase tagging
- **Photo Management:**
  - Bulk upload
  - Caption/Description
  - Location tagging
  - Before/After comparisons

### 12. TEAM MANAGEMENT
**Files Analyzed:** `peoples.pdf`, `assign new people.pdf`, `assign complete.pdf`

#### People Management
- **Team Directory:**
  - Contact information
  - Role-based listing
  - Availability status
- **Assignment System:**
  - Task assignment
  - Project assignment
  - Role-based permissions
  - Notification system

### 13. NOTIFICATIONS
**Files Analyzed:** `notifications.pdf`

#### Notification System
- **Notification Types:**
  - Task assignments
  - Inspection due
  - Snag reports
  - Progress updates
  - System alerts
- **Notification Management:**
  - Read/Unread status
  - Priority levels
  - Action buttons
  - Archive functionality

### 14. PROFILE & SETTINGS
**Files Analyzed:** `profile.pdf`, `help & support.pdf`

#### User Profile
- **Profile Information:**
  - Personal details
  - Company information
  - Role & permissions
  - Contact details
- **Settings:**
  - Language preferences
  - Notification settings
  - Privacy settings
  - Help & Support access

## 🎯 KEY INSIGHTS FOR DATABASE DESIGN

### Core Entities Identified:
1. **Users** (with enhanced company & role info)
2. **Projects** (with phases and detailed tracking)
3. **Tasks** (with priority, status, assignments)
4. **Inspections** (with checklists and results)
5. **Snags** (with resolution tracking)
6. **Plans** (with markup capabilities)
7. **Files** (with categorization)
8. **Photos** (with metadata)
9. **Daily Logs** (with manpower tracking)
10. **Notifications** (with types and status)
11. **Project Phases** (with progress tracking)
12. **Comments/Notes** (across multiple entities)
13. **Markups** (for plan annotations)

### Role-Based Access Requirements:
- **Contractor:** Full project management, team assignment
- **Consultant:** Review and approval workflows
- **Site Engineer:** Field data entry, inspections
- **Project Manager:** Overall project oversight
- **Stakeholder:** Read-only access to reports

### Mobile-First Features:
- Offline capability for field work
- Photo capture and upload
- GPS location tagging
- Push notifications
- File synchronization
- Drawing markup tools

This analysis will guide our database design to ensure all features are properly supported with optimal data relationships and performance.