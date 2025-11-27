<?php
	$names = ['Calories', 'Sleep', 'Water', 'Exercise', 'Medication'];
echo "<form method='post' action='main.php' style='display: flex; flex-direction: column; align-items: center'>";
echo "<input type='hidden' value=$activity[0] name='logID'>";
for($i=0; $i<5; ++$i){
			echo $names[$i];	
			echo "<input type='text' name='data[]' style='text-align: center; font-size: 18px;'>";
		//	echo $activity[$i] . "<br>";
	}
	echo "<input type='submit' name='action' value='Update Activity' style='font-size: 18px; margin-top; 1em;'>";
	echo "</form>";

	
	

?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Activity</title>
	<link rel="stylesheet" href="css/dash.css">
</head>	

</html>
