<?php

// Classes & Models
require_once 'model/db.php';
require_once 'model/Custom.php';
require_once 'classes/Helper.php';

include_once 'controller/DashboardController.php';
$dashboard=new DashboardController();
$dashboard->index();
?>