# Biltix API Documentation

## Overview

The Biltix API is a comprehensive, secure, and scalable REST API built with Laravel for construction project management. It provides endpoints for managing projects, tasks, inspections, snags, and more.

## Features

- **Secure Authentication**: API key + token-based authentication
- **Role-based Access Control**: Granular permissions for different user roles
- **Request/Response Encryption**: Optional encryption for sensitive data
- **Rate Limiting**: Built-in rate limiting to prevent abuse
- **Comprehensive Logging**: Detailed API request/response logging
- **Version Management**: API versioning support
- **Health Monitoring**: System health and status endpoints
- **Security Headers**: Comprehensive security headers implementation

## Base URL

```
Production: https://your-domain.com/api
Development: http://localhost:8000/api
```

## Authentication

### API Key
All requests require an API key in the header:
```
api-key: your-api-key-here
```

### User Token (Protected Endpoints)
Protected endpoints require a user token:
```
token: user-token-here
```

## Response Format

### Success Response
```json
{
    "code": 200,
    "message": "Success",
    "data": {
        // Response data
    },
    "meta": {
        "timestamp": "2024-01-01T00:00:00.000Z",
        "version": "v1",
        "request_id": "uuid-here",
        "response_time_ms": 150.5
    }
}
```

### Error Response
```json
{
    "code": 400,
    "message": "Error message",
    "data": {},
    "errors": {
        // Validation errors (if applicable)
    }
}
```

## Status Codes

| Code | Description |
|------|-------------|
| 200  | Success |
| 201  | Created |
| 400  | Bad Request |
| 401  | Unauthorized |
| 403  | Forbidden |
| 404  | Not Found |
| 422  | Validation Error |
| 429  | Too Many Requests |
| 500  | Internal Server Error |

## Rate Limiting

- **Limit**: 60 requests per minute
- **Headers**: 
  - `X-RateLimit-Limit`: Request limit per window
  - `X-RateLimit-Remaining`: Remaining requests
  - `X-RateLimit-Reset`: Reset time

## User Roles & Permissions

### Contractor
- Full project management access
- Can create, edit, delete projects
- Full task management
- Complete inspection control
- Snag management and resolution

### Site Engineer
- Limited project access (view only)
- Task status updates
- Daily log creation
- File and photo uploads
- Snag reporting

### Consultant
- Review and approval access
- Inspection assistance
- Snag review and resolution
- Plan markup capabilities

### Project Manager
- Full project oversight
- Team management
- Task assignment and tracking
- Report generation

### Stakeholder
- Read-only access to all modules
- View project progress
- Access to reports

## API Endpoints

### Health & Status
- `GET /api/v1/health` - Health check
- `GET /api/v1/health/status` - Detailed system status

### Authentication
- `POST /api/v1/auth/signup` - User registration
- `POST /api/v1/auth/login` - User login
- `POST /api/v1/auth/logout` - User logout
- `POST /api/v1/auth/send_otp` - Send OTP
- `POST /api/v1/auth/verify_otp` - Verify OTP
- `POST /api/v1/auth/reset_password` - Reset password
- `POST /api/v1/auth/get_user_profile` - Get user profile (Protected)
- `POST /api/v1/auth/update_profile` - Update user profile (Protected)

### Projects
- `POST /api/v1/projects/create` - Create project (Protected)
- `POST /api/v1/projects/list` - List projects (Protected)
- `POST /api/v1/projects/details` - Get project details (Protected)
- `POST /api/v1/projects/update` - Update project (Protected)
- `POST /api/v1/projects/delete` - Delete project (Protected)
- `POST /api/v1/projects/dashboard_stats` - Get dashboard stats (Protected)

### Tasks
- `POST /api/v1/tasks/create` - Create task (Protected)
- `POST /api/v1/tasks/list` - List tasks (Protected)
- `POST /api/v1/tasks/details` - Get task details (Protected)
- `POST /api/v1/tasks/update` - Update task (Protected)
- `POST /api/v1/tasks/delete` - Delete task (Protected)
- `POST /api/v1/tasks/change_status` - Change task status (Protected)

### Inspections
- `POST /api/v1/inspections/create` - Create inspection (Protected)
- `POST /api/v1/inspections/list` - List inspections (Protected)
- `POST /api/v1/inspections/details` - Get inspection details (Protected)
- `POST /api/v1/inspections/start_inspection` - Start inspection (Protected)
- `POST /api/v1/inspections/complete` - Complete inspection (Protected)

### Snags
- `POST /api/v1/snags/create` - Create snag (Protected)
- `POST /api/v1/snags/list` - List snags (Protected)
- `POST /api/v1/snags/details` - Get snag details (Protected)
- `POST /api/v1/snags/update` - Update snag (Protected)
- `POST /api/v1/snags/resolve` - Resolve snag (Protected)

### Files & Photos
- `POST /api/v1/files/upload` - Upload file (Protected)
- `POST /api/v1/files/list` - List files (Protected)
- `POST /api/v1/photos/upload` - Upload photo (Protected)
- `POST /api/v1/photos/list` - List photos (Protected)

## Security Features

### Encryption
- Optional request/response encryption
- Configurable via environment variables
- AES-256-CBC encryption

### Security Headers
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY`
- `X-XSS-Protection: 1; mode=block`
- `Strict-Transport-Security` (HTTPS only)
- `Content-Security-Policy`

### Input Validation
- Comprehensive request validation
- Sanitization of user inputs
- SQL injection prevention
- XSS protection

## Error Handling

The API provides detailed error responses with:
- HTTP status codes
- Descriptive error messages
- Validation error details
- Request ID for tracking

## Logging

All API requests are logged with:
- Request details (method, URL, headers)
- Response status and timing
- User information
- Error details
- Performance metrics

## Development Setup

1. Clone the repository
2. Install dependencies: `composer install`
3. Copy environment file: `cp .env.example .env`
4. Generate application key: `php artisan key:generate`
5. Configure database and other settings in `.env`
6. Run migrations: `php artisan migrate`
7. Seed database: `php artisan db:seed`
8. Start server: `php artisan serve`

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific API tests:
```bash
php artisan test tests/Feature/ApiTest.php
```

## Monitoring

### Health Check
Monitor API health:
```bash
curl -X GET "https://your-domain.com/api/v1/health"
```

### System Status
Get detailed system status:
```bash
curl -X GET "https://your-domain.com/api/v1/health/status"
```

## Support

For API support and documentation:
- Check the health endpoints for system status
- Review logs in `storage/logs/api.log`
- Contact the development team for assistance

## Changelog

### Version 1.0.0
- Initial API release
- Complete CRUD operations for all modules
- Role-based access control
- Security middleware implementation
- Comprehensive logging and monitoring
