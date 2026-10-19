<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-certificate"></i> Merit & Award Certificates</h1>
    </section>

    <section class="content">
        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Select Exam for Merit Certificate Generation</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo site_url('admin/scholarshipexam/certificates'); ?>" method="GET" class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Exam *</label>
                            <select name="exam_id" class="form-control" onchange="this.form.submit()">
                                <?php if (!empty($exams)) {
                                    foreach ($exams as $e) { ?>
                                        <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>)
                                        </option>
                                    <?php }
                                } else { ?>
                                    <option value="">No Exams Available</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($candidates)) {
            $exam_title = (isset($selected_exam) && isset($selected_exam['title'])) ? $selected_exam['title'] : 'Scholarship & Olympiad Exam';
            ?>
            <div class="box box-info" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-award"></i> Certificate Recipients</h3>
                </div>
                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th>Roll No</th>
                                <th>Candidate Name</th>
                                <th>Class</th>
                                <th>Marks Obtained</th>
                                <th>Merit Rank</th>
                                <th>Result</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates as $cand) { ?>
                                <tr>
                                    <td><strong class="text-primary"><?php echo htmlspecialchars($cand['roll_no']); ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($cand['firstname'] . ' ' . $cand['lastname']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($cand['class'] . ' - ' . $cand['section']); ?></td>
                                    <td><?php echo ($cand['marks_obtained'] !== null) ? $cand['marks_obtained'] : '-'; ?></td>
                                    <td>
                                        <?php if ($cand['rank']) { ?>
                                            <span class="label label-warning" style="font-size:12px;">Rank <?php echo $cand['rank']; ?></span>
                                        <?php } else { ?>
                                            <span class="text-muted">-</span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if ($cand['result_status'] == 'merit_holder') { ?>
                                            <span class="label label-success">MERIT HOLDER</span>
                                        <?php } else { ?>
                                            <span class="label label-default"><?php echo strtoupper($cand['result_status']); ?></span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('admin/scholarshipexam/print_certificate/' . $cand['id']); ?>" target="_blank" class="btn btn-xs btn-primary" style="border-radius: 4px;"><i class="fa fa-print"></i> Generate & Print Certificate</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } elseif ($selected_exam_id) { ?>
            <div class="alert alert-info">No candidates registered for this exam yet. Enrolled students will appear here for certificate printing.</div>
        <?php } ?>
    </section>
</div>

<!-- Modal Certificate Preview -->
<div class="modal fade" id="certModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background: #1e293b; color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-certificate"></i> Merit Certificate Preview</h4>
            </div>
            <div class="modal-body" id="certBody" style="background: #f8fafc; padding: 40px;">
                <!-- Certificate HTML Rendered via JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="printCertificate()"><i class="fa fa-print"></i> Print Certificate</button>
            </div>
        </div>
    </div>
</div>

<script>
function previewCertificate(studentName, examTitle, rankText, className) {
    var schoolName = "<?php echo isset($sch_setting[0]['name']) ? htmlspecialchars(addslashes($sch_setting[0]['name'])) : 'SUNRISE INTERNATIONAL PUBLIC SCHOOL'; ?>";
    var html = `
        <div id="printCertArea" style="border: 10px double #d97706; padding: 40px; background: #ffffff; text-align: center; font-family: 'Georgia', serif; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); position: relative;">
            <div style="font-size: 28px; font-weight: bold; color: #1e3a8a; text-transform: uppercase; letter-spacing: 2px;">${schoolName}</div>
            <div style="font-size: 16px; color: #64748b; margin-top: 5px;">CERTIFICATE OF MERIT & EXCELLENCE</div>
            <hr style="border-top: 2px solid #f59e0b; width: 40%; margin: 20px auto;">
            
            <p style="font-size: 16px; color: #475569; margin-top: 30px;">This certificate is proudly awarded to</p>
            <div style="font-size: 32px; font-weight: bold; color: #b45309; text-decoration: underline; margin: 15px 0;">${studentName}</div>
            <p style="font-size: 16px; color: #475569;">Class ${className} for outstanding performance in</p>
            <div style="font-size: 22px; font-weight: bold; color: #1e293b; margin: 10px 0;">${examTitle}</div>
            <p style="font-size: 16px; color: #475569;">Achieving distinction position of <strong style="color: #d97706; font-size: 20px;">${rankText}</strong></p>

            <div style="margin-top: 60px; display: flex; justify-content: space-between; align-items: flex-end; padding: 0 40px;">
                <div style="border-top: 1px solid #94a3b8; width: 180px; padding-top: 5px; font-size: 12px; color: #64748b;">Event Coordinator</div>
                <div style="font-size: 30px; color: #d97706;"><i class="fa fa-award"></i></div>
                <div style="border-top: 1px solid #94a3b8; width: 180px; padding-top: 5px; font-size: 12px; color: #64748b;">Principal Signature</div>
            </div>
        </div>
    `;
    $('#certBody').html(html);
    $('#certModal').modal('show');
}

function printCertificate() {
    var printContents = document.getElementById('printCertArea').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script>
