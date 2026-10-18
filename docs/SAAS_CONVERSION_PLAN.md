# SaaS Conversion Plan — School ERP (Smart-School-derived, CBSE-customized)

**Status:** Pre-development blueprint · **Date:** 2026-10-17
**Scope:** Turn the current single-school install (Sunrise) into a multi-tenant, self-serve, billable SaaS product without regressing the live school.

---

## 1. Current-State Audit (what the code actually is today)

| Area | Finding | SaaS impact |
|---|---|---|
| Framework | CodeIgniter 3, PHP, MySQL; ~155 admin controllers, ~226 models, ~273 tables | CI3 is EOL. Workable for v1, but must be pinned to a supported PHP (8.1+) and hardened. |
| Duplicate apps | `api/` is a **full second copy** of `application/` + `system/` (~34k tracked files) | Every fix must be made twice. Must be unified before scaling. |
| Separate app | `store/` has its own `config.php`/`index.php`, linked via `admin/Store_sso.php` | Needs tenant-aware SSO and config. |
| Tenancy hooks | `Db_manager` already switches DB per session (`admin.db_array.db_group`); `Site.php` hardcodes `db_group = 'default'` | **Good foundation** for database-per-tenant. |
| SaaS stubs | `saas-config.php` (`saas_enabled = false`), `SaasValidation` returns `TRUE` everywhere, `ResourceQuota` is an empty class | Hook points exist in upload/create paths; need real implementations. |
| Secrets | **Production DB credentials committed** in `application/config/database.php`; `encryption_key = ''` | Critical — rotate now, move to env vars. |
| Security config | `csrf_protection = FALSE`; sessions in `sys_get_temp_dir()` (files) | Cross-tenant session/CSRF risk; must fix before multi-tenant. |
| Hardcoding | "Sunrise" appears in ~22 app files (Cron, Api_cms, Brevo, AI exam generator, views, Store SSO) | Must become tenant settings. |
| Schema changes | Raw `.sql` files in `backend/`, ad-hoc `Migrate*.php` controllers, only 2 CI migrations | Cannot roll schema across N tenant DBs. Need a real migration runner. |
| Repo hygiene | SQL dumps, HTML invoices, `check_keys.php`, `Testdb.php`, `Testdata.php`, `Temp_install.php`, `ajax_debug.txt` at web root / in controllers | Publicly reachable test/debug endpoints — remove. |
| Integrations | Many payment gateways (Stripe, Paytm, CCAvenue, PayPal…), SMS, WhatsApp (Meta/Twilio), email (Brevo/SES), AWS S3, biometric, AI | Credentials must be per-tenant; platform keys separate from school keys. |
| Addons | `addons/` (accounts, cbse_examination, multi_branch, whatsapp) is git-ignored | Addons must be versioned and become **plan features**, not files on disk. |
| Cron | Single `Cron.php` controller | Must iterate tenants with isolation, locking and per-tenant failure handling. |

---

## 2. Target Architecture

