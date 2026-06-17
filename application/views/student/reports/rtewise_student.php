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
</style>

<div class="offcanvas-overlay" id="studentListOverlay"></div>

<div class="content-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-8">
                <div class="title-area">
                    <a href="<?php echo site_url('student/dashboard'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i></a>
                    <h2>RTE Students</h2>
                </div>
                <div class="breadcrumb-custom">Home > Student > RTE-students</div>
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
                <?php if (isset($results)) { ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-custom-header">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>CLASS</th>
                                <th>STREAM</th>
                                <th>SECTION</th>
                                <th>TOTAL RTE STUDENTS</th>
                                <th>View List</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($results)) { ?>
                            <tr>
                                <td colspan="6" class="text-center text-danger">No records found</td>
                            </tr>
                            <?php } else {
                                $count = 1;
                                foreach ($results as $row) {
                            ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo $row['class']; ?></td>
                                <td>Common</td>
                                <td><?php echo $row['section']; ?></td>
                                <td><?php echo $row['total']; ?></td>
                                <td>
                                    <button class="btn btn-default btn-xs" onclick='openStudentList(<?php echo json_encode($row); ?>)' title="View List">
                                        <i class="fa fa-eye text-success"></i>
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

    <div class="offcanvas-right" id="studentListOffcanvas">
        <div class="offcanvas-header">
            <h4 class="m-0"><b id="panel_title">Class - Section</b></h4>
            <div>
                <button class="btn btn-success btn-sm"><i class="fa fa-print"></i></button>
                <button class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i></button>
                <button type="button" class="close" style="margin-left:15px; font-size:28px;" onclick="closeOffcanvas()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
        </div>
        <div class="offcanvas-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-custom-header" id="panelStudentTable">
                    <thead>
                        <tr>
                            <th>SERIAL NO.</th>
                            <th>SR NO.</th>
                            <th>NAME</th>
                            <th>FATHER NAME</th>
                            <th>CONTACT</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#studentListOverlay').click(function() { closeOffcanvas(); });
    });

    function openStudentList(rowData) {
        $('#panel_title').text(rowData.class + ' - Common - ' + rowData.section);
        var tbody = '';
        var sr = 1;
        if(rowData.students && rowData.students.length > 0) {
            $.each(rowData.students, function(i, student) {
                var name = student.firstname + (student.lastname ? ' ' + student.lastname : '');
                var contact = student.mobileno ? student.mobileno : '';
                var father = student.father_name ? student.father_name : '';
                var adm_no = student.admission_no ? student.admission_no : '';
                
                tbody += '<tr>';
                tbody += '<td>' + sr++ + '</td>';
                tbody += '<td>' + adm_no + '</td>';
                tbody += '<td>' + name + '</td>';
                tbody += '<td>' + father + '</td>';
                tbody += '<td>' + contact + '</td>';
                tbody += '</tr>';
            });
        } else {
            tbody = '<tr><td colspan="5" class="text-center">No students found</td></tr>';
        }
        $('#panelStudentTable tbody').html(tbody);
        $('#studentListOffcanvas').addClass('open');
        $('#studentListOverlay').addClass('open');
    }

    function closeOffcanvas() {
        $('#studentListOffcanvas').removeClass('open');
        $('#studentListOverlay').removeClass('open');
    }
</script>
