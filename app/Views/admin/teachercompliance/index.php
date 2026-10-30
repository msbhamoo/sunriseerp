<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('admin/teachercompliance') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small>
                                        <input id="date" name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', $date); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4" style="padding-top: 25px;">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <?php if (isset($resultlist)) { ?>
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-list"></i> Teacher Compliance Report</h3>
                            <div class="box-tools pull-right">
                                <?php if($is_past_cutoff): ?>
                                    <span class="label label-danger">Past Cutoff (<?php echo $cutoff_time_display; ?>)</span>
                                <?php else: ?>
                                    <span class="label label-warning">Before Cutoff (<?php echo $cutoff_time_display; ?>)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('section'); ?></th>
                                            <th>Class Teacher</th>
                                            <th>Contact No</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($resultlist)) {
                                            foreach ($resultlist as $value) {
                                                $submitted = ($value['attendance_count'] > 0);
                                                ?>
                                                <tr>
                                                    <td><?php echo $value['class']; ?></td>
                                                    <td><?php echo $value['section']; ?></td>
                                                    <td>
                                                        <?php 
                                                            if (!empty($value['staff_name'])) {
                                                                echo $value['staff_name'] . ' ' . $value['staff_surname']; 
                                                            } else {
                                                                echo "<span class='text-muted'>Not Assigned</span>";
                                                            }
                                                        ?>
                                                    </td>
                                                    <td><?php echo $value['contact_no']; ?></td>
                                                    <td>
                                                        <?php if ($submitted) { ?>
                                                            <span class="label label-success">Submitted</span>
                                                        <?php } else { ?>
                                                            <?php if ($is_past_cutoff) { ?>
                                                                <span class="label label-danger">Non-Compliant</span>
                                                            <?php } else { ?>
                                                                <span class="label label-warning">Pending</span>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            endDate: '+0d',
            todayHighlight: true
        });
    });
</script>
