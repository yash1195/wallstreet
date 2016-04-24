<?php
include '../dbcon.php';
include '../log.php';
global $conn;
$val202 = array('status'=>'202');
$val303 = array('status'=>'303');
$val304 = array('status'=>'304');
$val305 = array('status'=>'305');
$val306 = array('status'=>'306');
$val307 = array('status'=>'307');
$val308 = array('status'=>'308');
$val309 = array('status'=>'309');
$val310 = array('status'=>'310');

//session variables
$username = $_SESSION['username'];
//testing
$username = "zeko";
$stockname = $_REQUEST['stockname'];
$stockvol = floor($_REQUEST['stockvol']);
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

// BUY SELL LOGS
function sanitizeStockName($stock)
{
	global $conn;
	$qry = "SELECT * FROM oe_wallstreet_allowed_stocks WHERE stockname = '$stock'";
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
	$qry = "SELECT * FROM oe_wallstreet_stockmarket WHERE stockname = '$stock'";
	$result = mysqli_query($conn,$qry);
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	return $row['l'];
}

	
	if(sanitizeStockName($stockname) && is_numeric($stockvol) && $username != '')
	{
		if($stockval = floatval(getStockValue($stockname)))
		{
			$cost = $stockval * $stockvol;
			$qry = "SELECT * FROM oe_wallstreet_user_stocks WHERE username = '$username' AND stockname = '$stockname'";
			$result = mysqli_query($conn,$qry);
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			if($row)
			{
				if($row['stockvol'] < $stockvol)
				{
					echo json_encode($val309);
					exit();
				}
				else
				{
					// start buying transaction

					//Cancel auto commit option in the database
					mysqli_autocommit($conn, FALSE);
					$qry1 = "UPDATE oe_wallstreet_user_stocks SET stockvol = stockvol - '$stockvol' WHERE username = '$username' AND stockname = '$stockname' ";	
					if($result = mysqli_query($conn,$qry1))
					{
						// subtract money
						$qry2 = "UPDATE oe_wallstreet_user_wallet SET cash = (cash + $cost) WHERE username = '$username'";	
						if($result = mysqli_query($conn,$qry2))
						{
							//success
							mysqli_commit($conn);
							echo json_encode($val307);
							// log
							sold($username,$stockvol,$stockval);
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
				echo json_encode($val308);
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

// else
// {
// 	echo json_encode($val202);
// 	exit();
// }
?>