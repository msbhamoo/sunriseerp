<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-certificate"></i> Generate Certificate - Step 2</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url() . $student['image'] ?>" alt="User profile picture">
                        <h3 class="profile-username text-center"><?php echo $student['firstname'] . " " . $student['lastname']; ?></h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Admission No</b> <a class="pull-right text-aqua"><?php echo $student['admission_no']; ?></a>
                            </li>
                            <li class="list-group-item">
                                <b>Class</b> <a class="pull-right text-aqua"><?php echo $student['class']; ?> (<?php echo $student['section']; ?>)</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Select Certificate Type</h3>
                    </div>
                    <form action="<?php echo base_url('admin/certificateregister/generate/' . $student_id); ?>" method="post">
                        <div class="box-body">
                            <?php if($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                            } ?>
                            <div class="form-group">
                                <label>Certificate Type <small class="req"> *</small></label>
                                <select name="certificate_type_id" class="form-control" required>
                                    <option value="">Select</option>
                                    <?php foreach ($certificate_types as $type) { ?>
                                        <option value="<?php echo $type['id']; ?>"><?php echo $type['certificate_name']; ?> (Next No: <?php echo $type['series_prefix'] . sprintf('%03d', max($type['start_number'], $type['current_number'] + 1)); ?>)</option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Remark / Reason for Leaving</label>
                                <textarea name="remark" class="form-control" rows="2" placeholder="e.g. PROMOTED, TO STUDY ELSEWHERE"></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="generate" value="generate" class="btn btn-info pull-right">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
