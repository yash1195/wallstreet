<?php
	include 'api/dbcon.php';
	global $conn;
	// SESSION VARIABLES
	$username = $_SESSION['username'];
	$username = "zeko";
	$qry = "SELECT * FROM oe_wallstreet_user_wallet WHERE username = '$username'";
	$result = mysqli_query($conn,$qry);
	if(mysqli_num_rows($result) > 0)
	{
		//do nothing
	}
	else
	{
		$qry = "INSERT INTO oe_wallstreet_user_wallet (username) VALUES ('$username')";
		$result = mysqli_query($conn,$qry);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="gotham.css">
	<link rel="stylesheet" href="default.css">
	<script type='text/javascript' src='js/jquery.js'></script>
	<script type='text/javascript' src='js/default.js'></script>
</head>
<body>
	<div id='help'>
		Mah Lyf Mah Rulzz <br>	
		<button id='rules-done'>Okay, got it!</button>
	</div>
	<div id='header'>
	<span class='tt-link'>TechTatva '15</span>
	<span class='event-title'>Wall Street</span>
	</div>
	<div class='container'>
		<div class='tunnel'>
			<div class='block' id='block1'>
				<div class='head'>
					Account
				</div>
				<div class='acc-block'>
					<div>Account Value(USD) : <span>$</span><span class='total'></span></div>
					<div>In Cash : <span>$</span><span class='incash'></span></div>
					<div>In Stocks : <span>$</span><span class='instock'></span></div>
					<div>Leaders : <span class='leaders'></span></div>
					<div>At par : <span class='atpar'></span></div>
					<div>Trailers : <span class='trailers'></span></div>
				</div>
			</div>

			<div class='block' id='block2'>
				<div id='stock-table'>
					<div class='head'>Stocks</div>
					<div class='row'>
						<div class='col dark'>company</div
						><div class='col dark'>change</div
						><div class='col dark'>price</div
						><div class='col dark'>previous close</div
						><div class='col dark'>own</div>
						
					</div>
					<div class='dynamic-table'></div>
				</div>
			</div>

			<div class='block' id='block3'>
				<div cass='head'>
				<br>
					Rules
				</div>
				<div class='rules-text'>
					1. The event will take place for three days: 7 Oct Wednesday, 8 Oct Thursday and 9 Oct Friday. The event will NOT take place on 10 Oct Saturday.
Event Timings are 7 PM to 1:30 PM IST. <br>
2. Online stocks of the NASDAQ and the NYSE Stock Exchange will be maintained and the participants are required to invest in them and make a fortune out of it. Each participant will be provided with 50000 USD virtual money at the starting of the event. He/she can buy/sell shares of the listed companies, according to their choice. <br>

3. All participants must make a minimum number of FIVE transactions during the course of a day’s business, failing to which they will be disqualified. <br>
4. If a participant finds a glitch in the system he is bound to report it to the event organizers immediately. Any participant who takes advantage of a system glitch will be suspended from the event. <br>
5. At the end of the stipulated time, the top 2 persons with the highest amount of credit to his/her name which includes the [ leftover virtual money + (the no. of shares * the present value of each share)] is to be declared the winners. 
Participants are encouraged to find more information about the company using its ticker symbol. (example : AAPL – APPLE)
Any changes/updates will be displayed on the notifications box. 
Decision of the event organizers is final

<br><br>Event Head : Shantanu - 7795166766
				</div>
			</div>
		</div>

	</div>

	<div id='bottom-bar-box'>
		<span class='bottom-bar'>Account</span>
		<span class='bottom-bar'>Stocks</span>
		<span class='bottom-bar'>Leaderboard</span>
	</div>

	<div id='terminal'>
		<span class='username' id='username'>zeko</span><span>@wallstreet:-$ Welcome to wallstreet.</span> <br>
		<span class='terminal-text'></span>
		<!-- <span id='blinker'>|</span> -->
		<input type="text" id='terminal-input'>
	</div>
	<div class="footer">
		<div>TechTatva '15</div>
	</div>
	<div id='hawk'>
		<img src="hawk.png" alt="" id='hawkp'>
	</div>
</body>
</html>