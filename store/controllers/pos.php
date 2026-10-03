<?php
// Store Controller: Point of Sale (POS) Counter Billing for Students & Parents
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';
$staff_id = $user['id'];
$last_invoice_id = null;

// Handle Print Receipt View
if (isset($_GET['print'])) {
    $sale_id = (int)$_GET['print'];
    $sale = $db->query("SELECT s.*, st.admission_no, st.father_name, st.firstname, st.lastname 
        FROM store_sales s 
        LEFT JOIN students st ON st.id = s.student_id 
        WHERE s.id = $sale_id LIMIT 1")->fetch_assoc();

    if ($sale) {
        $items = $db->query("SELECT si.*, i.item_name, i.item_code 
            FROM store_sale_items si 
            JOIN store_items i ON i.id = si.item_id 
            WHERE si.sale_id = $sale_id");
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Invoice <?= htmlspecialchars($sale['invoice_no']) ?></title>
            <style>
                body { font-family: 'Courier New', monospace; font-size: 13px; max-width: 320px; margin: 0 auto; padding: 15px; }
                .center { text-align: center; }
                .line { border-bottom: 1px dashed #000; margin: 8px 0; }
                table { width: 100%; border-collapse: collapse; font-size: 12px; }
                th, td { padding: 4px 0; text-align: left; }
                .right { text-align: right; }
                @media print { .no-print { display: none; } }
            </style>
        </head>
        <body onload="window.print()">
            <div class="center">
                <h3 style="margin: 0;">SUNRISE ENGLISH MEDIUM SCHOOL</h3>
                <p style="margin: 2px 0; font-size: 11px;">School Store & Book Depot</p>
                <p style="margin: 2px 0; font-size: 11px;">Receipt / Cash Memo</p>
            </div>
            <div class="line"></div>
            <div><strong>Inv No:</strong> <?= $sale['invoice_no'] ?></div>
            <div><strong>Date:</strong> <?= date('d-m-Y H:i', strtotime($sale['created_at'])) ?></div>
            <div><strong>Student:</strong> <?= htmlspecialchars($sale['customer_name']) ?></div>
            <?php if (!empty($sale['class_section'])): ?>
                <div><strong>Class:</strong> <?= htmlspecialchars($sale['class_section']) ?></div>
            <?php endif; ?>
            <?php if (!empty($sale['admission_no'])): ?>
                <div><strong>Adm No:</strong> <?= htmlspecialchars($sale['admission_no']) ?></div>
            <?php endif; ?>
            <div class="line"></div>
            <table>
                <thead>
                    <tr><th>Item</th><th class="right">Qty</th><th class="right">Rate</th><th class="right">Amt</th></tr>
                </thead>
                <tbody>
                    <?php while ($it = $items->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($it['item_name']) ?></td>
                            <td class="right"><?= number_format($it['quantity']) ?></td>
                            <td class="right"><?= number_format($it['unit_price'], 2) ?></td>
                            <td class="right"><?= number_format($it['net_amount'], 2) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <div class="line"></div>
            <div style="display:flex; justify-content:space-between;"><span>Subtotal:</span><span>₹<?= number_format($sale['subtotal'], 2) ?></span></div>
            <?php if ($sale['discount_amount'] > 0): ?>
                <div style="display:flex; justify-content:space-between;"><span>Discount:</span><span>-₹<?= number_format($sale['discount_amount'], 2) ?></span></div>
            <?php endif; ?>
            <div style="display:flex; justify-content:space-between; font-weight:bold; font-size:14px; margin-top:4px;"><span>GRAND TOTAL:</span><span>₹<?= number_format($sale['grand_total'], 2) ?></span></div>
            <div style="display:flex; justify-content:space-between; margin-top:4px;"><span>Payment Mode:</span><span><?= htmlspecialchars($sale['payment_mode']) ?></span></div>
            <div class="line"></div>
            <div class="center" style="font-size:11px; margin-top:10px;">
                Thank you!<br>Items once sold will only be exchanged within 3 days with receipt.
            </div>
            <div class="no-print" style="margin-top: 20px; text-align:center;">
                <button onclick="window.close()" style="padding: 6px 14px;">Close</button>
            </div>
        </body>
        </html>
        <?php
        exit();
    }
}

// Process Counter Sale
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_sale'])) {
    $student_id = !empty($_POST['student_id']) ? (int)$_POST['student_id'] : null;
    $customer_name = trim($_POST['customer_name'] ?? '');
    $class_section = trim($_POST['class_section'] ?? '');
    $contact_no = trim($_POST['contact_no'] ?? '');
    $payment_mode = $_POST['payment_mode'] ?? 'Cash';
    $godown_id = (int)($_POST['godown_id'] ?? 1);
    $items = $_POST['items'] ?? [];

    if (empty($customer_name) || empty($items)) {
        $err = "Student name and at least one item are required.";
    } else {
        $db->begin_transaction();
        try {
            $invoice_no = 'INV-' . date('Ymd') . '-' . rand(1000, 9999);
            $subtotal = 0;

            foreach ($items as $it) {
                $qty = (float)($it['qty'] ?? 0);
                $rate = (float)($it['rate'] ?? 0);
                $subtotal += ($qty * $rate);
            }

            $discount = (float)($_POST['discount'] ?? 0);
            $grand_total = max(0, $subtotal - $discount);
            $sale_date = date('Y-m-d');

            // Insert Sale Master
            $stmt = $db->prepare("INSERT INTO store_sales (invoice_no, godown_id, student_id, customer_name, class_section, contact_no, sale_date, subtotal, discount_amount, grand_total, payment_mode, billed_by_staff_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siissssdddsi", $invoice_no, $godown_id, $student_id, $customer_name, $class_section, $contact_no, $sale_date, $subtotal, $discount, $grand_total, $payment_mode, $staff_id);
            $stmt->execute();
            $sale_id = $db->insert_id;
            $last_invoice_id = $sale_id;

            // Insert Sale Items, Decrement Stock & Append Ledger
            foreach ($items as $it) {
                $item_id = (int)($it['item_id'] ?? 0);
                $qty = (float)($it['qty'] ?? 0);
                $rate = (float)($it['rate'] ?? 0);
                $line_net = $qty * $rate;

                if ($item_id > 0 && $qty > 0) {
                    $item_stmt = $db->prepare("INSERT INTO store_sale_items (sale_id, item_id, quantity, unit_price, net_amount) VALUES (?, ?, ?, ?, ?)");
                    $item_stmt->bind_param("iiddd", $sale_id, $item_id, $qty, $rate, $line_net);
                    $item_stmt->execute();

                    // Decrement Stock
                    $bal_stmt = $db->prepare("UPDATE store_stock_balances SET quantity = GREATEST(0, quantity - ?) WHERE godown_id = ? AND item_id = ?");
                    $bal_stmt->bind_param("dii", $qty, $godown_id, $item_id);
                    $bal_stmt->execute();

                    // Get new on-hand balance
                    $cur_bal_q = $db->query("SELECT quantity FROM store_stock_balances WHERE godown_id = $godown_id AND item_id = $item_id LIMIT 1");
                    $cur_bal = $cur_bal_q->fetch_assoc()['quantity'] ?? 0;

                    // Append immutable ledger entry
                    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                    $led_stmt = $db->prepare("INSERT INTO store_stock_ledger (godown_id, item_id, transaction_type, reference_id, reference_no, qty_out, unit_price, balance_after, created_by_staff_id, ip_address) VALUES (?, ?, 'SALE', ?, ?, ?, ?, ?, ?, ?)");
                    $led_stmt->bind_param("iiisdddis", $godown_id, $item_id, $sale_id, $invoice_no, $qty, $rate, $cur_bal, $staff_id, $ip);
                    $led_stmt->execute();
                }
            }

            $db->commit();
            $msg = "Invoice {$invoice_no} completed successfully!";
        } catch (Exception $e) {
            $db->rollback();
            $err = "Error processing billing: " . $e->getMessage();
        }
    }
}

// Fetch categories & saleable items with category info
$categories = $db->query("SELECT id, name FROM store_categories WHERE is_active = 1 ORDER BY name ASC");
$items_query = $db->query("SELECT i.id, i.category_id, c.name as category_name, i.item_name, i.item_code, i.barcode, i.sale_price, COALESCE(b.quantity, 0) as stock
    FROM store_items i
    LEFT JOIN store_categories c ON c.id = i.category_id
    LEFT JOIN store_stock_balances b ON b.item_id = i.id AND b.godown_id = 1
    WHERE i.is_active = 1 AND i.is_saleable = 1
    ORDER BY i.item_name ASC");

$saleable_items = [];
while ($it = $items_query->fetch_assoc()) {
    $saleable_items[] = $it;
}

// Pre-configured Bundles
$bundles_query = $db->query("SELECT id, bundle_name, bundle_code, total_price, class_name FROM store_bundles WHERE is_active = 1 ORDER BY bundle_name ASC");
$bundles_list = [];
while ($b = $bundles_query->fetch_assoc()) {
    $bundles_list[] = $b;
}

// Recent sales
$recent_sales = $db->query("SELECT * FROM store_sales ORDER BY id DESC LIMIT 6");

render_header('Point of Sale (POS) Counter Billing', 'pos');
?>

<style>
/* Modern POS Styles */
.pos-container {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    align-items: flex-start;
    width: 100%;
}
.pos-left-col {
    flex: 1 1 540px;
    min-width: 0;
}
.pos-cart-col {
    flex: 1 1 360px;
    min-width: 320px;
}
.student-fields-grid {
    display: grid;
    grid-template-columns: 1.2fr 1fr 1fr;
    gap: 12px;
}
.catalog-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    position: relative;
    user-select: none;
}
.catalog-card:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px -5px rgba(79, 70, 229, 0.15);
}
.catalog-card:active {
    transform: scale(0.98);
}
.cat-pill {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #cbd5e1;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.cat-pill.active, .cat-pill:hover {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
}
.cart-table th {
    font-size: 11.5px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    padding: 10px 8px;
}
.cart-table td {
    padding: 10px 8px;
    vertical-align: middle;
}
.qty-stepper {
    display: inline-flex;
    align-items: center;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
}
.qty-stepper button {
    background: #f8fafc;
    border: none;
    padding: 4px 10px;
    cursor: pointer;
    font-weight: 800;
    color: #334155;
    transition: 0.15s;
}
.qty-stepper button:hover {
    background: #e2e8f0;
}
.qty-stepper input {
    width: 44px;
    text-align: center;
    border: none;
    font-weight: 700;
    font-size: 13.5px;
    outline: none;
}
.payment-btn-label {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #fff;
    border: 2px solid #e2e8f0;
    padding: 12px 14px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 700;
    font-size: 13px;
    color: #334155;
    transition: all 0.15s ease;
}
.payment-btn-label input[type="radio"] {
    display: none;
}
.payment-btn-label.active {
    border-color: var(--primary);
    background: #eef2ff;
    color: var(--primary);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
}
.bundle-badge {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #fff;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 800;
}

/* Floating Mobile Cart Bar */
.mobile-cart-bar {
    display: none;
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: #0f172a;
    color: #fff;
    padding: 12px 20px;
    z-index: 999;
    box-shadow: 0 -4px 20px rgba(0,0,0,0.25);
    align-items: center;
    justify-content: space-between;
}

/* Responsive Breakpoints */
@media (max-width: 1024px) {
    .pos-container {
        flex-direction: column-reverse;
        gap: 20px;
    }
    .pos-cart-col {
        position: relative !important;
        top: 0 !important;
        width: 100% !important;
    }
    .student-fields-grid {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)) !important;
    }
}

