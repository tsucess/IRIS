# 🏗️ System Architecture — CommDevSys

This document describes the technical architecture, design decisions, and structural patterns used in the Community Development System.

---

## Table of Contents
- [Overview](#overview)
- [Directory Structure](#directory-structure)
- [Request Lifecycle](#request-lifecycle)
- [Middleware Stack](#middleware-stack)
- [Authentication Architecture](#authentication-architecture)
- [Models & Relationships](#models--relationships)
- [API Architecture](#api-architecture)
- [Frontend Architecture](#frontend-architecture)
- [Caching Strategy](#caching-strategy)
- [Security Architecture](#security-architecture)
- [Role-Based Access Control](#role-based-access-control)
- [Performance Design](#performance-design)
- [Key Design Decisions](#key-design-decisions)

---

## Overview

CommDevSys follows the **Model-View-Controller (MVC)** pattern provided by Laravel 12. The system is divided into two primary interfaces:

1. **Web Interface** — Server-rendered HTML via Blade templates, styled with Tailwind CSS, enhanced with Alpine.js interactivity.
2. **REST API** — JSON API under `/api/v1`, authenticated with Laravel Sanctum bearer tokens.

Both interfaces share the same **Models** and **business logic layer**, ensuring a single source of truth for data.

```
┌─────────────────────────────────────────┐
│              CommDevSys                 │
│                                         │
│  ┌───────────────┐  ┌─────────────────┐ │
│  │  Web Browser  │  │  API Client     │ │
│  │  (Blade/HTML) │  │  (JSON REST)    │ │
│  └───────┬───────┘  └────────┬────────┘ │
│          │                   │           │
│  routes/web.php        routes/api.php   │
│          │                   │           │
│  ┌───────▼───────────────────▼────────┐ │
│  │         Middleware Stack            │ │
│  │  [Security → Auth → Throttle]      │ │
│  └───────────────────┬────────────────┘ │
│                       │                  │
│  ┌────────────────────▼───────────────┐ │
│  │     Controllers (HTTP Layer)        │ │
│  │  Admin/ | Api/ | Auth/ | Web       │ │
│  └────────────────────┬───────────────┘ │
│                       │                  │
│  ┌────────────────────▼───────────────┐ │
│  │    Models (Eloquent ORM)            │ │
│  │  User | Street | Project | Task     │ │
│  │  ResidentExtended                   │ │
│  └────────────────────┬───────────────┘ │
│                       │                  │
│  ┌────────────────────▼───────────────┐ │
│  │    Database (SQLite / MySQL)        │ │
│  └────────────────────────────────────┘ │
└─────────────────────────────────────────┘
```

---

## Directory Structure

```
app/
├── Enums/
│   └── UserRole.php              # Backed string enum for roles
├── Exports/
│   └── UsersExport.php           # Excel export (Maatwebsite)
├── Http/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── DashboardController.php       # Analytics dashboard
│   │   │   ├── UserManagementController.php  # Admin CRUD + PDF + Excel
│   │   │   └── UserSearchController.php      # Resident search
│   │   ├── Api/
│   │   │   ├── AuthController.php            # Token auth (Sanctum)
│   │   │   ├── ProjectController.php         # API project CRUD
│   │   │   ├── StreetController.php          # API street CRUD
│   │   │   └── TaskController.php            # API task CRUD
│   │   ├── Auth/                             # Breeze auth handlers
│   │   ├── ProfileController.php             # User profile + ID card
│   │   ├── ProjectController.php             # Web project CRUD
│   │   ├── ResidentExtendedController.php    # Extended profile
│   │   ├── StreetController.php              # Web street CRUD
│   │   └── TaskController.php               # Web task CRUD
│   ├── Middleware/
│   │   ├── AdminOnly.php          # Restricts to admin/superadmin
│   │   ├── SanitizeInput.php      # Trims and nullifies empty strings
│   │   ├── SecurityHeaders.php    # HTTP security headers
│   │   └── ThrottleRequests.php   # Rate limiting
│   ├── Requests/                  # Form Request validation
│   └── Resources/                 # API JSON transformation
│       ├── UserResource.php
│       ├── StreetResource.php
│       ├── ProjectResource.php
│       └── TaskResource.php
├── Models/
│   ├── User.php                  # Auth + profile + relationships
│   ├── Street.php                # Geographic zones
│   ├── Project.php               # Community projects
│   ├── Task.php                  # Project tasks
│   └── ResidentExtended.php      # 35+ demographic fields
├── Policies/                     # Laravel authorization policies
└── Providers/
```

---

## Request Lifecycle

### Web Request
```
1. HTTP Request arrives at public/index.php
2. Laravel bootstraps (bootstrap/app.php)
3. Route matched in routes/web.php
4. Middleware executed in order:
   a. SecurityHeaders — adds HTTP security response headers
   b. SanitizeInput  — trims strings, converts empty to null
   c. auth           — verifies session authentication
   d. verified       — checks email_verified_at
   e. admin          — checks role is admin/superadmin
   f. throttle       — enforces rate limits
5. Controller method invoked
6. Eloquent models query the database
7. Blade view renders HTML response
```

### API Request
```
1. HTTP Request arrives at public/index.php
2. Laravel bootstraps
3. Route matched in routes/api.php (prefix: /api)
4. Middleware:
   a. ThrottleRequests — 60 req/min
   b. auth:sanctum    — validates Bearer token
5. Controller method invoked
6. Eloquent models + JSON Resource transforms data
7. JSON response returned
```

---

## Middleware Stack

| Middleware | Trigger | Purpose |
|---|---|---|
| `SecurityHeaders` | All web responses | Injects HTTP security headers |
| `SanitizeInput` | All web requests | Trims whitespace, converts empty strings to null |
| `ThrottleRequests` | API (60/min), Admin (100/min), Exports (10/min) | Rate limiting |
| `AdminOnly` | Admin routes (`/admin/*`, `/streets`) | Restricts to admin/superadmin roles |
| `auth` (session) | Protected web routes | Session authentication |
| `auth:sanctum` | Protected API routes | Bearer token authentication |
| `verified` | Dashboard, projects | Enforces email verification |

**Security Headers Applied:**
```
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Strict-Transport-Security: max-age=31536000 (production only)
Content-Security-Policy: default-src 'self'; ...
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

---

## Authentication Architecture

### Web Authentication (Laravel Breeze)
- Session-based authentication using Laravel's built-in session driver
- Registration, login, password reset, email verification flows
- Custom email verification using **6-character alphanumeric codes** (with expiry)
- Password hashing via **bcrypt**
- `remember_token` support for "remember me" functionality

### API Authentication (Laravel Sanctum)
- **Bearer token** authentication (`Authorization: Bearer <token>`)
- Tokens stored hashed in `personal_access_tokens` table
- Previous tokens **revoked on each new login** (security measure)
- Tokens have no built-in expiry (can be configured per environment)
- Token creation: `$user->createToken('auth-token')->plainTextToken`

---

## Models & Relationships

```
User
├── belongsTo(Street)
├── belongsToMany(Project)       via project_user
├── belongsToMany(Task)          via task_user
└── hasOne(ResidentExtended)

Street
├── hasMany(User)
└── hasMany(Project)

Project
├── belongsTo(Street)
├── belongsToMany(User)          via project_user
└── hasMany(Task)

Task
├── belongsTo(Project)
├── belongsTo(User, 'assigned_to')   [single assignee]
└── belongsToMany(User)              via task_user [multi-assignee]

ResidentExtended
└── belongsTo(User)
```

**Soft Deletes:** Applied to `User`, `Street`, `Project`, and `Task`.  
All queries automatically scope to non-deleted records unless `withTrashed()` is used.

---

## API Architecture

The API is designed following RESTful conventions with versioned routing:

```
/api/v1/
├── register         (POST)           — Public
├── login            (POST)           — Public
├── logout           (POST)           — Protected
├── user             (GET)            — Protected
├── streets          (GET/POST)       — Protected
├── streets/{id}     (GET/PUT/DELETE) — Protected
├── projects         (GET/POST)       — Protected
├── projects/{id}    (GET/PUT/DELETE) — Protected
└── projects/{id}/tasks               — Nested resource (GET/POST/GET/{tid}/PUT/{tid}/DELETE/{tid})
```

**JSON Resources** transform Eloquent models for consistent API output:
- `UserResource` — exposes `full_name`, `photo_url` computed attributes
- `StreetResource` — includes `full_name` (name + zone)
- `ProjectResource` — includes nested street/users/tasks when loaded
- `TaskResource` — includes project and assignee when loaded

---

## Frontend Architecture

```
resources/
├── css/
│   └── app.css           # Tailwind CSS directives
├── js/
│   ├── app.js            # Alpine.js bootstrap
│   └── particles.js      # Three.js particle background
└── views/
    ├── layouts/
    │   ├── app.blade.php      # Authenticated layout (nav + content)
    │   └── guest.blade.php    # Guest layout (login/register)
    ├── components/            # 25+ reusable Blade components
    │   ├── glass-card.blade.php
    │   ├── glass-button.blade.php
    │   ├── stat-card.blade.php
    │   ├── status-badge.blade.php
    │   ├── modal.blade.php
    │   └── ...
    ├── admin/                 # Admin-only views
    ├── profile/               # Profile and ID card views
    ├── projects/              # Project CRUD + show with tasks
    ├── streets/               # Street CRUD views
    └── tasks/                 # Task edit views
```

**Technology decisions:**
- **Tailwind CSS** — utility-first CSS; no custom SCSS required
- **Alpine.js** — lightweight reactivity for modals, dropdowns, AJAX actions (no full SPA needed)
- **Chart.js** — dashboard analytics charts
- **Select2** — enhanced searchable dropdowns for user/street assignment
- **Three.js** — particle background effect (loaded only on desktop for performance)
- **Vite** — fast HMR in development; optimised bundle splitting for production

---

## Caching Strategy

The analytics dashboard uses **Laravel's cache layer** to avoid expensive queries on every page load.

```php
$dashboardData = Cache::remember('dashboard_data_' . $zone, 300, function () {
    // All demographic queries run here — cached for 5 minutes
});
```

**Cache keys:** `dashboard_data_all`, `dashboard_data_ZoneA`, etc.  
**TTL:** 300 seconds (5 minutes)  
**Store:** Configurable via `CACHE_STORE` env var (database, redis, file)

The dashboard was optimised from **15 separate queries** down to a **single demographics query** processed in PHP, with a 5-minute cache wrapper delivering **90% faster** load times.

---

## Security Architecture

| Layer | Controls |
|---|---|
| **Transport** | HSTS header (production), enforced HTTPS recommended |
| **Input** | `SanitizeInput` middleware, `$fillable` mass-assignment guards, Form Request validation |
| **Authentication** | Bcrypt passwords, session invalidation on logout, token revocation on API re-login |
| **Authorisation** | `AdminOnly` middleware, `$this->authorize()` policy checks, `UserRole` enum |
| **File Uploads** | MIME allowlist (jpeg/png/jpg/gif), 2MB cap, `uniqid()` + timestamp filename |
| **Rate Limiting** | API (60/min), admin routes (100/min), export route (10/min) |
| **Headers** | CSP, X-Frame-Options, X-Content-Type-Options, X-XSS-Protection, Referrer-Policy |
| **Data Safety** | Soft deletes prevent accidental permanent data loss |

---

## Role-Based Access Control

Roles are managed via the `UserRole` backed string enum:

```php
enum UserRole: string {
    case SUPERADMIN     = 'superadmin';
    case ADMIN          = 'admin';
    case PROJECT_MANAGER = 'project_manager';
    case USER           = 'user';
    case AUTHOR         = 'author';
}
```

**Route-level protection:**
| Route Group | Middleware | Who Can Access |
|---|---|---|
| `/dashboard` | `auth, verified` | All authenticated + verified users |
| `/admin/*` | `auth, admin` | Admins and Superadmins only |
| `/streets` | `auth, admin` | Admins and Superadmins only |
| `/projects`, `/tasks` | `auth, verified` | All authenticated + verified users |
| `/profile` | `auth` | Self (authenticated user) |
| `/api/v1/*` | `auth:sanctum` | Token-authenticated API clients |

---

## Performance Design

| Optimisation | Implementation |
|---|---|
| Dashboard caching | `Cache::remember()` 5-min TTL per zone |
| Single demographics query | One `DB::table('resident_extended')->select(...)->get()` processed in PHP |
| Eager loading | `.with(['street', 'users', 'tasks'])` on all list queries |
| Select-field limiting | `Street::select('id','name')` for dropdowns |
| Pagination | `->paginate(10)` on all user-facing list views |
| Result limits | `->limit(100)` on user dropdowns to prevent memory issues |
| Conditional particle effects | Three.js background only loaded on desktop (`resources/js/particles.js`) |
| Asset code splitting | Vite configured for chunk splitting |
| Performance indexes | 9 targeted database indexes on hot query paths |

**Before/After dashboard:**
| Metric | Before | After |
|---|---|---|
| Page load time | 7–10 s | < 1 s |
| Database time | 3–5 s | < 0.5 s |
| Asset bundle size | 2.5 MB | 1.5 MB |

---

## Key Design Decisions

1. **SQLite for development** — Zero-configuration local setup; migrations automatically detect driver and use appropriate date functions (`strftime` vs `DATE_FORMAT`).

2. **Soft Deletes everywhere** — Users, Streets, Projects, and Tasks all use `SoftDeletes` trait to prevent data loss from accidental deletions.

3. **Dual controller namespaces** — `App\Http\Controllers\Api\*` for JSON API responses, `App\Http\Controllers\*` for web Blade responses. Shared models; no code duplication.

4. **Form Request classes** — Validation logic extracted to `StoreUserRequest`, `UpdateUserRequest`, etc. to keep controllers thin.

5. **JSON Resources** — All API responses go through `*Resource` classes for consistent, versioned output format.

6. **Database-agnostic queries** — Controllers detect `DB::connection()->getDriverName()` and choose appropriate raw SQL functions for SQLite vs MySQL compatibility.

7. **`UserRole` enum** — PHP 8.1+ backed enum replaces magic strings; provides `labels()`, `isAdmin()`, `all()` helpers throughout the application.
