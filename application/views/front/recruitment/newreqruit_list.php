<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("recruitment");
?>
</div>
<div class="main_content">

<?php if($current_menu=="recruitment" && $this->phpsession->get("ad_user_level")==3){ ?>
<a href="<?php echo site_url("recruitment/add");?>" class="publish flt_rght">Upload New Authorization Form</a>
<?php } ?>
<div class="flashmsg">
<?php 
	echo getFlashMsg();
	echo getErrorMsg($error);
?>
</div>
<?php
if($recruitments!=false)
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
		<th width="25%">Recruit Name</th>
		<th width="15%">Recruit Email</th>
        <th width="15%">Status</th>
    	<?php if(($current_menu=="recruitment" && $this->phpsession->get("ad_user_level")!=3) || $current_menu=="recievedrecruitment"){ ?>
        <th width="15%">Uploaded By</th>
        <?php } ?>
        <th width="10%">Uploaded On</th>
		<th width="15%">Actions</th>		
	</tr>
	</thead>
<?php
	$sno =1;
	$odd = "tbl_home_odd";
	$even = "tbl_home_even";
	
	foreach($recruitments->result() as $u)
	{
		$cls=$odd;
		if($sno%2 === 0) 
			$cls = $even;
?>
		<tr class="<?php echo $cls;?>">
			<td><?php echo $sno++;?></td>
			<td><?php echo $u->RecruitName;?></td>
			<td><?php echo $u->RecruitEmail;?></td>
			<td><?php 
            global $auform_status;
            if(isset($u->Status))
            echo $auform_status[$u->Status];
            
            ?></td>
            <?php if(($current_menu=="recruitment" && $this->phpsession->get("ad_user_level")!=3) || $current_menu=="recievedrecruitment"){ ?>
            <td><?php echo $u->FirstName." ".$u->LastName;?></td>
            <?php } ?>
            <td><?php echo date("d-m-Y",strtotime($u->UploadedOn));?></td>
			<td style="text-align: left;">
            <?php 
            if(checkAccess($this->phpsession->get("ad_user_level"),6,false))
            {
                ?>
    			<a href="<?php echo site_url("recruitment/download/form/".$u->FormId);?>" target="_blank" title="Download"><img src="<?php echo base_url("images/icons/report.png");?>" alt='Download' /></a>
                <a href="<?php echo site_url("recruitment/view/".$u->FormId);?>" title="View"><img src="<?php echo base_url("images/icons/magnifier.png");?>" alt='View' /></a>
    			<?php 
            }
            if(checkAccess($this->phpsession->get("ad_user_level"),8,false))
            {
			     if($u->Status=="1")
                 {
                     ?>
                     <a href="<?php echo site_url("recruitment/forward/".$u->FormId);?>" title="Forward to HR"><img src="<?php echo base_url("images/icons/forward-icon.png");?>" alt='Forward to HR' /></a>
                    <?php 
                 }
            }
            if(checkAccess($this->phpsession->get("ad_user_level"),9,false))
            {
			     if($u->Status=="2" || $u->Status=="4")
                 {
                    ?>
                    <a href="<?php echo site_url("recruitment/uploadoffer/".$u->FormId);?>" title="Upload Offer Letter"><img src="<?php echo base_url("images/icons/folder_page_add.png");?>" alt='Upload Offer Letter' /></a>
                    <?php
                }
            }
            if($u->Status>="3")
            {
                if(checkAccess($this->phpsession->get("ad_user_level"),11,false))
                {
                ?>
                    <a href="<?php echo site_url("recruitment/download/offer/".$u->FormId);?>" target="_blank" title="Download Offer Letter"><img src="<?php echo base_url("images/icons/folder_table.png");?>" alt='Download Offer Letter' /></a>
                <?php
                }
            }
            
            if($u->Status=="3")
            {    
                if(checkAccess($this->phpsession->get("ad_user_level"),10,false))
                {
                ?>    
                    <a href="<?php echo site_url("recruitment/changeoffer/accept/".$u->FormId);?>" title="Approve Offer Letter"><img src="<?php echo base_url("images/icons/success_msg.png");?>" alt='Approve Offer Letter' /></a>
                    <a href="<?php echo site_url("recruitment/changeoffer/reject/".$u->FormId);?>" title="Reject Offer Letter"><img src="<?php echo base_url("images/icons/red_cross.png");?>" alt='Reject Offer Letter' /></a>
                <?php
                }
            }
            
            if($u->Status=="5")
            {
                if(checkAccess($this->phpsession->get("ad_user_level"),12,false))
                {
                ?>    
                    <a href="<?php echo site_url("recruitment/salarybreakup/".$u->FormId);?>" title="Create Salary Breakup"><img src="<?php echo base_url("images/icons/page_add.png");?>" alt='Create Salary Breakup' /></a>
                <?php
                }
            }
            
            if($u->Status>="7")
            {
                if(checkAccess($this->phpsession->get("ad_user_level"),13,false))
                {
                ?>
                    <a href="<?php echo site_url("recruitment/download/salary/".$u->FormId);?>" target="_blank" title="Download Salary Breakup"><img src="<?php echo base_url("images/icons/coins.png");?>" alt='Download Salary Breakup' /></a>
                <?php
                }
                
                if($u->Status=="7" && checkAccess($this->phpsession->get("ad_user_level"),14,false))
                {
                ?>
                    <a href="<?php echo site_url("recruitment/changeoffer/reqconfirm/".$u->FormId);?>" title="Confirmed by Recruiter"><img src="<?php echo base_url("images/icons/success_msg.png");?>" alt='Confirmed by Recruiter' /></a>
                <?php
                }
            }
            
            if($u->Status>="8")
            {
                if(checkAccess($this->phpsession->get("ad_user_level"),15,false))
                {
                ?>
                    <a href="<?php echo site_url("recruitment/payrolldocs/".$u->FormId);?>" title="Upload Documents for Payroll Process"><img src="<?php echo base_url("images/icons/book_add_32.png");?>" alt='Upload Documents for Payroll Process' /></a>
                <?php    
                }
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