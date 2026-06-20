-- 1. Create the staff_substitutions table
CREATE TABLE IF NOT EXISTS `staff_substitutions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `absent_staff_id` int(11) NOT NULL,
  `substitute_staff_id` int(11) NOT NULL,
  `substitute_subject_id` int(11) DEFAULT NULL,
  `subject_timetable_id` int(11) NOT NULL,
  `is_unplanned` tinyint(1) NOT NULL DEFAULT 0,
  `override_conflict_timetable_id` int(11) DEFAULT NULL,
  `session_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 2. Insert sidebar menus for Substitute Planning and Substitution History
INSERT INTO `sidebar_sub_menus` (`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `created_at`, `updated_at`) 
VALUES 
((SELECT id FROM sidebar_menus WHERE `key` = 'academics'), 'Substitute Planning', 'substitution_planning', 'substitution_planning', 'admin/substitution', 99, '', 1, '', '', '', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
((SELECT id FROM sidebar_menus WHERE `key` = 'academics'), 'Substitution History', 'substitution_history', 'substitution_history', 'admin/substitution/history', 100, '', 1, '', '', '', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

-- 3. If you haven't added the translation keys yet, add them in the English lang file:
-- $lang['substitution_planning'] = 'Substitute Planning';
-- $lang['substitution_history'] = 'Substitution History';
