<?php
	echo "Dashboard";

	

?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Dashboard</title>
		<link rel="stylesheet" href="css/dash.css">

	</head>

	<body>

		<table>
			<tr>
				<td><button id="booking" name="booking"><a href="booking.php">Booking</a></button></td>
				<td><button id="logging" name="logging"><a href="logging.php">Logging</a></button></td>
				<td><button id="search" name="search"><a href="search.php">Search</a></button></td>
			</tr>

			<tr>
			
				<td><button id="monitor" name="monitor"><a href="monitor.php">Monitoring</a></button></td>
				<td><button id="signout" name="signout"><a href="main.php">Sign Out</a></button></td>
				<td><button id="alerts" name="alerts"><a href="alerts.php">Alerts</a></button></td>	
			

			</tr>
		</table>

	</body>

</html>
