# PSC Secure QR-Based Staff ID Verification System
## Project Scope Document

---

## 1. Project Summary

A self-hosted, Laravel-based web application for the Public Services Commission (PSC) that:
- Manages staff identity records via a protected admin panel
- Generates secure, signed QR codes for physical staff ID cards
- Provides a mobile-friendly public verification page when a QR code is scanned
- Exposes a JSON API endpoint for programmatic verification

The system is designed to be hosted at `id.psc.gov.gh` on a Linux server and developed locally using Docker.

---

## 2. Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.x |
| Framework | Laravel 11 |
| Admin UI | Blade + Livewire + Tailwind CSS |
| Public UI | Blade + Tailwind CSS |
| Database | MySQL 8 |
| Session / Rate limiting | Redis |
| QR Generation | `chillerlan/php-qrcode` |
| PDF / Print | `barryvdh/laravel-dompdf` |
| Auth | Laravel Breeze (session-based) |
| Dev Environment | Docker (PHP-FPM, Nginx, MySQL, Redis) |
| Production | Linux server (Nginx, PHP-FPM, MySQL, Redis) |

---

## 3. Application Modules

### 3.1 Public — QR Verification Page
- Route: `GET /v/verify?token=...`
- Triggered when a physical ID card QR code is scanned
- Validates the HMAC-signed token server-side
- Displays a branded mobile-first page showing:
  - Staff photo
  - Full name
  - Staff ID
  - Position / Job grade
  - Department
  - Status badge (VALID / INACTIVE / INVALID)
- No sensitive data is stored in the QR code itself

### 3.2 Public — JSON API Endpoint
- Route: `GET /api/v1/verify?token=...`
- Same token validation logic as the verification page
- Returns a JSON response for machine-to-machine consumers
- Responds to `Accept: application/json` or direct endpoint hit

### 3.3 Admin — Authentication
- Session-based login (Laravel Breeze)
- HTTPS-only access
- Role-based access control (RBAC):
  - **Super Admin**: full access including user management
  - **HR Admin**: staff CRUD, QR generation, card printing
  - **Viewer**: read-only access to staff records and audit logs

### 3.4 Admin — Staff Management
- Create, read, update, deactivate staff records
- Fields: full name, staff ID, position, job grade, department, phone, email, date of issue, card expiry, photo, status
- Staff records use a UUID as the primary lookup key (never the sequential DB ID)
- Soft deactivation (status flag) rather than deletion to preserve audit trail

### 3.5 Admin — QR Code & Card Management
- Generate signed HMAC tokens per staff member
- Regenerate token on card reissue (previous token remains valid until explicitly revoked)
- Display and download the QR code image
- Printable ID card layout (HTML print view + PDF via dompdf)
  - Card front: photo, name, staff ID, position, QR code
  - Card back: PSC branding, instructions for verifier

### 3.6 Admin — Audit & Revocation
- Log every verification scan (timestamp, IP, token used, result)
- Revoke individual tokens (compromised or lost cards)
- View audit log with filters (by staff, date range, result)

---

## 4. Security Architecture

### 4.1 QR Token Design
```
BASE64URL( staff_uuid | issued_at | nonce | HMAC_SHA256(staff_uuid|issued_at|nonce, SECRET) )
```
- `staff_uuid`: non-guessable UUID (not the staff number)
- `issued_at`: token creation timestamp
- `nonce`: random 32-character string to prevent replay attacks
- `HMAC`: server-side signature using a secret key stored in `.env`

### 4.2 Token Lifetime Strategy
- Tokens are **long-lived** (aligned with card validity period — typically 2–5 years)
- Revocation is handled via the `qr_tokens.revoked` flag, not expiry
- On card reissue, old token is revoked and a new token is generated

