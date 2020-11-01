<?php
include_once 'classes/FCM.php';
class UserController
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
	public function index(){
		$users=$this->db->getTable('users');
		include 'views/users.php';
	}
	public function WarningToUser()
	{
		$id=$_POST['userID'];
		$title="Warning";
		$body="warmimg from ADmin";
		if($this->fcm->sendToSingle($id,$title,$body))
			echo "1";
		else
			echo "0";
	}
	
	
	
	public function blockunblockUser()
	{
		$id=$_POST['blockID'];
		$user['status']=$_POST['status'];
		if($this->db->updateRow('users',$user,'id',$id))
		   echo $_POST['status'];
	   else
		   echo 'unsuccessful';
		
	}
	
	
	
	public function user(){
		$this->helper->validateInput('get',['id']);
		$res=$this->custom->returnUserData($_GET['id']);
		if($res['deleted'])
		       die("Your Account Has been Deleted,Please Contact To Administration");
		if(!$res['status'])
		       $this->response->error("You Are Banned, Please Contact To Administration");
		return $res;
	}
	
	
	
	public function deleteUser()
	{
		
		$this->helper->validateInput('post',['userId']);
		$userID=$_POST['userId'];unset($_POST['userId']);
		$redeem=$this->db->deleteDataWIthQuery("DELETE users,redeem_coupon FROM users LEFT JOIN redeem_coupon on redeem_coupon.user_id=users.id WHERE users.id='$userID'");
		if($redeem)
		{
			echo "1";
		}
		else
		echo "0";	
	}
	
	
	public function addUser()
	{
		
		$this->helper->validateInput('post',['name','phone','email','device_id']);
		if($data=$this->db->exist("users",'email',$_POST['email']))
			echo "<script>location.href='users?error=Email_TAKEN'</script>";
		if($data=$this->db->exist("users",'device_id',$_POST['device_id']))
			echo "<script>location.href='users?error=device_id_TAKEN'</script>";
		$user=$this->db->insertRow('users',$_POST);
		if($user)
			echo "<script>location.href='users?success=created'</script>";
		else
		echo "<script>location.href='users?error=not_created'</script>";	
	}
	
	public function getUser(){
		$this->helper->validateInput('get',['id']);
		$user=$this->db->getSingleRowIfMatch("users",'id',$_GET['id']);
		if($user)
		$res= array( 'status'=>200,'message'=> "User Data",'data'=>$user);
		print_r(json_encode($res));
		die;
	}
	
	
	
	public function updateUser()
	{
		print_r($_POST);die;
		
		$this->helper->validateInput('post',['name','phone','user_id','email']);

		$userID=$_POST['user_id'];  unset($_POST['user_id']);
		$user=$this->db->updateRow('users',$_POST,'id',$userID);
		if($user)
		{
			echo "<script>location.href='users?success=updated'</script>";
		}
		echo "<script>location.href='users?error=not_updated'</script>";	
	}
	
	
}
