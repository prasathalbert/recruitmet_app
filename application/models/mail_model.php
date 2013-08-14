<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Mail_model extends CI_Model {

    function sendMail($from_email, $from_name, $to,$subject,$content,$cc="",$bcc="", $attach=array())
    {
    	$CI = & get_instance();
    	$CI->load->library('email');
    	$CI->email->clear(true);
    	$CI->email->mailtype = "html";
    	$CI->email->from($from_email, $from_name);
    	$CI->email->to($to);
    	if($cc!="") $CI->email->cc($cc);
    	if($bcc!="") $CI->email->bcc($bcc);
    	
    	$CI->email->subject($subject);
    	$CI->email->message($content);
        
        if(count($attach)>0)
        {
            foreach($attach as $atdoc)
            {
                $this->email->attach($atdoc);    
            }
        }
            
    	/*echo $CI->email->print_debugger();exit;*/
    	if($CI->email->send())
    		return true;
    	else
    		return false;
    	
    }
    
    function clearMail()
    {
        $CI = & get_instance();
    	$CI->load->library('email');
    	$CI->email->clear(true);
    }
}
?>