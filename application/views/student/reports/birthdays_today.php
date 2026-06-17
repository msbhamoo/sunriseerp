<style>
    .offcanvas-right { position: fixed; top: 0; right: -800px; width: 800px; height: 100vh; background: #fff; z-index: 1050; box-shadow: -2px 0 8px rgba(0,0,0,0.1); transition: right 0.3s ease; overflow-y: auto; }
    .offcanvas-right.open { right: 0; }
    .offcanvas-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1040; display: none; }
    .offcanvas-overlay.open { display: block; }
    .offcanvas-header { padding: 15px; border-bottom: 1px solid #e5e5e5; background: #f8f9fa; display: flex; justify-content: space-between; align-items: center; }
    .offcanvas-body { padding: 15px; }
    .table-custom-header thead th { background-color: #4caf50; color: white; border: 1px solid #45a049; }
    .title-area { display: flex; align-items: center; margin-bottom: 15px; }
    .title-area h2 { margin: 0; font-size: 22px; font-weight: 600; margin-left: 15px; }
    .breadcrumb-custom { margin-left: 45px; font-size: 12px; color: #555; }
    
    /* Birthday Poster Styles */
    .bday-poster-container {
        width: 100%;
        max-width: 500px;
        margin: 0 auto;
        position: relative;
        text-align: center;
        background: linear-gradient(135deg, #fceabb 0%, #f8b500 100%);
        border-radius: 15px;
        padding: 30px 20px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        color: #333;
        font-family: 'Arial', sans-serif;
    }
    .bday-poster-header {
        font-size: 32px;
        font-weight: bold;
        color: #d32f2f;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .bday-photo {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid #fff;
        object-fit: cover;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        margin-bottom: 15px;
    }
    .bday-name {
        font-size: 28px;
        font-weight: 700;
        margin: 10px 0 5px;
        color: #2c3e50;
    }
    .bday-class {
        font-size: 18px;
        color: #555;
        margin-bottom: 25px;
    }
    .bday-message {
        font-size: 18px;
        font-style: italic;
        line-height: 1.5;
        margin-bottom: 30px;
    }
    .bday-share-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-top: 20px;
    }
    .btn-whatsapp {
        background-color: #25D366;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        transition: background-color 0.3s;
    }
    .btn-whatsapp:hover { background-color: #128C7E; color: #fff; }
    
    .btn-instagram {
        background: #f09433; 
        background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%); 
        background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); 
        background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); 
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-weight: bold;
        transition: opacity 0.3s;
    }
    .btn-instagram:hover { opacity: 0.9; color: #fff; }
</style>

<div class="offcanvas-overlay" id="studentListOverlay"></div>

<div class="content-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-8">
                <div class="title-area">
                    <a href="<?php echo site_url('student/dashboard'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></a>
                    <h2>Birthdays Today</h2>
                </div>
                <div class="breadcrumb-custom">Home > Student > Birthdays</div>
            </div>
            <div class="col-md-4 text-right">
                <button class="btn btn-success"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-success"><i class="fa fa-file-excel-o"></i></button>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-body">
                <form action="<?php echo site_url('studentreport/birthdays'); ?>" method="POST">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date <small class="req"> *</small></label>
                                <?php $d = (isset($date)) ? date($this->customlib->getSchoolDateFormat(), strtotime($date)) : date($this->customlib->getSchoolDateFormat()); ?>
                                <input type="text" class="form-control date" name="date" value="<?php echo $d; ?>" required readonly>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group" style="margin-top: 25px;">
                                <button type="submit" class="btn btn-success" style="background-color: #4caf50; border-color: #4caf50;">Get</button>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if (isset($results)) { ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-custom-header">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ADMISSION NO</th>
                                <th>NAME</th>
                                <th>CLASS (SECTION)</th>
                                <th>FATHER NAME</th>
                                <th>CONTACT</th>
                                <th>DOB</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($results)) { ?>
                            <tr>
                                <td colspan="8" class="text-center text-danger">No birthdays found</td>
                            </tr>
                            <?php } else {
                                $count = 1;
                                foreach ($results as $row) {
                                    $name = $row['firstname'] . ' ' . $row['lastname'];
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row['admission_no']; ?></td>
                                <td><?php echo $name; ?></td>
                                <td><?php echo $row['class'] . ' (' . $row['section'] . ')'; ?></td>
                                <td><?php echo $row['father_name']; ?></td>
                                <td><?php echo $row['mobileno']; ?></td>
                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($row['dob'])); ?></td>
                                <td>
                                    <button class="btn btn-primary btn-xs" onclick="openBirthdayPoster(<?php echo htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); ?>)" title="View Poster">
                                        <i class="fa fa-gift"></i> View Poster
                                    </button>
                                </td>
                            </tr>
                            <?php } } ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Right Side Panel for Birthday Poster -->
    <div class="offcanvas-right" id="studentListOffcanvas" style="width: 550px; right: -550px;">
        <div class="offcanvas-header">
            <h4 class="m-0"><b>Birthday Wishes</b></h4>
            <div>
                <button type="button" class="close" style="font-size:28px;" onclick="closeOffcanvas()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
        <div class="offcanvas-body">
            
            <div class="bday-poster-container" id="posterContainer">
                <div class="bday-poster-header">Happy Birthday!</div>
                <img src="" class="bday-photo" id="posterPhoto" onerror="this.src='<?php echo base_url("uploads/student_images/no_image.png"); ?>'">
                <div class="bday-name" id="posterName">Student Name</div>
                <div class="bday-class" id="posterClass">Class</div>
                <div class="bday-message">
                    "Wishing you a day filled with happiness and a year filled with joy. May all your dreams come true!"
                </div>
            </div>
            
            <div class="bday-share-buttons">
                <button class="btn btn-whatsapp" onclick="shareOnWhatsApp()"><i class="fa fa-whatsapp"></i> Share on WhatsApp</button>
                <button class="btn btn-instagram" onclick="shareOnInstagram()"><i class="fa fa-instagram"></i> Share on Instagram</button>
            </div>
            
        </div>
    </div>
</div>

<script>
    var currentStudent = null;
    var baseUrl = "<?php echo base_url(); ?>";

    $(document).ready(function() {
        if ($.fn.datepicker) {
            $('.date').datepicker({
                format: date_format,
                autoclose: true,
                todayHighlight: true
            });
        }
        $('#studentListOverlay').click(function() { closeOffcanvas(); });
    });

    function openBirthdayPoster(student) {
        currentStudent = student;
        
        var name = student.firstname + (student.lastname ? ' ' + student.lastname : '');
        var classStr = student.class + ' (' + student.section + ')';
        var photoUrl = student.image ? baseUrl + student.image : baseUrl + "uploads/student_images/no_image.png";
        
        $('#posterName').text(name);
        $('#posterClass').text(classStr);
        $('#posterPhoto').attr('src', photoUrl);
        
        $('#studentListOffcanvas').addClass('open');
        $('#studentListOverlay').addClass('open');
    }

    function closeOffcanvas() {
        $('#studentListOffcanvas').removeClass('open');
        $('#studentListOverlay').removeClass('open');
    }
    
    function shareOnWhatsApp() {
        if(!currentStudent) return;
        var name = currentStudent.firstname + (currentStudent.lastname ? ' ' + currentStudent.lastname : '');
        var message = "Happy Birthday " + name + "! Wishing you a fantastic day ahead. 🎉🎂";
        var whatsappUrl = "https://wa.me/?text=" + encodeURIComponent(message);
        window.open(whatsappUrl, '_blank');
    }
    
    function shareOnInstagram() {
        // Instagram doesn't have a direct share URL for pre-filled text or images via web intent like WhatsApp.
        // Usually, users have to download the poster and upload it manually.
        alert("To share on Instagram, please take a screenshot or download the poster and share it via the Instagram app.");
    }
</script>
