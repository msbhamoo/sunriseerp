<?php
// Store Controller: Excel / CSV Bulk Import & Export
require_once __DIR__ . '/../views/layout.php';

$msg = '';
$err = '';

// 1. Export Catalog to CSV
if (isset($_GET['action']) && $_GET['action'] === 'export_csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=sunrise_store_inventory_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    // Header
    fputcsv($output, ['SKU Code', 'Item Name', 'Category', 'Unit', 'Barcode', 'Purchase Price', 'Sale Price', 'Current Stock', 'Min Alert']);

    $res = $db->query("SELECT i.item_code, i.item_name, c.name as category_name, u.short_code as unit_code,
                       i.barcode, i.purchase_price, i.sale_price, COALESCE(SUM(b.quantity), 0) as stock, i.min_stock_alert
                       FROM store_items i
                       LEFT JOIN store_categories c ON c.id = i.category_id
                       LEFT JOIN store_units u ON u.id = i.unit_id
                       LEFT JOIN store_stock_balances b ON b.item_id = i.id
                       WHERE i.is_active = 1
                       GROUP BY i.id
                       ORDER BY i.id ASC");

    while ($r = $res->fetch_assoc()) {
        fputcsv($output, [
            $r['item_code'],
            $r['item_name'],
            $r['category_name'] ?? 'General',
            $r['unit_code'] ?? 'Pcs',
            $r['barcode'] ?? '',
            $r['purchase_price'],
            $r['sale_price'],
            $r['stock'],
            $r['min_stock_alert']
        ]);
    }
    fclose($output);
    exit();
}

// 2. Download Sample Import CSV Template
if (isset($_GET['action']) && $_GET['action'] === 'sample_template') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=sample_items_import_template.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Item Code', 'Item Name', 'Category Name', 'Unit', 'Barcode', 'Purchase Price', 'Sale Price', 'Opening Stock', 'Min Stock Alert']);
    fputcsv($output, ['BOOK-ENG-G5', 'NCERT Marigold English Class 5', 'Books & Textbooks', 'Pcs', '8901234567890', '120.00', '150.00', '50', '10']);
    fputcsv($output, ['UNIF-SHIRT-30', 'Regular School Shirt Size 30', 'School Uniforms', 'Pcs', '8909876543210', '280.00', '350.00', '30', '5']);
    fputcsv($output, ['STAT-NOTEBOOK-4R', 'Four-Line English Notebook 180 Pgs', 'Stationery & Office Supplies', 'Pcs', '', '35.00', '50.00', '100', '20']);
    fclose($output);
    exit();
}

// 3. Process Bulk CSV Import
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_csv'])) {
    if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $err = "Please upload a valid CSV file.";
    } else {
        $fileName = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($fileName, "r");

        if ($handle !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Skip header row
            $imported = 0;
            $updated = 0;

            $db->begin_transaction();
            try {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (empty($data[0]) || empty($data[1])) continue;

                    $item_code = trim($data[0]);
                    $item_name = trim($data[1]);
                    $cat_name  = trim($data[2] ?? 'General');
                    $unit_name = trim($data[3] ?? 'Pcs');
                    $barcode   = trim($data[4] ?? '');
                    $purch_pr  = (float)($data[5] ?? 0);
                    $sale_pr   = (float)($data[6] ?? 0);
                    $open_stk  = (float)($data[7] ?? 0);
                    $min_alert = (int)($data[8] ?? 5);

                    // Find or create Category
                    $cat_q = $db->prepare("SELECT id FROM store_categories WHERE LOWER(name) = LOWER(?) LIMIT 1");
                    $cat_q->bind_param("s", $cat_name);
                    $cat_q->execute();
                    $cat_row = $cat_q->get_result()->fetch_assoc();
                    if ($cat_row) {
                        $cat_id = $cat_row['id'];
                    } else {
                        $cat_code = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $cat_name), 0, 6));
                        $db->query("INSERT INTO store_categories (name, code) VALUES ('" . $db->real_escape_string($cat_name) . "', '$cat_code')");
                        $cat_id = $db->insert_id;
                    }

                    // Find or create Unit
                    $u_q = $db->prepare("SELECT id FROM store_units WHERE LOWER(unit_name) = LOWER(?) OR LOWER(short_code) = LOWER(?) LIMIT 1");
                    $u_q->bind_param("ss", $unit_name, $unit_name);
                    $u_q->execute();
                    $u_row = $u_q->get_result()->fetch_assoc();
                    if ($u_row) {
                        $unit_id = $u_row['id'];
                    } else {
                        $db->query("INSERT INTO store_units (unit_name, short_code) VALUES ('" . $db->real_escape_string($unit_name) . "', '" . $db->real_escape_string($unit_name) . "')");
                        $unit_id = $db->insert_id;
                    }

                    // Insert or Update Item
                    $stmt = $db->prepare("INSERT INTO store_items 
                        (category_id, unit_id, item_name, item_code, barcode, purchase_price, sale_price, min_stock_alert, is_saleable)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
                        ON DUPLICATE KEY UPDATE 
                        category_id = VALUES(category_id), unit_id = VALUES(unit_id), item_name = VALUES(item_name),
                        barcode = VALUES(barcode), purchase_price = VALUES(purchase_price), sale_price = VALUES(sale_price),
                        min_stock_alert = VALUES(min_stock_alert)");
                    
                    $stmt->bind_param("iisssddi", $cat_id, $unit_id, $item_name, $item_code, $barcode, $purch_pr, $sale_pr, $min_alert);
                    $stmt->execute();
                    $item_id = $stmt->insert_id ?: $db->query("SELECT id FROM store_items WHERE item_code = '" . $db->real_escape_string($item_code) . "'")->fetch_assoc()['id'];

                    // Opening stock if supplied
                    if ($open_stk > 0) {
                        $db->query("INSERT INTO store_stock_balances (godown_id, item_id, quantity) VALUES (1, $item_id, $open_stk) ON DUPLICATE KEY UPDATE quantity = quantity + $open_stk");
                    }

                    $imported++;
                }

                $db->commit();
                fclose($handle);
                $msg = "Bulk import completed! {$imported} item records processed successfully.";
            } catch (Exception $e) {
                $db->rollback();
                fclose($handle);
                $err = "Import failed: " . $e->getMessage();
            }
        } else {
            $err = "Could not read the uploaded CSV file.";
        }
    }
}

render_header('Excel / CSV Bulk Import & Export', 'import_export');
?>

<?php if ($msg): ?><div class="alert alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-danger"><?= htmlspecialchars($err) ?></div><?php endif; ?>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    <!-- Bulk Import Card -->
    <div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
        <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
            <span class="card-title" style="font-size: 16px;"><i class="fa fa-file-excel" style="color: #10b981;"></i> Bulk Import Items via CSV</span>
            <a href="<?= BASE_URL ?>import_export?action=sample_template" class="btn btn-secondary" style="padding:6px 14px; font-size:12px; font-weight:700;"><i class="fa fa-download"></i> Sample CSV</a>
        </div>
        <div class="card-body" style="padding: 24px;">
            <p style="font-size:13.5px; color:#64748b; line-height:1.6; margin-bottom: 20px;">
                Upload a spreadsheet (CSV) containing your books, uniforms, and stationery to import hundreds of items at once along with categories, prices, barcodes, and initial stock.
            </p>

            <form method="POST" action="" enctype="multipart/form-data">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Choose CSV File *</label>
                    <input type="file" name="csv_file" class="form-control" accept=".csv" required style="padding:10px;">
                </div>
                <button type="submit" name="import_csv" class="btn btn-success" style="padding:12px 22px; font-size:13.5px; font-weight:700;"><i class="fa fa-cloud-arrow-up"></i> Start Bulk Import</button>
            </form>
        </div>
    </div>

    <!-- Export Card -->
    <div class="card" style="border-radius: var(--radius-xl); box-shadow: var(--shadow-subtle);">
        <div class="card-header" style="background:#fff; border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
            <span class="card-title" style="font-size: 16px;"><i class="fa fa-file-csv" style="color: var(--primary);"></i> Export Master Inventory & Stock</span>
        </div>
        <div class="card-body" style="padding: 24px;">
            <p style="font-size:13.5px; color:#64748b; line-height:1.6; margin-bottom: 20px;">
                Download your entire inventory catalog, current live stock counts, and purchase/sale pricing in CSV format for offline reporting, audits, or Excel manipulation.
            </p>

            <a href="<?= BASE_URL ?>import_export?action=export_csv" class="btn btn-primary" style="padding:12px 22px; font-size:13.5px; font-weight:700;"><i class="fa fa-file-arrow-down"></i> Download Full Inventory CSV</a>
        </div>
    </div>
</div>

<?php render_footer(); ?>
