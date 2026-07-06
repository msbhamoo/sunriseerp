<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style type="text/css">
	.collect_grp_fees {
		font-size: 15px;
		font-weight: 600;
		padding-bottom: 15px;
	}

	.fees-list {
		list-style: none;
		margin: 0;
		padding: 0;
	}

	.fees-list>.item {
		border-radius: 3px;
		-webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
		padding: 10px 0;
		/*background: #fff;*/
	}

	.fees-list>.item:before,
	.fees-list>.item:after {
		content: " ";
		display: table;
	}

	.fees-list>.item:after {
		clear: both;
	}

	.fees-list .product-img {
		float: left;
	}

	.fees-list .product-img img {
		width: 50px;
		height: 50px;
	}

	.fees-list .product-info {
		margin-left: 0px;
	}

	.fees-list .product-title {
		font-family: 'Roboto-Medium';
		font-size: 15px;
		display: inline-flex;
		justify-content: space-between;
		align-items: center;
		width: 100%;
		/*color: #333;*/
	}

	.fees-list .product-title span {

		font-size: 15px;
		display: inline;
		font-weight: 100 !important;
	}

	.fees-list .product-description {
		display: flex;
		/*color: #000;*/
		overflow: hidden;
		white-space: nowrap;
		text-overflow: ellipsis;
		justify-content: space-between;
		align-items: center;
	}

	.fees-list-in-box>.item {
		-webkit-box-shadow: none;
		box-shadow: none;
		border-radius: 0;
		/*padding: 15px 0px 0px 0px;*/
		border-bottom: 1px solid var(--bs-hr-color);

	}

	.fees-list-in-box>.item:last-of-type {
		/*border-bottom-width: 100;*/
		margin-bottom: 10px;
	}

	.fees-footer {
		margin-top: 15px;
		border-top-color: var(--bs-hr-color);
	}

	.fees-footer {
		padding: 15px 0px 0px 0px;
		text-align: right;
		border-top: 1px solid var(--bs-hr-color);
	}
</style>

