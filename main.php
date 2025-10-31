<?php
//	require('user.php')
	require_once('db.php');
	require('functions.php'); //for testing, can be ignored
	require_once('user.php');
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if(session_status() === PHP_SESSION_NONE){

		session_start();
	}


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



	$dsn = 'mysql:host=127.0.0.1;dbname=health_system';
	$user = 'mgs_user';
	$pw = 'pa55word';

	$u = new User(); //user var


	try{
		
		$db = new PDO($dsn, $user, $pw);
		//echo "Database found<br>";
	        
	}catch(PDOException $e){
		
		//echo "Error";

	}
//change to switch	
	switch($action){
		
		case 'login':

			include('login.php');
			exit;
		

	

		case 'Submit':
		
			
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

				$result = authenticate($db, $username, $password);
				/*if(isset($result)){
				//	echo "good<br>";
					echo $result[3];
				}*/
				if($result[3]){

					session_start();

					$u->setID($result[0]);
					$u->setName($result[1]);
					$u->setAge($result[3]);
					$u->setHt($result[4]);
					$u->setWt($result[5]);
					$u->setGender($result[6]);
					$u->setImg($result[7]);
				//	echo "ID: " . $u->getID();

					$_SESSION['current'] = $u;
					$_SESSION['activities'] = getActivities($db, $_SESSION['current']->getID());
					if(empty($_SESSION['activities'])){
						echo "error";
					}
					include("dash.php");

					exit;

				}
				else{
				
					echo "<br>Failed to log in";
				}
			}


		case 'Booking':

			include("booking.php");
			exit;

		case 'Logging':
		//	$_SESSION['activities'] = getActivities($db, $_SESSION['current']->getID());
			
			include("logging.php");
			exit;
		
		case 'Search':

			include("search.php");
			exit;

		case 'Monitoring':

			include("monitor.php");
			exit;

		case 'Sign Out':

			session_destroy();
			include('login.php');
			break;

		case 'Alerts':

			include('alerts.php');
			exit;

		case 'Dashboard':

			include('dash.php');
			exit;

		case 'Back':

			include('dash.php');
			exit;

		case 'Add Activity':

			$values = [];


			$values[] = filter_input(INPUT_POST, 'calories');
			$values[] = filter_input(INPUT_POST, 'sleep');
			$values[] = filter_input(INPUT_POST, 'water');
			$values[] = filter_input(INPUT_POST, 'exercise');
			$values[] = filter_input(INPUT_POST, 'meds');

			$values[] = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT); 
			/*foreach($values as $index => $val){

				echo "$index: $val <br>";
			}*/

			addActivity($db, $values);
			break;

		case 'Delete Activity':
			
			$actID = filter_input(INPUT_POST, 'actID');
			deleteActivity($db, $actID);
			
			include('logging.php');
			exit;
			
		
		case 'Edit Activity':

			$actID = filter_input(INPUT_POST, 'actID');
			$activity = getActivity($db, $actID);

			include('editactivity.php');
			exit;


	        case 'Update Activity':
			
			$data = filter_input(INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
			$actID = filter_input(INPUT_POST, 'actID');

			updateActivity($db, $data, $actID);

			include('logging.php');
			exit;


		case 'Profile':

			include('profile.php');
			exit;

		case 'Save':
			
			$vals = [];

			$vals[] = filter_input(INPUT_POST, 'Username');
			$vals[] = filter_input(INPUT_POST, 'Age');
			$vals[] = filter_input(INPUT_POST, 'Height');
			$vals[] = filter_input(INPUT_POST, 'Weight');
			$vals[] = filter_input(INPUT_POST, 'Gender');

			foreach($vals as $i => $val){

				if(empty($val)){

					switch($i){
				
						case 0:
							$vals[$i] = $_SESSION['current']->getName();
							break;
						case 1:
							$vals[$i] = $_SESSION['current']->getAge();
							break;
						case 2:
							$vals[$i] = $_SESSION['current']->getHt();
							break;
						case 3:

							$vals[$i] = $_SESSION['current']->getWt();
							break;
						case 4:

							$vals[$i] = $_SESSION['current']->getGender();
							break;

					}
				}
			}
			
			

			$_SESSION['current']->setName($vals[0]);
			$_SESSION['current']->setAge($vals[1]);
			$_SESSION['current']->setHt((float) $vals[2]);
			$_SESSION['current']->setWt((float) $vals[3]);
			$_SESSION['current']->setGender($vals[4]);
			
			updateProfile($db, $_SESSION['current']->getID(), $vals);

			include('profile.php');
			exit;
			

			

	}
?>
