<!DOCTYPE html>
<html <?php echo $this->customlib->getRTL(); ?>>

<?php
$is_lock_panel   = check_lock_enabled();
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
$role            = $this->customlib->getUserRole();

?>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->customlib->getAppName(); ?></title>
        <meta content="width=device-width, initial-scale=1, viewport-fit=cover" name="viewport">
        <meta name="theme-color" content="#ffffff" />
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="Portal">
        <link rel="manifest" href="<?php echo base_url('manifest.json'); ?>">
        <link rel="apple-touch-icon" href="<?php echo base_url('backend/images/icons/icon-192x192.png'); ?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
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
                    classList.add(themeClassNavigation);
                }

                // Same CSS variable the admin panel uses for its sidebar accent
                document.documentElement.style.setProperty('--primary-theme-color', themeColor);
            }

            // === Apply layout ===
            function applyLayoutAndIcons() {
                const content = document.querySelector("section.content");

                if (content && !content.classList.contains(savedLayout)) {
                    content.classList.add(savedLayout);
                }

                // Stop observing once applied
                if (content) {
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

        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">

        <?php
$sch_setting = $this->setting_model->getSetting();
$this->load->view('layout/theme');
?>

        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ss-print.css">

        <?php
if ($this->customlib->getRTL() != "") {
    ?>
            <!-- Bootstrap 3.3.5 RTL -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/bootstrap-rtl/css/bootstrap-rtl.min.css"/>
            <!-- Theme RTL style -->
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/admin-rtl.min.css" />
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/ss-rtlmain.css">
            <link rel="stylesheet" href="<?php echo base_url(); ?>backend/rtl/dist/css/skins/_all-skins-rtl.min.css" />
            <?php
}
?>
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/iCheck/flat/blue.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/morris/morris.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/datepicker/datepicker3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/daterangepicker/daterangepicker-bs3.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/sweet-alert/sweetalert2.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/custom_style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/datepicker/css/bootstrap-datetimepicker.css">
        <!--file dropify-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/dropify.min.css">
        <!--file nprogress-->
        <link href="<?php echo base_url(); ?>backend/dist/css/nprogress.css" rel="stylesheet">
        <!--language css-->
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>backend/dist/css/bootstrap-select.min.css">

        <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
        <script language="javascript" src="<?php echo base_url('backend/custom/jquery-2.2.4.js'); ?>"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/moment.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/datepicker/js/bootstrap-datetimepicker.js"></script>
        <script src="<?php echo base_url(); ?>backend/dist/js/jquery-ui.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/school-custom.js"></script>
        <script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>

        <!-- fullCalendar -->
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/fullcalendar/dist/fullcalendar.print.min.css" media="print">
        <link rel="stylesheet" href="<?php echo base_url() ?>backend/dist/css/lucide-static@0.543.0/lucide.css">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <script type="text/javascript">
            var baseurl = "<?php echo base_url(); ?>";
            var start_week="<?php echo $this->customlib->getStartWeek(); ?>";
            window.sidebarStoragePrefix = 'student_';
            window.sidebarRestoreOpenMenu = false; // only auto-open the flyout holding the current page
        </script>

        <?php if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasModule('online_course')) {?>
        <script src="<?php echo base_url(); ?>backend/js/studentcourse.js"></script>
        <?php }?>

        <?php $this->load->view('layout/partials/modern_header_css'); ?>
        <?php $this->load->view('layout/partials/student_panel_css'); ?>
    </head>

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header" id="alert">
<?php
$function = 'user/user/dashboard';
if ($role == 'guest') {
    $function = 'user/studentcourse/profile';
}

$student_data = $this->customlib->getLoggedInUserData();
$photo_path   = '';
if (!empty($student_data["image"])) {
    $photo_path = (($student_data['role'] ?? '') == 'guest') ? "uploads/guest_images/" . $student_data["image"] : $student_data["image"];
}
// Fall back to the default avatar when the stored photo file is missing on disk
if ($photo_path != '' && is_file(FCPATH . $photo_path)) {
    $file = base_url() . $photo_path . img_time();
} else {
    $gender = $student_data['gender'] ?? '';
    if ($gender == 'Female') {
        $file = base_url() . "uploads/student_images/default_female.jpg" . img_time();
    } elseif ($gender == 'Male') {
        $file = base_url() . "uploads/student_images/default_male.jpg" . img_time();
    } else {
        $file = base_url() . "uploads/student_images/no_image.png";
    }
}

// WhatsApp link is shown only inside the configured time window
$whatsapp_url = '';
if ($sch_setting->student_panel_whatsapp) {
    $show_hide = 1;
    if ($sch_setting->student_panel_whatsapp_from != '' && $sch_setting->student_panel_whatsapp_to != '') {
        $today     = strtotime(date("H:i:s"));
        $show_hide = ($today >= strtotime($sch_setting->student_panel_whatsapp_from) && $today <= strtotime($sch_setting->student_panel_whatsapp_to)) ? 1 : 0;
    }
    if ($show_hide) {
        $whatsapp_url = "https://wa.me/" . $sch_setting->student_panel_whatsapp_mobile;
    }
}
$has_chat     = $this->module_lib->hasActive('chat') && $this->studentmodule_lib->hasActive('chat');
$has_calendar = $this->studentmodule_lib->hasActive('calendar_to_do_list');
?>
                <a href="<?php echo base_url() . $function; ?>" class="logo">
                    <span class="logo-mini"><img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $this->setting_model->getAdminsmalllogo()); ?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                    <span class="logo-lg"><img src="<?php echo base_url('uploads/school_content/admin_logo/' . $this->setting_model->getAdminlogo()); ?>" alt="<?php echo $this->customlib->getAppName() ?>" /></span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" title="Toggle Sidebar">
                        <span class="sr-only"><?php echo $this->lang->line('toggle_navigation'); ?></span>
                        <i class="icon-menu"></i>
                    </a>
                    <div class="col-lg-4 col-md-3 col-sm-2 col-xs-4">
                        <span href="#" class="sidebar-session">
                            <?php echo $this->setting_model->getCurrentSchoolName(); ?>
                        </span>
                    </div>
                    <div class="col-lg-8 col-md-9 col-sm-10 col-xs-8">
                        <div class="pull-right">
                            <div class="navbar-custom-menu">
                                <?php $language_count = $this->customlib->get_active_language_count();