<div class="row">
	<div class="col-lg-12">
		<div class="form-horizontal pr-0-5 pr-rtl-0">

			<?php
			$has_common_fee = false;
			$has_transport_fee = false;
			if (isset($feearray) && !empty($feearray)) {
				foreach ($feearray as $f) {
					if ($f->fee_category == 'transport' || $f->fee_category == 'transport_yearly') {
						$has_transport_fee = true;
					} else {
						$has_common_fee = true;
					}
				}
			}
			?>
			<?php if (isset($custom_receipt_settings)) { ?>
			<div class="form-group row">
				<label class="col-lg-3 col-md-3 col-sm-3 control-label"><?php echo $this->lang->line('receipt_no') ? $this->lang->line('receipt_no') : 'Receipt No.'; ?></label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<p class="form-control-static" style="padding-top: 7px; font-weight: bold; margin-bottom: 0;">
					<?php 
					$receipt_texts = [];
					if ($has_common_fee && $has_transport_fee) {
						$receipt_texts[] = "Fee: " . $custom_receipt_settings->common_prefix . $custom_receipt_settings->common_current;
						$receipt_texts[] = "Transport: " . $custom_receipt_settings->transport_prefix . $custom_receipt_settings->transport_current;
					} elseif ($has_common_fee) {
						$receipt_texts[] = $custom_receipt_settings->common_prefix . $custom_receipt_settings->common_current;
					} elseif ($has_transport_fee) {
						$receipt_texts[] = $custom_receipt_settings->transport_prefix . $custom_receipt_settings->transport_current;
					}
					echo implode(" | ", $receipt_texts);
					?>
					</p>
				</div>
			</div>
			<?php } ?>

			<div class="form-group row">
				<label for="inputEmail3" class="col-lg-3 col-md-3 col-sm-3 control-label"><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<input id="date" name="collected_date" placeholder="" type="text" class="form-control date_fee" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" autocomplete="off">
					<span id="form_collection_collected_date_error" class="text text-danger"></span>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputPassword3" class="col-lg-3 col-md-3 col-sm-3 control-label"> <?php echo $this->lang->line('payment_mode'); ?></label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<div style="margin-bottom: 5px;">
						<label class="radio-inline"><input type="radio" name="payment_mode_fee" value="Cash" checked="checked"> <?php echo $this->lang->line('cash'); ?></label>
						<label class="radio-inline"><input type="radio" name="payment_mode_fee" value="Cheque"> <?php echo $this->lang->line('cheque'); ?></label>
						<label class="radio-inline"><input type="radio" name="payment_mode_fee" value="DD"><?php echo $this->lang->line('dd'); ?></label>
						<label class="radio-inline"><input type="radio" name="payment_mode_fee" value="bank_transfer"><?php echo $this->lang->line('bank_transfer'); ?></label>
					</div>
					<div>
						<label class="radio-inline"><input type="radio" name="payment_mode_fee" value="upi"><?php echo $this->lang->line('upi'); ?></label>
						<label class="radio-inline"><input type="radio" name="payment_mode_fee" value="card"><?php echo $this->lang->line('card'); ?></label>
						<span class="text-danger" id="payment_mode_error"></span>
					</div>
				</div>
				<span id="form_collection_payment_mode_fee_error" class="text text-danger"></span>
			</div>
			
			<div class="form-group row" id="bank_account_row" style="display: none;">
				<label class="col-lg-3 col-md-3 col-sm-3 control-label"> Bank Account</label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<select class="form-control" name="bank_account_id" id="bank_account_id">
						<?php if(isset($bank_ledgers)) { foreach($bank_ledgers as $bank) { ?>
							<option value="<?php echo $bank['id']; ?>" <?php if(isset($bank_ledger_name) && $bank['name'] == $bank_ledger_name) echo 'selected'; ?>><?php echo $bank['name']; ?></option>
						<?php } } ?>
					</select>
					<span id="form_collection_bank_account_id_error" class="text text-danger"></span>
				</div>
			</div>

			<div class="form-group row">
				<label for="inputPassword3" class="col-lg-3 col-md-3 col-sm-3 control-label"> <?php echo $this->lang->line('note') ?></label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<textarea class="form-control" rows="2" name="fee_gupcollected_note" id="description" placeholder=""></textarea>
					<span id="form_collection_fee_gupcollected_note_error" class="text text-danger"></span>
				</div>
			</div>
			<div class="form-group row" id="reference_row" style="display: none;">
				<label for="inputPassword3" class="col-lg-3 col-md-3 col-sm-3 control-label" id="ref_label"> <?php echo $this->lang->line('reference_no') ? $this->lang->line('reference_no') : 'Reference No'; ?></label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<input class="form-control" name="reference_no" id="reference_no" placeholder="">
					<span id="form_collection_reference_no_error" class="text text-danger"></span>
				</div>
			</div>
			<div class="form-group row" id="date_row" style="display: none;">
				<label for="inputPassword3" class="col-lg-3 col-md-3 col-sm-3 control-label" id="date_label"> Payment Date</label>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<input class="form-control date_fee" name="cheque_date" id="cheque_date" placeholder="" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" autocomplete="off">
					<span id="form_collection_cheque_date_error" class="text text-danger"></span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-lg-3 col-md-3 col-sm-3"></div>
				<div class="col-lg-9 col-md-9 col-sm-9">
					<div id="ledger_info" class="alert alert-info" style="margin-bottom: 0; padding: 10px 15px; border-radius: 4px; font-size: 13px;" data-income="<?php echo $income_ledger_name; ?>" data-category="<?php echo $category_head_name ? $category_head_name : 'Default'; ?>">
						<i class="fa fa-info-circle"></i> Depositing to Ledger (Dr): <strong><?php echo $cash_ledger_name; ?></strong><br>
                        <i class="fa fa-credit-card"></i> Income Ledger (Cr): <strong><?php echo $income_ledger_name; ?></strong><br>
                        <i class="fa fa-tags"></i> Category/Head: <strong><?php echo $category_head_name ? $category_head_name : 'Default'; ?></strong>
					</div>
				</div>
			</div>
		</div>

		<ul class="fees-list fees-list-in-box">
			<hr>
		<div class="scroll-lg">	
			
			<?php
			// First check if there are any fees to display
			$has_fees_to_display = false;
			$row_counter = 1;
			$total_amount = 0;
			$total_fine_amount = 0;
			
			foreach ($feearray as $fee_key => $fee_value) {
				$amount_prev_paid = 0;
				$amount_to_be_pay = 0;
				
				if ($fee_value->fee_category == "fees") {
					$amount_to_be_pay = $fee_value->amount;
					
					if ($fee_value->is_system) {
						$amount_to_be_pay = $fee_value->student_fees_master_amount;
					}
					
					if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {
						$amount_data = json_decode($fee_value->amount_detail);
						foreach ($amount_data as $amount_data_key => $amount_data_value) {
							$amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
						}
						
						if ($fee_value->is_system) {
							$amount_to_be_pay = $fee_value->student_fees_master_amount - $amount_prev_paid;
						} else {
							$amount_to_be_pay = $fee_value->amount - $amount_prev_paid;
						}
					}
				} elseif ($fee_value->fee_category == "transport" || $fee_value->fee_category == "transport_yearly") {
					$amount_to_be_pay = $fee_value->fees;
					
					if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {
						$amount_data = json_decode($fee_value->amount_detail);
						foreach ($amount_data as $amount_data_key => $amount_data_value) {
							$amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
						}
						$amount_to_be_pay = $fee_value->fees - $amount_prev_paid;
					}
				}
				
				if ($amount_to_be_pay > 0) {
					$has_fees_to_display = true;
					break; // Found at least one fee
				}
			}
			
			// Only show headers if there are fees to display
			if ($has_fees_to_display) { ?>
			<div class="row sticky-lg-top">
				<div class="col-md-12">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"> <label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('fees'); ?></label>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"> <label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('fine_amount'); ?></label>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right"> <label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('fees_amount'); ?></label>
					</div>
				</div>
			</div>
			<?php } ?>


			<?php
			$row_counter = 1;
			$total_amount = 0;
			$total_fine_amount = 0;

			foreach ($feearray as $fee_key => $fee_value) {
				$amount_prev_paid = 0;
				$fees_fine_amount = 0;
				$fine_amount_paid = 0;
				$fine_amount_status = false;

				if ($fee_value->fee_category == "fees") {
					$amount_to_be_pay = $fee_value->amount;

					if ($fee_value->is_system) {
						$amount_to_be_pay = $fee_value->student_fees_master_amount;
					}

					if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {
						$amount_data = json_decode($fee_value->amount_detail);
						foreach ($amount_data as $amount_data_key => $amount_data_value) {
							$fine_amount_paid += $amount_data_value->amount_fine;
							$amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
						}

						if ($fee_value->is_system) {
							$amount_to_be_pay = $fee_value->student_fees_master_amount - $amount_prev_paid;
						} else {
							$amount_to_be_pay = $fee_value->amount - $amount_prev_paid;
						}
					}

					if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {

						$fine_amount_status = true;

						// get cumulative fine amount as delay days 
						if ($fee_value->fine_type == 'cumulative') {
							$date1 = date_create("$fee_value->due_date");
							$date2 = date_create(date('Y-m-d'));
							$diff = date_diff($date1, $date2);
							$due_days = $diff->format("%a");;

							if ($this->customlib->get_cumulative_fine_amount($fee_value->fee_groups_feetype_id, $due_days)) {
								$due_fine_amount = $this->customlib->get_cumulative_fine_amount($fee_value->fee_groups_feetype_id, $due_days);
							} else {
								$due_fine_amount = 0;
							}

							$fees_fine_amount   = 	$due_fine_amount - $fine_amount_paid;
						} else if ($fee_value->fine_type == 'fix' || $fee_value->fine_type == 'percentage') {
							$fees_fine_amount	=	$fee_value->fine_amount - $fine_amount_paid;
						}
						// get cumulative fine amount as delay days
					}

					$total_amount = $total_amount + $amount_to_be_pay;

					if ($amount_to_be_pay > 0) {  ?>

						<li class="item ptt10">
							<input name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
							<input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">
							<input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_groups_feetype_id; ?>">
							<input name="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">
							<input name="fee_category_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_category; ?>">
							<input name="fee_session_group_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_session_group_id; ?>">
							<input name="trans_fee_id_<?php echo $row_counter; ?>" type="hidden" value="0">
							<div class="product-info pb15">

								<div class="row align-items-center">
									<div class="col-md-12">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 5px; font-weight: 500; color: #e67e22;">
											<?php
											if ($fee_value->is_system) {
												echo $this->lang->line($fee_value->type) . " (" . $this->lang->line($fee_value->code) . ")";
											} else {
												echo $fee_value->type . " (" . $fee_value->code . ")";
											}
											?>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
											<?php if ($fine_amount_status && ($fees_fine_amount > 0)) {
												echo '<span class="text-danger">' . $currency_symbol . amountFormat($fees_fine_amount) . '</span><br/>';
												$total_fine_amount = $total_fine_amount + $fees_fine_amount;
											?>
												<input type="hidden" id="actual_fine_amount_<?php echo $row_counter; ?>" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>">
												<input class="form-control total_fine_paying pull-right" style="width:100%; margin-top:5px; padding: 6px 12px; height: 30px;" name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" id="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="text" value="">
											<?php } ?>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
											<span><?php echo  $currency_symbol . amountFormat($amount_to_be_pay); ?></span><br/>
											<input onkeyup="recalculateTotalPaying()" class="pull-right form-control" style="width:100%; margin-top:5px; padding: 6px 12px; height: 30px;" name="fee_amount_<?php echo $row_counter; ?>" id="fee_amount_<?php echo $row_counter; ?>" type="text" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" />										
											<input class="pull-right form-control" name="pay_fee_amount_<?php echo $row_counter; ?>" id="pay_fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">										
										</div>
									</div>
								</div>
							</div>
						</li>
					<?php  }
				} elseif ($fee_value->fee_category == "transport" || $fee_value->fee_category == "transport_yearly") {

					$amount_to_be_pay = $fee_value->fees;

					if (is_string(($fee_value->amount_detail)) && is_array(json_decode(($fee_value->amount_detail), true))) {

						$amount_data = json_decode($fee_value->amount_detail);

						foreach ($amount_data as $amount_data_key => $amount_data_value) {
							$fine_amount_paid += $amount_data_value->amount_fine;
							$amount_prev_paid = $amount_prev_paid + ($amount_data_value->amount + $amount_data_value->amount_discount);
						}

						$amount_to_be_pay = $fee_value->fees - $amount_prev_paid;
					}

					if (($fee_value->due_date != "0000-00-00" && $fee_value->due_date != NULL) && (strtotime($fee_value->due_date) < strtotime(date('Y-m-d'))) && $amount_to_be_pay > 0) {

						$transport_fine_amount  =  is_null($fee_value->fine_percentage) ? $fee_value->fine_amount : percentageAmount($fee_value->fees, $fee_value->fine_percentage);
						$fees_fine_amount = $transport_fine_amount - $fine_amount_paid;
						$fine_amount_status = true;
					}

					$total_amount = $total_amount + $amount_to_be_pay;
					if ($amount_to_be_pay > 0) { ?>

						<li class="item pb10">
							<input name="row_counter[]" type="hidden" value="<?php echo $row_counter; ?>">
							<input name="student_fees_master_id_<?php echo $row_counter; ?>" type="hidden" value="0">
							<input name="fee_groups_feetype_id_<?php echo $row_counter; ?>" type="hidden" value="0">
							<input name="fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">
							<div class="product-info pb10 pt5">
								<input name="fee_category_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->fee_category; ?>">
								<input name="trans_fee_id_<?php echo $row_counter; ?>" type="hidden" value="<?php echo $fee_value->id; ?>">

								<div class="row align-items-center">
									<div class="col-md-12">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 5px; font-weight: 500; color: #e67e22;">
											<?php echo $fee_value->fee_category == 'transport_yearly' ? $this->lang->line("transport_fees") . " (Yearly)" : $this->lang->line("transport_fees"); ?>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
											<?php if ($fine_amount_status && ($fees_fine_amount > 0)) { ?>
												<span class="text-danger"><?php echo $currency_symbol . amountFormat($fees_fine_amount); ?></span><br/>
												<?php $total_fine_amount = $total_fine_amount + $fees_fine_amount; ?>
												<input type="hidden" id="actual_fine_amount_<?php echo $row_counter; ?>" value="<?php echo convertBaseAmountCurrencyFormat($fees_fine_amount); ?>">
												<input class="form-control total_fine_paying pull-right" style="width:100%; margin-top:5px; padding: 6px 12px; height: 30px;" name="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" id="fee_groups_feetype_fine_amount_<?php echo $row_counter; ?>" type="text" value="">
											<?php } ?>
										</div>
										<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
											<span><?php echo  $currency_symbol . amountFormat($amount_to_be_pay); ?></span><br/>
											<input onkeyup="recalculateTotalPaying()" class="pull-right form-control" style="width:100%; margin-top:5px; padding: 6px 12px; height: 30px;" name="fee_amount_<?php echo $row_counter; ?>" id="fee_amount_<?php echo $row_counter; ?>" type="text" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>" />
											<input class="pull-right" name="pay_fee_amount_<?php echo $row_counter; ?>" id="pay_fee_amount_<?php echo $row_counter; ?>" type="hidden" value="<?php echo convertBaseAmountCurrencyFormat($amount_to_be_pay); ?>">
										</div>
									</div>
								</div>
							</div>
						</li>
						
			<?php
					}
				}

				$row_counter++;
			}
			?>

				</div>	
			</ul>
		</div>
	</div>


