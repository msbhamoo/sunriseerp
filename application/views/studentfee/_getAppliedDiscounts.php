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
     </style>
<div class="row">

    <?php 
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    ?>
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo $this->lang->line('date'); ?></th>
                <th><?php echo $this->lang->line('payment_id'); ?></th>
                <th><?php echo $this->lang->line('fees_discount'); ?></th>                 
                <th><?php echo $this->lang->line('value'); ?></th>
                <th>Status</th>
            </tr>
        </thead>
    <?php
    $count=1;
    $has_records = false;
	if(!empty($fees_discount)){
        $has_records = true;
        foreach ($fees_discount as $discount_index => $discount_value) {
            ?>
                <tr>
                    <td><?php echo $count;?></td>  
                    <td><?php echo $this->customlib->dateformat($discount_value->date);?></td>    
                    <td><?php echo $discount_value->invoice_id."/".$discount_value->sub_invoice_id;?></td>     
                    <td><?php echo $discount_value->name;?></td>     
                    <td><?php echo ($discount_value->type == "fix") ? $currency_symbol.(amountFormat($discount_value->amount)) : ($discount_value->percentage)."%";?></td>                   
                    <td><span class="label label-success">Approved</span></td>
                </tr>
            <?php 
            $count++;
        }
    }
    
    if(!empty($provisional_discounts)){
        $has_records = true;
        foreach ($provisional_discounts as $prov_discount) {
            ?>
                <tr>
                    <td><?php echo $count;?></td>  
                    <td><?php echo $this->customlib->dateformat($prov_discount->created_at);?></td>    
                    <td><?php echo $prov_discount->student_fees_deposite_id."/".$prov_discount->sub_invoice_id;?></td>     
                    <td><?php echo $prov_discount->reason;?></td>     
                    <td><?php echo ($prov_discount->discount_type == "fix") ? $currency_symbol.(amountFormat($prov_discount->amount)) : ($prov_discount->percentage)."%";?></td>                   
                    <td>
                        <?php if ($prov_discount->status == 'provisional' || $prov_discount->status == 'pending'): ?>
                            <span class="label label-warning">Pending Approval</span>
                        <?php elseif ($prov_discount->status == 'approved'): ?>
                            <span class="label label-success">Approved</span>
                        <?php elseif ($prov_discount->status == 'rejected'): ?>
                            <span class="label label-danger">Rejected</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php 
            $count++;
        }
    }
    
    if (!$has_records) {
		echo "<tr> <td colspan = '6' class='text-danger'><center>".$this->lang->line('no_record_found')."</center></td>   </tr>";
	}
?>
</table>
<?php
?>
</div>