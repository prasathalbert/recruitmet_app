<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class CI_Front_Template
{
	
    public $news=false;
    
	public function __construct()
	{	
		$this->CI =& get_instance();
        $this->loadPermission();
        $this->CI->load->model("news_model");
        $this->news = $this->CI->news_model->getNews();
	}
		
    
	public function load_template($data=array("content"=>"" , "title"=>"","current_menu" => "","view_file"=>""))
	{
        
        
        $headerdata = $data;
        $headerdata["news"] = $this->news;
		$this->CI->load->view("front/template/header",$headerdata);
		$this->CI->load->view("front/".$data["view_file"],$data);		
		$this->CI->load->view("front/template/footer");	
        
	}
    
    
	public function load_login_template($data=array("content"=>"" , "title"=>"","current_menu" => "","view_file"=>""))
	{
		$this->CI->load->view("front/template/login_header",$data);
		$this->CI->load->view("front/".$data["view_file"],$data);		
		$this->CI->load->view("front/template/login_footer");	
        
	}
    
	public function load_email_template($data=array("title"=>"","content"=>""))
	{
	    $return="";
		$return .=$this->CI->load->view("email/template/email_header",$data, TRUE);
		$return .=$this->CI->load->view("email/".$data["view_file"],$data, TRUE);		
		$return .=$this->CI->load->view("email/template/email_footer",$data, TRUE);
        
        return $return;
	}
    
    function loadPermission()
	{
		global $config_user_rights;
		// get all access rights from db		
		$res = $this->CI->db->get("tbl_user_action");
		if($res->num_rows()>0)
		foreach($res->result() as $r)
			$config_user_rights[] = "(".$r->DesignationId.",".$r->ActionId.")";
		
	}	
}
?>