# Gantt Chart API Documentation

This documentation provides details for integrating the Gantt Chart functionality into the Mobile App.
The backend handles all logic for progress calculation and status determination. The app simply needs to consume these APIs.

## Base URL
`https://your-domain.com/api/v1/gantt`

## Headers
All requests require the following headers:
- `Authorization`: `Bearer <token>`
- `Accept`: `application/json`

---

## 1. List Activities
Fetch all Gantt activities for a specific project. The backend automatically recalculates the progress for every activity based on the current date before returning the list.

### Request
**Endpoint:** `POST /list`

**Body:**
```json
{
    "project_id": 414
}
```

### Response
```json
{
    "status": true,
    "message": "Activities fetched successfully",
    "data": [
        {
            "id": 101,
            "project_id": 414,
            "name": "Foundation Work",
            "description": "Excavation and concrete work",
            "start_date": "2026-01-10",
            "end_date": "2026-02-10",
            "status": "in_progress",
            "progress": 67,
            "workers_count": 15,
            "equipment_count": 3
        },
        {
            "id": 102,
            "project_id": 414,
            "name": "Site Survey",
            "status": "complete",
            "progress": 100,
            "start_date": "2026-01-01",
            "end_date": "2026-01-05"
        }
    ],
    "code": 200
}
```

---

## 2. Create Activity
Add a new activity to the Gantt chart.
**Note**: You do not need to send `progress`. The backend calculates it automatically based on dates and status.

### Request
**Endpoint:** `POST /create`

**Body:**
```json
{
    "project_id": 414,
    "name": "Wall Construction",
    "description": "Building main walls",
    "start_date": "2026-02-01",
    "end_date": "2026-02-15",
    "status": "todo", 
    "workers_count": 10,  // Optional
    "equipment_count": 2  // Optional
}
```

**Supported Statuses:**
- `todo`
- `in_progress`
- `complete` (Forces progress to 100%)
- `approve` (Forces progress to 100%)

### Response
```json
{
    "status": true,
    "message": "Activity created successfully",
    "data": {
        "id": 103,
        "name": "Wall Construction",
        "progress": 0,
        "status": "todo",
        ...
    },
    "code": 200
}
```

---

## 3. Update Activity
Update an existing activity.

### Request
**Endpoint:** `POST /update`

**Body:**
```json
{
    "activity_id": 103,
    "name": "Wall Construction Updated",
    "start_date": "2026-02-01",
    "end_date": "2026-02-20",
    "status": "in_progress",
    "workers_count": 12
}
```

### Response
```json
{
    "status": true,
    "message": "Activity updated successfully",
    "data": {
        "id": 103,
        "name": "Wall Construction Updated",
        "status": "in_progress",
        "progress": 5, // Auto-recalculated based on new dates
        ...
    },
    "code": 200
}
```

---

## 4. Delete Activity
Remove an activity permanently.

### Request
**Endpoint:** `POST /delete`

**Body:**
```json
{
    "activity_id": 103
}
```

### Response
```json
{
    "status": true,
    "message": "Activity deleted successfully",
    "code": 200
}
```

---

## App Implementation Logic

### Color Mapping
Use the `status` and `end_date` from the API response to determine colors.

| Condition | Visual Style |
| :--- | :--- |
| **Status = complete / approve** | **Green Bar** (100% width) |
| **Status = todo** | **Gray Bar** (100% width) |
| **Status = in_progress** | **Mixed Bar**: Blue Background + Orange Fill (Width = `progress`%) |
| **Status != complete AND Today > End Date** | **Red Bar** (Delayed) |

### Read-Only Logic
When a user taps an activity:
1. Check `if (Today > activity.end_date)`.
2. If **True**: Show **View Details** popup (No Edit).
3. If **False**: Show **Edit Form** (Allow Update).
