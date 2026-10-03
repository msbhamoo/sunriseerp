<?php
// Store Controller: Inward GRN (Goods Received Note) & Purchase Stock-In
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';
$staff_id = $user['id'];

// Process GRN Inward Receipt
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_grn'])) {
    $vendor_id = (int)($_POST['vendor_id'] ?? 0);
    $godown_id = (int)($_POST['godown_id'] ?? 1);
    $invoice_no = trim($_POST['invoice_no'] ?? '');
    $challan_no = trim($_POST['challan_no'] ?? '');
    $grn_date = $_POST['grn_date'] ?? date('Y-m-d');
    $items = $_POST['items'] ?? [];

    if (!$vendor_id || empty($items)) {
        $err = "Please select a vendor and add at least one material line.";
    } else {
        $db->begin_transaction();
        try {
            $grn_number = 'GRN-' . date('Ymd') . '-' . rand(1000, 9999);
            $total_amount = 0;

            foreach ($items as $it) {
                $qty = (float)($it['qty'] ?? 0);
                $rate = (float)($it['rate'] ?? 0);
                $total_amount += ($qty * $rate);
            }

            // Insert GRN Master
            $stmt = $db->prepare("INSERT INTO store_grn (grn_number, vendor_id, godown_id, challan_no, invoice_no, grn_date, total_amount, created_by_staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siisssdi", $grn_number, $vendor_id, $godown_id, $challan_no, $invoice_no, $grn_date, $total_amount, $staff_id);
            $stmt->execute();
            $grn_id = $db->insert_id;

            // Insert GRN items and update stock balances & ledger
            foreach ($items as $it) {
                $item_id = (int)($it['item_id'] ?? 0);
                $qty = (float)($it['qty'] ?? 0);
                $rate = (float)($it['rate'] ?? 0);
                $line_total = $qty * $rate;
                $batch_no = trim($it['batch_no'] ?? '');

                if ($item_id > 0 && $qty > 0) {
                    $item_stmt = $db->prepare("INSERT INTO store_grn_items (grn_id, item_id, received_qty, unit_cost, total_cost, batch_no) VALUES (?, ?, ?, ?, ?, ?)");
                    $item_stmt->bind_param("iiddds", $grn_id, $item_id, $qty, $rate, $line_total, $batch_no);
                    $item_stmt->execute();

                    // Increment store_stock_balances
                    $bal_stmt = $db->prepare("INSERT INTO store_stock_balances (godown_id, item_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
                    $bal_stmt->bind_param("iid", $godown_id, $item_id, $qty);
                    $bal_stmt->execute();

                    // Get new on-hand balance for audit ledger
                    $cur_bal_q = $db->query("SELECT quantity FROM store_stock_balances WHERE godown_id = $godown_id AND item_id = $item_id LIMIT 1");
                    $cur_bal = $cur_bal_q->fetch_assoc()['quantity'] ?? $qty;

                    // Append immutable ledger entry
                    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                    $led_stmt = $db->prepare("INSERT INTO store_stock_ledger (godown_id, item_id, transaction_type, reference_id, reference_no, qty_in, unit_price, balance_after, created_by_staff_id, ip_address) VALUES (?, ?, 'PURCHASE_GRN', ?, ?, ?, ?, ?, ?, ?)");
                    $led_stmt->bind_param("iiisdddis", $godown_id, $item_id, $grn_id, $grn_number, $qty, $rate, $cur_bal, $staff_id, $ip);
                    $led_stmt->execute();
                }
            }

            $db->commit();
            $msg = "GRN {$grn_number} successfully recorded and stock balances updated!";
        } catch (Exception $e) {
            $db->rollback();
            $err = "Failed to process inward GRN: " . $e->getMessage();
        }
    }
}

// Masters for Form
$vendors = $db->query("SELECT id, name FROM store_vendors WHERE is_active = 1 ORDER BY name ASC");
$godowns = $db->query("SELECT id, godown_name FROM store_godowns WHERE is_active = 1 ORDER BY id ASC");
$item_list = $db->query("SELECT id, item_name, item_code, purchase_price FROM store_items WHERE is_active = 1 ORDER BY item_name ASC");
$items_array = [];
while ($it = $item_list->fetch_assoc()) {
    $items_array[] = $it;
}

