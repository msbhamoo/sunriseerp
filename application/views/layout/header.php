<!DOCTYPE html>
<html <?php echo $this->customlib->getRTL(); ?>>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta http-equiv="Cache-control" content="no-cache">
        <meta name="theme-color" content="#424242" />
		<link rel="stylesheet" href="<?php echo base_url('theme.css'); ?>">
		 <script>
        // === Load PHP theme settings safely ===
        const rawThemeSettings = <?php echo json_encode($this->customlib->getCurrentThemeSetting() ?? new stdClass()); ?>;
        const themeSettings = rawThemeSettings || {};
        (function() {																																		   
																													 

            // === Extract settings with fallback defaults ===
            const themeBackground = themeSettings.theme_background || 'light-mode';
            const savedLayout = themeSettings.theme_content || 'container-fluid';
            const themeShadow = themeSettings.theme_shadow || '';
            const themeColor = themeSettings.theme_color || '#7367f0';
            const themeNavigation = themeSettings.theme_navigation || "expanded";

            const themeClass = themeBackground === 'dark' ? 'dark' : 'light-mode';
            const themeClassNavigation = themeNavigation === 'collapsed' ? 'sidebar-collapse' : '';

            // === Apply theme, shadow, and navigation classes to <body> ===
            function applyBodyClasses() {
                if (!document.body) return;
                const classList = document.body.classList;
                // Remove existing theme and shadow classes
                classList.remove('light-mode', 'dark', 'sidebar-collapse');
                [...classList].forEach(cls => {
                    if (cls.startsWith('shadow')) classList.remove(cls);
                });

                classList.add(themeClass);
                if (themeShadow) {
                    classList.add(themeShadow);
                }

                if (themeClassNavigation) {
                    console.log(themeClassNavigation);
                    classList.add(themeClassNavigation);
                }
                
                // Inject the dynamic theme color as a CSS variable for our custom sidebar
                document.documentElement.style.setProperty('--primary-theme-color', themeColor);
            }

            // === Apply layout and update icons ===
            function applyLayoutAndIcons() {
                const content = document.querySelector("section.content");
                const layoutIcon = document.getElementById('content-icon');
                const shadowIcon = document.getElementById('iconskins');
                const navigationIcon = document.getElementById('icon_theme_navigation');

                if (content && !content.classList.contains(savedLayout)) {
                    content.classList.add(savedLayout);
                }

                if (navigationIcon) {
                    const isCollapsed = themeClassNavigation === 'sidebar-collapse';

                    navigationIcon.classList.toggle('fa-bars', isCollapsed);
                    navigationIcon.classList.toggle('fa-brands', !isCollapsed);
                    navigationIcon.classList.toggle('fa-elementor', !isCollapsed);
                }

                if (layoutIcon) {
                    layoutIcon.classList.toggle('fa-compress', savedLayout === 'container-fluid');
                    layoutIcon.classList.toggle('fa-expand', savedLayout !== 'container-fluid');
                }

                if (shadowIcon) {
                    shadowIcon.classList.toggle('fa-border-none', themeShadow === 'shadow-applied');
                    shadowIcon.classList.toggle('fa-border-all', themeShadow !== 'shadow-applied');
                }

                // Stop observing once applied
                if (content || layoutIcon || shadowIcon) {
                    layoutObserver.disconnect();
                }
            }

            // === Watch for body and content appearance if not yet in DOM ===
            if (document.body) {
                applyBodyClasses();
            } else {
                new MutationObserver((mutations, observer) => {
                    if (document.body) {
                        applyBodyClasses();
                        observer.disconnect();
                    }
                }).observe(document.documentElement, {
                    childList: true
                });
            }

            const layoutObserver = new MutationObserver(applyLayoutAndIcons);

            layoutObserver.observe(document.documentElement, {
                childList: true,
                subtree: true,
            });

            // Try applying immediately in case elements are already in DOM
            applyLayoutAndIcons();

        })();
    </script>
    <?php
    $this->load->view('layout/theme-color');
    ?>	 
		
        <link href="<?php echo $this->customlib->getBaseUrl(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/jquery.mCustomScrollbar.min.css">
       <?php
    $this->load->view('layout/theme');
    ?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css">
		<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/all.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css?v=<?php echo time(); ?>">
        <!--file dropify-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
        <!--file nprogress-->
        <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">
        <!--print table-->
        <!--language css-->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/bootstrap-select.min.css">
		 <link rel="stylesheet" href="https://icons.getbootstrap.com/assets/font/bootstrap-icons.min.css" />
        <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
        <script language="javascript" src="<?php echo base_url(); ?>backend/custom/jquery-2.2.4.js"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>

        <script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
         <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
        <script src="<?php echo base_url(); ?>backend/plugins/colorpicker/bootstrap-colorpicker.js"></script>

        <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/school-admin-custom.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/export_lib.js"></script>
        
        <!-- fullCalendar -->
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css" media="print">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/dist/css/lucide-static@0.543.0/lucide.css">
        <!-- Modern Typography (Inter Font) -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script type="text/javascript">
            var baseurl = "<?php echo base_url(); ?>";
            var start_week=<?php echo $this->customlib->getStartWeek(); ?>;
            var chk_validate="<?php echo $this->config->item('SSLK') ?>";
        </script>

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
    </head>
	
    <body class="hold-transition skin-blue sidebar-mini">		
	


       <div class="wrapper">
			 <?php $result = $this->customlib->getLoggedInUserData(); 
			  ?>
            <header class="main-header" id="alert">
                <a href="<?php echo base_url(); ?>admin/admin/dashboard" class="logo">
                    <span class="logo-mini"><img src="<?php echo $this->customlib->getBaseUrl(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo() . img_time();?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                    <span class="logo-lg"><img src="<?php echo $this->customlib->getBaseUrl(); ?>uploads/school_content/admin_logo/<?php echo $this->setting_model->getAdminlogo() . img_time();?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" title="Toggle Sidebar">
                        <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                        <i class="icon-menu"></i>
                    </a>				
					
                    <div class="col-lg-4 col-md-3 col-sm-2 col-xs-4">
                        <span href="#"  class="sidebar-session">
                            <?php echo $this->setting_model->getCurrentSchoolName(); ?>
                        </span>
                    </div>
                    <div class="col-lg-8 col-md-9 col-sm-10 col-xs-8">
                        <div class="pull-right">
                            <?php if ($this->rbac->hasPrivilege('student', 'can_view')) {?>

                            <?php }?>
                            <div class="navbar-custom-menu">
                                <?php if ($this->rbac->hasPrivilege('currency_switcher', 'can_view')) {
                                    $currency_count    = $this->customlib->get_active_currency_count();
                                    if ($currency_count > 1) { ?>
                                    <div class="currency-icon-list hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('currency') ?>">
                                        <select class="languageselectpicker" type="text" id="currencySwitcher" >
                                           <?php $this->load->view('admin/currency/currencySwitcher')?>
                                        </select>
                                    </div>
                                    <?php
                                }
} ?>

                                <?php if ($this->rbac->hasPrivilege('language_switcher', 'can_view')) {
                                     $language_count    = $this->customlib->get_active_language_count();
                                    if ($language_count > 1) { 
    ?>
                                    <div class="langdiv hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('language') ?>"><select class="languageselectpicker" onchange="set_languages(this.value)"  type="text" id="languageSwitcher" >

                                           <?php $this->load->view('admin/language/languageSwitcher')?>

                                        </select></div>
                                    <?php
                                     }
}?>

                                <ul class="nav navbar-nav headertopmenu">
 <!-- Dark/Light Mode Toggle Button -->

                                    <!-- QR Attendance: visible to every logged-in staff to mark their own attendance -->
                                    <li class="cal15" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('mark_my_attendance') ? $this->lang->line('mark_my_attendance') : 'Mark My Attendance'; ?>">
                                        <a href="<?php echo base_url() ?>admin/staffattendance/scan"><i class="fa fa-qrcode" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;" aria-hidden="true"></i></a>
                                    </li>

                                    <?php if ($this->rbac->hasPrivilege('student', 'can_view')) {?>
                                        <li class="cal15 hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('search'); ?>">
                                            <a href="#" data-toggle="modal" data-target="#globalSearchModal"><i class="icon-search" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;"></i></a>
                                        </li>
                                    <?php }?>
                                    
                                    <?php                                      
									if ($this->rbac->hasPrivilege('multi_branch_switch_branch', 'can_view')) {								
                                        if (($this->module_lib->hasModule('multi_branch') && $this->module_lib->hasActive('multi_branch')) || $this->db->multi_branch) { ?>
                                    
                                            <li class="cal15 hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_branch'); ?>"><a href="#" data-toggle="modal" data-target="#multiBranchSwitchModal"><i class="icon-arrow-right-left" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;" aria-hidden="true"></i></a></li>
                                    
                                    <?php } 
                                    }?>
                                    
                                    <?php if ($this->rbac->hasPrivilege('quick_session_change', 'can_view')) { ?>
                                            <li class="cal15 hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('current_session') . ": " . $this->setting_model->getCurrentSessionName(); ?>">
                                                <a href="#" data-toggle="modal" data-target="#sessionModal"><i class="icon-pencil" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;" aria-hidden="true"></i></a>
                                            </li>
                                    <?php } ?>
 
 
 <?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        ?>
                                            <li class="cal15 d-sm-none"><a data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('calendar') ?>" href="<?php echo base_url() ?>admin/calendar/events" ><i class="icon-calendar" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;"></i></a>

                                            </li>
                                            <?php
}
}
?>
                                    <?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        ?>
                                            <li class="dropdown hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('task') ?>">
                                                <a href="#"  class="dropdown-toggle todoicon" data-toggle="dropdown">
                                                    <i class="fa fa-check-square-o" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;"></i>
                                                    <?php
