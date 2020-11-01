<?php
class AuthController
{
	protected $helper;
	protected $custom;
	protected $response;
	protected $jwt;
	protected $db;
	protected $email;
	function __construct()
	{		
		$this->response = new Response();
		$this->jwt = new JWT();
		$this->db=new DB();
		$this->custom=new Custom();
		$this->helper=new Helper();
		$this->email=new Email();
	}
	
	public function login(){
		//if(!isset($_GET['apikey']) || $_GET['apikey']!=="ksd232b2j323b@345*2dhhs!")
				//$this->response->error("Not Authorized");
		if(isset($_GET['phone']))
		{
    		if (strpos($_GET['phone'], '+') === false) 
    			$_GET['phone']="+".$_GET['phone'];
    		$_GET['phone']=str_replace(' ', '',$_GET['phone']);
        	if(strlen($_GET['phone'])<12 || strpos($_GET['phone'], '+') === false  )
        		    $this->response->error("Invalid Phone Number");
				
        	$res=$this->custom->returnUserData($_GET['phone'],'phone');
		}
		else
		$this->response->error("Requires Phone");
    		    
		if($res)
		{
		    if($res['deleted'])
		       $this->response->error("Your Account Has been Deleted,Please Contact To Administration");
		    elseif(!$res['status'])
		       $this->response->error("You Are Banned From Using This Application, Please Contact To Administration");
			$res['token']=$this->jwt->generateToken($res['id']);
			if(isset($res['password']))
				unset($res['password']);
			$this->response->success("User Logged In Successfully",$res);
		}
		else
			$this->response->notFound("User Not Found");
	}
	
	
	
	
	
	public function register(){
		$data=$this->helper->getInput();
		$this->helper->validateInput($data,['phone','name','dob','gender','job','picture1']);
		if (strpos($data['phone'], '+') === false) 
				$data['phone']="+".$data['phone'];
		$data['phone']=str_replace(' ', '',$data['phone']);
		if(isset($data['email']))
		{	if($this->db->exist("users",'email',$data['email']))
				$this->response->error("This Email is already registered");
		}
		else if($this->db->exist("users",'phone',$data['phone']))
			$this->response->error("This Phone Number is already registered");
		$data['dob']=strtotime($data['dob']);
		// UPLOAD BASE64 IMAGE
		$picture=$this->helper->base64_to_jpeg($data['picture1'],"uploads/pictures/".time().rand(11,99).".jpg");
		if(isset($data['picture1'])) unset($data['picture1']);
		
		$newUser=$this->db->insertRow('users',$data);			
		if(empty($newUser))
			$this->response->error("Oooops Account Could Not be Created due to some reason");
	
		$this->db->insertRow('pictures',['user_id'=>$newUser['id'],'picture1'=>$picture]);
		$this->db->insertRow('filters',['user_id'=>$newUser['id']]);
		$res=$this->custom->returnUserData($newUser['id']);
		$res['token']=$this->jwt->generateToken($newUser['id']);
		if(isset($res['password']))
			unset($res['password']);
		$this->response->success("User Registred Successfully",$res);
	}
	
}
