<?php 
	include '../dbcon.php';
	global $conn;

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

	//variables
	//time
	//time
	date_default_timezone_set("UTC");
	$time = date("Y-m-d H:i:s", time());
	// get cash
	$qry = "SELECT * FROM oe_wallstreet_user_wallet";
	$result = mysqli_query($conn,$qry);
	while($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
	{

		global $conn;
		$wealth = 0;
		$v1 = $row['username'];
		$tmp = $row['cash'];
    	$wealth = round($tmp,2);
    	$tmp = getInStock($v1);
    	$wealth += round($tmp,2);
    	//insert into table
    	echo $wealth."<br>";
    	$sql = "INSERT INTO oe_wallstreet_leaderboard (username,wealth,lastupdate) VALUES ('$v1','$wealth','$time') ON DUPLICATE KEY UPDATE lastupdate = '$time',wealth='$wealth'";
    	$res = mysqli_query($conn,$sql);
    	if(!$res)
    	{
    		echo $res;
    	}
	}
	

    //get in stock


?>