@media (max-width: 640px) {
    .content {
        padding: 14px 12px !important;
    }
    .pos-container {
        gap: 14px;
    }
    #catalog_grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)) !important;
        gap: 10px !important;
        max-height: 380px !important;
    }
    .catalog-card {
        padding: 10px;
    }
    .payment-btn-label {
        padding: 8px 10px;
        font-size: 11.5px;
    }
    .cart-table th, .cart-table td {
        padding: 8px 4px;
        font-size: 11.5px;
    }
    .qty-stepper button {
        padding: 3px 6px;
    }
    .qty-stepper input {
        width: 32px;
        font-size: 12px;
    }
}
</style>

<?php if ($msg): ?>
    <div class="alert alert-success" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
        <div style="font-weight: 700; display:flex; align-items:center; gap:8px;">
            <i class="fa fa-circle-check" style="font-size:20px; color:#10b981;"></i> 
            <span><?= htmlspecialchars($msg) ?></span>
        </div>
        <?php if ($last_invoice_id): ?>
            <a href="<?= BASE_URL ?>pos?print=<?= $last_invoice_id ?>" target="_blank" class="btn btn-secondary" style="background:#fff; color:#065f46; font-weight:700;">
                <i class="fa fa-print"></i> Print Cash Memo
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>
<?php if ($err): ?>
    <div class="alert alert-danger" style="margin-bottom: 20px;">
        <i class="fa fa-triangle-exclamation"></i> <?= htmlspecialchars($err) ?>
    </div>
