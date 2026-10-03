<?php
// Store Controller: Categories & Units Management
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';

// Add Category
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_category'])) {
    $name = trim($_POST['cat_name'] ?? '');
    $code = trim($_POST['cat_code'] ?? '');
    if (!empty($name)) {
        $stmt = $db->prepare("INSERT INTO store_categories (name, code) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $code);
        $stmt->execute();
        $msg = "Category successfully created.";
    }
}

// Add Unit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_unit'])) {
    $unit_name = trim($_POST['unit_name'] ?? '');
    $short_code = trim($_POST['short_code'] ?? '');
    $allow_decimal = isset($_POST['allow_decimal']) ? 1 : 0;
    if (!empty($unit_name) && !empty($short_code)) {
        $stmt = $db->prepare("INSERT INTO store_units (unit_name, short_code, allow_decimal) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $unit_name, $short_code, $allow_decimal);
        $stmt->execute();
        $msg = "Unit of measurement added.";
    }
}

$cats = $db->query("SELECT * FROM store_categories WHERE is_active = 1 ORDER BY name ASC");
$units = $db->query("SELECT * FROM store_units ORDER BY unit_name ASC");

render_header('Categories & Units Configuration', 'categories');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<!-- Top Action Header with CTAs Matching Image 3 -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 22px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:800; color:#0f172a; letter-spacing:-0.4px;">Categories & Units Configuration</h2>
        <p style="margin:3px 0 0 0; font-size:13px; color:#64748b;">Manage item taxonomy, department categories, and units of measurement</p>
    </div>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-outline" onclick="openUnitDrawer()" style="background:#fff; border:1px solid #cbd5e1; font-weight:700; padding:9px 16px; border-radius:10px; display:inline-flex; align-items:center; gap:8px; color:#334155;">
            <i class="fa fa-scale-balanced" style="color:#10b981;"></i> + Add Unit of Measure
        </button>
        <button type="button" class="btn btn-primary" onclick="openCatDrawer()" style="box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35); font-weight:700; padding:9px 18px; border-radius:10px; display:inline-flex; align-items:center; gap:8px;">
            <i class="fa fa-circle-plus"></i> + Add Category
        </button>
    </div>
</div>

<!-- Modern Right Drawer: Add Category (Matches Image 3) -->
<div id="catDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeCatDrawer()">
    <div class="modern-drawer" style="width: 500px;">
        <div class="modern-drawer-header">
            <h3><i class="fa fa-folder-tree" style="color:var(--primary); font-size:18px;"></i> Add Product Category</h3>
            <button type="button" class="modern-drawer-close" onclick="closeCatDrawer()">&times;</button>
        </div>
        <div class="modern-drawer-body">
            <form method="POST" action="" id="categoryDrawerForm">
                <input type="hidden" name="add_category" value="1">
                <div class="form-group" style="margin-bottom:16px;">
                    <label>Category Name *</label>
                    <input type="text" name="cat_name" id="f_cat_name" class="form-control" placeholder="e.g. Science Lab Chemicals" required style="font-weight:600;">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label>Category Code</label>
                    <input type="text" name="cat_code" class="form-control" placeholder="e.g. CHEM" style="font-weight:600;">
                </div>
            </form>
        </div>
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeCatDrawer()">Cancel</button>
            <button type="submit" form="categoryDrawerForm" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save Category
            </button>
        </div>
    </div>
</div>

