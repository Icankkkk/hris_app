---
name: hris-api
description: Architecture, workflow conventions, local environment setup, and development guidelines for the HRIS Laravel API backend (hris-api-main). Use when modifying, testing, creating controllers, models, migrations, seeders, or debugging the HRIS backend API.
---

# HRIS Backend API Development Guide (`hris-api-main`)

This skill provides essential guidelines, architecture patterns, and operational instructions for developing and maintaining the Laravel-based backend of the HRIS application.

---

## 1. Technology Stack & Local Environment

- **Framework**: Laravel 12 (configured for PHP 8.2 compatibility).
- **PHP Version**: PHP 8.2 (Laragon NTS on Windows).
- **Database**: MySQL 8.0 / MariaDB on `127.0.0.1:3306`.
  - Database Name: `hris_api`
  - User: `root`
  - Password: `""` (empty)
- **Local Drivers Configuration (`.env`)**:
  - `SESSION_DRIVER=file`
  - `CACHE_STORE=file`
  - `QUEUE_CONNECTION=sync`
  - `SCOUT_DRIVER=null` (or `tntsearch` for local search without MeiliSearch)
  - `FILESYSTEM_DISK=public`
- **Authentication**: Laravel Sanctum (Bearer Token via `/api/v1/login`).
- **Authorization / RBAC**: `spatie/laravel-permission` with roles (`manager`, `hr`, `finance`, `employee`).

---

## 2. Directory & Component Structure

All backend code resides in `hris-api-main/`:

```text
hris-api-main/
├── app/
│   ├── Enums/                 # Department, JobStatus, TaskPriority, TeamStatus, etc.
│   ├── Http/
│   │   ├── Controllers/       # API Controllers (AuthController, EmployeeProfileController, etc.)
│   │   ├── Middleware/        # Custom middlewares (EnsureProjectMembership, etc.)
│   │   ├── Requests/          # Form Request validators
│   │   └── Resources/         # JSON API Resources
│   └── Models/                # Eloquent Models (User, EmployeeProfile, Team, Project, etc.)
├── config/                    # Application configs (database, auth, cors, etc.)
├── database/
│   ├── factories/             # Model factories for testing and seeding
│   ├── migrations/            # Database schema migrations
│   └── seeders/               # Seeders (RoleSeeder, ManagerSeeder, HrSeeder, etc.)
└── routes/
    ├── api.php                # All REST API routes under `/api/v1` prefix
    └── web.php
```

---

## 3. Seeded Accounts & Roles

| Role | Email | Password | Primary Permissions / Scope |
| :--- | :--- | :--- | :--- |
| **Manager** | `manager@gmail.com` | `password` | Full access, dashboard stats, team management, project assignment |
| **HR** | `hr@gmail.com` | `password` | Employee profiles, job info, attendance, leave approval |
| **Finance** | `finance@gmail.com` | `password` | Payroll generation, salary details, payment status |
| **Employee** | `employee@gmail.com` | `password` | Personal profile, my-team, check-in/out, leave requests |

---

## 4. API Conventions & Standards

### URL Prefix & Grouping
All API routes are prefixed with `/api/v1` in `routes/api.php`.

### Standard Response Format
Always return responses adhering to the standard JSON structure:

```json
{
  "success": true,
  "message": "Action completed successfully",
  "data": { ... }
}
```

For validation or error responses:
```json
{
  "message": "Validation failed message",
  "errors": {
    "field_name": ["Specific error explanation"]
  }
}
```

### Authentication Header
Protected endpoints require:
```http
Authorization: Bearer <sanctum_token>
Accept: application/json
```

---

## 5. Key Artisan Commands

Always execute artisan commands from `hris-api-main/`:

- **Run development server**:
  ```powershell
  php artisan serve --port=8000
  ```
- **Run migrations & refresh database**:
  ```powershell
  php artisan migrate
  php artisan migrate:fresh --seed
  ```
- **Clear & cache configuration**:
  ```powershell
  php artisan optimize:clear
  php artisan config:clear
  ```
- **Run tests (Pest / PHPUnit)**:
  ```powershell
  php artisan test
  ```
- **Storage link**:
  ```powershell
  php artisan storage:link
  ```

---

## 6. Guidelines for Making Code Changes

1. **Keep PHP 8.2 Compatibility**:
   - Avoid PHP 8.3+ specific features (like typed class constants without default or `pest ^4.0`).
2. **Always validate incoming requests**: Use Form Request classes (`app/Http/Requests`) or `$request->validate([...])`.
3. **Relationships**: Define proper Eloquent inverse relationships across `EmployeeProfile`, `JobInformation`, `BankInformation`, and `EmergencyContact`.
4. **Permissions**: Guard controller actions using Spatie role/permission checks or policy gates when introducing new features.