$userdata = $this->customlib->getUserData();
        $count    = $this->customlib->countincompleteTask($userdata["id"],$userdata["role_id"]);
        if ($count > 0) {
            ?>

                                                        <span class="todo-indicator"><?php echo $count ?></span>
                                                    <?php }?>
                                                </a>
                                                <ul class="dropdown-menu menuboxshadow">

                                                    <li class="todoview plr10 ssnoti"><?php echo $this->lang->line('today_you_have'); ?> <?php echo $count; ?> <?php echo $this->lang->line('pending_task'); ?><a href="<?php echo base_url() ?>admin/calendar/events" class="pull-right pt0"><?php echo $this->lang->line('view_all'); ?></a></li>
                                                    <li>
                                                        <ul class="todolist">
                                                            <?php
$tasklist = $this->customlib->getincompleteTask($userdata["id"],$userdata["role_id"]);
        foreach ($tasklist as $key => $value) {
            ?>
                                                                <li><div class="checkbox">
                                                                        <label><input type="checkbox" id="newcheck<?php echo $value["id"] ?>" onclick="markc('<?php echo $value["id"] ?>')" name="eventcheck"  value="<?php echo $value["id"]; ?>"><?php echo $value["event_title"] ?></label>
                                                                    </div></li>
                                                            <?php }?>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                        <li class="dropdown d-lg-none d-sm-block ellipsis-px-3">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;"></i>
                                        </a>
                                        <ul class="dropdown-menu min-w-full sm-drop-down">
                                          <?php if ($this->rbac->hasPrivilege('student', 'can_view')) {?>
                                              <li><a href="#" data-toggle="modal" data-target="#globalSearchModal"><i class="icon-search" style="margin-right: 10px; width: 16px; text-align: center;"></i> <?php echo $this->lang->line('search'); ?></a></li>
                                          <?php }?>
                                          <?php if ($this->rbac->hasPrivilege('multi_branch_switch_branch', 'can_view') && (($this->module_lib->hasModule('multi_branch') && $this->module_lib->hasActive('multi_branch')) || $this->db->multi_branch)) { ?>
                                              <li><a href="#" data-toggle="modal" data-target="#multiBranchSwitchModal"><i class="icon-arrow-right-left" style="margin-right: 10px; width: 16px; text-align: center;"></i> <?php echo $this->lang->line('switch_branch'); ?></a></li>
                                          <?php } ?>
                                          <?php if ($this->rbac->hasPrivilege('quick_session_change', 'can_view')) { ?>
                                              <li><a href="#" data-toggle="modal" data-target="#sessionModal"><i class="icon-pencil" style="margin-right: 10px; width: 16px; text-align: center;"></i> <?php echo $this->lang->line('current_session'); ?></a></li>
                                          <?php } ?>
                                          <?php if ($this->module_lib->hasActive('calendar_to_do_list') && $this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) { ?>
                                              <li><a href="<?php echo base_url() ?>admin/calendar/events"><i class="fa fa-check-square-o" style="margin-right: 10px; width: 16px; text-align: center;"></i> <?php echo $this->lang->line('task'); ?></a></li>
                                              <li><a href="<?php echo base_url() ?>admin/calendar/events"><i class="icon-calendar" style="margin-right: 10px; width: 16px; text-align: center;"></i> <?php echo $this->lang->line('calendar'); ?></a></li>
                                          <?php } ?>
                                          <?php 
                                          if ($this->module_lib->hasActive('chat')) {
                                            if ($this->rbac->hasPrivilege('chat', 'can_view')) { ?>
                                          <li><a href="<?php echo base_url() ?>admin/chat"><i class="icon-message-circle" style="margin-right: 10px; width: 16px; text-align: center;"></i> <?php echo $this->lang->line('chat'); ?></a></li>
                                          <?php } } ?>
                                         

