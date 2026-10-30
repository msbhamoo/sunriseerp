<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-certificate"></i> Generate Certificate</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Student</h3>
                    </div>
                    <div class="box-body">
                        <form id="search_form" action="" method="post">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Class</label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option value="">Select</option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Section</label>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value="">Select</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="search_btn" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> Search</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" id="student_list_div" style="display: none;">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"> Student List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover student-list-dt">
                                <thead>
                                    <tr>
                                        <th>Admission No</th>
                                        <th>Student Name</th>
                                        <th>Class/Section</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
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
        $('#class_id').change(function() {
            var class_id = $(this).val();
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $('#section_id').empty();
                    $('#section_id').append('<option value="">Select</option>');
                    $.each(data, function (i, obj) {
                        $('#section_id').append('<option value="' + obj.section_id + '">' + obj.section + '</option>');
                    });
                }
            });
        });

        var table = $('.student-list-dt').DataTable({
            "processing": true,
            "serverSide": true,
            "bFilter": true,
            "ajax": {
                "url": "<?php echo base_url('admin/scholarregister/dt_student_list') ?>",
                "type": "POST",
                "data": function(d) {
                    d.class_id = $('#class_id').val();
                    d.section_id = $('#section_id').val();
                }
            },
            "columnDefs": [
                {
                    "targets": 3,
                    "render": function(data, type, row, meta) {
                        // row[0] is photo, row[1] admission, row[2] name_link, row[3] father, row[4] class, row[5] status, row[6] action
                        // Wait, Scholarregister/dt_student_list returns a specific structure. 
                        // I will extract the student ID from the view link.
                        var html = $.parseHTML(row[2]);
                        var href = $(html).attr('href');
                        var std_id = href.split('/').pop();
                        return "<a href='<?php echo base_url('admin/certificateregister/generate/') ?>" + std_id + "' class='btn btn-primary btn-xs'>Proceed to Generate</a>";
                    }
                },
                { "targets": [0], "data": 1 }, // Admission No
                { "targets": [1], "data": 2 }, // Name
                { "targets": [2], "data": 4 }  // Class
            ]
        });

        $('#search_btn').click(function() {
            $('#student_list_div').show();
            table.ajax.reload();
        });
    });
</script>
