<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<div class="breadcump">
<?php
echo $this->breadcrumb->generateBreadcump("news");
?>
</div>
<div class="main_content">

<div class="flashmsg">
<?php 
	echo getFlashMsg();
?>
</div>
<?php
if($news!=false)
{
?>

<link href="<?php echo base_url()?>js/datatables/demo_table_jui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/complete.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/datatables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/admin.js"></script>

<?php
	
?>
	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tbl_home" id="datatable">
	<thead>
	<tr class="tbl_head">
		<th width="10%">S.No</th>
		<th width="75%">News Text</th>
		
		
		<th width="15%">Actions</th>		
	</tr>
	</thead>
<?php
	$sno =1;
	$odd = "tbl_home_odd";
	$even = "tbl_home_even";
	
	foreach($news->result() as $u)
	{
		$cls=$odd;
		if($sno%2 === 0) 
			$cls = $even;
?>
		<tr class="<?php echo $cls;?>">
			<td><?php echo $sno++;?></td>
			<td><?php echo $u->NewsText;?></td>
			
			<td>
			<?php
			if($u->IsActive==0)
			{
				$title="Un-Block this News";
				$img = "deactivate.png";
				$to = 1;
			}
			else
			{
				$title="Delete this News";
				$img = "deactivate.png";
				$to=0;
			}
				
			?>
			
			<a href="javascript:isChange('<?php echo $u->NewsId;?>','<?php echo $title;?>',<?php echo $to;?>);" title="<?php echo $title;?>"><img src="<?php echo base_url("images/icons/".$img);?>" alt='<?php echo $title;?>' /></a>
			
			</td>
		</tr>
<?php

	}
	
?>
	</table>
    
    <br />
<?php
}
else
	echo "<p>No News Found</p>";
?>
<br />
    <div>
    
    <?php
    
        $frm = array("name"=>"news-save-frm","id"=>"news-save-frm","method"=>"post");
    	echo form_open("news/save",$frm);  
    
    ?>
        <div id="b_right" style="float: none; margin: 0;">
        <div class="flashmsg">
        <?php 
	echo getErrorMsg($error);
     ?>
        </div>
            <div id="i_title">
                <label for="newstext">News</label>
    			<textarea name="newstext" id="newstext"><?php echo trim($this->input->post("newstext"));?></textarea>
    		</div>
        
        
            <div id="i_publish">
    			<ul>
    				
    				<li><input type="submit" class="sub_button" name="news_add" value="Save" /></li>
                    
                    
    			</ul>
    		</div>
        </div>
        
        
         
    <?php
       echo form_close();
       
    
    ?>
    </div>
    
	<script type="text/javascript">
	function isChange(id,msg,to_status)
	{
		if(confirm("Are you sure you want to "+msg+"?"))
		{
			window.location.href="<?php echo site_url("news/delete");?>/"+id;
		}		
	}
	</script>

</div>