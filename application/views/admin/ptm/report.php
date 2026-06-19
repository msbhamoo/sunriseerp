<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-pie-chart"></i> <?php echo $this->lang->line('ptm_reports'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/ptmreports') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('select_ptm'); ?></label><small class="req"> *</small>
                                        <select class="form-control" name="ptm_id" required>
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($ptm_list as $ptm_option) { ?>
                                                <option value="<?php echo $ptm_option['id']; ?>" <?php echo set_select('ptm_id', $ptm_option['id'], (isset($selected_ptm) && $selected_ptm == $ptm_option['id'])); ?>><?php echo $ptm_option['title'] . ' (' . date('d-m-Y', strtotime($ptm_option['ptm_date'])) . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="display:block;">&nbsp;</label>
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($students)) { 
            // Calculate Stats
            $total = count($students);
            $present = 0;
            foreach ($students as $student) {
                if (isset($attendances[$student['student_session_id']]) && $attendances[$student['student_session_id']]['status'] == 'present') {
                    $present++;
                }
            }
            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('ptm_attendance_report'); ?></h3>
                            <div class="pull-right">
                                <span class="label label-success"><?php echo $this->lang->line('total_present'); ?>: <?php echo $present; ?></span>
                                <span class="label label-danger"><?php echo $this->lang->line('total_absent'); ?>: <?php echo $total - $present; ?></span>
                                <span class="label label-info"><?php echo $this->lang->line('participation'); ?>: <?php echo $percentage; ?>%</span>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('attendee'); ?></th>
                                            <th><?php echo $this->lang->line('discussion_points'); ?></th>
                                            <th><?php echo $this->lang->line('action_items'); ?></th>
                                            <th><?php echo $this->lang->line('follow_up'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student) { 
                                            $att = isset($attendances[$student['student_session_id']]) ? $attendances[$student['student_session_id']] : [];
                                        ?>
                                            <tr>
                                                <td><?php echo $student['admission_no']; ?></td>
                                                <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                <td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                                <td>
                                                    <?php 
                                                        if (isset($att['status']) && $att['status'] == 'present') {
                                                            echo "<span class='label label-success'>Present</span>";
                                                        } else {
                                                            echo "<span class='label label-danger'>Absent</span>";
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo isset($att['attendee_type']) ? ucfirst($att['attendee_type']) : '-'; ?></td>
                                                <td><?php echo isset($att['discussion_points']) ? nl2br(htmlspecialchars($att['discussion_points'])) : ''; ?></td>
                                                <td><?php echo isset($att['action_items']) ? nl2br(htmlspecialchars($att['action_items'])) : ''; ?></td>
                                                <td>
                                                    <?php 
                                                        if (isset($att['followup_required']) && $att['followup_required'] == 1) {
                                                            echo "<span class='label label-warning'>Required</span><br>";
                                                            if (!empty($att['followup_date'])) {
                                                                echo "<small>Date: " . date('d-m-Y', strtotime($att['followup_date'])) . "</small>";
                                                            }
                                                        } else {
                                                            echo "-";
                                                        }
                                                    ?>
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
        <?php } ?>
    </section>
</div>
