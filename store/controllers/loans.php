<?php
// Store Controller: Returnable Asset Loans & Equipment Checkout
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';
$staff_id = $user['id'];

// Issue Loan / Returnable Asset
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['issue_loan'])) {
    $item_id = (int)($_POST['item_id'] ?? 0);
    $issued_to_staff_id = (int)($_POST['issued_to_staff_id'] ?? 0);
    $quantity = (float)($_POST['quantity'] ?? 1);
    $issue_date = $_POST['issue_date'] ?? date('Y-m-d');
    $due_return_date = $_POST['due_return_date'] ?? date('Y-m-d', strtotime('+7 days'));
    $condition_on_issue = trim($_POST['condition_on_issue'] ?? 'Good Working Condition');
    $remarks = trim($_POST['remarks'] ?? '');
    $godown_id = 1;

    if (!$item_id || !$issued_to_staff_id) {
        $err = "Please select both an item and a staff member.";
    } else {
        $loan_slip_no = 'LOAN-' . date('Ymd') . '-' . rand(1000, 9999);
        $stmt = $db->prepare("INSERT INTO store_loans (loan_slip_no, item_id, godown_id, issued_to_staff_id, quantity, issue_date, due_return_date, condition_on_issue, remarks, issued_by_staff_id, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Issued')");
        $stmt->bind_param("siiddssssi", $loan_slip_no, $item_id, $godown_id, $issued_to_staff_id, $quantity, $issue_date, $due_return_date, $condition_on_issue, $remarks, $staff_id);

        if ($stmt->execute()) {
            // Decrement on-hand available stock for loan
            $bal_stmt = $db->prepare("UPDATE store_stock_balances SET quantity = GREATEST(0, quantity - ?) WHERE godown_id = ? AND item_id = ?");
            $bal_stmt->bind_param("dii", $quantity, $godown_id, $item_id);
            $bal_stmt->execute();

            $msg = "Asset loan slip {$loan_slip_no} issued successfully.";
        } else {
            $err = "Error recording loan: " . $db->error;
        }
    }
}

// Return Asset Workflow
if (isset($_GET['return_id'])) {
    $loan_id = (int)$_GET['return_id'];
    $actual_return_date = date('Y-m-d');

    $loan_q = $db->query("SELECT * FROM store_loans WHERE id = $loan_id AND status IN ('Issued','Overdue') LIMIT 1");
    if ($loan = $loan_q->fetch_assoc()) {
        $item_id = $loan['item_id'];
        $qty = $loan['quantity'];
        $godown_id = $loan['godown_id'];

        // Mark returned
        $upd = $db->prepare("UPDATE store_loans SET status = 'Returned', actual_return_date = ?, received_by_staff_id = ? WHERE id = ?");
        $upd->bind_param("sii", $actual_return_date, $staff_id, $loan_id);
        $upd->execute();

        // Increment back available stock
        $bal_up = $db->prepare("UPDATE store_stock_balances SET quantity = quantity + ? WHERE godown_id = ? AND item_id = ?");
        $bal_up->bind_param("dii", $qty, $godown_id, $item_id);
        $bal_up->execute();

        $msg = "Asset successfully returned and restored to warehouse inventory.";
    }
}

// Fetch Returnable Items
$asset_items = $db->query("SELECT id, item_name, item_code FROM store_items WHERE is_active = 1 AND is_returnable_asset = 1 ORDER BY item_name ASC");

// Fetch Active Staff Members
$staff_members = $db->query("SELECT id, name, surname, employee_id FROM staff WHERE is_active = 1 ORDER BY name ASC");

// Fetch All Loans
$loans = $db->query("SELECT l.*, i.item_name, i.item_code, s.name, s.surname, s.employee_id as emp_code
    FROM store_loans l
    JOIN store_items i ON i.id = l.item_id
    JOIN staff s ON s.id = l.issued_to_staff_id
    ORDER BY l.id DESC LIMIT 50");

render_header('Returnable Asset Loans & Equipment Checkout', 'loans');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- Action Bar with Top CTA matching Image 3 -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 22px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:800; color:#0f172a; letter-spacing:-0.4px;">Asset Loans & Equipment Checkout</h2>
        <p style="margin:3px 0 0 0; font-size:13px; color:#64748b;">Manage checkout slips, returnable lab apparatus, projectors, and IT hardware loans</p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-primary" onclick="openLoanDrawer()" style="box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); font-weight:700; padding:10px 18px; border-radius:10px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa fa-circle-plus"></i> Issue Asset Loan
        </button>
    </div>
</div>