<?php if ($total_amount > 0) { ?>

	<div class="row ptt10">
		<div class="col-md-12">
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
				<label for="inputPassword3" class=" control-label"> <?php echo $this->lang->line('total'); ?></label>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
			
				<?php $total_amount_for_pay_numeric = $total_amount + $total_fine_amount;?>
				<input type="hidden" id="total_amount_for_pay" name="total_amount_for_pay" value="<?php echo convertBaseAmountCurrencyFormat($total_amount_for_pay_numeric); ?>" />
				<label for="inputPassword3" class=" control-label">
				<?php echo $currency_symbol . amountFormat($total_amount_for_pay_numeric); ?></label>								
				
		 
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
				<label for="inputPassword3" class=" control-label"> <?php echo $currency_symbol . amountFormat($total_fine_amount); ?></label>
			</div>
			 
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
				<label for="inputPassword3" class=" control-label"> <?php echo $currency_symbol . amountFormat($total_amount); ?></label>
			</div>						
		</div>	
	</div>	
	

	<div class="row pt10" style="margin-bottom: 15px;">
		<div class="col-md-12">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="bmedium">
					Discount (<?php echo $currency_symbol; ?>)
				</span>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="pull-right">
					<input class="form-control" style="width:200px" name="amount_discount_global" id="amount_discount_global" type="text" value="0">
				</span>
			</div>
		</div>
		<div class="col-md-12" id="discount_distribution_container" style="display:none; margin-top: 10px;">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="bmedium">Distribution Method</span>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="pull-right">
					<select class="form-control" name="discount_distribution" id="discount_distribution" style="width:200px">
						<option value="first">Apply entirely to First Fee</option>
						<option value="proportional">Split Proportionally</option>
					</select>
				</span>
			</div>
		</div>
		<div class="col-md-12" id="dynamic_discount_reason_container_global" style="display:none; margin-top: 10px;">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="bmedium">Discount Reason <small class="req"> *</small></span>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="pull-right" style="width: 200px; text-align: left;">
					<select class="form-control" id="dynamic_discount_reason_select_global" style="margin-bottom: 5px;">
						<option value=""><?php echo $this->lang->line('select'); ?> Reason *</option>
						<option value="Staff discount">Staff Discount</option>
						<option value="Sibling discount">Sibling Discount</option>
						<option value="Management discount">Management Discount</option>
						<option value="Merit scholarship">Merit Scholarship</option>
						<option value="Need-based scholarship">Need-based Scholarship</option>
						<option value="Sports scholarship">Sports Scholarship</option>
						<option value="Girl child concession">Girl Child Concession</option>
						<option value="Single parent concession">Single Parent Concession</option>
						<option value="Financial hardship">Financial Hardship</option>
						<option value="Special needs concession">Special Needs Concession</option>
						<option value="Early payment discount">Early Payment Discount</option>
						<option value="Annual payment discount">Annual Payment Discount</option>
						<option value="Other">Other</option>
					</select>
					<input type="text" class="form-control" name="dynamic_discount_reason" id="dynamic_discount_reason_global" placeholder="Type Custom Reason *" style="display:none;">
					<small class="text-warning"><i class="fa fa-info-circle"></i> Requires admin approval.</small>
				</span>
			</div>
		</div>
	</div>

	<script>
		$('#amount_discount_global').on('keyup change', function(){
			var val = parseFloat($(this).val());
			if (val > 0) {
				$('#discount_distribution_container').show();
				$('#dynamic_discount_reason_container_global').show();
			} else {
				$('#discount_distribution_container').hide();
				$('#dynamic_discount_reason_container_global').hide();
			}
			recalculateTotalPaying();
		});

		$('#dynamic_discount_reason_select_global').change(function(){
			var val = $(this).val();
			if(val === 'Other') {
				$('#dynamic_discount_reason_global').val('').show();
			} else {
				$('#dynamic_discount_reason_global').hide().val(val);
			}
		});
	</script>

	<div class="row">
		<div class="col-md-12">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="bmedium">
					<?php echo $this->lang->line("paying_amount") . " (" . $currency_symbol . ")"; ?> <small class="req"> *</small>
				</span>
			</div>

			<!--<div class="col-lg-3 col-md-3 col-xs-3">
			<span class="pull-right"  id="update_fine">				            
					<?php //echo amountFormat($total_fine_amount); 
					?>				 				
			</span>			
		</div>-->

			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<span class="pull-right">
					<input class="total_paying form-control" style="width:200px" name="total_paying" id="total_paying" type="" value="">
				</span>
			</div>
		</div>
	</div>

	<div class="row ">
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-4 text-right">
		</div>
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<span class="pull-right pr-1 pt5">
				<span id="form_collection_total_paying_error" class="text text-danger"></span>
			</span>
		</div>
	</div>

	<div class="row  hidden">
		<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
			<span>
				<?php echo "Paying Amount"; ?>
			</span>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<span class="pull-right">
				<?php echo $currency_symbol; ?><span id="paying_amount">0.00</span>
			</span>
		</div>
	</div>
	
	<div class="fees-footer">
		<div class="row">
			<div class="col-md-12">
				<button type="submit" class="btn btn-primary pull-right payment_collect" data-loading-text="<i class='fa fa-spinner fa-spin '></i><?php echo $this->lang->line('processing') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('pay'); ?></button>
			</div>
		</div>
	</div>

	<?php } else {  ?>

		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-info mb0">
					<?php echo $this->lang->line('no_fees_found'); ?>
				</div>
			</div>
		</div>

	<?php }  ?>


