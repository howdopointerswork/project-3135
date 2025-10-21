<?php
	require('user.php');
						

	if($_SESSION['username'] != null){

		$u = new User(1, $username);
		echo "<br><span id='welcome'>Hello, " . $u->getName() . "</span><br>" ;
		echo "<span id='time'>" . date('G:i a') . "</span>";
		




	}	

?>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Dashboard</title>
		<link rel="stylesheet" href="css/main.css">
		

	</head>

	<body>

		<table id="menu">
			<tr>	
				<td>
				<form method='post' action='main.php'>
				<input type="submit" class='buttons' name='action' value='Booking'>
				</form>
				</td>

				
				<td>
				<form method='post' action='main.php'>
				<input type='submit' class='buttons' name='action' value='Logging'>
				</form>
				</td>

				<td>
				<form method='post' action='main.php'>
				<input type='submit' class='buttons' name='action' value="Search">
				</form>
				</td>
			</tr>

			<tr>
					
				<td>
				<form method='post' action='main.php'>
				<input type='submit' class='buttons' name='action' value='Monitoring'>
				</form>
				</td>

				<td>
				<form method='post' action-'main.php'>
				<input type='submit' class='buttons' name='action' value='Sign Out'>
				</form>
				</td>

				<td>
				<form method='post' action='main.php'>
				<input type='submit' class='buttons' name='action' value='Alerts'>
				</form>
				</td>	
			

			</tr>
		</table>

	</body>

</html>
