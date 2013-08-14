<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recruitment_model extends CI_Model {
	public $table = "tbl_authorization_forms";
    public $table_message = "tbl_authorization_form_notes";
    public $table_user = "tbl_user_master";
    public $table_access = "tbl_authorization_form_access";
    public $table_payrolldocs = "tbl_recruit_files_master";
    public $table_recruit_payrolldocs = "tbl_recruit_files";

	public function insert($data)
	{
		$this->db->insert($this->table, $data); 
		return $this->db->insert_id();
	}
	public function insertmessage($data)
    {
        $this->db->insert($this->table_message, $data); 
		return $this->db->insert_id();    
    }
    public function insertdocs($data)
    {
        $this->db->insert($this->table_recruit_payrolldocs, $data); 
		return $this->db->insert_id();    
    }
	public function getRecruit($where=array())
	{
		if(count($where)>0)
			$this->db->where($where);
		$res = $this->db->get($this->table);
		if($res->num_rows()>0)
			return $res;
		else
			return false;
	}
    
    public function getRecruitDetails($where=array(),$limit="",$offset="",$orderby="",$disporder="")
	{
		$this->db->select("u.*,ut.FirstName, ut.LastName");
		$this->db->from($this->table." as u");		
		$this->db->join($this->table_user." as ut","u.UploadedBy = ut.UserId");
        if(count($where)>0)
			$this->db->where($where);
        
        
        if($orderby!="" && $disporder!="")
            $this->db->order_by($orderby,$disporder);
        else
            $this->db->order_by("u.UploadedOn","desc");
        
        if($limit!="" && $offset!="")
            $this->db->limit($limit,$offset);
        
		$res = $this->db->get();
        
		if($res->num_rows()>0)
			return $res;
		else
			false;	
	}
    
    public function getMessages($formid)
	{
		$this->db->select("u.*,ut.FirstName, ut.LastName");
		$this->db->from($this->table_message." as u");		
		$this->db->join($this->table_user." as ut","u.UserId = ut.UserId");
		$this->db->order_by("u.NotesOn","desc");
        $where=array("u.FormId"=>$formid);
        $this->db->where($where);
		$res = $this->db->get();
		if($res->num_rows()>0)
			return $res;
		else
			false;
	}
    
	public function update($data,$where=array())
	{
		if(count($where)>0)
			$this->db->where($where);
		return $this->db->update($this->table,$data);
	}
	
	public function delete($where=array())
	{
		if(count($where)>0)
			$this->db->where($where);
		return $this->db->delete($this->table);
	}
    
    public function assignAccess($data)
    {
        $this->db->insert($this->table_access, $data); 
		return $this->db->insert_id();    
    }
    
    public function getRecievedRecruitDetails($userid,$limit="",$offset="",$orderby="",$disporder="")
    {
        $this->db->select("u.*,ut.FirstName, ut.LastName");
		$this->db->from($this->table." as u");		
		$this->db->join($this->table_user." as ut","u.UploadedBy = ut.UserId");
        $this->db->join($this->table_access." as tc","u.FormId = tc.FormId");
        $where="tc.UserId = ".$userid." and tc.UserId!=u.UploadedBy";
        
		$this->db->where($where);
        $this->db->order_by("u.UploadedOn","desc");
		$res = $this->db->get();
        
		if($res->num_rows()>0)
			return $res;
		else
			false;
    }
    
    public function getPayrollDocsList($where)
    {
        $this->db->select("*");
		$this->db->from($this->table_payrolldocs);		
        if(count($where)>0)
        $this->db->where($where);
        
        $res = $this->db->get();
        
		if($res->num_rows()>0)
        {
            return $res->result();
        }
		else
			return false;
    }
    
    public function getDistributionlist($formid)
    {
        $this->db->select("tac.FormId, tum.*");
		$this->db->from($this->table_access." as tac");		
		$this->db->join($this->table_user." as tum","tac.UserId = tum.UserId");
        $this->db->where(array("tac.FormId"=>$formid));
		$res = $this->db->get();
		if($res->num_rows()>0)
			return $res->result();
		else
			false;	
    }
    
    public function getPayrollDocs($formid, $recfileid = "")
	{
		$this->db->select("rf.FormId, rfm.RecruitFileId, rf.FormLocation, rf.UploadedOn, rf.UploadedBy, rfm.RecruitFileName");
		$this->db->from($this->table_payrolldocs." as rfm");		
		$this->db->join($this->table_recruit_payrolldocs." as rf","rf.RecruitFileId = rfm.RecruitFileId and rf.FormId=".$formid, "LEFT");
        $where = array("rfm.IsActive"=>1);
        if($recfileid!="")
        {
                $where["rf.RecruitFileId"] = $recfileid;    
        }
        
		$this->db->where($where);
		$res = $this->db->get();
		if($res->num_rows()>0)
        {
            return $res->result();
        }
		else
			return false;
	}
    
    public function getPayrollDocsArray($formid)
	{
        $returnArray=array();
        
        $payrolldocslist= $this->getPayrollDocs($formid);
        if($payrolldocslist)
        {
            foreach($payrolldocslist as $pd)
            {
                if(isset($pd->FormId) && $pd->FormId!="")
                {
                    array_push($returnArray,$pd->RecruitFileId);
                }
            }
        }
        
        return $returnArray;
    }
    
    public function removedocs($formid,$recfileid)
    {
        $where = array("FormId"=>$formid, "RecruitFileId"=>$recfileid);
			$this->db->where($where);
		return $this->db->delete($this->table_recruit_payrolldocs);    
    }
    
    
}
?>