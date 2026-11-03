<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.js"></script>

<style>
/* Modern CBSE Exam List UI & Responsive Polish */
.cbse-exam-page {
    font-family: inherit;
}
.cbse-exam-page .box.box-primary {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    background: #ffffff;
    margin-bottom: 25px;
    overflow: hidden;
}
.cbse-exam-page .box-header {
    padding: 18px 24px;
    border-bottom: 1px solid #edf2f7;
    background: #ffffff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.cbse-exam-page .box-header .box-title {
    font-size: 18px;
    font-weight: 700;
    color: #1a202c;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.cbse-exam-page .box-header .box-title i {
    color: #3b82f6;
}
.cbse-exam-page .box-header .box-tools {
    position: static;
    float: none !important;
}
.cbse-exam-page .btn-add-exam {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 600;
    font-size: 13px;
    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
}
.cbse-exam-page .btn-add-exam:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4);
}
.cbse-exam-page .box-body {
    padding: 20px 24px;
}

/* Modern Table Styles */
.cbse-exam-page .table-responsive {
    border: none;
    margin-bottom: 0;
}
.cbse-exam-page .exam-table {
    margin-bottom: 0 !important;
    border-collapse: separate;
    border-spacing: 0;
    width: 100% !important;
}
.cbse-exam-page .exam-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-top: none;
    border-bottom: 2px solid #e2e8f0;
    padding: 12px 14px;
    white-space: nowrap;
}
.cbse-exam-page .exam-table tbody tr {
    transition: background 0.15s ease;
}
.cbse-exam-page .exam-table tbody tr:hover {
    background-color: #f8fafc !important;
}
.cbse-exam-page .exam-table tbody td {
    padding: 14px;
    vertical-align: middle;
    border-top: 1px solid #edf2f7;
    color: #334155;
    font-size: 13px;
}

/* Typography & Sub-elements */
.cbse-exam-page .exam-name-cell {
    font-weight: 600;
    color: #0f172a;
    font-size: 14px;
}
.cbse-exam-page .exam-badge-term {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #475569;
    font-size: 12px;
    font-weight: 600;
}
.cbse-exam-page .badge-class-pill {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    background: #e0e7ff;
    color: #3730a3;
    font-size: 11px;
    font-weight: 600;
    margin-right: 4px;
}
.cbse-exam-page .status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    line-height: 1.2;
}
.cbse-exam-page .status-pill.active {
    background: #dcfce7;
    color: #15803d;
}
.cbse-exam-page .status-pill.inactive {
    background: #fee2e2;
    color: #b91c1c;
}

/* Toggle Publish Button Pill */
.cbse-exam-page .btn-publish-pill {
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.3px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s ease;
}
.cbse-exam-page .btn-publish-pill.btn-published {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}
.cbse-exam-page .btn-publish-pill.btn-published:hover {
    background: #d1fae5;
    color: #065f46;
}
.cbse-exam-page .btn-publish-pill.btn-draft {
    background: #f3f4f6;
    color: #4b5563;
    border: 1px solid #e5e7eb;
}
.cbse-exam-page .btn-publish-pill.btn-draft:hover {
    background: #e5e7eb;
    color: #1f2937;
}

/* 3-Dots Action Dropdown Menu */
.cbse-exam-page .exam-action-dropdown {
    position: relative;
    display: inline-block;
}
.cbse-exam-page .btn-action-dots {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
}
.cbse-exam-page .btn-action-dots:hover,
.cbse-exam-page .btn-action-dots:focus,
.cbse-exam-page .open > .btn-action-dots {
    background: #f8fafc;
    color: #0f172a;
    border-color: #cbd5e1;
    outline: none;
}
.cbse-exam-page .exam-dropdown-menu {
    border-radius: 10px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    padding: 6px;
    min-width: 200px;
    right: 0;
    left: auto;
    margin-top: 4px;
    text-align: left;
    background: #ffffff;
    z-index: 1050;
}
.cbse-exam-page .exam-dropdown-menu > li > a,
.cbse-exam-page .exam-dropdown-menu > li > button {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 500;
    color: #334155;
    border-radius: 6px;
    border: none;
    background: transparent;
    text-align: left;
    text-decoration: none;
    transition: all 0.15s ease;
    cursor: pointer;
}
.cbse-exam-page .exam-dropdown-menu > li > a:hover,
.cbse-exam-page .exam-dropdown-menu > li > button:hover {
    background: #f1f5f9;
    color: #0f172a;
}
.cbse-exam-page .exam-dropdown-menu > li > a i,
.cbse-exam-page .exam-dropdown-menu > li > button i {
    width: 16px;
    font-size: 13px;
    text-align: center;
    color: #64748b;
}
.cbse-exam-page .exam-dropdown-menu > li.divider {
    margin: 4px 0;
    background-color: #f1f5f9;
    height: 1px;
}
.cbse-exam-page .exam-dropdown-menu > li.danger-action > button {
    color: #dc2626;
}
.cbse-exam-page .exam-dropdown-menu > li.danger-action > button i {
    color: #dc2626;
}
.cbse-exam-page .exam-dropdown-menu > li.danger-action > button:hover {
    background: #fef2f2;
    color: #b91c1c;
}

/* Responsive / Mobile Optimization */
@media (max-width: 768px) {
    .cbse-exam-page .box-header {
        padding: 14px 16px;
        flex-direction: column;
        align-items: flex-start;
    }
    .cbse-exam-page .box-header .box-tools {
        width: 100%;
    }
    .cbse-exam-page .btn-add-exam {
        width: 100%;
        justify-content: center;
        padding: 10px;
    }
    .cbse-exam-page .box-body {
        padding: 12px 14px;
    }
    .cbse-exam-page .table-responsive {
        border-radius: 8px;
        border: 1px solid #edf2f7;
    }
    .cbse-exam-page .exam-action-group {
        display: flex;
        flex-wrap: wrap;
        width: max-content;
    }
}
</style>

