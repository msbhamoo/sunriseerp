CREATE TABLE `student_call_purpose` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `purpose` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `student_calls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_session_id` int(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `call_type` varchar(20) NOT NULL DEFAULT 'Incoming',
  `contact_person` varchar(50) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `call_purpose_id` int(11) DEFAULT NULL,
  `call_status` varchar(50) DEFAULT NULL,
  `date` datetime NOT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `notes` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `student_call_followups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_call_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `priority` varchar(20) DEFAULT 'Medium',
  `assigned_to` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `call_status` varchar(50) DEFAULT NULL,
  `remarks` text,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- RBAC Permission Groups
INSERT IGNORE INTO `permission_group` (`name`, `short_code`, `is_active`, `system`, `created_at`) VALUES ('Student Call Log', 'student_call_log', 1, 0, CURRENT_TIMESTAMP);
INSERT IGNORE INTO `permission_group` (`name`, `short_code`, `is_active`, `system`, `created_at`) VALUES ('Call Purpose Setup', 'call_purpose_setup', 1, 0, CURRENT_TIMESTAMP);

-- RBAC Permission Categories (maps to the UI)
INSERT IGNORE INTO `permission_category` (`name`, `short_code`, `perm_group_id`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`) 
VALUES 
('Student Call Log', 'student_call_log', (SELECT id FROM `permission_group` WHERE `short_code` = 'student_call_log'), 1, 1, 1, 1, CURRENT_TIMESTAMP),
('Call Purpose Setup', 'call_purpose_setup', (SELECT id FROM `permission_group` WHERE `short_code` = 'call_purpose_setup'), 1, 1, 1, 1, CURRENT_TIMESTAMP);
