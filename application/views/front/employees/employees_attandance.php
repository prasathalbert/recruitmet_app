<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<style>
.ui-datepicker {
width: 50em; /*what ever width you want*/
height: 20em; /*what ever width you want*/
}
</style>
<script type="text/javascript">
// Maintain array of dates
<?php
$emp_absentdates = "";
$emp_absentdates_inp = "";
if($empattendance && count($empattendance)>0)
{
    $emp_absentdates = "'".implode("','",$empattendance)."'";
    $emp_absentdates_inp = implode(",",$empattendance);
}
?>
var dates = new Array(<?php echo $emp_absentdates; ?>);
function addDate(date) {if (jQuery.inArray(date, dates) < 0) dates.push(date);}
function removeDate(index) {dates.splice(index, 1);}

// Adds a date if we don't have it yet, else remove it
function addOrRemoveDate(date)
{
  var index = jQuery.inArray(date, dates); 
  if (index >= 0)
    removeDate(index);
  else 
    addDate(date);
  $("#date_calendar").val(dates);
}

// Takes a 1-digit number and inserts a zero before it
function padNumber(number)
{
  var ret = new String(number);
  if (ret.length == 1)
    ret = "0" + ret;
  return ret;
}

jQuery(function() {
jQuery("#att").datepicker({onSelect: function(dateText, inst) { addOrRemoveDate(dateText); },
                              beforeShowDay: function (date){
                                var year = date.getFullYear();
                                // months and days are inserted into the array in the form, e.g "01/01/2009", but here the format is "1/1/2009"
                                var month = padNumber(date.getMonth() + 1);
                                var day = padNumber(date.getDate());
                                // This depends on the datepicker's date format
                                var dateString = year + "/" + month + "/" + day;

                                var gotDate = jQuery.inArray(dateString, dates);
                                if (gotDate >= 0) {
                                  // Enable date so it can be deselected. Set style to be highlighted
                                  return [true,"ui-state-highlight"]; 
                                }
                                // Dates not in the array are left enabled, but with no extra style
                                return [true, ""];
                              },
                              dateFormat: "yy/mm/dd",
                              autoSize: true,
                              stepMonths: 0,
                              defaultDate: new Date(<?php echo $current_month; ?>)
                              
                         });
});

</script>
<div class="breadcump">
<?php
if($current_menu=="employee"){
    echo $this->breadcrumb->generateBreadcump("employees/edit");
    $typeUrl="employees";
    $typeText= "Employee";
}
elseif($current_menu=="branchadmin"){
    echo $this->breadcrumb->generateBreadcump("branchadmin/edit");
    $typeUrl="branchadmin";
    $typeText= "Branch Admin";
}
elseif($current_menu=="gkmemployees"){
    echo $this->breadcrumb->generateBreadcump("gkmemployees");
    $typeUrl="gkmemployees";
    $typeText= "GKM Employee";
}
?>
</div>
<div class="main_content">




<div id="b_right" style="float: none; margin: 0;">
		<div>
        
					<div id="bl_1">
					
						<div id="bll_1">
						
							<ul>
                                <li>Employee Name</li>
								<li>Gender</li>
                                <li>Email</li>
								<li>Contact</li>
								<li>Address</li>
							</ul>
						
						</div>
						
						<div id="bll_2">
						
							<ul>
                                <li><?php echo $employeedata->FirstName." ".$employeedata->LastName; ?></li>
								<li><?php if ($employeedata->Gender=="M") echo "Male"; elseif($employeedata->Gender=="F") echo "Female"; ?></li>
								<li><?php echo $employeedata->EmailId;?></li>
								<li><?php echo $employeedata->ContactNumber;?></li>
								<li><?php echo $employeedata->Address;?></li>
							</ul>	
						
						</div>
						
					
					</div>
        </div>
        
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
        
        
        <div id="i_title">
        <?php

            $frm = array("name"=>"employees-attendance-frm","id"=>"employees-attendance-frm","method"=>"post");
        	echo form_open("employees/attendance/".$employeedata->UserId,$frm);  
        
        ?>
        <label for="default_load"></label>
        
        <select name="default_load" onchange="this.form.submit();">
        
        <?php
        $selected_ym = $currentYear."_".$currentMonth;
        if($currentDate!="" && $startDate!="")
        {
            while ($currentDate >= $startDate) {
                $seleceted_dat = "";
                if($this->input->post("default_load")==date('Y_m',$currentDate) || $selected_ym == date('Y_m',$currentDate))
                    $seleceted_dat = "selected";
                echo "<option ".$seleceted_dat." value='".date('Y_m',$currentDate)."'>".date('Y - F',$currentDate)."</option>";
                $currentDate = strtotime( date('Y/m/01/',$currentDate).' -1 month');
            }
        }
        ?>
            
            </select>
        <?php echo form_close(); ?>    
        </div>
        
        
        <?php

            $frm = array("name"=>"employees-attendance-frm","id"=>"employees-attendance-frm","method"=>"post");
        	echo form_open("employees/saveattendance/",$frm);  
        
        ?>
        <div id="i_title">
            <label for="att">
            </label>
			<div id="att"></div>
            <input name="date_calendar" type="hidden" id="date_calendar" value="<?php echo $emp_absentdates_inp; ?>" />
		</div>
        <div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" id="employee_att_update" name="employee_att_update" value="Update" /></li>
                
                
			</ul>
		</div>
        <input type="hidden" name="current_month" value="<?php echo $currentMonth;?>" />
        <input type="hidden" name="current_year" value="<?php echo $currentYear;?>" />
        <input type="hidden" name="employee_id" value="<?php echo $employeedata->UserId;?>" />
        <?php echo form_close(); ?>
</div>
 

    
</div>   