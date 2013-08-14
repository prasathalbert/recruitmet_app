<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("myprofile/changepassword");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"changepassword-frm","id"=>"changepassword-frm","method"=>"post");
	echo form_open("myprofile/changepassword",$frm);  

?>
<div  id="b_right"  style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		
				<div id="i_title">
                    <label for="bfname">Old Password</label>
                    <input name="old_pass" type="password" id="old_pass" value=""/>
                </div>
                <div id="i_title">
                    <label for="blname">New Pssword</label>
                    <input name="new_pass" type="password" id="new_pass" value=""/>
                </div>
                <div id="i_title">
                    <label for="bemail">Confirm Password</label>
                    <input name="confirm_pass" type="password" id="confirm_pass" value=""/>
                </div>
					
                 <div id="i_publish">
                    <ul>
                        
                        <li><input type="submit" class="sub_button" name="change_password" value="Change Password" /></li>
                        
                        
                    </ul>
                </div>
				</div>
			</div>	