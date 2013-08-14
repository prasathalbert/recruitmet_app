<?php

/**
 * @author Prasath Albert
 * @Date 2013-07-08
 */

class Form_model extends CI_Loader 
{
    protected $loadedresult;
    protected $resultobj;
    protected $conn_id;
    
   /**
    * Form::save()
    * Will load the datas from getData and save or update the relevent record
    * @return
    */
   public function save()
    {
        $dataset = $this->getData();
        unset($dataset->id);
        $preparedstring = $this->preparesql($dataset,", ");
        if($this->id)
        {
            $sql = "Update ".$this->_table." set ".$preparedstring." where ".$this->_pk."=".$this->id;
        }    
        else
        {
            $sql = "insert into ".$this->_table."(name,email) values('".$this->name."','".$this->email."')";
           
        }
        $this->resultobj = $this->execute($sql);
        $this->load($this->lastinsertid());
        return $this;
    }
    /**
     * Form::load()
     * Load the Records based on Given Id
     * @param mixed $id
     * @return
     */
    public function load($id)
    {
        $sql = "SELECT * FROM ".$this->_table." where ".$this->_pk."=".$id;
        $this->resultobj = $this->execute($sql);
        $loadedresult = $this->fetchdbobject();
        $this->loadedresult = $loadedresult;
        $this->id=$loadedresult->id;
        $this->name=$loadedresult->name;
        $this->email=$loadedresult->email;
        
        return $this;
    }
    /**
     * Form::delete()
     * Delete the records by given id or details
     * @param string $id
     * @return
     */
    public function delete($id="")
    {
        if($id)
        {
            $sql = "Delete from ".$this->_table." where ".$this->_pk."=".$id;
        }
        else
        {
            $dataset = $this->getData();
            if(!$this->id)
                unset($dataset->id);
            $preparedstring = $this->preparesql($dataset," and ");
            
            $sql = "Delete from ".$this->_table." where ".$preparedstring;
            
        }
        
        $this->resultobj = $this->execute($sql);
        return $this;
    }
    /**
     * Form::getData()
     * get records
     * @param bool $key
     * @return
     */
    public function getData($key=false)
    {
        $returnobj = null;
        if($key)
            return $this->$key;
        else
        {
            $returnobj->id = $this->id;
            $returnobj->name = $this->name;
            $returnobj->email = $this->email;
            return $returnobj;
        }
            
    }
    /**
     * Form::setData()
     * set data for update, save, or delete 
     * @param mixed $arr
     * @param bool $value
     * @return
     */
    public function setData($arr, $value=false)
    {
        if(is_array($arr))
        {
           foreach($arr as $key=>$val)
                $this->$key=$val;
                
        }
        else
        {
            $this->$arr=$value;
        }
        
        return $this;
    }
    
    /**
     * Form::db_connect()
     * Connect to database
     * @return
     */
    protected function db_connect()
	{
        return @mysqli_connect($this->hostname, $this->username, $this->password, $this->database);
		
	}
    
    /**
     * Form::db_select()
     * Select database
     * @return
     */
    protected function db_select()
	{
		return @mysqli_select_db($this->conn_id, $this->database);
	}
    
    /**
     * Form::execute()
     * Execute the quries
     * @param mixed $sql
     * @return
     */
    protected function execute($sql)
	{
		$result = @mysqli_query($this->conn_id, $sql);
		return $result;
	}
    
    /**
     * Form::fetchdbobject()
     * fetch mysql object
     * @return
     */
    protected function fetchdbobject()
	{
		$result = @mysqli_fetch_object($this->resultobj);
		return $result;
	}
    
    /**
     * Form::preparesql()
     * prepare data for query
     * @param mixed $dataarray
     * @param string $seperator
     * @return
     */
    protected function preparesql($dataarray=null,$seperator = ",")
    {
        $returnstring = "";
        //print_r($dataarray);
        foreach ($dataarray as $key => $value) {
            $returnstring .= $key . "='" . $value ."'". $seperator;
        }
        if(strlen($returnstring)>0)
        {
            return substr($returnstring, 0 , (strlen($returnstring)-strlen($seperator)));
        }
        else
            $returnstring;
    }
    /**
     * Form::lastinsertid()
     * Get last inserted record id
     * 
     * @return
     */
    protected function lastinsertid()
    {
        if(mysqli_insert_id($this->conn_id))
        {
            return mysqli_insert_id($this->conn_id);
        }
    }
}
