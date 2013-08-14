<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("employees/import_excel");
?>
</div>
<div class="main_content">


<div style="float: none; margin: 0;">
		
        <div class="flashmsg">
        <?php	
	
	echo getFlashMsg();
	echo getErrorMsg($error);
	
        ?>
        </div>
		
        
        <table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbl_home" id="datatable">
	<thead>
	<tr class="tbl_head">
		<?php
        echo "<th>Sl.No</th>";
        for($inc1=1;$inc1<=count($import_result[1]);$inc1++)
        {
            echo "<th>".$import_result[1][$inc1]."</th>";
        }
        ?>		
	</tr>
	</thead>
<?php
	$sno =1;
	$odd = "tbl_home_odd";
	$even = "tbl_home_even";

	for($inc=2;$inc<=count($import_result);$inc++)
	{
	   $inc2=$inc+1;
		$cls=$odd;
		if($sno%2 === 0) 
			$cls = $even;
            
            if(isset($import_result[$inc2]))
            {
?>
		<tr class="<?php echo $cls;?>">
			<td><?php echo $sno++;?></td>
			<?php
                for($inc1=1;$inc1<=count($import_result[$inc2]);$inc1++)
                {
                    if($inc1==8)
                    {
                        if($import_result[$inc2][$inc1]=="fail")
                            echo "<td><img src='".base_url("images/icons/error_msg.png")."' title='Failed To Insert' /></td>";
                        else
                            echo "<td><img src='".base_url("images/icons/success_msg.png")."' title='Successfully Imported' /></td>";
                    }
                    else
                    echo "<td>".$import_result[$inc2][$inc1]."</td>";
                }
            ?>
		</tr>
<?php
            }
	}
	
?>
	</table>
        
 </div>

</div> 