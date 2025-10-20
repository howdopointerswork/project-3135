<?php
//	require('user.php')
	require('db.php');
	require('functions.php'); //for testing, can be ignored

	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);




	$action = filter_input(INPUT_POST, 'action');
	if($action == NULL){

		$action = filter_input(INPUT_GET, 'action');
		if($action == NULL){

			$action = 'login';
		}
	}

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

	echo date("Y/m/d H:i:s") . "<br>";

	try{
		
		$db = new PDO($dsn, $user, $pw);
	        
	}catch(PDOException $e){
		
		echo "Error";
	}
//change to switch	
	if($action == 'login'){
		


		include('login.php');
		

	}

	else if($action == 'Submit'){
		
			
		//action can be used to check for signups, logins, etc.

		$query = 'SELECT * FROM user WHERE username = :username';

		$statement = $db->prepare($query);

		$statement->bindValue(':username', $username);

		$statement->execute();

		$name = $statement->fetch();
	
		if(empty($name)){

			echo "<br>Sign Up";
			include("signup.php");
			exit;
			
			//addUser($db, $username, $password);
			
			//testing();
		

		} else{

			
			if(authenticate($db, $username, $password)){

				echo "Hello, " . $username . "<br>";
				session_start();
				$_SESSION['username'] = $username;
				include("dash.php");

				exit;

			}
			else{
				
				echo "<br>Failed to log in";
			}
		}

	}

?>
