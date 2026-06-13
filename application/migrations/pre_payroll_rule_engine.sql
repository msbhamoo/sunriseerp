-- ============================================================
-- 1. RULE GROUPS (execution stages)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_rule_groups` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(100) NOT NULL,
    `code` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `execution_order` INT NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_by` INT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 2. RULES (individual business rules)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_rules` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `rule_group_id` INT NOT NULL,
    `name` VARCHAR(200) NOT NULL,
    `code` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `rule_type` ENUM('earning','deduction','adjustment','calculation','validation') NOT NULL DEFAULT 'calculation',
    `applies_to` VARCHAR(500) NULL COMMENT 'JSON: role_ids, department_ids, designation_ids, or null=all',
    `priority` INT NOT NULL DEFAULT 100 COMMENT 'Lower number = higher priority',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `effective_from` DATE NULL,
    `effective_to` DATE NULL,
    `version` INT NOT NULL DEFAULT 1,
    `parent_rule_id` INT NULL COMMENT 'For versioning: points to original rule',
    `created_by` INT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_rule_group` (`rule_group_id`),
    KEY `idx_priority` (`priority`),
    KEY `idx_active_effective` (`is_active`, `effective_from`, `effective_to`),
    CONSTRAINT `fk_pre_rules_group` FOREIGN KEY (`rule_group_id`) 
        REFERENCES `pre_rule_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 3. RULE CONDITIONS (IF clauses)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_rule_conditions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `rule_id` INT NOT NULL,
    `condition_group` INT NOT NULL DEFAULT 1 COMMENT 'AND within group, OR across groups',
    `field` VARCHAR(100) NOT NULL COMMENT 'e.g. attendance_percentage, late_count, basic_salary, role_id',
    `operator` ENUM('eq','neq','gt','gte','lt','lte','between','in','not_in','is_null','is_not_null') NOT NULL,
    `value` VARCHAR(500) NOT NULL COMMENT 'Single value or JSON array for in/between',
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_rule` (`rule_id`),
    CONSTRAINT `fk_pre_conditions_rule` FOREIGN KEY (`rule_id`) 
        REFERENCES `pre_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 4. RULE ACTIONS (THEN clauses)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_rule_actions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `rule_id` INT NOT NULL,
    `action_type` ENUM(
        'add_paid_days','deduct_paid_days',
        'percentage_deduction','fixed_deduction',
        'percentage_addition','fixed_addition',
        'set_value','multiply_value',
        'add_allowance','add_deduction',
        'statutory_pf','statutory_esi','statutory_tds',
        'overtime_pay','salary_adjustment'
    ) NOT NULL,
    `target_field` VARCHAR(100) NOT NULL COMMENT 'e.g. paid_days, basic, net_salary, hra, pf_amount',
    `value` VARCHAR(500) NOT NULL COMMENT 'Amount, percentage, or formula reference',
    `value_type` ENUM('fixed','percentage','formula','reference') NOT NULL DEFAULT 'fixed',
    `sort_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_rule` (`rule_id`),
    CONSTRAINT `fk_pre_actions_rule` FOREIGN KEY (`rule_id`) 
        REFERENCES `pre_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 5. RULE VERSIONS (audit trail for rule changes)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_rule_versions` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `rule_id` INT NOT NULL,
    `version` INT NOT NULL,
    `rule_snapshot` TEXT NOT NULL COMMENT 'Full JSON snapshot of rule + conditions + actions',
    `change_reason` TEXT NULL,
    `created_by` INT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_rule_version` (`rule_id`, `version`),
    CONSTRAINT `fk_pre_versions_rule` FOREIGN KEY (`rule_id`) 
        REFERENCES `pre_rules` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 6. ENGINE RUN LOG (execution audit)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_engine_runs` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `run_type` ENUM('live','simulation','dry_run') NOT NULL DEFAULT 'live',
    `month` VARCHAR(20) NOT NULL,
    `year` VARCHAR(10) NOT NULL,
    `staff_id` INT NULL COMMENT 'NULL = bulk run',
    `role_filter` VARCHAR(100) NULL,
    `total_staff` INT NOT NULL DEFAULT 0,
    `total_processed` INT NOT NULL DEFAULT 0,
    `total_errors` INT NOT NULL DEFAULT 0,
    `execution_time_ms` INT NULL,
    `status` ENUM('running','completed','failed','cancelled') NOT NULL DEFAULT 'running',
    `error_log` TEXT NULL,
    `run_by` INT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_month_year` (`month`, `year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 7. CALCULATION TRACE (per-employee per-run detail)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_calculation_traces` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `engine_run_id` INT NOT NULL,
    `staff_id` INT NOT NULL,
    `payslip_id` INT NULL COMMENT 'Links to staff_payslip.id if live run',
    `input_data` TEXT NOT NULL COMMENT 'Snapshot of all input variables (JSON)',
    `rules_applied` TEXT NOT NULL COMMENT 'Ordered list of rules executed with results (JSON)',
    `output_data` TEXT NOT NULL COMMENT 'Final calculated values (JSON)',
    `status` ENUM('success','error','skipped') NOT NULL DEFAULT 'success',
    `error_message` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_run` (`engine_run_id`),
    KEY `idx_staff` (`staff_id`),
    CONSTRAINT `fk_pre_traces_run` FOREIGN KEY (`engine_run_id`) 
        REFERENCES `pre_engine_runs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ============================================================
-- 8. SIMULATION RESULTS (temporary, for preview before going live)
-- ============================================================
CREATE TABLE IF NOT EXISTS `pre_simulation_results` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `engine_run_id` INT NOT NULL,
    `staff_id` INT NOT NULL,
    `staff_name` VARCHAR(200) NULL,
    `role_name` VARCHAR(100) NULL,
    `basic_salary` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `total_days` INT NOT NULL DEFAULT 0,
    `paid_days` DECIMAL(5,1) NOT NULL DEFAULT 0,
    `present_days` INT NOT NULL DEFAULT 0,
    `absent_days` INT NOT NULL DEFAULT 0,
    `late_days` INT NOT NULL DEFAULT 0,
    `half_days` INT NOT NULL DEFAULT 0,
    `holidays` INT NOT NULL DEFAULT 0,
    `leaves_taken` INT NOT NULL DEFAULT 0,
    `total_earnings` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `total_deductions` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `tax_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `pf_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `esi_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `tds_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `overtime_amount` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `net_salary` DECIMAL(15,2) NOT NULL DEFAULT 0,
    `earnings_detail` TEXT NULL COMMENT '(JSON)',
    `deductions_detail` TEXT NULL COMMENT '(JSON)',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_run` (`engine_run_id`),
    KEY `idx_staff` (`staff_id`),
    CONSTRAINT `fk_pre_sim_run` FOREIGN KEY (`engine_run_id`) 
        REFERENCES `pre_engine_runs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- Permissions & Sidebar
INSERT INTO permission_group (name, short_code, is_active, created_at) 
SELECT 'Payroll Rules', 'payroll_rules', 1, CURRENT_TIMESTAMP 
FROM DUAL WHERE NOT EXISTS (SELECT id FROM permission_group WHERE short_code = 'payroll_rules');

SET @perm_group_id = (SELECT id FROM permission_group WHERE short_code = 'payroll_rules' LIMIT 1);

INSERT INTO permission_category (perm_group_id, name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at) 
SELECT @perm_group_id, 'Payroll Rules', 'payroll_rules', 1, 1, 1, 1, CURRENT_TIMESTAMP
FROM DUAL WHERE NOT EXISTS (SELECT id FROM permission_category WHERE short_code = 'payroll_rules');

SET @hr_menu_id = (SELECT id FROM sidebar_menus WHERE `activate_menu` = 'human_resource' LIMIT 1);

INSERT INTO sidebar_sub_menus (sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, activate_controller, activate_methods, is_active)
SELECT @hr_menu_id, 'Payroll Rules', 'payroll_rules', 'payroll_rules', 'admin/payrollrules', 2, '(\'payroll_rules\',\'can_view\')', 'payrollrules', 'index,rules,addrule,simulate,runsimulation,runlogs,runtrace,versions', 1
FROM DUAL WHERE NOT EXISTS (SELECT id FROM sidebar_sub_menus WHERE `key` = 'payroll_rules');


-- Seed Data: Rule Groups
INSERT INTO pre_rule_groups (name, code, execution_order, description) VALUES
('Attendance Calculation', 'attendance_calc', 10, 'Count present/absent/late/half-day from staff_attendance'),
('Leave Calculation', 'leave_calc', 20, 'Count approved leaves, apply leave-without-pay deduction'),
('Holiday Rules', 'holiday_rules', 30, 'Self-holiday eligibility'),
('Late/Early Deductions', 'late_early_deductions', 40, 'Late/early deductions'),
('Joining & Resignation', 'joining_resignation', 50, 'Rules for joining and resignation'),
('Allowances', 'allowances', 60, 'Allowance Additions (Uniform, TA, DA)'),
('Statutory Deductions', 'statutory_deductions', 70, 'Statutory (PF, ESI, TDS)'),
('Final Salary Computation', 'final_salary', 80, 'Final Salary Computation')
ON DUPLICATE KEY UPDATE execution_order=VALUES(execution_order);
