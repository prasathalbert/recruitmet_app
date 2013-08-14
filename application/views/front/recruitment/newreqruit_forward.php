<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("recruitment/view");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"recruitment-forward-frm","id"=>"recruitment-forward-frm","method"=>"post");
	echo form_open("recruitment/forward_save",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
        <?php
        if($recruit!=false)
        {
        ?>
		<div>
        
            <table class="detail_table">
					
				<tr>
						<th>Recruit Name</th>
                        <td> <?php echo $recruit->RecruitName; ?></td>
                </tr>
                <tr>
                        <th>Recruit Email</th>
                        <td><?php echo $recruit->RecruitEmail; ?> </td>
                </tr>
                <tr>
                        <th>Uploaded By</th>
                        <td> <?php echo $recruit->FirstName." ".$recruit->LastName; ?></td>
                </tr>
                <tr>
                        <th>Authorization Form</th>
                        <td> <a href="<?php echo base_url(PLACE_RECRUIT_DOC."/".$recruit->FormLocation);?>" target="_blank" title="download"><img src="<?php echo base_url("images/icons/179-notepad.png");?>" alt="Download" /></a></td>
                </tr>
                <tr>
                        <th>Forwarded to</th>
                        <td>
                            
                			<?php
                            $tempinc=1; 
                            foreach($distributionlist as $ds)
                            {
                                $check_attribute="";
                                if($haveaccess && in_array($ds["UserId"],$haveaccess))
                                    $check_attribute = 'checked="checked"  onclick="return false" onkeydown="return false" ';
                                else
                                    {
                                        $disp_array = $this->input->post("recdslist");
                                        if($disp_array && in_array($ds["UserId"],$disp_array))
                                            $check_attribute = 'checked="checked"';
                                    }
                                
                                if($this->input->post("recdslist")==$ds["UserId"])
                                    $check_attribute = 'checked="checked"';
                                ?>
                                <input class="checkbox" id="recdslist<?php echo $tempinc; ?>" name="recdslist[]" value="<?php echo $ds["UserId"]; ?>" <?php echo $check_attribute; ?> type="checkbox" /><label style="display: inline-block;" for="recdslist<?php echo $tempinc; ?>"><?php echo $ds["Name"]; ?> (<?php echo $ds["EmailId"]; ?>)</label> <br />
                                
                                <?php
                                $tempinc++;
                            }
                            ?>
                		</td>
                </tr>
                <tr>
						<th>Messages</th>
                        <td>
                            <?php 
                            if($messages) { 
                                foreach($messages->result() as $u)
                                {
                            ?>
                                    <div class="convers">
                                    <span><?php echo $u->FirstName." ".$u->LastName; ?>:</span>
                                    <p><?php echo $u->Notes; ?></p>
                                    <div class="small"><?php echo date("d-m-Y H:i:s", strtotime($u->NotesOn)); ?></div>
                                    </div>
                            <?php 
                                } 
                            }
                            ?>
                        </td>
				</tr>
				
			</table>
            
        </div>
        <input type="hidden" name="auformid" id="auformid" value="<?php echo $recruit->FormId; ?>" />
        
        <?php
        }
        ?>
        
        <div>
            <div id="i_title">
                
    			<textarea name="recmessage" id="recmessage"><?php echo $this->input->post("recmessage");?></textarea>
    		</div>
                 
            <div id="i_publish">
    			<ul>
    				
    				<li><input type="submit" class="sub_button" name="auform_forward" value="Forward" /></li>
                    
                    
    			</ul>
                
    		</div>
        </div>  
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   