<?php 
include "db_connection.php";

session_start();

$name = $_POST['user'];
$pass = $_POST['password'];

// sprawdzamy czy login i hasło są dobre
$s = "select user_id from usertable where name = '$name' && password = '$pass' ";

$result = mysqli_query($con, $s);
$num = mysqli_num_rows($result);

if ( $num > 0 )
{
	$_SESSION['logged'] = true;
	$_SESSION['user_id'] = $result;
	header('location: dashboard');

}else {
	session_destroy();
	header('location: login');
}

?>