<?php
// Store Controller: Vendor Directory Management
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_vendor'])) {
    $name = trim($_POST['name'] ?? '');
    $company_name = trim($_POST['company_name'] ?? '');
    $contact_person = trim($_POST['contact_person'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gstin = trim($_POST['gstin'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $opening_balance = (float)($_POST['opening_balance'] ?? 0);

    if (empty($name)) {
        $err = "Vendor or Company Name is required.";
    } else {
        $stmt = $db->prepare("INSERT INTO store_vendors (name, company_name, contact_person, mobile, email, gstin, address, opening_balance) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssd", $name, $company_name, $contact_person, $mobile, $email, $gstin, $address, $opening_balance);
        if ($stmt->execute()) {
            $msg = "Vendor successfully added.";
        } else {
            $err = "Error saving vendor: " . $db->error;
        }
    }
}

$vendors = $db->query("SELECT * FROM store_vendors WHERE is_active = 1 ORDER BY id DESC");

render_header('Vendor & Supplier Directory', 'vendors');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- Action Bar with Top CTA matching Image 3 -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 22px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:800; color:#0f172a; letter-spacing:-0.4px;">Vendor & Supplier Directory</h2>
        <p style="margin:3px 0 0 0; font-size:13px; color:#64748b;">Manage verified school vendors, contact details, GSTIN tax credentials, and ledgers</p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-primary" onclick="openVendorDrawer()" style="box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); font-weight:700; padding:10px 18px; border-radius:10px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa fa-circle-plus"></i> Register Supplier / Vendor
        </button>
    </div>
</div>

<!-- Modern Right Drawer: Register Vendor (Image 3 Style) -->
<div id="vendorDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeVendorDrawer()">
    <div class="modern-drawer" style="width: 600px;">
        <!-- Header -->
        <div class="modern-drawer-header">
            <h3><i class="fa fa-truck-field" style="color:var(--primary); font-size:18px;"></i> Register New Supplier / Vendor</h3>
            <button type="button" class="modern-drawer-close" onclick="closeVendorDrawer()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="vendorForm">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Vendor / Supplier Name *</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Oxford Book Depot" required style="font-weight:600;">
                    </div>
                    <div class="form-group">
                        <label>Company / Firm Name</label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g. Oxford Publications Pvt Ltd">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="e.g. Mr. Sharma">
                    </div>
                    <div class="form-group">
                        <label>Phone / Mobile</label>
                        <input type="text" name="mobile" class="form-control" placeholder="e.g. 9876543210" style="font-weight:600;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="e.g. sales@oxford.com">
                    </div>
                    <div class="form-group">
                        <label>GSTIN Tax Number</label>
                        <input type="text" name="gstin" class="form-control" placeholder="e.g. 08AAAAA0000A1Z5">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Opening Ledger Balance (₹)</label>
                    <input type="number" step="0.01" name="opening_balance" class="form-control" value="0.00" style="font-weight:700; color:var(--primary);">
                    <small style="color:#64748b; font-size:11.5px; display:block; margin-top:4px;">Initial payable or advance balance recorded at the start of financial ledger.</small>
                </div>

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Office Address & City</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="e.g. M.I. Road, Near Ajmeri Gate, Jaipur" style="resize:vertical;"></textarea>
                </div>
            </form>
        </div>

        <!-- Footer (Image 3 Style) -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeVendorDrawer()">Cancel</button>
            <button type="submit" form="vendorForm" name="save_vendor" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save Vendor Profile
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('vendorForm').submit();">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </div>
</div>

<script>
function openVendorDrawer() {
    const d = document.getElementById('vendorDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

function closeVendorDrawer() {
    const d = document.getElementById('vendorDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}
</script>

<div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
    <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
        <span class="card-title" style="font-size: 16px;"><i class="fa fa-list" style="color: var(--primary);"></i> Registered Suppliers Directory</span>
        <span style="font-size:12.5px; color:#64748b;">Showing verified school suppliers & ledger balances</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 20%;">Vendor Name</th>
                    <th style="width: 20%;">Company / Entity</th>
                    <th style="width: 15%;">Contact Person</th>
                    <th style="width: 13%;">Mobile</th>
                    <th style="width: 14%;">GSTIN</th>
                    <th style="width: 18%;">Opening Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($vendors && $vendors->num_rows > 0): ?>
                    <?php while ($v = $vendors->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($v['name']) ?></strong>
                                <?php if (!empty($v['address'])): ?>
                                    <div style="font-size:11.5px; color:#64748b; margin-top:2px;"><i class="fa fa-location-dot" style="font-size:10px;"></i> <?= htmlspecialchars($v['address']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span style="font-weight:600; color:#334155;"><?= htmlspecialchars($v['company_name'] ?? '-') ?></span>
                            </td>
                            <td>
                                <span style="font-weight:600; color:#475569;"><?= htmlspecialchars($v['contact_person'] ?? '-') ?></span>
                            </td>
                            <td>
                                <span style="color:#0f172a; font-weight:600;"><i class="fa fa-phone" style="font-size:11px; color:#64748b;"></i> <?= htmlspecialchars($v['mobile'] ?? '-') ?></span>
                            </td>
                            <td>
                                <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:3px 7px; border-radius:6px; font-size:12px; border:1px solid #e2e8f0;">
                                    <?= htmlspecialchars($v['gstin'] ?: 'Unregistered') ?>
                                </span>
                            </td>
                            <td>
                                <strong style="color: var(--primary); font-size:14px;">₹<?= number_format($v['opening_balance'], 2) ?></strong>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center; padding: 40px; color:#64748b;"><i class="fa fa-truck-field" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No vendors registered yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php render_footer(); ?>
