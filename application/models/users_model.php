<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
	public $table = "tbl_user_master";
    public $table_designation = "tbl_designation_master";
    public $table_branch_employees = "tbl_branch_employees";
    public $table_branch = "tbl_branch_master";
    public $table_attendance = "tbl_user_attendance";

	public function insert($data)
	{
		$this->db->insert($this->table, $data); 
		return $this->db->insert_id();
	}
	
	public function getUser($where=array(),$limit="",$offset="",$orderby="",$disporder="")
	{
		if(count($where)>0)
			$this->db->where($where);
        
        if($orderby!="" && $disporder!="")
            $this->db->order_by($orderby,$disporder);
        else
            $this->db->order_by("DesignationId, UserId","asc");
        
        if($limit!="" && $offset!="")
            $this->db->limit($limit,$offset);
        
		$res = $this->db->get($this->table);
		if($res->num_rows()>0)
			return $res;
		else
			return false;
	}
    
	public function getUserArray($where = array(),$limit="", $offset="")
	{
		$ret = array();
		$res = $this->getUser($where, $limit, $offset);
		if($res!=false)
		{
			foreach($res->result() as $c)
                {
                    $ret[$c->UserId]["UserId"] = $c->UserId;
                    $ret[$c->UserId]["Name"] = $c->FirstName." ".$c->LastName;
                    $ret[$c->UserId]["Designationid"] = $c->DesignationId;    
                    $ret[$c->UserId]["EmailId"] = $c->EmailId;
                }
				
		}
		return $ret;
	}
    public function getDesignation($where=array())
    {
        $ret = array();
        if(count($where)>0)
			$this->db->where($where);
		$res = $this->db->get($this->table_designation);
        
		if($res->num_rows()>0)
			{
                if($res!=false)
        		{
        			foreach($res->result() as $c)
        				$ret[$c->DesignationId] = $c->DesignationName;
        		}
        		return $ret;
			}
		else
			return false;
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
   
	public function  getUserUserDetail($where=array())
	{
		$this->db->select("u.*,ut.DesignationName");
		$this->db->from($this->table." as u");		
		$this->db->join($this->table_designation." as ut","u.DesignationId = ut.DesignationId");
        if(count($where)>0)
			$this->db->where($where);
        $this->db->order_by("u.UserId","desc");
		$res = $this->db->get();
		if($res->num_rows()>0)
			return $res;
		else
			false;	
	}
    
    public function  getUserInfo($userid)
	{
        $this->db->select("u.*,ut.DesignationName");
		$this->db->from($this->table." as u");		
		$this->db->join($this->table_designation." as ut","u.DesignationId = ut.DesignationId");
        $where = array("UserId"=>$userid);
		$this->db->where($where);
		$res = $this->db->get();
		if($res->num_rows()>0)
			return $res->row_object(0);
		else
			false;	
    }
	
    public function  getUserBranch($userid)
    {
        $ret = array();
        $this->db->select("bm.BranchId, bm.BranchName, bm.BranchLocation");
		$this->db->from($this->table_branch." as bm");		
		$this->db->join($this->table_branch_employees." as be","bm.BranchId = be.BranchId");
        $where = array("be.UserId"=>$userid);
		$this->db->where($where);
		$res = $this->db->get();
		if($res!=false)
		{
            $inc=0;
			foreach($res->result() as $c)
            {
                $ret[$inc]["BranchId"]=$c->BranchId;
                $ret[$inc]["BranchName"]=$c->BranchName;
                $ret[$inc]["BranchLocation"]=$c->BranchLocation;
                $inc++;
            }
				
		}
		return $ret;
    }
    
    public function insertEmployeeBatch($data)
    {
        return $this->db->insert_batch($this->table,$data);
    }
    
    public function getEmployeeAttendance($empid, $year="", $month="")
    {
        if($year=="")
            $year = date("Y");
        if($month=="")
            $month = date("m");
        
        $this->db->select("*");
		$this->db->from($this->table_attendance);	
        $where = array("UserId"=>$empid,"Month(AbsentDate)" => $month, "Year(AbsentDate)" => $year);
		$this->db->where($where);
		$res = $this->db->get();
        $ret = array();
		if($res->num_rows()>0)
		{
            if($res!=false)
    		{
    			foreach($res->result() as $c)
    				$ret[] = date("Y/m/d",strtotime($c->AbsentDate));
    		}
    		return $ret;
		}
		else
			return $ret;	
     
    }
    
    public function clearEmployeeAttendance($empid, $year, $month)
    {
        if($empid!="" && $year!="" && $month!="")
        {
            $where = array("UserId"=>$empid,"Month(AbsentDate)" => $month, "Year(AbsentDate)" => $year);
			$this->db->where($where);
            return $this->db->delete($this->table_attendance);
        }
    }
    
    public function updateEmployeeAttendance($empid, $year, $month, $absentdates)
    {
        if($empid!="" && $year!="" && $month!="" && $absentdates!="")
        {
            $absentdates_arr = explode(",",$absentdates);
            foreach($absentdates_arr as $abdate)
            {
                $data = array();
                $data["UserId"] = $empid;
                $data["AbsentDate"] = $abdate;
                $data["UpdatedBy"] = $this->phpsession->get("ad_user_id");
                $data["UpdatedOn"] = date("Y-m-d H:i:s");
                $this->db->insert($this->table_attendance, $data);
            }
            
        }
        return true;
    }
    
    
}
?>