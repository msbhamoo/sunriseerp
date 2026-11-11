<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Portal Login - Scholarship & Olympiad Exams</title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-950: #0a1426;
            --navy-900: #0f1d38;
            --navy-800: #192a4e;
            --gold-500: #b8860b;
            --gold-400: #c59b27;
            --emerald-600: #059669;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a1426 0%, #192a4e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
            color: #1e293b;
        }
        .portal-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.45);
            overflow: hidden;
            border-top: 5px solid var(--gold-500);
        }
        .portal-header {
            background: #f8fafc;
            padding: 30px 25px 20px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .portal-header h2 {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--navy-900);
            margin: 10px 0 5px;
        }
        .portal-header p {
            color: #64748b;
            font-size: 13px;
            margin: 0;
        }
        .portal-body {
            padding: 30px 25px;
        }
        .form-control {
            height: 46px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            font-size: 14px;
            box-shadow: none;
        }
        .form-control:focus {
            border-color: var(--gold-500);
            box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.15);
        }
        .btn-portal {
            background: linear-gradient(135deg, #b8860b 0%, #996515 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 15px;
            padding: 12px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
        }
        .btn-portal:hover {
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(184, 134, 11, 0.35);
        }
        .pin-box {
            letter-spacing: 6px;
            font-size: 20px;
            text-align: center;
            font-weight: 700;
        }
    </style>
</head>
<body>

<div class="portal-card">
    <div class="portal-header">
        <div style="width: 60px; height: 60px; background: rgba(184,134,11,0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: var(--gold-500); font-size: 26px;">
            <i class="fa fa-graduation-cap"></i>
        </div>
        <h2>Candidate Portal</h2>
        <p>Access Model Papers, Countdown, Syllabus & Admit Card</p>
    </div>

    <div class="portal-body">
        <?php if ($this->session->flashdata('error_msg')) { ?>
            <div class="alert alert-danger" style="border-radius: 8px; font-size: 13px;">
                <i class="fa fa-exclamation-triangle"></i> <?php echo $this->session->flashdata('error_msg'); ?>
            </div>
        <?php } ?>

        <form action="<?php echo site_url('scholarshipregister/portal_login'); ?>" method="POST">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-size: 13px; font-weight: 600; color: #334155;">
                    <i class="fa fa-id-card-o text-muted"></i> Roll No. or Mobile Number
                </label>
                <input type="text" name="login_identifier" class="form-control" placeholder="e.g. SSAT-1001 or 9828512821" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label style="font-size: 13px; font-weight: 600; color: #334155;">
                    <i class="fa fa-lock text-muted"></i> 4-Digit Password PIN
                </label>
                <input type="password" name="password_pin" class="form-control pin-box" maxlength="4" placeholder="••••" required pattern="\d{4}">
                <p class="help-block" style="font-size: 11px; margin-top: 6px; color: #64748b;">
                    <i class="fa fa-info-circle"></i> Enter the 4-digit PIN you chosen during registration.
                </p>
            </div>

            <button type="submit" class="btn btn-portal btn-block">
                <i class="fa fa-sign-in"></i> Sign In to Portal
            </button>
        </form>

        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px dashed #e2e8f0; text-align: center; font-size: 13px; color: #64748b;">
            Haven't registered yet? 
            <a href="<?php echo site_url('scholarshipregister/apply/SSAT-2026'); ?>" style="color: var(--gold-500); font-weight: 600;">Apply Now</a>
        </div>
        <div style="margin-top: 10px; text-align: center; font-size: 12px;">
            <a href="<?php echo site_url('scholarshipregister/check_status'); ?>" style="color: #64748b;"><i class="fa fa-search"></i> Check Registration Status</a>
        </div>
    </div>
</div>

</body>
</html>