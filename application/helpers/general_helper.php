<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ToDBDate($d)
{
	//$d should be in dd/mm/yyyy format
	$d_arr = explode("/",$d);
	if(count($d_arr)==3)
	{
		return $d_arr[2]."-".$d_arr[1]."-".$d_arr[0];
	}
	else
		return $d;
}
function siteDateFormat($d)
{
	return date("d-M-Y",strtotime($d));
}

function siteDateTimeFormat($d)
{
	return date("d-M-Y h:i A",strtotime($d));
}

function checkUserSession($redt=false,$url_after_login="")
{
	$red_url = "";
	if($redt)
		$red_url="login";		
	return checkForSession("ad",USER_SESSION_TIMEOUT,$red_url,$url_after_login);	
}
function userLogout()
{
		$CI = & get_instance();		
		$CI->phpsession->flashsave("succ_msg","<p>Logout successfully</p>");
		$CI->phpsession->clear();
}

function getSEOURL($baseurl,$pid,$page_title)
{
	$page = preg_replace("![^a-z0-9]+!i", "-", $page_title);
	return $baseurl."/".$pid."/".$page;
}

function getSlugFromName($name){
    return strtolower(preg_replace("![^a-z0-9]+!i", "-", $name));
}

function sub_array_search($array, $key, $value)
{
    $results = array();
	if (is_array($array))
    {
        if (isset($array[$key]) && $array[$key] == $value)
            $results = $array;
		$keys = array_keys($array);
		$i=0;
        foreach ($array as $subarray)
		{
         	$res = sub_array_search($subarray, $key, $value);
			if(is_array($res) and count($res)>0)
			    $results[$keys[$i]] = $res;
			$i++;	
		}
    }

    return $results;
}

function checkForSession($prefix,$time_out,$red_url="",$url_after_login="")
{
	$CI = & get_instance();
	
	
	if($CI->phpsession->get($prefix."_user_id")!="" and (time()-($CI->phpsession->get($prefix."_sess_last_access"))) < $time_out)
	{
		
		$CI->phpsession->save(array($prefix."_sess_last_access"=>time()));
		/*if($red_url!="")
			redirect($red_url);*/
		return true;
			
		
	}
	else
	{
		
		if($red_url!="")
		{			
			//$CI->phpsession->clear();
			$CI->phpsession->flashsave("error_msg","Login Required");
			$CI->phpsession->save(array("redirect_url"=>$url_after_login));	
			redirect(site_url($red_url));
		}
		else
		{			
			return false;
		}
	}
}

function sendMail($to,$sub,$cont,$cc="",$bcc="")
{
	$CI = & get_instance();
	$CI->load->library('email');
	$CI->email->clear();
	$CI->email->mailtype = "html";
	$CI->email->from(FROM_EMAIL, FROM_NAME);
	$CI->email->to($to);
	if($cc!="") $CI->email->cc($cc);
	if($bcc!="") $CI->email->bcc($bcc);
	
	$CI->email->subject($sub);
	$CI->email->message($cont);
	
	/*$CI->email->send();
	echo $CI->email->print_debugger();exit;*/
	if($CI->email->send())
		return true;
	else
		return false;
	

	
}


function checkCustomerSession($isRedirect=false,$url_after_login="")
{
	
	$CI = & get_instance();
	$red_url_if_not_lgged = "";
	if($isRedirect)
		$red_url_if_not_lgged="login";	
	
	
	return checkForSession("ttg",USER_SESSION_TIMEOUT,$red_url_if_not_lgged,$url_after_login);	

}
function year_array($from='1970', $to='')
{
		if($to=='')
			$to = (date('Y')+5);
        $year = array();
        $year[''] = 'Select Year';    
        for($i=$from;$i<=$to;$i++)
        {
          $year[$i]=$i;  
        }
        return $year;
}

function getFlashMsg()
{
	$CI = & get_instance();
	$ret = "";
	if($CI->phpsession->flashget("succ_msg")!="")
		$ret .= "<div class='succ_msg clear'>".$CI->phpsession->flashget("succ_msg")."</div>";	
	if($CI->phpsession->flashget("error_msg")!="")
		$ret .= "<div class='error_msg clear'>".$CI->phpsession->flashget("error_msg")."</div>";	
	return $ret;
}

