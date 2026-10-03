-- ==============================================================================
-- Realistic School Store Sandbox Seed Data
-- Database: erp (Additive to store_* tables, safe for local testing)
-- ==============================================================================

USE `erp`;

-- 1. Vendors (Publishers, Uniform Tailors, Sports Distributors)
INSERT INTO `store_vendors` (`id`, `name`, `company_name`, `contact_person`, `mobile`, `email`, `gstin`, `address`, `is_active`) VALUES
(1, 'Oxford Book Depot', 'Oxford Book Depot & Publishers', 'Rajesh Sharma', '9829011223', 'orders@oxfordbooks.demo', '08AABCO1234F1Z5', 'B-12 Chaura Rasta Book Market, Jaipur', 1),
(2, 'Sunrise Uniforms', 'Sunrise Uniforms & Textiles', 'Sunita Verma', '9829033445', 'sales@sunrisetextiles.demo', '08AABCS5678G2Z6', 'Plot 44 Sitapura Industrial Area, Jaipur', 1),
(3, 'Champion Sports', 'Champion Sports Goods Ltd.', 'Harpreet Singh', '9829055667', 'champion@sportsgoods.demo', '08AABCJ9012H3Z7', 'Shop 10 SMS Stadium Complex, Jaipur', 1),
(4, 'Apex IT Solutions', 'Apex IT & Lab Solutions', 'Amit Khandelwal', '9829077889', 'support@apexitlab.demo', '08AABCA3456K4Z8', 'G-4 Ganpati Plaza, M.I. Road, Jaipur', 1)
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `company_name`=VALUES(`company_name`);

-- 2. Master Items & SKUs (Uniforms, Books, Stationery, Sports, Assets)
INSERT INTO `store_items` (`id`, `category_id`, `unit_id`, `item_name`, `item_code`, `barcode`, `hsn_code`, `description`, `purchase_price`, `sale_price`, `gst_rate`, `min_stock_alert`, `rack_shelf`, `is_returnable_asset`, `is_saleable`, `is_active`) VALUES
-- School Uniforms (Category 1)
(1, 1, 1, 'School Polo Shirt (Navy Blue - Size 30)', 'UNI-POLO-30', '890100100001', '6205', 'Standard summer daily school polo shirt', 240.00, 350.00, 5.00, 10, 'RACK-A1', 0, 1, 1),
(2, 1, 1, 'School Polo Shirt (Navy Blue - Size 32)', 'UNI-POLO-32', '890100100002', '6205', 'Standard summer daily school polo shirt', 250.00, 380.00, 5.00, 10, 'RACK-A1', 0, 1, 1),
(3, 1, 1, 'School Winter Blazer (Maroon - Size 32)', 'UNI-BLZ-32', '890100100003', '6203', 'Premium wool blend maroon embroidered blazer', 950.00, 1450.00, 12.00, 5, 'RACK-A2', 0, 1, 1),
(4, 1, 4, 'School Cotton Socks (White - Pair)', 'UNI-SCK-WHT', '890100100004', '6115', 'Reinforced heel and toe cotton school socks', 35.00, 60.00, 5.00, 20, 'BIN-01', 0, 1, 1),
(5, 1, 1, 'School Tie & Belt Combo Set', 'UNI-TIE-SET', '890100100005', '6217', 'House stripe tie with adjustable buckle belt', 80.00, 140.00, 5.00, 15, 'BIN-02', 0, 1, 1),

-- Books & Textbooks (Category 2)
(6, 2, 1, 'NCERT Mathematics Class 5', 'BK-NCERT-MTH5', '978817450001', '4901', 'Prescribed mathematics textbook for Grade 5', 65.00, 75.00, 0.00, 15, 'RACK-B1', 0, 1, 1),
(7, 2, 1, 'NCERT Marigold English Class 5', 'BK-NCERT-ENG5', '978817450002', '4901', 'English reader text textbook Grade 5', 55.00, 65.00, 0.00, 15, 'RACK-B1', 0, 1, 1),
(8, 2, 1, 'NCERT Environmental Studies Class 5 (Looking Around)', 'BK-NCERT-EVS5', '978817450003', '4901', 'EVS comprehensive workbook & text Grade 5', 60.00, 70.00, 0.00, 15, 'RACK-B1', 0, 1, 1),
(9, 2, 1, 'NCERT Rimjhim Hindi Class 5', 'BK-NCERT-HIN5', '978817450004', '4901', 'Hindi literature coursebook Grade 5', 55.00, 65.00, 0.00, 15, 'RACK-B1', 0, 1, 1),

