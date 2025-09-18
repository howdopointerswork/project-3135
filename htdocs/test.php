<?php
	$fname = isset($_GET["fname"]) ? $_GET["fname"] : "";
	$lname = isset($_GET["lname"]) ? $_GET["lname"] : "";
	
/*	if($fname != ""){
		echo "Hello, " . htmlspecialchars($fname) . " " . htmlspecialchars($lname) . "!<br>";
	}
	else{

		echo "Hello!";
	}*/

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<!-- css here -->
		<link rel="stylesheet" href="test.css">
	</head>
	
	<body>
		<h1>PHP Test</h1>
		
		<div id="sbmt">
			<form action="test.php" method="get">
				<label for="fname">First Name:</label>
				<input type="text" id="fname" name="fname">
				<label for="lname">Last Name:</label>
				<input type="text" id="lname" name="lname">
				<input type="submit" value="Submit">
			</form>

			<?php if($fname != ""){echo htmlspecialchars($fname);} ?>
		</div>
	</body>

</html>
