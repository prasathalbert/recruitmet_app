<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("recruitment/add");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"recruitment-save-frm","id"=>"recruitment-save-frm","method"=>"post", "enctype"=>"multipart/form-data");
	echo form_open("recruitment/save",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		<div id="i_title">
            <label for="recname">Recruit Name</label>
			<input name="recname" type="text" id="recname" value="<?php echo $this->input->post("recname");?>"/>
		</div>
        <div id="i_title">
            <label for="recemail">Recruit Email</label>
			<input name="recemail" type="text" id="recemail" value="<?php echo $this->input->post("recemail");?>"/>
		</div>
        <div id="i_title">
            <label for="auform_file">Authorization Form</label>
			<input type="file" name="auform_file" id="auform_file" />
		</div>
        <div id="i_title">
            <label for="recmessage">Comments</label>
			<textarea name="recmessage" id="recmessage"><?php echo $this->input->post("recmessage");?></textarea>
		</div>
        <div id="i_title">
            <label for="recmessage">Distribution List</label><br />
			<?php 
             $tempinc=1;
            foreach($distributionlist as $ds)
            {
               
                if($ds["UserId"]!=$this->phpsession->get("ad_user_id"))
                {
                    $check_attribute="";
                    if($ds["Designationid"]=="8")
                        $check_attribute = 'checked="checked"  onclick="return false" onkeydown="return false" ';
                    else
                        {
                            $disp_array = $this->input->post("recdslist");
                            if($disp_array && in_array($ds["UserId"],$disp_array))
                                $check_attribute = 'checked="checked"';
                        }
                    ?>
                    <input class="checkbox" id="recdslist<?php echo $tempinc; ?>" name="recdslist[]" value="<?php echo $ds["UserId"]; ?>" <?php echo $check_attribute; ?> type="checkbox" /><label style="display: inline-block;" for="recdslist<?php echo $tempinc; ?>"><?php echo $ds["Name"]; ?> (<?php echo $ds["EmailId"]; ?>)</label> <br />
                    <?php
                }
                $tempinc++;
            }
            ?>
		</div>
        
        <div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" name="auform_add" value="Send" /></li>
                
                
			</ul>
		</div>
        
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   