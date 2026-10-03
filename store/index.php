<?php
/**
 * School Store & Inventory Management System (store.sunriseschool.in)
 * Application Entry Point & Clean Router
 */

session_start();
require_once __DIR__ . '/config.php';

$route = isset($_GET['route']) ? trim($_GET['route'], '/') : 'dashboard';
$db = get_db_connection();

// --- 1. SSO Verification Route ---
if ($route === 'auth/sso') {
    $token = isset($_GET['token']) ? trim($_GET['token']) : '';
    if (empty($token)) {
        die("Invalid or missing authentication token.");
    }

    $stmt = $db->prepare("SELECT id, staff_id, session_id, branch_id, expires_at, is_used FROM store_sso_tokens WHERE token = ? LIMIT 1");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $token_data = $result->fetch_assoc();

    if (!$token_data) {
        die("Authentication error: Invalid or unrecognized token.");
    }

    if ($token_data['is_used'] == 1) {
        die("Authentication error: This one-time token has already been consumed.");
    }

    if (strtotime($token_data['expires_at']) < time()) {
        die("Authentication error: Security token has expired. Please launch again from School ERP.");
    }

    // Mark token as consumed
    $used_at = date('Y-m-d H:i:s');
    $update_stmt = $db->prepare("UPDATE store_sso_tokens SET is_used = 1, used_at = ? WHERE id = ?");
    $update_stmt->bind_param("si", $used_at, $token_data['id']);
    $update_stmt->execute();

    // Fetch staff member profile
    $staff_id = (int)$token_data['staff_id'];
    $staff_stmt = $db->prepare("SELECT id, name, surname, email, employee_id, is_active FROM staff WHERE id = ? LIMIT 1");
    $staff_stmt->bind_param("i", $staff_id);
    $staff_stmt->execute();
    $staff = $staff_stmt->get_result()->fetch_assoc();

    if (!$staff || $staff['is_active'] != 1) {
        die("Access Denied: Staff account is inactive or not found.");
    }

    // Fetch role name and role_id
    $role_name = 'Store Staff';
    $role_id = 0;
    $role_query = $db->query("SELECT r.id, r.name FROM staff_roles sr JOIN roles r ON r.id = sr.role_id WHERE sr.staff_id = $staff_id LIMIT 1");
    if ($role_query && $r = $role_query->fetch_assoc()) {
        $role_name = $r['name'];
        $role_id = (int)$r['id'];
    }

    // Fetch individual store permissions for this role
    $permissions = [];
    $perm_query = $db->query("SELECT pc.short_code, rp.can_view, rp.can_add, rp.can_edit, rp.can_delete 
                              FROM roles_permissions rp 
                              JOIN permission_category pc ON pc.id = rp.perm_cat_id 
                              WHERE rp.role_id = $role_id AND pc.perm_group_id = 1600");
    if ($perm_query) {
        while ($p = $perm_query->fetch_assoc()) {
            $permissions[$p['short_code']] = [
                'can_view'   => (int)$p['can_view'],
                'can_add'    => (int)$p['can_add'],
                'can_edit'   => (int)$p['can_edit'],
                'can_delete' => (int)$p['can_delete'],
            ];
        }
    }

    // Establish authenticated Store session
    $_SESSION['store_user'] = [
        'id'          => $staff['id'],
        'name'        => trim($staff['name'] . ' ' . $staff['surname']),
        'email'       => $staff['email'],
        'employee_id' => $staff['employee_id'],
        'role_id'     => $role_id,
        'role'        => $role_name,
        'permissions' => $permissions,
        'branch_id'   => $token_data['branch_id'] ?? 1,
        'session_id'  => $token_data['session_id'] ?? 1,
        'logged_in'   => true
    ];

    header("Location: " . BASE_URL . "dashboard");
    exit();
}

// --- 2. Logout Route ---
if ($route === 'logout') {
    unset($_SESSION['store_user']);
    session_destroy();
    echo "<script>window.close();</script>";
    die("Logged out of School Store. You can close this tab.");
}

// --- 3. Authentication Barrier for Protected Store Routes ---
if (empty($_SESSION['store_user']) || !$_SESSION['store_user']['logged_in']) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>School Store Authentication</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; color: #fff; }
            .auth-card { background: #1e293b; padding: 44px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5); text-align: center; max-width: 440px; border: 1px solid rgba(255,255,255,0.08); }
            .icon-box { width: 72px; height: 72px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px; font-size: 32px; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.4); }
            h2 { color: #fff; margin: 0 0 10px 0; font-size: 22px; font-weight: 800; }
            p { color: #94a3b8; font-size: 14.5px; line-height: 1.6; margin: 0 0 28px 0; }
            .btn { display: inline-block; background: #4f46e5; color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 700; font-size: 14px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); transition: 0.2s; }
            .btn:hover { background: #4338ca; transform: translateY(-1px); }
        </style>
    </head>
    <body>
        <div class="auth-card">
            <div class="icon-box">🏬</div>
            <h2>School Store Portal</h2>
            <p>Access is restricted. Please log into the School ERP and click the <strong>Store & Inventory</strong> icon on your top navigation bar.</p>
            <a href="javascript:window.close();" class="btn">Close Tab</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// User context
$user = $_SESSION['store_user'];

// --- Modular Controller Dispatcher ---
$controllers = [
    'items'         => 'items.php',
    'bundles'       => 'bundles.php',
    'import_export' => 'import_export.php',
    'categories'    => 'categories.php',
    'vendors'       => 'vendors.php',
    'po'            => 'po.php',
    'grn'           => 'grn.php',
    'pos'           => 'pos.php',
    'requisitions'  => 'requisitions.php',
    'loans'         => 'loans.php',
    'reports'       => 'reports.php'
];

if (isset($controllers[$route])) {
    require_once __DIR__ . '/controllers/' . $controllers[$route];
    exit();
}

// Quick Stats for Dashboard
$total_items = $db->query("SELECT COUNT(*) as c FROM store_items WHERE is_active = 1")->fetch_assoc()['c'] ?? 0;
$total_vendors = $db->query("SELECT COUNT(*) as c FROM store_vendors WHERE is_active = 1")->fetch_assoc()['c'] ?? 0;
$total_loans_active = $db->query("SELECT COUNT(*) as c FROM store_loans WHERE status IN ('Issued','Overdue')")->fetch_assoc()['c'] ?? 0;
$total_sales_today = $db->query("SELECT COALESCE(SUM(grand_total), 0) as s FROM store_sales WHERE sale_date = CURDATE()")->fetch_assoc()['s'] ?? 0;

// Fetch Recent Sales
$recent_sales = $db->query("SELECT invoice_no, customer_name, class_section, grand_total, payment_mode, sale_date FROM store_sales ORDER BY id DESC LIMIT 5");

// Fetch Active Asset Loans
$active_loans = $db->query("SELECT l.loan_slip_no, i.item_name, s.name, s.surname, l.due_return_date, l.status 
    FROM store_loans l 
    JOIN store_items i ON i.id = l.item_id 
    JOIN staff s ON s.id = l.issued_to_staff_id 
    WHERE l.status IN ('Issued','Overdue') 
    ORDER BY l.id DESC LIMIT 5");

require_once __DIR__ . '/views/layout.php';
render_header('Store & Inventory Dashboard', 'dashboard');
?>

<!-- Top Welcome & Fast Action Bar -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 14px;">
    <div>
        <h2 style="font-size: 22px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin: 0;">Inventory & Sales Overview</h2>
        <p style="font-size: 13.5px; color: #64748b; margin-top: 4px;">Live tracking of counter POS sales, godown stock balances, and department requisitions</p>
    </div>
    <div style="display: flex; gap: 10px; align-items: center;">
        <a href="<?= BASE_URL ?>pos" class="btn btn-primary" style="padding: 10px 20px; font-size: 13.5px; font-weight: 700;">
            <i class="fa fa-cash-register"></i> Fast POS Counter
        </a>
        <a href="<?= BASE_URL ?>requisitions" class="btn btn-secondary" style="padding: 10px 16px; font-size: 13.5px;">
            <i class="fa fa-hand-holding-hand" style="color: var(--primary);"></i> Issue Requisition
        </a>
    </div>
</div>

<!-- Section Header matching Image 1: STUDENTS OVERVIEW -> STORE OVERVIEW -->
<div style="margin-bottom: 18px;">
    <h3 style="font-size: 13.5px; font-weight: 800; text-transform: uppercase; color: #475569; letter-spacing: 0.8px; margin: 0;">STORE OVERVIEW</h3>
</div>

<!-- KPI Stat Cards Grid (Matches Screenshot 1 Exactly) -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-bottom: 28px;">
    <!-- Stat 1: TOTAL PRODUCTS (Warm Bronze/Orange) -->
    <div style="background: #ffffff; border: 1px solid #fed7aa; border-radius: 12px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; color: #c2410c; letter-spacing: 0.6px;">TOTAL PRODUCTS</span>
            <a href="<?= BASE_URL ?>items" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 3px 8px; color: #64748b; font-size: 12px; background: #ffffff; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;" title="View Catalog">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        <div style="font-size: 34px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 8px; letter-spacing: -1px;"><?= number_format($total_items) ?></div>
        <div style="font-size: 12.5px; color: #64748b; font-weight: 500;">Active Products Enrolled</div>
    </div>

    <!-- Stat 2: TODAY'S POS SALES (Emerald Green) -->
    <div style="background: #ffffff; border: 1px solid #a7f3d0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; color: #059669; letter-spacing: 0.6px;">TODAY'S POS SALES</span>
            <a href="<?= BASE_URL ?>pos" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 3px 8px; color: #64748b; font-size: 12px; background: #ffffff; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;" title="Open POS">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        <div style="font-size: 34px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 8px; letter-spacing: -1px;">₹<?= number_format($total_sales_today, 0) ?></div>
        <div style="font-size: 12.5px; color: #64748b; font-weight: 500;">Sales Revenue Today</div>
    </div>

    <!-- Stat 3: ASSET LOANS (Amber / Gold) -->
    <div style="background: #ffffff; border: 1px solid #fde68a; border-radius: 12px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; color: #d97706; letter-spacing: 0.6px;">ASSET LOANS</span>
            <a href="<?= BASE_URL ?>loans" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 3px 8px; color: #64748b; font-size: 12px; background: #ffffff; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;" title="View Loans">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        <div style="font-size: 34px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 8px; letter-spacing: -1px;"><?= number_format($total_loans_active) ?></div>
        <div style="font-size: 12.5px; color: #64748b; font-weight: 500;">Assets on loan to staff</div>
    </div>

    <!-- Stat 4: VERIFIED VENDORS (Purple / Violet) -->
    <div style="background: #ffffff; border: 1px solid #ddd6fe; border-radius: 12px; padding: 18px 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); position: relative;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <span style="font-size: 11.5px; font-weight: 800; text-transform: uppercase; color: #7c3aed; letter-spacing: 0.6px;">ACTIVE VENDORS</span>
            <a href="<?= BASE_URL ?>vendors" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 3px 8px; color: #64748b; font-size: 12px; background: #ffffff; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;" title="View Vendors">
                <i class="fa fa-eye"></i>
            </a>
        </div>
        <div style="font-size: 34px; font-weight: 800; color: #0f172a; line-height: 1; margin-bottom: 8px; letter-spacing: -1px;"><?= number_format($total_vendors) ?></div>
        <div style="font-size: 12.5px; color: #64748b; font-weight: 500;">Approved Suppliers</div>
    </div>
</div>

<?php
// Low Stock Automated Check
$low_stock_query = $db->query("SELECT i.id, i.item_name, i.item_code, i.min_stock_alert, i.purchase_price,
                               COALESCE(SUM(b.quantity), 0) as current_stock,
                               (i.min_stock_alert * 2 - COALESCE(SUM(b.quantity), 0)) as suggested_reorder_qty
                               FROM store_items i
                               LEFT JOIN store_stock_balances b ON b.item_id = i.id AND b.godown_id = 1
                               WHERE i.is_active = 1
                               GROUP BY i.id
                               HAVING current_stock <= i.min_stock_alert
                               ORDER BY current_stock ASC");
$low_stock_items = [];
while ($row = $low_stock_query->fetch_assoc()) {
    $low_stock_items[] = $row;
}
?>

<?php if (!empty($low_stock_items)): ?>
<!-- Automated Low Stock & Reorder Alert Banner -->
<div class="card" style="border-left: 4px solid #ef4444; background: #fff;">
    <div class="card-header" style="background: #fef2f2; border-bottom: 1px solid #fee2e2;">
        <div style="display:flex; align-items:center; gap:10px;">
            <i class="fa fa-triangle-exclamation" style="color: #dc2626; font-size:18px;"></i>
            <span style="font-weight: 800; font-size: 15px; color: #991b1b;">
                Low Stock Alert (<?= count($low_stock_items) ?> Items Below Safety Level)
            </span>
        </div>
        <a href="<?= BASE_URL ?>po" class="btn btn-danger" style="padding: 6px 14px; font-size: 12px;">
            <i class="fa fa-cart-shopping"></i> Create Reorder PO
        </a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Item Code</th>
                    <th>Item Description</th>
                    <th>Safety Threshold</th>
                    <th>Current Stock</th>
                    <th>Recommended Reorder Qty</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($low_stock_items as $lsi): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($lsi['item_code']) ?></code></td>
                        <td><strong><?= htmlspecialchars($lsi['item_name']) ?></strong></td>
                        <td><?= number_format($lsi['min_stock_alert']) ?></td>
                        <td><span class="badge badge-danger"><?= number_format($lsi['current_stock']) ?> Units Left</span></td>
                        <td style="font-weight:700; color:#dc2626;">+<?= max(5, number_format($lsi['suggested_reorder_qty'])) ?> Units</td>
                        <td>
                            <a href="<?= BASE_URL ?>po" class="btn btn-secondary" style="padding: 4px 10px; font-size: 11px;">
                                <i class="fa fa-file-invoice"></i> Order
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
// --- 1. Weekly Sales Trend Data (Past 7 Days) ---
$weekly_trend_q = $db->query("SELECT DATE(sale_date) as sdate, COALESCE(SUM(grand_total), 0) as total_rev
                              FROM store_sales
                              WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
                              GROUP BY DATE(sale_date)
                              ORDER BY sdate ASC");
$weekly_data = [];
// Pre-fill last 7 days
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $weekly_data[$d] = [
        'day' => date('D', strtotime($d)),
        'date' => date('d M', strtotime($d)),
        'total' => 0
    ];
}
while ($r = $weekly_trend_q->fetch_assoc()) {
    if (isset($weekly_data[$r['sdate']])) {
        $weekly_data[$r['sdate']]['total'] = (float)$r['total_rev'];
    }
}
$max_weekly = max(100, max(array_column($weekly_data, 'total')));

// --- 2. Top-Selling Categories Breakdown ---
$top_cats_q = $db->query("SELECT c.name as category_name, COALESCE(SUM(si.net_amount), 0) as rev, COUNT(si.id) as units
                          FROM store_categories c
                          JOIN store_items i ON i.category_id = c.id
                          JOIN store_sale_items si ON si.item_id = i.id
                          GROUP BY c.id
                          ORDER BY rev DESC
                          LIMIT 5");
$cat_data = [];
$total_cat_rev = 0;
while ($cr = $top_cats_q->fetch_assoc()) {
    $cat_data[] = $cr;
    $total_cat_rev += (float)$cr['rev'];
}
$cat_colors = ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b', '#ec4899'];

// --- 3. Department Requisition Consumption Breakdown ---
$dept_q = $db->query("SELECT department, COUNT(DISTINCT r.id) as req_count, COALESCE(SUM(ri.issued_qty), 0) as total_items
                      FROM store_requisitions r
                      JOIN store_requisition_items ri ON ri.requisition_id = r.id
                      WHERE r.department IS NOT NULL AND r.department != ''
                      GROUP BY r.department
                      ORDER BY total_items DESC
                      LIMIT 4");
$dept_data = [];
while ($dr = $dept_q->fetch_assoc()) {
    $dept_data[] = $dr;
}
$max_dept_items = !empty($dept_data) ? max(1, max(array_column($dept_data, 'total_items'))) : 1;
?>

<!-- Interactive Visual Dashboard Grid -->
<!-- Interactive Visual Dashboard Grid (Matches Image 1 Class-Wise & Age Analysis cards) -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 28px;">
    <!-- Chart 1: Weekly Sales Trend (Responsive SVG Bar Chart) -->
    <div class="card" style="margin: 0; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 16px 20px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size: 13.5px; font-weight: 800; text-transform: uppercase; color: #1e293b; letter-spacing: 0.6px;">WEEKLY SALES TREND</span>
            </div>
            <a href="<?= BASE_URL ?>reports" style="border: 1px solid #cbd5e1; border-radius: 6px; background: #ffffff; padding: 5px 14px; font-size: 12px; font-weight: 700; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                View Metrics
            </a>
        </div>
        <div class="card-body" style="padding: 24px;">
            <?php 
            $has_weekly_sales = array_sum(array_column($weekly_data, 'total')) > 0;
            if ($has_weekly_sales): 
            ?>
            <!-- Custom CSS/SVG Interactive Bar Chart -->
            <div style="display: flex; align-items: flex-end; justify-content: space-between; height: 190px; padding-top: 20px; border-bottom: 1.5px solid #e2e8f0; gap: 14px;">
                <?php foreach ($weekly_data as $wd): ?>
                    <?php 
                    $pct = max(6, round(($wd['total'] / $max_weekly) * 100));
                    $is_today = ($wd['date'] === date('d M'));
                    $bar_bg = $is_today ? 'var(--primary-gradient)' : 'linear-gradient(180deg, #6366f1 0%, #4338ca 100%)';
                    $val_color = $is_today ? 'var(--primary)' : '#64748b';
                    ?>
                    <div style="flex: 1; display: flex; flex-direction: column; align-items: center; height: 100%; justify-content: flex-end;">
                        <span style="font-size: 11px; font-weight: 800; color: <?= $val_color ?>; margin-bottom: 8px;">
                            <?= $wd['total'] > 0 ? '₹' . number_format($wd['total']) : '₹0' ?>
                        </span>
                        <div style="width: 100%; max-width: 44px; height: <?= $pct ?>%; background: <?= $bar_bg ?>; border-radius: 6px 6px 0 0; transition: height 0.4s cubic-bezier(0.4, 0, 0.2, 1);" title="<?= $wd['date'] ?>: ₹<?= number_format($wd['total'], 2) ?>"></div>
                        <span style="font-size: 12px; font-weight: 700; color: <?= $is_today ? 'var(--primary)' : '#475569' ?>; margin-top: 10px;"><?= $wd['day'] ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 42px 20px; color: #94a3b8;">
                <i class="fa fa-chart-column" style="font-size: 36px; color: #cbd5e1; margin-bottom: 10px; display: block;"></i>
                <div style="font-weight: 700; color: #64748b; font-size: 14px; margin-bottom: 4px;">No Sales in the Last 7 Days</div>
                <div style="font-size: 12.5px;">Once counter sales are made in <a href="<?= BASE_URL ?>pos" style="color: var(--primary); font-weight:700; text-decoration:underline;">POS Billing</a>, daily revenue trends will appear here.</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Chart 2: Top Selling Categories Breakdown (Matches Age Analysis card in Image 1) -->
    <div class="card" style="margin: 0; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
        <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 16px 20px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size: 13.5px; font-weight: 800; text-transform: uppercase; color: #1e293b; letter-spacing: 0.6px;">CATEGORY BREAKDOWN</span>
            </div>
            <a href="<?= BASE_URL ?>categories" style="border: 1px solid #cbd5e1; border-radius: 6px; background: #ffffff; padding: 5px 14px; font-size: 12px; font-weight: 700; color: #334155; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='#fff'">
                View Metrics
            </a>
        </div>
        <div class="card-body" style="padding: 24px;">
            <?php if (!empty($cat_data)): ?>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    <?php foreach ($cat_data as $idx => $cd): ?>
                        <?php 
                        $cat_share = $total_cat_rev > 0 ? round(($cd['rev'] / $total_cat_rev) * 100) : 0;
                        $color = $cat_colors[$idx % count($cat_colors)];
                        ?>
                        <div>
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 12.5px; margin-bottom: 6px;">
                                <span style="font-weight: 700; color: #334155; display:flex; align-items:center; gap:8px;">
                                    <span style="width: 10px; height: 10px; border-radius: 50%; background: <?= $color ?>; box-shadow: 0 0 6px <?= $color ?>;"></span>
                                    <?= htmlspecialchars($cd['category_name']) ?>
                                </span>
                                <span style="font-weight: 800; color: #0f172a;"><?= $cat_share ?>% <span style="font-size: 11px; color:#64748b; font-weight:600;">(₹<?= number_format($cd['rev']) ?>)</span></span>
                            </div>
                            <div style="width: 100%; height: 9px; background: #f1f5f9; border-radius: 10px; overflow: hidden; border: 1px solid #e2e8f0;">
                                <div style="width: <?= $cat_share ?>%; height: 100%; background: <?= $color ?>; border-radius: 10px; transition: width 0.4s ease;"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align:center; padding:32px 20px; color:#94a3b8; font-size:13px;">
                    <i class="fa fa-chart-pie" style="font-size:32px; color:#cbd5e1; margin-bottom:8px; display:block;"></i>
                    No sales transactions recorded yet.<br>Category breakdown will populate after your first POS sale.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Department Consumption Analytics -->
<div class="card" style="margin-bottom: 30px;">
    <div class="card-header" style="background:#fff;">
        <span class="card-title"><i class="fa fa-building-user" style="color: #10b981;"></i> Department Material Consumption Breakdown</span>
        <span style="font-size: 12.5px; color: var(--text-muted); font-weight: 500;">Units Issued via Requisitions</span>
    </div>
    <div class="card-body">
        <?php if (!empty($dept_data)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                <?php foreach ($dept_data as $dd): ?>
                    <?php $pct_dept = round(($dd['total_items'] / $max_dept_items) * 100); ?>
                    <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 18px;">
                        <div style="font-size: 13.5px; font-weight: 700; color: #1e293b;"><?= htmlspecialchars($dd['department']) ?></div>
                        <div style="font-size: 24px; font-weight: 800; color: #0f172a; margin: 8px 0 4px;"><?= number_format($dd['total_items']) ?> <span style="font-size: 13px; font-weight: 500; color: #64748b;">Units</span></div>
                        <div style="font-size: 11.5px; color: #64748b; margin-bottom: 8px;"><?= $dd['req_count'] ?> Requisitions Approved</div>
                        <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                            <div style="width: <?= $pct_dept ?>%; height: 100%; background: #10b981; border-radius: 10px;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:30px; color:#94a3b8; font-size:13px;">
                <i class="fa fa-building-user" style="font-size:32px; color:#cbd5e1; margin-bottom:8px; display:block;"></i>
                No material requisitions issued to school departments yet.
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Operational Split Panels -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 28px;">
    <!-- Recent Counter Sales Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fa fa-receipt" style="color: var(--primary);"></i> Recent Student Counter Sales</span>
            <a href="<?= BASE_URL ?>pos" class="btn btn-primary" style="padding: 6px 14px; font-size: 12px;"><i class="fa fa-plus"></i> New Sale</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Student / Customer</th>
                        <th>Class</th>
                        <th>Amount</th>
                        <th>Mode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($recent_sales && $recent_sales->num_rows > 0): ?>
                        <?php while ($s = $recent_sales->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($s['invoice_no']) ?></strong></td>
                                <td style="font-weight: 600;"><?= htmlspecialchars($s['customer_name']) ?></td>
                                <td><?= htmlspecialchars($s['class_section'] ?? '-') ?></td>
                                <td style="font-weight: 700; color: var(--primary);">₹<?= number_format($s['grand_total'], 2) ?></td>
                                <td><span class="badge badge-success"><?= htmlspecialchars($s['payment_mode']) ?></span></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding: 25px; color:#64748b;">No counter sales recorded yet today.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Active Equipment Loans Panel -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fa fa-clock-rotate-left" style="color: #f59e0b;"></i> Active Equipment Loans</span>
            <a href="<?= BASE_URL ?>loans" class="btn btn-secondary" style="padding: 6px 14px; font-size: 12px;"><i class="fa fa-arrow-up-right-from-square"></i> View All</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Slip #</th>
                        <th>Asset Name</th>
                        <th>Staff Member</th>
                        <th>Due Return</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($active_loans && $active_loans->num_rows > 0): ?>
                        <?php while ($l = $active_loans->fetch_assoc()): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($l['loan_slip_no']) ?></strong></td>
                                <td style="font-weight: 600;"><?= htmlspecialchars($l['item_name']) ?></td>
                                <td><?= htmlspecialchars($l['name'] . ' ' . $l['surname']) ?></td>
                                <td><?= htmlspecialchars($l['due_return_date']) ?></td>
                                <td>
                                    <span class="badge <?= $l['status'] == 'Overdue' ? 'badge-danger' : 'badge-warning' ?>">
                                        <?= htmlspecialchars($l['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align:center; padding: 25px; color:#64748b;">No active equipment loans pending return.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php render_footer(); ?>
