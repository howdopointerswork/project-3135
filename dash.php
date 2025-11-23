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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

	<div class="dashboard-stats-container">
		<div class="stat-card">
			<i class="fas fa-running stat-icon"></i>
			<h3 class="stat-title">Activities</h3>
			<div class="stat-number"><?php if(!empty($_SESSION['activities'])){ echo count($_SESSION['activities']); } else { echo '0'; } ?></div>
		</div>

		<div class="stat-card">
			<i class="fas fa-calendar-alt stat-icon"></i>
			<h3 class="stat-title">Bookings</h3>
			<div class="stat-number"><?php if(!empty($_SESSION['bookings'])){ echo count($_SESSION['bookings']); } else { echo '0'; } ?></div>
		</div>

		<div class="stat-card">
			<i class="fas fa-user-md stat-icon"></i>
			<h3 class="stat-title">Appointments</h3>
			<div class="stat-number"><?php if(!empty(getAppointments($db, $_SESSION['current']->getID()))){ echo count(getAppointments($db, $_SESSION['current']->getID())); } else { echo '0'; } ?></div>
		</div>
	</div>
	
	<div class="news-section">
		<h1 class="news-title"><i class="fas fa-newspaper"></i> Latest News</h1>	
		<div class="news-container">
	<?php 
		$feed = simplexml_load_file("https://feeds.npr.org/1128/rss.xml");

		echo "<h2 class='news-source'>" . $feed->channel->title . "</h2>";
		echo "<div class='news-grid'>";

		$count = 0;
		foreach($feed->channel->item as $item){
			if($count >= 6) break; // Limit to 6 news items
			
			echo "<div class='news-card'>";
			echo "<div class='news-card-content'>";
			echo "<h3 class='news-card-title'><a href='{$item->link}' target='_blank'>" . $item->title . "</a></h3>";
			if(!empty($item->description)){
				echo "<p class='news-card-description'>" . strip_tags($item->description) . "</p>";
			}
			echo "<div class='news-card-footer'>";
			echo "<i class='fas fa-external-link-alt'></i> Read more";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			$count++;
		}
		echo "</div>";
?>
		</div>
	</div>

    </body>

</html>
