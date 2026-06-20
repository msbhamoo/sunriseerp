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
                        <h3 class="box-title"><i class="fa fa-history"></i> Substitution History</h3>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/substitution/history') ?>" method="post" class="">
                            <div class="row">
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('date'); ?></label>
                                        <input type="text" id="date" name="date" class="form-control date" value="<?php echo set_value('date', $search_date); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label>Teacher</label>
                                        <select id="staff_id" name="staff_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($staff_list as $staff) {
                                                $selected = ($staff['id'] == $search_staff_id) ? 'selected' : '';
                                                ?>
                                                <option value="<?php echo $staff['id'] ?>" <?php echo $selected; ?>><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label class="d-block">&nbsp;</label>
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Absent Teacher</th>
                                        <th>Substitute Teacher</th>
                                        <th>Class (Section)</th>
                                        <th>Subject</th>
                                        <th>Time</th>
                                        <th>Assigned By</th>
                                        <th>Conflict Override?</th>
                                        <th>Planned Leave?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($history as $h) { ?>
                                        <tr>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($h['date'])); ?></td>
                                            <td><?php echo $h['absent_name'] . " " . $h['absent_surname'] . " (" . $h['absent_emp_id'] . ")"; ?></td>
                                            <td><?php echo $h['sub_name'] . " " . $h['sub_surname'] . " (" . $h['sub_emp_id'] . ")"; ?></td>
                                            <td><?php echo $h['class'] . " (" . $h['section'] . ")"; ?></td>
                                            <td><?php echo $h['subject_name']; ?></td>
                                            <td><?php echo $h['time_from'] . " - " . $h['time_to']; ?></td>
                                            <td><?php echo $h['admin_name'] . " " . $h['admin_surname'] . " (" . $h['admin_emp_id'] . ")"; ?></td>
                                            <td>
                                                <?php if($h['override_conflict_timetable_id']) { ?>
                                                    <span class="label label-warning">Yes (ID: <?php echo $h['override_conflict_timetable_id']; ?>)</span>
                                                <?php } else { ?>
                                                    <span class="label label-success">No Conflict</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if($h['is_unplanned'] == 0) { ?>
                                                    <span class="label label-success">Yes</span>
                                                <?php } else { ?>
                                                    <span class="label label-danger">Unplanned</span>
                                                <?php } ?>
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
            "aaSorting": []
        });
    });
</script>
