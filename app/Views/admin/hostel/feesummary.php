<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
    }
    .d2-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .d2-title {
        font-size: 12px;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    .d2-box {
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #f0f0f0;
    }
    .d2-box-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .d2-box-val {
        font-size: 22px;
        font-weight: 700;
    }
    .d2-box-sub {
        font-size: 11px;
        color: #888;
    }
    .d2-box.students { background: #fdfaf6; border-color: #f7eedf; }
    .d2-box.students .d2-box-title { color: #d68940; }
    
    .d2-box.staff { background: #fdfaff; border-color: #f4e8fb; }
    .d2-box.staff .d2-box-title { color: #9d50ce; }

    .d2-box.attendance { background: #f6fffa; border-color: #dcf2e6; }
    .d2-box.attendance .d2-box-title { color: #3b9b65; }

    .d2-box.feerecovery { background: #fff5f8; border-color: #fbe0e8; }
    .d2-box.feerecovery .d2-box-title { color: #d8456a; }
    
    .d2-box.pending { background: #fffcf5; border-color: #fbedcf; }
    .d2-box.pending .d2-box-title { color: #d09435; }
</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-12">
                <h1 style="margin:0; font-size: 24px; font-weight:700;">Hostel Fee Summary</h1>
                <small style="color:#888;">Dashboard / Hostel / Fee Summary</small>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    <div class="d2-title">Overview</div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d2-box students">
                                <div class="d2-box-title">Total Hostel Fee</div>
                                <div class="d2-box-val">₹<?php echo number_format($total_hostel_fee, 2); ?></div>
                                <div class="d2-box-sub">Assigned Fee</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d2-box attendance">
                                <div class="d2-box-title">Total Collected</div>
                                <div class="d2-box-val">₹<?php echo number_format($total_collected, 2); ?></div>
                                <div class="d2-box-sub">Received Amount</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d2-box feerecovery">
                                <div class="d2-box-title">Total Pending</div>
                                <div class="d2-box-val">₹<?php echo number_format($total_pending, 2); ?></div>
                                <div class="d2-box-sub">Outstanding Balance</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    <div class="d2-title">Student Fee Details</div>
                    <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Hostel & Room</th>
                                        <th>Bed No</th>
                                        <th>Total Fee (₹)</th>
                                        <th>Collected (₹)</th>
                                        <th>Pending (₹)</th>
                                        <th class="text-right noExport">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($students)) {
                                        foreach ($students as $student) { ?>
                                            <tr>
                                                <td>
                                                    <a href="<?php echo base_url(); ?>student/view/<?php echo $student['student_id']; ?>">
                                                        <?php echo $student['firstname'] . ' ' . $student['lastname']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $student['admission_no']; ?></td>
                                                <td><?php echo $student['hostel_name'] . ' - ' . $student['room_no']; ?></td>
                                                <td><?php echo $student['hostel_bed_no']; ?></td>
                                                <td><?php echo number_format($student['total_fee'], 2); ?></td>
                                                <td class="text-success"><?php echo number_format($student['collected'], 2); ?></td>
                                                <td class="<?php echo $student['pending'] > 0 ? 'text-danger' : ''; ?>"><?php echo number_format($student['pending'], 2); ?></td>
                                                <td class="text-right">
                                                    <?php if ($student['collected'] > 0) { ?>
                                                        <button type="button" class="btn btn-default btn-xs" 
                                                                onclick="printSummary('<?php echo $student['student_session_id']; ?>')"
                                                                data-toggle="tooltip" title="Print Fee Summary">
                                                            <i class="fa fa-print"></i> Print
                                                        </button>
                                                    <?php } else { ?>
                                                        <span class="text-muted">Unpaid</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function printSummary(student_session_id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/hostelfeesummary/print_summary/' + student_session_id,
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.status === 1) {
                    var newWin = window.open('', 'Print-Window');
                    newWin.document.open();
                    newWin.document.write('<html><head><title>Hostel Fee Summary</title>');
                    newWin.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">');
                    newWin.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/AdminLTE.min.css">');
                    newWin.document.write('</head><body onload="window.print()">');
                    newWin.document.write(data.page);
                    newWin.document.write('</body></html>');
                    newWin.document.close();
                    setTimeout(function () { newWin.close(); }, 10);
                }
            }
        });
    }
</script>
