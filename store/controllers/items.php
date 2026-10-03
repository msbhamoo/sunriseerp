<?php
// Store Controller: Item Master (Enhanced UI/UX)
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';

// Handle Delete Item
if (isset($_GET['delete_id'])) {
    $del_id = (int)$_GET['delete_id'];
    $db->query("UPDATE store_items SET is_active = 0 WHERE id = $del_id");
    $msg = "Item removed from active catalog.";
}

// Handle Create / Update Item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_item'])) {
    $item_id = !empty($_POST['edit_item_id']) ? (int)$_POST['edit_item_id'] : null;
    $item_name = trim($_POST['item_name'] ?? '');
    $item_code = trim($_POST['item_code'] ?? '');
    $category_id = (int)($_POST['category_id'] ?? 0);
    $unit_id = (int)($_POST['unit_id'] ?? 0);
    $barcode = trim($_POST['barcode'] ?? '');
    $hsn_code = trim($_POST['hsn_code'] ?? '');
    $purchase_price = (float)($_POST['purchase_price'] ?? 0);
    $sale_price = (float)($_POST['sale_price'] ?? 0);
    $gst_rate = (float)($_POST['gst_rate'] ?? 0);
    $min_stock_alert = (int)($_POST['min_stock_alert'] ?? 5);
    $rack_shelf = trim($_POST['rack_shelf'] ?? '');
    $is_returnable = isset($_POST['is_returnable_asset']) ? 1 : 0;
    $is_saleable = isset($_POST['is_saleable']) ? 1 : 0;

    if (empty($item_name) || empty($item_code) || !$category_id || !$unit_id) {
        $err = "Item Name, SKU Code, Category, and Unit are required.";
    } else {
        if ($item_id) {
            $stmt = $db->prepare("UPDATE store_items SET 
                category_id = ?, unit_id = ?, item_name = ?, item_code = ?, barcode = ?, hsn_code = ?,
                purchase_price = ?, sale_price = ?, gst_rate = ?, min_stock_alert = ?, rack_shelf = ?,
                is_returnable_asset = ?, is_saleable = ? WHERE id = ?");
            $stmt->bind_param("iissssdddisiii", 
                $category_id, $unit_id, $item_name, $item_code, $barcode, $hsn_code,
                $purchase_price, $sale_price, $gst_rate, $min_stock_alert, $rack_shelf,
                $is_returnable, $is_saleable, $item_id
            );
        } else {
            $stmt = $db->prepare("INSERT INTO store_items 
                (category_id, unit_id, item_name, item_code, barcode, hsn_code, purchase_price, sale_price, gst_rate, min_stock_alert, rack_shelf, is_returnable_asset, is_saleable) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissssdddisii", 
                $category_id, $unit_id, $item_name, $item_code, $barcode, $hsn_code,
                $purchase_price, $sale_price, $gst_rate, $min_stock_alert, $rack_shelf,
                $is_returnable, $is_saleable
            );
        }

        if ($stmt->execute()) {
            $msg = $item_id ? "Item updated successfully." : "Item successfully added to catalog.";
        } else {
            $err = "Database error: " . $db->error;
        }
    }
}

// Fetch categories and units for dropdowns
$categories = $db->query("SELECT id, name FROM store_categories WHERE is_active = 1 ORDER BY name ASC");
$cat_arr = [];
while ($c = $categories->fetch_assoc()) $cat_arr[] = $c;

$units = $db->query("SELECT id, unit_name, short_code FROM store_units ORDER BY unit_name ASC");
$unit_arr = [];
while ($u = $units->fetch_assoc()) $unit_arr[] = $u;

// Filter params
$filter_cat = isset($_GET['f_cat']) ? (int)$_GET['f_cat'] : 0;
$filter_type = isset($_GET['f_type']) ? trim($_GET['f_type']) : '';
$search_text = isset($_GET['q']) ? trim($_GET['q']) : '';