<?php endif; ?>

<div class="pos-container">
    <!-- LEFT COLUMN: Catalogue & Quick Item Selector -->
    <div class="pos-left-col">
        <!-- Student Quick Selection Banner -->
        <div class="card" style="margin-bottom: 20px; border-left: 4px solid var(--primary); overflow: visible !important; position: relative; z-index: 50;">
            <div class="card-header" style="background:#fff; padding: 14px 20px;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fa fa-user-graduate" style="color:var(--primary); font-size:18px;"></i>
                    <span style="font-weight:800; font-size:14px; color:#0f172a;">Student & Customer Details</span>
                </div>
                <div id="student_badge_status" style="font-size:12px; color:#64748b; font-weight:600;">Walk-in Customer</div>
            </div>
            <div class="card-body" style="padding: 16px 20px; overflow: visible !important;">
                <!-- Live Search Bar -->
                <div style="position:relative; margin-bottom: 14px; z-index: 60;">
                    <i class="fa fa-magnifying-glass" style="position:absolute; left:14px; top:12px; color:#94a3b8; font-size:14px;"></i>
                    <input type="text" id="student_search_input" class="form-control" style="padding-left: 38px; font-weight:600;" placeholder="Search student by Name, Admission No, or Mobile (e.g. Lakshay, 5559)..." autocomplete="off">
                    <div id="student_suggestions" class="suggestions-box" style="z-index: 9999; max-height: 280px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 1.5px solid #cbd5e1;"></div>
                </div>

                <!-- Fields -->
                <div class="student-fields-grid">
                    <div>
                        <input type="text" form="posForm" name="customer_name" id="customer_name" class="form-control" placeholder="Customer / Student Name *" required>
                    </div>
                    <div>
                        <input type="text" form="posForm" name="class_section" id="class_section" class="form-control" placeholder="Class & Section (e.g. 5-A)">
                    </div>
                    <div>
                        <input type="text" form="posForm" name="contact_no" id="contact_no" class="form-control" placeholder="Phone Number">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pre-Configured Student Kits Quick Bar -->
        <?php if (!empty($bundles_list)): ?>
        <div class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%); border: 1.5px solid #fde68a;">
            <div style="padding: 12px 18px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fa fa-box-open" style="color:#d97706; font-size:18px;"></i>
                    <span style="font-weight:800; font-size:13.5px; color:#92400e;">1-Click Student Kits & Packs:</span>
                </div>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <?php foreach ($bundles_list as $b): ?>
                        <button type="button" class="btn btn-secondary" style="background:#fff; border-color:#fcd34d; color:#78350f; font-size:12px; font-weight:700; padding:6px 12px;" onclick="addBundleDirect(<?= $b['id'] ?>)">
                            <i class="fa fa-plus-circle" style="color:#d97706;"></i> <?= htmlspecialchars($b['bundle_name']) ?> (₹<?= number_format($b['total_price']) ?>)
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Item Catalogue Section -->
        <div class="card">
            <div class="card-header" style="background:#fff; padding: 14px 20px; flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fa fa-boxes-stacked" style="color:var(--primary); font-size:18px;"></i>
                    <span class="card-title">Item Master Catalog</span>
                </div>
                <!-- Search Item & Barcode Scanner -->
                <div style="position:relative; width: 260px;">
                    <i class="fa fa-barcode" style="position:absolute; left:12px; top:10px; color:#64748b; font-size:15px;"></i>
                    <input type="text" id="catalog_search" class="form-control" style="padding-left:36px; padding-top:6px; padding-bottom:6px; font-size:12.5px;" placeholder="Filter or Scan Barcode...">
                </div>
            </div>

            <div class="card-body" style="padding: 16px 20px;">
                <!-- Category Filter Pills -->
                <div style="display:flex; gap:8px; overflow-x:auto; padding-bottom: 12px; margin-bottom: 14px;">
                    <div class="cat-pill active" onclick="filterCatalog('all', this)">All Items</div>
                    <?php 
                    $categories->data_seek(0);
                    while ($cat = $categories->fetch_assoc()): 
                    ?>
                        <div class="cat-pill" onclick="filterCatalog('<?= $cat['id'] ?>', this)"><?= htmlspecialchars($cat['name']) ?></div>
                    <?php endwhile; ?>
                </div>

                <!-- Items Grid -->
                <div id="catalog_grid" style="display:grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 14px; max-height: 480px; overflow-y:auto; padding-right: 4px;">
                    <?php foreach ($saleable_items as $item): ?>
                        <div class="catalog-card" data-cat="<?= $item['category_id'] ?>" data-name="<?= strtolower(htmlspecialchars($item['item_name'])) ?>" data-code="<?= strtolower(htmlspecialchars($item['item_code'])) ?>" data-barcode="<?= htmlspecialchars($item['barcode'] ?? '') ?>" onclick="addItemToCart(<?= htmlspecialchars(json_encode($item)) ?>)">
                            <div>
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 6px;">
                                    <span style="font-size:10.5px; font-weight:700; color:#64748b; background:#f1f5f9; padding:2px 6px; border-radius:4px;"><?= htmlspecialchars($item['item_code']) ?></span>
                                    <span class="badge <?= $item['stock'] > 5 ? 'badge-success' : ($item['stock'] > 0 ? 'badge-warning' : 'badge-danger') ?>" style="font-size:10px;">
                                        <?= number_format($item['stock']) ?> left
                                    </span>
                                </div>
                                <div style="font-weight:700; font-size:13px; color:#1e293b; line-height:1.35; margin-bottom: 6px;">
                                    <?= htmlspecialchars($item['item_name']) ?>
                                </div>
                            </div>
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-top: 10px; border-top: 1px dashed #e2e8f0; padding-top: 8px;">
                                <span style="font-weight:800; font-size:15px; color:var(--primary);">₹<?= number_format($item['sale_price'], 2) ?></span>
                                <span style="font-size:12px; color:var(--primary); font-weight:700;"><i class="fa fa-plus-circle"></i> Add</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Billing Cart & Checkout -->
    <div class="pos-cart-col">
        <div class="card" style="position: sticky; top: 90px; margin: 0; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);">
            <div class="card-header" style="background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); padding: 16px 20px;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fa fa-cart-shopping" style="color:var(--primary); font-size:18px;"></i>
                    <span class="card-title">Billing Cart (<span id="cart_count_badge">0</span>)</span>
                </div>
                <button type="button" class="btn btn-secondary" style="padding: 4px 10px; font-size: 11px;" onclick="clearCart()">
                    <i class="fa fa-trash-can"></i> Clear
                </button>
            </div>

            <div class="card-body" style="padding: 16px 20px;">
                <form method="POST" action="" id="posForm">
                    <input type="hidden" name="godown_id" value="1">
                    <input type="hidden" name="student_id" id="student_id" value="">

                    <!-- Cart Items Table Container -->
                    <div style="max-height: 300px; overflow-y: auto; margin-bottom: 16px; border: 1.5px solid #e2e8f0; border-radius: 10px;">
                        <table class="table cart-table" style="margin:0; background:#fff;">
                            <thead style="background:#f8fafc; position:sticky; top:0; z-index:1;">
                                <tr>
                                    <th>Item</th>
                                    <th style="text-align:center;">Qty</th>
                                    <th style="text-align:right;">Rate</th>
                                    <th style="text-align:right;">Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <tr id="empty_cart_row">
                                    <td colspan="5" style="text-align:center; padding: 40px 10px; color:#94a3b8;">
                                        <i class="fa fa-basket-shopping" style="font-size:32px; color:#cbd5e1; margin-bottom:8px; display:block;"></i>
                                        Click any item from the catalog or load a student kit pack.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Payment Mode Selector -->
                    <div style="margin-bottom: 16px;">
                        <label style="font-size:12px; font-weight:700; color:#475569; margin-bottom: 6px; display:block;">Payment Method</label>
                        <div style="display:flex; gap:8px;">
                            <label class="payment-btn-label active" id="pay_cash_lbl" onclick="setPaymentMode('Cash', this)">
                                <input type="radio" name="payment_mode" value="Cash" checked>
                                <i class="fa fa-money-bill-1-wave" style="color:#10b981;"></i> Cash
                            </label>
                            <label class="payment-btn-label" id="pay_upi_lbl" onclick="setPaymentMode('UPI', this)">
                                <input type="radio" name="payment_mode" value="UPI">
                                <i class="fa fa-qrcode" style="color:#0ea5e9;"></i> UPI / QR
                            </label>
                            <label class="payment-btn-label" id="pay_ledger_lbl" onclick="setPaymentMode('StudentLedger', this)">
                                <input type="radio" name="payment_mode" value="StudentLedger">
                                <i class="fa fa-book" style="color:#f59e0b;"></i> Ledger
                            </label>
                        </div>
                    </div>

                    <!-- Calculation Totals Block -->
                    <div style="background:#f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 18px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px; color:#475569; margin-bottom: 8px;">
                            <span>Subtotal:</span>
                            <span style="font-weight:700; color:#1e293b;" id="subtotalDisplay">₹0.00</span>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:13px; color:#475569; margin-bottom: 12px;">
                            <span>Discount / Concession:</span>
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span>₹</span>
                                <input type="number" step="0.01" name="discount" id="discountInput" class="form-control" value="0.00" style="width: 90px; text-align:right; padding:4px 8px; font-weight:700;" oninput="calcTotals()">
                            </div>
                        </div>
                        <div style="display:flex; justify-content:space-between; align-items:center; border-top: 1.5px dashed #cbd5e1; padding-top: 12px;">
                            <span style="font-size: 15px; font-weight: 800; color: #0f172a;">Grand Total:</span>
                            <span id="grandTotalDisplay" style="font-size: 24px; font-weight: 800; color: var(--primary);">₹0.00</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <button type="submit" name="process_sale" id="checkoutBtn" class="btn btn-primary" style="width: 100%; padding: 14px; font-size: 15px; font-weight: 800; justify-content:center; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.35);" disabled>
                        <i class="fa fa-receipt"></i> Complete Sale & Issue Memo
                    </button>
                </form>
            </div>
        </div>

        <!-- Recent Completed Sales Widget -->
        <div class="card" style="margin-top: 20px;">
            <div class="card-header" style="background:#fff; padding:12px 18px;">
                <span class="card-title" style="font-size:13.5px;"><i class="fa fa-clock-rotate-left" style="color: #10b981;"></i> Recent Counter Sales</span>
            </div>
            <div class="table-responsive">
                <table class="table" style="font-size:12px; margin:0;">
                    <tbody>
                        <?php if ($recent_sales && $recent_sales->num_rows > 0): ?>
                            <?php while ($s = $recent_sales->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div style="font-weight:700; color:#0f172a;"><?= htmlspecialchars($s['invoice_no']) ?></div>
                                        <div style="color:#64748b; font-size:11px;"><?= htmlspecialchars($s['customer_name']) ?> (<?= htmlspecialchars($s['class_section'] ?? '-') ?>)</div>
                                    </td>
                                    <td style="text-align:right; font-weight:800; color:var(--primary);">
                                        ₹<?= number_format($s['grand_total'], 2) ?>
                                    </td>
                                    <td style="text-align:right;">
                                        <a href="<?= BASE_URL ?>pos?print=<?= $s['id'] ?>" target="_blank" class="btn btn-secondary" style="padding: 3px 8px; font-size: 11px;">
                                            <i class="fa fa-print"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" style="text-align:center; padding:15px; color:#94a3b8;">No sales yet today.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- POS Client Logic -->
<script>
// In-Memory Cart State
let cart = {}; // key: item_id -> { item_id, item_name, item_code, rate, qty, stock }

// 1. Add item to cart
function addItemToCart(item) {
    const id = item.id;
    if (cart[id]) {
        cart[id].qty++;
    } else {
        cart[id] = {
            item_id: id,
            item_name: item.item_name,
            item_code: item.item_code,
            rate: parseFloat(item.sale_price) || 0,
            qty: 1,
            stock: parseFloat(item.stock) || 0
        };
    }
    renderCart();
}

// 2. Change Cart Qty
function updateCartQty(id, delta) {
    if (cart[id]) {
        cart[id].qty += delta;
        if (cart[id].qty <= 0) {
            delete cart[id];
        }
    }
    renderCart();
}

function setCartQtyDirect(id, val) {
    val = parseInt(val) || 0;
    if (val <= 0) {
        delete cart[id];
    } else if (cart[id]) {
        cart[id].qty = val;
    }
    renderCart();
}

function removeCartItem(id) {
    delete cart[id];
    renderCart();
}

function clearCart() {
    cart = {};
    renderCart();
}

// 3. Render Cart Table
function renderCart() {
    const tbody = document.getElementById('cartTableBody');
    const keys = Object.keys(cart);
    document.getElementById('cart_count_badge').innerText = keys.length;

    if (keys.length === 0) {
        tbody.innerHTML = `
            <tr id="empty_cart_row">
                <td colspan="5" style="text-align:center; padding: 40px 10px; color:#94a3b8;">
                    <i class="fa fa-basket-shopping" style="font-size:32px; color:#cbd5e1; margin-bottom:8px; display:block;"></i>
                    Click any item from the catalog or load a student kit pack.
                </td>
            </tr>
        `;
        document.getElementById('checkoutBtn').disabled = true;
        calcTotals();
        return;
    }

    let html = '';
    let rowIdx = 0;
    keys.forEach(id => {
        const it = cart[id];
        const lineTotal = it.qty * it.rate;
        html += `
            <tr>
                <td>
                    <div style="font-weight:700; font-size:12.5px; color:#1e293b; line-height:1.3;">${it.item_name}</div>
                    <div style="font-size:11px; color:#64748b;">${it.item_code}</div>
                    <input type="hidden" name="items[${rowIdx}][item_id]" value="${it.item_id}">
                    <input type="hidden" name="items[${rowIdx}][rate]" value="${it.rate}">
                </td>
                <td style="text-align:center;">
                    <div class="qty-stepper">
                        <button type="button" onclick="updateCartQty(${id}, -1)">-</button>
                        <input type="number" name="items[${rowIdx}][qty]" value="${it.qty}" min="1" onchange="setCartQtyDirect(${id}, this.value)">
                        <button type="button" onclick="updateCartQty(${id}, 1)">+</button>
                    </div>
                </td>
                <td style="text-align:right; font-weight:600; font-size:12.5px; color:#475569;">
                    ₹${it.rate.toFixed(2)}
                </td>
                <td style="text-align:right; font-weight:700; font-size:13px; color:#0f172a;">
                    ₹${lineTotal.toFixed(2)}
                </td>
                <td style="text-align:right;">
                    <button type="button" style="background:none; border:none; color:#ef4444; cursor:pointer; font-size:13px;" onclick="removeCartItem(${id})">
                        <i class="fa fa-trash-can"></i>
                    </button>
                </td>
            </tr>
        `;
        rowIdx++;
    });

    tbody.innerHTML = html;
    document.getElementById('checkoutBtn').disabled = false;
    calcTotals();
}

// 4. Calculate Subtotal and Grand Total
function calcTotals() {
    let subtotal = 0;
    Object.values(cart).forEach(it => {
        subtotal += (it.qty * it.rate);
    });
    const discount = parseFloat(document.getElementById('discountInput').value) || 0;
    const grand = Math.max(0, subtotal - discount);

    document.getElementById('subtotalDisplay').innerText = '₹' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
    document.getElementById('grandTotalDisplay').innerText = '₹' + grand.toLocaleString('en-IN', {minimumFractionDigits: 2});
}

// 5. Fast Add Student Kit Direct
function addBundleDirect(bundleId) {
    fetch('<?= BASE_URL ?>api.php?action=get_bundle_items&bundle_id=' + bundleId)
        .then(res => res.json())
        .then(items => {
            if (!items || items.length === 0) {
                alert('No items found in this student pack.');
                return;
            }
            items.forEach(it => {
                const id = it.item_id;
                const qtyToAdd = parseFloat(it.qty) || 1;
                if (cart[id]) {
                    cart[id].qty += qtyToAdd;
                } else {
                    cart[id] = {
                        item_id: id,
                        item_name: it.item_name,
                        item_code: it.item_code,
                        rate: parseFloat(it.unit_price) || 0,
                        qty: qtyToAdd,
                        stock: parseFloat(it.stock) || 0
                    };
                }
            });
            renderCart();
        })
        .catch(err => alert('Failed to load pack: ' + err));
}

// 6. Filter Catalogue by Category
function filterCatalog(catId, pillEl) {
    document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('active'));
    pillEl.classList.add('active');

    const cards = document.querySelectorAll('.catalog-card');
    cards.forEach(c => {
        if (catId === 'all' || c.getAttribute('data-cat') === String(catId)) {
            c.style.display = 'flex';
        } else {
            c.style.display = 'none';
        }
    });
}

