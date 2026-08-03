<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APPENDIX - IX | MANDATORY PUBLIC DISCLOSURE</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/font-awesome/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0d1117;
            color: #c9d1d9;
            padding: 30px 15px;
        }
        .disclosure-card {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header-banner {
            background: linear-gradient(135deg, #1f2937, #111827);
            border-bottom: 2px solid #3b82f6;
            padding: 16px 24px;
        }
        .card-header-banner h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #60a5fa;
            text-transform: uppercase;
        }
        .table-custom {
            margin-bottom: 0;
            color: #d1d5db;
        }
        .table-custom th {
            background-color: #1f2937;
            color: #9ca3af;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            border-bottom: 1px solid #374151 !important;
            padding: 12px 16px;
        }
        .table-custom td {
            border-top: 1px solid #21262d !important;
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 13px;
        }
        .btn-doc {
            background-color: #1d4ed8;
            color: #ffffff !important;
            border: none;
            border-radius: 6px;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            transition: all 0.2s;
        }
        .btn-doc:hover {
            background-color: #2563eb;
            box-shadow: 0 0 10px rgba(59,130,246,0.5);
        }
        .badge-pass {
            background-color: #065f46;
            color: #34d399;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }
        .header-title-box {
            text-align: center;
            margin-bottom: 40px;
            padding: 24px;
            background: linear-gradient(135deg, #111827, #1f2937);
            border-radius: 12px;
            border: 1px solid #374151;
        }
        .header-title-box h1 {
            font-size: 26px;
            font-weight: 800;
            color: #f3f4f6;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .header-title-box h2 {
            font-size: 20px;
            font-weight: 700;
            color: #3b82f6;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-title-box">
            <span class="label label-primary" style="font-size:12px; padding: 4px 12px;">MANDATORY PUBLIC DISCLOSURE</span>
            <h2>APPENDIX - IX</h2>
            <h1><?php echo isset($disclosure_data['general_info']['school_name']['value']) && !empty($disclosure_data['general_info']['school_name']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_name']['value']) : (isset($sch_setting->name) ? htmlspecialchars($sch_setting->name) : 'Sunrise International Public School'); ?></h1>
            <p class="text-muted" style="margin-bottom:0;"><?php echo isset($disclosure_data['general_info']['school_address']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_address']['value']) : ''; ?></p>
        </div>

        <!-- A: GENERAL INFORMATION -->
        <div class="disclosure-card">
            <div class="card-header-banner">
                <h3>A : GENERAL INFORMATION</h3>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th style="width: 8%;">SL NO.</th>
                        <th style="width: 42%;">INFORMATION</th>
                        <th style="width: 50%;">DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>NAME OF THE SCHOOL</td><td><strong><?php echo isset($disclosure_data['general_info']['school_name']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_name']['value']) : '-'; ?></strong></td></tr>
                    <tr><td>2</td><td>AFFILIATION NO. (IF APPLICABLE)</td><td><?php echo isset($disclosure_data['general_info']['affiliation_no']['value']) ? htmlspecialchars($disclosure_data['general_info']['affiliation_no']['value']) : '-'; ?></td></tr>
                    <tr><td>3</td><td>SCHOOL CODE (IF APPLICABLE)</td><td><?php echo isset($disclosure_data['general_info']['school_code']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_code']['value']) : '-'; ?></td></tr>
                    <tr><td>4</td><td>COMPLETE ADDRESS WITH PIN CODE</td><td><?php echo isset($disclosure_data['general_info']['school_address']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_address']['value']) : '-'; ?></td></tr>
                    <tr><td>5</td><td>PRINCIPAL NAME</td><td><?php echo isset($disclosure_data['general_info']['principal_name']['value']) ? htmlspecialchars($disclosure_data['general_info']['principal_name']['value']) : '-'; ?></td></tr>
                    <tr><td>6</td><td>PRINCIPAL QUALIFICATION</td><td><?php echo isset($disclosure_data['general_info']['principal_qualification']['value']) ? htmlspecialchars($disclosure_data['general_info']['principal_qualification']['value']) : '-'; ?></td></tr>
                    <tr><td>7</td><td>SCHOOL EMAIL ID</td><td><?php echo isset($disclosure_data['general_info']['school_email']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_email']['value']) : '-'; ?></td></tr>
                    <tr><td>8</td><td>CONTACT DETAILS (LANDLINE/MOBILE)</td><td><?php echo isset($disclosure_data['general_info']['school_phone']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_phone']['value']) : '-'; ?></td></tr>
                </tbody>
            </table>
        </div>

        <!-- B: DOCUMENTS AND INFORMATION -->
        <div class="disclosure-card">
            <div class="card-header-banner">
                <h3>B : DOCUMENTS AND INFORMATION</h3>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th style="width: 8%;">SL NO.</th>
                        <th style="width: 62%;">DOCUMENTS/INFORMATION</th>
                        <th style="width: 30%; text-align: right;">LINKS OF UPLOADED DOCUMENTS ON YOUR SCHOOL WEBSITE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $doc_list = array(
                        'doc_affiliation' => 'COPIES OF AFFILIATION/UPGRADATION LETTER AND RECENT EXTENSION OF AFFILIATION, IF ANY',
                        'doc_society' => 'COPIES OF SOCIETIES/TRUST/COMPANY REGISTRATION/RENEWAL CERTIFICATE, AS APPLICABLE',
                        'doc_noc' => 'COPY OF NO OBJECTION CERTIFICATE (NOC) ISSUED, IF APPLICABLE, BY THE STATE GOVT./UT',
                        'doc_recognition' => 'COPIES OF RECOGNITION CERTIFICATE UNDER RTE ACT, 2009, AND IT\'S RENEWAL IF APPLICABLE',
                        'doc_building' => 'COPY OF VALID BUILDING SAFETY CERTIFICATE AS PER THE NATIONAL BUILDING CODE',
                        'doc_fire' => 'COPY OF VALID FIRE SAFETY CERTIFICATE ISSUED BY THE COMPETENT AUTHORITY',
                        'doc_deo' => 'COPY OF THE DEO CERTIFICATE SUBMITTED BY THE SCHOOL FOR AFFILIATION/UPGRADATION/EXTENSION OF AFFILIATION OR SELF CERTIFICATION BY SCHOOL',
                        'doc_water' => 'COPIES OF VALID WATER, HEALTH AND SANITATION CERTIFICATES'
                    );
                    $i = 1;
                    foreach ($doc_list as $doc_key => $doc_title):
                        $filePath = isset($disclosure_data['documents'][$doc_key]['file_path']) ? $disclosure_data['documents'][$doc_key]['file_path'] : '';
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $doc_title; ?></td>
                        <td style="text-align: right;">
                            <?php if (!empty($filePath)): ?>
                                <a href="<?php echo base_url($filePath); ?>" target="_blank" class="btn-doc"><i class="fa fa-external-link"></i> View Document</a>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:12px;">Under Process</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- C: RESULT AND ACADEMICS -->
        <div class="disclosure-card">
            <div class="card-header-banner">
                <h3>C : RESULT AND ACADEMICS</h3>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th style="width: 8%;">SL NO.</th>
                        <th style="width: 62%;">DOCUMENTS/INFORMATION</th>
                        <th style="width: 30%; text-align: right;">LINKS OF UPLOADED DOCUMENTS ON YOUR SCHOOL WEBSITE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $acad_docs = array(
                        'doc_fee_structure' => 'FEE STRUCTURE OF THE SCHOOL',
                        'doc_academic_calendar' => 'ANNUAL ACADEMIC CALENDAR',
                        'doc_smc' => 'LIST OF SCHOOL MANAGEMENT COMMITTEE (SMC)',
                        'doc_pta' => 'LIST OF PARENTS TEACHERS ASSOCIATION (PTA) MEMBERS',
                        'doc_three_year_result' => 'LAST THREE-YEAR RESULT OF THE BOARD EXAMINATION AS PER APPLICABILITY'
                    );
                    $i = 1;
                    foreach ($acad_docs as $acad_key => $acad_title):
                        $filePath = isset($disclosure_data['results'][$acad_key]['file_path']) ? $disclosure_data['results'][$acad_key]['file_path'] : '';
                    ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $acad_title; ?></td>
                        <td style="text-align: right;">
                            <?php if (!empty($filePath)): ?>
                                <a href="<?php echo base_url($filePath); ?>" target="_blank" class="btn-doc"><i class="fa fa-external-link"></i> View Document</a>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:12px;">Under Process</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="padding: 16px 24px; background-color: #111827; border-top: 1px solid #374151; border-bottom: 1px solid #374151;">
                <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #60a5fa;">RESULT CLASS: X</h4>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>S.NO.</th>
                        <th>YEAR</th>
                        <th>NO. OF REGISTERED STUDENTS</th>
                        <th>NO. OF STUDENTS PASSED</th>
                        <th>PASS PERCENTAGE</th>
                        <th>REMARKS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><?php echo isset($disclosure_data['results']['x_year']['value']) ? htmlspecialchars($disclosure_data['results']['x_year']['value']) : '-'; ?></td>
                        <td><?php echo isset($disclosure_data['results']['x_registered']['value']) ? htmlspecialchars($disclosure_data['results']['x_registered']['value']) : '-'; ?></td>
                        <td><?php echo isset($disclosure_data['results']['x_passed']['value']) ? htmlspecialchars($disclosure_data['results']['x_passed']['value']) : '-'; ?></td>
                        <td><span class="badge-pass"><?php echo isset($disclosure_data['results']['x_pass_percentage']['value']) ? htmlspecialchars($disclosure_data['results']['x_pass_percentage']['value']) : '-'; ?></span></td>
                        <td><?php echo isset($disclosure_data['results']['x_remarks']['value']) ? htmlspecialchars($disclosure_data['results']['x_remarks']['value']) : '-'; ?></td>
                    </tr>
                </tbody>
            </table>

            <div style="padding: 16px 24px; background-color: #111827; border-top: 1px solid #374151; border-bottom: 1px solid #374151;">
                <h4 style="margin: 0; font-size: 14px; font-weight: 700; color: #60a5fa;">RESULT CLASS: XII</h4>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>S.NO.</th>
                        <th>YEAR</th>
                        <th>NO. OF REGISTERED STUDENTS</th>
                        <th>NO. OF STUDENTS PASSED</th>
                        <th>PASS PERCENTAGE</th>
                        <th>REMARKS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td><?php echo isset($disclosure_data['results']['xii_year']['value']) ? htmlspecialchars($disclosure_data['results']['xii_year']['value']) : '-'; ?></td>
                        <td><?php echo isset($disclosure_data['results']['xii_registered']['value']) ? htmlspecialchars($disclosure_data['results']['xii_registered']['value']) : '-'; ?></td>
                        <td><?php echo isset($disclosure_data['results']['xii_passed']['value']) ? htmlspecialchars($disclosure_data['results']['xii_passed']['value']) : '-'; ?></td>
                        <td><span class="badge-pass"><?php echo isset($disclosure_data['results']['xii_pass_percentage']['value']) ? htmlspecialchars($disclosure_data['results']['xii_pass_percentage']['value']) : '-'; ?></span></td>
                        <td><?php echo isset($disclosure_data['results']['xii_remarks']['value']) ? htmlspecialchars($disclosure_data['results']['xii_remarks']['value']) : '-'; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- D: STAFF (TEACHING) -->
        <div class="disclosure-card">
            <div class="card-header-banner">
                <h3>D : STAFF (TEACHING)</h3>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th style="width: 8%;">SL NO.</th>
                        <th style="width: 52%;">INFORMATION</th>
                        <th style="width: 40%;">DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>PRINCIPAL</td><td><?php echo isset($disclosure_data['staff']['principal_staff']['value']) ? htmlspecialchars($disclosure_data['staff']['principal_staff']['value']) : '1'; ?></td></tr>
                    <tr><td>2</td><td>TOTAL NO. OF TEACHERS</td><td><?php echo isset($disclosure_data['staff']['total_teachers']['value']) ? htmlspecialchars($disclosure_data['staff']['total_teachers']['value']) : '-'; ?></td></tr>
                    <tr><td></td><td>PGT</td><td><?php echo isset($disclosure_data['staff']['pgt_count']['value']) ? htmlspecialchars($disclosure_data['staff']['pgt_count']['value']) : '-'; ?></td></tr>
                    <tr><td></td><td>TGT</td><td><?php echo isset($disclosure_data['staff']['tgt_count']['value']) ? htmlspecialchars($disclosure_data['staff']['tgt_count']['value']) : '-'; ?></td></tr>
                    <tr><td></td><td>PRT</td><td><?php echo isset($disclosure_data['staff']['prt_count']['value']) ? htmlspecialchars($disclosure_data['staff']['prt_count']['value']) : '-'; ?></td></tr>
                    <tr><td>3</td><td>TEACHERS SECTION RATIO</td><td><?php echo isset($disclosure_data['staff']['teacher_section_ratio']['value']) ? htmlspecialchars($disclosure_data['staff']['teacher_section_ratio']['value']) : '1.5:1'; ?></td></tr>
                    <tr><td>4</td><td>DETAILS OF SPECIAL EDUCATOR</td><td><?php echo isset($disclosure_data['staff']['special_educator']['value']) ? htmlspecialchars($disclosure_data['staff']['special_educator']['value']) : '-'; ?></td></tr>
                    <tr><td>5</td><td>DETAILS OF COUNSELLOR AND WELLNESS TEACHER</td><td><?php echo isset($disclosure_data['staff']['counsellor_details']['value']) ? htmlspecialchars($disclosure_data['staff']['counsellor_details']['value']) : '-'; ?></td></tr>
                </tbody>
            </table>
        </div>

        <!-- E: SCHOOL INFRASTRUCTURE -->
        <div class="disclosure-card">
            <div class="card-header-banner">
                <h3>E : SCHOOL INFRASTRUCTURE</h3>
            </div>
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th style="width: 8%;">SL NO.</th>
                        <th style="width: 52%;">INFORMATION</th>
                        <th style="width: 40%;">DETAILS</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>1</td><td>TOTAL CAMPUS AREA OF THE SCHOOL (IN SQ MTR)</td><td><?php echo isset($disclosure_data['infrastructure']['campus_area']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['campus_area']['value']) : '-'; ?></td></tr>
                    <tr><td>2</td><td>NO. AND SIZE OF THE CLASS ROOMS (IN SQ MTR)</td><td><?php echo isset($disclosure_data['infrastructure']['classrooms_details']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['classrooms_details']['value']) : '-'; ?></td></tr>
                    <tr><td>3</td><td>NO. AND SIZE OF LABORATORIES INCLUDING COMPUTER LABS (IN SQ MTR)</td><td><?php echo isset($disclosure_data['infrastructure']['labs_details']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['labs_details']['value']) : '-'; ?></td></tr>
                    <tr><td>4</td><td>INTERNET FACILITY (Y/N)</td><td><?php echo isset($disclosure_data['infrastructure']['internet_facility']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['internet_facility']['value']) : 'Yes'; ?></td></tr>
                    <tr><td>5</td><td>NO. OF GIRLS TOILETS</td><td><?php echo isset($disclosure_data['infrastructure']['girls_toilets']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['girls_toilets']['value']) : '-'; ?></td></tr>
                    <tr><td>6</td><td>NO. OF BOYS TOILETS</td><td><?php echo isset($disclosure_data['infrastructure']['boys_toilets']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['boys_toilets']['value']) : '-'; ?></td></tr>
                    <tr>
                        <td>7</td>
                        <td>LINK OF YOUTUBE VIDEO OF THE INSPECTION OF SCHOOL COVERING THE INFRASTRUCTURE OF THE SCHOOL</td>
                        <td>
                            <?php if (!empty($disclosure_data['infrastructure']['youtube_inspection_video']['value'])): ?>
                                <a href="<?php echo htmlspecialchars($disclosure_data['infrastructure']['youtube_inspection_video']['value']); ?>" target="_blank" class="text-danger" style="font-weight:600;"><i class="fa fa-youtube-play"></i> Watch Inspection Video</a>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:12px;">Not Provided</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
