# Smart School Sidebar & Permissions Guide

This document explains the architecture of the sidebar menus and access permissions in the Smart School application to prevent common mistakes when adding new modules.

## 1. Sidebar Menu Tables
The sidebar relies on two primary tables:
- `sidebar_menus`: The parent categories (e.g., Hostel, Front Office, Academics).
- `sidebar_sub_menus`: The child links under those categories (e.g., Hostel Rooms, Fee Summary).

### Common Mistake: `key` vs `key_id`
When adding to `sidebar_sub_menus`, the column for the unique string identifier is `key`, **NOT** `key_id`. Many developers assume `key_id` because of similar naming conventions elsewhere. 

**Correct SQL for Sub Menu:**
```sql
INSERT INTO sidebar_sub_menus (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `is_active`) 
VALUES (22, 'Fee Summary', 'hostel_fee_summary', 'hostel_fee_summary', 'admin/hostelfeesummary', 1, 'hostel_fee_summary,can_view', 1);
```

## 2. Permissions & Access Control
Access in Smart School is controlled via two tables:
- `permission_group`: Defines the module/feature (e.g., `short_code = 'hostel_fee_summary'`).
- `permission_category`: Defines what actions are allowed on that module (e.g., `enable_view = 1`).
- `roles_permissions`: Maps the `permission_category.id` to a specific `role_id` via the `perm_cat_id` column (e.g., Super Admin is Role ID 7).

### Common Mistake: `perm_cat_id` vs `perm_group_id`
In the `roles_permissions` table, the column name is `perm_cat_id`, which maps to the ID of the `permission_category` table. Do NOT use `perm_group_id`.

### Common Mistake: Missing `permission_group_id` in `sidebar_sub_menus`
When inserting into `sidebar_sub_menus`, ensure you assign the `permission_group_id` (which links back to `permission_group.id`). If omitted or mapped incorrectly, the SQL execution might silently fail or the menu won't show up.

### Linking Menus to Permissions
For a sub-menu to be visible, two things MUST happen:
1. The user's role must have the permission in `roles_permissions`.
2. The sub-menu's `access_permissions` column must match the expected format.

**Sub Menu Format:**
In `sidebar_sub_menus`, the `access_permissions` column expects a comma-separated string like:
`hostel_fee_summary,can_view`

**Parent Menu Format (CRITICAL):**
If you add a new sub-menu, the parent menu in `sidebar_menus` must ALSO be updated to include that permission, otherwise the parent won't expand/show the child when clicked. 
In `sidebar_menus`, the `access_permissions` column expects a logically separated format:
`('hostel_rooms', 'can_view') || ('room_type', 'can_view') || ('hostel_fee_summary', 'can_view')`

## 3. Language Translation
Finally, ensure the `lang_key` defined in your sub-menu exists in the system language file `application/language/english/app_files/system_lang.php`.
```php
$lang['hostel_fee_summary'] = "Fee Summary";
```

## 4. CRITICAL: Addon-Based Menus (e.g., CBSE Examination)

Addon menus (like CBSE Examination, sscbse) have a **completely different pattern** from standard menus. Getting this wrong will cause menus to be invisible on live with NO error messages.

### 4.1 The `addon_permission` Column

The `sidebar_sub_menus` table has an `addon_permission` column. The sidebar rendering code (`application/views/layout/sidebar.php`) checks this:

```php
if ($submenu_value->addon_permission != "") {
    // BOTH permission AND addon check must pass
    if ($this->rbac->hasPrivilege(...) && $this->auth->addonchk($submenu_value->addon_permission, false)) {
        $sidebar_access = true;
    }
} else {
    // Only permission check needed
    if ($this->rbac->hasPrivilege(...)) {
        $sidebar_access = true;
    }
}
```

**Rule:** If the parent module is an addon (like CBSE = `sscbse`), then ALL sub-menus under it MUST have `addon_permission = 'sscbse'`. If you set it to empty string `''`, the permission check will pass but the menu will still show (via the else branch). However, if you set it to a **wrong** addon name that isn't licensed, the menu will be HIDDEN.

### 4.2 The `permission_group_id` Column (Module Active Check)

After the permission check passes, there's a SECOND gate:

```php
if (!empty($submenu_value->permission_group_id)) {
    if (!$this->module_lib->hasActive($submenu_value->short_code)) {
        continue; // SKIPS the menu item!
    }
}
```

**Rule:** If `permission_group_id` is set, the system checks if that module's `short_code` is active in the `permission_group` table. For addon sub-menus that are just sub-features (not standalone modules), **set `permission_group_id` to NULL**. Only set it if the sub-menu represents an entire standalone module registered in `permission_group`.

### 4.3 The `access_permissions` Format for Addon Sub-Menus

⚠️ **CRITICAL DIFFERENCE:** Addon sub-menus (like CBSE) use the **parenthesized** format, NOT the comma-separated format:

