<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-exchange"></i> Room Transfer Logs</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Action</th>
                                        <th>From Room / Bed</th>
                                        <th>To Room / Bed</th>
                                        <th>Transfer Date</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (!empty($transfer_logs)) {
                                        foreach ($transfer_logs as $log) {
                                            $action = "Unknown";
                                            $from = "-";
                                            $to = "-";
                                            
                                            if ($log['from_room_id'] == NULL) {
                                                $action = '<span class="label label-success">Assigned</span>';
                                                $to = $log['to_hostel'] . ' - Rm: ' . $log['to_room'] . ' (Bed: ' . $log['to_bed_no'] . ')';
                                            } else if ($log['to_room_id'] == NULL) {
                                                $action = '<span class="label label-danger">Unassigned</span>';
                                                $from = $log['from_hostel'] . ' - Rm: ' . $log['from_room'] . ' (Bed: ' . $log['from_bed_no'] . ')';
                                            } else {
                                                $action = '<span class="label label-warning">Transferred</span>';
                                                $from = $log['from_hostel'] . ' - Rm: ' . $log['from_room'] . ' (Bed: ' . $log['from_bed_no'] . ')';
                                                $to = $log['to_hostel'] . ' - Rm: ' . $log['to_room'] . ' (Bed: ' . $log['to_bed_no'] . ')';
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $log['firstname'] . ' ' . $log['lastname']; ?></td>
                                                <td><?php echo $log['admission_no']; ?></td>
                                                <td><?php echo $action; ?></td>
                                                <td><?php echo $from; ?></td>
                                                <td><?php echo $to; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($log['transfer_date'])); ?></td>
                                                <td><?php echo $log['reason']; ?></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
