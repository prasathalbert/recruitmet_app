<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("payment");
?>
</div>
<div class="main_content">

<?php if($current_menu=="payment" && $this->phpsession->get("ad_user_level")==3){ ?>
<a href="<?php echo site_url("payment/request");?>" class="publish flt_rght">New Payment Request</a>
<?php } ?>
<div class="flashmsg">
<?php 
	echo getFlashMsg();
	echo getErrorMsg($error);
?>
</div>
<?php
if($paymentrequests!=false)
{
?>

<link href="<?php echo base_url()?>js/datatables/demo_table_jui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/complete.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/admin.js"></script>

<?php
	
?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbl_home" id="datatable">
	<thead>
	<tr class="tbl_head">
		<th width="5%">S.No</th>
		<th width="25%">Payment for</th>
		<th width="15%">Bill Amount <br /><small>(In Rs.)</small></th>
        
        <th width="15%">Due Date</th>
        <th width="15%">Status</th>
    	<?php if(($current_menu=="payment" && $this->phpsession->get("ad_user_level")!=3) || $current_menu=="recievedpaymentrequests"){ ?>
        <th width="15%">Branch Name</th>
        <th width="15%">Requested By</th>
        <?php } ?>
        <th width="10%">Requested On</th>
		<th width="15%">Actions</th>		
	</tr>
	</thead>
<?php
	$sno =1;
	$odd = "tbl_home_odd";
	$even = "tbl_home_even";
	global $payment_type_list;	
    global $payment_status;    
                    
	foreach($paymentrequests->result() as $u)
	{
		$cls=$odd;
		if($sno%2 === 0) 
			$cls = $even;
            
?>
		<tr class="<?php echo $cls;?>">
			<td><?php echo $sno++;?></td>
			<td><?php echo $u->PaymentRequestFor;?></td>
			<td>Rs. <?php echo number_format($u->BillAmount, 2, '.', ',');?></td>
            
            <td><?php echo date("dS M, Y",strtotime($u->DueDate));?></td>
			<td><?php if(isset($u->Status)) echo $payment_status[$u->Status]; ?> </td>
            
            <?php if(($current_menu=="payment" && $this->phpsession->get("ad_user_level")!=3) || $current_menu=="recievedpaymentrequests"){ ?>
            <td><?php echo $u->BranchName;?></td>
            <td><?php echo $u->FirstName." ".$u->LastName;?></td>
            <?php } ?>
            
            <td><?php echo date("dS M, Y",strtotime($u->RequestedOn));?></td>
			<td style="text-align: left;">
            <?php 
            if(checkAccess($this->phpsession->get("ad_user_level"),19,false))
            {
                ?>
                <a href="<?php echo site_url("payment/view/".$u->PaymentRequestId);?>" title="View"><img src="<?php echo base_url("images/icons/magnifier.png");?>" alt='View' /></a>
    			<?php 
            }
            ?>
            
            
			</td>
		</tr>
<?php

	}
	
?>
	</table>

<?php
}
else
	echo "<p>No Records Found</p>";
?>
</div>