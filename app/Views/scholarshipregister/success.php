<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Successful - <?php echo htmlspecialchars($candidate['roll_no']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; padding: 40px 15px; }
        .success-card { max-width: 680px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 40px; border-top: 6px solid #10b981; text-align: center; }
        .icon-box { font-size: 64px; color: #10b981; margin-bottom: 15px; }
        .roll-badge { display: inline-block; background: #ecfdf5; border: 2px solid #10b981; color: #047857; font-size: 28px; font-weight: bold; padding: 10px 30px; border-radius: 50px; margin: 20px 0; letter-spacing: 2px; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; text-align: left; margin: 25px 0; }
        .lookup-box { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; text-align: left; margin-top: 25px; }
    </style>
</head>
<body>

<div class="success-card">
    <div class="icon-box"><i class="fa fa-check-circle"></i></div>
    <h2 style="font-weight: bold; color: #065f46; margin: 0;">Registration Successful!</h2>
    <p class="text-muted" style="font-size: 15px; margin-top: 5px;">Your application for the competition has been received.</p>

    <div>
        <div style="font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: bold;">Your Registration Roll Number</div>
        <div class="roll-badge"><?php echo htmlspecialchars($candidate['roll_no']); ?></div>
    </div>

    <div class="info-box">
        <div class="row">
            <div class="col-xs-6"><strong>Candidate Name:</strong> <?php echo htmlspecialchars($candidate['firstname']); ?></div>
            <div class="col-xs-6"><strong>Exam Title:</strong> <?php echo htmlspecialchars($candidate['exam_title']); ?></div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-xs-6"><strong>Exam Code:</strong> <code><?php echo htmlspecialchars($candidate['exam_code']); ?></code></div>
            <div class="col-xs-6"><strong>School Name:</strong> <?php echo htmlspecialchars($candidate['school_name']); ?></div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-xs-6"><strong>Mobile No:</strong> <?php echo htmlspecialchars($candidate['mobile']); ?></div>
            <div class="col-xs-6"><strong>Admit Card Status:</strong> <span class="label label-warning">UNRELEASED / PENDING</span></div>
        </div>
    </div>

    <div class="alert alert-info text-left" style="border-radius: 8px;">
        <i class="fa fa-info-circle"></i> <strong>Important Note:</strong> Admit Cards for <strong><?php echo htmlspecialchars($candidate['exam_title']); ?></strong> have not been released by the administration yet. Please note down your <strong>Roll Number: <?php echo htmlspecialchars($candidate['roll_no']); ?></strong>. Once released, you can download your official Hall Ticket using your Roll Number.
    </div>

    <div class="lookup-box">
        <h4 style="font-weight: bold; color: #1e40af; margin-top: 0;"><i class="fa fa-search"></i> Check Admit Card & Result Status</h4>
        <form action="<?php echo site_url('scholarshipregister/check_status'); ?>" method="POST" class="row">
            <div class="col-sm-8">
                <input type="text" name="search_term" class="form-control" placeholder="Enter Roll Number (e.g. <?php echo htmlspecialchars($candidate['roll_no']); ?>) or Mobile" required>
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary btn-block" style="border-radius: 6px;"><i class="fa fa-search"></i> Check Status</button>
            </div>
        </form>
    </div>

    <div style="margin-top: 25px;">
        <button onclick="window.print()" class="btn btn-default"><i class="fa fa-print"></i> Print Slip</button>
        <a href="<?php echo site_url('scholarshipregister'); ?>" class="btn btn-primary" style="margin-left: 10px;">Back to Competitions</a>
    </div>
</div>

</body>
</html>
