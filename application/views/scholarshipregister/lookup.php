<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Check Candidate Admit Card & Result Status</title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body { background: #f1f5f9; font-family: 'Segoe UI', sans-serif; padding: 40px 15px; }
        .lookup-card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 35px; border-top: 6px solid #3b82f6; }
        .lookup-header { text-align: center; margin-bottom: 25px; }
        .lookup-header h3 { font-weight: bold; color: #1e3a8a; margin: 0; }
        .lookup-header p { color: #64748b; font-size: 14px; margin-top: 5px; }
    </style>
</head>
<body>

<div class="lookup-card">
    <div class="lookup-header">
        <h3><?php echo htmlspecialchars($sch_setting[0]['name']); ?></h3>
        <p>Candidate Admit Card & Result Status Lookup</p>
    </div>

    <?php if (isset($error) && !empty($error)) { ?>
        <div class="alert alert-warning" style="border-radius: 8px;">
            <i class="fa fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
    <?php } ?>

    <form action="<?php echo site_url('scholarshipregister/check_status'); ?>" method="POST">
        <div class="form-group">
            <label>Enter Candidate Roll Number or Registered Mobile Number *</label>
            <input type="text" name="search_term" class="form-control input-lg" placeholder="e.g. SCHOLAR-1001 or 9876543210" required>
        </div>

        <div class="text-center" style="margin-top: 25px;">
            <button type="submit" class="btn btn-primary btn-lg btn-block" style="border-radius: 8px; font-weight: bold;"><i class="fa fa-search"></i> Check Admit Card Status</button>
        </div>
    </form>

    <div class="text-center" style="margin-top: 25px;">
        <a href="<?php echo site_url('scholarshipregister'); ?>"><i class="fa fa-arrow-left"></i> Back to Online Registration Portal</a>
    </div>
</div>

</body>
</html>