<div class="content-wrapper cbse-exam-page"> 
    <!-- Main content -->
    <section class="content">
        <div class="row">           
            <div class="col-md-12">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-graduation-cap"></i> <?php echo $this->lang->line('exam_list'); ?></h3>  
                        <div class="box-tools pull-right">
                            <?php
                              if ($this->rbac->hasPrivilege('cbse_exam', 'can_add')) { ?>
                            <a href="<?php echo site_url('cbseexam/exam/wizard'); ?>" class="btn-add-exam"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add')?></a> 
                            <?php 
                             } 
                           ?>                                       
                        </div>               
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('exam_list'); ?></div>
                        <div class="table-responsive mailbox-messages">
                            <?php if ($this->session->flashdata('msgdelete')) { ?>
                                <?php echo $this->session->flashdata('msgdelete') ?>
                            <?php } ?>
                            <table class="table table-striped table-hover example exam-table" data-export-title="<?php echo $this->lang->line('exam_list'); ?>">
                                <thead>
                                    <tr>  
                                        <th><?php echo $this->lang->line('exam_name'); ?></th>
                                        <th><?php echo $this->lang->line('class'); ?> (<?php echo $this->lang->line('sections'); ?>)</th>
                                        <th><?php echo $this->lang->line('term'); ?></th>                                        
                                        <th><?php echo $this->lang->line('subjects_included'); ?></th>
                                        <th><?php echo $this->lang->line('exam_published'); ?></th>
                                        <th><?php echo $this->lang->line('published_result'); ?></th>
                                        <th><?php echo $this->lang->line('category_name') ?></th>
                                        <th><?php echo $this->lang->line('description') ?></th>
                                        <th><?php echo $this->lang->line('created_at') ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($result as $key => $value) { ?>
                                        <tr>
                                            <td><?php echo $value['name']; ?></td>
                                            <td>
                                                <?php 
                                                if (!empty($value['class_sections'])) {
                                                    $cs_array = explode(',', $value['class_sections']);
                                                    $grouped = [];
                                                    foreach ($cs_array as $cs) {
                                                        $parts = explode(' - ', trim($cs), 2);
                                                        if (count($parts) == 2) {
                                                            $c = trim($parts[0]);
                                                            $s = trim($parts[1]);
                                                            $grouped[$c][] = $s;
                                                        } else {
                                                            $grouped[trim($cs)] = [];
                                                        }
                                                    }
                                                    $group_count = count($grouped);
                                                    $display_limit = 2; // Show only 2 classes initially
                                                    $i = 0;
                                                    $hidden_html = "";
                                                    
                                                    foreach ($grouped as $c => $sections) {
                                                        $sec_str = !empty($sections) ? implode(', ', $sections) : '';
                                                        $html = "";
                                                        if (!empty($sec_str)) {
                                                            $html = "<div style='margin-bottom:5px; line-height: 1.4;'><span class='label label-info' style='display:inline-block; margin-right:5px; font-weight:bold;'>" . $c . "</span><span style='color:#444; font-size:12px;'>" . $sec_str . "</span></div>";
                                                        } else {
                                                            $html = "<div style='margin-bottom:5px; line-height: 1.4;'><span class='label label-info' style='display:inline-block; font-weight:bold;'>" . $c . "</span></div>";
                                                        }
                                                        
                                                        if ($i < $display_limit) {
                                                            echo $html;
                                                        } else {
                                                            $hidden_html .= $html;
                                                        }
                                                        $i++;
                                                    }
                                                    
                                                    if (!empty($hidden_html)) {
                                                        $unique_id = 'classes_' . $value['id'];
                                                        echo "<div id='more_" . $unique_id . "' style='display:none;'>" . $hidden_html . "</div>";
                                                        echo "<a href='javascript:void(0);' class='text-primary btn-show-more' onclick='$(this).hide(); $(\"#more_" . $unique_id . "\").slideDown();' style='font-size:12px; display:inline-block; margin-top:3px; font-weight:600;'><i class='fa fa-plus-circle'></i> Show More (" . ($group_count - $display_limit) . ")</a>";
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="exam-badge-term"><?php echo !empty($value['term_name']) ? $value['term_name'] : '-'; ?></span>
                                            </td>                                            
                                            <td>
                                                <span class="badge" style="background:#f1f5f9; color:#334155; font-size:11px; font-weight:600; padding:4px 8px;"><?php echo $value['subjectsincluded']; ?></span>
                                            </td>                               
                                            <td>
                                                <?php if ($value['is_active'] == 1) { ?>
                                                    <span class="status-pill active"><i class="fa fa-check-circle"></i> <?php echo $this->lang->line('yes'); ?></span>
                                                <?php } else { ?>
                                                    <span class="status-pill inactive"><i class="fa fa-times-circle"></i> <?php echo $this->lang->line('no'); ?></span>
                                                <?php } ?>
                                            </td>                                            
                                            
                                            <td>
                                                <?php $is_pub = ($value['is_publish'] == 1); ?>
                                                <button type="button" 
                                                        class="btn-publish-pill <?php echo $is_pub ? 'btn-published' : 'btn-draft'; ?> toggle-publish-btn" 
                                                        data-examid="<?php echo $value['id']; ?>" 
                                                        data-publish="<?php echo $is_pub ? '0' : '1'; ?>" 
                                                        data-toggle="tooltip" 
                                                        title="<?php echo $is_pub ? 'Click to Unpublish Result' : 'Click to Publish Result'; ?>">
                                                    <i class="fa <?php echo $is_pub ? 'fa-check-circle' : 'fa-clock-o'; ?>"></i> 
                                                    <span class="pub-text"><?php echo $is_pub ? 'Published' : 'Draft'; ?></span>
                                                </button>
                                            </td> 
                                            
                                            <td>                                                
                                                <span style="color:#64748b; font-size:12px;"><?php echo !empty($value['category_name']) ? $value['category_name'] : '-'; ?></span>
                                            </td>
                                            <td>                                                
                                                <span style="color:#64748b; font-size:12px;" title="<?php echo htmlspecialchars($value['description'] ?? ''); ?>">
                                                    <?php 
                                                        $desc = $value['description'] ?? '';
                                                        echo (strlen($desc) > 35) ? substr($desc, 0, 35) . '...' : ($desc ?: '-'); 
                                                    ?>
                                                </span>
                                            </td>
                                            <td style="white-space: nowrap; color: #64748b; font-size: 12px;">
                                                <?php echo $this->customlib->dateformat($value['created_at']); ?>
                                            </td> 
                                            <td class="text-right white-space-nowrap">                                           
                                                <div class="dropdown exam-action-dropdown">
                                                    <button class="btn-action-dots dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="<?php echo $this->lang->line('action'); ?>">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-right exam-dropdown-menu">
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam', 'can_edit')) { ?>
                                                        <li>
                                                            <button type="button" class="toggle-publish-btn" data-examid="<?php echo $value['id']; ?>" data-publish="<?php echo $is_pub ? '0' : '1'; ?>">
                                                                <i class="fa <?php echo $is_pub ? 'fa-eye-slash text-warning' : 'fa-eye text-success'; ?>"></i>
                                                                <span class="pub-action-text"><?php echo $is_pub ? 'Unpublish Result' : 'Publish Result'; ?></span>
                                                            </button>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam_assign_view_student', 'can_view')) { ?>
                                                        <li>
                                                            <button type="button" class="assignStudent" data-examid="<?php echo $value['id']; ?>">
                                                                <i class="fa fa-users text-primary"></i> <?php echo $this->lang->line('assign_view_student'); ?>
                                                            </button>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam_subjects', 'can_view')) { ?>
                                                        <li>
                                                            <button type="button" id="subjectModalButton" data-exam_id="<?php echo $value['id']; ?>">
                                                                <i class="fa fa-book text-info"></i> <?php echo $this->lang->line('exam_subjects'); ?>
                                                            </button>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam_marks', 'can_view')) { ?>
                                                        <li>
                                                            <a href="<?php echo site_url('cbseexam/exam/marks/'.$value['id']) ?>">
                                                                <i class="fa fa-pencil-square-o text-primary"></i> <?php echo $this->lang->line('exam_marks'); ?>
                                                            </a>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam_attendance', 'can_view')) { ?>
                                                        <li>
                                                            <button type="button" class="examattendance" data-recordid="<?php echo $value['id']; ?>">
                                                                <i class="fa fa-calendar-check-o text-success"></i> <?php echo $this->lang->line('exam_attendance'); ?>
                                                            </button>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam_teacher_remark', 'can_view')) { ?>
                                                        <li>
                                                            <button type="button" class="examTeacherReamark" data-recordid="<?php echo $value['id']; ?>">
                                                                <i class="fa fa-commenting-o text-warning"></i> <?php echo $this->lang->line('teacher_remark'); ?>
                                                            </button>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam_generate_rank', 'can_view')) { ?>
                                                        <li>
                                                            <a href="<?php echo site_url('cbseexam/exam/examwiserank/'.$value['id']);?>">
                                                                <i class="fa fa-trophy text-warning"></i> <?php echo $this->lang->line('generate_rank'); ?>
                                                            </a>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam', 'can_edit')) { ?>
                                                        <li class="divider"></li>
                                                        <li>
                                                            <a href="<?php echo site_url('cbseexam/exam/wizard/'.$value['id']); ?>">
                                                                <i class="fa fa-pencil text-muted"></i> <?php echo $this->lang->line('edit'); ?>
                                                            </a>
                                                        </li>
                                                        <?php } ?>
                                                        
                                                        <?php if ($this->rbac->hasPrivilege('cbse_exam', 'can_delete')) { ?>
                                                        <li class="divider"></li>
                                                        <li class="danger-action">
                                                            <button type="button" class="deleteexam" data-id="<?php echo $value['id']; ?>" data-exam="yk" data-toggle="modal" data-target="#confirm-delete">
                                                                <i class="fa fa-trash-o"></i> <?php echo $this->lang->line('delete'); ?>
                                                            </button>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                   <?php }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div> 
        </div> 
    </section>
</div>
 
<div id="addExamModal" class="modal fade " role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title" id="modal-title"><?php echo $this->lang->line('add_exam'); ?></h4>
            </div>
            <div class="scroll-area">
            <form role="form" id="add_exam_form" method="post" enctype="multipart/form-data" action="">
                <div class="modal-body">
                    <div id="delete_ides"></div>
                    <div class="row">
         <div class="col-md-12">
            <div class="form-group" >
                <label for="exampleInputEmail1"><?php echo $this->lang->line('exam_name'); ?></label> <small class="req"> *</small>
               <input class="form-control"  name="exam_name" />   
            </div>
         </div>      
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2">
            <div class="checkbox-inline">
                <label>
                    <input type="checkbox" value="1" name="is_active"><?php echo $this->lang->line('publish'); ?>
                </label>
            </div>
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 hidden">
            <div class="checkbox-inline">
                <label>
                    <input type="checkbox" value="1" name="is_publish" autocomplete="off"> <?php echo $this->lang->line('publish_result'); ?>
                </label>
            </div>
        </div> 
    </div>
<div class="row">
    <div class="col-md-12">
            <div class="form-group">
                <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label> 
                <textarea type="text" class="form-control" name="exam_description" cols="115" rows="3"></textarea>
            </div>
        </div>
</div>
<hr/>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6">
                    <div class="form-group">
                       
                        <label><?php echo $this->lang->line('term'); ?></label><small class="req"> *</small>
                        <select name="exam_term_id" id="exam_term_id"  class="form-control" >
                             <option value=""><?php echo $this->lang->line('select')?></option>
                             <?php 
                             foreach ($term_list as $term_key => $term_value) {
                                ?>
                                <option value="<?php echo $term_value->id;?>"> <?php echo $term_value->name." (".$term_value->term_code.")";?></option>
                                <?php
                             }
                             ?>
                         </select> 
                         <input type="hidden" name="action" id="action"  class="form-control">
                    </div>
                </div>
                    <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="searchclassid" name="class_id" onchange="getSectionByClass(this.value, 0, 'sections')"  class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($classlist as $class) {
                                        ?>
                                        <option <?php
                                       
                                        ?> value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                            <span class="text-danger" id="error_class_id"></span>
                        </div>                        
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-6">
                       
      <div class="form-group relative">
        <label><?php echo $this->lang->line('section'); ?></label>
        <small class="req"> *</small>
         <div id="checkbox-dropdown-container" class="checkbox-dropdown-container">
            <div class="">
               <div class="custom-select" id="custom-select"><?php echo $this->lang->line('select'); ?></div>               
                <div id="custom-select-option-box" class="custom-select-option-box">
                    <div class="custom-select-option checkbox">
                        <label class="vertical-middle line-h-18">
                            <input  class="custom-select-option-checkbox" type="checkbox"  name="select_all" id="select_all"> <?php echo $this->lang->line('select_all'); ?> 
                        </label> 
                    </div>                  
                </div>
            </div>
          </div>
      <span class="text-danger" id="error_class_id"></span>
    </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('assessment'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="searchclassid" name="assessment_id"   class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($assessment_result as $assessment) {
                                        ?>
                                        <option <?php
                                       
                                        ?> value="<?php echo $assessment['id'] ?>"><?php echo $assessment['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                            <span class="text-danger" id="error_class_id"></span>
                        </div>                        
                    </div>
                     <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('grade'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="searchclassid" name="grade_id"   class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($grade_result as $grade) {
                                        ?>
                                        <option <?php
                                       
                                        ?> value="<?php echo $grade['id'] ?>"><?php echo $grade['name'] ?></option>
                                            <?php
                                        }
                                        ?>
                            </select>
                            <span class="text-danger" id="error_class_id"></span>
                        </div>                        
                    </div>
                     <div class="col-md-4 col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('category'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="cbse_category_id" name="cbse_category_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php  $cbse_category_id = '';  
                                    foreach ($cbse_category as $value) {   ?>
									<option value="<?php echo $value->id ?>"  <?php echo $cbse_category_id; ?>><?php echo $value->name; ?></option>
									<?php  }  ?>
                            </select>
                            <span class="text-danger" id="error_class_id"></span>
                        </div>                        
                    </div>
                    </div>                    
                                    
                </div>
                <div class="modal-footer">                    
                    <button type="submit" class="btn btn-primary pull-right" data-loading-text="<?php echo $this->lang->line('submitting') ?>" value=""><?php echo $this->lang->line('save'); ?></button>
                </div> 
            </form>
        </div>
        </div>
    </div>
</div>

<div id="allotStudentModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('assign_view_student') ?></h4>
            </div>
            <div class="modal-body">                
                <div id="studentAllotForm">

                </div>
            </div>
        </div>
    </div>
</div> 

<div id="addSubject" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('add_exam_subject') ?></h4>
            </div>
            <div class="modal-body minheight260">

                <div class="modal_loader_div" style="display: none;"></div>

                <div class="modal-body-inner">
                     
                </div>

            </div>
        </div>
    </div>
</div>

<div id="subjectmarkModal" class="modal fade modalmark" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam_subjects'); ?></h4>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="observationModal" class="modal fade modalmark" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"> <?php echo $this->lang->line('exam_attendance'); ?></h4>
            </div>		
			 
                <div class="box-body">                                              
					
					<div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('from_date'); ?></label><span class="req"> *</span>
                                <input type="text" name="from_date" id="from_date" value="" class="form-control date" autocomplete="off">

                                <span class="text-danger"></span>
                            </div>
                        </div>
						<div class="col-md-3">
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('to_date'); ?></label><span class="req"> *</span>
                                <input type="text" name="to_date" id="to_date" value="" class="form-control date" autocomplete="off">

                                <span class="text-danger"></span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle exam_attendance_search"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?> </button>
                            </div>  
                        </div>
                    </div>
					
                </div>  
             
			<hr>
					
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div id="subjectModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header"></h4>
            </div>
            <div class="modal-body">               
                <div class="examheight100 relative">
                    <div id="examfade"></div>
                    <div id="exammodal">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksEntryForm">
                    </div>
					
					
                </div>
            </div>
        </div>
    </div>
</div>

<!--  -->
<div id="observationParameterModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">        
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title subjectmodal_header"></h4>
            </div>
            <div class="modal-body">               
                <div class="examheight100 relative">
                    <div id="examfade"></div>
                    <div id="exammodal">
                        <img id="loader" src="<?php echo base_url() ?>/backend/images/loading_blue.gif" />
                    </div>
                    <div class="marksObservationEntryForm">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="teacherRemarkModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('teacher_remark') ; ?></h4>
            </div>
            <div class="modal-body">

            </div>
        </div>
    </div>
</div>

<div id="editexamModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" >&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('edit_exam') ; ?></h4>
            </div>
            <div class="scroll-area">
            <form role="form" id="editform" method="post" enctype="multipart/form-data" action="">
     <div class="modal-body minheight260"> 

                <div class="modal_loader_div" style="display: none;"></div>

                <div class="modal-body-inner">
                     
                </div>

            </div>

            </form>
        </div>
        </div>
    </div>
</div>

<script type="text/javascript">      

    var x = 1;

(function ($){
    "use strict";
       var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy','M'=>'MM']) ?>';

    var date_format_time = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'DD', 'm' => 'MM', 'Y' => 'YYYY','M'=>'MMM']) ?>'; 
    
    $(document).ready(function () {
modal_click_disabled('subjectModal','addExamModal','observationParameterModal','teacherRemarkModal','allotStudentModal','addSubject','editexamModal','observationModal','subjectmarkModal');
   
    });
    
    $('#observationParameterModal').on('shown.bs.modal', function (e) {
        var exam_student_id = $(e.relatedTarget).data('exam_student_id');
        var exam_id=$(e.relatedTarget).data('exam_id');
        var student_name=$(e.relatedTarget).data('student_name');
        $('.subjectmodal_header').html("").html(student_name);
        $('.marksEntryForm').html(""); 
        
        $.ajax({ 
            type: 'POST',
            url: baseurl + "cbseexam/exam/get_observation_parameter",
            data: {exam_id:exam_id,exam_student_id:exam_student_id},
            dataType: 'JSON',
            beforeSend: function () {
               
            },
            success: function (data) {                
                $('.marksEntryForm').html(data.page);
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");              
            },
            complete: function () {
                
            }
        });
    }) 
    
    $('#subjectModal').on('shown.bs.modal', function (e) {
        var subject_id = $(e.relatedTarget).data('subject_id');
        var subject_name = $(e.relatedTarget).data('subject_name');
        var timetable_id = $(e.relatedTarget).data('timetable_id');
        var exam_id=$(e.relatedTarget).data('exam_id');
        
        $('.subjectmodal_header').html("").html('Enter ' + subject_name + ' Marks');
        $('.marksEntryForm').html(""); 
        $('.subject_id').val("").val(subject_id);

        $(e.currentTarget).find('input[name="subject_name"]').val(subject_name);
        var current_session = $('#current_session').val();
        $('#session_id option[value="'+current_session+'"]').prop("selected", true);
        $.ajax({ 
            type: 'POST',
            url: baseurl + "cbseexam/exam/subjectstudent",
            data: {exam_id:exam_id,subject_id:subject_id,timetable_id:timetable_id},
            dataType: 'JSON',
            beforeSend: function () {
               
            },
            success: function (data) {
                
                $('.marksEntryForm').html(data.page);
				$('.marksEntryForm').find('.dropify').dropify();
				
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
              
            },
            complete: function () {
                
            }
        });
    })

    $('#subjectModal').on('hidden.bs.modal', function () {
        $('.subjectmodal_header').html("");
        $('.marksEntryForm').html("");
        $('.subject_id').val("");
        $("#searchStudentForm").find('input:text,select,textarea').val('');
        $('#section_id').find('option').not(':first').remove();
        $('#session_id > option[selected="selected"]').removeAttr('selected'); 
    });

    $('#addExamModal,#editexamModal').on('hidden.bs.modal', function () {
           reset_form('#add_exam_form');
           $('.custom-select-option-box').closest('div').find("input[name='select_all']").attr('checked', false);
           $('.custom-select-option-box').children().not(':first').remove();           
           $("input[name=use_exam_roll_no][value='1']").prop("checked",true);
    });
    
    $(document).on('click', '.select_all', function (e) {
        if (this.checked) {
            $(this).closest('div.table-responsive').find('[type=checkbox]').prop('checked', true);
        } else {
            $(this).closest('div.table-responsive').find('[type=checkbox]').prop('checked', false);
        }
    });    
 
    $(document).on('submit', 'form#allot_exam_student', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var $this = form.find("button[type=submit]:focus");
        var url = form.attr('action');
        $.ajax({
            url: url,
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {
                if (res.status == 1) {
                    successMsg(res.message);
                    $('#allotStudentModal').modal('hide');

                } else {
                    errorMsg(res.message);
                }

                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }
    );

    $(document).on('click', '.examMarksSubject', function () {
        var $this = $(this);
        var recordid = $this.data('recordid');
        $('input[name=recordid]').val(recordid);
        
        $.ajax({
            type: 'POST',
            url: baseurl + "cbseexam/exam/getSubjectByExam",
            data: {'recordid': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                $('#subjectmarkModal .modal-body').html(data.subject_page);
                $('#subjectmarkModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });

    $(document).on('click', '.examattendance', function () {
        var $this = $(this);
        var recordid = $this.data('recordid');
        $('input[name=recordid]').val(recordid);
        
        $.ajax({
            type: 'POST',
            url: baseurl + "cbseexam/exam/exam_attendance",
            data: {'exam_id': recordid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {
                $('#observationModal .modal-body').html(data.page);
                $('#observationModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });    
        
    $(document).on('click', '.examTeacherReamark', function () {
        var $this = $(this);
        $('#teacherRemarkModal').modal('show');
        var recordid = $this.data('recordid');
        $.ajax({ 
            type: 'POST',
            url: baseurl + "cbseexam/exam/teacherRemark",
            data: {exam_id:recordid},
            dataType: 'JSON',
            beforeSend: function () {
               
            },
            success: function (data) {
                
                $('#teacherRemarkModal .modal-body').html(data.page);
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
              
            },
            complete: function () {
                
            }
        });
    });    
 
    $(document).on('click', '.assignStudent', function () {
        var $this = $(this);
        var examid = $(this).data('examid');
        
        $('#allotStudentModal').modal('show');
        $.ajax({ 
            type: 'POST',
            url: baseurl + "cbseexam/exam/examstudent",
            data: {examid:examid},
            dataType: 'JSON',
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (data) {                
                $('#studentAllotForm').html(data.page);
                $('#allotStudentModal').modal('show');
                $this.button('reset');
            },
            error: function (xhr) { // if error occured
                alert("Error occured.please try again");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    });

    $("#add_exam_form").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");      

        $.ajax({
            url: base_url+"cbseexam/exam/add",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');

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
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }

        });
    }));

    $(document).ready(function(){
        $("#exam_term_id").on('change',function(){            
        var term_id = $(this).val();
        var base_url = '<?php echo base_url() ?>';
            if(term_id!=''){
            $.ajax({
                type: "GET",
                url: base_url + "cbseexam/term/get_ClassSectionByTermId/"+term_id,
                dataType: "json",
                beforeSend: function () {
                     
                },
                success: function (data) {
                   
                   $('#searchclassid').val(data.class_id);
                   getSectionByClass(data.class_id, '', 'sections');
                },
                complete: function () {
                  
                }
            });
            }else{
                $('#searchclassid').val('');
                $('#sections').html('');
            }
        });
    });    

    var batch_subjects = "";
    var exam_assessments=[];
    var exam_classes=[];
    $(document).on('click', '#subjectModalButton', function (e) {
            
             x = 1;
            var class_batch_id = $(this).data('class_batch_id');
            var exam_id = $(this).data('exam_id');
            var exam_group_id = $('#examgroup_id').val();
          
            $.ajax({
                type: "POST",
                url: baseurl + "cbseexam/exam/getexamSubjects",
                data: {'exam_group_id': exam_group_id, 'class_batch_id': class_batch_id, 'exam_id': exam_id},
                dataType: "json",
                beforeSend: function () {
                     batch_subjects = "";
                     exam_assessments=[];
                        $('#addSubject .modal-body .modal-body-inner').html(""); 
                        $('#addSubject .modal-body .modal_loader_div').css("display", "block"); 
                        $('#addSubject').modal('show');

                },
                success: function (data) {

                    var s = data.subject_page;

                          $('#addSubject .modal-body .modal-body-inner').html(data.subject_page); 
                          $('#addSubject .modal-body .modal_loader_div').fadeOut(400);

                    var tmp_row = $('#item_table');

                    $('.datepicker_init', tmp_row).datetimepicker({
                        format: date_format_time,
                        showTodayButton: true,
                        ignoreReadonly: true
                    });

                    $('.datepicker_init_time', tmp_row).datetimepicker({
                        format: 'HH:mm:ss',
                        showTodayButton: true,
                        ignoreReadonly: true
                    });

                    batch_subjects = data.batch_subject_dropdown;
                    
                    exam_assessments = data.assessments;
                    exam_classes = data.classes;
                    if (data.exam_subjects_count > 0) {
                        x = data.exam_subjects_count + 1;
                    }

                },
                complete: function () {
                       
                 $('#addSubject .modal-body .modal_loader_div').fadeOut(400);
                }
            });
    });
        
    $(document).on('click', '.add', function () {
        
       

        var html = '';           
        
        html += '<tr>';
        html += '<td><select name="subject_' + x + '" class="form-control item_unit tddm200">' + batch_subjects + '</select></td>';
        html += '<td>'+classes_list(x)+'</td>';
        html += '<td>'+exam_assessments_list(x)+'</td>';
        html += '<td><div class="input-group datepicker_init"><input type="text" name="date_from_' + x + '" class="form-control"/><span class="input-group-addon" id="basic-addon2"><i class="fa fa-calendar"></i></span></div></td>';
        html += '<td><div class="input-group datepicker_init_time"><input type="text" name="time_from' + x + '" class="form-control"/><span class="input-group-addon" id="basic-addon2"><i class="fa fa-clock-o"></i></span></div></td>';
        html += '<td><input type="text" name="duration' + x + '" class="form-control duration" value="0"/></td>';       
        html += '<td class="" ><input type="text" name="room_no_' + x + '" class="form-control room_no" /><input type="hidden" name="rows[]" value="' + x + '"> <input name="prev_row[' + x + ']" type="hidden" value="0"></td>';      
        html += '<td class="text-center" ><span class="text text-danger remove fa fa-times mt5"></span></td></tr>';
        var tmp_row = $('#item_table').append(html);

        $('.datepicker_init', tmp_row).datetimepicker({
            format: date_format_time,
            showTodayButton: true,
            ignoreReadonly: true
        });

        $('.datepicker_init_time', tmp_row).datetimepicker({
            format: 'HH:mm:ss',
            showTodayButton: true,
            ignoreReadonly: true
        });
        x++;
    });

    function classes_list(x){
       var list="";
        $.each(exam_classes, function (key, val) {
            list+= "<div class='form-check' style='display:inline-block; margin-right:15px; margin-bottom:5px;'>";
            list+=  "<label class='form-check-label' for='cls_"+val.class_id+"_"+x+"'>";
            list+= "<input class='form-check-input' style='vertical-align: middle; margin-top: -2px;' type='checkbox' name='classes_"+x+"[]' value='"+val.class_id+"' id='cls_"+val.class_id+"_"+x+"'> "+val.class;                                          
            list+= "</label>";
            list+= "</div>";
            });
            return list;
    }

    function exam_assessments_list(x){
       var radio_list="";
        $.each(exam_assessments, function (key, val) {
            radio_list+= "<div class='form-check' style='display:inline-block; margin-right:15px; margin-bottom:5px;'>";
            radio_list+=  "<label class='form-check-label' for='chk_"+val.id+"_"+x+"'>";
            var sub_code=(val.code != "") ? " ("+val.code+")":"";
            radio_list+= "<input class='form-check-input' style='vertical-align: middle; margin-top: -2px;' type='checkbox' name='assessment_"+x+"[]' value='0_"+val.id+"' id='chk_"+val.id+"_"+x+"'> "+val.name+sub_code;                                          
            radio_list+= "</label>";
            radio_list+= "</div>";
            });
            return radio_list;

    }

    $(document).on('click', '.remove', function () {
        $(this).closest('tr').remove();
    });

    $('#insert_form').on('submit', function (event) {
        event.preventDefault();
        var error = '';
        $('.item_name').each(function () {
            var count = 1;
            if ($(this).val() == '')
            {
                error += "<p>Enter Item Name at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.item_quantity').each(function () {
            var count = 1;
            if ($(this).val() == '')
            {
                error += "<p>Enter Item Quantity at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });

        $('.item_unit').each(function () {
            var count = 1;
            if ($(this).val() == '')
            {
                error += "<p>Select Unit at " + count + " Row</p>";
                return false;
            }
            count = count + 1;
        });
        var form_data = $(this).serialize();
        if (error == '')
        {
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: form_data,
                success: function (data)
                {
                    if (data == 'ok')
                    {
                        $('#item_table').find("tr:gt(0)").remove();
                        $('#error').html('<div class="alert alert-success">Item Details Saved</div>');
                    }
                }
            });
        } else
        {
            $('#error').html('<div class="alert alert-danger">' + error + '</div>');
        }
    }); 
    
    $(document).on('submit', '.ssaddSubject', function (e) {

        e.preventDefault();
        var form = $(this);
        var subsubmit_button = $(this).find(':submit');
        var formdata = form.serializeArray();

        $.ajax({
            type: "POST",
            url: form.attr('action'),
            data: formdata, // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                subsubmit_button.button('loading');
            },
            success: function (response)
            {
                if (response.status == 0) {
                    var message = "";
                    $.each(response.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
 
                } else {                  
                    successMsg(response.message);
                    $('#addSubject').modal('hide');
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occured').", ".$this->lang->line('please_try_again')?>");
                subsubmit_button.button('reset');
            },
            complete: function () {
                subsubmit_button.button('reset');
            }
        });
    });

    $(document).ready(function () {

		$.validator.addMethod("uniqueUserName", function (value, element, options) {
			if (value === "") return true; // Allow empty values (not required)
			var max_mark = element.getAttribute('data-marks');
			return parseFloat(value) <= parseFloat(max_mark);
		}, "Invalid Marks");
	

        $(document).on('submit', 'form#assign_form1111', function (event) {
            event.preventDefault();

				$('form#assign_form1111').validate({
					debug: true,
					errorClass: 'error text text-danger',
					validClass: 'success',
					errorElement: 'span',
					highlight: function(element, errorClass, validClass) {
					$(element).addClass(errorClass);
					},
					unhighlight: function(element, errorClass, validClass) {
					$(element).removeClass(errorClass);
					}
				});           
			
				$('.mark').each(function () {
					$(this).rules("add", {
						required: true,
						uniqueUserName: true, // no 'required'
						messages: {
							uniqueUserName: "Invalid Marks"
						}
					});
				});

			// test if form is valid
            if ($('form#assign_form1111').validate().form()) {
				var form = $(this);
				var subsubmit_button = $(this).find(':submit');
				var $this = $(this).find("button[type=submit]:focus");     

					$.ajax({
						
						url: form.attr('action'),
						type: "POST",
						data: new FormData(this),
						dataType: 'json',
						contentType: false,
						cache: false,
						processData: false,
						beforeSend: function () {
							subsubmit_button.button('loading');
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
								$('#subjectModal').modal('hide');
			
							}
						},
						error: function (xhr) { // if error occured
							alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
							subsubmit_button.button('reset');
						},
						complete: function () {
							subsubmit_button.button('reset');
						}
					});       

            } else {
                console.log("does not validate");
            }
    
        })         

    });

    $('.editexamModalButton').click(function(){
        var exam_id = $(this).attr('data-exam_id');
        $.ajax({
            url: baseurl+'cbseexam/exam/get_exam',
            type: "POST",
            data: {exam_id:exam_id,delete_string:makeid(8)},
            dataType: 'json',
             beforeSend: function () {
                        $('#editexamModal .modal-body .modal-body-inner').html(""); 
                        $('#editexamModal .modal-body .modal_loader_div').css("display", "block"); 
                        $('#editexamModal').modal('show');
                    },
                    success: function (data)
                    {
                          $('#editexamModal .modal-body .modal-body-inner').html(data.page); 
                          $('#editexamModal .modal-body .modal_loader_div').fadeOut(400);
                           
                    },
                    error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                 
                    },

         complete: function () {
            
                    }

        });
    })

    $("#editform").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: base_url+"cbseexam/exam/edit",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
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
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));

    $('.deleteexam').click(function(){
        var exam_id = $(this).attr('data-id');
        if(confirm('<?php echo $this->lang->line('delete_confirm'); ?>')){
            $.ajax({
                url: base_url+'cbseexam/exam/deleteexam',
                type: "POST",
                data: {exam_id:exam_id},
                dataType: 'json',
                success: function (res)
                {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            });
        }
    })
    
})(jQuery);
</script>
<script>
    
    function add_exam(){
        $.ajax({
                url: base_url+'cbseexam/exam/add_exam',
                type: "POST",
                data:{delete_string:makeid(8)},
                dataType: 'json', 
                beforeSend: function() {
                    
                },
                success: function(res) {   
                   $('#exam_result').html(res);
                },
                error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");                   
                },
                complete: function() {
                      
                }
        });
    }

    function makeid(length) {
        var result = '';
        var characters = '0123456789';
        var charactersLength = characters.length;
        for (var i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        return result;
    }

    function remove(string)
    {
        var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('#'+string).html('');
        }
    }

    function remove_edit(id)
    {
        var result = confirm("<?php echo $this->lang->line('delete_confirm') ?>");
        if (result) {
            $('#'+id).html('');
            $('#delete_ides').append('<input type="hidden" name="delete_ides[]" value="'+id+'"/>');
        }
    } 

    function edit(exam_term_id){
        $('#action').val('edit');
        $('#exam_result').html('');
        $('#exam_term_id').val(exam_term_id);
        $('#delete_ides').html('');
        $('#modal-title').html('<?php echo $this->lang->line('edit')?>');
        
        $.ajax({
                url: base_url+'cbseexam/exam/get_editdetails',
                type: "POST",
                data:{id:exam_term_id},
                dataType: 'json',
                 beforeSend: function() {
                    
                },
                success: function(res) {   
                 
                    $.each(res.list, function (index, value) {

                         $.ajax({
                url: base_url+'cbseexam/exam/add_exam',
                type: "POST",
                data:{id:value.id,delete_string:value.id},
                dataType: 'json',
                beforeSend: function() {
                    
                },
                success: function(res) {   
                   $('#exam_result').append(res);
                },
                error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");                   
                },
                complete: function() {
                      
                }
            });
            });

                    $('#addExamModal').modal('show');
                    $('#modal-title').html('<?php echo $this->lang->line('edit')?>');
                },
                   error: function(xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                   
            },
            complete: function() {
                  
            }
        });
    }

        function getSectionByClass(class_id, section_id, select_control) {
           
        if (class_id != "") {
            $('#' + select_control).html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('.custom-select-option-box').closest('div').find("input[name='select_all']").attr('checked', false);
                    $('.custom-select-option-box').children().not(':first').remove();
                },
                success: function (data) {                 

                    $.each(data, function (i, obj)
                    {
                        var s=  $('<div>', {   
                            class: 'custom-select-option checkbox',
                        }).append($('<label>', {   
                            class: 'vertical-middle line-h-18',
                        }).append($('<input />', {   
                            class: 'custom-select-option-checkbox',
                            type: 'checkbox',
                            name:"section[]",
                            val:obj.id
                        })).append(obj.section));

                   $('.custom-select-option-box').append(s);
                     
                    });
                    
                },
                complete: function () {
                   
                }
            });
        }else{
            $('#sections').html('');
        }
    }  

</script>
<script>
    $(document).on('click',".custom-select",function(){
         $(".custom-select-option-box").toggle();
    });    

    $(".custom-select-option").on("click", function(e) {
        var checkboxObj = $(this).children("input");
        if($(e.target).attr("class") != "custom-select-option-checkbox") {
                if($(checkboxObj).prop('checked') == true) {
                    $(checkboxObj).prop('checked',false)
                } else {
                    $(checkboxObj).prop("checked",true);
                }
        }
    });

$(document).on('click', function(event) {
  if (event.target.className != "custom-select" && !$(event.target).closest('div').hasClass("custom-select-option")  ) {
          $(".custom-select-option-box").hide();
     }
});

$(document).on('change','#select_all',function(){   
        $('input:checkbox',$('.checkbox-dropdown-container')).not(this).prop('checked', this.checked);
});

$(document).on('change','.check_absent',function(){
  if(this.checked) {
    $(this).closest('td').find("input.mark").val(0).attr('readonly', true);
  }else{
    $(this).closest('td').find("input.mark").val(0).attr('readonly', false);
  }
});
</script>
<script>
    $(document).on('click', '.exam_attendance_search', function () {      
		
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var exam_id = $('#exam_id').val();

		if(to_date != '' && exam_id != '' && from_date != ''){       
		
			$.ajax({
				type: "POST",
				url: baseurl + "cbseexam/exam/exam_attendance_search",
				data: {'from_date': from_date, 'to_date': to_date, 'exam_id': exam_id},
				dataType: "json",
				success: function (data) {
					console.log(data);
					$('#observationModal .modal-body').html(data.page);
					
				}
			});
		}else{
			
			if(to_date != ''){
				alert('<?php echo $this->lang->line('please_select_from_date');?>');
			} else if (from_date != ''){
				alert('<?php echo $this->lang->line('please_select_to_date');?>');
			} else {
				alert('<?php echo $this->lang->line('please_select_date');?>');
			}
			
		}
		
    });
	
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', "#btnSubmit", function (event) {

            //stop submit the form, we will post it manually.
            event.preventDefault();
            var file_data = $('#my-file-selector').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);

            $.ajax({
                url: baseurl + "/cbseexam/exam/uploadfile", 
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
				contentType: false,
				cache: false,
				processData:false,
				beforeSend: function () {

                    $('#examfade,#exammodal').css({'display': 'block'});
				},
                success: function (data) {
					$('#fileUploadForm')[0].reset();
					if (data.status == "0") {
						var message = "";
						$.each(data.error, function (index, value) {
							message += value;
						});
						errorMsg(message);
					} else {
						var arr = [];
						$.each(data.student_marks, function (index) {
							var s = JSON.parse(data.student_marks[index]);
							arr.push({
								adm_no: s.adm_no,
								attendence: s.attendence,
								marks: s.marks, // this is now an array of marks
								note: s.note
							});
						});
						
						$.each(arr, function (index, value) {
                            let adm_no_csv = value.adm_no;
                            var row = $('.marksEntryForm').find('table tbody').find('tr[data-adm_no="' + adm_no_csv + '"]');

                            if (Array.isArray(value.marks)) {
                                $.each(value.marks, function (i, mark) {
                                    row.find('td input.marksssss_' + (i + 1)).val(mark);
                                });
                            } else {
                                row.find('td input.marksssss_1').val(value.marks);
                            }

                            row.find("td input.note").val(value.note);

                            if (value.attendence == 0) {
                                row.find("td input.attendance_chk").prop("checked", true);
                            } else {
                                row.find("td input.attendance_chk").prop("checked", false);
                            }
                        });
						
						successMsg("<?php echo $this->lang->line('csv_file_uploaded_successfully') ?>");
						$(".dropify-clear").trigger('click');
						//=================
					}
				},
				error: function (xhr) { // if error occured
					alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
					$('#examfade,#exammodal').css({'display': 'none'});
				},
				complete: function () {

					$('#fileUploadForm')[0].reset();
					$('#examfade,#exammodal').css({'display': 'none'});
				}

            });
        });
    });
</script>


<script>
  function get_template(class_section_id,template){
        var div_data="";
         $.ajax({
            type:'POST',
            url:'<?php echo base_url()?>cbseexam/template/get',
            data:{class_section_id:class_section_id},
            dataType:'JSON',
            beforeSend: function(){

            },
            success: function(data){
                div_data="<option value=''><?php echo $this->lang->line('select')?></option>";
                  $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if(template==obj.id){
                            sel="selected";
                        }
                        div_data += "<option value=" + obj.id + " " + sel + ">" + obj.name + "</option>";
                    });
                    $('#template').html(div_data);
            },
            error: function(){

            },

        });
    }

    $(document).on('click', '.toggle-publish-btn', function(e) {
        e.preventDefault();
        var $btn = $(this);
        var examId = $btn.data('examid');
        var newPublish = $btn.data('publish');
        var originalHtml = $btn.html();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        $.ajax({
            url: '<?php echo site_url("cbseexam/exam/toggle_publish"); ?>',
            type: 'POST',
            data: {
                exam_id: examId,
                publish: newPublish
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    successMsg(response.message);
                    
                    var isPublished = (response.is_publish == 1);
                    var nextPublish = isPublished ? 0 : 1;

                    // Update all buttons referencing this examId on this page
                    $('.toggle-publish-btn[data-examid="' + examId + '"]').each(function() {
                        var $el = $(this);
                        $el.data('publish', nextPublish);
                        $el.prop('disabled', false);

                        if ($el.find('.pub-text').length > 0) {
                            // Badge button in column
                            if (isPublished) {
                                $el.removeClass('btn-draft').addClass('btn-published');
                                $el.html('<i class="fa fa-check-circle"></i> <span class="pub-text">Published</span>');
                                $el.attr('data-original-title', 'Click to Unpublish Result').tooltip('fixTitle');
                            } else {
                                $el.removeClass('btn-published').addClass('btn-draft');
                                $el.html('<i class="fa fa-clock-o"></i> <span class="pub-text">Draft</span>');
                                $el.attr('data-original-title', 'Click to Publish Result').tooltip('fixTitle');
                            }
                        } else {
                            // Action dropdown item
                            if (isPublished) {
                                $el.html('<i class="fa fa-eye-slash text-warning"></i> <span class="pub-action-text">Unpublish Result</span>');
                            } else {
                                $el.html('<i class="fa fa-eye text-success"></i> <span class="pub-action-text">Publish Result</span>');
                            }
                        }
                    });
                } else {
                    errorMsg(response.message || 'Operation failed');
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function() {
                errorMsg('Something went wrong. Please try again.');
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });
</script>