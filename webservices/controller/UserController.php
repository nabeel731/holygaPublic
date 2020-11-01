<?php
class UserController
{
	protected $helper;
	protected $custom;
	protected $response;
	protected $db;
	protected $jwt;
	function __construct()
	{	
		$this->response = new Response();
		$this->db=new DB();
		$this->custom=new Custom();
		$this->helper=new Helper();
		$this->jwt=new JWT();
	}
	
	public function redeem(){
		$this->helper->validateInput('get',['coupon']);
		$token=$this->db->getSingleRowIfMatch('redeem_coupon','redeem_code',$_GET['coupon']);
		if(!$token)
			$this->response->error("Invalid coupon");
		elseif($token['status']>1)
			$this->response->error("Coupon already used");
		
			
			
		$jwt['expiry']=time()+(86400*$token['validity']);
		$jwt['user_id']=$token['user_id'];
		if($token=$this->db->updateRow('redeem_coupon',['status'=>2,'expiry'=>$jwt['expiry']],'id',$token['id']))
		{
			$token['jwt_token']=$this->jwt->generateToken($jwt);
			$this->response->success("Coupon Redeemed Successfully",$token);
		}
		
		$this->response->error("Coupon Could not be redeemed due to some error");
	}
	
	public function status()
	{
		$token=$this->db->getSingleRowIfMatch('redeem_coupon','user_id',LOGGED_USER);
		if($token['expiry']<time())
		{
			if($token['status']<3)
				$token=$this->db->updateRow('redeem_coupon',['status'=>3],'id',$token['id']);
			$this->response->error("Coupon Expired");
		}
		elseif($token['status']>2)
			$this->response->error("Coupon Expired");
		$this->response->success("Coupon is active",$token);
	}
	
}
