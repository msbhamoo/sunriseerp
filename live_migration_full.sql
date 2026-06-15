-- =========================================================
-- COMPLETE HOSTEL MODULE MIGRATION SCRIPT
-- =========================================================
-- This script contains all schema alterations, new tables, 
-- and menu/permission entries for the newly added Hostel features:
-- 1. Student Registration (Bed Assignment)
-- 2. Hostel Settings (Fee Group Mapping)
-- 3. Hostel Fee Summary
-- =========================================================

-- ---------------------------------------------------------
-- PART 1: TABLE ALTERATIONS & NEW TABLES
-- ---------------------------------------------------------

-- Add hostel assignment columns to the students table
ALTER TABLE `students` 
ADD COLUMN IF NOT EXISTS `hostel_room_id` INT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `hostel_bed_no` INT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `hostel_assign_date` DATE DEFAULT NULL;

-- Create table to map which fee groups are considered "Hostel Fees"
CREATE TABLE IF NOT EXISTS `hostel_fee_groups` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `fee_groups_id` INT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `unique_fee_group` (`fee_groups_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------
-- PART 2: PERMISSIONS
-- ---------------------------------------------------------

-- Insert Permission Groups
INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Student Registration', 'student_registration', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'student_registration');

INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Hostel Settings', 'hostel_settings', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'hostel_settings');

INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Hostel Fee Summary', 'hostel_fee_summary', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'hostel_fee_summary');

-- Insert Permission Categories
INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Student Registration', 'student_registration', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'student_registration');

INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Hostel Settings', 'hostel_settings', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'hostel_settings');

INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Hostel Fee Summary', 'hostel_fee_summary', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'hostel_fee_summary');

-- Grant Permissions to Super Admin (Role ID 7)
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'student_registration' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'student_registration' LIMIT 1));

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_settings' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_settings' LIMIT 1));

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_fee_summary' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_fee_summary' LIMIT 1));

-- ---------------------------------------------------------
-- PART 3: SIDEBAR MENUS
-- ---------------------------------------------------------

-- 1. Student Registration Sub-Menu
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `is_active`, `created_at`) 
SELECT 22, 'Student Registration', 'student_registration', 'student_registration', 'admin/hostelregistration', 1, 'student_registration,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'student_registration' LIMIT 1), 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/hostelregistration');

-- 2. Hostel Settings Sub-Menu
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `is_active`, `created_at`) 
SELECT 22, 'Hostel Settings', 'hostel_settings', 'hostel_settings', 'admin/hostelsettings', 1, 'hostel_settings,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'hostel_settings' LIMIT 1), 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/hostelsettings');

-- 3. Fee Summary Sub-Menu
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `is_active`, `created_at`) 
SELECT 22, 'Fee Summary', 'hostel_fee_summary', 'hostel_fee_summary', 'admin/hostelfeesummary', 1, 'hostel_fee_summary,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'hostel_fee_summary' LIMIT 1), 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/hostelfeesummary');

-- 4. Update Parent Menu (Hostel, ID 22) to allow expansion for the new sub-menus
UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'student_registration\', \'can_view\')') 
WHERE `id` = 22 AND `access_permissions` NOT LIKE '%student_registration%';

UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'hostel_settings\', \'can_view\')') 
WHERE `id` = 22 AND `access_permissions` NOT LIKE '%hostel_settings%';

UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'hostel_fee_summary\', \'can_view\')') 
WHERE `id` = 22 AND `access_permissions` NOT LIKE '%hostel_fee_summary%';


-- ---------------------------------------------------------
-- PART 4: ADDITIONAL FEATURES (Assets, Gate Pass, Attendance)
-- ---------------------------------------------------------

CREATE TABLE IF NOT EXISTS `hostel_asset_items` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `item_name` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hostel_room_assets` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hostel_room_id` INT NOT NULL,
    `asset_item_id` INT NOT NULL,
    `qty` INT NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hostel_gate_pass` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_session_id` INT NOT NULL,
    `going_to` VARCHAR(255) NOT NULL,
    `reason` TEXT,
    `out_date` DATE,
    `out_time` TIME,
    `expected_in_time` TIME,
    `actual_in_time` TIME NULL,
    `status` VARCHAR(50) DEFAULT 'Out',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `hostel_attendance` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `hostel_id` INT NOT NULL,
    `student_session_id` INT NOT NULL,
    `date` DATE NOT NULL,
    `attendence_type_id` INT NOT NULL,
    `remark` TEXT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Permission Groups
INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Hostel Gate Pass', 'hostel_gate_pass', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'hostel_gate_pass');

INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Hostel Attendance', 'hostel_attendance', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'hostel_attendance');

INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Hostel Room Assets', 'hostel_room_assets', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'hostel_room_assets');

-- Insert Permission Categories
INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Hostel Gate Pass', 'hostel_gate_pass', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'hostel_gate_pass');

INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Hostel Attendance', 'hostel_attendance', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'hostel_attendance');

INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Hostel Room Assets', 'hostel_room_assets', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'hostel_room_assets');

-- Grant Permissions to Super Admin (Role ID 7)
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_gate_pass' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_gate_pass' LIMIT 1));

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_attendance' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_attendance' LIMIT 1));

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_room_assets' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_room_assets' LIMIT 1));

-- Gate Pass Sub-Menu
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `is_active`, `created_at`) 
SELECT 22, 'Gate Pass', 'hostel_gate_pass', 'hostel_gate_pass', 'admin/hostelgatepass', 1, 'hostel_gate_pass,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'hostel_gate_pass' LIMIT 1), 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/hostelgatepass');

-- Hostel Attendance Sub-Menu
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `is_active`, `created_at`) 
SELECT 22, 'Hostel Attendance', 'hostel_attendance', 'hostel_attendance', 'admin/hostelattendance', 1, 'hostel_attendance,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'hostel_attendance' LIMIT 1), 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/hostelattendance');

-- Update Parent Menu
UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'hostel_gate_pass\', \'can_view\')') 
WHERE `id` = 22 AND `access_permissions` NOT LIKE '%hostel_gate_pass%';

UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'hostel_attendance\', \'can_view\')') 
WHERE `id` = 22 AND `access_permissions` NOT LIKE '%hostel_attendance%';


-- ---------------------------------------------------------
-- PART 5: WARDEN ASSIGNMENT & ROOM TRANSFER LOGS
-- ---------------------------------------------------------

-- Add warden_id to hostel
ALTER TABLE `hostel` ADD COLUMN IF NOT EXISTS `warden_id` INT NULL DEFAULT NULL;

-- Create hostel_room_transfers table
CREATE TABLE IF NOT EXISTS `hostel_room_transfers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_session_id` INT NOT NULL,
    `from_hostel_id` INT NULL,
    `from_room_id` INT NULL,
    `from_bed_no` VARCHAR(50) NULL,
    `to_hostel_id` INT NULL,
    `to_room_id` INT NULL,
    `to_bed_no` VARCHAR(50) NULL,
    `transfer_date` DATE NOT NULL,
    `reason` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Permission Group
INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `created_at`) 
SELECT 'Hostel Room Transfer', 'hostel_room_transfer', 1, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'hostel_room_transfer');

-- Insert Permission Category
INSERT INTO `permission_category` (`name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT 'Hostel Room Transfer', 'hostel_room_transfer', 1, 0, 0, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'hostel_room_transfer');