<script type="text/javascript">
	function getTotalPaying() {
		var totalAmount = 0;
		var count = <?php echo $row_counter; ?>;

		for (let i = 1; i < count; i++) {

			const value = parseFloat($("#fee_amount_" + i).val());

			if (value > 0) {
				totalAmount += value;
			}
		}

		$("#paying_amount").html(totalAmount.toFixed(2));
	}
</script>

<script type="text/javascript">

		$(document).on('input paste keyup', '.total_paying', function(e) {
			update_amount();			 
		});

		let update_amount = () => {

			let total_amount_for_pay = parseFloat($('#total_amount_for_pay').val()) || 0;
			let total_paying = parseFloat($('#total_paying').val()) || 0;
			let global_discount = parseFloat($('#amount_discount_global').val()) || 0;
			let count = <?php echo $row_counter; ?>;
			let distribution = $('#discount_distribution').val();

            if (global_discount > total_amount_for_pay) {
                global_discount = total_amount_for_pay;
                $('#amount_discount_global').val(global_discount);
            }

            let effective_total_for_pay = total_amount_for_pay - global_discount;

			if (total_paying > effective_total_for_pay) {
				alert("<?php echo $this->lang->line('enter_valid_amount_you_are_entering_more_than_required_amount');?>");
				$('#total_paying').val('');

				for (let i = 1; i < count; i++) {
					$("#fee_amount_" + i).val('');
					if ($("#fee_groups_feetype_fine_amount_" + i).length) {
						$("#fee_groups_feetype_fine_amount_" + i).val('');
					}
				}

				$("#paying_amount").html('0.00');
				$(':input[type="submit"]').prop('disabled', true);
				showBalanceMessage();
				return;
			}


			$(':input[type="submit"]').prop('disabled', false);

			let total_fee_paid = 0;
			let total_fine_paid = 0;

            let row_discounts = [];
            let total_fee_amount = 0;
            for (let i = 1; i < count; i++) {
                row_discounts[i] = 0;
                total_fee_amount += parseFloat($("#pay_fee_amount_" + i).val()) || 0;
            }

            if (global_discount > 0) {
                if (distribution === 'first') {
                    let remaining_discount = global_discount;
                    for (let i = 1; i < count; i++) {
                        let fee = parseFloat($("#pay_fee_amount_" + i).val()) || 0;
                        if (fee > 0 && remaining_discount > 0) {
                            row_discounts[i] = Math.min(remaining_discount, fee);
                            remaining_discount -= row_discounts[i];
                        }
                    }
                } else if (distribution === 'proportional') {
                    if (total_fee_amount > 0) {
                        for (let i = 1; i < count; i++) {
                            let fee = parseFloat($("#pay_fee_amount_" + i).val()) || 0;
                            if (fee > 0) {
                                row_discounts[i] = (fee / total_fee_amount) * global_discount;
                            }
                        }
                    }
                }
            }


			for (let i = 1; i < count; i++) {
				$("#fee_amount_" + i).val('');
				if ($("#fee_groups_feetype_fine_amount_" + i).length) {
					$("#fee_groups_feetype_fine_amount_" + i).val('');
				}
			}


			for (let i = 1; i < count; i++) {

				if (total_paying <= 0) break;

				let fee = parseFloat($("#pay_fee_amount_" + i).val()) || 0;
                fee = Math.max(0, fee - row_discounts[i]);
				let fine = parseFloat($("#actual_fine_amount_" + i).val()) || 0;


				if (fine > 0 && total_paying > 0) {
					if (total_paying >= fine) {
						$("#fee_groups_feetype_fine_amount_" + i).val(fine.toFixed(2));
						total_paying -= fine;
						total_fine_paid += fine;
					} else {
						$("#fee_groups_feetype_fine_amount_" + i).val(total_paying.toFixed(2));
						total_fine_paid += total_paying;
						total_paying = 0;
						break;
					}			
					
				}

				if (fee > 0 && total_paying > 0) {
					if (total_paying >= fee) {
						$("#fee_amount_" + i).val(fee.toFixed(2));
						total_paying -= fee;
						total_fee_paid += fee;
					} else {
						$("#fee_amount_" + i).val(total_paying.toFixed(2));
						total_fee_paid += total_paying;
						total_paying = 0;
						break;
					}
				}
			}

			$("#paying_amount").html(total_fee_paid.toFixed(2));
            showBalanceMessage();
		};

		$(document).on('input paste keyup', '.total_fine_paying', function() {

			let inputId = $(this).attr('id');
			let rowId = inputId.split('_').pop(); // last number

			validateFineAmount(rowId);
		});

		function validateFineAmount(rowId) {

			let enteredFine = parseFloat($("#fee_groups_feetype_fine_amount_" + rowId).val()) || 0;
			let actualFine = parseFloat($("#actual_fine_amount_" + rowId).val()) || 0;

			if (enteredFine > actualFine) {

				alert("<?php echo $this->lang->line('enter_valid_amount_you_are_entering_more_than_required_amount');?>");
				$("#fee_groups_feetype_fine_amount_" + rowId).val(actualFine.toFixed(2));
				return false;

			}

			return true;
		}		 

