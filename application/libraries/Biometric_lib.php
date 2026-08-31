<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Biometric Library for e-TimeOffice Cloud API Integration.
 *
 * Handles HTTP Basic Authentication, endpoints querying (DownloadInOutPunchData,
 * DownloadLastPunchData, DownloadPunchDataMCID), and data normalization & syncing
 * with LMS staff attendance records.
 */
class Biometric_lib
{
    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('StaffBiometricSetting_model');
        $this->CI->load->model('staffattendancemodel');
        $this->CI->load->model('staffAttendaceSetting_model');
    }

    /**
     * Build HTTP Basic Auth Authorization header for e-TimeOffice.
     * Format: Corporateid:Username:Password:True
     */
    public function getAuthHeader($setting = null)
    {
        if (empty($setting)) {
            $setting = $this->CI->StaffBiometricSetting_model->get();
        }
        $corp = trim($setting['corporate_id']);
        $user = trim($setting['username']);
        $pass = trim($setting['password']);

        $raw = "{$corp}:{$user}:{$pass}:True";
        return "Basic " . base64_encode($raw);
    }

    /**
     * Send HTTP request to e-TimeOffice API.
     */
    public function makeApiRequest($endpoint, $params = array(), $setting = null)
    {
        if (empty($setting)) {
            $setting = $this->CI->StaffBiometricSetting_model->get();
        }
        $baseUrl = rtrim($setting['api_url'], '/') . '/';
        $fullUrl = $baseUrl . $endpoint . '?' . http_build_query($params);

        $authHeader = $this->getAuthHeader($setting);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: " . $authHeader,
            "Content-Type: application/json",
            "Accept: application/json"
        ));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr  = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            return array(
                'status'    => false,
                'http_code' => $httpCode,
                'error'     => "cURL Error: " . $curlErr,
                'data'      => null
            );
        }

        if ($httpCode === 401) {
            return array(
                'status'    => false,
                'http_code' => 401,
                'error'     => 'Unauthorized (401). Please check Corporate ID, Username and Password.',
                'data'      => null
            );
        }

        $decoded = json_decode($response, true);
        if ($decoded === null && !empty($response)) {
            return array(
                'status'    => false,
                'http_code' => $httpCode,
                'error'     => 'Invalid JSON response from server: ' . substr($response, 0, 200),
                'data'      => null
            );
        }

        return array(
            'status'    => true,
            'http_code' => $httpCode,
            'error'     => null,
            'data'      => $decoded
        );
    }

    /**
     * Test connection to e-TimeOffice API.
     */
    public function testConnection($setting = null)
    {
        $today = date('d/m/Y');
        $params = array(
            'Empcode'  => 'ALL',
            'FromDate' => $today,
            'ToDate'   => $today
        );
        $res = $this->makeApiRequest('DownloadInOutPunchData', $params, $setting);
        if (!$res['status']) {
            return array('success' => false, 'message' => $res['error']);
        }
        return array('success' => true, 'message' => 'Connection successful! Connected to e-TimeOffice API.');
    }

    /**
     * Synchronize biometric attendance for a specific date (Y-m-d format) or date range.
     *
     * @param string $fromDate Y-m-d
     * @param string $toDate   Y-m-d
     * @param string $syncMode 'manual', 'ajax', 'cron', 'range'
     * @return array
     */
    public function syncAttendance($fromDate, $toDate = null, $syncMode = 'manual')
    {
        if (empty($toDate)) {
            $toDate = $fromDate;
        }

        $setting = $this->CI->StaffBiometricSetting_model->get();
        if (empty($setting['is_enabled'])) {
            return array('status' => 'disabled', 'message' => 'Biometric integration is currently disabled in settings.');
        }

        // Convert Y-m-d to dd/MM/yyyy as required by e-TimeOffice
        $apiFromDate = date('d/m/Y', strtotime($fromDate));
        $apiToDate   = date('d/m/Y', strtotime($toDate));

        $params = array(
            'Empcode'  => 'ALL',
            'FromDate' => $apiFromDate,
            'ToDate'   => $apiToDate
        );

        $apiRes = $this->makeApiRequest('DownloadInOutPunchData', $params, $setting);

        if (!$apiRes['status']) {
            $this->CI->StaffBiometricSetting_model->logSync(array(
                'sync_mode'             => $syncMode,
                'target_date'           => ($fromDate === $toDate) ? $fromDate : "{$fromDate} to {$toDate}",
                'total_records_fetched' => 0,
                'synced_count'          => 0,
                'unmapped_count'        => 0,
                'status'                => 'error',
                'log_summary'           => $apiRes['error']
            ));
            return array('status' => 'error', 'message' => $apiRes['error']);
        }

        $data = $apiRes['data'];
        $punchList = array();
        if (is_array($data)) {
            if (isset($data['InOutPunchData']) && is_array($data['InOutPunchData'])) {
                $punchList = $data['InOutPunchData'];
            } elseif (isset($data[0])) {
                $punchList = $data;
            }
        }

        // Build Staff lookup map: by biometric_emp_id AND by employee_id
        $staffMap = $this->getStaffLookupMap();

        $syncedCount   = 0;
        $unmappedCount = 0;
        $unmappedCodes = array();
        $processedRows = array();

        foreach ($punchList as $punch) {
            $empCode = isset($punch['Empcode']) ? trim($punch['Empcode']) : '';
            if ($empCode === '') {
                continue;
            }

            // Clean leading zeros or format matching if needed
            $staff = $this->findStaffInMap($empCode, $staffMap);

            if (!$staff) {
                $unmappedCount++;
                $unmappedCodes[$empCode] = isset($punch['Name']) ? $punch['Name'] : $empCode;
                continue;
            }

            // Parse Date: format "01/01/2026" -> "2026-01-01"
            $punchDate = null;
            if (!empty($punch['DateString'])) {
                $parts = explode('/', $punch['DateString']);
                if (count($parts) === 3) {
                    $punchDate = "{$parts[2]}-{$parts[1]}-{$parts[0]}";
                } else {
                    $punchDate = date('Y-m-d', strtotime($punch['DateString']));
                }
            } else {
                $punchDate = $fromDate;
            }

            $inTime  = (!empty($punch['INTime']) && $punch['INTime'] !== '--:--') ? date('H:i:s', strtotime($punch['INTime'])) : null;
            $outTime = (!empty($punch['OUTTime']) && $punch['OUTTime'] !== '--:--') ? date('H:i:s', strtotime($punch['OUTTime'])) : null;

            // Determine staff attendance type
            $attendanceTypeId = $this->resolveAttendanceType($staff['id'], $staff['role_id'], $inTime, $punch);

            $remark = '';
            if (!empty($punch['Remark'])) {
                $remark = trim($punch['Remark']);
            }
            if (!empty($punch['Late_In']) && $punch['Late_In'] !== '00:00') {
                $remark .= ($remark ? ' | ' : '') . 'Late: ' . $punch['Late_In'];
            }
            if (!empty($punch['Erl_Out']) && $punch['Erl_Out'] !== '00:00') {
                $remark .= ($remark ? ' | ' : '') . 'Early Out: ' . $punch['Erl_Out'];
            }

            $processedRows[] = array(
                'staff_id'                 => $staff['id'],
                'date'                     => $punchDate,
                'in_time'                  => $inTime,
                'out_time'                 => $outTime,
                'staff_attendance_type_id' => $attendanceTypeId,
                'biometric_attendence'     => 1,
                'attendance_source'        => 'biometric',
                'remark'                   => $remark,
                'biometric_device_data'    => json_encode($punch)
            );
        }

        if (!empty($processedRows)) {
            $syncedCount = $this->CI->staffattendancemodel->saveBiometricAttendanceBatch($processedRows);
        }

        // Update settings last sync metadata
        $now = date('Y-m-d H:i:s');
        $this->CI->StaffBiometricSetting_model->save(array(
            'last_sync_time'    => $now,
            'last_sync_status'  => 'success',
            'last_sync_message' => "Synced {$syncedCount} records for {$fromDate} to {$toDate}. Unmapped: {$unmappedCount}."
        ));

        // Audit Log
        $unmappedSummary = !empty($unmappedCodes) ? " Unmapped codes: " . implode(', ', array_keys($unmappedCodes)) : "";
        $this->CI->StaffBiometricSetting_model->logSync(array(
            'sync_mode'             => $syncMode,
            'target_date'           => ($fromDate === $toDate) ? $fromDate : "{$fromDate} to {$toDate}",
            'total_records_fetched' => count($punchList),
            'synced_count'          => $syncedCount,
            'unmapped_count'        => $unmappedCount,
            'status'                => 'success',
            'log_summary'           => "Fetched " . count($punchList) . " punches, synced {$syncedCount} records.{$unmappedSummary}"
        ));

        return array(
            'status'         => 'success',
            'message'        => "Biometric sync completed: {$syncedCount} attendance records updated successfully.",
            'fetched'        => count($punchList),
            'synced'         => $syncedCount,
            'unmapped'       => $unmappedCount,
            'unmapped_codes' => $unmappedCodes
        );
    }

    /**
     * Map active staff indexed by:
     * - biometric_emp_id (exact & stripped)
     * - employee_id (exact & stripped)
     * - id
     */
    protected function getStaffLookupMap()
    {
        $sql = "SELECT staff.id, staff.employee_id, staff.biometric_emp_id, staff.name, staff.surname, roles.id as role_id, roles.name as role_name
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                WHERE staff.is_active = 1";
        $list = $this->CI->db->query($sql)->result_array();

        $map = array(
            'by_bio_id'    => array(),
            'by_emp_id'    => array(),
            'by_emp_clean' => array()
        );

        foreach ($list as $s) {
            if (!empty($s['biometric_emp_id'])) {
                $bio = trim($s['biometric_emp_id']);
                $map['by_bio_id'][$bio] = $s;
                $map['by_bio_id'][ltrim($bio, '0')] = $s;
            }
            if (!empty($s['employee_id'])) {
                $emp = trim($s['employee_id']);
                $map['by_emp_id'][$emp] = $s;
                $clean = ltrim($emp, '0');
                if ($clean !== '') {
                    $map['by_emp_clean'][$clean] = $s;
                }
            }
        }
        return $map;
    }

    /**
     * Locate staff in map.
     */
    protected function findStaffInMap($empCode, $map)
    {
        $code = trim($empCode);
        $clean = ltrim($code, '0');

        if (isset($map['by_bio_id'][$code])) {
            return $map['by_bio_id'][$code];
        }
        if ($clean !== '' && isset($map['by_bio_id'][$clean])) {
            return $map['by_bio_id'][$clean];
        }
        if (isset($map['by_emp_id'][$code])) {
            return $map['by_emp_id'][$code];
        }
        if ($clean !== '' && isset($map['by_emp_clean'][$clean])) {
            return $map['by_emp_clean'][$clean];
        }
        return null;
    }

    /**
     * Determine staff attendance type ID (Present = 1, Late = 2, Half Day = 4, etc.)
     * based on role schedules or API status flag.
     */
    protected function resolveAttendanceType($staff_id, $role_id, $in_time, $punch)
    {
        // If in_time exists and role has attendance schedule configured
        if ($in_time && $role_id) {
            $range = $this->CI->staffAttendaceSetting_model->getAttendanceTypeByRole($role_id, $in_time);
            if ($range && !empty($range->staff_attendence_type_id)) {
                return (int) $range->staff_attendence_type_id;
            }
        }

        // Map API status string if schedule not found
        $apiStatus = isset($punch['Status']) ? strtoupper(trim($punch['Status'])) : '';
        if ($apiStatus === 'P' || strpos($apiStatus, 'P') !== false) {
            return 1; // Present
        } elseif ($apiStatus === 'A') {
            return 3; // Absent
        } elseif ($apiStatus === 'L' || strpos($apiStatus, 'LT') !== false) {
            return 2; // Late
        } elseif ($apiStatus === 'HD' || $apiStatus === 'H/D' || $apiStatus === 'P/2') {
            return 4; // Half Day
        }

        return 1; // Default Present
    }
}
