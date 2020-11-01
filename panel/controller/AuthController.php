<?php
class AuthController
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
	
	public function login(){
		 $this->helper->validateInput('POST',['email','password']);
		 $_POST['password']=sha1($_POST['password']);
		 $query="SELECT * from admins where email=? AND password=? LIMIT 1";
		 $admin=$this->db->getDataWithQuery($query,[$_POST['email'],$_POST['password']],'ss');
		if($admin && isset($admin[0]))
		{
			$admin = $admin[0];
			$_SESSION['tv_admin_id']=$admin['id'];
			$_SESSION['tv_admin_name']=$admin['name'];
			$user=new UserController();
	        $user->index();
		}
		else
		 echo "<script>location.href='login?error=INVALID_CREDENTIALS'</script>";
	 }
	 
	 public function logout($message=NULL)
	 {
		 if(isset($_SESSION['owl_admin_id']))
			 unset($_SESSION['owl_admin_id']);
		 if(isset($_SESSION['owl_admin_name']))
			 unset($_SESSION['owl_admin_name']);
		 session_destroy();
		 if(!$message)
		  echo "<script>location.href='login'</script>";
		else
		echo "<script>location.href='login?success=$message'</script>";
			
	 }
	 
	 public function updatePassword()
	 {
		 $query=" SELECT * FROM admins WHERE id=".$_SESSION['owl_admin_id']." AND password='".sha1($_POST['old_password'])."'";
		 $admin=$this->db->getDataWithQuery($query);
		  if($_POST['new_password']!= $_POST['confirm_password'] )
			  echo "<script>location.href='index?error=PASSWORD_MISMATCH'</script>";
		 if($admin && isset($admin[0]))
		 {
			 $this->db->updateRow("users",['password'=>sha1($_POST['new_password'])],'id',$_SESSION['owl_admin_id']);
			 $this->logout("PASSWORD_UPDATED");
		 }
		 else
			 echo "<script>location.href='index?error=INCORRECT_OLD_PASSWORD'</script>";
	 }
	
	
}
