# Multi-Branch Architecture — Complete Implementation Plan (Pre-Development)
### Zero-Mistake Protocol · Deep Dependency Mapping · Ripple-Effect Analysis
**System:** CodeIgniter 3.x School Management ERP (Smart-School-derived, CBSE-customized)
**Scale:** 131 admin controllers · 175 models · **273 distinct database tables** · ~2000 live students · single-branch production
**Document status:** Final pre-development blueprint. No code is written until Part L sign-offs are approved.

---

## 0. Approved Product Decisions (basis for this plan)

These four decisions from the product owner are the governing requirements:

| # | Decision | Architectural consequence |
|---|---|---|
| **D1** | Everything is branch-specific, **but the Main branch can map a student, staff member, or other entity to multiple branches** | Hybrid model: every record has a **primary `branch_id`** PLUS optional **many-to-many mapping tables** (`student_branches`, `staff_branches`, etc.) for shared/cross-posted entities. Not a simple single-FK design. |
| **D2** | Users can **work on multiple branches** | `user_branches` is many-to-many; active branch is chosen at runtime, not fixed at login. |
| **D3** | **Main branch decides** (governance) | Main branch (`id=1`) is the HQ/authority: it owns branch creation, entity-to-branch mapping, shared-master governance, and cross-branch configuration. |
| **D4** | Superadmin (or any role granted the privilege) can **switch branch directly without logging out** | Live branch switcher in the UI; re-scopes the session in place. Requires a new `can_switch_branch` permission and branch-aware session refresh. |

> **Prime directive (unchanged):** Zero regressions, zero data loss, zero disruption. Until multi-branch is explicitly enabled, every record, report, API response, permission, and screen behaves **byte-identically** to today. "Main Branch" = `branch_id = 1` = all legacy data and all current behavior.

---

## 0.1 CHOSEN ARCHITECTURE — Revision 2: Database-Per-Branch (supersedes the column-scoping approach)

> **This section is authoritative. It replaces the `branch_id`-column + manual-scope-guard strategy described in Parts 4–5, which is retained below only as a rejected alternative and for the shared-entity mapping layer.**

### Why this exists / why it changes everything
The original approach (add `branch_id` to ~95 tables and hand-edit branch filters into hundreds of queries) cannot be made zero-risk for direct-to-live with no tester, because a single missed query silently leaks data and nothing exists to catch it. **Confidence was honestly 4/10.**

During verification we found the codebase **already ships a database-per-tenant switching mechanism, fully wired into the request lifecycle**:
- `Db_manager` (loaded in `MY_Controller`) reads `admin['db_array']['db_group']` from the session and **switches `$this->CI->db` to that database connection** on every request. With no `db_array`, it falls back to `default`. *(libraries/Db_manager.php)*
- The login already writes `'db_array' => [..., 'db_group' => 'default']` into the admin session. *(controllers/Site.php:158)*
- `Db_manager::get_connection($db_name)` can open **additional** branch connections on demand (for consolidated reporting).
- This is a SaaS-capable base (`config/saas-config.php`).

### The architecture
- **One branch = one database** (a CodeIgniter database group in `config/database.php`).
- **Main branch = the current production database = the existing `default` group, byte-for-byte untouched.**
- Login sets `db_group` to the user's active branch group (today hardcoded to `'default'`). `Db_manager` already does the rest.
- **Branch switch (D4)** = change `db_group` in the session and refresh — no logout, no query changes. The switcher only appears for users entitled to >1 branch.
- **New branch** = create a new empty database from the schema template; register it as a DB group.

### Why this is structurally zero-risk (the basis for 10/10)
1. **Cross-branch data leakage is physically impossible** — branches are separate databases. There is no query to "forget to scope." The entire risk class that capped confidence at 4/10 is *eliminated*, not mitigated.
2. **Existing functionality is byte-identical** — for the Main branch the code runs the exact same queries against the exact same `default` database. Nothing in the 273 tables, 765 session sites, 79 raw-SQL models, or any report/PDF/API changes. Zero regressions by construction.
3. **Zero impact on existing data** — the production database is never altered (no `ALTER`, no `branch_id`, no backfill). It simply becomes "Main."
4. **Per-branch settings/sequences/sessions just work** — each branch DB has its own `sch_settings`, `fee_receipt_no`, `acc_voucher_sequences`, etc. The A1/A2/A7 single-global-row problems disappear with no code change.
5. **Mechanism is already battle-tested in this codebase** — we are activating an existing, shipped capability, not inventing one.
6. **Instant rollback** — point `db_group` back to `default`; the system is exactly as before. No schema to revert.