</script>
<script>
	
	let fineFeeValidateTimer = null; // debounce timer
	let fineAlertShown = false;      // alert flag

	$(document).on('input paste keyup', '.total_paying', function () {

		clearTimeout(fineFeeValidateTimer);

		fineFeeValidateTimer = setTimeout(function () {
			validateFineWithFee();
		}, 1000); // 1 second delay
	});

	function validateFineWithFee() {

		let count = <?php echo $row_counter; ?>;
		let isValid = true;
		let alertNeeded = false;

		for (let i = 1; i < count; i++) {

			let fine = parseFloat($("#fee_groups_feetype_fine_amount_" + i).val()) || 0;
			let fee  = parseFloat($("#fee_amount_" + i).val()) || 0;

			if (fine > 0 && fee <= 0) {
				alertNeeded = true;
				isValid = false;
				break; // stop loop if condition found
			}
		}

		if (alertNeeded && !fineAlertShown) {
			alert("<?php echo $this->lang->line('you_cant_save_fine_amount_only');?>");
			fineAlertShown = true;
		}

		if (isValid) {
			$(":input[type='submit']").prop("disabled", false);
			fineAlertShown = false; // reset flag
		} else {
			$(":input[type='submit']").prop("disabled", true);
		}

		return isValid;
	}