-- Stationery Supplies (Category 3)
(10, 3, 1, 'Four-Line English Notebook (172 Pages)', 'NB-4LINE-172', '890200200001', '4820', 'High quality paper notebook with school crest', 32.00, 50.00, 12.00, 30, 'RACK-C1', 0, 1, 1),
(11, 3, 1, 'Single-Line Hindi Notebook (172 Pages)', 'NB-1LINE-172', '890200200002', '4820', 'Ruled notebook for Hindi and Social Studies', 32.00, 50.00, 12.00, 30, 'RACK-C1', 0, 1, 1),
(12, 3, 1, 'Square-Grid Math Notebook (172 Pages)', 'NB-GRID-172', '890200200003', '4820', 'Grid ruled notebook for primary mathematics', 32.00, 50.00, 12.00, 30, 'RACK-C1', 0, 1, 1),
(13, 3, 2, 'Faber-Castell Art & Colouring Box (24 Shades)', 'STN-ART-BOX', '890200200004', '9609', 'Triangular coloured pencil set with sharpener', 120.00, 180.00, 18.00, 10, 'SHELF-S2', 0, 1, 1),
(14, 3, 1, 'A4 White Photocopier Paper Rim (500 Sheets - 75 GSM)', 'STN-PPR-A4', '890200200005', '4802', 'High-speed printer paper for admin & exams', 210.00, 270.00, 12.00, 8, 'STORE-BULK', 0, 1, 1),
(15, 3, 2, 'Non-Dust White Board Marker Set (4 Assorted Colors)', 'STN-MRK-4', '890200200006', '9608', 'Dry wipe whiteboard markers for classrooms', 85.00, 130.00, 18.00, 12, 'SHELF-S1', 0, 1, 1),

-- Sports Goods (Category 4)
(16, 4, 1, 'Cosco Championship Football (Size 5)', 'SPT-FTB-COS5', '890300300001', '9506', 'Official match grade synthetic leather football', 520.00, 750.00, 18.00, 4, 'SPT-ROOM', 0, 1, 1),
(17, 4, 3, 'Yonex Badminton Racket Set with Shuttlecock Box', 'SPT-BDM-SET', '890300300002', '9506', 'Pair of graphite alloy rackets with Mavis 350 shuttles', 890.00, 1250.00, 18.00, 3, 'SPT-ROOM', 0, 1, 1),

-- IT & Returnable Assets (Category 6 - Returnable Assets)
(18, 6, 1, 'Epson Full-HD Multimedia Classroom Projector', 'AST-PRJ-EPS01', 'AST8904001', '8528', '3500 Lumens HDMI classroom projector with remote', 32000.00, 0.00, 18.00, 1, 'ASSET-CUPBOARD', 1, 0, 1),
(19, 6, 1, 'Sony Alpha Digital 4K Event Camera Kit', 'AST-CAM-SNY01', 'AST8904002', '8525', 'Mirrorless 24MP camera with 18-55mm lens & carry case', 48000.00, 0.00, 18.00, 1, 'ASSET-CUPBOARD', 1, 0, 1),
(20, 6, 1, 'Ahuja Wireless Portable PA Collar Mic System', 'AST-MIC-AHU01', 'AST8904003', '8518', 'UHF dual-channel lapel mic with amplifier', 6500.00, 0.00, 18.00, 1, 'ASSET-CUPBOARD', 1, 0, 1)
ON DUPLICATE KEY UPDATE `item_name`=VALUES(`item_name`), `purchase_price`=VALUES(`purchase_price`), `sale_price`=VALUES(`sale_price`);