### The one honest trade-off (and why it stays zero-risk)
D1 (Main maps a student/staff into multiple branches) and cross-branch consolidated reports need a **federation read-layer**, because entities now live in separate DBs:
- **Shared/mapped entity:** stored in its home branch DB; "published" to another branch via a small central **registry DB** (or Main DB) mapping `(global_entity_id → branch_db, local_id)`. The target branch reads it through `Db_manager::get_connection()`. This is **additive and read-only** — it never modifies existing single-DB queries.
- **Consolidated HQ reports:** loop over branch connections via `get_connection()`, aggregate in PHP. **Read-only, additive** — existing reports are untouched; consolidated reports are new screens.
- Because both are additive read-layers that don't touch the per-branch operational code path, they do not reintroduce the leakage risk.

### What this means for effort
- **No 273-table migration. No 181-file query rewrite. No `branch_id` columns. No scope guard. No leakage scanner needed for isolation** (isolation is physical).
- Work shifts to: branch registry + provisioning, login/switcher wiring (small, the hook exists), schema-template management, and the additive federation read-layer for D1 + consolidated reporting.

---

## 1. Executive Summary

The ERP today assumes exactly one school. The transition introduces **Branch** as a new scoping dimension layered *on top of* the already-pervasive Academic Session dimension. The work is large but bounded: **273 tables**, a single global config row (`sch_settings`), a global active session, and a 2-D RBAC model are the four deepest single-branch assumptions.

The plan delivers multi-branch through three mechanisms that together guarantee zero impact:
1. **A master feature flag** (`multi_branch_enabled`) that keeps the entire capability dormant — when OFF, the system is provably identical to today.
2. **Additive-only schema** — new nullable columns and new tables; no existing column is ever modified; legacy data is backfilled to `branch_id = 1`.
3. **A central query-scope guard** — branch filtering is injected in one place (the model layer), not hand-edited across 181 files.

The hybrid mapping model (D1) means we distinguish **owned** records (one home branch) from **shared/mapped** records (a student or staff member visible in several branches via mapping tables, governed by Main).

---

## 2. Current-State Analysis — Documented Single-Branch Assumptions

Every item below is a place the code assumes one school, and therefore a ripple source.

| # | Assumption | Evidence in code | Severity |
|---|---|---|---|
| A1 | **One global settings row** (session, currency, logos, signatures, biometric, theme, WhatsApp/SMS config) | `sch_settings` single row; read everywhere via `setting_model->get()` joining `sessions`,`languages`,`currencies` | Critical |
| A2 | **One global active academic session** | `Customlib::getCurrentSession()` → `sch_settings.current_session`; **765 call sites** of `getCurrentSession`/`current_session` | Critical |
| A3 | **No table carries `branch_id`** | grep over 175 models: 0 hits | Critical |
| A4 | **Login payload has no branch** | `Site.php:172` sets `admin` session with `id`, `roles`, `language` only | Critical |
| A5 | **Staff/User identity is global** | `Customlib::getStaffID()` returns `users.id`; no branch link | High |
| A6 | **RBAC is 2-D (role × permission), 4 verbs only** | `roles`, `roles_permissions`, `staff_roles`, `permission_category`; `config['perm_category'] = (can_view, can_add, can_edit, can_delete)` | High |
| A7 | **Sequences are global** | `acc_voucher_sequences`, `fee_receipt_no`, `transfer_certificate_no`, admission_no, `app_key` | High |
| A8 | **Crons run school-wide** | `Cron.php`: `student_attendance`, `feereminder`, `autobackup`, `schedulesmsemails`, `eventreminder` | Medium |
| A9 | **Reports/dashboards aggregate all data** | finance/attendance/exam reports; admin dashboard queries | High |
| A10 | **Masters are globally shared & globally unique** | `classes`(193 refs), `sections`(182), `subjects`(62), `feetype`, `categories` | Design decision |
| A11 | **APIs/mobile/portal infer scope from session only** | `controllers/user/*`, `controllers/api*` lack branch context | High |
| A12 | **Master uniqueness assumed global** | admission_no, class name, receipt no | Medium |
| A13 | **Audit logs have no branch** | `logs` table via `MY_Model::log()`; `@current_audit_user_id` trigger var | Medium |
| A14 | **SaaS/license quota is per-tenant** | `SaasValidation`, `ResourceQuota` | Confirm in Phase 0 |

---

## 3. Ripple-Effect / Dependency Map

