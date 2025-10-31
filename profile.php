<?php

if(session_status() === PHP_SESSION_NONE){
	session_start();
}


include('nav.php');



?>

<script> let showForm = true; let arr = ['Username', 'Age', 'Height', 'Weight', 'Gender', 'Save'];</script>

<!DOCTYPE html>
<html>
<head>
<title>User Profile - <?php echo $_SESSION['current']->getName();?></title>
<meta charset='utf-8'>
<link rel="stylesheet" href="css/profile.css">
</head>


<body>
	<h1>My Profile</h1>

	<div id='prof' style='background-color: #dddddd; width: 75%; text-align: center; margin: 0 auto; font-size: 24px;'> 

		<ul style="list-style-type: none;">
		
		<li><img src=<?php echo 'img/' . $_SESSION['current']->getImg(); ?> width=100px height=100px></li>

<?php
	

	$arr = ['Username' => 'getName', 'Age' => 'getAge', 'Height' => 'getHt', 'Weight' => 'getWt', 'Gender' => 'getGender'];
	
	echo "<form method='post' action='main.php'>";

	foreach($arr as $index => $name){
	
		echo '<li><strong>' . $index . ': </strong>' . $_SESSION['current']->$name();
		echo "<li><input type='text' name=$index id=$index style='visibility: hidden;'></li>";

	}

	echo "<li><input type='submit' name='action' value='Save' id='Save' style='visibility: hidden; font-size: 18px;'></li>";

	echo "<li><input type='submit' name='action' value='Back' style='font-size: 18px;'></li>";

	echo "</form>";
?>
		
		<li><input type='button' value='Edit Profile' id='editCancel' style='font-size: 18px;'>
		</li>

		

		<script>
	document.getElementById('editCancel').addEventListener('click', function() {
		
		if(showForm){
			showForm = false;
			this.value = 'Cancel'
			arr.forEach(function(item){

				document.getElementById(item).style.visibility = 'visible';
			})}else{

			showForm = true;
				this.value = 'Edit Profile';
				arr.forEach(function(item){
					document.getElementById(item).style.visibility = 'hidden';
				})};

	});

		</script>
		</ul>

	</div>
</body>

</html>
