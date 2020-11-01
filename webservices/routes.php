<?php
header('Content-Type: text/html; charset=utf-8');
 header("Access-Control-Allow-Origin: *");
 date_default_timezone_set("Asia/Gaza");
 
// Classes & Models
require_once 'model/db.php';
require_once 'model/Custom.php';
require_once 'classes/Response.php';
require_once 'classes/JWT.php';
require_once 'classes/Helper.php';

$response=new Response();
// Controllers
require_once 'controller/UserController.php';

$user=new UserController();

switch($_GET['route'])
{
	//USER
	case "status" :
		$user->status();
		break;
	case "redeem" :
		$user->redeem();
		break;
		
		
		
	default:
		$response->notFound("(".$_GET['route'].") No Such Function");
}
	
?>