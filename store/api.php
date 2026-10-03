<?php
/**
 * AJAX Live Search API for School Store POS & Modules
 */
session_start();
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if (empty($_SESSION['store_user']) || !$_SESSION['store_user']['logged_in']) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$db = get_db_connection();
$action = $_GET['action'] ?? '';

// 1. Search Active Students
if ($action === 'search_students') {
    $q = trim($_GET['q'] ?? '');
    if (strlen($q) < 1) {
        echo json_encode([]);
        exit();
    }

    $search = "%{$q}%";
    $query = "SELECT s.id, s.admission_no, s.roll_no, s.firstname, s.lastname, s.mobileno, s.father_name,
              c.class, sec.section
              FROM students s
              LEFT JOIN student_session ss ON ss.student_id = s.id
              LEFT JOIN classes c ON c.id = ss.class_id
              LEFT JOIN sections sec ON sec.id = ss.section_id
              WHERE s.is_active = 'yes' 
              AND (
                  s.admission_no LIKE ? OR 
                  s.firstname LIKE ? OR 
                  s.lastname LIKE ? OR 
                  CONCAT(s.firstname, ' ', s.lastname) LIKE ? OR
                  s.mobileno LIKE ?
              )
              GROUP BY s.id
              ORDER BY s.firstname ASC
              LIMIT 15";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sssss", $search, $search, $search, $search, $search);
    $stmt->execute();
    $res = $stmt->get_result();

    $students = [];
    while ($row = $res->fetch_assoc()) {
        $full_name = trim($row['firstname'] . ' ' . $row['lastname']);
        $class_sec = ($row['class'] ? $row['class'] : '') . ($row['section'] ? ' - ' . $row['section'] : '');
        $students[] = [
            'id'           => $row['id'],
            'admission_no' => $row['admission_no'] ?? '',
            'name'         => $full_name,
            'class_section'=> $class_sec,
            'father_name'  => $row['father_name'] ?? '',
            'mobile'       => $row['mobileno'] ?? '',
            'label'        => "{$full_name} ({$class_sec}) - Adm: {$row['admission_no']}"
        ];
    }

    echo json_encode($students);
    exit();
}

// 2. Search Store Items
if ($action === 'search_items') {
    $q = trim($_GET['q'] ?? '');
    $search = "%{$q}%";
    
    $query = "SELECT i.id, i.item_name, i.item_code, i.barcode, i.sale_price, i.purchase_price,
              COALESCE(b.quantity, 0) as current_stock, u.short_code as unit
              FROM store_items i
              LEFT JOIN store_units u ON u.id = i.unit_id
              LEFT JOIN store_stock_balances b ON b.item_id = i.id AND b.godown_id = 1
              WHERE i.is_active = 1
              AND (i.item_name LIKE ? OR i.item_code LIKE ? OR i.barcode LIKE ?)
              ORDER BY i.item_name ASC
              LIMIT 20";

    $stmt = $db->prepare($query);
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $res = $stmt->get_result();

    $items = [];
    while ($row = $res->fetch_assoc()) {
        $items[] = [
            'id'             => $row['id'],
            'name'           => $row['item_name'],
            'code'           => $row['item_code'],
            'barcode'        => $row['barcode'],
            'sale_price'     => (float)$row['sale_price'],
            'purchase_price' => (float)$row['purchase_price'],
            'stock'          => (float)$row['current_stock'],
            'unit'           => $row['unit'] ?? 'Pcs'
        ];
    }

    echo json_encode($items);
    exit();
}

// 3. Quick Auto-Add Category
if ($action === 'quick_add_category') {
    $name = trim($_POST['name'] ?? '');
    if (empty($name)) {
        echo json_encode(['status' => 'error', 'message' => 'Category name is required.']);
        exit();
    }

    $code = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 8));
    
    // Check if category already exists
    $chk = $db->prepare("SELECT id, name FROM store_categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
    $chk->bind_param("s", $name);
    $chk->execute();
    $exist = $chk->get_result()->fetch_assoc();

    if ($exist) {
        echo json_encode(['status' => 'success', 'id' => $exist['id'], 'name' => $exist['name'], 'is_existing' => true]);
        exit();
    }

    $stmt = $db->prepare("INSERT INTO store_categories (name, code) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $code);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'id' => $db->insert_id, 'name' => $name, 'is_existing' => false]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $db->error]);
    }
    exit();
}

