-- ==========================================================
-- CBSE AI QUESTION & QUESTION PAPER GENERATOR SQL MIGRATION
-- Run this SQL in your LMS MySQL Database
-- ==========================================================

-- 1. Insert Sidebar Submenu for CBSE AI Generator (if not already existing)
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`,
    `menu`,
    `key`,
    `lang_key`,
    `url`,
    `level`,
    `access_permissions`,
    `permission_group_id`,
    `activate_controller`,
    `activate_methods`,
    `is_active`,
    `addon_permission`,
    `created_at`
) 
SELECT 
    id,
    'AI Question & Paper Generator',
    'cbse_ai_question_generator',
    'cbse_ai_question_generator',
    'cbseexam/aiquestiongenerator',
    1,
    '(''cbse_exam'', ''can_view'')',
    NULL,
    'aiquestiongenerator',
    'index',
    1,
    'sscbse',
    NOW()
FROM `sidebar_menus` 
WHERE `lang_key` = 'cbse_exam'
LIMIT 1;

-- 2. Add API Key Configuration Columns to School Settings
ALTER TABLE `sch_settings` 
ADD COLUMN IF NOT EXISTS `ai_gemini_api_key` VARCHAR(255) NULL DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `ai_groq_api_key` VARCHAR(255) NULL DEFAULT NULL;

-- 3. Create Paper Archiving & History Table
CREATE TABLE IF NOT EXISTS `cbse_ai_generated_papers` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `paper_title` VARCHAR(255) NOT NULL,
    `class_id` INT(11) NULL DEFAULT NULL,
    `class_name` VARCHAR(100) NOT NULL,
    `subject_id` INT(11) NULL DEFAULT NULL,
    `subject_name` VARCHAR(100) NOT NULL,
    `chapter` VARCHAR(255) NULL DEFAULT NULL,
    `total_marks` INT(11) NOT NULL DEFAULT 80,
    `difficulty` VARCHAR(100) NOT NULL DEFAULT 'Medium',
    `language` VARCHAR(50) NOT NULL DEFAULT 'English',
    `paper_json` LONGTEXT NOT NULL,
    `created_by` INT(11) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_class_subject` (`class_id`, `subject_id`),
    INDEX `idx_created_by` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
