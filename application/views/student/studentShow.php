<?php
$status          = 'documents';
$admin_session   = $this->session->userdata('admin');
$currency_symbol = $admin_session['currency_symbol'];
$theme_color     = isset($admin_session['theme']['theme_color']) ? $admin_session['theme']['theme_color'] : '#4f46e5';
// Generate a slightly darker/lighter version for gradients if needed, or just use the primary theme color.
// For simplicity, we use $theme_color for primary accents and a fallback blue for secondary gradient stop.
?>

<style type="text/css">
/* ============================================================
   STUDENT PROFILE 2.0 — Scoped Design System
   Matches dashboard2.php design language
   All classes namespaced under .sp2-wrapper
   ============================================================ */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.sp2-wrapper {
    background: #f4f6f9;
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
}



/* ---------- Cards ---------- */
.sp2-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 18px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #eaeaea;
}
.sp2-card-sm {
    padding: 14px 18px;
}

/* ---------- Section Title ---------- */
.sp2-section-title {
    font-size: 11px;
    font-weight: 700;
    color: #8a8a8a;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sp2-section-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #f0f0f0;
}

/* ---------- Hero Header (Clean Solid Card) ---------- */
.sp2-hero {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    position: relative;
    box-shadow: 0 4px 18px rgba(0,0,0,0.02);
    border: 1px solid #f1f5f9;
}
.sp2-hero-body {
    display: flex;
    align-items: center;
    gap: 18px;
}
.sp2-hero-avatar-wrap {
    flex-shrink: 0;
}
.sp2-hero-avatar {
    width: 90px;
    height: 90px;
    border-radius: 16px;
    border: 2px solid #e2e8f0;
    object-fit: cover;
    display: block;
    background: #f8fafc;
}
.sp2-hero-avatar-placeholder {
    width: 90px;
    height: 90px;
    border-radius: 16px;
    border: 2px solid #e2e8f0;
    background: #f8fafc;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: #94a3b8;
}
.sp2-hero-info {
    flex: 1;
}
.sp2-hero-name {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 8px 0;
    line-height: 1.2;
    letter-spacing: -0.3px;
}
.sp2-hero-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}
.sp2-hero-pill {
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}
.sp2-hero-pill.disabled-pill {
    background: #ffe4e6;
    border-color: #fecdd3;
    color: #e11d48;
}

.sp2-btn {
    padding: 8px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
    white-space: nowrap;
}
.sp2-btn:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.sp2-btn.sp2-btn-danger {
    background: #fef2f2;
    border-color: #fecdd3;
    color: #e11d48;
}
.sp2-btn.sp2-btn-danger:hover {
    background: #ffe4e6;
    color: #be123c;
}
.sp2-btn.sp2-btn-success {
    background: #ecfdf5;
    border-color: #a7f3d0;
    color: #059669;
}
.sp2-btn.sp2-btn-success:hover {
    background: #d1fae5;
    color: #047857;
}

