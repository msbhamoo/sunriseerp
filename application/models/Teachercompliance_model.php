<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Teachercompliance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getComplianceByDate($date, $session_id) {
        $sql = "SELECT 
                    c.id as class_id, c.class, 
                    s.id as section_id, s.section,
                    st.id as staff_id, st.name as staff_name, st.surname as staff_surname, st.contact_no,
                    (
                        SELECT COUNT(sa.id)
                        FROM student_attendences sa
                        JOIN student_session ss ON sa.student_session_id = ss.id
                        WHERE ss.class_id = c.id AND ss.section_id = s.id AND ss.session_id = ? AND sa.date = ?
                    ) as attendance_count
                FROM class_sections cs
                JOIN classes c ON cs.class_id = c.id
                JOIN sections s ON cs.section_id = s.id
                LEFT JOIN class_teacher ct ON ct.class_id = c.id AND ct.section_id = s.id AND ct.session_id = ?
                LEFT JOIN staff st ON ct.staff_id = st.id
                ORDER BY c.class, s.section";

        $query = $this->db->query($sql, array($session_id, $date, $session_id));
        return $query->result_array();
    }
}
