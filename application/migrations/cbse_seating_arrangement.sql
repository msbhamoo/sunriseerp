-- Buildings/blocks within the exam campus
CREATE TABLE IF NOT EXISTS cbse_seating_buildings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  code VARCHAR(20),
  description TEXT,
  is_active TINYINT(1) DEFAULT 1,
  session_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Exam rooms within buildings
CREATE TABLE IF NOT EXISTS cbse_seating_rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  building_id INT NOT NULL,
  room_number VARCHAR(50) NOT NULL,
  floor VARCHAR(20),
  seating_capacity INT NOT NULL DEFAULT 30,
  room_type ENUM('classroom','lab','hall') DEFAULT 'classroom',
  is_active TINYINT(1) DEFAULT 1,
  session_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (building_id) REFERENCES cbse_seating_buildings(id) ON DELETE CASCADE,
  UNIQUE KEY unique_room (building_id, room_number, session_id)
);

-- Master allocation record
CREATE TABLE IF NOT EXISTS cbse_seating_allocations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cbse_exam_id INT NOT NULL,
  exam_date DATE NOT NULL,
  allocation_strategy ENUM('interleaved','grouped','random') DEFAULT 'interleaved',
  seat_number_format ENUM('sequential','room_prefixed') DEFAULT 'sequential',
  column_depth INT DEFAULT 1,
  status ENUM('draft','finalized','locked') DEFAULT 'draft',
  total_students_allocated INT DEFAULT 0,
  total_rooms_used INT DEFAULT 0,
  session_id INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (cbse_exam_id) REFERENCES cbse_exams(id) ON DELETE CASCADE,
  UNIQUE KEY unique_allocation (cbse_exam_id, exam_date, session_id)
);

-- Rooms used for a particular allocation
CREATE TABLE IF NOT EXISTS cbse_seating_room_assignments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  allocation_id INT NOT NULL,
  room_id INT NOT NULL,
  seats_used INT DEFAULT 0,
  allocation_summary TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (allocation_id) REFERENCES cbse_seating_allocations(id) ON DELETE CASCADE,
  FOREIGN KEY (room_id) REFERENCES cbse_seating_rooms(id) ON DELETE CASCADE,
  UNIQUE KEY unique_room_alloc (allocation_id, room_id)
);

-- Individual student seat assignments
CREATE TABLE IF NOT EXISTS cbse_seating_student_seats (
  id INT AUTO_INCREMENT PRIMARY KEY,
  allocation_id INT NOT NULL,
  room_assignment_id INT NOT NULL,
  student_session_id INT NOT NULL,
  seat_number INT NOT NULL,
  formatted_seat_number VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (allocation_id) REFERENCES cbse_seating_allocations(id) ON DELETE CASCADE,
  FOREIGN KEY (room_assignment_id) REFERENCES cbse_seating_room_assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (student_session_id) REFERENCES student_session(id) ON DELETE CASCADE,
  UNIQUE KEY unique_student_alloc (allocation_id, student_session_id),
  UNIQUE KEY unique_seat (room_assignment_id, seat_number)
);

-- Invigilator (All staff) duty assignments
CREATE TABLE IF NOT EXISTS cbse_seating_invigilators (
  id INT AUTO_INCREMENT PRIMARY KEY,
  allocation_id INT NOT NULL,
  room_assignment_id INT NOT NULL,
  staff_id INT NOT NULL,
  role ENUM('chief','invigilator','relief') DEFAULT 'invigilator',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (allocation_id) REFERENCES cbse_seating_allocations(id) ON DELETE CASCADE,
  FOREIGN KEY (room_assignment_id) REFERENCES cbse_seating_room_assignments(id) ON DELETE CASCADE,
  FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE,
  UNIQUE KEY unique_invigilator_room (allocation_id, room_assignment_id, staff_id)
);

-- Insert Permissions
INSERT IGNORE INTO permission_category (name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at) VALUES 
('CBSE Exam Seating Rooms', 'cbse_exam_seating_rooms', 1, 1, 1, 1, NOW()),
('CBSE Exam Seating', 'cbse_exam_seating', 1, 1, 1, 1, NOW()),
('CBSE Exam Seating Reports', 'cbse_exam_seating_reports', 1, 0, 0, 0, NOW());

-- Update sidebar_sub_menus (Menu ID 34 = CBSE Examination)
INSERT IGNORE INTO sidebar_sub_menus (sidebar_menu_id, menu, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission) VALUES
(34, 'seatingarrangement', 'seatingarrangement', 'cbseexam/seatingarrangement', 1, '(''cbse_exam_seating'', ''can_view'')', NULL, 'seatingarrangement', 'index,create,preview,assign_invigilators', 'sscbse'),
(34, 'seatingreport', 'seatingreport', 'cbseexam/seatingreport', 1, '(''cbse_exam_seating_reports'', ''can_view'')', NULL, 'seatingreport', 'index,roomwise,studentwise,invigilator_duty,attendance_sheet,summary_report', 'sscbse');

-- Also, append the new menus to the parent sidebar_menus so the module permission knows about them
UPDATE sidebar_menus 
SET access_permissions = CONCAT(access_permissions, ' || (''cbse_exam_seating_rooms'', ''can_view'') || (''cbse_exam_seating'', ''can_view'') || (''cbse_exam_seating_reports'', ''can_view'')')
WHERE id = 34 AND access_permissions NOT LIKE '%cbse_exam_seating%';
