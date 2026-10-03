<?php
// Store Controller: Pre-Configured Student Kit / Bundle Packs
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';

// 1. Create Bundle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_bundle'])) {
    $bundle_name = trim($_POST['bundle_name'] ?? '');
    $bundle_code = trim($_POST['bundle_code'] ?? '');
    $class_name = trim($_POST['class_name'] ?? '');
    $items = $_POST['items'] ?? [];

    if (empty($bundle_name) || empty($bundle_code) || empty($items)) {
        $err = "Bundle Name, Code, and at least one item are required.";
    } else {
        $db->begin_transaction();
        try {
            $total_price = 0;
            foreach ($items as $it) {
                $qty = (float)($it['qty'] ?? 1);
                $rate = (float)($it['rate'] ?? 0);
                $total_price += ($qty * $rate);
            }

            $stmt = $db->prepare("INSERT INTO store_bundles (bundle_name, bundle_code, class_name, total_price) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE bundle_name = VALUES(bundle_name), class_name = VALUES(class_name), total_price = VALUES(total_price)");
            $stmt->bind_param("sssd", $bundle_name, $bundle_code, $class_name, $total_price);
            $stmt->execute();
            $bundle_id = $db->insert_id ?: $db->query("SELECT id FROM store_bundles WHERE bundle_code = '$bundle_code'")->fetch_assoc()['id'];

            // Clear old items if updating
            $db->query("DELETE FROM store_bundle_items WHERE bundle_id = $bundle_id");

            foreach ($items as $it) {
                $item_id = (int)($it['item_id'] ?? 0);
                $qty = (float)($it['qty'] ?? 1);
                $rate = (float)($it['rate'] ?? 0);

                if ($item_id > 0 && $qty > 0) {
                    $item_stmt = $db->prepare("INSERT INTO store_bundle_items (bundle_id, item_id, quantity, unit_price) VALUES (?, ?, ?, ?)");
                    $item_stmt->bind_param("iidd", $bundle_id, $item_id, $qty, $rate);
                    $item_stmt->execute();
                }
            }

            $db->commit();
            $msg = "Kit / Bundle '{$bundle_name}' configured successfully!";
        } catch (Exception $e) {
            $db->rollback();
            $err = "Error saving bundle: " . $e->getMessage();
        }
    }
}

// Fetch Classes from ERP
$classes_query = $db->query("SELECT DISTINCT class FROM classes ORDER BY id ASC");

// Fetch Saleable Items
$items_list = $db->query("SELECT id, item_name, item_code, sale_price FROM store_items WHERE is_active = 1 AND is_saleable = 1 ORDER BY item_name ASC");
$items_arr = [];
while ($it = $items_list->fetch_assoc()) {
    $items_arr[] = $it;
}

// Fetch Existing Bundles
$bundles = $db->query("SELECT b.*, COUNT(bi.id) as item_count 
    FROM store_bundles b 
    LEFT JOIN store_bundle_items bi ON bi.bundle_id = b.id 
    GROUP BY b.id 
    ORDER BY b.id DESC");

render_header('Pre-Configured Student Kits & Bundles', 'bundles');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- Action Bar with Top CTA matching Image 3 -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 22px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:800; color:#0f172a; letter-spacing:-0.4px;">Pre-Configured Student Kits & Bundles</h2>
        <p style="margin:3px 0 0 0; font-size:13px; color:#64748b;">Configure multi-item student sets (books, stationery, uniforms) for 1-click counter billing</p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-primary" onclick="openBundleDrawer()" style="box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); font-weight:700; padding:10px 18px; border-radius:10px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa fa-circle-plus"></i> Configure New Kit / Bundle
        </button>
    </div>
</div>

