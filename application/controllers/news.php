<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends CI_Controller {

	public $msg = array("validation_error"=>"");	
	
	public function __construct()
	{
		
		parent::__construct();
        checkUserSession(true,'',current_url());
        checkAccess($this->phpsession->get("ad_user_level"),26);
        $this->load->model("news_model");				
	}
	public function index()
	{
		
		$data = array(
						"view_file"=>"news/news_list",
						"title"=>"News List",
						"current_menu"=>"news",
                        "news" => $this->news_model->getNews(),
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function save()
	{
		if($this->input->post("news_add")!="")
		{            
			if($this->form_validation->run('news_add') == TRUE) 
			{
				$res = $this->news_model->getNews("NewsText = '". mysql_escape_string($this->input->post("newstext"))."'");
				if($res == false)
				{
					$ins_array = array(
									"NewsText"=>$this->input->post("newstext"),	
									"UpdatedBy"=>$this->phpsession->get("ad_user_id"),
									"UpdatedOn"=>date("Y-m-d H:i:s"),
                                    "IsActive"=>1												
								);
							
					$insrtid=$this->news_model->insert($ins_array);
                   
					$this->phpsession->flashsave("succ_msg","News added successfully");
					redirect("news");
				}
				else
				{
					$this->msg["validation_error"] = "News already exist.";
					$this->index();
				}
			}
			else
			{
				$this->msg["validation_error"] = validation_errors();
				$this->index();
			}
		}
		else
		{
				redirect("news");
		}
	}
    
    public function delete($newsid)
	{
		if($newsid=='')
			redirect("news");
		$res = $this->news_model->delete(array("NewsId"=>$newsid));
		if($this->db->affected_rows()>0)
			$this->phpsession->flashsave("succ_msg","Deleted successfully");
		else
			$this->phpsession->flashsave("error_msg","No changes made. Record not updated");
		redirect("news");
	}	
}
?>