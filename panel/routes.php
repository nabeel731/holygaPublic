<?php
session_start();
date_default_timezone_set("Asia/Gaza");
 
// Classes & Models
require_once 'model/db.php';
require_once 'model/Custom.php';
require_once 'classes/Helper.php';
// Controllers
require_once 'controller/AuthController.php';
require_once 'controller/UserController.php';
require_once 'controller/RedeemController.php';

$allowedWithoutLogin=['login','loginSubmit'];
if(!in_array($_GET['route'],$allowedWithoutLogin))
{
	if(!isset($_SESSION['tv_admin_id']))
		echo "<script>location.href='login'</script>";
}

$auth=new AuthController();
$user=new UserController();
$redeem=new RedeemController();

switch($_GET['route'])
{
	
	// AUTH
	case "login" :
		loadView('login');
		break;
	case "loginSubmit" :
		$auth->login();
		break;
	case "logout" :
		$auth->logout();
		break;
	case "updatePassword" :
		$auth->updatePassword();
		break;
		
	//Dashboard
	case "index" :
		$user->index();
		break;
	case "dashboard" :
		$dashboard->index();
		break;

	//USER
	case "users" :
		$user->index();
		break;	
    case "addUser" :
		$user->addUser();
		break;
		
	case "getUser" :
		$user->getUser();
		break;
	case "updateUser" :
		$user->updateUser();
		break;
	case "blockunblockUser" :
		$user->blockunblockUser();
		break;
		
	case "unblockUser" :
		$user->unblockUser();
		break;
		
	case "deleteUser" :
		$user->deleteUser();
		break;
		
		
	case "WarningToUser" :
		$user->WarningToUser();
		break;
		
	case "redeem" :
		$redeem->index();
		break;
		
		
		case "addRedeem" :
		$redeem->addRadeem();
		break;
		
		case "updateRedeem" :
		$redeem->updateRedeem();
		break;
		
		case "deleteRedeem" :
		$redeem->deleteRedeem();
		break;
		
		case "getRedeem" :
		$redeem->getRedeem();
		break;
		
		
		
	default:
		die("(".$_GET['route'].") No Such Function");
}
	
	function loadView($view)
	{
		include 'views/'.$view.".php";
	}
	
?>