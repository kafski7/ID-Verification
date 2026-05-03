# PSC ID Verification System — Build Todo

Ordered from project setup to production deployment. Work through phases sequentially.

---

## Phase 1 — Project Setup

- [x] Create a new Laravel 13 project (scaffold + `composer install` via Docker)
- [x] Set up `docker-compose.yml` with services: `app` (PHP-FPM), `nginx`, `mysql`, `redis`
- [x] Write `Dockerfile` for the PHP-FPM app container (PHP 8.4, pdo_mysql, gd, zip, redis PECL)
- [x] Write `nginx.conf` for the dev environment
- [x] Configure `.env` for Docker (DB host, Redis host, app URL, app key generated)
- [x] Add `.env.example` with all required keys (no real values)
- [x] Confirm app loads at `http://localhost:8080` (`docker compose up -d` → HTTP 200)
- [x] Initialize a Git repository, add `.gitignore`, push to GitHub (`kafski7/ID-Verification`)
- [x] Install frontend tooling: Tailwind CSS v4 + `@tailwindcss/vite`, configured in `vite.config.js`
- [x] Install Livewire (`livewire/livewire` v4.3.0)
- [x] Install `barryvdh/laravel-dompdf` (v3.1.2)
- [x] Install `chillerlan/php-qrcode` (v6.0.1)

---

## Phase 2 — Database & Models

- [x] Create migration: `admin_users` table
- [x] Create migration: `staff` table (uuid PK, staff_id, id_no, full_name, sex, position, job_grade, department, phone, email, other_contacts, photo_path, date_of_issue, card_expires, status)
- [x] Create migration: `qr_tokens` table (staff_uuid FK, nonce, issued_at, expires_at, revoked, revoked_at, revoked_by FK)
- [x] Create migration: `scan_logs` table (token_nonce, staff_uuid, scanned_at, ip_address, user_agent, result)
- [x] Create Eloquent model: `Staff` (UUID PK, boot-generated uuid, relationships to QrToken/ScanLog)
- [x] Create Eloquent model: `QrToken` (belongs to Staff, belongs to AdminUser, `isValid()` helper)
- [x] Create Eloquent model: `ScanLog` (no timestamps, belongs to Staff)
- [x] Create Eloquent model: `AdminUser` (extends Authenticatable, role enum, `isSuperAdmin()`/`isHrAdmin()`)
- [x] Write database seeders: `AdminUserSeeder` (SUPER_ADMIN + HR_ADMIN), `StaffSeeder` (3 sample staff)
- [x] Run `php artisan migrate --seed` — all 7 tables created, seeds successful

---

## Phase 3 — Admin Authentication

- [x] Install Laravel Breeze (Blade stack) and publish auth views
- [x] Restrict all auth routes to `/admin/login` prefix (remove default `/login` route)
- [x] Customise login view with PSC branding
- [x] HTTPS redirect deferred — handled by nginx/load balancer in production (not needed in dev Docker)
- [x] Create `RoleMiddleware` to restrict routes by role (SUPER_ADMIN, HR_ADMIN, VIEWER)
- [x] Register `RoleMiddleware` in `bootstrap/app.php`
- [x] Test login, logout, and role-based access (HTTP 200 login page, 302 redirect to dashboard on success)

---

## Phase 4 — Admin Dashboard & Layout

- [x] Create main admin Blade layout (`layouts/admin.blade.php`) with sidebar navigation
- [x] Create dashboard page (`/admin/dashboard`) showing:
  - Total active staff count
  - Total scans today
  - Recent scan log entries
- [x] Make dashboard stats dynamic (query from DB)

---

## Phase 5 — Staff Management Module

- [x] Create `StaffController` with methods: index, create, store, show, edit, update, deactivate
- [x] Create staff list page with search and pagination (Livewire component)
- [x] Create add staff form with all fields and validation (`StaffStoreRequest`)
- [x] Implement photo upload: validate type/size, store in `storage/app/private/photos`, generate safe filename
- [x] Create edit staff form with pre-populated fields (`StaffUpdateRequest`)
- [x] Implement deactivate action (set status to INACTIVE, do not delete)
- [x] Create staff detail view page
- [x] Restrict create/edit/deactivate to HR_ADMIN and SUPER_ADMIN roles
- [x] Test all CRUD operations

---

## Phase 6 — QR Token Generation

- [x] Create `QrTokenService` class with methods:
  - `generate(Staff $staff): QrToken` — creates UUID, nonce, HMAC, stores in `qr_tokens`
  - `buildUrl(QrToken $token): string` — returns the full signed verification URL
  - `verify(string $rawToken): array` — decodes, validates HMAC, checks revocation, returns staff data
