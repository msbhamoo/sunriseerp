<?php $this->load->view('layout/cbseexam_css.php'); ?>
<style>
    @media print {
        .content-wrapper { margin-left: 0 !important; }
        .main-header, .main-sidebar, .box-header, .main-footer { display: none !important; }
        .box { border: none !important; box-shadow: none !important; }
        .page-break { page-break-after: always; }
    }
    .slip-container { display: flex; flex-wrap: wrap; gap: 15px; justify-content: center; margin-bottom: 20px;}
    .slip-card { border: 2px solid #000; width: 31%; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
    .slip-header { text-align: center; border-bottom: 1px solid #333; margin-bottom: 10px; padding-bottom: 5px; font-weight: bold; }
    .slip-body p { margin-bottom: 5px; font-size: 16px; }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-user"></i> Student-wise Slips</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Student Seating Slips: <?php echo $allocation['exam_name']; ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm" onclick="window.print();"><i class="fa fa-print"></i> Print All Slips</button>
                    <a href="<?php echo site_url('cbseexam/seatingreport') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="box-body">
                <div class="slip-container">
                <?php foreach ($rooms as $room) { 
                    foreach ($room['students'] as $student) {
                ?>
                    <div class="slip-card">
                        <div class="slip-header">
                            SEATING SLIP<br>
                            <small><?php echo $allocation['exam_name']; ?></small>
                        </div>
                        <div class="slip-body">
                            <p><strong>Name:</strong> <?php echo $student['firstname'] . ' ' . $student['lastname']; ?></p>
                            <p><strong>Class (Sec):</strong> <?php echo $student['class_name'] . ' (' . $student['section_name'] . ')'; ?></p>
                            <p><strong>Roll No:</strong> <?php echo $student['roll_no']; ?></p>
                            <hr>
                            <p><strong>Room No:</strong> <?php echo $room['room_number']; ?> (<?php echo $room['building_name']; ?>)</p>
                            <p style="font-size: 20px; text-align: center;">
                                <strong>Seat No: <?php echo $student['seat_number']; ?></strong><br>
                                <small style="font-size: 14px;">(Room: <?php echo $room['room_number']; ?>)</small>
                            </p>
                        </div>
                    </div>
                <?php 
                    }
                } ?>
                </div>
            </div>
        </div>
    </section>
</div>
