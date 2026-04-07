# 🏘️ Community Development System (CommDevSys)

<p align="center">
  <strong>A full-stack community management platform for tracking residents, managing development projects, and generating comprehensive demographic insights.</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/License-MIT-22c55e?style=for-the-badge" alt="MIT License">
</p>

---

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [System Architecture](#system-architecture)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Reference](#api-reference)
- [Testing](#testing)
- [Performance](#performance)
- [Security](#security)
- [Contributing](#contributing)
- [License](#license)

---

## 🎯 About

**Community Development System (CommDevSys)** is a production-ready, Laravel 12 web application built for local government units, community associations, and residential management organisations. It provides a centralized platform for managing residents, tracking community development projects, organising streets and geographic zones, and generating rich demographic reports.

CommDevSys combines a powerful **web interface** (Breeze + Blade + Tailwind CSS) with a fully versioned **REST API** (Sanctum token-based auth), enabling both browser users and third-party integrations to interact with the platform.

### Core Capabilities
| Capability | Description |
|---|---|
| 👥 Resident Management | Full resident profiles with 35+ demographic fields |
| 🏗️ Project Tracking | End-to-end community project lifecycle management |
| ✅ Task Management | Task assignment, status tracking, and multi-user assignment |
| 📊 Analytics Dashboard | Real-time demographic and statistical analytics with zone filtering |
| 🪪 ID Card Generation | QR-code–embedded resident ID cards downloadable as PDF |
| 📤 Data Export | Filtered Excel export of resident data |
| 🔌 REST API | Full JSON API for streets, projects, and tasks (v1) |
| 🔒 Role-Based Access | Superadmin, Admin, Project Manager, User, Author roles |

---

## ✨ Features

### 👥 User & Resident Management
- Multi-role system: **Superadmin**, **Admin**, **Project Manager**, **User**, **Author**
- Registration and authentication via **Laravel Breeze**
- Email verification with custom code-based flow
- Extended resident profiles with **35+ demographic fields** (gender, marital status, ethnicity, religion, education, employment, income, civic participation, infrastructure access, and more)
- Profile photo upload with MIME/size validation and secure random filenames
- Auto-generated unique resident **ID numbers** (format: `COMM-<uid>`)
- Admin can create, edit, and delete users; users can edit their own profiles

### 🏗️ Project Management
- Create and manage community development projects with title, description, dates, and status
- Assign projects to specific **streets/zones**
- Track project status: `pending`, `in_progress`, `completed`, `cancelled`
- **Budget tracking** (`budget` vs `actual_cost`)
- Multi-user assignment to projects (many-to-many)
- Soft delete support — no data is permanently lost

### ✅ Task Management
- Tasks are **nested under projects**
- Assign tasks to multiple users (many-to-many via `task_user` pivot table)
- Track task status and due dates
- AJAX-powered task creation, editing, and deletion inside project view

### 📊 Dashboard & Analytics
- Cached (5-min TTL) analytics dashboard to ensure fast page loads
- **Single optimised DB query** for all demographic breakdowns
- Gender, marital status, ethnicity, religion, indigene, education, employment, occupation, income distributions
- Infrastructure access metrics (electricity, water, sanitation)
- Monthly resident population growth trend chart
- Population per zone visualisation
- **Zone-based filtering** via dropdown

### 🗺️ Street & Zone Management
- Organise community by named streets linked to geographic **zones**
- Each street displays user count and project count
- Admin-only create, edit, and delete access
- Zone-based reporting and dashboard filtering

### 📄 Reports & Exports
- **Excel export** of resident data with 10 combinable demographic filters
- Filtered by: gender, marital status, indigene status, disability, income bracket, education level, religion, ethnicity, employment status, age range
- **PDF ID card** generation with embedded QR code (links to resident profile)
- Downloadable per-user and admin-managed ID cards

### 🔌 REST API (v1)
- Token-based authentication via **Laravel Sanctum**
- Full CRUD for Streets, Projects, and Tasks
- Nested resource routing: `projects/{project}/tasks`
- Filtering, searching, and pagination on list endpoints
- Rate-limited: 60 req/min for API routes

### 🔒 Security Features
- Role-based access control with `AdminOnly` middleware
- HTTP security headers: `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Strict-Transport-Security` (production), CSP, `Referrer-Policy`, `Permissions-Policy`
- Input sanitisation middleware for all web requests
- File upload MIME/size validation
- Mass assignment protection (`$fillable`)
- CSRF protection on all state-changing web routes
- Password hashing with bcrypt
- Rate throttling on exports and API routes
- Soft deletes on Users, Streets, Projects, and Tasks

---

## 🛠️ Tech Stack

### Backend
| Layer | Technology |
|---|---|
| Framework | Laravel 12.x |
| Language | PHP 8.2+ |
| Database | SQLite (dev) / MySQL (production) |
| Authentication | Laravel Breeze (web) + Laravel Sanctum (API) |
| PDF Generation | barryvdh/laravel-dompdf ^3.1 |
| Excel Export | maatwebsite/excel ^3.1 |
| QR Codes | simplesoftwareio/simple-qrcode ^4.2 |
| API Tokens | Laravel Sanctum ^4.2 |

### Frontend
| Layer | Technology |
|---|---|
| CSS Framework | Tailwind CSS 3.x |
| JS Framework | Alpine.js 3.x |
| Charts | Chart.js |
| Dropdowns | Select2 |
| Particles | Three.js |
| Build Tool | Vite 6.x |
| Icons | Font Awesome 6 |

### Development & Testing
| Tool | Purpose |
|---|---|
| PHPUnit 11.x | Unit and Feature testing |
| Laravel Pint | PSR-12 code style |
| Faker | Test data generation |
| Laravel Sail | Docker-based local dev |
| Laravel Pail | Real-time log viewer |
| Composer 2.x + NPM | Dependency management |

---

## 🏗️ System Architecture

```
commdevsys/
├── app/
│   ├── Enums/              # UserRole enum (Superadmin, Admin, PM, User, Author)
│   ├── Exports/            # UsersExport (Maatwebsite Excel)
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/      # DashboardController, UserManagementController, UserSearchController
│   │   │   ├── Api/        # AuthController, ProjectController, StreetController, TaskController
│   │   │   ├── Auth/       # Breeze auth controllers
│   │   │   ├── ProfileController
│   │   │   ├── ProjectController
│   │   │   ├── ResidentExtendedController
│   │   │   ├── StreetController
│   │   │   └── TaskController
│   │   ├── Middleware/     # AdminOnly, SanitizeInput, SecurityHeaders, ThrottleRequests
│   │   ├── Requests/       # Form Request validation classes
│   │   └── Resources/      # API JSON resources (User, Street, Project, Task)
│   ├── Models/             # User, Street, Project, Task, ResidentExtended
│   ├── Policies/           # Authorization policies
│   └── Providers/
├── database/
│   ├── migrations/         # 23 migrations covering full schema history
│   ├── factories/          # Model factories for testing
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── admin/          # Dashboard, user management views
│   │   ├── auth/           # Login, register, password reset
│   │   ├── components/     # 25+ reusable Blade components
│   │   ├── profile/        # Edit, extended, ID card views
│   │   ├── projects/       # CRUD views for projects
│   │   ├── streets/        # CRUD views for streets
│   │   └── tasks/          # Task list and edit views
│   ├── css/                # Tailwind CSS source
│   └── js/                 # Alpine.js + app JS
├── routes/
│   ├── web.php             # Web routes (auth, admin, profile, projects, tasks, streets)
│   ├── api.php             # API v1 routes (register, login, streets, projects, tasks)
│   ├── auth.php            # Breeze auth routes
│   └── console.php
└── tests/
    ├── Feature/            # Integration tests per module
    └── Unit/
```

### Request Flow
```
Browser/Client
    │
    ▼
routes/web.php or routes/api.php
    │
    ▼
Middleware Stack
  [SecurityHeaders → SanitizeInput → Auth → AdminOnly → Throttle]
    │
    ▼
Controller (HTTP layer)
    │
    ▼
Model (Eloquent ORM + relationships)
    │
    ▼
Database (SQLite / MySQL)
    │
    ▼
View (Blade template) or JSON Resource (API)
```

---

## 📦 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- SQLite or MySQL database

### Step 1: Clone the Repository
```bash
git clone https://github.com/yourusername/commdevsys.git
cd commdevsys
```

### Step 2: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 3: Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Create SQLite database (if using SQLite)
touch database/database.sqlite
```

### Step 4: Configure Database
Edit `.env` file with your database credentials:

**For SQLite (Development):**
```env
DB_CONNECTION=sqlite
```

**For MySQL (Production):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=commdevsys
DB_USERNAME=root
DB_PASSWORD=your_password
```

### Step 5: Run Migrations
```bash
php artisan migrate
```

### Step 6: Seed Database (Optional)
```bash
php artisan db:seed
```

### Step 7: Build Frontend Assets
```bash
# Development
npm run dev

# Production
npm run build
```

### Step 8: Start Development Server
```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

---

## ⚙️ Configuration

### Cache Configuration
For optimal performance, configure caching in `.env`:

```env
CACHE_STORE=database  # or redis for production
CACHE_PREFIX=commdevsys_
```

### Mail Configuration
Configure email settings for notifications:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS="noreply@commdevsys.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### File Storage
Ensure storage is linked for public file access:

```bash
php artisan storage:link
```

---

## 🚀 Usage

### Creating an Admin User
After installation, create an admin user manually in the database or via tinker:

```bash
php artisan tinker
```

```php
$user = new App\Models\User();
$user->firstname = 'Admin';
$user->lastname = 'User';
$user->email = 'admin@commdevsys.com';
$user->password = bcrypt('password');
$user->role = 'admin';
$user->street_id = 1; // Ensure street exists
$user->save();
```

### Accessing the Dashboard
1. Login at `/login`
2. Admin users can access `/dashboard` for analytics
3. Manage users at `/admin/users`
4. Create projects at `/projects`

### Generating ID Cards
1. Navigate to your profile
2. Click "View ID Card"
3. Download as PDF

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Feature tests only
php artisan test --testsuite=Feature

# Unit tests only
php artisan test --testsuite=Unit
```

### Run Specific Test File
```bash
php artisan test tests/Feature/UserManagementTest.php
```

### Code Coverage
```bash
php artisan test --coverage
```

---

## ⚡ Performance

### Optimizations Implemented
- ✅ Database indexes on frequently queried columns
- ✅ Query result caching (5-minute TTL)
- ✅ Eager loading to prevent N+1 queries
- ✅ Optimized dashboard queries (15 queries → 1 query)
- ✅ Frontend asset code splitting
- ✅ Conditional particle effects (desktop only)

### Performance Metrics
| Metric | Before Optimization | After Optimization | Improvement |
|--------|---------------------|-------------------|-------------|
| Dashboard Load | 7-10s | <1s | **90% faster** |
| Database Queries | 3-5s | <0.5s | **85% faster** |
| Page Size | 2.5MB | 1.5MB | **40% smaller** |

### Production Optimization
```bash
# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev

# Build production assets
npm run build
```

### Enable OPcache (Production)
Add to `php.ini`:
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.revalidate_freq=2
```

---

## � API Reference

The REST API is fully implemented under `/api/v1`. All protected endpoints require a **Bearer token** obtained via login.

### Authentication
```bash
# Register
POST /api/v1/register
{ "firstname": "Jane", "lastname": "Doe", "email": "jane@example.com", "password": "secret123", "password_confirmation": "secret123" }

# Login — returns { "token": "..." }
POST /api/v1/login
{ "email": "jane@example.com", "password": "secret123" }

# Include token in all subsequent requests
Authorization: Bearer <token>
```

### Endpoints Summary

| Method | Endpoint | Description | Auth |
|--------|----------|-------------|------|
| POST | `/api/v1/register` | Register a new user | Public |
| POST | `/api/v1/login` | Login and get token | Public |
| POST | `/api/v1/logout` | Revoke current token | ✅ Required |
| GET | `/api/v1/user` | Get authenticated user | ✅ Required |
| GET | `/api/v1/streets` | List streets | ✅ Required |
| POST | `/api/v1/streets` | Create a street | ✅ Required |
| GET | `/api/v1/streets/{id}` | Get a street | ✅ Required |
| PUT | `/api/v1/streets/{id}` | Update a street | ✅ Required |
| DELETE | `/api/v1/streets/{id}` | Delete a street | ✅ Required |
| GET | `/api/v1/projects` | List projects | ✅ Required |
| POST | `/api/v1/projects` | Create a project | ✅ Required |
| GET | `/api/v1/projects/{id}` | Get a project | ✅ Required |
| PUT | `/api/v1/projects/{id}` | Update a project | ✅ Required |
| DELETE | `/api/v1/projects/{id}` | Delete a project | ✅ Required |
| GET | `/api/v1/projects/{id}/tasks` | List project tasks | ✅ Required |
| POST | `/api/v1/projects/{id}/tasks` | Create a task | ✅ Required |
| GET | `/api/v1/projects/{id}/tasks/{tid}` | Get a task | ✅ Required |
| PUT | `/api/v1/projects/{id}/tasks/{tid}` | Update a task | ✅ Required |
| DELETE | `/api/v1/projects/{id}/tasks/{tid}` | Delete a task | ✅ Required |

> 📄 For full request/response examples and filter parameters, see [API_REFERENCE.md](API_REFERENCE.md).

---

## 🔒 Security

CommDevSys applies defence-in-depth across all layers:

| Layer | Control |
|---|---|
| HTTP Headers | X-Frame-Options, CSP, HSTS (prod), X-XSS-Protection, Referrer-Policy |
| Input | SanitizeInput middleware on all web requests; Laravel validation on all forms/API |
| Authentication | Bcrypt passwords; Sanctum bearer tokens for API; session-based for web |
| Authorisation | AdminOnly middleware; Policy-based `authorize()` calls; UserRole enum |
| File Uploads | MIME type allowlist, 2MB size cap, randomised filenames |
| Rate Limiting | 60 req/min (API), 100 req/min (admin web), 10 req/min (exports) |
| Data | Soft deletes prevent accidental permanent data loss |
| CSRF | Laravel CSRF protection on all state-changing web routes |

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/my-feature`)
3. Write tests for new functionality
4. Run the linter: `./vendor/bin/pint`
5. Commit your changes: `git commit -m 'feat: add my feature'`
6. Push and open a Pull Request

### Coding Standards
- Follow **PSR-12** coding standards (enforced by Laravel Pint)
- Run `./vendor/bin/pint` before every commit
- Write PHPUnit tests for all new features
- Keep controllers thin — move logic to dedicated service classes or models
- Document public methods with PHPDoc blocks

---

## 🐛 Bug Reports & Feature Requests

Please use the [GitHub Issues](https://github.com/yourusername/commdevsys/issues) page to report bugs or request features. Include reproduction steps and your PHP/Laravel version.

---

## 📚 Additional Documentation

| Document | Description |
|---|---|
| [API_REFERENCE.md](API_REFERENCE.md) | Full REST API v1 reference with examples |
| [DATABASE_SCHEMA.md](DATABASE_SCHEMA.md) | Complete database schema and ERD |
| [ARCHITECTURE.md](ARCHITECTURE.md) | System architecture and design decisions |
| [USER_GUIDE.md](USER_GUIDE.md) | End-user guide for all system features |
| [DEPLOYMENT.md](DEPLOYMENT.md) | Production deployment instructions |
| [TESTING.md](TESTING.md) | Testing guide and test coverage |
| [CHANGELOG.md](CHANGELOG.md) | Release history and change log |

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Authors

- **Rise Networks Development Team** — Community Development System

---

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com) 12
- UI components from [Tailwind CSS](https://tailwindcss.com)
- Icons from [Font Awesome](https://fontawesome.com)
- Charts by [Chart.js](https://www.chartjs.org)
- Particle effects by [Three.js](https://threejs.org)
- QR Codes by [SimpleSoftwareIO](https://github.com/SimpleSoftwareIO/simple-qrcode)

---

## 📞 Support

For support, email **support@commdevsys.com** or open an issue on GitHub.

---

**Made with ❤️ for community development — iris**