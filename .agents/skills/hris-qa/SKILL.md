---
name: hris-qa
description: Comprehensive QA testing matrix, feature analysis, test accounts, API & UI test scenarios, and troubleshooting Q&A for testing and verifying the SenjaHRIS application.
---

# SenjaHRIS QA Testing & Verification Guide (`hris-qa`)

This skill provides a complete feature analysis, role-based testing matrix, API & UI test scenarios, and troubleshooting Q&A for validating the **SenjaHRIS** web application.

---

## 1. Test Accounts & Role Directory

| Role | Email | Password | Permissions Count | Primary Scope |
| :--- | :--- | :--- | :---: | :--- |
| **Manager** | `manager@gmail.com` | `password` | 38 Permissions | Full system access, team creation, project oversight, payroll approval, executive metrics. |
| **HR** | `hr@gmail.com` | `password` | 30 Permissions | Employee recruitment, attendance review, leave request approvals, project tracking. |
| **Finance** | `finance@gmail.com` | `password` | 17 Permissions | Payroll generation, salary adjustments, payroll mark-as-paid, Excel reporting. |
| **Employee** | `employee@gmail.com` | `password` | 19 Permissions | Clock-in/out attendance, personal leave requests, team viewing, monthly payslips. |

---

## 2. Feature & Module Analysis (Q&A Testing Guide)

### 📌 Module 1: Authentication & User Session
- **Q: What are the key test cases for Login & Auth?**
  - **TC-AUTH-01 (Valid Login)**: Login with valid credentials (`manager@gmail.com` / `password`). Expect HTTP 200, auth token stored in cookie, redirect to `/admin/dashboard`, and user object populated with `roles` and `permissions`.
  - **TC-AUTH-02 (Invalid Credentials)**: Submit wrong password. Expect HTTP 401 with error message `"Invalid credentials"`.
  - **TC-AUTH-03 (Session Persistence)**: Reload page (`F5`). Expect session to remain authenticated via `fetchMe()` on router load.
  - **TC-AUTH-04 (Logout)**: Click Logout in profile dropdown. Expect token deletion, cookie removal, and redirect to `/login`.

---

### 📌 Module 2: Dashboard & Executive Analytics
- **Q: What should be verified on the Dashboard?**
  - **TC-DASH-01 (Statistics Cards)**: Total Employees, Teams Count, Attendance Rate (%), Tasks Completed, and Active Projects load with real data from `GET /api/v1/dashboard/statistics`.
  - **TC-DASH-02 (Dynamic Quick Actions)**:
    - Manager: Shows `Add Employee`, `Create New Team`, `Process Payroll`, `Review Attendance`.
    - Employee: Shows `Clock In / Out`, `My Attendance`, `My Payslips`, `My Team`.
  - **TC-DASH-03 (Charts & Visualizations)**: Member growth and attendance trend charts render smoothly without console errors.

---

### 📌 Module 3: Teams Management (`/admin/teams`)
- **Q: How to test the Team module end-to-end?**
  - **TC-TEAM-01 (Paginated List & Filter)**: Verify pagination (10 items/page), search by team name, filter by department and status.
  - **TC-TEAM-02 (Create Team)**: Navigate to `/admin/teams/create`, fill in Name, Leader, Department, Expected Size, Responsibilities, and Icon. Verify redirect to list and new team is displayed.
  - **TC-TEAM-03 (Team Detail & Growth Chart)**: Click on a team card to open `/admin/teams/:id`. Verify team statistics, members list, and assigned projects.
  - **TC-TEAM-04 (Edit Team)**: Update team description or leader at `/admin/teams/edit/:id`. Verify changes persist.
  - **TC-TEAM-05 (Add/Remove Member)**: Assign a new employee to the team and verify member counter increments.

---

### 📌 Module 4: Employees Management (`/admin/employees`)
- **Q: What are the critical verification points for Employee profiles?**
  - **TC-EMP-01 (Directory List & Filters)**: Filter by Employment Type (Full-time, Part-time, Contract, Internship) and Job Status (Active, Probation, Suspended, Resigned, Terminated).
  - **TC-EMP-02 (Create Employee Multi-Step)**:
    - Step 1: Personal Info (Name, Email, Phone, Gender, Birth Date, Profile Picture).
    - Step 2: Employment Details (Job Title, Department, Designation, Work Location, Hire Date).
    - Step 3: Compensation (Base Salary, Allowances, Deductions).
  - **TC-EMP-03 (Profile Detail View)**: Verify avatar image loads via `http://localhost:8000/storage/...` without broken image icons.
  - **TC-EMP-04 (Edit Employee)**: Update job title or salary. Verify updated data in database and UI.

---