- [x] Store `QR_SECRET` in `.env` and access only via `config()`
- [x] Implement token encoding: `BASE64URL(uuid|issued_at|nonce|hmac)`
- [x] Implement HMAC validation using `hash_equals()` to prevent timing attacks
- [x] Create `QrController` with methods: show, regenerate, revoke
- [x] Create QR view page in admin: displays the QR image and signed URL for a staff member
- [x] Implement regenerate: revokes old token, generates new one
- [x] Implement revoke: marks token as revoked without regenerating
- [x] Generate QR image using `chillerlan/php-qrcode` and return as inline PNG
- [x] Test token generation, validation, and revocation end-to-end

---

## Phase 7 — ID Card Print Layout

- [x] Design card front Blade view (`card.blade.php` — browser flip-card with 3D CSS):
  - Staff photo, full name, Staff ID, ID No, Sex, position, department
  - Ghana Coat of Arms + PSC logo, Montserrat font
- [x] Design card back Blade view (back face of same `card.blade.php`):
  - Terms text, GPS address, cardholder name, other_contacts, QR code, footer logos
- [x] Create HTML preview route (`GET /admin/staff/{staff}/card`) — opens flip-card in browser
- [x] Create PDF download route (`GET /admin/staff/{staff}/card/pdf`) using dompdf
- [x] Add "Preview ID Card" and "Download PDF" buttons to staff show page
- [x] Test print output at CR80-inspired badge dimensions (340×540px browser, 242×382pt PDF)

---

## Phase 8 — Public Verification Page

- [x] Create `VerifyController` with a `show` method
- [x] Register public route: `GET /verify/{token}`
- [x] In `show`: call `QrTokenService::verify()`, log the scan result to `scan_logs`
- [x] Create verification Blade view (`verify/show.blade.php`):
  - Mobile-first, no login required
  - PSC branding header (Ghana coat of arms + PSC seal)
  - Staff photo (rendered as base64 data URI from private disk)
  - Full name, Staff ID, ID No, Sex, Position, Department, Job Grade, Status pill, Card Expires, Other Contacts
  - Large VALID (green) / INVALID (red) status badge
  - Timestamp of scan
- [x] Handle error states gracefully on the same view (revoked, not found, tampered, inactive)
- [x] Ensure no stack traces or internal errors are exposed on the public page

---

## Phase 9 — JSON API Endpoint

- [x] Register API route: `GET /api/v1/verify?token=...`
- [x] Reuse `QrTokenService::verify()` logic
- [x] Return structured JSON response with appropriate HTTP status codes:
  - `200` — valid staff data (staff_id, id_no, full_name, sex, position, job_grade, department, status, date_of_issue, card_expires, other_contacts, optional photo data URI)
  - `400` — malformed / missing token
  - `403` — invalid signature
  - `404` — staff not found
  - `410` — token revoked
  - `422` — other invalid states (expired, inactive)
  - `429` — rate limit exceeded
- [x] Log scan to `scan_logs` same as HTML endpoint
- [x] Add API rate limiting via Laravel rate limiter — 30 req/min per IP, backed by Redis cache driver

---

## Phase 10 — Audit Log Viewer

- [x] Create `AuditController` with index method
- [x] Create audit log list page in admin with:
  - Filters: staff name/ID, IP address, date range (from/to), result type
  - Pagination (25 per page)
  - Columns: date/time, staff name (linked to profile), staff ID, IP address, result pill
- [x] Restrict audit log to HR_ADMIN and SUPER_ADMIN via `role` middleware
- [x] Livewire `ScanLogList` component with reactive URL-bound filters

---

## Phase 11 — Admin User Management

- [x] Create `AdminUserController` (SUPER_ADMIN only) with: index, create, store, edit, update, deactivate
- [x] Create admin user list page (role colour pills, (you) indicator, deactivate button hidden for own account)
- [x] Create add/edit admin user forms (name, email, role, password / optional on edit)
- [x] Implement password hashing on store/update via `hashed` cast on model (Laravel 10+)
- [x] Prevent a Super Admin from deactivating their own account (controller guard + button hidden in view)
- [x] Admin Users nav link in sidebar (SUPER_ADMIN only)

---

## Phase 11b — Staff Self-Service Portal

### Auth
- [x] Create `staff` guard + `StaffAuthController` (separate from admin auth)
- [x] Create staff login page (`/staff/login`) — mobile-first, PSC branding
- [x] Add "Forgot Password" flow for staff: request form, email reset link, reset form (Laravel password broker with `staff` guard)
- [x] Protect all `/staff/*` routes with `auth:staff` middleware
- [x] Create staff portal layout (mobile-first, sticky header with collapsible nav + logout)