$where = ["i.is_active = 1"];
if ($filter_cat > 0) $where[] = "i.category_id = $filter_cat";
if ($filter_type === 'sale') $where[] = "i.is_saleable = 1";
if ($filter_type === 'asset') $where[] = "i.is_returnable_asset = 1";
if ($filter_type === 'low') $where[] = "b.quantity <= i.min_stock_alert";
if (!empty($search_text)) {
    $st = $db->real_escape_string($search_text);
    $where[] = "(i.item_name LIKE '%$st%' OR i.item_code LIKE '%$st%' OR i.barcode LIKE '%$st%')";
}

$where_sql = implode(" AND ", $where);

// Fetch Item Catalog with current stock
$query = "SELECT i.*, c.name as category_name, u.short_code as unit_code,
          COALESCE(SUM(b.quantity), 0) as current_stock
          FROM store_items i
          LEFT JOIN store_categories c ON c.id = i.category_id
          LEFT JOIN store_units u ON u.id = i.unit_id
          LEFT JOIN store_stock_balances b ON b.item_id = i.id
          WHERE {$where_sql}
          GROUP BY i.id
          ORDER BY i.id DESC";
$items = $db->query($query);

// Summary metrics
$metric_skus = $db->query("SELECT COUNT(*) as c FROM store_items WHERE is_active = 1")->fetch_assoc()['c'] ?? 0;
$metric_low = $db->query("SELECT COUNT(DISTINCT i.id) as c FROM store_items i LEFT JOIN store_stock_balances b ON b.item_id = i.id WHERE i.is_active = 1 AND COALESCE(b.quantity, 0) <= i.min_stock_alert")->fetch_assoc()['c'] ?? 0;
$metric_assets = $db->query("SELECT COUNT(*) as c FROM store_items WHERE is_active = 1 AND is_returnable_asset = 1")->fetch_assoc()['c'] ?? 0;

render_header('Item Master & Inventory Catalog', 'items');
?>

<?php if ($msg): ?><div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><i class="fa fa-triangle-exclamation"></i> <?= htmlspecialchars($err) ?></div><?php endif; ?>

<!-- Header Action Strip & Quick Metrics -->
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px; flex-wrap:wrap; gap:16px;">
    <!-- Metric Badges -->
    <div style="display:flex; gap:12px; align-items:center;">
        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 18px; display:flex; align-items:center; gap:10px;">
            <i class="fa fa-boxes-stacked" style="color:var(--primary); font-size:18px;"></i>
            <div>
                <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Catalog SKUs</div>
                <div style="font-size:18px; font-weight:800; color:#0f172a;"><?= number_format($metric_skus) ?></div>
            </div>
        </div>

        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 18px; display:flex; align-items:center; gap:10px;">
            <i class="fa fa-triangle-exclamation" style="color:#ef4444; font-size:18px;"></i>
            <div>
                <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Low Stock Items</div>
                <div style="font-size:18px; font-weight:800; color:#ef4444;"><?= number_format($metric_low) ?></div>
            </div>
        </div>

        <div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 18px; display:flex; align-items:center; gap:10px;">
            <i class="fa fa-laptop-file" style="color:#f59e0b; font-size:18px;"></i>
            <div>
                <div style="font-size:11px; color:#64748b; font-weight:700; text-transform:uppercase;">Asset / Loans</div>
                <div style="font-size:18px; font-weight:800; color:#f59e0b;"><?= number_format($metric_assets) ?></div>
            </div>
        </div>
    </div>

    <!-- Right Actions -->
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-primary" onclick="openItemDrawer()"><i class="fa fa-plus"></i> Add New Product / SKU</button>
        <a href="<?= BASE_URL ?>import_export" class="btn btn-secondary"><i class="fa fa-cloud-arrow-up"></i> Bulk Import / Export</a>
    </div>
</div>

