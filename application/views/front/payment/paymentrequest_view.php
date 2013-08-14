<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiFieldExtender-2.0.js"></script>

<script type="text/javascript">
 $(document).ready(function() {
  $(".file_multiple").EnableMultiField();
});

</script>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("payment/view");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"payment-save-frm","id"=>"payment-save-frm","method"=>"post", "enctype"=>"multipart/form-data");
	echo form_open("payment/addmessage",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
        <?php
        if($paymentdetails!=false)
        {
            $paymentstatus = $paymentdetails->Status;
        ?>
		<table class="detail_table">
				
                <tr>
						<th>Branch</th>
                        <td> <?php echo $paymentdetails->BranchName.", <br />".$paymentdetails->BranchLocation; ?></td>
                </tr>	
				<tr>
						<th>Request For</th>
                        <td> <?php echo $paymentdetails->PaymentRequestFor; ?></td>
                </tr>
                <tr>
                        <th>Bill Amount</th>
                        <td>Rs. <?php echo  number_format($paymentdetails->BillAmount, 2, '.', ','); ?> </td>
                </tr>
                <tr>
                        <th>Due Date</th>
                        <td><?php echo date("dS M, Y",strtotime($paymentdetails->DueDate)); ?> </td>
                </tr>
                <tr>
                        <th>Bill Amount After Due Date</th>
                        <td>Rs. <?php echo  number_format($paymentdetails->BillAmountAfterDueDate, 2, '.', ','); ?> </td>
                </tr>
                <tr>
                        <th>Payment Type</th>
                        <td><?php 
                        global $payment_type_list;	
                        echo $payment_type_list[$paymentdetails->PaymentTransferType]; ?> </td>
                </tr>
                
                <!--<tr>
                        <th>Authorization Form</th>
                        <td> <a href="<?php echo site_url("recruitment/download/form/".$recruit->FormId);?>" target="_blank" title="download"><img src="<?php echo base_url("images/icons/179-notepad.png");?>" alt="Download" /></a></td>
                </tr>-->
                <?php
                if($paymentdetails->PaymentTransferType=="C" || $paymentdetails->PaymentTransferType=="F")
                {
                ?>
                <tr>
                        <th>Payee Name</th>
                        <td><?php echo $paymentdetails->PayeeName; ?></td>
                </tr>
                <tr>
                        <th>Account Number</th>
                        <td><?php echo $paymentdetails->AccountNumber; ?></td>
                </tr>
                <tr>
                        <th>IFSC Code</th>
                        <td><?php echo $paymentdetails->IFSCCode; ?></td>
                </tr>
                <tr>
                        <th>Bank Details</th>
                        <td><?php echo $paymentdetails->BankBranchDetail; ?></td>
                </tr>
                <?php
                }
                else if($paymentdetails->PaymentTransferType=="O")
                {
                ?>
                <tr>
                        <th>Online Payment Details </th>
                        <td><?php echo $paymentdetails->BankBranchDetail; ?></td>
                </tr>
                <?php
                }
                
                ?>
                <tr>
                        <th>TDS Appicable</th>
                        <td> <?php 
                        global $tds_list;
                        echo $tds_list[$paymentdetails->TDSApplicable]; ?>
                        
                        </td>
                </tr>
                <tr>
                        <th>Service Taxt Included</th>
                        <td> <?php 
                        global $servtax_list;
                        echo $servtax_list[$paymentdetails->ServiceTaxIncluded]; ?>
                        
                        </td>
                </tr>
                <tr>
                        <th>Requested By</th>
                        <td> <?php echo $paymentdetails->FirstName." ".$paymentdetails->LastName; ?></td>
                </tr>
                <tr>
						<th>Support documents</th>
                        <td>
                            <?php 
                            
                            if($paymentdocs) { 
                                foreach($paymentdocs as $u)
                                {
                            ?>
                                    <div class="convers">
                                    <span><a target="_blank" href="<?php echo site_url("payment/download/".$paymentdetails->PaymentRequestId."/".$u->FileId); ?>"><?php echo $u->FileName; ?></a></span>
                                    <div class="small"><?php echo "Uploaded On : ".date("d-m-Y H:i:s", strtotime($u->UploadedOn)); ?></div>
                                    </div>
                            <?php 
                                } 
                            }
                            ?>
                        </td>
				</tr>
                <?php 
                if($paymentdetails->Status=="5" && $paymentdetails->PaymentFileLocation!="")
                {
                ?>
                <tr>
                        <th>Vouchers / Receipts</th>
                        <td>
                           
                            <div class="convers">
                            <span><a target="_blank" href="<?php echo site_url("payment/downloadreciept/".$paymentdetails->PaymentRequestId); ?>"><?php echo $paymentdetails->PaymentFileLocation; ?></a></span>
                            <div class="small"><?php echo "Payment On : ".date("d-m-Y H:i:s", strtotime($paymentdetails->PaymentFileUploadedOn)); ?></div>
                            </div>
                            
                        </td>
                </tr>
                <?php 
                }
                ?>
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
        <input type="hidden" name="papymentid" id="papymentid" value="<?php echo $paymentdetails->PaymentRequestId; ?>" />
        
        <div>
            <?php
                $submitbtn = "paymentmsg_add";
                
                if($paymentdetails->Status=="0")
                {
                    if(checkAccess($this->phpsession->get("ad_user_level"),18,false))
                    {
                        $supdoc_check_status[2] = "";
                        $supdoc_check_status[1] = "checked";
                        $supdoc_sec_disp = "none";
                        if($this->input->post("requeststatus")=="2")
                        {
                            $supdoc_check_status[2] = "checked";
                            $supdoc_check_status[1] = ""; 
                            $supdoc_sec_disp = "block";
                        }
                        ?>
                        <div id="i_title">
                        <label style="font-weight: bold; color: #1B6AAD;">Add Comments / Re-Request</label>
                            
                            <br />
                            
                            <input <?php echo $supdoc_check_status[1]; ?> class="radio" type="radio" name="requeststatus" value="1" id="requeststatus2" /> Add Comment 
                            <input <?php echo $supdoc_check_status[2]; ?>  class="radio" type="radio" name="requeststatus" value="2" id="requeststatus1" />  Re-Request
                            
                        </div>
                        <br />
                        <div id="i_title" class="supdocs_sec" style="display: <?php echo $supdoc_sec_disp; ?>;">
                            <label style="font-weight: bold; color: #1B6AAD;" for="supportdoc_file">Support Documents</label>
                			<div class="file_multiple"><input type="file" class="FileArea" name="supportdoc_file" id="supportdoc_file" /></div>
                		</div>
                        <?php
                        $submitbtn = "rerequest_status";
                    }
                }
                else if($paymentdetails->Status=="1")
                {
                    if(checkAccess($this->phpsession->get("ad_user_level"),20,false))
                    {
                        ?>
                        <div id="i_title">
                        <label style="font-weight: bold; color: #1B6AAD;">Approve / Reject</label>
                            
                            <br />
                            
                            <input checked="" class="radio" type="radio" name="paystatus" value="3" id="paystatus2" /> Approve 
                            <input class="radio" type="radio" name="paystatus" value="0" id="paystatus0" /> Reject
                            
                        </div>
                        <?php
                        $submitbtn = "changepayment_status";
                    }
                }
                else if($paymentdetails->Status=="2")
                {
                    if(checkAccess($this->phpsession->get("ad_user_level"),21,false))
                    {
                        ?>
                        <div id="i_title">
                        <label style="font-weight: bold; color: #1B6AAD;">Add Messages / Forward to Accounts</label>
                            
                            <br />
                            <input checked="" class="radio" type="radio" name="forwardstatus" value="1" id="paystatus2" /> Add Message
                            <input class="radio" type="radio" name="forwardstatus" value="2" id="paystatus0" /> Forward to Accounts
                            
                        </div>
                        <?php
                        $submitbtn = "forward_acc";
                    }
                }
                else if($paymentdetails->Status=="3" || $paymentdetails->Status=="4")
                {
                    if(checkAccess($this->phpsession->get("ad_user_level"),23,false))
                    {
                        ?>
                        <div id="i_title">
                        <label style="font-weight: bold; color: #1B6AAD;">Payment Status</label>
                            
                            <br />
                            <?php
                            $payment_check_status[1] = "checked";
                            $payment_check_status[2] = "";
                            $pay_sec_disp = "none";
                            if($this->input->post("paymade_status")=="2")
                            {
                                $payment_check_status[1] = "";
                                $payment_check_status[2] = "checked"; 
                                $pay_sec_disp = "block";
                            }
                            ?>
                            <input  class="radio" <?php echo $payment_check_status[1]; ?> type="radio" name="paymade_status" value="1" id="voucherstatus1" /> Add Message
                            <input class="radio" <?php echo $payment_check_status[2]; ?> type="radio" name="paymade_status" value="2" id="voucherstatus2" /> Payment made
                            
                        </div>
                        <br />
                        <div id="i_title" class="voucher_sec" style="display: <?php echo $pay_sec_disp; ?>;">
                            <label style="font-weight: bold; color: #1B6AAD;" for="payment_suc_file">Payment Docs</label>
                			<input type="file" class="FileArea" name="payment_suc_file" id="payment_suc_file" />
                		</div>
                        <?php
                        $submitbtn = "payment_status_change";
                    }
                }
            ?>
            <div id="i_title">
                <br />
                <input type="hidden" name="paymentstatus" id="paymentstatus" value="<?php echo $paymentstatus; ?>" />
    			<textarea name="recmessage" id="recmessage"><?php echo $this->input->post("recmessage");?></textarea>
    		</div>
                 
            <div id="i_publish">
    			<ul>
    				
    				<li><input type="submit" class="sub_button" name="<?php echo $submitbtn; ?>" value="Save" /></li>
                    
                    
    			</ul>
                
    		</div>
        </div>  
        
        <?php
        }
        ?>
</div>
 
<?php
   echo form_close();
   

?>
    
</div>
<script type="text/javascript">
$('#voucherstatus1').click(function() {
    $(".voucher_sec").css('display','none');
    $(".body_container").css('height','auto');
});
$('#voucherstatus2').click(function() {
    $(".voucher_sec").css('display','block');
    $(".body_container").css('height','auto');
});
$('#requeststatus2').click(function() {
    $(".supdocs_sec").css('display','none');
    $(".body_container").css('height','auto');
});
$('#requeststatus1').click(function() {
    $(".supdocs_sec").css('display','block');
    $(".body_container").css('height','auto');
});
</script>   