# 🔌 API Reference — CommDevSys v1

**Base URL:** `http://your-domain.com/api/v1`  
**Content-Type:** `application/json`  
**Authentication:** Bearer Token (Laravel Sanctum)  
**Rate Limit:** 60 requests per minute

---

## Table of Contents
- [Authentication](#authentication)
- [Streets](#streets)
- [Projects](#projects)
- [Tasks](#tasks)
- [Error Responses](#error-responses)

---

## Authentication

All endpoints except `register` and `login` require an `Authorization: Bearer <token>` header.

---

### POST `/register`
Register a new user account.

**Request Body:**
```json
{
  "firstname": "Jane",
  "lastname": "Doe",
  "email": "jane@example.com",
  "password": "secret12345",
  "password_confirmation": "secret12345",
  "phone": "+2348012345678",
  "street_id": 1
}
```

**Validation Rules:**
| Field | Rules |
|---|---|
| `firstname` | required, string, max:255 |
| `lastname` | required, string, max:255 |
| `email` | required, email, unique |
| `password` | required, min:8, confirmed |
| `phone` | nullable, string, max:20 |
| `street_id` | nullable, exists:streets,id |

**Response `201 Created`:**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 42,
    "firstname": "Jane",
    "lastname": "Doe",
    "email": "jane@example.com",
    "role": "user"
  },
  "token": "1|abc123..."
}
```

---

### POST `/login`
Authenticate a user and receive an access token.

**Request Body:**
```json
{
  "email": "jane@example.com",
  "password": "secret12345"
}
```

**Response `200 OK`:**
```json
{
  "message": "Login successful",
  "user": { "id": 42, "firstname": "Jane", "lastname": "Doe", "email": "jane@example.com", "role": "user" },
  "token": "2|xyz456..."
}
```

> **Note:** All existing tokens are revoked on each new login.

---

### POST `/logout` 🔒
Revoke the current access token.

**Response `200 OK`:**
```json
{ "message": "Logged out successfully" }
```

---

### GET `/user` 🔒
Get the currently authenticated user's profile.

**Response `200 OK`:**
```json
{
  "user": {
    "id": 42,
    "firstname": "Jane",
    "lastname": "Doe",
    "full_name": "Jane Doe",
    "email": "jane@example.com",
    "phone": "+2348012345678",
    "role": "user",
    "street_id": 1,
    "street": { "id": 1, "name": "Adeola Street", "zone": "Zone A" },
    "photo_url": "http://domain.com/uploads/profile_abc.jpg",
    "id_number": "COMM-63F4A12B",
    "created_at": "2025-08-15T10:30:00.000Z",
    "updated_at": "2026-01-05T09:00:00.000Z"
  }
}
```

---

## Streets

### GET `/streets` 🔒
List all streets with optional filtering and pagination.

**Query Parameters:**
| Parameter | Type | Description |
|---|---|---|
| `zone` | string | Filter by zone name |
| `search` | string | Search streets by name |
| `with_counts` | boolean | Include `users_count` and `projects_count` |
| `per_page` | integer | Results per page (default: 15) |
| `page` | integer | Page number |

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Adeola Street",
      "zone": "Zone A",
      "description": "Main residential street in Zone A",
      "full_name": "Adeola Street (Zone A)",
      "created_at": "2025-07-06T16:50:00.000Z"
    }
  ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": { "current_page": 1, "per_page": 15, "total": 50 }
}
```

---

### POST `/streets` 🔒
Create a new street.

**Request Body:**
```json
{
  "name": "New Palm Avenue",
  "zone": "Zone B",
  "description": "Newly developed residential avenue"
}
```

**Validation Rules:**
| Field | Rules |
|---|---|
| `name` | required, unique:streets |
| `zone` | required, string |
| `description` | nullable, string |

**Response `201 Created`:** Returns the created street resource.

---

### GET `/streets/{id}` 🔒
Get a single street. Includes `users_count` and `projects_count`.

**Response `200 OK`:**
```json
{
  "data": {
    "id": 1,
    "name": "Adeola Street",
    "zone": "Zone A",
    "description": "Main residential street",
    "full_name": "Adeola Street (Zone A)",
    "users_count": 25,
    "projects_count": 3,
    "created_at": "2025-07-06T16:50:00.000Z"
  }
}
```

---

### PUT `/streets/{id}` 🔒
Update a street.

**Request Body:** Same as POST `/streets` (name uniqueness ignores current record).

**Response `200 OK`:** Returns updated street resource.

---

### DELETE `/streets/{id}` 🔒
Soft-delete a street.

**Response `200 OK`:**
```json
{ "message": "Street deleted successfully" }
```

---

## Projects

### GET `/projects` 🔒
List all projects with filtering, searching, and pagination.

**Query Parameters:**
| Parameter | Type | Description |
|---|---|---|
| `status` | string | Filter: `pending`, `in_progress`, `completed`, `cancelled` |
| `street_id` | integer | Filter by street |
| `search` | string | Search by project title |
| `with` | string | Comma-separated relations to load: `users`, `street`, `tasks` |
| `with_counts` | boolean | Include `tasks_count` |
| `per_page` | integer | Default 15 |
| `page` | integer | Page number |

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 7,
      "title": "Zone A Road Rehabilitation",
      "description": "Full resurfacing of all roads in Zone A",
      "status": "in_progress",
      "start_date": "2025-09-01",
      "end_date": "2025-12-31",
      "budget": 5000000.00,
      "actual_cost": 2200000.00,
      "street_id": 1,
      "tasks_count": 12,
      "created_at": "2025-09-01T08:00:00.000Z"
    }
  ],
  "links": {},
  "meta": { "current_page": 1, "per_page": 15, "total": 42 }
}
```

---

### POST `/projects` 🔒
Create a new project.

**Request Body:**
```json
{
  "title": "Zone B Water Supply Project",
  "description": "Install borehole and distribution pipes",
  "start_date": "2026-02-01",
  "end_date": "2026-06-30",
  "status": "pending",
  "street_id": 2,
  "user_ids": [1, 5, 12]
}
```

**Validation Rules:**
| Field | Rules |
|---|---|
| `title` | required, string, max:255 |
| `description` | nullable, string |
| `start_date` | nullable, date |
| `end_date` | nullable, date, after_or_equal:start_date |
| `status` | required, string |
| `street_id` | nullable, exists:streets,id |
| `user_ids` | array |
| `user_ids.*` | exists:users,id |

**Response `201 Created`:** Returns the created project with loaded `users` and `street`.

---

### GET `/projects/{id}` 🔒
Get a single project with users, street, and tasks.

---

### PUT `/projects/{id}` 🔒
Update a project. Same request body as POST.

---

### DELETE `/projects/{id}` 🔒
Soft-delete a project.

**Response `200 OK`:**
```json
{ "message": "Project deleted successfully" }
```

---

## Tasks

Tasks are nested under projects: `/projects/{project_id}/tasks`

### GET `/projects/{project_id}/tasks` 🔒
List tasks for a project.

**Query Parameters:**
| Parameter | Type | Description |
|---|---|---|
| `status` | string | Filter by task status |
| `assigned_to` | integer | Filter by assigned user ID |
| `search` | string | Search by task title |
| `with` | string | Relations: `project`, `assignee` |
| `per_page` | integer | Default 15 |

**Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 3,
      "project_id": 7,
      "title": "Survey road damage extent",
      "description": "Use GPS and photos to map all damage points",
      "status": "completed",
      "due_date": "2025-09-15",
      "assigned_to": 5,
      "created_at": "2025-09-02T09:00:00.000Z"
    }
  ],
  "meta": {}
}
```

