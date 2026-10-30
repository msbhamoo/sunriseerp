<?php if (empty($exams_data)) { ?>
    <div class="alert alert-info">No exams found.</div>
<?php } else { ?>
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12 text-right">
            <button class="btn btn-default btn-sm" onclick="window.print()"><i class="fa fa-print"></i> Print All</button>
        </div>
    </div>
    
    <?php foreach ($exams_data as $data) { 
        $exam = $data['exam'];
        $timetable = $data['timetable'];
    ?>
        <div style="border: 1px solid #e0e0e0; border-radius: 4px; padding: 15px; margin-bottom: 20px; background-color: #f9f9f9;">
            <div class="row" style="margin-bottom: 10px;">
                <div class="col-md-12 text-center">
                    <h4 style="margin:0; font-weight:bold; color: #333;"><?php echo $exam['name']; ?></h4>
                    <?php if (!empty($exam['class_sections_str'])) { ?>
                        <div style="color:#0288d1; font-weight: 600; font-size: 13px; margin-top: 4px;">
                            <i class="fa fa-graduation-cap"></i> <?php echo $exam['class_sections_str']; ?>
                        </div>
                    <?php } ?>
                    <p style="color:#666; font-size: 13px; margin-top: 5px;">Timetable Schedule</p>
                </div>
            </div>
            
            <?php if (empty($timetable)) { ?>
                <div class="alert alert-warning" style="margin-bottom: 0;">No timetable scheduled for this exam yet.</div>
            <?php } else { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" style="background-color: #fff;">
                        <thead>
                            <tr style="background-color: #e8e8e8;">
                                <th>Date</th>
                                <th>Subject</th>
                                <th>Start Time</th>
                                <th>Duration (Mins)</th>
                                <th>Room No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($timetable as $row) { ?>
                                <tr>
                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($row->date)); ?></td>
                                    <td><?php echo $row->subject_name . ($row->subject_code ? ' (' . $row->subject_code . ')' : ''); ?></td>
                                    <td><?php echo $row->time_from; ?></td>
                                    <td><?php echo $row->duration; ?></td>
                                    <td><?php echo $row->room_no; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
<?php } ?>
