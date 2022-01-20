<?php	

session_start();	
$_SESSION['logged'] = false;
unset($_SESSION['user_id']);
session_destroy();

header('location:login');

?>