<?php if (empty($students)) { ?>
    <div class="alert alert-info text-center" style="margin-bottom: 0;">
        <i class="fa fa-info-circle"></i> No active students are currently assigned to this vehicle.
    </div>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover modern-table">
            <thead>
                <tr>
                    <th>Admission No</th>
                    <th>Student Name</th>
                    <th>Class (Section)</th>
                    <th>Route Title</th>
                    <th>Pickup Point</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student) { ?>
                    <tr>
                        <td><?php echo $student['admission_no']; ?></td>
                        <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                        <td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                        <td><?php echo !empty($student['route_title']) ? $student['route_title'] : '-'; ?></td>
                        <td><?php echo !empty($student['pickup_point_name']) ? $student['pickup_point_name'] : '-'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    
    <div class="text-right" style="margin-top: 15px;">
        <button type="button" class="btn btn-primary btn-sm pull-left" onclick="openAssignModal(<?php echo $vehicle_id; ?>)">
            <i class="fa fa-plus"></i> Assign New Student
        </button>
        <span class="badge badge-success" style="padding: 8px 12px; font-size: 14px; background-color: #10b981;">
            Total Students Assigned: <?php echo count($students); ?>
        </span>
    </div>
<?php } ?>
