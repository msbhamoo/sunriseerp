<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bullhorn"></i> <?php //echo $this->lang->line('communicate'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" >
                    <div class="box-header with-border">
                        <h3 class="box-title"> <?php echo $this->lang->line('whatsapp_log'); ?></h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('whatsapp_log'); ?></div>
							
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<a class="btn btn-primary btn-sm pull-right checkbox-toggle delete_whatsapp_log" ><?php echo $this->lang->line('delete_whatsapp_log'); ?>  </a>
									</div>	
								</div>	
							</div>
						
                            <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('whatsapp_log'); ?>">
                                <thead>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
										<th><?php echo $this->lang->line('schedule_date'); ?></th>
                                        <th>Mobile / Recipients</th>
										 <th class="noExport"><?php echo $this->lang->line('action'); ?></th>												   
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach ($listMessage as $message) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $message['title'] ?></td>
                                            <td class="mailbox-name"><?php echo $message['message'] ?></td>
                                            <td class="mailbox-name"> 
                                                <?php echo $this->customlib->dateyyyymmddToDateTimeformat($message['created_at'], false); ?> 
                                            </td>
											<td class="mailbox-name">                                                
                                                <?php echo $this->customlib->dateyyyymmddToDateTimeformat($message['schedule_date_time'], false); ?>                                                
                                            </td>
                                            <td class="mailbox-name">
                                                <?php
                                                $recipients = "";
                                                if (!empty($message['user_list'])) {
                                                    $user_list = json_decode($message['user_list']);
                                                    if (is_array($user_list) && count($user_list) > 0) {
                                                        if (count($user_list) == 1 && isset($user_list[0]->mobileno)) {
                                                            $recipients = $user_list[0]->mobileno;
                                                        } else {
                                                            $recipients = count($user_list) . " Recipients";
                                                        }
                                                    }
                                                } else if ($message['is_group']) {
                                                    $recipients = "Group Message";
                                                } else if ($message['is_class']) {
                                                    $recipients = "Class Message";
                                                }
                                                echo $recipients;
                                                ?>
                                            </td>
	<td>
												<a href="<?php echo base_url(); ?>admin/mailsms/edit_schedule/<?php echo $message['id']; ?>/send_now" class="btn btn-primary btn-xs" data-toggle="tooltip" data-original-title="<?php echo $this->lang->line('view_edit'); ?>"><i class="fa fa-reorder"></i></a>
                                                <a href="#" class="btn btn-default btn-xs view_whatsapp_logs" data-id="<?php echo $message['id']; ?>" data-toggle="tooltip" data-original-title="View Sent Logs"><i class="fa fa-eye"></i></a>
											</td>											
                                        </tr>
                                        <?php
}
?>
                                </tbody>
                            </table><!-- /.table -->
                        </div><!-- /.mail-box-messages -->
                    </div><!-- /.box-body -->
                </div>
            </div>
            <div class="col-md-8">

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
    </section>
</div>

<script>
	$(function () {
		$('.delete_whatsapp_log').on('click', function () {			
			if (confirm("<?php echo $this->lang->line('confirm_delete') ?>")) {				
				$.ajax({
					url: '<?php echo base_url(); ?>admin/mailsms/delete_whatsapp_log/',
					success: function (data) {
						if (data.status == "fail") {                        
							errorMsg(message);
						} else {
							successMsg(data.message);
							window.location.reload(true);
						}
					}
				});
			}
		});
	});
</script>

<script type="text/javascript">
    var base_url = '<?php echo base_url() ?>';
    function printDiv(elem) {
        Popup(jQuery(elem).html());
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

</script>

<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
        
        $('.view_whatsapp_logs').on('click', function(e) {
            e.preventDefault();
            var message_id = $(this).data('id');
            $('#whatsappLogModal').modal('show');
            $('#whatsapp_log_content').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
            
            $.ajax({
                url: base_url + 'admin/mailsms/get_whatsapp_individual_logs',
                type: 'POST',
                data: {message_id: message_id},
                dataType: 'json',
                success: function(response) {
                    var html = '';
                    if(response.length > 0) {
                        $.each(response, function(index, log) {
                            var status_label = log.status == 'sent' ? '<span class="label label-success">Sent</span>' : '<span class="label label-danger">Failed</span>';
                            html += '<tr>';
                            html += '<td>' + log.recipient_name + '</td>';
                            html += '<td>' + log.mobile_number + '</td>';
                            html += '<td>' + log.message_text + '</td>';
                            html += '<td>' + status_label + '</td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center">No individual logs found. Note: Messages sent before this feature was added do not have individual logs.</td></tr>';
                    }
                    $('#whatsapp_log_content').html(html);
                }
            });
        });
    });
</script>

<div class="modal fade" id="whatsappLogModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sent Logs</h4>
            </div>
            <div class="modal-body pt0 pb0">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Mobile Number</th>
                                <th>Exact Message Sent</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="whatsapp_log_content">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>