<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?> 
                            <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> 
                        <?php } else if (isset($msg)) { echo $msg; } ?>
                        <div class="row">
                            <form role="form" action="<?php echo site_url('student/generaterollno') ?>" method="post">
                                <input type="hidden" name="action" value="search">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" required>
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (isset($class_id) && $class_id == $class['id']) echo "selected=selected"; ?>><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label> <small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control" required>
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sort By</label>
                                        <select id="sort_by" name="sort_by" class="form-control">
                                            <option value="name" <?php if (isset($sort_by) && $sort_by == 'name') echo "selected=selected"; ?>>Student Name</option>
                                            <option value="admission_no" <?php if (isset($sort_by) && $sort_by == 'admission_no') echo "selected=selected"; ?>>Admission No</option>
                                            <option value="admission_date" <?php if (isset($sort_by) && $sort_by == 'admission_date') echo "selected=selected"; ?>>Admission Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (isset($students)) { ?>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-list"></i> Generate Roll No</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="well well-sm">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group mb0">
                                                    <label>Prefix (Optional)</label>
                                                    <input type="text" id="series_prefix" class="form-control" placeholder="e.g. 10A-">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb0">
                                                    <label>Start Number</label>
                                                    <input type="number" id="series_start" class="form-control" value="1" min="1">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group mb0" style="margin-top: 25px;">
                                                    <button type="button" class="btn btn-info btn-sm" onclick="generateSeries()">Apply Series</button>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="clearSeries()">Clear All</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <form action="<?php echo site_url('student/generaterollno') ?>" method="post">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="action" value="save">
                                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                                <input type="hidden" name="sort_by" value="<?php echo $sort_by; ?>">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                                <th><?php echo $this->lang->line('admission_date'); ?></th>
                                                <th><?php echo $this->lang->line('roll_no'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($students)) { ?>
                                                <tr>
                                                    <td colspan="4" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                </tr>
                                            <?php } else {
                                                foreach ($students as $student) {
                                            ?>
                                                    <tr>
                                                        <td><?php echo $student->admission_no; ?></td>
                                                        <td><?php echo $student->firstname . " " . $student->middlename . " " . $student->lastname; ?></td>
                                                        <td><?php if ($student->admission_date != null && $student->admission_date != '0000-00-00') echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student->admission_date)); ?></td>
                                                        <td>
                                                            <input type="hidden" name="student_id[]" value="<?php echo $student->id; ?>">
                                                            <input type="text" name="roll_no[]" class="form-control generated_roll_no" value="<?php echo $student->roll_no; ?>">
                                                        </td>
                                                    </tr>
                                            <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if (!empty($students)) { ?>
                                    <button type="submit" class="btn btn-primary pull-right">Save Roll Numbers</button>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                <?php } ?>
            </div>
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
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    $(document).ready(function() {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo isset($section_id) ? $section_id : "" ?>';
        if(class_id != "") {
            getSectionByClass(class_id, section_id);
        }
        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });

    function generateSeries() {
        var prefix = $('#series_prefix').val();
        var startNo = parseInt($('#series_start').val());
        if(isNaN(startNo)) {
            alert('Please enter a valid starting number');
            return;
        }
        $('.generated_roll_no').each(function(index) {
            $(this).val(prefix + (startNo + index));
        });
    }

    function clearSeries() {
        $('.generated_roll_no').val('');
    }
</script>