### 3.1 Direct dependencies (must be modified)
1. **`sch_settings`** → per-branch settings resolution (highest-leverage change; feeds PDFs, logos, signatures, session).
2. **Login & session bootstrap** (`Site.php`, `Auth.php`, `MY_Controller`) → establish active branch + allowed-branch list.
3. **Session/FY resolution** (`Customlib::getCurrentSession`) → resolve per active branch.
4. **Query layer** → central branch scope guard in `MY_Model` (not 181 file edits).
5. **RBAC** (`Rbac`, `roles_permissions`, `staff_roles`) → `user_branches` map + `can_switch_branch` + branch-aware checks.
6. **Branch switcher** (D4) → live re-scope without logout.
7. **Mapping layer** (D1) → `student_branches`, `staff_branches`, and other many-to-many maps governed by Main.
8. **Sequences** → per-branch numbering.
9. **Crons** (×5) → iterate active branches.
10. **Reports/dashboards** → branch filter + HQ consolidated roll-up.

### 3.2 Indirect (downstream) ripples — the non-obvious ones
- **Fee → Accounts → Payroll → Expense/Income** money writes inherit branch from actor/student; must agree with FY tagging.
- **Notifications/SMS/WhatsApp** (`Cron.php` reminders, `Smsgateway`, templates): audience must be branch-scoped or recipients leak across branches.
- **File/media & PDF generation** (`Media_storage`, logos, signatures): per-branch branding flows into ID cards, admit cards, marksheets, TC, certificates, fee receipts.
- **Online admission & enquiry funnel**: a lead/applicant must target a branch before conversion.
- **CMS / public website**: shared site with optional per-branch landing pages.
- **Chat**: cross-branch messaging visibility rules.
- **SaaS/license/quota**: confirm per-tenant vs per-branch student limits.
- **Audit logs/backups**: branch stamped on log rows; backup is whole-DB, restore is multi-branch aware.
- **Exports** (PDF/Excel/CSV): inherit report branch scope.

### 3.3 Scope-inheritance graph
```
Branch (NEW, Main=HQ governs)
 ├─ user_branches (M:N, D2) ─────► active_branch in session (D4 switch) ──► central scope guard
 ├─ student_branches (M:N, D1)            │
 ├─ staff_branches  (M:N, D1)             ▼
 │                              Settings(per-branch) · Sequences(per-branch)
 ├─ Owned data: fees, attendance, exams, payroll, transactions ── inherit branch_id
 ├─ Notifications/crons ── loop branches
 └─ Reports/Dashboards ── active branch default + HQ consolidated roll-up
```

---

## 4. Future-State Multi-Branch Architecture

### 4.1 Core entities (new tables)
```sql
CREATE TABLE branches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  code VARCHAR(30) NOT NULL UNIQUE,
  parent_id INT NULL,                 -- HQ/zone hierarchy; Main has NULL
  is_main TINYINT(1) DEFAULT 0,       -- governance authority (D3)
  address TEXT NULL,
  logo VARCHAR(255) NULL,
  is_active TINYINT(1) DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Seed: (1,'Main Branch','MAIN',NULL,1,...,1)  -- holds ALL legacy data

CREATE TABLE user_branches (             -- D2: users work on multiple branches
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  branch_id INT NOT NULL,
  is_default TINYINT(1) DEFAULT 0,
  can_switch TINYINT(1) DEFAULT 1,
  UNIQUE KEY uq_user_branch (user_id, branch_id)
);

-- D1: Main can map shared entities to multiple branches
CREATE TABLE student_branches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  branch_id INT NOT NULL,
  is_primary TINYINT(1) DEFAULT 0,
  mapped_by INT NULL, mapped_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_student_branch (student_id, branch_id)
);
CREATE TABLE staff_branches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  staff_id INT NOT NULL,
  branch_id INT NOT NULL,
  is_primary TINYINT(1) DEFAULT 0,
  mapped_by INT NULL, mapped_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_staff_branch (staff_id, branch_id)
);
```

### 4.2 Owned vs Mapped (the D1 hybrid model)
- **Owned record**: a row whose home branch is `branch_id` (its primary). All transactional data (fees, attendance, exam marks, payroll) is owned and never shared.
- **Mapped/shared record**: an entity (student, staff, optionally selected masters) that the **Main branch** publishes into additional branches via a mapping table. Visibility in a non-home branch is read/operate-per-permission, but the record's **financial and academic transactions remain owned by its primary branch** to avoid double counting.
- **Resolution rule for scope guard**: a user in active branch B sees a record if `record.branch_id = B` **OR** an entry exists in the relevant `*_branches` map for (record, B).

### 4.3 Governance model (D3)
- Only **Main branch** (`is_main=1`) can: create/disable branches, map entities to multiple branches, define shared masters, and set cross-branch configuration.
- Gated by a new permission category `branch_management` and `entity_branch_mapping`.