<?php  
	if($result['admin_panel_whatsapp']){ 
	$waurl = "https://wa.me/";
	$mobile = $result['admin_panel_whatsapp_mobile'];	 
	$url = $waurl.$mobile;
	$today = strtotime(date("H:i:s"));
	$show_hide = 1;
	
	if($result['admin_panel_whatsapp_from'] != '' && $result['admin_panel_whatsapp_to'] != ''){
		
		$admin_panel_whatsapp_from = strtotime($result['admin_panel_whatsapp_from']);
		$admin_panel_whatsapp_to = strtotime($result['admin_panel_whatsapp_to']);
	
		if($today>=$admin_panel_whatsapp_from && $today<=$admin_panel_whatsapp_to){
			$show_hide = 1;
		}else{
			$show_hide = 0;
		}
		
	}
	
	if($show_hide){
?>
<li><a href="<?php echo $url; ?>" target="_blank"><i class="fa fa-whatsapp" style="margin-right: 10px; width: 16px; text-align: center; color:#25D366; font-size:16px;"></i> <?php echo $this->lang->line('whatsapp_link') ?></a></li>
<?php } } ?>

                                        </ul>
                                      </li>
                                            <?php
}
}
if ($this->module_lib->hasActive('chat')) {
    if ($this->rbac->hasPrivilege('chat', 'can_view')) {
        ?>
    <li class="cal15 d-sm-none">

        <a data-placement="bottom" data-toggle="tooltip" title="" href="<?php echo base_url() ?>admin/chat" data-original-title="<?php echo $this->lang->line('chat') ?>" class="todoicon">
            <i class="icon-message-circle" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;"></i>
            <span class="total_chat_msg topbadges"><?php $msg_count=$this->customlib->get_chat_msg_count(); echo count($msg_count); ?></span>
        </a>

   
    </li>
<?php
}
?>

                                <?php }