<!-- Modern Right Drawer: Issue Asset Loan (Image 3 style) -->
<div id="loanDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeLoanDrawer()">
    <div class="modern-drawer" style="width: 600px;">
        <!-- Header -->
        <div class="modern-drawer-header">
            <h3><i class="fa fa-hand-holding-hand" style="color:var(--primary); font-size:18px;"></i> Checkout Equipment / Returnable Asset</h3>
            <button type="button" class="modern-drawer-close" onclick="closeLoanDrawer()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="loanForm">
                <div class="form-group" style="margin-bottom:16px;">
                    <label>Select Asset / Equipment *</label>
                    <select name="item_id" class="form-control" required style="font-weight:600;">
                        <option value="">-- Select Returnable Asset --</option>
                        <?php 
                        $asset_items->data_seek(0);
                        while ($ai = $asset_items->fetch_assoc()): ?>
                            <option value="<?= $ai['id'] ?>"><?= htmlspecialchars($ai['item_name']) ?> (<?= $ai['item_code'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Issue To Staff Member *</label>
                    <select name="issued_to_staff_id" class="form-control" required style="font-weight:600;">
                        <option value="">-- Choose Staff Member --</option>
                        <?php 
                        $staff_members->data_seek(0);
                        while ($st = $staff_members->fetch_assoc()): ?>
                            <option value="<?= $st['id'] ?>"><?= htmlspecialchars($st['name'] . ' ' . $st['surname']) ?> (Emp ID: <?= $st['employee_id'] ?>)</option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Quantity *</label>
                        <input type="number" step="1" name="quantity" class="form-control" value="1" min="1" required style="font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label>Condition on Checkout</label>
                        <input type="text" name="condition_on_issue" class="form-control" value="Good Working Condition">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Issue Date *</label>
                        <input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>" required style="font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label>Expected Return Date *</label>
                        <input type="date" name="due_return_date" class="form-control" value="<?= date('Y-m-d', strtotime('+3 days')) ?>" required style="font-weight:600;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Purpose / Remarks</label>
                    <textarea name="remarks" class="form-control" rows="3" placeholder="e.g. For Annual Day Photography / Physics Lab Practicals" style="resize:vertical;"></textarea>
                </div>

                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px;">
                    <div style="display:flex; align-items:center; gap:8px; font-weight:700; font-size:12.5px; color:#475569;">
                        <i class="fa fa-circle-info" style="color:var(--primary);"></i> Returnable Asset Notice
                    </div>
                    <p style="margin:4px 0 0 0; font-size:12px; color:#64748b;">Issued items remain in school inventory registry under staff custody until marked as returned.</p>
                </div>
            </form>
        </div>

        <!-- Footer (Image 3 Style) -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeLoanDrawer()">Cancel</button>
            <button type="submit" form="loanForm" name="issue_loan" class="btn-drawer-save">
                <i class="fa fa-check"></i> Issue Equipment Loan
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('loanForm').submit();">
                <i class="fa fa-print"></i> Issue & Print Slip
            </button>
        </div>
    </div>
</div>

<script>
function openLoanDrawer() {
    const d = document.getElementById('loanDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

function closeLoanDrawer() {
    const d = document.getElementById('loanDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}
</script>

<!-- Active Loans List -->
<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
        <span class="card-title" style="font-size: 16px;"><i class="fa fa-list-check" style="color: var(--primary);"></i> Active Asset Loans & Checkout Registry</span>
        <span style="font-size:12.5px; color:#64748b;">Tracking returnable lab apparatus, projectors, and IT hardware</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 15%;">Loan Slip</th>
                    <th style="width: 22%;">Asset Name</th>
                    <th style="width: 8%;">Qty</th>
                    <th style="width: 18%;">Borrower (Staff)</th>
                    <th style="width: 11%;">Issue Date</th>
                    <th style="width: 11%;">Due Return</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 15%; text-align:right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($loans && $loans->num_rows > 0): ?>
                    <?php while ($l = $loans->fetch_assoc()): ?>
                        <?php 
                        $is_overdue = ($l['status'] === 'Issued' && strtotime($l['due_return_date']) < time());
                        $display_status = $is_overdue ? 'Overdue' : $l['status'];
                        ?>
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12.5px; border:1px solid #e2e8f0; display:inline-block;">
                                    <?= htmlspecialchars($l['loan_slip_no']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($l['item_name']) ?></strong>
                            </td>
                            <td>
                                <span style="font-weight:700; color:#334155;"><?= number_format($l['quantity']) ?></span>
                            </td>
                            <td>
                                <div style="font-weight:700; color:#334155; font-size:13.5px;"><?= htmlspecialchars($l['name'] . ' ' . $l['surname']) ?></div>
                            </td>
                            <td style="color:#64748b; font-weight:600;"><?= htmlspecialchars($l['issue_date']) ?></td>
                            <td style="color:<?= $is_overdue ? '#e11d48' : '#475569' ?>; font-weight:700;">
                                <?= htmlspecialchars($l['due_return_date']) ?>
                            </td>
                            <td>
                                <span class="badge <?= $display_status === 'Returned' ? 'badge-success' : ($display_status === 'Overdue' ? 'badge-danger' : 'badge-warning') ?>">
                                    <i class="fa <?= $display_status === 'Returned' ? 'fa-circle-check' : ($display_status === 'Overdue' ? 'fa-triangle-exclamation' : 'fa-clock') ?>"></i> <?= htmlspecialchars($display_status) ?>
                                </span>
                            </td>
                            <td style="text-align:right;">
                                <?php if ($l['status'] !== 'Returned'): ?>
                                    <a href="<?= BASE_URL ?>loans?return_id=<?= $l['id'] ?>" class="btn btn-success" style="padding: 6px 12px; font-size: 11.5px; font-weight:700;" onclick="return confirm('Confirm receipt of this asset back into store?');">
                                        <i class="fa fa-arrow-turn-down"></i> Return Check-in
                                    </a>
                                <?php else: ?>
                                    <span style="font-size: 12px; color: #047857; font-weight:700; background:#ecfdf5; border:1px solid #a7f3d0; padding:4px 8px; border-radius:6px; display:inline-block;">
                                        <i class="fa fa-circle-check"></i> Checked-in
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align:center; padding: 40px; color:#64748b;"><i class="fa fa-laptop-file" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No asset loan records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>
