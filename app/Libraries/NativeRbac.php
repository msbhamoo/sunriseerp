<?php

namespace App\Libraries;

/**
 * NativeRbac
 *
 * Permission check for native CI4 controllers, with the same rules as the
 * legacy Rbac library (application/libraries/Rbac.php) but without needing a
 * CI3 controller instance. Denies by default.
 */
class NativeRbac
{
    protected array $cache = [];

    public function hasPrivilege($category = null, $permission = null): bool
    {
        $admin = session()->get('admin');
        if (empty($admin['roles']) || !is_array($admin['roles'])) {
            return false;
        }

        $role_name = key($admin['roles']);
        if ($role_name === 'Super Admin') {
            return true;
        }

        $role_id  = (int) $admin['roles'][$role_name];
        $category = trim((string) $category);
        $key      = $role_id . '|' . $category;

        if (!array_key_exists($key, $this->cache)) {
            $this->cache[$key] = db_connect()->table('roles_permissions')
                ->select('roles_permissions.*')
                ->join('permission_category', 'permission_category.id = roles_permissions.perm_cat_id')
                ->where('roles_permissions.role_id', $role_id)
                ->where('permission_category.short_code', $category)
                ->get()
                ->getRowArray();
        }

        $role_perm = $this->cache[$key];
        return !empty($role_perm) && array_key_exists($permission, $role_perm) && (bool) $role_perm[$permission];
    }
}
