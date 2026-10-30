<?php $this->load->view('layout/cbseexam_css.php'); ?>
<style>
    @media print {
        html, body, .wrapper, .content-wrapper, .content, .box, .box-body { 
            margin: 0 !important; 
            padding: 0 !important; 
            background: #fff !important;
            min-height: 0 !important;
        }
        .main-header, .main-sidebar, .content-header, .box-header, .main-footer { display: none !important; }
        .box { border: none !important; box-shadow: none !important; }
        .page-break { page-break-after: always; page-break-before: auto !important; }
        .page-break:last-of-type { page-break-after: auto; }
        hr { display: none !important; }
    }
    .receipt-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
    }
    .logo-container { width: 80px; flex-shrink: 0; }
    .logo-container img { width: 100%; height: auto; }
    .school-info { flex-grow: 1; text-align: center; padding: 0 15px; }
    .school-name { font-size: 20px; font-weight: 700; margin: 0 0 5px 0; }
    .school-address { font-size: 11px; color: #666; margin: 0 0 5px 0; }
    .school-contact { font-size: 10px; margin: 0; color: #666; }
    
    .sheet-header { text-align: center; margin-bottom: 15px; }
    .sheet-header h4 { font-size: 13px; margin: 5px 0; font-weight: bold; }
    
    .table-bordered { font-size: 12px; }
    .table-bordered > thead > tr > th { padding: 6px; font-size: 12px; background: #f4f4f4 !important; -webkit-print-color-adjust: exact; }
    .table-bordered > tbody > tr > td { padding: 6px; vertical-align: middle; }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-check-square-o"></i> Attendance Sheet</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Attendance Sheet: <?php echo $allocation['exam_name']; ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-default btn-sm" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                    <a href="<?php echo site_url('cbseexam/seatingreport') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="box-body">
                <?php 
                $settinglist = $this->setting_model->get();
                foreach ($rooms as $room) { 
                    if (empty($room['students'])) continue; 
                ?>
                <div class="page-break">
                    
                    <div class="receipt-header">
                        <div class="logo-container">
                            <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
                        </div>
                        <div class="school-info">
                            <h1 class="school-name"><?php echo $settinglist[0]['name']; ?></h1>
                            <div class="school-address"><?php echo $settinglist[0]['address']; ?></div>
                            <p class="school-contact">Email: <?php echo $settinglist[0]['email']; ?> | Mobile No: <?php echo $settinglist[0]['phone']; ?></p>
                        </div>
                        <div style="width: 80px; text-align: right; font-weight: bold; font-size: 12px;">ATTENDANCE</div>
                    </div>

                    <div class="sheet-header">
                        <h4>Exam: <?php echo $allocation['exam_name']; ?> | Date: <?php echo $this->customlib->dateformat($allocation['exam_date']); ?></h4>
                        <h4>Room: <?php echo $room['room_number']; ?> (<?php echo $room['building_name']; ?>)</h4>
                    </div>
                    
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="50">S.No</th>
                                <th width="100">Seat No</th>
                                <th>Student Name</th>
                                <th>Roll No</th>
                                <th>Class (Sec)</th>
                                <th>Signature</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sno = 1;
                            foreach ($room['students'] as $student) { ?>
                                <tr>
                                    <td><?php echo $sno++; ?></td>
                                    <td><strong><?php echo $student['formatted_seat_number']; ?></strong></td>
                                    <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                    <td><?php echo $student['roll_no']; ?></td>
                                    <td><?php echo $student['class_name'] . ' (' . $student['section_name'] . ')'; ?></td>
                                    <td style="height: 50px;"></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 12px;">
                        <div>
                            <strong>Invigilator 1 Signature:</strong> ________________________
                        </div>
                        <div>
                            <strong>Invigilator 2 Signature:</strong> ________________________
                        </div>
                    </div>
                </div>
                <hr style="margin: 30px 0; border-top: 2px dashed #ccc;">
                <?php } ?>
            </div>
        </div>
    </section>
</div>