### 📌 Module 5: Projects & Kanban Task Board (`/admin/projects`)
- **Q: How to test the Project & Task workflow?**
  - **TC-PROJ-01 (Project Listing)**: Filter by project status (`Planning`, `In Progress`, `On Hold`, `Completed`, `Cancelled`).
  - **TC-PROJ-02 (Create Project)**: Create project with Name, Assigned Team, Start/End Date, and Budget.
  - **TC-PROJ-03 (Kanban Task Board)**: Open project detail (`/admin/projects/:id`). Verify tasks grouped by status columns (`Todo`, `In Progress`, `In Review`, `Done`).
  - **TC-PROJ-04 (Task Creation & Assignment)**: Add a task, set priority (`Low`, `Medium`, `High`, `Urgent`), and assign to a team member.

---

### 📌 Module 6: Attendance & Leave Management
- **Q: How to test Attendance Clocking and Leave Approval?**
  - **TC-ATT-01 (Employee Clock In)**: Log in as Employee, go to `/admin/attendance/clock`, click Clock In. Expect success alert and start timestamp saved.
  - **TC-ATT-02 (Employee Clock Out)**: Click Clock Out. Expect work duration calculated and recorded.
  - **TC-ATT-03 (Admin Attendance Review)**: Log in as HR/Manager, open `/admin/attendances`. Verify date filter and status indicators.
  - **TC-ATT-04 (Leave Request & Approval)**:
    - Employee submits leave request (type: Annual / Sick, date range, reason).
    - HR/Manager reviews and clicks `Approve` or `Reject`.
    - Employee verifies status updated to `Approved`.

---

### 📌 Module 7: Payroll & Payslip Engine (`/admin/payroll`)
- **Q: What is the testing flow for Payroll?**
  - **TC-PAY-01 (Generate Payroll Period)**: Log in as Finance/Manager, go to `/admin/payroll/create`, select Year & Month. Click Generate. Expect automatic calculation of all active employee salaries.
  - **TC-PAY-02 (Payroll Detail & Adjustments)**: Open generated payroll, inspect individual employee breakdown (Base + Allowances - Deductions). Edit bonus or penalty if needed.
  - **TC-PAY-03 (Mark as Processed / Paid)**: Click `Mark as Paid`. Verify payroll status updates from `Pending` to `Paid`.
  - **TC-PAY-04 (Employee Payslip Download)**: Log in as Employee, go to `/admin/my-payslips`, click View/Download. Verify clean PDF generation and printed values.
  - **TC-PAY-05 (Export Excel)**: Click Export Excel in Payroll list. Verify file download with complete column data.

---

## 3. Automated API Health Check Script

To run a full automated check across all core backend endpoints:

```powershell
php -r "
require 'vendor/autoload.php';
\$app = require_once 'bootstrap/app.php';
\$kernel = \$app->make('Illuminate\Contracts\Console\Kernel');
\$kernel->bootstrap();
\$user = App\Models\User::first();
\$token = \$user->createToken('qa_check')->plainTextToken;

\$endpoints = [
    '/api/v1/me',
    '/api/v1/dashboard/statistics',
    '/api/v1/teams/all/paginated',
    '/api/v1/employees/all/paginated',
    '/api/v1/projects/all/paginated',
    '/api/v1/attendances/all/paginated',
    '/api/v1/payrolls/all/paginated',
    '/api/v1/options/departments',
    '/api/v1/options/employment-types',
    '/api/v1/options/job-statuses'
];

foreach (\$endpoints as \$ep) {
    \$req = Illuminate\Http\Request::create(\$ep, 'GET');
    \$req->headers->set('Authorization', 'Bearer ' . \$token);
    \$req->headers->set('Accept', 'application/json');
    \$res = \$app->handle(\$req);
    echo \"{\$ep} => HTTP \" . \$res->getStatusCode() . PHP_EOL;
}
"
```

---

## 4. Common Troubleshooting & FAQs

- **Q: Sidebar menu is completely blank or missing links after login?**
  - **Cause**: User permissions array is empty in Pinia `authStore.user.permissions`.
  - **Fix**: Run `php artisan db:seed --class=RolePermissionSeeder` and verify `UserResource.php` serializes permissions.

- **Q: Images / Avatars are broken (404 Not Found)?**
  - **Cause**: Broken or missing `public/storage` link.
  - **Fix**: Run `php artisan storage:link` and ensure image assets are populated in `storage/app/public/`.

- **Q: API returns 422 `"The row per page field is required"` on table load?**
  - **Cause**: Controller `getAllPaginated()` requires `row_per_page`.
  - **Fix**: Set `'row_per_page' => 'nullable|integer|min:1'` with default fallback to `10`.
