-- =====================================================================
-- Master lists for the Material Register dropdowns (item / quantity / unit).
-- Powers the select2 quick-add: new values typed by the user are persisted
-- here via AJAX so they appear in the dropdown next time.
-- Idempotent. Additive only.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `material_masters` (
  `id`         INT(11) NOT NULL AUTO_INCREMENT,
  `type`       ENUM('item','quantity','unit','party','department','vehicle','driver') NOT NULL,
  `name`       VARCHAR(150) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_type_name` (`type`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Ensure the enum is current even if the table pre-existed an earlier version.
ALTER TABLE `material_masters`
  MODIFY COLUMN `type` ENUM('item','quantity','unit','party','department','vehicle','driver') NOT NULL;

-- A few common starter units (ignored if already present).
INSERT IGNORE INTO `material_masters` (`type`, `name`) VALUES
  ('unit', 'Pcs'),
  ('unit', 'Box'),
  ('unit', 'Kg'),
  ('unit', 'Litre'),
  ('unit', 'Packet'),
  ('unit', 'Set');
