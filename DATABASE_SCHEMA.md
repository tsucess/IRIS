# 🗄️ Database Schema — CommDevSys

This document describes the complete database schema for the Community Development System. The application supports both **SQLite** (development) and **MySQL** (production).

---

## Table of Contents
- [Entity Relationship Overview](#entity-relationship-overview)
- [Tables](#tables)
  - [users](#users)
  - [streets](#streets)
  - [resident_extended](#resident_extended)
  - [projects](#projects)
  - [project_user](#project_user-pivot)
  - [tasks](#tasks)
  - [task_user](#task_user-pivot)
  - [personal_access_tokens](#personal_access_tokens)
  - [cache](#cache)
  - [jobs](#jobs)
- [Indexes](#indexes)
- [Migration History](#migration-history)

---

## Entity Relationship Overview

```
streets (1) ──< users (1) ──< resident_extended (1)
   |
   └──< projects (M) >──< users (M)  [project_user pivot]
            |
            └──< tasks (M) >──< users (M)  [task_user pivot]

users (1) ──< personal_access_tokens (M)  [Sanctum API tokens]
```

**Relationship summary:**
- A **Street** has many **Users** and many **Projects**
- A **User** belongs to one **Street** (optional)
- A **User** has one **ResidentExtended** profile (optional, created on first save)
- **Projects** and **Users** share a many-to-many relationship via `project_user`
- **Tasks** belong to one **Project** and have many assigned **Users** via `task_user`
- All main entities use **soft deletes** (except `resident_extended`)

---

## Tables

### `users`
Primary user table. Stores authentication credentials and basic profile data.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `firstname` | varchar(255) | No | — | First name |
| `lastname` | varchar(255) | No | — | Last name |
| `email` | varchar(255) | No | — | Unique email address |
| `email_verified_at` | timestamp | Yes | NULL | Email verification timestamp |
| `email_verification_code` | varchar(255) | Yes | NULL | Code sent for email verification |
| `email_verification_code_expires_at` | timestamp | Yes | NULL | Expiry of verification code |
| `password` | varchar(255) | No | — | Bcrypt hashed password |
| `phone` | varchar(255) | Yes | NULL | Phone number |
| `street_id` | bigint UNSIGNED | Yes | NULL | FK → `streets.id` |
| `role` | varchar(255) | No | `user` | One of: superadmin, admin, project_manager, user, author |
| `photo` | varchar(255) | Yes | NULL | Profile photo filename (in `/public/uploads`) |
| `id_number` | varchar(255) | Yes | NULL | Auto-generated unique ID (e.g. `COMM-63F4A12B`) |
| `remember_token` | varchar(100) | Yes | NULL | Session remember token |
| `deleted_at` | timestamp | Yes | NULL | Soft delete timestamp |
| `created_at` | timestamp | Yes | NULL | |
| `updated_at` | timestamp | Yes | NULL | |

**Indexes:** `email` (unique), `street_id`, `role`, `deleted_at`

---

### `streets`
Represents a named street or road within a geographic zone.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `name` | varchar(255) | No | — | Unique street name |
| `zone` | varchar(255) | Yes | NULL | Geographic zone (e.g. "Zone A") |
| `description` | text | Yes | NULL | Optional description |
| `deleted_at` | timestamp | Yes | NULL | Soft delete timestamp |
| `created_at` | timestamp | Yes | NULL | |
| `updated_at` | timestamp | Yes | NULL | |

**Indexes:** `name` (unique), `zone`, `deleted_at`

---

### `resident_extended`
Extended demographic profile for a user. Created via `updateOrCreate` on first profile edit.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `user_id` | bigint UNSIGNED | No | — | FK → `users.id` (cascade delete) |
| `middle_name` | varchar(255) | Yes | NULL | |
| `gender` | enum | Yes | NULL | male, female, other |
| `date_of_birth` | date | Yes | NULL | |
| `place_of_birth` | varchar(255) | Yes | NULL | |
| `marital_status` | enum | Yes | NULL | single, married, divorced, widowed |
| `number_of_children` | integer | Yes | NULL | |
| `ethnicity` | varchar(255) | Yes | NULL | |
| `religion` | varchar(255) | Yes | NULL | |
| `address` | varchar(500) | Yes | NULL | Street address |
| `city` | varchar(255) | Yes | NULL | |
| `state` | varchar(255) | Yes | NULL | |
| `postal_code` | varchar(20) | Yes | NULL | |
| `phone_number` | varchar(20) | Yes | NULL | |
| `email` | varchar(255) | Yes | NULL | Alternative contact email |
| `education_level` | enum | Yes | NULL | none, primary, secondary, tertiary, vocational |
| `employment_status` | enum | Yes | NULL | employed, unemployed, self-employed, retired |
| `occupation` | varchar(255) | Yes | NULL | |
| `income_bracket` | enum | Yes | NULL | low, middle, high |
| `has_disability` | tinyint(1) | No | 0 | |
| `blood_group` | varchar(10) | Yes | NULL | |
| `is_voter` | tinyint(1) | No | 0 | |
| `is_taxpayer` | tinyint(1) | No | 0 | |
| `household_size` | integer | Yes | NULL | |
| `access_to_electricity` | tinyint(1) | No | 0 | |
| `access_to_clean_water` | tinyint(1) | No | 0 | |
| `access_to_sanitation` | tinyint(1) | No | 0 | |
| `internet_access` | tinyint(1) | No | 0 | |
| `emergency_contact_name` | varchar(255) | Yes | NULL | |
| `emergency_contact_phone` | varchar(20) | Yes | NULL | |
| `emergency_contact_relation` | varchar(255) | Yes | NULL | |
| `civic_participation` | varchar(500) | Yes | NULL | |
| `volunteer_activities` | varchar(500) | Yes | NULL | |
| `indigene` | tinyint(1) | Yes | NULL | Indigenous resident flag |
| `country` | varchar(255) | Yes | NULL | Country of origin |
| `created_at` | timestamp | Yes | NULL | |
| `updated_at` | timestamp | Yes | NULL | |

**Indexes:** `user_id` (unique FK)

---

### `projects`
Community development project records.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `title` | varchar(255) | No | — | Project title |
| `description` | text | Yes | NULL | Full project description |
| `start_date` | date | Yes | NULL | Project start date |
| `end_date` | date | Yes | NULL | Project end date |
| `status` | varchar(255) | No | `pending` | pending, in_progress, completed, cancelled |
| `street_id` | bigint UNSIGNED | Yes | NULL | FK → `streets.id` |
| `budget` | double | Yes | NULL | Planned project budget |
| `actual_cost` | double | Yes | NULL | Actual cost incurred |
| `deleted_at` | timestamp | Yes | NULL | Soft delete timestamp |
| `created_at` | timestamp | Yes | NULL | |
| `updated_at` | timestamp | Yes | NULL | |

**Indexes:** `status`, `street_id`, `deleted_at`

---

### `project_user` (Pivot)
Many-to-many relationship between Projects and Users.

| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint UNSIGNED | No | Primary key |
| `project_id` | bigint UNSIGNED | No | FK → `projects.id` |
| `user_id` | bigint UNSIGNED | No | FK → `users.id` |
| `created_at` | timestamp | Yes | |
| `updated_at` | timestamp | Yes | |

**Indexes:** `(project_id, user_id)` composite

---

### `tasks`
Tasks nested under projects, with optional user assignment.

| Column | Type | Nullable | Default | Description |
|---|---|---|---|---|
| `id` | bigint UNSIGNED | No | AUTO_INCREMENT | Primary key |
| `project_id` | bigint UNSIGNED | No | — | FK → `projects.id` |
| `title` | varchar(255) | No | — | Task title |
| `description` | text | Yes | NULL | Task description |
| `status` | varchar(255) | No | — | Task status (e.g. pending, in_progress, completed) |
| `due_date` | date | Yes | NULL | Task due date |
| `assigned_to` | bigint UNSIGNED | Yes | NULL | FK → `users.id` (single primary assignee) |
| `deleted_at` | timestamp | Yes | NULL | Soft delete timestamp |
| `created_at` | timestamp | Yes | NULL | |
| `updated_at` | timestamp | Yes | NULL | |

**Indexes:** `project_id`, `status`, `deleted_at`

---

### `task_user` (Pivot)
Many-to-many relationship between Tasks and Users (multi-assignee support).

| Column | Type | Nullable | Description |
|---|---|---|---|
| `id` | bigint UNSIGNED | No | Primary key |
| `task_id` | bigint UNSIGNED | No | FK → `tasks.id` |
| `user_id` | bigint UNSIGNED | No | FK → `users.id` |
| `created_at` | timestamp | Yes | |
| `updated_at` | timestamp | Yes | |

---

### `personal_access_tokens`
Laravel Sanctum API tokens.

| Column | Type | Description |
|---|---|---|
| `id` | bigint UNSIGNED | Primary key |
| `tokenable_type` | varchar(255) | Polymorphic type (usually `App\Models\User`) |
| `tokenable_id` | bigint UNSIGNED | FK to the tokenable model |
| `name` | varchar(255) | Token name (e.g. `auth-token`) |
| `token` | varchar(64) | Hashed token value |
| `abilities` | text | JSON array of token abilities |
| `last_used_at` | timestamp | Last usage timestamp |
| `expires_at` | timestamp | Token expiry (null = no expiry) |
| `created_at` | timestamp | |
| `updated_at` | timestamp | |

---

### `cache`
Laravel framework cache table (used when `CACHE_STORE=database`).

| Column | Type | Description |
|---|---|---|
| `key` | varchar(255) | Cache key (primary key) |
| `value` | mediumtext | Serialized cached value |
| `expiration` | integer | Unix timestamp of expiry |

---

### `jobs`
Laravel queue jobs table.

| Column | Type | Description |
|---|---|---|
| `id` | bigint UNSIGNED | Primary key |
| `queue` | varchar(255) | Queue name |
| `payload` | longtext | Serialized job payload |
| `attempts` | tinyint UNSIGNED | Number of processing attempts |
| `reserved_at` | integer | Unix timestamp when reserved |
| `available_at` | integer | Unix timestamp when available |
| `created_at` | integer | Unix timestamp when created |

---

## Indexes

Performance indexes added in migration `2026_01_05_000001_add_performance_indexes`:

| Table | Column(s) | Type | Purpose |
|---|---|---|---|
| `users` | `email` | UNIQUE | Login lookup |
| `users` | `street_id` | INDEX | FK join performance |
| `users` | `role` | INDEX | Role-based filtering |
| `users` | `deleted_at` | INDEX | Soft delete scoping |
| `streets` | `zone` | INDEX | Zone-based filtering |
| `streets` | `deleted_at` | INDEX | Soft delete scoping |
| `projects` | `status` | INDEX | Status filtering |
| `projects` | `street_id` | INDEX | FK join performance |
| `resident_extended` | `user_id` | UNIQUE | One-to-one enforcement |

---

## Migration History

| Migration File | Description |
|---|---|
| `0001_01_01_000000_create_users_table` | Initial users table |
| `0001_01_01_000001_create_cache_table` | Laravel cache table |
| `0001_01_01_000002_create_jobs_table` | Laravel queue jobs table |
| `2025_07_06_165009_create_streets_table` | Streets table |
| `2025_07_06_170105_add_fields_to_users_table` | Add phone, street_id, role, photo, id_number to users |
| `2025_07_06_211714_add_role_to_users_table` | Add role enum to users |
| `2025_07_08_053947_create_projects_table` | Projects table |
| `2025_07_08_053948_create_tasks_table` | Tasks table |
| `2025_07_29_042244_add_assigned_user_id_to_projects_table` | Add assigned user FK to projects |
| `2025_07_29_071204_create_project_user_table` | Many-to-many: projects ↔ users |
| `2025_07_29_075946_create_task_user_table` | Many-to-many: tasks ↔ users |
| `2025_08_15_150308_rename_name_to_firstname_and_add_lastname` | Split name into firstname + lastname |
| `2025_08_15_154836_create_resident_extended_tables` | Full resident_extended table |
| `2025_08_17_050631_add_extra_columns_to_resident_extended` | Add civic/volunteer fields |
| `2025_08_18_052818_add_indigene_to_resident_extended` | Add indigene boolean field |
| `2025_08_19_100247_add_country_to_resident_extended` | Add country field |
| `2026_01_05_000001_add_performance_indexes` | Database performance indexes |
| `2026_01_05_031225_add_soft_deletes_to_users_and_streets` | Soft deletes for users & streets |
| `2026_01_05_100000_create_personal_access_tokens_table` | Sanctum API tokens table |
| `2026_04_07_000001_add_budget_to_projects_table` | Add budget & actual_cost to projects |
| `2026_04_07_000002_add_soft_deletes_to_tasks_table` | Soft deletes for tasks |
| `2026_04_07_124406_add_verification_code_to_users_table` | Email verification code fields |