// 7. Live Filter or Barcode Scanner
const catalogSearch = document.getElementById('catalog_search');
catalogSearch.addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    const cards = document.querySelectorAll('.catalog-card');
    let exactBarcodeMatch = null;

    cards.forEach(c => {
        const name = c.getAttribute('data-name');
        const code = c.getAttribute('data-code');
        const barcode = c.getAttribute('data-barcode');

        if (barcode && barcode.toLowerCase() === q) {
            exactBarcodeMatch = c;
        }

        if (q === '' || name.includes(q) || code.includes(q) || (barcode && barcode.includes(q))) {
            c.style.display = 'flex';
        } else {
            c.style.display = 'none';
        }
    });

    // If exact barcode scanner event triggered
    if (exactBarcodeMatch && q.length >= 8) {
        exactBarcodeMatch.click();
        catalogSearch.value = '';
    }
});

// 8. Payment Mode Selector
function setPaymentMode(mode, labelEl) {
    document.querySelectorAll('.payment-btn-label').forEach(l => l.classList.remove('active'));
    labelEl.classList.add('active');
    labelEl.querySelector('input').checked = true;
}

// 9. Live Student Search via AJAX
const searchInput = document.getElementById('student_search_input');
const suggestionsBox = document.getElementById('student_suggestions');
let searchDebounceTimer = null;

