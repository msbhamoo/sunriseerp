<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Settings + token handling for QR-based staff attendance.
 * The table holds a single row (id = 1).
 */
class StaffQrSetting_model extends CI_Model
{
    protected $table = 'staff_qr_attendance_setting';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the single settings row as an array. Falls back to safe
     * defaults if the row is missing (e.g. migration seed not run).
     */
    public function get()
    {
        $row = $this->db->get_where($this->table, ['id' => 1])->row_array();
        if (empty($row)) {
            return array(
                'id'                       => 1,
                'is_enabled'               => 0,
                'qr_mode'                  => 'daily',
                'static_token'             => null,
                'daily_token'              => null,
                'daily_token_date'         => null,
                'rescan_cooldown_minutes'  => 5,
                'earliest_out_source'      => 'schedule',
                'manual_earliest_out_time' => null,
                'ip_allowlist'             => '',
                'gps_enabled'              => 0,
                'gps_lat'                  => null,
                'gps_lng'                  => null,
                'gps_radius_m'             => 200,
                'dynamic_interval_seconds' => 30,
                'dynamic_secret'           => null,
            );
        }
        return $row;
    }

    /**
     * Insert or update the single settings row.
     */
    public function save($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $exists = $this->db->get_where($this->table, ['id' => 1])->num_rows();
        if ($exists) {
            $this->db->where('id', 1)->update($this->table, $data);
        } else {
            $data['id']         = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert($this->table, $data);
        }
        return true;
    }

    /**
     * Generate a cryptographically-random token.
     */
    public function generateToken()
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Return the token that is valid right now, generating/persisting it as
     * needed. In daily mode the token auto-rotates when the stored date is not
     * today, so yesterday's screenshot stops working. In static mode a fixed
     * token is created once and reused.
     */
    public function getValidToken()
    {
        $s     = $this->get();
        $today = date('Y-m-d');

        // dynamic mode: a time-based token that rotates every N seconds. It is
        // derived from a persistent secret + the current time window (like a
        // TOTP), so the server can validate it without storing every token.
        if ($s['qr_mode'] === 'dynamic') {
            $secret = $s['dynamic_secret'];
            if (empty($secret)) {
                $secret = $this->generateToken();
                $this->save(['dynamic_secret' => $secret, 'qr_mode' => 'dynamic']);
            }
            $interval = max(5, (int) $s['dynamic_interval_seconds']);
            $window   = (int) floor(time() / $interval);
            return $this->dynamicTokenFor($secret, $window);
        }

        if ($s['qr_mode'] === 'static') {
            if (empty($s['static_token'])) {
                $token = $this->generateToken();
                $this->save(['static_token' => $token, 'qr_mode' => 'static']);
                return $token;
            }
            return $s['static_token'];
        }

        // daily mode
        if (empty($s['daily_token']) || $s['daily_token_date'] !== $today) {
            $token = $this->generateToken();
            $this->save([
                'daily_token'      => $token,
                'daily_token_date' => $today,
                'qr_mode'          => 'daily',
            ]);
            return $token;
        }
        return $s['daily_token'];
    }

    /**
     * Derive the rotating token for a given time window.
     */
    private function dynamicTokenFor($secret, $window)
    {
        return substr(hash_hmac('sha256', (string) $window, (string) $secret), 0, 32);
    }

    /**
     * True when the supplied token matches the currently-valid token. In
     * dynamic mode the current AND the immediately previous window are accepted,
     * so a code scanned right as it rotates is still honoured (clock/scan skew).
     */
    public function isTokenValid($token)
    {
        if ($token === null || $token === '') {
            return false;
        }

        $s = $this->get();
        if ($s['qr_mode'] === 'dynamic') {
            $secret = $s['dynamic_secret'];
            if (empty($secret)) {
                return false;
            }
            $interval = max(5, (int) $s['dynamic_interval_seconds']);
            $window   = (int) floor(time() / $interval);
            foreach (array($window, $window - 1) as $w) {
                if (hash_equals($this->dynamicTokenFor($secret, $w), (string) $token)) {
                    return true;
                }
            }
            return false;
        }

        return hash_equals((string) $this->getValidToken(), (string) $token);
    }
}
