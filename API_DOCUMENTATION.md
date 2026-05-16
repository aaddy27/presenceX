# 📡 PresenceX API Documentation

> **Version**: 1.0 | **Backend**: Laravel 12 + Sanctum | **Auth**: Bearer Token
>
> **Base URL (Local Dev)**: `http://127.0.0.1:8000/api`
>
> **Base URL (Android Emulator)**: `http://10.0.2.2:8000/api`

---

## 📋 Table of Contents

- [Authentication Flow](#-authentication-flow)
- [1. Login](#1-login)
- [2. Logout](#2-logout)
- [3. Sync Down — Fetch Staff](#3-sync-down--fetch-active-staff)
- [4. Register Staff](#4-register-new-staff)
- [5. Attendance Sync Up](#5-attendance-sync-up)
- [6. Get Holidays](#6-get-all-holidays)
- [7. Add Holiday](#7-add-holiday)
- [8. Generate Payroll](#8-generate-payroll)
- [Error Reference](#-error-reference)
- [Default Credentials](#-default-credentials)
- [Quick cURL Tests](#-quick-curl-tests)

---

## 🔐 Authentication Flow

All **protected** endpoints require a `Bearer` token in every HTTP request header:

```
Authorization: Bearer {your_token_here}
Content-Type: application/json
Accept: application/json
```

> Store the token securely using `flutter_secure_storage`. The token is obtained from the Login endpoint.

---

## 1. Login

Authenticates the user and returns a Sanctum Bearer token.

| Field        | Value           |
|--------------|-----------------|
| **Method**   | `POST`          |
| **Endpoint** | `/auth/login`   |
| **Auth**     | Not Required    |

**Request Body:**
```json
{
  "email": "admin@presencex.com",
  "password": "password123",
  "device_name": "PresenceX Kiosk Tablet"
}
```

| Field         | Type     | Required | Description                                |
|---------------|----------|----------|--------------------------------------------|
| `email`       | `string` | Yes      | Registered user email address              |
| `password`    | `string` | Yes      | Account password                           |
| `device_name` | `string` | Yes      | Unique device/session name for this token  |

**Success Response — `200 OK`:**
```json
{
  "token": "1|abcdef1234567890tokenstring...",
  "user": {
    "id": 1,
    "name": "Super Admin",
    "email": "admin@presencex.com",
    "role": "Admin"
  }
}
```

| Field        | Type     | Description                                        |
|--------------|----------|----------------------------------------------------|
| `token`      | `string` | Sanctum plain text token — **save this securely**  |
| `user.id`    | `int`    | User primary key                                   |
| `user.name`  | `string` | Full display name                                  |
| `user.email` | `string` | Email address                                      |
| `user.role`  | `string` | Either `Admin` or `Kiosk`                          |

**Error Response — `422 Unprocessable Entity`:**
```json
{
  "message": "The provided credentials are incorrect.",
  "errors": {
    "email": ["The provided credentials are incorrect."]
  }
}
```

---

## 2. Logout

Revokes the current device's Bearer token from the server.

| Field        | Value        |
|--------------|--------------|
| **Method**   | `POST`       |
| **Endpoint** | `/auth/logout`|
| **Auth**     | Required     |

**Request Body:** *(None)*

**Success Response — `200 OK`:**
```json
{
  "message": "Logged out successfully"
}
```

---

## 3. Sync Down — Fetch Active Staff

Downloads all **Active** staff members including their face embeddings. Call this on app startup and after any new registration to refresh the local cache used for offline face matching.

| Field        | Value              |
|--------------|--------------------|
| **Method**   | `GET`              |
| **Endpoint** | `/staff/sync-down` |
| **Auth**     | Required           |

**Request Body:** *(None)*

**Success Response — `200 OK`:**
```json
[
  {
    "id": 1,
    "staff_code": "STF001",
    "full_name": "John Doe",
    "designation": "Software Engineer",
    "face_embedding": [0.123, -0.456, 0.789, 0.012, -0.345]
  },
  {
    "id": 2,
    "staff_code": "STF002",
    "full_name": "Jane Smith",
    "designation": "HR Manager",
    "face_embedding": [0.321, -0.654, 0.987, 0.111, -0.222]
  }
]
```

| Field            | Type           | Description                                           |
|------------------|----------------|-------------------------------------------------------|
| `id`             | `int`          | Primary key — use as local Hive/SQLite record key      |
| `staff_code`     | `string`       | Unique employee code (e.g., STF001)                   |
| `full_name`      | `string`       | Full display name                                     |
| `designation`    | `string`       | Job title                                             |
| `face_embedding` | `List<double>` | 128-dimension float vector for face recognition       |

> **Important**: Cache `face_embedding` locally in Hive/SQLite. During attendance marking, use
> **Cosine Similarity** or **Euclidean Distance** to compare live TFLite embeddings against
> these cached vectors. A similarity score above `0.80` is typically considered a valid match.

---

## 4. Register New Staff

Onboards a new employee by saving their personal info and the face embedding vector captured from the Flutter camera.

| Field        | Value             |
|--------------|-------------------|
| **Method**   | `POST`            |
| **Endpoint** | `/staff/register` |
| **Auth**     | Required          |

**Request Body:**
```json
{
  "staff_code": "STF003",
  "full_name": "Ali Hassan",
  "phone": "+92-300-1234567",
  "designation": "Front Desk Officer",
  "joining_date": "2026-05-16",
  "shift_id": 1,
  "basic_salary": 45000,
  "monthly_allowances": 5000,
  "face_embedding": [0.123, -0.456, 0.789, 0.012],
  "email": "ali@presencex.com",
  "password": "secret123"
}
```

| Field                | Type           | Required | Description                                         |
|----------------------|----------------|----------|-----------------------------------------------------|
| `staff_code`         | `string`       | Yes      | Unique employee code — must not already exist        |
| `full_name`          | `string`       | Yes      | Employee's full name                                |
| `phone`              | `string`       | Yes      | Contact number                                      |
| `designation`        | `string`       | Yes      | Job title                                           |
| `joining_date`       | `date`         | Yes      | Format: `YYYY-MM-DD`                                |
| `shift_id`           | `int`          | Yes      | Must exist in the `shifts` table                    |
| `basic_salary`       | `numeric`      | Yes      | Monthly base salary                                 |
| `monthly_allowances` | `numeric`      | No       | Monthly allowances — defaults to `0`                |
| `face_embedding`     | `List<double>` | Yes      | 128-float vector generated by TFLite on device      |
| `email`              | `string`       | No       | Optional — creates a linked user account if provided |
| `password`           | `string`       | No       | Required only if `email` is also provided            |

**Success Response — `201 Created`:**
```json
{
  "message": "Staff registered successfully",
  "staff": {
    "id": 3,
    "staff_code": "STF003",
    "full_name": "Ali Hassan",
    "phone": "+92-300-1234567",
    "designation": "Front Desk Officer",
    "joining_date": "2026-05-16",
    "shift_id": 1,
    "basic_salary": 45000,
    "monthly_allowances": 5000,
    "status": "Active",
    "created_at": "2026-05-16T10:30:00.000000Z"
  }
}
```

**Error Response — `422 Unprocessable Entity`:**
```json
{
  "message": "The staff code has already been taken.",
  "errors": {
    "staff_code": ["The staff code has already been taken."]
  }
}
```

---

## 5. Attendance Sync Up

Sends an array of locally cached attendance punch logs to the server. The backend auto-calculates attendance status and productive hours per log entry.

| Field        | Value                 |
|--------------|-----------------------|
| **Method**   | `POST`                |
| **Endpoint** | `/attendance/sync-up` |
| **Auth**     | Required              |

**Request Body:**
```json
{
  "logs": [
    {
      "staff_code": "STF001",
      "date": "2026-05-16",
      "punch_in": "09:15:00",
      "punch_out": "17:45:00",
      "breaks": [
        {
          "break_start": "13:00:00",
          "break_end": "13:30:00"
        }
      ]
    },
    {
      "staff_code": "STF002",
      "date": "2026-05-16",
      "punch_in": "10:30:00",
      "punch_out": null,
      "breaks": []
    }
  ]
}
```

| Field                        | Type          | Required | Description                            |
|------------------------------|---------------|----------|----------------------------------------|
| `logs`                       | `array`       | Yes      | Array of attendance log objects         |
| `logs.*.staff_code`          | `string`      | Yes      | Identifies the staff member             |
| `logs.*.date`                | `date`        | Yes      | Format: `YYYY-MM-DD`                   |
| `logs.*.punch_in`            | `string/null` | No       | First punch time — format: `HH:MM:SS`  |
| `logs.*.punch_out`           | `string/null` | No       | Last punch time — format: `HH:MM:SS`   |
| `logs.*.breaks`              | `array/null`  | No       | Array of break intervals               |
| `logs.*.breaks.*.break_start`| `string`      | No       | Format: `HH:MM:SS`                     |
| `logs.*.breaks.*.break_end`  | `string`      | No       | Format: `HH:MM:SS`                     |

**Backend Auto-Calculation Logic:**

| Status         | Condition                                                            |
|----------------|----------------------------------------------------------------------|
| `Present`      | Punched in within grace period                                       |
| `Late In`      | `punch_in` is after `shift.start_time + grace_time_minutes`         |
| `Early Out`    | `punch_out` is before `shift.end_time`                              |
| `Half Day`     | Productive hours < 50% of total shift hours                          |
| `Absent`       | No punch recorded                                                    |

> **Productive Hours** = `(punch_out - punch_in) - total_break_duration`

**Success Response — `200 OK`:**
```json
{
  "message": "Sync process completed",
  "processed_count": 2,
  "errors": []
}
```

**Partial Failure Response — `200 OK`** *(other logs still processed)*:
```json
{
  "message": "Sync process completed",
  "processed_count": 1,
  "errors": ["Staff STF099 not found"]
}
```

| Field             | Type     | Description                                              |
|-------------------|----------|----------------------------------------------------------|
| `message`         | `string` | Completion status message                                |
| `processed_count` | `int`    | Number of logs successfully saved or updated on server    |
| `errors`          | `array`  | List of failed log reasons (empty array if all succeeded) |

---

## 6. Get All Holidays

Fetches all registered organizational holidays sorted by date ascending.

| Field        | Value       |
|--------------|-------------|
| **Method**   | `GET`       |
| **Endpoint** | `/holidays` |
| **Auth**     | Required    |

**Request Body:** *(None)*

**Success Response — `200 OK`:**
```json
[
  {
    "id": 1,
    "date": "2026-08-14",
    "description": "Independence Day",
    "created_at": "2026-05-16T10:00:00.000000Z",
    "updated_at": "2026-05-16T10:00:00.000000Z"
  },
  {
    "id": 2,
    "date": "2026-12-25",
    "description": "Christmas Day",
    "created_at": "2026-05-16T10:00:00.000000Z",
    "updated_at": "2026-05-16T10:00:00.000000Z"
  }
]
```

| Field         | Type     | Description                         |
|---------------|----------|-------------------------------------|
| `id`          | `int`    | Primary key                         |
| `date`        | `string` | Holiday date — format: `YYYY-MM-DD` |
| `description` | `string` | Human-readable holiday name         |

---

## 7. Add Holiday

Adds a new holiday entry. Should only be called by `Admin` role users.

| Field        | Value       |
|--------------|-------------|
| **Method**   | `POST`      |
| **Endpoint** | `/holidays` |
| **Auth**     | Required    |

**Request Body:**
```json
{
  "date": "2026-11-09",
  "description": "Iqbal Day"
}
```

| Field         | Type     | Required | Description                             |
|---------------|----------|----------|-----------------------------------------|
| `date`        | `date`   | Yes      | Format: `YYYY-MM-DD` — must be unique   |
| `description` | `string` | Yes      | Name/label of the holiday               |

**Success Response — `201 Created`:**
```json
{
  "id": 3,
  "date": "2026-11-09",
  "description": "Iqbal Day",
  "created_at": "2026-05-16T15:00:00.000000Z",
  "updated_at": "2026-05-16T15:00:00.000000Z"
}
```

**Error Response — `422 Unprocessable Entity`:**
```json
{
  "message": "The date has already been taken.",
  "errors": {
    "date": ["The date has already been taken."]
  }
}
```

---

## 8. Generate Payroll

Calculates and returns a detailed payroll breakdown for a specific employee and month.

| Field        | Value               |
|--------------|---------------------|
| **Method**   | `GET`               |
| **Endpoint** | `/payroll/generate` |
| **Auth**     | Required            |

**Query Parameters:**
```
GET /payroll/generate?staff_id=1&month=2026-05
```

| Param      | Type     | Required | Description                           |
|------------|----------|----------|---------------------------------------|
| `staff_id` | `int`    | Yes      | The `id` from the `staff_details` table |
| `month`    | `string` | Yes      | Format: `YYYY-MM` (e.g., `2026-05`)   |

**Success Response — `200 OK`:**
```json
{
  "staff_name": "John Doe",
  "month": "2026-05",
  "basic_salary": 45000,
  "allowances": 5000,
  "per_day_salary": 1666.67,
  "stats": {
    "present": 20,
    "late_in": 3,
    "half_day": 1,
    "absent": 2,
    "holidays": 2
  },
  "deductions": {
    "late_in_penalty_days": 0.5,
    "half_day_penalty_days": 0.5
  },
  "payable_days": 21.0,
  "final_payable_salary": 35000.07
}
```

| Field                              | Type     | Description                                                         |
|------------------------------------|----------|---------------------------------------------------------------------|
| `staff_name`                       | `string` | Employee full name                                                  |
| `month`                            | `string` | Pay period                                                          |
| `basic_salary`                     | `float`  | Configured monthly base salary                                      |
| `allowances`                       | `float`  | Monthly allowances                                                  |
| `per_day_salary`                   | `float`  | `(basic + allowances) / days_in_month`                              |
| `stats.present`                    | `int`    | Days marked Present, Late In, or Early Out                          |
| `stats.late_in`                    | `int`    | Total late arrival count                                            |
| `stats.half_day`                   | `int`    | Total half-day count                                                |
| `stats.absent`                     | `int`    | Total absent days                                                   |
| `stats.holidays`                   | `int`    | Paid holidays in the month                                          |
| `deductions.late_in_penalty_days`  | `float`  | Every 3 late arrivals = 0.5 day salary deduction                    |
| `deductions.half_day_penalty_days` | `float`  | Each half day = 0.5 day deduction                                   |
| `payable_days`                     | `float`  | `present + holidays - late_deductions - half_day_deductions`        |
| `final_payable_salary`             | `float`  | `payable_days x per_day_salary` — the net disbursement amount       |

---

## 🚨 Error Reference

### HTTP Status Codes

| Code  | Status               | Cause                                                      |
|-------|----------------------|------------------------------------------------------------|
| `200` | OK                   | Request succeeded                                          |
| `201` | Created              | New resource created (register staff, add holiday)         |
| `401` | Unauthorized         | Token missing, expired, or invalid — re-login required     |
| `403` | Forbidden            | Authenticated but lacking role permission                  |
| `404` | Not Found            | Resource does not exist                                    |
| `422` | Unprocessable Entity | Validation failed — read the `errors` object               |
| `500` | Internal Server Error| Unexpected server crash                                    |

### Standard `422` Validation Error Shape
```json
{
  "message": "Human readable summary.",
  "errors": {
    "field_name": [
      "Specific validation error message."
    ]
  }
}
```

### Standard `401` Unauthorized Shape
```json
{
  "message": "Unauthenticated."
}
```

---

## 🔑 Default Credentials (Development Only)

> **IMPORTANT**: Change these before going to production!

| Role    | Email                   | Password      |
|---------|-------------------------|---------------|
| `Admin` | `admin@presencex.com`   | `password123` |
| `Kiosk` | `kiosk@presencex.com`   | `password123` |

---

## 🧪 Quick cURL Tests

**Login:**
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@presencex.com","password":"password123","device_name":"curl-test"}'
```

**Fetch All Staff (replace YOUR_TOKEN):**
```bash
curl -X GET http://127.0.0.1:8000/api/staff/sync-down \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

**Push Attendance Logs:**
```bash
curl -X POST http://127.0.0.1:8000/api/attendance/sync-up \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"logs":[{"staff_code":"STF001","date":"2026-05-16","punch_in":"09:05:00","punch_out":"17:00:00","breaks":[]}]}'
```

**Generate Payroll:**
```bash
curl -X GET "http://127.0.0.1:8000/api/payroll/generate?staff_id=1&month=2026-05" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

*PresenceX Biometric Face Attendance System — Laravel 12 + Sanctum API v1.0*
