<?php

$con = mysqli_connect("34.125.40.127","root","pssd123");

if(!$con){
    die("Connection failed: " . mysqli_connect_error());
}else{
	mysqli_select_db($con, 'measurements');
}

?>
