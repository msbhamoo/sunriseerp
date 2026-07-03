<?php $this->load->view('layout/cbseexam_css.php'); ?>
<style>
    @media print {
        .content-wrapper { margin-left: 0 !important; }
        .main-header, .main-sidebar, .box-header, .main-footer { display: none !important; }
        .box { border: none !important; box-shadow: none !important; }
        .page-break { page-break-after: always; }
    }
    .room-box { border: 1px solid #ddd; margin-bottom: 20px; padding: 15px; }
    .room-header { text-align: center; border-bottom: 2px solid #333; margin-bottom: 15px; padding-bottom: 10px; }
    .seat-grid { display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; }
    .seat-item { border: 1px solid #999; padding: 10px; width: 120px; text-align: center; background: #f9f9f9; -webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact; transition: all 0.2s; position: relative; }
    .seat-item.selectable { cursor: pointer; }
    .seat-item.selectable:hover { transform: scale(1.05); }
    .seat-item.selected { border: 3px solid #000; box-shadow: 0 0 10px rgba(0,0,0,0.5); transform: scale(1.05); z-index: 10; }
    .seat-number { font-weight: bold; font-size: 16px; margin-bottom: 5px; }
    .student-roll { font-size: 14px; }
    
    .fab-editing-bar { position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-top: 2px solid #3c8dbc; padding: 15px 30px; display: none; z-index: 9999; box-shadow: 0 -5px 15px rgba(0,0,0,0.1); justify-content: space-between; align-items: center; }
    @media print {
        .fab-editing-bar, #editModeBtn { display: none !important; }
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-th"></i> Room-wise Seating Plan</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Seating Plan: <?php echo $allocation['exam_name']; ?></h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-warning btn-sm" id="editModeBtn"><i class="fa fa-pencil"></i> Enable Adjustment Mode</button>
                    <button class="btn btn-default btn-sm" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
                    <a href="<?php echo site_url('cbseexam/seatingreport') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="box-body">
                <?php 
                $class_colors = array();
                $pastel_palette = array('#FFB3BA', '#FFDFBA', '#FFFFBA', '#BAFFC9', '#BAE1FF', '#E2BAFF', '#FFBAF3', '#C2F2D0', '#FFC8A2', '#D5AAFF');
                $color_index = 0;
                foreach ($rooms as $room) {
                    if (!empty($room['students'])) {
                        foreach ($room['students'] as $student) {
                            $class_key = $student['class_name'] . " (" . $student['section_name'] . ")";
                            if (!isset($class_colors[$class_key])) {
                                $class_colors[$class_key] = $pastel_palette[$color_index % count($pastel_palette)];
                                $color_index++;
                            }
                        }
                    }
                }
                ?>
                <?php foreach ($rooms as $room) { 
                    if (empty($room['students'])) continue; 
                ?>
                <div class="room-box page-break">
                    <div class="room-header">
                        <h2>Room: <?php echo $room['room_number']; ?> (<?php echo $room['building_name']; ?>)</h2>
                        <p><strong>Capacity:</strong> <?php echo $room['seating_capacity']; ?> | <strong>Allocated:</strong> <?php echo count($room['students']); ?></p>
                        <p><strong>Invigilators:</strong> 
                            <?php 
                            if (!empty($room['invigilators'])) {
                                $invs = [];
                                foreach ($room['invigilators'] as $inv) {
                                    $invs[] = $inv['staff_name'] . " " . $inv['staff_surname'];
                                }
                                echo implode(", ", $invs);
                            } else {
                                echo "None Assigned";
                            }
                            ?>
                        </p>
                    </div>
                    <div class="seat-grid">
                        <?php foreach ($room['students'] as $student) { 
                            $class_key = $student['class_name'] . " (" . $student['section_name'] . ")";
                            $bg_color = $class_colors[$class_key];
                        ?>
                            <div class="seat-item" data-seat-id="<?php echo $student['id']; ?>" style="background-color: <?php echo $bg_color; ?>;">
                                <div class="seat-number"><?php echo $student['formatted_seat_number']; ?></div>
                                <div class="student-roll"><?php echo $student['roll_no']; ?></div>
                                <div class="student-class"><small><?php echo $class_key; ?></small></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<div class="fab-editing-bar" id="editingBar" style="display: none;">
    <div>
        <strong><span id="selectedCount">0</span> Seats Selected</strong>
        <span class="text-muted" style="margin-left: 15px;">Click seats to select them.</span>
    </div>
    <div style="display: flex; gap: 15px; align-items: center;">
        <button class="btn btn-primary" id="btnSwap" disabled><i class="fa fa-exchange"></i> Swap Seats (Needs exactly 2)</button>
        
        <div class="input-group" style="width: 300px;">
            <select class="form-control" id="targetRoom">
                <option value="">-- Move to Room --</option>
                <?php foreach ($rooms as $r) { 
                    if (empty($r['room_number'])) continue;
                ?>
                    <option value="<?php echo $r['id']; ?>"><?php echo $r['room_number']; ?> (<?php echo $r['building_name']; ?>)</option>
                <?php } ?>
            </select>
            <span class="input-group-btn">
                <button class="btn btn-success" id="btnMove" disabled><i class="fa fa-arrow-right"></i> Move</button>
            </span>
        </div>
        
        <button class="btn btn-default" id="btnCancelEdit"><i class="fa fa-times"></i> Cancel</button>
    </div>
</div>

<script>
$(document).ready(function() {
    var editMode = false;
    
    $('#editModeBtn').click(function() {
        editMode = true;
        $('.seat-item').addClass('selectable');
        $('#editingBar').css('display', 'flex');
        $(this).hide();
    });
    
    $('#btnCancelEdit').click(function() {
        editMode = false;
        $('.seat-item').removeClass('selectable selected');
        $('#editingBar').hide();
        $('#editModeBtn').show();
        updateSelectionState();
    });
    
    $('.seat-item').click(function() {
        if (!editMode) return;
        $(this).toggleClass('selected');
        updateSelectionState();
    });
    
    function updateSelectionState() {
        var count = $('.seat-item.selected').length;
        $('#selectedCount').text(count);
        
        if (count === 2) {
            $('#btnSwap').prop('disabled', false);
        } else {
            $('#btnSwap').prop('disabled', true);
        }
        
        if (count > 0 && $('#targetRoom').val() !== '') {
            $('#btnMove').prop('disabled', false);
        } else {
            $('#btnMove').prop('disabled', true);
        }
    }
    
    $('#targetRoom').change(function() {
        updateSelectionState();
    });
    
    $('#btnSwap').click(function() {
        var selected = $('.seat-item.selected');
        if (selected.length !== 2) return;
        
        var seat1 = $(selected[0]).data('seat-id');
        var seat2 = $(selected[1]).data('seat-id');
        
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Swapping...');
        
        $.ajax({
            url: '<?php echo site_url("cbseexam/seatingreport/ajax_swap_seats"); ?>',
            type: 'POST',
            data: { seat1_id: seat1, seat2_id: seat2 },
            dataType: 'json',
            success: function(res) {
                if(res.status) {
                    toastr.success(res.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    toastr.error(res.msg);
                    $('#btnSwap').prop('disabled', false).html('<i class="fa fa-exchange"></i> Swap Seats (Needs exactly 2)');
                }
            }
        });
    });
    
    $('#btnMove').click(function() {
        var selected = $('.seat-item.selected');
        if (selected.length === 0) return;
        
        var targetRoom = $('#targetRoom').val();
        if (!targetRoom) return;
        
        var seatIds = [];
        selected.each(function() {
            seatIds.push($(this).data('seat-id'));
        });
        
        $(this).prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Moving...');
        
        $.ajax({
            url: '<?php echo site_url("cbseexam/seatingreport/ajax_move_students"); ?>',
            type: 'POST',
            data: { seat_ids: seatIds, target_room_assignment_id: targetRoom },
            dataType: 'json',
            success: function(res) {
                if(res.status) {
                    toastr.success(res.msg);
                    setTimeout(function() { location.reload(); }, 1000);
                } else {
                    toastr.error(res.msg);
                    $('#btnMove').prop('disabled', false).html('<i class="fa fa-arrow-right"></i> Move');
                }
            }
        });
    });
});
</script>
