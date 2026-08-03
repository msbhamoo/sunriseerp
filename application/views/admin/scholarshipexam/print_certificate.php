<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Certificate - <?php echo htmlspecialchars($candidate['firstname'] . ' ' . $candidate['lastname']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body { background: #f1f5f9; font-family: 'Georgia', serif; padding: 30px; }
        .cert-card-container { max-width: 900px; margin: 0 auto; background: #ffffff; border: 12px double #d97706; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 50px 60px; text-align: center; position: relative; }
        .cert-header { font-size: 32px; font-weight: bold; color: #1e3a8a; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 5px; }
        .cert-subtitle { font-size: 18px; color: #b45309; font-weight: bold; letter-spacing: 3px; text-transform: uppercase; }
        .cert-divider { border-top: 2px solid #f59e0b; width: 35%; margin: 20px auto; }
        .cert-recipient-label { font-size: 16px; color: #475569; margin-top: 25px; font-style: italic; }
        .cert-name { font-size: 36px; font-weight: bold; color: #1e293b; text-decoration: underline; margin: 15px 0 5px 0; text-transform: uppercase; }
        .cert-meta { font-size: 15px; color: #64748b; margin-bottom: 20px; }
        .cert-body { font-size: 18px; color: #334155; line-height: 1.6; margin: 20px 0; }
        .cert-exam-title { font-size: 24px; font-weight: bold; color: #1e3a8a; margin: 10px 0; }
        .cert-rank-badge { display: inline-block; background: #fef3c7; border: 2px solid #f59e0b; color: #b45309; padding: 8px 24px; border-radius: 50px; font-size: 22px; font-weight: bold; margin-top: 15px; }
        .cert-signatures { margin-top: 70px; }
        .sig-box { border-top: 1px dashed #94a3b8; width: 200px; margin: 0 auto; padding-top: 8px; font-size: 13px; color: #64748b; font-family: 'Segoe UI', sans-serif; }
        @media print {
            body { background: #fff; padding: 0; }
            .cert-card-container { border: 10px double #d97706; box-shadow: none; max-width: 100%; width: 100%; border-radius: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="text-center no-print" style="margin-bottom: 25px;">
    <button onclick="window.print()" class="btn btn-primary btn-lg" style="border-radius: 8px; font-family: 'Segoe UI', sans-serif;"><i class="fa fa-print"></i> Print Merit Certificate</button>
</div>

<div class="cert-card-container">
    <div class="cert-header"><?php echo htmlspecialchars($sch_setting[0]['name']); ?></div>
    <div class="cert-subtitle">Certificate of Merit & Academic Excellence</div>
    <div class="cert-divider"></div>

    <div class="cert-recipient-label">This Certificate is Proudly Awarded to</div>
    <div class="cert-name"><?php echo htmlspecialchars($candidate['firstname'] . ' ' . $candidate['lastname']); ?></div>
    <div class="cert-meta">Class: <strong><?php echo htmlspecialchars($candidate['class']); ?> (Section <?php echo htmlspecialchars($candidate['section']); ?>)</strong> | Admission No: <strong><?php echo htmlspecialchars($candidate['admission_no']); ?></strong></div>

    <div class="cert-body">
        For outstanding performance and achieving high distinction in the competition
    </div>

    <div class="cert-exam-title"><?php echo htmlspecialchars($candidate['exam_title']); ?></div>

    <div style="margin-top: 15px;">
        <div class="cert-rank-badge">
            <i class="fa fa-trophy"></i> 
            <?php if ($candidate['rank'] == 1) { ?>
                1st Rank - Gold Medalist
            <?php } elseif ($candidate['rank'] == 2) { ?>
                2nd Rank - Silver Medalist
            <?php } elseif ($candidate['rank'] == 3) { ?>
                3rd Rank - Bronze Medalist
            <?php } elseif ($candidate['rank']) { ?>
                Merit Rank Holder (Rank <?php echo $candidate['rank']; ?>)
            <?php } else { ?>
                Certificate of Merit
            <?php } ?>
        </div>
    </div>

    <div class="row cert-signatures">
        <div class="col-xs-4 text-center">
            <div class="sig-box">Exam Controller</div>
        </div>
        <div class="col-xs-4 text-center" style="margin-top: -20px;">
            <div style="font-size: 40px; color: #d97706;"><i class="fa fa-award"></i></div>
            <div style="font-size: 11px; color: #94a3b8; font-family: sans-serif; margin-top: 4px;">SEAL OF EXCELLENCE</div>
        </div>
        <div class="col-xs-4 text-center">
            <div class="sig-box">Principal / Director</div>
        </div>
    </div>
</div>

</body>
</html>
