
<table width="598" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" style="font-size:1px;" valign="top" bgcolor="#f2f2f2"><img src="<?php echo base_url("images/email/header-05.gif"); ?>" alt=" " width="5" height="130" style="display:block;" /></td>
    <td width="20">&nbsp;</td>
    <td valign="top">
    	
      <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td style="font-family: Verdana; font-size:20px; color:#1D5CA3; padding-bottom:5px;" align="left"><?php echo $title; ?></td>
        </tr>
        <tr>
            <td style="font-family: Verdana; font-size:16px; color:#555555; line-height:22px; padding-bottom:10px;" align="left"> <br /> <?php if($name) echo $name.","; else echo "&nbsp;"; ?> </td>
        </tr>
        <tr>
            <td style="font-family: Verdana; font-size:12px; color:#222222; line-height:19px; padding-bottom:7px;" align="left">
                
                <table width="100%" cellspacing="0" bordercolor="#d9d9d9" cellpadding="5" border="1">
                    <tr><td width="50%">New Recuit Name</td><td><?php echo $recruitdetails->RecruitName; ?></td></tr>
                    <tr><td>New Recuit Email</td><td><?php echo $recruitdetails->RecruitEmail; ?></td></tr>
                    <tr><td>Uploaded By</td><td><?php echo $recruitdetails->FirstName." ".$recruitdetails->LastName; ?></td></tr>
                    <tr style="line-height:12px;"><td valign="top">Message</td><td>
                            <?php 
                            if($messages) { 
                                foreach($messages->result() as $u)
                                {
                            ?>
                                    
                                    <b><?php echo $u->FirstName." ".$u->LastName; ?>:</b>
                                    <p><?php echo $u->Notes; ?></p>
                                    <p><?php echo date("d-m-Y H:i:s", strtotime($u->NotesOn)); ?></p>
                                    <br />
                                    
                            <?php 
                                } 
                            }
                            ?>
                    </td></tr>
                </table> 
            </td>
        </tr>
        
      </table>
      
    </td>
    <td width="20">&nbsp;</td>
    <td width="5" style="font-size:1px;" valign="top" bgcolor="#f2f2f2"><img src="<?php echo base_url("images/email/header-06.gif"); ?>" width="5" height="156" style="display:block;" alt=" " /></td>
  </tr>
</table>          

<table width="598" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="5" style="font-size:1px;" valign="bottom" bgcolor="#f2f2f2"><img src="<?php echo base_url("images/email/footer-01.gif"); ?>" alt=" " /></td>
    <td>
    
    	<br />
      <table width="588" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="20">&nbsp;</td>
          <td style="border-bottom:1px solid #d9d9d9;">&nbsp;</td>
          <td width="20">&nbsp;</td>
        </tr>
        <tr>
          <td width="20">&nbsp;</td>
          <td  style="font-family: Verdana; font-size:10px; color:#555555; line-height:19px;" align="left">
          <br />
          <?php echo nl2br($signature); ?>
          </td>
          <td width="20">&nbsp;</td>
        </tr>
      </table>
      
                   
    </td>
    <td width="5" style="font-size:1px;" valign="bottom" bgcolor="#f2f2f2"><img src="<?php echo base_url("images/email/footer-02.gif"); ?>" alt=" " /></td>
  </tr>
</table>            
