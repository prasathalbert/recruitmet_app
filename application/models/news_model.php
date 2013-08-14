<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class News_model extends CI_Model {
    public $table = "tbl_news";
   
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
   
    function getNews($where=array(),$limit="",$offset="",$orderby="",$disporder="")
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
    public function delete($where)
	{
		if(count($where)>0)
        {
            $this->db->where($where);
            return $this->db->delete($this->table);
        }
		return false;
	}
 }
?>