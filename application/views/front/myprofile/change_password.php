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

    $frm = array("name"=>"myprofile-changepass-frm","id"=>"myprofile-changepass-frm","method"=>"post");
	echo form_open("myprofile/change_password",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		<div id="i_title">
            <label for="oldpass">Old Password</label>
			<input name="oldpass" type="password" id="oldpass" value=""/>
		</div>
        <div id="i_title">
            <label for="newpass">New Password</label>
			<input name="newpass" type="password" id="newpass" value=""/>
		</div>
        <div id="i_title">
            <label for="renewpass">Re-type New Password</label>
			<input name="renewpass" type="password" id="renewpass" value=""/>
		</div>
        

        <div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" name="change_pwd" value="Change Password" /></li>
                
                
			</ul>
		</div>
               
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   