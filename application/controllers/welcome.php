<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}
    
    public function testmail()
	{
	   //$this->output->enable_profiler(TRUE);
       
	   $to = "chitraexecutivedirector@yahoo.com";
       $cc = "badmumbai.gkm@gmail.com";
       $bcc = "";
       $from_email = "prasathlive@gmail.com";
       $from_name = "Prasth AR";
       $subject = "TEst";
       $content = "resr";
       $attach = array();
       
		$CI = & get_instance();
    	$CI->load->library('email');
    	$CI->email->clear();
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
                $CI->email->attach($atdoc);    
            }
        }
            
    	/*echo $CI->email->print_debugger();exit;*/
    	if($CI->email->send())
    		echo "send";
    	else
        {
            $CI->email->print_debugger();
            echo "Failed";
        }
    		
    	
        echo "<br />";
	}
    
    public function pkrmail()
    {
      $config = array();
        $config['useragent']           = "CodeIgniter";
        //$config['mailpath']            = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        //$config['protocol']            = "smtp";
        //$config['smtp_host']           = "localhost";
        //$config['smtp_port']           = "25";
        //$config['protocol'] = 'smtp';
        $config['mailtype'] = 'html';
        $config['charset']  = 'utf-8';
        $config['newline']  = "\r\n";
        $config['wordwrap'] = TRUE;
        


        $to = "chitraexecutivedirector@yahoo.com";
       $cc = "badmumbai.gkm@gmail.com";
       $bcc = "";
       $from_email = "prasathlive@gmail.com";
       $from_name = "Prasth AR";
       $subject = "TEst";
       $content = "resr";
        
        $this->load->library('email');

        $this->email->initialize($config);

        $this->email->from($from_email, $from_name);
        $this->email->to($to);

        $this->email->subject('???? Email');
        $this->email->message("test");

        $this->email->send();
        
            echo $this->email->print_debugger();
    }
    
     public function ttgmail()
	{
	   //$this->output->enable_profiler(TRUE);
       
	   $to = "chitraexecutivedirector@yahoo.com";
       $cc = "badmumbai.gkm@gmail.com";
       $bcc = "";
       $from_email = "prasathlive@gmail.com";
       $from_name = "Prasth AR";
       $subject = "TEst";
       $content = "resr";
       $attach = array();
       
    	/*echo $CI->email->print_debugger();exit;*/
    	if(sendMail($to,$subject,$content))
    		echo "send";
    	else
        {
            $CI->email->print_debugger();
            echo "Failed";
        }
    		
    	
        echo "<br />";
	}
    
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */