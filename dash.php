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
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/dash.css">

    </head>
<?php 
 
$date = new DateTime($_SESSION['date']); 
$newdate = $date->modify('-1 day'); 
?>
    <body>
	<h1 id="greeting">Hello, <?php echo $_SESSION['current']->getName();?></h1>
	<h2 style="text-align: center"><?php echo $newdate->format("Y-m-d") . " " . date("H:i:s", time()-28800); ?></h2>

	<table style="margin: 0 auto; font-size: 24px; text-align: center;">
	<tr>
		<th style="font-size: 48px; padding: 2em;">Activities</th>
		<th style="font-size: 48px; padding: 2em;">Bookings</th>
		<th style="font-size: 48px; padding: 2em;">Appointments</th>
	</tr>

	<tr>
	<td style="border: solid 2px black; font-size: 32px;"><?php if(!empty($_SESSION['activities'])){ echo count($_SESSION['activities']); }?></td>
	<td style="border: solid 2px black; font-size: 32px;"><?php if(!empty($_SESSION['bookings'])){ echo count($_SESSION['bookings']); }?></td>
	<td style="border: solid 2px black; font-size: 32px;"><?php if(!empty(getAppointments($db, $_SESSION['current']->getID()))){ echo count(getAppointments($db, $_SESSION['current']->getID())); } ?></td>
	</tr>
	</table>
	<h1 style="text-align: center;">News</h1>	
	<section id="rss" style="margin-top: 2em;">
	<?php 
		$feed = simplexml_load_file("https://feeds.npr.org/1128/rss.xml");

		echo "<h2 style='text-align: center;>" . $feed->channel->title . "</h2>";
		echo "<ul style='list-style-type: none;'>";

		foreach($feed->channel->item as $item){

			echo "<li style='
				text-align: center; 
				margin: 2em;
				'>";

			echo "<a href='{$item->link}' style='text-decoration: none;'>" . $item->title . "</a>";
			echo "</li>";
		}
		echo "</ul>";
?>
	</section>

    </body>

</html>
