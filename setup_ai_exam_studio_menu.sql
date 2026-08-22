-- ================================================================
-- DEDICATED "AI EXAM STUDIO" INDEPENDENT SIDEBAR MENU MIGRATION
-- ================================================================

-- 1. Create dedicated top-level menu for AI Exam Studio
INSERT INTO `sidebar_menus` (
    `product_name`, `menu`, `lang_key`, `icon`, `activate_menu`, `level`, `access_permissions`, 
    `permission_group_id`, `sidebar_display`, `is_active`, `created_at`
)
SELECT 
    'school', 'AI Exam Studio', 'ai_exam_studio', 'fa fa-magic', 'aiexam', 1, 
    '(\'\'examination\'\', \'\'can_view\'\')', NULL, 1, 1, NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `sidebar_menus` WHERE `lang_key` = 'ai_exam_studio'
);

-- 2. Remove old submenu under CBSE Exam
DELETE FROM `sidebar_sub_menus` WHERE `key` = 'cbse_ai_question_generator';

-- 3. Register 'AI Paper Generator' Submenu under 'AI Exam Studio'
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, 
    `access_permissions`, `permission_group_id`, `activate_controller`, 
    `activate_methods`, `is_active`, `addon_permission`, `created_at`
)
SELECT 
    sm.id,
    'AI Paper Generator',
    'ai_paper_generator',
    'ai_paper_generator',
    'admin/aiexamgenerator',
    1,
    '(\'\'examination\'\', \'\'can_view\'\')',
    NULL,
    'aiexamgenerator',
    'index',
    1,
    '',
    NOW()
FROM `sidebar_menus` sm
WHERE sm.`lang_key` = 'ai_exam_studio'
AND NOT EXISTS (
    SELECT 1 FROM `sidebar_sub_menus` WHERE `key` = 'ai_paper_generator'
)
LIMIT 1;

-- 4. Register 'AI Answer Sheet Evaluator' Submenu under 'AI Exam Studio'
INSERT INTO `sidebar_sub_menus` (
    `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, 
    `access_permissions`, `permission_group_id`, `activate_controller`, 
    `activate_methods`, `is_active`, `addon_permission`, `created_at`
)
SELECT 
    sm.id,
    'AI Answer Sheet Evaluator',
    'ai_answer_evaluator',
    'ai_answer_evaluator',
    'admin/aiexamevaluator',
    1,
    '(\'\'examination\'\', \'\'can_view\'\')',
    NULL,
    'aiexamevaluator',
    'index',
    1,
    '',
    NOW()
FROM `sidebar_menus` sm
WHERE sm.`lang_key` = 'ai_exam_studio'
AND NOT EXISTS (
    SELECT 1 FROM `sidebar_sub_menus` WHERE `key` = 'ai_answer_evaluator'
)
LIMIT 1;

-- 5. Ensure cbse_ai_generated_papers table exists
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

-- 6. Answer Sheet Evaluations Table
CREATE TABLE IF NOT EXISTS `cbse_ai_answer_evaluations` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `paper_id` INT(11) NOT NULL,
    `student_id` INT(11) NOT NULL,
    `class_id` INT(11) NOT NULL,
    `section_id` INT(11) NULL DEFAULT NULL,
    `exam_session` VARCHAR(50) NOT NULL DEFAULT '2026-2027',
    `uploaded_pages_json` LONGTEXT NOT NULL,
    `custom_solution_url` VARCHAR(255) NULL DEFAULT NULL,
    `evaluation_json` LONGTEXT NOT NULL,
    `total_max_marks` DECIMAL(5,2) NOT NULL DEFAULT 80.00,
    `total_obtained_marks` DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    `average_confidence` INT(3) NOT NULL DEFAULT 90,
    `status` ENUM('pending', 'reviewed', 'published') NOT NULL DEFAULT 'pending',
    `evaluated_by` INT(11) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_paper_student` (`paper_id`, `student_id`),
    INDEX `idx_evaluated_by` (`evaluated_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
