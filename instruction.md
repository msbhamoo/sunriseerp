# Development Instructions

This document defines the required engineering rules for building new features and modifying existing features in this School Management System (Sunrise School ERP). Follow it for every core module change, add-on, database change, fee-related workflow, and student-facing feature.

**Last Updated:** 2026-06-01

---

## 1. Architecture Rules

The application is a CodeIgniter/PHP monolith using MVC.

Core locations:

- Controllers: `application/controllers`
- Models: `application/models`
- Views: `application/views`
- Libraries: `application/libraries`
- Helpers: `application/helpers`
- Core base classes: `application/core`
- Add-on packages: `addon` or `addons`
- Language files: `application/language/English/app_files/system_lang.php`

Use the existing CodeIgniter patterns. Do not introduce a new framework, routing system, ORM, or unrelated architectural style.

Admin features must extend `Admin_Controller`.

Student panel features must extend `Student_Controller`.

Public features must extend the appropriate public/front controller already used in the codebase.

Accounts addon controllers must extend `MY_Addon_AccountsController`.

All database access must go through CodeIgniter Query Builder or escaped raw SQL using `$this->db->escape()`.

Do not concatenate untrusted input into SQL.

## 2. Academic Session Rules

Academic session is the primary data boundary.

Current session must be read using:

```php
$current_session = $this->setting_model->getCurrentSession();
```

Student academic data must not be queried from `students` alone. Always join through `student_session`.

Required student academic filter:

```php
$this->db->join('student_session', 'student_session.student_id = students.id');
$this->db->where('student_session.session_id', $current_session);
```

For student-specific academic records, prefer storing:

```text
student_session_id
```

For session-wide records, store:

```text
session_id
```

Never mix data across sessions unless the feature is explicitly a historical report and the user has selected a session.

Historical reports must accept an explicit `session_id`. They must not silently use all sessions.

## 3. Student Lifecycle Rules

`students.id` is the student identity.

`student_session.id` is the student's academic enrollment for a specific academic session, class, and section.

New features related to attendance, exams, homework, transport, hostel, fees, discipline, certificates, reports, or class activities must reference `student_session_id` unless there is a strong reason not to.

Do not use only `student_id` for academic records that vary by year, class, section, route, exam, attendance, or fee cycle.

Student status rules:

- Active student: `students.is_active = 'yes'`
- Disabled/dropped student: `students.is_active = 'no'`
- Alumni: `student_session.is_alumni = 1` and related `alumni_students` record

Do not delete student records for normal workflows. Use status fields.

## 4. RBAC Rules

Every admin controller method must be protected by RBAC unless it is an internal helper that cannot be routed directly.

Use:

```php
if (!$this->rbac->hasPrivilege('permission_short_code', 'can_view')) {
    access_denied();
}
```

Use the correct action:

- `can_view` for list/view/report pages
- `can_add` for create
- `can_edit` for update
- `can_delete` for delete/disable

Register new permissions in:

- `permission_group`
- `permission_category`
- `roles_permissions`
- `sidebar_menus`
- `sidebar_sub_menus`

### 4.1. Permission Group ID Registry & Conflict Prevention

To prevent `id` collisions across different environments (e.g., local vs. live) and addons (like Accounts vs. CBSE Examination), **never hardcode arbitrary IDs** for permission groups without checking the registry.

**Current Known `permission_group` IDs:**
- `1` to `31`: Core Base Modules (Student Information, Fees Collection, Income, Expense, etc.)
- `900`: Accounts Addon (`accounts`)
- `1001`: Payroll Rules Addon (`payroll_rules`)
- `1002`: CBSE Examination Addon (`cbseexam`)