if ($language_count > 1) {?>
                                    <div class="langdiv">
                                        <select class="languageselectpicker" onchange="set_languages(this.value)" id="languageSwitcher" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('language') ?>">
                                           <?php $this->load->view('student/languageSwitcher')?>
                                        </select>
                                    </div>
                                <?php }?>
                                <?php $currency_count = $this->customlib->get_active_currency_count();
if ($currency_count > 1) {?>
                                    <div class="currency-icon-list" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('currency') ?>">
                                        <select class="languageselectpicker" id="currencySwitcher">
                                           <?php $this->load->view('student/currencySwitcher')?>
                                        </select>
                                    </div>
                                <?php }?>

                                <ul class="nav navbar-nav headertopmenu">
                                    <?php if ($role == 'student' || $role == 'parent') {?>
                                    <li class="cal15 hidden-xs" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('current_session') . ": " . $this->setting_model->getCurrentSessionName(); ?>">
                                        <a href="#" data-toggle="modal" data-target="#user_sessionModal"><i class="icon-pencil topnav-icon"></i></a>
                                    </li>
                                    <?php }?>

                                    <?php if ($this->studentmodule_lib->hasActive('multi_class')) {?>
                                    <li class="cal15" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('switch_class') ?>">
                                        <a href="#" data-toggle="modal" data-target="#classSwitchModal"><i class="icon-arrow-right-left topnav-icon"></i></a>
                                    </li>
                                    <?php }?>

                                    <?php if ($has_calendar) {
    $userdata = $this->customlib->getLoggedInUserData();
    $count    = $this->customlib->countincompleteTask($userdata["id"] ?? 0);
    ?>
                                    <li class="cal15 d-sm-none <?php echo ($is_lock_panel) ? "disable-link" : "" ?>"><a data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('calendar') ?>" href="<?php echo base_url() ?>user/calendar/"><i class="icon-calendar topnav-icon"></i></a></li>

                                    <li data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('task') ?>" class="dropdown hidden-xs <?php echo ($is_lock_panel) ? "disable-link" : "" ?>">
                                        <a href="#" class="dropdown-toggle todoicon" data-toggle="dropdown">
                                            <i class="fa fa-check-square-o topnav-icon"></i>
                                            <?php if ($count > 0) {?>
                                                <span class="todo-indicator"><?php echo $count ?></span>
                                            <?php }?>
                                        </a>
                                        <ul class="dropdown-menu menuboxshadow">
                                            <li class="todoview plr10 ssnoti"><?php echo $this->lang->line('today_you_have') . " " . $count . " " . $this->lang->line('pending_task') ?><a href="<?php echo base_url() ?>user/calendar/" class="pull-right pt0"> <?php echo $this->lang->line('view_all'); ?></a></li>
                                            <li>
                                                <ul class="todolist">
                                                    <?php
$tasklist = $this->customlib->getincompleteTask($userdata["id"] ?? 0);
    foreach ((array) $tasklist as $value) {
        ?>
                                                        <li><div class="checkbox">
                                                                <label><input type="checkbox" id="newcheck<?php echo $value["id"] ?>" onclick="markc('<?php echo $value["id"] ?>')" name="eventcheck" value="<?php echo $value["id"]; ?>"><?php echo $value["event_title"] ?></label>
                                                            </div></li>
                                                    <?php }?>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <?php }?>

                                    <?php if ($has_chat) {?>
                                    <li class="cal15 d-sm-none <?php echo ($is_lock_panel) ? "disable-link" : "" ?>"><a data-placement="bottom" data-toggle="tooltip" href="<?php echo base_url() ?>user/chat" title="<?php echo $this->lang->line('chat'); ?>" class="todoicon"><i class="icon-message-circle topnav-icon"></i> <span class="total_chat_msg text-white badge bg-red topbadges"><?php
echo count((array) $this->customlib->get_student_parent_chat_msg_count());
    ?></span></a></li>
                                    <?php }?>

                                    <?php if ($whatsapp_url != '') {?>
                                    <li class="cal15 d-sm-none" data-placement="bottom" data-toggle="tooltip" title="<?php echo $this->lang->line('whatsapp_link') ?>">
                                        <a target="_blank" href="<?php echo $whatsapp_url; ?>"><i class="fa fa-whatsapp topnav-icon" style="color:#25D366;"></i></a>
                                    </li>
                                    <?php }?>

                                    <!-- Mobile overflow menu -->
                                    <li class="dropdown d-lg-none d-sm-block ellipsis-px-3">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-ellipsis-v topnav-icon"></i></a>
                                        <ul class="dropdown-menu min-w-full sm-drop-down">
                                            <?php if ($role == 'student' || $role == 'parent') {?>
                                            <li><a href="#" data-toggle="modal" data-target="#user_sessionModal"><i class="icon-pencil mobile-menu-icon"></i> <?php echo $this->lang->line('current_session'); ?></a></li>
                                            <?php }?>
                                            <?php if ($has_calendar) {?>
                                            <li><a href="<?php echo base_url() ?>user/calendar/"><i class="icon-calendar mobile-menu-icon"></i> <?php echo $this->lang->line('calendar'); ?></a></li>
                                            <?php }?>
                                            <?php if ($has_chat) {?>
                                            <li><a href="<?php echo base_url() ?>user/chat"><i class="icon-message-circle mobile-menu-icon"></i> <?php echo $this->lang->line('chat'); ?></a></li>
                                            <?php }?>
                                            <?php if ($whatsapp_url != '') {?>
                                            <li><a href="<?php echo $whatsapp_url; ?>" target="_blank"><i class="fa fa-whatsapp mobile-menu-icon" style="color:#25D366;"></i> <?php echo $this->lang->line('whatsapp_link') ?></a></li>
                                            <?php }?>
                                        </ul>
                                    </li>

                                    <li class="dropdown user-menu">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                                            <?php if ($sch_setting->student_photo) {?>
                                                <img src="<?php echo $file; ?>" class="topuser-image" alt="User Image">
                                            <?php } else {?>
                                                <i class="icon-user-round topnav-icon"></i>
                                            <?php }?>
                                        </a>
                                        <ul class="dropdown-menu dropdown-user menuboxshadow">
                                            <li>
                                                <div class="sstopuser">
                                                    <div class="ssuserleft">
                                                        <?php if ($sch_setting->student_photo) {?>
                                                        <a href="<?php echo base_url() . $function; ?>"><img src="<?php echo $file; ?>" alt="User Image"></a>
                                                        <?php }?>
                                                    </div>

                                                    <div class="sstopuser-test">
                                                        <h4 class="text-capitalize mb0"><a href="<?php echo base_url(); ?>user/user/profile"><?php echo $this->customlib->getStudentSessionUserName(); ?></a></h4>
                                                        <h5 class="mt0"><?php
