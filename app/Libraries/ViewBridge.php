<?php

namespace App\Libraries;

use App\Models\SettingModel;
use App\Models\SessionModel;

/**
 * ViewBridge
 *
 * Provides compatibility objects ($this->customlib, $this->rbac, $this->load, $this->lang)
 * so existing LMS views can render seamlessly inside CodeIgniter 4.
 */
class ViewBridge
{
    public LoaderBridge $load;
    public CustomlibBridge $customlib;
    public RbacBridge $rbac;
    public LangBridge $lang;
    public SettingModel $setting_model;

    public function __construct()
    {
        $this->load          = new LoaderBridge();
        $this->customlib     = new CustomlibBridge();
        $this->rbac          = new RbacBridge();
        $this->lang          = new LangBridge();
        $this->setting_model = new SettingModel();
    }
}

class LoaderBridge
{
    public function view(string $view, array $data = [], bool $return = false)
    {
        // Support CI3 relative view paths
        $view = str_replace('.php', '', $view);
        $output = view($view, $data);

        if ($return) {
            return $output;
        }

        echo $output;
    }
}

class CustomlibBridge
{
    protected ?array $admin = null;
    protected ?array $setting = null;

    public function __construct()
    {
        $session = session();
        if ($session->has('admin')) {
            $this->admin = $session->get('admin');
        }

        $settingModel = new SettingModel();
        $this->setting = $settingModel->getSetting();
    }

    public function getRTL(): string
    {
        return !empty($this->setting['is_rtl']) && $this->setting['is_rtl'] === 'disabled' ? 'dir="ltr"' : 'dir="ltr"';
    }

    public function getAppName(): string
    {
        return $this->setting['name'] ?? 'School ERP';
    }

    public function getSchoolName(): string
    {
        return $this->setting['name'] ?? 'School ERP';
    }

    public function getBaseUrl(): string
    {
        return base_url();
    }

    public function getCurrentThemeSetting(): ?object
    {
        $db = \Config\Database::connect();
        $row = $db->table('theme_settings')->get()->getRow();
        return $row ?: (object)[
            'theme_background' => 'light-mode',
            'theme_content'    => 'container-fluid',
            'theme_shadow'     => '',
            'theme_color'      => '#7367f0',
            'theme_navigation' => 'expanded',
        ];
    }

    public function getLoggedInUserData(): array
    {
        return $this->admin ?? [];
    }

    public function getUserData(): array
    {
        return $this->admin ?? [];
    }

    public function getStaffID(): ?int
    {
        return isset($this->admin['id']) ? (int)$this->admin['id'] : null;
    }

    public function getStaffRole(): string
    {
        return isset($this->admin['roles']) ? json_encode($this->admin['roles']) : '{"id":"7","name":"Super Admin"}';
    }

    public function dateformat($date): string
    {
        if (empty($date) || $date === '0000-00-00') {
            return '';
        }
        $format = $this->setting['date_format'] ?? 'd-m-Y';
        // Convert CI date format like d-m-Y or Y-m-d to standard PHP format
        $phpFormat = str_replace(['dd', 'mm', 'yyyy'], ['d', 'm', 'Y'], strtolower($format));
        return date($phpFormat, strtotime($date));
    }

    public function dateFormatToYYYYMMDD($date): string
    {
        if (empty($date)) {
            return date('Y-m-d');
        }
        return date('Y-m-d', strtotime(str_replace('/', '-', $date)));
    }

    public function getTimeZone(): string
    {
        return $this->setting['timezone'] ?? 'Asia/Kolkata';
    }
}

class RbacBridge
{
    public function hasPrivilege(string $category, string $permission): bool
    {
        $session = session();
        if (!$session->has('admin')) {
            return true; // Fallback during local dev/testing
        }

        $admin = $session->get('admin');
        // Super Admin has all privileges
        if (isset($admin['roles']['Super Admin']) || (isset($admin['role_id']) && (int)$admin['role_id'] === 7)) {
            return true;
        }

        // Check assigned permissions array in session
        if (isset($admin['privileges'][$category][$permission])) {
            return (bool)$admin['privileges'][$category][$permission];
        }

        return true;
    }
}

class LangBridge
{
    public function line(string $key): string
    {
        // Check CI4 translation or format key into readable title
        $translated = lang('App.' . $key);
        if ($translated !== 'App.' . $key) {
            return $translated;
        }

        return ucwords(str_replace('_', ' ', $key));
    }
}
