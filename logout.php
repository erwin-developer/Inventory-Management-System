<?php
ob_start();
session_start();

if (!isset($_SESSION['id'])) {
	ob_end_clean();
	header("Location: signin.php");
	exit();

	}else{ 

	$_SESSION = array();
	session_destroy();
	setcookie (session_name(), '', time()-300);
	}

	ob_end_clean();
	header("Location: signin.php");
	exit();
?>
