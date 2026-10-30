<style>
/* =========================================================
   MODERN HORIZONTAL SIDEBAR (ICON & NAME IN SAME ROW)
   ========================================================= */

/* Primary Sidebar Container (Desktop Layout) */
@media (min-width: 768px) {
    .main-sidebar {
        position: fixed !important;
        top: 0 !important;
        bottom: 0 !important;
        left: 0 !important;
        height: 100vh !important;
        width: 240px !important;
        padding-top: 65px !important;
        border-right: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        box-shadow: 2px 0 12px rgba(15, 23, 42, 0.03) !important;
        z-index: 810 !important;
    }

    .sidebar {
        height: calc(100vh - 65px) !important;
        overflow-y: auto !important;
        padding-bottom: 55px !important;
    }
}

/* Elegant Scrollbars */
.main-sidebar, .sidebar, .sidebar-menu, .treeview-menu, .slimScrollDiv {
    scrollbar-width: thin !important;
    scrollbar-color: #cbd5e1 transparent !important;
}

.main-sidebar ::-webkit-scrollbar,
.sidebar-menu ::-webkit-scrollbar,
.treeview-menu ::-webkit-scrollbar,
.slimScrollDiv ::-webkit-scrollbar {
    width: 4px !important;
}
.main-sidebar ::-webkit-scrollbar-track,
.sidebar-menu ::-webkit-scrollbar-track,
.treeview-menu ::-webkit-scrollbar-track,
.slimScrollDiv ::-webkit-scrollbar-track {
    background: transparent !important;
}
.main-sidebar ::-webkit-scrollbar-thumb,
.sidebar-menu ::-webkit-scrollbar-thumb,
.treeview-menu ::-webkit-scrollbar-thumb,
.slimScrollDiv ::-webkit-scrollbar-thumb {
    background: #cbd5e1 !important;
    border-radius: 4px !important;
}

div.slimScrollBar {
    background: #cbd5e1 !important;
    width: 4px !important;
    border-radius: 4px !important;
    opacity: 0.8 !important;
}
div.slimScrollRail {
    display: none !important;
}

.sidebar-menu {
    margin-top: 0 !important;
    padding: 0 4px 50px 4px !important;
}

/* Primary Sidebar Item Links (Icon Left, Name Right in Same Row) */
.sidebar-menu > li > a {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
    font-weight: 500 !important;
    color: #334155 !important;
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: flex-start !important;
    padding: 8px 12px !important;
    margin: 3px 8px !important;
    border-radius: 10px !important;
    text-align: left !important;
    white-space: nowrap !important;
    height: auto !important;
    cursor: pointer !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

/* Primary Item Hover State */
.sidebar-menu > li:hover > a {
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
}

.sidebar-menu > li:hover > a > i:first-child {
    transform: scale(1.05) !important;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08) !important;
}

/* Modern Squircle Icon Container Box */
.sidebar-menu > li > a > i:first-child {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 36px !important;
    height: 36px !important;
    border-radius: 10px !important;
    font-size: 18px !important;
    margin-right: 12px !important;
    margin-bottom: 0 !important;
    flex-shrink: 0 !important;
    position: relative !important;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
}

/* Eliminate font-awesome / ionicon pseudo-element overlays causing artifact circles */
.sidebar-menu > li > a > i[class*="icon-"]::before {
    font-family: 'lucide' !important;
}
.sidebar-menu > li > a > i::after {
    content: none !important;
    display: none !important;
}

/* Primary Item Title Text */
.sidebar-menu > li > a > span {
    font-size: 13px !important;
    line-height: 1.3 !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
    display: block !important;
    color: #334155 !important;
    letter-spacing: -0.1px !important;
    flex: 1 !important;
}

/* Right Chevron Arrow in Row */
.sidebar-menu > li > a > .menu-arrow,
.sidebar-menu > li > a > .icon-chevron-right {
    margin-left: auto !important;
    font-size: 13px !important;
    color: #94a3b8 !important;
    transition: transform 0.2s ease !important;
    display: inline-block !important;
}

