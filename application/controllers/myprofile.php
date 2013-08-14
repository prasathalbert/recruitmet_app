<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Myprofile extends CI_Controller {
	
	public $msg = array("validation_error"=>"");
	public function __construct()
	{	
		parent::__construct();
        $this->load->model("users_model");	
	}
	public function index($default_load="")
	{
	   checkUserSession(true);
       $empdataobj=null;
       $empdata=null;
       
       if($this->input->post("default_load")!="")
        $default_load = $this->input->post("default_load");
        
       $empdataobj=$this->users_model->getUser(array("UserId"=>$this->phpsession->get("ad_user_id")));
            if($empdataobj)
            $empdata=$empdataobj->result();
            if(!$empdata)
            redirect("home");
       
       
        $month = date("m")-1;
        $default_month = date("Y,".$month.",01");
        $current_month = date("m");
        $current_year = date("Y");
        if($default_load!="")
        {
            $def_array = explode("_",$default_load);
            $emp_absentdates = $this->users_model->getEmployeeAttendance($this->phpsession->get("ad_user_id"), $def_array[0], $def_array[1]);
            if($emp_absentdates && count($emp_absentdates)>0)
            {
                //$emp_default_date = "'".implode("','",$empattendance)."'";
                //$emp_absentdates_inp = implode(",",$empattendance);
                $def_data_array = explode("/",$emp_absentdates[0]);
                $default_month = $def_data_array[0].",".($def_data_array[1]-1).",".$def_data_array[2];
                //$default_month = $def_array[0].",".($def_array[1]-1).",01";
            }
            else
            {
                $default_month = $def_array[0].",".($def_array[1]-1).",01";
            }
            
            $current_month = $def_array[1];
            $current_year = $def_array[0];
        }
        else
        {
            $emp_absentdates = $this->users_model->getEmployeeAttendance($this->phpsession->get("ad_user_id"));
        }
        
		$data = array(
						"view_file" => "myprofile/myprofile_content",
						"current_menu"=>"myprofile",
						"title"=>"My Profile",
						"content_title"=>"My Profile",
                        "current_month"=>$default_month,
                        "empattendance" => $emp_absentdates,
						"content"=>"",
                        "mydetail"=>$empdata[0],
						"error"=>$this->msg
						);
        
        $data["startDate"] = strtotime(EMP_ATTENDANCE_START);
        $data["endDate"] = strtotime(date("Y-m-d"));
        $data["currentDate"] = $data["endDate"];
        $data["currentMonth"] = $current_month;
        $data["currentYear"] = $current_year;
        
		$this->front_template->load_template($data);
	}
    public function edit()
    {
        $employeeid=$this->phpsession->get("ad_user_id");
        $empdataobj=null;
       $empdata=null;
		if($employeeid!="")
        {
            $empdataobj=$this->users_model->getUser(array("UserId"=>$employeeid));
            if($empdataobj)
            $empdata=$empdataobj->result();
            if(!$empdata)
            redirect("home");
        }
        else
        redirect("home");
        
		$data = array(
						"view_file"=>"myprofile/myprofile_edit",
						"title"=>"Edit My Profile",
						"current_menu"=>"myprofile",
                        "employeedata" => $empdata[0],
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
    }
    
    public function update()
    {
        $employeeid=$this->phpsession->get("ad_user_id");
        if($this->input->post("employee_update")!="" and $employeeid>0)
		{            
			if($this->form_validation->run('edit_employee') == TRUE) 
			{
                    $res = $this->users_model->getUser(("UserName = '". mysql_escape_string($this->input->post("bemail"))."' and UserId <> ".$employeeid));
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
                            $this->users_model->update($upd_array,array("UserId"=>$employeeid));
							redirect("myprofile");
					}
					else
					{
						$this->msg["validation_error"] = "<p>Employee Email (UserName) already exist.</p>";
						$this->edit();
					}
				
			}
			else
			{
				$this->msg["validation_error"] = validation_errors();
				$this->edit();
			}
		}
		else
		{
				redirect("myprofile");
		}
    }
    
    public function change_password()
	{
		$data = array(
						"view_file"=>"myprofile/change_password",
						"title"=>"Change My Password",
						"current_menu"=>"myprofile",
                        "error" => $this->msg
						);
		
		if($this->input->post("change_pwd")!="")
		{
			
			if($this->form_validation->run("change_password") == TRUE) 
			{
                $query_fetch = $this->users_model->getUser(array("UserId"=>$this->phpsession->get("ad_user_id")));
				
				if($query_fetch!=false)
				{
					$resu = $query_fetch->result();			
					if(sha1($this->input->post('oldpass'))!=$resu[0]->Password)
					{
						$this->phpsession->flashsave("error_msg",'Please enter correct current password');
						
					}
					elseif(sha1($this->input->post('newpass'))!=sha1($this->input->post('renewpass')))
					{
						$this->phpsession->flashsave("error_msg","New password and Confirm Password doesn't match");
						
					}
					else
					{
						$this->users_model->update(array("Password"=>sha1($this->input->post('newpass'))),array("UserId"=>$this->phpsession->get("ad_user_id")));
						$this->phpsession->flashsave("succ_msg","Password updated successfully");
						redirect(site_url("myprofile"));
					}
					redirect(site_url("myprofile/change_password"));
				}
				else
				{
					userLogout();
					checkUserSession(true);
				}
            }
            else
            {
				$this->phpsession->flashsave("error_msg",validation_errors());
                redirect(site_url("myprofile/change_password"));
            }
        
		}
		$this->front_template->load_template($data);
		
	}
}

/* End of file myprofile.php */
/* Location: ./application/controllers/myprofile.php */