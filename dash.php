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

    </head>

    <body>
	<h1 id="greeting">Hello, <?php echo $_SESSION['current']->getName();?></h1>
	<div id="rss">
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
	</div>

    </body>

</html>
