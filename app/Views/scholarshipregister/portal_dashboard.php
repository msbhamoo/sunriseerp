<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Portal - <?php echo htmlspecialchars($candidate['firstname']); ?></title>
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
            --gold-600: #996515;
            --gold-500: #b8860b;
            --gold-400: #c59b27;
            --emerald-700: #047857;
            --emerald-600: #059669;
            --emerald-50: #ecfdf5;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .portal-navbar {
            background: var(--navy-950);
            color: #ffffff;
            padding: 14px 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        }

        .portal-navbar .brand-title {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 20px;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: 0.5px;
        }

        .portal-container {
            max-width: 1140px;
            margin: 30px auto;
            padding: 0 15px;
        }

        /* Hero / Motivation Banner */
        .motivation-card {
            background: linear-gradient(135deg, #0f1d38 0%, #1e3a8a 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 30px 35px;
            box-shadow: 0 10px 25px -5px rgba(15, 29, 56, 0.3);
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
            border-left: 6px solid var(--gold-500);
        }

        .motivation-card::after {
            content: "\f091";
            font-family: 'FontAwesome';
            position: absolute;
            right: 20px;
            bottom: -20px;
            font-size: 160px;
            color: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }

        .countdown-box {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 15px 20px;
            text-align: center;
            display: inline-block;
            margin-top: 15px;
        }

        .countdown-digits {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 34px;
            font-weight: 800;
            color: #fde047;
            line-height: 1;
        }

        .countdown-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #e2e8f0;
            margin-top: 4px;
            font-weight: 600;
        }

        .card-custom {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            padding: 24px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .card-title-custom {
            font-family: 'Crimson Pro', Georgia, serif;
            font-size: 20px;
            font-weight: 700;
            color: var(--navy-900);
            margin-top: 0;
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .candidate-meta-item {
            display: flex;
            justify-content: space-between;
            padding: 9px 0;
            border-bottom: 1px dashed #f1f5f9;
            font-size: 13.5px;
        }

        .candidate-meta-item:last-child {
            border-bottom: none;
        }

        .material-item {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 20px;
            margin-bottom: 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .material-item:hover {
            border-color: var(--gold-500);
            background: #fdfaf2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        .badge-sample {
            background: #dbeafe;
            color: #1e40af;
            font-weight: 600;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .badge-notes {
            background: #dcfce7;
            color: #166534;
            font-weight: 600;
            font-size: 11px;
            padding: 4px 8px;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .quote-box {
            background: #fefce8;
            border-left: 4px solid #eab308;
            border-radius: 8px;
            padding: 14px 18px;
            font-style: italic;
            font-size: 14px;
            color: #713f12;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<!-- Navigation Header -->
<div class="portal-navbar">
    <div class="container" style="max-width: 1140px;">
        <div class="row" style="display: flex; align-items: center;">
            <div class="col-xs-8">
                <span class="brand-title">
                    <i class="fa fa-graduation-cap" style="color: var(--gold-400);"></i> 
                    <?php echo isset($sch_setting[0]['name']) ? htmlspecialchars($sch_setting[0]['name']) : 'Sunrise Education Group'; ?>
                </span>
                <span class="hidden-xs" style="color: #94a3b8; font-size: 13px; margin-left: 10px;">| Candidate Dashboard</span>
            </div>
            <div class="col-xs-4 text-right">
                <a href="<?php echo site_url('scholarshipregister/portal_logout'); ?>" class="btn btn-sm btn-danger" style="border-radius: 6px;">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<div class="portal-container">

    <!-- Hero Banner with Motivation & Countdown -->
    <div class="motivation-card">
        <div class="row" style="display: flex; align-items: center; flex-wrap: wrap;">
            <div class="col-md-8">
                <span class="label" style="background: var(--gold-500); font-size: 12px; font-weight: 700; letter-spacing: 0.5px; padding: 4px 10px; border-radius: 4px;">
                    <?php echo htmlspecialchars($candidate['exam_code']); ?> CANDIDATE PORTAL
                </span>
                <h1 style="font-family: 'Crimson Pro', Georgia, serif; font-size: 28px; margin: 12px 0 6px; font-weight: 700;">
                    Welcome, <?php echo htmlspecialchars($candidate['firstname']); ?>!
                </h1>
                <p style="color: #cbd5e1; font-size: 14.5px; margin: 0; line-height: 1.5;">
                    Your preparation journey for <strong><?php echo htmlspecialchars($candidate['exam_title']); ?></strong> starts here. Download your class sample papers and test your speed with real MCQs.
                </p>

                <!-- Daily Motivation Quote -->
                <div class="quote-box">
                    <i class="fa fa-quote-left"></i> 
                    <strong>"Success doesn't come from what you do occasionally, it comes from what you do consistently."</strong>
                    <br><small style="font-style: normal; font-weight: 600; color: #854d0e;">— Sunrise Academic Excellence Team</small>
                </div>
            </div>

            <!-- Countdown Timer Block -->
            <div class="col-md-4 text-center">
                <?php if ($days_remaining !== null) { ?>
                    <div class="countdown-box">
                        <div class="countdown-digits">
                            <?php echo $days_remaining; ?> <span style="font-size: 18px; color: #fff;">Days</span>
                            <?php echo $hours_remaining; ?> <span style="font-size: 18px; color: #fff;">Hrs</span>
                        </div>
                        <div class="countdown-label">Time Remaining Until Exam</div>
                        <div style="font-size: 12px; color: #cbd5e1; margin-top: 6px;">
                            <i class="fa fa-calendar"></i> <?php echo date('d M Y, h:i A', strtotime($exam_date)); ?>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="countdown-box">
                        <div class="countdown-digits"><i class="fa fa-calendar-check-o"></i></div>
                        <div class="countdown-label">Exam Date Announced Soon</div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column: Study Material & Model Papers -->
        <div class="col-md-7">
            <div class="card-custom">
                <h3 class="card-title-custom">
                    <span><i class="fa fa-file-text text-primary"></i> Sample Papers & Model Tests</span>
                    <span class="label label-info" style="font-size: 12px;"><?php echo htmlspecialchars($candidate['class']); ?></span>
                </h3>

                <p style="font-size: 13.5px; color: #64748b; margin-bottom: 20px;">
                    Practice with authentic sample question papers prepared by subject matter experts strictly according to NCERT blueprint.
                </p>

                <?php if (!empty($sample_papers)) {
                    foreach ($sample_papers as $sp) { ?>
                        <div class="material-item">
                            <div>
                                <span class="badge-sample"><i class="fa fa-file-pdf-o"></i> Sample Paper</span>
                                <h4 style="margin: 6px 0 3px; font-size: 15px; font-weight: 700; color: #0f172a;">
                                    <?php echo htmlspecialchars($sp['title']); ?>
                                </h4>
                                <?php if (!empty($sp['description'])) { ?>
                                    <p style="font-size: 12.5px; color: #64748b; margin: 0;"><?php echo htmlspecialchars($sp['description']); ?></p>
                                <?php } ?>
                                <small style="color: #94a3b8; font-size: 11.5px;">
                                    Size: <?php echo htmlspecialchars($sp['file_size'] ?: 'PDF'); ?> &bull; Uploaded: <?php echo date('d M Y', strtotime($sp['created_at'])); ?>
                                </small>
                            </div>
                            <div>
                                <a href="<?php echo base_url($sp['file_path']); ?>" target="_blank" class="btn btn-sm btn-primary" style="border-radius: 6px; font-weight: 600;">
                                    <i class="fa fa-download"></i> Download
                                </a>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 8px; padding: 25px; text-align: center; color: #64748b;">
                        <i class="fa fa-cloud-download" style="font-size: 32px; color: #94a3b8; margin-bottom: 8px;"></i>
                        <p style="margin: 0; font-size: 14px; font-weight: 600;">No sample papers uploaded for your class yet.</p>
                        <small style="color: #94a3b8;">Teachers will upload sample papers soon. Please check back regularly.</small>
                    </div>
                <?php } ?>
            </div>

            <!-- Other Study Materials & Syllabus -->
            <div class="card-custom">
                <h3 class="card-title-custom">
                    <span><i class="fa fa-bookmark text-success"></i> Syllabus & Subject Notes</span>
                </h3>

                <?php 
                $other_filtered = array();
                if (!empty($other_materials)) {
                    foreach ($other_materials as $om) {
                        if ($om['material_type'] != 'sample_paper') {
                            $other_filtered[] = $om;
                        }
                    }
                }
                ?>

                <?php if (!empty($other_filtered)) {
                    foreach ($other_filtered as $om) { ?>
                        <div class="material-item">
                            <div>
                                <span class="badge-notes"><i class="fa fa-folder-open"></i> <?php echo ucfirst($om['material_type']); ?></span>
                                <h4 style="margin: 6px 0 3px; font-size: 15px; font-weight: 700; color: #0f172a;">
                                    <?php echo htmlspecialchars($om['title']); ?>
                                </h4>
                                <?php if (!empty($om['description'])) { ?>
                                    <p style="font-size: 12.5px; color: #64748b; margin: 0;"><?php echo htmlspecialchars($om['description']); ?></p>
                                <?php } ?>
                            </div>
                            <div>
                                <a href="<?php echo base_url($om['file_path']); ?>" target="_blank" class="btn btn-sm btn-success" style="border-radius: 6px; font-weight: 600;">
                                    <i class="fa fa-download"></i> View File
                                </a>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <div style="font-size: 13.5px; color: #64748b; background: #f8fafc; padding: 15px; border-radius: 8px;">
                        <i class="fa fa-info-circle text-info"></i> All syllabus questions are strictly based on standard NCERT textbook curriculum for your registered class.
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- Right Column: Registration Card & Admit Card Access -->
        <div class="col-md-5">
            <!-- Candidate Profile Card -->
            <div class="card-custom">
                <h3 class="card-title-custom">
                    <span><i class="fa fa-user-circle text-primary"></i> Registered Profile</span>
                    <span class="label label-success" style="font-size: 11px;">VERIFIED</span>
                </h3>

                <div class="candidate-meta-item">
                    <span class="text-muted">Roll Number</span>
                    <strong style="color: var(--emerald-700); font-size: 15px;"><?php echo htmlspecialchars($candidate['roll_no']); ?></strong>
                </div>

                <div class="candidate-meta-item">
                    <span class="text-muted">Candidate Name</span>
                    <strong><?php echo htmlspecialchars($candidate['firstname']); ?></strong>
                </div>

                <div class="candidate-meta-item">
                    <span class="text-muted">Father's Name</span>
                    <span><?php echo htmlspecialchars($candidate['father_name'] ?: '-'); ?></span>
                </div>

                <div class="candidate-meta-item">
                    <span class="text-muted">Registered Class</span>
                    <strong><?php echo htmlspecialchars($candidate['class']); ?></strong>
                </div>

                <div class="candidate-meta-item">
                    <span class="text-muted">School</span>
                    <span><?php echo htmlspecialchars(!empty($candidate['school_name']) ? $candidate['school_name'] : (!empty($cand_raw['external_school_name']) ? $cand_raw['external_school_name'] : 'School')); ?></span>
                </div>

                <div class="candidate-meta-item">
                    <span class="text-muted">Registered Mobile</span>
                    <span><?php echo htmlspecialchars(!empty($cand_raw['external_mobile']) ? $cand_raw['external_mobile'] : '-'); ?></span>
                </div>

                <div class="candidate-meta-item">
                    <span class="text-muted">Exam Center</span>
                    <span style="font-weight: 600; color: #0f1d38; text-align: right;"><?php echo htmlspecialchars($candidate['exam_center'] ?: 'Main School Campus'); ?></span>
                </div>

                <div style="margin-top: 20px; padding: 15px; background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe;">
                    <div style="font-size: 13px; font-weight: 700; color: #1e40af; margin-bottom: 6px;">
                        <i class="fa fa-ticket"></i> Official Admit Card / Hall Ticket
                    </div>
                    <?php if (isset($candidate['admit_card_status']) && $candidate['admit_card_status'] == 1) { ?>
                        <p style="font-size: 12.5px; color: #1e3a8a; margin-bottom: 12px;">
                            Your Admit Card has been released! Please download and print it before the examination.
                        </p>
                        <a href="<?php echo site_url('admin/scholarshipexam/admitcard/' . $candidate['id']); ?>" target="_blank" class="btn btn-sm btn-primary btn-block" style="border-radius: 6px; font-weight: 700;">
                            <i class="fa fa-print"></i> Download & Print Admit Card
                        </a>
                    <?php } else { ?>
                        <p style="font-size: 12.5px; color: #64748b; margin: 0;">
                            <i class="fa fa-clock-o"></i> Admit Card will be released shortly before the examination. You can print it directly from here once unlocked.
                        </p>
                    <?php } ?>
                </div>
            </div>

            <!-- Exam Guidelines & Instructions -->
            <div class="card-custom">
                <h3 class="card-title-custom">
                    <span><i class="fa fa-list-ul text-warning"></i> Important Instructions</span>
                </h3>

                <ul style="padding-left: 18px; font-size: 13px; color: #475569; line-height: 1.8; margin-bottom: 0;">
                    <li>Bring your printed <strong>Admit Card</strong> & valid ID proof.</li>
                    <li>Use only <strong>Blue / Black Ballpoint pen</strong> to darken bubbles on OMR sheets.</li>
                    <li>Report to the exam center <strong>30 minutes</strong> prior to scheduled time.</li>
                    <li>No electronic devices, calculators or smartphones allowed in the exam hall.</li>
                    <li>Contact helpline at <strong>9828512821 / 9783200821</strong> for any query.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<footer style="text-align: center; padding: 25px 0 35px; color: #64748b; font-size: 13px;">
    &copy; 2026 <?php echo isset($sch_setting[0]['name']) ? htmlspecialchars($sch_setting[0]['name']) : 'Sunrise Education Group'; ?>. All Rights Reserved.
</footer>

</body>
</html>