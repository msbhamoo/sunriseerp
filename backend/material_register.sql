-- =====================================================================
-- Material Register (Inward / Outward) — Front Office module.
-- Logs every material item coming into or going out of the school gate.
-- Idempotent. Additive only. Safe to run more than once.
-- =====================================================================

-- 1) Register table -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `material_register` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `direction`     ENUM('inward','outward') NOT NULL DEFAULT 'inward',
  `date`          DATE NOT NULL,
  `material_name` VARCHAR(255) NOT NULL,
  `quantity`      VARCHAR(50) DEFAULT NULL,
  `unit`          VARCHAR(50) DEFAULT NULL,
  `carried_by`    VARCHAR(150) DEFAULT NULL,
  `contact`       VARCHAR(20) DEFAULT NULL,
  `party_name`    VARCHAR(255) DEFAULT NULL,   -- sender (inward) / receiver (outward)
  `vehicle_no`    VARCHAR(50) DEFAULT NULL,
  `gate_pass_no`  VARCHAR(50) DEFAULT NULL,
  `driver_name`   VARCHAR(150) DEFAULT NULL,
  `staff_id`      INT(11) DEFAULT NULL,        -- issued / received by (school staff)
  `department`    VARCHAR(150) DEFAULT NULL,
  `approved_by`   VARCHAR(150) DEFAULT NULL,
  `in_time`       VARCHAR(20) DEFAULT NULL,
  `out_time`      VARCHAR(20) DEFAULT NULL,
  `remarks`       TEXT DEFAULT NULL,
  `image`         TEXT DEFAULT NULL,           -- photo of material / scanned challan
  `created_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `updated_at`    TIMESTAMP NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `staff_id` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2) Permission category under the Front Office group -------------------
INSERT INTO `permission_category`
  (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`)
SELECT pg.`id`, 'Material Register', 'material_register', 1, 1, 1, 1
FROM `permission_group` pg
WHERE pg.`short_code` = 'front_office'
  AND NOT EXISTS (SELECT 1 FROM `permission_category` pc WHERE pc.`short_code` = 'material_register');

-- 3) Grant full access to Admin (1) and Receptionist (6). Super Admin bypasses RBAC.
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`)
SELECT r.`role_id`, pc.`id`, 1, 1, 1, 1
FROM (SELECT 1 AS role_id UNION SELECT 6) r
CROSS JOIN `permission_category` pc
WHERE pc.`short_code` = 'material_register'
  AND NOT EXISTS (
    SELECT 1 FROM `roles_permissions` rp
    WHERE rp.`role_id` = r.`role_id` AND rp.`perm_cat_id` = pc.`id`
  );

-- 4) Sidebar sub-menu entry under the Front Office menu -----------------
INSERT INTO `sidebar_sub_menus`
  (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `access_permissions`, `is_active`)
SELECT sm.`sidebar_menu_id`, 'Material Register', 'material_register', 'material_register', 'admin/materialregister',
       "('material_register', 'can_view')", 1
FROM `sidebar_sub_menus` sm
WHERE sm.`url` = 'admin/visitors'
  AND NOT EXISTS (
    SELECT 1 FROM `sidebar_sub_menus` s2 WHERE s2.`url` = 'admin/materialregister'
  )
LIMIT 1;

UPDATE `sidebar_sub_menus`
SET `menu` = 'Material Register', `lang_key` = 'material_register'
WHERE `url` = 'admin/materialregister';

