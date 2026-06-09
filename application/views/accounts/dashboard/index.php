<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
?>

<!-- Chart.js 2.9.4 — capture before footer's older version can overwrite -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="<?php echo base_url('backend/js/Chart.min.js'); ?>"></script>
<script>window.AccountsChart = window.Chart;</script>

<?php
// Developer Sandbox: Super Admin only (role_id 1 or 7)
$__admin_role = isset($this->session->userdata('admin')['role_id'])
    ? (int)$this->session->userdata('admin')['role_id'] : 0;
$__is_super_admin = in_array($__admin_role, [1, 7]);

// Currency symbol
$curr = $this->customlib->getSchoolCurrencyFormat();

// Monthly P&L helpers
$m_income  = isset($monthly_pl['total_income'])  ? (float)$monthly_pl['total_income']  : 0;
$m_expense = isset($monthly_pl['total_expense']) ? (float)$monthly_pl['total_expense'] : 0;
$m_net     = isset($monthly_pl['net_profit'])    ? (float)$monthly_pl['net_profit']    : 0;
?>

<style>
/* ── Root & Typography ────────────────────────── */
.acc-db {
    font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif !important;
    background: #f4f7fb;
    padding-bottom: 40px;
    color: #1f2937;
}
.acc-db *, .acc-db *::before, .acc-db *::after {
    box-sizing: border-box;
}
.acc-db *:not(.fa):not(.fa-stack-1x):not(.fa-stack-2x) {
    font-family: 'Inter', 'Helvetica Neue', Arial, sans-serif !important;
}

/* ── Custom Styled Datatable Action Buttons ────── */
.btn-acc-print {
    background: #f1f5f9 !important;
    border: 1px solid #cbd5e1 !important;
    color: #475569 !important;
    padding: 3px 6px !important;
    border-radius: 4px !important;
    font-size: 11px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s !important;
    margin-right: 4px !important;
    height: 24px !important;
    width: 24px !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
    text-decoration: none !important;
}
.btn-acc-print:hover {
    background: #e2e8f0 !important;
    color: #0f172a !important;
    border-color: #94a3b8 !important;
    transform: translateY(-1px) !important;
}