// Recent GRN entries
$recent_grns = $db->query("SELECT g.*, v.name as vendor_name, gd.godown_name, s.name as staff_name, s.surname as staff_surname
    FROM store_grn g
    JOIN store_vendors v ON v.id = g.vendor_id
    JOIN store_godowns gd ON gd.id = g.godown_id
    LEFT JOIN staff s ON s.id = g.created_by_staff_id
    ORDER BY g.id DESC LIMIT 10");

render_header('Inward Stock & Goods Received (GRN)', 'grn');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- Action Bar with Top CTA matching Image 3 -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 22px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:800; color:#0f172a; letter-spacing:-0.4px;">Goods Received Notes (GRN)</h2>
        <p style="margin:3px 0 0 0; font-size:13px; color:#64748b;">Record warehouse inward deliveries, vendor bills, and update godown stock ledger</p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-primary" onclick="openGrnDrawer()" style="box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); font-weight:700; padding:10px 18px; border-radius:10px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa fa-circle-plus"></i> Record Inward Entry (GRN)
        </button>
    </div>
</div>

<!-- Modern Right Drawer: Inward GRN (Strictly matching Image 3 layout) -->
<div id="grnDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeGrnDrawer()">
    <div class="modern-drawer" style="width: 660px;">
        <!-- Header -->
        <div class="modern-drawer-header">
            <h3><i class="fa fa-dolly" style="color:var(--primary); font-size:18px;"></i> Record Inward Entry (GRN)</h3>
            <button type="button" class="modern-drawer-close" onclick="closeGrnDrawer()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="grnForm">
                <!-- Two Column Grid: Vendor & Godown -->
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Select Supplier / Vendor *</label>
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
                        <label>Destination Godown / Store *</label>
                        <select name="godown_id" class="form-control" required style="font-weight:600;">
                            <?php 
                            $godowns->data_seek(0);
                            while ($g = $godowns->fetch_assoc()): ?>
                                <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['godown_name']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Invoice No, Challan No, Receipt Date -->
                <div style="display:grid; grid-template-columns:1fr 1fr 1.2fr; gap:14px; margin-bottom:18px;">
                    <div class="form-group">
                        <label>Vendor Bill / Invoice No.</label>
                        <input type="text" name="invoice_no" class="form-control" placeholder="e.g. INV-2026-9081">
                    </div>
                    <div class="form-group">
                        <label>Challan / Gate Pass</label>
                        <input type="text" name="challan_no" class="form-control" placeholder="e.g. CH-890">
                    </div>
                    <div class="form-group">
                        <label>Receipt Date *</label>
                        <input type="date" name="grn_date" class="form-control" value="<?= date('Y-m-d') ?>" required style="font-weight:600;">
                    </div>
                </div>

                <!-- Received Materials / Items Nested Card Section (Matches Image 3) -->
                <div class="drawer-card-section">
                    <div class="drawer-card-header">
                        <div class="drawer-card-title">
                            <i class="fa fa-boxes-stacked" style="color:var(--primary);"></i> Received Materials / Items *
                        </div>
                        <button type="button" class="drawer-btn-add" onclick="addGrnItemRow()">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                    </div>

                    <div id="grnItemsContainer">
                        <!-- Line Item 0 in Image 3 Box Style -->
                        <div class="drawer-item-box" id="grn_row_0">
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="font-size:12px; font-weight:700; color:#334155;">Material / Item Name *</label>
                                <select name="items[0][item_id]" class="form-control" required style="font-weight:600;" onchange="setGrnCost(this, 0)">
                                    <option value="">-- Select Material / Item --</option>
                                    <?php foreach ($items_array as $it): ?>
                                        <option value="<?= $it['id'] ?>" data-cost="<?= $it['purchase_price'] ?>"><?= htmlspecialchars($it['item_name']) ?> (<?= $it['item_code'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="display:flex; align-items:flex-end; gap:10px;">
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Qty Received</label>
                                    <input type="number" step="0.01" min="0.01" name="items[0][qty]" id="grn_qty_0" class="form-control" value="1" required style="font-weight:600;" oninput="updateGrnLineTotal(0)">
                                </div>
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Unit Cost (₹)</label>
                                    <input type="number" step="0.01" name="items[0][rate]" id="rate_0" class="form-control" value="0.00" required style="font-weight:600;" oninput="updateGrnLineTotal(0)">
                                </div>
                                <div style="flex:1.2;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Batch / Lot #</label>
                                    <input type="text" name="items[0][batch_no]" class="form-control" placeholder="Batch / SN">
                                </div>
                                <div style="width:90px; text-align:right;">
                                    <label style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px; display:block;">Line Total</label>
                                    <div id="grn_total_0" style="font-weight:800; font-size:13.5px; color:#0f172a; padding:9px 0;">₹0.00</div>
                                </div>
                                <div style="width:34px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Grand Total Summary Banner -->
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#ffffff; border:1px dashed #cbd5e1; border-radius:10px; padding:12px 16px; margin-top:12px;">
                        <span style="font-size:13px; font-weight:700; color:#475569;">Total Inward Value:</span>
                        <span id="grnGrandTotal" style="font-size:17px; font-weight:900; color:#047857;">₹0.00</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer (Exact Image 3 style: Cancel, Save, Save & Print) -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeGrnDrawer()">Cancel</button>
            <button type="submit" form="grnForm" name="save_grn" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save & Record GRN
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('grnForm').submit();">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </div>
</div>

<script>
const itemsData = <?= json_encode($items_array) ?>;
let rowIdx = 1;

function openGrnDrawer() {
    const d = document.getElementById('grnDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

function closeGrnDrawer() {
    const d = document.getElementById('grnDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function setGrnCost(selectElem, idx) {
    if (!selectElem || selectElem.selectedIndex < 0) return;
    const selectedOption = selectElem.options[selectElem.selectedIndex];
    const cost = selectedOption ? selectedOption.getAttribute('data-cost') : 0;
    const input = document.getElementById('rate_' + idx);
    if (input) {
        input.value = cost ? parseFloat(cost).toFixed(2) : '0.00';
    }
    updateGrnLineTotal(idx);
}

function updateGrnLineTotal(idx) {
    const qty = parseFloat(document.getElementById('grn_qty_' + idx)?.value) || 0;
    const rate = parseFloat(document.getElementById('rate_' + idx)?.value) || 0;
    const totalElem = document.getElementById('grn_total_' + idx);
    if (totalElem) {
        totalElem.innerText = '₹' + (qty * rate).toFixed(2);
    }
    calcGrnGrandTotal();
}

function calcGrnGrandTotal() {
    let sum = 0;
    const boxes = document.querySelectorAll('#grnItemsContainer .drawer-item-box');
    boxes.forEach(box => {
        const qtyIn = box.querySelector('input[name*="[qty]"]');
        const rateIn = box.querySelector('input[name*="[rate]"]');
        if (qtyIn && rateIn) {
            sum += (parseFloat(qtyIn.value) || 0) * (parseFloat(rateIn.value) || 0);
        }
    });
    const gt = document.getElementById('grnGrandTotal');
    if (gt) gt.innerText = '₹' + sum.toFixed(2);
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

function addGrnItemRow() {
    let optionsHtml = '<option value="">-- Select Material / Item --</option>';
    if (Array.isArray(itemsData) && itemsData.length > 0) {
        itemsData.forEach(item => {
            const escapedName = escapeHtml(item.item_name);
            const escapedCode = escapeHtml(item.item_code);
            const cost = parseFloat(item.purchase_price || 0).toFixed(2);
            optionsHtml += `<option value="${item.id}" data-cost="${cost}">${escapedName} (${escapedCode})</option>`;
        });
    }

    const box = document.createElement('div');
    box.className = 'drawer-item-box';
    box.id = `grn_row_${rowIdx}`;
    box.innerHTML = `
        <div class="form-group" style="margin-bottom:12px;">
            <label style="font-size:12px; font-weight:700; color:#334155;">Material / Item Name *</label>
            <select name="items[${rowIdx}][item_id]" class="form-control" required style="font-weight:600;" onchange="setGrnCost(this, ${rowIdx})">
                ${optionsHtml}
            </select>
        </div>
        <div style="display:flex; align-items:flex-end; gap:10px;">
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Qty Received</label>
                <input type="number" step="0.01" min="0.01" name="items[${rowIdx}][qty]" id="grn_qty_${rowIdx}" class="form-control" value="1" required style="font-weight:600;" oninput="updateGrnLineTotal(${rowIdx})">
            </div>
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Unit Cost (₹)</label>
                <input type="number" step="0.01" name="items[${rowIdx}][rate]" id="rate_${rowIdx}" class="form-control" value="0.00" required style="font-weight:600;" oninput="updateGrnLineTotal(${rowIdx})">
            </div>
            <div style="flex:1.2;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Batch / Lot #</label>
                <input type="text" name="items[${rowIdx}][batch_no]" class="form-control" placeholder="Batch / SN">
            </div>
            <div style="width:90px; text-align:right;">
                <label style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px; display:block;">Line Total</label>
                <div id="grn_total_${rowIdx}" style="font-weight:800; font-size:13.5px; color:#0f172a; padding:9px 0;">₹0.00</div>
            </div>
            <div style="width:34px;">
                <button type="button" class="drawer-btn-del" onclick="this.closest('.drawer-item-box').remove(); calcGrnGrandTotal();">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    const container = document.getElementById('grnItemsContainer');
    if (container) {
        container.appendChild(box);
        rowIdx++;
    }
}
</script>

<!-- Recent GRN List -->
<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
        <span class="card-title" style="font-size: 16px;"><i class="fa fa-history" style="color: var(--primary);"></i> Inward Material Receipt History</span>
        <span style="font-size:12.5px; color:#64748b;">Showing recent GRN batches & stock inward records</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 16%;">GRN Number</th>
                    <th style="width: 24%;">Vendor / Supplier</th>
                    <th style="width: 16%;">Godown Location</th>
                    <th style="width: 14%;">Invoice / Challan</th>
                    <th style="width: 12%;">Inward Date</th>
                    <th style="width: 12%;">Total Amount</th>
                    <th style="width: 16%;">Received By</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($recent_grns && $recent_grns->num_rows > 0): ?>
                    <?php while ($r = $recent_grns->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12.5px; border:1px solid #e2e8f0; display:inline-block;">
                                    <?= htmlspecialchars($r['grn_number']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($r['vendor_name']) ?></strong>
                            </td>
                            <td>
                                <span style="background:#f8fafc; border:1px solid #e2e8f0; padding:3px 10px; border-radius:20px; font-size:12px; font-weight:700; color:#334155; display:inline-flex; align-items:center; gap:5px;">
                                    <i class="fa fa-warehouse" style="color:#64748b; font-size:11px;"></i> <?= htmlspecialchars($r['godown_name']) ?>
                                </span>
                            </td>
                            <td style="color:#64748b; font-weight:600;"><?= htmlspecialchars($r['invoice_no'] ?: ($r['challan_no'] ?: '-')) ?></td>
                            <td style="color:#475569; font-weight:600;"><?= htmlspecialchars($r['grn_date']) ?></td>
                            <td>
                                <strong style="color: #047857; font-size:14px;">₹<?= number_format($r['total_amount'], 2) ?></strong>
                            </td>
                            <td>
                                <span style="font-size:12.5px; font-weight:700; color:#334155;">
                                    <?= htmlspecialchars(($r['staff_name'] ?? '') . ' ' . ($r['staff_surname'] ?? '')) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding: 40px; color:#64748b;"><i class="fa fa-dolly" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No inward GRN entries recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>
