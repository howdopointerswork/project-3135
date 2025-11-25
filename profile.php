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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/dash.css">
<link rel="stylesheet" href="css/profile.css">
</head>


<body>
	<div class="profile-header-container">
		<form method='post' action='main.php' style='margin: 0;'>
			<button type='submit' name='action' value='Back' class='profile-back-btn'><i class="fas fa-arrow-left"></i> Back</button>
		</form>
		<h1 class="profile-title">My Profile</h1>
	</div>

	<div class="profile-card">
		<div class="profile-photo-section">
			<img src="<?php echo 'img/' . $_SESSION['current']->getImg(); ?>" alt="profile picture" class="profile-photo">
			<button type='button' id='editCancel' class="profile-edit-btn"><i class="fas fa-edit"></i> Edit Profile</button>
		</div>

		<div class="profile-info-section">
			<form method='post' action='main.php' class="profile-form">
<?php
	$arr = ['Username' => 'getName', 'Age' => 'getAge', 'Height' => 'getHt', 'Weight' => 'getWt', 'Gender' => 'getGender'];
	
	foreach($arr as $index => $name){
		echo '<div class="profile-field">';
		echo '<label class="profile-label"><i class="fas fa-' . ($index == 'Username' ? 'user' : ($index == 'Age' ? 'calendar' : ($index == 'Height' ? 'ruler-vertical' : ($index == 'Weight' ? 'weight' : 'venus-mars')))) . '"></i> ' . $index . ':</label>';
		echo '<span class="profile-value" id="display-' . $index . '">' . $_SESSION['current']->$name() . '</span>';
		echo '<input type="text" name="' . $index . '" id="' . $index . '" class="profile-input" style="display: none;" placeholder="Enter ' . $index . '">';
		echo '</div>';
	}
?>
				<div class="profile-actions">
					<input type='submit' name='action' value='Save' id='Save' class='profile-btn profile-btn-save' style='display: none;'>
				</div>
			</form>
		</div>
	</div>

	
	<script>
	document.getElementById('editCancel').addEventListener('click', function() {
		if(showForm){
			showForm = false;
			this.innerHTML = '<i class="fas fa-times"></i> Cancel';
			this.classList.add('cancel-mode');
			arr.forEach(function(item){
				const input = document.getElementById(item);
				const display = document.getElementById('display-' + item);
				if(input) input.style.display = 'block';
				if(display) display.style.display = 'none';
			});
			document.getElementById('Save').style.display = 'inline-block';
		}else{
			showForm = true;
			this.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
			this.classList.remove('cancel-mode');
			arr.forEach(function(item){
				const input = document.getElementById(item);
				const display = document.getElementById('display-' + item);
				if(input) input.style.display = 'none';
				if(display) display.style.display = 'inline';
			});
			document.getElementById('Save').style.display = 'none';
		}

	});
	</script>
</body>

</html>
