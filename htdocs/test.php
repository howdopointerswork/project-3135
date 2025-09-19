<?php
	$fname = isset($_GET["fname"]) ? $_GET["fname"] : "";
	$lname = isset($_GET["lname"]) ? $_GET["lname"] : "";
	$val = isset($_GET["val"]) ? $_GET["val"] : "";		
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
		<script src="test.js"></script>
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

		<!--	<?php if($fname != ""){echo htmlspecialchars($fname);}?> -->

			<form action="test.php" method="get">
			<ul>
				<li><button type="button" id="add" name="add">Add</button></li>
				<li><input type="text" id="val" name="val"></li>
				<li><button type="button" id="sub" name="sub">Sub</button></li>
			</ul>

			
				<input type="submit" id="calc" name="calc" value="Calculate">
			</form>
		</div>

<?php echo "The average is: " . $val?>
	</body>

</html>
