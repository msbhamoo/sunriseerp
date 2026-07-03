<?php $this->load->view('layout/cbseexam_css.php'); ?>
<style>
    @media print {
        .content-wrapper { margin-left: 0 !important; }
        .main-header, .main-sidebar, .box-header, .main-footer { display: none !important; }
        .box { border: none !important; box-shadow: none !important; }
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bar-chart"></i> Summary Report</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Class-wise Summary: <?php echo $allocation['exam_name']; ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                    <a href="<?php echo site_url('cbseexam/seatingreport') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Class (Section)</th>
                                <th>Total Students Allocated</th>
                                <th>Rooms Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($summary as $class_name => $data) { ?>
                                <tr>
                                    <td><strong><?php echo $class_name; ?></strong></td>
                                    <td><?php echo $data['count']; ?></td>
                                    <td><?php echo implode(', ', $data['rooms']); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total Classes/Sections: <?php echo count($summary); ?></th>
                                <th>Total Students: <?php echo $allocation['total_students_allocated']; ?></th>
                                <th>Total Rooms Used: <?php echo $allocation['total_rooms_used']; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
