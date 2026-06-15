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

## Quick Checklist for Adding a Menu Item:
1. [ ] Add to `permission_group` (e.g., `short_code = my_new_module`).
2. [ ] Add to `permission_category`.
3. [ ] Insert into `sidebar_sub_menus` using `key` (not `key_id`), set `access_permissions = 'my_new_module,can_view'`, and include the `permission_group_id`.
4. [ ] Update the parent menu in `sidebar_menus` to append `|| ('my_new_module', 'can_view')` to `access_permissions`.
5. [ ] Grant permission to Role 7 in `roles_permissions` using `perm_cat_id`.
6. [ ] Add the translation to `system_lang.php`.
