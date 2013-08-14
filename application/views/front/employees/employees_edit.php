<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="breadcump">
<?php
if($current_menu=="employee"){
    echo $this->breadcrumb->generateBreadcump("employees/edit");
    $typeUrl="employees";
    $typeText= "Employee";
}
elseif($current_menu=="branchadmin"){
    echo $this->breadcrumb->generateBreadcump("branchadmin/edit");
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

<?php

    $frm = array("name"=>$typeUrl."-update-frm","id"=>$typeUrl."-update-frm","method"=>"post");
	echo form_open($typeUrl."/update",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		<div id="i_title">
            <label for="bfname">First Name</label>
			<input name="bfname" type="text" id="bfname" value="<?php echo $employeedata->FirstName;?>"/>
		</div>
        <div id="i_title">
            <label for="blname">Last Name</label>
			<input name="blname" type="text" id="blname" value="<?php echo $employeedata->LastName;?>"/>
		</div>
        <div id="i_title">
            <label for="bemail">Email Address (UserName)</label>
			<input name="bemail" class="readonly" type="text" id="bemail" readonly="true" value="<?php echo $employeedata->EmailId;?>"/>
		</div>
        <?php if($current_menu=="gkmemployees"){ ?>
        <div id="i_title">
            <label for="bdesignation">Designation</label>
            
			<select name="bdesignation" id="bdesignation">
            <option value="">Select Designation</option>
            <?php 
            global $designation_list;		
		      $gl = $designation_list;
            foreach($gl as $adlid=>$adname)
            {
                if($employeedata->DesignationId==$adlid)
                {
                    ?>
                    <option selected="true" value="<?php echo $adlid;?>"><?php echo $adname;?></option>
                    <?php
                }
                else
                {
                    ?>
                    <option value="<?php echo $adlid;?>"><?php echo $adname;?></option>
                    <?php
                }
            } 
                
            ?>
                
            </select>
		</div>
        <?php } ?>
        <div id="i_title">
            <label for="bcontact">Contact Number</label>
			<input name="bcontact" type="text" maxlength="15" id="bcontact" value="<?php echo $employeedata->ContactNumber;?>"/>
		</div>
        <div id="i_title">
            <label for="baddress">Address</label>
			<textarea name="baddress" id="baddress"><?php echo $employeedata->Address;?></textarea>
		</div>
        <div id="i_title">
            <label for="bgender">Gender</label>
            
			<select name="bgender" id="bgender">
            <option value="">Select Gender</option>
            <?php 
            global $gender_list;		
		      $gl = $gender_list;
            foreach($gl as $adlid=>$adname)
            {
                if($employeedata->Gender==$adlid)
                {
                    ?>
                    <option selected="true" value="<?php echo $adlid;?>"><?php echo $adname;?></option>
                    <?php
                }
                else
                {
                    ?>
                    <option value="<?php echo $adlid;?>"><?php echo $adname;?></option>
                    <?php
                }
            } 
                
            ?>
                
            </select>
		</div>
        
        <div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" name="employee_update" value="Update" /></li>
                
                
			</ul>
		</div>
        <input type="hidden" name="employee_id" value="<?php echo $employeedata->UserId;?>" />
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   