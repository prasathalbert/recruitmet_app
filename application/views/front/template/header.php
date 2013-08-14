<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title><?php
echo "GKM";
if($title!="")
	 echo " - ".$title;
?></title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" media="all" style="text/css"/>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery_common.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-1.10.2.custom.min.js"></script>
    <link rel="stylesheet" href="<?php echo base_url();?>css/custom-theme/jquery-ui-1.10.2.custom.css" media="all" style="text/css"/>
    <script type="text/javascript" src="<?php echo base_url();?>js/validation.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/general.js"></script>
</head>
<body>
	
<?php
$menu_class['branch'] = "";
$menu_class['employee'] = "";
$menu_class['branchadmin'] = "";
$menu_class['recruitment'] = "";
$menu_class['recievedrecruitment'] = "";
$menu_class['gkmemployees'] = "";
$menu_class['payment'] = "";
$menu_class['news'] = "";

$menu_class[$current_menu]="select";
?>	
	<div id="main">
	
		<div id="left">
		
			<div id="logo">
			<a href="<?php echo site_url();?>" class="logo_text" title="GKM"> &nbsp; </a>
			</div>
			
			<div id="tools">
            <ul>
            <?php if(checkAccess($this->phpsession->get("ad_user_level"),1,false)){ ?>
					<li class="<?php echo $menu_class['branch']; ?>"><a id="medya" href="<?php echo site_url("branches"); ?>">Branch List</a></li>
            <?php } if(checkAccess($this->phpsession->get("ad_user_level"),2,false)){ ?>
					<li class="<?php echo $menu_class['employee']; ?>"><a id="kullanicilar" href="<?php echo site_url("employees"); ?>">Employee List</a></li>
            <?php } if(checkAccess($this->phpsession->get("ad_user_level"),3,false)){ ?>
                    <li class="<?php echo $menu_class['branchadmin']; ?>"><a id="sayfalar" href="<?php echo site_url("branchadmin"); ?>">Branch Admin List</a></li>
            <?php } if(checkAccess($this->phpsession->get("ad_user_level"),2,false)){ ?>                    
                    <li class="<?php echo $menu_class['gkmemployees']; ?>"><a id="gkmemp" href="<?php echo site_url("gkmemployees"); ?>">GKM Employees</a></li>
            <?php } if(checkAccess($this->phpsession->get("ad_user_level"),4,false)){ ?>                    
				    <li class="<?php echo $menu_class['recruitment']; ?>"><a  id="yazilar" href="<?php echo site_url("recruitment"); ?>">Authorization List</a></li>
            <?php } if(checkAccess($this->phpsession->get("ad_user_level"),7,false)){ ?>
                    <li class="<?php echo $menu_class['recievedrecruitment']; ?>"><a id="recievedr" href="<?php echo site_url("recruitment/recieved"); ?>">Recieved Authorization Forms</a></li>
            <?php }if(checkAccess($this->phpsession->get("ad_user_level"),17,false)){ ?>
                    <li class="<?php echo $menu_class['payment']; ?>"><a id="paymentr" href="<?php echo site_url("payment"); ?>">Payment Requests</a></li>
            <?php }if(checkAccess($this->phpsession->get("ad_user_level"),26,false)){ ?>
                    <li class="<?php echo $menu_class['news']; ?>"><a id="yorumlar" href="<?php echo site_url("news"); ?>">News List</a></li>
            <?php }?>	
                
            </ul>
			</div>
			
			<div id="keep">
			
			</div>
		
		</div>
		
		<div id="right">
		
			<div id="header">
					
				
					
				<div id="other">
						
					<!--<a href="#"><span id="bulut"></span></a>
					<a href="#"><span id="yorum"></span><div id="yorum_sayi">7</div></a>
					<a href="#"><span id="zil"></span></a>
					<a href="#"><span id="wordpress"></span></a>-->
						
				</div>
                
                <div id="plus">
					
					<div id="f_right">
                    <?php
                    $user_level = $this->phpsession->get("ad_user_level");
                    $user_class = "employee";
                    if($user_level == 1 || $user_level == 2)
                        $user_class = "administrator";
                    else if($user_level == 3)
                        $user_class = "branchadmin";
                    else if($user_level == 4)
                        $user_class = "hr";
                    else if($user_level == 5)
                        $user_class = "accountant";
                    else if($user_level == 7)
                        $user_class = "clinet_manager"; 
                    else if($user_level == 8)
                        $user_class = "ceo";   
                    ?>
					<ul>
						<li><span id="user" class="<?php echo $user_class; ?>"><font><?php echo $this->phpsession->get("ad_fullname"); ?></font></span></li>
						<li><a href="<?php echo site_url("myprofile"); ?>" title="My Profile"><span id="setting"></span></a></li>
						<li><a href="<?php echo site_url("login/logout"); ?>" title="Logout"><span id="exit"></span></a></li>
					</ul>
				</div>
						
				</div>
						
			</div>
            <?php $bodyclass = "martop50"; if($news!=false){ $bodyclass = "martop0";?>
            
			<div class="scroller"><marquee><ul>
            <?php 
            foreach($news->result() as $u)
    	    {  
            ?>
                <li><?php echo $u->NewsText; ?></li>
    		<?php 
            } 
            ?>
            </ul></marquee></div>
            <?php } ?>
            
            <div id="body" class="<?php echo $bodyclass; ?>">
			
				<h2 id="p_title" class="<?php echo $current_menu."_title"; ?>"><?php echo $title; ?></h2>
			     <div class="body_container">