.sidebar-menu > li.active > a > .menu-arrow,
.sidebar-menu > li.active > a > .icon-chevron-right {
    color: var(--primary-theme-color, #4f46e5) !important;
    transform: rotate(90deg) !important;
}

/* Active State for Primary Menu Category */
.sidebar-menu > li.active > a {
    background-color: rgba(99, 102, 241, 0.08) !important;
    color: var(--primary-theme-color, #4f46e5) !important;
    font-weight: 600 !important;
}

.sidebar-menu > li.active > a > span {
    color: var(--primary-theme-color, #4f46e5) !important;
    font-weight: 600 !important;
}

.sidebar-menu > li.active > a > i:first-child {
    filter: brightness(0.95) saturate(1.2) !important;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.4), 0 2px 6px rgba(0, 0, 0, 0.08) !important;
}

/* =========================================
   Secondary Flyout Submenu Panel
   ========================================= */

/* Desktop Secondary Flyout Submenu Panel */
@media (min-width: 768px) {
    .sidebar-menu .treeview-menu {
        position: fixed !important;
        left: 240px !important;
        top: 50px !important;
        width: 250px !important;
        height: calc(100vh - 50px) !important;
        background-color: #ffffff !important;
        border-right: 1px solid #e2e8f0 !important;
        box-shadow: 8px 0 28px rgba(15, 23, 42, 0.08), 2px 0 6px rgba(15, 23, 42, 0.03) !important;
        z-index: 1020 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
        display: none !important;
    }
}

/* Mobile Submenu Full Panel Overlay (Inside 280px Mobile Drawer) */
@media (max-width: 767px) {
    .sidebar-menu .treeview-menu {
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        height: 100vh !important;
        background-color: #ffffff !important;
        z-index: 100000 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
        display: none !important;
        box-shadow: none !important;
        border: none !important;
    }
}

.sidebar-menu .treeview-menu.is-open {
    display: block !important;
}

/* Flyout Panel Header */
.sidebar-menu .treeview-menu .submenu-header {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    padding: 14px 18px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    background: #f8fafc !important;
    margin: 0 !important;
    border-top-left-radius: 0 !important;
    border-top-right-radius: 0 !important;
}

.submenu-header .submenu-title {
    font-weight: 700 !important;
    font-size: 11px !important;
    color: #64748b !important;
    text-transform: uppercase !important;
    letter-spacing: 0.8px !important;
    margin: 0 !important;
    line-height: 1.4 !important;
}

.submenu-header .close-submenu {
    background: #ffffff !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    width: 28px !important;
    height: 28px !important;
    border-radius: 8px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
}

.submenu-header .close-submenu:hover {
    background: #fee2e2 !important;
    border-color: #fca5a5 !important;
    color: #ef4444 !important;
}

/* Submenu Links */
.sidebar-menu .treeview-menu > li:not(.submenu-header) > a {
    color: #334155 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    padding: 9px 14px !important;
    border-radius: 8px !important;
    margin: 3px 10px !important;
    transition: all 0.18s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: flex !important;
    align-items: center !important;
}

/* Submenu Link Hover State */
.sidebar-menu .treeview-menu > li:not(.submenu-header) > a:hover {
    background-color: #f1f5f9 !important;
    color: #0f172a !important;
    padding-left: 18px !important;
}

/* Submenu Link Active State */
.sidebar-menu .treeview-menu > li.active > a {
    color: var(--primary-theme-color, #4f46e5) !important;
    font-weight: 600 !important;
    background-color: rgba(99, 102, 241, 0.08) !important;
}

/* Submenu Active Indicator Dot */
.sidebar-menu .treeview-menu > li.active > a::before {
    content: "" !important;
    display: inline-block !important;
    width: 6px !important;
    height: 6px !important;
    border-radius: 50% !important;
    background-color: var(--primary-theme-color, #4f46e5) !important;
    margin-right: 8px !important;
    flex-shrink: 0 !important;
}

/* Hide small icons inside submenu links */
.sidebar-menu .treeview-menu > li > a > .icon-circle-small,
.sidebar-menu .treeview-menu > li > a > i {
    display: none !important;
}

/* Sidebar Menu Search Box Styling (First Menu Item) */
.sidebar-menu > li.sidebar-search-item {
    padding: 6px 10px 10px 10px !important;
    margin: 0 0 4px 0 !important;
    background: transparent !important;
    list-style: none !important;
    display: block !important;
}

.sidebar-search-wrapper {
    position: relative !important;
    display: flex !important;
    align-items: center !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 10px !important;
    padding: 8px 12px !important;
    height: 38px !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
}

.sidebar-search-wrapper:focus-within {
    background: #ffffff !important;
    border-color: var(--primary-theme-color, #4f46e5) !important;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12) !important;
}

.sidebar-search-icon {
    font-size: 15px !important;
    color: #64748b !important;
    margin-right: 10px !important;
    flex-shrink: 0 !important;
    display: inline-block !important;
}

.sidebar-search-input {
    width: 100% !important;
    border: none !important;
    background: transparent !important;
    outline: none !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    color: #0f172a !important;
    font-family: 'Inter', -apple-system, sans-serif !important;
    padding: 0 !important;
    height: 100% !important;
    box-shadow: none !important;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
}

.sidebar-search-input::placeholder {
    color: #94a3b8 !important;
    font-weight: 400 !important;
}

.sidebar-search-clear {
    background: none !important;
    border: none !important;
    color: #94a3b8 !important;
    font-size: 12px !important;
    cursor: pointer !important;
    padding: 0 4px !important;
    line-height: 1 !important;
    display: none;
}

.sidebar-search-clear:hover {
    color: #ef4444 !important;
}

/* Desktop Bottom Sidebar Collapse Footer Bar */
@media (min-width: 768px) {
    .sidebar-collapse-footer {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        width: 240px !important;
        height: 46px !important;
        background: #ffffff !important;
        border-top: 1px solid #f1f5f9 !important;
        border-right: 1px solid #e2e8f0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        padding: 0 14px !important;
        z-index: 1050 !important;
        transition: width 0.3s ease-in-out !important;
    }
}

.sidebar-bottom-toggle {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 8px !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    color: #64748b !important;
    font-size: 15px !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05) !important;
    outline: none !important;
    margin-left: auto !important;
    margin-right: 0 !important;
}

.sidebar-bottom-toggle:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    border-color: #cbd5e1 !important;
}

.sidebar-bottom-toggle .collapse-icon {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    display: inline-block !important;
}

/* Rotate icon 180 deg when collapsed */
body.sidebar-collapse .sidebar-bottom-toggle .collapse-icon {
    transform: rotate(180deg) !important;
}

@media (min-width: 768px) {
    body.sidebar-collapse .sidebar-collapse-footer {
        width: 70px !important;
        justify-content: center !important;
        padding: 0 !important;
    }
}

body.sidebar-collapse .sidebar-bottom-toggle {
    margin-left: auto !important;
    margin-right: auto !important;
}

/* Mobile Layout Overrides (Strictly Hides Drawer Off-Screen) */
@media (max-width: 767px) {
    .sidebar-collapse-footer {
        display: none !important;
    }

    .main-sidebar,
    body.sidebar-collapse .main-sidebar {
        position: fixed !important;
        top: 0 !important;
        bottom: 0 !important;
        left: -280px !important;
        width: 280px !important;
        height: 100vh !important;
        padding-top: 50px !important;
        background-color: #ffffff !important;
        z-index: 99999 !important;
        transition: left 0.3s ease-in-out !important;
        box-shadow: none !important;
    }

    body.sidebar-open .main-sidebar,
    body.sidebar-open.sidebar-collapse .main-sidebar {
        left: 0 !important;
        box-shadow: 4px 0 25px rgba(0, 0, 0, 0.25) !important;
    }
}

/* Collapsed Mode Search Icon Button */
body.sidebar-collapse .sidebar-menu > li.sidebar-search-item {
    display: block !important;
    padding: 8px 17px !important;
    margin: 0 !important;
}

body.sidebar-collapse .sidebar-search-wrapper {
    width: 36px !important;
    height: 36px !important;
    padding: 0 !important;
    justify-content: center !important;
    cursor: pointer !important;
    border-radius: 10px !important;
    background: #f8fafc !important;
    border: 1px solid #e2e8f0 !important;
    transition: all 0.2s ease !important;
}

body.sidebar-collapse .sidebar-search-wrapper:hover {
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
}

body.sidebar-collapse .sidebar-search-wrapper:hover .sidebar-search-icon {
    color: var(--primary-theme-color, #4f46e5) !important;
    transform: scale(1.1) !important;
}

body.sidebar-collapse .sidebar-search-icon {
    margin-right: 0 !important;
    font-size: 16px !important;
    transition: all 0.2s ease !important;
}

body.sidebar-collapse .sidebar-search-input,
body.sidebar-collapse .sidebar-search-clear {
    display: none !important;
}
</style>

<script type="text/javascript">
// Sidebar state keys are namespaced per panel (window.sidebarStoragePrefix) so the
// admin and student/parent panels don't overwrite each other's saved state.
function sidebarKey(key) {
    return (window.sidebarStoragePrefix || '') + key;
}

$(document).ready(function () {
    // Click search icon in collapsed mode to expand sidebar and focus search input
    $(document).on('click', 'body.sidebar-collapse .sidebar-search-wrapper', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('body').removeClass('sidebar-collapse');
        localStorage.setItem(sidebarKey('sidebar_collapsed'), '0');
        setTimeout(function () {
            $('#sidebar-menu-search').focus();
        }, 150);
    });
    $('#sidebar-menu-search').on('keyup input', function () {
        var query = $(this).val().toLowerCase().trim();

        if (query.length > 0) {
            $('#sidebar-search-clear').show();
        } else {
            $('#sidebar-search-clear').hide();
        }

        $('.sidebar-menu > li.treeview').each(function () {
            var $parentLi = $(this);
            var mainTitle = $parentLi.find('> a > span').text().toLowerCase();
            var hasMatchingSubmenu = false;

            $parentLi.find('.treeview-menu > li:not(.submenu-header)').each(function () {
                var subTitle = $(this).text().toLowerCase();
                if (query.length > 0 && subTitle.indexOf(query) !== -1) {
                    hasMatchingSubmenu = true;
                    $(this).show();
                } else if (query.length > 0 && mainTitle.indexOf(query) === -1) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });

            if (query === "" || mainTitle.indexOf(query) !== -1 || hasMatchingSubmenu) {
                $parentLi.show();
                if (query.length > 1 && hasMatchingSubmenu && mainTitle.indexOf(query) === -1) {
                    $parentLi.find('.treeview-menu').addClass('is-open');
                }
            } else {
                $parentLi.hide();
            }
        });
    });

    $('#sidebar-search-clear').on('click', function () {
        $('#sidebar-menu-search').val('').trigger('input').focus();
    });
});
</script>