</script>
	
<script>
	$(document).on('input paste keyup', '.total_fine_paying', function () {
		let inputId = $(this).attr('id');
		let rowId = inputId.split('_').pop();

		// Validate fine amount
		if (!validateFineAmount(rowId)) return;

		// Validate fine-with-fee rule
		if (!validateFineWithFeeRow(rowId)) return;

		// Recalculate total paying
		recalculateTotalPaying();
	});

	function recalculateTotalPaying() {

		let count = <?php echo $row_counter; ?>;
		let totalFee = 0;
		let totalFine = 0;

		for (let i = 1; i < count; i++) {

			totalFee += parseFloat($("#fee_amount_" + i).val()) || 0;
			totalFine += parseFloat($("#fee_groups_feetype_fine_amount_" + i).val()) || 0;
		}

		let grandTotal = totalFee + totalFine;

		$("#total_paying").val(grandTotal.toFixed(2));
		$("#paying_amount").html(totalFee.toFixed(2));

		showBalanceMessage();
	}

	function showBalanceMessage() {

		let enteredPaying = parseFloat($("#total_paying").val()) || 0;
		let allowedTotal  = parseFloat($("#total_amount_for_pay").val()) || 0;
		let global_discount = parseFloat($('#amount_discount_global').val()) || 0;

		let diff = enteredPaying - (allowedTotal - global_discount);

		if (diff > 0) {
			$("#form_collection_total_paying_error").html(
				"Extra amount <?php echo $currency_symbol;?>" + diff.toFixed(2) 
			);
            $(':input[type="submit"]').prop('disabled', true);
		} else if (diff < 0) {
			$("#form_collection_total_paying_error").html(
				"<?php echo $this->lang->line('balance_amount') .' '. $currency_symbol;?>" + Math.abs(diff).toFixed(2) 
			);
            $(':input[type="submit"]').prop('disabled', false);
		} else {
			$("#form_collection_total_paying_error").html('');
            $(':input[type="submit"]').prop('disabled', false);
		}
	}

	let fineRowAlertShown = {};

	function validateFineWithFeeRow(rowId) {

		let fine = parseFloat($("#fee_groups_feetype_fine_amount_" + rowId).val()) || 0;
		let fee  = parseFloat($("#fee_amount_" + rowId).val()) || 0;

		if (fine > 0 && fee <= 0) {

			if (!fineRowAlertShown[rowId]) {
				alert("<?php echo $this->lang->line('you_cant_save_fine_amount_only');?>");
				fineRowAlertShown[rowId] = true;
			}

			$("#fee_groups_feetype_fine_amount_" + rowId).val('');
			$(":input[type='submit']").prop("disabled", true);
			return false;
		}

		fineRowAlertShown[rowId] = false;
		$(":input[type='submit']").prop("disabled", false);
		return true;
	}

</script>