/* ── Vibrant Header Banner ─────────────────────── */
.acc-header-banner {
    background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
    padding: 32px 32px 65px;
    position: relative;
    box-shadow: 0 4px 15px rgba(30, 58, 138, 0.15);
}
.acc-header-banner::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.5;
}
.acc-header-banner::after {
    content: '';
    position: absolute;
    bottom: -1px; left: 0; right: 0;
    height: 45px;
    background: #f4f7fb;
    border-radius: 45px 45px 0 0;
}
.acc-header-banner h1 {
    color: #fff !important;
    font-size: 26px !important;
    font-weight: 800 !important;
    margin: 0 0 6px !important;
    letter-spacing: -0.5px;
    position: relative;
    z-index: 1;
}
.acc-header-greeting {
    color: rgba(255,255,255,0.75);
    font-size: 14px;
    font-weight: 500;
    margin: 0;
    position: relative;
    z-index: 1;
}
.acc-header-greeting strong { color: #fff; }
.acc-header-right {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    position: relative;
    z-index: 1;
}
.acc-date-pill {
    background: rgba(255,255,255,0.1);
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 18px;
    border-radius: 99px;
    letter-spacing: 0.3px;
    backdrop-filter: blur(4px);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

/* ── Body Content ──────────────────────────────── */
.acc-body {
    padding: 0 28px;
    margin-top: -30px;
    position: relative;
    z-index: 2;
}

/* ── Metric Cards ──────────────────────────────── */
.acc-metric-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(255,255,255,0.8);
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.03), 0 8px 10px -6px rgba(0,0,0,0.01);
    display: flex;
    align-items: center;
    gap: 20px;
    height: 100%;
    transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
}
.acc-metric-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.04);
}
.acc-metric-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0;
    width: 4px; height: 100%;
}
.acc-metric-card.green::before  { background: #10b981; }
.acc-metric-card.blue::before   { background: #3b82f6; }
.acc-metric-card.amber::before  { background: #f59e0b; }
.acc-metric-card.red::before    { background: #ef4444; }

.acc-metric-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.acc-metric-card.green  .acc-metric-icon { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #fff; }
.acc-metric-card.blue   .acc-metric-icon { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: #fff; }
.acc-metric-card.amber  .acc-metric-icon { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: #fff; }
.acc-metric-card.red    .acc-metric-icon { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #fff; }

.acc-metric-info { flex: 1; min-width: 0; }
.acc-metric-label {
    font-size: 12px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    display: block;
    margin-bottom: 6px;
}
.acc-metric-value {
    font-size: 26px;
    font-weight: 800;
    color: #111827;
    line-height: 1.1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.acc-metric-link {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-decoration: none;
    margin-top: 8px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s, transform 0.2s;
}
.acc-metric-link:hover { color: #111827; text-decoration: none; transform: translateX(2px); }

/* ── Quick Actions (Vertical for Sidebar) ────────── */
.acc-qa-sidebar {
    background: #fff;
    border-radius: 16px;
    padding: 20px 24px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.03);
    margin-bottom: 24px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.acc-qa-sidebar-title {
    font-size: 14px;
    font-weight: 800;
    color: #4b5563;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.acc-qa-btn {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    border: none;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    width: 100%;
    cursor: pointer;
}
.acc-qa-btn.receipt { background: #ecfdf5; color: #065f46 !important; box-shadow: inset 0 0 0 1px #a7f3d0; }
.acc-qa-btn.receipt:hover { background: #d1fae5; transform: translateY(-2px); box-shadow: inset 0 0 0 1px #34d399, 0 4px 6px -1px rgba(16,185,129,0.1); }
.acc-qa-btn.payment { background: #fef2f2; color: #991b1b !important; box-shadow: inset 0 0 0 1px #fecaca; }
.acc-qa-btn.payment:hover { background: #fee2e2; transform: translateY(-2px); box-shadow: inset 0 0 0 1px #f87171, 0 4px 6px -1px rgba(239,68,68,0.1); }
.acc-qa-btn.contra  { background: #fffbeb; color: #92400e !important; box-shadow: inset 0 0 0 1px #fde68a; }
.acc-qa-btn.contra:hover  { background: #fef3c7; transform: translateY(-2px); box-shadow: inset 0 0 0 1px #fbbf24, 0 4px 6px -1px rgba(245,158,11,0.1); }
.acc-qa-btn.purchase{ background: #f8fafc; color: #1e293b !important; box-shadow: inset 0 0 0 1px #cbd5e1; }
.acc-qa-btn.purchase:hover{ background: #f1f5f9; transform: translateY(-2px); box-shadow: inset 0 0 0 1px #94a3b8, 0 4px 6px -1px rgba(55,65,81,0.1); }
.acc-qa-btn.journal { background: #eff6ff; color: #1e3a8a !important; box-shadow: inset 0 0 0 1px #bfdbfe; }
.acc-qa-btn.journal:hover { background: #dbeafe; transform: translateY(-2px); box-shadow: inset 0 0 0 1px #93c5fd, 0 4px 6px -1px rgba(30,58,138,0.1); }

/* ── White Panel Card ──────────────────────────── */
.acc-panel {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.03);
    overflow: hidden;
    height: 100%;
}
.acc-panel-header {
    padding: 20px 24px 18px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.acc-panel-title {
    font-size: 16px;
    font-weight: 800;
    color: #111827;
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}
.acc-panel-title i { font-size: 16px; }
.acc-panel-body { padding: 0; }
.acc-panel-body-pad { padding: 24px; }

/* ── Transaction Table ─────────────────────────── */
.acc-txn-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}
.acc-txn-table thead th {
    padding: 14px 20px;
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    background: #f9fafb;
    border-bottom: 2px solid #e5e7eb;
    white-space: nowrap;
}
.acc-txn-table tbody td {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
    vertical-align: middle;
}
.acc-txn-table tbody tr { transition: background 0.15s; }
.acc-txn-table tbody tr:last-child td { border-bottom: none; }
.acc-txn-table tbody tr:hover { background: #f8fafc; }
.acc-txn-table .v-num { font-family: 'Consolas', monospace; font-weight: 700; color: #1e3a8a; font-size: 13px; background: #eff6ff; padding: 4px 8px; border-radius: 6px; }
.acc-txn-table .v-amount { font-weight: 800; text-align: right; font-size: 14px; }
.acc-txn-table .v-amount.credit { color: #059669; }
.acc-txn-table .v-amount.debit  { color: #dc2626; }
.acc-empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
}
.acc-empty-state i { font-size: 48px; display: block; margin-bottom: 12px; color: #d1d5db; }
.acc-empty-state p { font-size: 14px; margin: 0; font-weight: 500; }

/* ── Voucher Type Badges ───────────────────────── */
.vtype-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
}
.vtype-receipt  { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
.vtype-payment  { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.vtype-contra   { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
.vtype-journal  { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
.vtype-purchase { background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; }

/* ── Monthly Summary ───────────────────────────── */
.acc-summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    border-bottom: 1px solid #f3f4f6;
}
.acc-summary-row:last-child { border-bottom: none; }
.acc-summary-label { font-size: 14px; color: #4b5563; font-weight: 600; display: flex; align-items: center; gap: 10px; }
.acc-summary-value { font-size: 16px; font-weight: 800; }
.acc-summary-value.income  { color: #059669; }
.acc-summary-value.expense { color: #dc2626; }
.acc-summary-net-box {
    margin: 16px 24px;
    border-radius: 12px;
    padding: 18px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1px solid #e2e8f0;
}
.acc-summary-net-box.surplus { background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-color: #6ee7b7; }
.acc-summary-net-box.deficit { background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-color: #fca5a5; }
.acc-summary-net-box .net-label { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
.acc-summary-net-box.surplus .net-label { color: #065f46; }
.acc-summary-net-box.deficit .net-label { color: #991b1b; }
.acc-summary-net-box .net-value { font-size: 22px; font-weight: 800; }
.acc-summary-net-box.surplus .net-value { color: #047857; }
.acc-summary-net-box.deficit .net-value { color: #b91c1c; }

/* ── Ledger Lookup ─────────────────────────────── */
.acc-lookup-box {
    padding: 20px 24px;
    border-top: 1px solid #f3f4f6;
    background: #f8fafc;
}
.acc-lookup-box label {
    font-size: 12px;
    font-weight: 800;
    color: #4b5563;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    display: block;
    margin-bottom: 10px;
}
.acc-lookup-box select {
    width: 100%;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 500;
    color: #1e293b;
    background: #fff;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.acc-lookup-box select:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.acc-lookup-result {
    margin-top: 12px;
    min-height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 8px;
    padding: 12px;
    border: 1.5px dashed #cbd5e1;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
}
.acc-lookup-result .prompt-text { font-size: 13px; color: #64748b; font-weight: 500; }
.acc-lookup-result .balance-display { text-align: center; }
.acc-lookup-result .balance-display .bal-name { font-size: 12px; font-weight: 700; color: #4b5563; display: block; text-transform: uppercase; letter-spacing: 0.5px; }
.acc-lookup-result .balance-display .bal-amount { font-size: 24px; font-weight: 800; color: #0f172a; display: block; margin: 4px 0; }
.acc-lookup-result .balance-display .bal-type { font-size: 12px; font-weight: 600; color: #64748b; display: block; }

/* ── View All Link ─────────────────────────────── */
.acc-view-all-link {
    font-size: 13px;
    font-weight: 700;
    color: #3b82f6;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s, transform 0.2s;
}
.acc-view-all-link:hover { color: #1d4ed8; text-decoration: none; transform: translateX(2px); }

/* ── Developer Sandbox ─────────────────────────── */
.acc-sandbox-bar {
    background: #1e293b;
    border-radius: 12px;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 24px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}
.acc-sandbox-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 500;
}
.acc-sandbox-label .badge-sb {
    background: rgba(56,189,248,0.2);
    color: #38bdf8;
    border: 1px solid rgba(56,189,248,0.3);
    font-size: 11px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 99px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
}
.acc-sandbox-actions { display: flex; gap: 10px; }
.btn-sb-seed, .btn-sb-reset {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}
.btn-sb-seed { background: #10b981; color: #fff !important; border: none; }
.btn-sb-seed:hover { background: #059669; transform: translateY(-1px); }
.btn-sb-reset { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.25); color: #f87171 !important; }
.btn-sb-reset:hover { background: rgba(239,68,68,0.25); color: #ef4444 !important; transform: translateY(-1px); }

/* ── Flash message ─────────────────────────────── */
.acc-flash { margin-bottom: 20px; border-radius: 10px; overflow: hidden; font-weight: 600; }

/* ── Offcanvas Quick Entry ─────────────────────── */
.acc-offcanvas-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15,23,42,0.6);
    z-index: 9998;
    visibility: hidden;
    opacity: 0;
    transition: all 0.3s ease;
    backdrop-filter: blur(4px);
}
.acc-offcanvas-overlay.show { visibility: visible; opacity: 1; }
.acc-offcanvas {
    position: fixed;
    top: 0;
    height: 100vh;
    background: #fff;
    z-index: 9999;
    box-shadow: -10px 0 30px rgba(0,0,0,0.1);
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
}
#accQuickSidebar_receipt { width: 650px; right: -650px; }
#accQuickSidebar_payment { width: 650px; right: -650px; }
#accQuickSidebar_contra { width: 650px; right: -650px; }
#accQuickSidebar_journal { width: 650px; right: -650px; }
#accQuickSidebar_purchase { width: 750px; right: -750px; }

#accQuickSidebar_receipt.show,
#accQuickSidebar_payment.show,
#accQuickSidebar_contra.show,
#accQuickSidebar_journal.show,
#accQuickSidebar_purchase.show {
    right: 0 !important;
}
.acc-offcanvas-header {
    padding: 24px 28px;
    background: linear-gradient(135deg, #1e3a8a 0%, #312e81 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.acc-offcanvas-title { font-size: 18px; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 10px; letter-spacing: 0.5px; }
.acc-offcanvas-close {
    background: rgba(255,255,255,0.15);
    border: none; color: #fff;
    width: 32px; height: 32px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    transition: background 0.2s, transform 0.2s;
}
.acc-offcanvas-close:hover { background: rgba(255,255,255,0.25); transform: rotate(90deg); }
.acc-offcanvas-body { padding: 28px; }
.acc-form-group { margin-bottom: 20px; }
.acc-form-group label {
    font-size: 13px; font-weight: 700; color: #374151;
    display: block; margin-bottom: 8px;
}
.acc-form-control {
    width: 100%;
    border: 1.5px solid #cbd5e1;
    border-radius: 8px;
    padding: 12px 14px;
    font-size: 14px; color: #1e293b;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.acc-form-control:focus { outline: none; border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
.acc-form-control[disabled] { background: #f8fafc; color: #94a3b8; }
.select2-container--open { z-index: 10005 !important; }
.select2-container .select2-selection--single { height: 46px !important; border: 1.5px solid #cbd5e1 !important; border-radius: 8px !important; display: flex; align-items: center; }
.acc-offcanvas-footer {
    padding: 24px 28px;
    border-top: 1px solid #e2e8f0;
    display: flex; gap: 14px;
    background: #f8fafc;
}
.btn-offcanvas-submit {
    flex: 1; background: #3b82f6; color: #fff;
    border: none; padding: 12px; border-radius: 8px;
    font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s;
    box-shadow: 0 4px 6px -1px rgba(59,130,246,0.2);
}
.btn-offcanvas-submit:hover { background: #2563eb; transform: translateY(-1px); box-shadow: 0 6px 8px -1px rgba(59,130,246,0.3); }
.btn-offcanvas-cancel {
    flex: 1; background: #fff; color: #4b5563;
    border: 1.5px solid #cbd5e1; padding: 12px; border-radius: 8px;
    font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.2s;
}
.btn-offcanvas-cancel:hover { background: #f1f5f9; color: #1e293b; border-color: #94a3b8; }

/* ── Chart card ────────────────────────────────── */
.acc-chart-wrap { padding: 24px; }
.acc-chart-legend {
    display: flex;
    gap: 24px;
    font-size: 13px;
    font-weight: 700;
    color: #4b5563;
}
.acc-chart-legend span { display: flex; align-items: center; gap: 8px; }
.legend-dot { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }

/* ── Responsive ────────────────────────────────── */
@media (max-width: 767px) {
    .acc-header-banner { padding: 24px 20px 50px; }
    .acc-body { padding: 0 16px; margin-top: -20px; }
    .acc-metric-card { flex-direction: column; align-items: flex-start; gap: 12px; padding: 20px; }
    .acc-qa-wrap { flex-direction: column; gap: 12px; }
    .acc-qa-btn { width: 100%; }
    .acc-global-search-container { width: 100% !important; margin-bottom: 8px; }
}

/* ── Global Autocomplete Search ───────────────── */
.acc-global-search-container {
    position: relative;
    width: 320px;
    z-index: 999;
}
.acc-global-search-container .input-group {
    margin: 0;
}
.acc-global-search-container input::placeholder {
    color: rgba(255,255,255,0.6) !important;
}
.acc-global-search-container input:focus {
    background: rgba(255, 255, 255, 0.2) !important;
    border-color: rgba(255,255,255,0.5) !important;
    outline: none;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.1) !important;
}
.acc-search-dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    margin-top: 8px;
    max-height: 300px;
    overflow-y: auto;
    display: none;
    z-index: 10000;
    border: 1px solid #e2e8f0;
    padding: 6px 0;
}
.acc-search-dropdown-menu::-webkit-scrollbar {
    width: 6px;
}
.acc-search-dropdown-menu::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}
.acc-search-item {
    padding: 10px 16px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.2s;
    text-decoration: none !important;
}
.acc-search-item:last-child {
    border-bottom: none;
}
.acc-search-item:hover {
    background: #f8fafc;
}
.acc-search-item-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    text-align: left;
}
.acc-search-item-no {
    font-size: 12px;
    font-weight: 700;
    color: #1e3a8a;
}
.acc-search-item-meta {
    font-size: 11px;
    color: #64748b;
}
.acc-search-item-amount {
    font-family: 'JetBrains Mono', monospace;
    font-size: 12px;
    font-weight: 700;
    color: #0f172a;
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}
.acc-search-item-badge {
    font-size: 9px;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 8px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.badge-receipt { background: #d1fae5; color: #065f46; }
.badge-payment { background: #fee2e2; color: #991b1b; }
.badge-contra  { background: #e0f2fe; color: #0369a1; }
.badge-journal { background: #fef3c7; color: #92400e; }
.badge-purchase { background: #f3e8ff; color: #6b21a8; }
</style>

<div class="content-wrapper">
    <section class="content pb0">

        <!-- Flash messages -->
        <?php if ($this->session->flashdata('msg')): ?>
            <div class="dashalert alert alert-success alert-dismissible" role="alert">
                <button type="button" class="alertclose close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <?php echo $this->session->flashdata('msg'); ?>
            </div>
        <?php endif; ?>

        <!-- Developer Sandbox (Super Admin only) -->
        <?php if ($__is_super_admin): ?>
        <div class="box borderwhite shadow" style="background:#fff3cd; border-color:#ffeeba;">
            <div class="box-body">
                <strong style="color:#856404;"><i class="fa fa-terminal"></i> Dev Sandbox</strong>
                <span style="color:#856404; margin-left:10px;">Seed realistic mock transactions to populate charts &amp; metrics</span>
                <div class="pull-right">
                    <a href="<?php echo site_url('accounts/dashboard/generate_demo_data'); ?>" class="btn btn-success btn-xs" onclick="return confirm('Load 22 realistic transactions over 6 months?');"><i class="fa fa-database"></i> Seed Data</a>
                    <a href="<?php echo site_url('accounts/dashboard/reset_demo_data'); ?>" class="btn btn-danger btn-xs" onclick="return confirm('Delete all demo data and reset opening balances?');"><i class="fa fa-trash-o"></i> Clear</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ROW 1: 4 METRIC CARDS -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="box borderwhite shadow">
                    <div class="box-body direct-top-equal-scroll-22" style="padding: 20px;">
                        <h4 style="margin-top:0; color:#6b7280; font-size:14px; text-transform:uppercase;"><i class="fa fa-money text-success"></i> Cash Balance</h4>
                        <h3 style="margin-bottom:0; font-weight:700;"><?php echo $curr . number_format($cash_balance, 2); ?></h3>
                        <a href="<?php echo site_url('accounts/reports/cashbook'); ?>" style="font-size:12px; margin-top:10px; display:inline-block;">View Cash Book &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="box borderwhite shadow">
                    <div class="box-body direct-top-equal-scroll-22" style="padding: 20px;">
                        <h4 style="margin-top:0; color:#6b7280; font-size:14px; text-transform:uppercase;"><i class="fa fa-university text-info"></i> Bank Balance</h4>
                        <h3 style="margin-bottom:0; font-weight:700;"><?php echo $curr . number_format($bank_balance, 2); ?></h3>
                        <a href="<?php echo site_url('accounts/reports/bankbook'); ?>" style="font-size:12px; margin-top:10px; display:inline-block;">View Bank Book &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="box borderwhite shadow">
                    <div class="box-body direct-top-equal-scroll-22" style="padding: 20px;">
                        <h4 style="margin-top:0; color:#6b7280; font-size:14px; text-transform:uppercase;"><i class="fa fa-arrow-circle-down text-warning"></i> Receivables</h4>
                        <h3 style="margin-bottom:0; font-weight:700;"><?php echo $curr . number_format($receivables, 2); ?></h3>
                        <a href="<?php echo site_url('accounts/reports/outstanding'); ?>" style="font-size:12px; margin-top:10px; display:inline-block;">View Outstanding &rarr;</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="box borderwhite shadow">
                    <div class="box-body direct-top-equal-scroll-22" style="padding: 20px;">
                        <h4 style="margin-top:0; color:#6b7280; font-size:14px; text-transform:uppercase;"><i class="fa fa-arrow-circle-up text-danger"></i> Payables</h4>
                        <h3 style="margin-bottom:0; font-weight:700;"><?php echo $curr . number_format($payables, 2); ?></h3>
                        <a href="<?php echo site_url('accounts/reports/outstanding'); ?>" style="font-size:12px; margin-top:10px; display:inline-block;">View Outstanding &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <!-- Recent Transactions -->
                <div class="box borderwhite shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list-ul text-primary"></i> Recent Transactions</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('accounts/reports/daybook'); ?>" class="btn btn-default btn-xs">View Day Book &rarr;</a>
                        </div>
                    </div>
                    <div class="box-body pb0" style="padding-bottom: 10px;">
                        <?php if (!empty($recent_vouchers)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Voucher No</th>
                                        <th>Type</th>
                                        <th>Narration</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_vouchers as $v):
                                        $vtype  = $v['voucher_type'];
                                        $isCredit = in_array($vtype, ['receipt']);
                                        $amt   = number_format((float)$v['total_amount'], 2);
                                        $narr  = isset($v['narration']) && $v['narration']
                                               ? mb_substr($v['narration'], 0, 45) . (mb_strlen($v['narration']) > 45 ? '…' : '')
                                               : '—';
                                    ?>
                                    <tr>
                                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($v['voucher_date'])); ?></td>
                                        <td><a href="<?php echo site_url('accounts/' . $vtype . 'voucher/edit/' . $v['id']); ?>"><?php echo $v['voucher_no']; ?></a></td>
                                        <td>
                                            <?php if($vtype == 'receipt') { ?>
                                                <span class="label label-success">Receipt</span>
                                            <?php } elseif($vtype == 'payment') { ?>
                                                <span class="label label-danger">Payment</span>
                                            <?php } elseif($vtype == 'contra') { ?>
                                                <span class="label label-info">Contra</span>
                                            <?php } elseif($vtype == 'journal') { ?>
                                                <span class="label label-warning">Journal</span>
                                            <?php } else { ?>
                                                <span class="label label-default"><?php echo ucfirst($vtype); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $narr; ?></td>
                                        <td class="text-right <?php echo $isCredit ? 'text-success' : 'text-danger'; ?>">
                                            <strong><?php echo $curr . $amt; ?></strong>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                        <div style="text-align: center; padding: 20px;">
                            <img src="https://smart-school.in/ssappresource/images/addnewitem.svg" width="150" class="center-block mt20">
                            <p class="text-muted mt10">No vouchers recorded yet.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Trend Chart -->
                <div class="box borderwhite shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-line-chart text-primary"></i> Revenue &amp; Expense — 6-Month Trend</h3>
                    </div>
                    <div class="box-body" style="padding: 20px;">
                        <canvas id="accTrendChart" height="90"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-12">
                <!-- Global Search -->
                <div class="box borderwhite shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search text-primary"></i> Voucher Search</h3>
                    </div>
                    <div class="box-body">
                        <div class="acc-global-search-container" style="width: 100%;">
                            <div class="input-group">
                                <input type="text" id="accGlobalSearch" class="form-control" placeholder="Search Voucher (No., Ledger, Amt)..." autocomplete="off" />
                                <span class="input-group-addon"><i class="fa fa-search"></i></span>
                            </div>
                            <div id="accSearchDropdown" class="acc-search-dropdown-menu" style="top:auto;"></div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="box borderwhite shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bolt text-warning"></i> Quick Actions</h3>
                    </div>
                    <div class="box-body pb0">
                        <ul class="user-progress ps mb0" style="padding-left: 0; list-style: none;">
                            <li style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-success btn-block text-left btn-open-quick" data-type="receipt"><i class="fa fa-plus-circle"></i> Collect Receipt</button>
                            </li>
                            <li style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-danger btn-block text-left btn-open-quick" data-type="payment"><i class="fa fa-minus-circle"></i> Log Payment</button>
                            </li>
                            <li style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-info btn-block text-left btn-open-quick" data-type="contra"><i class="fa fa-exchange"></i> Contra Transfer</button>
                            </li>
                            <li style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-default btn-block text-left btn-open-quick" data-type="purchase"><i class="fa fa-shopping-cart"></i> Purchase Entry</button>
                            </li>
                            <li style="margin-bottom: 10px;">
                                <button type="button" class="btn btn-warning btn-block text-left btn-open-quick" data-type="journal"><i class="fa fa-book"></i> Journal Entry</button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Monthly Summary -->
                <div class="box borderwhite shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar-check-o text-primary"></i> <?php echo date('F'); ?> Summary</h3>
                    </div>
                    <div class="box-body">
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b><i class="fa fa-arrow-down text-success"></i> Total Income</b> <a class="pull-right text-success"><?php echo $curr . number_format($m_income, 2); ?></a>
                            </li>
                            <li class="list-group-item">
                                <b><i class="fa fa-arrow-up text-danger"></i> Total Expenses</b> <a class="pull-right text-danger"><?php echo $curr . number_format($m_expense, 2); ?></a>
                            </li>
                            <li class="list-group-item" style="background: <?php echo $m_net >= 0 ? '#dff0d8' : '#f2dede'; ?>; padding: 10px; margin-top: 10px;">
                                <b><?php echo $m_net >= 0 ? '▲ Net Surplus' : '▼ Net Deficit'; ?></b> 
                                <a class="pull-right" style="color: <?php echo $m_net >= 0 ? '#3c763d' : '#a94442'; ?>; font-weight:bold; font-size:16px;"><?php echo $curr . number_format(abs($m_net), 2); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Ledger Lookup -->
                <div class="box borderwhite shadow">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search text-primary"></i> Quick Balance Lookup</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <select id="acc-ledger-select" class="form-control">
                                <option value="">— Select a ledger account —</option>
                                <?php foreach ($all_ledgers as $lg): ?>
                                    <option value="<?php echo $lg['id']; ?>"><?php echo htmlspecialchars($lg['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="acc-lookup-result well well-sm text-center" id="acc-lookup-result" style="margin-bottom:0;">
                            <span class="text-muted"><i class="fa fa-info-circle"></i> Select a ledger above</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ═══ SHARED OFFCANVAS OVERLAY ═══════════════════════════ -->
<div class="acc-offcanvas-overlay" id="accQuickOverlay"></div>

<!-- ═══ OFFCANVAS SIDEBAR: COLLECT RECEIPT ══════════════════ -->
<div class="acc-offcanvas" id="accQuickSidebar_receipt">
    <div class="acc-offcanvas-header">
        <h3 class="acc-offcanvas-title"><i class="fa fa-plus-circle"></i> Collect Receipt</h3>
        <button type="button" class="acc-offcanvas-close" onclick="closeAllSidebars()"><i class="fa fa-times"></i></button>
    </div>
    <form action="<?php echo site_url('accounts/receiptvoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data" style="margin: 0;">
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="acc-offcanvas-body" style="padding: 20px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher No</label><small class="req"> *</small>
                        <input name="voucher_no" type="text" class="form-control" value="<?php echo htmlspecialchars($next_receipt_no); ?>" readonly/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher Date</label><small class="req"> *</small>
                        <input name="voucher_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cash/Bank (Dr)</label><small class="req"> *</small>
                        <div class="input-group">
                            <select name="dr_ledger_id" id="receipt_dr_ledger_id" class="form-control select2-namespaced" style="width: 100%;" required>
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($cash_bank_ledgers as $ledger) { ?>
                                    <option value="<?php echo $ledger['id'] ?>"><?php echo htmlspecialchars($ledger['name']) ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="receipt_showAddPaymentModeModal()">
                                <i class="fa fa-plus text-primary"></i>
                            </div>
                        </div>
                        <div id="receipt_dr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:4px;"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" id="receipt_payment_method" class="form-control" style="width: 100%;">
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="DD">DD</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Net Banking">Net Banking</option>
                            <option value="UPI">UPI</option>
                            <option value="Card">Card</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="receipt_payment_details_section" style="display:none; padding:10px; background:#f9fafb; border: 1px solid #f3f4f6; border-radius:6px; margin-bottom:15px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="receipt_lbl_ref_no">Reference No</label>
                        <input name="reference_no" id="receipt_reference_no" type="text" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="receipt_lbl_payment_date">Payment Date</label>
                        <input name="payment_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
                <div class="col-md-12 receipt_bank_field_group" style="display:none;">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <div class="input-group">
                            <select name="bank_name" id="receipt_bank_name" class="form-control" style="width: 100%;">
                                <option value="">Select Bank</option>
                                <?php foreach($banks as $bank) { ?>
                                    <option value="<?php echo htmlspecialchars($bank['name']); ?>"><?php echo htmlspecialchars($bank['name']); ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddBankModal()">
                                <i class="fa fa-plus text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="margin-top: 10px; margin-bottom: 15px;">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped" id="receipt_item_table" style="font-size: 13px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th>Ledger Account (Cr)</th>
                                <th>Category/Head</th>
                                <th width="120">Amount</th>
                                <th width="40"><button type="button" class="btn btn-sm btn-primary" onclick="receipt_add_row()"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach($ledgers as $l) { ?>
                                                <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['name']); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="receipt_showAddLedgerModal($(this).closest('.input-group').find('select'))">
                                            <i class="fa fa-plus text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="cr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                </td>
                                <td>
                                    <select name="expense_type_id[]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($expense_types as $et) { ?>
                                            <option value="<?php echo $et['id']; ?>"><?php echo htmlspecialchars($et['name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="amount[]" class="form-control receipt_amount" required></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="receipt_remove_row(this)"><i class="fa fa-remove"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total</th>
                                <th><input type="text" id="receipt_total_amount" class="form-control" value="0.00" readonly></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Narration</label>
                        <textarea name="narration" class="form-control" rows="2" placeholder="Receipt narration..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                        <input type="file" name="attachment" class="form-control filestyle">
                    </div>
                </div>
            </div>
        </div>
        <div class="acc-offcanvas-footer">
            <button type="button" class="btn btn-default" onclick="closeAllSidebars()">Cancel</button>
            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Receipt</button>
        </div>
    </form>
</div>

<!-- ═══ OFFCANVAS SIDEBAR: LOG PAYMENT ══════════════════════ -->
<div class="acc-offcanvas" id="accQuickSidebar_payment">
    <div class="acc-offcanvas-header">
        <h3 class="acc-offcanvas-title"><i class="fa fa-minus-circle"></i> Log Payment</h3>
        <button type="button" class="acc-offcanvas-close" onclick="closeAllSidebars()"><i class="fa fa-times"></i></button>
    </div>
    <form action="<?php echo site_url('accounts/paymentvoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data" style="margin: 0;">
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="acc-offcanvas-body" style="padding: 20px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher No</label><small class="req"> *</small>
                        <input name="voucher_no" type="text" class="form-control" value="<?php echo htmlspecialchars($next_payment_no); ?>" readonly/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher Date</label><small class="req"> *</small>
                        <input name="voucher_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cash/Bank (Cr)</label><small class="req"> *</small>
                        <div class="input-group">
                            <select name="cr_ledger_id" id="payment_cr_ledger_id" class="form-control select2-namespaced" style="width: 100%;" required>
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($cash_bank_ledgers as $ledger) { ?>
                                    <option value="<?php echo $ledger['id'] ?>"><?php echo htmlspecialchars($ledger['name']) ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="payment_showAddPaymentModeModal()">
                                <i class="fa fa-plus text-primary"></i>
                            </div>
                        </div>
                        <div id="payment_cr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:4px;"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Payment Method</label>
                        <select name="payment_method" id="payment_payment_method" class="form-control" style="width: 100%;">
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="DD">DD</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Net Banking">Net Banking</option>
                            <option value="UPI">UPI</option>
                            <option value="Card">Card</option>
                            <option value="Online">Online</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" id="payment_payment_details_section" style="display:none; padding:10px; background:#f9fafb; border: 1px solid #f3f4f6; border-radius:6px; margin-bottom:15px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="payment_lbl_ref_no">Reference No</label>
                        <input name="reference_no" id="payment_reference_no" type="text" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label id="payment_lbl_payment_date">Payment Date</label>
                        <input name="payment_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
                <div class="col-md-12 payment_bank_field_group" style="display:none;">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <div class="input-group">
                            <select name="bank_name" id="payment_bank_name" class="form-control" style="width: 100%;">
                                <option value="">Select Bank</option>
                                <?php foreach($banks as $bank) { ?>
                                    <option value="<?php echo htmlspecialchars($bank['name']); ?>"><?php echo htmlspecialchars($bank['name']); ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddBankModal()">
                                <i class="fa fa-plus text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="margin-top: 10px; margin-bottom: 15px;">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped" id="payment_item_table" style="font-size: 13px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th>Ledger Account (Dr)</th>
                                <th>Category/Head</th>
                                <th width="120">Amount</th>
                                <th width="40"><button type="button" class="btn btn-sm btn-primary" onclick="payment_add_row()"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="input-group">
                                        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach($ledgers as $l) { ?>
                                                <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['name']); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="payment_showAddLedgerModal($(this).closest('.input-group').find('select'))">
                                            <i class="fa fa-plus text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="dr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                </td>
                                <td>
                                    <select name="expense_type_id[]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($expense_types as $et) { ?>
                                            <option value="<?php echo $et['id']; ?>"><?php echo htmlspecialchars($et['name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="amount[]" class="form-control payment_amount" required></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="payment_remove_row(this)"><i class="fa fa-remove"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total</th>
                                <th><input type="text" id="payment_total_amount" class="form-control" value="0.00" readonly></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Narration</label>
                        <textarea name="narration" class="form-control" rows="2" placeholder="Payment narration..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                        <input type="file" name="attachment" class="form-control filestyle">
                    </div>
                </div>
            </div>
        </div>
        <div class="acc-offcanvas-footer">
            <button type="button" class="btn btn-default" onclick="closeAllSidebars()">Cancel</button>
            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Payment</button>
        </div>
    </form>
</div>

<!-- ═══ OFFCANVAS SIDEBAR: CONTRA TRANSFER ══════════════════ -->
<div class="acc-offcanvas" id="accQuickSidebar_contra">
    <div class="acc-offcanvas-header">
        <h3 class="acc-offcanvas-title"><i class="fa fa-exchange"></i> Contra Transfer</h3>
        <button type="button" class="acc-offcanvas-close" onclick="closeAllSidebars()"><i class="fa fa-times"></i></button>
    </div>
    <form action="<?php echo site_url('accounts/contravoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data" style="margin: 0;">
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="acc-offcanvas-body" style="padding: 20px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher No</label><small class="req"> *</small>
                        <input name="voucher_no" type="text" class="form-control" value="<?php echo htmlspecialchars($next_contra_no); ?>" readonly/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher Date</label><small class="req"> *</small>
                        <input name="voucher_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Ref No.</label>
                        <input name="reference_no" type="text" class="form-control" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Transaction Type</label><small class="req"> *</small>
                        <select name="transaction_type" id="contra_transaction_type" class="form-control" required style="width: 100%;">
                            <option value="Cash To Bank">Cash To Bank</option>
                            <option value="Bank To Cash">Bank To Cash</option>
                            <option value="Bank To Bank">Bank To Bank</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr style="margin-top:5px; margin-bottom:15px;">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label id="contra_lbl_cr_account">Withdraw From (Credit)</label><small class="req"> *</small>
                        <select name="cr_ledger_id" id="contra_cr_ledger_id" class="form-control select2-namespaced" required style="width: 100%;">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach($cash_bank_ledgers as $l) { ?>
                                <option value="<?php echo $l['id']; ?>" data-group="<?php echo $l['group_id']; ?>" data-system-group="<?php echo $l['system_name']; ?>"><?php echo htmlspecialchars($l['name']); ?></option>
                            <?php } ?>
                        </select>
                        <div class="contra_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label id="contra_lbl_dr_account">Deposit Into (Debit)</label><small class="req"> *</small>
                        <select name="dr_ledger_id" id="contra_dr_ledger_id" class="form-control select2-namespaced" required style="width: 100%;">
                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                            <?php foreach($cash_bank_ledgers as $l) { ?>
                                <option value="<?php echo $l['id']; ?>" data-group="<?php echo $l['group_id']; ?>" data-system-group="<?php echo $l['system_name']; ?>"><?php echo htmlspecialchars($l['name']); ?></option>
                            <?php } ?>
                        </select>
                        <div class="contra_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Amount</label><small class="req"> *</small>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea name="narration" class="form-control" rows="2" placeholder="Contra transfer remark..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                        <input type="file" name="attachment" class="form-control filestyle">
                    </div>
                </div>
            </div>
        </div>
        <div class="acc-offcanvas-footer">
            <button type="button" class="btn btn-default" onclick="closeAllSidebars()">Cancel</button>
            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Contra</button>
        </div>
    </form>
</div>

<!-- ═══ OFFCANVAS SIDEBAR: PURCHASE ENTRY ══════════════════ -->
<div class="acc-offcanvas" id="accQuickSidebar_purchase">
    <div class="acc-offcanvas-header">
        <h3 class="acc-offcanvas-title"><i class="fa fa-shopping-cart"></i> Purchase Entry</h3>
        <button type="button" class="acc-offcanvas-close" onclick="closeAllSidebars()"><i class="fa fa-times"></i></button>
    </div>
    <form action="<?php echo site_url('accounts/purchaseentry/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data" style="margin: 0;">
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="acc-offcanvas-body" style="padding: 20px;">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Supplier</label><small class="req"> *</small>
                        <div class="input-group">
                            <select name="supplier_ledger_id" id="purchase_supplier_ledger_id" class="form-control select2-namespaced" style="width: 100%;" required>
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($suppliers as $supplier) { ?>
                                    <option value="<?php echo $supplier['id'] ?>"><?php echo htmlspecialchars($supplier['name']) ?></option>
                                <?php } ?>
                            </select>
                            <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="showAddSupplierModal()">
                                <i class="fa fa-plus text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Voucher Date</label><small class="req"> *</small>
                        <input name="purchase_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Invoice No</label>
                        <input name="invoice_no" type="text" class="form-control" value="<?php echo htmlspecialchars($next_purchase_no ?? ''); ?>" readonly />
                    </div>
                </div>
            </div>
            <hr style="margin-top: 5px; margin-bottom: 15px;">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped" id="purchase_item_table" style="font-size: 13px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th>Item Description</th>
                                <th>Expense Type</th>
                                <th width="80">Qty</th>
                                <th width="100">Rate</th>
                                <th width="120">Amount</th>
                                <th width="40"><button type="button" class="btn btn-sm btn-primary" onclick="purchase_add_row()"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="item_description[]" class="form-control" required></td>
                                <td>
                                    <select name="expense_type_id[]" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach($expense_types as $et) { ?>
                                            <option value="<?php echo $et['id']; ?>"><?php echo htmlspecialchars($et['name']); ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                                <td><input type="number" step="0.01" name="qty[]" class="form-control purchase_qty" value="1.00" required></td>
                                <td><input type="number" step="0.01" name="rate[]" class="form-control purchase_rate" required></td>
                                <td><input type="number" step="0.01" name="amount[]" class="form-control purchase_amount" readonly></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="purchase_remove_row(this)"><i class="fa fa-remove"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-right">Total</th>
                                <th><input type="number" step="0.01" name="total_amount" id="purchase_total_amount" class="form-control" value="0.00" readonly></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">Discount</th>
                                <th><input type="number" step="0.01" name="discount" id="purchase_discount" class="form-control purchase_calc_net" value="0.00"></th>
                                <th></th>
                            </tr>
                            <tr>
                                <th colspan="4" class="text-right">GST Amount</th>
                                <th><input type="number" step="0.01" name="gst_amount" id="purchase_gst_amount" class="form-control purchase_calc_net" value="0.00"></th>
                                <th></th>
                            </tr>
                            <tr style="background: #f0fdf4;">
                                <th colspan="4" class="text-right" style="font-weight: 700; color: #166534;">Net Amount</th>
                                <th><input type="number" step="0.01" name="net_amount" id="purchase_net_amount" class="form-control" value="0.00" readonly style="font-weight: 700; color: #166534; background: #fff;"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Narration</label>
                        <textarea name="narration" class="form-control" rows="2" placeholder="Purchase entry narration..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                        <input type="file" name="attachment" class="form-control filestyle">
                    </div>
                </div>
            </div>
        </div>
        <div class="acc-offcanvas-footer">
            <button type="button" class="btn btn-default" onclick="closeAllSidebars()">Cancel</button>
            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Purchase</button>
        </div>
    </form>
</div>

<!-- ═══ OFFCANVAS SIDEBAR: JOURNAL VOUCHER ══════════════════ -->
<div class="acc-offcanvas" id="accQuickSidebar_journal">
    <div class="acc-offcanvas-header">
        <h3 class="acc-offcanvas-title"><i class="fa fa-book"></i> Journal Entry</h3>
        <button type="button" class="acc-offcanvas-close" onclick="closeAllSidebars()"><i class="fa fa-times"></i></button>
    </div>
    <form action="<?php echo site_url('accounts/journalvoucher/index') ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data" id="journal_form_submit" style="margin: 0;">
        <?php echo $this->customlib->getCSRF(); ?>
        <div class="acc-offcanvas-body" style="padding: 20px;">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher No</label><small class="req"> *</small>
                        <input name="voucher_no" type="text" class="form-control" value="<?php echo htmlspecialchars($next_journal_no); ?>" readonly/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Voucher Date</label><small class="req"> *</small>
                        <input name="voucher_date" type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                    </div>
                </div>
            </div>
            <hr style="margin-top: 5px; margin-bottom: 15px;">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped" id="journal_item_table" style="font-size: 13px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th width="100">Type (Dr/Cr)</th>
                                <th>Account</th>
                                <th width="150">Amount</th>
                                <th width="40"><button type="button" class="btn btn-sm btn-primary" onclick="journal_add_row()"><i class="fa fa-plus"></i></button></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="type[]" class="form-control journal_type_select" style="width:100%;">
                                        <option value="Dr">Dr</option>
                                        <option value="Cr">Cr</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <select name="ledger_id[]" class="form-control select2-namespaced" required style="width: 100%;">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach($ledgers as $l) { ?>
                                                <option value="<?php echo $l['id']; ?>"><?php echo htmlspecialchars($l['name']); ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="journal_showAddLedgerModal($(this).closest('.input-group').find('select'))">
                                            <i class="fa fa-plus text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="journal_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>
                                </td>
                                <td><input type="number" step="0.01" name="amount[]" class="form-control journal_amount" required></td>
                                <td><button type="button" class="btn btn-sm btn-danger" onclick="journal_remove_row(this)"><i class="fa fa-remove"></i></button></td>
                            </tr>
                        </tbody>
                        <tfoot class="journal-totals">
                            <tr style="background:#fef2f2;">
                                <th colspan="2" class="text-right" style="color:#991b1b;">Total Debit</th>
                                <th><input type="text" id="journal_total_dr" class="form-control" value="0.00" readonly style="color:#991b1b; font-weight:700;"></th>
                                <th></th>
                            </tr>
                            <tr style="background:#f0fdf4;">
                                <th colspan="2" class="text-right" style="color:#065f46;">Total Credit</th>
                                <th><input type="text" id="journal_total_cr" class="form-control" value="0.00" readonly style="color:#065f46; font-weight:700;"></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Narration</label>
                        <textarea name="narration" class="form-control" rows="2" placeholder="Journal narration..."></textarea>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('attach_document'); ?></label>
                        <input type="file" name="attachment" class="form-control filestyle">
                    </div>
                </div>
            </div>
        </div>
        <div class="acc-offcanvas-footer">
            <button type="button" class="btn btn-default" onclick="closeAllSidebars()">Cancel</button>
            <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Save Journal</button>
        </div>
    </form>
</div>


<!-- ═══ SHARED HELPERS MODALS ════════════════════════════════ -->

<!-- Add Payment Mode Modal (Shared) -->
<div class="modal fade" id="addPaymentModeModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModeModalLabel" style="z-index: 10010;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addPaymentModeModalLabel">Add Account Mode (Cash/Bank)</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Account Name</label><small class="req"> *</small>
                    <input type="text" id="new_pm_name" class="form-control" placeholder="e.g. Petty Cash, Axis Bank">
                </div>
                <div class="form-group">
                    <label>Group</label><small class="req"> *</small>
                    <select id="pm_group_id" class="form-control">
                        <option value="">Select Group</option>
                        <?php foreach($ledger_groups as $group) { 
                            if ($group['id'] == 1 || $group['id'] == 2) { ?>
                                <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                            <?php } 
                        } ?>
                    </select>
                </div>
                <div id="pm_bank_details_section" style="display:none;">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <select id="pm_bank_id" class="form-control">
                            <option value="">Select Bank</option>
                            <?php foreach($banks as $bank) { ?>
                                <option value="<?php echo $bank['id']; ?>"><?php echo htmlspecialchars($bank['name']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Account No</label>
                        <input type="text" id="new_pm_account_no" class="form-control" placeholder="Enter Account Number">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewPaymentMode()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add General Ledger Modal (Shared) -->
<div class="modal fade" id="addLedgerModal" tabindex="-1" role="dialog" aria-labelledby="addLedgerModalLabel" style="z-index: 10010;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addLedgerModalLabel">Add General Ledger Account</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Ledger Account Name</label><small class="req"> *</small>
                    <input type="text" id="new_ledger_name" class="form-control" placeholder="e.g. Tuition Fee Income, Sale of Uniforms">
                </div>
                <div class="form-group">
                    <label>Ledger Group</label><small class="req"> *</small>
                    <select id="ledger_group_id" class="form-control">
                        <option value="">Select Group</option>
                        <?php foreach($ledger_groups as $group) { ?>
                            <option value="<?php echo $group['id']; ?>"><?php echo htmlspecialchars($group['name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewLedger()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Bank Modal (Shared) -->
<div class="modal fade" id="addBankModal" tabindex="-1" role="dialog" aria-labelledby="addBankModalLabel" style="z-index: 10010;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addBankModalLabel">Add Bank</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Bank Name</label><small class="req"> *</small>
                    <input type="text" id="new_bank_name" class="form-control" placeholder="e.g. State Bank of India">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewBank()">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Supplier Modal (Shared) -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel" style="z-index: 10010;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addSupplierModalLabel">Add New Supplier</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Supplier Name</label><small class="req"> *</small>
                    <input type="text" id="new_supplier_name" class="form-control" placeholder="Enter supplier/company name">
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <input type="text" id="new_supplier_mobile" class="form-control" placeholder="Enter contact number">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitNewSupplier()">Save</button>
            </div>
        </div>
    </div>
</div>



<!-- Helper form for outstanding redirects -->
<form id="outstanding-redirect-form" method="post" action="<?php echo site_url('accounts/reports/outstanding'); ?>" style="display:none;">
    <input type="hidden" name="type" id="outstanding-form-type" value="receivable">
    <input type="hidden" name="search" value="search">
</form>

<script>
(function() {
    console.log("Accounts Dashboard JS initializing...");

    // ── Trend Chart ─────────────────────────────────────────────
    try {
        var trendData = <?php echo isset($months_trend) ? json_encode($months_trend) : '[]'; ?>;
        if (!trendData) trendData = [];
        
        var trendLabels = Array.isArray(trendData) ? trendData.map(function(d) { return d.label; }) : [];
        var incomeVals  = Array.isArray(trendData) ? trendData.map(function(d) { return parseFloat(d.income); }) : [];
        var expenseVals = Array.isArray(trendData) ? trendData.map(function(d) { return parseFloat(d.expense); }) : [];

        var ctxTrend = document.getElementById('accTrendChart');
        if (ctxTrend && typeof AccountsChart !== 'undefined') {
            var ig = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 280);
            ig.addColorStop(0, 'rgba(59,130,246,0.12)');
            ig.addColorStop(1, 'rgba(59,130,246,0)');
            var eg = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 280);
            eg.addColorStop(0, 'rgba(239,68,68,0.10)');
            eg.addColorStop(1, 'rgba(239,68,68,0)');

            new AccountsChart(ctxTrend.getContext('2d'), {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: incomeVals,
                            borderColor: '#3b82f6',
                            backgroundColor: ig,
                            borderWidth: 2.5,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            lineTension: 0.45,
                            fill: true
                        },
                        {
                            label: 'Expenses',
                            data: expenseVals,
                            borderColor: '#ef4444',
                            backgroundColor: eg,
                            borderWidth: 3,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#ef4444',
                            pointBorderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 6,
                            lineTension: 0.45,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: '#1e293b',
                        titleFontFamily: "'Inter', sans-serif",
                        bodyFontFamily: "'Inter', sans-serif",
                        titleFontSize: 12,
                        bodyFontSize: 12,
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ti, data) {
                                var ds  = data.datasets[ti.datasetIndex];
                                var val = parseFloat(ti.yLabel).toLocaleString('en-US', { minimumFractionDigits: 2 });
                                return ' ' + ds.label + ': <?php echo addslashes($curr); ?>' + val;
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: { display: false },
                            ticks: {
                                fontSize: 11,
                                fontFamily: "'Inter', sans-serif",
                                fontColor: '#9ca3af'
                            }
                        }],
                        yAxes: [{
                            gridLines: { color: '#f3f4f6', drawBorder: false },
                            ticks: {
                                fontSize: 11,
                                fontFamily: "'Inter', sans-serif",
                                fontColor: '#9ca3af',
                                callback: function(v) {
                                    return v >= 1000 ? '<?php echo addslashes($curr); ?>' + (v/1000).toFixed(0) + 'k' : '<?php echo addslashes($curr); ?>' + v;
                                },
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
            console.log("Trend Chart initialized successfully.");
        } else {
            console.warn("Trend Chart element or AccountsChart class missing.");
        }
    } catch(e) {
        console.error("Trend Chart initialization failed:", e);
    }

    // ── Ledger Balance Lookup AJAX ─────────────────────────────
    try {
        var ledgerSelect = document.getElementById('acc-ledger-select');
        var lookupResult = document.getElementById('acc-lookup-result');

        if (ledgerSelect) {
            ledgerSelect.addEventListener('change', function() {
                var id = this.value;
                if (!id) {
                    lookupResult.innerHTML = '<span class="prompt-text"><i class="fa fa-info-circle"></i> Select a ledger above</span>';
                    return;
                }
                lookupResult.innerHTML = '<span class="prompt-text"><i class="fa fa-spinner fa-spin"></i> Loading…</span>';

                if (window.jQuery) {
                    window.jQuery.ajax({
                        url: '<?php echo site_url('accounts/dashboard/get_ledger_balance'); ?>',
                        type: 'POST',
                        data: {
                            ledger_id: id,
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(res) {
                            try {
                                var r = typeof res === 'string' ? JSON.parse(res) : res;
                                if (r.status === 'success') {
                                    lookupResult.innerHTML =
                                        '<div class="balance-display">' +
                                        '<span class="bal-name">' + r.ledger_name + '</span>' +
                                        '<span class="bal-amount">' + r.balance + '</span>' +
                                        '<span class="bal-type">' + r.type + '</span>' +
                                        '</div>';
                                } else {
                                    lookupResult.innerHTML = '<span class="prompt-text" style="color:#ef4444;"><i class="fa fa-exclamation-circle"></i> ' + (r.message || 'Error') + '</span>';
                                }
                            } catch(e) {
                                lookupResult.innerHTML = '<span class="prompt-text" style="color:#ef4444;"><i class="fa fa-exclamation-circle"></i> Parse error</span>';
                            }
                        },
                        error: function() {
                            lookupResult.innerHTML = '<span class="prompt-text" style="color:#ef4444;"><i class="fa fa-exclamation-triangle"></i> Network error</span>';
                        }
                    });
                } else {
                    console.warn("jQuery missing for Ledger Balance Lookup.");
                }
            });
            console.log("Ledger Lookup handler bound.");
        }
    } catch(e) {
        console.error("Ledger Lookup initialization failed:", e);
    }

    // ── Dynamic Offcanvas Sidebars & Namespaced Logic ────────────
    try {
        // Safe scoping of all variables & calculation routines
        
        // PHP-to-JS Options injection
        var receipt_ledger_opts = '<?php 
            $opt = "";
            foreach($ledgers as $l) {
                $opt .= "<option value=\"".$l['id']."\">".addslashes(htmlspecialchars($l['name']))."</option>";
            }
            echo $opt;
        ?>';
        var receipt_expense_opts = '<?php
            $opt = "";
            foreach($expense_types as $et) {
                $opt .= "<option value=\"".$et['id']."\">".addslashes(htmlspecialchars($et['name']))."</option>";
            }
            echo $opt;
        ?>';
        
        var payment_ledger_opts = receipt_ledger_opts;
        var payment_expense_opts = receipt_expense_opts;
        var purchase_expense_opts = receipt_expense_opts;
        var journal_ledger_opts = receipt_ledger_opts;
        
        var activeLedgerSelect = null;

        // Move fixed elements directly to body to prevent styling/clipping issues
        if (window.jQuery) {
            window.jQuery('.acc-offcanvas').appendTo('body');
            window.jQuery('#accQuickOverlay').appendTo('body');
        }

        // ── Navigation & Control ──
        window.openSidebar = function(type) {
            closeAllSidebars();
            var $sidebar = window.jQuery('#accQuickSidebar_' + type);
            var $overlay = window.jQuery('#accQuickOverlay');
            if ($sidebar.length) {
                $sidebar.addClass('show');
                $overlay.addClass('show');
                
                // Initialize Select2 dropdowns
                if (window.jQuery.fn.select2) {
                    $sidebar.find('.select2-namespaced').select2({ width: '100%' });
                    $sidebar.find('.ledger_select').select2({ width: '100%' });
                }
                
                // Contextual initialization
                if (type === 'receipt') {
                    receipt_calculate_total();
                } else if (type === 'payment') {
                    payment_calculate_total();
                } else if (type === 'contra') {
                    contra_update_ledgers();
                } else if (type === 'purchase') {
                    purchase_calculate_total();
                } else if (type === 'journal') {
                    journal_calculate_total();
                }
            }
        };

        window.closeAllSidebars = function() {
            window.jQuery('.acc-offcanvas').removeClass('show');
            window.jQuery('#accQuickOverlay').removeClass('show');
        };

        // Event delegation for opening and closing sidebars
        if (window.jQuery) {
            window.jQuery(document).on('click', '.btn-open-quick', function(e) {
                e.preventDefault();
                var type = window.jQuery(this).attr('data-type');
                openSidebar(type);
            });
            window.jQuery(document).on('click', '#accQuickOverlay', function(e) {
                e.preventDefault();
                closeAllSidebars();
            });
        }

        // ── Shared Balance Lookup AJAX ──
        window.fetchLedgerBalance = function(ledgerId, $badgeEl) {
            if (!$badgeEl || $badgeEl.length === 0) return;
            if (!ledgerId) {
                $badgeEl.html('');
                return;
            }
            $badgeEl.html('<i class="fa fa-spinner fa-spin" style="color:#6b7280;"></i>');
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/ledgermaster/get_balance"); ?>',
                type: 'POST',
                data: {
                    id: ledgerId,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'JSON',
                success: function(res) {
                    if (res.status === 'success') {
                        var color = (res.type === 'Cr') ? '#10b981' : '#ef4444';
                        $badgeEl.html('Balance: <span style="color:' + color + '; font-weight: 600;">' + parseFloat(res.balance).toFixed(2) + ' ' + res.type + '</span>');
                    } else {
                        $badgeEl.html('');
                    }
                },
                error: function() {
                    $badgeEl.html('');
                }
            });
        };

        // ── Receipt Voucher Section ──
        window.receipt_add_row = function() {
            var html = '<tr>' +
                '<td>' +
                '    <div class="input-group">' +
                '        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">' +
                '            <option value="">Select</option>' + receipt_ledger_opts +
                '        </select>' +
                '        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="receipt_showAddLedgerModal(window.jQuery(this).closest(\'.input-group\').find(\'select\'))">' +
                '            <i class="fa fa-plus text-primary"></i>' +
                '        </div>' +
                '    </div>' +
                '    <div class="cr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>' +
                '</td>' +
                '<td>' +
                '    <select name="expense_type_id[]" class="form-control">' +
                '        <option value="">Select</option>' + receipt_expense_opts +
                '    </select>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control receipt_amount" required></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="receipt_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            var $row = window.jQuery(html);
            window.jQuery('#receipt_item_table tbody').append($row);
            if (window.jQuery.fn.select2) {
                $row.find('.ledger_select').select2({ width: '100%' });
            }
        };

        window.receipt_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            receipt_calculate_total();
        };

        window.receipt_calculate_total = function() {
            var total = 0;
            window.jQuery('.receipt_amount').each(function() {
                total += parseFloat(window.jQuery(this).val()) || 0;
            });
            window.jQuery('#receipt_total_amount').val(total.toFixed(2));
        };

        window.receipt_showAddPaymentModeModal = function() {
            window.jQuery('#new_pm_name').val('');
            window.jQuery('#pm_group_id').val('').trigger('change');
            window.jQuery('#pm_bank_id').val('');
            window.jQuery('#new_pm_account_no').val('');
            window.jQuery('#addPaymentModeModal').modal('show');
        };

        window.receipt_showAddLedgerModal = function($select) {
            activeLedgerSelect = $select;
            window.jQuery('#new_ledger_name').val('');
            window.jQuery('#ledger_group_id').val('');
            window.jQuery('#addLedgerModal').modal('show');
        };

        window.jQuery(document).on('keyup change', '.receipt_amount', function() {
            receipt_calculate_total();
        });

        window.jQuery(document).on('change', '#receipt_dr_ledger_id', function() {
            fetchLedgerBalance($(this).val(), window.jQuery('#receipt_dr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#receipt_item_table .ledger_select', function() {
            var row = window.jQuery(this).closest('tr');
            fetchLedgerBalance($(this).val(), row.find('.cr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#receipt_payment_method', function() {
            var method = $(this).val();
            if (method === 'Cash') {
                window.jQuery('#receipt_payment_details_section').slideUp();
            } else {
                window.jQuery('#receipt_payment_details_section').slideDown();
                if (method === 'Cheque') {
                    window.jQuery('#receipt_lbl_ref_no').text('Cheque No');
                    window.jQuery('.receipt_bank_field_group').show();
                } else if (method === 'UPI') {
                    window.jQuery('#receipt_lbl_ref_no').text('UPI Transaction ID');
                    window.jQuery('.receipt_bank_field_group').hide();
                } else if (method === 'Net Banking') {
                    window.jQuery('#receipt_lbl_ref_no').text('Net Banking Ref');
                    window.jQuery('.receipt_bank_field_group').show();
                } else {
                    window.jQuery('#receipt_lbl_ref_no').text('Reference No');
                    window.jQuery('.receipt_bank_field_group').hide();
                }
            }
        });

        // ── Payment Voucher Section ──
        window.payment_add_row = function() {
            var html = '<tr>' +
                '<td>' +
                '    <div class="input-group">' +
                '        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">' +
                '            <option value="">Select</option>' + payment_ledger_opts +
                '        </select>' +
                '        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="payment_showAddLedgerModal(window.jQuery(this).closest(\'.input-group\').find(\'select\'))">' +
                '            <i class="fa fa-plus text-primary"></i>' +
                '        </div>' +
                '    </div>' +
                '    <div class="dr_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>' +
                '</td>' +
                '<td>' +
                '    <select name="expense_type_id[]" class="form-control">' +
                '        <option value="">Select</option>' + payment_expense_opts +
                '    </select>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control payment_amount" required></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="payment_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            var $row = window.jQuery(html);
            window.jQuery('#payment_item_table tbody').append($row);
            if (window.jQuery.fn.select2) {
                $row.find('.ledger_select').select2({ width: '100%' });
            }
        };

        window.payment_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            payment_calculate_total();
        };

        window.payment_calculate_total = function() {
            var total = 0;
            window.jQuery('.payment_amount').each(function() {
                total += parseFloat(window.jQuery(this).val()) || 0;
            });
            window.jQuery('#payment_total_amount').val(total.toFixed(2));
        };

        window.payment_showAddPaymentModeModal = function() {
            receipt_showAddPaymentModeModal();
        };

        window.payment_showAddLedgerModal = function($select) {
            receipt_showAddLedgerModal($select);
        };

        window.jQuery(document).on('keyup change', '.payment_amount', function() {
            payment_calculate_total();
        });

        window.jQuery(document).on('change', '#payment_cr_ledger_id', function() {
            fetchLedgerBalance($(this).val(), window.jQuery('#payment_cr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#payment_item_table .ledger_select', function() {
            var row = window.jQuery(this).closest('tr');
            fetchLedgerBalance($(this).val(), row.find('.dr_ledger_balance'));
        });

        window.jQuery(document).on('change', '#payment_payment_method', function() {
            var method = $(this).val();
            if (method === 'Cash') {
                window.jQuery('#payment_payment_details_section').slideUp();
            } else {
                window.jQuery('#payment_payment_details_section').slideDown();
                if (method === 'Cheque') {
                    window.jQuery('#payment_lbl_ref_no').text('Cheque No');
                    window.jQuery('.payment_bank_field_group').show();
                } else if (method === 'UPI') {
                    window.jQuery('#payment_lbl_ref_no').text('UPI Transaction ID');
                    window.jQuery('.payment_bank_field_group').hide();
                } else if (method === 'Net Banking') {
                    window.jQuery('#payment_lbl_ref_no').text('Net Banking Ref');
                    window.jQuery('.payment_bank_field_group').show();
                } else {
                    window.jQuery('#payment_lbl_ref_no').text('Reference No');
                    window.jQuery('.payment_bank_field_group').hide();
                }
            }
        });

        // ── Contra Voucher Section ──
        window.contra_update_ledgers = function() {
            var type = window.jQuery('#contra_transaction_type').val();
            var dr_select = window.jQuery('#contra_dr_ledger_id');
            var cr_select = window.jQuery('#contra_cr_ledger_id');
            
            if (dr_select.data('select2')) dr_select.select2('destroy');
            if (cr_select.data('select2')) cr_select.select2('destroy');
            
            dr_select.find('option').prop('disabled', false).show();
            cr_select.find('option').prop('disabled', false).show();
            
            if (type === 'Cash To Bank') {
                window.jQuery('#contra_lbl_dr_account').text('Deposit Into Bank (Debit) *');
                window.jQuery('#contra_lbl_cr_account').text('Withdraw From Cash (Credit) *');
                dr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
                cr_select.find('option[data-system-group!="cash"][value!=""]').prop('disabled', true).hide();
            } else if (type === 'Bank To Cash') {
                window.jQuery('#contra_lbl_dr_account').text('Deposit Into Cash (Debit) *');
                window.jQuery('#contra_lbl_cr_account').text('Withdraw From Bank (Credit) *');
                dr_select.find('option[data-system-group!="cash"][value!=""]').prop('disabled', true).hide();
                cr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
            } else if (type === 'Bank To Bank') {
                window.jQuery('#contra_lbl_dr_account').text('Receiving Bank (Debit) *');
                window.jQuery('#contra_lbl_cr_account').text('Sending Bank (Credit) *');
                dr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
                cr_select.find('option[data-system-group!="bank"][value!=""]').prop('disabled', true).hide();
            }
            
            if (dr_select.find('option:selected').prop('disabled')) dr_select.val('');
            if (cr_select.find('option:selected').prop('disabled')) cr_select.val('');
            
            if (window.jQuery.fn.select2) {
                dr_select.select2({ width: '100%' });
                cr_select.select2({ width: '100%' });
            }
        };

        window.jQuery(document).on('change', '#contra_transaction_type', function() {
            contra_update_ledgers();
        });

        window.jQuery(document).on('change', '#contra_dr_ledger_id', function() {
            var row = window.jQuery(this).closest('.form-group');
            fetchLedgerBalance($(this).val(), row.find('.contra_ledger_balance'));
        });

        window.jQuery(document).on('change', '#contra_cr_ledger_id', function() {
            var row = window.jQuery(this).closest('.form-group');
            fetchLedgerBalance($(this).val(), row.find('.contra_ledger_balance'));
        });

        // ── Purchase Entry Section ──
        window.purchase_add_row = function() {
            var html = '<tr>' +
                '<td><input type="text" name="item_description[]" class="form-control" required></td>' +
                '<td>' +
                '    <select name="expense_type_id[]" class="form-control">' +
                '        <option value="">Select</option>' + purchase_expense_opts +
                '    </select>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="qty[]" class="form-control purchase_qty" value="1.00" required></td>' +
                '<td><input type="number" step="0.01" name="rate[]" class="form-control purchase_rate" required></td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control purchase_amount" readonly></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="purchase_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            window.jQuery('#purchase_item_table tbody').append(html);
        };

        window.purchase_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            purchase_calculate_total();
        };

        window.purchase_calculate_total = function() {
            var total = 0;
            window.jQuery('.purchase_amount').each(function() {
                total += parseFloat(window.jQuery(this).val()) || 0;
            });
            window.jQuery('#purchase_total_amount').val(total.toFixed(2));
            
            var discount = parseFloat(window.jQuery('#purchase_discount').val()) || 0;
            var gst = parseFloat(window.jQuery('#purchase_gst_amount').val()) || 0;
            
            var net = total - discount + gst;
            window.jQuery('#purchase_net_amount').val(net.toFixed(2));
        };

        window.jQuery(document).on('keyup change', '.purchase_qty, .purchase_rate', function() {
            var row = window.jQuery(this).closest('tr');
            var qty = parseFloat(row.find('.purchase_qty').val()) || 0;
            var rate = parseFloat(row.find('.purchase_rate').val()) || 0;
            var amount = qty * rate;
            row.find('.purchase_amount').val(amount.toFixed(2));
            purchase_calculate_total();
        });

        window.jQuery(document).on('keyup change', '.purchase_calc_net', function() {
            purchase_calculate_total();
        });

        // ── Journal Voucher Section ──
        window.journal_add_row = function() {
            var html = '<tr>' +
                '<td>' +
                '    <select name="type[]" class="form-control journal_type_select" style="width:100%;">' +
                '        <option value="Dr">Dr</option>' +
                '        <option value="Cr">Cr</option>' +
                '    </select>' +
                '</td>' +
                '<td>' +
                '    <div class="input-group">' +
                '        <select name="ledger_id[]" class="form-control ledger_select" required style="width: 100%;">' +
                '            <option value="">Select</option>' + journal_ledger_opts +
                '        </select>' +
                '        <div class="input-group-addon" style="padding: 6px 10px; cursor: pointer;" onclick="journal_showAddLedgerModal(window.jQuery(this).closest(\'.input-group\').find(\'select\'))">' +
                '            <i class="fa fa-plus text-primary"></i>' +
                '        </div>' +
                '    </div>' +
                '    <div class="journal_ledger_balance" style="font-size:11px; font-weight:600; margin-top:2px;"></div>' +
                '</td>' +
                '<td><input type="number" step="0.01" name="amount[]" class="form-control journal_amount" required></td>' +
                '<td><button type="button" class="btn btn-sm btn-danger" onclick="journal_remove_row(this)"><i class="fa fa-remove"></i></button></td>' +
                '</tr>';
            var $row = window.jQuery(html);
            window.jQuery('#journal_item_table tbody').append($row);
            if (window.jQuery.fn.select2) {
                $row.find('.ledger_select').select2({ width: '100%' });
            }
        };

        window.journal_remove_row = function(el) {
            window.jQuery(el).closest('tr').remove();
            journal_calculate_total();
        };

        window.journal_calculate_total = function() {
            var dr = 0;
            var cr = 0;
            window.jQuery('#journal_item_table tbody tr').each(function() {
                var type = window.jQuery(this).find('.journal_type_select').val();
                var val = parseFloat(window.jQuery(this).find('.journal_amount').val()) || 0;
                if (type === 'Dr') {
                    dr += val;
                } else {
                    cr += val;
                }
            });
            window.jQuery('#journal_total_dr').val(dr.toFixed(2));
            window.jQuery('#journal_total_cr').val(cr.toFixed(2));
            
            if (dr !== cr || dr === 0) {
                window.jQuery('#journal_total_dr, #journal_total_cr').css('border-color', 'red');
            } else {
                window.jQuery('#journal_total_dr, #journal_total_cr').css('border-color', 'green');
            }
        };

        window.journal_showAddLedgerModal = function($select) {
            receipt_showAddLedgerModal($select);
        };

        window.jQuery(document).on('keyup change', '.journal_amount, .journal_type_select', function() {
            journal_calculate_total();
        });

        window.jQuery(document).on('change', '#journal_item_table select[name="ledger_id[]"]', function() {
            var row = window.jQuery(this).closest('tr');
            fetchLedgerBalance($(this).val(), row.find('.journal_ledger_balance'));
        });

        window.jQuery('#journal_form_submit').submit(function(e) {
            var dr = parseFloat(window.jQuery('#journal_total_dr').val()) || 0;
            var cr = parseFloat(window.jQuery('#journal_total_cr').val()) || 0;
            if (dr !== cr || dr === 0) {
                e.preventDefault();
                alert("Debit and Credit amounts must be equal and greater than zero.");
                return false;
            }
        });

        // ── Shared Quick Add Modals Logic ──
        window.showAddBankModal = function() {
            window.jQuery('#new_bank_name').val('');
            window.jQuery('#addBankModal').modal('show');
        };

        window.showAddSupplierModal = function() {
            window.jQuery('#new_supplier_name').val('');
            window.jQuery('#new_supplier_mobile').val('');
            window.jQuery('#addSupplierModal').modal('show');
        };

        window.jQuery('#pm_group_id').change(function() {
            var val = window.jQuery(this).find('option:selected').text();
            if (val.toLowerCase() === 'bank account' || val.toLowerCase() === 'bank accounts') {
                window.jQuery('#pm_bank_details_section').slideDown();
            } else {
                window.jQuery('#pm_bank_details_section').slideUp();
            }
        });

        window.submitNewPaymentMode = function() {
            var name = window.jQuery('#new_pm_name').val().trim();
            var group_id = window.jQuery('#pm_group_id').val();
            if (name === '' || !group_id) {
                alert('Please enter a name and select a group.');
                return;
            }
            
            var bank_id = window.jQuery('#pm_bank_id').val();
            var account_no = window.jQuery('#new_pm_account_no').val().trim();
            
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/receiptvoucher/quick_add_ledger_ajax"); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    group_id: group_id,
                    name: name,
                    bank_id: bank_id,
                    account_no: account_no,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        var newOption1 = new Option(res.name, res.id, true, true);
                        var newOption2 = new Option(res.name, res.id, true, true);
                        var newOption3 = new Option(res.name, res.id, true, true);
                        var newOption4 = new Option(res.name, res.id, true, true);
                        
                        // Add metadata attribute for Contra grouping
                        window.jQuery(newOption3).attr('data-group', group_id);
                        window.jQuery(newOption4).attr('data-group', group_id);
                        
                        window.jQuery('#receipt_dr_ledger_id').append(newOption1).trigger('change');
                        window.jQuery('#payment_cr_ledger_id').append(newOption2).trigger('change');
                        window.jQuery('#contra_dr_ledger_id').append(newOption3).trigger('change');
                        window.jQuery('#contra_cr_ledger_id').append(newOption4).trigger('change');
                        
                        window.jQuery('#addPaymentModeModal').modal('hide');
                    } else {
                        alert(res.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        window.submitNewLedger = function() {
            var name = window.jQuery('#new_ledger_name').val().trim();
            var group_id = window.jQuery('#ledger_group_id').val();
            if (name === '' || !group_id) {
                alert('Please enter a name and select a group.');
                return;
            }
            
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/receiptvoucher/quick_add_ledger_ajax"); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    group_id: group_id,
                    name: name,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        // Append to options so future dynamic rows have it
                        var optHtml = '<option value="' + res.id + '">' + res.name + '</option>';
                        receipt_ledger_opts += optHtml;
                        payment_ledger_opts += optHtml;
                        journal_ledger_opts += optHtml;
                        
                        // Synced update: append to all existing ledger selects
                        window.jQuery('select.ledger_select').each(function() {
                            var newOpt = new Option(res.name, res.id, false, false);
                            window.jQuery(this).append(newOpt);
                        });
                        
                        // Select it in the active dropdown that triggered it
                        if (activeLedgerSelect && activeLedgerSelect.length) {
                            activeLedgerSelect.val(res.id).trigger('change');
                        }
                        
                        window.jQuery('#addLedgerModal').modal('hide');
                    } else {
                        alert(res.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        window.submitNewBank = function() {
            var name = window.jQuery('#new_bank_name').val().trim();
            if (name === '') {
                alert('Please enter a bank name.');
                return;
            }
            
            var newOption1 = new Option(name, name);
            var newOption2 = new Option(name, name);
            var newOption3 = new Option(name, name);
            
            window.jQuery('#receipt_bank_name').append(newOption1);
            window.jQuery('#payment_bank_name').append(newOption2);
            window.jQuery('#pm_bank_id').append(newOption3);
            
            window.jQuery('#addBankModal').modal('hide');
        };

        window.submitNewSupplier = function() {
            var name = window.jQuery('#new_supplier_name').val().trim();
            var mobile = window.jQuery('#new_supplier_mobile').val().trim();
            if (name === '') {
                alert('Please enter a supplier name.');
                return;
            }
            
            window.jQuery.ajax({
                url: '<?php echo site_url("accounts/purchaseentry/addsupplier"); ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    name: name,
                    mobile: mobile,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(res) {
                    if (res.status === 'success') {
                        var newOption = new Option(res.name, res.id, true, true);
                        window.jQuery('#purchase_supplier_ledger_id').append(newOption).trigger('change');
                        window.jQuery('#addSupplierModal').modal('hide');
                    } else {
                        alert(res.error);
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        };

        // Global Autocomplete Voucher Search logic
        (function() {
            var $search = window.jQuery('#accGlobalSearch');
            var $dropdown = window.jQuery('#accSearchDropdown');
            var searchTimeout = null;

            $search.on('input', function() {
                var query = $search.val().trim();
                clearTimeout(searchTimeout);

                if (query.length < 1) {
                    $dropdown.html('').hide();
                    return;
                }

                searchTimeout = setTimeout(function() {
                    window.jQuery.ajax({
                        url: '<?php echo site_url("accounts/dashboard/search_vouchers_ajax"); ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            query: query,
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(response) {
                            if (response.status === 'success' && response.results.length > 0) {
                                var html = '';
                                response.results.forEach(function(item) {
                                    var badgeClass = 'badge-' + item.type;
                                    var typeLabel = item.type.toUpperCase();
                                    
                                    html += '<a href="' + item.url + '" target="_blank" class="acc-search-item">';
                                    html += '  <div class="acc-search-item-info">';
                                    html += '    <div class="acc-search-item-no">' + item.no + '</div>';
                                    html += '    <div class="acc-search-item-meta">' + item.ledgers + ' (' + item.date + ')</div>';
                                    html += '  </div>';
                                    html += '  <div class="acc-search-item-amount">';
                                    html += '    <span>' + '<?php echo $curr; ?>' + parseFloat(item.amount).toFixed(2) + '</span>';
                                    html += '    <span class="acc-search-item-badge ' + badgeClass + '">' + typeLabel + '</span>';
                                    html += '  </div>';
                                    html += '</a>';
                                });
                                $dropdown.html(html).show();
                            } else {
                                $dropdown.html('<div style="padding:12px 16px; text-align:center; color:#64748b; font-size:12px;"><i class="fa fa-info-circle"></i> No vouchers found</div>').show();
                            }
                        },
                        error: function() {
                            $dropdown.html('<div style="padding:12px 16px; text-align:center; color:#ef4444; font-size:12px;"><i class="fa fa-exclamation-triangle"></i> Search failed</div>').show();
                        }
                    });
                }, 300);
            });

            // Close dropdown when clicking outside
            window.jQuery(document).on('click', function(e) {
                if (!window.jQuery(e.target).closest('.acc-global-search-container').length) {
                    $dropdown.hide();
                }
            });

            $search.on('focus', function() {
                if ($dropdown.html() !== '') {
                    $dropdown.show();
                }
            });
        })();

        console.log("Quick Actions Offcanvas Sidebars initialized successfully.");
    } catch (e) {
        console.error("Quick Actions Offcanvas Sidebars initialization failed:", e);
    }

})();
</script>
