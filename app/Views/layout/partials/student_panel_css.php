<style type="text/css">
/* =========================================================
   Student / Parent panel – aligns content with the admin panel look
   (shared header/sidebar styles live in modern_header_css / modern_sidebar_assets)
   ========================================================= */

body, .content-wrapper, .main-footer {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
}

.content-wrapper {
    background-color: #f8fafc !important;
}

/* Top navbar icons */
.headertopmenu .topnav-icon {
    font-size: 18px;
    font-weight: bold;
    position: relative;
    top: 2px;
    color: #334155;
}
.headertopmenu > li > a:hover .topnav-icon {
    color: var(--primary-theme-color, #4f46e5);
}
.sm-drop-down .mobile-menu-icon {
    margin-right: 10px;
    width: 16px;
    text-align: center;
}

/* Unread badge inside a sidebar item */
.sidebar-menu > li > a > .sidebar-badge {
    margin-left: auto;
    font-size: 10px;
    border-radius: 10px;
    padding: 3px 7px;
}

/* ---------- Cards (AdminLTE .box) ---------- */
.content .box {
    background: #ffffff;
    border: 1px solid #e2e8f0 !important;
    border-top: 1px solid #e2e8f0 !important;
    border-radius: 14px !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04) !important;
    overflow: hidden;
}

.content .box > .box-header {
    padding: 18px 22px 14px 22px;
    border-bottom: 1px solid #f1f5f9 !important;
    background: #ffffff;
}

.content .box > .box-header .box-title {
    font-family: 'Inter', -apple-system, sans-serif;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    text-transform: uppercase;
    letter-spacing: 1.2px;
}

.content .box > .box-body {
    padding: 16px 22px;
}

/* Inner list rows used by dashboard widgets (classes, teachers, notices) */
.content .box .direct-chat-messages {
    height: 330px;
}
.content .box .user-progress,
.content .box .box-widget,
.content .box .info-box {
    border-radius: 10px;
}

/* Tables inside cards */
.content .box table.table > thead > tr > th {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
}
.content .box table.table > tbody > tr > td {
    border-top: 1px solid #f1f5f9;
    color: #334155;
}

