<?php
	$names = ['', 'Profile', 'Booking', 'Logging', 'Search', 'Monitoring', 'Sign Out', 'Alerts', 'Dashboard'];

	echo '<nav>
	<form method="post" action="main.php" style="display: flex; flex-direction: row;>';

	foreach($names as $name){
	
		echo '<input type="submit" name="action" value="' . $name .  '" style="margin: 0.5em; font-size: 18px; padding: 0.2em;">';
	}
/*

	<input type="submit" name="action" value="Profile" style="margin: 0.5em; font-size: 18px; padding: 0.2em;">
	<input type="submit" name="action" value="Booking" style="margin: 0.5em; font-size: 18px; padding: 0.2em;">
	<input type="submit" name="action" value="Logging" style="margin: 0.5em; font-size: 18px; padding: 0.2em;">
	<input type="submit" name=action" value="Search" style="margin: 0.5em; font-size: 18px; padding: 0.2em;">
	<input type="submit" name="action" value="Monitoring" style="margin: 0.5em; font-size: 18px;">
	<input type="submit" name="action" value="Sign Out" style="margin: 0.5em; font-size: 18px;">
	<input type="submit" name="action" value="Alerts" style="margin: 0.5em; font-size: 18px;">
*/
	echo '</form></nav>';

	

?>


