<?php

if(session_status() === PHP_SESSION_NONE){
	
	session_start();

}

include ('nav.php');

?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Dashboard</title>
        <link rel="stylesheet" href="css/dash.css">

    </head>

    <body>
	<h1>Hello, <?php echo $_SESSION['current']->getName();?></h1>
	
	<form method='post' action='main.php'>
	<input id='prof' type='submit' name='action' value='Profile'>
	</form>

	<table>
	    <tr>
		<form method='post' action='main.php'>
		<td><input type='submit' value='Booking' name='action'></td>
		</form>
		
		<form method='post' action='main.php'>       
		<td><input type='submit' value='Logging' name='action'></td>
		</form>

		<form method='post' action='main.php'>
		<td><input type='submit' value='Search' name='action'></td>
		</form>
            </tr>

            <tr>
            	<form method='post' action='main.php'>
		<td><input type='submit' value='Monitoring' name='action'></td>
		</form>

		<form method='post' action='main.php'>
		<td><input type='submit' value='Sign Out' name='action'></td>
		</form>
		
		<form method='post' action='main.php'>
                <td><input type='submit' value='Alerts' name='action'></td>
            	</form>

            </tr>
        </table>

    </body>

</html>
