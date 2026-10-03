<?php
// Store Controller: Inventory Reports & Real-Time Stock Ledger / Audit Cardex
require_once __DIR__ . '/../views/layout.php';

$filter_item_id = isset($_GET['item_id']) ? (int)$_GET['item_id'] : 0;
$filter_type = isset($_GET['type']) ? trim($_GET['type']) : '';

// Fetch Items for filter
$items_list = $db->query("SELECT id, item_name, item_code FROM store_items WHERE is_active = 1 ORDER BY item_name ASC");

// Build Ledger Query
$where = [];
if ($filter_item_id > 0) {
    $where[] = "l.item_id = " . $filter_item_id;
}
if (!empty($filter_type)) {
    $where[] = "l.transaction_type = '" . $db->real_escape_string($filter_type) . "'";
}

$where_sql = !empty($where) ? "WHERE " . implode(" AND ", $where) : "";

$ledger = $db->query("SELECT l.*, i.item_name, i.item_code, g.godown_name, s.name as staff_name, s.surname as staff_surname
    FROM store_stock_ledger l
    JOIN store_items i ON i.id = l.item_id
    LEFT JOIN store_godowns g ON g.id = l.godown_id
    LEFT JOIN staff s ON s.id = l.created_by_staff_id
    {$where_sql}
    ORDER BY l.id DESC LIMIT 100");

// Valuation Summary
$val_summary = $db->query("SELECT 
    COUNT(DISTINCT i.id) as total_skus,
    COALESCE(SUM(b.quantity), 0) as total_units,
    COALESCE(SUM(b.quantity * i.purchase_price), 0) as total_inventory_valuation
    FROM store_items i
    LEFT JOIN store_stock_balances b ON b.item_id = i.id
    WHERE i.is_active = 1")->fetch_assoc();

render_header('Inventory Reports & Stock Audit Ledger', 'reports');
?>

<!-- KPI Summary -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px;">
    <div class="card" style="margin:0; padding:22px; border-radius:var(--radius-xl); box-shadow:var(--shadow-subtle);">
        <div style="font-size:12px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.8px;">Catalog SKUs</div>
        <div style="font-size:28px; font-weight:800; color:#0f172a; margin-top:6px; letter-spacing:-0.5px;"><?= number_format($val_summary['total_skus']) ?> SKUs</div>
        <div style="font-size:12px; color:#10b981; font-weight:700; margin-top:4px;"><i class="fa fa-boxes-packing"></i> Active in Godowns</div>
    </div>
    <div class="card" style="margin:0; padding:22px; border-radius:var(--radius-xl); box-shadow:var(--shadow-subtle);">
        <div style="font-size:12px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.8px;">Stock On-Hand</div>
        <div style="font-size:28px; font-weight:800; color:#0284c7; margin-top:6px; letter-spacing:-0.5px;"><?= number_format($val_summary['total_units']) ?> Units</div>
        <div style="font-size:12px; color:#0284c7; font-weight:700; margin-top:4px;"><i class="fa fa-warehouse"></i> Central Godown</div>
    </div>
    <div class="card" style="margin:0; padding:22px; border-radius:var(--radius-xl); box-shadow:var(--shadow-subtle);">
        <div style="font-size:12px; color:#64748b; font-weight:700; text-transform:uppercase; letter-spacing:0.8px;">Inventory Valuation</div>
        <div style="font-size:28px; font-weight:800; color:#047857; margin-top:6px; letter-spacing:-0.5px;">₹<?= number_format($val_summary['total_inventory_valuation'], 2) ?></div>
        <div style="font-size:12px; color:#047857; font-weight:700; margin-top:4px;"><i class="fa fa-vault"></i> Asset Book Value</div>
    </div>
</div>

<!-- Ledger Audit Filter -->
<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle); margin-bottom: 24px;">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
        <span class="card-title" style="font-size: 15.5px;"><i class="fa fa-filter" style="color: var(--primary);"></i> Stock Cardex & Audit Trail Filter</span>
    </div>
    <div class="card-body" style="padding: 20px 24px;">
        <form method="GET" action="" style="display:flex; gap:16px; align-items:flex-end; flex-wrap:wrap;">
            <div class="form-group" style="flex:2; min-width:240px;">
                <label>Filter by Item / Product</label>
                <select name="item_id" class="form-control">
                    <option value="">-- All Items --</option>
                    <?php while ($it = $items_list->fetch_assoc()): ?>
                        <option value="<?= $it['id'] ?>" <?= $filter_item_id == $it['id'] ? 'selected' : '' ?>><?= htmlspecialchars($it['item_name']) ?> (<?= $it['item_code'] ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group" style="flex:1; min-width:180px;">
                <label>Transaction Type</label>
                <select name="type" class="form-control">
                    <option value="">-- All Types --</option>
                    <option value="PURCHASE_GRN" <?= $filter_type == 'PURCHASE_GRN' ? 'selected' : '' ?>>Inward GRN (Stock In)</option>
                    <option value="SALE" <?= $filter_type == 'SALE' ? 'selected' : '' ?>>Student Sale (Stock Out)</option>
                    <option value="STAFF_ISSUE" <?= $filter_type == 'STAFF_ISSUE' ? 'selected' : '' ?>>Staff Issue</option>
                    <option value="LOAN_ISSUE" <?= $filter_type == 'LOAN_ISSUE' ? 'selected' : '' ?>>Asset Loan Issue</option>
                    <option value="LOAN_RETURN" <?= $filter_type == 'LOAN_RETURN' ? 'selected' : '' ?>>Asset Loan Return</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="padding:10px 18px;"><i class="fa fa-search"></i> Filter Ledger</button>
            <a href="<?= BASE_URL ?>reports" class="btn btn-secondary" style="padding:10px 16px;"><i class="fa fa-rotate-left"></i> Reset</a>
        </form>
    </div>
</div>

<!-- Real-time Immutable Ledger Table -->
<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
        <span class="card-title" style="font-size: 16px;"><i class="fa fa-book-bookmark" style="color: var(--primary);"></i> Complete Inventory Cardex & Movement History</span>
        <span style="font-size:12.5px; color:#64748b;">Immutable chronological audit log</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 14%;">Date & Time</th>
                    <th style="width: 24%;">Item Description</th>
                    <th style="width: 14%;">Transaction Type</th>
                    <th style="width: 12%;">Reference #</th>
                    <th style="width: 10%;">Inward (+)</th>
                    <th style="width: 10%;">Outward (-)</th>
                    <th style="width: 10%;">Running Bal.</th>
                    <th style="width: 16%;">Audited By</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($ledger && $ledger->num_rows > 0): ?>
                    <?php while ($l = $ledger->fetch_assoc()): ?>
                        <?php 
                        $badge_class = 'badge-info';
                        if ($l['transaction_type'] === 'PURCHASE_GRN' || $l['transaction_type'] === 'LOAN_RETURN') $badge_class = 'badge-success';
                        elseif ($l['transaction_type'] === 'SALE' || $l['transaction_type'] === 'STAFF_ISSUE') $badge_class = 'badge-primary';
                        elseif ($l['transaction_type'] === 'LOAN_ISSUE') $badge_class = 'badge-warning';
                        ?>
                        <tr>
                            <td style="color:#64748b; font-size:12.5px; font-weight:600;"><?= htmlspecialchars($l['created_at']) ?></td>
                            <td>
                                <strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($l['item_name']) ?></strong>
                                <span style="font-size:11.5px; color:#64748b;">(<?= $l['item_code'] ?>)</span>
                            </td>
                            <td><span class="badge <?= $badge_class ?>"><?= htmlspecialchars($l['transaction_type']) ?></span></td>
                            <td>
                                <span style="font-family:monospace; font-weight:700; color:#475569; background:#f1f5f9; padding:2px 6px; border-radius:4px; font-size:11.5px;">
                                    <?= htmlspecialchars($l['reference_no'] ?? '-') ?>
                                </span>
                            </td>
                            <td style="color:#047857; font-weight:800;"><?= $l['qty_in'] > 0 ? '+' . number_format($l['qty_in']) : '-' ?></td>
                            <td style="color:#be123c; font-weight:800;"><?= $l['qty_out'] > 0 ? '-' . number_format($l['qty_out']) : '-' ?></td>
                            <td><strong style="color: #0f172a; font-size:14px;"><?= number_format($l['balance_after']) ?></strong></td>
                            <td style="color:#475569; font-weight:600; font-size:13px;"><?= htmlspecialchars(($l['staff_name'] ?? 'System') . ' ' . ($l['staff_surname'] ?? '')) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center; padding: 40px; color:#64748b;"><i class="fa fa-book-open" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No inventory ledger transactions recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>
