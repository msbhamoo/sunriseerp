  <style type="text/css">
		
		span.flag-icon.flag-icon-us{
			text-orientation: mixed;
		}
		#header_search_form {
			overflow: visible !important;
		}
		
		/* Ensure top nav logo is fully visible and not cut off */
		.main-header .logo img {
			max-height: 50px; /* standard navbar height */
			width: auto;
			max-width: 100%;
			object-fit: contain;
		}

		/* ===== HEADER AJAX SEARCH DROPDOWN ===== */
		.ajax-search-results {
			position: absolute !important;
			top: calc(100% + 5px) !important;
			right: 0 !important;
			left: auto !important;
			width: 560px !important;
			background: #fff !important;
			z-index: 99999 !important;
			border-radius: 10px;
			box-shadow: 0 8px 32px rgba(0,0,0,0.18);
			max-height: 420px;
			overflow-y: auto;
			overflow-x: hidden;
			display: none;
			border: 1px solid #e0e3e8;
			padding: 8px;
		}
		.ajax-search-results::-webkit-scrollbar { width: 5px; }
		.ajax-search-results::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
		.ajax-search-results::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
		.ajax-search-results::-webkit-scrollbar-thumb:hover { background: #999; }

		a.ajax-search-item {
			display: flex !important;
			flex-direction: row !important;
			flex-wrap: nowrap !important;
			align-items: center !important;
			width: 100% !important;
			box-sizing: border-box !important;
			padding: 10px 12px !important;
			margin-bottom: 6px;
			background-color: #ffffff;
			border: 1px solid #eef0f3;
			border-radius: 8px;
			text-decoration: none !important;
			color: #333 !important;
			transition: all 0.2s ease-in-out;
			cursor: pointer;
		}
		a.ajax-search-item:nth-child(even) {
			background-color: #f4f6f9;
		}
		a.ajax-search-item:last-child {
			margin-bottom: 0;
		}
		a.ajax-search-item:hover,
		a.ajax-search-item:focus {
			background-color: var(--primary-theme-color, #2eab66) !important;
			color: #fff !important;
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
			border-color: var(--primary-theme-color, #2eab66) !important;
			outline: none;
		}

		.ajax-search-avatar {
			width: 40px !important;
			height: 40px !important;
			min-width: 40px !important;
			border-radius: 50% !important;
			object-fit: cover;
			margin-right: 12px !important;
			border: 2px solid #fff;
			box-shadow: 0 2px 5px rgba(0,0,0,0.1);
			background: #f0f0f0;
			flex-shrink: 0 !important;
		}

		.ajax-search-details {
			flex: 1 1 auto !important;
			display: flex !important;
			flex-direction: row !important;
			align-items: center !important;
			justify-content: flex-start !important;
			min-width: 0 !important;
			gap: 12px;
		}

		.ajax-search-col {
			display: flex !important;
			flex-direction: column !important;
			flex: 0 0 200px !important;
			width: 200px !important;
			min-width: 0 !important;
		}
		.ajax-search-name {
			font-weight: 600;
			font-size: 13px;
			margin-bottom: 2px;
			text-transform: uppercase;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			color: #2c3e50;
		}
		a.ajax-search-item:hover .ajax-search-name {
			color: #fff;
		}
		.ajax-search-meta {
			font-size: 11px;
			color: #6c757d;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}
		a.ajax-search-item:hover .ajax-search-meta {
			color: rgba(255,255,255,0.8);
		}

		.ajax-search-parents {
			display: flex !important;
			flex-direction: column !important;
			font-size: 11px;
			color: #495057;
			text-transform: uppercase;
			font-weight: 500;
			flex-shrink: 0 !important;
			white-space: nowrap;
		}
		a.ajax-search-item:hover .ajax-search-parents {
			color: #fff;
		}
		.ajax-search-parents span {
			margin-bottom: 2px;
			display: flex !important;
			align-items: center !important;
		}

		.parent-badge {
			color: #fff !important;
			border-radius: 50%;
			width: 16px;
			height: 16px;
			min-width: 16px;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			font-size: 9px;
			margin-right: 5px;
			font-weight: bold;
			flex-shrink: 0;
			line-height: 1;
		}
		.badge-father {
			background-color: #007bff !important;
		}
		.badge-mother {
			background-color: #e83e8c !important;
		}

		.ajax-search-icon {
			color: #ccc;
			margin-left: 10px;
			font-size: 15px;
			flex-shrink: 0 !important;
			transition: color 0.2s;
		}
		a.ajax-search-item:hover .ajax-search-icon {
			color: #fff;
		}
		.ajax-search-no-result {
			padding: 18px;
			text-align: center;
			color: #7f8c8d;
			font-size: 13px;
		}

/* =========================================
   Top Navbar SaaS Modernization
   ========================================= */

/* Organization Name (Sidebar Session) */
.sidebar-session {
    font-family: 'Inter', -apple-system, sans-serif !important;
    font-size: 16px !important;
    font-weight: 600 !important;
    color: #111827 !important;
    display: flex !important;
    align-items: center !important;
    height: 50px !important;
    padding-left: 15px !important;
    letter-spacing: -0.2px !important;
    text-transform: none !important;
}

/* Base Navbar Cleanup */
.main-header {
    border-bottom: 1px solid #e5e7eb !important;
    box-shadow: none !important;
    background-color: #ffffff !important;
}

/* Separation for Logo (Sidebar Width) */
.main-header .logo {
    padding-left: 20px !important;
    border-right: 1px solid #9ca3af !important;
    background-color: #ffffff !important;
    text-align: left !important;
}

.main-header .navbar {
    background-color: #ffffff !important;
    box-shadow: none !important;
    height: 50px !important;
}

/* Flexbox Layout for Right Menu */
.navbar-custom-menu {
    display: flex !important;
    align-items: center !important;
    justify-content: flex-end !important;
    height: 100% !important;
}

.navbar-nav.headertopmenu {
    display: flex !important;
    align-items: center !important;
    margin: 0 !important;
    padding: 0 !important;
    flex-direction: row !important;
}

/* Fix spacing for currency and language switchers */
.currency-icon-list, .langdiv {
    display: flex !important;
    align-items: center !important;
    margin-right: 8px !important;
}

/* Navbar Icons (The Action Buttons) */
.navbar-nav.headertopmenu > li {
    display: flex !important;
    align-items: center !important;
}

/* Visibility Fixes */
@media (max-width: 767px) {
    .navbar-nav.headertopmenu > li.hidden-xs,
    .navbar-nav.headertopmenu > li.d-sm-none {
        display: none !important;
    }
}
@media (min-width: 768px) {
    .navbar-nav.headertopmenu > li.d-lg-none,
    .navbar-nav.headertopmenu > li.ellipsis-px-3 {
        display: none !important;
    }
}

/* Top Header Icons */
.navbar-nav.headertopmenu > li > a {
    color: #4b5563 !important;
    transition: all 0.2s ease !important;
    padding: 0 !important;
    width: 38px !important;
    height: 38px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border-radius: 50% !important; /* Circular like Google/GitHub */
    margin: 6px 4px !important; /* 6px top/bottom to vertically center in 50px navbar */
    position: relative !important;
    overflow: visible !important;
}

.navbar-nav.headertopmenu > li > a:hover {
    background-color: #f3f4f6 !important;
    color: #111827 !important;
}

.navbar-nav.headertopmenu > li > a i {
    font-size: 20px !important;
    font-weight: normal !important;
    position: static !important;
    color: #4b5563 !important;
}

/* Fix WhatsApp Icon */
.whatsapp-icon-bg {
    display: flex !important;
    align-items: center !important;
}
.whatsapp-icon-bg a {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 38px !important;
    height: 38px !important;
    border-radius: 50% !important;
    margin-top: 6px !important;
}
.whatsapp-icon-bg svg {
    transition: all 0.2s ease !important;
    width: 22px !important;
    height: 22px !important;
}
.whatsapp-icon-bg:hover svg {
    transform: scale(1.1);
}

/* Notification Badges (The Red Dots) */
.navbar-nav.headertopmenu .todo-indicator,
.navbar-nav.headertopmenu .topbadges {
    position: absolute !important;
    top: 2px !important;
    right: 2px !important;
    margin: 0 !important;
    background-color: #ef4444 !important; /* Soft Red */
    color: #ffffff !important;
    font-size: 10px !important;
    font-weight: 700 !important;
    border-radius: 50% !important;
    width: 16px !important;
    height: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
    border: 2px solid #ffffff !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1) !important;
    box-sizing: content-box !important;
    z-index: 10 !important;
    line-height: 1 !important;
}
.main-header, .navbar, .navbar-custom-menu, .navbar-nav.headertopmenu, .navbar-nav.headertopmenu > li, .navbar-nav.headertopmenu > li > a {
    overflow: visible !important;
}

/* User Profile Image Box */
.topuser-image {
    width: 34px !important;
    height: 34px !important;
    border-radius: 50% !important;
    object-fit: cover !important;
    border: 2px solid transparent !important;
    transition: all 0.2s ease !important;
    margin-left: 8px !important;
}

.navbar-nav.headertopmenu > li > a:hover .topuser-image {
    border-color: var(--primary-theme-color, #3b82f6) !important;
}

/* Top Navbar Dropdowns (Tasks, Profile, Language) */
.navbar-nav .dropdown-menu,
.dropdown-menu.menuboxshadow {
    border-radius: 12px !important;
    box-shadow: 0 10px 30px -5px rgba(0,0,0,0.1), 0 4px 10px -5px rgba(0,0,0,0.04) !important;
    border: 1px solid #e5e7eb !important;
    padding: 8px 0 !important;
    margin-top: 10px !important;
}

.navbar-nav .dropdown-menu > li > a {
    color: #4b5563 !important;
    font-size: 13.5px !important;
    font-weight: 500 !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
    margin: 2px 8px !important;
    transition: all 0.2s ease !important;
    display: block !important;
}

.navbar-nav .dropdown-menu > li > a:hover {
    background-color: #f3f4f6 !important;
    color: #111827 !important;
    transform: translateX(2px) !important;
}

/* Dropdown Headers (e.g. "You have 3 pending tasks") */
.navbar-nav .dropdown-menu .todoview,
.navbar-nav .dropdown-menu li.header {
    background-color: #ffffff !important;
    color: #6b7280 !important;
    font-weight: 600 !important;
    font-size: 11px !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    padding: 10px 16px !important;
    border-bottom: 1px solid #f3f4f6 !important;
    margin-bottom: 6px !important;
}

/* Fix Language & Currency Dropdown Containers */
.langdiv .bootstrap-select > .dropdown-toggle,
.currency-icon-list .bootstrap-select > .dropdown-toggle {
    background-color: transparent !important;
    border: none !important;
    box-shadow: none !important;
    color: #4b5563 !important;
    font-weight: 500 !important;
    padding: 6px 12px !important;
    border-radius: 8px !important;
    transition: all 0.2s ease !important;
}
.langdiv .bootstrap-select > .dropdown-toggle:hover,
.currency-icon-list .bootstrap-select > .dropdown-toggle:hover {
    background-color: #f3f4f6 !important;
    color: #111827 !important;
}
/* Offcanvas System Notifications */
.sys-offcanvas {
    position: fixed;
    top: 50px;
    right: -350px;
    width: 350px;
    height: calc(100vh - 50px);
    background-color: #fff;
    box-shadow: -4px 0 15px rgba(0,0,0,0.1);
    z-index: 1050;
    transition: right 0.3s ease;
    display: flex;
    flex-direction: column;
}
.sys-offcanvas.open {
    right: 0;
}
.sys-offcanvas-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f9fafb;
}
.sys-offcanvas-header h4 {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}
.sys-offcanvas-close {
    cursor: pointer;
    font-size: 20px;
    color: #6b7280;
}
.sys-offcanvas-body {
    flex: 1;
    overflow-y: auto;
    padding: 15px 20px;
}
.sys-alert-group {
    margin-bottom: 20px;
}
.sys-alert-group-title {
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    margin-bottom: 10px;
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 5px;
}
.sys-alert-item {
    display: block;
    padding: 12px;
    border-radius: 8px;
    background-color: #eff6ff; /* slightly blue for unread */
    margin-bottom: 8px;
    text-decoration: none !important;
    color: #374151 !important;
    transition: background-color 0.2s;
    border: 1px solid #dbeafe;
    position: relative;
}
.sys-alert-item.read-alert {
    background-color: #ffffff;
    border: 1px solid #e5e7eb;
}
.sys-unread-dot {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 8px;
    height: 8px;
    background-color: #3b82f6;
    border-radius: 50%;
    display: none;
}
.sys-alert-item.unread-alert .sys-unread-dot {
    display: block;
}
.sys-alert-item:hover {
    background-color: #e5e7eb;
}
.sys-alert-item strong {
    display: block;
    font-size: 14px;
    color: #111827;
    margin-bottom: 4px;
}
.sys-alert-item.read-alert strong {
    font-weight: 500;
    color: #4b5563;
}
.sys-alert-item small {
    display: block;
    font-size: 12px;
    color: #6b7280;
    line-height: 1.4;
}
  </style>
