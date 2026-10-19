<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <?php 
        $app_name = $this->setting_model->get(); 
        $school = $app_name[0];
        ?>
        <title><?php echo $school['name']; ?></title>
        <!--favican-->
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php echo $this->setting_model->getAdminsmalllogo(); ?>" rel="shortcut icon" type="image/x-icon">
        <!-- CSS -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:400,500,600,700&display=swap">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/css/bootstrap.min.css">
        
        <style>
            /* New Scoped CSS for Redesign */
            body, html { margin: 0; padding: 0; height: 100%; font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
            .new-login-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; background-color: #f4f6f9; padding: 20px; box-sizing: border-box; }
            .new-login-card { display: flex; flex-direction: row; width: 100%; max-width: 1000px; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1); min-height: 600px; }
            .new-login-left { flex: 1; background-size: cover; background-position: center; background-repeat: no-repeat; position: relative; color: #ffffff; padding: 40px; display: flex; flex-direction: column; justify-content: space-between; }
            .new-login-left::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%); z-index: 1; }
            .new-login-left-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; }
            .new-login-logo img { max-width: 200px; max-height: 60px; }
            .new-login-right { flex: 1; padding: 50px 60px; display: flex; flex-direction: column; justify-content: center; background-color: #ffffff; position: relative; }
            
            .icon-circle { width: 60px; height: 60px; background: #fff5eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; color: #f6a05a; font-size: 24px; }
            .new-login-right h2 { font-size: 28px; font-weight: 700; color: #1a202c; margin-top: 0; margin-bottom: 8px; text-align: center; }
            .new-login-right p.subtitle { font-size: 15px; color: #718096; margin-bottom: 30px; text-align: center; }
            
            .new-form-group { margin-bottom: 20px; position: relative; }
            .new-form-group label { display: flex; justify-content: space-between; font-size: 12px; font-weight: 600; color: #718096; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px; }
            .new-form-control { width: 100%; padding: 12px 15px 12px 40px; font-size: 15px; color: #2d3748; background-color: #f7fafc; border: 1px solid #e2e8f0; border-radius: 10px; box-sizing: border-box; transition: all 0.2s; }
            .new-form-control:focus { outline: none; border-color: #fbc18c; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(251, 193, 140, 0.2); }
            .new-form-icon { position: absolute; left: 15px; top: 38px; color: #a0aec0; font-size: 16px; }
            .new-btn-submit { width: 100%; padding: 14px; font-size: 16px; font-weight: 600; color: #ffffff; background: linear-gradient(135deg, #fbc18c 0%, #f6a05a 100%); border: none; border-radius: 10px; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s; display: flex; align-items: center; justify-content: center; margin-top: 10px; }
            .new-btn-submit:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(246, 160, 90, 0.4); }
            
            .new-login-footer { margin-top: auto; text-align: center; font-size: 12px; color: #a0aec0; padding-top: 30px; }
            .new-login-footer span { margin: 0 10px; }
            
            .mockup-card { background: #1e293b; border-radius: 16px; padding: 25px; display: inline-flex; align-items: flex-start; gap: 20px; margin-right: 20px; border: 1px solid rgba(255,255,255,0.05); box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 350px; white-space: normal; vertical-align: top; }
            .mockup-card-icon { width: 50px; height: 50px; background: rgba(255,255,255,0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fbc18c; font-size: 20px; flex-shrink: 0; }
            .mockup-card-content h4 { font-size: 16px; font-weight: 700; color: #ffffff; margin: 0 0 8px 0; }
            .mockup-card-content p { font-size: 13px; line-height: 1.5; color: #94a3b8; margin: 0; }
            .left-footer-text { margin-top: 30px; font-size: 11px; font-weight: 700; color: rgba(255,255,255,0.5); letter-spacing: 1px; text-transform: uppercase; display: flex; align-items: center; gap: 15px; }
            .left-footer-text .dot { width: 4px; height: 4px; background: rgba(255,255,255,0.5); border-radius: 50%; }
            .m-logo { display: none; text-align: center; margin-bottom: 30px; }
            .m-logo img { max-width: 180px; max-height: 60px; }
            .new-alert { font-size: 13px; border-radius: 8px; padding: 10px 15px; margin-bottom: 20px; }
            .mobile-header-text { display: none; }
            
            @media (max-width: 768px) {
                .new-login-wrapper { padding: 0; align-items: flex-start; background-color: #1a202c; }
                .new-login-card { flex-direction: column; border-radius: 0; box-shadow: none; background: transparent; }
                .new-login-left { height: 350px; padding: 30px 20px; flex: none; }
                .new-login-right { flex: 1; background-color: #ffffff; border-top-left-radius: 30px; border-top-right-radius: 30px; padding: 40px 25px; margin-top: -40px; z-index: 10; }
                .desktop-logo { display: none; }
                .m-logo { display: block; }
                .mockup-card, .left-footer-text { display: none; }
                .mobile-header-text { display: block; text-align: center; margin-top: 30px; }
                .mobile-header-text span { display: inline-block; background: rgba(255,255,255,0.2); padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-bottom: 15px; backdrop-filter: blur(5px); }
                .mobile-header-text h1 { font-size: 28px; font-weight: 700; margin: 0; line-height: 1.2; color: #fff; }
            }
        </style>
    </head>
    <body>
        <div class="new-login-wrapper">
            <div class="new-login-card">
                
                <!-- Left Pane -->
                <div class="new-login-left" style="background-image: url('<?php echo base_url(); ?>uploads/school_content/login_image/<?php echo $school['user_login_page_background']; ?>');">
                    <div class="new-login-left-content">
                        <div class="new-login-logo desktop-logo">
                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/'.$school['image']); ?>" alt="Logo">
                        </div>
                        
                        <div class="mobile-header-text">
                            <span><i class="fa fa-institution"></i> <?php echo $school['name']; ?></span>
                            <h1>Welcome to<br><?php echo $school['name']; ?></h1>
                        </div>

                        <?php 
                        $CI =& get_instance();
                        $CI->load->model('holiday_model');
                        $all_holidays = $CI->holiday_model->get(null, null, 1);
                        $upcoming_holidays = array();
                        if (!empty($all_holidays)) {
                            foreach($all_holidays as $h) {
                                if(strtotime($h['from_date']) >= strtotime(date('Y-m-d'))) {
                                    $upcoming_holidays[] = $h;
                                }
                            }
                        }
                        $upcoming_holidays = array_slice($upcoming_holidays, 0, 3);
                        ?>

                        <!-- Holidays/Cards (Desktop only) -->
                        <?php if (!empty($upcoming_holidays)) { ?>
                        <div class="desktop-logo" style="margin-top: auto; max-width: 100%; overflow: hidden;">
                            <marquee direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();" style="margin-bottom: 20px; white-space: nowrap;">
                                <?php foreach ($upcoming_holidays as $holiday) { ?>
                                <div class="mockup-card">
                                    <div class="mockup-card-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <div class="mockup-card-content">
                                        <h4>Upcoming Holiday: <?php echo $holiday['description']; ?></h4>
                                        <p>School will be closed from <strong><?php echo date('M d, Y', strtotime($holiday['from_date'])); ?></strong> 
                                        <?php if($holiday['from_date'] != $holiday['to_date']) { echo " to <strong>" . date('M d, Y', strtotime($holiday['to_date'])) . "</strong>"; } ?>.</p>
                                    </div>
                                </div>
                                <?php } ?>
                            </marquee>
                            
                            <div class="left-footer-text">
                                <?php echo strtoupper($school['name']); ?>
                                <span class="dot"></span>
                                EXCELLENCE IN EDUCATION
                            </div>
                        </div>
                        <?php } else { ?>
                            <div class="desktop-logo" style="margin-top: auto;">
                                <div class="mockup-card">
                                    <div class="mockup-card-icon">
                                        <i class="fa fa-graduation-cap"></i>
                                    </div>
                                    <div class="mockup-card-content">
                                        <h4>Welcome to <?php echo $school['name']; ?></h4>
                                        <p>Empowering students with best-in-class education. Keep your academic journey ahead with high-quality, specialized learning.</p>
                                    </div>
                                </div>
                                <div class="left-footer-text">
                                    <?php echo strtoupper($school['name']); ?>
                                    <span class="dot"></span>
                                    EXCELLENCE IN EDUCATION
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Right Pane -->
                <div class="new-login-right">
                    <div class="m-logo">
                        <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/'.$school['image']); ?>" alt="Logo">
                    </div>
                    
                    <div style="width: 40px; height: 4px; background: #e2e8f0; border-radius: 2px; margin: -20px auto 30px auto; display: none;" class="d-md-block d-sm-block d-lg-none"></div>

                    <div class="icon-circle">
                        <i class="fa fa-envelope-o"></i>
                    </div>
                    <h2><?php echo $this->lang->line('forgot_password'); ?></h2>
                    <p class="subtitle">Enter your email address to receive a verification code.</p>

                    <!-- Alerts -->
                    <?php if (isset($error_message)) { echo "<div class='alert alert-danger new-alert'>" . $error_message . "</div>"; } ?>

                    <!-- Form -->
                    <form action="<?php echo site_url('site/ufpassword') ?>" method="post">
                        <?php echo $this->customlib->getCSRF(); ?>
                        
                        <div class="new-form-group">
                            <label for="form-username"><?php echo $this->lang->line('email'); ?> ADDRESS</label>
                            <i class="fa fa-envelope new-form-icon"></i>
                            <input type="text" name="username" value="" class="new-form-control" id="form-username" placeholder="name@company.com" autocomplete="off">
                            <span class="text-danger" style="font-size: 12px;"><?php echo form_error('username'); ?></span>
                        </div>
                        
                        <div class="new-form-group" style="display: flex; gap: 20px; align-items: center; margin-top: 20px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; text-transform: none; color: #2d3748;">
                                <input name="user[]" type="radio" value="student" <?php echo set_radio('user[]', 'student'); ?>>
                                <?php echo $this->lang->line('student'); ?>
                            </label>
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; text-transform: none; color: #2d3748;">
                                <input name="user[]" type="radio" value="parent" <?php echo set_radio('user[]', 'parent'); ?>>
                                <?php echo $this->lang->line('parent'); ?>
                            </label>
                        </div>
                        <span class="text-danger" style="font-size: 12px; margin-top: -10px; display: block;"><?php echo form_error('user[]'); ?></span>
                        
                        <button type="submit" class="new-btn-submit">
                            <?php echo $this->lang->line('submit'); ?> <i class="fa fa-arrow-right"></i>
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <a href="<?php echo site_url('site/userlogin') ?>" style="color: #718096; font-size: 14px; font-weight: 500; text-decoration: none;"><i class="fa fa-arrow-left"></i> Back to <?php echo $this->lang->line('user_login'); ?></a>
                    </div>

                    <!-- Footer -->
                    <div class="new-login-footer">
                        <?php if(!empty($school['phone'])){ ?>
                        <span><i class="fa fa-phone"></i> <?php echo $school['phone']; ?></span>
                        <?php } ?>
                        <?php if(!empty($school['email'])){ ?>
                        <span><i class="fa fa-envelope"></i> <?php echo $school['email']; ?></span>
                        <?php } ?>
                        <div style="margin-top: 10px; font-size: 11px;">&copy; <?php echo date('Y'); ?> <?php echo $school['name']; ?>. All Rights Reserved.</div>
                    </div>
                </div>
            </div>
        </div>
        
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/js/jquery-1.11.1.min.js"></script>
        <script src="<?php echo base_url(); ?>backend/usertemplate/assets/bootstrap/js/bootstrap.min.js"></script>
        <script>
            $(document).ready(function() {
                $('form').on('submit', function() {
                    var btn = $(this).find('button[type="submit"]');
                    btn.prop('disabled', true);
                    btn.html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
                });
            });
        </script>
    </body>
</html>