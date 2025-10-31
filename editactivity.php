<?php
	$names = ['Calories', 'Sleep', 'Water', 'Exercise', 'Medication'];
echo "<form method='post' action='main.php'>";
echo "<input type='hidden' value=$activity[6] name='actID'>";
for($i=0; $i<5; ++$i){
			echo $names[$i];	
			echo "<input type='text' name='data[]' value=$activity[$i] style='text-align: center; font-size: 18px; display: block;'>";
		//	echo $activity[$i] . "<br>";
	}
	echo "<input type='submit' name='action' value='Update Activity' style='font-size: 18px; margin-top; 1em;'>";
	echo "</form>";

	
	

?>