<!-- Modern Right Drawer: Add Unit (Matches Image 3) -->
<div id="unitDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeUnitDrawer()">
    <div class="modern-drawer" style="width: 500px;">
        <div class="modern-drawer-header">
            <h3><i class="fa fa-scale-balanced" style="color:#10b981; font-size:18px;"></i> Add Unit of Measurement</h3>
            <button type="button" class="modern-drawer-close" onclick="closeUnitDrawer()">&times;</button>
        </div>
        <div class="modern-drawer-body">
            <form method="POST" action="" id="unitDrawerForm">
                <input type="hidden" name="add_unit" value="1">
                <div class="form-group" style="margin-bottom:16px;">
                    <label>Unit Name *</label>
                    <input type="text" name="unit_name" id="f_unit_name" class="form-control" placeholder="e.g. Liter / Kilogram / Roll" required style="font-weight:600;">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label>Short Symbol / Code *</label>
                    <input type="text" name="short_code" class="form-control" placeholder="e.g. Ltr / Kg / Rl" required style="font-weight:600;">
                </div>
                <div class="form-group" style="margin-bottom:16px;">
                    <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                        <input type="checkbox" name="allow_decimal" value="1" style="width:16px; height:16px;">
                        <span style="font-weight:600; font-size:13px; color:#334155;">Allow Decimal Quantities (e.g. 1.5 Ltr, 2.75 Kg)</span>
                    </label>
                </div>
            </form>
        </div>
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeUnitDrawer()">Cancel</button>
            <button type="submit" form="unitDrawerForm" class="btn-drawer-save" style="background:#10b981;">
                <i class="fa fa-check"></i> Save Unit
            </button>
        </div>
    </div>
</div>

<script>
function openCatDrawer() {
    const d = document.getElementById('catDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
    document.getElementById('f_cat_name')?.focus();
}

function closeCatDrawer() {
    const d = document.getElementById('catDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function openUnitDrawer() {
    const d = document.getElementById('unitDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
    document.getElementById('f_unit_name')?.focus();
}

function closeUnitDrawer() {
    const d = document.getElementById('unitDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}
</script>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <!-- Categories Column -->
    <div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
        <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
            <span class="card-title" style="font-size: 16px;"><i class="fa fa-folder-tree" style="color: var(--primary);"></i> Product Categories</span>
            <button type="button" class="btn btn-sm btn-primary" onclick="openCatDrawer()" style="padding:6px 12px; font-size:12px; font-weight:700;">
                <i class="fa fa-plus"></i> New Category
            </button>
        </div>
        <div class="card-body" style="padding: 20px 24px;">
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th style="width:70%;">Category Name</th><th style="width:30%;">Short Code</th></tr></thead>
                    <tbody>
                        <?php if ($cats && $cats->num_rows > 0): ?>
                            <?php while ($c = $cats->fetch_assoc()): ?>
                                <tr>
                                    <td><strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($c['name']) ?></strong></td>
                                    <td>
                                        <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:3px 7px; border-radius:6px; font-size:12px; border:1px solid #e2e8f0;">
                                            <?= htmlspecialchars($c['code'] ?? '-') ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="2" style="text-align:center; padding:30px; color:#94a3b8;">No categories created yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Units Column -->
    <div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
        <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 18px 24px;">
            <span class="card-title" style="font-size: 16px;"><i class="fa fa-scale-balanced" style="color: #10b981;"></i> Units of Measurement</span>
            <button type="button" class="btn btn-sm btn-success" onclick="openUnitDrawer()" style="padding:6px 12px; font-size:12px; font-weight:700;">
                <i class="fa fa-plus"></i> New Unit
            </button>
        </div>
        <div class="card-body" style="padding: 20px 24px;">
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th style="width:55%;">Unit Name</th><th style="width:25%;">Code / Symbol</th><th style="width:20%;">Decimals</th></tr></thead>
                    <tbody>
                        <?php if ($units && $units->num_rows > 0): ?>
                            <?php while ($u = $units->fetch_assoc()): ?>
                                <tr>
                                    <td><strong style="color: #0f172a; font-size:14px;"><?= htmlspecialchars($u['unit_name']) ?></strong></td>
                                    <td>
                                        <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:3px 7px; border-radius:6px; font-size:12px; border:1px solid #e2e8f0;">
                                            <?= htmlspecialchars($u['short_code']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($u['allow_decimal']): ?>
                                            <span class="badge badge-success"><i class="fa fa-check"></i> Allowed</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary" style="background:#f1f5f9; color:#64748b;">Integers</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align:center; padding:30px; color:#94a3b8;">No units configured yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php render_footer(); ?>
