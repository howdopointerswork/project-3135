


<!DOCTYPE html>
<html>

	<head>
		<title>Log Activities</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="css/main.css">
	</head>

	<body>
	
		
		<form method='post' action='main.php'>
			<input type='submit' name='action' value='Back'>
		</form>
		
		<h1 id="log_title">Activities</h1>
		
		<?php  

			$activities = array();

			//load here

			if(empty($activities)){

				echo "<p style='text-align: center; font-size: 20px;'>You have no Activities<br></p>";

			}

		?>	
		
		<div id="logging_system" style="display: none;">
			<form method='post' action='main.php'>
		
			<label for="calories">Calorie Intake</label>
			<input type="text" name="calories">
			
			<label for="sleep">Hours of Sleep</label>
			<input type="text" name="sleep">

			<label for="water">Water Intake (mL)</label>
			<input type="text" name="water">

			<input type="submit" name="action" value="Add Activity">



			</form>

			<button id="cancel">Cancel</button>

			</div>
		
		<button id="add" name="add">Log Activity</button>
		
<script>

document.getElementById('add').addEventListener('click', function(){ 
	document.getElementById('logging_system').style.display = ''; 

});

document.getElementById('cancel').addEventListener('click', function(){

	document.getElementById('logging_system').style.display = 'none';

});


</script>

	
	</body>

</html>
