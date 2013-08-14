<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("branches/add");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"branch-save-frm","id"=>"branch-save-frm","method"=>"post");
	echo form_open("branches/save",$frm);  

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
			<input name="bname" type="text" id="bname" value="<?php echo $this->input->post("bname");?>"/>
		</div>
        <div id="i_title">
            <label for="blocation">Branch Location</label>
			<input name="blocation" type="text" id="blocation" value="<?php echo $this->input->post("blocation");?>"/>
		</div>
        <div id="i_title">
            <label for="baddress">Branch Address</label>
			<textarea name="baddress" id="baddress"><?php echo $this->input->post("baddress");?></textarea>
		</div>
        <div id="i_title">
            <label for="baddress">Branch Admin</label>
            
			<select name="badmin" id="badmin">
            <option value="">Select Branch Admin</option>
            <?php foreach($admin_list as $adlid=>$adname)
            {
                if($this->input->post("badmin")==$adlid)
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
            <?php $emparray=$this->input->post("bemployees");
            
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
				
				<li><input type="submit" class="sub_button" name="branch_add" value="Save" /></li>
                
                
			</ul>
		</div>
        
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   