<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("recruitment/payrolldocs");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"recruitment-payrolldocs-frm","id"=>"recruitment-payrolldocs-frm","method"=>"post",  "enctype"=>"multipart/form-data");
	echo form_open("recruitment/payrolldocs_save",$frm);  

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
        
        if(checkAccess($this->phpsession->get("ad_user_level"),15,false))
        {
        ?>
        
        <div>
        <b style="font-size:12px; color: #1B6AAD;">Payroll Documents</b>
        <table class="detail_table">
					
        <?php 
        $docsStatus = 0;
        
        foreach($payrolldocslist as $pd) 
        {
            if(isset($pd->FormId) && $pd->FormId!="")
            {
                ?>
                
				<tr>
						<th><?php echo $pd->RecruitFileName; ?></th>
                        <td>
                            
                            <a style="text-decoration: none; color: #1B6AAD;" href="<?php echo site_url("recruitment/download/payroll/".$u->FormId."/".$pd->RecruitFileId);?>" target="_blank" title="Download <?php echo $pd->RecruitFileName; ?>">
                            <img src="<?php echo base_url("images/icons/68-paperclip.png");?>" />
                            </a>
                             &nbsp; &nbsp; <a href="<?php echo site_url("recruitment/removepaydoc/".$u->FormId."/".$pd->RecruitFileId);?>" style="color: red;" onclick="return confirm('Are you Sure to Delete?');" title="Delete  <?php echo $pd->RecruitFileName; ?>">Delete</a>
                        </td>
                </tr>
                <?php
            }
            else
            {
                $docsStatus = 1;
        ?>
            <tr>
				<th><?php echo $pd->RecruitFileName; ?></th>
                <td>
                    <div id="i_title">
                        <?php
                        $fname = "auform_file_".$pd->RecruitFileId;
                        ?>
                        <label for="<?php echo $fname; ?>"><?php echo $pd->RecruitFileName; ?></label>
                        <input type="file" name="<?php echo $fname; ?>" id="<?php echo $fname; ?>" />
                    </div>
                </td>
            </tr>
        <?php
            } 
        } 
        ?>   
        </table>
        </div>  
        <div>    
              <?php
              if($docsStatus=="1")
              {
              ?>   
            <div id="i_publish">
    			<ul>
    				
    				<li><input type="submit" class="sub_button" name="payrolldocs_save" value="Upload" /></li>
                    
    			</ul>
                
    		</div>
            <?php
            }
            
            ?>
        </div> 
        
        <?php
        }
        ?> 
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   