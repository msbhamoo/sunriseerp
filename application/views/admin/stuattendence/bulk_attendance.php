<style type="text/css">
    .radio {
        padding-left: 20px;
    }

    .radio label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 5px;
    }

    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid var(--bs-primary);
        border-radius: 50%;
        background-color: var(--bs-input-bg);
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out;
    }

    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
    }

    .radio input[type="radio"] {
        opacity: 0;
        z-index: 1;
    }

    .radio input[type="radio"]:focus+label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
    }

    .radio input[type="radio"]:checked+label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    .radio input[type="radio"]:disabled+label {
        opacity: 0.65;
    }

    .radio input[type="radio"]:disabled+label::before {
        cursor: not-allowed;
    }

    .radio.radio-inline {
        margin-top: 0;
    }

    .radio-primary input[type="radio"]+label::after {
        background-color: #337ab7;
    }

    .radio-primary input[type="radio"]:checked+label::before {
        border-color: color-mix(in srgb, var(--primary-white), transparent 40%);
    }

    .radio-primary input[type="radio"]:checked+label::after {
        background-color: color-mix(in srgb, var(--primary-white), transparent 40%);
    }

    .radio-danger input[type="radio"]+label::after {
        background-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::before {
        border-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::after {
        background-color: #d9534f;
    }

    .radio-info input[type="radio"]+label::after {
        background-color:var(--bs-primary);
    }

    .radio-info input[type="radio"]:checked+label::before {
        border-color:var(--bs-primary);
    }

    .radio-info input[type="radio"]:checked+label::after {
        background-color:var(--bs-primary);
    }

    @media (max-width:767px) {
        .radio.radio-inline {
            display: inherit;
        }
    }    
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small>Bulk Attendance</small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">

                <?php if ($this->session->flashdata('msg')) { ?>
                    <?php echo $this->session->flashdata('msg');
                    $this->session->unset_userdata('msg'); ?>
                <?php } ?>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> Mark Entire School Attendance (Bulk)</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/stuattendence/index'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i> Back to Class Attendance</a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('admin/stuattendence/bulk_save') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bulk_date">
                                            <?php echo $this->lang->line('attendance_date'); ?>
                                        </label><small class="req"> *</small>
                                        <input id="bulk_date" name="date" placeholder="" type="text" class="form-control date bulk_date_check" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                    </div>
                                    <div id="bulk_sunday_message" class="text-danger" style="display:none; font-weight:bold;">Today is Sunday, so mark it a holiday</div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('attendance'); ?></label><small class="req"> *</small>
                                        <br/>
                                        <?php
                                        if (isset($attendencetypeslist)) {
                                            $types_to_loop = $attendencetypeslist;
                                        } else {
                                            $types_to_loop = $this->attendencetype_model->get();
                                        }
                                        foreach ($types_to_loop as $key => $type) {
                                            $att_type = str_replace(" ", "_", strtolower($type['type']));
                                        ?>
                                            <div class="radio radio-info radio-inline">
                                                <input type="radio" name="attendencetype" value="<?php echo $type['id'] ?>" id="bulk_attendencetype<?php echo $type['id'] ?>" <?php echo ($att_type == 'holiday' ? 'class="bulk_holiday_radio"' : ''); ?>>
                                                <label for="bulk_attendencetype<?php echo $type['id'] ?>">
                                                    <?php echo $this->lang->line($att_type); ?>
                                                </label>
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-sm pull-right" style="margin-top:25px;" onclick="return confirm('Are you sure you want to mark attendance for ALL active students in the school?');"><i class="fa fa-save"></i> Save for Entire School</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">

// Convert a PHP date() format string (e.g. "d M Y") to the equivalent
// moment.js format (e.g. "DD MMM YYYY"). Uppercasing the PHP format is wrong:
// PHP "M" (short month name, "Aug") is not moment "M" (numeric month), which
// caused strict parsing to fail and a lenient fallback to report the wrong
// weekday.
function phpToMomentFormat(phpFormat) {
    var map = {
        d: 'DD', j: 'D', D: 'ddd', l: 'dddd', N: 'E', w: 'e',
        m: 'MM', n: 'M', M: 'MMM', F: 'MMMM',
        Y: 'YYYY', y: 'YY',
        H: 'HH', G: 'H', h: 'hh', g: 'h', i: 'mm', s: 'ss', A: 'A', a: 'a'
    };
    var out = '';
    for (var i = 0; i < phpFormat.length; i++) {
        var ch = phpFormat[i];
        out += map.hasOwnProperty(ch) ? map[ch] : ch;
    }
    return out;
}

function parseDateValAndCheckSunday(dateVal) {
    if (!dateVal) return false;

    var format = '<?php echo $this->customlib->getSchoolDateFormat(); ?>';

    if (typeof moment !== 'undefined') {
        var momentFormat = phpToMomentFormat(format);
        var m = moment(dateVal, momentFormat, true); // strict parse with correct tokens
        if (m.isValid()) {
            return m.day() === 0;
        }
        // Fallback: try a few common explicit formats (strict).
        var m2 = moment(dateVal, ["DD MMM YYYY", "DD-MM-YYYY", "YYYY-MM-DD", "DD.MM.YYYY", "DD/MM/YYYY", "MM/DD/YYYY"], true);
        if (m2.isValid()) {
            return m2.day() === 0;
        }
    }

    // Last-resort native fallback.
    var d = new Date(dateVal);
    if (!isNaN(d.getTime())) {
        return d.getDay() === 0;
    }
    return false;
}

function checkSundayAndShowMessage() {
    var dateInputId = 'bulk_date';
    var messageContainerId = 'bulk_sunday_message';
    var dateVal = $('#' + dateInputId).val();
    
    if (dateVal) {
        var isSunday = parseDateValAndCheckSunday(dateVal);
        
        if (isSunday) {
            $('#' + messageContainerId).show();
            $('.bulk_holiday_radio').prop('checked', true);
        } else {
            $('#' + messageContainerId).hide();
        }
    }
}

$(document).ready(function() {
    // Check initially
    checkSundayAndShowMessage();

    // Bootstrap datepicker dp.change or change
    $('.date').on('change dp.change', function(e) {
        checkSundayAndShowMessage();
    });
});

</script>
