<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("myprofile/edit");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"myprofile-update-frm","id"=>"myprofile-update-frm","method"=>"post");
	echo form_open("myprofile/update",$frm);  

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
            <label for="bemail">Employee Email (UserName)</label>
			<input name="bemail" class="readonly" type="text" id="bemail" readonly="true" value="<?php echo $employeedata->EmailId;?>"/>
		</div>
        
        <div id="i_title">
            <label for="bcontact">Contact Number</label>
			<input name="bcontact" type="text" maxlength="15" id="bcontact" value="<?php echo $employeedata->ContactNumber;?>"/>
		</div>
        <div id="i_title">
            <label for="baddress">Branch Address</label>
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
               
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   