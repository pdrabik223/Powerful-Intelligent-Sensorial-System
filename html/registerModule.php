<?php 
include "db_connection.php";

session_start();

$name = $_POST['user'];
$pass = $_POST['password'];
$conf_pass = $_POST['conf_pass'];

//jesli rozne wartosci hasel
if($pass != $conf_pass)
{
	//echo"rozne wartosci hasel";
	session_destroy();
	header('location:register');
}else{

	//sprawdzamy czy taki user juz istnieje, jesli nie to dodajemy do bazy
	$s = "select user_id from usertable where name = '$name' ";
	$result = mysqli_query($con, $s);
	$num = mysqli_num_rows($result);

	if($num == 0){
		$reg = "insert into usertable(name, password) values ('$name', '$pass')";
		mysqli_query($con, $reg);
		//echo" Registration succesful";
		session_destroy();
		header('location:login');
	}else{
		//echo" Username already taken";
		session_destroy();
		header('location:register');
	}
}
?>
