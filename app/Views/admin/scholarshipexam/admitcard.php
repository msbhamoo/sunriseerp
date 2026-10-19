<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admit Card - <?php echo htmlspecialchars($candidate['roll_no']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body { background: #eef2f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
        .admit-card-container { max-width: 800px; margin: 0 auto; background: #fff; border-radius: 12px; border: 2px solid #3b82f6; box-shadow: 0 10px 30px rgba(0,0,0,0.1); padding: 30px; position: relative; }
        .admit-header { border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 20px; }
        .admit-title { font-size: 22px; font-weight: bold; color: #1e3a8a; text-transform: uppercase; margin: 0; }
        .admit-subtitle { font-size: 14px; color: #64748b; font-weight: 500; }
        .student-photo { width: 110px; height: 130px; border: 2px solid #cbd5e1; border-radius: 8px; object-fit: cover; }
        .info-table td { padding: 6px 12px; vertical-align: middle; }
        .info-label { font-weight: 600; color: #475569; width: 140px; }
        .barcode-box { text-align: center; border: 1px dashed #94a3b8; border-radius: 8px; padding: 10px; background: #f8fafc; }
        .instructions-box { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px 15px; border-radius: 4px; margin-top: 20px; font-size: 12px; color: #1e40af; }
        @media print {
            body { background: #fff; padding: 0; }
            .admit-card-container { border: 1px solid #000; box-shadow: none; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="text-center no-print" style="margin-bottom: 20px;">
    <button onclick="window.print()" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-print"></i> Print Candidate Admit Card</button>
</div>

<div class="admit-card-container">
    <div class="admit-header row">
        <div class="col-xs-9">
            <h2 class="admit-title"><?php echo htmlspecialchars($sch_setting[0]['name']); ?></h2>
            <div class="admit-subtitle"><strong style="color: #2563eb;"><?php echo htmlspecialchars($candidate['exam_title']); ?></strong> (Code: <?php echo htmlspecialchars($candidate['exam_code']); ?>)</div>
            <div style="font-size: 12px; color: #64748b; margin-top: 4px;">OFFICIAL HALL TICKET / ADMIT CARD</div>
        </div>
        <div class="col-xs-3 text-right">
            <div class="barcode-box">
                <div style="font-weight: bold; font-family: monospace; font-size: 16px; color: #000;"><?php echo htmlspecialchars($candidate['roll_no']); ?></div>
                <div style="font-size: 10px; color: #64748b;">ROLL NUMBER</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-9">
            <table class="table info-table table-borderless">
                <tr>
                    <td class="info-label">Candidate Name:</td>
                    <td><strong style="font-size: 16px; color: #0f172a;"><?php echo htmlspecialchars($candidate['firstname'] . ' ' . $candidate['lastname']); ?></strong></td>
                </tr>
                <tr>
                    <td class="info-label">Roll Number:</td>
                    <td><strong style="color: #2563eb; font-size: 15px;"><?php echo htmlspecialchars($candidate['roll_no']); ?></strong></td>
                </tr>
                <tr>
                    <td class="info-label">Admit Card No:</td>
                    <td><code><?php echo htmlspecialchars($candidate['admit_card_no']); ?></code></td>
                </tr>
                <tr>
                    <td class="info-label">Class & Section:</td>
                    <td>Class <?php echo htmlspecialchars($candidate['class']); ?> (Section <?php echo htmlspecialchars($candidate['section']); ?>)</td>
                </tr>
                <tr>
                    <td class="info-label">Admission No:</td>
                    <td><?php echo htmlspecialchars($candidate['admission_no']); ?></td>
                </tr>
                <tr>
                    <td class="info-label">Father's Name:</td>
                    <td><?php echo htmlspecialchars($candidate['father_name'] ?: $candidate['guardian_name']); ?></td>
                </tr>
            </table>
        </div>
        <div class="col-xs-3 text-center">
            <?php if (!empty($candidate['image'])) { ?>
                <img src="<?php echo base_url($candidate['image']); ?>" class="student-photo" alt="Photo">
            <?php } else { ?>
                <div class="student-photo" style="display:flex; align-items:center; justify-content:center; background:#f1f5f9; color:#94a3b8; margin: 0 auto; line-height: 130px;">PASSPORT PHOTO</div>
            <?php } ?>
        </div>
    </div>

    <div class="row" style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 15px;">
        <div class="col-xs-6">
            <div style="font-size: 11px; color: #64748b; font-weight: 600;">EXAM DATE & TIME</div>
            <div style="font-size: 14px; font-weight: bold; color: #0f172a;">
                <?php echo $candidate['exam_date'] ? date('d M Y (l) \a\t h:i A', strtotime($candidate['exam_date'])) : 'Schedule TBA'; ?>
            </div>
            <div style="font-size: 12px; color: #475569;">Duration: <?php echo $candidate['duration_minutes']; ?> Minutes</div>
        </div>
        <div class="col-xs-6">
            <div style="font-size: 11px; color: #64748b; font-weight: 600;">EXAM CENTER / VENUE</div>
            <div style="font-size: 13px; font-weight: bold; color: #0f172a;">
                <?php echo htmlspecialchars($candidate['exam_center']); ?>
            </div>
            <div style="font-size: 12px; color: #475569;">Mode: <?php echo strtoupper($candidate['exam_mode']); ?> EXAM</div>
        </div>
    </div>

    <div class="instructions-box">
        <strong>IMPORTANT INSTRUCTIONS FOR CANDIDATES:</strong>
        <ol style="margin-bottom: 0; padding-left: 18px;">
            <li>Candidates must present this printed Admit Card along with valid School ID.</li>
            <li>Report to the exam center 20 minutes prior to the scheduled exam start time.</li>
            <li><?php echo htmlspecialchars($candidate['instructions'] ?: 'Do not bring mobile phones, electronic smartwatches or calculators into the exam hall.'); ?></li>
        </ol>
    </div>

    <div class="row" style="margin-top: 50px;">
        <div class="col-xs-6 text-center">
            <div style="border-top: 1px dashed #94a3b8; width: 180px; margin: 0 auto; padding-top: 4px; font-size: 11px; color: #64748b;">Candidate Signature</div>
        </div>
        <div class="col-xs-6 text-center">
            <div style="border-top: 1px dashed #94a3b8; width: 180px; margin: 0 auto; padding-top: 4px; font-size: 11px; color: #64748b;">Controller of Examinations</div>
        </div>
    </div>
</div>

</body>
</html>
