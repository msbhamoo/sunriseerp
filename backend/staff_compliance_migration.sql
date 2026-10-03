-- Migration for Staff Compliance Checklist Submenu and Permissions
-- 1. Insert Permission Category for Staff Compliance Checklist under Human Resource (perm_group_id = 18)
INSERT INTO `permission_category` (`id`, `perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`, `updated_at`)
VALUES (15020, 18, 'Staff Compliance Checklist', 'staff_compliance', 1, 1, 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = 'Staff Compliance Checklist', `enable_view` = 1, `enable_add` = 1, `enable_edit` = 1;

-- 2. Insert Submenu under Human Resource (sidebar_menu_id = 15)
INSERT INTO `sidebar_sub_menus` (`id`, `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`)
VALUES (345, 15, 'Compliance Checklist', NULL, 'staff_compliance_checklist', 'admin/staffcompliance', 17, '(\'staff_compliance\', \'can_view\')', 18, 'staffcompliance', 'index', NULL, 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE `menu` = 'Compliance Checklist', `url` = 'admin/staffcompliance', `access_permissions` = '(\'staff_compliance\', \'can_view\')', `activate_controller` = 'staffcompliance', `activate_methods` = 'index', `is_active` = 1;

-- 3. Grant full permissions (can_view, can_add, can_edit) to Superadmin (role_id = 7)
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`)
VALUES (7, 15020, 1, 1, 1, 0, NOW())
ON DUPLICATE KEY UPDATE `can_view` = 1, `can_add` = 1, `can_edit` = 1;

-- Grant to Admin role (role_id = 1) if exists
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`)
SELECT 1, 15020, 1, 1, 1, 0, NOW()
FROM `roles` WHERE `id` = 1
ON DUPLICATE KEY UPDATE `can_view` = 1, `can_add` = 1, `can_edit` = 1;
