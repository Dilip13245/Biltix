# Biltix API Setup Guide

## Overview

This guide will help you set up and configure the Biltix API environment for secure, maintainable, and expandable API development.

## 🚀 Quick Start

### 1. Environment Configuration

Copy the example environment file and configure your settings:

```bash
cp .env.example .env
```

### 2. Key Configuration Variables

Update your `.env` file with the following essential settings:

```env
# Application
APP_NAME="Biltix API"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# API Security
API_KEY=your-secure-api-key-here
APP_SECRET=your-32-character-secret-key-here
APP_IV=your-16-character-iv-here
ENCRYPTION_ENABLED=0

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biltix
DB_USERNAME=root
DB_PASSWORD=

# Rate Limiting
API_RATE_LIMIT=60
API_RATE_LIMIT_WINDOW=1

# CORS
CORS_ALLOWED_ORIGINS=*
CORS_ALLOWED_METHODS=GET,POST,PUT,DELETE,OPTIONS
CORS_ALLOWED_HEADERS=*
```

### 3. Generate Application Key

```bash
php artisan key:generate
```

### 4. Run Database Migrations

```bash
php artisan migrate
```

### 5. Seed Database

```bash
php artisan db:seed
```

## 🔧 Configuration Details

### API Security Configuration

The API uses multiple layers of security:

1. **API Key Authentication**: Required for all requests
2. **User Token Authentication**: Required for protected endpoints
3. **Role-based Permissions**: Granular access control
4. **Request/Response Encryption**: Optional encryption layer

### Middleware Stack

The API uses the following middleware in order:

1. `ApiVersioningMiddleware` - Handles API versioning
2. `SecurityHeadersMiddleware` - Adds security headers
3. `ApiLoggingMiddleware` - Logs API requests/responses
4. `ApiResponseMiddleware` - Formats API responses
5. `ThrottleRequests` - Rate limiting
6. `SubstituteBindings` - Route model binding

### Rate Limiting

Configure rate limits in `config/rate-limiting.php`:

- **API**: 60 requests per minute (default)
- **Auth**: 5 login attempts per minute
- **Upload**: 10 file uploads per minute
- **General**: 100 requests per minute

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── BaseApiController.php
│   │       ├── AuthController.php
│   │       ├── HealthController.php
│   │       └── DocumentationController.php
│   ├── Middleware/
│   │   ├── ApiResponseMiddleware.php
│   │   ├── ApiLoggingMiddleware.php
│   │   ├── ApiVersioningMiddleware.php
│   │   ├── SecurityHeadersMiddleware.php
│   │   ├── VerifyApiKey.php
│   │   ├── CheckUserToken.php
│   │   └── CheckPermission.php
│   └── Requests/
│       ├── BaseApiRequest.php
│       ├── Auth/
│       └── Project/
├── Services/
│   └── ApiService.php
└── Exceptions/
    └── ApiException.php

config/
├── api.php
├── rate-limiting.php
└── logging.php

routes/
└── api/
    ├── api.php
    └── v1.php
```

## 🔐 Security Features

### 1. Authentication Flow

```
Client Request → API Key Check → Token Validation → Permission Check → Controller
```

### 2. Encryption Support

Enable encryption by setting `ENCRYPTION_ENABLED=1` in your `.env` file.

### 3. Security Headers

The API automatically adds security headers:
- `X-Content-Type-Options: nosniff`
- `X-Frame-Options: DENY`
- `X-XSS-Protection: 1; mode=block`
- `Strict-Transport-Security` (HTTPS only)

### 4. Input Validation

All requests are validated using Laravel's validation system with custom error handling.

## 📊 Monitoring & Logging

### Health Endpoints

- `GET /api/v1/health` - Basic health check
- `GET /api/v1/health/status` - Detailed system status

### Logging Channels

- `api` - API request/response logs
- `security` - Security-related events
- `performance` - Performance monitoring

### Log Files

- `storage/logs/api.log` - API activity
- `storage/logs/security.log` - Security events
- `storage/logs/performance.log` - Performance metrics

## 🧪 Testing

### Run Tests

```bash
# Run all tests
php artisan test

# Run API tests only
php artisan test tests/Feature/ApiTest.php

# Run with coverage
php artisan test --coverage
```

### Test Endpoints

The test suite covers:
- Health check endpoints
- Authentication flow
- Rate limiting
- Security headers
- Error handling
- Validation

## 📚 API Documentation

### Access Documentation

Visit `/api/v1/docs` to get comprehensive API documentation including:
- Available endpoints
- Authentication requirements
- Request/response formats
- Error codes
- Rate limiting information

### Example Requests

#### Health Check
```bash
curl -X GET "http://localhost:8000/api/v1/health"
```

#### User Login
```bash
curl -X POST "http://localhost:8000/api/v1/auth/login" \
  -H "api-key: your-api-key" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

#### Get User Profile (Protected)
```bash
curl -X POST "http://localhost:8000/api/v1/auth/get_user_profile" \
  -H "api-key: your-api-key" \
  -H "token: user-token" \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1
  }'
```

## 🔄 API Versioning

The API supports versioning through:
- URL path: `/api/v1/endpoint`
- Accept header: `Accept: application/vnd.api+json;version=1`
- Custom header: `X-API-Version: v1`

## 🚀 Deployment

### Production Checklist

1. Set `APP_ENV=production`
2. Set `APP_DEBUG=false`
3. Configure secure API keys
4. Enable HTTPS
5. Set up proper CORS origins
6. Configure rate limiting
7. Set up monitoring and alerting
8. Enable encryption if required

### Environment Variables for Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Security
API_KEY=your-production-api-key
APP_SECRET=your-production-secret
ENCRYPTION_ENABLED=1

# CORS
CORS_ALLOWED_ORIGINS=https://your-frontend.com,https://your-app.com

# Rate Limiting
API_RATE_LIMIT=100
```

## 🛠️ Customization

### Adding New Endpoints

1. Create controller in `app/Http/Controllers/Api/`
2. Extend `BaseApiController`
3. Add routes in `routes/api/v1.php`
4. Add validation requests if needed
5. Update documentation

### Adding New Middleware

1. Create middleware in `app/Http/Middleware/`
2. Register in `app/Http/Kernel.php`
3. Apply to routes as needed

### Custom Validation

1. Extend `BaseApiRequest`
2. Define validation rules
3. Add custom error messages
4. Use in controllers

## 📞 Support

For issues and questions:
1. Check the health endpoints
2. Review API logs
3. Check the documentation at `/api/v1/docs`
4. Contact the development team

## 🔄 Updates and Maintenance

### Regular Maintenance Tasks

1. Monitor API logs for errors
2. Check rate limiting effectiveness
3. Review security headers
4. Update dependencies
5. Monitor performance metrics

### Backup and Recovery

1. Regular database backups
2. Configuration file backups
3. Log file rotation
4. Disaster recovery procedures

This setup provides a robust, secure, and scalable API environment that can grow with your application needs.
