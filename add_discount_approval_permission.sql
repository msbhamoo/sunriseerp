-- 1. Insert Permission Category for Fee Discount Approval
INSERT INTO `permission_category` (`perm_group_id`, `name`, `short_code`, `enable_view`, `enable_add`, `enable_edit`, `enable_delete`, `created_at`, `updated_at`)
SELECT 2, 'Fee Discount Approval', 'fee_discount_approval', 1, 0, 1, 0, NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permission_category` WHERE `short_code` = 'fee_discount_approval');

-- 2. Update sidebar_sub_menus to require fee_discount_approval permission
UPDATE `sidebar_sub_menus` 
SET `access_permissions` = "('fee_discount_approval', 'can_view')" 
WHERE `url` = 'admin/feediscount/approvalQueue';

-- 3. Grant view & edit permissions to Admin (1) and Super Admin (7) by default
SET @perm_cat_id = (SELECT id FROM `permission_category` WHERE `short_code` = 'fee_discount_approval' LIMIT 1);

INSERT INTO `roles_permissions` (`role_id`, `perm_cat_id`, `can_view`, `can_add`, `can_edit`, `can_delete`, `created_at`, `updated_at`)
SELECT r.id, @perm_cat_id, 
       CASE WHEN r.id IN (1, 7) THEN 1 ELSE 0 END, 
       0, 
       CASE WHEN r.id IN (1, 7) THEN 1 ELSE 0 END, 
       0, 
       NOW(), NOW()
FROM `roles` r
WHERE NOT EXISTS (SELECT 1 FROM `roles_permissions` rp WHERE rp.role_id = r.id AND rp.perm_cat_id = @perm_cat_id);