**Rules for Adding New Permission Groups:**
1. **Use High IDs:** When creating a completely new permission group for a custom module or addon, assign an ID in the **10000+** range (e.g., 10001, 10002) to avoid future conflicts with official Smart School updates.
2. **Update Registry:** Always update the registry above when a new group is finalized.
3. **Safe Inserts:** Use `INSERT IGNORE` or `ON DUPLICATE KEY UPDATE` carefully. Do NOT use `ON DUPLICATE KEY UPDATE name = VALUES(name)` if there's a risk the ID belongs to a completely different module on the live database.

### 4.2. Preventing Missing Permissions in Menus

When adding new sub-menus, they will **fail to appear** in the sidebar unless correctly mapped in all tables:
1. **`permission_category`**: The sub-menu must have a valid `perm_group_id` linking it to its parent group. If `perm_group_id` is missing or incorrect, it won't appear on the Assign Permissions page.
2. **`sidebar_sub_menus`**: The `access_permissions` string MUST strictly follow the exact tuple format expected by the RBAC parser.
   - **Correct:** `('student_dashboard', 'can_view')`
   - **Incorrect:** `student_dashboard`
3. If the permissions aren't fully registered in `permission_category` and properly formatted in `sidebar_sub_menus`, the UI will silently hide the menu items.

Sidebar visibility must match route protection. Do not expose a menu link whose controller method uses a different permission.

Super Admin may bypass permissions through existing RBAC behavior, but all other roles must be explicitly granted.

## 5. Sidebar, Menu, and Active State Rules

Every new admin page that appears in the sidebar must be registered in both the database sidebar tables and the menu activation helper.

The dynamic sidebar is rendered from:

```text
sidebar_menus
sidebar_sub_menus
```

The parent menu active/open state is controlled by:

```php
activate_main_menu($side_list_value->activate_menu)
```

The submenu active state is controlled by:

```php
activate_submenu($submenu_value->activate_controller, explode(',', $submenu_value->activate_methods))
```

These functions live in:

```text
application/helpers/menu_helper.php
```

When adding a new sidebar submenu, update `sidebar_sub_menus` with the correct:

```text
sidebar_menu_id
menu
key
lang_key
url
level
access_permissions
activate_controller
activate_methods
is_active
```

The `url` must match the route:

```text
admin/transport/dashboard
```

The `activate_controller` must match the actual router controller class in lowercase:

```text
transport
```

The `activate_methods` value must include the routed method:

```text
dashboard
```

If one submenu should stay active for multiple methods, use comma-separated methods:

```text
dashboard,index,edit
```

The parent menu must also be updated in `main_menu_array()` inside `application/helpers/menu_helper.php`.

Example for Transport:

```php
'transport' => array(
    'transport'   => array('dashboard', 'feemaster', 'feetype', 'fee_report', 'fee_defaulters'),
    'pickuppoint' => array('index', 'assign', 'student_fees'),
    'route'       => array('index', 'edit'),
    'vehicle'     => array('index', 'maintenance'),
    'vehroute'    => array('index', 'edit'),
),
```

If this helper map is not updated, the submenu may be active but the parent menu can still collapse because the parent `treeview` does not receive the `active` class.

Every controller method that renders a sidebar page should also set the session menu keys using the same route-style strings used by the sidebar:

```php
$this->session->set_userdata('top_menu', 'Transport');
$this->session->set_userdata('sub_menu', 'transport/dashboard');
```

For dynamic sidebar pages, the database `activate_controller` and `activate_methods` are more important than `sub_menu`, but set both for compatibility with older/static menu helpers.

New language keys for sidebar labels and page titles must be added in:

```text
application/language/English/app_files/system_lang.php
```

Before approving a new menu or submenu, verify:

- The row exists in `sidebar_sub_menus`.
- `url` opens the correct page.
- `activate_controller` equals `$this->router->fetch_class()`.
- `activate_methods` includes `$this->router->fetch_method()`.
- `sidebar_menus.activate_menu` exists in `main_menu_array()`.
- The controller method sets matching `top_menu` and `sub_menu`.
- The parent menu remains expanded after clicking the submenu.
- The clicked submenu has the `active` class.
- Language key exists in `system_lang.php`.