<!-- Modern Search, Filter & View Controls -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-body" style="padding: 16px 20px;">
        <form method="GET" action="" style="display:flex; gap:14px; align-items:center; flex-wrap:wrap;">
            <!-- Live Search Bar -->
            <div style="flex:2; min-width:240px; position:relative;">
                <input type="text" name="q" value="<?= htmlspecialchars($search_text) ?>" class="form-control" placeholder="Search by name, SKU code, or barcode..." style="padding-left:36px;">
                <i class="fa fa-magnifying-glass" style="position:absolute; left:12px; top:12px; color:#94a3b8;"></i>
            </div>

            <!-- Category Filter -->
            <div style="flex:1; min-width:180px;">
                <select name="f_cat" class="form-control">
                    <option value="">-- All Categories --</option>
                    <?php foreach ($cat_arr as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $filter_cat == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Type Filter -->
            <div style="flex:1; min-width:160px;">
                <select name="f_type" class="form-control">
                    <option value="">-- All Item Types --</option>
                    <option value="sale" <?= $filter_type == 'sale' ? 'selected' : '' ?>>Counter POS Saleable</option>
                    <option value="asset" <?= $filter_type == 'asset' ? 'selected' : '' ?>>Returnable Asset</option>
                    <option value="low" <?= $filter_type == 'low' ? 'selected' : '' ?>>Low Stock Alert Level</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="padding:10px 18px;"><i class="fa fa-filter"></i> Apply Filters</button>
            <?php if (!empty($search_text) || $filter_cat > 0 || !empty($filter_type)): ?>
                <a href="<?= BASE_URL ?>items" class="btn btn-secondary"><i class="fa fa-rotate-left"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Item Catalog Table Card -->
<div class="card">
    <div class="card-header" style="background:#fff;">
        <span class="card-title"><i class="fa fa-boxes-packing" style="color: var(--primary);"></i> Inventory Master Catalog</span>
        <span style="font-size:12.5px; color:#64748b;">Showing <?= $items ? $items->num_rows : 0 ?> items</span>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th style="width:12%;">SKU / Code</th>
                    <th style="width:26%;">Product Name & Details</th>
                    <th style="width:15%;">Category</th>
                    <th style="width:10%;">Pricing</th>
                    <th style="width:10%;">Rack / Shelf</th>
                    <th style="width:13%;">Stock Health</th>
                    <th style="width:14%; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($items && $items->num_rows > 0): ?>
                    <?php while ($row = $items->fetch_assoc()): ?>
                        <?php 
                        $is_low = ($row['current_stock'] <= $row['min_stock_alert']);
                        ?>
                        <tr>
                            <td>
                                <span style="font-family: monospace; font-weight:700; color:#1e293b; background:#f1f5f9; padding:4px 8px; border-radius:6px; font-size:12.5px; border:1px solid #e2e8f0; display:inline-block;">
                                    <?= htmlspecialchars($row['item_code']) ?>
                                </span>
                                <?php if (!empty($row['barcode'])): ?>
                                    <div style="font-size:11px; color:#64748b; margin-top:4px;"><i class="fa fa-barcode"></i> <?= htmlspecialchars($row['barcode']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="font-weight:800; color:#0f172a; font-size:14px;"><?= htmlspecialchars($row['item_name']) ?></div>
                                <div style="display:flex; gap:6px; margin-top:5px;">
                                    <?php if ($row['is_returnable_asset']): ?>
                                        <span class="badge badge-warning"><i class="fa fa-laptop"></i> Returnable Asset</span>
                                    <?php endif; ?>
                                    <?php if ($row['is_saleable']): ?>
                                        <span class="badge badge-primary"><i class="fa fa-cash-register"></i> POS Saleable</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span style="font-weight:700; color:#334155; background:#f8fafc; border:1px solid #e2e8f0; padding:3px 10px; border-radius:20px; font-size:12px; display:inline-block;">
                                    <?= htmlspecialchars($row['category_name']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-weight:800; color:var(--primary); font-size:14.5px;">₹<?= number_format($row['sale_price'], 2) ?> <span style="font-size:11px; color:#64748b; font-weight:600;">/ <?= htmlspecialchars($row['unit_code']) ?></span></div>
                                <div style="font-size:11px; color:#94a3b8; margin-top:2px;">Cost: ₹<?= number_format($row['purchase_price'], 2) ?></div>
                            </td>
                            <td>
                                <span style="background:#f1f5f9; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:700; color:#475569; border:1px solid #e2e8f0; display:inline-flex; align-items:center; gap:5px;">
                                    <i class="fa fa-map-pin" style="color:#94a3b8; font-size:11px;"></i> <?= htmlspecialchars($row['rack_shelf'] ?: 'Shelf A-1') ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($is_low): ?>
                                    <span class="badge badge-danger"><i class="fa fa-triangle-exclamation"></i> <?= number_format($row['current_stock']) ?> <?= htmlspecialchars($row['unit_code']) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-success"><i class="fa fa-circle-check"></i> <?= number_format($row['current_stock']) ?> <?= htmlspecialchars($row['unit_code']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex; gap:6px;">
                                    <button type="button" class="btn btn-secondary" style="padding:6px 12px; font-size:12px;" onclick="editItem(<?= htmlspecialchars(json_encode($row)) ?>)">
                                        <i class="fa fa-pencil" style="color:var(--primary);"></i> Edit
                                    </button>
                                    <a href="<?= BASE_URL ?>items?delete_id=<?= $row['id'] ?>" class="btn btn-danger" style="padding:6px 10px; font-size:12px;" onclick="return confirm('Remove this item from catalog?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center; padding: 40px; color: #64748b;"><i class="fa fa-box-open" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>No items found. Click "+ Add New Product / SKU" to register an item.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Slide-Over Drawer for Item Form (Add / Edit) - Matches Image 3 -->
<div id="itemDrawer" class="modern-drawer-backdrop" onclick="if(event.target===this) closeItemDrawer()">
    <div class="modern-drawer">
        <!-- Header -->
        <div class="modern-drawer-header">
            <h3 id="drawerTitle">
                <i class="fa fa-circle-plus"></i> Add Item Master Entry
            </h3>
            <button type="button" class="modern-drawer-close" onclick="closeItemDrawer()">&times;</button>
        </div>

        <!-- Body -->
        <div class="modern-drawer-body">
            <form method="POST" action="" id="itemMasterForm">
                <input type="hidden" name="edit_item_id" id="edit_item_id" value="">

                <div class="form-group" style="margin-bottom:16px;">
                    <label>Item / Product Name *</label>
                    <input type="text" name="item_name" id="f_item_name" class="form-control" placeholder="e.g. School Uniform Shirt (Size 32)" required>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <label>SKU / Item Code *</label>
                        <input type="text" name="item_code" id="f_item_code" class="form-control" placeholder="e.g. UNIF-SHIRT-32" required>
                    </div>
                    <div class="form-group">
                        <label>Barcode / GTIN</label>
                        <input type="text" name="barcode" id="f_barcode" class="form-control" placeholder="Scan or enter barcode">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                    <div class="form-group">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <label>Category *</label>
                            <a href="javascript:void(0)" onclick="openQuickCatModal()" style="font-size:11.5px; color:var(--primary); font-weight:700;"><i class="fa fa-plus"></i> New</a>
                        </div>
                        <select name="category_id" id="category_select" class="form-control" required>
                            <option value="">-- Choose Category --</option>
                            <?php foreach ($cat_arr as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <label>Unit of Measure *</label>
                            <a href="javascript:void(0)" onclick="openQuickUnitModal()" style="font-size:11.5px; color:var(--primary); font-weight:700;"><i class="fa fa-plus"></i> New</a>
                        </div>
                        <select name="unit_id" id="unit_select" class="form-control" required>
                            <option value="">-- Choose Unit --</option>
                            <?php foreach ($unit_arr as $u): ?>
                                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['unit_name']) ?> (<?= $u['short_code'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Rates / Pricing Section (Sub-Card) -->
                <div class="drawer-card-section">
                    <div class="drawer-card-title" style="margin-bottom:12px;">
                        <i class="fa fa-tag" style="color:var(--primary);"></i> Pricing & Valuation
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                        <div class="form-group">
                            <label>Purchase / Cost Price (₹)</label>
                            <input type="number" step="0.01" name="purchase_price" id="f_purchase_price" class="form-control" value="0.00">
                        </div>
                        <div class="form-group">
                            <label>Counter Sale Price (₹)</label>
                            <input type="number" step="0.01" name="sale_price" id="f_sale_price" class="form-control" value="0.00">
                        </div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
                    <div class="form-group">
                        <label>Min. Stock Alert Threshold</label>
                        <input type="number" name="min_stock_alert" id="f_min_alert" class="form-control" value="5">
                    </div>
                    <div class="form-group">
                        <label>Rack / Shelf Location</label>
                        <input type="text" name="rack_shelf" id="f_rack_shelf" class="form-control" placeholder="e.g. Shelf B-2">
                    </div>
                </div>

                <!-- Flags -->
                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:14px 16px; margin-bottom:24px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; margin-bottom:10px;">
                        <input type="checkbox" name="is_saleable" id="f_is_saleable" value="1" checked style="width:16px; height:16px;">
                        <div>
                            <div style="font-weight:700; font-size:13.5px; color:#1e293b;">Available for Student Counter Sale</div>
                            <div style="font-size:11.5px; color:#64748b;">Displays in the fast POS student billing counter</div>
                        </div>
                    </label>
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                        <input type="checkbox" name="is_returnable_asset" id="f_is_returnable" value="1" style="width:16px; height:16px;">
                        <div>
                            <div style="font-weight:700; font-size:13.5px; color:#1e293b;">Returnable School Asset / Loan</div>
                            <div style="font-size:11.5px; color:#64748b;">E.g. Projectors, DSLR Cameras, Sports Kits issued temporarily to teachers</div>
                        </div>
                    </label>
                </div>
            </form>
        </div>

        <!-- Footer Matching Image 3 -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn-drawer-cancel" onclick="closeItemDrawer()">Cancel</button>
            <button type="submit" form="itemMasterForm" name="save_item" class="btn-drawer-save">
                <i class="fa fa-check"></i> Save
            </button>
            <button type="button" class="btn-drawer-print" onclick="document.getElementById('itemMasterForm').submit();">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </div>
</div>

<!-- Quick Add Category Modal -->
<div id="quickCatModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:99999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:14px; width:400px; padding:24px; box-shadow:var(--shadow-xl);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h4 style="margin:0; font-size:16px; font-weight:700;"><i class="fa fa-folder-plus" style="color:var(--primary);"></i> Quick Add Category</h4>
            <span onclick="closeQuickCatModal()" style="cursor:pointer; font-size:18px; color:#94a3b8;">&times;</span>
        </div>
        <div class="form-group" style="margin-bottom:14px;">
            <label>Category Name *</label>
            <input type="text" id="new_cat_name" class="form-control" placeholder="e.g. Science Lab Chemicals" autofocus>
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-secondary" onclick="closeQuickCatModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitQuickCat()"><i class="fa fa-check"></i> Add to Dropdown</button>
        </div>
    </div>
</div>

<!-- Quick Add Unit Modal -->
<div id="quickUnitModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.6); z-index:99999; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:14px; width:400px; padding:24px; box-shadow:var(--shadow-xl);">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
            <h4 style="margin:0; font-size:16px; font-weight:700;"><i class="fa fa-scale-balanced" style="color:#10b981;"></i> Quick Add Unit</h4>
            <span onclick="closeQuickUnitModal()" style="cursor:pointer; font-size:18px; color:#94a3b8;">&times;</span>
        </div>
        <div class="form-group" style="margin-bottom:12px;">
            <label>Unit Name *</label>
            <input type="text" id="new_unit_name" class="form-control" placeholder="e.g. Bundle / Roll">
        </div>
        <div class="form-group" style="margin-bottom:14px;">
            <label>Short Symbol / Code</label>
            <input type="text" id="new_unit_code" class="form-control" placeholder="e.g. Bdl / Rl">
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" class="btn btn-secondary" onclick="closeQuickUnitModal()">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="submitQuickUnit()"><i class="fa fa-check"></i> Add to Dropdown</button>
        </div>
    </div>
</div>

<script>
// Drawer open/close
function openItemDrawer() {
    document.getElementById('edit_item_id').value = '';
    document.getElementById('drawerTitle').innerHTML = '<i class="fa fa-boxes-packing" style="color:var(--primary);"></i> Add New Product / SKU';
    document.getElementById('itemMasterForm').reset();
    document.getElementById('f_is_saleable').checked = true;
    const d = document.getElementById('itemDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
    document.getElementById('f_item_name').focus();
}

function closeItemDrawer() {
    const d = document.getElementById('itemDrawer');
    if (d) {
        d.style.display = 'none';
        d.classList.remove('active');
    }
    document.body.style.overflow = '';
}

function editItem(item) {
    document.getElementById('edit_item_id').value = item.id;
    document.getElementById('drawerTitle').innerHTML = '<i class="fa fa-pencil" style="color:var(--primary);"></i> Edit Item: ' + item.item_code;
    document.getElementById('f_item_name').value = item.item_name;
    document.getElementById('f_item_code').value = item.item_code;
    document.getElementById('f_barcode').value = item.barcode || '';
    document.getElementById('category_select').value = item.category_id;
    document.getElementById('unit_select').value = item.unit_id;
    document.getElementById('f_purchase_price').value = item.purchase_price;
    document.getElementById('f_sale_price').value = item.sale_price;
    document.getElementById('f_min_alert').value = item.min_stock_alert;
    document.getElementById('f_rack_shelf').value = item.rack_shelf || '';
    document.getElementById('f_is_saleable').checked = (item.is_saleable == 1);
    document.getElementById('f_is_returnable').checked = (item.is_returnable_asset == 1);
    const d = document.getElementById('itemDrawer');
    if (d) {
        d.style.display = 'flex';
        d.classList.add('active');
    }
    document.body.style.overflow = 'hidden';
}

// Category Modal
function openQuickCatModal() {
    document.getElementById('quickCatModal').style.display = 'flex';
    document.getElementById('new_cat_name').focus();
}

function closeQuickCatModal() {
    document.getElementById('quickCatModal').style.display = 'none';
    document.getElementById('new_cat_name').value = '';
}

function submitQuickCat() {
    const name = document.getElementById('new_cat_name').value.trim();
    if (!name) return alert('Enter category name');

    const fd = new FormData();
    fd.append('name', name);

    fetch('<?= BASE_URL ?>api.php?action=quick_add_category', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                const sel = document.getElementById('category_select');
                let found = false;
                for (let i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value == d.id) { found = true; break; }
                }
                if (!found) sel.add(new Option(d.name, d.id, true, true));
                sel.value = d.id;
                closeQuickCatModal();
            } else alert(d.message);
        });
}

// Unit Modal
function openQuickUnitModal() {
    document.getElementById('quickUnitModal').style.display = 'flex';
    document.getElementById('new_unit_name').focus();
}

function closeQuickUnitModal() {
    document.getElementById('quickUnitModal').style.display = 'none';
    document.getElementById('new_unit_name').value = '';
    document.getElementById('new_unit_code').value = '';
}

function submitQuickUnit() {
    const unitName = document.getElementById('new_unit_name').value.trim();
    const unitCode = document.getElementById('new_unit_code').value.trim();
    if (!unitName) return alert('Enter unit name');

    const fd = new FormData();
    fd.append('unit_name', unitName);
    fd.append('short_code', unitCode);

    fetch('<?= BASE_URL ?>api.php?action=quick_add_unit', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(d => {
            if (d.status === 'success') {
                const sel = document.getElementById('unit_select');
                let found = false;
                for (let i = 0; i < sel.options.length; i++) {
                    if (sel.options[i].value == d.id) { found = true; break; }
                }
                if (!found) sel.add(new Option(d.unit_name + ' (' + d.short_code + ')', d.id, true, true));
                sel.value = d.id;
                closeQuickUnitModal();
            } else alert(d.message);
        });
}
</script>

<?php render_footer(); ?>