<!-- Modern Right Drawer: Bundle Configuration (Matches Image 3) -->
<div id="bundleDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeBundleDrawer()">
    <div class="modern-drawer" style="width: 660px;">
        <!-- Header -->
        <div class="modern-drawer-header">
            <h3><i class="fa fa-boxes-packing" style="color:var(--primary); font-size:18px;"></i> Configure New Student Kit / Bundle</h3>
            <button type="button" class="modern-drawer-close" onclick="closeBundleDrawer()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="bundleForm">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Kit / Bundle Name *</label>
                        <input type="text" name="bundle_name" class="form-control" placeholder="e.g. Class 5 Complete Book Set (CBSE)" required style="font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label>Bundle Code *</label>
                        <input type="text" name="bundle_code" class="form-control" placeholder="e.g. BUNDLE-CLS5-ALL" required style="font-weight:600;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:18px;">
                    <label>Target Class (Optional)</label>
                    <select name="class_name" class="form-control" style="font-weight:600;">
                        <option value="">-- All Classes / General Kit --</option>
                        <?php 
                        $classes_query->data_seek(0);
                        while ($cl = $classes_query->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($cl['class']) ?>"><?= htmlspecialchars($cl['class']) ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Kit Items Nested Card Section (Matches Image 3) -->
                <div class="drawer-card-section">
                    <div class="drawer-card-header">
                        <div class="drawer-card-title">
                            <i class="fa fa-layer-group" style="color:var(--primary);"></i> Items Included in this Bundle *
                        </div>
                        <button type="button" class="drawer-btn-add" onclick="addBundleRow()">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                    </div>

                    <div id="bundleItemsContainer">
                        <!-- Line Item 0 in Image 3 Box Style -->
                        <div class="drawer-item-box" id="bundle_row_0">
                            <div class="form-group" style="margin-bottom:12px;">
                                <label style="font-size:12px; font-weight:700; color:#334155;">Material / Book / Item *</label>
                                <select name="items[0][item_id]" class="form-control" required style="font-weight:600;" onchange="setBundleRate(this, 0)">
                                    <option value="">-- Select Material / Item --</option>
                                    <?php foreach ($items_arr as $it): ?>
                                        <option value="<?= $it['id'] ?>" data-price="<?= $it['sale_price'] ?>"><?= htmlspecialchars($it['item_name']) ?> (<?= $it['item_code'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div style="display:flex; align-items:flex-end; gap:10px;">
                                <div style="flex:1;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Quantity</label>
                                    <input type="number" step="1" min="1" name="items[0][qty]" id="bnd_qty_0" class="form-control" value="1" required style="font-weight:600;" oninput="updateBundleLineTotal(0)">
                                </div>
                                <div style="flex:1.2;">
                                    <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Pack Unit Price (₹)</label>
                                    <input type="number" step="0.01" name="items[0][rate]" id="bnd_rate_0" class="form-control" value="0.00" required style="font-weight:600;" oninput="updateBundleLineTotal(0)">
                                </div>
                                <div style="width:100px; text-align:right;">
                                    <label style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px; display:block;">Line Total</label>
                                    <div id="bnd_total_0" style="font-weight:800; font-size:13.5px; color:#0f172a; padding:9px 0;">₹0.00</div>
                                </div>
                                <div style="width:34px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Grand Total Summary Banner -->
                    <div style="display:flex; justify-content:space-between; align-items:center; background:#ffffff; border:1px dashed #cbd5e1; border-radius:10px; padding:12px 16px; margin-top:12px;">
                        <span style="font-size:13px; font-weight:700; color:#475569;">Total Bundle Value:</span>
                        <span id="bundleGrandTotal" style="font-size:17px; font-weight:900; color:var(--primary);">₹0.00</span>
                    </div>
                </div>
            </form>
        </div>

        <!-- Footer (Exact Image 3 style: Cancel, Save, Save & Print) -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeBundleDrawer()">Cancel</button>
            <button type="submit" form="bundleForm" name="save_bundle" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save Student Kit Pack
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('bundleForm').submit();">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </div>
</div>

<script>
const bundleItemsList = <?= json_encode($items_arr) ?>;
let bndIdx = 1;

function openBundleDrawer() {
    const d = document.getElementById('bundleDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

function closeBundleDrawer() {
    const d = document.getElementById('bundleDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function setBundleRate(sel, idx) {
    if (!sel || sel.selectedIndex < 0) return;
    const opt = sel.options[sel.selectedIndex];
    const price = opt ? opt.getAttribute('data-price') : null;
    const input = document.getElementById('bnd_rate_' + idx);
    if (input) {
        input.value = price ? parseFloat(price).toFixed(2) : '0.00';
    }
    updateBundleLineTotal(idx);
}

function updateBundleLineTotal(idx) {
    const qty = parseFloat(document.getElementById('bnd_qty_' + idx)?.value) || 0;
    const rate = parseFloat(document.getElementById('bnd_rate_' + idx)?.value) || 0;
    const totalElem = document.getElementById('bnd_total_' + idx);
    if (totalElem) {
        totalElem.innerText = '₹' + (qty * rate).toFixed(2);
    }
    calcBundleGrandTotal();
}

function calcBundleGrandTotal() {
    let sum = 0;
    const boxes = document.querySelectorAll('#bundleItemsContainer .drawer-item-box');
    boxes.forEach(box => {
        const qtyIn = box.querySelector('input[name*="[qty]"]');
        const rateIn = box.querySelector('input[name*="[rate]"]');
        if (qtyIn && rateIn) {
            sum += (parseFloat(qtyIn.value) || 0) * (parseFloat(rateIn.value) || 0);
        }
    });
    const gt = document.getElementById('bundleGrandTotal');
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

function addBundleRow() {
    let optionsHtml = '<option value="">-- Select Material / Item --</option>';
    if (Array.isArray(bundleItemsList) && bundleItemsList.length > 0) {
        bundleItemsList.forEach(it => {
            const escapedName = escapeHtml(it.item_name);
            const escapedCode = escapeHtml(it.item_code);
            const rate = parseFloat(it.sale_price || 0).toFixed(2);
            optionsHtml += `<option value="${it.id}" data-price="${rate}">${escapedName} (${escapedCode})</option>`;
        });
    }

    const box = document.createElement('div');
    box.className = 'drawer-item-box';
    box.id = `bundle_row_${bndIdx}`;
    box.innerHTML = `
        <div class="form-group" style="margin-bottom:12px;">
            <label style="font-size:12px; font-weight:700; color:#334155;">Material / Book / Item *</label>
            <select name="items[${bndIdx}][item_id]" class="form-control" required style="font-weight:600;" onchange="setBundleRate(this, ${bndIdx})">
                ${optionsHtml}
            </select>
        </div>
        <div style="display:flex; align-items:flex-end; gap:10px;">
            <div style="flex:1;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Quantity</label>
                <input type="number" step="1" min="1" name="items[${bndIdx}][qty]" id="bnd_qty_${bndIdx}" class="form-control" value="1" required style="font-weight:600;" oninput="updateBundleLineTotal(${bndIdx})">
            </div>
            <div style="flex:1.2;">
                <label style="font-size:11.5px; font-weight:700; color:#334155; margin-bottom:4px; display:block;">Pack Unit Price (₹)</label>
                <input type="number" step="0.01" name="items[${bndIdx}][rate]" id="bnd_rate_${bndIdx}" class="form-control" value="0.00" required style="font-weight:600;" oninput="updateBundleLineTotal(${bndIdx})">
            </div>
            <div style="width:100px; text-align:right;">
                <label style="font-size:11px; font-weight:600; color:#64748b; margin-bottom:4px; display:block;">Line Total</label>
                <div id="bnd_total_${bndIdx}" style="font-weight:800; font-size:13.5px; color:#0f172a; padding:9px 0;">₹0.00</div>
            </div>
            <div style="width:34px;">
                <button type="button" class="drawer-btn-del" onclick="this.closest('.drawer-item-box').remove(); calcBundleGrandTotal();">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    const container = document.getElementById('bundleItemsContainer');
    if (container) {
        container.appendChild(box);
        bndIdx++;
    }
}
</script>

<!-- Existing Bundles List -->
<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
        <span class="card-title" style="font-size: 16px;"><i class="fa fa-list" style="color: var(--primary);"></i> Configured Student Kits & Sets</span>
        <span style="font-size:12.5px; color:#64748b;">Bundles available for 1-click loading at POS counter</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 18%;">Bundle Code</th>
                    <th style="width: 30%;">Kit / Bundle Description</th>
                    <th style="width: 15%;">Assigned Class</th>
                    <th style="width: 12%;">Items Count</th>
                    <th style="width: 13%;">Package Price</th>
                    <th style="width: 12%;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($bundles && $bundles->num_rows > 0): ?>
                    <?php while ($b = $bundles->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12.5px; border:1px solid #e2e8f0; display:inline-block;">
                                    <?= htmlspecialchars($b['bundle_code']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($b['bundle_name']) ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-info"><i class="fa fa-graduation-cap"></i> <?= htmlspecialchars($b['class_name'] ?: 'All Classes') ?></span>
                            </td>
                            <td>
                                <span style="font-weight:700; color:#475569;"><?= number_format($b['item_count']) ?> Items</span>
                            </td>
                            <td>
                                <strong style="color: var(--primary); font-size:14.5px;">₹<?= number_format($b['total_price'], 2) ?></strong>
                            </td>
                            <td>
                                <span class="badge badge-success"><i class="fa fa-circle-check"></i> Ready for POS</span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 40px; color:#64748b;"><i class="fa fa-boxes-stacked" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No student kit bundles configured yet. Use the builder above to create 1-click kits.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>