Do not add sidebar links only in views. Sidebar registration must be database-driven and helper-aware.

## 6. Database Design Rules

Table names must use lowercase snake_case.

Column names must use lowercase snake_case.

Every new table should include:

```sql
id INT NOT NULL AUTO_INCREMENT,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
```

For admin-created records, include:

```sql
created_by INT NULL
```

For academic records, include one of:

```sql
session_id INT NOT NULL
student_session_id INT NOT NULL
```

Use additive migrations. Do not remove, rename, or repurpose existing columns used by historical records.

**SQL Wildcard Join Warning:**
Avoid joining tables on dynamic prefix/suffix identifier strings using standard `LIKE CONCAT(...)` with wildcards (e.g., `LIKE CONCAT(deposit.id, '\_%')`). The underscore `_` acts as a wildcard matching any character, leading to duplicate row matching (e.g., ID `2` matching `20_1` or `21_1`). Always use strict matching functions, such as `SUBSTRING_INDEX(reference_id, "_", 1) = id`, to ensure clean and precise matches.

Whenever a table is added, altered, indexed, or given a new constraint, update the installer schema as well:

```text
application/controllers/install/database.sql
```

If the change belongs to an add-on, update the add-on installer SQL too:

```text
addon/<addon_name>/installer/install/db/db.sql
```

Do not rely only on temporary migration files such as `db_update.sql` or one-off scripts. Those may patch an existing site, but a fresh installation must receive the same schema from the installer SQL.

For every schema change, maintain both:

- Fresh install path: `application/controllers/install/database.sql` or add-on `installer/install/db/db.sql`
- Upgrade path: migration/update SQL for existing deployments

When replacing a concept, add a new column/table and keep backward-compatible fallback reads.

Do not hard-delete business records once payments, attendance, exams, or audit records depend on them. Prefer:

```sql
is_active TINYINT(1) DEFAULT 1
```

or the existing app convention:

```sql
is_active VARCHAR(10) DEFAULT 'yes'
```

Use the convention already present in the nearby module.

## 7. Model Rules

Models must extend `MY_Model`.

Session-scoped models should initialize:

```php
protected $current_session;

public function __construct()
{
    parent::__construct();
    $this->current_session = $this->setting_model->getCurrentSession();
}
```

Create, update, delete, and disable operations must be wrapped in transactions when they affect more than one table.

Use:

```php
$this->db->trans_start();
...
$this->db->trans_complete();
```

or:

```php
$this->db->trans_begin();
...
if ($this->db->trans_status() === false) {
    $this->db->trans_rollback();
} else {
    $this->db->trans_commit();
}
```

Write audit logs using:

```php
$this->log($message, $record_id, $action);
```

Do not put large business workflows directly in views.

Do not duplicate large SQL queries across controllers. Move reusable business queries into models.

## 8. Controller Rules

Every POST endpoint must validate inputs using `$this->form_validation`.

Example:

```php
$this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');
```

Return structured JSON for AJAX endpoints:

```php
echo json_encode([
    'status' => 'success',
    'message' => 'Saved successfully'
]);
```

For validation errors:

```php
echo json_encode([
    'status' => 'fail',
    'error' => $msg
]);
```

Do not trust IDs from POST. Check that the record belongs to the current session or valid ownership scope before updating.

Do not allow direct route access to helper methods that mutate data without RBAC.

## 9. View Rules

Views must not contain business-critical calculations that affect stored data.

Views may format and display already-calculated values.

Escape user-generated output using the existing app style, and avoid rendering untrusted HTML.

Forms must include CSRF fields if CSRF is enabled in the application.

AJAX views must call protected controller endpoints only.

## 9A. UI/UX Rules (Architecture-Aligned)

UI behavior must follow RBAC, session scope, and sidebar architecture already used by the system.

Every new page must have a clear role target:

- Admin operational pages for data entry, reconciliation, and reports
- Staff pages for task execution
- Student/Parent pages for read-mostly visibility

Do not expose actions in UI if RBAC denies them. Hide buttons/links based on permission, and still enforce permission in controller.

All session-scoped screens must clearly reflect the active academic session context. Filters and summary cards must not silently mix cross-session data.

List pages must keep existing grid/filter patterns used in nearby modules:

- Search/filter row at top
- Pagination for large result sets
- Export actions only where existing module convention allows
- Totals/summary blocks computed from already validated backend data

Form UX rules:

- Match existing Bootstrap/component style used in current module
- Required fields must be visibly marked
- Validation errors must appear inline and in AJAX response payload
- Date, amount, and reference fields must use the same format conventions already used by fees/accounts modules

Menu and navigation UX rules:

- New submenu pages must preserve active state and keep parent menu expanded
- Controller `top_menu` and `sub_menu` values must match sidebar route strings
- Breadcrumb/page header text must match the business action name used in sidebar label

Destructive actions UX rules:

- Use confirmation prompts for delete/disable/reverse actions
- Prefer disable/soft-delete labels where data has dependencies
- Show dependency error messages clearly, for example "cannot delete because in use"

Finance-sensitive UX rules:

- Fee and payment screens must separate: due, paid, fine, discount, balance
- Do not display only latest payment when history exists
- Any accounting sync status or failure should be visible to authorized users, not silently ignored in UI

Performance and usability rules:

- Avoid loading large related datasets on first render when not needed
- Use module-consistent AJAX endpoints for dependent dropdowns
- Keep page usable on common desktop and tablet widths used by school staff

Accessibility and consistency rules:

- Buttons and links must use meaningful labels, not icon-only actions without tooltip/title
- Maintain consistent color semantics: success, warning, danger
- Do not introduce a new visual system inside a single module; follow local module patterns



## 10. Upload and File Rules

Do not use raw `move_uploaded_file()` in new code unless there is no existing app helper for that use case.

Prefer:

```php
$this->load->library('media_storage');
$this->media_storage->fileupload(...)
```

Use existing file type validation through `filetype_model`.

Use quota validation where the surrounding module uses `SaasValidation`.

Do not create upload folders with `0777`.

Use controlled paths under `uploads`.

Never store absolute local filesystem paths in the database. Store relative upload paths.

## 15. Notification Rules

Use the existing notification libraries.

For SMS/email templates:

```php
$this->mailsmsconf->mailsms('template_key', $payload);
```

For push notifications, use the existing `pushnotification` library.

Do not introduce a separate notification mechanism unless it is wrapped behind the existing notification pattern.

Student timeline entries should use `student_timeline` when the event should be visible in student history.

## 16. Add-on Rules

Add-ons must be isolated by package and table prefix.

Recommended package structure:

```text
addons/your_addon/
  addon_info.json
  installer/
    install/
      db/db.sql
      files/application/controllers/...
      files/application/models/...
      files/application/views/...
      files/application/libraries/...
    uninstall/
      db.sql
      unistall_directory.json
  updater/
    version_x/
      install/db/db.sql
      install/files/...
      uninstall/db.sql
```

Add-on tables must use a prefix, for example:

```text
acc_*
alumni_ext_*
onlineclass_*
```

Add-ons must register:

- `addons`
- `addon_versions`
- permissions
- sidebar menus
- language files
- config files

Do not modify core tables from an add-on except with additive nullable columns that are clearly documented.

Do not delete core data during add-on uninstall.

## 17. Editing Existing Features

Before editing an existing feature:

1. Identify all tables used by the current workflow.
2. Identify whether the data is session-scoped.
3. Identify whether the feature touches students, fees, accounting, attendance, exams, or notifications.
4. Trace the controller, model, view, helper, and library path.
5. Check whether historical data exists.

When replacing a feature:

- Keep the old data readable.
- Add new columns/tables instead of repurposing old meanings.
- Write fallback reads.
- Avoid hard deletes.
- Preserve receipt, voucher, and audit references.

When changing a paid workflow:

- Do not change invoice IDs.
- Do not change accounting reference IDs.
- Do not rewrite old `amount_detail` JSON.
- Do not delete fee definitions used by paid records.

## 18. Production Readiness Checklist

Before release, verify:

- PHP syntax passes for changed files.
- New tables/columns are added through migration SQL.
- Installer SQL is updated for fresh installs.
- Add-on installer SQL is updated when the schema belongs to an add-on.
- Existing records still load.
- Current-session filters are present.
- RBAC blocks unauthorized access.
- Sidebar links match permissions.
- Sidebar active state works for every new submenu.
- Parent sidebar menu remains expanded after selecting a child page.
- Create/update/delete actions are audited.
- Fee totals match existing fee collection screens.
- Accounting vouchers are not duplicated.
- Historical receipts still print.
- Uploads validate file type and size.
- Add-on files do not overwrite unrelated core files.
- Temporary debug/test files are removed before release.
- CHANGELOG.md is updated with all changes made in this session.

## 19. Required Review Questions

Ask these questions before approving any change:

1. Does this feature need `session_id` or `student_session_id`?
2. Can this leak data from another academic session?
3. Does this affect fee due, paid, fine, discount, receipt, or refund logic?
4. Does this affect accounting vouchers or ledger balances?
5. Does this require RBAC permission registration?
6. Does this need a sidebar menu entry?
7. If it has a sidebar entry, are `activate_controller`, `activate_methods`, and `main_menu_array()` updated?
8. Does this need a notification, audit log, or student timeline entry?
9. Can this break historical records?
10. Can this create duplicate payments or vouchers?
11. Can this be safely disabled or rolled back?
12. Does an existing function/model/library already do this? (See §22)

## 20. Non-Negotiable Rules

Do not bypass RBAC.

Do not bypass academic-session filtering.

Do not add sidebar submenus without active-state registration in `sidebar_sub_menus` and `main_menu_array()`.

Do not add or alter tables without updating the relevant installer `db.sql` / `database.sql`.

Do not overwrite fee payment JSON.

Do not change accounting voucher reference format.

Do not repurpose old foreign key columns for new table IDs.

Do not hard-delete records that have dependent payments, receipts, vouchers, attendance, exams, or audit records.

Do not use direct file uploads when existing upload helpers are available.

Do not introduce isolated business logic that duplicates fee/accounting calculations.

Do not make schema changes without backward compatibility.

Do not modify or alter any payment IDs, current payment functionality, or core payment processing logic unless explicitly requested.
Do not overwrite settings database fields with blank values; always verify that form submission fields are correctly mapped and preserved.
Do not conclude a task without validating modified endpoints (especially AJAX POST requests) to ensure they do not return 500 errors or blank/empty responses.
Do not declare high confidence in changes without verifying them through compilation and functional checks.
Do not rebuild existing functions, libraries, or logic — always check §22 first.
Do not conclude a development session without updating CHANGELOG.md.



## 22. Existing System Settings, Functions & Libraries — DO NOT REBUILD

### CRITICAL RULE: Before building ANY new logic, check this catalog. If an existing function, model method, library, or setting already provides the capability, USE IT. Do not create duplicates.

### 22A. System Settings (sch_settings table)

The `sch_settings` table is the central configuration store. Access via:

```php
$this->setting_model->getSetting();       // Full settings object
$this->setting_model->get();              // Array format with session join
$this->setting_model->getCurrentSession(); // Current academic session ID
```

