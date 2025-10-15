<?php
	require('db.php');
	require('functions.php'); //for testing, can be ignored
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	$username = filter_input(INPUT_POST, 'username');
	$password = filter_input(INPUT_POST, 'password');
			
/*	if($fname != ""){
		echo "Hello, " . htmlspecialchars($fname) . " " . htmlspecialchars($lname) . "!<br>";
	}
	else{

		echo "Hello!";
	}*/



	$dsn = 'mysql:host=localhost;dbname=health_system';
	$user = 'mgs_user';
	$pw = 'pa55word';

	try{
		
		$db = new PDO($dsn, $user, $pw);
	        echo 'Success!';	

	}catch(PDOException $e){
		
		echo "Error";
	}

	if($_SERVER['REQUEST_METHOD'] === 'POST'){
		
		$action = $_POST['action'] ?? '';
		//action can be used to check for signups, logins, etc.

		$query = 'SELECT * FROM user WHERE username = :username';

		$statement = $db->prepare($query);

		$statement->bindValue(':username', $username);

		$statement->execute();

		$name = $statement->fetch();
	
		if(empty($name)){

			echo "<br>Sign Up";
			header("Location: signup.php");
			exit;
			
			//addUser($db, $username, $password);
			
			//testing();
		

		} else{

			echo "<br>Log In";
			if(authenticate($db, $username, $password)){

				echo "<br>Successfully logged in";
				header("Location: dash.php");
				exit;

			}
			else{
				
				echo "<br>Failed to log in";
			}
		}

	}

?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<!-- css here -->
		<link rel="stylesheet" href="css/main.css">
		<script src="js/main.js"></script>
	</head>
	
	<body>
		<h1>Log In</h1>
		
		<div id="login">
			<form action="test.php" method="post">
				<label for="username">Username:</label>
				<input type="text" id="username" name="username">
				<label for="password">Password:</label>
				<input type="password" id="password" name="password">
				<input type="submit" value="Submit" name="action">
			</form>

		
	</body>

</html>
