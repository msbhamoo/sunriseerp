<?php
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
 
<link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<script src="<?php echo base_url(); ?>backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i>  <small></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('manage_alumni', 'can_add')) { ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addNewAlumni()"><i class="fa fa-plus"></i> Add New Alumni</button>
                                <a href="<?php echo site_url('admin/alumni/import') ?>" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Import Alumni</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {?> <div class="alert alert-success">  <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?> </div> <?php }?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/alumni/alumnilist') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('pass_out_session'); ?></label> <small class="req"> *</small>
                                                <select autofocus="" id="session_id" name="session_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
foreach ($sessionlist as $sessions) {
    ?>
                                                        <option value="<?php echo $sessions['id'] ?>" <?php if (set_value('session_id') == $sessions['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $sessions['session'] ?></option>
                                                        <?php
$count++;
}
?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                                <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php
foreach ($classlist as $class) {
    ?>
                                                        <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                        <?php
$count++;
}
?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('section'); ?></label>
                                                <select  id="section_id" name="section_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!--./col-md-6-->
                            <div class="col-md-6">
                                <div class="row">
                                    <form role="form" action="<?php echo site_url('admin/alumni/alumnilist') ?>" method="post" class="">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('search_by_admission_number'); ?></label>
                                                <input type="text" name="search_text" value="<?php echo set_value('search_text'); ?>" class="form-control"   placeholder="<?php echo $this->lang->line('search_by_admission_number') ?>">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!--./col-md-6-->
                        </div><!--./row-->
                    </div>
                    <?php
if (isset($resultlist)) {
    ?>
                        <div class="nav-tabs-custom border0 navnoshadow">
                            <div class="box-header ptbnull"></div>
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list_view'); ?></a></li>
                                <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('details_view'); ?></a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="download_label"><?php echo $title; ?></div>
                                <div class="tab-pane active table-responsive no-padding overflow-visible" id="tab_1">
                                    <table class="table table-striped table-bordered table-hover dt_table" cellspacing="0" width="100%"  data-export-title="<?php echo $this->lang->line('manage_alumni_details');?>">
                                        <thead>
                                            <tr>
                                                <th width='6%'><?php echo $this->lang->line('admission_no'); ?></th>
                                                <th><?php echo $this->lang->line('student_name'); ?></th>
                                                <th><?php echo $this->lang->line('class'); ?></th>
                                                <th><?php echo $this->lang->line('gender'); ?></th>
                                                <th><?php echo $this->lang->line('current_email'); ?></th>
                                                <th><?php echo $this->lang->line('current_phone'); ?></th>
                                                <th>Show on Website</th>
                                                <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
if (empty($resultlist)) {
        ?>

                                                <?php
} else {
        $count = 1;
        foreach ($resultlist as $student) {
            ?>
                                                    <tr>
                                                        <td> <?php echo $student['admission_no']; ?></td>
                                                        <td>
                                                            <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                        </td>
                                                        <td><?php echo $student['class']; ?></td>
                                                        <td><?php echo $this->lang->line(strtolower($student['gender'])); ?></td>
                                                        <td><?php
if (array_key_exists($student['id'], $alumni_studets)) {
                echo $alumni_studets[$student['id']]['current_email'];
            }
            ?></td>
                                                        <td><?php
if (array_key_exists($student['id'], $alumni_studets)) {
                echo $alumni_studets[$student['id']]['current_phone'];
            }
            ?></td>
                                                        <td>
                                                            <?php
$is_web = (array_key_exists($student['id'], $alumni_studets) && isset($alumni_studets[$student['id']]['show_on_website'])) ? $alumni_studets[$student['id']]['show_on_website'] : 0;
if ($is_web == 1) {
    echo '<span class="label label-success" style="cursor:pointer;" onclick="toggleWebsiteStatus('.$student['id'].', 1)"><i class="fa fa-globe"></i> Yes</span>';
} else {
    echo '<span class="label label-default" style="cursor:pointer;" onclick="toggleWebsiteStatus('.$student['id'].', 0)"><i class="fa fa-eye-slash"></i> No</span>';
}
                                                            ?>
                                                        </td>
                                                        <td class="pull-right" style="white-space:nowrap;">
                                                            <?php
if (array_key_exists($student['id'], $alumni_studets)) {
                if ($this->rbac->hasPrivilege('manage_alumni', 'can_edit')) {
                    ?>
                                                                    <a href="#" onclick="editStory('<?php echo $student['id']; ?>')" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Manage Story"><i class="fa fa-book"></i></a>
                                                                    <a href="<?php echo site_url('admin/alumni/story/' . $student['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Story Page" target="_blank"><i class="fa fa-newspaper-o"></i></a>
                                                                    <a href="#" onclick="add('<?php echo $student['id']; ?>')" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit') ?>"><i class="fa fa-pencil"></i></a>
                                                                    <?php
}
                if ($this->rbac->hasPrivilege('manage_alumni', 'can_delete')) {
                    ?>
                                                                    <a href="#" onclick="deletestudent('<?php echo $student['id']; ?>')" data-toggle="tooltip" class="btn btn-primary btn-xs"  title="<?php echo $this->lang->line('delete') ?>"><i class="fa fa-remove"></i></a>
                                                                    <?php
}
            } else {
                if ($this->rbac->hasPrivilege('manage_alumni', 'can_add')) {
                    ?>
                                                                    <a href="#" onclick="add('<?php echo $student['id']; ?>')" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('add') ?>">
                                                                        <i class="fa fa-plus"></i>
                                                                    </a>
                                                                <?php }
            }
            ?>
                                                        </td>
                                                    </tr>
                                                    <?php
$count++;
        }
    }
    ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="tab_2">
                                    <?php if (empty($resultlist)) {
        ?>
                                        <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                        <?php
} else {
        $count = 1;
        
        foreach ($resultlist as $student) {
            
           if (array_key_exists($student['id'], $alumni_studets) && (!empty($alumni_studets[$student['id']]['photo']))) {
                    $image = 'uploads/alumni_student_images/'.$alumni_studets[$student['id']]['photo'];
            } elseif(!empty($student["image"])) {
                    $image = $student["image"];
            } else {
                $image = "uploads/student_images/no_image.png";
            }            
            ?>
                                            <div class="carousel-row">
                                                <div class="slide-row">
                                                    <div id="carousel-2" class="carousel slide slide-carousel" data-ride="carousel">
                                                        <div class="carousel-inner">
                                                            <div class="item active">
                                                                <a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>">
                                                                 <img class="img-responsive img-thumbnail width150" alt="<?php echo $student["firstname"] . " " . $student["lastname"] ?>" src="<?php echo $this->media_storage->getImageURL($image); ?>" alt="Image">
                                                             </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="slide-content">
                                                        <h4><a href="<?php echo base_url(); ?>student/view/<?php echo $student['id'] ?>">  <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></a></h4>
                                                        <div class="row">
                                                            <div class="col-xs-6 col-md-6">
                                                                <address>
                                                                    <strong><b><?php echo $this->lang->line('class'); ?>: </b><?php echo $student['class']; ?></strong><br>
                                                                    <b><?php echo $this->lang->line('admission_no'); ?>: </b><?php echo $student['admission_no'] ?><br/>
                                                                    <b><?php echo $this->lang->line('date_of_birth'); ?>:
            <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($student['dob'])); ?><br>
                                                                        <b><?php echo $this->lang->line('gender'); ?>:&nbsp;</b><?php echo $this->lang->line(strtolower($student['gender'])) ?><br>
                                                                        <b><?php echo $this->lang->line('national_identification_number'); ?>:&nbsp;</b><?php echo $student['adhar_no'] ?><br>
                                                                        </address>
                                                                        </div>
                                                                        <div class="col-xs-6 col-md-6">
                                                                            <b><?php echo $this->lang->line('current_phone'); ?>: </b> <abbr title="Phone"><i class="fa fa-phone-square"></i>&nbsp;</abbr> <?php
if (array_key_exists($student['id'], $alumni_studets)) {
                echo $alumni_studets[$student['id']]['current_phone'];
            }
            ?><br>
                                                                            <b>
                                                                                <b><?php echo $this->lang->line('current_email'); ?>: </b> <abbr title="Phone">&nbsp;</abbr> <?php
if (array_key_exists($student['id'], $alumni_studets)) {
                echo $alumni_studets[$student['id']]['current_email'];
            }
            ?><br>
                                                                                <b><?php echo $this->lang->line('current_address'); ?>:&nbsp;</b><?php
if (array_key_exists($student['id'], $alumni_studets)) {
                echo $alumni_studets[$student['id']]['address'];
            } else {

                echo $student['current_address'];
            }
            ?> <?php echo $student['city'] ?><br>
                                                                                <?php if (array_key_exists($student['id'], $alumni_studets)) {
                ?><b><?php echo $this->lang->line('occupation'); ?>:&nbsp;</b><?php
echo $alumni_studets[$student['id']]['occupation'];
            }
            ?> <br>
                                                                                </div>
                                                                                </div>
                                                                                </div>
                                                                                <div class="slide-footer">
                                                                                    <span class="pull-right buttons">





                                                                                        <?php
if (array_key_exists($student['id'], $alumni_studets)) {
                if ($this->rbac->hasPrivilege('manage_alumni', 'can_edit')) {
                    ?>

                                                                                                <a href="#" onclick="add('<?php echo $student['id']; ?>')" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('edit') ?>"><i class="fa fa-pencil"></i></a>
                <?php } if ($this->rbac->hasPrivilege('manage_alumni', 'can_delete')) { ?>
                                                                                                <a href="#" onclick="deletestudent('<?php echo $student['id']; ?>')" data-toggle="tooltip" class="btn btn-primary btn-xs"  title="<?php echo $this->lang->line('delete') ?>"><i class="fa fa-remove"></i></a>
                                                                                                <?php
}
            } else {
                if ($this->rbac->hasPrivilege('manage_alumni', 'can_add')) {
                    ?>
                                                                                                <a href="#" onclick="add('<?php echo $student['id']; ?>')" class="btn btn-primary btn-xs" data-toggle="tooltip" title="" data-original-title="<?php echo $this->lang->line('add') ?>">

                                                                                                    <i class="fa fa-plus"></i>
                                                                                                </a>
                                                                                            <?php }
            }
            ?>
                                                                                    </span>
                                                                                </div>
                                                                        </div>
                                                                        </div>
                                                                        <?php
}
        $count++;
    }
    ?>
                                                                </div>
                                                                </div>
                                                                </div>
                                                                </div><!--./box box-primary -->
                                                                <?php
}
?>
                                                            </div>
                                                            </div>
                                                            </section>
                                                            </div>
                                                            <div class="modal fade" id="add_alumni" tabindex="-1" role="dialog" aria-labelledby="evaluation" style="padding-left: 0 !important">
                                                                <div class="modal-dialog modal-lg" role="document">
                                                                    <div class="modal-content modal-media-content">
                                                                        <div class="modal-header modal-media-header">
                                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                            <h4 class="box-title" ><span id="title_id"> <?php echo $this->lang->line('manage_alumni_details'); ?></span></h4>
                                                                        </div>
                                                                        <div class="modal-body pt0 pb0" >
                                                                            <form id="formadd" method="post" class="ptt10" enctype="multipart/form-data">

                                                                                <div class="row">
                                                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                                                        <div class="row">
                                                                                            <input type="hidden" id="student_id"  name="student_id">
                                                                                            <input type="hidden" id="id"  name="id">
                                                                                            <div class="col-sm-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="pwd"><?php echo $this->lang->line('current_phone'); ?></label><small class="req"> *</small>
                                                                                                    <input type="text" id="current_phone" name="current_phone" class="form-control">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="pwd"><?php echo $this->lang->line('current_email'); ?></label>
                                                                                                    <input type="text" id="current_email" name="current_email" class="form-control" >
                                                                                                </div>
                                                                                            </div>

                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('occupation'); ?></label>
                                                                                                    <textarea name="occupation" id="occupation" class="form-control" rows="2"></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('address'); ?></label>
                                                                                                    <textarea name="address" id="address" class="form-control" rows="2"></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                                <div class="form-group">
                                                                                                    <label for="pwd"><?php echo $this->lang->line('current_photo'); ?></label>
                                                                                                    <input type="file" id="documents"  name="documents" class="form-control filestyle">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-sm-6">
                                                                                                <div class="form-group" style="margin-top:25px;">
                                                                                                    <label style="cursor:pointer; font-weight:600; color:#337ab7;">
                                                                                                        <input type="checkbox" id="show_on_website" name="show_on_website" value="1"> <i class="fa fa-globe"></i> Show on Website (Feature on School Website)
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div><!--./row-->

                                                                                        <!-- Dynamic Education Section -->
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12">
                                                                                                <div class="box box-solid box-default mb10" style="border:1px solid #e0e0e0;">
                                                                                                    <div class="box-header with-border" style="background:#f4f6f9;">
                                                                                                        <h4 class="box-title text-primary font16" style="font-size:15px; font-weight:600;"><i class="fa fa-graduation-cap"></i> Higher Education Details</h4>
                                                                                                        <button type="button" class="btn btn-primary btn-xs pull-right" id="add_edu_btn"><i class="fa fa-plus"></i> Add Education</button>
                                                                                                    </div>
                                                                                                    <div class="box-body" id="education_wrapper">
                                                                                                        <!-- Dynamic Education Rows -->
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                        <!-- Dynamic Work / Career Section -->
                                                                                        <div class="row">
                                                                                            <div class="col-sm-12">
                                                                                                <div class="box box-solid box-default mb10" style="border:1px solid #e0e0e0;">
                                                                                                    <div class="box-header with-border" style="background:#f4f6f9;">
                                                                                                        <h4 class="box-title text-primary font16" style="font-size:15px; font-weight:600;"><i class="fa fa-briefcase"></i> Career / Work & Startup Details</h4>
                                                                                                        <button type="button" class="btn btn-primary btn-xs pull-right" id="add_work_btn"><i class="fa fa-plus"></i> Add Job / Startup</button>
                                                                                                    </div>
                                                                                                    <div class="box-body" id="work_wrapper">
                                                                                                        <!-- Dynamic Work Rows -->
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div><!--./col-md-12-->
                                                                                </div><!--./row-->
                                                                        </div><!--./row-->
                                                                        <div class="box-footer">
                                                                            <div class="pull-right paddA10">
                                                                                <button type="submit" class="btn btn-info" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait') ?>"><?php echo $this->lang->line('save') ?></button>
                                                                            </div>
                                                                        </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            </div>

<!-- Add New Alumni Modal -->
<div class="modal fade" id="modal_add_new_alumni" tabindex="-1" role="dialog" aria-labelledby="addAlumniModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-user-plus"></i> Add New Alumni</h4>
            </div>
            <form id="form_add_new_alumni" method="post">
                <div class="modal-body pb0">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('first_name'); ?></label> <small class="req"> *</small>
                                <input type="text" name="firstname" id="add_firstname" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('last_name'); ?></label>
                                <input type="text" name="lastname" id="add_lastname" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('admission_no'); ?></label>
                                <input type="text" name="admission_no" id="add_admission_no" class="form-control" placeholder="Leave blank for auto-generate">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('gender'); ?></label>
                                <select name="gender" id="add_gender" class="form-control">
                                    <option value="Male"><?php echo $this->lang->line('male'); ?></option>
                                    <option value="Female"><?php echo $this->lang->line('female'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('date_of_birth'); ?></label>
                                <input type="text" name="dob" id="add_dob" class="form-control date">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('pass_out_session'); ?></label> <small class="req"> *</small>
                                <select name="session_id" id="add_session_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($sessionlist as $sessions) { ?>
                                        <option value="<?php echo $sessions['id'] ?>"><?php echo $sessions['session'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('class'); ?></label> <small class="req"> *</small>
                                <select name="class_id" id="add_class_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php foreach ($classlist as $class) { ?>
                                        <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('section'); ?></label> <small class="req"> *</small>
                                <select name="section_id" id="add_section_id" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('current_email'); ?></label> <small class="req"> *</small>
                                <input type="email" name="current_email" id="add_current_email" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('current_phone'); ?></label>
                                <input type="text" name="current_phone" id="add_current_phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('occupation'); ?></label>
                                <input type="text" name="occupation" id="add_occupation" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('address'); ?></label>
                                <textarea name="address" id="add_address" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-right" id="btn_add_new_alumni" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('please_wait') ?>"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Alumni Story Builder Modal -->
<div class="modal fade" id="modal_alumni_story" tabindex="-1" role="dialog" aria-labelledby="storyModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-media-header" style="background:#0f172a; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title" style="color:#fff; font-weight:700;"><i class="fa fa-book"></i> Manage Alumni Story</h4>
            </div>
            <form id="form_alumni_story" method="post">
                <div class="modal-body" style="padding:20px 25px;">
                    <input type="hidden" id="story_student_id" name="student_id">

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label style="font-weight:600;">Passout Badge Text</label>
                                <input type="text" id="badge_text" name="badge_text" class="form-control" placeholder="e.g. CLASS OF 2013">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label style="font-weight:600;">Subtitle / Current Title</label>
                                <input type="text" id="subtitle" name="subtitle" class="form-control" placeholder="e.g. Founder & CEO @ Next Gen Edulite">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:600;">Introductory Story Paragraph</label>
                        <textarea id="story_intro" name="story_intro" class="form-control" rows="3" placeholder="e.g. Parmod's journey from Sikar to London is a testament..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight:600;"><i class="fa fa-graduation-cap text-info"></i> Higher Education Highlight</label>
                                <input type="text" id="higher_edu_summary" name="higher_edu_summary" class="form-control" placeholder="e.g. MBA, UWTSD London">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight:600;"><i class="fa fa-map-marker text-warning"></i> Current Location Highlight</label>
                                <input type="text" id="location_summary" name="location_summary" class="form-control" placeholder="e.g. London, UK">
                            </div>
                        </div>
                    </div>

                    <hr style="margin:15px 0;">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label style="font-weight:600;"><i class="fa fa-book text-primary"></i> Section 1 Title</label>
                                <input type="text" id="section1_title" name="section1_title" class="form-control" value="The Sunrise Foundation">
                            </div>
                            <div class="form-group">
                                <label style="font-weight:600;">Section 1 Content</label>
                                <textarea id="section1_content" name="section1_content" class="form-control" rows="3" placeholder="Describe early school years, mentors, foundation..."></textarea>
                            </div>
                        </div>
                    </div>

                    <hr style="margin:15px 0;">

                    <!-- Quote Block -->
                    <div class="panel panel-default" style="border-left:4px solid #f59e0b; background:#f8fafc;">
                        <div class="panel-body">
                            <div class="form-group mb10">
                                <label style="font-weight:600;"><i class="fa fa-quote-left text-warning"></i> Inspirational Quote</label>
                                <textarea id="quote_text" name="quote_text" class="form-control" rows="2" placeholder="e.g. The teachers at Sunrise didn't just teach me..."></textarea>
                            </div>
                            <div class="form-group mb0">
                                <label style="font-weight:600;">Quote Author / Attribution</label>
                                <input type="text" id="quote_author" name="quote_author" class="form-control" placeholder="e.g. — Parmod Kumar">
                            </div>
                        </div>
                    </div>

                    <hr style="margin:15px 0;">

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label style="font-weight:600;"><i class="fa fa-heart text-danger"></i> Section 2 Title</label>
                                <input type="text" id="section2_title" name="section2_title" class="form-control" value="Going Above and Beyond">
                            </div>
                            <div class="form-group">
                                <label style="font-weight:600;">Section 2 Content</label>
                                <textarea id="section2_content" name="section2_content" class="form-control" rows="3" placeholder="Describe milestones, current achievements, leadership..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="cursor:pointer; font-weight:600;">
                            <input type="checkbox" id="story_is_published" name="is_published" value="1" checked> Publish Story Online
                        </label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn_save_story"><i class="fa fa-save"></i> Save Story</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="application/javascript">

    function addNewAlumni() {
        $('#form_add_new_alumni')[0].reset();
        $('#add_section_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
        $('#modal_add_new_alumni').modal('show');
    }

    $(document).on('change', '#add_class_id', function (e) {
        var class_id = $(this).val();
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
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                $('#add_section_id').html(div_data);
            }
        });
    });

    $("#form_add_new_alumni").on('submit', function (e) {
        e.preventDefault();
        $("#btn_add_new_alumni").prop("disabled", true);
        $.ajax({
            url: "<?php echo site_url("admin/alumni/create_new_alumni") ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        if (value !== '') {
                            message += value;
                        }
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    $('#modal_add_new_alumni').modal('hide');
                    window.location.reload(true);
                }
            },
            error: function () {
                alert("Error occurred, please try again.");
            },
            complete: function () {
                $("#btn_add_new_alumni").prop("disabled", false);
            }
        });
    });

    function editStory(student_id) {
        $.ajax({
            type: "POST",
            url: base_url + "admin/alumni/get_story_details",
            data: {'student_id': student_id},
            dataType: "json",
            success: function (data) {
                $('#story_student_id').val(student_id);
                $('#badge_text').val(data.badge_text || 'CLASS OF 2013');
                $('#subtitle').val(data.subtitle || '');
                $('#story_intro').val(data.story_intro || '');
                $('#higher_edu_summary').val(data.higher_edu_summary || '');
                $('#location_summary').val(data.location_summary || '');
                $('#section1_title').val(data.section1_title || 'The Sunrise Foundation');
                $('#section1_content').val(data.section1_content || '');
                $('#quote_text').val(data.quote_text || '');
                $('#quote_author').val(data.quote_author || '');
                $('#section2_title').val(data.section2_title || 'Going Above and Beyond');
                $('#section2_content').val(data.section2_content || '');
                $('#story_is_published').prop('checked', data.is_published == 1);

                $('#modal_alumni_story').modal('show');
            }
        });
    }

    $("#form_alumni_story").on('submit', function (e) {
        e.preventDefault();
        $("#btn_save_story").prop("disabled", true);
        $.ajax({
            url: "<?php echo site_url("admin/alumni/save_story") ?>",
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.status == "fail") {
                    errorMsg("Error saving story details.");
                } else {
                    successMsg(res.message);
                    $('#modal_alumni_story').modal('hide');
                }
            },
            error: function () {
                alert("Error occurred, please try again.");
            },
            complete: function () {
                $("#btn_save_story").prop("disabled", false);
            }
        });
    });

    $(document).ready(function(){
        displayDataTable('dt_table',
        [
            {
            targets: [0,-2],
           
            className: 'dt-body-left dt-head-left'
            },
            {
            targets: [-1],
            orderable: false,
            className: 'dt-body-right dt-head-right'
            }
        ]
    );
    });

    function deletestudent(id){
        var result = confirm("<?php echo $this->lang->line('delete_confirm'); ?>");
        if(result){
            $.ajax({
                url: "<?php echo base_url(); ?>admin/alumni/deletestudent/"+id,
                type: "POST",
                success: function (res)
                {
                    successMsg('<?php echo $this->lang->line("delete_message"); ?>');
                    window.location.reload(true);
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

                },
                complete: function () {

                }
            });
        }
    }