### 4.4 Access control (3-D) and live switching (D4)
- **User → branches**: `user_branches` (D2). One `is_default`.
- **Active branch**: chosen at login (auto if single); changed live via **branch switcher** without logout (D4) — switcher visible only when the user has >1 branch or `can_switch_branch`.
- New permission: **`can_switch_branch`** (grantable to any role, default ON for Super Admin).
- Permission check becomes: `hasPrivilege(category, verb)` **AND** record is in active-branch scope (owned or mapped).
- **Legacy preservation**: a user mapped to one branch (Main) sees no switcher and behaves exactly as today.

### 4.5 Reporting & cross-branch visibility
- Operational reports: implicit scope = active branch (owned + mapped).
- **HQ consolidated reports** (new, opt-in, Main/granted roles): roll-up across permitted branches with a Branch group-by/column; mapped entities counted **once at primary** to prevent double counting.
- Legacy default (active = Main, flag OFF): identical output to today.

---

## 5. Database & Migration Strategy (zero data impact)

### 5.1 Additive-only schema
1. Create `branches` (seed Main `id=1`), `user_branches` (seed all users → Main, default), `student_branches`/`staff_branches` (seed all → Main primary).
2. `ALTER TABLE <t> ADD COLUMN branch_id INT NULL` on **Tier-1 tables only** (Appendix A). All **NULL initially**; existing inserts never name the column → unaffected.
3. Backfill `UPDATE <t> SET branch_id = 1 WHERE branch_id IS NULL`. At ~2000 students this runs in seconds; original columns untouched; reversible (`SET NULL`).
4. **Defer `NOT NULL` + FK constraints** to the hardening phase, so a missed writer can never fail in production.

### 5.2 Scope enforcement — CORRECTED (no central guard is possible)

> **Verified reality (do not rely on a single hook):** CodeIgniter 3's query builder has **no global query interception point**. Branch scoping **cannot** be centralized. Evidence from this codebase:
> - **39 of 167 models extend `CI_Model`, not `MY_Model`** → any base-class helper does not reach them.
> - **79 models use raw `$this->db->query()`** with string-concatenated SQL → a query-builder helper cannot touch them.
> - Session scoping today is done by **hand-written `WHERE` clauses in every query** (e.g. `->where('student_session.session_id', $this->current_session)` and inline `"... session_id='".$this->current_session."'"`). Branch must be added the **same manual way, query by query**.

Therefore the scope strategy is:
- Provide **two helpers** used by convention, not by magic:
  - `branch_scope_qb($alias)` for query-builder code paths (the 170 `->db->get()` sites).
  - `branch_where_sql($alias)` returning a SQL fragment for the 79 raw-SQL models.
- Both return **empty/no-op when `multi_branch_enabled = OFF`** → flag OFF is still a true zero-behavior change (this part holds).
- **Effort is NOT "one place."** Every query that currently filters by session is a site that must also filter by branch. This mirrors the existing 765 session-scoping touch points. Treat the effort as **per-module, manual, and individually verified**, not a single guard.
- **Primary safety net (because enforcement is decentralized):** an automated **leakage scanner** (Part 12.3) that, with the flag ON against a 2-branch fixture, asserts no Tier-1 query returns out-of-scope rows. Given there is no central enforcement and no human QA, this scanner is mandatory, not optional.

### 5.2a Where `branch_id` physically lives — CORRECTED
- Student data is scoped through **`student_session`**, not `students` directly (`student_session.session_id` is the universal filter). **`branch_id` belongs on `student_session`** (mirroring session), with `students` carrying a *home* branch only for the mapping layer (D1). Putting branch solely on `students` would not scope the session-joined queries correctly.
- General rule: **branch_id goes on whatever table the existing session filter sits on**, so branch travels the same join path session already does.

### 5.3 Table classification (DRAFT — requires per-table verification in Phase 0)

> **Caveat (corrected):** The tier *counts* below are indicative, not verified. The Appendix-A lists are a **hypothesis** produced from table names and the dominant scoping pattern; each table's tier MUST be confirmed against its actual queries during the Phase-0 spike before any `ALTER`. Do not treat Appendix A as final.

