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

    $frm = array("name"=>"recruitment-uploadsalary-frm","id"=>"recruitment-uploadsalary-frm","method"=>"post",  "enctype"=>"multipart/form-data");
	echo form_open("recruitment/uploadsalary_save",$frm);  

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
                        <th>Send to</th>
                        <td>
                            
                			<?php echo $recruit->RecruitEmail; ?>
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
        
        <div><br />
            <div id="i_title">
                <label for="auform_file">Upload Salary Breakup</label>
                <input type="file" name="auform_file" id="auform_file" />
            </div>
            
            <div id="i_title">
                <label for="recmessage">Comments</label>
    			<textarea name="recmessage" id="recmessage"><?php echo $this->input->post("recmessage");?></textarea>
    		</div>
                 
            <div id="i_publish">
    			<ul>
    				
    				<li><input type="submit" class="sub_button" name="upload_salary" value="Upload" /></li>
                    
                    
    			</ul>
                
    		</div>
        </div>  
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   