/* Disabled student banner */
.sp2-disabled-banner {
    background: #fff5f8;
    border-left: 4px solid #d8456a;
    border-radius: 6px;
    padding: 12px 16px;
    margin-bottom: 14px;
    font-size: 12px;
    color: #d8456a;
    font-weight: 600;
}
.sp2-disabled-banner strong { color: #222; }

/* ---------- Sidebar Info ---------- */
.sp2-info-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 9px 0;
    border-bottom: 1px solid #f4f6f9;
    gap: 8px;
}
.sp2-info-row:last-child { border-bottom: none; }
.sp2-info-label {
    font-size: 11px;
    font-weight: 600;
    color: #8a8a8a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    flex-shrink: 0;
}
.sp2-info-value {
    font-size: 13px;
    font-weight: 600;
    color: #222;
    text-align: right;
    word-break: break-word;
}
.sp2-info-value a { color: #222; }

/* ---------- Pills / Badges ---------- */
.sp2-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.sp2-pill-blue { background: #eef2ff; color: #4f46e5; }
.sp2-pill-teal { background: #e6fcf5; color: #0ca678; }
.sp2-pill-amber { background: #fff8e6; color: #f59f00; }
.sp2-pill-purple { background: #f3f0ff; color: #7950f2; }

/* ---------- Profile Tab Clean Tables ---------- */
.sp2-profile-table {
    width: 100%;
    margin-bottom: 0;
}
.sp2-profile-table td {
    padding: 10px 0;
    border-bottom: 1px solid #f4f6f9 !important;
    border-top: none !important;
    vertical-align: top;
}
.sp2-profile-table tr:last-child td {
    border-bottom: none !important;
}
.sp2-profile-table td:first-child {
    font-size: 11px;
    font-weight: 600;
    color: #8a8a8a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 35%;
}
.sp2-profile-table td:last-child {
    font-size: 13px;
    font-weight: 600;
    color: #222;
    text-align: right;
    word-break: break-word;
}
.sp2-profile-table td a { color: <?php echo $theme_color; ?>; }
}
.sp2-pill-blue   { background: #e0e7ff; color: <?php echo $theme_color; ?>; }
.sp2-pill-green  { background: #dcf2e6; color: #3b9b65; }
.sp2-pill-red    { background: #fff0f3; color: #d8456a; }
.sp2-pill-amber  { background: #fbedcf; color: #d09435; }
.sp2-pill-purple { background: #f4e8fb; color: #9d50ce; }
.sp2-pill-gray   { background: #f3f4f6; color: #4b5563; }
.sp2-pill-teal   { background: #e0fdf4; color: #0f766e; }

/* ---------- Siblings ---------- */
.sp2-sibling-card {
    background: #fafafa;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    padding: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 10px;
    transition: all 0.2s;
}
.sp2-sibling-card:hover {
    background: #f4f6f9;
    border-color: #e0e7ff;
    box-shadow: 0 2px 8px rgba(79,70,229,0.08);
}
.sp2-sibling-img {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e7ff;
    flex-shrink: 0;
}
.sp2-sibling-info { flex: 1; min-width: 0; }
.sp2-sibling-name {
    font-size: 13px;
    font-weight: 700;
    color: #222;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sp2-sibling-name a { color: #222; }
.sp2-sibling-name a:hover { color: <?php echo $theme_color; ?>; }
.sp2-sibling-meta { font-size: 11px; color: #888; margin-top: 2px; }

/* ---------- Parent/Guardian ---------- */
.sp2-parent-item {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
}
.sp2-parent-item:last-child { border-bottom: none; padding-bottom: 0; }
.sp2-parent-avatar {
    width: 44px;
    height: 44px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid #eaeaea;
    background: #f9fafb;
    flex-shrink: 0;
}
.sp2-parent-details {
    flex: 1;
    display: flex;
    flex-wrap: wrap;
    row-gap: 8px;
    column-gap: 16px;
}
.sp2-parent-title {
    width: 100%;
    font-size: 11px;
    font-weight: 700;
    color: <?php echo $theme_color; ?>;
    text-transform: uppercase;
    margin-bottom: 0px;
    letter-spacing: 0.5px;
}
.sp2-parent-row {
    display: flex;
    flex-direction: column;
    font-size: 13px;
    min-width: 80px;
}
.sp2-parent-label {
    color: #888;
    font-weight: 600;
    font-size: 10px;
    text-transform: uppercase;
    margin-bottom: 2px;
}
.sp2-parent-val {
    color: #111827;
    font-weight: 500;
}

/* ---------- Enhanced Tab Navigation (Segmented Responsive Card Tiles) ---------- */
.sp2-tabs-wrapper {
    background: #ffffff;
    border-radius: 16px;
    padding: 14px 16px;
    margin-bottom: 24px;
    border: 1px solid #f1f5f9;
    box-shadow: 0 4px 20px rgba(0,0,0,0.03);
}
.sp2-tabs-nav {
    border-bottom: none !important;
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 10px !important;
    margin-bottom: 0 !important;
    padding: 0 !important;
    list-style: none !important;
}

.sp2-tabs-nav > li { 
    margin: 0 !important; 
    float: none !important;
    flex: 1 1 calc(20% - 10px);
    min-width: 140px;
}
@media (max-width: 1200px) {
    .sp2-tabs-nav > li { flex: 1 1 calc(25% - 10px); }
}
@media (max-width: 991px) {
    .sp2-tabs-nav > li { flex: 1 1 calc(33.33% - 10px); }
}
@media (max-width: 600px) {
    .sp2-tabs-nav > li { flex: 1 1 calc(50% - 10px); }
}

.sp2-tabs-nav > li > a {
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    padding: 12px 14px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    color: #475569 !important;
    background: #ffffff !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
    text-decoration: none !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.02) !important;
    text-align: center !important;
}
.sp2-tabs-nav > li > a i {
    font-size: 16px !important;
    color: #4f46e5 !important;
    transition: transform 0.2s ease, color 0.2s ease !important;
}
.sp2-tabs-nav > li > a:hover {
    color: #4f46e5 !important;
    background: #eef2ff !important;
    border-color: #c7d2fe !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 14px rgba(79, 70, 229, 0.12) !important;
}
.sp2-tabs-nav > li > a:hover i {
    transform: scale(1.15) !important;
}

.sp2-tabs-nav > li.active > a, 
.sp2-tabs-nav > li.active > a:hover, 
.sp2-tabs-nav > li.active > a:focus {
    background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%) !important;
    color: #ffffff !important;
    border-color: #4338ca !important;
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.3) !important;
}
.sp2-tabs-nav > li.active > a i {
    color: #ffffff !important;
}

.sp2-tab-content {
    background: transparent;
    border: none;
    padding: 0;
    box-shadow: none;
    margin-bottom: 18px;
}
/* ---------- Profile Data Grid (Replaces Tables) ---------- */
.sp2-data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
    margin-bottom: 14px;
}
@media (max-width: 991px) {
    .sp2-data-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 575px) {
    .sp2-data-grid { grid-template-columns: 1fr; }
}
.sp2-data-box {
    background: #f9fafb;
    border: 1px solid #f0f0f0;
    border-radius: 6px;
    padding: 10px 12px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: all 0.2s;
}
.sp2-data-box:hover {
    background: #fff;
    border-color: #e5e7eb;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    transform: translateY(-1px);
}
.sp2-data-box-label {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.sp2-data-box-value {
    font-size: 14px;
    font-weight: 700;
    color: #111827;
    word-break: break-word;
}
.sp2-data-box-value a { color: <?php echo $theme_color; ?>; text-decoration: none; }
.sp2-data-box-value a:hover { text-decoration: underline; }

/* ---------- Fees Invoice List ---------- */
.sp2-invoice-wrapper {
    background: transparent;
    margin-bottom: 24px;
    overflow-x: auto;
    width: 100%;
}
.sp2-invoice-table { display: block; min-width: 1200px; }
.sp2-invoice-thead {
    display: flex;
    padding: 0 16px 8px 16px;
    font-weight: 600;
    color: #6b7280;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #eaeaea;
    margin-bottom: 8px;
}
.sp2-invoice-thead .sp2-invoice-row {
    background: transparent;
    border: none;
    box-shadow: none;
    padding: 0;
    margin: 0;
}
.sp2-invoice-tbody { display: flex; flex-direction: column; gap: 12px; }

/* ---------- Attendance Top Cards ---------- */
.fee-deposits-wrapper {
    display: none;
    flex-direction: column;
    gap: 12px;
}
.fee-deposits-wrapper.open {
    display: flex;
}
.sp2-att-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.sp2-att-box {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    border: 1px solid #eaeaea;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.sp2-att-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.06);
}
.sp2-att-box-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    color: #6b7280;
    margin-bottom: 8px;
}
.sp2-att-box-val {
    font-size: 24px;
    font-weight: 700;
}
.ab-present .sp2-att-box-val { color: #10b981; }
.ab-absent .sp2-att-box-val { color: #ef4444; }
.ab-late .sp2-att-box-val { color: #f59e0b; }
.ab-halfday .sp2-att-box-val { color: #8b5cf6; }
.ab-holiday .sp2-att-box-val { color: #3b82f6; }

/* ---------- Timeline Modernization ---------- */
.timeline.timeline-inverse {
    position: relative;
    padding-left: 30px;
}
.timeline.timeline-inverse::before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 14px;
    width: 2px;
    background: repeating-linear-gradient(to bottom, #d1d5db, #d1d5db 6px, transparent 6px, transparent 12px);
}
.timeline.timeline-inverse > li {
    margin-bottom: 24px;
    position: relative;
}
.timeline.timeline-inverse > li.time-label {
    margin-bottom: 16px;
}
.timeline.timeline-inverse > li.time-label > span {
    background-color: <?php echo $theme_color; ?> !important;
    color: #fff;
    border-radius: 20px;
    padding: 6px 16px;
    font-size: 12px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.timeline.timeline-inverse > li > i {
    width: 32px;
    height: 32px;
    font-size: 14px;
    line-height: 32px;
    left: -30px;
    background-color: #fff !important;
    color: <?php echo $theme_color; ?> !important;
    border: 2px solid <?php echo $theme_color; ?>;
    box-shadow: 0 0 0 4px #fff;
    border-radius: 50%;
    z-index: 2;
}
.timeline.timeline-inverse > li > .timeline-item {
    background: #fff;
    border: 1px solid #eaeaea;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    border-radius: 12px;
    margin-left: 20px;
    padding: 16px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.timeline.timeline-inverse > li > .timeline-item:hover {
    transform: translateX(4px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.06);
    border-color: <?php echo $theme_color; ?>;
}
.timeline.timeline-inverse > li > .timeline-item > .time {
    color: #9ca3af;
    font-size: 12px;
}
.timeline.timeline-inverse > li > .timeline-item > .timeline-header {
    border-bottom: none;
    padding: 0;
    font-size: 16px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}
.timeline.timeline-inverse > li > .timeline-item > .timeline-body {
    padding: 0;
    color: #4b5563;
    font-size: 14px;
    line-height: 1.6;
}

.sp2-invoice-row {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    border: 1px solid #eaeaea;
    display: grid;
    grid-template-columns: minmax(160px, 1.5fr) minmax(90px, 1fr) minmax(90px, 1fr) minmax(110px, 1.2fr) minmax(100px, 1fr) minmax(90px, 1fr) minmax(100px, 1fr) minmax(100px, 1fr) minmax(100px, 1fr) minmax(100px, 1fr) minmax(110px, 1.2fr);
    align-items: center;
    gap: 12px;
}
.sp2-invoice-main-row {
    border-left: 4px solid <?php echo $theme_color; ?>;
}
.sp2-invoice-main-row:has(.label-danger) {
    background-color: #fef2f2;
    border-left: 4px solid #ef4444;
}
.sp2-invoice-main-row:has(.label-success) {
    background-color: #f0fdf4;
    border-left: 4px solid #10b981;
}
.sp2-invoice-main-row:has(.label-warning) {
    background-color: #fffbeb;
    border-left: 4px solid #f59e0b;
}

.sp2-invoice-sub-row {
    background: #fdfdfd;
    margin-left: 24px;
    margin-top: -8px;
    padding: 12px 16px;
    border-left: 2px dashed #d1d5db;
    box-shadow: none;
    border-radius: 0 12px 12px 0;
}
.sp2-invoice-total-row {
    background: #f4f5f7;
    border: none;
    font-weight: bold;
    justify-content: flex-end;
}
.sp2-invoice-col {
    flex: 1 1 80px;
    font-size: 13px;
    color: #4b5563;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.sp2-invoice-col:first-child {
    flex: 2 1 180px;
    font-weight: 600;
    color: #111827;
    font-size: 15px;
}
.sp2-invoice-col.text-right {
    text-align: right;
    font-weight: 600;
}
.sp2-invoice-col .label {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    text-transform: uppercase;
}
.sp2-invoice-col .label-success { background-color: #10b981 !important; }
.sp2-invoice-col .label-danger { background-color: #ef4444 !important; }
.sp2-invoice-col .label-warning { background-color: #f59e0b !important; }

/* ---------- Profile Data Rows ---------- */
.sp2-data-section {
    margin-bottom: 20px;
}
.sp2-data-section:last-child { margin-bottom: 0; }
.sp2-data-row {
    display: flex;
    align-items: flex-start;
    padding: 10px 0;
    border-bottom: 1px solid #f4f6f9;
    gap: 12px;
}
.sp2-data-row:last-child { border-bottom: none; }
.sp2-data-label {
    font-size: 12px;
    font-weight: 600;
    color: #8a8a8a;
    width: 38%;
    flex-shrink: 0;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    padding-top: 1px;
}
.sp2-data-value {
    font-size: 13px;
    font-weight: 600;
    color: #222;
    flex: 1;
    word-break: break-word;
}
.sp2-data-value img {
    width: 46px;
    height: 46px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #eaeaea;
}
.sp2-parent-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid #f4f6f9;
}
.sp2-parent-row:last-child { border-bottom: none; }
.sp2-parent-avatar {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #eaeaea;
    flex-shrink: 0;
}

/* ---------- Fees Tab ---------- */
.sp2-fee-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.sp2-fee-stat {
    border-radius: 8px;
    padding: 14px;
    border: 1px solid #f0f0f0;
}
.sp2-fee-stat-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 6px;
}
.sp2-fee-stat-val {
    font-size: 18px;
    font-weight: 800;
    color: #222;
}
.sp2-fee-stat.fs-total   { background: #f4f6f9; border-color: #e9ecef; }
.sp2-fee-stat.fs-total .sp2-fee-stat-label { color: #555; }
.sp2-fee-stat.fs-paid    { background: #f6fffa; border-color: #dcf2e6; }
.sp2-fee-stat.fs-paid .sp2-fee-stat-label { color: #3b9b65; }
.sp2-fee-stat.fs-paid .sp2-fee-stat-val { color: #3b9b65; }
.sp2-fee-stat.fs-balance { background: #fff5f8; border-color: #fbe0e8; }
.sp2-fee-stat.fs-balance .sp2-fee-stat-label { color: #d8456a; }
.sp2-fee-stat.fs-balance .sp2-fee-stat-val { color: #d8456a; }
.sp2-fee-stat.fs-fine    { background: #fffcf5; border-color: #fbedcf; }
.sp2-fee-stat.fs-fine .sp2-fee-stat-label { color: #d09435; }
.sp2-fee-stat.fs-fine .sp2-fee-stat-val { color: #d09435; }

.sp2-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
    margin-bottom: 0;
}
.sp2-table thead th {
    background: #f4f6f9;
    color: #555;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    padding: 10px 12px;
    border-bottom: 2px solid #eaeaea;
    white-space: nowrap;
}
.sp2-table tbody tr {
    border-bottom: 1px solid #f4f6f9;
    transition: background 0.15s;
}
.sp2-table tbody tr:hover { background: #fafbff; }
.sp2-table tbody tr.sp2-row-danger { background: #fff5f8 !important; }
.sp2-table tbody tr.sp2-row-sub { background: #fafafa; }
.sp2-table tbody td {
    padding: 9px 12px;
    color: #333;
    vertical-align: middle;
}
.sp2-table tfoot td {
    padding: 11px 12px;
    font-weight: 700;
    font-size: 13px;
    background: #f4f6f9;
    border-top: 2px solid #eaeaea;
    color: #222;
}
.sp2-table-wrap {
    border: 1px solid #eaeaea;
    border-radius: 8px;
    overflow: hidden;
}

/* ---------- Attendance Stat Boxes ---------- */
.sp2-att-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.sp2-att-box {
    border-radius: 8px;
    padding: 14px;
    text-align: center;
    border: 1px solid;
}
.sp2-att-box-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin-bottom: 6px;
}
.sp2-att-box-val {
    font-size: 26px;
    font-weight: 800;
    line-height: 1;
}
.sp2-att-box.ab-present  { background: #f6fffa; border-color: #dcf2e6; }
.sp2-att-box.ab-present .sp2-att-box-label { color: #3b9b65; }
.sp2-att-box.ab-present .sp2-att-box-val   { color: #3b9b65; }
.sp2-att-box.ab-absent   { background: #fff5f8; border-color: #fbe0e8; }
.sp2-att-box.ab-absent .sp2-att-box-label  { color: #d8456a; }
.sp2-att-box.ab-absent .sp2-att-box-val    { color: #d8456a; }
.sp2-att-box.ab-late     { background: #fffcf5; border-color: #fbedcf; }
.sp2-att-box.ab-late .sp2-att-box-label    { color: #d09435; }
.sp2-att-box.ab-late .sp2-att-box-val      { color: #d09435; }
.sp2-att-box.ab-halfday  { background: #fdfaff; border-color: #f4e8fb; }
.sp2-att-box.ab-halfday .sp2-att-box-label { color: #9d50ce; }
.sp2-att-box.ab-halfday .sp2-att-box-val   { color: #9d50ce; }
.sp2-att-box.ab-holiday  { background: #f0f4ff; border-color: #c7d7fd; }
.sp2-att-box.ab-holiday .sp2-att-box-label { color: #4f46e5; }
.sp2-att-box.ab-holiday .sp2-att-box-val   { color: <?php echo $theme_color; ?>; }

/* ---------- Timeline Overhaul ---------- */
.sp2-timeline {
    list-style: none;
    padding: 0;
    margin: 0;
    position: relative;
}
.sp2-timeline::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, <?php echo $theme_color; ?>, #007bff, #eaeaea);
}
.sp2-timeline-item {
    display: flex;
    gap: 16px;
    margin-bottom: 18px;
    position: relative;
}
.sp2-timeline-dot {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: <?php echo $theme_color; ?>;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 13px;
    flex-shrink: 0;
    z-index: 1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 2px;
}
.sp2-timeline-body {
    flex: 1;
    background: #f9fafb;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    padding: 12px 16px;
}
.sp2-timeline-date {
    font-size: 11px;
    font-weight: 600;
    color: <?php echo $theme_color; ?>;
    background: #e0e7ff;
    padding: 2px 8px;
    border-radius: 12px;
    display: inline-block;
    margin-bottom: 6px;
}
.sp2-timeline-title {
    font-size: 14px;
    font-weight: 700;
    color: #222;
    margin-bottom: 4px;
}
.sp2-timeline-desc {
    font-size: 12px;
    color: #666;
    line-height: 1.5;
}
.sp2-timeline-actions {
    display: flex;
    gap: 8px;
    margin-top: 8px;
}

/* ---------- Documents Tab ---------- */
.sp2-doc-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
    flex-wrap: wrap;
    gap: 8px;
}
.sp2-doc-btn {
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    background: <?php echo $theme_color; ?>;
    color: #fff;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
}
.sp2-doc-btn:hover { background: #333; color: #fff; }

/* ---------- Exam Tab ---------- */
.sp2-exam-card {
    background: #fff;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 18px;
}
.sp2-exam-header {
    background: linear-gradient(135deg, #f0f4ff, #e0e7ff);
    padding: 12px 16px;
    border-bottom: 1px solid #c7d7fd;
}
.sp2-exam-title {
    font-size: 14px;
    font-weight: 700;
    color: <?php echo $theme_color; ?>;
    margin: 0;
}
.sp2-exam-table thead th {
    background: <?php echo $theme_color; ?>;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 9px 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.sp2-exam-table tbody td {
    padding: 8px 12px;
    font-size: 12px;
    border-bottom: 1px solid #f4f6f9;
    color: #333;
}
.sp2-exam-table tbody tr:hover { background: #f9faff; }
.sp2-exam-summary {
    background: linear-gradient(135deg, #f0f4ff, #e8edff);
    border-top: 2px solid #c7d7fd;
    padding: 12px 16px;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}
.sp2-exam-summary-item {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.sp2-exam-summary-label {
    font-size: 10px;
    font-weight: 600;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.sp2-exam-summary-val {
    font-size: 16px;
    font-weight: 800;
    color: <?php echo $theme_color; ?>;
}

/* ---------- Behaviour Tab ---------- */
.sp2-behaviour-table thead th {
    background: #fdfaff;
    border-bottom: 2px solid #f4e8fb;
    color: #9d50ce;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    padding: 9px 12px;
}
.sp2-behaviour-table tbody td {
    padding: 9px 12px;
    font-size: 12px;
    border-bottom: 1px solid #f9f0ff;
    vertical-align: middle;
}
.sp2-behaviour-table tbody tr.danger { background: #fff5f8; }

/* ---------- Misc Tweaks ---------- */
.sp2-add-btn {
    padding: 6px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    background: <?php echo $theme_color; ?>;
    color: #fff;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
    float: right;
}
.sp2-add-btn:hover { background: #333; color: #fff; }
.sp2-no-record {
    text-align: center;
    padding: 30px;
    color: #999;
    font-size: 13px;
}
.sp2-no-record i {
    font-size: 28px;
    margin-bottom: 10px;
    display: block;
    opacity: 0.4;
}
.sp2-dropdown-menu {
    background: #fff;
    border: 1px solid #eaeaea;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    min-width: 180px;
}
.sp2-dropdown-menu li a {
    font-size: 13px;
    padding: 8px 16px;
    color: #333;
}
.sp2-dropdown-menu li a:hover { background: #f4f6f9; color: <?php echo $theme_color; ?>; }

/* Responsive for sidebar layout */
@media (max-width: 768px) {
    .sp2-hero-body { flex-wrap: wrap; }
    .sp2-hero-actions { justify-content: flex-start; }
    .sp2-fee-stat-grid { grid-template-columns: repeat(2, 1fr); }
    .sp2-att-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>

<div class="content-wrapper sp2-wrapper">
    <div class="row">
        <div>
            <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $student["class"]; ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i></a></p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($class_section as $skey => $svalue) {
                            ?>
                                <li <?php
                                    if ($student["section_id"] == $svalue["section_id"]) {
                                        echo "class='active'";
                                    }
                                    ?>><a href="#section<?php echo $svalue["section_id"] ?>" data-toggle="tab"><?php print_r($svalue["section"]); ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content pb20">
                        <?php foreach ($class_section as $skey => $snvalue) {
                        ?>
                            <div class="tab-pane <?php
                                                    if ($student["section_id"] == $snvalue["section_id"]) {
                                                        echo "active";
                                                    }
                                                    ?>" id="section<?php echo $snvalue["section_id"]; ?>">
                                <?php
                                foreach ($studentlistbysection as $stkey => $stvalue) {
                                    if ($stvalue['section_id'] == $snvalue["section_id"]) {

                                ?>
                                        <div class="studentname">
                                            <a class="" href="<?php echo base_url() . "student/view/" . $stvalue["id"] ?>">
                                                <div class="icon">
                                                    <?php if ($sch_setting->student_photo) {
                                                    ?>
                                                        <img src="<?php
                                                                    if (!empty($stvalue["image"])) {
                                                                        echo $this->media_storage->getImageURL($stvalue["image"]);
                                                                    } else {
                                                                        if ($student['gender'] == 'Female') {
                                                                            echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
                                                                        } elseif ($student['gender'] == 'Male') {
                                                                            echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
                                                                        }
                                                                    }
                                                                    ?>" alt="">
                                                    <?php } ?>
                                                </div>
                                                <div class="student-tittle"><?php echo $this->customlib->getFullName($stvalue['firstname'], $stvalue['middlename'], $stvalue['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></div>
                                            </a>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </aside>
        </div>
        <!-- /.control-sidebar -->
    </div>

    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12" style="padding-top:6px;">
                <!-- ===== SP2 HERO CARD ===== -->
                <div class="sp2-hero">
                    <div class="sp2-hero-body">
                        <div class="sp2-hero-avatar-wrap">
                            <?php if ($sch_setting->student_photo) {
                                if (!empty($student["image"])) {
                                    $image_url = $this->media_storage->getImageURL($student["image"]);
                                } else {
                                    if ($student['gender'] == 'Female') {
                                        $image_url = $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
                                    } else {
                                        $image_url = $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
                                    }
                                }
                            ?>
                                <img class="sp2-hero-avatar" src="<?php echo $image_url; ?>" alt="Student photo">
                            <?php } else { ?>
                                <div class="sp2-hero-avatar-placeholder"><i class="fa fa-user"></i></div>
                            <?php } ?>
                        </div>
                        <div class="sp2-hero-info">
                            <div class="sp2-hero-name"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></div>
                            <div class="sp2-hero-pills">
                                <span class="sp2-hero-pill"><i class="fa fa-id-card-o"></i> <?php echo $this->lang->line('admission_no'); ?>: <?php echo $student['admission_no']; ?></span>
                                <?php if ($sch_setting->roll_no && !empty($student['roll_no'])) { ?>
                                <span class="sp2-hero-pill"><i class="fa fa-hashtag"></i> <?php echo $this->lang->line('roll_number'); ?>: <?php echo $student['roll_no']; ?></span>
                                <?php } ?>
                                <span class="sp2-hero-pill"><?php echo $student['class']; ?> &bull; <?php echo $student['section']; ?></span>
                                <?php if ($student["is_active"] == "no") { ?>
                                <span class="sp2-hero-pill disabled-pill"><i class="fa fa-ban"></i> Disabled</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons Row -->
                    <div style="display:flex; flex-wrap:wrap; gap:8px; padding-top:16px; border-top:1px solid #f1f5f9; margin-top:16px;">
                        <?php if ($student["is_active"] == "yes") { ?>
                            <?php if ($this->rbac->hasPrivilege('student', 'can_edit')) { ?>
                            <a href="<?php echo base_url() . 'student/edit/' . $student['id'] ?>" class="sp2-btn" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i> <?php echo $this->lang->line('edit'); ?></a>
                            <?php } ?>
                            <?php if ($this->module_lib->hasActive('fees_collection')) { ?>
                            <a href="<?php echo site_url('studentfee/addfee/' . $student['student_session_id']) ?>" class="sp2-btn sp2-btn-success" data-toggle="tooltip" title="<?php echo $this->lang->line('collect_fees'); ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('collect_fees'); ?></a>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('disable_student', 'can_view')) { ?>
                            <a style="cursor:pointer;" onclick="disable_student('<?php echo $student['id'] ?>')" class="sp2-btn sp2-btn-danger" data-toggle="tooltip" title="<?php echo $this->lang->line('student_logout_and_disable'); ?>"><i class="fa fa-ban"></i> <?php echo $this->lang->line('student_logout_and_disable'); ?></a>
                            <div class="dropdown" style="display:inline-block;">
                                <a href="#" class="sp2-btn dropdown-toggle" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                <ul class="dropdown-menu sp2-dropdown-menu">
                                    <li><a style="cursor:pointer;" onclick="send_password()"><?php echo $this->lang->line('send_student_password'); ?></a></li>
                                    <li><a style="cursor:pointer;" onclick="send_parent_password()"><?php echo $this->lang->line('send_parent_password'); ?></a></li>
                                </ul>
                            </div>
                            <?php } ?>
                            <?php if ($this->rbac->hasPrivilege('student_login_credential_report', 'can_view')) { ?>
                            <a href="#" class="sp2-btn schedule_modal" data-toggle="tooltip" title="<?php echo $this->lang->line('login_details'); ?>"><i class="fa fa-key"></i></a>
                            <?php } ?>
                        <?php } else { ?>
                            <a href="#" onclick="enable('<?php echo $student['id'] ?>')" class="sp2-btn sp2-btn-success" data-toggle="tooltip" title="<?php echo $this->lang->line('enable'); ?>"><i class="fa fa-thumbs-o-up"></i> <?php echo $this->lang->line('enable'); ?></a>
                        <?php } ?>
                        <a type="button" class="sp2-btn print_student_details" data-student_id="<?php echo $student['id'] ?>" data-student_name="<?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>" data-admission_no="<?php echo $student['admission_no']; ?>" data-action="download" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('print'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i>" autocomplete="off"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></a>
                    </div>
                </div><!-- /.sp2-hero -->

                <!-- ===== SP2 SIDEBAR INFO CARD ===== -->
                <div class="sp2-card sp2-card-sm">
                    <?php if ($student['is_active'] == 'no') { ?>
                    <div class="sp2-disabled-banner">
                        <i class="fa fa-exclamation-circle"></i> <strong><?php echo $this->lang->line('disable_reason'); ?>:</strong> <?php if(!empty($reason_data['reason'])){ echo $reason_data['reason']; } ?>
                        <br><strong><?php echo $this->lang->line('disable_note'); ?>:</strong> <?php echo $student['dis_note'] ?>
                        <br><strong><?php echo $this->lang->line('disable_date'); ?>:</strong> <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['disable_at'])); ?>
                    </div>
                    <?php } ?>

                    <div class="sp2-section-title" style="color:#d68940;">Quick Info</div>

                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('class'); ?></span>
                        <span class="sp2-info-value"><span class="sp2-pill sp2-pill-blue"><?php echo $student['class'] . ' (' . $session . ')'; ?></span></span>
                    </div>
                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('section'); ?></span>
                        <span class="sp2-info-value"><span class="sp2-pill sp2-pill-teal"><?php echo $student['section']; ?></span></span>
                    </div>
                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('gender'); ?></span>
                        <span class="sp2-info-value">
                            <?php
                            $g = strtolower((string)$student['gender']);
                            $gpill = ($g == 'female') ? 'sp2-pill-purple' : 'sp2-pill-amber';
                            ?>
                            <span class="sp2-pill <?php echo $gpill; ?>"><?php echo $this->lang->line($g); ?></span>
                        </span>
                    </div>
                    <?php if ($sch_setting->rte) { ?>
                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('rte'); ?></span>
                        <span class="sp2-info-value"><?php if($student['rte']){ echo $this->lang->line(strtolower($student['rte'])); } ?></span>
                    </div>
                    <?php } ?>
                    <?php if ($sch_setting->student_barcode == 1) { ?>
                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('barcode'); ?></span>
                        <span class="sp2-info-value">
                            <?php if (file_exists("uploads/student_id_card/barcodes/" . $student['id'] . ".png")) { ?>
                            <a href="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/barcodes/' . $student['id'] . '.png'); ?>" target="_blank">
                                <img class="h-36" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/barcodes/' . $student['id'] . '.png'); ?>" width="auto" height="36" style="border-radius:4px;" /></a>
                            <?php } ?>
                        </span>
                    </div>
                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('qrcode'); ?></span>
                        <span class="sp2-info-value">
                            <?php if (file_exists("./uploads/student_id_card/qrcode/" . $student['id'] . ".png")) { ?>
                            <a href="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student['id'] . '.png'); ?>" target="_blank">
                                <img class="h-50 qrcodeimg" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student['id'] . '.png'); ?>" width="50" height="50" style="border-radius:4px;" /></a>
                            <?php } ?>
                        </span>
                    </div>
                    <?php } ?>
                    <!------- Behaviour Report Start-------->
                    <?php
                    if ($this->module_lib->hasModule('behaviour_records')) {
                        if ($this->rbac->hasPrivilege('behaviour_records_assign_incident', 'can_view')) {
                    ?>
                    <div class="sp2-info-row">
                        <span class="sp2-info-label"><?php echo $this->lang->line('behaviour_score'); ?></span>
                        <span class="sp2-info-value">
                            <span class="sp2-pill <?php echo ($student['total_points'] >= 0) ? 'sp2-pill-green' : 'sp2-pill-red'; ?>"><?php echo $student['total_points']; ?></span>
                        </span>
                    </div>
                    <?php
                        }
                    }
                    ?>
                    <!------- Behaviour Report End--------->
                </div><!-- /.sp2-card -->

                <!-- ===== SP2 SIBLINGS CARD ===== -->
                <?php if (!empty($siblings)) { ?>
                <div class="sp2-card sp2-card-sm" style="margin-top:0;">
                    <div class="sp2-section-title" style="color:#9d50ce;"><i class="fa fa-users"></i> <?php echo $this->lang->line('sibling'); ?></div>
                    <?php foreach ($siblings as $sibling_key => $sibling_value) { ?>
                    <div class="sp2-sibling-card">
                        <img class="sp2-sibling-img" src="<?php
                            if (!empty($sibling_value->image)) {
                                echo $this->media_storage->getImageURL($sibling_value->image);
                            } else {
                                if ($sibling_value->gender == 'Female') {
                                    echo $this->media_storage->getImageURL("uploads/student_images/default_female.jpg");
                                } else {
                                    echo $this->media_storage->getImageURL("uploads/student_images/default_male.jpg");
                                }
                            }
                        ?>" alt="<?php echo $this->lang->line('user_avatar'); ?>">
                        <div class="sp2-sibling-info">
                            <div class="sp2-sibling-name"><a href="<?php echo site_url('student/view/' . $sibling_value->id) ?>"><?php echo $this->customlib->getFullName($sibling_value->firstname, $sibling_value->middlename, $sibling_value->lastname, $sch_setting->middlename, $sch_setting->lastname); ?></a></div>
                            <div class="sp2-sibling-meta">
                                <?php echo $sibling_value->class; ?> &bull; <?php echo $sibling_value->section; ?>
                                &bull; <span class="sp2-pill sp2-pill-gray" style="font-size:10px; padding:1px 7px;"><?php echo $sibling_value->admission_no; ?></span>
                                <?php if ($sch_setting->roll_no && !empty($sibling_value->roll_no)) { ?>
                                &bull; Roll: <?php echo $sibling_value->roll_no; ?>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div><!-- /.sp2-card siblings -->
                <?php } ?>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12" style="padding-top:6px;">
                <!-- ===== SP2 TAB NAVIGATION WRAPPER ===== -->
                <div class="sp2-tabs-wrapper">
                    <ul class="sp2-tabs-nav nav nav-tabs">
                        <li class="active"><a href="#activity" data-toggle="tab"><i class="fa fa-user"></i> <?php echo $this->lang->line('profile'); ?></a></li>

                        <?php
                        if ($this->module_lib->hasActive('fees_collection')) {
                            if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) {
                        ?>
                                <li class=""><a href="#fee" data-toggle="tab" aria-expanded="true"><i class="fa fa-money"></i> <?php echo $this->lang->line('fees'); ?></a></li>
                        <?php
                            }
                        }
                        ?>

                        <?php if ($this->module_lib->hasActive('examination')) { ?>
                            <li><a href="#exam" data-toggle="tab" aria-expanded="true"><i class="fa fa-pencil-square-o"></i> <?php echo $this->lang->line('exam'); ?></a></li>
                        <?php } ?>

                        <!------- CBSE Exam Start-------->
                        <?php
                        if ($this->module_lib->hasModule('cbseexam')) {
                        ?>
                            <li class=""><a href="#cbseexam" data-toggle="tab" aria-expanded="true"><i class="fa fa-graduation-cap"></i> <?php echo $this->lang->line('cbse_exam'); ?></a></li>
                        <?php
                        }
                        ?>
                        <!------- CBSE Exam End-------->

                        <?php if ($this->module_lib->hasActive('student_attendance')) {
                            if (!$sch_setting->attendence_type) {
                        ?>
                                <li class=""><a href="#attendance" data-toggle="tab" aria-expanded="true"><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?></a>
                                </li>
                        <?php
                            }
                        }
                        ?>
                        <?php if ($sch_setting->upload_documents) {
                        ?>
                            <li class=""><a href="#documents" data-toggle="tab" aria-expanded="true"><i class="fa fa-folder-open-o"></i> <?php echo $this->lang->line('documents'); ?></a></li>
                        <?php
                        } ?>

                        <?php if ($this->rbac->hasPrivilege('student_timeline', 'can_view')) { ?>

                            <li class=""><a href="#timelineh" data-toggle="tab" aria-expanded="true"><i class="fa fa-clock-o"></i> <?php echo $this->lang->line('timeline') ?></a></li>
                        <?php } ?>

                        <!------- Behaviour Report Start-------->
                        <?php
                        if ($this->module_lib->hasModule('behaviour_records')) {
                            if ($this->rbac->hasPrivilege('behaviour_records_assign_incident', 'can_view')) {

                        ?>
                                <li class=""><a href="#incident" data-toggle="tab" aria-expanded="true"><i class="fa fa-smile-o"></i> <?php echo $this->lang->line('student_behaviour'); ?></a></li>
                        <?php

                            }
                        }
                        ?>
                        <!------- Behaviour Report End-------->

                        
                        <?php if ($this->rbac->hasPrivilege('student_call_log', 'can_view')) { ?>
                            <li class=""><a href="#call_log" data-toggle="tab" aria-expanded="true"><i class="fa fa-phone"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Call Log'); ?></a></li>
                            <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('absentee_followup', 'can_view')) { ?>
                            <li class=""><a href="#absentee_followup" data-toggle="tab" aria-expanded="true"><i class="fa fa-user-times"></i> <?php echo ($this->lang->line('absentee_followup') ? $this->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></a></li>
                        <?php } ?>
                        <li class=""><a href="#ptm_history_tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-comments-o"></i> PTM History</a></li>
                    </ul>
                </div>

                    <div class="tab-content">
                        <div class="tab-pane" id="ptm_history_tab">
                            <div class="sp2-card">
                                <div class="sp2-section-title"><i class="fa fa-comments-o"></i> Parent Teacher Meeting (PTM) History</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>PTM Event</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Attendee</th>
                                                <th>Arrival / Departure</th>
                                                <th>Discussion Points</th>
                                                <th>Parent Remarks</th>
                                                <th>Teacher Remarks</th>
                                                <th>Concerns</th>
                                                <th>Action Items</th>
                                                <th>Follow-up Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($ptm_history) && !empty($ptm_history)) { 
                                                foreach ($ptm_history as $ptm_item) { 
                                                    $concerns = [];
                                                    if (!empty($ptm_item['concerns_academics'])) $concerns[] = "Academics";
                                                    if (!empty($ptm_item['concerns_attendance'])) $concerns[] = "Attendance";
                                                    if (!empty($ptm_item['concerns_behavior'])) $concerns[] = "Behavior";
                                                    if (!empty($ptm_item['concerns_discipline'])) $concerns[] = "Discipline";
                                                ?>
                                                <tr>
                                                    <td><strong><?php echo $ptm_item['ptm_title']; ?></strong></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($ptm_item['ptm_date'])); ?></td>
                                                    <td>
                                                        <?php if ($ptm_item['status'] == 'present'): ?>
                                                            <span class="label label-success">Present</span>
                                                        <?php else: ?>
                                                            <span class="label label-danger">Absent</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo ucfirst($ptm_item['attendee_type'] ?: '-'); ?></td>
                                                    <td><?php echo (!empty($ptm_item['arrival_time']) ? $ptm_item['arrival_time'] : '--:--') . ' - ' . (!empty($ptm_item['departure_time']) ? $ptm_item['departure_time'] : '--:--'); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($ptm_item['discussion_points'] ?: '-')); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($ptm_item['parent_remarks'] ?: '-')); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($ptm_item['teacher_remarks'] ?: '-')); ?></td>
                                                    <td><?php echo !empty($concerns) ? implode(', ', $concerns) : '-'; ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($ptm_item['action_items'] ?: '-')); ?></td>
                                                    <td>
                                                        <?php if (!empty($ptm_item['followup_required']) && $ptm_item['followup_required'] == 1): ?>
                                                            <span class="label label-warning">Required</span><br>
                                                            <?php if (!empty($ptm_item['assigned_staff_name'])): ?>
                                                                <small><strong>Assigned:</strong> <?php echo $ptm_item['assigned_staff_name'] . ' ' . $ptm_item['assigned_staff_surname']; ?></small><br>
                                                            <?php endif; ?>
                                                            <?php if (!empty($ptm_item['followup_date'])): ?>
                                                                <small><strong>Date:</strong> <?php echo date('d-m-Y', strtotime($ptm_item['followup_date'])); ?></small>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">No</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php } } else { ?>
                                                <tr><td colspan="11" class="text-center text-muted">No PTM records found for this student.</td></tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                                                <div class="tab-pane" id="absentee_followup">
                            <div class="sp2-card">
                                <div class="sp2-section-title"><i class="fa fa-phone"></i> <?php echo ($this->lang->line('absentee_followup') ? $this->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('date'); ?></th>
                                                <th><?php echo ($this->lang->line('followup_status') ? $this->lang->line('followup_status') : 'Status'); ?></th>
                                                <th><?php echo $this->lang->line('remark'); ?></th>
                                                <th><?php echo $this->lang->line('created_by'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset($absentee_followups) && !empty($absentee_followups)) { 
                                                foreach ($absentee_followups as $log) { ?>
                                                <tr>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($log['date'])); ?></td>
                                                    <td><?php echo $log['followup_status']; ?></td>
                                                    <td><?php echo $log['remark']; ?></td>
                                                    <td><?php echo $log['name'] . ' ' . $log['surname'] . ' (' . $log['employee_id'] . ')'; ?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

<div class="tab-pane" id="call_log">
                            <div class="sp2-card">
                                <div class="sp2-section-title"><i class="fa fa-phone"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Call Log'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('call_type'); ?></th>
                                                <th><?php echo $this->lang->line('purpose'); ?></th>
                                                <th><?php echo ($this->lang->line('contact_person') ? $this->lang->line('contact_person') : 'Contact Person'); ?></th>
                                                <th><?php echo $this->lang->line('phone'); ?></th>
                                                <th><?php echo $this->lang->line('status'); ?></th>
                                                <th><?php echo $this->lang->line('date'); ?></th>
                                                <th><?php echo ($this->lang->line('next_follow_up_date') ? $this->lang->line('next_follow_up_date') : 'Next Follow Up'); ?></th>
                                                <th><?php echo $this->lang->line('note'); ?></th>
                                                <th><?php echo $this->lang->line('created_by'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $CI =& get_instance();
                                            $CI->load->model('studentcall_model');
                                            $stu_calls = $CI->studentcall_model->get_calls_by_student($student['student_session_id']);
                                            if (!empty($stu_calls)) {
                                                foreach ($stu_calls as $c) { ?>
                                                    <tr>
                                                        <td><?php echo $c['call_type']; ?></td>
                                                        <td><?php echo $c['purpose_name']; ?></td>
                                                        <td><?php echo $c['contact_person']; ?></td>
                                                        <td><?php echo $c['phone_number']; ?></td>
                                                        <td><?php echo $c['call_status']; ?></td>
                                                        <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($c['date'])); ?></td>
                                                        <td><?php 
                                                            $stu_fws = $CI->studentcall_model->get_followups_by_call($c['id']);
                                                            if(!empty($stu_fws) && isset($stu_fws[0]['due_date']) && $stu_fws[0]['status']=='Pending') {
                                                                echo date($this->customlib->getSchoolDateFormat(), strtotime($stu_fws[0]['due_date']));
                                                            }
                                                        ?></td>
                                                        <td><?php echo $c['notes']; ?></td>
                                                        <td><?php echo $c['staff_name'] . " " . $c['staff_surname']; ?></td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane active" id="activity">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="sp2-card">
                                        <div class="sp2-section-title"><i class="fa fa-user"></i> <?php echo $this->lang->line('personal_details'); ?></div>
                                        <div class="sp2-data-grid">
                                            <?php if ($sch_setting->admission_date) {
                                            ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('admission_date'); ?></div>
    <div class="sp2-data-box-value"><?php
                                                        if (!empty($student['admission_date'])) {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($student['admission_date']))));
                                                        }
                                                        ?></div>
</div>
                                            <?php } ?>
                                            <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('date_of_birth'); ?></div>
    <div class="sp2-data-box-value"><?php
                                                    if (!empty($student['dob']) && $student['dob'] != '0000-00-00') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob']));
                                                    }
                                                    ?></div>
</div>
                                            <?php if ($sch_setting->category) {
                                            ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('category'); ?></div>
    <div class="sp2-data-box-value"><?php
                                                        foreach ($category_list as $value) {
                                                            if ($student['category_id'] == $value['id']) {
                                                                echo $value['category'];
                                                            }
                                                        }
                                                        ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->mobile_no) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('mobile_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['mobileno']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->cast) {
                                            ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('caste'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['cast']; ?></div>
</div>
                                            <?php
                                            }
                                            if ($sch_setting->religion) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('religion'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['religion']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->student_email) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('email'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['email']; ?></div>
</div>
                                            <?php }

                                            ?>
                                            <?php
                                            $cutom_fields_data = get_custom_table_values($student['id'], 'students');
                                            if (!empty($cutom_fields_data)) {
                                                foreach ($cutom_fields_data as $field_key => $field_value) {
                                            ?>
                                                    <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $field_value->name; ?></div>
    <div class="sp2-data-box-value"><?php
                                                            if (is_string($field_value->field_value) && is_array(json_decode($field_value->field_value, true)) && (json_last_error() == JSON_ERROR_NONE)) {
                                                                $field_array = json_decode($field_value->field_value);
                                                                echo "<ul class='student_custom_field'>";
                                                                foreach ($field_array as $each_key => $each_value) {
                                                                    echo "<li>" . $each_value . "</li>";
                                                                }
                                                                echo "</ul>";
                                                            } else {
                                                                $display_field = $field_value->field_value;

                                                                if ($field_value->type == "link") {
                                                                    $display_field = "<a href=" . $field_value->field_value . " target='_blank'>" . $field_value->field_value . "</a>";
                                                                }
                                                                echo $display_field;
                                                            }
                                                            ?></div>
</div>
                                                <?php
                                                }
                                            }

                                            if ($sch_setting->student_note) {
                                                ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('note'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['note']; ?></div>
</div>
                                            <?php
                                            }
                                            ?>
                                        </div>
                            </div>
                            <div class="sp2-card">
                                <div class="sp2-section-title"><i class="fa fa-map-marker"></i> <?php echo $this->lang->line('address'); ?></div>
                                <div class="sp2-data-grid">
                                            <?php if ($sch_setting->current_address) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('current_address'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['current_address']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->permanent_address) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('permanent_address'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['permanent_address']; ?></div>
</div>
                                            <?php } ?>
                                        </div>
                            </div>
<div class="sp2-card">
                                <div class="sp2-section-title"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('miscellaneous_details'); ?></div>
                                <div class="sp2-data-grid">
                                            <?php if ($sch_setting->is_blood_group) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('blood_group'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['blood_group']; ?></div>
</div>
                            
                                            <?php }
                                            if ($sch_setting->is_student_house) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('house'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['house_name']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->student_height) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('height'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['height']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->student_weight) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('weight'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['weight']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->measurement_date) {
                                            ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('measurement_date'); ?></div>
    <div class="sp2-data-box-value"><?php
                                                        if (!empty($student['measurement_date']) && $student['measurement_date'] != '0000-00-00') {
                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['measurement_date']));
                                                        }
                                                        ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->previous_school_details) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('previous_school_details'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['previous_school']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->national_identification_no) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('national_identification_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['adhar_no']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->local_identification_no) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('local_identification_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['samagra_id']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->bank_account_no) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('bank_account_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['bank_account_no']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->ifsc_code) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('bank_name'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['bank_name']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->ifsc_code) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('ifsc_code'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['ifsc_code']; ?></div>
</div>
                                            <?php } ?>
                                         </div>
                            </div>
                            </div><!-- /.col-md-6 -->


                            <div class="col-md-6 col-sm-12">
                            <div class="sp2-card">
                                <?php if (($sch_setting->father_name) || ($sch_setting->father_phone) || ($sch_setting->father_occupation) || ($sch_setting->father_pic) || ($sch_setting->mother_name) || ($sch_setting->mother_phone) || ($sch_setting->mother_occupation) || ($sch_setting->mother_pic) || ($sch_setting->guardian_name) || ($sch_setting->guardian_occupation) || ($sch_setting->guardian_relation) || ($sch_setting->guardian_phone) || ($sch_setting->guardian_email) || ($sch_setting->guardian_pic) || ($sch_setting->guardian_address)) {
                                ?>
                                    <div class="sp2-section-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('parent_guardian_detail'); ?> </div>                                        <div class="sp2-parent-list">
                                            <?php if ($sch_setting->father_name || $sch_setting->father_phone || $sch_setting->father_occupation) { ?>
                                            <div class="sp2-parent-item">
                                                <img class="sp2-parent-avatar" src="<?php
                                                    if (!empty($student["father_pic"])) {
                                                        echo $this->media_storage->getImageURL($student["father_pic"]);
                                                    } else {
                                                        echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                    }
                                                ?>">
                                                <div class="sp2-parent-details">
                                                    <div class="sp2-parent-title"><?php echo $this->lang->line('father'); ?></div>
                                                    <?php if ($sch_setting->father_name) { ?>
                                                    <div class="sp2-parent-row"><span class="sp2-parent-label"><?php echo $this->lang->line('name'); ?></span><span class="sp2-parent-val"><?php echo $student['father_name']; ?></span></div>
                                                    <?php } ?>
                                                    <?php if ($sch_setting->father_phone) { ?>
                                                    <div class="sp2-parent-row"><span class="sp2-parent-label"><?php echo $this->lang->line('phone'); ?></span><span class="sp2-parent-val"><?php echo $student['father_phone']; ?></span></div>
                                                    <?php } ?>
                                                    <?php if ($sch_setting->father_occupation) { ?>
                                                    <div class="sp2-parent-row"><span class="sp2-parent-label"><?php echo $this->lang->line('occupation'); ?></span><span class="sp2-parent-val"><?php echo $student['father_occupation']; ?></span></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            
                                            <?php if ($sch_setting->mother_name || $sch_setting->mother_phone || $sch_setting->mother_occupation) { ?>
                                            <div class="sp2-parent-item">
                                                <img class="sp2-parent-avatar" src="<?php
                                                    if (!empty($student["mother_pic"])) {
                                                        echo $this->media_storage->getImageURL($student["mother_pic"]);
                                                    } else {
                                                        echo $this->media_storage->getImageURL("uploads/student_images/no_image.png");
                                                    }
                                                ?>">
                                                <div class="sp2-parent-details">
                                                    <div class="sp2-parent-title"><?php echo $this->lang->line('mother'); ?></div>
                                                    <?php if ($sch_setting->mother_name) { ?>
                                                    <div class="sp2-parent-row"><span class="sp2-parent-label"><?php echo $this->lang->line('name'); ?></span><span class="sp2-parent-val"><?php echo $student['mother_name']; ?></span></div>
                                                    <?php } ?>
                                                    <?php if ($sch_setting->mother_phone) { ?>
                                                    <div class="sp2-parent-row"><span class="sp2-parent-label"><?php echo $this->lang->line('phone'); ?></span><span class="sp2-parent-val"><?php echo $student['mother_phone']; ?></span></div>
                                                    <?php } ?>
                                                    <?php if ($sch_setting->mother_occupation) { ?>
                                                    <div class="sp2-parent-row"><span class="sp2-parent-label"><?php echo $this->lang->line('occupation'); ?></span><span class="sp2-parent-val"><?php echo $student['mother_occupation']; ?></span></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>


                                          </div>
                                <?php } ?>
                            </div>
                            <?php if ($sch_setting->route_list) {
                            ?>
                                <?php
                                if ($this->module_lib->hasActive('transport')) {

                                    if ($student['pickup_point_name'] != '') {
                                ?>
                                        <div class="sp2-card">
                                            <div class="sp2-section-title"><i class="fa fa-bus"></i> <?php echo $this->lang->line('route_details'); ?></div>
                                            <div class="sp2-data-grid">
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('pick_up_point'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['pickup_point_name']; ?></div>
</div>
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('route'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['route_title']; ?></div>
</div>
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('vehicle_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['vehicle_no']; ?></div>
</div>
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('driver_name'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['driver_name']; ?></div>
</div>
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('driver_contact'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['driver_contact']; ?></div>
</div>
                                                    </div>
                                        </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                            <?php if ($sch_setting->hostel_id) {
                                if ($this->module_lib->hasActive('hostel')) {

                                    if ($student['hostel_room_id'] != 0) {
                            ?>
                                        <div class="sp2-card">
                                            <div class="sp2-section-title"><i class="fa fa-building"></i> <?php echo $this->lang->line('hostel_details'); ?></div>
                                            <div class="sp2-data-grid">
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('hostel'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['hostel_name']; ?></div>
</div>
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('room_no'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['room_no']; ?></div>
</div>
                                                        <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('room_type'); ?></div>
    <div class="sp2-data-box-value"><?php echo $student['room_type']; ?></div>
</div>
                                                    </div>
                                        </div>
                            <?php
                                    }
                                }
                            }
                            ?>
                            </div><!-- /.col-md-6 -->
                            </div><!-- /.row -->
                        </div>

                        <?php if ($this->module_lib->hasModule('behaviour_records')) {
                        ?>
                            <!------- Behaviour Report Start-------->
                            <div class="tab-pane" id="incident">
                                <div class="no-border table-responsive overflow-visible-lg">
                                    <div class="download_label"><?php echo $this->lang->line('student_behaviour'); ?></div>
                                    <table class="table table-striped table-bordered table-hover example">

                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('title'); ?></th>
                                                <th><?php echo $this->lang->line('point'); ?></th>
                                                <th><?php echo $this->lang->line('date'); ?></th>
                                                <th><?php echo $this->lang->line('description'); ?></th>
                                                <th><?php echo $this->lang->line('assign_by'); ?></th>
                                                <th class="noExport"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($assignstudent)) {
                                            ?>

                                                <?php
                                            } else {

                                                foreach ($assignstudent as $assignstudent_value) {
                                                    $staff_id = '';
                                                    if ($assignstudent_value['staff_employee_id'] != "") {
                                                        $staff_id = ' (' . $assignstudent_value['staff_employee_id'] . ')';
                                                    }

                                                    $pointclass = '';
                                                    if ($assignstudent_value['point'] < 0) {
                                                        $pointclass = 'danger';
                                                    }
                                                ?>
                                                    <tr class="<?php echo $pointclass; ?>">
                                                        <td><?php echo $assignstudent_value['title'] ?></td>
                                                        <td><?php echo $assignstudent_value['point'] ?></td>
                                                        <td><?php echo $this->customlib->dateformat($assignstudent_value['created_at']) ?></td>
                                                        <td width="40%"> <?php echo $assignstudent_value['description'] ?></td>
                                                        <td> <?php

                                                                if ($superadmin_visible == 'disabled') {

                                                                    if ($staffrole->id == 7) {
                                                                        echo $assignstudent_value['staff_name'] . ' ' . $assignstudent_value['staff_surname'] . $staff_id;
                                                                    } elseif ($assignstudent_value['role_id'] != 7) {
                                                                        echo $assignstudent_value['staff_name'] . ' ' . $assignstudent_value['staff_surname'] . $staff_id;
                                                                    }
                                                                } else {
                                                                    echo $assignstudent_value['staff_name'] . ' ' . $assignstudent_value['staff_surname'] . $staff_id;
                                                                }
                                                                ?></td>


                                                        <td>
                                                            <a class="btn btn-default  btn-xs comments relative overflow-inherit" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo $this->lang->line('comment'); ?>" data-record-id="<?php echo $assignstudent_value['id'] ?>">
                                                                <?php if ($assignstudent_value['totalcomments']['totalcomments'] != '0') { ?><span class="comment-badges"><?php echo $assignstudent_value['totalcomments']['totalcomments']; ?></span><?php } ?><i class="fa fa-comment"></i> </a>
                                                        </td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!------- Behaviour Report End-------->
                        <?php } ?>


                        <!------- CBSE Exam Start-------->
                        <?php
                        if ($this->module_lib->hasModule('cbseexam')) {  ?>
                            <div class="tab-pane" id="cbseexam">
                                <div class="dt-buttons btn-group pull-right miusDM42">
                                    <a class="btn btn-default btn-xs dt-button no_print border0" id="print" data-toggle="tooltip" data-placement="bottom" title="Print" onclick="printDivCbse()"><i class="fa fa-print"></i></a>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">

                                        <?php
                                        if (!empty($exams)) {
                                            foreach ($exams as $exam_key => $exam_value) {

                                                $total_marks = 0;
                                                $total_max_marks = 0;
                                        ?>

                                                <div class="sp2-card" style="padding: 0; overflow: hidden; margin-bottom: 24px;">
                                                    <div class="sp2-section-title" style="padding: 16px 20px; border-bottom: 1px solid #eaeaea; background: #fdfdfd; margin: 0; display: flex; align-items: center; gap: 8px;">
                                                        <i class="fa fa-graduation-cap" style="color: <?php echo $theme_color; ?>; font-size: 16px;"></i> <?php echo  $exam_value->name; ?>
                                                    </div>
                                                    <div class="table-responsive" style="padding: 20px;">
                                                        <?php
                                                        if (!empty($exam_value->subjects)) {
                                                        ?>
                                                            <table class="table table-bordered sp2-exam-table mb0">
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="bolds" style="background: #f9fafb; color: #4b5563; font-size: 12px; text-transform: uppercase;">
                                                                            <?php echo $this->lang->line('subject'); ?>
                                                                        </td>
                                                                        <?php

                                                                        foreach ($exam_value->exam_assessments as $exam_assessment_key => $exam_assessment_value) {
                                                                        ?>
                                                                            <td class="text-center bolds">

                                                                                <?php $assessment_code = ($exam_assessment_value->code == "") ? "" : " (" . $exam_assessment_value->code . ")"; ?>
                                                                                <?php echo $exam_assessment_value->name . $assessment_code; ?>


                                                                                <br />
                                                                                (<?php echo $this->lang->line('max'); ?> <?php echo $exam_assessment_value->maximum_marks; ?>)
                                                                            </td>
                                                                        <?php
                                                                        }

                                                                        ?>
                                                                        <td class="bolds">
                                                                            <?php echo $this->lang->line('total'); ?>
                                                                        </td>
                                                                    </tr>

                                                                    <?php
                                                                    foreach ($exam_value->subjects as $subject_key => $subject_value) {
                                                                        $subject_total = 0;
                                                                    ?>
                                                                        <tr>
                                                                            <td>
                                                                                <?php $subject_code = ($subject_value->subject_code == "") ? "" : " (" . $subject_value->subject_code . ")"; ?>
                                                                                <?php echo $subject_value->subject_name . $subject_code; ?>
                                                                            </td>
                                                                            <?php
                                                                            foreach ($exam_value->exam_assessments as $exam_assessment_key => $exam_assessment_value) {

                                                                            ?>
                                                                                <td class="text-center">
                                                                                    <?php

                                                                                    $assessment_exists =  find_subject_assessment_exists($exam_value->exam_subject_assessments, $subject_value->id, $exam_assessment_value->id);

                                                                                    if ($assessment_exists) {
                                                                                        $assessment_array = findAssessmentValue($subject_value->subject_id, $exam_assessment_value->id, $exam_value);
                                                                                        echo ($assessment_array['is_absent']) ? $this->lang->line('abs') : $assessment_array['marks'];
                                                                                        if ($assessment_array['marks'] == "N/A") {
                                                                                            $assessment_array['marks'] = 0;
                                                                                        }
                                                                                        $subject_total += $assessment_array['marks'];
                                                                                        $total_max_marks += $assessment_array['maximum_marks'];
                                                                                        $total_marks += $assessment_array['marks'];
                                                                                    } else {
                                                                                        echo "<b>xx</b>";
                                                                                    }

                                                                                    ?>
                                                                                </td>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                            <td class="bolds">
                                                                                <?php echo  two_digit_float($subject_total); ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php
                                                                    }
                                                                    ?>

                                                                </tbody>

                                                            </table>
                                                        <?php

                                                        }
                                                        if ($total_max_marks > 0) {
                                                            $exam_percentage = getPercent($total_max_marks, $total_marks);
                                                        } else {
                                                            $exam_percentage = 0;
                                                        }
                                                        ?>

                                                        <table class="table table-bordered sp2-exam-summary-table mb0" style="margin-top: 16px; border: 2px solid #eaeaea;">
                                                            <tr>
                                                                <td class="bolds" style="background: #fdfdfd;"><?php echo $this->lang->line('total_marks'); ?> <div style="font-size: 16px; color: <?php echo $theme_color; ?>;"><?php echo $total_marks . " / " . $total_max_marks; ?></div></td>
                                                                <td class="bolds" style="background: #fdfdfd;"><?php echo $this->lang->line('percentage'); ?> (%) <div style="font-size: 16px; color: <?php echo $theme_color; ?>;"><?php echo $exam_percentage; ?>%</div></td>
                                                                <td class="bolds" style="background: #fdfdfd;"><?php echo $this->lang->line('grade'); ?> <div style="font-size: 16px; color: #10b981;"><?php echo getGrade($exam_value->grades, $exam_percentage); ?></div></td>
                                                                <td class="bolds" style="background: #fdfdfd;"><?php echo $this->lang->line('rank'); ?> <div style="font-size: 16px; color: #f59e0b;"><?php echo $exam_value->rank; ?></div></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>

                                            <?php

                                            }
                                        } else {
                                            ?>
                                            <div class="alert alert-info">
                                                <?php echo $this->lang->line('no_exam_assigned'); ?>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>

                            </div>
                        <?php
                        }
                        ?>
                        <!------- CBSE Exam End--------->


                        <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) { ?>
                        <div class="tab-pane" id="fee">
                           
                            <?php
                            if (empty($student_due_fee) && empty($student_discount_fee) && empty($transport_fees)) {
                            ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->lang->line('no_record_found'); ?>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="sp2-invoice-wrapper">
                                    <div class="sp2-invoice-table">
                                        <div class="sp2-invoice-thead">
                                            <div class="sp2-invoice-row header-row">
                                                <div class="sp2-invoice-col"><?php echo $this->lang->line('fees'); ?></div>
                                                <!-- <div class="sp2-invoice-col"><?php //echo $this->lang->line('fees_group'); ?></div> -->
                                                <!-- <div class="sp2-invoice-col"><?php //echo $this->lang->line('fees_code'); ?></div> -->
                                                <div class="sp2-invoice-col text-left"><?php echo $this->lang->line('due_date'); ?></div>
                                                <div class="sp2-invoice-col text-left"><?php echo $this->lang->line('status'); ?></div>
                                                <div class="sp2-invoice-col text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></div>
                                                <div class="sp2-invoice-col text-left"><?php echo $this->lang->line('payment_id'); ?></div>
                                                <div class="sp2-invoice-col text-left"><?php echo $this->lang->line('mode'); ?></div>
                                                <div class="sp2-invoice-col text-left"><?php echo $this->lang->line('date'); ?></div>
                                                <div class="sp2-invoice-col text-right"><?php echo $this->lang->line('discount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></div>
                                                <div class="sp2-invoice-col text-right"><?php echo $this->lang->line('fine'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></div>
                                                <div class="sp2-invoice-col text-right"><?php echo $this->lang->line('paid'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></div>
                                                <div class="sp2-invoice-col text-right"><?php echo $this->lang->line('balance'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></div>
                                            </div>
                                        </div>
                                        <div class="sp2-invoice-tbody">
                                            <?php
                                            $total_amount           = 0;
                                            $total_deposite_amount  = 0;
                                            $total_fine_amount      = 0;
                                            $total_discount_amount  = 0;
                                            $total_balance_amount   = 0;
                                            $alot_fee_discount      = 0;
                                            $total_fees_fine_amount = 0; 

                                            foreach ($student_due_fee as $key => $fee) {

                                                foreach ($fee->fees as $fee_key => $fee_value) {
                                                    $fee_paid          = 0;
                                                    $fee_discount      = 0;
                                                    $fee_fine          = 0;
                                                    $alot_fee_discount = 0;
                                                    $fees_fine_amount = 0;

                                                    if (!empty($fee_value->amount_detail)) {
                                                        $fee_deposits = json_decode(($fee_value->amount_detail));

                                                        foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                            $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                            $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                            $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                        }
                                                    }
                                                    $total_amount           = $total_amount + $fee_value->amount;
                                                    $total_discount_amount  = $total_discount_amount + $fee_discount;
                                                    $total_deposite_amount  = $total_deposite_amount + $fee_paid;
                                                    $total_fine_amount      = $total_fine_amount + $fee_fine;
                                                    $feetype_balance        = $fee_value->amount - ($fee_paid + $fee_discount);
													if($feetype_balance<0){
														 $feetype_balance =0;
													}
                                                    $total_balance_amount   = $total_balance_amount + $feetype_balance;
													
													if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
            
                                                        //$total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                                                    }
		
		
                                            ?>
                                                    <?php if($fee_value->due_date){
                                                    if ($feetype_balance > 0 && strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                    ?>
                                                        <div class="sp2-invoice-row sp2-invoice-main-row danger font12">
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <div class="sp2-invoice-row sp2-invoice-main-row dark-gray">
                                                        <?php
                                                    }}
                                                        ?>
                                                    

                                                        <div class="sp2-invoice-col" style="display: flex; align-items: center; gap: 8px;">
                                                            <?php if (!empty($fee_value->amount_detail)) { ?>
                                                                <a href="javascript:void(0)" onclick="$('.fee_deposits_<?php echo $fee_value->id; ?>').toggleClass('open'); $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');" style="color: #6b7280; font-size: 12px; width: 16px; text-align: center;"><i class="fa fa-chevron-right"></i></a>
                                                            <?php } else { ?>
                                                                <span style="width: 16px;"></span>
                                                            <?php } ?>
                                                            <span>
                                                            <?php
                                                            if ($fee_value->is_system) {
                                                                echo $this->lang->line($fee_value->type) . " (" . $this->lang->line($fee_value->code) . ")";
                                                            } else {
                                                                echo $fee_value->type . " (" . $fee_value->code . ")";
                                                            }
                                                            ?>
                                                            </span>
                                                        </div>



                                                        <div class="sp2-invoice-col text-left">
                                                            <?php
                                                            if ($fee_value->due_date == "0000-00-00") {
                                                            } else {
                                                                if ($fee_value->due_date) {
                                                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_value->due_date));
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="sp2-invoice-col text-left">
                                                            <?php
                                                            if ($feetype_balance == 0) {
                                                            ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                                } else if (!empty($fee_value->amount_detail)) {
                                                                                                    ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                    } else {
                                                                                                        ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                    }
                                                                                                    ?>
                                                        </div>
                                                        <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_value->amount);
                                                                                    if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != null) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d')))) {
																										
                                                                // get cumulative fine amount as delay days 
                                                                if($fee_value->fine_type=='cumulative'){
                                                                    $date1=date_create("$fee_value->due_date");
                                                                    $date2=date_create(date('Y-m-d'));
                                                                    $diff=date_diff($date1,$date2);
                                                                    $due_days= $diff->format("%a");;
                                                                    
                                                                    if($this->customlib->get_cumulative_fine_amount($fee_value->fee_groups_feetype_id,$due_days)){
                                                                        $due_fine_amount=$this->customlib->get_cumulative_fine_amount($fee_value->fee_groups_feetype_id,$due_days);
                                                                    }else{
                                                                        $due_fine_amount=0;
                                                                    }
                                                                    $fees_fine_amount       = $due_fine_amount;
                                                                    $total_fees_fine_amount = $total_fees_fine_amount + $due_fine_amount;
                                                    
                                                                }else if($fee_value->fine_type=='fix' || $fee_value->fine_type=='percentage'){
                                                                    $fees_fine_amount       = $fee_value->fine_amount;
                                                                    $total_fees_fine_amount = $total_fees_fine_amount + $fee_value->fine_amount;
                                                                }
                                                                // get cumulative fine amount as delay days		
																						 
																						
                                                                                    ?>

                                                                <span data-toggle="popover" class="text text-danger detail_popover"><?php 
                                                                echo " + " . amountFormat($fees_fine_amount); ?></span>
                                                                <div class="fee_detail_popover" style="display: none">
                                                                    <?php
                                                                                        if ($fees_fine_amount != "") {
                                                                    ?>
                                                                        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>
                                                                    <?php
                                                                                        }
                                                                    ?>
                                                                </div>
                                                            <?php
                                                                                    }
                                                            ?>
                                                        </div>
                                                        <div class="sp2-invoice-col text-left"></div>
                                                        <div class="sp2-invoice-col text-left"></div>
                                                        <div class="sp2-invoice-col text-left"></div>
                                                        <div class="sp2-invoice-col text-right"><?php
                                                                                    echo amountFormat($fee_discount);
                                                                                    ?></div>
                                                        <div class="sp2-invoice-col text-right"><?php
                                                                                    echo amountFormat($fee_fine);
                                                                                    ?></div>
                                                        <div class="sp2-invoice-col text-right"><?php
                                                                                    echo amountFormat($fee_paid);
                                                                                    ?></div>
                                                        <div class="sp2-invoice-col text-right"><?php
                                                                                    $display_none = "ss-none";
                                                                                    if ($feetype_balance > 0) {
                                                                                        $display_none = "";
                                                                                        echo amountFormat($feetype_balance);
                                                                                    }
                                                                                    ?>
                                                        </div>
                                                        </div>
                                                        <?php
                                                        if (!empty($fee_value->amount_detail)) {
                                                            echo '<div class="fee-deposits-wrapper fee_deposits_' . $fee_value->id . '">';

                                                            $fee_deposits = json_decode(($fee_value->amount_detail));

                                                            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                        ?>
                                                                <div class="sp2-invoice-row sp2-invoice-sub-row white-td">
                                                                    <!-- <div class="sp2-invoice-col text-left"></div> -->
                                                                    <div class="sp2-invoice-col text-left"></div>
                                                                    <div class="sp2-invoice-col text-left"></div>
                                                                    <div class="sp2-invoice-col text-left"></div>
                                                                    <div class="sp2-invoice-col text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></div>
                                                                    <div class="sp2-invoice-col text-left">

                                                                        <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                        <div class="fee_detail_popover" style="display: none">
                                                                            <?php
                                                                            if ($fee_deposits_value->description == "") {
                                                                            ?>
                                                                                <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                            <?php
                                                                            } else {
                                                                            ?>
                                                                                <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="sp2-invoice-col text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></div>
                                                                    <div class="sp2-invoice-col text-center">
                                                                        <?php 
																			if (($fee_deposits_value->date != "0000-00-00" && $fee_deposits_value->date != null)) {
																				echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); 
																			}
																		?>
                                                                    </div>
                                                                    <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></div>
                                                                    <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></div>
                                                                    <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></div>
                                                                    <div class="sp2-invoice-col"></div>
                                                                </div>
                                                        <?php
                                                            }
                                                            echo '</div>';
                                                        }
                                                        ?>
                                                <?php
                                                }
                                            }
                                                ?>

                                                <?php

                                                if (!empty($transport_fees)) {
                                                    foreach ($transport_fees as $transport_fee_key => $transport_fee_value) {

                                                        $fee_paid         = 0;
                                                        $fee_discount     = 0;
                                                        $fee_fine         = 0;
                                                        $fees_fine_amount = 0;
                                                        $feetype_balance  = 0;

                                                        if (!empty($transport_fee_value->amount_detail)) {
                                                            $fee_deposits = json_decode(($transport_fee_value->amount_detail));

                                                            foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                                $fee_paid     = $fee_paid + $fee_deposits_value->amount;
                                                                $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                                                $fee_fine     = $fee_fine + $fee_deposits_value->amount_fine;
                                                            }
                                                        }

                                                        $feetype_balance = $transport_fee_value->fees - ($fee_paid + $fee_discount);

                                                        if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                            $fees_fine_amount       = is_null($transport_fee_value->fine_percentage) ? $transport_fee_value->fine_amount : percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                                                            $total_fees_fine_amount = $total_fees_fine_amount + $fees_fine_amount;
                                                        }

                                                        $total_amount += $transport_fee_value->fees;
                                                        $total_discount_amount += $fee_discount;
                                                        $total_deposite_amount += $fee_paid;
                                                        $total_fine_amount += $fee_fine;
                                                        $total_balance_amount += $feetype_balance;

                                                        if (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d'))) {
                                                ?>
                                                            <div class="sp2-invoice-row sp2-invoice-main-row danger font12">
                                                            <?php
                                                        } else {
                                                            ?>
                                                            <div class="sp2-invoice-row sp2-invoice-main-row dark-gray">
                                                            <?php
                                                        }
                                                            ?>
                                                          
                                                            <div class="sp2-invoice-col" align="left" style="display: flex; align-items: center; gap: 8px;">
                                                                <?php if (!empty($transport_fee_value->amount_detail)) { ?>
                                                                    <a href="javascript:void(0)" onclick="$('.fee_deposits_tr_<?php echo $transport_fee_value->id; ?>').toggleClass('open'); $(this).find('i').toggleClass('fa-chevron-right fa-chevron-down');" style="color: #6b7280; font-size: 12px; width: 16px; text-align: center;"><i class="fa fa-chevron-right"></i></a>
                                                                <?php } else { ?>
                                                                    <span style="width: 16px;"></span>
                                                                <?php } ?>
                                                                <span><?php echo $this->lang->line('transport_fees')." (".$this->lang->line(strtolower($transport_fee_value->month)).")"; ?></span>
                                                            </div>

                                                            <div class="sp2-invoice-col" align="left" class="text text-left">
                                                                <?php echo $this->customlib->dateformat($transport_fee_value->due_date); ?> </div>
                                                            <div class="sp2-invoice-col" align="left" class="text text-left width85">
                                                                <?php
                                                                if ($feetype_balance == 0) {
                                                                ?><span class="label label-success"><?php echo $this->lang->line('paid'); ?></span><?php
                                                                                                } else if (!empty($transport_fee_value->amount_detail)) {
                                                                                                    ?><span class="label label-warning"><?php echo $this->lang->line('partial'); ?></span><?php
                                                                                                    } else {
                                                                                                        ?><span class="label label-danger"><?php echo $this->lang->line('unpaid'); ?></span><?php
                                                                                                    }
                                                                                                    ?>
                                                            </div>
                                                            <div class="sp2-invoice-col text-right"><?php echo amountFormat($transport_fee_value->fees);

                                                                                        if (($transport_fee_value->due_date != "0000-00-00" && $transport_fee_value->due_date != null) && (strtotime($transport_fee_value->due_date) < strtotime(date('Y-m-d')))) {
                                                                                            $tr_fine_amount = $transport_fee_value->fine_amount;
                                                                                            if ($transport_fee_value->fine_type != "" && $transport_fee_value->fine_type == "percentage") {
                                                                                                $tr_fine_amount = percentageAmount($transport_fee_value->fees, $transport_fee_value->fine_percentage);
                                                                                            }
                                                                                        ?>
                                                                    <span data-toggle="popover" class="text text-danger detail_popover"><?php echo " + " . amountFormat($tr_fine_amount); ?></span>
                                                                    <div class="fee_detail_popover" style="display: none">
                                                                        <p class="text text-danger"><?php echo $this->lang->line('fine'); ?></p>

                                                                    </div>
                                                                <?php
                                                                                        }
                                                                ?>
                                                            </div>
                                                            <div class="sp2-invoice-col text-left"></div>
                                                            <div class="sp2-invoice-col text-left"></div>
                                                            <div class="sp2-invoice-col text-left"></div>
                                                            <div class="sp2-invoice-col text-right"><?php
                                                                                        echo amountFormat($fee_discount);
                                                                                        ?></div>
                                                            <div class="sp2-invoice-col text-right"><?php
                                                                                        echo amountFormat($fee_fine);
                                                                                        ?></div>
                                                            <div class="sp2-invoice-col text-right"><?php
                                                                                        echo amountFormat($fee_paid);
                                                                                        ?></div>
                                                            <div class="sp2-invoice-col text-right"><?php
                                                                                        $display_none = "ss-none";
                                                                                        if ($feetype_balance > 0) {
                                                                                            $display_none = "";

                                                                                            echo amountFormat($feetype_balance);
                                                                                        }
                                                                                        ?>
                                                            </div>
                                                            </div>
                                                            <?php
                                                            if (!empty($transport_fee_value->amount_detail)) {
                                                                echo '<div class="fee-deposits-wrapper fee_deposits_tr_' . $transport_fee_value->id . '">';

                                                                $fee_deposits = json_decode(($transport_fee_value->amount_detail));

                                                                foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                                            ?>
                                                                    <div class="sp2-invoice-row sp2-invoice-sub-row white-td">
                                                                        <!-- <div class="sp2-invoice-col" align="left"></div> -->
                                                                        <div class="sp2-invoice-col" align="left"></div>
                                                                        <div class="sp2-invoice-col" align="left"></div>
                                                                        <div class="sp2-invoice-col" align="left"></div>
                                                                        <div class="sp2-invoice-col text-right"><img src="<?php echo base_url(); ?>backend/images/table-arrow.png" alt="" /></div>
                                                                        <div class="sp2-invoice-col text-left">

                                                                            <a href="#" data-toggle="popover" class="detail_popover"> <?php echo $transport_fee_value->student_fees_deposite_id . "/" . $fee_deposits_value->inv_no; ?></a>
                                                                            <div class="fee_detail_popover" style="display: none">
                                                                                <?php
                                                                                if ($fee_deposits_value->description == "") {
                                                                                ?>
                                                                                    <p class="text text-danger"><?php echo $this->lang->line('no_description'); ?></p>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <p class="text text-info"><?php echo $fee_deposits_value->description; ?></p>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                        <div class="sp2-invoice-col text-left"><?php echo $this->lang->line(strtolower($fee_deposits_value->payment_mode)); ?></div>
                                                                        <div class="sp2-invoice-col text-left">
                                                                            <?php 
																				if (($fee_deposits_value->date != "0000-00-00" && $fee_deposits_value->date != null)) {
																					echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposits_value->date)); 
																				}
																			?>
                                                                        </div>
                                                                        <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_deposits_value->amount_discount); ?></div>
                                                                        <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_deposits_value->amount_fine); ?></div>
                                                                        <div class="sp2-invoice-col text-right"><?php echo amountFormat($fee_deposits_value->amount); ?></div>
                                                                        <div class="sp2-invoice-col"></div>
                                                                    </div>
                                                            <?php
                                                                }
                                                                echo '</div>';
                                                            }
                                                            ?>

                                                    <?php
                                                    }
                                                }
                                                ?>
                                                
                                                <div class="sp2-invoice-summary-row" style="display: flex; gap: 15px; justify-content: flex-end; align-items: stretch; margin-top: 10px; padding: 16px; background: #fffaeb; border-radius: 8px; border-left: 4px solid #fbd38d;">
                                                        <div style="background: #fff; padding: 12px 18px; border-radius: 6px; border: 1px solid #eaeaea; display: flex; align-items: center; justify-content: space-between; width: 240px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                                            <span style="font-weight: 600; color: #4b5563; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase;">Total Fee</span>
                                                            <span style="font-weight: 700; color: #111827; font-size: 16px;">
                                                                <?php echo $currency_symbol . amountFormat($total_amount); ?>
                                                                <?php if ($total_fees_fine_amount != "") { echo "<span class='text text-danger'>+" . amountFormat($total_fees_fine_amount) . "</span>"; } ?>
                                                            </span>
                                                        </div>
                                                        <div style="background: #fff; padding: 12px 18px; border-radius: 6px; border: 1px solid #eaeaea; display: flex; align-items: center; justify-content: space-between; width: 240px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                                            <span style="font-weight: 600; color: #4b5563; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase;">Paid</span>
                                                            <span style="font-weight: 700; color: #10b981; font-size: 16px;">
                                                                <?php echo $currency_symbol . amountFormat($total_deposite_amount); ?>
                                                            </span>
                                                        </div>
                                                        <div style="background: #fff; padding: 12px 18px; border-radius: 6px; border: 1px solid #eaeaea; display: flex; align-items: center; justify-content: space-between; width: 240px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                                                            <span style="font-weight: 600; color: #4b5563; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase;">Balance</span>
                                                            <span style="font-weight: 700; color: #ef4444; font-size: 16px;">
                                                                <?php echo $currency_symbol . amountFormat($total_balance_amount - $alot_fee_discount); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                        </div>
                        <?php } ?>
                        <div class="tab-pane" id="documents">
                            <div class="timeline-header no-border">
                                <?php if ($this->session->flashdata('msg') != '') {
                                ?>
                                    <div class="alert alert-success">
                                        <?php
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                        ?>
                                    </div>
                                <?php } ?>
                            <div class="d-xl-flex justify-content-lg-end gap-1">  

                               <?php if ($this->rbac->hasPrivilege('student', 'can_add')) { ?>
                                    <button type="button" data-student-session-id="<?php echo $student['student_session_id'] ?>" class="btn btn-xs btn-primary myTransportFeeBtn mb5"> <i class="fa fa-upload"></i> <?php echo $this->lang->line('upload_documents'); ?></button>
                                <?php } ?>


                                <!-- upload from google drive added -->                          
                                <?php 
                                $userdata = $this->customlib->getUserData(); 
                                if($googledrivepickersetting['is_enable']=="enabled" && $userdata["role_id"] ==7){ ?>
                                <button id="google_drive" onclick="opengoogledrivemodel()" class="btn btn-xs btn-primary mb5"> <i class="fa fa-upload"></i> <?php echo $this->lang->line('upload_through_google_drive'); ?></button>
                                <?php }

                                if($googledrivepickersetting['is_enable']=="enabled" && $googledrivepickersetting['is_staff']=="enabled" && $userdata["role_id"] !=7){ ?>
                                <button id="google_drive" onclick="opengoogledrivemodel()" class="btn btn-xs btn-primary mb5"> <i class="fa fa-upload"></i> <?php echo $this->lang->line('upload_through_google_drive'); ?></button>
                                <?php } ?>     
                                <!-- upload from google drive added -->
                                                               
                            </div>     
                                <div class="table-responsive" style="clear: both;">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $this->lang->line('title'); ?>
                                                </th>
                                                <th>
                                                    <?php echo $this->lang->line('file_name'); ?>
                                                </th>
                                                <th class="mailbox-date text-right">
                                                    <?php echo $this->lang->line('action'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <div class="row">
                                            <tbody>
                                                <?php
                                                if (empty($student_doc)) {
                                                ?>
                                                    <tr>
                                                        <td colspan="5" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                    </tr>
                                                    <?php
                                                } else {
                                                    foreach ($student_doc as $value) {

                                                    ?>
                                                        <tr>
                                                            <td><?php echo $value['title']; ?></td>
                                                            <td><?php echo $this->media_storage->fileview($value['doc']); ?></td>
                                                            <td class="mailbox-date pull-right white-space-nowrap">
                                                                <a href="<?php echo site_url('student/download/' . $value['student_id'] . "/" . $value['id']); ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                                <?php if ($this->rbac->hasPrivilege('student', 'can_delete')) { ?>
                                                                    <a href="<?php echo base_url(); ?>student/doc_delete/<?php echo $value['id'] . "/" . $value['student_id']; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                        <i class="fa fa-remove"></i>
                                                                    </a>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                <?php
                                                    }
                                                }
                                                ?>
												 <tr class="laoder hide">
                                                        <td colspan="3"><center><img src="<?php echo base_url() . '/backend/images/loading.gif' ?>"></center></td>
                                                    </tr>	 
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                            </table>
                        </div>
                        <div class="tab-pane" id="timelineh">
                            <div>
                                <?php if ($this->rbac->hasPrivilege('student_timeline', 'can_add')) { ?>
                                    <button type="button" id="myTimelineButton" class="btn btn-sm btn-primary pull-right"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') ?></button>
                                <?php } ?>
                            </div>
                            <br />
                            <div class="timeline-header no-border">
                                <div id="timeline_list">
                                    <?php
                                    if (empty($timeline_list)) {
                                    ?>
                                        <br />
                                        <div class="alert alert-info"><?php echo $this->lang->line("no_record_found") ?></div>

                                    <?php } else {
                                    ?>
                                        <ul class="timeline timeline-inverse">
                                            <?php
                                            foreach ($timeline_list as $key => $value) {
                                            ?>
                                                <li class="time-label">
                                                    <span class="bg-blue">

                                                        <?php
                                                        if (!empty($value['timeline_date'])) {

                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat(date("Y-m-d", strtotime($value['timeline_date']))));
                                                        }
                                                        ?>
                                                    </span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <?php if ($this->rbac->hasPrivilege('student_timeline', 'can_delete')) { ?>
                                                            <span class="time"><a class="defaults-c text-right" data-toggle="tooltip" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a></span>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('student_timeline', 'can_edit')) { ?>
                                                            <span class="time">
                                                                <a data-toggle="tooltip" class="pull-right edit_timeline defaults-c text-right" data-id="<?php echo $value["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if (!empty($value["document"])) { ?>
                                                            <span class="time"><a class="defaults-c text-right" style="color:#0084B4" data-toggle="tooltip" href="<?php echo base_url() . "admin/timeline/download/" . $value["id"]; ?>" data-original-title="<?php echo $this->lang->line('download'); ?>"><i class="fa fa-download"></i></a></span>
                                                        <?php } ?>
                                                        <h3 class="timeline-header text-aqua text-break"> <?php echo $value['title']; ?> </h3>
                                                        <div class="timeline-body text-break">
                                                            <?php echo $value['description']; ?>

                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <li><i class="fa fa-clock-o bg-blue"></i></li>
                                        <?php } ?>
                                        </ul>
                                </div>
                            </div>
                        </div>
                        <?php

                        if (!$sch_setting->attendence_type) {
                        ?>
                            <div class="tab-pane" id="attendance">
                                <div class="sp2-att-grid">
                                    <div class="sp2-att-box ab-present">
                                        <div class="sp2-att-box-label"><?php echo $this->lang->line('total_present'); ?></div>
                                        <div class="sp2-att-box-val"><?php echo (!empty($countAttendance[1])) ? $countAttendance[1] : "0"; ?></div>
                                    </div>
                                    <div class="sp2-att-box ab-late">
                                        <div class="sp2-att-box-label"><?php echo $this->lang->line('total_late'); ?></div>
                                        <div class="sp2-att-box-val"><?php echo (!empty($countAttendance[3])) ? $countAttendance[3] : "0"; ?></div>
                                    </div>
                                    <div class="sp2-att-box ab-absent">
                                        <div class="sp2-att-box-label"><?php echo $this->lang->line('total_absent'); ?></div>
                                        <div class="sp2-att-box-val"><?php echo (!empty($countAttendance[4])) ? $countAttendance[4] : "0"; ?></div>
                                    </div>
                                    <div class="sp2-att-box ab-halfday">
                                        <div class="sp2-att-box-label"><?php echo $this->lang->line('total_half_day'); ?></div>
                                        <div class="sp2-att-box-val"><?php echo (!empty($countAttendance[6])) ? $countAttendance[6] : "0"; ?></div>
                                    </div>
                                    <div class="sp2-att-box ab-holiday">
                                        <div class="sp2-att-box-label"><?php echo $this->lang->line('total_holiday'); ?></div>
                                        <div class="sp2-att-box-val"><?php echo (!empty($countAttendance[5])) ? $countAttendance[5] : "0"; ?></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="halfday pull-right">
                                            <?php
                                            foreach ($attendencetypeslist as $key_type => $value_type) {
                                            ?>
                                                <b>
                                                    <?php
                                                    $att_type = str_replace(" ", "_", strtolower($value_type['type']));
                                                    echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                    ?>
                                                </b>
                                            <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="download_label"><?php echo $this->lang->line('student_attendance_report'); ?> <?php echo $student["firstname"] . " " . $student["lastname"] . ' (' . $student["admission_no"] . ')'; ?></div>
                                    <div id="ajaxattendance" class="table-responsive">
                                        <table class="table table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th class="dt-body-left dt-head-left">
                                                        <?php echo $this->lang->line('date_month'); ?>
                                                    </th>
                                                    <?php foreach ($monthlist as $monthkey => $monthvalue) {
                                                    ?>
                                                        <th><?php echo $monthvalue; ?></th>
                                                    <?php }
                                                    ?>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php
                                                $j = 1;
                                                for ($i = 1; $i <= 31; $i++) {
                                                    $start_year = date('Y-m-d', strtotime($session_year_start));
                                                    $start_year = date('Y-m', strtotime($start_year));
                                                    $start_year = date('Y-m-d', strtotime($start_year . '-' . $j));

                                                ?>
                                                    <tr>
                                                        <td class="dt-body-left dt-head-left"><?php echo $i; ?></td>
                                                        <?php
                                                        $display = true;
                                                        foreach ($monthlist as $monthkey => $monthvalue) {

                                                        ?>
                                                            <td>
                                                                <?php
                                                                if ($display) {

                                                                    if (array_key_exists($start_year, $resultlist)) {

                                                                        if (!empty($resultlist[$start_year]['key'])) {
                                                                            echo ($resultlist[$start_year]['key']);
                                                                        }
                                                                    }
                                                                }

                                                                $display = true;

                                                                $temp_next_month = date('m', strtotime('+1 month', strtotime($start_year)));

                                                                $keys  = array_keys($monthlist);
                                                                $index = array_search($monthkey, $keys);
                                                                if (count($monthlist) <= $index + 1) {
                                                                } else {
                                                                    $keys[$index + 1];
                                                                    $mm = date('m', strtotime($keys[$index + 1]));
                                                                    if ($mm == $temp_next_month) {
                                                                        $start_year = date('Y-m', strtotime('+1 month', strtotime($start_year)));
                                                                        $start_year = date('Y-m-d', strtotime($start_year . '-' . $j));
                                                                    } else {
                                                                        $display = false;
                                                                    }
                                                                }
                                                                echo "<br/>";
                                                                ?></td>
                                                        <?php

                                                        }
                                                        ?>
                                                    </tr>
                                                <?php
                                                    $j++;
                                                }

                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        <div class="tab-pane" id="exam">

                            <div id="visible">
                                <center>
                                    <h4 class="hide" id="exam_student_name"><?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?> (<?php echo $student["class"]; ?>) </h4>
                                </center>

                                <div class="download_label">
                                    <?php echo $this->lang->line('exam_result'); ?>
                                </div>
                                <?php
                                if (empty($exam_result)) {
                                ?>
                                    <div class="alert alert-danger">
                                        <?php echo $this->lang->line('no_record_found'); ?>
                                    </div>
                                <?php
                                }
                                if (!empty($exam_result)) {
                                ?>
                                    <div class="dt-buttons btn-group pull-right miusDM42">
                                        <a class="btn btn-default btn-xs dt-button no_print border0" id="print" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv()"><i class="fa fa-print"></i></a>
                                    </div>
                                    <?php
                                    foreach ($exam_result as $exam_key => $exam_value) {
                                    ?>
                                        <div class="sp2-card">
                                            <h4 class="pagetitleh">
                                                <?php
                                                echo $exam_value->exam;
                                                ?>
                                            </h4>
                                            <?php
                                            if (!empty($exam_value->exam_result)) {
                                                if ($exam_value->exam_result['exam_connection'] == 0) {
                                                    if (!empty($exam_value->exam_result['result'])) { 
                                                        $exam_quality_points = 0;
                                                        $exam_total_points   = 0;
                                                        $exam_credit_hour    = 0;
                                                        $exam_grand_total    = 0;
                                                        $exam_get_total      = 0;
                                                        $exam_pass_status    = 1;
                                                        $exam_absent_status  = 0;
                                                        $total_exams         = 0;
                                            ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-hover ptt10" id="headerTable">
                                                                <thead>
                                                                    <th><?php echo $this->lang->line('subject'); ?></th>
                                                                    <?php
                                                                    if ($exam_value->exam_type == "gpa") {
                                                                    ?>
                                                                        <th><?php echo $this->lang->line('grade_point'); ?></th>
                                                                        <th><?php echo $this->lang->line('credit_hours'); ?></th>
                                                                        <th><?php echo $this->lang->line('quality_points'); ?></th>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <?php
                                                                    if ($exam_value->exam_type != "gpa") {
                                                                    ?>
                                                                        <th><?php echo $this->lang->line('max_marks'); ?></th>
                                                                        <?php
                                                                        if ($exam_value->exam_type != "average_passing") {

                                                                        ?>
                                                                            <th><?php echo $this->lang->line('min_marks'); ?></th>
                                                                        <?php
                                                                        }
                                                                        ?>
                                                                        <th><?php echo $this->lang->line('marks_obtained'); ?></th>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <?php
                                                                    if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                                    ?>
                                                                        <th><?php echo $this->lang->line('grade'); ?> </th>
                                                                    <?php
                                                                    }

                                                                    if ($exam_value->exam_type == "basic_system") {
                                                                    ?>
                                                                        <th>
                                                                            <?php echo $this->lang->line('result'); ?>
                                                                        </th>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    <th><?php echo $this->lang->line('note'); ?></th>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    if (!empty($exam_value->exam_result['result'])) {
                                                                        $total_exams = 1;
                                                                        foreach ($exam_value->exam_result['result'] as $exam_result_key => $exam_result_value) {
                                                                            $exam_grand_total = $exam_grand_total + $exam_result_value->max_marks;
                                                                            $exam_get_total   = $exam_get_total + $exam_result_value->get_marks;
                                                                            $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                                            if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                                $exam_pass_status = 0;
                                                                            }
                                                                    ?>
                                                                            <tr>
                                                                                <td>
                                                                                <?php echo ($exam_result_value->name); ?> 
                                                                                <?php if ($exam_result_value->code) {
                                                                                       echo ' (' . $exam_result_value->code . ')';
                                                                                       } ?>
                                                                                </td>
                                                                                <?php
                                                                                if ($exam_value->exam_type != "gpa") {
                                                                                ?>
                                                                                    <td><?php echo ($exam_result_value->max_marks); ?></td>
                                                                                    <?php
                                                                                    if ($exam_value->exam_type != "average_passing") {
                                                                                    ?>

                                                                                        <td><?php echo ($exam_result_value->min_marks); ?></td>
                                                                                    <?php
                                                                                    }
                                                                                    ?>
                                                                                    <td>
                                                                                        <?php
                                                                                        echo $exam_result_value->get_marks;

                                                                                        if ($exam_result_value->attendence == "absent") {
                                                                                            $exam_absent_status = 1;
                                                                                            echo "&nbsp;" . $this->lang->line('abs');
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                <?php
                                                                                } elseif ($exam_value->exam_type == "gpa") {
                                                                                ?>
                                                                                    <td>
                                                                                        <?php

                                                                                        $percentage_grade  = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                                                        $point             = findGradePoints($exam_grade, $exam_value->exam_type, $percentage_grade);
                                                                                        $exam_total_points = $exam_total_points + $point;
                                                                                        echo two_digit_float($point);
                                                                                        ?>
                                                                                    </td>
                                                                                    <td> <?php
                                                                                            echo $exam_result_value->credit_hours;
                                                                                            $exam_credit_hour = $exam_credit_hour + $exam_result_value->credit_hours;
                                                                                            ?></td>
                                                                                    <td><?php
                                                                                        echo two_digit_float($exam_result_value->credit_hours * $point);
                                                                                        $exam_quality_points = $exam_quality_points + ($exam_result_value->credit_hours * $point);
                                                                                        ?></td>
                                                                                <?php
                                                                                }
                                                                                ?>

                                                                                <?php
                                                                                if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                                                ?>
                                                                                    <td><?php echo findExamGrade($exam_grade, $exam_value->exam_type, $percentage_grade); ?></td>
                                                                                <?php
                                                                                }
                                                                                if ($exam_value->exam_type == "basic_system") {
                                                                                ?>
                                                                                    <td>
                                                                                        <?php
                                                                                        if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                                        ?>
                                                                                            <label class="label label-danger" style="margin-right: 5px;"><?php echo $this->lang->line('fail') ?></label>
                                                                                        <?php
                                                                                        } else {
                                                                                        ?>
                                                                                            <label class="label label-success" style="margin-right: 5px;"><?php echo $this->lang->line('pass') ?></label>
                                                                                        <?php
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                                <td><?php echo ($exam_result_value->note); ?></td>
                                                                            </tr>
                                                                    <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <?php ?>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="bgtgray">
                                                                    <?php
                                                                    if ($exam_value->exam_type != "gpa") {
                                                                    ?>

                                                                        <div class="col-sm-2 col-lg-2 col-md-2 border-right">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('grand_total'); ?> : <span class="description-text"><?php echo $exam_grand_total; ?></span></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 col-lg-3 col-md-3 border-right">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('total_obtain_marks'); ?> : <span class="description-text"><?php echo $exam_get_total; ?></span></h5>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-3 col-lg-2 col-md-3">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('percentage') ?> : <span class="description-text">
                                                                                <?php
                                                                                    $exam_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                                     echo two_digit_float($exam_percentage);
                                                                                ?>
                                                                                </span>
                                                                            </h5>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="col-sm-1 pull ">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('rank'); ?> : <span class="description-text"><?php echo $exam_value->rank; ?></span></h5>
                                                                            </div>
                                                                        </div>

                                                                        <div class="col-sm-4 col-lg-4 col-md-4 border-right">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('result') ?> :<span class="description-text">
                                                                                        <?php
                                                                                        if ($total_exams) {
                                                                                            if ($exam_value->exam_type == "average_passing") {
                                                                                                if ($exam_value->passing_percentage <= $exam_percentage) {

                                                                                        ?>
                                                                                                    <span class='label bg-green' style="margin-right: 5px;">
                                                                                                        <?php
                                                                                                        echo $this->lang->line('pass');
                                                                                                        ?>
                                                                                                    </span> <?php
                                                                                                        } else {
                                                                                                            ?>
                                                                                                    <span class='label label-danger' style="margin-right: 5px;">
                                                                                                        <?php
                                                                                                            echo $this->lang->line('fail');
                                                                                                        ?>
                                                                                                    </span>
                                                                                    </span><?php
                                                                                                        }
                                                                                                        echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                                                                                                    } else {
                                                                                                        if ($exam_absent_status) {
                                                                                            ?>
                                                                                    <span class='label label-danger' style="margin-right: 5px;">
                                                                                        <?php
                                                                                                            echo $this->lang->line('fail');
                                                                                        ?>
                                                                                    </span>
                                                                                    <?php
                                                                                                        } else {

                                                                                                            if ($exam_pass_status) {
                                                                                    ?>
                                                                                        <span class='label bg-green' style="margin-right: 5px;">
                                                                                            <?php
                                                                                                                echo $this->lang->line('pass');
                                                                                            ?>
                                                                                        </span> <?php
                                                                                                            } else {
                                                                                                ?>
                                                                                        <span class='label label-danger' style="margin-right: 5px;">
                                                                                            <?php
                                                                                                                echo $this->lang->line('fail');
                                                                                            ?>
                                                                                        </span>
                                                                                     
                                                                        <?php
                                                                                                            }


                                                                                                            
                                                                                                            echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                        ?>
                                                                                </h5>
                                                                            </div>
                                                                        </div>

                                                                        

                                                                    <?php
                                                                    } elseif ($exam_value->exam_type == "gpa") {
                                                                    ?>

                                                                        <div class="col-sm-3">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('credit_hours'); ?> : <span class="description-text"><?php echo $exam_credit_hour; ?></span></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-3 pull ">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('rank'); ?> : <span class="description-text"><?php echo $exam_value->rank; ?></span></h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-sm-5">
                                                                            <div class="description-block">
                                                                                <h5 class="description-header"><?php echo $this->lang->line('quality_points'); ?> : <span class="description-text">
                                                                                        <?php
                                                                                        if ($exam_credit_hour <= 0) {
                                                                                            echo "--";
                                                                                        } else {
                                                                                            $exam_grade_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                                            echo $exam_quality_points . "/" . $exam_credit_hour . '=' . two_digit_float($exam_quality_points / $exam_credit_hour) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $exam_grade_percentage) . "]";
                                                                                        }

                                                                                        ?>
                                                                                    </span>

                                                                                    <?php
                                                                                    ?>
                                                                                </h5>
                                                                            </div>
                                                                        </div>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                        </div>
                                    <?php
                                                    }
                                                } elseif ($exam_value->exam_result['exam_connection'] == 1) {

                                                    $exam_connected_exam = ($exam_value->exam_result['exam_result']['exam_result_' . $exam_value->exam_group_class_batch_exam_id]);

                                                    if (!empty($exam_connected_exam)) {
                                                        $exam_quality_points = 0;
                                                        $exam_total_points   = 0;
                                                        $exam_credit_hour    = 0;
                                                        $exam_grand_total    = 0;
                                                        $exam_get_total      = 0;
                                                        $exam_pass_status    = 1;
                                                        $exam_absent_status  = 0;
                                                        $total_exams         = 0;
                                    ?>
                                        <table class="table table-striped ">
                                            <thead>
                                                <th><?php echo $this->lang->line('subject') ?></th>
                                                <?php
                                                        if ($exam_value->exam_type == "gpa") {
                                                ?>
                                                    <th><?php echo $this->lang->line('grade_point') ?> </th>
                                                    <th><?php echo $this->lang->line('credit_hours') ?></th>
                                                    <th><?php echo $this->lang->line('quality_points'); ?></th>
                                                <?php
                                                        }
                                                ?>
                                                <?php
                                                        if ($exam_value->exam_type != "gpa") {
                                                ?>
                                                    <th><?php echo $this->lang->line('max_marks'); ?></th>
                                                    <?php
                                                            if ($exam_value->exam_type != "average_passing") {
                                                    ?>

                                                        <th><?php echo $this->lang->line('min_marks') ?></th>
                                                    <?php
                                                            }
                                                    ?>
                                                    <th><?php echo $this->lang->line('marks_obtained'); ?> </th>
                                                <?php
                                                        }
                                                ?>
                                                <?php
                                                        if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                ?>
                                                    <th><?php echo $this->lang->line('grade'); ?></th>
                                                <?php
                                                        }

                                                        if ($exam_value->exam_type == "basic_system") {
                                                ?>
                                                    <th>
                                                        <?php echo $this->lang->line('result'); ?>
                                                    </th>
                                                <?php
                                                        }
                                                ?>
                                                <th><?php echo $this->lang->line('note') ?></th>
                                            </thead>
                                            <tbody>
                                                <?php
                                                        if (!empty($exam_connected_exam)) {
                                                            $total_exams = 1;
                                                            foreach ($exam_connected_exam as $exam_result_key => $exam_result_value) {
                                                                $exam_grand_total = $exam_grand_total + $exam_result_value->max_marks;
                                                                $exam_get_total   = $exam_get_total + $exam_result_value->get_marks;
                                                                $percentage_grade = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                                if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                    $exam_pass_status = 0;
                                                                }
                                                ?>
                                                        <tr>
                                                            <td><?php echo ($exam_result_value->name); ?> <?php if ($exam_result_value->code) {
                                                                                                                echo ' (' . $exam_result_value->code . ')';
                                                                                                            } ?></td>
                                                            <?php
                                                                if ($exam_value->exam_type != "gpa") {
                                                            ?>
                                                                <td><?php echo ($exam_result_value->max_marks); ?></td>
                                                                <?php

                                                                    if ($exam_value->exam_type != "average_passing") {
                                                                ?>
                                                                    <td><?php echo ($exam_result_value->min_marks); ?></td>
                                                                <?php
                                                                    }
                                                                ?>
                                                                <td>
                                                                    <?php
                                                                    echo $exam_result_value->get_marks;

                                                                    if ($exam_result_value->attendence == "absent") {
                                                                        $exam_absent_status = 1;
                                                                        echo "&nbsp; " . $this->lang->line('abs');
                                                                    }
                                                                    ?>
                                                                </td>
                                                            <?php
                                                                } elseif ($exam_value->exam_type == "gpa") {
                                                            ?>
                                                                <td style="">
                                                                    <?php
                                                                    $percentage_grade  = ($exam_result_value->get_marks * 100) / $exam_result_value->max_marks;
                                                                    $point             = findGradePoints($exam_grade, $exam_value->exam_type, $percentage_grade);
                                                                    $exam_total_points = $exam_total_points + $point;
                                                                    echo two_digit_float($point);
                                                                    ?>
                                                                </td>
                                                                <td> <?php
                                                                        echo $exam_result_value->credit_hours;
                                                                        $exam_credit_hour = $exam_credit_hour + $exam_result_value->credit_hours;
                                                                        ?></td>
                                                                <td><?php
                                                                    echo two_digit_float($exam_result_value->credit_hours * $point);
                                                                    $exam_quality_points = $exam_quality_points + ($exam_result_value->credit_hours * $point);
                                                                    ?></td>
                                                            <?php
                                                                }
                                                            ?>
                                                            <?php
                                                                if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                                            ?>
                                                                <td><?php echo findExamGrade($exam_grade, $exam_value->exam_type, $percentage_grade); ?></td>
                                                            <?php
                                                                }
                                                                if ($exam_value->exam_type == "basic_system") {
                                                            ?>
                                                                <td>
                                                                    <?php
                                                                    if ($exam_result_value->get_marks < $exam_result_value->min_marks) {
                                                                    ?>
                                                                        <label class="label label-danger" style="margin-right: 5px;">
                                                                            <?php echo $this->lang->line('fail') ?>
                                                                            <label>
                                                                            <?php
                                                                        } else {
                                                                            ?>
                                                                              <label class="label label-success" style="margin-right: 5px;"><?php echo $this->lang->line('pass') ?><label>
                                                                           <?php
                                                                                }
                                                                           ?>
                                                                </td>
                                                            <?php
                                                                }
                                                            ?>
                                                            <td><?php echo ($exam_result_value->note); ?></td>
                                                        </tr>
                                                <?php
                                                            }
                                                        }
                                                ?>
                                            </tbody>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="bgtgray">
                                                    <?php
                                                        if ($exam_value->exam_type != "gpa") {
                                                    ?>

                                                    <div class="col-sm-2 col-lg-2 col-md-2 border-right no-print">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('grand_total'); ?> : <span class="description-text"><?php echo $exam_grand_total; ?></span></h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 border-right no-print">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('total_obtain_marks'); ?> : <span class="description-text"><?php echo $exam_get_total; ?></span></h5>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-3 col-lg-2 col-md-3 pull no-print">
                                                            <div class="description-block">
                                                                <h5 class="description-header"> <?php echo $this->lang->line('percentage') ?> : <span class="description-text">
                                                                        <?php
                                                                        $exam_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                        echo two_digit_float($exam_percentage);
                                                                        ?>
                                                                    </span></h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 col-lg-1 col-md-3 pull ">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('rank'); ?> : <span class="description-text"><?php echo $exam_value->rank; ?></span></h5>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-4 col-lg-4 col-md-4 border-right no-print">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('result'); ?> :<span class="description-text">
                                                                        <?php
                                                                        if ($total_exams) {

                                                                            if ($exam_value->exam_type == "average_passing") {
                                                                                if ($exam_value->passing_percentage <= $exam_percentage) {

                                                                        ?>
                                                                                    <span class='label bg-green' style="margin-right: 5px;">
                                                                                        <?php
                                                                                        echo $this->lang->line('pass');
                                                                                        ?>
                                                                                    </span> <?php
                                                                                        } else {
                                                                                            ?>
                                                                                    <span class='label label-danger' style="margin-right: 5px;">
                                                                                        <?php
                                                                                            echo $this->lang->line('fail');
                                                                                        ?>
                                                                                    </span>
                                                                                <?php
                                                                                        }
                                                                                    } else {
                                                                                        if ($exam_absent_status) {
                                                                                ?>
                                                                                    <span class='label label-danger' style="margin-right: 5px;">
                                                                                        <?php
                                                                                            echo $this->lang->line('fail');
                                                                                        ?>
                                                                                    </span>
                                                                                    <?php
                                                                                        } else {
                                                                                            if ($exam_pass_status) {

                                                                                    ?>
                                                                                        <span class='label bg-green' style="margin-right: 5px;">
                                                                                            <?php
                                                                                                echo $this->lang->line('pass');
                                                                                            ?>
                                                                                        </span>
                                                                                    <?php
                                                                                            } else {
                                                                                    ?>
                                                                                        <span class='label label-danger' style="margin-right: 5px;">
                                                                                            <?php
                                                                                                echo $this->lang->line('fail');
                                                                                            ?>
                                                                                        </span>
                                                                        <?php
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                        ?>
                                                                        <?php
                                                                        if ($total_exams) {

                                                                     
                                                                                echo $this->lang->line('division') . " : " . findExamDivision($marks_division, $exam_percentage);
                                                                        
                                                                        }
                                                                        ?>
                                                                    </span></h5>
                                                            </div>
                                                        </div>
                                                        
                                                    <?php
                                                        } elseif ($exam_value->exam_type == "gpa") {
                                                    ?>
                                                        <div class="col-sm-3 col-lg-3 col-md-3 pull">
                                                            <div class="description-block">
                                                                <h5 class="description-header">
                                                                    <?php echo $this->lang->line('credit_hours'); ?> :
                                                                    <span class="description-text"><?php echo $exam_credit_hour; ?>
                                                                    </span>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-3 pull ">
                                                            <div class="description-block">
                                                                <h5 class="description-header"><?php echo $this->lang->line('rank'); ?> : <span class="description-text"><?php echo $exam_value->rank; ?></span></h5>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-5 pull">
                                                            <div class="description-block">
                                                                <h5 class="description-header">
                                                                    <?php echo $this->lang->line('quality_points'); ?> :<span class="description-text">
                                                                        <?php
                                                                        if ($exam_credit_hour <= 0) {
                                                                         echo "--";
                                                                        } else {
                                                                           $exam_grade_percentage = ($exam_get_total * 100) / $exam_grand_total;
                                                                           echo $exam_quality_points . "/" . $exam_credit_hour . '=' . two_digit_float($exam_quality_points / $exam_credit_hour) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $exam_grade_percentage) . "]";
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="sp2-card">
                                            <h4 class="pagetitleh">
                                                <?php echo $this->lang->line('consolidated_result'); ?>
                                            </h4>
                                            <?php
                                                    $consolidate_exam_result            = false;
                                                    $consolidate_exam_result_percentage = false;
                                                    if ($exam_value->exam_type == "coll_grade_system" || $exam_value->exam_type == "school_grade_system") {
                                            ?>
                                                <table class="table table-striped ">
                                                    <thead>
                                                        <th><?php echo $this->lang->line('exam') ?></th>
                                                        <?php
                                                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                                        ?>
                                                            <th>
                                                                <?php echo $each_exam_value->exam; ?>
                                                            </th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <th><?php echo $this->lang->line('consolidate') ?></th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php echo $this->lang->line('marks_obtained'); ?></td>
                                                            <?php
                                                            $consolidate_get_total            = 0;
                                                            $consolidate_get_total_percentage = 0;
                                                            $consolidate_max_total            = 0;
                                                            if (!empty($exam_value->exam_result['exams'])) {
                                                                $consolidate_exam_result = "pass";
                                                                foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                                            ?>
                                                                    <td>
                                                                        <?php
                                                                        $consolidate_each    = getCalculatedExam($exam_value->exam_result['exam_result'], $each_exam_value->id);
                                                                        
																		if($consolidate_each->max_marks > 0){
                                                                            $exam_get_percentage = ($consolidate_each->get_marks * 100) / $consolidate_each->max_marks;
                                                                        }else{
                                                                            $exam_get_percentage = 0;    
                                                                        }

                                                                        $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_each->get_marks, $exam_get_percentage);
                                                                        if ($consolidate_each->exam_status == "fail") {
                                                                            $consolidate_exam_result = "fail";
                                                                        }

                                                                        echo $consolidate_get_percentage_mark['exam_consolidate_marks'] . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";

                                                                        $consolidate_get_total_percentage += ($consolidate_get_percentage_mark['exam_consolidate_percentage']);

                                                                        $consolidate_get_total = $consolidate_get_total + ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                                                                        $consolidate_max_total = $consolidate_max_total + ($consolidate_each->max_marks);
                                                                        ?>
                                                                    </td>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                            <td>
                                                                <?php
                                                                $consolidate_percentage_grade = ($consolidate_max_total > 0) ? ($consolidate_get_total * 100) / $consolidate_max_total : 0;

                                                                echo two_digit_float($consolidate_get_total_percentage) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_get_total_percentage) . "]";

                                                                $consolidate_exam_result_percentage = $consolidate_get_total_percentage;
                                                                ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php
                                                    } elseif ($exam_value->exam_type == "basic_system" || $exam_value->exam_type == "average_passing") {
                                            ?>
                                                <table class="table table-striped ">
                                                    <thead>
                                                        <th><?php echo $this->lang->line('exam'); ?></th>
                                                        <?php
                                                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                                        ?>
                                                            <th>
                                                                <?php echo $each_exam_value->exam; ?>
                                                            </th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <th><?php echo $this->lang->line('consolidate'); ?></th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php echo $this->lang->line('marks_obtained'); ?></td>
                                                            <?php
                                                            $consolidate_get_total            = 0;
                                                            $consolidate_max_total            = 0;
                                                            $consolidate_get_total_percentage = 0;
                                                            if (!empty($exam_value->exam_result['exams'])) {
                                                                $consolidate_exam_result = "pass";
                                                                foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {

                                                            ?>
                                                                    <td>
                                                                        <?php
                                                                        $consolidate_each = getCalculatedExam($exam_value->exam_result['exam_result'], $each_exam_value->id);
                                                                        if ($consolidate_each->max_marks > 0) {
                                                                            $exam_get_percentage = ($consolidate_each->get_marks * 100) / $consolidate_each->max_marks;
                                                                        } else {
                                                                            $exam_get_percentage = 0;
                                                                        }
                                                                        $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_each->get_marks, $exam_get_percentage);
                                                                        if ($exam_value->exam_type == "average_passing") {
                                                                            if ($each_exam_value->passing_percentage > $exam_get_percentage) {
                                                                                $consolidate_exam_result = "fail";
                                                                            }
                                                                        } elseif ($consolidate_each->exam_status == "fail") {
                                                                            $consolidate_exam_result = "fail";
                                                                        }

                                                                        echo two_digit_float($consolidate_get_percentage_mark['exam_consolidate_marks']) . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";

                                                                        $consolidate_get_total += ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                                                                        $consolidate_get_total_percentage += ($consolidate_get_percentage_mark['exam_consolidate_percentage']);
                                                                        $consolidate_max_total += ($consolidate_each->max_marks);

                                                                        ?>
                                                                    </td>
                                                            <?php
                                                                }
                                                            }
                                                            ?>
                                                            <td><?php
                                                                $consolidate_percentage_grade = ($consolidate_max_total > 0) ? ($consolidate_get_total * 100) / $consolidate_max_total : 0;

                                                                echo two_digit_float($consolidate_get_total_percentage) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_get_total_percentage) . "]";
                                                                $consolidate_exam_result_percentage = $consolidate_get_total_percentage;

                                                                ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php
                                                    } elseif ($exam_value->exam_type == "gpa") {
                                            ?>
                                                <table class="table table-striped ">
                                                    <thead>
                                                        <th><?php echo $this->lang->line('exam') ?></th>
                                                        <?php
                                                        foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {
                                                        ?>
                                                            <th>
                                                                <?php echo $each_exam_value->exam; ?>
                                                            </th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <th><?php echo $this->lang->line('consolidate'); ?></th>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><?php echo $this->lang->line('marks_obtained') ?></td>
                                                            <?php
                                                            $consolidate_get_total      = 0;
                                                            $consolidate_subjects_total = 0;

                                                            foreach ($exam_value->exam_result['exams'] as $each_exam_key => $each_exam_value) {

                                                            ?>
                                                                <td>
                                                                    <?php
                                                                    $consolidate_each = getCalculatedExamGradePoints($exam_value->exam_result['exam_result'], $each_exam_value->id, $exam_grade, $exam_value->exam_type);
																	if($consolidate_each->return_credit_hours > 0){
                                                                    $consolidate_exam_result = ($consolidate_each->return_quality_point / $consolidate_each->return_credit_hours);
																	}else{
																	$consolidate_exam_result = 0;	
																	}
                                                                    $consolidate_each->total_points . "/" . $consolidate_each->total_exams . "=" . two_digit_float($consolidate_exam_result, 2, '.', '');
																	if($consolidate_each->total_max_marks > 0){
                                                                    $exam_get_percentage = ($consolidate_each->total_get_marks * 100) / $consolidate_each->total_max_marks;
																	}else{
																	$exam_get_percentage = 0;	
																	}
                                                                    $consolidate_get_percentage_mark = getConsolidateRatio($exam_value->exam_result['exam_connection_list'], $each_exam_value->id, $consolidate_exam_result, 100);

                                                                    echo two_digit_float($consolidate_get_percentage_mark['exam_consolidate_marks']) . " (" . $consolidate_get_percentage_mark['exam_weightage'] . "%)";
                                                                    $consolidate_get_total      = $consolidate_get_total + ($consolidate_get_percentage_mark['exam_consolidate_marks']);
                                                                    $consolidate_subjects_total = $consolidate_subjects_total + $consolidate_each->total_exams;
                                                                    ?>
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                            <td>
                                                                <?php
                                                                $consolidate_percentage_grade = ($consolidate_get_total * 100) / 10;
                                                                $consolidate_exam_result_percentage = $consolidate_percentage_grade;
                                                                echo (two_digit_float($consolidate_get_total, 2, '.', '')) . " [" . findExamGrade($exam_grade, $exam_value->exam_type, $consolidate_percentage_grade) . "]";
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            <?php
                                                    }

                                                    if ($consolidate_exam_result) {
                                            ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="bgtgray">

                                                            <?php

                                                            if ($exam_value->exam_type != "gpa") {
                                                            ?>
                                                                <div class="col-sm-3 pull no-print">
                                                                    <div class="description-block">
                                                                        <h5 class="description-header"><?php echo $this->lang->line('result') ?> :
                                                                            <span class="description-text">
                                                                                <?php
                                                                                if ($consolidate_exam_result == "pass") {
                                                                                ?>
                                                                                    <span class='label label-success' style="margin-right: 5px;">
                                                                                        <?php
                                                                                        echo $this->lang->line('pass');
                                                                                        ?>
                                                                                    </span>
                                                                                <?php
                                                                                } else {
                                                                                ?>
                                                                                    <span class='label label-danger' style="margin-right: 5px;">
                                                                                        <?php
                                                                                        echo $this->lang->line('fail');
                                                                                        ?>
                                                                                    </span>
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                            </span>
                                                                        </h5>
                                                                    </div>
                                                                </div>

                                                            <?php

                                                            }
                                                            ?>
                                                            <?php
                                                            if ($consolidate_exam_result_percentage) {
                                                            ?>
                                                                <div class="col-sm-3 border-right no-print">
                                                                    <div class="description-block">
                                                                        <h5 class="description-header"><?php echo $this->lang->line('division'); ?> :<span class="description-text">
                                                                                <?php echo findExamDivision($marks_division, $consolidate_exam_result_percentage); ?>
                                                                            </span></h5>
                                                                    </div>
                                                                </div>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                            <?php
                                                }
                                            }
                                        }
                                    } else {
                            ?>
                        <?php
                                    }

                        ?>
                            </div>
                        </div>

                    </div>
                </div>
    </section>
</div>

<!-- student incident comments -->
<div class="modal fade" id="commentmodel" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content modal-media-content">
           <!-- <div class="modal-header modal-media-header d-flex justify-content-between">-->
            <div class="modal-header modal-media-header">
                <div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="box-title"><?php echo $this->lang->line('comments'); ?></h4>
                </div>
            </div>
            <div class="">
                <div class="modal-body pt0 pb0 relative bg-e6">
                    <form id="formadd" method="post" class="ptt10 mb10 place-italic" enctype="multipart/form-data">
                        <input type="hidden" name="student_incident_id" id="student_incident_id">
                        <div class="clearfix">
                            <div class="d-flex justify-content-between gap-1">
                                <textarea name="comment" cols="10" rows="2" placeholder="<?php echo $this->lang->line('type_your_comment'); ?>" class="form-control resize-auto border-radius-1 max-height-40"></textarea>

                                <button type="submit" class="btn btn-primary pr10 overflow-inherit max-height-40" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('send') ?></button>
                            </div>
                        </div>
                    </form>
                    <div class="scroll-area-inside">
                        <ul class="user-progress">
                            <div id="messagedetails"></div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('.comments').click(function() {
        var student_incident_id = $(this).attr('data-record-id');
        $('#student_incident_id').val(student_incident_id);

        $('#commentmodel').modal({
            backdrop: 'static',
            keyboard: false,
            show: true
        });
        getmessage(student_incident_id);
    })

    $("#formadd").on('submit', (function(e) {
        e.preventDefault();

        var student_incident_id = $('#student_incident_id').val();

        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("behaviour/studentincidentcomments/addmessage"); ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(res) {
                if (res.status == "fail") {

                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);

                } else {
                    successMsg(res.message);
                    $('#formadd')[0].reset();
                    getmessage(student_incident_id);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }

        });
    }));

    function getmessage(student_incident_id) {
        $('#messagedetails').html('');
        $.ajax({
            url: "<?php echo site_url("behaviour/studentincidentcomments/getmessage"); ?>",
            type: "POST",
            data: {
                student_incident_id: student_incident_id
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == "success") {
                    $('#messagedetails').html(res.page);
                } else {
                    $('#messagedetails').html('');
                }
            }
        });
    }

    function delete_comment(id, student_incident_id) {
        if (confirm("<?php echo $this->lang->line('delete_confirm'); ?>") == true) {
            $.ajax({
                url: "<?php echo site_url("behaviour/studentincidentcomments/delete_comment"); ?>",
                type: "POST",
                data: {
                    id: id
                },
                success: function(res) {
                    getmessage(student_incident_id);
                }
            });
        }
    }
</script>

<script type="text/javascript">
    $("#myTimelineButton").click(function() {
        $("#reset").click();
        $('.transport_fees_title').html("<b><?php echo $this->lang->line('add_timeline'); ?></b>");
        $(".dropify-clear").click();
        $('#myTimelineModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    });

    $(".myTransportFeeBtn").click(function() {
        $("span[id$='_error']").html("");
        $('#transport_amount').val("");
        $('#transport_amount_discount').val("0");
        $('#transport_amount_fine').val("0");
        var student_session_id = $(this).data("student-session-id");
        $('.transport_fees_title').html("<b><?php echo $this->lang->line('upload_documents'); ?></b>");
        $('#transport_student_session_id').val(student_session_id);
        $('#myTransportFeesModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    });
</script>
<div class="modal fade" id="myTimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title transport_fees_title"></h4>
            </div>
            <div class="">
                <div class="">
                    <form id="timelineform" name="timelineform" method="post" enctype="multipart/form-data">
                        <div class="modal-body pb0">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div id='timeline_hide_show' class="row">
                                <input type="hidden" name="student_id" value="<?php echo $student["id"] ?>" id="student_id">
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                        <input id="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" name="timeline_date" placeholder="" type="text" class="form-control date" />
                                        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder="" class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div class=""><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file" class="filestyle form-control" data-height="40" value="<?php echo set_value('timeline_doc'); ?>" />
                                            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="labeltopmb0"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                        <input class="valign-top" id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                            <button type="reset" id="reset" style="display: none" class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- code updated -->
<div class="modal fade" id="myTransportFeesModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center transport_fees_title"></h4>
            </div>
            <div class="">
                <div class="">
                    <div class="">
                        <input type="hidden" class="form-control" id="transport_student_session_id" value="0" readonly="readonly" />
                        <form id="form1" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="modal-body">
                                <div id='upload_documents_hide_show'>
                                    <input type="hidden" name="student_id" value="<?php echo $student_doc_id; ?>" id="student_id">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?><small class="req"> *</small></label>
                                        <input id="first_title" name="first_title" placeholder="" type="text" class="form-control" value="<?php echo set_value('first_title'); ?>" />
                                        <span class="text-danger"><?php echo form_error('first_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('documents'); ?><small class="req"> *</small></label>
                                        <div class="">
                                            <input id="first_doc_id" name="first_doc[]" placeholder="" type="file" class="filestyle form-control" data-height="40" value="<?php echo set_value('first_doc'); ?>" multiple />
                                            <span class="text-danger"><?php echo form_error('first_doc'); ?></span>
                                        </div>
                                        <!-- code added file_doc[] -->					  
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="clear:both">
                                <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- code updated -->					 


<!-- google drive modal -->
 <div class="modal fade" id="google_drive_model" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center"><?php echo $this->lang->line('upload_through_google_drive'); ?></h4>
            </div>
            <div class="">
                <div class="">
                    <div class="">
                        <form role="form" class="validateTitle" method="post" action="<?php echo base_url('student/searchvalidationtitle');?>">
                            <div class="modal-body">
                                <div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?><small class="req"> *</small></label>
                                        <input id="image_first_title" name="image_first_title" placeholder="" type="text" class="form-control" value="<?php echo set_value('image_first_title'); ?>" />
                                        <span class="text-danger error_image_first_title"><?php echo form_error('image_first_title'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer" style="clear:both">
                            <button type="submit" id="authorize_button"  class="btn btn-xs btn-primary "> <i class="fa fa-upload"></i> <?php 
                            echo $this->lang->line('upload_documents'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
<!-- google drive modal -->

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title_logindetail"></h4>
            </div>
            <div class="modal-body_logindetail">
            </div>
            <div class="modal-footer clearboth">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="disable_modal" tabindex="-1" role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
    <div class="modal-dialog " role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="box-title"><?php echo $this->lang->line('disable_student') ?></h4>
            </div>
            <form role="form" id="disable_form" method="post" enctype="multipart/form-data" action="">
                <div class="modal-body pb0">
                    <div class="row">
                        
                           
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('reason'); ?></label><small class="req"> *</small>

                                        <input type="hidden" name="student_id" id="disstudent_id">
                                        <select class="form-control" name="reason" id="reason">
                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                            <?php
                                            foreach ($reason as $value) {
                                            ?>
                                                <option value="<?php echo $value['id'] ?>"><?php echo $value['reason'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                                        <input name="disable_date" id="disable_date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" type="text" readonly="readonly" />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                        <textarea name="note" id="note" class="form-control"></textarea>
                                    </div>
                                </div>
                            
                       
                    </div>
                </div>
                <div class="box-footer">
                     
                        <button class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Please wait" value=""><?php echo $this->lang->line('save'); ?></button>
                    </div>  
					
					 
						
            </form>
       
    </div>
</div>
</div>

<div class="modal fade" id="edittimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_timeline'); ?></h4>
            </div>
            <form id="edittimelineform" name="timelineform" method="post" action="<?php echo base_url() . "admin/timeline/add_staff_timeline" ?>" enctype="multipart/form-data">
                <div class="modal-body pb0">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id="edittimelinedata"></div>
                </div>
                <div class="modal-footer" style="clear:both">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                    <button type="reset" id="reset" style="display: none" class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(e) {
        $('#myTransportFeesModal').on('hidden.bs.modal', function() {
            $(this).find('form').trigger('reset');
            $(".dropify-clear").click();
        })
    });

    $("#timelineform").on('submit', (function(e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/timeline/add") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));

    function delete_timeline(id) {
        var student_id = $("#student_id").val();
        if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/timeline/delete_timeline/',
                type: 'post',
                data: {
                    id: id
                },
                dataType: 'JSON',
                success: function(res) {
                    if (res.status == 'success') {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                },
                error: function() {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                }
            });
        }
    }

    function disable_student(id) {
        if (confirm("<?php echo $this->lang->line('are_you_sure_you_want_to_disable_this_student') ?>")) {
            $('#disstudent_id').val(id);
            $('#disable_modal').modal('show');
            $('#note').val('');
            $('#reason').val('');
        }
    }

    $("#disable_form").on('submit', (function(e) {
        e.preventDefault();
        var id = $('#disstudent_id').val();
        var $this = $(this).find("button[type=submit]:focus");

        $.ajax({
            url: "<?php echo site_url("student/disable_reason") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');

            },
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again') ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));

    function disable(id) {
        if (confirm("<?php echo $this->lang->line('are_you_sure_you_want_to_disable_this_student') ?>")) {
            var student_id = '<?php echo $student["id"] ?>';
            $.ajax({
                type: "post",
                url: base_url + "student/getUserLoginDetails",
                data: {
                    'student_id': student_id
                },
                dataType: "json",
                success: function(response) {
                    var userid = response.id;
                    changeStatus(userid, 'no', 'student');
                }
            });
        } else {
            return false;
        }
    }

    function enable(id, status, role) {
        if (confirm("<?php echo $this->lang->line('are_you_sure_you_want_to_enable_this_record'); ?>")) {
            var student_id = '<?php echo $student["id"] ?>';

            $.ajax({
                type: "post",
                url: base_url + "student/getUserLoginDetails",
                data: {
                    'student_id': student_id
                },
                dataType: "json",
                success: function(response) {

                    var userid = response.id;
                    changeStatus(userid, 'yes', 'student');
                }
            });

            $.ajax({
                type: "post",
                url: base_url + "student/enablestudent/" + student_id,
                data: {
                    'student_id': student_id
                },
                dataType: "json",
                success: function(data) {
                    window.location.reload(true);

                }
            });

        } else {
            return false;
        }
    }

    function changeStatus(rowid, status = 'no', role = 'student') {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            type: "POST",
            url: base_url + "admin/users/changeStatus",
            data: {
                'id': rowid,
                'status': status,
                'role': role
            },
            dataType: "json",
            success: function(data) {
                successMsg(data.msg);
            }
        });
    }

    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: false,
            paging: false,
            bSort: false,
            info: false
        });
    });

    function send_password() {
        var base_url = '<?php echo base_url() ?>';
        var student_session_id = '<?php echo $student['student_session_id']; ?>';
        var student_id = '<?php echo $student['id']; ?>';
        var username = '<?php echo $student['username']; ?>';
        var password = '<?php echo $student['password']; ?>';
        var contact_no = '<?php echo $student['mobileno']; ?>';
        var email = '<?php echo $student['email']; ?>';
        var admission_no = '<?php echo $student['admission_no']; ?>';

        $.ajax({
            type: "post",
            url: base_url + "student/sendpassword",
            data: {
                student_id: student_id,
                username: username,
                password: password,
                contact_no: contact_no,
                email: email,
                admission_no: admission_no,
                student_session_id: student_session_id
            },
            success: function(response) {
                successMsg('<?php echo $this->lang->line('message_successfully_sent'); ?>');
            }
        });
    }

    function send_parent_password() {
        var base_url = '<?php echo base_url() ?>';
        var student_id = '<?php echo $student['id']; ?>';
        var student_session_id = '<?php echo $student['student_session_id']; ?>';
        var username = '<?php echo $guardian_credential['username']; ?>';
        var password = '<?php echo $guardian_credential['password']; ?>';
        var contact_no = '<?php echo $student['guardian_phone']; ?>';
        var email = '<?php echo $student['guardian_email']; ?>';
        var admission_no = '<?php echo $student['admission_no']; ?>';

        $.ajax({
            type: "post",
            url: base_url + "student/send_parent_password",
            data: {
                student_id: student_id,
                username: username,
                password: password,
                contact_no: contact_no,
                email: email,
                admission_no: admission_no,
                student_session_id: student_session_id
            },
            success: function(response) {
                successMsg('<?php echo $this->lang->line('message_successfully_sent'); ?>');
            }
        });
    }

    $(document).on('click', '.schedule_modal', function() {
        $('.modal-title_logindetail').html("");
        $('.modal-title_logindetail').html("<?php echo $this->lang->line('login_details'); ?>");
        var base_url = '<?php echo base_url() ?>';
        var student_id = '<?php echo $student["id"] ?>';
        var student_name = '<?php echo $this->customlib->getFullName($student["firstname"], $student["middlename"], $student["lastname"], $sch_setting->middlename, $sch_setting->lastname); ?>';
        $.ajax({
            type: "post",
            url: base_url + "student/getlogindetail",
            data: {
                'student_id': student_id
            },
            dataType: "json",
            success: function(response) {
                var data = "";
                data += '<div class="col-md-12">';
                data += '<div class="table-responsive pb10">';
                data += '<p class="lead text text-center ptt10">' + student_name + '</p>';
                data += '<table class="table table-hover">';
                data += '<thead>';
                data += '<tr>';
                data += '<th>' + "<?php echo $this->lang->line('user_type'); ?>" + '</th>';
                data += '<th class="text text-center">' + "<?php echo $this->lang->line('username'); ?>" + '</th>';
                data += '<th class="text text-center">' + "<?php echo $this->lang->line('password'); ?>" + '</th>';
                data += '</tr>';
                data += '</thead>';
                data += '<tbody>';
                $.each(response, function(i, obj) {
                    data += '<tr>';
                    data += '<td><b>' + (obj.role) + '</b></td>';
                    data += '<input type=hidden name=userid id=userid value=' + obj.id + '>';
                    data += '<td class="text text-center">' + obj.username + '</td> ';
                    data += '<td class="text text-center">' + obj.password + '</td> ';
                    data += '</tr>';
                });
                data += '</tbody>';
                data += '</table>';
				data += '<b class="lead text bs-text-primary" style="font-size:14px;padding-left: 5px;"> ' + "<?php echo $this->lang->line('login_url'); ?>" + ': ' + base_url + 'site/userlogin</b>';
                data += '</div>  ';
                data += '</div>  ';
                $('.modal-body_logindetail').html(data);
                $("#scheduleModal").modal('show');
            }
        });
    });

    function firstToUpperCase(str) {
        return str.substr(0, 1).toUpperCase() + str.substr(1);
    }

    $(document).ready(function() {
        getExamResult();
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function() {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });

    $(document).ready(function() {
        $('#disable_modal,#scheduleModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false

        });
    });

    function getExamResult(student_session_id) {
        if (student_session_id != "") {
            $('.examgroup_result').html("");

            $.ajax({
                type: "POST",
                url: baseurl + "admin/examresult/getStudentCurrentResult",
                data: {
                    'student_session_id': 17
                },
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(data) {
                    $('.examgroup_result').html(data.result);
                },
                complete: function() {

                }
            });
        }
    }
</script>

<script type="text/javascript">
    $(document).on('change', '#exam_group_id', function() {
        var exam_group_id = $(this).val();
        if (exam_group_id != "") {
            $('#exam_id').html("");

            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "POST",
                url: baseurl + "admin/examgroup/getExamsByExamGroup",
                data: {
                    'exam_group_id': exam_group_id
                },
                dataType: "JSON",
                beforeSend: function() {
                    $('#exam_id').addClass('dropdownloading');
                },
                success: function(data) {
                    console.log(data);
                    $.each(data.result, function(i, obj) {
                        div_data += "<option value=" + obj.id + ">" + obj.exam + "</option>";
                    });
                    $('#exam_id').append(div_data);
                },
                complete: function() {
                    $('#exam_id').removeClass('dropdownloading');
                }
            });
        }
    });

    // this is the id of the form
    $("form#form_examgroup").submit(function(e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url = form.attr('action');
        var submit_button = $("button[type=submit]");
        $.ajax({
            type: "POST",
            url: url,
            dataType: 'JSON',
            data: form.serialize(), // serializes the form's elements.
            beforeSend: function() {
                submit_button.button('loading');
            },
            success: function(data) {
                $('.examgroup_result').html(data.result);
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again') ?>");
                submit_button.button('reset');
            },
            complete: function() {
                submit_button.button('reset');
            }
        });
    });

    $("#form1").on('submit', (function(e) {
        e.preventDefault();

        var $this = $(this).find("button[type=submit]:focus");

        $.ajax({
            url: "<?php echo site_url("student/create_doc") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },			 
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));
</script>

<script>
    $('.edit_timeline').click(function() {
        $('#edittimelineModal').modal('show');
        var id = $(this).attr('data-id');
        $.ajax({
            url: "<?php echo site_url("admin/timeline/getstudentsingletimeline") ?>",
            type: "POST",
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#edittimelinedata').html(response.page);
            }
        });
    })

    $("#edittimelineform").on('submit', (function(e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/timeline/editstudenttimeline") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function(index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    }));

    function ajax_attendance(id, year) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'student/ajax_attendance/',
            type: 'POST',
            data: {
                id: id,
                year: year
            },
            success: function(result) {
                $("#ajaxattendance").html(result);
            }
        });
    }
</script>

<script type="text/javascript">
    function printDiv() {
        $("#visible").removeClass("hide");
        $("#exam_student_name").removeClass("hide");

        document.getElementById("print").style.display = "none";
        var divElements = document.getElementById('visible').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        location.reload(true);
    }

    function printDivCbse() {


        document.getElementById("cbseexam").style.display = "none";
        var divElements = document.getElementById('cbseexam').innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        location.reload(true);
    }
</script>

<script type="text/javascript">
	$(document).on('click', '.print_student_details', function() {
    let $button_ = $(this);
    var student_id = "<?php echo $student["id"] ?>";
    var admission_no = $(this).attr('data-admission_no');
	var student_name = $(this).attr('data-student_name');
    $.ajax({
        type: 'POST',
        url: baseurl + 'student/printStudentDetails',  // Assuming baseurl is defined elsewhere
        data: {'student_id':student_id},  // Add any data you need to send here
		 
        beforeSend: function() {
            $button_.button('loading');  // Change button state to loading
        },
        xhr: function() {
            var xhr = new XMLHttpRequest();  // Fixed the typo here
            xhr.responseType = 'blob';  // Set response type to blob
            return xhr;
        },
        success: function(data, jqXHR, response) {
            // Create a Blob with the response data (PDF)
            var blob = new Blob([data], {type: 'application/pdf'});

            // Create an anchor element to trigger the file download
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = student_name + '_' + admission_no + '.pdf';  // Assumes student_name and admission_no are defined
            document.body.appendChild(link);  // Append to body to trigger download
            link.click();
            document.body.removeChild(link);  // Clean up by removing the link

            $button_.button('reset');  // Reset the button to its original state
        },
        error: function(xhr, status, error) {
            // If an error occurs, reset the button
            console.error("Error occurred:", status, error);  // You can log errors for debugging
            $button_.button('reset');
        },
        complete: function() {
            // Reset the button regardless of success or failure
            $button_.button('reset');
        }
    });
});	
	
</script>

<?php
function findGradePoints($exam_grade, $exam_type, $percentage)
{
    foreach ($exam_grade as $exam_grade_key => $exam_grade_value) {
        if ($exam_grade_value['exam_key'] == $exam_type) {

            if (!empty($exam_grade_value['exam_grade_values'])) {
                foreach ($exam_grade_value['exam_grade_values'] as $grade_key => $grade_value) {
                    if ($grade_value->mark_from >= $percentage && $grade_value->mark_upto <= $percentage) {
                        return $grade_value->point;
                    }
                }
            }
        }
    }
    return 0;
}

function findExamGrade($exam_grade, $exam_type, $percentage)
{
    foreach ($exam_grade as $exam_grade_key => $exam_grade_value) {
        if ($exam_grade_value['exam_key'] == $exam_type) {

            if (!empty($exam_grade_value['exam_grade_values'])) {
                foreach ($exam_grade_value['exam_grade_values'] as $grade_key => $grade_value) {
                    if ($grade_value->mark_from >= $percentage && $grade_value->mark_upto <= $percentage) {
                        return $grade_value->name;
                    }
                }
            }
        }
    }
    return "";
}

function findExamDivision($marks_division, $percentage)
{
    if (!empty($marks_division)) {
        foreach ($marks_division as $division_key => $division_value) {
            if ($division_value->percentage_from >= $percentage && $division_value->percentage_to <= $percentage) {
                return $division_value->name;
            }
        }
    }

    return "";
}

function getConsolidateRatio($exam_connection_list, $examid, $get_marks, $exam_get_percentage)
{
    if (!empty($exam_connection_list)) {
        foreach ($exam_connection_list as $exam_connection_key => $exam_connection_value) {

            if ($exam_connection_value->exam_group_class_batch_exams_id == $examid) {
                return [
                    'exam_weightage'      => $exam_connection_value->exam_weightage,
                    'exam_consolidate_marks'      => ($get_marks * $exam_connection_value->exam_weightage) / 100,
                    'exam_consolidate_percentage' => ($exam_get_percentage * $exam_connection_value->exam_weightage) / 100
                ];
            }
        }
    }
    return 0;
}

function getCalculatedExamGradePoints($array, $exam_id, $exam_grade, $exam_type)
{
    $object               = new stdClass();
    $return_total_points  = 0;
    $return_total_exams   = 0;
    $return_max_marks     = 0;
    $return_quality_point = 0;
    $return_get_marks     = 0;
    $return_credit_hours  = 0;
    if (!empty($array)) {

        if (!empty($array['exam_result_' . $exam_id])) {

            foreach ($array['exam_result_' . $exam_id] as $exam_key => $exam_value) {
                $return_total_exams++;
                $percentage_grade    = ($exam_value->get_marks * 100) / $exam_value->max_marks;
                $point               = findGradePoints($exam_grade, $exam_type, $percentage_grade);
                $return_total_points = $return_total_points + $point;
                $return_quality_point += ($point * $exam_value->credit_hours);
                $return_credit_hours += $exam_value->credit_hours;
                $return_max_marks += $exam_value->max_marks;
                $return_get_marks += $exam_value->get_marks;
            }
        }
    }

    $object->total_max_marks      = $return_max_marks;
    $object->total_get_marks      = $return_get_marks;
    $object->total_points         = $return_total_points;
    $object->total_exams          = $return_total_exams;
    $object->return_quality_point = $return_quality_point;
    $object->return_credit_hours  = $return_credit_hours;

    return $object;
}

function getCalculatedExam($array, $exam_id)
{
    $object              = new stdClass();
    $return_max_marks    = 0;
    $return_get_marks    = 0;
    $return_credit_hours = 0;
    $return_exam_status  = false;
    if (!empty($array)) {
        $return_exam_status = 'pass';
        if (!empty($array['exam_result_' . $exam_id])) {
            foreach ($array['exam_result_' . $exam_id] as $exam_key => $exam_value) {

                if ($exam_value->get_marks < $exam_value->min_marks || $exam_value->attendence != "present") {
                    $return_exam_status = "fail";
                }

                $return_max_marks    = $return_max_marks + ($exam_value->max_marks);
                $return_get_marks    = $return_get_marks + ($exam_value->get_marks);
                $return_credit_hours = $return_credit_hours + ($exam_value->credit_hours);
            }
        }
    }
    $object->credit_hours = $return_credit_hours;
    $object->get_marks    = $return_get_marks;
    $object->max_marks    = $return_max_marks;
    $object->exam_status  = $return_exam_status;
    return $object;
}

//-----------CBSE Exam start----------------------

function find_subject_assessment_exists($subject_assessments, $cbse_exam_timetable_id, $cbse_exam_assessment_type_id)
{

    if (!empty($subject_assessments)) {
        foreach ($subject_assessments as $key => $value) {
            if ($value->id == $cbse_exam_timetable_id) {
                if (!empty($value->subject_assessments)) {
                    foreach ($value->subject_assessments as $askey => $asvalue) {
                        if ($asvalue->cbse_exam_timetable_id == $cbse_exam_timetable_id  && $asvalue->cbse_exam_assessment_type_id == $cbse_exam_assessment_type_id) {
                            return true;
                            break;
                        }
                    }
                }
            }
        }
    }
    return false;
}

function getGrade($grade_array, $Percentage)
{

    if (!empty($grade_array)) {
        foreach ($grade_array as $grade_key => $grade_value) {

            if ($grade_value->minimum_percentage <= $Percentage) {
                return $grade_value->name;
                break;
            } elseif (($grade_value->minimum_percentage >= $Percentage && $grade_value->maximum_percentage <= $Percentage)) {

                return $grade_value->name;
                break;
            }
        }
    }
    return "-";
}

function findAssessmentValue($find_subject_id, $find_cbse_exam_assessment_type_id, $student_value)
{
    $return_array = [
        'maximum_marks' => "",
        'marks' => "",
        'note' => "",
        'is_absent' => "",
    ];

    if (property_exists($student_value, 'subjects')) {

        if (array_key_exists($find_subject_id, $student_value->exam_data['subjects'])) {

            $result_array = ($student_value->exam_data['subjects'][$find_subject_id]['exam_assessments'][$find_cbse_exam_assessment_type_id]);

            $return_array = [
                'maximum_marks' => $result_array['maximum_marks'],
                'marks' => is_null($result_array['marks']) ? "N/A" : $result_array['marks'],
                'note' => $result_array['note'],
                'is_absent' => $result_array['is_absent'],
            ];
        }
    }

    return $return_array;
}

//-----------CBSE Exam End----------------------
?>
<script>
    
   $(document).on('submit', '.validateTitle', function(e) {
           e.preventDefault(); // avoid to execute the actual submit of the form.
            var $this = $("button[type=submit][clicked=true]");
            var form = $(this);
            var url = form.attr('action');
            var form_data = form.serializeArray();

            $.ajax({
                url: url,
                type: "POST",
                dataType: 'JSON',
                data: form_data, 
                success: function(response) { // your success handler
                    if (!response.status) {
                        $.each(response.error, function(key, value) {
                            $('.error_' + key).html(value);
                        });
                    } else {
                       handleAuthClick();
                    }
                },
                error: function() { // your error handler
                },
                complete: function() {
                }
            });
        });
</script>


<!--======================ADD GOOGLE DRIVE============================-->

<script type="text/javascript">
  /* exported gapiLoaded */
  /* exported gisLoaded */
  /* exported handleAuthClick */
  /* exported handleSignoutClick */

  // Authorization scopes required by the API; multiple scopes can be
  // included, separated by spaces.
  //const SCOPES = 'https://www.googleapis.com/auth/drive.metadata.readonly';
  const SCOPES = 'https://www.googleapis.com/auth/drive.file';

  // TODO(developer): Replace with your client ID and API key from https://console.cloud.google.com/.
   const CLIENT_ID = '<?php echo $googledrivepickersetting['client_id']; ?>';
   const API_KEY = '<?php echo $googledrivepickersetting['api_key']; ?>';
   // TODO(developer): Replace with your project number from https://console.cloud.google.com/.
   const APP_ID = '<?php echo $googledrivepickersetting['project_number']; ?>';

  let tokenClient;
  let accessToken = null;
  let pickerInited = false;
  let gisInited = false;

  document.getElementById('authorize_button').style.visibility = 'hidden';

  /**
   * Callback after api.js is loaded.
   */
  function gapiLoaded() {
    gapi.load('client:picker', initializePicker);
  }

  /**
   * Callback after the API client is loaded. Loads the
   * discovery doc to initialize the API.
   */
  async function initializePicker() {
    await gapi.client.load('https://www.googleapis.com/discovery/v1/apis/drive/v3/rest');
    pickerInited = true;
    maybeEnableButtons();
  }

  /**
   * Callback after Google Identity Services are loaded.
   */
  function gisLoaded() {
    tokenClient = google.accounts.oauth2.initTokenClient({
      client_id: CLIENT_ID,
      scope: SCOPES,
      callback: '', // defined later
    });
    gisInited = true;
    maybeEnableButtons();
  }

  /**
   * Enables user interaction after all libraries are loaded.
   */
  function maybeEnableButtons() {
    if (pickerInited && gisInited) {
      document.getElementById('authorize_button').style.visibility = 'visible';
    }
  }

  /**
   *  Sign in the user upon button click.
   **/
  function handleAuthClick() {
    $("#google_drive_model").modal("hide");//added by webfeb

    tokenClient.callback = async (response) => {
      if (response.error !== undefined) {
        throw (response);
      }
      accessToken = response.access_token;
      document.getElementById('authorize_button').innerText = 'Refresh';
      await createPicker();
    };

    if (accessToken === null) {
      // Prompt the user to select a Google Account and ask for consent to share their data
      // when establishing a new session.
      tokenClient.requestAccessToken({prompt: 'consent'});
    } else {
      // Skip display of account chooser and consent dialog for an existing session.
      tokenClient.requestAccessToken({prompt: ''});
    }   
  }

  /**
   *  Create and render a Google Picker object for searching images.
   */
  function createPicker() {
    const view = new google.picker.View(google.picker.ViewId.DOCS);
    // view.setMimeTypes('image/png,image/jpeg,image/jpg');

view.setMimeTypes(
  'image/png,image/jpeg,image/jpg,' +
  'application/pdf,' +
  'application/msword,' +
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document,' +
  'application/vnd.ms-excel,' +
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
);



    const picker = new google.picker.PickerBuilder()
        .enableFeature(google.picker.Feature.NAV_HIDDEN)
        .enableFeature(google.picker.Feature.MULTISELECT_ENABLED)
        .setDeveloperKey(API_KEY)
        .setAppId(APP_ID)
        .setOAuthToken(accessToken)
        .addView(view)
        .addView(new google.picker.DocsUploadView())
        .setCallback(pickerCallback)
        .build();
    picker.setVisible(true);

     console.log("accessToken-> "+accessToken);
  }

  /**
   * Displays the file details of the user's selection.
   * @param {object} data - Contains the user selection from the Google Picker.
   */
  async function pickerCallback(data) {
    if (data.action === google.picker.Action.PICKED) {
      let text = `Google Picker response: \n${JSON.stringify(data, null, 2)}\n`;
      const document = data[google.picker.Response.DOCUMENTS][0];
      const fileId = document[google.picker.Document.ID];
      const res = await gapi.client.drive.files.get({
        'fileId': fileId,
        'fields': '*',
      });
      text += `Drive API response for first document: \n${JSON.stringify(res.result, null, 2)}\n`;
        //window.document.getElementById('content').innerText = text;//comment by webfeb
        //================store image=================
            storefile(data);
        //================store image=================
    }
  }
</script>
<script async defer src="https://apis.google.com/js/api.js" onload="gapiLoaded()"></script>
<script async defer src="https://accounts.google.com/gsi/client" onload="gisLoaded()"></script>
</body>
</html>
<script>

function opengoogledrivemodel(){
    $("#image_first_title").val("");  
    $("#google_drive_model").modal("show");
}

function storefile(data) {

    var first_title = $("#image_first_title").val();
    var student_id = "<?php echo $student['id'] ?>";

    $.ajax({
        url: base_url + 'student/save_image',
        type: 'POST',
        dataType: "JSON",
        data: {
            data: data,
            accessToken: accessToken,
            first_title: first_title,
            student_id: student_id
        },

       
        beforeSend: function () {
            $(".laoder").removeClass("hide");   
        },

    
        success: function (res) {

            if (res.status == "fail") {

                let message = "";
                $.each(res.error, function (i, val) {
                    message += val;
                });

                errorMsg(message);

            } else {
                successMsg(res.message);
                location.reload();
            }
        },

 
        error: function (xhr, status, error) {
            errorMsg("Something went wrong. Please try again.");
        },

       
        complete: function () {
            $(".laoder").addClass("hide");   
        }
    });
}


</script>
<!--======================ADD GOOGLE DRIVE============================-->