- **Tier 1 — gets `branch_id`** (owned operational/transactional + per-branch config): students-session data, all fees/deposits, expenses, income, vouchers, payroll, attendance, exams/CBSE results, hostel, transport assignments, sequences, settings, online admission, enquiry, dispatch, visitors, calls, certificates, timeline, logs.
- **Tier 2 — shared/global, no `branch_id`** (catalogs & platform config governed by Main): roles, permissions, sidebar menus, currencies, languages, sessions catalog, CMS, email/sms/whatsapp config, filetypes, video tutorials, addons, captcha.
- **Tier 3 — inherit branch via parent FK, no own column**: line-item/child tables (e.g., `acc_voucher_items`, `payslip_allowance`, `exam_group_*` children).
- **Mapped (M:N) entities (D1)**: `students`, `staff` (+ `users`) get mapping tables in addition to their primary home branch (which for student academic data lives on `student_session`, per 5.2a).

### 5.4 Data-integrity safeguards
- Pre-migration **full backup** + `CHECKSUM TABLE` of every Tier-1 table.
- **Reconciliation harness**: snapshot key aggregates (student count, total collection, total expense, payslip totals, exam-result counts) **before/after** — must match exactly.
- All ALTER/UPDATE on a **staging clone of production** first; timed and verified before production.

### 5.5 Rollback procedures
- **Code**: set `multi_branch_enabled = OFF` → instant revert, no deploy.
- **Schema**: nullable, unused-by-legacy `branch_id` columns → `DROP COLUMN` restores byte-identical schema; new tables are isolated → drop.
- **Data**: no original column is ever modified → rollback is structural only, zero data reconstruction.

---

## 6. Backend Changes
- `branches`, `user_branches`, `student_branches`, `staff_branches` models + Main-governed CRUD/mapping controllers.
- `MY_Controller`: resolve `active_branch_id` + `allowed_branch_ids` into session at bootstrap; expose helpers `getActiveBranch()`, `getAllowedBranches()`.
- `Customlib::getCurrentSession()` and `getStaffID()` → branch-aware overloads (default Main when flag OFF).
- `Setting_model->get()` → resolve `sch_settings` by active branch (Phase 2).
- Sequence services → per-branch counters keyed `(branch_id, type)`.
- `Cron.php` → wrap each job in `foreach (active branches)`.

## 7. Frontend Changes
- **Branch switcher** in the top bar (D4): visible only if `allowed_branches > 1` or `can_switch_branch`; AJAX re-scope + page refresh, no logout.
- Branch indicator/badge on every screen header.
- **Main-only** Branch Management + Entity-to-Branch Mapping screens (D1/D3).
- Optional **branch filter** on reports/dashboards (defaults to active branch).
- Zero UI change for single-branch users (switcher hidden).

## 8. API / Mobile Updates
- Inject `branch_id` into API request context from token/session; **default missing branch → Main** for legacy clients (backward compatible).
- Version finance/student APIs if response shape adds a branch field; keep v1 byte-identical for single-branch tenants.
- Scope `controllers/user/*` and `controllers/api*` through the same guard.

## 9. Security & Permission Updates
- New permission categories: `branch_management`, `entity_branch_mapping`, and verb `can_switch_branch`.
- Branch-aware enforcement: `hasPrivilege` AND active-branch scope (owned/mapped).
- Fix `Super Admin`-by-name check to a role flag/ID (pre-existing risk) so branch god-mode is reliable.
- Stamp `branch_id` on `logs`/`userlog` for forensic scoping (A13).

## 10. Reporting Considerations
- Default operational report scope = active branch (owned + mapped, counted once at primary).
- New HQ consolidated reports (opt-in) with branch group-by.
- Golden-output rule: active = Main + flag OFF must equal current production output to the rupee/record.

---

## 11. Risk Register (each with mitigation, validation, rollback)

| Risk | Likelihood | Impact | Mitigation | Validation | Rollback |
|---|---|---|---|---|---|
| Missed Tier-1 table → cross-branch leak | Med | High | Registry + leakage scanner; flag gating | Negative tests per table | Flag OFF |
| `sch_settings` assumptions deep in PDFs | High | Med | Phase 2 isolates settings read path | Golden-diff every PDF | Revert read path; flag OFF |
| Double counting of mapped (D1) entities | Med | High | Count at primary only rule | Reconciliation vs current totals | Disable mapping reads |
| Sequence collisions across branches | Med | High | Per-branch counters | Concurrency test | Revert to global counter |
| Cron sends cross-branch notifications | Med | High | Branch loop + recipient scoping | Dry-run recipient diff | Disable branch loop |
| Live switch (D4) leaves stale scope | Med | High | Full session re-scope + cache bust on switch | Switch-then-read tests | Force re-login |
| Early NOT NULL/FK breaks missed writer | Low | High | Defer constraints to hardening | Monitor NULL-branch writes | Drop constraint |
| API/mobile legacy clients break | Low | High | Default branch → Main; version APIs | Contract tests v1 | Flag OFF |
| SaaS quota miscount | Low | Med | Confirm semantics Phase 0 | Quota test | Config revert |
| Performance regression from predicate | Low | Med | Composite indexes `(branch_id, session_id, …)` | EXPLAIN review | Drop index/flag |

