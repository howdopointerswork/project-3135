
<?php

require_once('alerts.php');
if(session_status() === PHP_SESSION_NONE){

	session_start();
}


	$names = ['', 'Profile', 'Booking', 'Logging', 'Search', 'Monitoring', 'Sign Out', 'Dashboard'];

	echo '<nav>
	<form method="post" action="main.php" style="display: flex; flex-direction: row; border-bottom: solid 2px black;>';

	foreach($names as $name){
	
		echo '<input type="submit" name="action" value="' . $name .  '" style="margin: 0.5em; font-size: 18px; padding: 0.2em;">';
	}

//	echo '<input type="submit" style="margin: 0.5em; font-size: 18px; padding: 0.2em;"  name="action" id="alerts" value="Alerts: ' . $_SESSION['alerts']->getSize() . '">';

	echo '</form></nav>';

//	$_SESSION['alerts']->display();

//	echo '<script> document.getElementById("display").style.visibility = "visible"; </script>';
//	echo '<form method="post" action="main.php">';
	echo '<button style="margin: 0.5em; font-size: 18px; padding: 0.2em;" id="alerts">Alerts: ' . $_SESSION['alerts']->getSize() . '</button>';
	//	echo '</form>'

	echo '<table id="alertTbl" style="visibility: hidden; border: solid 2px black; background-color: white;">';
	$category;
	
	forEach($_SESSION['alerts']->getArray() as $i => $alert){	

	echo '<tr id="row' . $i . '" style="background-color:' . $_SESSION['alerts']->resolveStatus($alert->getStatus()) . '"><td>' . $_SESSION['alerts']->resolveCat($alert->getCategory()) . '</td>';
		echo '<td>' . $alert->getMsg() . '</td>';
		echo '</tr>';
	}
	echo "<tr><td>
		<form method='post' action='main.php'>
		<input type='submit' value='Clear' name='action'></td></tr>
		</form>
		</table>";	
?>

	
<script>
	document.getElementById("alerts").addEventListener('click', function(){

	
		let element = document.getElementById("alertTbl");
		

		if(element.style.visibility == "visible"){

			element.style.visibility = "hidden";

		}else if(element.style.visibility == "hidden"){

			element.style.visibility = "visible";
		}else{
				
			alert('none');
		}
	});

</script>

