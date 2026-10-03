<?php
// Store Controller: Purchase Orders (PO) Management
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';
$staff_id = $user['id'];

// Create Purchase Order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_po'])) {
    $vendor_id = (int)($_POST['vendor_id'] ?? 0);
    $po_date = $_POST['po_date'] ?? date('Y-m-d');
    $delivery_expected_date = $_POST['delivery_expected_date'] ?? null;
    $remarks = trim($_POST['remarks'] ?? '');
    $items = $_POST['items'] ?? [];

    if (!$vendor_id || empty($items)) {
        $err = "Please select a vendor and add at least one line item.";
    } else {
        $db->begin_transaction();
        try {
            $po_number = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
            $total_cost = 0;

            foreach ($items as $it) {
                $qty = (float)($it['qty'] ?? 0);
                $rate = (float)($it['rate'] ?? 0);
                $total_cost += ($qty * $rate);
            }

            $stmt = $db->prepare("INSERT INTO store_purchase_orders (po_number, vendor_id, po_date, delivery_expected_date, subtotal, total_amount, status, remarks, created_by_staff_id) VALUES (?, ?, ?, ?, ?, ?, 'Approved', ?, ?)");
            $stmt->bind_param("sisssdsi", $po_number, $vendor_id, $po_date, $delivery_expected_date, $total_cost, $total_cost, $remarks, $staff_id);
            $stmt->execute();
            $po_id = $db->insert_id;

            foreach ($items as $it) {
                $item_id = (int)($it['item_id'] ?? 0);
                $qty = (float)($it['qty'] ?? 0);
                $rate = (float)($it['rate'] ?? 0);
                $line_cost = $qty * $rate;

                if ($item_id > 0 && $qty > 0) {
                    $item_stmt = $db->prepare("INSERT INTO store_po_items (po_id, item_id, quantity, unit_price, total_cost) VALUES (?, ?, ?, ?, ?)");
                    $item_stmt->bind_param("iiddd", $po_id, $item_id, $qty, $rate, $line_cost);
                    $item_stmt->execute();
                }
            }

            $db->commit();
            $msg = "Purchase Order {$po_number} created successfully.";
        } catch (Exception $e) {
            $db->rollback();
            $err = "Error creating PO: " . $e->getMessage();
        }
    }
}

// Master lists
$vendors = $db->query("SELECT id, name FROM store_vendors WHERE is_active = 1 ORDER BY name ASC");
$items_list = $db->query("SELECT id, item_name, item_code, purchase_price FROM store_items WHERE is_active = 1 ORDER BY item_name ASC");
$items_arr = [];
while ($it = $items_list->fetch_assoc()) {
    $items_arr[] = $it;
}