---

### POST `/projects/{project_id}/tasks` 🔒
Create a task under a project.

**Request Body:**
```json
{
  "title": "Procure construction materials",
  "description": "Order gravel, sand, and bitumen from suppliers",
  "status": "pending",
  "due_date": "2025-10-01",
  "assigned_to": 5
}
```

**Validation Rules:**
| Field | Rules |
|---|---|
| `title` | required, string, max:255 |
| `description` | nullable, string |
| `status` | required, string |
| `due_date` | nullable, date |
| `assigned_to` | nullable, exists:users,id |

---

### GET `/projects/{project_id}/tasks/{task_id}` 🔒
Get a single task. Returns 404 if task does not belong to the project.

---

### PUT `/projects/{project_id}/tasks/{task_id}` 🔒
Update a task. Same request body as POST.

---

### DELETE `/projects/{project_id}/tasks/{task_id}` 🔒
Soft-delete a task.

**Response `200 OK`:**
```json
{ "message": "Task deleted successfully" }
```

---

## Error Responses

### Validation Error `422`
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthenticated `401`
```json
{ "message": "Unauthenticated." }
```

### Not Found `404`
```json
{ "message": "Task not found in this project" }
```

### Server Error `500`
```json
{
  "message": "Registration failed",
  "error": "An error occurred during registration"
}
```

### Rate Limit Exceeded `429`
```json
{ "message": "Too Many Attempts." }
```

---

## Notes

- All list endpoints return paginated responses following [Laravel's pagination format](https://laravel.com/docs/pagination).
- Soft-deleted records are excluded from all queries by default.
- The API is rate-limited to **60 requests per minute** per IP.
- All timestamps are in **ISO 8601** format (UTC).
- The `role` field on User is one of: `superadmin`, `admin`, `project_manager`, `user`, `author`.

