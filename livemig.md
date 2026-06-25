ALTER TABLE hostel_attendance 
ADD roll_call_type VARCHAR(20) NOT NULL DEFAULT 'morning' 
AFTER date;


-- Disable the individual legacy menus
UPDATE sidebar_sub_menus 
SET is_active = 0 
WHERE lang_key IN ('student_categories', 'student_house', 'disable_reason', 'call_purpose', 'call_purpose_setup', 'setup_call_purpose');

-- Insert the new unified "Student Settings" menu under "Student Information"
INSERT INTO sidebar_sub_menus (
    sidebar_menu_id, 
    menu, 
    lang_key, 
    url, 
    level, 
    access_permissions, 
    activate_controller, 
    activate_methods, 
    is_active
) VALUES (
    (SELECT id FROM sidebar_menus WHERE lang_key = 'student_information'), 
    'Student Settings', 
    'student_settings', 
    'category', 
    7, 
    '(\'student_categories\', \'can_view\')', 
    'category,schoolhouse,disable_reason,callpurpose,religion,cast', 
    'index,edit,add,update', 
    1
);


-- Create Religion and Caste tables
CREATE TABLE IF NOT EXISTS religions (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    religion VARCHAR(100) NOT NULL, 
    is_active TINYINT(1) DEFAULT 1, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS casts (
    id INT AUTO_INCREMENT PRIMARY KEY, 
    cast VARCHAR(100) NOT NULL, 
    is_active TINYINT(1) DEFAULT 1, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, 
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
