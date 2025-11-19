<?php 
	require_once('db.php');
	require_once('user.php');
	require_once('alerts.php');

	if(session_status() === PHP_SESSION_NONE){

		session_start();
	}

	include('nav.php');	

	
?>

<head>
	<title>Monitoring</title>
	<meta charset='utf-8'>
	<link rel="stylesheet" href="css/main.css">
</head>

<body>
	<h1 style="text-align: center;">Monitoring</h1>
	
	<div id="monitor_body" style="text-align: center">
	
<?php


			$mod = date('Y-m-d', strtotime('-30 days'));
			$cmp;
	


	

			$results = getActivitiesByDate($db, $_SESSION['current']->getID(), $mod, $_SESSION['date']);

			$monitor = getMonitor($db, $_SESSION['current']->getID());

			if($monitor){
			
			echo "Date1: " . $_SESSION['date'] . "<br>";
			echo "Date2: $monitor[4]<br>";

			if($monitor[4] != $_SESSION['date']){

				$cmp = true;
			}else{
				$cmp = false;
			}
			

		//	echo "Today is: " . $_SESSION['date'] . "<br>"; 
			$arr = ['BMI', 'Calories', 'Sleep', 'Water', 'Exercise', 'Medication'];

			echo '<form method="post" action="main.php">';		

			echo '<span style="font-size: 24px;">Attribute to Monitor:</span> 
				<select name="toMonitor" style="font-size: 36px; margin: 0 auto;">';
			
			foreach($arr as $item){

				echo "<option value=$item>$item</option>";
			}
	
			

			echo '</select>';


			echo "<br>";

			echo '<input type="text" name="threshold">';


			echo "<br>";

			echo '<input type="submit" name="action" value="Monitor">';

			echo '</form>';


			$avg = 0;
			$status = 0;


		//	echo "<table>";

			if($monitor){
				foreach($results as $result){
					if($monitor[0] > 0){	
						
						$avg += $result[$monitor[0]+1];
					}
				}
				//check for 0
				$avg /= count($results);
				$dev = $monitor[1]/10; //change to sd?
				$cat = $monitor[0];
			
				if($monitor[0] > 0){
					echo "<p style='font-size: 24px'><br>Currently Monitoring: " . $arr[$cat] . "<br></p>"; 
					echo "<span id='avg'>Current Average: $avg</span>";
				}
			}else{

				echo "Nothing set";
			}

			if(isset($cat) && $cat === 0){

				echo '<br>bmi';

				$height = pow($_SESSION['current']->getHt(),2);
				$bmi = ($_SESSION['current']->getWt()/$height) * 703;

				echo "<br><p id='bmi'>" . number_format($bmi,2) . "</p>";

				$color = '';
				
				
				$getBMI = '<script>document.getElementById("bmi").style.color';
				$closing = '</script>';

				$code;

				
				if($bmi > 18.5 && $bmi < 24.9){

					$color = "'green'";
					
				}else{

					$color = "'red'";
					
				}

				

				$display = $getBMI . "=" . $color . ";" . $closing;
				echo $display . "<br>";


			}else{

			if((isset($avg) && isset($monitor)) && (number_format($avg,0) != number_format($monitor[3],0) || !isset($monitor[3]))){

				
				//check if second cond is true, if so, send alert
				//	if(number_format($avg,0) ! number
				
				if($monitor[0] > 0){
					
					updateScore($db, $_SESSION['current']->getID(), $avg);
				}
					

			}//else{
				echo '<script> document.getElementById("avg").style.fontSize = "24px";</script>';
				$byID = '<script> document.getElementById("avg").style';
				$close = '</script>';
				
				
				
			//	echo "Average: $avg";
				if(compareScore($db, $_SESSION['current']->getID(), $avg)){
					
					if($avg >= $monitor[1] && $avg < ($monitor[1]+($dev*2))){
						$code=2;
						$status = 1;
						echo "$byID.color='green';$close";
						//	echo "<td>Status</td>";
						echo "<p style='color: green'><br>Normal<br></p>"; 
					}else if($avg >= ($monitor[1]+($dev*2)) && $avg < ($monitor[1] + ($dev*4))){
						$code = 4;
						$status = 2;
						echo "$byID.color='yellow';$close";
					//	echo "<td>Status</td>";
						echo "<p style='color: yellow'><br>Moderate</p>";
					}else{
						$code = 6;
						$status = 3;
						echo "$byID.color='red';$close";
					//	echo "<td>Status</td>";
						echo "<p style='color: red'><br>Abnormal</p>";
					}

				//	echo "</tr>";


				}else{

					
					
					if($avg <= $monitor[1] && $avg >= ($monitor[1]-($dev*2))){
						$code = 1;
						$status = 1; 
						echo "$byID.color='green';$close"; 
						echo "<p style='color: green'><br>Normal<br></p>"; 
					}else if($avg < ($monitor[1]-($dev*2)) && $avg >= ($monitor[1] - ($dev*4))){
						$code = 3;
						$status = 2;
						echo "$byID.color='yellow';$close";
						echo "<p style='color: yellow'><br>Moderate</p>";
					}else{
						$code = 5;
						$status = 3;
						echo "$byID.color='red';$close";
						echo "<p style='color: red'><br>Abnormal</p>";
					}
						
					

				}


					
			//}	


			
				echo "<p style='font-size: 24px'><br>Threshold: $monitor[1]<br></p>";

				//alert here

				
			if($cmp && !$_SESSION['alerts']->findCat(1)){

				$alert = new Alert();
				$alert->setCategory(1);
				$alert->setCode($code);
				$alert->setStatus($status);
				$_SESSION['alerts']->alert_monitor($alert, $arr[$cat]);

				echo 'Alert';
				echo "<br>Size: " . $_SESSION['alerts']->getSize();
			//	echo "Message: " . $AS->getAlert(0)->getMsg() . "<br>";
			}else{
				echo "No alert";
			}
				
			}
			}else{
				echo "You have nothing to monitor - please log activities or book an appointment";
			}
		?>	
			
	</div>

</body>
