<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller {
	
	public $msg = array("validation_error"=>"");
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("users_model");
        $this->load->model("mail_model");
	}
	
    public function newrecruit()
    {
        
        $to = "subbu@aes.in";
        $sub = "GKM - New Recruit Added";
        $from_email = "prasathlive@gmail.com";
        $from_name = "Prasath A.R";


        $signature = "Prasath A.R";
        $cc = "";
        $data["signature"] = $signature;
        $data["name"] = "";
        $data["title"] = $sub;
        $data["recruitname"] = "Test Recruit";
        $data["recruitemail"] = "testrecruit@asdsa.sd";
        $data["comments"] = "sads";
        $data["view_file"] = "newrecruit";

        $cont = $this->front_template->load_email_template($data);

        $attach_file = './' . PLACE_RECRUIT_DOC . '/auth_form.doc';
        $attachement = array("0" => $attach_file);

        $this->mail_model->sendMail($from_email, $from_name, $to, $sub, $cont, $cc, "", $attachement);

		//$cont = $this->load->view('email/newrecruit', $data);
        
		
    }
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */