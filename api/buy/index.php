

<?php
include '../dbcon.php';
include '../log.php';
global $conn;
	//SESSION VARIABLES
	$username = $_SESSION['username'];
	//testing
	$username = "zeko";
	$stockname = $_REQUEST['stockname'];
	$stockvol = floor($_REQUEST['stockvol']);
$val202 = array('status'=>'202');
$val303 = array('status'=>'303');
$val304 = array('status'=>'304');
$val305 = array('status'=>'305');
$val306 = array('status'=>'306');
$val307 = array('status'=>'307');
$val310 = array('status'=>'310');
//SANITIZE FUNCTION
// function sanitize($a)
// {
// 	if($a == ""){
// 		return false;
// 	}
// 	else if(!preg_match("/^[a-zA-Z ]*$/",$a)){ // check name
//   		return false;
// 		}
// 	else{
// 		return true;
// 	}
// }
function sanitizeStockName($stock)
{
	global $conn;
	$qry = "SELECT * FROM oe_wallstreet_allowed_stocks WHERE stockname = '$stock' LIMIT 1";
	$result = mysqli_query($conn,$qry);
	if(mysqli_num_rows($result) > 0)
	{
		return true;
	}
	else
	{
		return false;
	}
}
function getStockValue($stock)
{
	global $conn;
	$qry = "SELECT * FROM oe_wallstreet_stockmarket WHERE stockname = '$stock' LIMIT 1";
	$result = mysqli_query($conn,$qry);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	return $row['l'];
}


	
	if(sanitizeStockName($stockname) && is_numeric($stockvol) && $username != '')
	{

		if($stockval = floatval(getStockValue($stockname)))
		{
			$cost = $stockval * $stockvol;
			$qry = "SELECT * FROM oe_wallstreet_user_wallet WHERE username = '$username'";
			$result = mysqli_query($conn,$qry);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			if($row)
			{
				if($row['cash'] < $cost)
				{
					echo json_encode($val305);
					exit();
				}
				else
				{
					// start buying transaction
					//Cancel auto commit option in the database
					mysqli_autocommit($conn, FALSE);
					$qry1 = "INSERT INTO oe_wallstreet_user_stocks (username , stockname , stockvol) VALUES ('$username','$stockname','$stockvol') ON DUPLICATE KEY UPDATE stockvol = stockvol +'$stockvol'";	
					if($result = mysqli_query($conn,$qry1))
					{
						// subtract money
						$qry2 = "UPDATE oe_wallstreet_user_wallet SET cash = (cash - $cost) WHERE username = '$username'";	
						if($result = mysqli_query($conn,$qry2))
						{
							//success
							mysqli_commit($conn);
							echo json_encode($val307);
							bought($username,$stockvol,$stockval);
						}
						else
						{
							mysqli_rollback($conn);
							echo json_encode($val306);
						}
					}
					else
					{
						echo json_encode($val306);
					}
					mysqli_autocommit($conn,TRUE);
					mysqli_close($conn);
				}
			}
			else
			{
				echo json_encode($val303);
				exit();
			}
		}
		else
		{	
			echo json_encode($val310);
			exit();
		}
	}
	else
	{
		echo json_encode($val304);
		exit();
	}

?>