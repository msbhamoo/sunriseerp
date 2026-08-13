-- =====================================================================
-- QR staff attendance: mid-day "step out / step in" break log.
-- Idempotent. Additive only. Does NOT change existing attendance data.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `staff_attendance_break` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `staff_id` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `out_time` TIME NOT NULL,
  `in_time` TIME NULL DEFAULT NULL,
  `duration_minutes` INT(11) NULL DEFAULT NULL,
  `reason` VARCHAR(255) NULL DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_staff_date` (`staff_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
