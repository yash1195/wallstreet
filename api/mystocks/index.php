<?php
	include '../dbcon.php';
	global $conn;

	//session variables
	$username = $_SESSION['username'];
	//testing
	$username = "zeko";
	$data = array();
	$qry = "SELECT * FROM oe_wallstreet_user_stocks WHERE username = '$username'";
	$result = mysqli_query($conn,$qry);
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		array_push($data, $row);
	}
	echo json_encode($data);
?>