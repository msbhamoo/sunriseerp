-- 1. Create dedicated Permission Group for School Store
INSERT INTO `permission_group` (`id`, `name`, `short_code`, `is_active`, `system`, `created_at`, `updated_at`) 
VALUES (1600, 'School Store & POS', 'school_store', 1, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE `name` = 'School Store & POS', `is_active` = 1;

-- 2. Add individual feature permissions
INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`, `updated_at`) VALUES
(1600, 'Student POS Billing', 'store_pos', 1, 1, 1, 1, NOW(), NOW()),
(1600, 'Item Master & Catalog', 'store_items', 1, 1, 1, 1, NOW(), NOW()),
(1600, 'Student Kits & Bundles', 'store_bundles', 1, 1, 1, 1, NOW(), NOW()),
(1600, 'Purchase Orders & Inward GRN', 'store_procurement', 1, 1, 1, 1, NOW(), NOW()),
(1600, 'Staff Requisitions', 'store_requisitions', 1, 1, 1, 1, NOW(), NOW()),
(1600, 'Asset Loans & Returns', 'store_loans', 1, 1, 1, 1, NOW(), NOW()),
(1600, 'Stock & Sales Reports', 'store_reports', 1, 0, 0, 0, NOW(), NOW());
