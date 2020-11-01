<?php
class Custom
{
    protected $db;
    protected $response;
	function __construct()
	{
	    $this->db=new DB();
	    $this->conn=$this->db->getConnection();
	    $this->response=new Response();
	}
	
	
	public function returnStatus(){
		$query="";
	}

	
}