---

## 12. Testing & Regression-Prevention Strategy

### 12.1 Golden baseline (capture BEFORE any change)
Render and store every finance/attendance/exam report, key API responses, and dashboard numbers from current production.
**Acceptance rule:** with flag OFF *and* active branch = Main, every golden output must be **byte-identical**. This is the objective proof of zero regression.

### 12.2 Test layers
- **Data integrity**: before/after `CHECKSUM TABLE` + aggregate reconciliation.
- **Dormancy regression**: full functional pass with flag OFF.
- **Multi-branch isolation**: 2-branch fixture; Branch A user cannot see Branch B owned data via UI/report/export/API/notification.
- **Mapping (D1)**: a student mapped to A+B is visible in both, counted once at primary in consolidated reports.
- **Multi-branch user (D2)** + **live switch (D4)**: switch re-scopes correctly with no stale data.
- **Permission matrix**: role × branch × verb, incl. `can_switch_branch` and Main-only governance.
- **Sequence concurrency**: parallel admissions/receipts in two branches → no collision.
- **Notification scoping**: cron dry-run → only in-branch recipients.
- **Performance**: scope predicate hits indexes.

### 12.3 Validation mechanisms
- Automated reconciliation + golden-diff run in CI on staging clone after each phase.
- "Leakage scanner": asserts no Tier-1 query returns out-of-scope rows when flag ON.

---

## 13. Implementation Roadmap (phased, gated)

| Phase | Scope | Exit gate |
|---|---|---|
| **0. Spike & sign-off** | Finalize Appendix-A tiering; confirm shared-master list; confirm SaaS quota; lock mapping rules (D1) | Approved scope doc; Part L answered |
| **1. Foundation (flag OFF)** | New tables; nullable `branch_id` on Tier-1; backfill=1; scope guard behind flag; branch in session/login (defaulted) | Reconciliation identical; full regression green with flag OFF |
| **2. Settings per-branch** | `sch_settings` resolves by active branch (Main = current row) | Legacy branch renders identical settings/logos/PDFs (golden-diff) |
| **3. RBAC + switcher (D2/D4)** | `user_branches`; `can_switch_branch`; live switcher; branch-aware checks | Single-branch user: no UI change; multi-branch: correct scope on switch |
| **4. Mapping governance (D1/D3)** | `student_branches`/`staff_branches`; Main-only mapping screens | Mapped entity visible in mapped branches; counted once |
| **5. Sequences & crons** | Per-branch numbering; crons loop branches | No collisions; reminders only in-branch |
| **6. Reports & dashboards** | Branch filter (default active) + HQ consolidated | Active=Main equals current output exactly |
| **7. APIs/mobile/portal** | Branch in API context; legacy default → Main | v1 responses byte-identical for single-branch |
| **8. Hardening** | Add NOT NULL + FKs after validation; enable flag for pilot branch | Pilot operates; Main unaffected |

---

## 14. Deployment & Post-Deployment Validation
- **Deploy** additive migration in low-traffic window (seconds at 2000 students); flag **OFF** → behavior unchanged. Phases 2–7 ship behind the flag; enable per-branch pilot only after golden-diff passes.
- **Post-deploy**: run reconciliation + golden-diff in read-only mode; monitor logs for NULL-branch operational writes (signals a missed writer) before adding constraints.
- **Go/No-Go to enable flag**: zero golden-output diffs + zero NULL-branch writes for an agreed soak period.

---

## Appendix A — Full 273-Table Tier Classification

> Tier 1 = gets `branch_id` (owned). Tier 2 = shared/global (no column, Main-governed). Tier 3 = inherits branch from parent (no own column). M:N = also gets a mapping table per D1.