/* Progress bars */
.content .progress {
    height: 6px;
    border-radius: 6px;
    background: #f1f5f9;
    box-shadow: none;
}
.content .progress .progress-bar {
    border-radius: 6px;
    background-color: var(--primary-theme-color, #4f46e5);
}

/* Buttons follow the theme accent */
.content .btn-primary {
    border-radius: 8px;
}

.main-footer {
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 12px;
}

/* ---------- Page titles (legacy content-header / box titles) ---------- */
.content-header > h1 { font-size: 20px; font-weight: 700; color: #0f172a; }
.content-header > h1:empty, .content-header > h1:has(> small:empty) { display: none; }
.content .box > .box-header .box-tools .btn,
.content .btn { border-radius: 8px; font-weight: 500; }
.content .btn-xs { padding: 4px 10px; }

/* ---------- Forms & modals ---------- */
.content .form-control, .modal .form-control {
    border-radius: 10px;
    border-color: #e2e8f0;
    box-shadow: none;
    min-height: 38px;
}
.content .form-control:focus, .modal .form-control:focus {
    border-color: var(--primary-theme-color, #6366f1);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
}
.modal-content { border-radius: 16px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 40px rgba(15, 23, 42, 0.18); }
.modal-header { border-bottom: 1px solid #f1f5f9; }
.modal-footer { border-top: 1px solid #f1f5f9; }

/* ---------- Small reusable pieces for redesigned pages ---------- */
.sd-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    padding: 22px 24px;
    margin-bottom: 20px;
}
@media (max-width: 767px) { .sd-card { padding: 16px; border-radius: 14px; margin-bottom: 12px; } }
.sp-page-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 0 0 18px; flex-wrap: wrap; }
.sp-page-title { margin: 0; font-size: 22px; font-weight: 700; color: #0f172a; letter-spacing: -0.3px; }
.sp-page-sub { margin: 2px 0 0; font-size: 13px; color: #64748b; }
.sp-btn {
    display: inline-flex; align-items: center; gap: 6px;
    border: 1px solid #cbd5e1; border-radius: 10px; padding: 8px 14px;
    font-size: 13px; font-weight: 500; color: #1e293b !important; background: #fff;
    text-decoration: none !important; cursor: pointer; transition: all .15s ease;
}
.sp-btn:hover { background: #f1f5f9; }
.sp-btn-primary { background: var(--primary-theme-color, #6366f1); border-color: transparent; color: #fff !important; }
.sp-btn-primary:hover { filter: brightness(0.95); background: var(--primary-theme-color, #6366f1); }
.sp-tiles { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
.sp-tile { border: 1px solid; border-radius: 12px; padding: 14px 16px; background: #fff; }
.sp-tile-label { display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: .3px; }
.sp-tile-value { display: block; font-size: 22px; font-weight: 700; color: #0f172a; margin-top: 2px; }
.sp-tile-blue   { background: #f5f8ff; border-color: #dbe5ff; } .sp-tile-blue .sp-tile-label { color: #1d4ed8; }
.sp-tile-green  { background: #f3fdf8; border-color: #c6f1dc; } .sp-tile-green .sp-tile-label { color: #047857; }
.sp-tile-amber  { background: #fffbef; border-color: #fbe6b3; } .sp-tile-amber .sp-tile-label { color: #b45309; }
.sp-tile-red    { background: #fff5f5; border-color: #fdd5d5; } .sp-tile-red .sp-tile-label { color: #b91c1c; }
.sp-tile-purple { background: #faf6ff; border-color: #eadcfd; } .sp-tile-purple .sp-tile-label { color: #7e22ce; }

/* ---------- Mobile bottom tab bar (PWA) ---------- */
.pwa-tabbar { display: none; }
.pwa-install {
    position: fixed; right: 16px; bottom: 20px; z-index: 1040;
    border: 0; border-radius: 999px; padding: 10px 16px;
    background: #0f172a; color: #fff; font-size: 13px; font-weight: 600;
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.25);
}
.pwa-install[hidden] { display: none; }

@media (max-width: 767px) {
    .pwa-tabbar {
        display: flex;
        position: fixed; left: 0; right: 0; bottom: 0; z-index: 1030;
        background: rgba(255, 255, 255, 0.96);
        backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
        border-top: 1px solid #e2e8f0;
        padding: 6px 4px calc(6px + env(safe-area-inset-bottom));
    }
    .pwa-tab {
        flex: 1; display: flex; flex-direction: column; align-items: center; gap: 3px;
        padding: 4px 2px; border-radius: 12px;
        color: #64748b !important; text-decoration: none !important;
        font-size: 10.5px; font-weight: 500; line-height: 1.2;
        -webkit-tap-highlight-color: transparent;
    }
    .pwa-tab i { font-size: 21px; line-height: 1; }
    .pwa-tab span { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }
    .pwa-tab.active { color: var(--primary-theme-color, #6366f1) !important; font-weight: 600; }
    .pwa-tab.active i { transform: translateY(-1px); }
    .pwa-install { bottom: calc(76px + env(safe-area-inset-bottom)); }

    body .content-wrapper { padding-top: 0 !important; padding-bottom: calc(72px + env(safe-area-inset-bottom)); }
    .main-footer { display: none; }
    .content { padding: 12px !important; }
    .content-header { padding: 12px 12px 0; }
    .content .box > .box-header { padding: 14px 16px 12px; }
    .content .box > .box-body { padding: 12px 14px; }
    .sp-page-title { font-size: 19px; }
    .sp-tiles { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
    .sp-tile-value { font-size: 18px; }

    /* Compact top bar: one row, school name truncates, secondary icons live in the ⋮ menu */
    .main-header .logo { display: none !important; }
    .main-header { position: sticky; top: 0; z-index: 1020; }
    .sidebar-session { font-size: 14px !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 52vw; display: block !important; line-height: 50px; }

    /* Tables become stacked cards on phones (labels filled in by student_panel_js) */
    .content table.sp-cards, .content table.sp-cards tbody { display: block; width: 100%; }
    .content table.sp-cards thead, .content table.sp-cards tr.sp-head-row { display: none; }
    .content table.sp-cards tr {
        display: block; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px;
        margin-bottom: 10px; padding: 6px 12px;
    }
    .content table.sp-cards td {
        display: flex; justify-content: space-between; align-items: flex-start; gap: 12px;
        border: 0 !important; border-bottom: 1px solid #f1f5f9 !important;
        padding: 8px 0 !important; text-align: right !important; width: auto !important;
    }
    .content table.sp-cards td:last-child { border-bottom: 0 !important; }
    .content table.sp-cards td::before {
        content: attr(data-label); flex-shrink: 0; max-width: 45%;
        font-size: 12px; font-weight: 600; color: #64748b; text-align: left;
    }
    .content table.sp-cards td[data-label=""]::before { display: none; }
    .content table.sp-cards td:empty { display: none; }
    .content .table-responsive { border: 0; overflow-x: visible; }
    .content table.sp-cards td[colspan] { justify-content: center; text-align: center !important; }
    .content .dt-buttons, .content .dt-length { display: none !important; }

    /* Tab strips scroll sideways instead of wrapping */
    .content .nav-tabs {
        display: flex; flex-wrap: nowrap; overflow-x: auto; overflow-y: hidden;
        scrollbar-width: none; -webkit-overflow-scrolling: touch;
    }
    .content .nav-tabs::-webkit-scrollbar { display: none; }
    .content .nav-tabs > li { float: none; flex: 0 0 auto; }
    .content .nav-tabs > li > a { white-space: nowrap; }
}
</style>
