<div class="box box-primary box-solid">
    <div class="box-header with-border">
        <h3 class="box-title"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></h3>
    </div>
    <div class="box-body">
        <p><strong><?php echo $this->lang->line('admission_no'); ?>:</strong> <?php echo $student['admission_no']; ?></p>
        <p><strong><?php echo $this->lang->line('class'); ?>:</strong> <?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></p>
        
        <hr>
        
        <h4><i class="fa fa-calendar-check-o text-success"></i> <?php echo $this->lang->line('attendance_percentage'); ?></h4>
        <!-- Placeholder for actual calculated attendance -->
        <div class="progress">
            <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100" style="width: 85%">
                85% (Sample)
            </div>
        </div>

        <hr>

        <h4><i class="fa fa-book text-info"></i> <?php echo $this->lang->line('recent_exam_results'); ?></h4>
        <!-- Placeholder for actual exam results -->
        <ul class="list-group">
            <li class="list-group-item">
                <span class="badge bg-green">A</span>
                Term 1 Examination
            </li>
            <li class="list-group-item">
                <span class="badge bg-blue">B+</span>
                Unit Test 2
            </li>
        </ul>

        <hr>

        <h4><i class="fa fa-comments text-warning"></i> <?php echo $this->lang->line('previous_remarks'); ?></h4>
        <div class="alert alert-info">
            <p><i>"Student needs to focus more on Mathematics. Behavior in class is excellent."</i></p>
            <small>- PTM on 2026-05-10</small>
        </div>
    </div>
</div>
