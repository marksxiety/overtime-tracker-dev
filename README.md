# 🕒 TimeTrack Pro

TimeTrack Pro is a modern overtime tracker system that helps organizations track, analyze, and manage employee overtime hours.

It provides a digital filing workflow for employees and approvers, plus analytics and reporting on overtime usage and reasons.

Now enhanced with **AI integration** for:

- **Report Analysis:** Automatically summarizes overtime trends, identifies recurring issues, and highlights insights from weekly or monthly data.
- **Reason Enhancer:** Refines and standardizes employee-submitted overtime reasons for clarity, tone, and completeness — ensuring more consistent reporting quality.

## 📘 Overview

The system allows:

- Employees to file overtime requests with reasons and actual hours.
- Approvers to approve, disapprove, or mark as filed.
- Weekly schedule management (employee and approver views).
- Shift codes with optional time windows.
- Required weekly hours per organization unit with remaining-hours computation.
- Analytics dashboard and date-range reporting.

## 🧩 Entity Relationship Diagram (ERD)

The ERD includes employees, shifts, schedules, overtime requests, and weekly hour rules.

![ERD Diagram](overtime-tracker-erd.drawio.png)

## ⚙️ Tech Stack

- Backend: Laravel 11 (API-based)
- Frontend: Vue 3 + Inertia.js + TypeScript
- Database: MySQL
- Auth: Laravel Sanctum (JWT-ready)
- Styling: TailwindCSS + DaisyUI
- Optional AI: OpenAI API (for future insights and summaries)

## 🧰 Prerequisites

- PHP 8.2+
- Composer 2.5+
- Node.js 18+ (recommended: 20 LTS)
- npm 9+
- MySQL 8+ (or compatible MariaDB)
- OpenAI API key for AI-assisted features

## 🚀 Getting Started

### Clone the Repository

```bash
git clone https://github.com/marksxiety/timetrack-pro.git
cd timetrack-pro
```

### Install Dependencies

```bash
composer install
npm install
```

### Setup Environment

- Copy `.env.example` to `.env`
- Update database credentials and `APP_URL`
- Example MySQL setup:

### Run Application

```bash
php artisan migrate
php artisan storage:link
php artisan serve
npm run dev
```

Then visit:

- Laravel + Vue (Inertia): <http://127.0.0.1:8000>
- Vue only: <http://localhost:5173>

## 🔐 Authentication & Roles

- Roles: employee, approver, admin
- Guards/Middleware: auth, employee, approver
- Guest: register/login only
- Authenticated: dashboard, requests, schedules, reports

## 🛣️ API & Web Routes (by Role)

### Guest

- GET /register – Register form
- POST /register – Register
- GET /login – Login form
- POST /login – Login

### Authenticated

- GET / – Role-based landing
- POST /logout – Logout

### Employee Routes

- /schedule/list – Fetch weekly schedule
- /schedule/user – Get specific user schedule
- /schedule/submit – Submit or update schedule
- /overtime/request – File overtime request
- /overtime/update/employee – Update pending overtime
- /employee/profile – View profile
- /profile/update/employee – Update profile or avatar
- /overtime/requests/list – View overtime list by week/status

### Approver Routes

- /shift – Manage shift codes
- /shift/register – Add shift code
- /shift/{shift} – Update/Delete shift code
- /hours – Manage required hours
- /overtime/update/approver – Approve/decline requests
- /overtime/pending, /filed, /filing – Get overtime requests
- /schedule/manage – Manage employee schedules
- /schedule/employee/list – Get merged employee-week structure
- /users/registered – Manage users
- /generate/report – Generate overtime reports
- /404 – Unauthorized page

## 🧮 Data Model (High Level)

| Table               | Description                               |
| ------------------- | ----------------------------------------- |
| users               | Employee/Approver/Admin data              |
| shift_codes         | Time window templates                      |
| schedules           | Weekly assignments                         |
| overtime_requests   | Overtime filing details                    |
| required_hours      | Weekly cap per org unit                    |
| organization_units  | Organizational grouping                    |

Relationships:

- User → hasMany → Schedule
- Schedule → belongsTo → User, Shift
- Schedule → hasMany → OvertimeRequest
- OvertimeRequest → belongsTo → Schedule
- RequiredHours → belongsTo → OrganizationUnit

## 🔁 Workflow Summary

- Setup by Approver – Register shift codes and required hours.
- Employee Schedule – Employee sets or confirms schedule.
- Filing Overtime – Submit requests (validated for overlaps).
- Approval – Approver reviews, approves, or disapproves.
- Analytics – Dashboard shows status and remaining hours.
- Reporting – Generate overtime reports by date range and org unit.

**Status Lifecycle:**

PENDING → APPROVED/FILED → DECLINED/DISAPPROVED/CANCELED

## 🧪 Time & Validation Rules

- Format: 24-hour (H:i)
- Minimum overtime: 1 hour
- If shift has time window:
  - Must start ≥60 mins before or after shift
  - Cannot overlap scheduled shift
  - Night shifts roll end time to next day