### 4.3 Security Controls
| Control | Implementation |
|---|---|
| HTTPS only | Nginx + Let's Encrypt (enforced at server level) |
| Token signature | HMAC-SHA256 |
| Timing-attack prevention | `hash_equals()` |
| UUID-based lookups | Prevents IDOR / BOLA (OWASP API #1) |
| SQL injection prevention | Eloquent ORM / prepared statements only |
| Rate limiting | Nginx `limit_req_zone` + Laravel + Redis |
| No data in QR | Token is opaque; data fetched server-side |
| Audit logging | Every scan logged |
| Secrets management | `.env` file, never committed to repo |
| Input validation | Laravel Form Requests on all inputs |

---

## 5. Database Schema

### `staff`
| Column | Type | Notes |
|---|---|---|
| id | INT AUTO_INCREMENT | Internal PK only |
| uuid | CHAR(36) UNIQUE | Public-facing lookup key |
| staff_id | VARCHAR(20) UNIQUE | Visible on card (e.g. PSC-01452) |
| full_name | VARCHAR(120) | |
| position | VARCHAR(120) | |
| job_grade | VARCHAR(50) | |
| department | VARCHAR(120) | |
| phone | VARCHAR(20) | |
| email | VARCHAR(120) | |
| photo_path | VARCHAR(255) | Server-side path |
| date_of_issue | DATE | |
| card_expires | DATE | Nullable |
| status | ENUM('ACTIVE','INACTIVE') | |
| created_at / updated_at | TIMESTAMP | |

### `qr_tokens`
| Column | Type | Notes |
|---|---|---|
| id | INT AUTO_INCREMENT | |
| staff_uuid | CHAR(36) | FK to staff.uuid |
| nonce | CHAR(32) | |
| issued_at | DATETIME | |
| expires_at | DATETIME | Nullable |
| revoked | BOOLEAN DEFAULT 0 | |
| revoked_at | DATETIME | Nullable |
| revoked_by | INT | FK to admin users |

### `scan_logs`
| Column | Type | Notes |
|---|---|---|
| id | BIGINT AUTO_INCREMENT | |
| token_nonce | CHAR(32) | Reference to token used |
| staff_uuid | CHAR(36) | |
| scanned_at | DATETIME | |
| ip_address | VARCHAR(45) | IPv4 and IPv6 |
| user_agent | VARCHAR(255) | |
| result | ENUM('VALID','INVALID','REVOKED','EXPIRED','NOT_FOUND') | |

### `admin_users`
| Column | Type | Notes |
|---|---|---|
| id | INT AUTO_INCREMENT | |
| name | VARCHAR(120) | |
| email | VARCHAR(120) UNIQUE | |
| password | VARCHAR(255) | Bcrypt hashed |
| role | ENUM('SUPER_ADMIN','HR_ADMIN','VIEWER') | |
| created_at / updated_at | TIMESTAMP | |

---

## 6. Application Routes Overview

```
Public
  GET  /v/verify?token=...        → Verification HTML page
  GET  /api/v1/verify?token=...   → Verification JSON response

Auth
  GET  /admin/login               → Login page
  POST /admin/login               → Authenticate
  POST /admin/logout              → Logout

Admin (authenticated)
  GET  /admin/dashboard           → Dashboard / stats
  GET  /admin/staff               → Staff list
  GET  /admin/staff/create        → Add staff form
  POST /admin/staff               → Save new staff
  GET  /admin/staff/{uuid}        → View staff record
  GET  /admin/staff/{uuid}/edit   → Edit staff form
  PUT  /admin/staff/{uuid}        → Update staff
  POST /admin/staff/{uuid}/deactivate → Deactivate staff
  GET  /admin/staff/{uuid}/qr     → View/generate QR
  POST /admin/staff/{uuid}/qr     → Regenerate token
  POST /admin/staff/{uuid}/revoke → Revoke current token
  GET  /admin/staff/{uuid}/card   → Printable card view
  GET  /admin/staff/{uuid}/card/pdf → Download card PDF
  GET  /admin/audit               → Audit log list
  GET  /admin/users               → Admin user management (Super Admin only)
```

---

## 7. Docker Dev Environment

```
docker-compose.yml services:
  app     → PHP 8.x FPM (Laravel application)
  nginx   → Web server (port 80/443)
  mysql   → MySQL 8 database
  redis   → Session store and rate limiting
```

Volumes:
- Application code mounted for live editing
- MySQL data persisted in a named volume
- `.env` file used for all secrets (never in Docker image)

---

## 8. Production Deployment

- OS: Linux (Ubuntu 22.04 LTS recommended)
- Web server: Nginx + PHP 8.x FPM
- Database: MySQL 8
- Cache/sessions: Redis
- TLS: Let's Encrypt (Certbot)
- Domain: `id.psc.gov.gh`
- Deployment: Git pull + `php artisan migrate --force` + `php artisan optimize`
- Secrets: `.env` on server, excluded from repo via `.gitignore`

---

## 9. Out of Scope (v1)

- Mobile app
- Active Directory / SSO integration
- Offline verification
- Biometric verification
- Email/SMS notifications
- Geo-anomaly detection (noted as future enhancement)
