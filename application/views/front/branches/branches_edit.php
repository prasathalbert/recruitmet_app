<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("branches/edit");
?>
</div>
<div class="main_content">
<?php
if($branchdata)
{
    $frm = array("name"=>"branch-edit-frm","id"=>"branch-edit-frm","method"=>"post");
	echo form_open("branches/update",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		<div id="i_title">
            <label for="bname">Branch Name</label>
			<input name="bname" type="text" id="bname" value="<?php echo $branchdata->BranchName;?>"/>
		</div>
        <div id="i_title">
            <label for="blocation">Branch Location</label>
			<input name="blocation" type="text" id="blocation" value="<?php echo $branchdata->BranchLocation;?>"/>
		</div>
        <div id="i_title">
            <label for="baddress">Branch Address</label>
			<textarea name="baddress" id="baddress"><?php echo $branchdata->BranchAddress;?></textarea>
		</div>
       
        <div id="i_title">
            <label for="baddress">Branch Admin</label>
            
			<select name="badmin" id="badmin">
            <option value="">Select Branch Admin</option>
            <?php foreach($admin_list as $adlid=>$adname)
            {
                if($branch_admin->UserId==$adlid)
                {
                    ?>
                    <option selected="true" value="<?php echo $adlid;?>"><?php echo $adname["Name"];?></option>
                    <?php
                }
                else
                {
                    ?>
                    <option value="<?php echo $adlid;?>"><?php echo $adname["Name"];?></option>
                    <?php
                }
            } 
                
            ?>
                
            </select>
		</div>
        <div id="i_title">
            <label for="baddress">Branch Employees</label>
            <?php $emparray=$existingemployees;
                    if(!$emparray)
                        $emparray=array();
            ?>
			<select multiple="true" name="bemployees[]" id="bemployees">
            <?php foreach($employee_list as $adelid=>$adename)
            {
                if(in_array($adelid,$emparray))
                {
                    ?>
                    <option selected="true" value="<?php echo $adelid;?>"><?php echo $adename["Name"];?></option>
                    <?php
                }
                else
                {
                    ?>
                    <option value="<?php echo $adelid;?>"><?php echo $adename["Name"];?></option>
                    <?php
                }
            } 
                
            ?>
                
            </select>
		</div>
        <div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" name="branch_update" value="Update" /></li>
                
                
			</ul>
		</div>
        <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branchdata->BranchId;?>" />
</div>
 
<?php
   echo form_close();
   
}
?>
    
</div>   