if ($role == 'student') {
    echo $this->lang->line("student");
} elseif ($role == 'parent') {
    echo $this->lang->line("parent");
} elseif ($role == 'guest') {
    echo $this->lang->line("guest");
}
?></h5>
                                                        <a href="<?php echo base_url(); ?>user/user/changepass"><i class="icon-key" style="margin-right: 5px;"></i> <?php echo $this->lang->line('change_password'); ?></a>
                                                        <?php if ($this->module_lib->hasModule('google_authenticator') && $this->module_lib->hasActive('google_authenticator')) {?>
                                                            <p class="sspass"><a href="<?php echo site_url('user/gauthenticate/setup') ?>"><i class="icon-settings" style="margin-right: 5px;"></i><?php echo $this->lang->line('setting'); ?></a></p>
                                                        <?php }?>
                                                    </div>
                                                    <div class="divider"></div>

                                                    <div class="sspass">
                                                        <?php
$getfrontcmssetting = $this->customlib->getfrontcmssetting();
if ($getfrontcmssetting->is_active_front_cms) {?>
                                                        <a href="<?php echo base_url(); ?>" target="_blank" class="forgot"><i class="icon-globe" style="margin-right: 5px;"></i><?php echo $this->lang->line('front_site'); ?></a>
                                                        <?php }?>
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
<?php
/*
 * Sidebar menu, rendered with the same markup/classes as the admin sidebar
 * (layout/sidebar.php): coloured squircle icons, and a flyout secondary
 * panel for items that have children (li.treeview + ul.treeview-menu).
 */
