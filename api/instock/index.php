<?php 
	include '../dbcon.php';
	global $conn;
	//session variables
	$username = $_SESSION['username'];
	$username = "zeko";
	$instock = 0;
	function getStockValue($stock)
	{
		global $conn;
		$qry = "SELECT * FROM oe_wallstreet_stockmarket WHERE stockname = '$stock'";
		$result = mysqli_query($conn,$qry);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		return $row['l'];
	}

	$qry = "SELECT * FROM oe_wallstreet_user_stocks WHERE username = '$username'";
	$result = mysqli_query($conn,$qry);
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		$vol = $row['stockvol'];
		$price = getStockValue($row['stockname']);
		$instock += $vol*$price;
	}
	$instock = round($instock,2);
	$data = array('instock' => $instock );
	echo json_encode($data);
?>