$file   = "";
$role = $this->customlib->getStaffRole();
$image = $result["image"];
$role  = json_decode($role)->name;
$id    = $result["id"];
if (!empty($image)) {

    $file = "uploads/staff_images/" . $image . img_time();
} else {
    if ($result['gender'] == 'Female') {
        $file = "uploads/staff_images/default_female.jpg" . img_time();
    } else {
        $file = "uploads/staff_images/default_male.jpg" . img_time();
    }
}
?>                              

<?php  
	if($result['admin_panel_whatsapp']){ 
	$waurl = "https://wa.me/";
	$mobile = $result['admin_panel_whatsapp_mobile'];	 	
	$url = $waurl.$mobile;
	$today = strtotime(date("H:i:s")); 
	
	$show_hide = 1;
	if($result['admin_panel_whatsapp_from'] != '' && $result['admin_panel_whatsapp_to'] != ''){
		
		$admin_panel_whatsapp_from = strtotime($result['admin_panel_whatsapp_from']);
		$admin_panel_whatsapp_to = strtotime($result['admin_panel_whatsapp_to']);
		
		if($today>=$admin_panel_whatsapp_from && $today<=$admin_panel_whatsapp_to){
			$show_hide = 1;
		}else{
			$show_hide = 0;
		}		
	}
	
	if($show_hide){
?>


<li class="cal15 whatsapp-icon-bg d-sm-none"><a target="_blank" href="<?php echo $url; ?>" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('whatsapp_link') ?>">
<svg height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
     viewBox="0 0 512 512" xml:space="preserve">
<path style="fill:#fff;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
    S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"/>
<path style="fill:#55CD6C;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
    c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
    c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"/>
<path style="fill:#fff;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
    c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
    c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
    c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
    c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
    l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"/>
</svg></a></li> 

<?php } } ?>

                                    <!-- System Notifications Bell -->
                                    <li class="hidden-xs" data-placement="bottom" data-toggle="tooltip" title="System Notifications">
                                        <a href="#" id="sys-notification-bell">
                                            <i class="icon-bell" style="font-size: 18px; font-weight: bold; position: relative; top: 2px;"></i>
                                            <span class="todo-indicator sys-notification-count" style="display:none;">0</span>
                                        </a>
                                    </li>

                                    <li class="dropdown user-menu">
                                        <a class="dropdown-toggle" style="padding: 15px 12px;" data-toggle="dropdown" href="#" aria-expanded="false">
                                            <img src="<?php echo base_url($file); ?>" class="topuser-image" alt="User Image">
                                        </a>
                                        <ul class="dropdown-menu dropdown-user menuboxshadow">
                                            <li>
                                                <div class="sstopuser">
                                                    <div class="ssuserleft">
                                                        <a href="<?php echo base_url() . "admin/staff/profile/" . $id ?>"><img src="<?php echo base_url($file); ?>" alt="User Image"></a>
                                                    </div>
                                                    <div class="sstopuser-test">
                                                        <h4 class="text-capitalize mb0"><a href="<?php echo base_url() . "admin/staff/profile/" . $id ?>"><?php echo $this->customlib->getAdminSessionUserName(); ?></a></h4>
                                                        <h5 class="mt0"><?php echo $role; ?></h5>
                                                        <a href="<?php echo base_url(); ?>admin/admin/changepass"><i class="icon-key" style="margin-right: 5px;"></i> <?php echo $this->lang->line('password'); ?></a>
                                                    </div>
                                                    <div class="divider"></div>
                                                    <div class="sspass">
                                                        <?php
                                                        $getfrontcmssetting =$this->customlib->getfrontcmssetting();
                                                        if($getfrontcmssetting->is_active_front_cms){  ?>
                                                        <a style="" href="<?php echo base_url(); ?>" target="_blank" class="forgot"> <i class="icon-globe" style="margin-right: 5px;"></i>
                                                        <?php echo $this->lang->line('front_site'); ?>
                                                        </a>

                                                        <?php } ?>
                                                    

                                                        <a href="<?php echo base_url(); ?>site/logout"><i class="icon-log-out" style="margin-right: 5px;"></i><?php echo $this->lang->line('logout'); ?></a>
                                                    </div>
                                                </div><!--./sstopuser--></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>

            <!-- System Notifications Offcanvas -->
            <div class="sys-offcanvas" id="sys-offcanvas">
                <div class="sys-offcanvas-header">
                    <h4>Notifications</h4>
                    <div>
                        <a href="#" id="sys-mark-all-read" style="font-size:12px; color:#3b82f6; margin-right:15px; text-decoration:none;">Mark all as read</a>
                        <span class="sys-offcanvas-close" id="sys-offcanvas-close">&times;</span>
                    </div>
                </div>
                <div class="sys-offcanvas-body" id="sys-notification-list">
                    <div style="text-align: center; color: #6b7280; padding: 20px;">Loading...</div>
                </div>
            </div>

            <?php $this->load->view('layout/sidebar');?>
<script>
    function set_languages(lang_id){
        $.ajax({
        type: "POST",
        url: base_url + "admin/language/user_language/"+lang_id,
        data: {},
        success: function (data) {
            successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
            window.location.reload('true');
        }
        });
    }
</script>
<script>
    $(document).ready(function() {
        // Toggle Offcanvas
        $('#sys-notification-bell').on('click', function(e) {
            e.preventDefault();
            $('#sys-offcanvas').toggleClass('open');
        });
        $('#sys-offcanvas-close').on('click', function() {
            $('#sys-offcanvas').removeClass('open');
        });

        function renderGroup(title, alerts) {
            if (!alerts || alerts.length === 0) return '';
            var html = '<div class="sys-alert-group">';
            html += '<div class="sys-alert-group-title">' + title + '</div>';
            $.each(alerts, function(i, alert) {
                var link = alert.action_url ? baseurl + alert.action_url : '#';
                var readClass = (alert.is_read == '1' || alert.is_read == 1) ? 'read-alert' : 'unread-alert';
                html += '<a href="' + link + '" class="sys-alert-item ' + readClass + '" data-id="' + alert.id + '">';
                html += '<strong>' + alert.title + '</strong>';
                html += '<small>' + alert.message + '</small>';
                html += '<span class="sys-unread-dot"></span>';
                html += '</a>';
            });
            html += '</div>';
            return html;
        }

        function loadSystemAlerts() {
            $.ajax({
                url: baseurl + 'admin/systemalerts/get_alerts',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var count = parseInt(data.count);
                    if (count > 0) {
                        $('.sys-notification-count').text(count).show();
                    } else {
                        $('.sys-notification-count').hide();
                    }
                    
                    var html = '';
                    if (data.grouped_alerts) {
                        html += renderGroup('Today', data.grouped_alerts.today);
                        html += renderGroup('Yesterday', data.grouped_alerts.yesterday);
                        html += renderGroup('Last Week', data.grouped_alerts.last_week);
                        html += renderGroup('Last Month', data.grouped_alerts.last_month);
                    }
                    
                    if (html === '') {
                        html = '<div style="text-align: center; color: #6b7280; padding: 20px;">No new notifications</div>';
                    }
                    
                    $('#sys-notification-list').html(html);
                }
            });
        }
        
        loadSystemAlerts();
        setInterval(loadSystemAlerts, 60000); // Poll every minute
        
        // Mark Single as Read
        $(document).on('click', '.sys-alert-item.unread-alert', function(e) {
            var id = $(this).data('id');
            $.ajax({
                url: baseurl + 'admin/systemalerts/mark_as_read',
                type: 'POST',
                data: {id: id},
                success: function() {
                    loadSystemAlerts();
                }
            });
        });

        // Mark All as Read
        $('#sys-mark-all-read').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: baseurl + 'admin/systemalerts/mark_all_as_read',
                type: 'POST',
                success: function() {
                    loadSystemAlerts();
                }
            });
        });
    });