function getErrorMsg($error)
{
	
	if($error["validation_error"]!="")
		return "<div class='error_msg clear'>".$error["validation_error"]."</div>";
	return;
}

function clearURL($str){
    return strtolower(preg_replace("![^a-z0-9]+!i", "", $str));
}

function sendSMS($mobile_no,$content_to_send)
{
	return true;
}


function image_resize($file_data,$w,$h,$wm=false)
	{
		$CI = & get_instance();
		
		$config['image_library'] = 'gd2';
		$config['source_image']	= $file_data["full_path"];
		$config['create_thumb'] = false;
		$config['maintain_ratio'] = false;
		$config['width']	 = $w;
		$config['height']	= $h;
		
		if($wm!=false)
		{
			//water marking
			$config['wm_text'] = WATER_MARTK_TEXT;
			$config['wm_type'] = 'text';
			$config['wm_font_path'] = './system/fonts/texb.ttf';
			$config['wm_font_size'] = '12';
			$config['wm_font_color'] = 'F9BC01';
			$config['wm_hor_alignment'] = "center";
			$config['wm_vrt_alignment'] = "bottom";
		/*	$config['wm_hor_offset'] = '100';
			$config['wm_vrt_offset'] = '100';	*/
			$config['wm_opacity'] = '80';
			$config['padding']='25';
		}		
		$CI->load->library('image_lib', $config);	
		$CI->image_lib->initialize($config); 	
		$CI->image_lib->resize();
		if($wm)
			$CI->image_lib->watermark();
		unset($config);
		$CI->image_lib->clear();
	}

function getTimeToDisplay($m,$display_zero = false)
{
	$m = floor($m/60);
	$ret = "";
	$h = floor($m/60);
	$min = $m%60;
	$day = floor($h/24);
	$h = $h%24;	
	$day_text = "Day";
	if($day>1) $day_text = "Days";
	$h_text = "Hours";
	if($h>1)	$h_text = "Hours";
	$m_text = "Min";
	if($min>1)	$m_text = "Mins";
	if($display_zero)
	{
		return $day." ".$day_text." ".$h." ".$h_text." ". $min. " ".$m_text;
	}
	else
	{
		if($day>0)
			$ret .= $day." ".$day_text;
		if($h>0)
		  $ret .= " ".$h." ".$h_text;
		 if($min>0)	
			$ret .= " ".$min." ".$m_text;
		//echo $ret;exit;
		return $ret;
	}
}

function getTimeDropDown($start,$end,$interval='300')
{
	$ret_array = array(""=>"---Select---");
	$stime = strtotime($start);
	$endtime = strtotime($end)-$interval;
	for(;$stime<=$endtime;$stime+=$interval)
		$ret_array[$stime] = date("h:i A",$stime)." - ".date("h:i A",($stime+$interval));
	return $ret_array;
}

function checkAccess($usertypeid, $actionid,$ret=true,$ret_url="")
{
	global $config_user_rights;
	$combination = "(".$usertypeid.",".$actionid.")";
	if(array_search($combination,$config_user_rights)===false)// Denied
	{
		if($ret)
		{
			$CI = & get_instance();
			$CI->phpsession->flashsave("error_msg","<p>Access Denied</p>");
			redirect($ret_url);
		}
		else
		{
			return false;
		}
	}
	else
		return true;
}


function MY_encode($value){ 

	if(!$value)
		return false;
	$text = $value;
	$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
	$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, ENC_KEY, $text, MCRYPT_MODE_ECB, $iv);
	return trim(safe_b64encode($crypttext)); 
}
 
function MY_decode($value){
 
        if(!$value){return false;}
        $crypttext = safe_b64decode($value); 
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, ENC_KEY, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }
function safe_b64encode($string) {
 
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
 
function safe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }

function isValidEmail($email){
    return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $email);
}

?>