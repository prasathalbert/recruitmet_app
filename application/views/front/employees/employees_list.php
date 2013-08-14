<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="breadcump">
<?php
if($current_menu=="employee"){
    echo $this->breadcrumb->generateBreadcump("employees");
    $typeUrl="employees";
    $typeText= "Employee";
}
elseif($current_menu=="branchadmin"){
    echo $this->breadcrumb->generateBreadcump("branchadmin");
    $typeUrl="branchadmin";
    $typeText= "Branch Admin";
}
elseif($current_menu=="gkmemployees"){
    echo $this->breadcrumb->generateBreadcump("gkmemployees");
    $typeUrl="gkmemployees";
    $typeText= "GKM Employee";
}
?>
</div>
<div class="main_content">

<div class="right_top_content">
<a href="<?php echo site_url($typeUrl."/add");?>" class="publish">Add New <?php echo $typeText;?></a> &nbsp;  
<?php if($current_menu=="employee"){ ?>
<a href="<?php echo site_url($typeUrl."/import_excel");?>" class="publish marght10">Import Employees</a> &nbsp;
<?php } ?>
</div>
<div class="flashmsg">
<?php 
	echo getFlashMsg();
	echo getErrorMsg($error);
?>
</div>
<?php
if($employees!=false)
{
?>

<link href="<?php echo base_url()?>js/datatables/demo_table_jui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/complete.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/admin.js"></script>

<?php
	
?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbl_home" id="datatable">
	<thead>
	<tr class="tbl_head">
		<th width="5%">S.No</th>
		<th width="25%"><?php echo $typeText;?> Name</th>
        <th width="15%">Email Address</th>
        <?php if($current_menu=="gkmemployees"){ ?>
        <th width="15%">Designation</th>
        <?php } ?>
        <th width="10%">Actions</th>		
	</tr>
	</thead>
<?php
	$sno =1;
	$odd = "tbl_home_odd";
	$even = "tbl_home_even";
	
	foreach($employees->result() as $u)
	{
		$cls=$odd;
		if($sno%2 === 0) 
			$cls = $even;
?>
		<tr class="<?php echo $cls;?>">
			<td><?php echo $sno++;?></td>
			<td><?php echo $u->FirstName." ".$u->LastName;?></td>
			<td><?php echo $u->EmailId;?></td>
			<?php if($current_menu=="gkmemployees"){ ?>
            <td><?php echo $u->DesignationName;?></td>
            <?php } ?>
            
    			<td>
    			<a href="<?php echo site_url($typeUrl."/edit/".$u->UserId);?>" title="Edit"><img src="<?php echo base_url("images/icons/user_edit.png");?>" alt='Edit' /></a>
    			<?php
    			if($u->IsActive==0)
    			{
    				$title="Un-Block this ".$typeText;
    				$img = "deactivate.png";
    				$to = 1;
    			}
    			else
    			{
    				$title="Block this ".$typeText;
    				$img = "activate.png";
    				$to=0;
    			}
    				
    			?>
    			
    			<a href="javascript:isChange('<?php echo $u->UserId;?>','<?php echo $title;?>',<?php echo $to;?>);" title="<?php echo $title;?>"><img src="<?php echo base_url("images/icons/".$img);?>" alt='<?php echo $title;?>' /></a>
    			<?php if(checkAccess($this->phpsession->get("ad_user_level"),24,false)){ ?>
                    <?php if($current_menu=="employee"){ ?>  
                    <a href="<?php echo site_url($typeUrl."/attendance/".$u->UserId);?>" title="Mark Attendance"><img src="<?php echo base_url("images/icons/user_add.png");?>" alt='Mark Attendance' /></a>
                    <?php } ?>
                <?php } ?>
    			</td>
            
		</tr>
<?php

	}
	
?>
	</table>
	<script type="text/javascript">
	function isChange(id,msg,to_status)
	{
		if(confirm("Are you sure you want to "+msg+"?"))
		{
			window.location.href="<?php echo site_url($typeUrl."/change");?>/"+id+"/"+to_status;
		}		
	}
	</script>
<?php
}
else
	echo "<p>No Records Found</p>";
?>
</div>