### 2.1 Tenancy model — Database-per-tenant (recommended)
Rationale: the code already switches DB per request; adding `tenant_id` to 273 tables and every query is unsafe (one missed `WHERE` leaks a school's student data).

```
                ┌──────────────────────────────┐
  *.yourerp.in  │  Edge: Cloudflare (WAF, TLS) │
  custom domains└──────────────┬───────────────┘
                               │
                ┌──────────────▼───────────────┐
                │ App nodes (PHP-FPM + Nginx), │  stateless, N replicas
                │ one codebase for web + API   │
                └───┬───────────┬──────────┬───┘
                    │           │          │
         ┌──────────▼──┐  ┌─────▼────┐ ┌───▼──────────┐
         │ Platform DB │  │  Redis   │ │ Object store │
         │ (control    │  │ sessions │ │ S3/R2, prefix│
         │  plane)     │  │ cache,   │ │ per tenant   │
         └──────┬──────┘  │ queues   │ └──────────────┘
                │         └──────────┘
     ┌──────────▼──────────────────────────┐
     │ Tenant DBs: tenant_0001 … tenant_N  │  (MySQL 8 cluster(s); shard by server)
     └─────────────────────────────────────┘
```

- **Platform (control-plane) DB:** `tenants`, `tenant_domains`, `plans`, `plan_features`, `subscriptions`, `invoices`, `payments`, `usage_counters`, `platform_users`, `audit_log`, `db_servers`, `migration_state`, `feature_flags`.
- **Tenant DB:** today's schema, unchanged. The live Sunrise DB becomes tenant #1 as-is.
- **Branches inside a tenant:** keep the existing `docs/MULTI_BRANCH_IMPLEMENTATION_PLAN.md` design (DB-per-branch) as a *sub-tenant* concept; a branch = additional DB under the same tenant record. Decide before build: branch-as-DB vs branch-as-column — DB-per-branch multiplies DB count fast at SaaS scale. **Recommendation:** for SaaS, keep branches as DBs only on Enterprise plans.

### 2.2 Tenant resolution (every request)
1. Resolve host → `tenant_domains` (subdomain `school.yourerp.in` or verified custom domain). Cache in Redis (TTL 5 min).
2. Unknown/suspended host → marketing/suspended page, never the default DB.
3. Replace `Db_manager` constructor: build the connection config dynamically from the tenant record (no static groups in `database.php`); credentials from secrets manager.
4. **Bind session to tenant**: store `tenant_id` in session; reject if it doesn't match the resolved host (stops session replay across schools).
5. API (`api/`) and mobile apps: tenant from host or a `X-Tenant` + token claim; tokens signed with tenant id.
6. CLI/cron/queue workers: explicit `--tenant=` argument; no implicit default.
7. **Remove the `default` fallback** once all paths are tenant-aware — fail closed.

### 2.3 Per-tenant config
Move into the tenant DB (`sch_settings` already exists) or platform DB: school name/branding, logos, SMS/WhatsApp/email/payment-gateway credentials (encrypted with a per-tenant data key), timezone, currency, language, academic session. Strip all "Sunrise" literals into these.

### 2.4 File storage
- All uploads to S3-compatible storage at `tenants/{tenant_id}/…`; signed URLs for private documents (student docs, marksheets, staff IDs).
- `Media_storage` is the single choke point — enforce prefix there; forbid direct `uploads/` writes (grep and refactor `move_uploaded_file`, `file_put_contents` callers).
- Implement `SaasValidation::validateCanUploadFile/updateStorageLimit` against `usage_counters`.

---

## 3. Product & Commercial Layer

### 3.1 Plans (example — validate with market)
| Plan | Students | Modules | Notable |
|---|---|---|---|
| Starter | ≤300 | Core: students, attendance, fees, exams, communication | Subdomain only, email support |
| Growth | ≤1,500 | + HR/payroll, transport, hostel, library, online admission, PTM, mobile app | Custom domain, WhatsApp |
| Pro / CBSE | ≤5,000 | + CBSE exam addon, accounts addon, store, AI exam/vision, biometric/QR staff attendance | Priority support, SSO |
| Enterprise | Custom | + multi-branch, dedicated DB server, data residency, SLA | Annual contract |

- Pricing basis: per active student/year (Indian market norm) with plan minimum; add-ons for SMS/WhatsApp credits (pass-through + margin).
- Trial: 14–30 days, sample data seeded, auto-convert or read-only lock.

### 3.2 Entitlements engine
- `plan_features` table → `Module_lib`/`Rbac` check `feature_enabled('cbse_exam')` **before** role permissions. Hide sidebar items + block controllers (central `MY_Controller` hook mapping controller → feature).
- Quotas via `ResourceQuota` (students, staff, storage GB, SMS credits, AI calls). Implement `validateCanAddNewResource` at every create path that already calls it; add calls where missing (bulk import, online admission, API).
- Soft-limit warnings at 80/100%, hard block at 110% with grace.

### 3.3 Billing
- Gateway: **Razorpay Subscriptions** (India: UPI AutoPay, e-mandate, GST invoices) + Stripe for international.
- GST-compliant invoices (GSTIN, HSN/SAC 998431/997331, place of supply), credit notes, TDS handling for schools.
- Dunning: day 0 fail → retries → day 7 banner → day 15 read-only → day 45 suspended → day 90 data export offered → day 180 deletion (per contract).
- **Never** reuse school fee gateways for platform billing; keep platform billing in the control plane.

### 3.4 Self-serve onboarding
Signup → email/phone OTP → school profile → plan → tenant provisioned (async job: create DB, run migrations, seed masters, create admin user, DNS record) → setup wizard (sessions, classes, sections, fee masters, import students CSV, branding, gateway keys) → go-live checklist.
Target: provisioned in < 60 s.

### 3.5 Super-admin console (platform side, separate app/subdomain)
Tenants list, health, usage, plan changes, impersonation (**audited, time-boxed, tenant-visible log**), suspend/resume, migration status per tenant, feature flags, announcements, support tickets, revenue dashboards (MRR, churn, ARPA).

---

## 4. Security & Compliance (non-negotiable before tenant #2)

1. **Rotate** the committed production DB password now; purge from git history (`git filter-repo`); move all secrets to env/secrets manager.
2. Set `encryption_key`; re-encrypt anything using `Enc_lib`/`Aes`.
3. `csrf_protection = TRUE` with exclusions only for webhooks/API (verify each webhook by signature).
4. Sessions → Redis, `Secure`, `HttpOnly`, `SameSite=Lax`, regenerate on login, tenant-bound.
5. Delete/block: `check_keys.php`, `Testdb.php`, `Testdata.php`, `Test_stats.php`, `Temp_install.php`, `Migrate*.php` web controllers, `*.sql`, `ajax_debug.txt`, invoice HTML at web root; `.htaccess`/Nginx deny for `backup/`, `temp/`, `addons/*.zip`, `application/logs`.
6. Password hashing audit (bcrypt/argon2), 2FA for admins, login rate-limiting, captcha on public forms.
7. Authorization review: every controller must call `Rbac` — run an automated scan for controllers/methods without permission checks; IDOR review on student/staff ids in URLs.
8. File-upload hardening: MIME sniffing, extension allowlist, no execution in upload paths.
9. Dependency audit: `third_party`/`vendor` (mPDF, PHPMailer, Zend, Paytm SDK) to current versions.
10. **India DPDP Act 2023**: data processing agreement with each school (school = data fiduciary, you = processor), children's data consent flows, breach notification process, data retention policy, grievance officer. Privacy policy + ToS + DPA.
11. Pen test (external) before public launch; repeat annually. Consider ISO 27001 later for enterprise/government schools.
12. Audit log (exists for staff attendance) generalised: who/what/when/tenant for all admin writes and all platform-staff access.

---

## 5. Engineering Foundations

| Item | Action |
|---|---|
| Codebase unification | Merge `api/` into main app (shared models/libraries; API as controller namespace or thin front controller). Delete the duplicate. |
| Config | `.env` (vlucas/phpdotenv) for all environments; no host-sniffing in `database.php`. |
| Migrations | Adopt a real runner (CI migrations or Phinx). Convert `backend/*.sql` and `Migrate*.php` into ordered migrations. Platform tool runs migrations **per tenant** with state in `migration_state`, batched, resumable, canary-first. Rule: backward-compatible (expand → deploy → contract). |
| Addons | Bring `addons/` into git (or a private package), versioned, enabled by entitlement. |
| Background jobs | Redis queue + workers (SMS/WhatsApp/email bulk, PDF report cards, imports, AI). Remove long synchronous loops from controllers. |
| Cron | One scheduler that fans out per tenant, with locks, timeouts, per-tenant error isolation and alerting. |
| Testing | PHPUnit + smoke suite of critical flows (login, admission, fee collect + receipt, attendance, marks entry, report card, PTM report). Tenant-isolation tests: tenant A token/session must never read tenant B. |
| CI/CD | GitHub Actions: lint, PHPStan (level 3→5), tests, security scan, build image → staging → canary tenants → all. |
| Environments | local, staging (anonymised copy), production. No more testing on live. |
| Containerisation | Docker images (PHP-FPM 8.2 + Nginx); deploy on managed k8s or simpler: 2+ VMs behind a load balancer (Hostinger shared hosting cannot run SaaS). |
| PHP 8 compat | Audit deprecations (dynamic properties, `each()`, null-to-string) — CI3.1.13 works on 8.2 with fixes. |
| Performance | Query/index review on hot tables (attendance, fees, marks), Redis cache, OPcache, CDN for `backend/` assets. |
| Observability | Sentry (errors, tagged `tenant_id`), structured logs, uptime + per-tenant metrics (Grafana/Prometheus or a SaaS APM). |

---

## 6. Data, Backup & DR

- Automated daily full + binlog PITR per DB server; nightly per-tenant logical dumps to separate-region object storage; 30-day retention (plan-dependent).
- Monthly restore drill (scripted). RPO ≤ 15 min, RTO ≤ 4 h targets.
- Tenant self-service export (CSV/Excel + documents zip) — also required for offboarding and DPDP.
- Tenant import tool: migrate schools from other ERPs / Excel (students, parents, fee dues, staff) — a key sales enabler.
- Hard tenant deletion workflow with confirmation, grace period, and certificate of deletion.

---

## 7. Operations & Go-to-Market Readiness

- Support: helpdesk (Freshdesk/Zoho), in-app help, knowledge base, onboarding videos; SLA per plan.
- Status page; incident runbooks; on-call.
- Release notes per version; feature flags for gradual rollout.
- White-labelling: logo, colours, domain, email sender domain (SPF/DKIM verification per tenant), SMS sender ID / DLT templates (India TRAI DLT registration per school or platform-level).
- WhatsApp: Meta Business per school vs platform BSP account — decide; platform BSP is simpler to sell.
- Mobile apps: one white-label app with school code login, or per-school builds for Enterprise.
- Legal: company entity, GST registration, ToS, Privacy, DPA, SLA, refund policy; Smart School licence — **confirm the original vendor licence permits SaaS resale** of the derived code (high business risk if not).

---

## 8. Phased Roadmap

| Phase | Weeks | Deliverables | Exit criteria |
|---|---|---|---|
| **0. Emergency hygiene** | 1 | Rotate DB creds, purge secrets from git, remove test/debug endpoints, set encryption key, enable CSRF on staging | No secrets in repo; scanner clean |
| **1. Foundations** | 2–6 | `.env`, migration runner + converted SQL, staging env, Docker, CI pipeline, smoke tests, Sentry, PHP 8.2 compat | Sunrise runs from container on staging identical to prod |
| **2. Tenancy core** | 5–10 | Platform DB, host→tenant resolver, dynamic `Db_manager`, tenant-bound Redis sessions, S3 per-tenant prefix, remove "Sunrise" hardcoding, tenant-aware cron & queues, API unified | Two tenants on staging; isolation test suite green |
| **3. Commercial** | 9–14 | Plans/entitlements, quotas (`SaasValidation`/`ResourceQuota`), Razorpay subscriptions, GST invoices, dunning, signup + provisioning, setup wizard, super-admin console | End-to-end: signup → pay → use → suspend → resume |
| **4. Hardening** | 13–16 | Pen test + fixes, load test (e.g. 50 tenants × 2k students), backup/restore drill, DPDP docs, status page, support stack | Pen test no high/critical; restore drill passes |
| **5. Pilot** | 17–20 | Migrate Sunrise as tenant #1 (zero-change cutover), onboard 3–5 friendly schools | 30 days with no Sev-1, NPS feedback |
| **6. GA** | 21+ | Public launch, marketing site, self-serve, partner/reseller program | — |

Parallelisable: Phase 0 must precede all; within Phase 1–2, migrations/CI and the tenant resolver can run concurrently; billing (Phase 3) can start once the platform DB schema is fixed.

---

## 9. Sunrise Cutover (tenant #1) — zero-regression approach
1. Freeze schema changes 1 week before.
2. Point new infra at a replica of the Sunrise DB; run full smoke suite + compare key reports (fee collection totals, attendance %, marks) byte-for-byte against prod.
3. Copy `uploads/` to S3 under `tenants/1/`; verify checksums.
4. Cutover window (night/holiday): set read-only, final sync, DNS switch, verify, unlock. Rollback = DNS back to old host (keep it warm 2 weeks).

---

## 10. Key Risks
| Risk | Mitigation |
|---|---|
| Cross-tenant data leak | DB-per-tenant, fail-closed resolver, tenant-bound sessions/tokens, isolation tests in CI |
| Migration drift across hundreds of DBs | Single runner, state table, canary rollout, expand/contract rule |
| Licence of Smart School base code | Legal review before selling |
| Duplicated `api/` codebase diverging | Unify in Phase 2 |
| Shared-hosting limits (Hostinger) | Move to VPS/cloud in Phase 1 |
| Noisy-neighbour (bulk SMS, PDF generation) | Queues with per-tenant rate limits |
| CI3 EOL | Pin, harden, plan long-term migration of new modules to a modern framework behind the same DB |

---

## 11. Decisions Needed From Product Owner
1. Tenancy model confirmed as DB-per-tenant? (recommended)
2. Branch model inside SaaS: DB-per-branch (current plan) vs column-scoped — Enterprise-only?
3. Pricing basis and plan/module mapping (§3.1).
4. Hosting target (AWS Mumbai / DigitalOcean BLR / Azure India) and budget.
5. Platform-level vs per-school SMS DLT / WhatsApp BSP.
6. Smart School licence status for resale.
7. Brand/domain for the SaaS product.
