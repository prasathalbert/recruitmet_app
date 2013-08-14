<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	
	public $msg = array("validation_error"=>"");
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("users_model");
        $this->load->model("mail_model");
	}
	public function index()
	{
	   if(checkUserSession())
			redirect(site_url("home"));             
		$data = array(
						"view_file" => "home/login_content",
						"current_menu"=>"login",
						"title"=>"Login",
						"content_title"=>"Login",
						"content"=>"",
						"error"=>$this->msg
						);
		$this->front_template->load_login_template($data);
	}
    
    public function do_login()
	{
		
		if($this->input->post("login_now")!="")
		{
			$auth = $this->users_model->getUser(array("UserName"=>$this->input->post("ad_uname"),"Password"=>sha1($this->input->post("ad_pwd"))));
			
			if($auth!=false)
			{
				$res = $auth->row();
				if($res->IsActive==0)
				{
					$this->phpsession->flashsave("error_msg","<p>Your account has been blocked. Please contact Administrator to unblock your account.</p>");
					redirect("login");
				}
				else
				{
					$this->phpsession->save(array(
												"ad_user_id"=>$res->UserId,
												"ad_fullname"=>$res->FirstName." ".$res->LastName,
												"ad_username"=>$res->UserName,
												"ad_user_level"=>$res->DesignationId,
                                                "ad_user_signature"=>$res->EmailSignature,
                                                "ad_user_email"=>$res->EmailId,
												"ad_sess_start_time"=>time(),
												"ad_sess_last_access"=>time()
										)
								);				
				 }
                 if($res->DesignationId==6)
                 redirect(site_url("myprofile"));
                 else
				redirect(site_url("home"));
				
			}
			$this->msg["validation_error"]="<p>Invalid login</p>";
			$this->index();
		}
		elseif($this->input->post("forgot_password")!="")
		{
			if($this->input->post("ad_uname")=="")
			{
				$this->phpsession->flashsave("error_msg","<p>Please enter Username</p>");
				redirect(site_url("login"));
			}
			$res = $this->users_model->getUser(array("UserName"=>$this->input->post("ad_uname")));
			if($res!=false)
			{
				$temp_pwd = rand(9999,99999);
				$update_data = $this->users_model->update(array("Password"=>sha1($temp_pwd)),array("UserName"=>$this->input->post("ad_uname")));
				
				$row = $res->result();
				$to = $row[0]->EmailId;
				$sub = "New Password for your GKM Account.";
                $from_email = "support@gkm.com"; 
                $from_name = "GKM";
                $data["title"] = $sub;
                
                $data["name"] = $row[0]->FirstName." ".$row[0]->LastName;
                $data["password"] = $temp_pwd;
                $data["signature"] = "GKM, <br /> Coimbatore";
                
                $data["view_file"] = "forgetpassword";
                
                $cont = $this->front_template->load_email_template($data);
                
				if($this->mail_model->sendMail($from_email, $from_name, $to,$sub,$cont))
					$this->phpsession->flashsave("succ_msg","New Password has beed mailed to your email id ".($row[0]->EmailId).".");
				else
					$this->phpsession->flashsave("error_msg","Mail sending failed.");
                    
				redirect(site_url("login"));				
				
				
			}
			else
			{
				$this->phpsession->flashsave("error_msg","<p>Invalid Username</p>");
				redirect(site_url("login"));	
			}
			
		}
		else
		{
			redirect(site_url("login"));
		}
	}
	
	public function logout()
	{
		userLogout();
		redirect("login");
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */