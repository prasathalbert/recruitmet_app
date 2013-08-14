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

    $frm = array("name"=>"recruitment-save-frm","id"=>"recruitment-save-frm","method"=>"post");
	echo form_open("recruitment/addmessage",$frm);  

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
                        <td> <a href="<?php echo site_url("recruitment/download/form/".$recruit->FormId);?>" target="_blank" title="download"><img src="<?php echo base_url("images/icons/179-notepad.png");?>" alt="Download" /></a></td>
                </tr>
                <?php if($recruit->Status>=5 && checkAccess($this->phpsession->get("ad_user_level"),11,false)){ ?>
                <tr>
                        <th>Offer Letter</th>
                        <td> <a href="<?php echo site_url("recruitment/download/offer/".$recruit->FormId);?>" target="_blank" title="download"><img src="<?php echo base_url("images/icons/185-printer.png");?>" alt="Download Offer Letter" /></a></td>
                </tr>
                <?php } ?>
                <?php if($recruit->Status>=6 && checkAccess($this->phpsession->get("ad_user_level"),13,false)){ ?>
                <tr>
                        <th>Salary Breakup</th>
                        <td> <a href="<?php echo site_url("recruitment/download/salary/".$recruit->FormId);?>" target="_blank" title="download"><img src="<?php echo base_url("images/icons/192-credit-card.png");?>" alt="Download Salary Breakup" /></a></td>
                </tr>
                <?php } ?>
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
        <input type="hidden" name="auformid" id="auformid" value="<?php echo $recruit->FormId; ?>" />
        <input type="hidden" name="auformstatus" id="auformstatus" value="<?php echo $recruit->Status; ?>" />
        <?php
        }
        ?>
        
        <div>
            <div id="i_title">
                
    			<textarea name="recmessage" id="recmessage"><?php echo $this->input->post("recmessage");?></textarea>
    		</div>
                 
            <div id="i_publish">
    			<ul>
    				
    				<li><input type="submit" class="sub_button" name="auformmsg_add" value="Save" /></li>
                    
                    
    			</ul>
                
    		</div>
        </div>  
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   