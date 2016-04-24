<?php
	include 'dbcon.php';
	
	function bought($user,$stock,$stockp)
	{
		global $conn;
		$qry = "INSERT INTO oe_wallstreet_logs (username,stock_bought,stockprice) VALUES('$user','$stock','$stockp')";
		$result = mysqli_query($conn,$qry);
	}
	function sold($user,$stock,$stockp)
	{
		global $conn;
		$qry = "INSERT INTO oe_wallstreet_logs (username,stock_sold,stockprice) VALUES('$user','$stock','$stockp')";
		$result = mysqli_query($conn,$qry);
	}
?>