// 4. Quick Auto-Add Unit of Measure
if ($action === 'quick_add_unit') {
    $unit_name = trim($_POST['unit_name'] ?? '');
    $short_code = trim($_POST['short_code'] ?? '');

    if (empty($unit_name)) {
        echo json_encode(['status' => 'error', 'message' => 'Unit name is required.']);
        exit();
    }

    if (empty($short_code)) {
        $short_code = ucfirst(substr($unit_name, 0, 3));
    }

    // Check if unit already exists
    $chk = $db->prepare("SELECT id, unit_name, short_code FROM store_units WHERE LOWER(unit_name) = LOWER(?) OR LOWER(short_code) = LOWER(?) LIMIT 1");
    $chk->bind_param("ss", $unit_name, $short_code);
    $chk->execute();
    $exist = $chk->get_result()->fetch_assoc();

    if ($exist) {
        echo json_encode(['status' => 'success', 'id' => $exist['id'], 'unit_name' => $exist['unit_name'], 'short_code' => $exist['short_code'], 'is_existing' => true]);
        exit();
    }

    $allow_decimal = 0;
    $stmt = $db->prepare("INSERT INTO store_units (unit_name, short_code, allow_decimal) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $unit_name, $short_code, $allow_decimal);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'id' => $db->insert_id, 'unit_name' => $unit_name, 'short_code' => $short_code, 'is_existing' => false]);
    } else {
        echo json_encode(['status' => 'error', 'message' => $db->error]);
    }
    exit();
}

// 5. Fetch Bundle Items for POS One-Click Add
if ($action === 'get_bundle_items') {
    $bundle_id = (int)($_GET['bundle_id'] ?? 0);
    $query = "SELECT bi.quantity, bi.unit_price, i.id as item_id, i.item_name, i.item_code,
              COALESCE(b.quantity, 0) as stock
              FROM store_bundle_items bi
              JOIN store_items i ON i.id = bi.item_id
              LEFT JOIN store_stock_balances b ON b.item_id = i.id AND b.godown_id = 1
              WHERE bi.bundle_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $bundle_id);
    $stmt->execute();
    $res = $stmt->get_result();

    $items = [];
    while ($r = $res->fetch_assoc()) {
        $items[] = [
            'item_id'    => (int)$r['item_id'],
            'item_name'  => $r['item_name'],
            'item_code'  => $r['item_code'],
            'qty'        => (float)$r['quantity'],
            'unit_price' => (float)$r['unit_price'],
            'stock'      => (float)$r['stock']
        ];
    }

    echo json_encode($items);
    exit();
}

// 6. Fetch Requisition Details & Items for Quick Drawer/Modal Preview
if ($action === 'get_requisition_details') {
    $req_id = (int)($_GET['id'] ?? 0);
    $req = $db->query("SELECT r.*, s.name, s.surname, s.employee_id 
        FROM store_requisitions r 
        JOIN staff s ON s.id = r.staff_id 
        WHERE r.id = $req_id LIMIT 1")->fetch_assoc();

    if (!$req) {
        echo json_encode(['status' => 'error', 'message' => 'Requisition not found']);
        exit();
    }

    $items_res = $db->query("SELECT ri.*, i.item_name, i.item_code, u.short_code as unit 
        FROM store_requisition_items ri 
        JOIN store_items i ON i.id = ri.item_id 
        LEFT JOIN store_units u ON u.id = i.unit_id 
        WHERE ri.requisition_id = $req_id");

    $items = [];
    while ($it = $items_res->fetch_assoc()) {
        $items[] = [
            'item_name' => $it['item_name'],
            'item_code' => $it['item_code'],
            'requested_qty' => (float)$it['requested_qty'],
            'issued_qty' => (float)$it['issued_qty'],
            'unit' => $it['unit'] ?? 'Pcs',
            'remarks' => $it['remarks'] ?? ''
        ];
    }

    echo json_encode([
        'status' => 'success',
        'requisition' => [
            'id' => $req['id'],
            'requisition_no' => $req['requisition_no'],
            'staff_name' => trim($req['name'] . ' ' . $req['surname']),
            'employee_id' => $req['employee_id'] ?? '',
            'department' => $req['department'],
            'request_date' => date('d M Y', strtotime($req['request_date'])),
            'purpose' => $req['purpose'] ?? '',
            'status' => $req['status']
        ],
        'items' => $items
    ]);
    exit();
}

echo json_encode(['status' => 'invalid_action']);
