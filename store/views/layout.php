<?php
// Shared Premium UI Layout for Store Portal
function render_header($title = 'Sunrise Store', $active_menu = 'dashboard') {
    global $user;
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= htmlspecialchars($title) ?> - Sunrise School Store</title>
        <!-- Google Fonts: Inter & Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <!-- FontAwesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <style>
            :root {
                --primary: #4f46e5;
                --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
                --primary-hover: #4338ca;
                --primary-light: #eef2ff;
                --primary-glow: rgba(79, 70, 229, 0.25);
                
                --emerald: #10b981;
                --emerald-gradient: linear-gradient(135deg, #34d399 0%, #059669 100%);
                --emerald-light: #ecfdf5;
                
                --sky: #0ea5e9;
                --sky-gradient: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
                --sky-light: #f0f9ff;
                
                --amber: #f59e0b;
                --amber-gradient: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
                --amber-light: #fffbeb;
                
                --rose: #f43f5e;
                --rose-gradient: linear-gradient(135deg, #fb7185 0%, #e11d48 100%);
                --rose-light: #fff1f2;
                
                --violet: #8b5cf6;
                --violet-gradient: linear-gradient(135deg, #a78bfa 0%, #7c3aed 100%);
                --violet-light: #f5f3ff;
                
                --sidebar-bg: #ffffff;
                --sidebar-border: #f1f5f9;
                --sidebar-hover: #f8fafc;
                --sidebar-text: #334155;
                --sidebar-active-bg: #eff6ff;
                --sidebar-active-text: #1d4ed8;
                
                --bg-body: #f8fafc;
                --surface: #ffffff;
                --card-border: #e2e8f0;
                --text-main: #0f172a;
                --text-muted: #64748b;
                --radius-xl: 14px;
                --radius-lg: 12px;
                --radius-md: 8px;
                --radius-sm: 6px;
                --shadow-subtle: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
                --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.03);
                --shadow-md: 0 8px 20px -4px rgba(15, 23, 42, 0.06), 0 4px 8px -2px rgba(15, 23, 42, 0.03);
                --shadow-lg: 0 16px 32px -8px rgba(15, 23, 42, 0.1), 0 6px 16px -4px rgba(15, 23, 42, 0.04);
                --shadow-xl: 0 24px 48px -12px rgba(15, 23, 42, 0.15);
            }

            * { box-sizing: border-box; margin: 0; padding: 0; }
            body { 
                font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; 
                background-color: var(--bg-body); 
                color: var(--text-main); 
                display: flex; 
                min-height: 100vh;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            /* Clean Modern White Sidebar (Matches ERP Design) */
            .sidebar {
                width: 275px;
                background-color: #ffffff;
                color: var(--sidebar-text);
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
                border-right: 1px solid #eef2f6;
                position: relative;
                z-index: 50;
            }

            .sidebar-brand {
                padding: 16px 18px 12px;
                display: flex;
                flex-direction: column;
                align-items: center;
                text-align: center;
                border-bottom: 1px solid #f1f5f9;
            }

            .sidebar-brand .brand-logo-img {
                height: 52px;
                max-width: 170px;
                object-fit: contain;
                margin-bottom: 4px;
            }

            .sidebar-search-item {
                padding: 12px 14px 6px;
            }

            .sidebar-search-wrapper {
                position: relative;
                width: 100%;
            }

            .sidebar-search-wrapper i {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 13px;
                pointer-events: none;
            }

            .sidebar-search-input {
                width: 100%;
                height: 38px;
                padding: 8px 12px 8px 36px;
                background: #f8fafc;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                font-size: 13px;
                font-family: inherit;
                color: #0f172a;
                outline: none;
                transition: all 0.2s ease;
            }

            .sidebar-search-input:focus {
                background: #ffffff;
                border-color: #93c5fd;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.12);
            }

            .sidebar-search-input::placeholder {
                color: #94a3b8;
                font-weight: 500;
            }

            .sidebar-menu {
                list-style: none;
                padding: 6px 12px 24px;
                flex: 1;
                overflow-y: auto;
            }

            .sidebar-menu::-webkit-scrollbar {
                width: 4px;
            }
            .sidebar-menu::-webkit-scrollbar-thumb {
                background: #e2e8f0;
                border-radius: 4px;
            }

            .menu-header {
                font-size: 10px;
                text-transform: uppercase;
                font-weight: 800;
                color: #94a3b8;
                padding: 16px 10px 6px;
                letter-spacing: 0.8px;
            }

            .sidebar-menu li a {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 8px 10px;
                color: #334155;
                text-decoration: none;
                font-size: 13px;
                font-weight: 600;
                border-radius: 10px;
                transition: all 0.18s ease;
                margin-bottom: 3px;
                position: relative;
            }

            .sidebar-menu li a:hover {
                background: #f8fafc;
                color: #0f172a;
                transform: translateX(2px);
            }

            /* Only direct child <a> of active top-level <li> gets highlighted */
            .sidebar-menu > li.active > a {
                background: #eff6ff;
                color: #1d4ed8;
                font-weight: 700;
            }

            /* Pastel Rounded-Square Icon Container */
            .menu-icon-box {
                width: 36px;
                height: 36px;
                min-width: 36px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 15px;
                transition: transform 0.2s ease;
            }

            .menu-label {
                flex: 1;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .menu-chevron {
                font-size: 11px;
                color: #94a3b8;
                margin-left: auto;
                transition: transform 0.2s ease, color 0.2s ease;
            }

            .sidebar-menu li a:hover .menu-chevron {
                color: #3b82f6;
            }

            .sidebar-menu li.active > a .menu-chevron {
                color: #2563eb;
            }

            /* Treeview Collapsible Group */
            .sidebar-menu li.treeview > a {
                cursor: pointer;
                user-select: none;
            }

            .sidebar-menu li.treeview .menu-chevron {
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .sidebar-menu li.treeview.menu-open > a .menu-chevron {
                transform: rotate(90deg);
                color: #2563eb;
            }

            /* Submenu List */
            .treeview-sub-menu {
                list-style: none;
                padding: 4px 0 6px 14px;
                margin: 0;
                display: none;
                position: relative;
            }

            .sidebar-menu li.treeview.menu-open > .treeview-sub-menu {
                display: block;
            }

            .treeview-sub-menu::before {
                content: '';
                position: absolute;
                left: 28px;
                top: 4px;
                bottom: 8px;
                width: 1.5px;
                background: #e2e8f0;
            }

            .treeview-sub-menu li a {
                padding: 7px 12px 7px 28px;
                font-size: 12.5px;
                font-weight: 600;
                color: #475569;
                background: transparent;
                border-radius: 8px;
                margin-bottom: 2px;
                display: flex;
                align-items: center;
                gap: 8px;
                position: relative;
            }

            .treeview-sub-menu li a::before {
                content: '';
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                width: 5px;
                height: 5px;
                border-radius: 50%;
                background: #cbd5e1;
                transition: background 0.15s, transform 0.15s;
            }

            .treeview-sub-menu li a:hover {
                background: #f8fafc;
                color: #0f172a;
            }

            .treeview-sub-menu li a:hover::before {
                background: #3b82f6;
                transform: translateY(-50%) scale(1.3);
            }

            /* Only the specifically selected child page gets highlighted */
            .treeview-sub-menu li.active a {
                background: #eff6ff !important;
                color: #1d4ed8 !important;
                font-weight: 700 !important;
            }

            .treeview-sub-menu li.active a::before {
                background: #2563eb !important;
                box-shadow: 0 0 6px rgba(37, 99, 235, 0.4);
            }

            /* Main Layout Area */
            .main-wrapper {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
                width: calc(100% - 275px);
                overflow-x: hidden;
            }

            /* Glassmorphism Sticky Top Header */
            .topbar {
                height: 70px;
                background: rgba(255, 255, 255, 0.85);
                backdrop-filter: blur(14px);
                -webkit-backdrop-filter: blur(14px);
                border-bottom: 1px solid var(--card-border);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 32px;
                position: sticky;
                top: 0;
                z-index: 40;
                transition: all 0.2s ease;
            }

            .page-title {
                font-size: 21px;
                font-weight: 800;
                letter-spacing: -0.5px;
                color: var(--text-main);
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .user-nav {
                display: flex;
                align-items: center;
                gap: 16px;
            }

            .user-badge {
                display: flex;
                align-items: center;
                gap: 12px;
                background: #ffffff;
                padding: 6px 14px 6px 8px;
                border-radius: 30px;
                border: 1px solid #e2e8f0;
                box-shadow: var(--shadow-sm);
            }

            .avatar {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: var(--primary-gradient);
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-weight: 800;
                font-size: 14px;
                box-shadow: 0 4px 10px var(--primary-glow);
                border: 2px solid #fff;
            }

            .btn-logout {
                background: #fff1f2;
                color: #e11d48;
                border: 1px solid #fecdd3;
                padding: 8px 14px;
                border-radius: 10px;
                font-size: 12.5px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: all 0.2s;
            }

            .btn-logout:hover {
                background: #ffe4e6;
                color: #be123c;
                transform: translateY(-1px);
                box-shadow: 0 4px 10px rgba(244, 63, 94, 0.15);
            }

            /* Content Cards & Container */
            .content {
                padding: 28px 32px;
                flex: 1;
            }

            .card {
                background: var(--surface);
                border: 1px solid var(--card-border);
                border-radius: var(--radius-lg);
                box-shadow: var(--shadow-subtle);
                margin-bottom: 24px;
                overflow: hidden;
                transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .card:hover {
                box-shadow: var(--shadow-md);
            }

            .card-header {
                padding: 18px 24px;
                border-bottom: 1px solid var(--card-border);
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: #ffffff;
            }

            .card-title {
                font-size: 16.5px;
                font-weight: 800;
                letter-spacing: -0.3px;
                color: var(--text-main);
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .card-body {
                padding: 24px;
            }

            /* Modern Vibrant Buttons */
            .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
                padding: 9px 18px;
                border-radius: var(--radius-md);
                font-size: 13.5px;
                font-weight: 700;
                cursor: pointer;
                text-decoration: none;
                border: 1px solid transparent;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
            }

            .btn:active { transform: scale(0.97); }

            .btn-primary {
                background: var(--primary-gradient);
                color: #fff;
                box-shadow: 0 4px 12px var(--primary-glow);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
                box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
                transform: translateY(-1px);
                color: #fff;
            }

            .btn-success {
                background: var(--emerald-gradient);
                color: #fff;
                box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
            }

            .btn-success:hover {
                background: linear-gradient(135deg, #059669 0%, #047857 100%);
                box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
                transform: translateY(-1px);
                color: #fff;
            }

            .btn-secondary {
                background: #f8fafc;
                color: #334155;
                border-color: #cbd5e1;
            }

            .btn-secondary:hover {
                background: #f1f5f9;
                color: #0f172a;
                border-color: #94a3b8;
            }

            .btn-danger {
                background: var(--rose-gradient);
                color: #fff;
                box-shadow: 0 4px 12px rgba(244, 63, 94, 0.25);
            }

            .btn-danger:hover {
                background: linear-gradient(135deg, #e11d48 0%, #be123c 100%);
                box-shadow: 0 6px 18px rgba(244, 63, 94, 0.35);
                transform: translateY(-1px);
                color: #fff;
            }

            /* Inputs & Forms */
            .form-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 18px;
                margin-bottom: 20px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
                gap: 6px;
                position: relative;
            }

            .form-group label {
                font-size: 13px;
                font-weight: 700;
                color: #334155;
            }

            .form-control {
                padding: 10px 14px;
                border: 1.5px solid #cbd5e1;
                border-radius: var(--radius-md);
                font-size: 14px;
                font-family: inherit;
                color: #0f172a;
                background-color: #fff;
                outline: none;
                transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .form-control:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            }

            /* Modern Tables */
            .table-responsive {
                width: 100%;
                overflow-x: auto;
            }

            .table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
                font-size: 13.5px;
            }

            .table th {
                background: #f8fafc;
                color: #475569;
                font-weight: 800;
                text-transform: uppercase;
                font-size: 11px;
                letter-spacing: 0.6px;
                text-align: left;
                padding: 14px 20px;
                border-bottom: 1.5px solid var(--card-border);
            }

            .table td {
                padding: 14px 20px;
                border-bottom: 1px solid #f1f5f9;
                color: var(--text-main);
                vertical-align: middle;
                transition: background 0.15s ease;
            }

            .table tr:hover td {
                background: #f8fafc;
            }

            /* Modern Translucent Badges */
            .badge {
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 11.5px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                letter-spacing: 0.2px;
                border: 1px solid transparent;
            }

            .badge-success { 
                background: #ecfdf5; 
                color: #047857; 
                border-color: #a7f3d0; 
            }
            .badge-warning { 
                background: #fffbeb; 
                color: #b45309; 
                border-color: #fde68a; 
            }
            .badge-danger { 
                background: #fff1f2; 
                color: #be123c; 
                border-color: #fecdd3; 
            }
            .badge-info { 
                background: #f0f9ff; 
                color: #0369a1; 
                border-color: #bae6fd; 
            }
            .badge-primary { 
                background: #eef2ff; 
                color: #4338ca; 
                border-color: #c7d2fe; 
            }

            .alert {
                padding: 14px 18px;
                border-radius: var(--radius-md);
                font-size: 13.5px;
                margin-bottom: 22px;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .alert-success { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
            .alert-danger { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

            /* Autocomplete Suggestions Box */
            .suggestions-box {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #fff;
                border: 1px solid #cbd5e1;
                border-radius: 8px;
                box-shadow: var(--shadow-xl);
                max-height: 240px;
                overflow-y: auto;
                z-index: 100;
                display: none;
                margin-top: 4px;
            }

            .suggestion-item {
                padding: 10px 14px;
                cursor: pointer;
                border-bottom: 1px solid #f1f5f9;
                transition: background 0.15s;
            }

            .suggestion-item:hover, .suggestion-item.active {
                background: #f1f5f9;
                color: var(--primary);
            }

            /* Modern Right-Sidebar Slide-Over Drawer (Matches Screenshot 3) */
            .modern-drawer-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.45);
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
                z-index: 9999;
                justify-content: flex-end;
            }

            .modern-drawer-backdrop.active {
                display: flex !important;
            }

            .modern-drawer {
                background: #ffffff;
                width: 580px;
                max-width: 95vw;
                height: 100vh;
                box-shadow: -10px 0 35px rgba(0, 0, 0, 0.12);
                display: flex;
                flex-direction: column;
                animation: slideDrawerIn 0.24s cubic-bezier(0.16, 1, 0.3, 1);
            }

            @keyframes slideDrawerIn {
                from { transform: translateX(100%); }
                to { transform: translateX(0); }
            }

            .modern-drawer-header {
                padding: 18px 24px;
                border-bottom: 1px solid #f1f5f9;
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: #ffffff;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .modern-drawer-header h3 {
                margin: 0;
                font-size: 17px;
                font-weight: 800;
                color: #0f172a;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .modern-drawer-header h3 i {
                color: #059669;
                font-size: 19px;
            }

            .modern-drawer-close {
                width: 32px;
                height: 32px;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                background: #ffffff;
                color: #64748b;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 16px;
                cursor: pointer;
                transition: all 0.15s ease;
            }

            .modern-drawer-close:hover {
                background: #f8fafc;
                color: #0f172a;
                border-color: #cbd5e1;
            }

            .modern-drawer-body {
                padding: 24px;
                flex: 1;
                overflow-y: auto;
            }

            .drawer-card-section {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                padding: 16px;
                margin-bottom: 20px;
            }

            .drawer-card-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 14px;
            }

            .drawer-card-title {
                font-size: 13.5px;
                font-weight: 700;
                color: #1e293b;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .drawer-btn-add {
                background: #0f172a;
                color: #ffffff;
                border: none;
                border-radius: 6px;
                padding: 5px 12px;
                font-size: 12px;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                transition: background 0.15s;
            }

            .drawer-btn-add:hover {
                background: #1e293b;
            }

            .drawer-item-box {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 10px;
                padding: 14px;
                margin-bottom: 12px;
            }

            .drawer-btn-del {
                width: 36px;
                height: 38px;
                background: #ef4444;
                color: #ffffff;
                border: none;
                border-radius: 6px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: background 0.15s;
            }

            .drawer-btn-del:hover {
                background: #dc2626;
            }

            .modern-drawer-footer {
                padding: 16px 24px;
                border-top: 1px solid #f1f5f9;
                background: #ffffff;
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 10px;
                position: sticky;
                bottom: 0;
                z-index: 10;
            }

            .btn-drawer-cancel {
                background: #ffffff;
                border: 1px solid #cbd5e1;
                color: #475569;
                border-radius: 8px;
                padding: 9px 18px;
                font-size: 13px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.15s;
            }

            .btn-drawer-cancel:hover {
                background: #f8fafc;
                color: #0f172a;
            }

            .btn-drawer-save {
                background: #0f766e;
                border: 1px solid #0f766e;
                color: #ffffff;
                border-radius: 8px;
                padding: 9px 20px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: background 0.15s;
            }

            .btn-drawer-save:hover {
                background: #0d9488;
            }

            .btn-drawer-print {
                background: #0284c7;
                border: 1px solid #0284c7;
                color: #ffffff;
                border-radius: 8px;
                padding: 9px 20px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                transition: background 0.15s;
            }

            .btn-drawer-print:hover {
                background: #0369a1;
            }

            /* Mobile & Tablet Global Responsiveness */
            .sidebar-toggle-btn {
                display: none;
                background: #f1f5f9;
                border: 1px solid var(--card-border);
                color: #334155;
                font-size: 18px;
                padding: 7px 12px;
                border-radius: 8px;
                cursor: pointer;
            }
            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(15, 23, 42, 0.6);
                backdrop-filter: blur(2px);
                z-index: 998;
            }

            @media (max-width: 992px) {
                .sidebar-toggle-btn {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                }
                .sidebar {
                    position: fixed;
                    top: 0;
                    left: -280px;
                    bottom: 0;
                    height: 100vh;
                    z-index: 999;
                    transition: left 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5);
                }
                .sidebar.mobile-open {
                    left: 0;
                }
                .sidebar-overlay.active {
                    display: block;
                }
                .main-wrapper {
                    width: 100% !important;
                }
                .topbar {
                    padding: 0 16px;
                }
                .content {
                    padding: 20px 16px;
                }
                .ctrl-k-btn {
                    display: none !important;
                }
                .user-badge div:last-child {
                    display: none;
                }
                .page-title {
                    font-size: 16px;
                }
            }

            @media (max-width: 576px) {
                .topbar {
                    height: 60px;
                }
                .user-nav {
                    gap: 8px;
                }
                .user-nav .btn-primary span {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <!-- Mobile Sidebar Backdrop Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleMobileSidebar()"></div>
        <!-- Dedicated Store Sidebar (Matches ERP Design Exactly) -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <img src="/lms/uploads/school_content/logo/1781588142-13080977706a30e0ae60fae!r-Learn2Care-logo%20(1).png" alt="Sunrise School Crest" class="brand-logo-img" onerror="this.onerror=null; this.src='/lms/uploads/school_content/admin_small_logo/1781588128-78849546a30e0a01cf49!r-Learn2Care-logo%20(1).png';">
            </div>

            <!-- Search Menu Input -->
            <div class="sidebar-search-item">
                <div class="sidebar-search-wrapper">
                    <i class="fa fa-magnifying-glass"></i>
                    <input type="text" id="sidebar-menu-search" class="sidebar-search-input" placeholder="Search menu..." autocomplete="off" onkeyup="filterSidebarMenu()">
                </div>
            </div>

            <?php
            // Role Permission Matrix for Store
            $role_name = strtolower($user['role'] ?? '');
            $is_super = ($role_name === 'super admin' || $role_name === 'admin');
            $user_perms = $user['permissions'] ?? [];

            // Helper to check granular feature permission
            $can = function($feature_code, $action = 'can_view') use ($is_super, $user_perms, $role_name) {
                if ($is_super) return true;
                // If specific permissions are configured for this role
                if (isset($user_perms[$feature_code])) {
                    return !empty($user_perms[$feature_code][$action]);
                }
                // Fallback for Cashier if no explicit matrix row saved yet
                if (strpos($role_name, 'cashier') !== false || strpos($role_name, 'receptionist') !== false) {
                    return ($feature_code === 'store_pos');
                }
                // Full Store Keepers/others have access by default
                return true;
            };

            $can_items = $can('store_items');
            $can_bundles = $can('store_bundles');
            $can_procurement = $can('store_procurement');
            $can_pos = $can('store_pos');
            $can_requisitions = $can('store_requisitions');
            $can_loans = $can('store_loans');
            $can_reports = $can('store_reports');
            ?>
            <ul class="sidebar-menu" id="mainSidebarMenu">
                <!-- Single Item: Dashboard -->
                <li class="<?= $active_menu === 'dashboard' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>dashboard">
                        <span class="menu-icon-box" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                            <i class="fa fa-chart-pie"></i>
                        </span>
                        <span class="menu-label">Dashboard</span>
                    </a>
                </li>
                
                <!-- Treeview 1: Inventory Masters (Collapsible) -->
                <?php 
                $is_inv_active = in_array($active_menu, ['items', 'bundles', 'import_export', 'categories', 'vendors']);
                if ($can_items || $can_bundles): 
                ?>
                <li class="treeview <?= $is_inv_active ? 'active menu-open' : '' ?>">
                    <a href="javascript:void(0)" onclick="toggleTreeviewMenu(this)">
                        <span class="menu-icon-box" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                            <i class="fa fa-boxes-packing"></i>
                        </span>
                        <span class="menu-label">Inventory Masters</span>
                        <i class="fa fa-chevron-right menu-chevron"></i>
                    </a>
                    <ul class="treeview-sub-menu" style="<?= $is_inv_active ? 'display:block;' : '' ?>">
                        <?php if ($can_items): ?>
                        <li class="<?= $active_menu === 'items' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>items"><span>Item Master</span></a>
                        </li>
                        <?php endif; ?>
                        <?php if ($can_bundles): ?>
                        <li class="<?= $active_menu === 'bundles' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>bundles"><span>Student Kits & Bundles</span></a>
                        </li>
                        <?php endif; ?>
                        <?php if ($can_items): ?>
                        <li class="<?= $active_menu === 'import_export' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>import_export"><span>Bulk Import & Export</span></a>
                        </li>
                        <li class="<?= $active_menu === 'categories' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>categories"><span>Categories & Units</span></a>
                        </li>
                        <li class="<?= $active_menu === 'vendors' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>vendors"><span>Vendor Directory</span></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Treeview 2: Procurement / Stock In (Collapsible) -->
                <?php 
                $is_proc_active = in_array($active_menu, ['po', 'grn']);
                if ($can_procurement): 
                ?>
                <li class="treeview <?= $is_proc_active ? 'active menu-open' : '' ?>">
                    <a href="javascript:void(0)" onclick="toggleTreeviewMenu(this)">
                        <span class="menu-icon-box" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                            <i class="fa fa-dolly"></i>
                        </span>
                        <span class="menu-label">Procurement (Stock In)</span>
                        <i class="fa fa-chevron-right menu-chevron"></i>
                    </a>
                    <ul class="treeview-sub-menu" style="<?= $is_proc_active ? 'display:block;' : '' ?>">
                        <li class="<?= $active_menu === 'po' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>po"><span>Purchase Orders</span></a>
                        </li>
                        <li class="<?= $active_menu === 'grn' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>grn"><span>Inward GRN Entry</span></a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Treeview 3: Stock Out & Issue (Collapsible) -->
                <?php 
                $is_out_active = in_array($active_menu, ['pos', 'requisitions', 'loans']);
                if ($can_pos || $can_requisitions || $can_loans): 
                ?>
                <li class="treeview <?= $is_out_active ? 'active menu-open' : '' ?>">
                    <a href="javascript:void(0)" onclick="toggleTreeviewMenu(this)">
                        <span class="menu-icon-box" style="background: rgba(14, 165, 233, 0.12); color: #0ea5e9;">
                            <i class="fa fa-hand-holding-hand"></i>
                        </span>
                        <span class="menu-label">Stock Out & Issue</span>
                        <i class="fa fa-chevron-right menu-chevron"></i>
                    </a>
                    <ul class="treeview-sub-menu" style="<?= $is_out_active ? 'display:block;' : '' ?>">
                        <?php if ($can_pos): ?>
                        <li class="<?= $active_menu === 'pos' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>pos"><span>Student Counter POS</span></a>
                        </li>
                        <?php endif; ?>
                        <?php if ($can_requisitions): ?>
                        <li class="<?= $active_menu === 'requisitions' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>requisitions"><span>Staff Requisitions</span></a>
                        </li>
                        <?php endif; ?>
                        <?php if ($can_loans): ?>
                        <li class="<?= $active_menu === 'loans' ? 'active' : '' ?>">
                            <a href="<?= BASE_URL ?>loans"><span>Asset Loans & Returns</span></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Single Item: Reports -->
                <?php if ($can_reports): ?>
                <li class="<?= $active_menu === 'reports' ? 'active' : '' ?>">
                    <a href="<?= BASE_URL ?>reports">
                        <span class="menu-icon-box" style="background: rgba(168, 85, 247, 0.12); color: #a855f7;">
                            <i class="fa fa-chart-line"></i>
                        </span>
                        <span class="menu-label">Stock & Sales Reports</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Single Item: Exit -->
                <li>
                    <a href="<?= BASE_URL ?>logout">
                        <span class="menu-icon-box" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
                            <i class="fa fa-arrow-right-from-bracket"></i>
                        </span>
                        <span class="menu-label">Close Store</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="main-wrapper">
            <div class="topbar">
                <div style="display:flex; align-items:center; gap:14px;">
                    <!-- Mobile Hamburger Toggle Button -->
                    <button type="button" class="sidebar-toggle-btn" onclick="toggleMobileSidebar()" aria-label="Toggle Sidebar Menu">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="page-title"><?= htmlspecialchars($title) ?></div>
                    <!-- Quick-Access Header Search Trigger (Ctrl+K) -->
                    <div class="ctrl-k-btn" onclick="openQuickSearchModal()" style="display:flex; align-items:center; gap:10px; background:#f1f5f9; border:1px solid #cbd5e1; padding:7px 14px; border-radius:10px; cursor:pointer; font-size:13px; color:#64748b; transition:all 0.2s;" onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='#cbd5e1'">
                        <i class="fa fa-magnifying-glass" style="color:var(--primary);"></i>
                        <span>Search item, barcode, stock...</span>
                        <kbd style="background:#fff; border:1px solid #cbd5e1; padding:2px 6px; border-radius:4px; font-size:11px; font-weight:700; color:#475569;">Ctrl K</kbd>
                    </div>
                </div>

                <div class="user-nav">
                    <a href="<?= BASE_URL ?>pos" class="btn btn-primary" style="padding:6px 14px; font-size:12px; font-weight:700;">
                        <i class="fa fa-cash-register"></i> <span>Fast POS</span>
                    </a>
                    <div class="user-badge">
                        <div class="avatar"><?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?></div>
                        <div>
                            <div style="font-weight: 700; font-size: 13.5px;"><?= htmlspecialchars($user['name'] ?? 'Staff') ?></div>
                            <div style="font-size: 11px; color: var(--text-muted); font-weight: 600;"><?= htmlspecialchars($user['role'] ?? 'Store Staff') ?></div>
                        </div>
                    </div>
                    <a href="<?= BASE_URL ?>logout" class="btn-logout"><i class="fa fa-power-off"></i> Exit</a>
                </div>
            </div>

            <!-- Global Ctrl+K Quick Search Modal -->
            <div id="globalQuickSearchModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(15,23,42,0.65); z-index:99999; backdrop-filter:blur(4px); align-items:flex-start; justify-content:center; padding-top:80px;">
                <div style="background:#fff; border-radius:16px; width:600px; max-width:92%; box-shadow:var(--shadow-xl); overflow:hidden; border:1px solid var(--card-border);">
                    <div style="display:flex; align-items:center; padding:16px 20px; border-bottom:1.5px solid #f1f5f9; gap:12px;">
                        <i class="fa fa-magnifying-glass" style="color:var(--primary); font-size:18px;"></i>
                        <input type="text" id="global_search_input" placeholder="Type item name, SKU, or scan barcode..." style="width:100%; border:none; outline:none; font-size:15px; font-family:inherit; color:#0f172a;" autocomplete="off">
                        <span onclick="closeQuickSearchModal()" style="cursor:pointer; color:#94a3b8; font-size:18px;"><i class="fa fa-xmark"></i></span>
                    </div>
                    <div id="global_search_results" style="max-height:360px; overflow-y:auto; padding:10px;">
                        <div style="padding:20px; text-align:center; color:#94a3b8; font-size:13px;">Type at least 1 character to search products & live stock...</div>
                    </div>
                    <div style="background:#f8fafc; padding:10px 16px; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; font-size:11.5px; color:#64748b;">
                        <span><kbd style="background:#fff; border:1px solid #cbd5e1; padding:2px 5px; border-radius:3px;">Esc</kbd> to close</span>
                        <span>Instant Barcode & Stock Check</span>
                    </div>
                </div>
            </div>

            <script>
            // Ctrl+K Listener
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    openQuickSearchModal();
                }
                if (e.key === 'Escape') {
                    closeQuickSearchModal();
                }
            });

            function openQuickSearchModal() {
                const modal = document.getElementById('globalQuickSearchModal');
                modal.style.display = 'flex';
                const input = document.getElementById('global_search_input');
                input.value = '';
                input.focus();
            }

            function closeQuickSearchModal() {
                document.getElementById('globalQuickSearchModal').style.display = 'none';
            }

            function toggleMobileSidebar() {
                const sb = document.querySelector('.sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sb.classList.toggle('mobile-open');
                overlay.classList.toggle('active');
            }

            function toggleTreeviewMenu(triggerLink) {
                const parentLi = triggerLink.closest('li.treeview');
                if (!parentLi) return;
                
                const submenu = parentLi.querySelector('.treeview-sub-menu');
                const isOpen = parentLi.classList.contains('menu-open');

                if (isOpen) {
                    parentLi.classList.remove('menu-open');
                    if (submenu) submenu.style.display = 'none';
                } else {
                    parentLi.classList.add('menu-open');
                    if (submenu) submenu.style.display = 'block';
                }
            }

            function filterSidebarMenu() {
                const query = document.getElementById('sidebar-menu-search').value.toLowerCase().trim();
                const menuList = document.getElementById('mainSidebarMenu');
                const topItems = menuList.children;

                Array.from(topItems).forEach(item => {
                    if (item.classList.contains('treeview')) {
                        const parentTitle = item.querySelector('.menu-label')?.innerText.toLowerCase() || '';
                        const subLinks = item.querySelectorAll('.treeview-sub-menu li');
                        let hasSubMatch = false;

                        subLinks.forEach(subLi => {
                            const subText = subLi.innerText.toLowerCase();
                            if (!query || subText.includes(query)) {
                                subLi.style.display = '';
                                hasSubMatch = true;
                            } else {
                                subLi.style.display = 'none';
                            }
                        });

                        const isParentMatch = parentTitle.includes(query);
                        const submenu = item.querySelector('.treeview-sub-menu');

                        if (!query) {
                            item.style.display = '';
                            if (submenu) {
                                submenu.style.display = item.classList.contains('active') ? 'block' : 'none';
                            }
                        } else if (hasSubMatch || isParentMatch) {
                            item.style.display = '';
                            item.classList.add('menu-open');
                            if (submenu) submenu.style.display = 'block';
                            if (isParentMatch) {
                                subLinks.forEach(subLi => subLi.style.display = '');
                            }
                        } else {
                            item.style.display = 'none';
                        }
                    } else {
                        // Normal standalone menu item (e.g., Dashboard, Close Store)
                        const text = item.innerText.toLowerCase();
                        if (!query || text.includes(query)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            }

            let globalSearchTimer = null;
            document.getElementById('global_search_input').addEventListener('input', function() {
                clearTimeout(globalSearchTimer);
                const q = this.value.trim();
                const resBox = document.getElementById('global_search_results');

                if (q.length < 1) {
                    resBox.innerHTML = '<div style="padding:20px; text-align:center; color:#94a3b8; font-size:13px;">Type to search products & live stock...</div>';
                    return;
                }

                globalSearchTimer = setTimeout(() => {
                    fetch('<?= BASE_URL ?>api.php?action=search_items&q=' + encodeURIComponent(q))
                        .then(r => r.json())
                        .then(items => {
                            if (!items || items.length === 0) {
                                resBox.innerHTML = '<div style="padding:20px; text-align:center; color:#94a3b8; font-size:13px;">No items found matching your query.</div>';
                                return;
                            }
                            let html = '';
                            items.forEach(it => {
                                const stockBadge = it.stock <= 5 
                                    ? `<span class="badge badge-danger">${it.stock} ${it.unit}</span>` 
                                    : `<span class="badge badge-success">${it.stock} ${it.unit}</span>`;

                                html += `
                                    <div style="padding:12px 14px; border-radius:10px; display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin-bottom:4px; transition:background 0.15s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                                        <div>
                                            <div style="font-weight:700; color:#0f172a; font-size:14px;">${it.name}</div>
                                            <div style="font-size:12px; color:#64748b; margin-top:2px;">
                                                SKU: <code>${it.code}</code> ${it.barcode ? '| Barcode: ' + it.barcode : ''}
                                            </div>
                                        </div>
                                        <div style="text-align:right;">
                                            <div style="font-weight:700; color:var(--primary); font-size:14px;">₹${parseFloat(it.sale_price).toFixed(2)}</div>
                                            <div style="margin-top:2px;">${stockBadge}</div>
                                        </div>
                                    </div>
                                `;
                            });
                            resBox.innerHTML = html;
                        })
                        .catch(err => console.error(err));
                }, 200);
            });
            </script>
            <div class="content">
    <?php
}

function render_footer() {
    ?>
            </div>
        </div>
    </body>
    </html>
    <?php
}