-- Grant Permissions to Super Admin (Role ID 7)
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_room_transfer' LIMIT 1), 1, 0, 0, 0, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'hostel_room_transfer' LIMIT 1));

-- Room Transfer Log Sub-Menu
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `is_active`, `created_at`) 
SELECT 22, 'Room Transfer Log', 'hostel_room_transfer_log', 'room_transfer_log', 'admin/hosteltransfer', 1, 'hostel_room_transfer,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'hostel_room_transfer' LIMIT 1), 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/hosteltransfer');

-- Update Parent Menu
UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'hostel_room_transfer\', \'can_view\')') 
WHERE `id` = 22 AND `access_permissions` NOT LIKE '%hostel_room_transfer%';

-- ---------------------------------------------------------
-- PART 6: ATTENDANCE ADVANCED FEATURES
-- (Absentee Follow-up, Teacher Compliance, Analytics)
-- ---------------------------------------------------------

-- 1. Create Follow-up Table
CREATE TABLE IF NOT EXISTS `student_attendence_followup` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_session_id` INT NOT NULL,
    `date` DATE NOT NULL,
    `followup_status` VARCHAR(50) NOT NULL,
    `remark` TEXT NULL,
    `action_by` INT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Insert Permission Groups
INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `system`, `created_at`) 
SELECT 'Absentee Follow-up', 'absentee_followup', 1, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'absentee_followup');

INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `system`, `created_at`) 
SELECT 'Teacher Compliance Tracker', 'teacher_compliance', 1, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'teacher_compliance');

INSERT INTO `permission_group` (`name`, `short_code`, `is_active`, `system`, `created_at`) 
SELECT 'Attendance Analytics Dashboard', 'attendance_analytics', 1, 0, NOW() WHERE NOT EXISTS (SELECT 1 FROM `permission_group` WHERE `short_code` = 'attendance_analytics');

-- 3. Insert Permission Categories
INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT (SELECT `id` FROM `permission_group` WHERE `short_code` = 'absentee_followup' LIMIT 1), 'Absentee Follow-up', 'absentee_followup', 1, 1, 1, 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'absentee_followup');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT (SELECT `id` FROM `permission_group` WHERE `short_code` = 'teacher_compliance' LIMIT 1), 'Teacher Compliance Tracker', 'teacher_compliance', 1, 1, 1, 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'teacher_compliance');

INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
SELECT (SELECT `id` FROM `permission_group` WHERE `short_code` = 'attendance_analytics' LIMIT 1), 'Attendance Analytics Dashboard', 'attendance_analytics', 1, 1, 1, 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'attendance_analytics');

-- 4. Grant Permissions to Super Admin (Role ID 7)
INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'absentee_followup' LIMIT 1), 1, 1, 1, 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'absentee_followup' LIMIT 1));

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'teacher_compliance' LIMIT 1), 1, 1, 1, 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'teacher_compliance' LIMIT 1));

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`) 
SELECT 7, (SELECT `id` FROM `permission_category` WHERE `short_code` = 'attendance_analytics' LIMIT 1), 1, 1, 1, 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` WHERE `role_id` = 7 AND `perm_cat_id` = (SELECT `id` FROM `permission_category` WHERE `short_code` = 'attendance_analytics' LIMIT 1));

-- 5. Sub-Menus for Attendance
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `is_active`, `created_at`) 
SELECT (SELECT `id` FROM `sidebar_menus` WHERE `lang_key` = 'attendance' LIMIT 1), 'Absentee Follow-up', 'absentee_followup', 'absentee_followup', 'admin/absenteefollowup', 1, 'absentee_followup,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'absentee_followup' LIMIT 1), 'absenteefollowup', 'index', 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/absenteefollowup');

INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `is_active`, `created_at`) 
SELECT (SELECT `id` FROM `sidebar_menus` WHERE `lang_key` = 'attendance' LIMIT 1), 'Teacher Compliance Tracker', 'teacher_compliance', 'teacher_compliance', 'admin/teachercompliance', 1, 'teacher_compliance,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'teacher_compliance' LIMIT 1), 'teachercompliance', 'index', 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/teachercompliance');

INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `is_active`, `created_at`) 
SELECT (SELECT `id` FROM `sidebar_menus` WHERE `lang_key` = 'attendance' LIMIT 1), 'Attendance Analytics Dashboard', 'attendance_analytics', 'attendance_analytics', 'admin/attendanceanalytics', 1, 'attendance_analytics,can_view', (SELECT `id` FROM `permission_group` WHERE `short_code` = 'attendance_analytics' LIMIT 1), 'attendanceanalytics', 'index', 1, NOW() 
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/attendanceanalytics');

-- 6. Update Parent Menu (Attendance)
UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'absentee_followup\', \'can_view\')') 
WHERE `lang_key` = 'attendance' AND `access_permissions` NOT LIKE '%absentee_followup%';

UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'teacher_compliance\', \'can_view\')') 
WHERE `lang_key` = 'attendance' AND `access_permissions` NOT LIKE '%teacher_compliance%';

UPDATE `sidebar_menus` 
SET `access_permissions` = CONCAT(`access_permissions`, ' || (\'attendance_analytics\', \'can_view\')') 
WHERE `lang_key` = 'attendance' AND `access_permissions` NOT LIKE '%attendance_analytics%';
