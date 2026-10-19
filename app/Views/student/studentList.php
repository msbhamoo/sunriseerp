<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-graduation-cap"></i> <?php echo $this->lang->line('student_information'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Main content -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-filter text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('student', 'can_add')) { ?>
                                <a href="<?php echo base_url(); ?>student/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_student'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">

                        <?php if ($this->session->flashdata('msg')) { ?> 
                            <div class="alert alert-success" style="border-radius: 8px;"> 
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> 
                            </div> 
                        <?php } ?>
                        
                        <div class="row">
                            <form role="form" id="student_list_form" method="post" class="class_search_form">
                                <?php echo $this->customlib->getCSRF(); ?>
                                
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <select id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('category'); ?></label>
                                        <select id="category_id" name="category_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($categorylist as $category) { ?>
                                                <option value="<?php echo $category['id'] ?>"><?php echo $category['category'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('gender'); ?></label>
                                        <select id="gender" name="gender" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($this->customlib->getGender() as $gender_key => $gender_value) {
                                            ?>
                                                <option value="<?php echo $gender_key; ?>"><?php echo $gender_value; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('sort_by'); ?></label>
                                        <select id="custom_sort_by" name="custom_sort_by" class="form-control">
                                            <option value="Recent Admission"><?php echo $this->lang->line('recent_admission'); ?></option>
                                            <option value="Name"><?php echo $this->lang->line('student_name'); ?></option>
                                            <option value="Scholar No"><?php echo $this->lang->line('scholar_no'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('sort_direction'); ?></label>
                                        <select id="custom_sort_dir" name="custom_sort_dir" class="form-control">
                                            <option value="Descending"><?php echo $this->lang->line('descending'); ?></option>
                                            <option value="Ascending"><?php echo $this->lang->line('ascending'); ?></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-10 col-sm-9">
                                    <div class="form-group">
                                        <input type="text" name="search_keyword" id="search_keyword" class="form-control" placeholder="Search by Sr No / Admission No / Name / Father Contact...">
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-3">
                                    <div class="form-group">
                                        <button type="submit" name="search" id="search_btn" value="search_filter" class="btn btn-primary btn-block"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </form>
                        </div><!--./row-->
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#customExportModal"><i class="fa fa-download text-primary"></i> Export Options</button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive" style="min-height: 300px;">
                            <table class="table table-striped table-bordered table-hover custom-student-list" id="custom-student-list" data-export-title="<?php echo $this->lang->line('student_list'); ?>">
                                <thead>
                                    <tr>
                                        <th>S. NO.</th>
                                        <th>SCHOLAR NO</th>
                                        <th>STUDENT NAME</th>
                                        <th>FATHER NAME</th>
                                        <th>CONTACT NO</th>
                                        <th>CLASS</th>
                                        <th>SECTION</th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
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

<!-- Custom Export Modal -->
<div id="customExportModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Custom Export</h4>
            </div>
            <form id="custom_export_form" method="POST" action="<?php echo base_url('student/export_custom_data'); ?>">
                <!-- Hidden inputs to pass current filters -->
                <input type="hidden" name="class_id" id="export_class_id">
                <input type="hidden" name="section_id" id="export_section_id">
                <input type="hidden" name="category_id" id="export_category_id">
                <input type="hidden" name="gender" id="export_gender">
                <input type="hidden" name="search_keyword" id="export_search_keyword">
                <input type="hidden" name="format" id="export_format" value="csv">

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h5><b>Basic & Custom Details</b></h5>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="admission_no" checked> Admission No</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="firstname" checked> First Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="middlename"> Middle Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="lastname" checked> Last Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="dob"> Date of Birth</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="gender"> Gender</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="category"> Category</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="religion"> Religion</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="blood_group"> Blood Group</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="height"> Height</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="weight"> Weight</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="measurement_date"> Measurement Date</label></div>
                            
                            <?php if (!empty($custom_fields)) {
                                foreach ($custom_fields as $cf) { ?>
                                    <div class="checkbox"><label><input type="checkbox" name="columns[]" value="<?php echo $cf->name; ?>"> <?php echo $cf->name; ?></label></div>
                            <?php } } ?>
                        </div>
                        <div class="col-md-4">
                            <h5><b>Academic Details</b></h5>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="class_section_list" checked> Class & Section</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="roll_no"> Roll No</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="admission_date"> Admission Date</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="previous_school"> Previous School</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="adhar_no"> National Identification Number</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="samagra_id"> Local Identification Number</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="rte"> RTE</label></div>
                            <hr style="margin:5px 0;">
                            <h5><b>Bank Details</b></h5>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="bank_account_no"> Bank Account No</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="bank_name"> Bank Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="ifsc_code"> IFSC Code</label></div>
                        </div>
                        <div class="col-md-4">
                            <h5><b>Contact & Guardian Details</b></h5>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="mobileno" checked> Mobile No</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="email"> Email</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="current_address"> Current Address</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="permanent_address"> Permanent Address</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="city"> City</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="state"> State</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="pincode"> Pincode</label></div>
                            <hr style="margin:5px 0;">
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="father_name"> Father Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="mother_name"> Mother Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="guardian_name"> Guardian Name</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="guardian_relation"> Guardian Relation</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="guardian_phone"> Guardian Phone</label></div>
                            <div class="checkbox"><label><input type="checkbox" name="columns[]" value="guardian_address"> Guardian Address</label></div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-warning btn-sm" id="btnPreviewExport">Preview Data</button>
                            <div id="exportPreviewContainer" style="margin-top: 15px;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnExportExcel"><i class="fa fa-file-excel-o"></i> Download Excel</button>
                    <button type="button" class="btn btn-primary" id="btnExportCsv"><i class="fa fa-file-text-o"></i> Download CSV</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#custom-student-list').DataTable({
            "processing": true,
            "serverSide": true,
            "bFilter": false, 
            "bSort": false,   // Disabled because we are using our custom sort via AJAX
            "pageLength": 100,
            "ajax": {
                "url": "<?php echo site_url('student/ajax_student_list_custom') ?>",
                "type": "POST",
                "data": function(d) {
                    d.class_id = $('#class_id').val();
                    d.section_id = $('#section_id').val();
                    d.category_id = $('#category_id').val();
                    d.gender = $('#gender').val();
                    d.custom_sort_by = $('#custom_sort_by').val();
                    d.custom_sort_dir = $('#custom_sort_dir').val();
                    d.search_keyword = $('#search_keyword').val();
                }
            },
            "columns": [
                { "data": 0 },
                { "data": 1 },
                { "data": 2 },
                { "data": 3 },
                { "data": 4 },
                { "data": 5 },
                { "data": 6 },
                { "data": 7, "orderable": false }
            ],
            "rowCallback": function(row, data, displayNum, displayIndex, dataIndex) {
                var info = this.api().page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (displayIndex + 1));
                $('td:eq(0)', row).html(index);
            }
        });

        $('#student_list_form').on('submit', function(e) {
            e.preventDefault();
            table.ajax.reload();
        });

        // Instant AJAX Search on Dropdown Change
        $('#class_id, #section_id, #category_id, #gender, #custom_sort_by, #custom_sort_dir').on('change', function() {
            table.ajax.reload();
        });

        // Instant AJAX Search on Keyword Type (with simple debounce)
        var searchTimer;
        $('#search_keyword').on('keyup', function() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(function() {
                table.ajax.reload();
            }, 300); // 300ms delay to prevent excessive requests
        });

        $('#class_id').change(function() {
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: { 'class_id': class_id },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').html(div_data);
                }
            });
        });

        // Sync filters into hidden inputs before submitting/previewing
        function syncExportFilters() {
            $('#export_class_id').val($('#class_id').val());
            $('#export_section_id').val($('#section_id').val());
            $('#export_category_id').val($('#category_id').val());
            $('#export_gender').val($('#gender').val());
            $('#export_search_keyword').val($('#search_keyword').val());
        }

        $('#btnPreviewExport').on('click', function() {
            syncExportFilters();
            var form_data = $('#custom_export_form').serialize();
            $('#exportPreviewContainer').html('<i class="fa fa-spinner fa-spin"></i> Loading preview...');
            $.ajax({
                url: '<?php echo base_url('student/preview_custom_export'); ?>',
                type: 'POST',
                data: form_data,
                success: function(res) {
                    $('#exportPreviewContainer').html(res);
                },
                error: function() {
                    $('#exportPreviewContainer').html('<p class="text-danger">Failed to load preview.</p>');
                }
            });
        });

        $('#btnExportCsv').on('click', function() {
            syncExportFilters();
            $('#export_format').val('csv');
            $('#custom_export_form').submit();
        });

        $('#btnExportExcel').on('click', function() {
            syncExportFilters();
            $('#export_format').val('excel');
            $('#custom_export_form').submit();
        });
    });
</script>