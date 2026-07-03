<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-print"></i> Seating Reports</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Generate Reports for Allocations</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped example">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Date</th>
                                        <th>Strategy</th>
                                        <th>Status</th>
                                        <th class="text-right">Generate Reports</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allocations as $alloc) { ?>
                                        <tr>
                                            <td><?php echo $alloc['exam_name']; ?></td>
                                            <td><?php echo $this->customlib->dateformat($alloc['exam_date']); ?></td>
                                            <td><?php echo ucfirst($alloc['allocation_strategy']); ?></td>
                                            <td>
                                                <?php if($alloc['status'] == 'draft') { ?>
                                                    <span class="label label-warning">Draft</span>
                                                <?php } else { ?>
                                                    <span class="label label-success"><?php echo ucfirst($alloc['status']); ?></span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo site_url('cbseexam/seatingreport/roomwise/'.$alloc['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Room-wise Seating Plan"><i class="fa fa-th"></i></a>
                                                <a href="<?php echo site_url('cbseexam/seatingreport/studentwise/'.$alloc['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Student-wise Slips"><i class="fa fa-user"></i></a>
                                                <a href="<?php echo site_url('cbseexam/seatingreport/invigilator_duty/'.$alloc['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Invigilator Duty Chart"><i class="fa fa-users"></i></a>
                                                <a href="<?php echo site_url('cbseexam/seatingreport/attendance_sheet/'.$alloc['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Attendance Sheet"><i class="fa fa-check-square-o"></i></a>
                                                <a href="<?php echo site_url('cbseexam/seatingreport/summary_report/'.$alloc['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Summary Report"><i class="fa fa-bar-chart"></i></a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $(document).ready(function() {
        $('.example').DataTable({
            "aaSorting": [],
            "bSort": true,
            "bPaginate": true,
            "bInfo": true,
            "bFilter": true
        });
    });
</script>
