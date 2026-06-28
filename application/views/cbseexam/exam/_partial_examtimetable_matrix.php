<a class="btn btn-primary btn-xs pull-right mt8 mr-1 hide-print" id="print" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv('print_matrix')"><i class="fa fa-print"></i></a>
<div id="print_matrix">

<style type="text/css">
    @media print {
        .print-header-container {
            display: flex !important;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .print-logo {
            width: 80px;
        }
        .print-logo img {
            width: 100%;
            height: auto;
        }
        .print-school-info {
            flex-grow: 1;
            text-align: center;
            padding: 0 15px;
        }
        .print-school-name {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .print-school-address {
            font-size: 12px;
            margin: 5px 0;
        }
        .print-school-contact {
            font-size: 11px;
            margin: 0;
        }
        .print-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .hide-print {
            display: none !important;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black !important;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
    }
</style>

    <div class="print-header-container" style="display:none;">
        <div class="print-logo">
            <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
        </div>
        <div class="print-school-info">
            <h1 class="print-school-name"><?php echo $settinglist[0]['name']; ?></h1>
            <div class="print-school-address"><?php echo $settinglist[0]['address']; ?></div>
            <div class="print-school-contact">Email: <?php echo $settinglist[0]['email']; ?> &nbsp; Mobile No: <?php echo $settinglist[0]['phone']; ?></div>
            <div class="print-title">EXAM TIME TABLE: <?php echo $exam_name; ?> <?php echo isset($class_name) ? " - " . $class_name : ""; ?></div>
        </div>
    </div>

    <h4 class="pagetitleh2 border-b-none hide-print">
        <?php echo $exam_name; ?> <?php echo isset($class_name) ? " - " . $class_name : ""; ?>
    </h4>

    <?php if (empty($matrix['dates'])) { ?>
        <div class="alert alert-danger">
            <?php echo $this->lang->line('no_record_found'); ?>
        </div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-stripped table-b">
                <thead>
                    <tr>
                        <th><?php echo $this->lang->line('class'); ?></th>
                        <?php foreach ($matrix['dates'] as $date) { ?>
                            <th class="text-center"><?php echo $this->customlib->dateformat($date); ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matrix['classes'] as $class_name => $dates_data) { ?>
                        <tr>
                            <td><strong><?php echo $class_name; ?></strong></td>
                            <?php foreach ($matrix['dates'] as $date) { ?>
                                <td class="text-center">
                                    <?php 
                                    if (isset($dates_data[$date])) {
                                        foreach ($dates_data[$date] as $subject) {
                                            $subject_code = ($subject['subject_code'] != "") ? " (" . $subject['subject_code'] . ")" : "";
                                            echo "<div>" . $subject['subject_name'] . $subject_code . "</div>";
                                            if ($subject['time_from']) {
                                                echo "<div class='text-muted small'>" . $subject['time_from'] . "</div>";
                                            }
                                        }
                                    } else {
                                        echo "-";
                                    }
                                    ?>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>
