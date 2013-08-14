<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Employees extends CI_Controller {

	public $msg = array("validation_error"=>"");	
	
	public function __construct()
	{
		
		parent::__construct();
        checkUserSession(true,'',current_url());
		checkAccess($this->phpsession->get("ad_user_level"),2);        
        $this->load->model("users_model");					
	}
	public function index()
	{
		$data = array(
						"view_file"=>"employees/employees_list",
						"title"=>"Employees List",
						"current_menu"=>"employee",
                        "employees" => $this->users_model->getUser(array("DesignationId"=>6)),
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function add()
	{
		
		$data = array(
						"view_file"=>"employees/employees_add",
						"title"=>"Add New Employee",
						"current_menu"=>"employee",
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
                                        "DesignationId"=>'6',
                                        "CreatedBy"=>$this->phpsession->get("ad_user_id"),
    									"CreatedOn"=>date("Y-m-d H:i:s"),
                                        "UpdatedBy"=>$this->phpsession->get("ad_user_id"),
    									"UpdatedOn"=>date("Y-m-d H:i:s"),
                                        "IsActive"=>1												
    								);
    					$insrtid=$this->users_model->insert($ins_array);		
    					$this->phpsession->flashsave("succ_msg","<p>New Employee added successfully</p>");
    					redirect("employees");
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
				redirect("employees/add");
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
            redirect("employees");
        }
        else
        redirect("employees");
        
		$data = array(
						"view_file"=>"employees/employees_edit",
						"title"=>"Edit Employee Details",
						"current_menu"=>"employee",
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
							redirect("employees");
					}
					else
					{
						$this->msg["validation_error"] = "<p>Employee Email (UserName) already exist.</p>";
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
				redirect("employees");
		}
    }
    
    public function change($emp_id='',$to_status='')
	{
		if($emp_id=='' or $to_status=='')
			redirect("employees");
		$res = $this->users_model->update(array("IsActive"=>$to_status,"UpdatedBy"=>$this->phpsession->get("ad_user_id"),"UpdatedOn"=>date("Y-m-d H:i:s")),array("UserId"=>$emp_id,"IsActive <> "=>$to_status));
		if($this->db->affected_rows()>0)
			$this->phpsession->flashsave("succ_msg","<p>Status updated successfully</p>");
		else
			$this->phpsession->flashsave("error_msg","<p>No changes made. Record not updated</p>");
		redirect("employees");
	}
    
   	public function import_excel()
	{	
		$data = array(
						"view_file"=>"employees/import",
						"title"=>"Import Employees List From Excel",
						"current_menu"=>"employee",
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
	public function save_excel()
	{
		//checkAccess($this->phpsession->get("ad_user_level"),7);
		if($this->input->post("employee_add")!="")
		{
			$img_name="";
			$img_config['upload_path'] = './'.PLACE_EXCEL.'/';
			$img_config['allowed_types'] = 'xls|XLS';	
				
			$this->load->library('upload', $img_config);				
			if ( ! $this->upload->do_upload("employee_file"))
			{
				$this->msg["validation_error"]=$this->upload->display_errors();
				$this->import_excel();
				return;
			
			}
			else
			{
				$data =$this->upload->data();	
				$file_name =$data["raw_name"].$data["file_ext"];				
										
				$this->load->library("spreadsheet_excel_reader");
				$this->spreadsheet_excel_reader->read("./".PLACE_EXCEL."/".$file_name);
				$obj = $this->spreadsheet_excel_reader->sheets;
				unlink("./".PLACE_EXCEL."/".$file_name);
				$cols = $obj[0];
                
				$ins_arr = array();
                $cols["cells"][1][8]="Status";
                $cols["cells"][1][9]="Reason";
				for($r = 3;$r<=$obj[0]["numRows"];$r++)
				{
					if(isset($cols["cells"][$r]) && $cols["cells"][$r][1]!="")
					{
						$res = $this->users_model->getUser(("lcase(UserName) = '". mysql_escape_string(strtolower($cols["cells"][$r][3]))."' OR lcase(EmailId) = '".mysql_escape_string(strtolower($cols["cells"][$r][3]))."'"));
						if($res==false)
						{
                            $ins_arr[] = array(
    									"UserName"=>$cols["cells"][$r][3],												
    									"Password"=>sha1($cols["cells"][$r][4]),
                                        "FirstName"=>$cols["cells"][$r][1],
                                        "LastName"=>$cols["cells"][$r][2],
                                        "Gender"=>$cols["cells"][$r][7],
    									"Address"=>$cols["cells"][$r][6],
                                        "ContactNumber"=>$cols["cells"][$r][5],
                                        "EmailId"=>$cols["cells"][$r][3],
                                        "DesignationId"=>'6',
                                        "CreatedBy"=>$this->phpsession->get("ad_user_id"),
    									"CreatedOn"=>date("Y-m-d H:i:s"),
                                        "UpdatedBy"=>$this->phpsession->get("ad_user_id"),
    									"UpdatedOn"=>date("Y-m-d H:i:s"),
                                        "IsActive"=>1												
    								);																			
                            
                            $cols["cells"][$r][8]="success";
                            $cols["cells"][$r][9]="Successfully Imported";
						}
                        else
                        {
                            $cols["cells"][$r][8]="fail";
                            $cols["cells"][$r][9]="Email Already Exist";
                        }
					}
					
				}	
				if(count($ins_arr)>0)
				{
					$this->users_model->insertEmployeeBatch($ins_arr);
                    $this->phpsession->flashsave("succ_msg","Place excel imported successfully");					
				}					
				$this->import_result($cols["cells"]);
                return;	
			}				
		}
        else
        redirect("employees");
	}
    
    public function import_result($dataarray)
    {
        $data = array(
						"view_file"=>"employees/import_result",
						"title"=>"Import Employees List From Excel",
						"current_menu"=>"employee",
                        "import_result"=>$dataarray,
                        "error" => $this->msg
						);
                        
		$this->front_template->load_template($data);
    }
    
    public function attendance($employeeid, $default_load="")
    {
        checkAccess($this->phpsession->get("ad_user_level"),24);  
        
        $empdataobj=null;
        $empdata=null;
        
        if($this->input->post("default_load")!="")
        $default_load = $this->input->post("default_load");
        
		if($employeeid!="")
        {
            $empdataobj=$this->users_model->getUser(array("UserId"=>$employeeid, "DesignationId"=>6));
            if($empdataobj)
            $empdata=$empdataobj->result();
            if(!$empdata)
            redirect("employees");
        }
        else
        redirect("employees");
        
        $month = date("m")-1;
        $default_month = date("Y,".$month.",01");
        $current_month = date("m");
        $current_year = date("Y");
        if($default_load!="")
        {
            $def_array = explode("_",$default_load);
            $emp_absentdates = $this->users_model->getEmployeeAttendance($employeeid, $def_array[0], $def_array[1]);
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
            $emp_absentdates = $this->users_model->getEmployeeAttendance($employeeid);
        }
        
		$data = array(
						"view_file"=>"employees/employees_attandance",
						"title"=>"Edit Employee Attandance",
						"current_menu"=>"employee",
                        "current_month"=>$default_month,
                        "employeedata" => $empdata[0],
                        "empattendance" => $emp_absentdates,
                        "error" => $this->msg
						);
        
        
        $data["startDate"] = strtotime(EMP_ATTENDANCE_START);
        $data["endDate"] = strtotime(date("Y-m-d"));
        $data["currentDate"] = $data["endDate"];
        $data["currentMonth"] = $current_month;
        $data["currentYear"] = $current_year;
        
		$this->front_template->load_template($data);
    }
    
    public function saveattendance()
    {
        checkAccess($this->phpsession->get("ad_user_level"),24);
        
        if($this->input->post("employee_att_update")!="" && $this->input->post("employee_id")!=""
            && $this->input->post("current_month")!="" && $this->input->post("current_year")!="")
		{
            $this->users_model->clearEmployeeAttendance($this->input->post("employee_id"), $this->input->post("current_year"), $this->input->post("current_month"));
            $this->users_model->updateEmployeeAttendance($this->input->post("employee_id"), $this->input->post("current_year"), $this->input->post("current_month"), $this->input->post("date_calendar"));
            
            $selected_ym = $this->input->post("current_year")."_".$this->input->post("current_month");
            redirect("employees/attendance/".$this->input->post("employee_id")."/".$selected_ym);
        }
    }
		
}
?>