<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Branches_model extends CI_Model {
    public $table = "tbl_branch_master";
    public $table_employee = "tbl_branch_employees";
   
    function get($table,$where=array())
	{
		if(count($where)>0)
			$this->db->where($where);
		$res = $this->db->get($table);
		if($res->num_rows()>0)
			return $res;
		else
			return false;
	}	
   
    function getBranches($where=array(),$limit="",$offset="",$orderby="",$disporder="")
	{
	   if(count($where)>0)
			$this->db->where($where);
        
        if($orderby!="" && $disporder!="")
            $this->db->order_by($orderby,$disporder);
            
		if($limit!="" && $offset!="")
            $this->db->limit($limit,$offset);
        
		$res = $this->db->get($this->table);
		if($res->num_rows()>0)
			return $res;
		else
			return false;
	}	
	
	
	public function insert($data)
	{
		$this->db->insert($this->table,$data);
		return $this->db->insert_id();
	}
    
    public function update($data,$where=array())
	{
		if(count($where)>0)
			$this->db->where($where);
		return $this->db->update($this->table,$data);
	}
    public function delete($table,$data,$where=array())
	{
		if(count($where)>0)
			$this->db->where($where);
		return $this->db->delete($table,$data);
	}
    public function getBranchadmin($branchid)
    {
        $this->db->select('um.UserId, um.FirstName, um.LastName, um.EmailId, um.DesignationId, dm.DesignationName');
        $this->db->from('tbl_user_master um');
        $this->db->join('tbl_designation_master dm', 'um.DesignationId=dm.DesignationId');
        $this->db->join('tbl_branch_employees be', 'um.UserId=be.UserId');
        $where=array("um.DesignationId"=>3,"be.BranchId"=>$branchid);
        $this->db->where($where);
        
        $res = $this->db->get();
        
		if($res->num_rows()>0)
        {
            foreach($res->result() as $c)
                $return=$c;
            return $return;
        }
		else
			return false;
    }
    
    public function getBranchemployees($branchid)
    {
        $returnarray=array();
        $this->db->select('um.UserId, um.FirstName, um.LastName, um.EmailId, um.DesignationId, dm.DesignationName');
        $this->db->from('tbl_user_master um');
        $this->db->join('tbl_designation_master dm', 'um.DesignationId=dm.DesignationId');
        $this->db->join('tbl_branch_employees be', 'um.UserId=be.UserId');
        $where=array("um.DesignationId"=>6,"be.BranchId"=>$branchid);
        $this->db->where($where);
        
        $res = $this->db->get();
        if($res && $res->num_rows()>0)
        {
            foreach($res->result() as $c)
                $returnarray[]=$c->UserId;
            
        }
        return $returnarray;
    }
    
    public function addEmployee($branchid,$empid)
    {
        $data=array("BranchId"=>$branchid, "UserId"=>$empid);
        $this->db->insert($this->table_employee,$data);
        
    }
    
    public function removeEmployee($branchid,$empid)
    {
        $where=array("BranchId"=>$branchid, "UserId"=>$empid);
        return $this->delete($this->table_employee,$where);
    }
 }
?>