<?php
$theme_setting = $this->customlib->getCurrentThemeSetting();
$theme_color = isset($theme_setting['theme_color']) ? $theme_setting['theme_color'] : '#4f46e5';
?>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
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
.sp2-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid #eaeaea;
    background: #fcfcfc;
    font-weight: 700;
    font-size: 14px;
    color: #222;
    border-radius: 10px 10px 0 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sp2-card-header i { color: <?php echo $theme_color; ?>; }
.sp2-card-body {
    padding: 18px;
}
.sp2-data-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
}
.sp2-data-box {
    background: #f9fafb;
    border: 1px solid #f0f0f0;
    border-radius: 8px;
    padding: 12px 14px;
}
.sp2-data-box-label {
    font-size: 10px;
    font-weight: 700;
    color: #8a8a8a;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 4px;
}
.sp2-data-box-value {
    font-size: 14px;
    font-weight: 700;
    color: #222;
    word-break: break-word;
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

/* ---------- Hero Header ---------- */
.sp2-hero {
    background: linear-gradient(135deg, <?php echo $theme_color; ?> 0%, #3026ad 100%);
    border-radius: 12px;
    padding: 24px 24px 0 24px;
    margin-bottom: 18px;
    position: relative;
    box-shadow: 0 4px 20px rgba(79,70,229,0.25);
}
.sp2-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 12px;
    overflow: hidden;
    z-index: 0;
}
.sp2-hero-bg::before {
    content: '';
    position: absolute;
    top: -40px;
    right: -40px;
    width: 160px;
    height: 160px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}
.sp2-hero-bg::after {
    content: '';
    position: absolute;
    bottom: -20px;
    left: 30%;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.sp2-hero-body {
    display: flex;
    align-items: flex-end;
    gap: 20px;
    position: relative;
    z-index: 1;
}
.sp2-hero-avatar-wrap {
    flex-shrink: 0;
}
.sp2-hero-avatar {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.8);
    object-fit: cover;
    box-shadow: 0 4px 14px rgba(0,0,0,0.2);
    display: block;
    margin-bottom: 0;
}
.sp2-hero-avatar-placeholder {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: rgba(255,255,255,0.8);
    margin-bottom: 0;
}
.sp2-hero-info {
    flex: 1;
    padding-bottom: 20px;
}
.sp2-hero-name {
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    margin: 0 0 8px 0;
    line-height: 1.2;
    text-shadow: 0 1px 3px rgba(0,0,0,0.15);
}
.sp2-hero-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 7px;
    margin-bottom: 10px;
}
.sp2-hero-pill {
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    background: rgba(255,255,255,0.2);
    color: rgba(255,255,255,0.95);
    border: 1px solid rgba(255,255,255,0.25);
    backdrop-filter: blur(4px);
}
.sp2-hero-pill.disabled-pill {
    background: rgba(216,69,106,0.6);
    border-color: rgba(216,69,106,0.8);
}
.sp2-hero-actions {
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 20px;
    flex-wrap: wrap;
    justify-content: flex-end;
    flex-shrink: 0;
    position: relative;
    z-index: 2;
}
.sp2-btn {
    padding: 6px 13px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid rgba(255,255,255,0.35);
    background: rgba(255,255,255,0.15);
    color: #fff;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s;
    white-space: nowrap;
    backdrop-filter: blur(4px);
}
.sp2-btn:hover {
    background: rgba(255,255,255,0.3);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
}
.sp2-btn.sp2-btn-danger {
    background: rgba(216,69,106,0.3);
    border-color: rgba(216,69,106,0.6);
}
.sp2-btn.sp2-btn-success {
    background: rgba(59,155,101,0.35);
    border-color: rgba(59,155,101,0.6);
}
.sp2-btn.sp2-btn-solid {
    background: rgba(255,255,255,0.9);
    color: <?php echo $theme_color; ?>;
    border-color: transparent;
}
.sp2-btn.sp2-btn-solid:hover {
    background: #fff;
    color: <?php echo $theme_color; ?>;
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

/* ---------- Enhanced Tab Navigation (Light Pill Liquid Glass) ---------- */
.sp2-tabs-nav {
    border-bottom: none;
    display: inline-flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    white-space: nowrap;
    gap: 8px;
    background: rgba(255, 255, 255, 0.6);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    padding: 8px;
    border-radius: 50px;
    margin-bottom: 32px !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
    border: 1px solid rgba(255, 255, 255, 0.8);
    list-style: none;
    max-width: 100%;
}
.sp2-tabs-nav::-webkit-scrollbar { display: none; }
.sp2-tabs-nav > li { margin: 0; }
.sp2-tabs-nav > li > a {
    border: none !important;
    border-radius: 40px !important;
    padding: 10px 24px;
    font-weight: 600;
    color: #6b7280;
    background: transparent;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: block;
    white-space: nowrap;
}
.sp2-tabs-nav > li > a:hover {
    color: #111827;
    background: rgba(255, 255, 255, 0.5);
}
.sp2-tabs-nav > li.active > a, 
.sp2-tabs-nav > li.active > a:hover, 
.sp2-tabs-nav > li.active > a:focus {
    background: #fff;
    color: #111827;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.sp2-tab-content {
    /* Container for the tab content, removing old borders */
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
}
.sp2-invoice-table { display: block; }
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
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
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
/* ---------- Tab Navigation (Pill Glass UI) ---------- */
.nav-tabs-custom > .nav-tabs { border-bottom-color: transparent !important; }
.nav-tabs-custom { box-shadow: none !important; background: transparent !important; }
.sp2-tabs-nav {
    display: flex;
    gap: 8px;
    background: #fff;
    padding: 10px 14px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    border: 0px solid #f0f0f0;
    border-bottom: none;
    overflow-x: auto;
    margin-bottom: 20px !important;
}
.sp2-tabs-nav > li {
    float: none;
    margin: 0;
    border: none !important;
}
.sp2-tabs-nav > li > a {
    border: none !important;
    background: transparent !important;
    color: #6b7280 !important;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 16px;
    border-radius: 20px !important;
    transition: all 0.2s;
    margin-right: 0;
}
.sp2-tabs-nav > li > a:hover {
    color: #222 !important;
    background: #f3f4f6 !important;
}
.sp2-tabs-nav > li.active > a,
.sp2-tabs-nav > li.active > a:hover,
.sp2-tabs-nav > li.active > a:focus {
    background: linear-gradient(135deg, <?php echo $theme_color; ?> 0%, #3026ad 100%) !important;
    color: #fff !important;
    box-shadow: 0 4px 10px rgba(79,70,229,0.25);
    border: none !important;
}
.sp2-tabs-nav > li.active {
    border: none !important;
}
</style>
<div class="sp2-wrapper content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <section class="content-header">
                <h1><i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); 
                                                    ?></h1>
            </section>
        </div>
        <div>
            <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) { ?>
                <a id="sidebarCollapse" class="studentsideopen"><i class="fa fa-navicon"></i></a>
            <?php } ?>

            <?php
            $employee_id = '';
            if ($staff["employee_id"] != '') {
                $employee_id = ' (' . $staff["employee_id"] . ')';
            }
            ?>
            <aside class="studentsidebar">
                <div class="stutop" id="">
                    <!-- Create the tabs -->
                    <div class="studentsidetopfixed">
                        <p class="classtap"><?php echo $this->lang->line('staff'); ?> <a href="#" data-toggle="control-sidebar" class="studentsideclose"><i class="fa fa-times"></i>
                            </a>
                        </p>
                        <ul class="nav nav-justified studenttaps">
                            <?php foreach ($roles as $role_key => $role_value) {
                            ?>
                                <li <?php
                                    if ($staff["role_id"] == $role_value["id"]) {
                                        echo "class='active'";
                                    }
                                    ?>><a href="#role<?php echo $role_value["id"] ?>" data-toggle="tab"><?php echo $role_value["name"] ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <?php foreach ($roles as $rolet_key => $rolet_value) {
                        ?>
                            <div class="tab-pane <?php
                                                    if ($staff["role_id"] == $rolet_value["id"]) {
                                                        echo "active";
                                                    }
                                                    ?>" id="role<?php echo $rolet_value['id'] ?>">

                                <?php
                                foreach ($stafflist as $skey => $svalue) {

                                    if ($rolet_value['id'] == $svalue["role_id"]) {

                                        if (!empty($svalue["image"])) {
                                            $image = $svalue['image'];
                                        } else {
                                            if ($svalue['gender'] == 'Male') {
                                                $image = "default_male.jpg";
                                            } else {
                                                $image = "default_female.jpg";
                                            }
                                        }
                                ?>
                                        <div class="studentname">
                                            <a href="<?php echo base_url() . "admin/staff/profile/" . $svalue["id"] ?>">
                                                <div class="icon"><img src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $image); ?>" alt="<?php echo $this->lang->line('user_image'); ?>"></div>
                                                <div class="student-tittle"><?php echo $svalue['name'] . " " . $svalue['surname']; ?></div>
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
    </div>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
<div class="sp2-hero" style="margin-bottom:14px;">
    <div class="sp2-hero-bg"></div>
    <div class="sp2-hero-body" style="flex-direction: column; align-items: center; text-align: center; gap: 10px; padding-bottom: 20px;">
        <div class="sp2-hero-avatar-wrap">
            <?php
            $image = $staff['image'];
            if (!empty($image)) {
                $file = $staff['image'];
            } else {
                if ($staff['gender'] == 'Male') {
                    $file = "default_male.jpg";
                } else {
                    $file = "default_female.jpg";
                }
            }
            ?>
            <img class="sp2-hero-avatar" src="<?php echo $this->media_storage->getImageURL("uploads/staff_images/" . $file); ?>" alt="User profile picture">
        </div>
        <div class="sp2-hero-info" style="padding-bottom: 0;">
            <h3 class="sp2-hero-name" style="font-size: 18px;"><?php echo $staff['name'] . " " . $staff['surname']; ?></h3>
            <div class="sp2-hero-pills" style="justify-content: center;">
                <span class="sp2-hero-pill"><?php echo $staff['user_type']; ?></span>
            </div>
            <?php if ($staff['user_type'] == 'Teacher') {
            ?>

                <?php if ($rate_canview == 1) { ?><center>
                        <h3><?php
                            $stage     = (int) ($rate);
                            $stagehalf = "";
                            $half      = fmod($rate, 1);
                            if ($half != 0) {
                                $stagehalf = $stage + 1;
                            }

                            for ($i = 1; $i <= 5; $i++) {
                            ?>
                                <span class="fa fa-star<?php
                                                        if ($i == $stagehalf && ($half > 0 && $half < 1)) {
                                                            echo '-half-o checked';
                                                        }
                                                        ?> " <?php if ($stage >= $i) { ?> style="color:orange;" <?php } ?>></span>
                            <?php
                            }
                            ?>
                        </h3>
                    </center>
                    <center>
                        <h5><?php echo substr($rate, 0, 3); ?> average based on <?php echo $reviews; ?> <?php echo $this->lang->line('reviews'); ?>.</h5>
                    </center> <?php }
                        } ?>
        </div>
    </div>
</div>
<div class="sp2-card sp2-card-sm">
    <div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('staff_id'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['employee_id']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('role'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['user_type']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('designation'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['designation']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('department'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['department']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('epf_no'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['epf_no']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('basic_salary'); ?></div>
    <div class="sp2-info-value"><?php if (!empty($staff['basic_salary'])) {
                                                                                                                            echo amountFormat($staff['basic_salary']);
                                                                                                                        } ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('contract_type'); ?></div>
    <div class="sp2-info-value"><?php
                                                                                                                                if (array_key_exists($staff['contract_type'], $contract_type)) {
                                                                                                                                    echo $contract_type[$staff['contract_type']];
                                                                                                                                }
                                                                                                                                ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('work_shift'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['shift']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('work_location'); ?></div>
    <div class="sp2-info-value"><?php echo $staff['location']; ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('date_of_joining'); ?></div>
    <div class="sp2-info-value"><?php
                                                                                                                                if (!empty($staff["date_of_joining"]) && $staff["date_of_joining"] != '0000-00-00') {
                                                                                                                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($staff['date_of_joining']));
                                                                                                                                }
                                                                                                                                ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('barcode'); ?></div>
    <div class="sp2-info-value"><?php if (file_exists("./uploads/staff_id_card/barcodes/" . $staff['id'] . ".png")) { ?>
                                        <a class="pull-right text-aqua" href="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/barcodes/' . $staff['id'] . '.png'); ?>" target="_blank">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/barcodes/' . $staff['id'] . '.png'); ?>" width="auto" height="auto" /></a>
                                    <?php } ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('qrcode'); ?></div>
    <div class="sp2-info-value"><?php if (file_exists("./uploads/staff_id_card/qrcode/" . $staff['id'] . ".png")) { ?>
                                        <a class="pull-right text-aqua" href="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/' . $staff['id'] . '.png'); ?>" target="_blank">
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/staff_id_card/qrcode/' . $staff['id'] . '.png'); ?>" width="auto" height="auto" class="h-50 qrcodeimg" /></a>
                                    <?php } ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('date_of_leaving'); ?></div>
    <div class="sp2-info-value"><?php

                                                                                                                                echo $this->customlib->dateformat($staff['date_of_leaving']);

                                                                                                                                ?></div>
</div>
<div class="sp2-info-row">
    <div class="sp2-info-label"><?php echo $this->lang->line('disable_date'); ?></div>
    <div class="sp2-info-value"><?php

                                                                                                                            echo $this->customlib->dateformat($staff['disable_at']);

                                                                                                                            ?></div>
</div>
</div>
</div>

            <div class="col-md-9">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="sp2-tabs-nav nav nav-tabs" style="margin-bottom:0;">
                        <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('profile'); ?></a></li>
                        <li class=""><a href="#payroll" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('payroll'); ?></a></li>
                        <li class=""><a href="#leaves" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('leaves'); ?></a></li>
                        <li class=""><a href="#attendance" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('attendance'); ?></a></li>
                        <?php if ($sch_setting->staff_upload_documents) { ?>
                            <li class=""><a href="#documents" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('documents'); ?></a></li>
                        <?php } ?>
                        <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_view')) { ?>
                            <li class=""><a href="#timelineh" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('timeline'); ?></a></li>
                        <?php } ?>
                        <?php if ($staff['user_type'] == 2) {
                        ?>
                            <li class=""><a href="#reviews" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('reviews'); ?></a></li>
                        <?php
                        }
                        $userdata            = $this->customlib->getUserData();
                        $logged_in_User      = $this->customlib->getLoggedInUserData();
                        $logged_in_User_Role = json_decode($this->customlib->getStaffRole());
                        $a                   = false;
                        if ($staff['id'] == $logged_in_User['id']) {
                            $a = true;
                        } elseif ($logged_in_User_Role->id == 7 && $logged_in_User_Role->name == "Super Admin") {
                            if ($staff["role_id"] == 7) {
                                if ($staff["role_id"] == 7 && $staff['id'] != $logged_in_User['id']) {
                                    $a = false;
                                } else {
                                    $a = true;
                                }
                            } else {
                                $a = true;
                            }
                        }

                        ?>
                        <?php

                        if ($enable_disable == 1) {
                            if ($staff["is_active"] == 1) {
                                if ($this->rbac->hasPrivilege('disable_staff', 'can_view')) {
                                    if ($logged_in_User_Role->id == 7) {
                                        if ($a) {
                        ?>
                                            <li class="pull-right"><a class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('disable'); ?>" onclick="disable_staff('<?php echo $id; ?>');"></i> <i class="fa fa-thumbs-o-down"></i></a></li>
                                        <?php
                                        }
                                    } else {
                                        ?>
                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/disablestaff/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('disable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_disable_this_record'); ?>')"></i> <i class="fa fa-thumbs-o-down"></i></a></li>
                                    <?php
                                    }
                                }
                            } else if ($staff["is_active"] == 0) {
                                if ($logged_in_User_Role->id == 7) {
                                    if ($a) {
                                    ?>

                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/delete/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_want_to_delete'); ?>');"></i><i class="fa fa-trash"></i></a></li>
                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/enablestaff/' . $id); ?>" class="text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('enable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable_this_record'); ?>');"><i class="fa fa-thumbs-o-up"></i></a></li>

                                    <?php
                                    }
                                } else {
                                    if ($this->rbac->hasPrivilege('staff', 'can_delete')) {
                                    ?>

                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/delete/' . $id); ?>" class="text-red" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_want_to_delete'); ?>');"></i><i class="fa fa-trash"></i></a></li>
                                    <?php }
                                    if ($this->rbac->hasPrivilege('disable_staff', 'can_view')) {
                                    ?>

                                        <li class="pull-right"><a href="<?php echo base_url('admin/staff/enablestaff/' . $id); ?>" class="text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('enable'); ?>" onclick="return confirm('<?php echo $this->lang->line('are_you_sure_you_want_to_enable_this_record'); ?>');"><i class="fa fa-thumbs-o-up"></i></a></li>

                        <?php
                                    }
                                }
                            }
                        }
                        ?>

                        <li class="pull-right">
                            <?php if ($a) {
                            ?>
                                <a href="#" class="change_password text-green" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('change_password'); ?>"></i> <i class="fa fa-key"></i></a>

                            <?php
                            }
                            ?>
                        </li>
                        <?php
                        $is_self_profile = ($logged_in_User_Role->id == $id || (isset($staff['id']) && $logged_in_User_Role->id == $staff['id']));
                        if ($this->rbac->hasPrivilege('staff', 'can_edit') || ($a && $this->rbac->hasPrivilege('staff_edit_self', 'can_edit'))) {

                            if ($logged_in_User_Role->id == 7) {
                                if ($a) {
                        ?>

                                    <li class="pull-right"><a href="<?php echo base_url('admin/staff/edit/' . $id); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('edit'); ?>" class="text-light"><i class="fa fa-pencil"></i></a></li>
                                <?php
                                }
                            } else {
                                ?>

                                <li class="pull-right"><a href="<?php echo base_url('admin/staff/edit/' . $id); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo $this->lang->line('edit'); ?>" class="text-light"><i class="fa fa-pencil"></i></a></li>
                        <?php
                            }
                        }
                        ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <div class="sp2-card">
    <div class="sp2-card-header"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('contact_details'); ?></div>
    <div class="sp2-card-body">
                                <div class="sp2-data-grid">
                                            <?php if ($sch_setting->staff_phone) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('phone'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['contact_no']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_emergency_contact) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('emergency_contact_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['emergency_contact_no']; ?></div>
</div>
                                            <?php } ?>
                                            <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('email'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['email']; ?></div>
</div>
                                            <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('gender'); ?></div>
    <div class="sp2-data-box-value"><?php echo $this->lang->line(strtolower($staff['gender'])); ?></div>
</div>
                                            <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('date_of_birth'); ?></div>
    <div class="sp2-data-box-value"><?php
                                                    if (!empty($staff["dob"]) && $staff["dob"] != '0000-00-00') {
                                                        echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($staff['dob']));
                                                    }
                                                    ?></div>
</div>
                                            <?php if ($sch_setting->staff_marital_status) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('marital_status'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['marital_status']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_father_name) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('father_name'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['father_name']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_mother_name) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('mother_name'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['mother_name']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_qualification) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('qualification'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['qualification']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_work_experience) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('work_experience'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['work_exp']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_note) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('note'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['note']; ?></div>
</div>
                                                <?php }
                                            $cutom_fields_data = get_custom_table_values($staff['id'], 'staff');
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
                                            ?>
                                        </div>
                            </div>
</div>
                            <div class="sp2-card">
    <div class="sp2-card-header"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('address_details'); ?></div>
    <div class="sp2-card-body">
                                <div class="sp2-data-grid">
                                            <?php if ($sch_setting->staff_current_address) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('current_address'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['local_address']; ?></div>
</div>
                                            <?php }
                                            if ($sch_setting->staff_permanent_address) { ?>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('permanent_address'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['permanent_address']; ?></div>
</div>
                                            <?php } ?>
                                        </div>
                            </div>
</div>
                            <?php if ($sch_setting->staff_account_details) { ?>
                                <div class="sp2-card">
    <div class="sp2-card-header"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('bank_account_details'); ?></div>
    <div class="sp2-card-body">
                                    <div class="sp2-data-grid">
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('account_title'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['account_title']; ?></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('bank_name'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['bank_name']; ?></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('bank_branch_name'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['bank_branch']; ?></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('bank_account_number'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['bank_account_no']; ?></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('ifsc_code'); ?></div>
    <div class="sp2-data-box-value"><?php echo $staff['ifsc_code']; ?></div>
</div>
                                            </div>
                                </div>
</div>
                            <?php }
                            if ($sch_setting->staff_social_media) { ?>
                                <div class="sp2-card">
    <div class="sp2-card-header"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('social_media_link'); ?></div>
    <div class="sp2-card-body">
                                    <div class="sp2-data-grid">
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('facebook_url'); ?></div>
    <div class="sp2-data-box-value"><a href="<?php echo $staff['facebook']; ?>" target="_blank"><?php echo $staff['facebook']; ?></a></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('twitter_url'); ?></div>
    <div class="sp2-data-box-value"><a href="<?php echo $staff['twitter']; ?>" target="_blank"><?php echo $staff['twitter']; ?></a></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('linkedin_url'); ?></div>
    <div class="sp2-data-box-value"><a href="<?php echo $staff['linkedin']; ?>" target="_blank"><?php echo $staff['linkedin']; ?></a></div>
</div>
                                                <div class="sp2-data-box">
    <div class="sp2-data-box-label"><?php echo $this->lang->line('instagram_url'); ?></div>
    <div class="sp2-data-box-value"><a href="<?php echo $staff['instagram']; ?>" target="_blank"><?php echo $staff['instagram']; ?></a></div>
</div>
                                            </div>
                                </div>
</div>
                            <?php } ?>
                        </div>
                        <div class="tab-pane" id="payroll">
                            <div class="sp2-fee-stat-grid">
    <div class="sp2-fee-stat fs-paid">
        <div class="sp2-fee-stat-label"><?php echo $this->lang->line('total_net_salary_paid'); ?></div>
        <div class="sp2-fee-stat-val"><?php
                                            if (!empty($salary["net_salary"])) {
                                                echo $currency_symbol . amountFormat($salary["net_salary"]);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            }
                                            ?></div>
    </div>
    <div class="sp2-fee-stat fs-fine">
        <div class="sp2-fee-stat-label"><?php echo $this->lang->line('total_gross_salary'); ?></div>
        <div class="sp2-fee-stat-val"><?php
                                            if (!empty($salary["basic_salary"])) {
                                                $basic_salary = $salary["basic_salary"] + $salary["earnings"] - $salary["deduction"];
                                                echo $currency_symbol . amountFormat($basic_salary);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            }
                                            ?></div>
    </div>
    <div class="sp2-fee-stat fs-earning" style="background: #e0e7ff; border-color: #c7d2fe;">
        <div class="sp2-fee-stat-label" style="color: #4f46e5;"><?php echo $this->lang->line('total_earning'); ?></div>
        <div class="sp2-fee-stat-val" style="color: #4f46e5;"><?php
                                            if (!empty($salary["earnings"])) {
                                                echo $currency_symbol . amountFormat($salary["earnings"]);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            }
                                            ?></div>
    </div>
    <div class="sp2-fee-stat fs-balance">
        <div class="sp2-fee-stat-label"><?php echo $this->lang->line('total_deduction'); ?></div>
        <div class="sp2-fee-stat-val"><?php
                                            $deduction = $salary["deduction"] + $salary["tax"];

                                            if (!empty($deduction)) {
                                                echo $currency_symbol . amountFormat($deduction);
                                            } else {
                                                echo $currency_symbol . "0.00";
                                            } ?> </div>
    </div>
</div>

                            <div class="sp2-card">
<div class="table-responsive">
                                <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"] . $employee_id; ?></div>
                                <table class="table table-hover table-striped example" data-export-title="<?php echo $staff["name"] . " " . $staff["surname"] . $employee_id; ?>">
                                    <thead>
                                        <tr>
                                            <th class="dt-body-left dt-head-left"><?php echo $this->lang->line('payslip'); ?> #</th>
                                            <th class="text text-left"><?php echo $this->lang->line('month_year'); ?><span></span></th>
                                            <th class="text text-left"><?php echo $this->lang->line('date'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('mode'); ?></th>
                                            <th class="text text-left"><?php echo $this->lang->line('status'); ?></th>
                                            <th class="text text-right"><?php echo $this->lang->line('net_salary'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($staff_payroll as $key => $payroll_value) {

                                            if ($payroll_value["status"] == "paid") {
                                                $label = "class='label label-success'";
                                            } else if ($payroll_value["status"] == "generated") {
                                                $label = "class='label label-warning'";
                                            } else {
                                                $label = "class='label label-default'";
                                            }
                                        ?>
                                            <tr>
                                                <td class="dt-body-left dt-head-left">
                                                    <a data-toggle="popover" href="#" class="detail_popover" data-original-title="" title=""><?php echo $payroll_value['id'] ?></a>
                                                    <div class="fee_detail_popover" style="display: none"><?php echo $payroll_value['remark']; ?></div>
                                                </td>
                                                <td><?php echo $this->lang->line(strtolower($payroll_value['month'])) . " - " . $payroll_value['year']; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($payroll_value['payment_date'])); ?></td>
                                                <td><?php
                                                    if (!empty($payroll_value['payment_mode'])) {
                                                        echo $payment_mode[$payroll_value['payment_mode']];
                                                    }
                                                    ?></td>
                                                <td><span <?php echo $label ?>><?php echo $payroll_status[$payroll_value['status']]; ?></span></td>
                                                <td class="text text-right"><?php echo amountFormat($payroll_value['net_salary']); ?></td>
                                                <td class="dt-body-left dt-head-left">
                                                    <?php if ($payroll_value["status"] == "paid") {
                                                    ?>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_view')
                                                        ) {
                                                        ?>
                                                            <a href="#" onclick="getPayslip('<?php echo $payroll_value["id"]; ?>')" role="button" class="btn btn-primary btn-xs checkbox-toggle edit_setting" data-toggle="tooltip"><?php echo $this->lang->line('view_payslip'); ?></a>

                                                        <?php }else{ echo "&nbsp;";} ?>
                                                    <?php }else { echo "&nbsp;";} ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
</div>
                        </div>

                        <?php if ($sch_setting->staff_upload_documents) {
                        ?>
                            <div class="tab-pane" id="documents">
                                <div class="timeline-header no-border">
                                    <div class="row">
                                        <?php if ((empty($staff["resume"])) && (empty($staff["joining_letter"])) && (empty($staff["resignation_letter"])) && (empty($staff["other_document_file"]))) {
                                        ?>
                                            <div class="col-md-12">
                                                <div class="alert alert-info"><?php echo $this->lang->line("no_record_found"); ?></div>
                                            </div>
                                        <?php } else {
                                        ?>
                                            <?php if (!empty($staff["resume"])) { ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('resume'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'resume'; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/resume"; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-text-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                            <?php if (!empty($staff["joining_letter"])) {
                                            ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('joining_letter'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'joining_letter'; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/joining_letter"; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i>
                                                            </a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-archive-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                            <?php if (!empty($staff["resignation_letter"])) {
                                            ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('resignation_letter'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'resignation_letter'; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/resignation_letter"; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-archive-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                            <?php if (!empty($staff["other_document_file"])) {
                                            ?>
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="staffprofile">
                                                        <h5><?php echo $this->lang->line('other_documents'); ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/staff/download/<?php echo $staff['id'] . "/" . 'other_document_file'; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                            <i class="fa fa-download"></i></a>
                                                        <?php
                                                        if (
                                                            $this->rbac->hasPrivilege('staff', 'can_edit')
                                                        ) {
                                                        ?>
                                                            <a href="<?php echo base_url(); ?>admin/staff/doc_delete/<?php echo $staff['id'] . "/other_document_file" ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                <i class="fa fa-remove"></i></a>
                                                        <?php } ?>
                                                        <div class="icon">
                                                            <i class="fa fa-file-archive-o"></i>
                                                        </div>
                                                    </div>
                                                </div><!--./col-md-3-->
                                            <?php } ?>
                                        <?php } ?>
                                    </div><!--./row-->
                                </div>
                            </div>
                        <?php } ?>

                        <div class="tab-pane" id="timelineh">
                            <div><?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_add')) { ?>
                                    <input type="button" id="myTimelineButton" class="btn btn-sm btn-primary pull-right " value="<?php echo $this->lang->line('add') ?>" />
                                <?php } ?>
                            </div>
                            <br />
                            <div class="timeline-header no-border">
                                <div id="timeline_list">
                                    <?php
                                    if (empty($timeline_list)) {
                                    ?>
                                        <br />
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                    <?php } else {
                                    ?>
                                        <ul class="timeline timeline-inverse">
                                            <?php
                                            foreach ($timeline_list as $key => $value) {
                                            ?>
                                                <li class="time-label">
                                                    <span class="bg-blue"> <?php
                                                                            echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['timeline_date']));
                                                                            ?></span>
                                                </li>
                                                <li>
                                                    <i class="fa fa-list-alt bg-blue"></i>
                                                    <div class="timeline-item">
                                                        <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_delete')) { ?>
                                                            <span class="time">
                                                                <a class="text-right" data-toggle="tooltip" title="" onclick="delete_timeline('<?php echo $value['id']; ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash"></i></a>
                                                            </span>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('staff_timeline', 'can_edit')) { ?>
                                                            <span class="time">
                                                                <a data-toggle="tooltip" class="pull-right edit_timeline  text-right" data-id="<?php echo $value["id"]; ?>" data-original-title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                            </span>
                                                        <?php } ?>

                                                        <?php if (!empty($value["document"])) { ?>
                                                            <span class="time"><a class="text-right" data-toggle="tooltip" title="" href="<?php echo base_url() . "admin/timeline/download_staff_timeline/" . $value["id"] ?>" data-original-title="Download"><i class="fa fa-download"></i></a></span>
                                                        <?php } ?>
                                                        <h3 class="timeline-header text-aqua"> <?php echo $value['title']; ?> </h3>
                                                        <div class="timeline-body">
                                                            <?php echo $value['description']; ?>

                                                        </div>
                                                    </div>
                                                </li>
                                            <?php } ?>
                                            <li><i class="fa fa-clock-o bg-gray"></i></li>
                                        <?php } ?>
                                        </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="attendance">
                            <div class="sp2-att-grid">
                                <div class="sp2-att-box ab-present">
                                    <div class="sp2-att-box-val"><?php echo count($countAttendance['Present']); ?></div>
                                    <div class="sp2-att-box-label"><?php echo $this->lang->line('total_present'); ?></div>
                                </div>
                                <div class="sp2-att-box ab-late">
                                    <div class="sp2-att-box-val"><?php echo count($countAttendance['Late']); ?></div>
                                    <div class="sp2-att-box-label"><?php echo $this->lang->line('total_late'); ?></div>
                                </div>
                                <div class="sp2-att-box ab-absent">
                                    <div class="sp2-att-box-val"><?php echo count($countAttendance['Absent']); ?></div>
                                    <div class="sp2-att-box-label"><?php echo $this->lang->line('total_absent'); ?></div>
                                </div>
                                <div class="sp2-att-box ab-halfday">
                                    <div class="sp2-att-box-val"><?php echo count($countAttendance['Half Day']); ?></div>
                                    <div class="sp2-att-box-label"><?php echo $this->lang->line('total_half_day'); ?></div>
                                </div>
                                <div class="sp2-att-box ab-holiday">
                                    <div class="sp2-att-box-val"><?php echo count($countAttendance['Holiday']); ?></div>
                                    <div class="sp2-att-box-label"><?php echo $this->lang->line('total_holiday'); ?></div>
                                </div>
                                <div class="sp2-att-box ab-halfday">
                                    <div class="sp2-att-box-val"><?php echo count($countAttendance['Half Day Second Shift']); ?></div>
                                    <div class="sp2-att-box-label"><?php echo $this->lang->line('half_day_second_shift'); ?></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3">
                                    <form id="" action="" method="">
                                        <div class="form-group">
                                            <label class="sess18"><?php echo $this->lang->line('year'); ?></label>
                                            <div class="sessyearbox">
                                                <select class="form-control" style="margin-top: -5px;" name="year" onchange="ajax_attendance('<?php echo $staff["id"]; ?>', this.value)">
                                                    <?php foreach ($yearlist as $yearkey => $yearvalue) {
                                                    ?>
                                                        <option <?php
                                                                if ($yearvalue["year"] == date("Y")) {
                                                                    echo "selected";
                                                                }
                                                                ?> value="<?php echo $yearvalue["year"]; ?>"><?php echo $yearvalue["year"]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('year'); ?></span>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-9 col-sm-9">
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
                            <div style="position: relative;" class="row">
                                <div class="modal_inner_loader displaynone"></div>
                                <div class="sp2-card">
<div id="ajaxattendance" class="table-responsive mt10">
                                 
                                    <table class="table table-striped table-bordered table-hover attendancetable" data-export-title="<?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"]; ?>" id="attendancetable">
                                        <thead>
                                            <tr>
                                                <th>
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
                                            $j = 0;

                                            for ($i = 1; $i <= 31; $i++) {
                                            ?>
                                                <tr>
                                                    <td><?php echo sprintf("%02d", $i) ?></td>
                                                    <?php
                                                    foreach ($monthlist as $key => $value) {
                                                        $datemonth = date("m", strtotime($key));
                                                        $att_dates = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);
                                                    ?>
                                                        <td>
                                                            <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php
                                                                                                                                                                                if (array_key_exists($att_dates, $resultlist)) {
                                                                                                                                                                                    if (!empty($resultlist[$att_dates]["key"])) {
                                                                                                                                                                                        echo $resultlist[$att_dates]["key"];
                                                                                                                                                                                    } else {
                                                                                                                                                                                    }
                                                                                                                                                                                }
                                                                                                                                                                                ?></a>
                                                            </span>
                                                        </td>
                                                    <?php
                                                    } ?>
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
                        </div>
                        <?php if ($staff['user_type'] == 2) {
                        ?>
                            <div class="tab-pane" id="reviews">
                                <div class="row">
                                </div>
                                <div class="timeline-header no-border">
                                    <div class="table-responsive" style="clear: both;">
                                        <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"]; ?></div>
                                        <table class="table table-striped table-bordered table-hover example">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('role'); ?></th>
                                                    <th><?php echo $this->lang->line('rate'); ?></th>
                                                    <th><?php echo $this->lang->line('comment'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($user_reviewlist as $value) { ?>
                                                    <tr>
                                                        <td><?php
                                                            if ($value['role'] == 'student') {
                                                                echo $value['firstname'] . " " . $value['lastname'];
                                                            } else {
                                                                echo $value['guardian_name'];
                                                            }
                                                            ?></td>
                                                        <td><?php echo $value['role']; ?></td>
                                                        <td><?php
                                                            $j = 5;
                                                            for ($i = 1; $i <= $j; $i++) {
                                                            ?>
                                                                <span class="fa fa-star" <?php if ($i <= $value['rate']) { ?> style="color:orange" <?php } ?>></span>
                                                            <?php }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $value['comment']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="tab-pane" id="leaves">
                            <div class="sp2-fee-stat-grid">
                                <?php foreach ($leavedetails as $ldkey => $ldvalue) {
                                ?>
                                    <?php if (!empty($ldvalue["alloted_leave"])) {
                                    ?>
                                        <div class="sp2-fee-stat fs-total" style="background: #fff8e6; border-color: #fce4a8;">
                                            <div class="sp2-fee-stat-label" style="color: #d09435;"><?php echo $ldvalue["type"] . " (" . $ldvalue["alloted_leave"] . ")"; ?></div>
                                            <div class="sp2-fee-stat-val" style="color: #d09435; font-size: 13px;">
                                                <?php echo $this->lang->line('used'); ?>: <?php
                                                                                            if (!empty($ldvalue["approve_leave"])) {
                                                                                                echo $ldvalue["approve_leave"];
                                                                                            } else {
                                                                                                echo "0";
                                                                                            }
                                                                                            ?><br>
                                                <?php echo $this->lang->line('available'); ?>: <?php echo $ldvalue["alloted_leave"] - $ldvalue["approve_leave"] ?>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                            <div class="sp2-card">
                                <div class="download_label"><?php echo $this->lang->line('details_for'); ?> <?php echo $staff["name"] . " " . $staff["surname"] . $staff['employee_id']; ?></div>
                                <div class="table-responsive overflow-visible">
                                    <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $staff["name"] . " " . $staff["surname"] . $employee_id; ?>">
                                        <thead>
                                            <th><?php echo $this->lang->line('leave_type'); ?></th>
                                            <th><?php echo $this->lang->line('leave_date'); ?></th>
                                            <th><?php echo $this->lang->line('days'); ?></th>
                                            <th><?php echo $this->lang->line('apply_date'); ?></th>
                                            <th><?php echo $this->lang->line("status") ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line("action") ?></th>
                                        </thead>
                                        <tbody>
                                            <?php
                                            foreach ($staff_leaves as $key => $value) {

                                                if ($value["status"] == "approved" || $value["status"] == "approve") {
                                                    $status1 = "approve";
                                                    $label = "class='label label-success'";
                                                } else if ($value["status"] == "pending") {
                                                    $status1 = "pending";
                                                    $label = "class='label label-warning'";
                                                } else if ($value["status"] == "disapproved" || $value["status"] == "disapprove") {
                                                    $status1 = "disapprove";
                                                    $label = "class='label label-danger'";
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $value["type"]; ?></td>
                                                    <td class="white-space-nowrap"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['leave_from'])) . " - " . date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['leave_to'])); ?></td>
                                                    <td><?php echo $value["leave_days"]; ?></td>
                                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])); ?></td>
                                                    <td><small style="text-transform: capitalize;" <?php echo $label ?>><?php echo $status[$status1]; ?></small></td>
                                                    <td class="text-right white-space-nowrap"><a href="#leavedetails" onclick="getRecord('<?php echo $value["id"] ?>')" role="button" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>"><i class="fa fa-eye"></i></a>
                                                        <?php if (!empty($value['document_file'])) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/leaverequest/downloadleaverequestdoc/<?php echo $value['staff_id'] . "/" . $value['id']; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('download'); ?>">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<div id="leavedetails" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-dialog modal-dialog2 modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?php echo $this->lang->line('details'); ?></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form role="form" id="leavedetails_form" action="">
                            <div class="col-md-12 table-responsive">
                                <table class="table mb0 table-striped table-bordered examples">
                                    <tr>
                                        <th width="15%"><?php echo $this->lang->line('name'); ?></th>
                                        <td width="35%"><span id='name'></span></td>
                                        <th width="15%"><?php echo $this->lang->line('staff_id'); ?></th>
                                        <td width="35%"><span id="employee_id"></span>
                                            <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('leave'); ?></th>
                                        <td><span id='leave_from'></span> - <label for="exampleInputEmail1"> </label><span id='leave_to'> </span> (<span id='days'></span>)
                                            <span class="text-danger"><?php echo form_error('leave_from'); ?></span>
                                        </td>
                                        <th><?php echo $this->lang->line('leave_type'); ?></th>
                                        <td><span id="leave_type"></span>
                                            <input id="leave_request_id" name="leave_request_id" placeholder="" type="hidden" class="form-control" />
                                            <span class="text-danger"><?php echo form_error('leave_request_id'); ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <td>
                                            <span id="status"></span>
                                        </td>
                                        <th><?php echo $this->lang->line('apply_date'); ?></th>
                                        <td><span id="applied_date"></span></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $this->lang->line('reason'); ?></th>
                                        <td><span id="reason"> </span></td>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <td>
                                            <span id="remark"> </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myTimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title"><?php echo $this->lang->line('add_timeline'); ?> </h4>
            </div>
            <form id="timelineform" name="timelineform" method="post" action="<?php echo base_url() . "admin/timeline/add_staff_timeline" ?>" enctype="multipart/form-data">
                <div class="modal-body pt0 pb0">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div id='timeline_hide_show'>
                        <input type="hidden" name="staff_id" value="<?php echo $staff["id"] ?>" id="staff_id">
                        <h4></h4>
                        <div class="">
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control" />
                                <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                <input id="timeline_date" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getSchoolDateFormat())); ?>" placeholder="" type="text" class="form-control date" />
                                <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('description'); ?></label>
                                <textarea id="timeline_desc" name="timeline_desc" placeholder="" class="form-control"></textarea>
                                <span class="text-danger"><?php echo form_error('description'); ?></span>
                            </div>
                            <div class="form-group">
                                <label for=""><?php echo $this->lang->line('attach_document'); ?></label>
                                <div class=""><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file" class="filestyle form-control" data-height="40" value="<?php echo set_value('timeline_doc'); ?>" />
                                    <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-align--top"><?php echo $this->lang->line('visible_to_this_person'); ?></label>
                                <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="clear:both">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>

                    <button type="reset" id="reset" style="display: none" class="btn btn-info pull-right"><?php echo $this->lang->line('reset'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title_logindetail"></h4>
            </div>
            <div class="modal-body_logindetail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="payslipview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?> <span id="print" class=></span></h4>
            </div>
            <div class="modal-body" id="testdata">

            </div>
        </div>
    </div>
</div>

<div id="changepwdmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('change_password'); ?></h4>
            </div>
            <form method="post" id="changepassbtn" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('new_password'); ?> <small class="req"> *</small></label>
                        <input type="password" class="form-control" name="new_pass" id="pass">
                    </div>
                    <div class="form-group">
                        <label for="pwd"><?php echo $this->lang->line('confirm_password'); ?> <small class="req"> *</small></label>
                        <input type="password" class="form-control" name="confirm_pass" id="pwd">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="disablemodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('disable_staff'); ?></h4>
            </div>
            <form method="post" id="disablebtn" action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="email"><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                        <input type="text" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" name="date" readonly="readonly">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
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
    function disable_staff(id) {
        $('#disablemodal').modal({
            backdrop: 'static',
            keyboard: false,
            show: true

        });
    }

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

    $("#timelineform").on('submit', (function(e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/timeline/add_staff_timeline") ?>",
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

    $(document).ready(function(e) {
        $("#disablebtn").on('submit', (function(e) {
            var staff_id = $("#staff_id").val();
            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url('admin/staff/disablestaff/') ?>" + staff_id,
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    if (data.status == "fail") {
                        var message = "";
                        $.each(data.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(data.message);
                        window.location.reload(true);
                    }

                },
                error: function(e) {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                    console.log(e);
                }
            });
        }));
    });

    $(document).ready(function(e) {
        $("form#changepassbtn").on('submit', (function(e) {

            var staff_id = $("#staff_id").val();
            var form = $(this);
            var $this = form.find("button[type=submit]:focus");
            e.preventDefault();

            $.ajax({
                url: "<?php echo site_url('admin/staff/change_password/') ?>" + staff_id,
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
                    $this.button('loading');
                },
                error: function(xhr) { // if error occured
                    alert("Error occured.please try again");
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }



            });
        }));
    });

    function delete_timeline(id) {
        var staff_id = $("#staff_id").val();
        if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {

            $.ajax({
                url: '<?php echo base_url(); ?>admin/timeline/delete_staff_timeline/' + id,
                success: function(res) {
                    $.ajax({
                        url: '<?php echo base_url(); ?>admin/timeline/staff_timeline/' + staff_id,
                        success: function(res) {
                            $('#timeline_list').html(res);
                            successMsg('<?php echo $this->lang->line('delete_message'); ?>');
                        },
                        error: function() {
                            alert("<?php echo $this->lang->line('fail'); ?>");
                        }
                    });
                },
                error: function() {
                    alert("<?php echo $this->lang->line('fail'); ?>");
                }
            });
        }
    }


    let datatableFn=()=>{
                displayDataTable('attendancetable',[
            {
            targets: [-1,0,1,2,3,4,5,6,7,8,9,10,11,12],
            orderable: false,
            className: 'dt-body-center dt-head-center'
                }
            ], 50 ,[], false,  false, false,  false,'landscape',[]);
    }
    $(document).ready(function() {
        datatableFn();
        $(document).on('click', '.change_password', function() {
            $('#changepwdmodal').modal('show');
        }); 
    });
</script>

<script>
    $(document).ready(function() {
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

    function getRecord(id) {
        $('input:radio[name=status]').attr('checked', false);
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/leaverequest/leaveRecord',
            type: 'POST',
            data: {
                id: id
            },
            dataType: "json",
            success: function(result) {

                $('inputs[name="leave_request_id"]').val(result.id);
                $('#name').html(result.name + ' ' + result.surname);
                $('#leave_from').html(result.leavefrom);
                $('#leave_to').html(result.leaveto);
                $('#leave_type').html(result.type);
                $('#reason').html(result.employee_remark);
                $('#applied_date').html(result.date);
                $('#days').html(result.leave_days + ' Days');
                $("#remark").html(result.admin_remark);
                $("#employee_id").html(' ' + result.employee_id);
                $("#status").html(' ' + result.leave_status);

            }
        });

        $('#leavedetails').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    };

    function ajax_attendance(id, year) {

        $.ajax({
            url: baseurl + 'admin/staff/ajax_attendance',
            type: 'POST',
            data: {
                "id": id,
                "year": year
            },
            dataType: "JSON",
            beforeSend: function() {
                $('.modal_inner_loader').css({
                    'display': 'block'
                });
            },
            success: function(result) {
                $("#ajaxattendance").html(result.page);
                
                $('.total_present').text(result.countAttendance.present);
                $('.total_late').text(result.countAttendance.late);
                $('.total_absent').text(result.countAttendance.absent);
                $('.total_half_day').text(result.countAttendance.half_day);
                $('.total_holiday').text(result.countAttendance.holiday);
                $('.total_half_day_second_shift').text(result.countAttendance.half_day_second_shift);
                   datatableFn();
                $('.modal_inner_loader').fadeOut("slow");
            },
            error: function(xhr) { // if error occured
                alert("Error occured.please try again");
                $('.modal_inner_loader').fadeOut("slow");
            },
            complete: function() {
                $('.modal_inner_loader').fadeOut("slow");
            }


        });
    }

   function getPayslip(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },

            success: function(result) {
                $("#print").html("<a href='#' class='pull-right modal-title moprintblack mt3 mright8 rtl-mleft8' onclick='printData(" + id + ")'  title='<?php echo $this->lang->line('print'); ?>'><i class='fa fa-print'></i></a>");
                $("#testdata").html(result);

            }
        });

        $('#payslipview').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    };

    function printData(id) {
        var base_url = '<?php echo base_url() ?>';
        $.ajax({
            url: base_url + 'admin/payroll/payslipView',
            type: 'POST',
            data: {
                payslipid: id
            },
            success: function(result) {
                $("#testdata").html(result);
                popup(result);
            }
        });
    }

    function popup(data, winload = false) {
		var newWin = window.open('', 'Print-Window');
	
		newWin.document.open();
		newWin.document.write('<html>');
		newWin.document.write('<head>');
		newWin.document.write('<title></title>');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/bootstrap/css/bootstrap.min.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/font-awesome.min.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/sh-print.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/ionicons.min.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/AdminLTE.min.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/dist/css/skins/_all-skins.min.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/iCheck/flat/blue.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/morris/morris.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/datepicker/datepicker3.css">');
		newWin.document.write('<link rel="stylesheet" href="' + baseurl + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
		newWin.document.write('</head>');
		newWin.document.write('<body onload="window.print()">');
        newWin.document.write(data);
        newWin.document.write('</body>');
		newWin.document.write('</html>');
		newWin.document.close();
	
		// Wait until the new window has fully loaded
		newWin.onload = function () {
			setTimeout(function () {
				newWin.focus();
				newWin.print();
				newWin.close();
				if (winload) {
					window.location.reload(true);
				}
			}, 500); // small delay ensures rendering is complete
		};
	
		return true;
	}

     
</script>

<script>
    $('.edit_timeline').click(function() {
        $('#edittimelineModal').modal('show');
        var id = $(this).attr('data-id');
        $.ajax({
            url: "<?php echo site_url("admin/timeline/getstaffsingletimeline") ?>",
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
            url: "<?php echo site_url("admin/timeline/editstafftimeline") ?>",
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