$menu = [];
if ($role == 'student' || $role == 'parent') {
    $menu[] = ['label' => $this->lang->line('dashboard'), 'url' => 'user/user/dashboard', 'icon' => 'icon-layout-dashboard', 'color' => '#6366f1', 'active' => set_Topmenu('dashboard')];
    $menu[] = ['label' => $this->lang->line('my_profile'), 'url' => 'user/user/profile', 'icon' => 'icon-user-round', 'color' => '#3b82f6', 'active' => set_Topmenu('my_profile')];

    if ($this->module_lib->hasActive('fees_collection') && $this->studentmodule_lib->hasActive('fees')) {
        $menu[] = ['label' => $this->lang->line('fees'), 'url' => 'user/user/getfees', 'icon' => 'icon-wallet', 'color' => '#10b981', 'active' => set_Topmenu('fees')];
    }
    if ($this->module_lib->hasModule('online_course') && $this->module_lib->hasActive('online_course') && $this->studentmodule_lib->hasActive('online_course') && $this->auth->addonchk('ssoclc', false)) {
        $menu[] = ['label' => $this->lang->line('online_course'), 'url' => 'user/studentcourse', 'icon' => 'icon-monitor-play', 'color' => '#ec4899', 'active' => set_topmenu('user/studentcourse')];
    }
    if ($this->module_lib->hasModule('zoom_live_classes') && $this->module_lib->hasActive('zoom_live_classes') && $this->studentmodule_lib->hasActive('live_classes') && $this->auth->addonchk('sszlc', false)) {
        $menu[] = ['label' => $this->lang->line('zoom_live_classes'), 'url' => 'user/conference', 'icon' => 'icon-video', 'color' => '#3b82f6', 'active' => set_topmenu('Conference')];
    }
    if ($this->module_lib->hasModule('gmeet_live_classes') && $this->module_lib->hasActive('gmeet_live_classes') && $this->studentmodule_lib->hasActive('gmeet_live_classes') && $this->auth->addonchk('ssglc', false)) {
        $menu[] = ['label' => $this->lang->line('gmeet') . " " . $this->lang->line('live_class'), 'url' => 'user/gmeet', 'icon' => 'icon-video', 'color' => '#10b981', 'active' => set_topmenu('Gmeet')];
    }
    if ($this->studentmodule_lib->hasActive('class_timetable')) {
        $menu[] = ['label' => $this->lang->line('class_timetable'), 'url' => 'user/timetable', 'icon' => 'icon-calendar-clock', 'color' => '#0ea5e9', 'active' => set_Topmenu('Time_table')];
    }
    if ($this->module_lib->hasActive('lesson_plan') && $this->studentmodule_lib->hasActive('lesson_plan')) {
        $menu[] = ['label' => $this->lang->line('lesson_plan'), 'url' => 'user/syllabus', 'icon' => 'icon-notebook-pen', 'color' => '#f59e0b', 'active' => set_Topmenu('syllabus')];
    }
    if ($this->studentmodule_lib->hasActive('syllabus_status')) {
        $menu[] = ['label' => $this->lang->line('syllabus_status'), 'url' => 'user/syllabus/status', 'icon' => 'icon-list-checks', 'color' => '#14b8a6', 'active' => set_Topmenu('syllabus/status')];
    }
    if ($this->module_lib->hasActive('homework') && $this->studentmodule_lib->hasActive('homework')) {
        $menu[] = ['label' => $this->lang->line('homework'), 'url' => 'user/homework', 'icon' => 'icon-flask-conical', 'color' => '#f43f5e', 'active' => set_Topmenu('Homework')];
    }
    if ($this->module_lib->hasActive('online_examination') && $this->studentmodule_lib->hasActive('online_examination')) {
        $menu[] = ['label' => $this->lang->line('online_exam'), 'url' => 'user/onlineexam', 'icon' => 'icon-laptop', 'color' => '#8b5cf6', 'active' => set_Topmenu('Onlineexam')];
    }
    if ($this->studentmodule_lib->hasActive('apply_leave')) {
        $menu[] = ['label' => $this->lang->line('apply_leave'), 'url' => 'user/apply_leave', 'icon' => 'icon-calendar-x', 'color' => '#ef4444', 'active' => set_Topmenu('apply_leave')];
    }
    if ($this->studentmodule_lib->hasActive('visitor_book')) {
        $menu[] = ['label' => $this->lang->line('visitor_book'), 'url' => 'user/visitors', 'icon' => 'icon-door-open', 'color' => '#64748b', 'active' => set_Topmenu('visitors')];
    }
    if ($this->module_lib->hasActive('download_center') && $this->studentmodule_lib->hasActive('download_center')) {
        $menu[] = ['label' => $this->lang->line('download_center'), 'icon' => 'icon-download', 'color' => '#64748b', 'active' => set_Topmenu('Downloads'), 'children' => [
            ['label' => $this->lang->line('contents'), 'url' => 'user/content/list', 'active' => set_Submenu('content/index')],
            ['label' => $this->lang->line('video_tutorial'), 'url' => 'user/video_tutorial', 'active' => set_Submenu('video_tutorial/index')],
        ]];
    }
    if ($this->module_lib->hasActive('student_attendance') && $this->studentmodule_lib->hasActive('attendance')) {
        $menu[] = ['label' => $this->lang->line('attendance'), 'url' => 'user/attendence', 'icon' => 'icon-calendar-check', 'color' => '#14b8a6', 'active' => set_Topmenu('Attendence')];
    }
    if ($this->module_lib->hasModule('cbseexam') && $this->module_lib->hasActive('cbseexam') && $this->studentmodule_lib->hasActive('cbseexam')) {
        $menu[] = ['label' => $this->lang->line('cbse_exam'), 'icon' => 'icon-file-text', 'color' => '#f97316', 'active' => set_Topmenu('cbse_exam'), 'children' => [
            ['label' => $this->lang->line('exam_result'), 'url' => 'user/cbse/exam/result', 'active' => set_Submenu('user/cbse/cbse_exam_result')],
            ['label' => $this->lang->line('exam_schedule'), 'url' => 'user/cbse/exam/timetable', 'active' => set_Submenu('user/cbse/cbse_exam_timetable')],
        ]];
    }
    if ($this->module_lib->hasActive('examination') && $this->studentmodule_lib->hasActive('examinations')) {
        $menu[] = ['label' => $this->lang->line('examinations'), 'icon' => 'icon-graduation-cap', 'color' => '#a855f7', 'active' => set_Topmenu('Examinations'), 'children' => [
            ['label' => $this->lang->line('exam_schedule'), 'url' => 'user/examschedule', 'active' => set_Submenu('examSchedule/index')],
            ['label' => $this->lang->line('exam_result'), 'url' => 'user/exam/examresult', 'active' => set_Submenu('examresult/index')],
        ]];
    }
    if ($this->studentmodule_lib->hasActive('notice_board')) {
        $menu[] = ['label' => $this->lang->line('notice_board'), 'url' => 'user/notification', 'icon' => 'icon-bell', 'color' => '#f59e0b', 'active' => set_Topmenu('notification'), 'badge' => $this->customlib->getUserunreadNotification()];
    }
    if ($this->studentmodule_lib->hasActive('teachers_rating')) {
        $menu[] = ['label' => $this->lang->line('teachers_reviews'), 'url' => 'user/teacher', 'icon' => 'icon-star', 'color' => '#eab308', 'active' => set_Topmenu('Teachers')];
    }
    if ($this->module_lib->hasActive('library') && $this->studentmodule_lib->hasActive('library')) {
        $menu[] = ['label' => $this->lang->line('library'), 'icon' => 'icon-library', 'color' => '#d946ef', 'active' => set_Topmenu('Library'), 'children' => [
            ['label' => $this->lang->line('books'), 'url' => 'user/book', 'active' => set_Submenu('book/index')],
            ['label' => $this->lang->line('book_issued'), 'url' => 'user/book/issue', 'active' => set_Submenu('book/issue')],
        ]];
    }
    if ($this->module_lib->hasActive('transport') && $this->studentmodule_lib->hasActive('transport_routes')) {
        $menu[] = ['label' => $this->lang->line('transport_routes'), 'url' => 'user/route', 'icon' => 'icon-bus', 'color' => '#eab308', 'active' => set_Submenu('route/index')];
    }
    if ($this->module_lib->hasActive('hostel') && $this->studentmodule_lib->hasActive('hostel_rooms')) {
        $menu[] = ['label' => $this->lang->line('hostel_rooms'), 'url' => 'user/hostelroom', 'icon' => 'icon-building', 'color' => '#06b6d4', 'active' => set_Submenu('hostelroom/index')];
    }
} elseif ($role == 'guest') {
    $menu[] = ['label' => $this->lang->line('my_profile'), 'url' => 'user/studentcourse/profile', 'icon' => 'icon-user-round', 'color' => '#3b82f6', 'active' => set_Topmenu('user/guestprofile')];
    $menu[] = ['label' => $this->lang->line('online_course'), 'url' => 'user/studentcourse', 'icon' => 'icon-monitor-play', 'color' => '#ec4899', 'active' => set_topmenu('user/studentcourse')];
    $menu[] = ['label' => $this->lang->line('purchase_history'), 'url' => 'user/studentcourse/purchasehistory', 'icon' => 'icon-history', 'color' => '#10b981', 'active' => set_topmenu('user/purchasehistory')];
}
?>
            <aside class="main-sidebar" id="alert2">
                <section class="sidebar <?php echo ($is_lock_panel) ? "disable-sidebar" : "" ?>" id="sibe-box">
                    <ul class="sidebar-menu verttop">
                        <li class="sidebar-search-item">
                            <div class="sidebar-search-wrapper">
                                <i class="icon-search sidebar-search-icon"></i>
                                <input type="text" id="sidebar-menu-search" class="sidebar-search-input" placeholder="Search menu..." autocomplete="off">
                                <button type="button" id="sidebar-search-clear" class="sidebar-search-clear"><i class="fa fa-times"></i></button>
                            </div>
                        </li>
                        <?php foreach ($menu as $item) {
    list($r, $g, $b) = sscanf($item['color'], "#%02x%02x%02x");
    $icon_style      = "color: {$item['color']}; background-color: rgba($r, $g, $b, 0.12);";
    $has_children    = !empty($item['children']);
    ?>
                        <li class="<?php echo $has_children ? 'treeview ' : ''; ?><?php echo $item['active']; ?>">
                            <a href="<?php echo $has_children ? '#' : site_url($item['url']); ?>">
                                <i class="<?php echo $item['icon']; ?>" style="<?php echo $icon_style; ?>"></i>
                                <span><?php echo $item['label']; ?></span>
                                <?php if (!empty($item['badge'])) {?>
                                    <small class="label bg-red sidebar-badge"><?php echo $item['badge']; ?></small>
                                <?php }?>
                                <?php if ($has_children) {?><i class="icon-chevron-right menu-arrow"></i><?php }?>
                            </a>
                            <?php if ($has_children) {?>
                            <ul class="treeview-menu custom-secondary-panel">
                                <li class="submenu-header">
                                    <span class="submenu-title"><?php echo $item['label']; ?></span>
                                    <button type="button" class="close-submenu"><i class="fa fa-times"></i></button>
                                </li>
                                <?php foreach ($item['children'] as $child) {?>
                                <li class="<?php echo $child['active']; ?>"><a href="<?php echo site_url($child['url']); ?>"><i class="icon-circle-small"></i><?php echo $child['label']; ?></a></li>
                                <?php }?>
                            </ul>
                            <?php }?>
                        </li>
                        <?php }?>
                    </ul>
                </section>

                <div class="sidebar-collapse-footer">
                    <button type="button" class="sidebar-toggle sidebar-bottom-toggle" title="Collapse / Expand Sidebar">
                        <i class="icon-chevrons-left collapse-icon"></i>
                    </button>
                </div>
            </aside>
