<?php
	include '../dbcon.php';
	global $conn;
	//session variables
	$username = $_SESSION['username'];
	$username = "zeko";	
	
	$qry = "SELECT * FROM oe_wallstreet_user_wallet WHERE username = '$username'";
	$result = mysqli_query($conn,$qry);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	echo json_encode($row);
?>