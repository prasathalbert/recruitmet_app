<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payment_model extends CI_Model {
	public $table = "tbl_payment_request";
    public $table_user = "tbl_user_master";
    public $table_paymentdocs = "tbl_payment_request_files";
    public $table_paymentnotes = "tbl_payment_request_notes";
    public $table_branch = "tbl_branch_master";
	public function insert($data)
	{
		$this->db->insert($this->table, $data); 
		return $this->db->insert_id();
	}
	public function insertmessage($data)
    {
        $this->db->insert($this->table_paymentnotes, $data); 
		return $this->db->insert_id();    
    }
    public function insertdocs($data)
    {
        $this->db->insert($this->table_paymentdocs, $data); 
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
    
    public function getPaymentRequestDetails($where=array(),$limit="",$offset="",$orderby="",$disporder="")
	{
		$this->db->select("p.*,ut.FirstName, ut.LastName, tb.BranchName, tb.BranchLocation, ut.EmailId");
		$this->db->from($this->table." as p");		
		$this->db->join($this->table_user." as ut","p.RequestedBy = ut.UserId");
        $this->db->join($this->table_branch." as tb","p.RequestedBranchId = tb.BranchId");
        if(count($where)>0)
			$this->db->where($where);
            
        if($orderby!="" && $disporder!="")
            $this->db->order_by($orderby,$disporder);
        else
            $this->db->order_by("p.RequestedOn","desc");
        
        if($limit!="" && $offset!="")
            $this->db->limit($limit,$offset);
            
		$res = $this->db->get();
		if($res->num_rows()>0)
			return $res;
		else
			false;	
	}
    
    public function getNotes($paymentid)
	{
		$this->db->select("u.*,ut.FirstName, ut.LastName");
		$this->db->from($this->table_paymentnotes." as u");		
		$this->db->join($this->table_user." as ut","u.UserId = ut.UserId");
		$this->db->order_by("u.NotesOn","desc");
        $where=array("u.PaymentRequestId"=>$paymentid);
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
    
    public function getPaymentDocs($paymentid, $fileid="")
	{
		$this->db->select("FileId, PaymentRequestId, FileName, FileLocation, UploadedBy, UploadedOn, IsActive");
		$this->db->from($this->table_paymentdocs);
        $where = array("PaymentRequestId"=>$paymentid);
        if($fileid!="")
        $where["FileId"] = $fileid;
        
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
    
    public function getPaymentDistributionlist($designation="7")
    {
        $where = "tum.DesignationId in(".$designation.")";
        $this->db->select("tum.*");
		$this->db->from($this->table_user." as tum");
        $this->db->where($where);
		$res = $this->db->get();
		if($res->num_rows()>0)
			return $res->result();
		else
			false;	
    }
    
    
}
?>