// PO List
$pos = $db->query("SELECT p.*, v.name as vendor_name, s.name as staff_name, s.surname as staff_surname
    FROM store_purchase_orders p
    JOIN store_vendors v ON v.id = p.vendor_id
    LEFT JOIN staff s ON s.id = p.created_by_staff_id
    ORDER BY p.id DESC LIMIT 20");

render_header('Purchase Orders (PO) Management', 'po');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- Top Header with + New Purchase Order CTA -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:14px;">
    <div>
        <h2 style="font-size:22px; font-weight:800; color:#0f172a; margin:0; letter-spacing:-0.5px;">Purchase Orders (PO) Registry</h2>
        <p style="font-size:13.5px; color:#64748b; margin-top:4px;">Manage supplier purchase orders, delivery terms, and material procurement</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openPoDrawer()" style="padding:10px 20px; font-size:13.5px; font-weight:700;">
        <i class="fa fa-plus-circle"></i> Create Purchase Order
    </button>
</div>

<!-- Slide-Over Drawer for New Purchase Order (Matches Image 3) -->
<div id="poDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closePoDrawer()">
    <div class="modern-drawer" style="width:640px;">
        <!-- Header -->
        <div class="modern-drawer-header">
            <h3>
                <i class="fa fa-circle-plus"></i> Add Purchase Order Entry
            </h3>
            <button type="button" class="modern-drawer-close" onclick="closePoDrawer()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="poForm">
                <!-- Two Column Grid: Vendor & PO Date -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Vendor / Supplier *</label>
                        <select name="vendor_id" class="form-control" required style="font-weight:600;">
                            <option value="">-- Choose Vendor --</option>
                            <?php 
                            $vendors->data_seek(0);
                            while ($v = $vendors->fetch_assoc()): ?>
                                <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>PO Date *</label>
                        <input type="date" name="po_date" class="form-control" value="<?= date('Y-m-d') ?>" required style="font-weight:600;">
                    </div>
                </div>

                <!-- Delivery Expected & Terms -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:18px;">
                    <div class="form-group">
                        <label>Expected Delivery Date</label>
                        <input type="date" name="delivery_expected_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" style="font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label>Payment / Delivery Terms</label>
                        <input type="text" name="remarks" class="form-control" placeholder="e.g. 30-day credit / cash on delivery">
                    </div>
                </div>

                <!-- Ordered Materials / Items Nested Card Section (Matches Image 3) -->
                <div class="drawer-card-section">
                    <div class="drawer-card-header">
                        <div class="drawer-card-title">
                            <i class="fa fa-boxes-packing" style="color:var(--primary);"></i> Ordered Materials / Items *
                        </div>
                        <button type="button" class="drawer-btn-add" onclick="addPoItemRow()">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                    </div>

                    <div id="poItemsContainer">
                        <!-- Line Item 0 in Image 3 Box Style -->
                        <div class="drawer-item-box" id="po_row_0">
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="font-size:12px; font-weight:700; color:#334155;">Material / Item Name *</label>
                                <select name="items[0][item_id]" class="form-control" required style="font-weight:600;" onchange="setPoItemRate(this, 0)">
                                    <option value="">-- Select Material / Item --</option>
                                    <?php foreach ($items_arr as $it): ?>
                                        <option value="<?= $it['id'] ?>" data-price="<?= $it['purchase_price'] ?>"><?= htmlspecialchars($it['item_name']) ?> (<?= $it['item_code'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="display:flex; align-items:flex-end; gap:10px;">
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Quantity</label>
                                    <input type="number" step="0.01" min="0.01" name="items[0][qty]" id="po_qty_0" class="form-control" value="1" required style="font-weight:600;" oninput="updatePoLineTotal(0)">
                                </div>
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Rate / Unit (₹)</label>
                                    <input type="number" step="0.01" name="items[0][rate]" id="po_rate_0" class="form-control" value="0.00" required style="font-weight:600;" oninput="updatePoLineTotal(0)">
                                </div>
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Total Cost (₹)</label>
                                    <input type="text" id="po_total_0" class="form-control" value="0.00" readonly style="background:#f8fafc; font-weight:700; color:#0f172a;">
                                </div>
                                <button type="button" class="drawer-btn-del" disabled style="opacity:0.4; cursor:not-allowed;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer Matching Image 3 -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closePoDrawer()">Cancel</button>
            <button type="submit" form="poForm" name="save_po" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('poForm').submit();">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </div>
</div>

<script>
let poItemIndex = 1;
const poItemsMaster = <?= json_encode($items_arr) ?>;

function openPoDrawer() {
    const d = document.getElementById('poDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

function closePoDrawer() {
    const d = document.getElementById('poDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function setPoItemRate(sel, idx) {
    if (!sel || sel.selectedIndex < 0) return;
    const opt = sel.options[sel.selectedIndex];
    const price = opt ? opt.getAttribute('data-price') : 0;
    const rateInput = document.getElementById('po_rate_' + idx);
    if (rateInput) {
        rateInput.value = parseFloat(price || 0).toFixed(2);
    }
    updatePoLineTotal(idx);
}

function updatePoLineTotal(idx) {
    const qty = parseFloat(document.getElementById('po_qty_' + idx)?.value || 0);
    const rate = parseFloat(document.getElementById('po_rate_' + idx)?.value || 0);
    const totalEl = document.getElementById('po_total_' + idx);
    if (totalEl) {
        totalEl.value = (qty * rate).toFixed(2);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function addPoItemRow() {
    const idx = poItemIndex;
    let options = '<option value="">-- Select Material / Item --</option>';
    if (Array.isArray(poItemsMaster) && poItemsMaster.length > 0) {
        poItemsMaster.forEach(it => {
            const escapedName = escapeHtml(it.item_name);
            const escapedCode = escapeHtml(it.item_code);
            const rate = parseFloat(it.purchase_price || 0).toFixed(2);
            options += `<option value="${it.id}" data-price="${rate}">${escapedName} (${escapedCode})</option>`;
        });
    }

    const box = document.createElement('div');
    box.className = 'drawer-item-box';
    box.id = 'po_row_' + idx;
    box.innerHTML = `
        <div class="form-group" style="margin-bottom:12px;">
            <label style="font-size:12px; font-weight:700; color:#334155;">Material / Item Name *</label>
            <select name="items[${idx}][item_id]" class="form-control" required style="font-weight:600;" onchange="setPoItemRate(this, ${idx})">
                ${options}
            </select>
        </div>
        <div style="display:flex; align-items:flex-end; gap:10px;">
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Quantity</label>
                <input type="number" step="0.01" min="0.01" name="items[${idx}][qty]" id="po_qty_${idx}" class="form-control" value="1" required style="font-weight:600;" oninput="updatePoLineTotal(${idx})">
            </div>
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Rate / Unit (₹)</label>
                <input type="number" step="0.01" name="items[${idx}][rate]" id="po_rate_${idx}" class="form-control" value="0.00" required style="font-weight:600;" oninput="updatePoLineTotal(${idx})">
            </div>
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Total Cost (₹)</label>
                <input type="text" id="po_total_${idx}" class="form-control" value="0.00" readonly style="background:#f8fafc; font-weight:700; color:#0f172a;">
            </div>
            <button type="button" class="drawer-btn-del" onclick="document.getElementById('po_row_${idx}').remove()">
                <i class="fa fa-trash"></i>
            </button>
        </div>
    `;
    const container = document.getElementById('poItemsContainer');
    if (container) {
        container.appendChild(box);
        poItemIndex++;
    }
}
</script>

<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
        <span class="card-title" style="font-size: 16px;"><i class="fa fa-list" style="color: var(--primary);"></i> Purchase Orders Registry</span>
        <span style="font-size:12.5px; color:#64748b;">Showing recent purchase requisitions</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 18%;">PO Number</th>
                    <th style="width: 26%;">Vendor / Supplier</th>
                    <th style="width: 14%;">Issue Date</th>
                    <th style="width: 14%;">Expected Delivery</th>
                    <th style="width: 14%;">Total Valuation</th>
                    <th style="width: 14%;">Workflow Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($pos && $pos->num_rows > 0): ?>
                    <?php while ($p = $pos->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12.5px; border:1px solid #e2e8f0; display:inline-block;">
                                    <?= htmlspecialchars($p['po_number']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($p['vendor_name']) ?></strong>
                            </td>
                            <td style="color:#64748b; font-weight:600;"><?= htmlspecialchars($p['po_date']) ?></td>
                            <td>
                                <span style="color:#334155; font-weight:600;"><?= htmlspecialchars($p['delivery_expected_date'] ?? '-') ?></span>
                            </td>
                            <td>
                                <strong style="color: var(--primary); font-size:14px;">₹<?= number_format($p['total_amount'], 2) ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-success"><i class="fa fa-circle-check"></i> <?= htmlspecialchars($p['status']) ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 40px; color:#64748b;"><i class="fa fa-file-invoice" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No purchase orders generated yet. Use the form above to issue your first PO.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>
