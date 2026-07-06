<style type="text/css">
    @media print {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 { width: 100%; }
        .col-sm-11 { width: 91.66666667%; }
        .col-sm-10 { width: 83.33333333%; }
        .col-sm-9 { width: 75%; }
        .col-sm-8 { width: 66.66666667%; }
        .col-sm-7 { width: 58.33333333%; }
        .col-sm-6 { width: 50%; }
        .col-sm-5 { width: 41.66666667%; }
        .col-sm-4 { width: 33.33333333%; }
        .col-sm-3 { width: 25%; }
        .col-sm-2 { width: 16.66666667%; }
        .col-sm-1 { width: 8.33333333%; }
        .col-sm-pull-12 { right: 100%; }
        .col-sm-pull-11 { right: 91.66666667%; }
        .col-sm-pull-10 { right: 83.33333333%; }
        .col-sm-pull-9 { right: 75%; }
        .col-sm-pull-8 { right: 66.66666667%; }
        .col-sm-pull-7 { right: 58.33333333%; }
        .col-sm-pull-6 { right: 50%; }
        .col-sm-pull-5 { right: 41.66666667%; }
        .col-sm-pull-4 { right: 33.33333333%; }
        .col-sm-pull-3 { right: 25%; }
        .col-sm-pull-2 { right: 16.66666667%; }
        .col-sm-pull-1 { right: 8.33333333%; }
        .col-sm-pull-0 { right: auto; }
        .col-sm-push-12 { left: 100%; }
        .col-sm-push-11 { left: 91.66666667%; }
        .col-sm-push-10 { left: 83.33333333%; }
        .col-sm-push-9 { left: 75%; }
        .col-sm-push-8 { left: 66.66666667%; }
        .col-sm-push-7 { left: 58.33333333%; }
        .col-sm-push-6 { left: 50%; }
        .col-sm-push-5 { left: 41.66666667%; }
        .col-sm-push-4 { left: 33.33333333%; }
        .col-sm-push-3 { left: 25%; }
        .col-sm-push-2 { left: 16.66666667%; }
        .col-sm-push-1 { left: 8.33333333%; }
        .col-sm-push-0 { left: auto; }
        .col-sm-offset-12 { margin-left: 100%; }
        .col-sm-offset-11 { margin-left: 91.66666667%; }
        .col-sm-offset-10 { margin-left: 83.33333333%; }
        .col-sm-offset-9 { margin-left: 75%; }
        .col-sm-offset-8 { margin-left: 66.66666667%; }
        .col-sm-offset-7 { margin-left: 58.33333333%; }
        .col-sm-offset-6 { margin-left: 50%; }
        .col-sm-offset-5 { margin-left: 41.66666667%; }
        .col-sm-offset-4 { margin-left: 33.33333333%; }
        .col-sm-offset-3 { margin-left: 25%; }
        .col-sm-offset-2 { margin-left: 16.66666667%; }
        .col-sm-offset-1 { margin-left: 8.33333333%; }
        .col-sm-offset-0 { margin-left: 0%; }
        .visible-xs { display: none !important; }
        .hidden-xs { display: block !important; }
        table.hidden-xs { display: table; }
        tr.hidden-xs { display: table-row !important; }
        th.hidden-xs, td.hidden-xs { display: table-cell !important; }
        .hidden-xs.hidden-print { display: none !important; }
        .hidden-sm { display: none !important; }
        .visible-sm { display: block !important; }
        table.visible-sm { display: table; }
        tr.visible-sm { display: table-row !important; }
        th.visible-sm, td.visible-sm { display: table-cell !important; }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('reports'); ?></h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Installment Due Report</h3>
                    </div>
                    
                    <form role="form" action="<?php echo site_url('admin/installmentreport') ?>" method="post">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected" ?>><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                        <select id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-md-4">
                                    <div class="form-group">
                                        <label>Installment Plan</label>
                                        <select id="installment_plan_id" name="installment_plan_id" class="form-control" >
                                            <option value="">All</option>
                                            <?php
                                            foreach ($installment_plans as $plan) {
                                                $type = $plan['is_global'] ? 'Global' : 'Per Class';
                                                ?>
                                                <option value="<?php echo $plan['id'] ?>" <?php if (set_value('installment_plan_id') == $plan['id']) { echo "selected=selected"; } ?>><?php echo $plan['name'] . ' (' . $type . ')'; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
            
            <?php if (isset($students_list)) { ?>
            <div class="col-md-12">
                <div class="box box-info" id="report">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i> Student Installment Due List</h3>
                        <div class="box-tools pull-right">
                            <button id="print" onclick="printDiv('report')" class="btn btn-default btn-xs" title="<?php echo $this->lang->line('print'); ?>"><i class="fa fa-print"></i></button>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="download_label">Student Installment Due List</div>
                        <table class="table table-striped table-bordered table-hover example">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('student_name'); ?></th>
                                    <th><?php echo $this->lang->line('admission_no'); ?></th>
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <th><?php echo $this->lang->line('father_name'); ?></th>
                                    <th>Plan Name</th>
                                    <th>Overdue Installments</th>
                                    <th class="text-right">Total Overdue (<?php echo $currency_symbol; ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $grand_total = 0;
                                if (!empty($students_list)) { 
                                    foreach ($students_list as $student) {
                                        $grand_total += $student['total_overdue'];
                                ?>
                                <tr>
                                    <td><?php echo $student['name']; ?></td>
                                    <td><?php echo $student['admission_no']; ?></td>
                                    <td><?php echo $student['class_section']; ?></td>
                                    <td><?php echo $student['father_name']; ?></td>
                                    <td><?php echo $student['plan_name']; ?></td>
                                    <td>
                                        <ul style="list-style: none; padding-left: 0;">
                                        <?php foreach ($student['overdue_details'] as $od) { ?>
                                            <li>
                                                <b>Inst <?php echo $od['installment_number']; ?></b> (Due: <?php echo $this->customlib->dateformat($od['due_date']); ?>) - 
                                                <span class="text-danger"><?php echo amountFormat($od['total_balance']); ?></span>
                                                <br>
                                                <small class="text-muted">
                                                (Ac: <?php echo amountFormat($od['academic_balance']); ?>, 
                                                Tr: <?php echo amountFormat($od['transport_balance']); ?>, 
                                                Ho: <?php echo amountFormat($od['hostel_balance']); ?>)
                                                </small>
                                            </li>
                                        <?php } ?>
                                        </ul>
                                    </td>
                                    <td class="text-right text-danger"><b><?php echo amountFormat($student['total_overdue']); ?></b></td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right"><?php echo $this->lang->line('grand_total'); ?></th>
                                    <th class="text-right text-danger"><?php echo amountFormat($grand_total); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </section>
</div>

<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }
    
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, section_id);
        });
    });
</script>
