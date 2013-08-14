<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("branches");
?>
</div>
<div class="main_content">


<a href="<?php echo site_url("branches/add");?>" class="publish flt_rght">Add New Branch</a>
<div class="flashmsg">
<?php 
	echo getFlashMsg();
	echo getErrorMsg($error);
?>
</div>
<?php
if($branches!=false)
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
		<th width="25%">Branch Name</th>
		<th width="15%">Location</th>
        <th width="35%">Address</th>
		
		<th width="10%">Actions</th>		
	</tr>
	</thead>
<?php
	$sno =1;
	$odd = "tbl_home_odd";
	$even = "tbl_home_even";
	
	foreach($branches->result() as $u)
	{
		$cls=$odd;
		if($sno%2 === 0) 
			$cls = $even;
?>
		<tr class="<?php echo $cls;?>">
			<td><?php echo $sno++;?></td>
			<td><?php echo $u->BranchName;?></td>
			<td><?php echo $u->BranchLocation;?></td>
			<td><?php echo $u->BranchAddress;?></td>
			
			<td>
			<a href="<?php echo site_url("branches/edit/".$u->BranchId);?>" title="Edit"><img src="<?php echo base_url("images/icons/user_edit.png");?>" alt='Edit' /></a>
			<?php
			if($u->IsActive==0)
			{
				$title="Un-Block this Branch";
				$img = "deactivate.png";
				$to = 1;
			}
			else
			{
				$title="Block this Branch";
				$img = "activate.png";
				$to=0;
			}
				
			?>
			
			<a href="javascript:isChange('<?php echo $u->BranchId;?>','<?php echo $title;?>',<?php echo $to;?>);" title="<?php echo $title;?>"><img src="<?php echo base_url("images/icons/".$img);?>" alt='<?php echo $title;?>' /></a>
			
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
			window.location.href="<?php echo site_url("branches/change");?>/"+id+"/"+to_status;
		}		
	}
	</script>
<?php
}
else
	echo "<p>No Branches Found</p>";
?>
</div>