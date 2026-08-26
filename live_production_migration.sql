-- =========================================================================================
-- SUNRISE ERP - LMS AI EXAM STUDIO COMPLETE LIVE PRODUCTION DATABASE MIGRATION
-- Run this script directly in phpMyAdmin / MySQL CLI on your LIVE production server.
-- =========================================================================================

-- 1. Add all AI API Key & Model Configuration Columns to sch_settings
ALTER TABLE `sch_settings` 
ADD COLUMN IF NOT EXISTS `ai_gemini_api_key` VARCHAR(255) NULL AFTER `is_active`,
ADD COLUMN IF NOT EXISTS `ai_openrouter_api_key` VARCHAR(255) NULL AFTER `ai_gemini_api_key`,
ADD COLUMN IF NOT EXISTS `ai_groq_api_key` VARCHAR(255) NULL AFTER `ai_openrouter_api_key`,
ADD COLUMN IF NOT EXISTS `ai_openai_api_key` VARCHAR(255) NULL AFTER `ai_groq_api_key`,
ADD COLUMN IF NOT EXISTS `ai_default_model` VARCHAR(100) DEFAULT 'gemini' AFTER `ai_openai_api_key`;

-- 2. Create NCERT / CBSE Curriculum Syllabus Chapters Cache Table (For 0ms Fast Load)
CREATE TABLE IF NOT EXISTS `cbse_syllabus_chapters` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `class_name` VARCHAR(100) NOT NULL,
    `subject_name` VARCHAR(100) NOT NULL,
    `chapters_json` LONGTEXT NOT NULL,
    `updated_at` DATETIME NOT NULL,
    INDEX `idx_class_sub` (`class_name`, `subject_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create AI Generated Question Papers History Archive Table
CREATE TABLE IF NOT EXISTS `cbse_ai_generated_papers` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `paper_title` VARCHAR(255) NOT NULL,
    `class_id` INT NULL,
    `class_name` VARCHAR(100) NULL,
    `subject_id` INT NULL,
    `subject_name` VARCHAR(100) NULL,
    `chapter` VARCHAR(255) NULL,
    `total_marks` INT DEFAULT 80,
    `difficulty` VARCHAR(50) DEFAULT 'Medium',
    `language` VARCHAR(50) DEFAULT 'English',
    `paper_json` LONGTEXT NOT NULL,
    `created_by` INT NULL,
    `created_at` DATETIME NOT NULL,
    INDEX `idx_class_sub_created` (`class_id`, `subject_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Create AI Answer Sheet Student Evaluations Master Table
CREATE TABLE IF NOT EXISTS `cbse_ai_evaluations` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `student_id` INT NOT NULL,
    `class_id` INT NOT NULL,
    `section_id` INT NOT NULL,
    `subject_id` INT NULL,
    `paper_title` VARCHAR(255) NOT NULL,
    `total_marks` INT NOT NULL DEFAULT 80,
    `obtained_marks` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `percentage` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `grade` VARCHAR(10) DEFAULT 'B',
    `evaluation_json` LONGTEXT NOT NULL,
    `annotations_json` LONGTEXT NULL,
    `evaluated_by` INT NULL,
    `evaluated_at` DATETIME NOT NULL,
    `created_at` DATETIME NOT NULL,
    INDEX `idx_student_eval` (`student_id`, `class_id`, `section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Create / Update Main Sidebar Menu: AI Exam Studio
INSERT INTO `sidebar_menus` (`id`, `permission_group_id`, `icon`, `menu`, `activate_menu`, `lang_key`, `system_level`, `level`, `sidebar_display`, `access_permissions`, `is_active`)
SELECT 43, NULL, 'icon-sparkles', 'AI Exam Studio', 'aiexam', 'ai_exam_studio', 0, 1, 1, '(\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\') || (\'cbse_exam\', \'can_view\')', 1
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `sidebar_menus` WHERE `lang_key` = 'ai_exam_studio' OR `id` = 43)
LIMIT 1;

UPDATE `sidebar_menus` 
SET `menu` = 'AI Exam Studio', 
    `lang_key` = 'ai_exam_studio', 
    `activate_menu` = 'aiexam', 
    `icon` = 'icon-sparkles',
    `access_permissions` = '(\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\') || (\'cbse_exam\', \'can_view\')',
    `is_active` = 1
WHERE `id` = 43 OR `lang_key` = 'ai_exam_studio';

-- 6. Create All Sub-Menus (Dynamically resolves parent AI Exam Studio ID to satisfy foreign key constraint)
-- Submenu 1: AI Paper Generator
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`)
SELECT sm.id, 'AI Paper Generator', 'ai_paper_generator', 'ai_paper_generator', 'admin/aiexamgenerator', 1, '(\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\') || (\'cbse_exam\', \'can_view\')', NULL, 'aiexamgenerator', 'index', '', 1, NOW(), NOW()
FROM `sidebar_menus` sm
WHERE (sm.`activate_menu` = 'aiexam' OR sm.`lang_key` = 'ai_exam_studio')
  AND NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/aiexamgenerator')
LIMIT 1;

-- Submenu 2: AI Answer Sheet Evaluator
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`)
SELECT sm.id, 'AI Answer Sheet Evaluator', 'ai_answer_evaluator', 'ai_answer_evaluator', 'admin/aiexamevaluator', 2, '(\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\') || (\'cbse_exam\', \'can_view\')', NULL, 'aiexamevaluator', 'index', '', 1, NOW(), NOW()
FROM `sidebar_menus` sm
WHERE (sm.`activate_menu` = 'aiexam' OR sm.`lang_key` = 'ai_exam_studio')
  AND NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/aiexamevaluator')
LIMIT 1;

-- Submenu 3: Curriculum & Syllabus Catalog
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`)
SELECT sm.id, 'Curriculum & Syllabus Catalog', 'ai_curriculum_catalog', 'ai_curriculum_catalog', 'admin/aiexamsyllabus', 3, '(\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\') || (\'cbse_exam\', \'can_view\')', NULL, 'aiexamsyllabus', 'index', '', 1, NOW(), NOW()
FROM `sidebar_menus` sm
WHERE (sm.`activate_menu` = 'aiexam' OR sm.`lang_key` = 'ai_exam_studio')
  AND NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/aiexamsyllabus')
LIMIT 1;

-- Submenu 4: Question & Answer Bank
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`)
SELECT sm.id, 'Question Bank', 'question_bank', 'question_bank', 'admin/question', 4, '(\'question_bank\', \'can_view\') || (\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\')', NULL, 'question', 'index', '', 1, NOW(), NOW()
FROM `sidebar_menus` sm
WHERE (sm.`activate_menu` = 'aiexam' OR sm.`lang_key` = 'ai_exam_studio')
  AND NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` ssm WHERE ssm.`sidebar_menu_id` = sm.id AND ssm.`url` = 'admin/question')
LIMIT 1;

-- Submenu 5: AI Engine Configuration
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`)
SELECT sm.id, 'AI Engine Configuration', 'ai_engine_setting', 'ai_engine_setting', 'admin/aisetting', 5, '(\'exam_group\', \'can_view\') || (\'online_examination\', \'can_view\') || (\'cbse_exam\', \'can_view\')', NULL, 'aisetting', 'index', '', 1, NOW(), NOW()
FROM `sidebar_menus` sm
WHERE (sm.`activate_menu` = 'aiexam' OR sm.`lang_key` = 'ai_exam_studio')
  AND NOT EXISTS (SELECT 1 FROM `sidebar_sub_menus` WHERE `url` = 'admin/aisetting')
LIMIT 1;
