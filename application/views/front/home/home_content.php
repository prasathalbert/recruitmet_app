<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


?>
<div class="breadcump">

<?php

    echo $this->breadcrumb->generateBreadcump("Home");
   
?>

</div>
<div class="flashmsg">
<?php 
	echo getFlashMsg();
	echo getErrorMsg($error);
?>
</div>
<div class="main_content">

					<?php
                    if(checkAccess($this->phpsession->get("ad_user_level"),2,false))
                    { 
                        if($employees!=false)
                        {
                            
                        ?>
                        
                        <div id="b_left">
                        <h3 class="bl_title">New Employees</h3>
                        <div class="dots"></div>
    					<div id="bl_1">
    					
    						
    						
    						<div id="bll_2">
    						
    							<ul class="unorder_list">
                                <?php foreach($employees->result() as $u) { ?>
    								<li><?php echo $u->FirstName." ".$u->LastName;?></li>
    							<?php } ?>	
    							</ul>
    						
    						</div>
    						
    						<div id="bll_2">
    						
    							<ul>
                                <?php foreach($employees->result() as $u) { ?>
    								<li><a href="<?php echo site_url("employees/edit/".$u->UserId);?>" title="Edit"><img src="<?php echo base_url("images/icons/user_edit.png");?>" alt='Edit' /></a></li>
    							<?php } ?>		
    							</ul>	
    						
    						</div>
                            
    						
    					
    					</div>
                        <div id="theme_notice"><a href="<?php echo site_url("employees");?>">View All</a></div>
                        </div>
    					<?php } ?>
                    <?php 
                    
                    } 
                    if(checkAccess($this->phpsession->get("ad_user_level"),1,false))
                    { 
                        if($branches!=false)
                        {
                            
                        ?>
                         <div id="b_left">
                        <h3 class="bl_title">New Branches</h3>
                        <div class="dots"></div>
    					<div id="bl_1">
    					
    						
    						
    						<div id="bll_2">
    						
    							<ul class="unorder_list">
                                <?php foreach($branches->result() as $u) { ?>
    								<li><?php echo $u->BranchName;?></li>
    							<?php } ?>	
    							</ul>
    						
    						</div>
    						
    						<div id="bll_2">
    						
    							<ul>
                                <?php foreach($branches->result() as $u) { ?>
    								<li><a href="<?php echo site_url("branches/edit/".$u->BranchId);?>" title="Edit"><img src="<?php echo base_url("images/icons/user_edit.png");?>" alt='Edit' /></a></li>
    							<?php } ?>		
    							</ul>	
    						
    						</div>
                            
    						
    					
    					</div>
                        <div id="theme_notice" style="clear: both;"><a href="<?php echo site_url("branches");?>">View All</a></div>
                        </div>
    					<?php } ?>
                    <?php 
                    }
                    if(checkAccess($this->phpsession->get("ad_user_level"),3,false))
                    { 
                        if($branchadmin!=false)
                        {
                            
                        ?>
                        
                        <div id="b_left">
                        <h3 class="bl_title">New Branch Admin</h3>
                        <div class="dots"></div>
    					<div id="bl_1">
    					
    						
    						
    						<div id="bll_2">
    						
    							<ul class="unorder_list">
                                <?php foreach($branchadmin->result() as $u) { ?>
    								<li><?php echo $u->FirstName." ".$u->LastName;?></li>
    							<?php } ?>	
    							</ul>
    						
    						</div>
    						
    						<div id="bll_2">
    						
    							<ul>
                                <?php foreach($branchadmin->result() as $u) { ?>
    								<li><a href="<?php echo site_url("branchadmin/edit/".$u->UserId);?>" title="Edit"><img src="<?php echo base_url("images/icons/user_edit.png");?>" alt='Edit' /></a></li>
    							<?php } ?>		
    							</ul>	
    						
    						</div>
                            
    						
    					
    					</div>
                        <div id="theme_notice"><a href="<?php echo site_url("branchadmin");?>">View All</a></div>
                        </div>
    					<?php } ?>
                    <?php 
                    }
                    if(checkAccess($this->phpsession->get("ad_user_level"),4,false))
                    { 
                        if($recruits!=false)
                        {
                            
                        ?>
                        
                        <div id="b_left">
                        <h3 class="bl_title">New Authorization Forms</h3>
                        <div class="dots"></div>
    					<div id="bl_1">
    					
    						
    						
    						<div id="bll_2">
    						
    							<ul class="unorder_list">
                                <?php foreach($recruits->result() as $u) { ?>
    								<li><?php echo $u->RecruitName;?></li>
    							<?php } ?>	
    							</ul>
    						
    						</div>
    						
    						<div id="bll_2">
    						
    							<ul>
                                <?php foreach($recruits->result() as $u) { ?>
    								<li><a href="<?php echo site_url("recruitment/view/".$u->FormId);?>" title="View"><img src="<?php echo base_url("images/icons/magnifier.png");?>" alt='View' /></a></li>
    							<?php } ?>		
    							</ul>	
    						
    						</div>
                            
    						
    					
    					</div>
                        <div id="theme_notice"><a href="<?php echo site_url("recruitment");?>">View All</a></div>
                        </div>
    					
                    <?php 
                        }
                    }
                    if(checkAccess($this->phpsession->get("ad_user_level"),7,false))
                    { 
                        if($recrecruits!=false)
                        {
                            
                        ?>
                        
                        <div id="b_left">
                        <h3 class="bl_title">Recieved Authorization Forms</h3>
                        <div class="dots"></div>
    					<div id="bl_1">
    					
    						
    						
    						<div id="bll_2">
    						
    							<ul class="unorder_list">
                                <?php foreach($recrecruits->result() as $u) { ?>
    								<li><?php echo $u->RecruitName;?></li>
    							<?php } ?>	
    							</ul>
    						
    						</div>
    						
    						<div id="bll_2">
    						
    							<ul>
                                <?php foreach($recrecruits->result() as $u) { ?>
    								<li><a href="<?php echo site_url("recruitment/view/".$u->FormId);?>" title="View"><img src="<?php echo base_url("images/icons/magnifier.png");?>" alt='View' /></a></li>
    							<?php } ?>		
    							</ul>	
    						
    						</div>
                            
    						
    					
    					</div>
                        <div id="theme_notice"><a href="<?php echo site_url("recruitment/recieved");?>">View All</a></div>
                        </div>
    					
                    <?php 
                        }
                    }
                    if(checkAccess($this->phpsession->get("ad_user_level"),17,false))
                    { 
                        if($payments!=false)
                        {
                            
                        ?>
                        
                        <div id="b_left">
                        <h3 class="bl_title">Payment Requests</h3>
                        <div class="dots"></div>
    					<div id="bl_1">
    					
    						
    						
    						<div id="bll_2">
    						
    							<ul class="unorder_list">
                                <?php foreach($payments->result() as $u) { ?>
    								<li><?php echo $u->PaymentRequestFor;?></li>
    							<?php } ?>	
    							</ul>
    						
    						</div>
    						
    						<div id="bll_2">
    						
    							<ul>
                                <?php foreach($payments->result() as $u) { ?>
    								<li><a href="<?php echo site_url("payment/view/".$u->PaymentRequestId);?>" title="View"><img src="<?php echo base_url("images/icons/magnifier.png");?>" alt='View' /></a></li>
    							<?php } ?>		
    							</ul>	
    						
    						</div>
                            
    						
    					
    					</div>
                        <div id="theme_notice"><a href="<?php echo site_url("payment");?>">View All</a></div>
                        </div>
    					
                    <?php 
                        }
                    }  
                    ?>
					
			


</div>				