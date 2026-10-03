<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Store SSO Launcher Controller
 * 
 * Safely generates a one-time cryptographic token for authorized staff members
 * and seamlessly redirects them to the standalone Store portal (store.sunriseschool.in).
 * 
 * Zero side-effects on existing ERP sessions or academic modules.
 */
class Store_sso extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('rbac');
    }

    public function launch()
    {
        // 1. Strict RBAC privilege check
        if (!$this->rbac->hasPrivilege('store_module', 'can_view')) {
            access_denied();
            return;
        }

        // 2. Fetch logged in admin data
        $admin = $this->session->userdata('admin');
        if (empty($admin) || empty($admin['id'])) {
            redirect('site/login');
            return;
        }

        $staff_id   = (int) $admin['id'];
        $session_id = !empty($admin['current_session']) ? (int) $admin['current_session'] : 1;
        $branch_id  = !empty($admin['branch_id']) ? (int) $admin['branch_id'] : 1;

        // 3. Generate a cryptographically secure 64-character token
        $token = bin2hex(random_bytes(32));
        $now   = date('Y-m-d H:i:s');
        $expires_at = date('Y-m-d H:i:s', strtotime('+60 seconds'));
        $ip_address = $this->input->ip_address();

        // 4. Save single-use token in store_sso_tokens table
        $data = array(
            'token'      => $token,
            'staff_id'   => $staff_id,
            'session_id' => $session_id,
            'branch_id'  => $branch_id,
            'created_at' => $now,
            'expires_at' => $expires_at,
            'is_used'    => 0,
            'ip_address' => $ip_address,
        );

        $this->db->insert('store_sso_tokens', $data);

        // 5. Determine Store URL based on environment
        $is_localhost = isset($_SERVER['HTTP_HOST']) && (
            $_SERVER['HTTP_HOST'] === 'localhost' || 
            $_SERVER['HTTP_HOST'] === '127.0.0.1' || 
            strpos($_SERVER['HTTP_HOST'], 'localhost:') === 0
        );

        if ($is_localhost) {
            $store_base_url = 'http://localhost/lms/store';
        } else {
            $store_base_url = 'https://store.sunriseschool.in';
        }

        // 6. Direct redirection to Store SSO endpoint
        $redirect_url = rtrim($store_base_url, '/') . '/auth/sso?token=' . urlencode($token);
        redirect($redirect_url);
    }
}
