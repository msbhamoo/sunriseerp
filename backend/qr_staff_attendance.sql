-- =====================================================================
-- QR-based Staff/Teacher Attendance
-- Idempotent migration. Safe to run multiple times.
-- =====================================================================

-- 1) Settings table (single row, id = 1)
CREATE TABLE IF NOT EXISTS `staff_qr_attendance_setting` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `is_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `qr_mode` ENUM('daily','static') NOT NULL DEFAULT 'daily',
  `static_token` VARCHAR(64) NULL DEFAULT NULL,
  `daily_token` VARCHAR(64) NULL DEFAULT NULL,
  `daily_token_date` DATE NULL DEFAULT NULL,
  `rescan_cooldown_minutes` INT(11) NOT NULL DEFAULT 5,
  `earliest_out_source` ENUM('schedule','manual') NOT NULL DEFAULT 'schedule',
  `manual_earliest_out_time` TIME NULL DEFAULT NULL,
  `ip_allowlist` TEXT NULL DEFAULT NULL,
  `gps_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `gps_lat` DECIMAL(10,7) NULL DEFAULT NULL,
  `gps_lng` DECIMAL(10,7) NULL DEFAULT NULL,
  `gps_radius_m` INT(11) NOT NULL DEFAULT 200,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed the single settings row if the table is empty.
INSERT INTO `staff_qr_attendance_setting` (`id`, `is_enabled`, `qr_mode`, `created_at`)
SELECT 1, 0, 'daily', NOW()
WHERE NOT EXISTS (SELECT 1 FROM `staff_qr_attendance_setting`);

-- 2) Add attendance_source to staff_attendance (guarded add for MySQL that
--    lacks "ADD COLUMN IF NOT EXISTS"). Run only the ALTER if the column is
--    missing.
SET @col_exists := (
  SELECT COUNT(*) FROM information_schema.COLUMNS
  WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'staff_attendance'
    AND COLUMN_NAME = 'attendance_source'
);
SET @ddl := IF(@col_exists = 0,
  'ALTER TABLE `staff_attendance` ADD COLUMN `attendance_source` VARCHAR(20) NULL DEFAULT NULL',
  'SELECT 1');
PREPARE stmt FROM @ddl;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
