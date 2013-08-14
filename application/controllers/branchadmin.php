<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branchadmin extends CI_Controller {

	public $msg = array("validation_error"=>"");	
	
	public function __construct()
	{
		
		parent::__construct();
        checkUserSession(true,'',current_url());
		checkAccess($this->phpsession->get("ad_user_level"),3);        
        $this->load->model("users_model");					
	}
	public function index()
	{
		$data = array(
						"view_file"=>"employees/employees_list",
						"title"=>"Branch Admin List",
						"current_menu"=>"branchadmin",
                        "employees" => $this->users_model->getUser(array("DesignationId"=>3)),
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function add()
	{
		
		$data = array(
						"view_file"=>"employees/employees_add",
						"title"=>"Add New Branch Admin",
						"current_menu"=>"branchadmin",
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function save()
	{
		if($this->input->post("employee_add")!="")
		{            
			if($this->form_validation->run('add_employee') == TRUE) 
			{
                if($this->input->post("bpassword")==$this->input->post("brepassword"))
                {
                    $res = $this->users_model->getUser("UserName = '". mysql_escape_string($this->input->post("bemail"))."' OR EmailId = '". mysql_escape_string($this->input->post("bemail"))."'");
    				if($res == false)
    				{
    				    $ins_array = array(
    									"UserName"=>$this->input->post("bemail"),												
    									"Password"=>sha1($this->input->post("bpassword")),
                                        "FirstName"=>$this->input->post("bfname"),
                                        "LastName"=>$this->input->post("blname"),
                                        "Gender"=>$this->input->post("bgender"),
    									"Address"=>$this->input->post("baddress"),
                                        "ContactNumber"=>$this->input->post("bcontact"),
                                        "EmailId"=>$this->input->post("bemail"),
                                        "DesignationId"=>'3',
                                        "CreatedBy"=>$this->phpsession->get("ad_user_id"),
    									"CreatedOn"=>date("Y-m-d H:i:s"),
                                        "UpdatedBy"=>$this->phpsession->get("ad_user_id"),
    									"UpdatedOn"=>date("Y-m-d H:i:s"),
                                        "IsActive"=>1												
    								);
    					$insrtid=$this->users_model->insert($ins_array);		
    					$this->phpsession->flashsave("succ_msg","<p>New Employee added successfully</p>");
    					redirect("branchadmin");
    				}
    				else
    				{
    					$this->msg["validation_error"] = "<p>EmailId already exist.</p>";
    					$this->add();
    				}   
                }
                else
                {
                    $this->msg["validation_error"] = "<p>Passwords should be mached</p>";
					$this->add();
                }
			}
			else
			{
				$this->msg["validation_error"] = validation_errors();
				$this->add();
			}
		}
		else
		{
				redirect("branchadmin/add");
		}
	}
    
	public function edit($employeeid)
	{
	   $empdataobj=null;
       $empdata=null;
		if($employeeid!="")
        {
            $empdataobj=$this->users_model->getUser(array("UserId"=>$employeeid));
            if($empdataobj)
            $empdata=$empdataobj->result();
            if(!$empdata)
            redirect("branchadmin");
        }
        else
        redirect("branchadmin");
        
		$data = array(
						"view_file"=>"employees/employees_edit",
						"title"=>"Edit BranchAdmin Details",
						"current_menu"=>"branchadmin",
                        "employeedata" => $empdata[0],
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function update()
    {
        if($this->input->post("employee_update")!="" and $this->input->post("employee_id")>0)
		{            
			if($this->form_validation->run('edit_employee') == TRUE) 
			{
                    $res = $this->users_model->getUser(("UserName = '". mysql_escape_string($this->input->post("bemail"))."' and UserId <> ".$this->input->post("employee_id")));
					if($res == false)
					{
					   $upd_array = array(
    									"FirstName"=>$this->input->post("bfname"),
                                        "LastName"=>$this->input->post("blname"),
                                        "Gender"=>$this->input->post("bgender"),
    									"Address"=>$this->input->post("baddress"),
                                        "ContactNumber"=>$this->input->post("bcontact"),
                                        "UpdatedBy"=>$this->phpsession->get("ad_user_id"),
    									"UpdatedOn"=>date("Y-m-d H:i:s"),
                                    );
							$this->phpsession->flashsave("succ_msg","<p>updated successfully</p>");
                            $this->users_model->update($upd_array,array("UserId"=>$this->input->post("employee_id")));
							redirect("branchadmin");
					}
					else
					{
						$this->msg["validation_error"] = "<p>BranchAdmin Email (UserName) already exist.</p>";
						$this->edit($this->input->post("employee_id"));
					}
				
			}
			else
			{
				$this->msg["validation_error"] = validation_errors();
				$this->edit($this->input->post("employee_id"));
			}
		}
		else
		{
				redirect("branchadmin");
		}
    }
    
    public function change($branch_id='',$to_status='')
	{
		if($branch_id=='' or $to_status=='')
			redirect("branchadmin");
		$res = $this->users_model->update(array("IsActive"=>$to_status,"UpdatedBy"=>$this->phpsession->get("ad_user_id"),"UpdatedOn"=>date("Y-m-d H:i:s")),array("UserId"=>$branch_id,"IsActive <> "=>$to_status));
		if($this->db->affected_rows()>0)
			$this->phpsession->flashsave("succ_msg","<p>Status updated successfully</p>");
		else
			$this->phpsession->flashsave("error_msg","<p>No changes made. Record not updated</p>");
		redirect("branchadmin");
	}
    
}
?>