-- 3. Stock Balances in Godown 1 (Main School Store)
-- Providing realistic stock quantities (with a few below threshold to demonstrate low-stock alert)
INSERT INTO `store_stock_balances` (`godown_id`, `item_id`, `quantity`) VALUES
(1, 1, 45.00),   -- Polo Shirt Size 30 (Healthy Stock)
(1, 2, 4.00),    -- Polo Shirt Size 32 (LOW STOCK: Alert Threshold is 10)
(1, 3, 2.00),    -- Winter Blazer Size 32 (LOW STOCK: Alert Threshold is 5)
(1, 4, 120.00),  -- Socks White Pair
(1, 5, 8.00),    -- Tie & Belt Set (LOW STOCK: Alert Threshold is 15)
(1, 6, 85.00),   -- Math Class 5
(1, 7, 72.00),   -- English Class 5
(1, 8, 90.00),   -- EVS Class 5
(1, 9, 68.00),   -- Hindi Class 5
(1, 10, 210.00), -- 4-Line Notebooks
(1, 11, 195.00), -- 1-Line Notebooks
(1, 12, 180.00), -- Square Grid Notebooks
(1, 13, 34.00),  -- Colouring Boxes
(1, 14, 25.00),  -- A4 Paper Rims
(1, 15, 40.00),  -- Whiteboard Markers
(1, 16, 12.00),  -- Footballs
(1, 17, 8.00),   -- Badminton Sets
(1, 18, 4.00),   -- Epson Projectors
(1, 19, 2.00),   -- Sony Cameras
(1, 20, 5.00)    -- Ahuja Mic Kits
ON DUPLICATE KEY UPDATE `quantity`=VALUES(`quantity`);

-- 4. Pre-Configured Student Kits / Bundles
INSERT INTO `store_bundles` (`id`, `bundle_name`, `bundle_code`, `class_name`, `total_price`, `is_active`) VALUES
(1, 'Class 5 Complete Academic Kit (All Books + 6 Notebooks)', 'KIT-CLS05-ACAD', 'Class 5', 575.00, 1),
(2, 'Primary School Summer Uniform Pack (2 Shirts + Socks + Tie)', 'KIT-UNI-SUMMER', 'Class 5', 880.00, 1)
ON DUPLICATE KEY UPDATE `bundle_name`=VALUES(`bundle_name`), `total_price`=VALUES(`total_price`);

-- Bundle Items Mapping
INSERT INTO `store_bundle_items` (`bundle_id`, `item_id`, `quantity`, `unit_price`) VALUES
-- Kit 1: Class 5 Academic Kit
(1, 6, 1.00, 75.00),  -- Math 5
(1, 7, 1.00, 65.00),  -- English 5
(1, 8, 1.00, 70.00),  -- EVS 5
(1, 9, 1.00, 65.00),  -- Hindi 5
(1, 10, 2.00, 50.00), -- 2x 4-line notebooks (100)
(1, 11, 2.00, 50.00), -- 2x 1-line notebooks (100)
(1, 12, 2.00, 50.00), -- 2x square-grid notebooks (100)
-- Kit 2: Uniform Pack
(2, 1, 2.00, 350.00), -- 2x Polo Shirts (700)
(2, 4, 2.00, 60.00),  -- 2x Socks Pair (120)
(2, 5, 1.00, 140.00)  -- 1x Tie & Belt Combo (140)
ON DUPLICATE KEY UPDATE `quantity`=VALUES(`quantity`), `unit_price`=VALUES(`unit_price`);

-- 5. Realistic Student Sales (Spread across past 7 days to illuminate weekly trend & categories)
INSERT INTO `store_sales` (`id`, `invoice_no`, `session_id`, `branch_id`, `godown_id`, `student_id`, `customer_name`, `class_section`, `contact_no`, `sale_date`, `subtotal`, `discount_amount`, `tax_amount`, `grand_total`, `payment_mode`, `payment_reference`, `payment_status`, `billed_by_staff_id`) VALUES
(1, 'INV-2026-00101', 1, 1, 1, 1, 'LAKSHAY SHEKHAWAT', 'Class 5 - A', '9829100001', DATE_SUB(CURDATE(), INTERVAL 6 DAY), 1250.00, 50.00, 0.00, 1200.00, 'UPI', 'UPI-TXN-8849102', 'Paid', 1),
(2, 'INV-2026-00102', 1, 1, 1, 2, 'FARMAN KHILJI', 'Class 5 - B', '9829100002', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 880.00, 0.00, 0.00, 880.00, 'Cash', NULL, 'Paid', 1),
(3, 'INV-2026-00103', 1, 1, 1, 3, 'HARSHITA AGARWAL', 'Class 6 - A', '9829100003', DATE_SUB(CURDATE(), INTERVAL 4 DAY), 2450.00, 100.00, 0.00, 2350.00, 'DebitCard', 'POS-AUTH-99124', 'Paid', 1),
(4, 'INV-2026-00104', 1, 1, 1, 1, 'LAKSHAY SHEKHAWAT', 'Class 5 - A', '9829100001', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 575.00, 0.00, 0.00, 575.00, 'Cash', NULL, 'Paid', 1),
(5, 'INV-2026-00105', 1, 1, 1, 2, 'FARMAN KHILJI', 'Class 5 - B', '9829100002', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 1640.00, 40.00, 0.00, 1600.00, 'UPI', 'UPI-TXN-4491022', 'Paid', 1),
(6, 'INV-2026-00106', 1, 1, 1, 3, 'HARSHITA AGARWAL', 'Class 6 - A', '9829100003', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 2150.00, 50.00, 0.00, 2100.00, 'Cash', NULL, 'Paid', 1),
(7, 'INV-2026-00107', 1, 1, 1, 1, 'LAKSHAY SHEKHAWAT', 'Class 5 - A', '9829100001', CURDATE(), 930.00, 30.00, 0.00, 900.00, 'UPI', 'UPI-TXN-9988112', 'Paid', 1)
ON DUPLICATE KEY UPDATE `customer_name`=VALUES(`customer_name`), `grand_total`=VALUES(`grand_total`);

-- Sale Items for Category Breakdown
INSERT INTO `store_sale_items` (`id`, `sale_id`, `item_id`, `quantity`, `unit_price`, `discount`, `tax_amount`, `net_amount`) VALUES
-- Sale 1
(1, 1, 17, 1.00, 1250.00, 50.00, 0.00, 1200.00), -- Badminton Set (Sports)
-- Sale 2
(2, 2, 1, 2.00, 350.00, 0.00, 0.00, 700.00),    -- 2x Polo Shirts (Uniforms)
(3, 2, 4, 3.00, 60.00, 0.00, 0.00, 180.00),     -- 3x Socks (Uniforms)
-- Sale 3
(4, 3, 3, 1.00, 1450.00, 50.00, 0.00, 1400.00), -- Winter Blazer (Uniforms)
(5, 3, 16, 1.00, 750.00, 50.00, 0.00, 700.00),  -- Football (Sports)
(6, 3, 13, 1.00, 250.00, 0.00, 0.00, 250.00),   -- Art Box (Stationery)
-- Sale 4
(7, 4, 1, 1.00, 350.00, 0.00, 0.00, 350.00),    -- Polo Shirt
(8, 4, 10, 4.00, 50.00, 0.00, 0.00, 200.00),    -- 4x Notebooks (Stationery)
(9, 4, 4, 1.00, 60.00, 35.00, 0.00, 25.00),     -- Socks
-- Sale 5
(10, 5, 6, 2.00, 75.00, 0.00, 0.00, 150.00),    -- Math Books (Books)
(11, 5, 7, 2.00, 65.00, 0.00, 0.00, 130.00),    -- English Books (Books)
(12, 5, 8, 2.00, 70.00, 0.00, 0.00, 140.00),    -- EVS Books (Books)
(13, 5, 1, 3.00, 380.00, 40.00, 0.00, 1100.00), -- Uniform
(14, 5, 13, 1.00, 80.00, 0.00, 0.00, 80.00),    -- Stationery
-- Sale 6
(15, 6, 3, 1.00, 1450.00, 0.00, 0.00, 1450.00), -- Blazer
(16, 6, 10, 8.00, 50.00, 0.00, 0.00, 400.00),   -- Notebooks
(17, 6, 13, 1.00, 250.00, 0.00, 0.00, 250.00),  -- Art Box
-- Sale 7 (Today)
(18, 7, 1, 2.00, 350.00, 0.00, 0.00, 700.00),   -- Polo Shirts
(19, 7, 10, 4.00, 50.00, 0.00, 0.00, 200.00)    -- Notebooks
ON DUPLICATE KEY UPDATE `net_amount`=VALUES(`net_amount`);

-- 6. Department Requisitions (Powering Department Consumption Widget)
INSERT INTO `store_requisitions` (`id`, `requisition_no`, `staff_id`, `department`, `request_date`, `purpose`, `status`, `approved_by_staff_id`, `issue_date`) VALUES
(1, 'REQ-2026-001', 1, 'Academic Examination Cell', DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'Term-1 Exam papers printing and question sheets', 'Issued', 1, DATE_SUB(CURDATE(), INTERVAL 5 DAY)),
(2, 'REQ-2026-002', 2, 'Senior Science Laboratory', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'Physics and Chemistry practical notebooks and markers', 'Issued', 1, DATE_SUB(CURDATE(), INTERVAL 3 DAY)),
(3, 'REQ-2026-003', 3, 'Physical Education & Sports', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'Annual Inter-House Sports tournament equipment', 'Issued', 1, DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(4, 'REQ-2026-004', 1, 'Administrative Main Office', CURDATE(), 'Whiteboard markers and printer paper reams for staff rooms', 'Issued', 1, CURDATE())
ON DUPLICATE KEY UPDATE `purpose`=VALUES(`purpose`), `status`=VALUES(`status`);

INSERT INTO `store_requisition_items` (`id`, `requisition_id`, `item_id`, `requested_qty`, `approved_qty`, `issued_qty`, `remarks`) VALUES
-- Exam Cell
(1, 1, 14, 15.00, 15.00, 15.00, 'Issued for term exam papers'),
-- Science Lab
(2, 2, 10, 40.00, 40.00, 40.00, 'Practical records'),
(3, 2, 15, 6.00, 6.00, 6.00, 'Lab presentation markers'),
-- Sports Department
(4, 3, 16, 6.00, 6.00, 6.00, 'Football tournament matches'),
(5, 3, 17, 4.00, 4.00, 4.00, 'Badminton trial matches'),
-- Main Office
(6, 4, 14, 8.00, 8.00, 8.00, 'Admin office printer'),
(7, 4, 15, 12.00, 12.00, 12.00, 'Staff room whiteboard supply')
ON DUPLICATE KEY UPDATE `issued_qty`=VALUES(`issued_qty`);

-- 7. Asset Loans (Active & Overdue Asset Checkouts)
INSERT INTO `store_loans` (`id`, `loan_slip_no`, `item_id`, `godown_id`, `issued_to_staff_id`, `quantity`, `issue_date`, `due_return_date`, `condition_on_issue`, `status`, `remarks`, `issued_by_staff_id`) VALUES
(1, 'LN-2026-0001', 18, 1, 2, 1.00, DATE_SUB(CURDATE(), INTERVAL 4 DAY), DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'Working condition with HDMI and power cable', 'Overdue', 'Borrowed for Multimedia Science Seminar in Auditorium', 1),
(2, 'LN-2026-0002', 19, 1, 3, 1.00, DATE_SUB(CURDATE(), INTERVAL 1 DAY), DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Camera kit with 64GB SD card and battery pack', 'Issued', 'Annual Inter-House Sports meet photography', 1),
(3, 'LN-2026-0003', 20, 1, 1, 1.00, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'Collar mic receiver tested, rechargeable batteries charged', 'Issued', 'Morning Assembly and Chief Guest address', 1)
ON DUPLICATE KEY UPDATE `status`=VALUES(`status`);