<?php
// Mobile bottom tab bar: the most used sections the user has access to, plus "Menu"
$tab_candidates = [
    'user/user/dashboard' => ['Home', 'icon-layout-dashboard'],
    'user/timetable'      => [$this->lang->line('timetable') ?: 'Timetable', 'icon-calendar-clock'],
    'user/homework'       => [$this->lang->line('homework'), 'icon-flask-conical'],
    'user/user/getfees'   => [$this->lang->line('fees'), 'icon-wallet'],
    'user/attendence'     => [$this->lang->line('attendance'), 'icon-calendar-check'],
    'user/notification'   => [$this->lang->line('notice_board'), 'icon-bell'],
];
$menu_urls  = array_column($menu, 'url');
$tabs       = [];
$current    = trim(uri_string(), '/');
foreach ($tab_candidates as $tab_url => $tab) {
    if (in_array($tab_url, $menu_urls, true) && count($tabs) < 4) {
        $tabs[$tab_url] = $tab;
    }
}
?>
            <?php if (!empty($tabs)) {?>
            <nav class="pwa-tabbar" aria-label="Quick navigation">
                <?php foreach ($tabs as $tab_url => $tab) {
    $is_active = ($current === $tab_url || ($tab_url !== 'user/user/dashboard' && strpos($current, $tab_url) === 0));?>
                <a href="<?php echo site_url($tab_url); ?>" class="pwa-tab <?php echo $is_active ? 'active' : ''; ?>">
                    <i class="<?php echo $tab[1]; ?>"></i><span><?php echo $tab[0]; ?></span>
                </a>
                <?php }?>
                <a href="#" class="pwa-tab pwa-tab-menu" role="button">
                    <i class="icon-menu"></i><span>Menu</span>
                </a>
            </nav>
            <?php }?>
            <button type="button" id="pwa-install" class="pwa-install" hidden>
                <i class="icon-download"></i> Install app
            </button>
            <?php $this->load->view('layout/partials/modern_sidebar_assets'); ?>
            <script>
                // Bottom bar "Menu" opens the off-canvas sidebar on phones
                $(document).on('click', '.pwa-tab-menu', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    $('body').toggleClass('sidebar-open');
                });

                // PWA: service worker + install prompt
                if ('serviceWorker' in navigator) {
                    window.addEventListener('load', function () {
                        navigator.serviceWorker.register('<?php echo base_url('sw.js'); ?>', {scope: '<?php echo rtrim(parse_url(base_url(), PHP_URL_PATH), '/') . '/'; ?>'}).catch(function () {});
                    });
                }
                (function () {
                    var deferredPrompt = null;
                    var btn = document.getElementById('pwa-install');
                    window.addEventListener('beforeinstallprompt', function (e) {
                        e.preventDefault();
                        deferredPrompt = e;
                        try { if (localStorage.getItem('pwa_install_dismissed') === '1') return; } catch (err) {}
                        btn.hidden = false;
                    });
                    btn.addEventListener('click', function () {
                        if (!deferredPrompt) return;
                        deferredPrompt.prompt();
                        deferredPrompt.userChoice.then(function (choice) {
                            if (choice.outcome !== 'accepted') {
                                try { localStorage.setItem('pwa_install_dismissed', '1'); } catch (err) {}
                            }
                            deferredPrompt = null;
                            btn.hidden = true;
                        });
                    });
                    window.addEventListener('appinstalled', function () { btn.hidden = true; });
                })();
            </script>
            <script>
                function set_languages(lang_id){
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url(); ?>user/user/user_language/"+lang_id,
                        data: {},
                        success: function (data) {
                            successMsg("<?php echo $this->lang->line('status_change_successfully'); ?>");
                            window.location.reload('true');
                        }
                    });
                }
            </script>
