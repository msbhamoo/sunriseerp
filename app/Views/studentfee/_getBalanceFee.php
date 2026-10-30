 <div class="form-horizontal balanceformpopup" style="font-family: 'Inter', system-ui, -apple-system, sans-serif;">
     <style>
         .d-flex {
             display: flex;
         }
         .justify-content-between {
             justify-content: space-between;
         }
         .align-items-center {
             align-items: center;
         }
         .checkbox-fees{
             padding: 5px 0px 0px 1px;
         }
         .drawer-form-group {
             margin-bottom: 14px;
         }
         .drawer-form-group label.control-label {
             font-size: 12.5px;
             font-weight: 600;
             color: #334155;
             text-align: left;
             padding-top: 6px;
         }
         .balanceformpopup .form-control {
             height: 38px;
             border: 1px solid #cbd5e1;
             border-radius: 8px;
             padding: 6px 12px;
             font-size: 13.5px;
             color: #0f172a;
             box-shadow: none;
             transition: all 0.2s ease;
             background: #ffffff;
         }
         .balanceformpopup .form-control:focus {
             border-color: #114B5F;
             box-shadow: 0 0 0 3px rgba(17, 75, 95, 0.12);
             outline: none;
         }
         .balanceformpopup textarea.form-control {
             height: auto !important;
             min-height: 60px;
             resize: vertical;
         }
         /* Custom Payment Mode Pill Buttons */
         .payment-modes-wrap {
             display: flex;
             flex-wrap: wrap;
             gap: 6px;
             margin-top: 2px;
         }
         .payment-mode-pill {
             position: relative;
             margin: 0 !important;
             cursor: pointer;
             font-weight: 500;
         }
         .payment-mode-pill input[type="radio"] {
             position: absolute;
             opacity: 0;
             cursor: pointer;
         }
         .payment-mode-pill span {
             display: inline-flex;
             align-items: center;
             padding: 6px 12px;
             border-radius: 7px;
             border: 1px solid #cbd5e1;
             background: #f8fafc;
             color: #475569;
             font-size: 12.5px;
             transition: all 0.15s ease;
             user-select: none;
         }
         .payment-mode-pill input[type="radio"]:checked + span {
             background: #114B5F;
             border-color: #114B5F;
             color: #ffffff;
             font-weight: 600;
             box-shadow: 0 2px 6px rgba(17, 75, 95, 0.2);
         }
         .payment-mode-pill:hover span {
             border-color: #94a3b8;
         }
         /* Ledger banner styling */
         .drawer-ledger-banner {
             background: #f0fdfa;
             border: 1px solid #ccfbf1;
             border-radius: 8px;
             padding: 10px 14px;
             font-size: 12px;
             color: #134e4a;
             line-height: 1.6;
         }
         .drawer-ledger-banner i {
             color: #0d9488;
             margin-right: 4px;
         }
         .btn-drawer-cancel {
             background: #ffffff;
             border: 1px solid #cbd5e1;
             color: #475569;
             font-weight: 600;
             padding: 8px 18px;
             border-radius: 8px;
             font-size: 13px;
             transition: all 0.15s ease;
         }
         .btn-drawer-cancel:hover {
             background: #f1f5f9;
             color: #0f172a;
         }
         .save_button.primary {
             background-color: #114B5F !important;
             border-color: #114B5F !important;
             color: #ffffff !important;
             font-weight: 600 !important;
             padding: 8px 18px !important;
             border-radius: 8px !important;
             font-size: 13px !important;
             transition: all 0.2s ease !important;
         }
         .save_button.primary:hover {
             background-color: #0c3847 !important;
             border-color: #0c3847 !important;
         }
         .save_button.secondary {
             background-color: #ffffff !important;
             border: 1px solid #114B5F !important;
             color: #114B5F !important;
             font-weight: 600 !important;
             padding: 8px 18px !important;
             border-radius: 8px !important;
             font-size: 13px !important;
             transition: all 0.2s ease !important;
         }
         .save_button.secondary:hover {
             background-color: #f0fdfa !important;
         }
     </style>
     <div class="box-body" style="padding: 0;">
         <?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
         <input type="hidden" class="form-control" id="fee_session_group_id" value="<?php echo $fee_session_group_id; ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="std_id" value="<?php echo $student["student_session_id"]; ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="parent_app_key" value="<?php echo $student['parent_app_key'] ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="guardian_phone" value="<?php echo $student['guardian_phone'] ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="guardian_email" value="<?php echo $student['guardian_email'] ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="student_fees_master_id" value="<?php echo $student_fees_master_id ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="fee_groups_feetype_id" value="<?php echo $fee_groups_feetype_id ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="transport_fees_id" value="<?php echo $transport_fees_id ?>" readonly="readonly" />
         <input type="hidden" class="form-control" id="fee_category" value="<?php echo $fee_category ?>" readonly="readonly" />

         <div class="form-group drawer-form-group">
             <label for="inputEmail3" class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('fees'); ?> (<?php echo $currency_symbol; ?>)</label>
             <div class="col-sm-9 col-lg-9 col-md-9" style="padding-top: 6px;">
                 <span style="font-weight: 700; font-size: 15px; color: #0f172a;"><?php echo $balance; ?></span>
             </div>
         </div>
		 <?php if (isset($custom_receipt_settings)) { ?>
         <div class="form-group drawer-form-group">
             <label class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('receipt_no') ? $this->lang->line('receipt_no') : 'Receipt No.'; ?></label>
             <div class="col-sm-9 col-lg-9 col-md-9" style="padding-top: 6px;">
                 <span style="font-weight: 700; color: #0f172a;">
                 <?php 
                 if ($fee_category == 'transport' || $fee_category == 'transport_yearly') {
                     echo $custom_receipt_settings->transport_prefix . $custom_receipt_settings->transport_current;
                 } else {
                     echo $custom_receipt_settings->common_prefix . $custom_receipt_settings->common_current;
                 }
                 ?>
                 </span>
             </div>
         </div>
         <?php } ?>
		 <div class="form-group drawer-form-group">
             <label for="inputEmail3" class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
             <div class="col-sm-9">
                 <input id="date" name="admission_date" placeholder="" type="text" class="form-control date_fee" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" />
                 <span class="text-danger" id="date_error"></span>
             </div>
         </div>
         <div class="form-group drawer-form-group">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('paying_amount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
             <div class="col-sm-9">
                 <input type="text" autofocus="" class="form-control modal_amount" id="amount" value="<?php echo $balance; ?>">
                 <span class="text-danger" id="amount_error"></span>
             </div>
         </div>
         <div class="form-group drawer-form-group">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label pt0"> <?php echo $this->lang->line('discount_group'); ?></label>
             <div class="col-sm-9 col-lg-9 col-md-9">
<?php 

if(!empty($discount_not_applied)){
?>
     <div class="checkbox-fees-scroll" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px; max-height: 140px; overflow-y: auto;">
     <div class="row" style="margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-7 col-lg-7 col-sm-7 col-xs-7" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;"><strong><?php echo $this->lang->line('fees_discount'); ?></strong></div>
                <div class="col-md-3 col-sm-3 col-xs-3 text text-center" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;"><strong><?php echo $this->lang->line('available_count'); ?></strong></div>
                <div class="col-md-2 col-sm-2 col-xs-2 text text-right" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;"><strong><?php echo $this->lang->line('value'); ?> </strong></div>
            </div>
        </div>
    </div>
<div class="row">
    <?php

       foreach ($discount_not_applied as $index => $discount_value) {

       ?>
        <div class="col-md-12" style="margin-bottom: 4px;">
            <div class="row">
<div class="col-md-7 col-sm-7 col-xs-7">   
    <label class="checkbox-inline pt0" style="font-size: 12.5px; font-weight: 500;">
                        <input type="checkbox" name="fee_discount_group[]" class="grp_discount" value="<?php echo $discount_value->id;?>" data-disamount="<?php echo ($discount_value->type == "fix") ? ($discount_value->amount) : "0"?>" data-type="<?php echo $discount_value->type ; ?>" data-percentage="<?php echo ($discount_value->type == "percentage") ?  ($discount_value->percentage): "0";?>"><?php echo $discount_value->name ; ?><?php if($discount_value->code){ echo " (".$discount_value->code.")"; } ?>
                 
                    </label></div>
<div class="col-md-3 col-sm-3 col-xs-3 text text-center" style="font-size: 12px; font-weight: 600; color: #64748b;"><?php echo $discount_value->remaining_discount_limit; ?></div>
<div class="col-md-2 col-sm-2 col-xs-2 text text-right" style="font-size: 12px; font-weight: 700; color: #0f172a;"><?php echo ($discount_value->type == "fix") ? $currency_symbol.(($discount_value->amount)) : ($discount_value->percentage)."%";?></div>
            </div>
         
        </div>
        <?php
           // Close and start a new row after every two columns
           if (($index + 1) % 1 === 0 && $index + 1 !== count($discount_not_applied)) {
           ?>
</div>
<div class="row">
<?php
           }
       }
?>

</div>

<span class="text-danger" id="amount_error"></span>
</div>
<?php
}else{
    ?>
      <div>
      <div class="d-flex justify-content-between align-items-center checkbox-fees text text-muted" style="font-size: 12.5px; padding: 6px 0;">
      <i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_discount_available'); ?>
      </div>
      </div>
    <?php 
 
}
?>
           
            </div> 
         </div>
         <div class="form-group drawer-form-group">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('discount'); ?> (<?php echo $currency_symbol; ?>)<small class="req"> *</small></label>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <div class="row">
                     <div class="col-md-5 col-sm-5 col-lg-5">
                         <div class="">
                             <input type="text" class="form-control" name="amount_discount" id="amount_discount" value="0">
                             <span class="text-danger" id="amount_discount_error"></span>
                         </div>
                         <div id="dynamic_discount_reason_container" style="display:none; margin-top: 10px;">
                             <select class="form-control" id="dynamic_discount_reason_select" style="margin-bottom: 5px;">
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
                             <input type="text" class="form-control" name="dynamic_discount_reason" id="dynamic_discount_reason" placeholder="Type Custom Reason *" style="display:none;">
                             <span class="text-danger" id="dynamic_discount_reason_error"></span>
                             <small class="text-warning"><i class="fa fa-info-circle"></i> Requires admin approval.</small>
                         </div>
                         <script>
                             $('#dynamic_discount_reason_select').change(function(){
                                 var val = $(this).val();
                                 if(val === 'Other') {
                                     $('#dynamic_discount_reason').val('').show();
                                 } else {
                                     $('#dynamic_discount_reason').hide().val(val);
                                 }
                             });
                         </script>
                     </div>
                     <div class="col-md-2 col-sm-2 col-lg-2 ltextright">
                         <label for="inputPassword3" class="control-label" style="font-size: 12px; font-weight: 600;"><?php echo $this->lang->line('fine'); ?> (<?php echo $currency_symbol; ?>)<small class="req">*</small></label>
                     </div>
                     <div class="col-md-5 col-sm-5 col-lg-5">
                         <div class="">
                             <input type="text" class="form-control" name="amount_fine" id="amount_fine" value="<?php echo $remain_amount_fine; ?>">
                             <span class="text-danger" id="amount_fine_error"></span>
                         </div>
                     </div>
                 </div>
             </div><!--./col-sm-9-->
         </div>
         <div class="form-group drawer-form-group">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('payment_mode'); ?></label>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <div class="payment-modes-wrap">
                     <label class="payment-mode-pill"><input type="radio" name="payment_mode_fee" value="Cash" checked="checked"> <span><i class="fa fa-money" style="margin-right:4px;"></i> <?php echo $this->lang->line('cash'); ?></span></label>
                     <label class="payment-mode-pill"><input type="radio" name="payment_mode_fee" value="Cheque"> <span><i class="fa fa-file-text-o" style="margin-right:4px;"></i> <?php echo $this->lang->line('cheque'); ?></span></label>
                     <label class="payment-mode-pill"><input type="radio" name="payment_mode_fee" value="DD"> <span><i class="fa fa-bank" style="margin-right:4px;"></i> <?php echo $this->lang->line('dd'); ?></span></label>
                     <label class="payment-mode-pill"><input type="radio" name="payment_mode_fee" value="bank_transfer"> <span><i class="fa fa-exchange" style="margin-right:4px;"></i> <?php echo $this->lang->line('bank_transfer'); ?></span></label>
                     <label class="payment-mode-pill"><input type="radio" name="payment_mode_fee" value="upi"> <span><i class="fa fa-mobile" style="margin-right:4px; font-size: 14px;"></i> <?php echo $this->lang->line('upi'); ?></span></label>
                     <label class="payment-mode-pill"><input type="radio" name="payment_mode_fee" value="card"> <span><i class="fa fa-credit-card" style="margin-right:4px;"></i> <?php echo $this->lang->line('card'); ?></span></label>
                 </div>
                 <span class="text-danger" id="payment_mode_error"></span>
             </div>
         </div>
         <div class="form-group drawer-form-group" id="bank_account_row" style="display: none;">
             <label class="col-sm-3 col-lg-3 col-md-3 control-label"> Bank Account</label>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <select class="form-control" name="bank_account_id" id="bank_account_id">
                     <?php if(isset($bank_ledgers)) { foreach($bank_ledgers as $bank) { ?>
                         <option value="<?php echo $bank['id']; ?>" <?php if(isset($bank_ledger_name) && $bank['name'] == $bank_ledger_name) echo 'selected'; ?>><?php echo $bank['name']; ?></option>
                     <?php } } ?>
                 </select>
                 <span id="form_collection_bank_account_id_error" class="text text-danger"></span>
             </div>
         </div>
         <div class="form-group drawer-form-group">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label"><?php echo $this->lang->line('note'); ?></label>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <textarea class="form-control" rows="2" id="description" placeholder="Optional notes..."></textarea>
             </div>
         </div>
         <div class="form-group drawer-form-group" id="reference_row" style="display: none;">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label" id="ref_label"> <?php echo $this->lang->line('reference_no') ? $this->lang->line('reference_no') : 'Reference No'; ?></label>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <input class="form-control" name="reference_no" id="reference_no" placeholder="">
                 <span id="form_collection_reference_no_error" class="text text-danger"></span>
             </div>
         </div>
         <div class="form-group drawer-form-group" id="date_row" style="display: none;">
             <label for="inputPassword3" class="col-sm-3 col-lg-3 col-md-3 control-label" id="date_label"> Payment Date</label>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <input class="form-control date_fee" name="cheque_date" id="cheque_date" placeholder="" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly" autocomplete="off">
                 <span id="form_collection_cheque_date_error" class="text text-danger"></span>
             </div>
         </div>
         <div class="form-group drawer-form-group">
             <div class="col-sm-3 col-lg-3 col-md-3"></div>
             <div class="col-sm-9 col-lg-9 col-md-9">
                 <div id="ledger_info" class="drawer-ledger-banner" data-income="<?php echo $income_ledger_name ?? 'Income Ledger'; ?>" data-category="<?php echo $category_head_name ? $category_head_name : 'Default'; ?>">
                     <div><i class="fa fa-info-circle"></i> Depositing to Ledger (Dr): <strong><?php echo $cash_ledger_name ?? 'Cash Account'; ?></strong></div>
                     <div><i class="fa fa-credit-card"></i> Income Ledger (Cr): <strong><?php echo $income_ledger_name ?? 'Income Ledger'; ?></strong></div>
                     <div><i class="fa fa-tags"></i> Category/Head: <strong><?php echo $category_head_name ? $category_head_name : 'Default'; ?></strong></div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="modal-footer" style="padding: 16px 0 0 0; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; align-items: center;">
     <button type="button" class="btn btn-drawer-cancel" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
     <button type="button" class="btn save_button secondary" id="load" data-action="print" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-print"></i> <?php echo $this->lang->line('collect_print'); ?></button>
     <button type="button" class="btn save_button primary" id="load" data-action="collect" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>"><i class="fa fa-money"></i> <?php echo $currency_symbol; ?> <?php echo $this->lang->line('collect_fees'); ?></button>
 </div>