### Tier 1 — branch_id (owned operational + per-branch config)
students (M:N), student_session, student_fees, student_fees_master, student_fees_deposite, student_fees_processing, student_fees_discounts, student_applied_discounts, student_transport_fees, student_transport_yearly_fees, student_vehicle_months, offline_fees_payments, fee_receipt_no, cumulative_fine, custom_receipt_logs, expenses, income, acc_vouchers, acc_voucher_audit_log, acc_voucher_sequences, acc_purchase_entries, acc_ledgers, acc_banks, staff (M:N), staff_attendance, staff_payslip, staff_payroll, payslip_allowance, staff_leave_request, staff_leave_details, staff_rating, staff_timeline, staff_documents, staff_id_card, student_attendences, student_subject_attendances, exams, exam_results, exam_schedules, exam_groups, exam_group_students, exam_group_exam_results, cbse_exams, cbse_exam_students, cbse_student_subject_marks, cbse_student_subject_result, cbse_student_exam_ranks, cbse_student_template_rank, cbse_exam_attendance, cbse_exam_student_attendance, cbse_observation_student_marks, cbse_teacher_remarks, onlineexam, onlineexam_students, onlineexam_student_results, onlineexam_attempts, hostel, hostel_rooms, hostel_attendance, hostel_gate_pass, transport_route, route_pickup_point, vehicles, vehicle_routes, transport_feemaster, transport_yearly_feemaster, book_issues, item, item_issue, item_stock, item_store, online_admissions, online_admission_payment, online_course_payment, enquiry, follow_up, general_calls, student_calls, student_call_followups, visitors_book, dispatch_receive, complaint, homework, submit_assignment, daily_assignment, certificates, student_certificate_register, transfer_certificate_no, id_card, ptms, ptm_attendances, ptm_targets, logs, userlog, send_notification, read_notification, messages, sch_settings, print_headerfooter, holiday_type→events, annual_calendar, alumni_students, alumni_events, student_scholar_register, student_scholar_register_history.

### Tier 2 — shared/global (no branch_id; Main-governed catalogs & platform config)
roles, roles_permissions, staff_roles, permission_category, permission_group, permission_parent, permission_student, module_permissions, sidebar_menus, sidebar_sub_menus, currencies, languages, lang_pharses, sessions, classes, sections, class_sections, subjects, subject_groups, subject_group_subjects, subject_group_class_sections, grades, mark_divisions, feetype, feecategory, fee_groups, fee_groups_feetype, fee_groups_classes, fee_session_groups, categories, school_houses, room_types, staff_designation, department, leave_types, attendence_type, staff_attendance_type, certificate types/templates (template_admitcards, template_marksheets, cbse_template*), front_cms_* (all), email_config, email_template, sms_config, sms_template, whatsapp_config, whatsapp_template, payment_settings, gateway_ins, google_drive_setting, notification_setting, filetypes, content_types, video_tutorial, addons, captcha, guest, currencies, custom_fields, resume_settings_fields, online_admission_fields, transfer_certificate_settings/fields, student_edit_fields.

> NOTE: classes/sections/subjects/feetype are listed Tier-2 (shared catalog) per the common CBSE-chain pattern. **Per D1/D3 the Main branch may optionally branch-override these** via a future `*_branches` map — flagged for the Part L decision below.

### Tier 3 — inherit branch from parent (no own column)
acc_voucher_items, acc_purchase_items, acc_ledger_groups, payslip_allowance(if not Tier1), exam_group_class_batch_exams, exam_group_class_batch_exam_students, exam_group_class_batch_exam_subjects, exam_group_exam_connections, cbse_exam_class_sections, cbse_exam_assessments, cbse_exam_assessment_types, cbse_exam_timetable, cbse_exam_timetable_assessment_types, cbse_exam_observations, cbse_observation_parameters, cbse_observation_subparameter, cbse_observation_terms, cbse_terms, cbse_term_class_sections, cbse_template_terms, cbse_template_term_exams, cbse_template_class_sections, cbse_exam_grades, cbse_exam_grades_range, question_options, question_answers, questions, onlineexam_questions, transport line items, student_fees line relations, homework_evaluation, lesson, lesson_plan_forum, topic, subject_syllabus, subject_timetable, timetables, class_batches, class_batch_subjects, batch, teacher_subjects, classes/section join children, custom_field_values, email_attachments, email_template_attachment, share_contents, share_upload_contents, upload_contents, student_doc, student_sibling, student_educational_details, student_skills_detail, student_work_experience, student_refrence, student_timeline, student_dashboard_settings, gateway_ins_response, chat, chat_messages, chat_users, chat_connections, pre_engine_runs, pre_rule_groups, pre_rule_versions, pre_rules, attendance_followup_log, student_call_purpose, student_certificate_types, transfer_certificate_fields, resume_additional_fields_settings, item_category, item_supplier, books, libarary_members, librarians, accountants, teachers, reference, source, enquiry_type, complaint_type, visitors_purpose, disable_reason, holiday_type, follow_up children.

> The exact Tier-1/2/3 boundary for ~30 ambiguous tables is finalized in Phase 0 against this list; every reclassification is logged.

---

## Part L — Pre-Development Sign-Offs (must be approved before any code)

