<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Question Paper - <?php echo htmlspecialchars($exam['title']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body { background: #eef2f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; }
        .paper-container { max-width: 850px; margin: 0 auto; background: #fff; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 10px 30px rgba(0,0,0,0.08); padding: 40px; }
        .paper-header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 15px; margin-bottom: 25px; }
        .q-item { margin-bottom: 20px; page-break-inside: avoid; }
        .q-title { font-weight: bold; font-size: 15px; color: #0f172a; margin-bottom: 6px; }
        .q-opts { margin-left: 20px; }
        .q-opt-item { display: inline-block; width: 48%; font-size: 14px; margin-bottom: 4px; color: #334155; }
        @media print {
            body { background: #fff; padding: 0; }
            .paper-container { border: none; box-shadow: none; width: 100%; max-width: 100%; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="text-center no-print" style="margin-bottom: 20px;">
    <button onclick="window.print()" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-print"></i> Print Question Paper</button>
</div>

<div class="paper-container">
    <div class="paper-header">
        <h2 style="font-weight: bold; text-transform: uppercase; margin: 0;"><?php echo htmlspecialchars($sch_setting[0]['name']); ?></h2>
        <h3 style="font-size: 20px; color: #1e3a8a; margin: 5px 0; font-weight: bold;"><?php echo htmlspecialchars($exam['title']); ?></h3>
        <div style="font-size: 13px; color: #475569;">Exam Code: <strong><?php echo htmlspecialchars($exam['exam_code']); ?></strong> | Category: <?php echo htmlspecialchars($exam['exam_category']); ?></div>
        
        <div class="row" style="margin-top: 15px; border-top: 1px solid #cbd5e1; padding-top: 10px; font-size: 12px; font-weight: 600;">
            <div class="col-xs-4 text-left">Time Allowed: 60 Mins</div>
            <div class="col-xs-4 text-center">Mode: <?php echo strtoupper($exam['exam_mode']); ?></div>
            <div class="col-xs-4 text-right">Max Marks: <?php
                $tot = 0;
                if (!empty($questions)) {
                    foreach ($questions as $q) { $tot += $q['marks']; }
                }
                echo floatval($tot);
            ?></div>
        </div>
    </div>

    <div class="paper-instructions" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 10px 15px; margin-bottom: 25px; font-size: 12px;">
        <strong>GENERAL INSTRUCTIONS:</strong>
        <ol style="margin: 5px 0 0 15px; padding: 0;">
            <li>All questions are compulsory.</li>
            <li>Write your Roll Number clearly on the answer sheet / OMR sheet provided.</li>
            <li><?php echo htmlspecialchars($exam['instructions'] ?: 'Do not write anything on the question paper.'); ?></li>
        </ol>
    </div>

    <div class="paper-questions">
        <?php if (!empty($questions)) {
            $num = 1;
            foreach ($questions as $q) { ?>
                <div class="q-item">
                    <div class="q-title">Q<?php echo $num++; ?>. <?php echo strip_tags($q['question']); ?> <span class="pull-right" style="font-size:12px; font-weight:normal; color:#64748b;">[<?php echo floatval($q['marks']); ?> Mark(s)]</span></div>
                    <?php if ($q['question_type'] != 'descriptive') { ?>
                        <div class="q-opts">
                            <div class="q-opt-item">(A) <?php echo strip_tags($q['opt_a']); ?></div>
                            <div class="q-opt-item">(B) <?php echo strip_tags($q['opt_b']); ?></div>
                            <div class="q-opt-item">(C) <?php echo strip_tags($q['opt_c']); ?></div>
                            <div class="q-opt-item">(D) <?php echo strip_tags($q['opt_d']); ?></div>
                        </div>
                    <?php } else { ?>
                        <div style="height: 60px; border: 1px dashed #cbd5e1; border-radius: 4px; margin-top: 5px;"></div>
                    <?php } ?>
                </div>
            <?php }
        } else { ?>
            <div class="text-center text-muted">No questions added to this question paper yet.</div>
        <?php } ?>
    </div>
</div>

</body>
</html>