searchInput.addEventListener('input', function() {
    clearTimeout(searchDebounceTimer);
    const query = this.value.trim();

    if (query.length < 1) {
        suggestionsBox.style.display = 'none';
        return;
    }

    searchDebounceTimer = setTimeout(() => {
        fetch('<?= BASE_URL ?>api.php?action=search_students&q=' + encodeURIComponent(query))
            .then(res => res.json())
            .then(data => {
                if (data && data.length > 0) {
                    let html = '';
                    data.forEach(s => {
                        html += `<div class="suggestion-item" onclick="selectStudent(${JSON.stringify(s).replace(/"/g, '&quot;')})">
                                    <div style="font-weight:700; color:#0f172a;">${s.name} <span class="badge badge-info">${s.class_section}</span></div>
                                    <div style="font-size:12px; color:#64748b;">Adm No: <strong>${s.admission_no}</strong> | Father: ${s.father_name} | Mobile: ${s.mobile || 'N/A'}</div>
                                 </div>`;
                    });
                    suggestionsBox.innerHTML = html;
                    suggestionsBox.style.display = 'block';
                } else {
                    suggestionsBox.innerHTML = '<div style="padding:12px; color:#64748b; font-size:13px;">No student matching query found.</div>';
                    suggestionsBox.style.display = 'block';
                }
            })
            .catch(err => console.error(err));
    }, 200);
});

document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
        suggestionsBox.style.display = 'none';
    }
});

function selectStudent(student) {
    document.getElementById('student_id').value = student.id;
    document.getElementById('customer_name').value = student.name;
    document.getElementById('class_section').value = student.class_section;
    document.getElementById('contact_no').value = student.mobile || '';
    searchInput.value = student.name + ' (' + student.admission_no + ')';
    suggestionsBox.style.display = 'none';
    document.getElementById('student_badge_status').innerHTML = `<span class="badge badge-success"><i class="fa fa-check"></i> ${student.admission_no}</span>`;
}

// 10. Form Validation
document.getElementById('posForm').addEventListener('submit', function(e) {
    if (Object.keys(cart).length === 0) {
        e.preventDefault();
        alert('Your cart is empty. Please add items before checking out.');
        return;
    }
    const custName = document.getElementById('customer_name').value.trim();
    if (!custName) {
        e.preventDefault();
        alert('Please enter or search for a Student / Customer name.');
        document.getElementById('customer_name').focus();
        return;
    }
});
</script>

<?php render_footer(); ?>
