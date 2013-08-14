<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("employees/import");
?>
</div>
<div class="main_content">

<?php

    $frm = array("name"=>"employees-import-frm","id"=>"employees-import-frm","method"=>"post", "enctype"=>"multipart/form-data");
	echo form_open("employees/save_excel",$frm);  

?>


<div id="b_right" style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		<div id="i_title">
            <label for="employee_file">Seelct Excel File to import</label>
		<br /> 	<input type="file" name="employee_file" id="employee_file" />
        
        <br /><br />
        <a target="_blank" href="<?php echo base_url("misc/Employee_Detail_Format.xls"); ?>">Sample Excel</a>
		</div>
        
        <div id="i_publish">
			<ul>
				
				<li><input type="submit" class="sub_button" name="employee_add" value="Import" /></li>
                
                
			</ul>
		</div>
        
        
        
 </div>
 
<?php
   echo form_close();
   

?>
    
</div> 