### Profile & Settings
- [x] Create staff dashboard / profile page — display all current details (read-only overview)
- [x] Create "Edit My Details" form — editable fields: **telephone, email, other_contacts** only
- [x] Create "Change Password" page with current-password confirmation
- [x] Add `StaffProfileController` with: `show`, `editDetails`, `updateDetails`, `editPassword`, `updatePassword`
- [x] Validate email uniqueness (excluding own record) on update

### Privacy Controls
- [x] Add `privacy_settings` JSON column + `password` + `remember_token` to `staff` table (migration)
- [x] Create "Privacy Settings" page with toggle switches (ON = visible, OFF = hidden)
- [x] Add `PrivacyController` — `edit`, `update`
- [x] Update `verify/show.blade.php` to honour privacy settings via `Staff::privacyHides()`
- [x] Hideable fields: Staff ID, Grade, Telephone, Email, Other Contacts
- [x] Non-hideable fields (always shown on verify): Full Name, ID No, Sex, Position, Department, Card Expires, Status

### Navigation
- [x] Staff portal routes fully isolated under `auth:staff` guard (no cross-access with admin)

### Mail
- [x] Configured Resend SMTP (`smtp.resend.com:465`) in `.env`
- [ ] Verify `psc.gov.gh` domain in Resend dashboard (add DKIM/SPF/DMARC DNS records) — then set `MAIL_MAILER=smtp` and `MAIL_FROM_ADDRESS=noreply@psc.gov.gh`
- [x] Currently using `MAIL_MAILER=log` for dev — reset links appear in `storage/logs/laravel.log`

---

## Phase 12 — Security Hardening

- [ ] Add global rate limiting on verification routes (Nginx `limit_req_zone`)
- [ ] Add CSRF protection to all admin forms (Laravel default, verify not disabled anywhere)
- [ ] Add Content Security Policy headers via middleware
- [ ] Add `X-Frame-Options: DENY` and `X-Content-Type-Options: nosniff` headers
- [ ] Validate all file uploads (photo): MIME type check, max size, randomise stored filename
- [ ] Ensure `APP_DEBUG=false` and `APP_ENV=production` in production `.env`
- [ ] Review all routes — confirm public routes expose zero internal information on error
- [ ] Run `php artisan route:list` and audit for any unintended exposed routes

---

## Phase 13 — Testing

- [ ] Write feature tests for `QrTokenService` (generate, verify, revoke, tampered token, expired token)
- [ ] Write feature test for verification page (valid, invalid, revoked token responses)
- [ ] Write feature test for API endpoint (correct HTTP status codes per scenario)
- [ ] Write feature tests for staff CRUD (including role restrictions)
- [ ] Write feature test for admin authentication (login, logout, role middleware)
- [ ] Run full test suite: `php artisan test`
- [ ] Fix any failures before proceeding to deployment

---

## Phase 14 — Production Server Setup

- [ ] Provision Linux server (Ubuntu 22.04 LTS recommended)
- [ ] Install: Nginx, PHP 8.x FPM, MySQL 8, Redis, Certbot
- [ ] Create a dedicated non-root system user for the application
- [ ] Configure Nginx server block for `id.psc.gov.gh`
- [ ] Obtain TLS certificate via Certbot (`certbot --nginx -d id.psc.gov.gh`)
- [ ] Configure Nginx to enforce HTTPS (redirect HTTP → HTTPS)
- [ ] Set up MySQL: create production database and dedicated DB user with least-privilege access
- [ ] Create production `.env` on server (never transfer via insecure channel)
- [ ] Set correct file permissions on `storage/` and `bootstrap/cache/`
- [ ] Configure PHP-FPM pool settings (user, max children, memory limit)
- [ ] Set up Redis for session and cache in production `.env`

---

## Phase 15 — Deployment

- [ ] Clone repository to server (`/var/www/psc-id` or similar)
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `npm run build` (or run locally and commit built assets)
- [ ] Run `php artisan migrate --force`
- [ ] Run `php artisan optimize` (config/route/view cache)
- [ ] Seed Super Admin user on first deploy
- [ ] Confirm the application is live at `https://id.psc.gov.gh/admin/login`
- [ ] Test QR scan end-to-end on production using a test staff record
- [ ] Remove any test/seed data from production

---

## Phase 16 — Handover & Documentation

- [ ] Document deployment steps in `docs/deployment.md`
- [ ] Document how to add a new staff member and generate a QR code
- [ ] Document how to revoke a compromised card
- [ ] Document how to rotate the `QR_SECRET` (and its impact — all cards must be reissued)
- [ ] Document backup procedure for MySQL database and uploaded photos
- [ ] Train HR Admin users on the admin panel
