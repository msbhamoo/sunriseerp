<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>

            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-file-text-o"></i> CBSE Mandatory Public Disclosure (SARAS 7.0 - Circular 09/2021)</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('cbse_disclosure'); ?>" target="_blank" class="btn btn-sm btn-primary"><i class="fa fa-external-link"></i> View Live Disclosure Page</a>
                        </div>
                    </div>

                    <div class="box-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="cbseTabs" role="tablist">
                            <li class="active"><a href="#secA" data-toggle="tab">A. General Information</a></li>
                            <li><a href="#secB" data-toggle="tab">B. Documents & Information</a></li>
                            <li><a href="#secC" data-toggle="tab">C. Result & Academics</a></li>
                            <li><a href="#secD" data-toggle="tab">D. Staff (Teaching)</a></li>
                            <li><a href="#secE" data-toggle="tab">E. Infrastructure</a></li>
                        </ul>

                        <div class="tab-content" style="padding-top: 20px;">
                            <!-- SECTION A: GENERAL INFORMATION -->
                            <div class="tab-pane active" id="secA">
                                <form class="cbse-ajax-form" enctype="multipart/form-data">
                                    <input type="hidden" name="section" value="general_info">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>1. NAME OF THE SCHOOL</label>
                                            <input type="text" class="form-control" name="fields[school_name]" value="<?php echo isset($disclosure_data['general_info']['school_name']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_name']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>2. AFFILIATION NO. (IF APPLICABLE)</label>
                                            <input type="text" class="form-control" name="fields[affiliation_no]" value="<?php echo isset($disclosure_data['general_info']['affiliation_no']['value']) ? htmlspecialchars($disclosure_data['general_info']['affiliation_no']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>3. SCHOOL CODE (IF APPLICABLE)</label>
                                            <input type="text" class="form-control" name="fields[school_code]" value="<?php echo isset($disclosure_data['general_info']['school_code']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_code']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>4. COMPLETE ADDRESS WITH PIN CODE</label>
                                            <input type="text" class="form-control" name="fields[school_address]" value="<?php echo isset($disclosure_data['general_info']['school_address']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_address']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>5. PRINCIPAL NAME</label>
                                            <input type="text" class="form-control" name="fields[principal_name]" value="<?php echo isset($disclosure_data['general_info']['principal_name']['value']) ? htmlspecialchars($disclosure_data['general_info']['principal_name']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>6. PRINCIPAL QUALIFICATION</label>
                                            <input type="text" class="form-control" name="fields[principal_qualification]" value="<?php echo isset($disclosure_data['general_info']['principal_qualification']['value']) ? htmlspecialchars($disclosure_data['general_info']['principal_qualification']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>7. SCHOOL EMAIL ID</label>
                                            <input type="email" class="form-control" name="fields[school_email]" value="<?php echo isset($disclosure_data['general_info']['school_email']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_email']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>8. CONTACT DETAILS (LANDLINE/MOBILE)</label>
                                            <input type="text" class="form-control" name="fields[school_phone]" value="<?php echo isset($disclosure_data['general_info']['school_phone']['value']) ? htmlspecialchars($disclosure_data['general_info']['school_phone']['value']) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="box-footer text-right">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save General Info</button>
                                    </div>
                                </form>
                            </div>

                            <!-- SECTION B: DOCUMENTS & INFORMATION -->
                            <div class="tab-pane" id="secB">
                                <form class="cbse-ajax-form" enctype="multipart/form-data">
                                    <input type="hidden" name="section" value="documents">
                                    <div class="alert alert-info" style="font-size:12px;">
                                        <i class="fa fa-info-circle"></i> Upload PDF / Document files for CBSE SARAS 7.0 inspection compliance. Visitors will be able to view/download these directly from your public website.
                                    </div>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">S.NO.</th>
                                                <th style="width: 45%;">DOCUMENTS / INFORMATION</th>
                                                <th style="width: 50%;">UPLOAD OFFICIAL PDF / FILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $doc_list = array(
                                                'doc_affiliation' => 'COPIES OF AFFILIATION/UPGRADATION LETTER AND RECENT EXTENSION OF AFFILIATION',
                                                'doc_society' => 'COPIES OF SOCIETIES/TRUST/COMPANY REGISTRATION/RENEWAL CERTIFICATE',
                                                'doc_noc' => 'COPY OF NO OBJECTION CERTIFICATE (NOC) ISSUED, IF APPLICABLE, BY THE STATE GOVT./UT',
                                                'doc_recognition' => 'COPIES OF RECOGNITION CERTIFICATE UNDER RTE ACT, 2009, AND IT\'S RENEWAL IF APPLICABLE',
                                                'doc_building' => 'COPY OF VALID BUILDING SAFETY CERTIFICATE AS PER THE NATIONAL BUILDING CODE',
                                                'doc_fire' => 'COPY OF VALID FIRE SAFETY CERTIFICATE ISSUED BY THE COMPETENT AUTHORITY',
                                                'doc_deo' => 'COPY OF THE DEO CERTIFICATE SUBMITTED BY THE SCHOOL FOR AFFILIATION/UPGRADATION/EXTENSION',
                                                'doc_water' => 'COPIES OF VALID WATER, HEALTH AND SANITATION CERTIFICATES'
                                            );
                                            $i = 1;
                                            foreach ($doc_list as $doc_key => $doc_title):
                                                $filePath = isset($disclosure_data['documents'][$doc_key]['file_path']) ? $disclosure_data['documents'][$doc_key]['file_path'] : '';
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><strong><?php echo $doc_title; ?></strong></td>
                                                <td>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <label for="input_<?php echo $doc_key; ?>" class="btn btn-sm btn-primary" style="margin-bottom:0; cursor:pointer;">
                                                            <i class="fa fa-upload"></i> Choose PDF File
                                                        </label>
                                                        <input type="file" id="input_<?php echo $doc_key; ?>" name="<?php echo $doc_key; ?>" class="cbse-file-input" style="opacity: 0; position: absolute; width: 1px; height: 1px;" accept=".pdf,.png,.jpg,.jpeg">
                                                        <span class="file-name-label text-muted" style="font-size: 11px; font-style: italic;">No file chosen</span>
                                                        <?php if (!empty($filePath)): ?>
                                                            <a href="<?php echo base_url($filePath); ?>" target="_blank" class="btn btn-xs btn-info" style="margin-left: auto; white-space: nowrap;"><i class="fa fa-eye"></i> View PDF</a>
                                                        <?php else: ?>
                                                            <span class="label label-warning" style="margin-left: auto; white-space: nowrap;">Not Uploaded</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                    <div class="box-footer text-right">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Documents</button>
                                    </div>
                                </form>
                            </div>

                            <!-- SECTION C: RESULT AND ACADEMICS -->
                            <div class="tab-pane" id="secC">
                                <form class="cbse-ajax-form" enctype="multipart/form-data">
                                    <input type="hidden" name="section" value="results">
                                    <h4 class="box-title">Academic & Committee Documents</h4>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%;">S.NO.</th>
                                                <th style="width: 45%;">DOCUMENTS / INFORMATION</th>
                                                <th style="width: 50%;">UPLOAD OFFICIAL PDF / FILE</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $acad_docs = array(
                                                'doc_fee_structure' => 'FEE STRUCTURE OF THE SCHOOL',
                                                'doc_academic_calendar' => 'ANNUAL ACADEMIC CALENDAR',
                                                'doc_smc' => 'LIST OF SCHOOL MANAGEMENT COMMITTEE (SMC)',
                                                'doc_pta' => 'LIST OF PARENTS TEACHERS ASSOCIATION (PTA) MEMBERS',
                                                'doc_three_year_result' => 'LAST THREE-YEAR RESULT OF THE BOARD EXAMINATION AS PER APPLICABILITY'
                                            );
                                            $i = 1;
                                            foreach ($acad_docs as $acad_key => $acad_title):
                                                $filePath = isset($disclosure_data['results'][$acad_key]['file_path']) ? $disclosure_data['results'][$acad_key]['file_path'] : '';
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><strong><?php echo $acad_title; ?></strong></td>
                                                <td>
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <label class="btn btn-sm btn-primary" style="margin-bottom:0; cursor:pointer;">
                                                            <i class="fa fa-upload"></i> Choose PDF File
                                                            <input type="file" name="<?php echo $acad_key; ?>" class="cbse-file-input" style="display: none;" accept=".pdf,.png,.jpg,.jpeg">
                                                        </label>
                                                        <span class="file-name-label text-muted" style="font-size: 11px; font-style: italic;">No file chosen</span>
                                                        <?php if (!empty($filePath)): ?>
                                                            <a href="<?php echo base_url($filePath); ?>" target="_blank" class="btn btn-xs btn-info" style="margin-left: auto; white-space: nowrap;"><i class="fa fa-eye"></i> View PDF</a>
                                                        <?php else: ?>
                                                            <span class="label label-warning" style="margin-left: auto; white-space: nowrap;">Not Uploaded</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    <h4 class="box-title" style="margin-top: 30px;">RESULT CLASS: X</h4>
                                    <div class="row">
                                        <div class="col-md-2 form-group"><label>YEAR</label><input type="text" name="fields[x_year]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['x_year']['value']) ? htmlspecialchars($disclosure_data['results']['x_year']['value']) : '2024'; ?>"></div>
                                        <div class="col-md-3 form-group"><label>NO. OF REGISTERED STUDENTS</label><input type="text" name="fields[x_registered]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['x_registered']['value']) ? htmlspecialchars($disclosure_data['results']['x_registered']['value']) : ''; ?>"></div>
                                        <div class="col-md-3 form-group"><label>NO. OF STUDENTS PASSED</label><input type="text" name="fields[x_passed]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['x_passed']['value']) ? htmlspecialchars($disclosure_data['results']['x_passed']['value']) : ''; ?>"></div>
                                        <div class="col-md-2 form-group"><label>PASS PERCENTAGE</label><input type="text" name="fields[x_pass_percentage]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['x_pass_percentage']['value']) ? htmlspecialchars($disclosure_data['results']['x_pass_percentage']['value']) : ''; ?>"></div>
                                        <div class="col-md-2 form-group"><label>REMARKS</label><input type="text" name="fields[x_remarks]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['x_remarks']['value']) ? htmlspecialchars($disclosure_data['results']['x_remarks']['value']) : ''; ?>"></div>
                                    </div>

                                    <h4 class="box-title" style="margin-top: 20px;">RESULT CLASS: XII</h4>
                                    <div class="row">
                                        <div class="col-md-2 form-group"><label>YEAR</label><input type="text" name="fields[xii_year]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['xii_year']['value']) ? htmlspecialchars($disclosure_data['results']['xii_year']['value']) : '2024'; ?>"></div>
                                        <div class="col-md-3 form-group"><label>NO. OF REGISTERED STUDENTS</label><input type="text" name="fields[xii_registered]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['xii_registered']['value']) ? htmlspecialchars($disclosure_data['results']['xii_registered']['value']) : ''; ?>"></div>
                                        <div class="col-md-3 form-group"><label>NO. OF STUDENTS PASSED</label><input type="text" name="fields[xii_passed]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['xii_passed']['value']) ? htmlspecialchars($disclosure_data['results']['xii_passed']['value']) : ''; ?>"></div>
                                        <div class="col-md-2 form-group"><label>PASS PERCENTAGE</label><input type="text" name="fields[xii_pass_percentage]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['xii_pass_percentage']['value']) ? htmlspecialchars($disclosure_data['results']['xii_pass_percentage']['value']) : ''; ?>"></div>
                                        <div class="col-md-2 form-group"><label>REMARKS</label><input type="text" name="fields[xii_remarks]" class="form-control input-sm" value="<?php echo isset($disclosure_data['results']['xii_remarks']['value']) ? htmlspecialchars($disclosure_data['results']['xii_remarks']['value']) : ''; ?>"></div>
                                    </div>

                                    <div class="box-footer text-right">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Results & Academics</button>
                                    </div>
                                </form>
                            </div>

                            <!-- SECTION D: STAFF (TEACHING) -->
                            <div class="tab-pane" id="secD">
                                <form class="cbse-ajax-form" enctype="multipart/form-data">
                                    <input type="hidden" name="section" value="staff">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>1. PRINCIPAL</label>
                                            <input type="text" class="form-control" name="fields[principal_staff]" value="<?php echo isset($disclosure_data['staff']['principal_staff']['value']) ? htmlspecialchars($disclosure_data['staff']['principal_staff']['value']) : '1'; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>2. TOTAL NO. OF TEACHERS</label>
                                            <input type="text" class="form-control" name="fields[total_teachers]" value="<?php echo isset($disclosure_data['staff']['total_teachers']['value']) ? htmlspecialchars($disclosure_data['staff']['total_teachers']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>PGT COUNT</label>
                                            <input type="text" class="form-control" name="fields[pgt_count]" value="<?php echo isset($disclosure_data['staff']['pgt_count']['value']) ? htmlspecialchars($disclosure_data['staff']['pgt_count']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>TGT COUNT</label>
                                            <input type="text" class="form-control" name="fields[tgt_count]" value="<?php echo isset($disclosure_data['staff']['tgt_count']['value']) ? htmlspecialchars($disclosure_data['staff']['tgt_count']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>PRT COUNT</label>
                                            <input type="text" class="form-control" name="fields[prt_count]" value="<?php echo isset($disclosure_data['staff']['prt_count']['value']) ? htmlspecialchars($disclosure_data['staff']['prt_count']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>3. TEACHERS SECTION RATIO</label>
                                            <input type="text" class="form-control" name="fields[teacher_section_ratio]" value="<?php echo isset($disclosure_data['staff']['teacher_section_ratio']['value']) ? htmlspecialchars($disclosure_data['staff']['teacher_section_ratio']['value']) : '1.5:1'; ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>4. DETAILS OF SPECIAL EDUCATOR</label>
                                            <input type="text" class="form-control" name="fields[special_educator]" value="<?php echo isset($disclosure_data['staff']['special_educator']['value']) ? htmlspecialchars($disclosure_data['staff']['special_educator']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>5. DETAILS OF COUNSELLOR AND WELLNESS TEACHER</label>
                                            <input type="text" class="form-control" name="fields[counsellor_details]" value="<?php echo isset($disclosure_data['staff']['counsellor_details']['value']) ? htmlspecialchars($disclosure_data['staff']['counsellor_details']['value']) : ''; ?>">
                                        </div>
                                    </div>
                                    <div class="box-footer text-right">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Staff Analytics</button>
                                    </div>
                                </form>
                            </div>

                            <!-- SECTION E: SCHOOL INFRASTRUCTURE -->
                            <div class="tab-pane" id="secE">
                                <form class="cbse-ajax-form" enctype="multipart/form-data">
                                    <input type="hidden" name="section" value="infrastructure">
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>1. TOTAL CAMPUS AREA OF THE SCHOOL (IN SQ MTR)</label>
                                            <input type="text" class="form-control" name="fields[campus_area]" value="<?php echo isset($disclosure_data['infrastructure']['campus_area']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['campus_area']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>2. NO. AND SIZE OF THE CLASS ROOMS (IN SQ MTR)</label>
                                            <input type="text" class="form-control" name="fields[classrooms_details]" value="<?php echo isset($disclosure_data['infrastructure']['classrooms_details']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['classrooms_details']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>3. NO. AND SIZE OF LABORATORIES INCLUDING COMPUTER LABS (IN SQ MTR)</label>
                                            <input type="text" class="form-control" name="fields[labs_details]" value="<?php echo isset($disclosure_data['infrastructure']['labs_details']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['labs_details']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>4. INTERNET FACILITY (Y/N)</label>
                                            <input type="text" class="form-control" name="fields[internet_facility]" value="<?php echo isset($disclosure_data['infrastructure']['internet_facility']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['internet_facility']['value']) : 'Yes'; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>5. NO. OF GIRLS TOILETS</label>
                                            <input type="text" class="form-control" name="fields[girls_toilets]" value="<?php echo isset($disclosure_data['infrastructure']['girls_toilets']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['girls_toilets']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>6. NO. OF BOYS TOILETS</label>
                                            <input type="text" class="form-control" name="fields[boys_toilets]" value="<?php echo isset($disclosure_data['infrastructure']['boys_toilets']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['boys_toilets']['value']) : ''; ?>">
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label>7. LINK OF YOUTUBE VIDEO OF THE INSPECTION OF SCHOOL COVERING THE INFRASTRUCTURE OF THE SCHOOL</label>
                                            <input type="text" class="form-control" name="fields[youtube_inspection_video]" value="<?php echo isset($disclosure_data['infrastructure']['youtube_inspection_video']['value']) ? htmlspecialchars($disclosure_data['infrastructure']['youtube_inspection_video']['value']) : ''; ?>" placeholder="https://www.youtube.com/watch?v=...">
                                        </div>
                                    </div>
                                    <div class="box-footer text-right">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Infrastructure</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $(document).on('change', '.cbse-file-input', function() {
        var fileName = $(this).val().split('\\').pop();
        if (fileName) {
            $(this).closest('div').find('.file-name-label').text(fileName).css({'color': '#28a745', 'font-weight': 'bold'});
        } else {
            $(this).closest('div').find('.file-name-label').text('No file chosen').css({'color': '#777', 'font-weight': 'normal'});
        }
    });

    $('.cbse-ajax-form').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var formData = new FormData(this);
        var submitBtn = form.find('button[type="submit"]');

        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo site_url("schsettings/ajax_save_cbse_disclosure"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(data) {
                submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Section');
                if (data.status === 'success') {
                    successMsg(data.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                } else {
                    errorMsg(data.message);
                }
            },
            error: function() {
                submitBtn.prop('disabled', false).html('<i class="fa fa-save"></i> Save Section');
                errorMsg('An error occurred while saving data.');
            }
        });
    });
});
</script>
