<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	
	public $msg = array("validation_error"=>"");
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("users_model");
        $this->load->model("branches_model");
        $this->load->model("recruitment_model");
        $this->load->model("payment_model");
	}
	public function index()
	{
	   checkUserSession(true);
		$data = array(
						"view_file" => "home/home_content",
						"current_menu"=>"home",
						"title"=>"Home",
						"content_title"=>"Home",
						"content"=>"",
						"error"=>$this->msg
						);
        if(checkAccess($this->phpsession->get("ad_user_level"),1,false)){ 
            $data["branches"] = $this->branches_model->getBranches(array(),"5","0","UpdatedOn","desc");
            }
        if(checkAccess($this->phpsession->get("ad_user_level"),2,false)){ 
            $data["employees"] = $this->users_model->getUser(array("DesignationId"=>6),"5","0","CreatedOn","desc");
            }
        if(checkAccess($this->phpsession->get("ad_user_level"),3,false)){ 
            $data["branchadmin"] = $this->users_model->getUser(array("DesignationId"=>3),"5","0","CreatedOn","desc");
        }
        if(checkAccess($this->phpsession->get("ad_user_level"),4,false)){ 
            if ($this->phpsession->get("ad_user_level") == 3)
                $data["recruits"] = $this->recruitment_model->getRecruitDetails(array("u.UploadedBy" => $this->phpsession->get("ad_user_id")),"5","0","u.UploadedOn","desc");
            else
                $data["recruits"] = $this->recruitment_model->getRecruitDetails(array(),"5","0","u.UploadedOn","desc");
            
        }
        if(checkAccess($this->phpsession->get("ad_user_level"),7,false)){ 
            $data["recrecruits"] = $this->recruitment_model->getRecievedRecruitDetails($this->phpsession->get("ad_user_id"),"5","0","u.UploadedOn","desc");
        }
        
        if(checkAccess($this->phpsession->get("ad_user_level"),17,false)){ 
                    if ($this->phpsession->get("ad_user_level") == 3)
                        $paymentrequests = $this->payment_model->getPaymentRequestDetails(array("p.RequestedBy" => $this->phpsession->get("ad_user_id")),"5","0","p.RequestedOn","desc");
                    else if ($this->phpsession->get("ad_user_level") == 8)
                        $paymentrequests = $this->payment_model->getPaymentRequestDetails("p.Status in(2, 3, 4, 5, 6)","5","0","p.RequestedOn","desc");
                    else if ($this->phpsession->get("ad_user_level") == 5)
                        $paymentrequests = $this->payment_model->getPaymentRequestDetails("p.Status in(2, 3, 4, 5, 6)","5","0","p.RequestedOn","desc");
                    else
                        $paymentrequests = $this->payment_model->getPaymentRequestDetails(array(),"5","0","p.RequestedOn","desc");

            $data["payments"] = $paymentrequests;
        }
        
		$this->front_template->load_template($data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */