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
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
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
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>				
					
                    <div class="col-lg-4 col-md-3 col-sm-2 col-xs-4">
                        <span href="#"  class="sidebar-session">
                            <?php echo $this->setting_model->getCurrentSchoolName(); ?>
                        </span>
                    </div>
                    <div class="col-lg-8 col-md-9 col-sm-10 col-xs-8">
                        <div class="pull-right">
                            <?php if ($this->rbac->hasPrivilege('student', 'can_view')) {?>
                                <form id="header_search_form" class="navbar-form navbar-left search-form" role="search"  action="<?php echo site_url('admin/admin/search'); ?>" method="POST" style="position:relative;">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="input-group">
                                        <input type="text" value="<?php echo set_value('search_text1'); ?>" name="search_text1" id="search_text1" class="form-control search-form search-form3" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>" autocomplete="off">
                                        <span class="input-group-btn">
                                            <button type="submit" name="search" id="search-btn" onclick="getstudentlist()" style="" class="btn btn-flat topsidesearchbtn"><i class="fa fa-search"></i></button>
                                        </span>
                                    </div>
                                    <div id="ajax_search_results_container" class="ajax-search-results"></div>
                                </form>
                            <?php }?>
                            <div class="navbar-custom-menu">
                                <?php if ($this->rbac->hasPrivilege('currency_switcher', 'can_view')) {
                                    $currency_count    = $this->customlib->get_active_currency_count();
                                    if ($currency_count > 1) { ?>
                                    <div class="currency-icon-list" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('currency') ?>">
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
                                    <div class="langdiv" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('language') ?>"><select class="languageselectpicker" onchange="set_languages(this.value)"  type="text" id="languageSwitcher" >

                                           <?php $this->load->view('admin/language/languageSwitcher')?>

                                        </select></div>
                                    <?php
                                     }
}?>

                                <ul class="nav navbar-nav headertopmenu">
 <!-- Dark/Light Mode Toggle Button -->                             
	
                                    <?php                                      
									if ($this->rbac->hasPrivilege('multi_branch_switch_branch', 'can_view')) {								
                                        if (($this->module_lib->hasModule('multi_branch') && $this->module_lib->hasActive('multi_branch')) || $this->db->multi_branch) { ?>
                                    
                                            <li class="cal15" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_branch'); ?>"><a href="#" data-toggle="modal" data-target="#multiBranchSwitchModal"><i class="fa fa-exchange" aria-hidden="true"></i></a></li>
                                    
                                    <?php } 
                                    }?>
                                    
                                    <?php if ($this->rbac->hasPrivilege('quick_session_change', 'can_view')) { ?>
                                            <li class="cal15" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('current_session') . ": " . $this->setting_model->getCurrentSessionName(); ?>">
                                                <a href="#" data-toggle="modal" data-target="#sessionModal"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                            </li>
                                    <?php } ?>
 
 
 <?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        ?>
                                            <li class="cal15 d-sm-none"><a data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('calendar') ?>" href="<?php echo base_url() ?>admin/calendar/events" ><i class="fa fa-calendar"></i></a>

                                            </li>
                                            <?php
}
}
?>
                                    <?php
if ($this->module_lib->hasActive('calendar_to_do_list')) {
    if ($this->rbac->hasPrivilege('calendar_to_do_list', 'can_view')) {
        ?>
                                            <li class="dropdown" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('task') ?>">
                                                <a href="#"  class="dropdown-toggle todoicon" data-toggle="dropdown">
                                                    <i class="fa fa-check-square-o"></i>
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
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v"></i>
                                        </a>
                                        <ul class="dropdown-menu min-w-full sm-drop-down">
                                          <li><a href="<?php echo base_url() ?>admin/calendar/events"><i class="fa fa-calendar"></i></a></li>
                                          <?php 
                                          if ($this->module_lib->hasActive('chat')) {
                                            if ($this->rbac->hasPrivilege('chat', 'can_view')) { ?>
                                          <li><a href="<?php echo base_url() ?>admin/chat"><i class="fa fa-comment-o"></i></a></li>
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
<li class="cal15 whatsapp-icon-bg"><a href="<?php echo $url; ?>" target="_blank" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('whatsapp_link') ?>">
<svg height="18px" width="18px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
<path style="fill:#fff;" d="M0,512l35.31-128C12.359,344.276,0,300.138,0,254.234C0,114.759,114.759,0,255.117,0
    S512,114.759,512,254.234S395.476,512,255.117,512c-44.138,0-86.51-14.124-124.469-35.31L0,512z"></path>
<path style="fill:#55CD6C;" d="M137.71,430.786l7.945,4.414c32.662,20.303,70.621,32.662,110.345,32.662
    c115.641,0,211.862-96.221,211.862-213.628S371.641,44.138,255.117,44.138S44.138,137.71,44.138,254.234
    c0,40.607,11.476,80.331,32.662,113.876l5.297,7.945l-20.303,74.152L137.71,430.786z"></path>
<path style="fill:#fff;" d="M187.145,135.945l-16.772-0.883c-5.297,0-10.593,1.766-14.124,5.297
    c-7.945,7.062-21.186,20.303-24.717,37.959c-6.179,26.483,3.531,58.262,26.483,90.041s67.09,82.979,144.772,105.048
    c24.717,7.062,44.138,2.648,60.028-7.062c12.359-7.945,20.303-20.303,22.952-33.545l2.648-12.359
    c0.883-3.531-0.883-7.945-4.414-9.71l-55.614-25.6c-3.531-1.766-7.945-0.883-10.593,2.648l-22.069,28.248
    c-1.766,1.766-4.414,2.648-7.062,1.766c-15.007-5.297-65.324-26.483-92.69-79.448c-0.883-2.648-0.883-5.297,0.883-7.062
    l21.186-23.834c1.766-2.648,2.648-6.179,1.766-8.828l-25.6-57.379C193.324,138.593,190.676,135.945,187.145,135.945"></path>
</svg></a></li>
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

        <a data-placement="bottom" data-toggle="tooltip" title="" href="<?php echo base_url() ?>admin/chat" data-original-title="<?php echo $this->lang->line('chat') ?>" class="todoicon"><i class="fa fa-comment-o"> <span class="total_chat_msg text-white badge bg-red topbadges"><?php
        $msg_count=$this->customlib->get_chat_msg_count();
        echo  count($msg_count);
    ?></span></i></a>

   
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
                                                        <a href="<?php echo base_url(); ?>admin/admin/changepass"><i class="fa fa-key"></i> <?php echo $this->lang->line('password'); ?></a>
                                                    </div>
                                                    <div class="divider"></div>
                                                    <div class="sspass">
                                                        <?php
                                                        $getfrontcmssetting =$this->customlib->getfrontcmssetting();
                                                        if($getfrontcmssetting->is_active_front_cms){  ?>
                                                        <a style="" href="<?php echo base_url(); ?>" target="_blank" class="forgot"> <i class="fa fa-empire"></i>
                                                        <?php echo $this->lang->line('front_site'); ?>
                                                        </a>

                                                        <?php } ?>
                                                    

                                                        <a href="<?php echo base_url(); ?>site/logout"><i class="fa fa-sign-out fa-fw"></i><?php echo $this->lang->line('logout'); ?></a>
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
    let searchTimeout;
    const searchInput = $('#search_text1');
    const resultsContainer = $('#ajax_search_results_container');

    searchInput.on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().trim();

        if (query.length >= 2) {
            searchTimeout = setTimeout(function() {
                var postData = {
                    search_text: query
                };
                
                // Automatically capture CSRF from the form if present
                var csrfInput = $('#header_search_form input[type="hidden"]');
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
                            response.data.forEach(function(student) {
                                const fatherName = student.father_name ? student.father_name : 'N/A';
                                const motherName = student.mother_name ? student.mother_name : 'N/A';
                                const className = student.class ? student.class : '';
                                const sectionName = student.section ? student.section : '';
                                const admNo = student.admission_no ? student.admission_no : '';

                                const html = `
                                    <a href="${baseurl}student/view/${student.id}" class="ajax-search-item">
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
                                        <i class="fa fa-share ajax-search-icon"></i>
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

    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#header_search_form').length) {
            resultsContainer.hide();
        }
    });

    // Show dropdown again if clicking back on input and it has value
    searchInput.on('focus', function() {
        if ($(this).val().trim().length >= 2 && resultsContainer.children().length > 0) {
            resultsContainer.show();
        }
    });
});
</script>