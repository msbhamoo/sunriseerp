<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Prospectus & Information Brochure - <?php echo htmlspecialchars($exam['title']); ?></title>
    <link rel="stylesheet" href="<?php echo base_url('backend/bootstrap/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('backend/font-awesome/css/font-awesome.min.css'); ?>">
    <style>
        body {
            background-color: #f1f5f9;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #1e293b;
            padding: 30px 15px;
            line-height: 1.6;
        }
        .brochure-container {
            max-width: 900px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border: 1px solid #cbd5e1;
            padding: 45px 50px;
        }
        .header-title-box {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .school-name {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .exam-title-badge {
            display: inline-block;
            background: #1e3a8a;
            color: #ffffff;
            font-size: 20px;
            font-weight: 700;
            padding: 8px 24px;
            border-radius: 6px;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .spec-list {
            margin: 0;
            padding: 0;
            list-style: none;
            counter-reset: spec-counter;
        }
        .spec-item {
            position: relative;
            padding-left: 45px;
            margin-bottom: 22px;
            font-size: 15px;
        }
        .spec-num {
            position: absolute;
            left: 0;
            top: 0;
            font-weight: 700;
            width: 35px;
            color: #1e293b;
        }
        .spec-label {
            font-weight: 700;
            color: #0f172a;
            display: inline-block;
            min-width: 150px;
        }
        .syllabus-table {
            width: 100%;
            margin-top: 15px;
            margin-bottom: 20px;
            border: 1px solid #000;
            border-collapse: collapse;
        }
        .syllabus-table th, .syllabus-table td {
            border: 1px solid #000;
            padding: 9px 12px;
            font-size: 14px;
            vertical-align: middle;
        }
        .syllabus-table th {
            background-color: #f1f5f9;
            font-weight: 700;
            text-align: center;
        }
        .syllabus-table td.center {
            text-align: center;
        }
        .sub-alpha-list {
            list-style: none;
            padding-left: 20px;
            margin-top: 6px;
        }
        .sub-alpha-list li {
            margin-bottom: 6px;
        }
        .sub-alpha-list .alpha-tag {
            font-weight: 700;
            display: inline-block;
            width: 32px;
        }
        .sub-roman-list {
            list-style: none;
            padding-left: 35px;
            margin-top: 8px;
        }
        .sub-roman-list li {
            margin-bottom: 5px;
        }
        .sub-roman-list .roman-tag {
            font-weight: 600;
            display: inline-block;
            width: 35px;
        }
        .highlight-badge {
            background-color: #fef3c7;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            color: #92400e;
        }
        .whatsapp-badge {
            background-color: #25d366;
            color: #fff;
            padding: 3px 10px;
            border-radius: 4px;
            font-weight: 700;
            text-decoration: none;
        }
        .btn-action-bar {
            margin-bottom: 25px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .brochure-container {
                border: none;
                box-shadow: none;
                max-width: 100%;
                width: 100%;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .syllabus-table th {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
            }
            .exam-title-badge {
                border: 2px solid #000;
                color: #000 !important;
                background: #fff !important;
            }
        }
    </style>
</head>
<body>

<div class="container text-center no-print btn-action-bar">
    <button onclick="window.print()" class="btn btn-primary btn-lg" style="border-radius: 6px; font-weight: bold;">
        <i class="fa fa-print"></i> Print Official Brochure
    </button>
    <a href="<?php echo site_url('scholarshipregister/apply/' . urlencode($exam['exam_code'])); ?>" target="_blank" class="btn btn-success btn-lg" style="border-radius: 6px; font-weight: bold; margin-left: 10px;">
        <i class="fa fa-pencil-square-o"></i> Go to Public Registration Portal
    </a>
    <a href="<?php echo site_url('admin/scholarshipexam/exams'); ?>" class="btn btn-default btn-lg" style="border-radius: 6px; margin-left: 10px;">
        <i class="fa fa-arrow-left"></i> Back to Exams
    </a>
</div>

<div class="brochure-container">
    <div class="header-title-box">
        <div class="school-name"><?php echo htmlspecialchars($sch_setting[0]['name'] ?: 'SUNRISE INTERNATIONAL PUBLIC SCHOOL'); ?></div>
        <div class="exam-title-badge">
            <?php echo htmlspecialchars($exam['title'] ?: 'SUNRISE SCHOLARSHIP CUM ADMISSION TEST'); ?>
        </div>
        <div style="margin-top: 10px; font-size: 13px; color: #64748b;">
            Notification Code: <strong><?php echo htmlspecialchars($exam['exam_code']); ?></strong> | Category: <strong><?php echo htmlspecialchars($exam['exam_category']); ?></strong>
        </div>
    </div>

    <div class="spec-list">
        <!-- 1. Date of Exam -->
        <div class="spec-item">
            <span class="spec-num">1.</span>
            <span class="spec-label">Date of Exam</span> : 
            <strong>
                <?php 
                if (!empty($exam['schedules'][0]['exam_date'])) {
                    echo date('d M Y (l)', strtotime($exam['schedules'][0]['exam_date']));
                } else {
                    echo '15 Nov 2026 (Sunday)';
                }
                ?>
            </strong>
        </div>

        <!-- 2. Eligibility -->
        <div class="spec-item">
            <span class="spec-num">2.</span>
            <span class="spec-label">Eligibility</span> : 
            All the students from <strong>class 5<sup>th</sup> to 11<sup>th</sup></strong> from any school other than Sunrise.
        </div>

        <!-- 3. Medium -->
        <div class="spec-item">
            <span class="spec-num">3.</span>
            <span class="spec-label">Medium</span> : 
            Hindi & English (bilingual)
        </div>

        <!-- 4. Type of Exam -->
        <div class="spec-item">
            <span class="spec-num">4.</span>
            <span class="spec-label">Type of Exam</span> : 
            <?php echo ($exam['exam_mode'] == 'online') ? 'Online Computer-Based Test (CBT)' : 'MCQ based on OMR Sheet'; ?>.
        </div>

        <!-- 5. Syllabus, number of questions and total marks -->
        <div class="spec-item">
            <span class="spec-num">5.</span>
            <span class="spec-label" style="display:block; margin-bottom: 8px;">Syllabus, number of questions and total marks :</span>
            
            <table class="syllabus-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Class</th>
                        <th style="width: 40%;">Syllabus<br><small style="font-weight: normal;">(out of 100% NCERT Syllabus)</small></th>
                        <th style="width: 12%;">No of Questions</th>
                        <th style="width: 12%;">No of Marks</th>
                        <th style="width: 11%;">Total Time</th>
                        <th style="width: 10%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="center"><strong>5<sup>th</sup></strong></td>
                        <td>Hindi, English, Maths, EVS</td>
                        <td class="center">40</td>
                        <td class="center">50</td>
                        <td class="center">90 Mins</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="center"><strong>6<sup>th</sup> to 10<sup>th</sup></strong></td>
                        <td>Hindi, English, Sci, Maths, SST, Reasoning</td>
                        <td class="center">60</td>
                        <td class="center">90</td>
                        <td class="center">90 Mins</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="center"><strong>11<sup>th</sup> Sci</strong></td>
                        <td>English, Maths/Bio, Physics, Chemistry</td>
                        <td class="center">60</td>
                        <td class="center">120</td>
                        <td class="center">90 Mins</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="center"><strong>11<sup>th</sup> Arts</strong></td>
                        <td>English, Pol Sci, History, Geography</td>
                        <td class="center">60</td>
                        <td class="center">120</td>
                        <td class="center">90 Mins</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- 6. Mode of Registration / apply for the exam -->
        <div class="spec-item">
            <span class="spec-num">6.</span>
            <span class="spec-label" style="display:block; margin-bottom: 8px;">Mode of Registration/apply for the exam :</span>

            <ul class="sub-alpha-list">
                <li>
                    <span class="alpha-tag">(a)</span>
                    <strong><u>Online by the applicant</u> :</strong> Through the portal link:
                    <a href="<?php echo site_url('scholarshipregister/apply/' . urlencode($exam['exam_code'])); ?>" target="_blank" style="font-weight: 600; color: #1d4ed8; word-break: break-all;">
                        <?php echo site_url('scholarshipregister/apply/' . urlencode($exam['exam_code'])); ?>
                    </a>
                </li>
                <li>
                    <span class="alpha-tag">(b)</span>
                    <strong><u>Through the WhatsApp</u> :</strong> Sending the message on WhatsApp No <a href="https://wa.me/919783200821" target="_blank" class="whatsapp-badge"><i class="fa fa-whatsapp"></i> 9783200821</a> by sending the following details :-
                    
                    <ul class="sub-roman-list">
                        <li><span class="roman-tag">(i)</span> <span style="min-width: 170px; display:inline-block;">Name of the student</span> : _________________________________</li>
                        <li><span class="roman-tag">(ii)</span> <span style="min-width: 170px; display:inline-block;">Father's Name</span> : _________________________________</li>
                        <li><span class="roman-tag">(iii)</span> <span style="min-width: 170px; display:inline-block;">Present Class</span> : _________________________________</li>
                        <li><span class="roman-tag">(iv)</span> <span style="min-width: 170px; display:inline-block;">Name of the school</span> : _________________________________</li>
                        <li><span class="roman-tag">(v)</span> <span style="min-width: 170px; display:inline-block;">Village & District</span> : _________________________________</li>
                        <li><span class="roman-tag">(vi)</span> <span style="min-width: 170px; display:inline-block;">Mobile No</span> : _________________________________</li>
                    </ul>
                </li>
                <li style="margin-top: 10px;">
                    <span class="alpha-tag">(c)</span>
                    <strong><u>Offline</u> :</strong> Form available at the school reception centre and can be downloaded from the school website. These forms can be deposited at school reception or uploaded on the link given at school website.
                </li>
            </ul>
        </div>

        <!-- 7. Important Dates -->
        <div class="spec-item">
            <span class="spec-num">7.</span>
            <span class="spec-label" style="display:block; margin-bottom: 8px;">Important Dates :-</span>

            <ul class="sub-alpha-list">
                <li><span class="alpha-tag">(a)</span> <span style="min-width: 260px; display:inline-block;">Date of Release of notification</span> : <strong>15 Oct 2026</strong></li>
                <li><span class="alpha-tag">(b)</span> <span style="min-width: 260px; display:inline-block;">Registration start date</span> : <strong>15 Oct 2026</strong></li>
                <li><span class="alpha-tag">(c)</span> <span style="min-width: 260px; display:inline-block;">Registration closes</span> : <strong>10 Nov 2026</strong></li>
                <li><span class="alpha-tag">(d)</span> <span style="min-width: 260px; display:inline-block;">Date of Exam (offline & online)</span> : <strong>15 Nov 2026 (Sunday)</strong></li>
                <li><span class="alpha-tag">(e)</span> <span style="min-width: 260px; display:inline-block;">Announcement of result</span> : <strong>21 Nov 2026</strong></li>
                <li><span class="alpha-tag">(f)</span> <span style="min-width: 260px; display:inline-block;">Result Ceremony</span> : <span class="highlight-badge">To be declared separately</span></li>
            </ul>
        </div>

        <!-- 8. Awards -->
        <div class="spec-item">
            <span class="spec-num">8.</span>
            <span class="spec-label" style="display:block; margin-bottom: 8px;">Awards :</span>

            <ul class="sub-alpha-list">
                <li><span class="alpha-tag">(a)</span> <strong>Upto 100% Scholarship in school fees.</strong></li>
                <li><span class="alpha-tag">(b)</span> <strong>Thousands of Cash Awards.</strong></li>
                <li><span class="alpha-tag">(c)</span> <strong>Gifts, Memento and Certificates of participation</strong> for meritorious achievers.</li>
            </ul>
        </div>

        <!-- 9. Syllabus & Sample papers -->
        <div class="spec-item">
            <span class="spec-num">9.</span>
            <span class="spec-label" style="min-width: auto;">Classwise Syllabus & Sample question papers are available on the Website :</span>
            <strong><?php echo site_url('scholarshipregister'); ?></strong>
        </div>

        <!-- 10. Online Practice Tests -->
        <div class="spec-item">
            <span class="spec-num">10.</span>
            <span class="spec-label" style="min-width: auto;">The students can attempt more tests online by clicking on the link on our website :</span>
            <strong><?php echo site_url('scholarshipregister'); ?></strong>
        </div>
    </div>

    <div style="margin-top: 40px; padding-top: 20px; border-top: 1px dashed #94a3b8; font-size: 13px; color: #475569; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <i class="fa fa-map-marker"></i> <strong>Examination Venue:</strong> <?php echo htmlspecialchars($exam['exam_center'] ?: 'Sunrise School Campus'); ?>
        </div>
        <div>
            <i class="fa fa-phone"></i> <strong>Helpline / WhatsApp:</strong> +91 9783200821
        </div>
    </div>
</div>

</body>
</html>
