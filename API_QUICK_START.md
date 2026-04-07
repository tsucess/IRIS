# 🚀 API Quick Start Guide
## Community Development System API v1

---

## ⚡ **Quick Setup**

### 1. Fix Autoloader (REQUIRED FIRST)
```bash
composer dump-autoload
```

### 2. Run Tests
```bash
# Run all API tests
php artisan test --filter=Api

# Run specific test suites
php artisan test tests/Feature/Api/AuthenticationTest.php
php artisan test tests/Feature/Api/StreetApiTest.php
php artisan test tests/Feature/Api/ProjectApiTest.php
```

### 3. Start Server
```bash
php artisan serve
```

---

## 📝 **API Testing with cURL**

### Authentication

#### Register a New User
```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "firstname": "John",
    "lastname": "Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

**Save the token from the response!**

#### Get Current User
```bash
curl -X GET http://localhost:8000/api/v1/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Logout
```bash
curl -X POST http://localhost:8000/api/v1/logout \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

### Streets API

#### List All Streets
```bash
curl -X GET "http://localhost:8000/api/v1/streets?with_counts=1" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Create Street
```bash
curl -X POST http://localhost:8000/api/v1/streets \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Main Street",
    "zone": "Zone A",
    "description": "Main street in Zone A"
  }'
```

#### Get Street Details
```bash
curl -X GET http://localhost:8000/api/v1/streets/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Update Street
```bash
curl -X PUT http://localhost:8000/api/v1/streets/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Street Name",
    "zone": "Zone B",
    "description": "Updated description"
  }'
```

#### Delete Street
```bash
curl -X DELETE http://localhost:8000/api/v1/streets/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

### Projects API

#### List All Projects
```bash
curl -X GET "http://localhost:8000/api/v1/projects?status=pending&with=users,street" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Create Project
```bash
curl -X POST http://localhost:8000/api/v1/projects \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Road Construction Project",
    "description": "Building new road",
    "status": "pending",
    "start_date": "2026-02-01",
    "end_date": "2026-06-30",
    "street_id": 1,
    "user_ids": [1, 2, 3]
  }'
```

#### Get Project Details
```bash
curl -X GET http://localhost:8000/api/v1/projects/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Update Project
```bash
curl -X PUT http://localhost:8000/api/v1/projects/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Project Title",
    "status": "in_progress",
    "user_ids": [1, 2]
  }'
```

---

### Tasks API

#### List Project Tasks
```bash
curl -X GET "http://localhost:8000/api/v1/projects/1/tasks?status=pending" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Create Task
```bash
curl -X POST http://localhost:8000/api/v1/projects/1/tasks \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Survey the area",
    "description": "Complete initial survey",
    "status": "pending",
    "due_date": "2026-02-15",
    "assigned_to": 1
  }'
```

---

## 🔍 **Query Parameters**

### Pagination
```
?per_page=20          # Items per page (default: 15)
?page=2               # Page number
```

### Filtering
```
?zone=Zone A          # Filter streets by zone
?status=pending       # Filter projects/tasks by status
?street_id=1          # Filter projects by street
?assigned_to=1        # Filter tasks by assigned user
```

### Search
```
?search=Main          # Search by name/title
```

### Relationships
```
?with=users,street    # Include relationships
?with_counts=1        # Include counts
```

---

## 📊 **Response Format**

### Success Response (Single Resource)
```json
{
  "data": {
    "id": 1,
    "name": "Main Street",
    "zone": "Zone A",
    "created_at": "2026-01-05T10:00:00.000000Z"
  }
}
```

### Success Response (Collection)
```json
{
  "data": [...],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "total": 50,
    "per_page": 15
  }
}
```

### Error Response
```json
{
  "message": "Validation error",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

---

## 🛠️ **Postman Collection**

You can import these endpoints into Postman:
1. Create a new collection
2. Add environment variable: `token`
3. Set Authorization header: `Bearer {{token}}`
4. Import the endpoints above

---

## 📚 **Additional Resources**

- Full API documentation: `API_IMPLEMENTATION_STATUS.md`
- Progress report: `MEDIUM_PRIORITY_PROGRESS.md`
- Overall improvements: `IMPROVEMENTS_JANUARY_2026.md`

---

**Base URL**: `http://localhost:8000/api/v1`  
**Authentication**: Bearer Token (Sanctum)  
**Rate Limit**: 60 requests per minute

