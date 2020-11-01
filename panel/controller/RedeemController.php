<?php
class RedeemController
{
	
	protected $helper;
	protected $custom;
	protected $db;
	protected $fcm;
	function __construct()
	{	
		$this->db=new DB();
		$this->custom=new Custom();
		$this->helper=new Helper();
		$this->fcm=new FCM();
	}
	
	
	public function index()
	{
		$redeemdata=$this->db->getDataWIthQuery('select * from users join redeem_coupon on users.id=redeem_coupon.user_id');
		$usersdata=$this->db->getTable('users');
		include 'views/redeem.php';
	}
	
	
	
	
	public function addRadeem()
	{
		
		$this->helper->validateInput('post',['validity','redeem_code','user_id']);
		if($data=$this->db->exist("redeem_coupon",'redeem_code',$_POST['redeem_code']))
			echo "<script>location.href='redeem?error=coupon_already_exists'</script>";
		$user=$this->db->insertRow('redeem_coupon',$_POST);
		if($user)
		{
			echo "<script>location.href='redeem?success=created'</script>";
		}
		echo "<script>location.href='users?error=not_created'</script>";	
	}
	
	public function getRedeem(){
		$this->helper->validateInput('get',['id']);
		$user=$this->db->getSingleRowIfMatch("redeem_coupon",'id',$_GET['id']);
		if($user)
		$res= array( 'status'=>200,'message'=> "Redeem Data",'data'=>$user);
		print_r(json_encode($res));
		die;
	}
	
	
	
	public function updateRedeem()
	{
		
		$this->helper->validateInput('post',['validity','redeem_code','user_id','redeem_id']);
		$userID=$_POST['user_id'];
		$redeemCode=$_POST['redeem_code'];
		if($this->db->getDataWIthQuery("select * from redeem_coupon where user_id=!$userID AND redeem_code=$redeemCode"))
			echo "<script>location.href='redeem?error=coupon_already_exists'</script>";
		$RedeemID=$_POST['redeem_id'];unset($_POST['redeem_id']);
		$user=$this->db->updateRow('redeem_coupon',$_POST,'id',$RedeemID);
		if($user)
		{
			echo "<script>location.href='redeem?success=updated'</script>";
		}
		echo "<script>location.href='redeem?error=not_updated'</script>";	
	}
	
	
	
	public function deleteRedeem()
	{
		
		$this->helper->validateInput('post',['redeemId']);
		$redeemId=$_POST['redeemId'];
		$redeem=$this->db->deleteRow('redeem_coupon','id',$redeemId);
		if($redeem)
		{
			echo "1";
		}
		else
		echo "0";	
	}
	
	
}
