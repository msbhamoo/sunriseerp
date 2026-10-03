<?php
// Store Controller: Staff & Department Material Requisitions (Stock Out)
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';
$staff_id = $user['id'];

// 1. Process Requisition Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_requisition'])) {
    $department = trim($_POST['department'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $req_staff_id = (int)($_POST['staff_id'] ?? $staff_id);
    $request_date = !empty($_POST['request_date']) ? $_POST['request_date'] : date('Y-m-d');
    $items = $_POST['items'] ?? [];

    if ($req_staff_id <= 0 || empty($department)) {
        $err = "Recipient Staff Member and Department are required.";
    } elseif (empty($items)) {
        $err = "Please select at least one consumable item to issue.";
    } else {
        $db->begin_transaction();
        try {
            $req_no = 'REQ-' . date('Ymd') . '-' . rand(1000, 9999);
            $status = 'Issued'; // Immediate inventory deduction upon store authorization
            
            $stmt = $db->prepare("INSERT INTO store_requisitions (requisition_no, staff_id, department, request_date, purpose, status, approved_by_staff_id, issue_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssis", $req_no, $req_staff_id, $department, $request_date, $purpose, $status, $staff_id, $request_date);
            $stmt->execute();
            $req_id = $db->insert_id;

            $godown_id = 1;
            $items_added = 0;

            foreach ($items as $it) {
                $item_id = (int)($it['item_id'] ?? 0);
                $qty = (float)($it['qty'] ?? 0);
                $remarks = trim($it['remarks'] ?? '');

                if ($item_id > 0 && $qty > 0) {
                    $item_stmt = $db->prepare("INSERT INTO store_requisition_items (requisition_id, item_id, requested_qty, approved_qty, issued_qty, remarks) VALUES (?, ?, ?, ?, ?, ?)");
                    $item_stmt->bind_param("iiddds", $req_id, $item_id, $qty, $qty, $qty, $remarks);
                    $item_stmt->execute();

                    // Deduct stock immediately
                    $bal_stmt = $db->prepare("UPDATE store_stock_balances SET quantity = GREATEST(0, quantity - ?) WHERE godown_id = ? AND item_id = ?");
                    $bal_stmt->bind_param("dii", $qty, $godown_id, $item_id);
                    $bal_stmt->execute();

                    // Fetch balance after deduction
                    $cur_bal_q = $db->query("SELECT quantity FROM store_stock_balances WHERE godown_id = $godown_id AND item_id = $item_id LIMIT 1");
                    $cur_bal = $cur_bal_q->fetch_assoc()['quantity'] ?? 0;

                    // Audit ledger
                    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                    $led_stmt = $db->prepare("INSERT INTO store_stock_ledger (godown_id, item_id, transaction_type, reference_id, reference_no, qty_out, balance_after, created_by_staff_id, ip_address) VALUES (?, ?, 'STAFF_ISSUE', ?, ?, ?, ?, ?, ?)");
                    $led_stmt->bind_param("iiisddis", $godown_id, $item_id, $req_id, $req_no, $qty, $cur_bal, $staff_id, $ip);
                    $led_stmt->execute();

                    $items_added++;
                }
            }

            if ($items_added === 0) {
                throw new Exception("No valid items with quantity > 0 were specified.");
            }

            $db->commit();
            $msg = "Requisition slip <strong>{$req_no}</strong> authorized and stock deducted successfully!";
        } catch (Exception $e) {
            $db->rollback();
            $err = "Error processing requisition: " . $e->getMessage();
        }
    }
}

// 2. Search & Filter Parameters
$search_query = trim($_GET['q'] ?? '');
$filter_dept = trim($_GET['dept'] ?? '');

$where_clauses = ["1=1"];
if (!empty($search_query)) {
    $sq = $db->real_escape_string($search_query);
    $where_clauses[] = "(r.requisition_no LIKE '%$sq%' OR s.name LIKE '%$sq%' OR s.surname LIKE '%$sq%' OR r.department LIKE '%$sq%' OR r.purpose LIKE '%$sq%')";
}
if (!empty($filter_dept)) {
    $fd = $db->real_escape_string($filter_dept);
    $where_clauses[] = "r.department = '$fd'";
}
$where_sql = implode(' AND ', $where_clauses);

// 3. Fetch KPI Metrics
$total_reqs = $db->query("SELECT COUNT(*) as c FROM store_requisitions")->fetch_assoc()['c'] ?? 0;
$total_units_issued = $db->query("SELECT COALESCE(SUM(issued_qty), 0) as s FROM store_requisition_items")->fetch_assoc()['s'] ?? 0;
$total_depts_active = $db->query("SELECT COUNT(DISTINCT department) as c FROM store_requisitions WHERE department IS NOT NULL AND department != ''")->fetch_assoc()['c'] ?? 0;

// 4. Fetch Staff & Consumable Materials
$staff_list = $db->query("SELECT id, name, surname, employee_id FROM staff WHERE is_active = 1 ORDER BY name ASC");
$staff_arr = [];
while ($st = $staff_list->fetch_assoc()) {
    $staff_arr[] = $st;
}

$dept_list = $db->query("SELECT DISTINCT department_name FROM department WHERE department_name IS NOT NULL AND department_name != '' ORDER BY department_name ASC");
$dept_arr = [];
while ($d = $dept_list->fetch_assoc()) {
    $dept_arr[] = $d['department_name'];
}

// Fallback department names if ERP department table is sparse
$custom_depts = ['Academic Examination Cell', 'Senior Science Laboratory', 'Physical Education & Sports', 'Administrative Main Office', 'Library & Reading Room', 'Primary Wing Staff Room', 'Computer Lab'];
foreach ($custom_depts as $cd) {
    if (!in_array($cd, $dept_arr)) {
        $dept_arr[] = $cd;
    }
}
sort($dept_arr);

// Fetch Available Consumable Items
$items_list = $db->query("SELECT i.id, i.item_name, i.item_code, u.short_code as unit, COALESCE(b.quantity, 0) as stock 
    FROM store_items i 
    LEFT JOIN store_units u ON u.id = i.unit_id
    LEFT JOIN store_stock_balances b ON b.item_id = i.id AND b.godown_id = 1
    WHERE i.is_active = 1 AND i.is_returnable_asset = 0 
    ORDER BY i.item_name ASC");

$items_arr = [];
while ($it = $items_list->fetch_assoc()) {
    $items_arr[] = $it;
}

// 5. Fetch Requisitions History with Item Count
$requisitions_query = "SELECT r.*, s.name, s.surname, s.employee_id,
    COUNT(ri.id) as item_count,
    COALESCE(SUM(ri.issued_qty), 0) as total_qty
    FROM store_requisitions r
    LEFT JOIN staff s ON s.id = r.staff_id
    LEFT JOIN store_requisition_items ri ON ri.requisition_id = r.id
    WHERE {$where_sql}
    GROUP BY r.id
    ORDER BY r.id DESC
    LIMIT 100";
$requisitions = $db->query($requisitions_query);

render_header('Staff & Department Requisitions', 'requisitions');
?>

<style>
/* Requisitions Module Modern Styles */
.req-kpi-card {
    background: #fff;
    border: 1px solid var(--card-border);
    border-radius: var(--radius-lg);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: all 0.2s;
    box-shadow: var(--shadow-sm);
}
.req-kpi-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}
.kpi-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

/* Off-Canvas Slide Drawer */
.offcanvas-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: stretch;
    justify-content: flex-end;
}
.offcanvas-drawer {
    background: #fff;
    width: 650px;
    max-width: 95%;
    height: 100%;
    overflow-y: auto;
    box-shadow: var(--shadow-xl);
    display: flex;
    flex-direction: column;
    animation: slideInRight 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes slideInRight {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}
.drawer-header {
    padding: 20px 28px;
    border-bottom: 1.5px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    position: sticky;
    top: 0;
    z-index: 10;
}
.drawer-body {
    padding: 24px 28px;
    flex: 1;
}
.drawer-footer {
    padding: 18px 28px;
    border-top: 1.5px solid #f1f5f9;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 12px;
    position: sticky;
    bottom: 0;
    z-index: 10;
}
.req-item-row {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.15s;
}
.req-item-row:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
</style>

<?php if ($msg): ?>
    <div class="alert alert-success" style="display:flex; justify-content:space-between; align-items:center;">
        <div><i class="fa fa-circle-check" style="font-size:18px; color:#10b981;"></i> <?= $msg ?></div>
        <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:#065f46; cursor:pointer; font-size:16px;">&times;</button>
    </div>
<?php endif; ?>
<?php if ($err): ?>
    <div class="alert alert-danger" style="display:flex; justify-content:space-between; align-items:center;">
        <div><i class="fa fa-triangle-exclamation" style="font-size:18px; color:#ef4444;"></i> <?= htmlspecialchars($err) ?></div>
        <button type="button" onclick="this.parentElement.remove()" style="background:none; border:none; color:#991b1b; cursor:pointer; font-size:16px;">&times;</button>
    </div>
<?php endif; ?>

<!-- Header Strip: Metrics & Primary Action CTA -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; flex-wrap:wrap; gap:16px;">
    <!-- Metric Badges -->
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; flex:1; max-width: 680px;">
        <!-- KPI 1 -->
        <div class="req-kpi-card" style="border-left: 4px solid var(--primary);">
            <div>
                <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Requisitions</div>
                <div style="font-size: 22px; font-weight: 800; color: var(--text-main); margin-top: 4px;"><?= number_format($total_reqs) ?></div>
            </div>
            <div class="kpi-icon-box" style="background:#eef2ff; color:var(--primary);">
                <i class="fa fa-file-signature"></i>
            </div>
        </div>

        <!-- KPI 2 -->
        <div class="req-kpi-card" style="border-left: 4px solid #10b981;">
            <div>
                <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Items Issued</div>
                <div style="font-size: 22px; font-weight: 800; color: #047857; margin-top: 4px;"><?= number_format($total_units_issued) ?> <span style="font-size:12px; font-weight:600; color:#64748b;">Units</span></div>
            </div>
            <div class="kpi-icon-box" style="background:#dcfce7; color:#10b981;">
                <i class="fa fa-boxes-packing"></i>
            </div>
        </div>

        <!-- KPI 3 -->
        <div class="req-kpi-card" style="border-left: 4px solid #f59e0b;">
            <div>
                <div style="font-size: 11.5px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Active Departments</div>
                <div style="font-size: 22px; font-weight: 800; color: #b45309; margin-top: 4px;"><?= number_format($total_depts_active) ?></div>
            </div>
            <div class="kpi-icon-box" style="background:#fef3c7; color:#f59e0b;">
                <i class="fa fa-building-user"></i>
            </div>
        </div>
    </div>

    <!-- Main CTA Button -->
    <div>
        <button type="button" class="btn btn-primary" style="padding: 12px 22px; font-size: 14px; font-weight: 700; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);" onclick="openRequisitionDrawer()">
            <i class="fa fa-plus-circle"></i> Issue Material Requisition
        </button>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="" style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
            <!-- Search Input -->
            <div style="flex:2; min-width:240px; position:relative;">
                <input type="text" name="q" value="<?= htmlspecialchars($search_query) ?>" class="form-control" placeholder="Search by slip no, staff member, department, or purpose..." style="padding-left:38px;">
                <i class="fa fa-magnifying-glass" style="position:absolute; left:13px; top:12px; color:#94a3b8;"></i>
            </div>

            <!-- Department Filter -->
            <div style="flex:1; min-width:200px;">
                <select name="dept" class="form-control">
                    <option value="">-- All Departments --</option>
                    <?php foreach ($dept_arr as $dname): ?>
                        <option value="<?= htmlspecialchars($dname) ?>" <?= $filter_dept === $dname ? 'selected' : '' ?>><?= htmlspecialchars($dname) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:10px 18px;"><i class="fa fa-filter"></i> Filter</button>
            <?php if (!empty($search_query) || !empty($filter_dept)): ?>
                <a href="<?= BASE_URL ?>requisitions" class="btn btn-secondary"><i class="fa fa-rotate-left"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Requisitions List Table -->
<div class="card">
    <div class="card-header" style="background:#fff; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <span class="card-title"><i class="fa fa-clipboard-check" style="color: var(--primary);"></i> Material Issue History & Slips</span>
            <div style="font-size:12px; color:var(--text-muted); margin-top:2px;">Audit record of all stationery, consumables, and supplies issued to school departments</div>
        </div>
        <span style="font-size:12.5px; color:#64748b; font-weight:600;">Showing <?= $requisitions ? $requisitions->num_rows : 0 ?> records</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:14%;">Slip No</th>
                    <th style="width:20%;">Recipient Staff Member</th>
                    <th style="width:20%;">Department / Section</th>
                    <th style="width:12%;">Issue Date</th>
                    <th style="width:12%;">Items Count</th>
                    <th style="width:10%;">Status</th>
                    <th style="width:12%; text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($requisitions && $requisitions->num_rows > 0): ?>
                    <?php while ($rq = $requisitions->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong style="color:#0f172a; font-family:monospace; font-size:13px;"><?= htmlspecialchars($rq['requisition_no']) ?></strong>
                            </td>
                            <td>
                                <div style="font-weight:700; color:#1e293b;">
                                    <?= htmlspecialchars($rq['name'] . ' ' . $rq['surname']) ?>
                                </div>
                                <?php if (!empty($rq['employee_id'])): ?>
                                    <div style="font-size:11px; color:#64748b;">Emp ID: <?= htmlspecialchars($rq['employee_id']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-weight:600; color:#334155; display:inline-flex; align-items:center; gap:6px;">
                                    <i class="fa fa-building" style="color:#94a3b8; font-size:12px;"></i>
                                    <?= htmlspecialchars($rq['department']) ?>
                                </span>
                            </td>
                            <td>
                                <span style="color:#475569; font-weight:500; font-size:13px;">
                                    <?= date('d M Y', strtotime($rq['request_date'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-info" style="font-weight:700;">
                                    <?= number_format($rq['item_count']) ?> Item<?= $rq['item_count'] > 1 ? 's' : '' ?> (<?= number_format($rq['total_qty']) ?> Qty)
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-success">
                                    <i class="fa fa-circle-check"></i> <?= htmlspecialchars($rq['status'] ?? 'Issued') ?>
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <button type="button" class="btn btn-secondary" style="padding:5px 12px; font-size:12px; font-weight:600;" onclick="viewRequisitionDetails(<?= $rq['id'] ?>)">
                                    <i class="fa fa-eye"></i> View Slip
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align:center; padding: 48px 20px; color:#94a3b8;">
                            <i class="fa fa-hand-holding-hand" style="font-size:36px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>
                            No material requisitions found.<br>
                            Click <strong>"Issue Material Requisition"</strong> above to record staff consumption.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================================== -->
<!-- OFF-CANVAS SLIDE-OVER DRAWER: ISSUE REQUISITION                 -->
<!-- ============================================================== -->
<!-- OFF-CANVAS SLIDE-OVER DRAWER: ISSUE REQUISITION (Matches Image 3) -->
<div class="modern-drawer-backdrop" id="requisitionDrawer" onclick="handleDrawerBackdropClick(event)">
    <div class="modern-drawer" style="width:620px;">
        <!-- Drawer Header Matching Image 3 -->
        <div class="modern-drawer-header">
            <h3>
                <i class="fa fa-circle-plus"></i> Add Material Entry
            </h3>
            <button type="button" class="modern-drawer-close" onclick="closeRequisitionDrawer()">&times;</button>
        </div>

        <!-- Drawer Body Form -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="requisitionForm">
                <!-- Top Two-Column Grid: In/Out and Date -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
                    <div class="form-group">
                        <label>In / Out *</label>
                        <select name="entry_type" class="form-control" style="font-weight:600;">
                            <option value="Outward" selected>Outward (Issue to Staff)</option>
                            <option value="Inward">Inward (Return to Store)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date *</label>
                        <input type="date" name="request_date" id="f_request_date" class="form-control" value="<?= date('Y-m-d') ?>" required style="font-weight:600;">
                    </div>
                </div>

                <!-- Materials / Items Nested Card Section (Matches Image 3) -->
                <div class="drawer-card-section">
                    <div class="drawer-card-header">
                        <div class="drawer-card-title">
                            <i class="fa fa-cubes" style="color:var(--primary);"></i> Materials / Items *
                        </div>
                        <button type="button" class="drawer-btn-add" onclick="addReqRow()">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                    </div>

                    <!-- Items Container -->
                    <div id="reqItemsContainer">
                        <!-- Default Line 0 in Image 3 Card Style -->
                        <div class="drawer-item-box" id="req_row_0">
                            <div class="form-group" style="margin-bottom:12px; position:relative;">
                                <label style="font-size:12px; font-weight:700; color:#334155;">Material / Item Name *</label>
                                <input type="hidden" name="items[0][item_id]" id="req_item_id_0" value="" required>
                                <div style="position:relative;">
                                    <input type="text" id="req_item_search_0" class="form-control" style="font-weight:600; padding-right:28px;" placeholder="Select or search item..." autocomplete="off" onfocus="openItemDropdown(0)" oninput="filterItemList(0)">
                                    <span onclick="clearItemRowSelection(0)" id="clear_item_btn_0" style="display:none; position:absolute; right:10px; top:10px; cursor:pointer; color:#94a3b8; font-size:16px;">&times;</span>
                                </div>
                                <!-- Searchable Items Dropdown Panel -->
                                <div id="item_dropdown_list_0" class="suggestions-box" style="position:absolute; top:100%; left:0; right:0; max-height:220px; overflow-y:auto; z-index:10000; box-shadow:0 15px 30px rgba(0,0,0,0.15); border:1.5px solid #cbd5e1; border-radius:10px; background:#fff; display:none; margin-top:4px;">
                                    <?php foreach ($items_arr as $it): ?>
                                        <div class="suggestion-item item-option-0" 
                                             data-id="<?= $it['id'] ?>"
                                             data-name="<?= htmlspecialchars($it['item_name']) ?>"
                                             data-code="<?= htmlspecialchars($it['item_code']) ?>"
                                             data-stock="<?= $it['stock'] ?>"
                                             data-unit="<?= htmlspecialchars($it['unit']) ?>"
                                             onclick="selectRowItem(0, <?= $it['id'] ?>, '<?= htmlspecialchars(addslashes($it['item_name'])) ?>', '<?= htmlspecialchars(addslashes($it['item_code'])) ?>', <?= $it['stock'] ?>, '<?= htmlspecialchars(addslashes($it['unit'])) ?>')"
                                             style="padding:10px 14px; cursor:pointer; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                                            <div>
                                                <div style="font-weight:700; color:#0f172a; font-size:13px;"><?= htmlspecialchars($it['item_name']) ?></div>
                                                <div style="font-size:11px; color:#64748b;">Code: <code><?= htmlspecialchars($it['item_code']) ?></code></div>
                                            </div>
                                            <span class="badge <?= $it['stock'] > 0 ? 'badge-success' : 'badge-danger' ?>" style="font-size:11px;">
                                                <?= number_format($it['stock']) ?> <?= htmlspecialchars($it['unit']) ?>
                                            </span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Row for Quantity, Unit, Rate/Unit, Total Cost and Trash Button (Matches Image 3) -->
                            <div style="display:flex; align-items:flex-end; gap:10px;">
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Quantity</label>
                                    <input type="number" step="0.01" min="0.01" name="items[0][qty]" id="req_qty_0" class="form-control" value="1" required style="font-weight:600;" oninput="updateReqTotal(0)">
                                </div>
                                <div style="width:110px;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Unit</label>
                                    <input type="text" id="req_unit_0" class="form-control" value="Select" readonly style="background:#f8fafc; font-weight:600;">
                                </div>
                                <div style="width:100px;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Rate / Unit</label>
                                    <input type="number" step="0.01" id="req_rate_0" class="form-control" value="0.00" style="font-weight:600;" oninput="updateReqTotal(0)">
                                </div>
                                <div style="width:105px;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Total Cost</label>
                                    <input type="text" id="req_total_0" class="form-control" value="0.00" readonly style="background:#f8fafc; font-weight:700; color:#0f172a;">
                                </div>
                                <button type="button" class="drawer-btn-del" disabled style="opacity:0.4; cursor:not-allowed;" title="Delete item">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recipient Staff & Contact Fields (Matches Image 3) -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group" style="position:relative;">
                        <label>Carried By / Recipient Staff *</label>
                        <input type="hidden" name="staff_id" id="f_staff_id" value="" required>
                        <input type="text" id="staff_search_input" class="form-control" placeholder="e.g. John Doe" autocomplete="off" onfocus="openStaffDropdown()" oninput="filterStaffList()" style="font-weight:600;">
                        <!-- Staff Dropdown -->
                        <div id="staff_dropdown_list" class="suggestions-box" style="position:absolute; top:100%; left:0; right:0; max-height:220px; overflow-y:auto; z-index:10000; box-shadow:0 15px 30px rgba(0,0,0,0.15); border:1.5px solid #cbd5e1; border-radius:10px; background:#fff; display:none; margin-top:4px;">
                            <?php foreach ($staff_arr as $s): ?>
                                <div class="suggestion-item staff-option-item" 
                                     data-id="<?= $s['id'] ?>" 
                                     data-name="<?= htmlspecialchars($s['name'] . ' ' . $s['surname']) ?>" 
                                     data-empid="<?= htmlspecialchars($s['employee_id'] ?? '') ?>"
                                     onclick="selectStaffMember(<?= $s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'] . ' ' . $s['surname'])) ?>', '<?= htmlspecialchars(addslashes($s['employee_id'] ?? '')) ?>')"
                                     style="padding:10px 14px; cursor:pointer; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                                    <div>
                                        <div style="font-weight:700; color:#0f172a; font-size:13.5px;"><?= htmlspecialchars($s['name'] . ' ' . $s['surname']) ?></div>
                                        <?php if (!empty($s['employee_id'])): ?>
                                            <div style="font-size:11.5px; color:#64748b;">Emp ID: <code><?= htmlspecialchars($s['employee_id']) ?></code></div>
                                        <?php endif; ?>
                                    </div>
                                    <span style="font-size:11.5px; color:var(--primary); font-weight:700;">Select <i class="fa fa-arrow-right"></i></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Contact</label>
                        <input type="text" name="contact_no" id="f_contact_no" class="form-control" placeholder="e.g. +91 9876543210">
                    </div>
                </div>

                <!-- From / To (Party / Department) -->
                <div class="form-group" style="margin-bottom:16px; position:relative;">
                    <label>From / To (Department / Party) *</label>
                    <input type="text" name="department" id="f_department" class="form-control" placeholder="Sender for inward, receiver for outward" required autocomplete="off" onfocus="openDeptDropdown()" oninput="filterDeptList()" style="font-weight:600;">
                    <!-- Department dropdown -->
                    <div id="dept_dropdown_list" class="suggestions-box" style="position:absolute; top:100%; left:0; right:0; max-height:200px; overflow-y:auto; z-index:10000; box-shadow:0 15px 30px rgba(0,0,0,0.15); border:1.5px solid #cbd5e1; border-radius:10px; background:#fff; display:none; margin-top:4px;">
                        <?php foreach ($dept_arr as $dname): ?>
                            <div class="suggestion-item dept-option-item" 
                                 data-dept="<?= htmlspecialchars($dname) ?>"
                                 onclick="selectDepartment('<?= htmlspecialchars(addslashes($dname)) ?>')"
                                 style="padding:10px 14px; cursor:pointer; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:8px;">
                                <i class="fa fa-building-user" style="color:var(--primary); font-size:12px;"></i>
                                <span style="font-weight:600; color:#1e293b; font-size:13px;"><?= htmlspecialchars($dname) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Vehicle No & Gate Pass No -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Vehicle No.</label>
                        <input type="text" name="vehicle_no" class="form-control" placeholder="Select or enter vehicle no.">
                    </div>
                    <div class="form-group">
                        <label>Gate Pass No</label>
                        <input type="text" name="gate_pass_no" class="form-control" value="MGP-<?= date('ymd') ?>-<?= rand(10,99) ?>" readonly style="background:#f8fafc; font-weight:700; color:#334155;">
                    </div>
                </div>

                <!-- Driver / Bearer Name -->
                <div class="form-group" style="margin-bottom:20px;">
                    <label>Driver / Bearer Name</label>
                    <input type="text" name="driver_name" class="form-control" placeholder="Select or enter driver/bearer name">
                </div>

                <div class="form-group" style="margin-bottom:10px;">
                    <label>Purpose / Remarks</label>
                    <input type="text" name="purpose" id="f_purpose" class="form-control" placeholder="e.g. Science lab equipment / exam material issue">
                </div>
            </form>
        </div>

        <!-- Drawer Footer Matching Image 3 (Cancel, Save, Save & Print) -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeRequisitionDrawer()">Cancel</button>
            <button type="submit" form="requisitionForm" name="save_requisition" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('requisitionForm').submit();">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL: VIEW REQUISITION DETAILS & SLIP                         -->
<!-- ============================================================== -->
<div id="viewRequisitionModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(3px); align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:16px; width:640px; max-width:95%; box-shadow:var(--shadow-xl); overflow:hidden; border:1px solid var(--card-border);">
        <div style="padding:18px 24px; border-bottom:1.5px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:36px; height:36px; border-radius:10px; background:#eef2ff; color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:18px;">
                    <i class="fa fa-file-invoice"></i>
                </div>
                <div>
                    <h4 id="v_slip_no" style="margin:0; font-size:16px; font-weight:800; color:#0f172a;">Requisition Details</h4>
                    <div style="font-size:11.5px; color:#64748b;" id="v_date"></div>
                </div>
            </div>
            <span onclick="closeViewModal()" style="cursor:pointer; font-size:22px; color:#94a3b8;">&times;</span>
        </div>

        <div style="padding:24px; max-height:450px; overflow-y:auto;">
            <!-- Header Grid -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; background:#f8fafc; padding:14px 16px; border-radius:10px; margin-bottom:18px; border:1px solid #e2e8f0;">
                <div>
                    <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Recipient Staff</div>
                    <div style="font-weight:700; color:#0f172a; font-size:14px; margin-top:2px;" id="v_staff"></div>
                </div>
                <div>
                    <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Department / Section</div>
                    <div style="font-weight:700; color:#0f172a; font-size:14px; margin-top:2px;" id="v_dept"></div>
                </div>
                <div style="grid-column:span 2; border-top:1px dashed #e2e8f0; padding-top:8px; margin-top:4px;">
                    <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Purpose</div>
                    <div style="color:#334155; font-size:13px; margin-top:2px;" id="v_purpose"></div>
                </div>
            </div>

            <!-- Items Table -->
            <div style="font-weight:700; font-size:13px; color:#1e293b; margin-bottom:8px;">Issued Materials Breakdown:</div>
            <div class="table-responsive">
                <table class="table" style="margin:0;">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Code</th>
                            <th style="text-align:right;">Quantity</th>
                        </tr>
                    </thead>
                    <tbody id="v_items_body">
                        <!-- Populated by AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <div style="background:#f8fafc; padding:14px 24px; border-top:1.5px solid #f1f5f9; display:flex; justify-content:flex-end;">
            <button type="button" class="btn btn-secondary" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<!-- ============================================================== -->
<!-- CLIENT JAVASCRIPT: DRAWER & DYNAMIC ROW CONTROLS                -->
<!-- ============================================================== -->
<script>
const availableItems = <?= json_encode($items_arr) ?>;
let reqRowIndex = 1;

// 1. Drawer open/close
function openRequisitionDrawer() {
    const d = document.getElementById('requisitionDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
    document.getElementById('staff_search_input')?.focus();
}

function closeRequisitionDrawer() {
    const d = document.getElementById('requisitionDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function handleDrawerBackdropClick(e) {
    if (e.target.id === 'requisitionDrawer') {
        closeRequisitionDrawer();
    }
}

// Searchable Staff Combobox Handlers
function openStaffDropdown() {
    document.getElementById('staff_dropdown_list').style.display = 'block';
}

function filterStaffList() {
    const q = document.getElementById('staff_search_input').value.trim().toLowerCase();
    const items = document.querySelectorAll('.staff-option-item');
    let matchCount = 0;

    items.forEach(el => {
        const name = (el.getAttribute('data-name') || '').toLowerCase();
        const empid = (el.getAttribute('data-empid') || '').toLowerCase();

        if (name.includes(q) || empid.includes(q)) {
            el.style.display = 'flex';
            matchCount++;
        } else {
            el.style.display = 'none';
        }
    });

    document.getElementById('staff_dropdown_list').style.display = 'block';
}

function selectStaffMember(id, name, empid) {
    document.getElementById('f_staff_id').value = id;
    document.getElementById('staff_search_input').value = name + (empid ? ' (' + empid + ')' : '');
    document.getElementById('staff_dropdown_list').style.display = 'none';
    document.getElementById('clear_staff_btn').style.display = 'block';
    document.getElementById('selected_staff_badge').style.display = 'inline-block';
}

function clearStaffSelection() {
    document.getElementById('f_staff_id').value = '';
    document.getElementById('staff_search_input').value = '';
    document.getElementById('clear_staff_btn').style.display = 'none';
    document.getElementById('selected_staff_badge').style.display = 'none';
    filterStaffList();
    document.getElementById('staff_search_input').focus();
}

// Close staff dropdown on outside click
document.addEventListener('click', function(e) {
    const input = document.getElementById('staff_search_input');
    const list = document.getElementById('staff_dropdown_list');
    if (input && list && !input.contains(e.target) && !list.contains(e.target)) {
        list.style.display = 'none';
    }
});

// Searchable Department Combobox Handlers
function openDeptDropdown() {
    document.getElementById('dept_dropdown_list').style.display = 'block';
}

function filterDeptList() {
    const q = document.getElementById('f_department').value.trim().toLowerCase();
    const items = document.querySelectorAll('.dept-option-item');
    let matchCount = 0;

    items.forEach(el => {
        const dept = (el.getAttribute('data-dept') || '').toLowerCase();
        if (dept.includes(q)) {
            el.style.display = 'flex';
            matchCount++;
        } else {
            el.style.display = 'none';
        }
    });

    document.getElementById('clear_dept_btn').style.display = (q.length > 0) ? 'block' : 'none';
    document.getElementById('dept_dropdown_list').style.display = 'block';
}

function selectDepartment(deptName) {
    document.getElementById('f_department').value = deptName;
    document.getElementById('dept_dropdown_list').style.display = 'none';
    document.getElementById('clear_dept_btn').style.display = 'block';
}

function clearDeptSelection() {
    document.getElementById('f_department').value = '';
    document.getElementById('clear_dept_btn').style.display = 'none';
    filterDeptList();
    document.getElementById('f_department').focus();
}

// Close department dropdown on outside click
document.addEventListener('click', function(e) {
    const input = document.getElementById('f_department');
    const list = document.getElementById('dept_dropdown_list');
    if (input && list && !input.contains(e.target) && !list.contains(e.target)) {
        list.style.display = 'none';
    }
});

// Searchable Item Row Combobox Handlers
function openItemDropdown(idx) {
    document.getElementById('item_dropdown_list_' + idx).style.display = 'block';
}

function filterItemList(idx) {
    const q = document.getElementById('req_item_search_' + idx).value.trim().toLowerCase();
    const items = document.querySelectorAll('.item-option-' + idx);
    let matchCount = 0;

    items.forEach(el => {
        const name = (el.getAttribute('data-name') || '').toLowerCase();
        const code = (el.getAttribute('data-code') || '').toLowerCase();

        if (name.includes(q) || code.includes(q)) {
            el.style.display = 'flex';
            matchCount++;
        } else {
            el.style.display = 'none';
        }
    });

    document.getElementById('clear_item_btn_' + idx).style.display = (q.length > 0) ? 'block' : 'none';
    document.getElementById('item_dropdown_list_' + idx).style.display = 'block';
}

function selectRowItem(idx, id, name, code, stock, unit, rate) {
    document.getElementById('req_item_id_' + idx).value = id;
    document.getElementById('req_item_search_' + idx).value = name + ' (' + code + ')';
    document.getElementById('item_dropdown_list_' + idx).style.display = 'none';
    document.getElementById('clear_item_btn_' + idx).style.display = 'block';
    
    // Update Unit and Rate
    const unitInput = document.getElementById('req_unit_' + idx);
    if (unitInput) unitInput.value = unit || 'Units';

    const rateInput = document.getElementById('req_rate_' + idx);
    if (rateInput && rate !== undefined) {
        rateInput.value = parseFloat(rate || 0).toFixed(2);
    }
    updateReqTotal(idx);
}

function updateReqTotal(idx) {
    const qty = parseFloat(document.getElementById('req_qty_' + idx)?.value || 0);
    const rate = parseFloat(document.getElementById('req_rate_' + idx)?.value || 0);
    const totalEl = document.getElementById('req_total_' + idx);
    if (totalEl) {
        totalEl.value = (qty * rate).toFixed(2);
    }
}

function clearItemRowSelection(idx) {
    document.getElementById('req_item_id_' + idx).value = '';
    document.getElementById('req_item_search_' + idx).value = '';
    document.getElementById('clear_item_btn_' + idx).style.display = 'none';
    const unitInput = document.getElementById('req_unit_' + idx);
    if (unitInput) unitInput.value = 'Select';
    const rateInput = document.getElementById('req_rate_' + idx);
    if (rateInput) rateInput.value = '0.00';
    updateReqTotal(idx);
    filterItemList(idx);
    document.getElementById('req_item_search_' + idx).focus();
}

// Close item dropdowns on outside click
document.addEventListener('click', function(e) {
    const activeLists = document.querySelectorAll('[id^="item_dropdown_list_"]');
    activeLists.forEach(list => {
        const idx = list.id.replace('item_dropdown_list_', '');
        const input = document.getElementById('req_item_search_' + idx);
        if (input && list && !input.contains(e.target) && !list.contains(e.target)) {
            list.style.display = 'none';
        }
    });
});

// 3. Add dynamic item row (Matches Image 3 sub-card layout)
function addReqRow() {
    const curIdx = reqRowIndex;
    let itemsOptionsHtml = '';
    availableItems.forEach(it => {
        const escapedName = it.item_name.replace(/'/g, "\\'");
        const escapedCode = it.item_code.replace(/'/g, "\\'");
        const escapedUnit = (it.unit || '').replace(/'/g, "\\'");
        const itemRate = parseFloat(it.purchase_price || it.sale_price || 0);
        const stockBadge = it.stock > 0 ? 'badge-success' : 'badge-danger';
        itemsOptionsHtml += `
            <div class="suggestion-item item-option-${curIdx}" 
                 data-id="${it.id}"
                 data-name="${it.item_name}"
                 data-code="${it.item_code}"
                 data-stock="${it.stock}"
                 data-unit="${it.unit}"
                 onclick="selectRowItem(${curIdx}, ${it.id}, '${escapedName}', '${escapedCode}', ${it.stock}, '${escapedUnit}', ${itemRate})"
                 style="padding:10px 14px; cursor:pointer; border-bottom:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <div style="font-weight:700; color:#0f172a; font-size:13px;">${it.item_name}</div>
                    <div style="font-size:11px; color:#64748b;">Code: <code>${it.item_code}</code></div>
                </div>
                <span class="badge ${stockBadge}" style="font-size:11px;">
                    ${it.stock} ${it.unit}
                </span>
            </div>
        `;
    });

    const row = document.createElement('div');
    row.className = 'drawer-item-box';
    row.id = 'req_row_' + curIdx;
    row.innerHTML = `
        <div class="form-group" style="margin-bottom:12px; position:relative;">
            <label style="font-size:12px; font-weight:700; color:#334155;">Material / Item Name *</label>
            <input type="hidden" name="items[${curIdx}][item_id]" id="req_item_id_${curIdx}" value="" required>
            <div style="position:relative;">
                <input type="text" id="req_item_search_${curIdx}" class="form-control" style="font-weight:600; padding-right:28px;" placeholder="Select or search item..." autocomplete="off" onfocus="openItemDropdown(${curIdx})" oninput="filterItemList(${curIdx})">
                <span onclick="clearItemRowSelection(${curIdx})" id="clear_item_btn_${curIdx}" style="display:none; position:absolute; right:10px; top:10px; cursor:pointer; color:#94a3b8; font-size:16px;">&times;</span>
            </div>
            <!-- Searchable Items Dropdown Panel -->
            <div id="item_dropdown_list_${curIdx}" class="suggestions-box" style="position:absolute; top:100%; left:0; right:0; max-height:220px; overflow-y:auto; z-index:10000; box-shadow:0 15px 30px rgba(0,0,0,0.15); border:1.5px solid #cbd5e1; border-radius:10px; background:#fff; display:none; margin-top:4px;">
                ${itemsOptionsHtml}
            </div>
        </div>

        <div style="display:flex; align-items:flex-end; gap:10px;">
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Quantity</label>
                <input type="number" step="0.01" min="0.01" name="items[${curIdx}][qty]" id="req_qty_${curIdx}" class="form-control" value="1" required style="font-weight:600;" oninput="updateReqTotal(${curIdx})">
            </div>
            <div style="width:110px;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Unit</label>
                <input type="text" id="req_unit_${curIdx}" class="form-control" value="Select" readonly style="background:#f8fafc; font-weight:600;">
            </div>
            <div style="width:100px;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Rate / Unit</label>
                <input type="number" step="0.01" id="req_rate_${curIdx}" class="form-control" value="0.00" style="font-weight:600;" oninput="updateReqTotal(${curIdx})">
            </div>
            <div style="width:105px;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Total Cost</label>
                <input type="text" id="req_total_${curIdx}" class="form-control" value="0.00" readonly style="background:#f8fafc; font-weight:700; color:#0f172a;">
            </div>
            <button type="button" class="drawer-btn-del" onclick="document.getElementById('req_row_${curIdx}').remove()" title="Delete item">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    `;
    document.getElementById('reqItemsContainer').appendChild(row);
    reqRowIndex++;
}

// 4. View Requisition Details Modal
function viewRequisitionDetails(reqId) {
    fetch('<?= BASE_URL ?>api.php?action=get_requisition_details&id=' + reqId)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const r = data.requisition;
                document.getElementById('v_slip_no').innerText = r.requisition_no;
                document.getElementById('v_date').innerText = 'Issued on ' + r.request_date;
                document.getElementById('v_staff').innerText = r.staff_name + (r.employee_id ? ' (' + r.employee_id + ')' : '');
                document.getElementById('v_dept').innerText = r.department;
                document.getElementById('v_purpose').innerText = r.purpose || 'General Academic / Administrative Consumption';

                let itemsHtml = '';
                data.items.forEach(it => {
                    itemsHtml += `
                        <tr>
                            <td><strong>${it.item_name}</strong></td>
                            <td><code>${it.item_code}</code></td>
                            <td style="text-align:right; font-weight:700; color:var(--primary);">${it.issued_qty} ${it.unit}</td>
                        </tr>
                    `;
                });
                document.getElementById('v_items_body').innerHTML = itemsHtml;
                document.getElementById('viewRequisitionModal').style.display = 'flex';
            } else {
                alert('Could not load requisition details: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => alert('Failed to connect: ' + err));
}

function closeViewModal() {
    document.getElementById('viewRequisitionModal').style.display = 'none';
}
</script>

<?php render_footer(); ?>