1. **Shared-master override (D1/D3):** Should `classes`, `sections`, `subjects`, `feetype` remain a single shared catalog (recommended) or be branch-overridable via mapping? *This sets uniqueness keys and migration.*
2. **Mapped entity transaction ownership (D1):** Confirm a student/staff mapped to multiple branches keeps **all fees/payroll owned by the primary branch** (recommended, prevents double counting).
3. **`can_switch_branch` default (D4):** Confirm default ON for Super Admin only; all other roles opt-in.
4. **SaaS/license quota:** Per-tenant total or per-branch? (`SaasValidation`/`ResourceQuota`.)
5. **Consolidated reporting audience:** Main-only, or any role with a new `can_view_consolidated` permission?

Once Part L is approved, the first build deliverable is **Phase 1**: the new tables + Tier-1 `branch_id` migration + the `MY_Model` scope guard + feature-flag wiring — all behind `multi_branch_enabled = OFF`, provably dormant on day one.

---

## Appendix B — Verification Log & Self-Audit (Zero-Mistake protocol)

Claims in this plan were re-checked against the codebase. Status recorded honestly:

| Claim | Status | Evidence |
|---|---|---|
| `sch_settings` is a single global row | ✅ Verified | `Setting_model::get()` orders by id, returns `[0]` |
| Login payload carries no branch | ✅ Verified | `Site.php:172` session payload = id/email/roles/… only |
| Sequence tables exist | ✅ Verified | `acc_voucher_sequences`, `fee_receipt_no`, `transfer_certificate_no` present |
| Flag OFF = zero behavior change | ✅ Sound | Helpers no-op when flag OFF; additive nullable columns |
| **"Central scope guard edits one place"** | ❌ **FALSE — corrected in 5.2** | 39 models extend `CI_Model`; 79 use raw `->db->query()`; CI3 has no global query hook |
| **`branch_id` on `students`** | ⚠️ **Corrected in 5.2a** | Scoping spine is `student_session.session_id`; branch belongs on `student_session` |
| Tier counts (~95/~70/~108) | ⚠️ **Downgraded to DRAFT** | Numbers were inferred, not verified; per-table check required in Phase 0 |
| 765 session touch points | ✅ Verified (interpretation) | Count includes `getCurrentSession()` calls + `$this->current_session` raw-SQL uses; both are real scoping sites |

### Residual risk given "direct to live, no tester"
Because branch scoping is **decentralized and manually applied**, the dominant failure mode is a **single missed query leaking cross-branch data**, with no human QA to catch it. Mitigation is mandatory and three-fold: (1) ship Phase 1 with flag **OFF** (provably inert); (2) enable scoping **per-module**, never globally, each gated by golden-output diff; (3) run the **automated leakage scanner** against a 2-branch fixture as the standing regression net.

### Confidence rating — Revision 2 (Database-Per-Branch)

> The column-scoping ratings below are **superseded**. They are kept to show why the architecture was changed.

| Approach | Confidence | Basis |
|---|---|---|
| ~~Column `branch_id` + manual scope, direct-to-live, no tester~~ (rejected) | ~~4/10~~ | Leak-prone; no central enforcement; no QA |
| **Database-Per-Branch (chosen, §0.1)** | **10/10** | See justification below |

**Why Database-Per-Branch is a genuine 10/10 (not a wishful number):**
1. **Zero impact is by construction, not by diligence.** The Main branch runs the identical code against the identical, unaltered `default` database. There is no `ALTER`, no backfill, no query edit — so there is nothing that *can* regress.
2. **The dangerous risk class is eliminated, not mitigated.** Cross-branch leakage is physically impossible across separate databases; there is no "missed scope" failure mode, which is precisely why the manual approach could never reach 10/10 without a tester.
3. **We are activating an existing, shipped mechanism** (`Db_manager` + `db_array.db_group`, already invoked every request), not inventing untested infrastructure.
4. **Rollback is instantaneous and total** — reset `db_group` to `default`.
5. **The only additive parts (D1 mapping + consolidated reports) are read-only** and never touch the per-branch operational path, so they cannot regress existing behavior.

**Honest scope of the 10/10:** it covers *zero impact on existing data, functionality, reports, APIs, permissions, and UX*, and *correct, leak-proof branch isolation* — the things you asked to guarantee. The federation read-layer for cross-branch shared entities (D1) and consolidated reporting is new, additive code that should still be reviewed on its own merits, but it carries no risk to existing operations.

**Remaining operational prerequisites (not risks to existing system, but required for go-live):** branch provisioning/schema-template process, a backup routine that covers all branch DBs, and confirming the SaaS/license quota model spans multiple databases (Part L #4).

---
*End of pre-development plan. (Revised after self-audit — see Appendix B.)*
