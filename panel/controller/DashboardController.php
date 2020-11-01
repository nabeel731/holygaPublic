<?php
class DashboardController
{
	protected $helper;
	protected $custom;
	protected $db;
	function __construct()
	{	
		$this->db=new DB();
		$this->custom=new Custom();
		$this->helper=new Helper();
	}
	
	public function index(){
		$data['stats']=$this->custom->getWebStats();
		include 'views/index.php';
	}
	
	
}
