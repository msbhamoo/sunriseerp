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

        // Day lock: past days are never re-synced. Nothing to fetch if the whole range is locked.
        if ($this->CI->staffAttendaceSetting_model->isDayLocked($toDate)) {
            return array('status' => 'locked', 'message' => 'Attendance for ' . ($fromDate === $toDate ? date('d M Y', strtotime($fromDate)) : 'these dates')
                . ' is locked because the day is over. Biometric sync only updates today. To fix a past day, edit that staff member\'s attendance by hand; the change is recorded in the Change Log.');
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
        $lockedCount   = 0;

        // Shift snapshots already recorded for this range: re-syncs keep evaluating against them.
        $snapshotShift = array();
        $snapRows = $this->CI->db->query("SELECT staff_id, date, shift_id FROM staff_attendance WHERE shift_id IS NOT NULL AND date BETWEEN "
            . $this->CI->db->escape($fromDate) . " AND " . $this->CI->db->escape($toDate))->result_array();
        foreach ($snapRows as $sr) {
            $snapshotShift[$sr['staff_id'] . '|' . $sr['date']] = (int) $sr['shift_id'];
        }
        // Off days per staff (Sundays + calendar holidays, adjusted by Holidays & Working Days entries):
        // no punch on an off day is Holiday, not Absent.
        $this->CI->load->model('staffdayoverride_model');
        $holidayId = $this->CI->staffAttendaceSetting_model->getAttendanceTypeIdByKey('h');

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

            // CRITICAL: Prevent biometric machine from returning/overwriting attendance outside requested date range
            if (!empty($fromDate) && !empty($toDate)) {
                if ($punchDate < $fromDate || $punchDate > $toDate) {
                    continue; // Skip punches not belonging to the chosen date / date range
                }
            }
            // Day lock: a finished day is never changed by sync (only by a manual admin edit).
            if ($this->CI->staffAttendaceSetting_model->isDayLocked($punchDate)) {
                $lockedCount++;
                continue;
            }

            $inTime  = $this->normalizePunchTime(isset($punch['INTime']) ? $punch['INTime'] : null, false);
            $outTime = $this->normalizePunchTime(isset($punch['OUTTime']) ? $punch['OUTTime'] : null, true, $inTime);

            // Determine staff attendance type scoped to that punch's effective date
            $snapKey = $staff['id'] . '|' . $punchDate;
            $shiftId = isset($snapshotShift[$snapKey]) ? $snapshotShift[$snapKey]
                : ($staff['role_id'] ? $this->CI->staffAttendaceSetting_model->resolveShiftId($staff['role_id'], $punchDate) : null);
            $attendanceTypeId = $this->resolveAttendanceType($staff['id'], $staff['role_id'], $inTime, $punch, $punchDate, $shiftId, $outTime);
            // Leaving before the shift's "Early End Half" time turns Present / Late into Half Day.
            $attendanceTypeId = $this->CI->staffAttendaceSetting_model->applyEarlyHalfRule($attendanceTypeId, $outTime, $staff['role_id'], $punchDate, $shiftId);

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

            $hasPunch = (!empty($inTime) && $inTime !== '00:00:00') || (!empty($outTime) && $outTime !== '00:00:00');
            if (!$hasPunch && $holidayId && $this->CI->staffdayoverride_model->isOff($staff['id'], $staff['role_id'], $punchDate, $fromDate, $toDate)) {
                $attendanceTypeId = $holidayId;
            }

            $processedRows[] = array(
                'staff_id'                 => $staff['id'],
                'date'                     => $punchDate,
                'in_time'                  => $inTime,
                'out_time'                 => $outTime,
                'staff_attendance_type_id' => $attendanceTypeId,
                'shift_id'                 => $shiftId,
                'biometric_attendence'     => $hasPunch ? 1 : 0,
                // Always 'biometric': 'manual' marks a status set by hand and blocks later syncs from changing it.
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
            'last_sync_message' => "Synced {$syncedCount} records for {$fromDate} to {$toDate}. Unmapped: {$unmappedCount}. Locked days skipped: {$lockedCount}."
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
            'log_summary'           => "Fetched " . count($punchList) . " punches, synced {$syncedCount} records, skipped {$lockedCount} on locked days.{$unmappedSummary}"
        ));

        $lockedNote = $lockedCount ? " {$lockedCount} punch(es) for past days were skipped because those days are locked; correct them by editing the attendance by hand." : '';
        return array(
            'status'         => 'success',
            'message'        => "Biometric sync completed: {$syncedCount} attendance records updated successfully.{$lockedNote}",
            'locked'         => $lockedCount,
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
     * Determine the staff attendance type id from the role's shift bands for the day
     * (configured in Settings > Attendance Type).
     */
    protected function resolveAttendanceType($staff_id, $role_id, $in_time, $punch, $date = null, $shift_id = null, $out_time = null)
    {
        $hasIn = !empty($in_time) && $in_time !== '00:00:00';

        // 1. The role has a shift for the day: its bands decide (gaps between bands fall into the earlier band).
        if ($hasIn && $role_id) {
            $range = $this->CI->staffAttendaceSetting_model->getAttendanceTypeByRole($role_id, $in_time, $date, $shift_id);
            if ($range && !empty($range->staff_attendence_type_id)) {
                return (int) $range->staff_attendence_type_id;
            }
            // Punch outside every band: before the first band counts as that band (Present), after the
            // last band as the last one. No clock-based or device-status guesses when a shift exists.
            $bands = $this->CI->staffAttendaceSetting_model->getRoleShiftBands($role_id, $date, $shift_id);
            if (!empty($bands)) {
                $first = reset($bands);
                $last  = end($bands);
                return (int) ($in_time < $first->entry_time_from ? $first->staff_attendence_type_id : $last->staff_attendence_type_id);
            }
        }

        // 2. Timing rules come only from Settings > Attendance Type; the device's own status flags are
        //    ignored. Status ids are looked up by their configured keys, never hard-coded.
        $settings = $this->CI->staffAttendaceSetting_model;
        $hasOut   = !empty($out_time) && $out_time !== '00:00:00';
        if ($hasIn || $hasOut) {
            // Was in school but no band applies (no shift for the role, or only an out-punch):
            // Present; the early-half rule may still turn it into Half Day from the out time.
            return $settings->getAttendanceTypeIdByKey('p');
        }
        return $settings->getAttendanceTypeIdByKey('a'); // no punch at all
    }

    /**
     * Normalize punch time from raw strings (e.g., "04:30", "04:30 PM", "16:30:00")
     * into standard MySQL "H:i:s" 24-hour format.
     * Prevents afternoon exit times without AM/PM from being interpreted as AM.
     */
    public function normalizePunchTime($timeStr, $isExit = false, $inTime = null)
    {
        if (empty($timeStr) || $timeStr === '--:--' || $timeStr === '00:00' || $timeStr === '00:00:00') {
            return null;
        }

        $timeStr = trim($timeStr);

        // If time already contains AM or PM, standard strtotime handles it accurately
        if (stripos($timeStr, 'AM') !== false || stripos($timeStr, 'PM') !== false) {
            $ts = strtotime($timeStr);
            return $ts ? date('H:i:s', $ts) : null;
        }

        // Match HH:MM or HH:MM:SS
        if (preg_match('/^(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $timeStr, $matches)) {
            $h = (int)$matches[1];
            $m = (int)$matches[2];
            $s = isset($matches[3]) ? (int)$matches[3] : 0;

            // e-TimeOffice already sends 24-hour format (e.g. 06:59 for 6:59 AM, 18:59 for 6:59 PM).
            // For exit times where machine sends 12-hour without AM/PM (e.g. 01:00 to 07:00 PM for afternoon dispersal):
            if ($isExit && $h >= 1 && $h <= 7) {
                if ($inTime) {
                    $inHour = (int)substr($inTime, 0, 2);
                    if ($inHour >= 7 && $h < $inHour) {
                        $h += 12;
                    }
                }
            }

            return sprintf('%02d:%02d:%02d', $h, $m, $s);
        }

        $ts = strtotime($timeStr);
        return $ts ? date('H:i:s', $ts) : null;
    }
}

