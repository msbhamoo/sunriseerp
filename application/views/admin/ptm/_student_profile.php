<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success btn-sm print_report" onclick="printDiv('print_ptm_report')" style="margin-bottom: 15px;"><i class="fa fa-print"></i> Print</button>
    </div>
</div>

<div id="print_ptm_report" style="background:#fff; padding:20px; border:1px solid #ddd; border-radius:5px;">
    <!-- School Header -->
    <div class="row" style="border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:20px;">
        <div class="col-xs-3 col-sm-2 text-center">
            <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="logo" style="max-height:80px; max-width:100%;">
        </div>
        <div class="col-xs-9 col-sm-10 text-center">
            <h3 style="margin-top:0; margin-bottom:5px; font-weight:bold; color:#333;"><?php echo $settinglist[0]['name']; ?></h3>
            <p style="margin:0; font-size:12px; color:#555;">
                <?php echo $settinglist[0]['address']; ?><br>
                Email: <?php echo $settinglist[0]['email']; ?> | Mobile No: <?php echo $settinglist[0]['phone']; ?> | Website: <?php echo $settinglist[0]['dise_code']; /* Using as placeholder if no website */ ?><br>
                <strong>Session: <?php echo $settinglist[0]['session']; ?></strong>
            </p>
            <h4 style="color:#007bff; font-weight:bold; margin-top:10px;">PTM Report</h4>
        </div>
    </div>

    <!-- Student Details -->
    <div class="row" style="margin-bottom:20px;">
        <div class="col-sm-10 col-xs-9">
            <table style="width:100%; font-size:12px;">
                <tr>
                    <td width="20%"><strong>Student Name:</strong></td><td width="30%"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                    <td width="20%"><strong>Roll No.:</strong></td><td width="30%"><?php echo $student['roll_no']; ?></td>
                </tr>
                <tr>
                    <td><strong>Father's Name:</strong></td><td><?php echo $student['father_name']; ?></td>
                    <td><strong>Class & Section:</strong></td><td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                </tr>
                <tr>
                    <td><strong>Mother's Name:</strong></td><td><?php echo $student['mother_name']; ?></td>
                    <td><strong>Date of Birth:</strong></td><td><?php echo !empty($student['dob']) ? date('M d, Y', strtotime($student['dob'])) : ''; ?></td>
                </tr>
                <tr>
                    <td><strong>Gender:</strong></td><td><?php echo $student['gender']; ?></td>
                    <td><strong>Address:</strong></td><td><?php echo $student['current_address']; ?></td>
                </tr>
                <tr>
                    <td><strong>Sr No.:</strong></td><td><?php echo $student['admission_no']; ?></td>
                    <td><strong>Telephone No.:</strong></td><td><?php echo $student['mobileno']; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-2 col-xs-3 text-right">
            <?php 
                $image = $student['image'] ? $student['image'] : 'uploads/student_images/no_image.png';
            ?>
            <img src="<?php echo base_url($image); ?>" alt="student" style="max-height:100px; max-width:100px; border-radius:50%; border:2px solid #ddd;">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Fee Summary -->
            <h5 style="font-weight:bold; margin-top:20px;">Fee Summary (Total)</h5>
            <table class="table table-bordered table-striped" style="font-size:12px;">
                <thead style="background-color:#4CAF50; color:white;">
                    <tr>
                        <th>TYPE</th>
                        <th>TOTAL FEES</th>
                        <th>COLLECTED</th>
                        <th>DUE FEE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $total_fee = 0; $total_paid = 0;
                        if (!empty($fees)) {
                            foreach ($fees as $fee_group) {
                                if (!empty($fee_group->fees)) {
                                    foreach ($fee_group->fees as $fee) {
                                        $total_fee += $fee->amount;
                                        $paid = 0;
                                        if (is_string($fee->amount_detail)) {
                                            $amount_detail = json_decode($fee->amount_detail);
                                            if (is_object($amount_detail) || is_array($amount_detail)) {
                                                foreach ($amount_detail as $ad) {
                                                    $paid += $ad->amount;
                                                }
                                            }
                                        }
                                        $total_paid += $paid;
                    ?>
                    <tr>
                        <td><?php echo $fee->name ?? 'Fee'; ?> (<?php echo $fee->type ?? ''; ?>)</td>
                        <td><?php echo $settinglist[0]['currency_symbol'] . $fee->amount; ?></td>
                        <td><?php echo $settinglist[0]['currency_symbol'] . $paid; ?></td>
                        <td><?php echo $settinglist[0]['currency_symbol'] . ($fee->amount - $paid); ?></td>
                    </tr>
                    <?php 
                                    }
                                }
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No fee data available.</td></tr>";
                        }
                    ?>
                    <tr style="font-weight:bold; background-color:#f9f9f9;">
                        <td>TOTAL</td>
                        <td><?php echo $settinglist[0]['currency_symbol'] . number_format($total_fee, 2); ?></td>
                        <td><?php echo $settinglist[0]['currency_symbol'] . number_format($total_paid, 2); ?></td>
                        <td><?php echo $settinglist[0]['currency_symbol'] . number_format($total_fee - $total_paid, 2); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="col-md-6">
            <!-- CBSE Exam Summary -->
            <h5 style="font-weight:bold; margin-top:20px;">CBSE Exam Details</h5>
            <table class="table table-bordered table-striped" style="font-size:12px;">
                <thead style="background-color:#4CAF50; color:white;">
                    <tr>
                        <th>EXAM NAME</th>
                        <th>TOTAL MARKS</th>
                        <th>OBTAINED MARKS</th>
                        <th>PERCENTAGE / GRADE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if (!empty($cbse_exams)) {
                            foreach ($cbse_exams as $exam) {
                    ?>
                    <tr>
                        <td><?php echo isset($exam['exam_name']) ? $exam['exam_name'] : (isset($exam['name']) ? $exam['name'] : '-'); ?></td>
                        <td><?php echo isset($exam['total_marks']) ? $exam['total_marks'] : '-'; ?></td>
                        <td><?php echo isset($exam['obtained_marks']) ? $exam['obtained_marks'] : '-'; ?></td>
                        <td><?php echo isset($exam['percentage']) ? $exam['percentage'].'%' : '-'; ?></td>
                    </tr>
                    <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'><i>No Exam data available.</i></td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight:bold; margin-top:20px;">Current PTM Discussion & Remarks</h5>
            <table class="table table-bordered" style="font-size:12px;">
                <tbody>
                    <?php if ($ptm_remarks): ?>
                    <tr>
                        <th width="20%">Status</th>
                        <td><?php echo ucfirst($ptm_remarks['status']); ?></td>
                        <th width="20%">Attendee</th>
                        <td><?php echo ucfirst($ptm_remarks['attendee_type']); ?></td>
                    </tr>
                    <tr>
                        <th>Discussion Points</th>
                        <td colspan="3"><?php echo nl2br(htmlspecialchars($ptm_remarks['discussion_points'])); ?></td>
                    </tr>
                    <tr>
                        <th>Parent Remarks</th>
                        <td><?php echo nl2br(htmlspecialchars($ptm_remarks['parent_remarks'])); ?></td>
                        <th>Teacher Remarks</th>
                        <td><?php echo nl2br(htmlspecialchars($ptm_remarks['teacher_remarks'])); ?></td>
                    </tr>
                    <tr>
                        <th>Concerns</th>
                        <td colspan="3">
                            <?php 
                                $concerns = [];
                                if (!empty($ptm_remarks['concerns_academics'])) $concerns[] = "Academics";
                                if (!empty($ptm_remarks['concerns_attendance'])) $concerns[] = "Attendance";
                                if (!empty($ptm_remarks['concerns_behavior'])) $concerns[] = "Behavior/Discipline";
                                echo !empty($concerns) ? implode(', ', $concerns) : '-';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Action Items</th>
                        <td colspan="3"><?php echo nl2br(htmlspecialchars($ptm_remarks['agreed_action_items'])); ?></td>
                    </tr>
                    <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">No remarks entered for this PTM yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if (!empty($previous_ptm_remarks)): ?>
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight:bold; margin-top:10px; color:#666;">Previous PTM History</h5>
            <?php foreach ($previous_ptm_remarks as $prev): ?>
            <div style="background:#f9f9f9; padding:10px; border:1px solid #eee; margin-bottom:10px; font-size:12px;">
                <strong><?php echo $prev['title']; ?> (<?php echo date('M d, Y', strtotime($prev['ptm_date'])); ?>)</strong><br>
                <strong>Discussion:</strong> <?php echo htmlspecialchars($prev['discussion_points'] ?: '-'); ?><br>
                <strong>Action Items:</strong> <?php echo htmlspecialchars($prev['agreed_action_items'] ?: '-'); ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function printDiv(divId) {
    var content = document.getElementById(divId).innerHTML;
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print PTM Report</title>');
    printWindow.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">');
    printWindow.document.write('<style>body { padding: 20px; font-family: Arial, sans-serif; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Allow styles to load before printing
    setTimeout(function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