</script>
<!-- Global Search Modal -->
<style>
.global-search-modal .modal-content {
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.15);
    font-family: 'Inter', sans-serif;
}
.global-search-modal .modal-header {
    background-color: var(--bs-primary, #f0852e);
    border-bottom: 1px solid #eaeaea;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.global-search-modal .modal-title {
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin: 0;
}
.global-search-modal .close {
    color: #fff;
    opacity: 0.9;
    font-size: 22px;
    font-weight: 400;
    margin-top: -2px;
    text-shadow: none;
}
.global-search-modal .close:hover {
    color: #fff;
    opacity: 1;
}
.global-search-modal .modal-body {
    background: #f4f6f9;
    padding: 25px 20px;
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
}
.gs-input-group {
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border-radius: 6px;
    display: flex;
    width: 100%;
}
.gs-input {
    border: 1px solid #eaeaea;
    height: 46px;
    border-radius: 6px 0 0 6px !important;
    font-size: 15px;
    box-shadow: none !important;
    flex: 1;
    padding: 10px 15px;
}
.gs-input:focus {
    border-color: var(--bs-primary, #f0852e);
    outline: none;
}
.gs-btn {
    background-color: var(--bs-primary, #f0852e);
    color: #fff;
    border: none;
    height: 46px;
    width: 50px;
    border-radius: 0 6px 6px 0 !important;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    transition: background 0.2s;
}
.gs-btn:hover {
    opacity: 0.9;
    color: #fff;
}
.gs-results {
    background: #fff;
    border-radius: 6px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    border: 1px solid #eaeaea;
    margin-top: 8px;
    max-height: 320px;
    overflow-y: auto;
    position: absolute;
    width: 100%;
    z-index: 1050;
    display: none;
}
.gs-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #eaeaea;
    margin-top: 25px;
    display: none;
}
.gs-card-img-wrapper {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 3px solid #f4f6f9;
    overflow: hidden;
    margin: 0 auto;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.gs-card-img-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.gs-table {
    width: 100%;
    margin-bottom: 0;
}
.gs-table td {
    padding: 10px 8px;
    border-bottom: 1px solid #f0f0f0;
}
.gs-table tr:last-child td {
    border-bottom: none;
}
.gs-card-label {
    font-size: 12px;
    font-weight: 600;
    color: #8a8a8a;
    text-transform: uppercase;
    width: 35%;
}
.gs-card-val {
    font-size: 14px;
    font-weight: 700;
    color: #2c2c2c;
}
.gs-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}
.gs-action-btn {
    padding: 8px 16px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 500;
    border: 1px solid #ccc;
    background: #fff;
    color: #333;
    transition: all 0.2s;
    text-decoration: none !important;
}
.gs-action-btn:hover { 
    background: #f5f5f5; 
    color: #333;
}
.gs-action-btn.primary { 
    background-color: var(--bs-primary, #f0852e);
    color: #fff; 
    border: none;
}
.gs-action-btn.primary:hover { 
    opacity: 0.9;
    color: #fff;
}
.modal-search-item {
    display: flex;
    padding: 12px 15px;
    border-bottom: 1px solid #eaeaea;
    color: #333;
    text-decoration: none !important;
    align-items: center;
    transition: background 0.2s;
}
.modal-search-item:hover {
    background: #f8f9fa;
    color: var(--bs-primary, #f0852e);
}
.modal-search-item:last-child {
    border-bottom: none;
}
</style>

<div class="modal fade global-search-modal" id="globalSearchModal" tabindex="-1" role="dialog" aria-labelledby="globalSearchModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="globalSearchModalLabel">Search Student</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <form id="global_search_form" action="<?php echo site_url('admin/admin/search'); ?>" method="POST" style="margin:0;">
            <?php echo $this->customlib->getCSRF(); ?>
            <div style="position:relative;">
                <div class="gs-input-group">
                    <input type="text" value="" name="search_text1" id="modal_search_text" class="gs-input" placeholder="Search by Name, Admission No, etc." autocomplete="off" autofocus>
                    <button type="submit" name="search" class="gs-btn btn-primary"><i class="fa fa-search"></i></button>
                </div>
                <div id="modal_ajax_search_results_container" class="gs-results"></div>
            </div>
        </form>

        <div id="modal_student_details" class="gs-card">
            <div class="row" style="margin:0; display:flex; align-items:center;">
                <div class="col-sm-4 text-center" style="padding: 15px;">
                    <div class="gs-card-img-wrapper">
                        <img id="detail_student_image" src="">
                    </div>
                </div>
                <div class="col-sm-8" style="padding: 15px;">
                    <table class="gs-table">
                        <tbody>
                            <tr>
                                <td class="gs-card-label">Name</td>
                                <td id="detail_student_name" class="gs-card-val"></td>
                            </tr>
                            <tr>
                                <td class="gs-card-label">Admission No</td>
                                <td id="detail_student_admission" class="gs-card-val"></td>
                            </tr>
                            <tr>
                                <td class="gs-card-label">Class</td>
                                <td id="detail_student_class" class="gs-card-val"></td>
                            </tr>
                            <tr>
                                <td class="gs-card-label">Father Name</td>
                                <td id="detail_student_father" class="gs-card-val" style="font-weight: 500;"></td>
                            </tr>
                            <tr>
                                <td class="gs-card-label">Mother Name</td>
                                <td id="detail_student_mother" class="gs-card-val" style="font-weight: 500;"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) { ?>
            <div class="gs-fee-summary" style="margin-top: 20px; border-top: 1px solid #f0f0f0; padding-top: 15px;">
                <h5 style="font-weight: 600; color: #2c2c2c; margin-bottom: 15px; font-size: 14px;">Fees Summary</h5>
                <table class="gs-table" style="font-size: 13px;">
                    <thead>
                        <tr>
                            <th style="color: #8a8a8a; text-transform: uppercase; font-size: 11px; padding: 8px; border-bottom: 1px solid #eaeaea;">Fee Head</th>
                            <th style="color: #8a8a8a; text-transform: uppercase; font-size: 11px; padding: 8px; border-bottom: 1px solid #eaeaea;">Total Fees</th>
                            <th style="color: #8a8a8a; text-transform: uppercase; font-size: 11px; padding: 8px; border-bottom: 1px solid #eaeaea;">Collected</th>
                            <th style="color: #8a8a8a; text-transform: uppercase; font-size: 11px; padding: 8px; border-bottom: 1px solid #eaeaea;">Due</th>
                        </tr>
                    </thead>
                    <tbody id="detail_student_fees_body">
                        <tr><td colspan="4" class="text-center" style="padding: 15px;"><i class="fa fa-spinner fa-spin"></i> Loading fees...</td></tr>
                    </tbody>
                </table>
            </div>
            <?php } ?>

            <div class="gs-actions">
                <a id="btn_view_profile" href="#" class="gs-action-btn"><i class="fa fa-user"></i> View Profile</a>
                <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_add')) { ?>
                <a id="btn_collect_fee" href="#" class="gs-action-btn btn-primary primary"><i class="fa fa-money"></i> Collect Fee</a>
                <?php } ?>
            </div>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#modal_search_text');
    const resultsContainer = $('#modal_ajax_search_results_container');
    const studentDetailsContainer = $('#modal_student_details');
    let searchDataCache = {};

    // Focus input when modal opens
    $('#globalSearchModal').on('shown.bs.modal', function () {
        $('#modal_search_text').focus();
    });

    // Reset when modal closes
    $('#globalSearchModal').on('hidden.bs.modal', function () {
        searchInput.val('');
        resultsContainer.empty().hide();
        studentDetailsContainer.hide();
    });

    searchInput.on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();
        studentDetailsContainer.hide(); // Hide details when typing again

        if (query.length >= 2) {
            searchTimeout = setTimeout(function() {
                var postData = {
                    search_text: query
                };
                
                var csrfInput = $('#global_search_form input[type="hidden"]');
                if (csrfInput.length > 0) {
                    var csrfName = csrfInput.attr('name');
                    var csrfHash = csrfInput.val();
                    if (csrfName && csrfHash) {
                        postData[csrfName] = csrfHash;
                    }
                }

                $.ajax({
                    url: baseurl + 'admin/admin/ajax_search',
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        resultsContainer.empty().show();
                        if (response.status === 'success' && response.data && response.data.length > 0) {
                            searchDataCache = {}; // Reset cache
                            response.data.forEach(function(student) {
                                searchDataCache[student.id] = student; // Store for later use
                                
                                const fatherName = student.father_name ? student.father_name : 'N/A';
                                const motherName = student.mother_name ? student.mother_name : 'N/A';
                                const className = student.class ? student.class : '';
                                const sectionName = student.section ? student.section : '';
                                const admNo = student.admission_no ? student.admission_no : '';

                                const html = `
                                    <a href="#" data-student-id="${student.id}" class="ajax-search-item modal-search-item">
                                        <img src="${student.image}" alt="Student" class="ajax-search-avatar">
                                        <div class="ajax-search-details">
                                            <div class="ajax-search-col">
                                                <span class="ajax-search-name">${student.full_name}</span>
                                                <span class="ajax-search-meta">${className} - ${sectionName} &nbsp;&nbsp; ${admNo}</span>
                                            </div>
                                            <div class="ajax-search-parents">
                                                <span style="display:flex; align-items:center; margin-bottom:4px;"><span class="parent-badge badge-father">F</span> ${fatherName}</span>
                                                <span style="display:flex; align-items:center;"><span class="parent-badge badge-mother">M</span> ${motherName}</span>
                                            </div>
                                        </div>
                                        <i class="fa fa-chevron-right ajax-search-icon"></i>
                                    </a>
                                `;
                                resultsContainer.append(html);
                            });
                        } else {
                            resultsContainer.html('<div class="ajax-search-no-result">No students found</div>');
                        }
                    }
                });
            }, 300);
        } else {
            resultsContainer.empty().hide();
        }
    });

    // Handle click on a student result
    $(document).on('click', '.modal-search-item', function(e) {
        e.preventDefault();
        const studentId = $(this).data('student-id');
        const student = searchDataCache[studentId];
        
        if (student) {
            resultsContainer.hide();
            
            // Populate Details
            $('#detail_student_image').attr('src', student.image);
            $('#detail_student_name').text(student.full_name);
            $('#detail_student_admission').text(student.admission_no);
            $('#detail_student_class').text((student.class ? student.class : '') + (student.section ? ' (' + student.section + ')' : ''));
            $('#detail_student_father').text(student.father_name ? student.father_name : 'N/A');
            $('#detail_student_mother').text(student.mother_name ? student.mother_name : 'N/A');
            
            // Set CTA Links
            $('#btn_view_profile').attr('href', baseurl + 'student/view/' + student.id);
            if ($('#btn_collect_fee').length) {
                $('#btn_collect_fee').attr('href', baseurl + 'studentfee/addfee/' + student.student_session_id);
            }
            
            studentDetailsContainer.fadeIn(200);

            if ($('.gs-fee-summary').length > 0) {
                // Show loading for fees
                $('.gs-fee-summary').show();
                $('#detail_student_fees_body').html('<tr><td colspan="4" class="text-center" style="padding: 15px;"><i class="fa fa-spinner fa-spin"></i> Loading fees...</td></tr>');
                
                // Fetch Fee Summary
                var feePostData = {
                    student_session_id: student.student_session_id
                };
                var csrfInput = $('#global_search_form input[type="hidden"]');
                if (csrfInput.length > 0) {
                    var csrfName = csrfInput.attr('name');
                    var csrfHash = csrfInput.val();
                    if (csrfName && csrfHash) {
                        feePostData[csrfName] = csrfHash;
                    }
                }

                $.ajax({
                    url: baseurl + 'admin/certificateregister/get_student_fee_summary_ajax',
                    type: 'POST',
                    data: feePostData,
                    dataType: 'json',
                    success: function(response) {
                        if (response) {
                            var html = '';
                            var feeCount = 0;
                            if (response.academic && parseFloat(response.academic.total) > 0) {
                                html += '<tr><td style="padding: 8px; color: #4b5563; font-weight: 600;">Academic Fees</td><td style="padding: 8px; color: #4b5563;">' + parseFloat(response.academic.total).toFixed(2) + '</td><td style="padding: 8px; color: #4b5563;">' + parseFloat(response.academic.collected).toFixed(2) + '</td><td style="padding: 8px; color: #1f2937; font-weight: 700;">' + parseFloat(response.academic.due).toFixed(2) + '</td></tr>';
                                feeCount++;
                            }
                            if (response.transport && parseFloat(response.transport.total) > 0) {
                                html += '<tr><td style="padding: 8px; color: #4b5563; font-weight: 600;">Transport Fees</td><td style="padding: 8px; color: #4b5563;">' + parseFloat(response.transport.total).toFixed(2) + '</td><td style="padding: 8px; color: #4b5563;">' + parseFloat(response.transport.collected).toFixed(2) + '</td><td style="padding: 8px; color: #1f2937; font-weight: 700;">' + parseFloat(response.transport.due).toFixed(2) + '</td></tr>';
                                feeCount++;
                            }
                            if (response.hostel && parseFloat(response.hostel.total) > 0) {
                                html += '<tr><td style="padding: 8px; color: #4b5563; font-weight: 600;">Hostel Fees</td><td style="padding: 8px; color: #4b5563;">' + parseFloat(response.hostel.total).toFixed(2) + '</td><td style="padding: 8px; color: #4b5563;">' + parseFloat(response.hostel.collected).toFixed(2) + '</td><td style="padding: 8px; color: #1f2937; font-weight: 700;">' + parseFloat(response.hostel.due).toFixed(2) + '</td></tr>';
                                feeCount++;
                            }

                            if (feeCount > 0) {
                                $('#detail_student_fees_body').html(html);
                            } else {
                                $('.gs-fee-summary').hide();
                            }
                        } else {
                            $('#detail_student_fees_body').html('<tr><td colspan="4" class="text-center text-danger" style="padding: 15px;">Failed to load fees.</td></tr>');
                        }
                    },
                    error: function() {
                        $('#detail_student_fees_body').html('<tr><td colspan="4" class="text-center text-danger" style="padding: 15px;">Failed to load fees.</td></tr>');
                    }
                });
            }
        }
    });

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#global_search_form').length && !$(e.target).closest('.modal-search-item').length) {
            resultsContainer.hide();
        }
    });

    // Show dropdown again if clicking back on input and it has value, but only if details aren't shown
    searchInput.on('focus', function() {
        if ($(this).val().trim().length >= 2 && resultsContainer.children().length > 0 && !studentDetailsContainer.is(':visible')) {
            resultsContainer.show();
        }
    });
});
</script>