<?php

include '../dbcon.php';
global $conn;

// SESSION VARIABLES
$username = $_SESSION['username'];
$username = "zeko";

$qry = "SELECT * FROM oe_wallstreet_stockmarket";
$result = mysqli_query($conn,$qry);
$data = array();
while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
{
	array_push($data, $row);
}

echo json_encode($data);


?>