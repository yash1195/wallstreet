<?php
	include '../dbcon.php';
	global $conn;
	$username = $_SESSION['username'];
	//testing
	$username = "zeko";
	$mycash = 0;
	$leaders = 0;
	$followers = 0;


	
	function getStockValue($stock)
	{
		global $conn;
		$qry = "SELECT * FROM oe_wallstreet_stockmarket WHERE stockname = '$stock'";
		$result = mysqli_query($conn,$qry);
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		return $row['l'];
	}

	function getInStock($username)
	{
		$instock = 0;
		global $conn;
		$qry = "SELECT * FROM oe_wallstreet_user_stocks WHERE username = '$username'";
		$result = mysqli_query($conn,$qry);
		while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$vol = $row['stockvol'];
			$price = getStockValue($row['stockname']);
			$instock += $vol*$price;
		}
		return $instock;
	}

	$mywealth = getInStock($username);


	$qry = "SELECT * FROM oe_wallstreet_user_wallet WHERE username = '$username'";
	$result = mysqli_query($conn,$qry);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	$mycash = $row['cash'];
	$mywealth += $mycash;

	$qry = "SELECT * FROM oe_wallstreet_user_wallet";
	$result = mysqli_query($conn,$qry);
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{
		$oinstock = getInStock($row['username']);
		$owealth  = $row['cash']+$oinstock;
		if($owealth < $mywealth)
		{
			$trailers++;
		}
		if($owealth > $mywealth)
		{
			$leaders++;
		}
		if($owealth == $mywealth)
		{
			$atpar++;
		}
	}

	
	$data = array('leaders'=>$leaders ,'atpar'=>$atpar, 'trailers' => $followers);
	echo json_encode($data);
?>