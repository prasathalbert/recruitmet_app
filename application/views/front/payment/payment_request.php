<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiFieldExtender-2.0.js"></script>

<script type="text/javascript">
 $(document).ready(function() {
  $(".file_multiple").EnableMultiField();
});

$(function() {
    $("#duedate").datepicker({
showOn: "both",
buttonImage: "<?php echo base_url();?>/images/icons/83-calendar.png",
buttonImageOnly: true,
dateFormat: "yy/mm/dd"
});
    });
</script>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("payment/request");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"payment-save-frm","id"=>"payment-save-frm","method"=>"post", "enctype"=>"multipart/form-data");
	echo form_open("payment/save",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
        	echo getFlashMsg();
        	echo getErrorMsg($error);
	
        ?>
        </div>
        <div id="i_title">
            
        <?php
            if(count($branchlist)==1)
            {
                ?>
                <label for="reqfor">Branch </label>
                <?php echo $branchlist[0]["BranchName"]; ?>, 
                <?php echo $branchlist[0]["BranchLocation"]; ?><br />
                <br />
                <input type="hidden" name="branchid" id="branchid" value="<?php echo $branchlist[0]["BranchId"]; ?>" />
                <?php
            }
            else
            {
                ?>
                <label for="reqfor">Branch</label>
                <select name="branchid" id="branchid">
                <option value="">Select Branch</option>
                <?php
                    for($incs=0;$incs<count($branchlist);$incs++)
                    {
                        if($this->input->post("branchid")==$branchlist[$incs]["BranchId"])
                            echo "<option selected value='".$branchlist[$incs]["BranchId"]."'>".$branchlist[$incs]["BranchName"]."</option>";
                        else
                            echo "<option value='".$branchlist[$incs]["BranchId"]."'>".$branchlist[$incs]["BranchName"]."</option>";                        
                    }
                
                ?>
                
            </select>
                <?php
            }
        ?>
        </div>
		<div id="i_title">
            <label for="reqfor">Request For</label>
			<input name="reqfor" maxlength="220" type="text" id="reqfor" value="<?php echo $this->input->post("reqfor");?>"/>
		</div>
        <div id="i_title">
            <label for="billamount">Bill Amount</label>
			<input name="billamount" maxlength="7" type="text" id="billamount" value="<?php echo $this->input->post("billamount");?>"/>
		</div>
        <div id="i_title">
            <label for="duedate">Due Date</label>
			<input readonly="true" maxlength="10" name="duedate" type="text" id="duedate" value="<?php echo $this->input->post("duedate");?>"/>
		</div>
        <div id="i_title">
            <label for="billamount_duedate">Bill Amount After Due Date</label>
			<input name="billamount_duedate" maxlength="7" type="text" id="billamount_duedate" value="<?php echo $this->input->post("billamount_duedate");?>"/>
		</div>
        <div id="i_title">
            <label for="paymenttransfer">Payment Type</label>
			<select name="paymenttransfer" id="paymenttransfer">
                <option value="">Select Type</option>
                <?php
                global $payment_type_list;		
                $pt = $payment_type_list;
                $tnc=0;
                foreach($pt as $adlid=>$adname)
                {
                    $seleceted ="";
                    if($this->input->post("paymenttransfer") == $adlid)
                        $seleceted ="selected";
                    ?>
                    <option value="<?php echo $adlid; ?>" <?php echo $seleceted; ?> ><?php echo $adname; ?></option>
                    <?php
                }
                    
                ?>
                
            </select>
		</div>
        <?php
            $bank_detail_display = "none";
            $online_detail_display = "none";
            
            if($this->input->post("paymenttransfer")=="C" || $this->input->post("paymenttransfer")=="F")
            {
               $bank_detail_display = "block";
               $online_detail_display = "none"; 
            }
            else if($this->input->post("paymenttransfer")=="O")
            {
               $bank_detail_display = "none";
               $online_detail_display = "block"; 
            }    
        ?>
        <div id="bank_detail" style="display: <?php echo $bank_detail_display;?>;">
            <div id="i_title">
                <label for="payeename">Payee Name</label>
    			<input name="payeename" maxlength="200" type="text" id="payeename" value="<?php echo $this->input->post("payeename");?>"/>
    		</div>
            <div id="i_title">
                <label for="accountnumber">Account Number</label>
    			<input name="accountnumber" maxlength="20" type="text" id="accountnumber" value="<?php echo $this->input->post("accountnumber");?>"/>
    		</div>
            <div id="i_title">
                <label for="ifsccode">IFSC Code</label>
    			<input name="ifsccode" maxlength="10" type="text" id="ifsccode" value="<?php echo $this->input->post("ifsccode");?>"/>
    		</div>
            <div id="i_title">
                <label for="bankdetails">Bank Details</label>
    			<textarea name="bankdetails" id="bankdetails"><?php echo $this->input->post("bankdetails");?></textarea>
    		</div>
        </div>
        <div id="online_detail" style="display: <?php echo $online_detail_display;?>;">
            <div id="i_title">
                <label for="online_payment_details">Online Payment Details</label>
    			<textarea name="online_payment_details" id="online_payment_details"><?php echo $this->input->post("online_payment_details");?></textarea>
    		</div>
        </div>
        <div id="i_title">
            <label>TDS Appicable</label><br />
			
            
			
            <?php 
            global $tds_list;		
		      $gl = $tds_list;
              $tnc=0;
            foreach($gl as $adlid=>$adname)
            {
                
                if($this->input->post("tdsdetail")==$adlid || ($this->input->post("tdsdetail")=="" && $tnc==0))
                {
                    ?>
                    <input class="radio" type="radio" checked="true" name="tdsdetail" id="tdsdetail" value="<?php echo $adlid;?>" /> <?php echo $adname;?>
                    
                    <?php
                }
                else
                {
                    ?>
                    <input class="radio" type="radio" name="tdsdetail" id="tdsdetail" value="<?php echo $adlid;?>" /> <?php echo $adname;?>
                    <?php
                }
                $tnc++;
            } 
                
            ?>
             
                    
		</div>
        <br />
        <div id="i_title">
            <label>Service Taxt Included</label><br />
			
            
			
            <?php 
            global $servtax_list;		
		      $gls = $servtax_list;
              $tncs=0;
            foreach($gls as $adlids=>$adnames)
            {
                
                if($this->input->post("servtax")==$adlids || ($this->input->post("servtax")=="" && $tncs==0))
                {
                    ?>
                    <input class="radio" type="radio" checked="true" name="servtax" id="servtax" value="<?php echo $adlids;?>" /> <?php echo $adnames;?>
                    
                    <?php
                }
                else
                {
                    ?>
                    <input class="radio" type="radio" name="servtax" id="servtax" value="<?php echo $adlids;?>" /> <?php echo $adnames;?>
                    <?php
                }
                $tncs++;
            } 
                
            ?>
             
                    
        </div>
        <br />
        <div id="i_title">
            <label for="payment_notes">Notes</label>
        	<textarea name="payment_notes" id="payment_notes"><?php echo $this->input->post("payment_notes");?></textarea>
        </div>
        <div id="i_title">
            <label for="supportdoc_file">Supporting Documents</label>
			<div class="file_multiple"><input type="file" class="FileArea" name="supportdoc_file" id="supportdoc_file" /></div>
		</div>
        <div id="i_publish">
        	<ul>
        		
        		<li><input type="submit" class="sub_button" name="payment_request" value="Send" /></li>
                
                
        	</ul>
        </div>
        
</div>
 
<?php
   echo form_close();
   

?>
    
</div>   
<script type="text/javascript">
$('#paymenttransfer').change(function() {
    if(this.value=="C" || this.value=="F")
    {
        $('#online_detail').css("display","none");
        $('#bank_detail').css("display","block");
    }
    else if(this.value=="O")
    {
        $('#online_detail').css("display","block");
        $('#bank_detail').css("display","none");
    }
    else
    {
        $('#bank_detail').css("display","none");
        $('#online_detail').css("display","none");
    }
    $(".body_container").css('height','auto');
});

</script>