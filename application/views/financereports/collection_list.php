<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header"></section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box removeboxmius">
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form role="form" action="<?php echo site_url('financereports/collection_list') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            
                            <div class="col-sm-4 col-lg-4 col-md-4">
                                <div class="form-group" style="position:relative;">
                                    <label>Search to collect Fee</label>
                                    <input type="text" name="search_student" id="search_student_ajax" class="form-control" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>" autocomplete="off">
                                    <div id="ajax_student_search_results_container" class="custom-ajax-search-container"></div>
                                </div>
                            </div>

                            <div class="col-sm-4 col-lg-4 col-md-4">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('search_duration'); ?><small class="req"> *</small></label>
                                    <select class="form-control" name="search_type" onchange="showdate(this.value)">

                                        <?php foreach ($searchlist as $key => $search) { ?>
                                            <option value="<?php echo $key ?>" <?php
                                            if ((isset($search_type)) && ($search_type == $key)) {
                                                echo "selected";
                                            }
                                            ?>><?php echo $search ?></option>
                                        <?php }?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('search_type'); ?></span>
                                </div>
                            </div>

                            <div id='date_result'>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" name="search" value="search_filter" id="search_btn" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php if (empty($results)) { ?>
                        <div class="box-header ptbnull">
                            <div class="alert alert-info">
                               <?php echo $this->lang->line('no_record_found'); ?>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="">
                            <div class="box-header ptbnull"></div>
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><i class="fa fa-money"></i> Fee Collection List</h3>
                                <div class="box-tools pull-right" style="margin-top: 5px;">
                                    <input type="text" id="table_filter" class="form-control input-sm" placeholder="Search in list...">
                                </div>
                            </div>
                            
                            <div class="box-body table-responsive" id="transfee">
                                <div id="printhead"><center><b><h4>Fee Collection List<br><?php $this->customlib->get_postmessage(); ?></h4></b></center></div>
                                <div class="download_label">Fee Collection List<br><?php $this->customlib->get_postmessage(); ?></div>

                                <a class="btn btn-default btn-xs pull-right" id="print" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv()" ><i class="fa fa-print"></i></a>
                                <a class="btn btn-default btn-xs pull-right" id="btnExport" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('download_excel'); ?>"  onclick="fnExcelReport();"> <i class="fa fa-file-excel-o"></i> </a>

                                <table class="table table-striped table-bordered table-hover " id="headerTable">
                                    <thead class="header">
                                        <tr>
                                            <th>Sr. No.</th>
                                            <th>Receipt No.</th>
                                            <th>Admission No.</th>
                                            <th>Deposit Date</th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th>Father Name</th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th>Fees Type</th>
                                            <th style="mso-number-format:'\@'" class="text text-right"><?php echo $this->lang->line('amount'); ?> <span><?php echo "(" . $currency_symbol . ")"; ?></span></th>
                                            <th><?php echo $this->lang->line('mode'); ?></th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       <?php
                                        $count = 1;
                                        $grdTotalLabel = array();
                                     
                                        foreach ($results as $collect) {           
                                            $t1 = two_digit_float($collect['amount']) + two_digit_float($collect['amount_fine']);
                                            $grdTotalLabel[] = $t1;
                                            $status_class = ($collect['custom_receipt_status'] == 'Reversed') ? 'text-danger' : 'text-success';
                                            $strike_class = ($collect['custom_receipt_status'] == 'Reversed') ? 'style="text-decoration: line-through;"' : '';
                                        ?>
                                            <tr <?php echo $strike_class; ?>>
                                                <td><?php echo $count++; ?></td>
                                                <td><?php echo $collect['custom_receipt_no']; ?></td>                
                                                <td><?php echo $collect['admission_no']; ?></td>                
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($collect['date'])); ?></td>                
                                                <td><?php echo $this->customlib->getFullName($collect['firstname'], $collect['middlename'], $collect['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>                
                                                <td><?php echo isset($collect['father_name']) ? $collect['father_name'] : ''; ?></td>                
                                                <td><?php echo $collect['class'] . " (" . $collect['section'] . ")";    ?></td>                
                                                <td>
                                                    <?php
                                                        if ( $collect['is_system']) {
                                                            echo $this->lang->line($collect['type']);
                                                        } else {
                                                            echo $collect['type'] ;
                                                            echo " (".$collect['code'].")";
                                                        }    
                                                    ?>
                                                </td>
                                                <td class="text text-right">
                                                    <?php echo two_digit_float($t1); ?>
                                                </td>                              
                                                <td><?php echo $this->lang->line(strtolower($collect['payment_mode'])); ?></td>
                                                <td>
                                                    <?php if($collect['custom_receipt_status'] == 'Reversed') { ?>
                                                        <span class="label label-danger">Reverted</span>
                                                    <?php } else { ?>
                                                        <span class="label label-success">Received</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if($collect['custom_receipt_status'] == 'Collected') { ?>
                                                        <button class="btn btn-default btn-xs" onclick="printReceipt('<?php echo $collect['id']; ?>', '<?php echo $collect['inv_no']; ?>')" data-toggle="tooltip" title="Print"><i class="fa fa-print"></i></button>
                                                    <?php } ?>
                                                </td>
                                            </tr>                    
                                        <?php } ?>                            
                                        
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="font-weight:bold"><?php echo $this->lang->line('grand_total'); ?></td>
                                            <td class="text text-right " style="font-weight:bold" ><?php echo $currency_symbol.amountFormat(array_sum($grdTotalLabel)); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
<?php
if ($search_type == 'period') {
    ?>
        $(document).ready(function () {
            showdate('period');
        });
    <?php
}
?>

function printReceipt(invoice_id, sub_invoice_id) {
    var base_url = '<?php echo base_url() ?>';
    $.ajax({
        url: base_url + 'studentfee/printFeesByGroup',
        type: 'post',
        data: {'fee_groups_feetype_id': 0, 'fee_master_id': 0, 'fee_session_group_id': 0, 'student_session_id': 0, 'trans_fee_id': 0, 'student_fees_deposite_id': invoice_id, 'sub_invoice_id': sub_invoice_id},
        success: function (response) {
            Popup(response);
        }
    });
}
function Popup(data)
{
    var frame1 = $('<iframe />');
    frame1[0].name = "frame1";
    frame1.css({"position": "absolute", "top": "-1000000px"});
    $("body").append(frame1);
    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
    frameDoc.document.open();
    //Create a new HTML document.
    frameDoc.document.write('<html>');
    frameDoc.document.write('<head>');
    frameDoc.document.write('<title></title>');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
    frameDoc.document.write('</head>');
    frameDoc.document.write('<body>');
    frameDoc.document.write(data);
    frameDoc.document.write('</body>');
    frameDoc.document.write('</html>');
    frameDoc.document.close();
    setTimeout(function () {
        window.frames["frame1"].focus();
        window.frames["frame1"].print();
        frame1.remove();
    }, 500);
    return true;
}

if (document.getElementById("print")) {
    document.getElementById("print").style.display = "block";
}
if (document.getElementById("btnExport")) {
    document.getElementById("btnExport").style.display = "block";
}
if (document.getElementById("printhead")) {
    document.getElementById("printhead").style.display = "none";
}

function printDiv() {
    document.getElementById("print").style.display = "none";
    document.getElementById("btnExport").style.display = "none";
    document.getElementById("printhead").style.display = "block";
    var divElements = document.getElementById('transfee').innerHTML;
    var oldPage = document.body.innerHTML;
    document.body.innerHTML =
            "<html><head><title>Fee Collection List</title></head><body>" +
            divElements + "</body>";
    window.print();
    document.body.innerHTML = oldPage;
    document.getElementById("printhead").style.display = "none";
    location.reload(true);
}

$(document).ready(function() {
    let searchTimeoutAjax;
    const searchInputAjax = $('#search_student_ajax');
    const resultsContainerAjax = $('#ajax_student_search_results_container');

    searchInputAjax.on('keyup', function() {
        clearTimeout(searchTimeoutAjax);
        const query = $(this).val().trim();

        if (query.length >= 2) {
            searchTimeoutAjax = setTimeout(function() {
                var postData = {
                    search_text: query
                };
                
                var csrfInput = $('form input[name="<?php echo $this->security->get_csrf_token_name(); ?>"]');
                if (csrfInput.length > 0) {
                    var csrfName = csrfInput.attr('name');
                    var csrfHash = csrfInput.val();
                    if (csrfName && csrfHash) {
                        postData[csrfName] = csrfHash;
                    }
                }

                $.ajax({
                    url: baseurl + 'admin/admin/ajax_search',
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    success: function(response) {
                        resultsContainerAjax.empty().show();
                        if (response.status === 'success' && response.data && response.data.length > 0) {
                            response.data.forEach(function(student) {
                                const fatherName = student.father_name ? student.father_name : 'N/A';
                                const motherName = student.mother_name ? student.mother_name : 'N/A';
                                const className = student.class ? student.class : '';
                                const sectionName = student.section ? student.section : '';
                                const admNo = student.admission_no ? student.admission_no : '';

                                const html = `
                                    <a href="${baseurl}studentfee/addfee/${student.student_session_id}" class="custom-ajax-search-item">
                                        <img src="${student.image}" alt="Student" class="custom-ajax-search-avatar">
                                        <div class="custom-ajax-search-details">
                                            <div class="custom-ajax-search-col">
                                                <span class="custom-ajax-search-name">${student.full_name}</span>
                                                <span class="custom-ajax-search-meta">${className} - ${sectionName} &nbsp;&nbsp; ${admNo}</span>
                                            </div>
                                            <div class="custom-ajax-search-parents">
                            <span style="display:flex; align-items:center; margin-bottom:4px;"><span class="parent-badge badge-father">F</span> ${fatherName}</span>
                            <span style="display:flex; align-items:center;"><span class="parent-badge badge-mother">M</span> ${motherName}</span>
                        </div>                    </div>
                                        <i class="fa fa-share custom-ajax-search-icon"></i>
                                    </a>
                                `;
                                resultsContainerAjax.append(html);
                            });
                        } else {
                            resultsContainerAjax.html('<div class="ajax-search-no-results" style="padding: 10px; text-align: center;">No students found</div>');
                        }
                    },
                    error: function() {
                        resultsContainerAjax.html('<div class="ajax-search-error" style="padding: 10px; text-align: center; color: red;">Error fetching results</div>');
                    }
                });
            }, 300);
        } else {
            resultsContainerAjax.empty().hide();
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('#search_student_ajax').length && !$(e.target).closest('#ajax_student_search_results_container').length) {
            resultsContainerAjax.hide();
        }
    });

    // Client-side list filter
    $("#table_filter").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#headerTable tbody tr").not(':last').filter(function() { // exclude grand total row if we want, or just filter all
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>

<style>
    .custom-ajax-search-container {
        width: 100%; 
        min-width: 450px; /* Make it wide enough to fit all columns */
        max-width: 100vw;
        position: absolute; 
        z-index: 999; 
        background: #fff; 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
        display: none; 
        max-height: 450px; 
        overflow-y: auto;
        border-radius: 6px;
        padding: 10px;
        border: 1px solid #e1e4e8;
        top: 100%;
        margin-top: 5px;
    }
    .custom-ajax-search-item {
        display: flex;
        align-items: center;
        padding: 12px;
        margin-bottom: 8px;
        background-color: #ffffff;
        border: 1px solid #eef0f3;
        border-radius: 8px;
        text-decoration: none;
        color: #333;
        transition: all 0.2s ease-in-out;
    }
    .custom-ajax-search-item:nth-child(even) {
        background-color: #f4f6f9;
    }
    .custom-ajax-search-item:last-child {
        margin-bottom: 0;
    }
    .custom-ajax-search-item:hover, .custom-ajax-search-item:focus {
        background-color: var(--primary-theme-color, #2eab66);
        color: #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        border-color: var(--primary-theme-color, #2eab66);
        outline: none;
    }
    .custom-ajax-search-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 15px;
        border: 2px solid #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        background: #fff;
    }
    .custom-ajax-search-details {
        flex: 1;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        min-width: 0; /* Important for flex child truncation */
        gap: 12px;
    }
    .custom-ajax-search-col {
        display: flex;
        flex-direction: column;
        flex: 0 0 200px;
        width: 200px;
        min-width: 0;
    }
    .custom-ajax-search-name {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 3px;
        text-transform: uppercase;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .custom-ajax-search-meta {
        font-size: 12px;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-meta {
        color: #e0f2e9;
    }
    .custom-ajax-search-parents {
        display: flex;
        flex-direction: column;
        font-size: 12px;
        color: #495057;
        text-transform: uppercase;
        font-weight: 500;
        min-width: 140px;
        flex-shrink: 0;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-parents {
        color: #fff;
    }
    .parent-badge {
        color: #fff !important;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        margin-right: 6px;
        font-weight: bold;
        flex-shrink: 0;
    }
    .badge-father {
        background-color: #007bff !important;
    }
    .badge-mother {
        background-color: #e83e8c !important;
    }
    .custom-ajax-search-icon {
        color: #adb5bd;
        margin-left: 15px;
        font-size: 18px;
    }
    .custom-ajax-search-item:hover .custom-ajax-search-icon {
        color: #fff;
    }
    /* Scrollbar styling for the container */
    .custom-ajax-search-container::-webkit-scrollbar {
        width: 6px;
    }
    .custom-ajax-search-container::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 4px;
    }
    .custom-ajax-search-container::-webkit-scrollbar-thumb {
        background: #ccc; 
        border-radius: 4px;
    }
    .custom-ajax-search-container::-webkit-scrollbar-thumb:hover {
        background: #999; 
    }
</style>
