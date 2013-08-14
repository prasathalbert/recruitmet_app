<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Branches extends CI_Controller {

	public $msg = array("validation_error"=>"");	
	
	public function __construct()
	{
		
		parent::__construct();
        checkUserSession(true,'',current_url());
        checkAccess($this->phpsession->get("ad_user_level"),1);
        $this->load->model("branches_model");
        $this->load->model("users_model");					
	}
	public function index()
	{
		
		$data = array(
						"view_file"=>"branches/branch_list",
						"title"=>"Branch List",
						"current_menu"=>"branch",
                        "branches" => $this->branches_model->getBranches(),
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function add()
	{
		
		$data = array(
						"view_file"=>"branches/branches_add",
						"title"=>"Add New Branch",
						"current_menu"=>"branch",
                        "admin_list"=>$this->users_model->getUserArray(array("IsActive"=>1,"DesignationId"=>"3")),
                        "employee_list"=>$this->users_model->getUserArray(array("IsActive"=>1,"DesignationId"=>"6")),
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function save()
	{
		if($this->input->post("branch_add")!="")
		{            
			if($this->form_validation->run('edit_branch') == TRUE) 
			{
				$res = $this->branches_model->getBranches("BranchName = '". mysql_escape_string($this->input->post("bname"))."'");
				if($res == false)
				{
					$ins_array = array(
									"BranchName"=>$this->input->post("bname"),												
									"BranchLocation"=>$this->input->post("blocation"),
									"BranchAddress"=>$this->input->post("baddress"),
									"UpdatedBy"=>$this->phpsession->get("ad_user_id"),
									"UpdatedOn"=>date("Y-m-d H:i:s"),
                                    "IsActive"=>1												
								);
							
					$insrtid=$this->branches_model->insert($ins_array);
                    if($insrtid)
                    {
                        $this->branches_model->addEmployee($insrtid,$this->input->post("badmin"));
                        $emparray=$this->input->post("bemployees");
                        foreach($emparray as $empid)
                            $this->branches_model->addEmployee($insrtid,$empid);   
                    }
					$this->phpsession->flashsave("succ_msg","New Branch added successfully");
					redirect("branches");
				}
				else
				{
					$this->msg["validation_error"] = "BranchName already exist.";
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
				redirect("branches/add");
		}
	}
    
	public function edit($branchid)
	{
	   $branchdataobj=null;
       $branchdata=null;
		if($branchid!="")
        {
            $branchdataobj=$this->branches_model->getBranches(array("BranchId"=>$branchid));
            if($branchdataobj)
            $branchdata=$branchdataobj->result();
            if(!$branchdata)
            redirect("branches");
        }
        else
        redirect("branches");
        
		$data = array(
						"view_file"=>"branches/branches_edit",
						"title"=>"Edit Branch",
						"current_menu"=>"branch",
                        "branch_admin"=>$this->branches_model->getBranchadmin($branchid),
                        "existingemployees"=>$this->branches_model->getBranchemployees($branchid),
                        "admin_list"=>$this->users_model->getUserArray(array("IsActive"=>1,"DesignationId"=>"3")),
                        "employee_list"=>$this->users_model->getUserArray(array("IsActive"=>1,"DesignationId"=>"6")),
                        "branchdata" => $branchdata[0],
                        "error" => $this->msg
						);
		$this->front_template->load_template($data);
	}
    
    public function update()
    {
        if($this->input->post("branch_update")!="" and $this->input->post("branch_id")>0)
		{            
			if($this->form_validation->run('edit_branch') == TRUE) 
			{
					$res = $this->branches_model->getBranches(("BranchName = '". mysql_escape_string($this->input->post("bname"))."' and BranchId <> ".$this->input->post("branch_id")));
					if($res == false)
					{
					   //BranchId, BranchName, BranchLocation, BranchAddress, UpdatedBy, UpdatedOn, IsActive
						$upd_array = array(
												"BranchName"=>$this->input->post("bname"),												
												"BranchLocation"=>$this->input->post("blocation"),
												"BranchAddress"=>$this->input->post("baddress"),
												"UpdatedBy"=>$this->phpsession->get("ad_user_id"),
												"UpdatedOn"=>date("Y-m-d H:i:s")												
											);
							$this->phpsession->flashsave("succ_msg","updated successfully");
                            $this->branches_model->update($upd_array,array("BranchId"=>$this->input->post("branch_id")));
                            $oldbranchadmin=$this->branches_model->getBranchadmin($this->input->post("branch_id"));
                            if($oldbranchadmin)
                            {
                                if($oldbranchadmin!=$this->input->post("badmin"))
                                {
                                    $this->branches_model->removeEmployee($this->input->post("branch_id"),$oldbranchadmin->UserId);
                                    $this->branches_model->addEmployee($this->input->post("branch_id"),$this->input->post("badmin"));
                                }
                                
                            }
                            else
                            {
                                $this->branches_model->addEmployee($this->input->post("branch_id"),$this->input->post("badmin"));
                                
                            }
                            
                            $existingemp=$this->branches_model->getBranchemployees($this->input->post("branch_id"));
                            foreach($existingemp as $eempid)
                                $this->branches_model->removeEmployee($this->input->post("branch_id"),$eempid);
                            
                            $emparray=$this->input->post("bemployees");
                            foreach($emparray as $empid)
                                $this->branches_model->addEmployee($this->input->post("branch_id"),$empid);  
							redirect("branches");
					}
					else
					{
						$this->msg["validation_error"] = "Branch Name already exist.";
						$this->edit($this->input->post("branch_id"));
					}
				
			}
			else
			{
				$this->msg["validation_error"] = validation_errors();
				$this->edit($this->input->post("branch_id"));
			}
		}
		else
		{
				redirect("branches");
		}
    }
    
    public function change($branch_id='',$to_status='')
	{
		if($branch_id=='' or $to_status=='')
			redirect("branches");
		$res = $this->branches_model->update(array("IsActive"=>$to_status,"UpdatedBy"=>$this->phpsession->get("ad_user_id"),"UpdatedOn"=>date("Y-m-d H:i:s")),array("BranchId"=>$branch_id,"IsActive <> "=>$to_status));
		if($this->db->affected_rows()>0)
			$this->phpsession->flashsave("succ_msg","Status updated successfully");
		else
			$this->phpsession->flashsave("error_msg","No changes made. Record not updated");
		redirect("branches");
	}	
}
?>