| Menu Type | Format | Example |
|-----------|--------|---------|
| Standard sub-menus (admin/*) | Comma-separated | `hostel_fee_summary,can_view` |
| Addon sub-menus (cbseexam/*) | Parenthesized | `('cbse_exam', 'can_view')` |

**Always check the existing working sub-menus** in the same parent to determine the correct format. Copy the pattern exactly.

### 4.4 The `access_permissions` Must Reference an EXISTING Permission

The `access_permissions` value references a `short_code` in the `permission_category` table. If the referenced permission doesn't exist, `hasPrivilege()` returns `false` and the menu is hidden.

**Common Mistake:** Using a permission like `cbse_exam_admit_card` that was never inserted into `permission_category`. Always verify the permission exists before referencing it. If unsure, use a known working permission like `cbse_exam`.

### 4.5 The `access_permissions` Must NOT Be Empty

If `access_permissions` is empty string `''`, the sidebar code sets `$sidebar_access = false` and **the menu will NEVER show**, regardless of the user's role (even Super Admin).

### 4.6 The `sidebar_menu_id` Must Be Correct

Every sub-menu MUST have the correct `sidebar_menu_id` pointing to the parent menu's `id` in `sidebar_menus`. If this value is NULL, empty, or wrong, the sub-menu will not appear under the parent.

**Always verify:** `SELECT id FROM sidebar_menus WHERE lang_key = 'cbse_exam'` to get the correct parent ID before inserting.

## 5. CBSE Examination Reference (Known Working Pattern)

Here is the **exact pattern** used by all working CBSE sub-menus on the live server. **Copy this pattern exactly** when adding new CBSE sub-menus:

```sql
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, 
    `access_permissions`, `permission_group_id`, `activate_controller`, 
    `activate_methods`, `is_active`, `addon_permission`, `created_at`
) VALUES (
    (SELECT id FROM sidebar_menus WHERE lang_key = 'cbse_exam' LIMIT 1),
    'My New Feature',           -- Display name (fallback)
    'my_new_feature',           -- Unique key
    'my_new_feature',           -- Lang key (must exist in cbse_lang.php)
    'cbseexam/mycontroller',    -- URL (no /index suffix)
    1,                          -- Level
    '(''cbse_exam'', ''can_view'')',  -- Use existing permission!
    NULL,                       -- NULL - not a standalone module
    'mycontroller',             -- Controller name for activate_submenu()
    'index',                    -- Method name for activate_submenu()
    1,                          -- is_active
    'sscbse',                   -- Addon permission (MUST be 'sscbse')
    NOW()
);
```

### Key Values for CBSE:
| Field | Value | Why |
|-------|-------|-----|
| `sidebar_menu_id` | `34` (or SELECT query) | CBSE Examination parent ID |
| `access_permissions` | `('cbse_exam', 'can_view')` | Use existing known permission |
| `permission_group_id` | `NULL` | Sub-features don't need module check |
| `addon_permission` | `sscbse` | Required for CBSE addon |
| `url` | `cbseexam/controller` | No `/index` suffix needed |

## 6. Sidebar Rendering Pipeline (Full Flow)

Understanding the full rendering pipeline prevents invisible menu bugs:

```
sidebar_menus (parent)
  │
  ├─ is_active = 1? ──────────────── NO → Parent hidden
  ├─ sidebar_display = 1? ────────── NO → Parent hidden
  ├─ hasModule(short_code)? ──────── NO → Parent hidden
  ├─ hasActive(short_code)? ──────── NO → Parent hidden
  ├─ access_permissions check ────── NO match → Parent hidden
  │
  └─ sidebar_sub_menus (children)
       │
       ├─ is_active = 1? ────────── NO → Child hidden
       ├─ access_permissions = ''? ─ YES → Child hidden (ALWAYS)
       ├─ hasPrivilege() check ───── NO → Child hidden
       ├─ addon_permission != ''? 
       │    └─ addonchk() ────────── NO → Child hidden
       ├─ permission_group_id set?
       │    └─ hasActive() ───────── NO → Child hidden
       │
       └─ ✅ VISIBLE
```

## 7. Quick Checklist for Adding a Menu Item

### Standard Menu (admin/*)
1. [ ] Add to `permission_group` (e.g., `short_code = my_new_module`).
2. [ ] Add to `permission_category`.
3. [ ] Insert into `sidebar_sub_menus` using `key` (not `key_id`), set `access_permissions = 'my_new_module,can_view'`, and include the `permission_group_id`.
4. [ ] Update the parent menu in `sidebar_menus` to append `|| ('my_new_module', 'can_view')` to `access_permissions`.
5. [ ] Grant permission to Role 7 in `roles_permissions` using `perm_cat_id`.
6. [ ] Add the translation to `system_lang.php`.

### Addon Menu (cbseexam/*, etc.)
1. [ ] **DO NOT** create new `permission_group` or `permission_category` unless absolutely necessary.
2. [ ] Use an **existing** permission in `access_permissions` (e.g., `('cbse_exam', 'can_view')`).
3. [ ] Set `permission_group_id = NULL`.
4. [ ] Set `addon_permission` to the addon's short name (e.g., `sscbse`).
5. [ ] Use the **parenthesized** format for `access_permissions`.
6. [ ] Ensure `sidebar_menu_id` is correct (verify with SELECT query).
7. [ ] Ensure `access_permissions` is NOT empty.
8. [ ] Add the `lang_key` translation to the addon's lang file (e.g., `cbse_lang.php`).
9. [ ] Add controller/methods to `menu_helper.php` → `main_menu_array()` for active state.

## 8. Debugging: Menu Not Showing?

Use this debug controller (`application/controllers/admin/Debugsidebar.php`) to inspect the live database. Access via `/admin/debugsidebar` while logged in. **DELETE after use.**

Common causes (in order of likelihood):
1. **Entry doesn't exist** — INSERT was never run or WHERE NOT EXISTS blocked it
2. **`sidebar_menu_id` is wrong/empty** — Sub-menu not linked to parent
3. **`access_permissions` is empty** — Always hidden, even for Super Admin
4. **`access_permissions` references non-existent permission** — `hasPrivilege()` returns false
5. **`addon_permission` is wrong** — `addonchk()` returns false
6. **`permission_group_id` is set but module isn't active** — `hasActive()` returns false
7. **`is_active = 0`** — Explicitly disabled
8. **Missing lang_key** — Menu appears but with blank label (looks invisible)
