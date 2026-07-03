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
        <h1><i class="fa fa-users"></i> Invigilator Duty Chart</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Duty Chart: <?php echo $allocation['exam_name']; ?> (<?php echo $this->customlib->dateformat($allocation['exam_date']); ?>)</h3>
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
                                <th>S.No</th>
                                <th>Staff Name (Emp ID)</th>
                                <th>Building / Block</th>
                                <th>Room Assigned</th>
                                <th>Students in Room</th>
                                <th>Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sno = 1;
                            foreach ($rooms as $room) {
                                if (empty($room['invigilators'])) continue;
                                foreach ($room['invigilators'] as $inv) {
                            ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><strong><?php echo $inv['staff_name'] . ' ' . $inv['staff_surname'] . ' (' . $inv['employee_id'] . ')'; ?></strong></td>
                                    <td><?php echo $room['building_name']; ?></td>
                                    <td><?php echo $room['room_number']; ?></td>
                                    <td><?php echo count($room['students']); ?></td>
                                    <td style="width: 150px;"></td>
                                </tr>
                            <?php 
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