</script>
<script type="text/javascript">

    function createEducationRow(data) {
        data = data || {};
        var edu_level = data.education_level || 'UG';
        var degree_name = data.degree_name || '';
        var college_name = data.college_name || '';
        var college_type = data.college_type || 'Government';
        var study_location = data.study_location || 'National';
        var country_name = data.country_name || '';
        var passout_year = data.passout_year || '';

        var country_style = (study_location === 'International') ? '' : 'display:none;';

        var html = '<div class="edu-row well well-sm mb10" style="position:relative; background:#fafafa; border:1px solid #e3e3e3; margin-bottom:10px; padding:10px;">';
        html += '<button type="button" class="btn btn-danger btn-xs remove-edu-row" style="position:absolute; right:8px; top:8px;"><i class="fa fa-remove"></i></button>';
        html += '<div class="row">';
        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Level</label><select name="edu_level[]" class="form-control input-sm">';
        html += '    <option value="UG" '+(edu_level==='UG'?'selected':'')+'>UG (Undergraduate)</option>';
        html += '    <option value="PG" '+(edu_level==='PG'?'selected':'')+'>PG (Postgraduate)</option>';
        html += '    <option value="Doctorate" '+(edu_level==='Doctorate'?'selected':'')+'>Doctorate / PhD</option>';
        html += '    <option value="Diploma" '+(edu_level==='Diploma'?'selected':'')+'>Diploma</option>';
        html += '    <option value="Senior Secondary" '+(edu_level==='Senior Secondary'?'selected':'')+'>Senior Secondary</option>';
        html += '    <option value="Other" '+(edu_level==='Other'?'selected':'')+'>Other</option>';
        html += '  </select></div></div>';

        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Degree / Course</label><input type="text" name="degree_name[]" value="'+degree_name+'" placeholder="e.g. B.Tech CS, MBA" class="form-control input-sm"></div></div>';
        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">College / University</label><input type="text" name="college_name[]" value="'+college_name+'" placeholder="College Name" class="form-control input-sm"></div></div>';

        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">College Type</label><select name="college_type[]" class="form-control input-sm">';
        html += '    <option value="Government" '+(college_type==='Government'?'selected':'')+'>Government</option>';
        html += '    <option value="Private" '+(college_type==='Private'?'selected':'')+'>Private</option>';
        html += '  </select></div></div>';

        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Location</label><select name="study_location[]" class="form-control input-sm edu-location-select">';
        html += '    <option value="National" '+(study_location==='National'?'selected':'')+'>National (India)</option>';
        html += '    <option value="International" '+(study_location==='International'?'selected':'')+'>International</option>';
        html += '  </select></div></div>';

        html += '  <div class="col-sm-3 edu-country-group" style="'+country_style+'"><div class="form-group mb5"><label style="font-size:12px;">Country Name</label><input type="text" name="country_name[]" value="'+country_name+'" placeholder="e.g. USA, UK" class="form-control input-sm"></div></div>';
        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Passout Year / Date</label><input type="text" name="passout_year[]" value="'+passout_year+'" placeholder="e.g. 2024" class="form-control input-sm"></div></div>';

        html += '</div></div>';
        return html;
    }

    function createWorkRow(data) {
        data = data || {};
        var work_type = data.work_type || 'Job';
        var organization_name = data.organization_name || '';
        var designation = data.designation || '';
        var joining_date = data.joining_date || '';
        var completion_date = data.completion_date || '';
        var is_current = data.is_current == '1' ? 'checked' : '';
        var location = data.location || '';

        var html = '<div class="work-row well well-sm mb10" style="position:relative; background:#fafafa; border:1px solid #e3e3e3; margin-bottom:10px; padding:10px;">';
        html += '<button type="button" class="btn btn-danger btn-xs remove-work-row" style="position:absolute; right:8px; top:8px;"><i class="fa fa-remove"></i></button>';
        html += '<div class="row">';

        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Category</label><select name="work_type[]" class="form-control input-sm">';
        html += '    <option value="Job" '+(work_type==='Job'?'selected':'')+'>Job (Employment)</option>';
        html += '    <option value="Startup" '+(work_type==='Startup'?'selected':'')+'>Startup / Business</option>';
        html += '    <option value="Freelance" '+(work_type==='Freelance'?'selected':'')+'>Freelance</option>';
        html += '    <option value="Higher Studies" '+(work_type==='Higher Studies'?'selected':'')+'>Higher Studies</option>';
        html += '    <option value="Other" '+(work_type==='Other'?'selected':'')+'>Other</option>';
        html += '  </select></div></div>';

        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Organization / Company</label><input type="text" name="organization_name[]" value="'+organization_name+'" placeholder="Company or Startup Name" class="form-control input-sm"></div></div>';
        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Designation / Role</label><input type="text" name="designation[]" value="'+designation+'" placeholder="e.g. Software Engineer, Founder" class="form-control input-sm"></div></div>';
        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">City / Location</label><input type="text" name="work_location[]" value="'+location+'" placeholder="Location" class="form-control input-sm"></div></div>';

        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Joining Date</label><input type="text" name="joining_date[]" value="'+joining_date+'" placeholder="YYYY-MM-DD" class="form-control input-sm date"></div></div>';
        html += '  <div class="col-sm-3"><div class="form-group mb5"><label style="font-size:12px;">Completion / End Date</label><input type="text" name="completion_date[]" value="'+completion_date+'" placeholder="YYYY-MM-DD" class="form-control input-sm date"></div></div>';
        html += '  <div class="col-sm-3"><div class="form-group mb5" style="margin-top:22px;"><label style="font-size:12px; font-weight:normal; cursor:pointer;"><input type="checkbox" name="is_current[]" value="1" '+is_current+'> Currently Working Here</label></div></div>';

        html += '</div></div>';
        return html;
    }

    $(document).on('click', '#add_edu_btn', function() {
        $('#education_wrapper').append(createEducationRow({}));
    });

    $(document).on('click', '#add_work_btn', function() {
        $('#work_wrapper').append(createWorkRow({}));
    });

    $(document).on('click', '.remove-edu-row', function() {
        $(this).closest('.edu-row').remove();
    });

    $(document).on('click', '.remove-work-row', function() {
        $(this).closest('.work-row').remove();
    });

    $(document).on('change', '.edu-location-select', function() {
        var val = $(this).val();
        var country_group = $(this).closest('.row').find('.edu-country-group');
        if (val === 'International') {
            country_group.show();
        } else {
            country_group.hide();
        }
    });

    function add(student_id) {            
        $.ajax({
            type: "POST",
            url: base_url + "admin/alumni/get_alumnidetails",
            data: {'student_id': student_id},
            dataType: "json",
            success: function (data) {
                $('#id').val(data.id);
                $('#current_email').val(data.current_email);
                $('#current_phone').val(data.current_phone);
                $('#occupation').val(data.occupation);
                $('#address').val(data.address);
                $('#show_on_website').prop('checked', data.show_on_website == 1);
                $('#student_id').val(student_id);

                $('#education_wrapper').html('');
                if (data.education_list && data.education_list.length > 0) {
                    $.each(data.education_list, function(i, edu) {
                        $('#education_wrapper').append(createEducationRow(edu));
                    });
                } else {
                    $('#education_wrapper').append(createEducationRow({}));
                }

                $('#work_wrapper').html('');
                if (data.work_list && data.work_list.length > 0) {
                    $.each(data.work_list, function(i, wrk) {
                        $('#work_wrapper').append(createWorkRow(wrk));
                    });
                } else {
                    $('#work_wrapper').append(createWorkRow({}));
                }

                $("#add_alumni").modal("show");
            }
        });
    }

    function toggleWebsiteStatus(student_id, current_val) {
        var new_val = (current_val == 1) ? 0 : 1;
        $.ajax({
            type: "POST",
            url: base_url + "admin/alumni/change_show_on_website",
            data: {'student_id': student_id, 'show_on_website': new_val},
            dataType: "json",
            success: function (res) {
                if (res.status == 'success') {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            }
        });
    }

    $("#formadd").on('submit', (function (e) {
        e.preventDefault();
        $("#submit").prop("disabled", true);        
        $.ajax({
            url: "<?php echo site_url("admin/alumni/add") ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {

            },
            success: function (res)
            {
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);

                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");              
                $("#submit").prop("disabled", false);
            },
            complete: function () {               
                $("#submit").prop("disabled", false);
            }           

        });
    }));

function getSectionByClass(class_id, section_id) {
    if (class_id != "" && section_id != "") {
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
                        sel = "selected";
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
                    div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                });
                $('#section_id').append(div_data);
            }
        });
    });
});
</script>