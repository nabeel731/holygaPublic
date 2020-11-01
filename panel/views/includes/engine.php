<?php


    if (session_status()== PHP_SESSION_NONE) {
        session_start();
    }
	if(!isset($_SESSION['owl_admin_id']))
		echo "<script>location.href='login'</script>";