| Setting | Purpose |
|---|---|
| `session_id` | Current active academic session |
| `date_format` | School-wide date display format (e.g. d-m-Y) |
| `currency` | Currency ID (joined to `currencies` table) |
| `currency_place` | before_number or after_number |
| `currency_format` | Formatting mask (e.g. #,##,###.##) |
| `start_month` | Academic year start month |
| `start_week` | Week start day |
| `fee_due_days` | Default fee due days |
| `student_partial_payment` | Allow partial fee payments |
| `collect_back_date_fees` | Allow backdated fee collection |
| `is_duplicate_fees_invoice` | Allow duplicate invoice numbers |
| `fees_discount` | Fee discount module enabled |
| `online_admission` | Online admission enabled |
| `exam_result` | Exam result publication control |
| `attendence_type` | Attendance type (daily/period) |
| `single_page_print` | Print multiple receipts on single page |
| `student_profile_edit` | Allow student self-edit |
| `student_panel_login` | Student login enabled |
| `parent_panel_login` | Parent login enabled |
| `is_offline_fee_payment` | Offline payment approval flow |
| `is_student_feature_lock` | Lock student features for defaulters |
| `lock_grace_period` | Grace period days for feature lock |
| `low_attendance_limit` | Low attendance warning threshold |
| `maintenance_mode` | Site maintenance mode |
| `superadmin_restriction` | Hide super admin from non-super admin |
| `student_timeline` | Enable student timeline |
| `biometric` | Biometric attendance enabled |
| `biometric_device` | Biometric device type |
| `time_format` | 12-hour or 24-hour |
| `display_previous_fees` | Show previous session fees |
| `staff_notification_email` | Staff email notifications |
| `admin_logo` | Admin panel logo |
| `admin_small_logo` | Admin panel collapsed sidebar logo |
| `image` | School logo |
| `adm_prefix`, `adm_start_from`, `adm_auto_insert`, `adm_no_digit` | Admission number auto-generation |
| `staffid_prefix`, `staffid_start_from`, `staffid_auto_insert`, `staffid_no_digit` | Staff ID auto-generation |
| `scan_code_type` | Barcode or QR code for ID cards |

### 22B. Print Header/Footer System

Print headers and footers for different document types are stored in `print_headerfooter`:

| print_type | Usage |
|---|---|
| `student_receipt` | Fee receipt print |
| `staff_payslip` | Payslip print |
| `online_admission_receipt` | Online admission receipt |
| `online_exam` | Online exam report |
| `general_purpose` | General purpose documents |
| `email` | Email header/footer |

Access via Setting_model methods:

```php
$this->setting_model->get_receiptheader();
$this->setting_model->get_receiptfooter();
$this->setting_model->get_payslipheader();
$this->setting_model->get_payslipfooter();
$this->setting_model->get_general_purpose_header();
$this->setting_model->get_general_purpose_footer();
```

### 22C. Customlib (application/libraries/Customlib.php)

The `Customlib` library is auto-loaded and provides essential utility methods:

| Method | Purpose |
|---|---|
| `getSchoolDateFormat()` | Get configured date format string |
| `getSchoolCurrencyFormat()` | Get currency symbol |
| `getCurrencyFormat()` | Get currency number formatting mask |
| `getSchoolCurrencyWithPlace($amount)` | Format amount with symbol and placement |
| `getSchoolCurrentSession()` | Get current session with details |
| `getSchoolTimeFormat()` | Get 12h/24h time format |
| `getBaseUrl()` | Get school base URL |
| `getFolderPath()` | Get folder path |
| `getCSRF()` | Get CSRF hidden input |
| `getStaffID()` | Get current logged-in staff ID |
| `getStaffRole()` | Get current staff role JSON |
| `getLoggedInUserData()` | Get logged-in user session data |
| `getGender()` | Gender dropdown options |
| `getDayList()` | Day of week dropdown |
| `getMonthDropdown()` | Month dropdown |
| `getDateFormat()` | Available date format options |
| `currency_format()` | Available currency format options |
| `getPaymenttype()` | Payment type filter options |
| `getTimeZone()` | Configured timezone |
| `getRTL()` | RTL layout attribute |
| `superadmin_visible()` | Check super admin visibility setting |
| `getGatewayProcessingFees($amount)` | Calculate payment gateway charges |
| `datetostrtotime($date)` | Convert school-format date to timestamp |
| `dateFormatToYYYYMMDD($date)` | Convert school-format date to Y-m-d |
| `dateyyyymmddTodateformat($date)` | Convert Y-m-d to school date format |

### 22D. Key Helper Functions

`application/helpers/custom_helper.php`:

| Function | Purpose |
|---|---|
| `amountFormat($amount)` | Format number according to school currency format |
| `convertCurrencyFormatToBaseAmount($amount)` | Parse formatted amount back to raw number |

`application/helpers/menu_helper.php`:

| Function | Purpose |
|---|---|
| `activate_main_menu($menu)` | Check if main sidebar menu should be active |
| `activate_submenu($controller, $methods)` | Check if submenu should be active |
| `main_menu_array()` | Map of all controller→methods for menu activation |

### 22E. Key Model Methods — DO NOT REBUILD

| Model | Method | Purpose |
|---|---|---|
| `Setting_model` | `getCurrentSession()` | Get current session ID |
| `Setting_model` | `getSetting()` | Get full school settings object |
| `Setting_model` | `getSchoolDetail()` | Get minimal school info |
| `Setting_model` | `getAdminlogo()` | Get admin logo path |
| `Studentfeemaster_model` | Full fee calculation | All fee due/paid/balance/discount logic |
| `Studentfee_model` | Fee deposit operations | Payment recording and receipt generation |
| `Feediscount_model` | `getApprovalList()` | Discount approval pending list |
| `Feediscount_model` | `updateApprovalStatus()` | Approve/reject discount |
| `Payroll_model` | `getOpenAdvances($staff_id)` | Get open staff advance balances |
| `Payroll_model` | `recoverAdvance($staff_id, $amount, $payslip_id)` | FIFO advance recovery |
| `Payroll_model` | `reverseAdvanceRecovery($staff_id, $amount)` | Reverse advance recovery on payslip revert |
| `Payroll_model` | `paymentSuccess($data, $payslipid)` | Mark payslip paid + trigger accounting sync |
| `Accvoucher_model` | `generateVoucherNo($type)` | Generate next voucher number |
| `Accvoucher_model` | `addVoucher($data, $items)` | Create voucher with line items |
| `Accvoucher_model` | `getVoucher($id)` | Get voucher details |
| `Accvoucher_model` | `reverseAutoSyncVoucher($id)` | Reverse an auto-synced voucher |
| `Accvoucher_model` | `getDatatableVouchers($type)` | Server-side DataTable for voucher lists |
| `Accvoucher_model` | `deleteVoucher($id)` | Soft-reverse a voucher |
| `Accledger_model` | `getLedgers()` | Get all ledgers |
| `Accledger_model` | `getLedgersByGroupIds($ids)` | Get ledgers filtered by group |
| `Accledger_model` | `getLedgerGroups()` | Get ledger group list |
| `Accreport_model` | Various report methods | Day book, cash book, trial balance, P&L, balance sheet |

### 22F. Key Libraries — DO NOT REBUILD

| Library | Purpose |
|---|---|
| `Accounts_integration` | All fee/payroll/expense/income double-entry sync |
| `Customlib` | Core utility functions |
| `Rbac` | Permission checking |
| `Mailsmsconf` | SMS/Email/WhatsApp notification dispatch |
| `Pushnotification` | Push notification delivery |
| `Media_storage` | File upload handling |
| `Datatables` | Server-side DataTable processing |
| `Paymentgateway` | Payment gateway integration |
| `Form_builder` | Dynamic form field rendering |
| `Mailer` | Email sending |
| `Smsgateway` | SMS gateway integration |
| `Module_lib` | Module enable/disable checks |
| `Studentmodule_lib` | Student panel module access |
| `SaasValidation` | Resource quota validation |
| `CSVReader` | CSV file import |
| `M_pdf` | PDF generation |
| `QR_Code` | QR code generation |



