<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> 
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar-check-o"></i> Today's Schedule (<?php echo date($this->customlib->getSchoolDateFormat()) . ' - ' . $day; ?>)</h3>
                    </div>
                    <div class="box-body">
                        <?php foreach ($schedule as $class_name => $timetable) { 
                            if (!empty($timetable)) {
                        ?>
                            <h4><i class="fa fa-th"></i> <?php echo $class_name; ?></h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Time</th>
                                            <th>Teacher</th>
                                            <th>Room No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($timetable as $t) { ?>
                                            <tr class="<?php echo ($t->is_absent_uncovered) ? 'danger' : (($t->is_substituted) ? 'warning' : ''); ?>">
                                                <td>
                                                    <?php if (isset($t->period_type) && $t->period_type == 'break') { ?>
                                                        <span class="label label-default" style="font-size: 13px;"><i class="fa fa-coffee"></i> <?php echo $t->break_label; ?></span>
                                                    <?php } else { ?>
                                                        <?php echo $t->subject_name != '' ? $t->subject_name : '<span class="text-danger">'.$this->lang->line('not_scheduled').'</span>'; ?>
                                                        <?php if ($t->code != '') { echo " (" . $t->code . ")"; } ?>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $t->time_from . " - " . $t->time_to; ?></td>
                                                <td>
                                                    <?php if (isset($t->period_type) && $t->period_type == 'break') { ?>
                                                        -
                                                    <?php } else if ($t->is_substituted) { ?>
                                                        <span class="label label-warning" title="Substitute">Sub:</span> <?php echo $t->name . " " . $t->surname . " (" . $t->employee_id . ")"; ?>
                                                    <?php } else if ($t->is_absent_uncovered) { ?>
                                                        <span class="label label-danger">Absent (No Sub)</span> <?php echo $t->name . " " . $t->surname . " (" . $t->employee_id . ")"; ?>
                                                    <?php } else { ?>
                                                        <?php echo $t->name != '' ? $t->name . " " . $t->surname . " (" . $t->employee_id . ")" : '<span class="text-danger">'.$this->lang->line('not_scheduled').'</span>'; ?>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo (isset($t->period_type) && $t->period_type == 'break') ? '-' : $t->room_no; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <hr/>
                        <?php } 
                        } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
