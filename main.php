<?php
//	require('user.php')
	require_once('db.php');
	require('functions.php'); //for testing, can be ignored
	require_once('user.php');
	require_once('alerts.php');
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	if(session_status() === PHP_SESSION_NONE){

		session_start();
	}

	
	if(!isset($_SESSION['date'])){

		$_SESSION['date'] = date('Y-m-d');
	}

	if(!isset($_SESSION['alerts'])){

		$_SESSION['alerts'] = new AlertSystem();
	}

	if(!isset($_SESSION['page'])){

		$_SESSION['page'] = 'main.php';

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



	$dsn = 'mysql:host=127.0.0.1;dbname=health_system_final';
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
			$_SESSION['page'] = 'login.php';
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
				if(isset($result)){
				//	echo "good<br>";
					echo $result[3];
				}
				if($result[3]){

					if(session_status() === PHP_SESSION_NONE){
						session_start();
					}

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
					}else{

						if($_SESSION['activities'][count($_SESSION['activities'])-1] != $_SESSION['date']){

						//	echo 'logging alert<br>';
							$time1 = new DateTime($_SESSION['date']);
							$time2 = new DateTime($_SESSION['activities'][count($_SESSION['activities'])-1][7]);		
							//use constructor
							$diff = $time1->diff($time2);
							$alert = new Alert();
							$alert->setCategory(2);
							$alert->setStatus(3);
							$alert->setCode(0);
							$alert->setMsg("You haven't logged an activity in " . $diff->days . " day(s)!");
							$_SESSION['alerts']->addAlert($alert);

							$bookings = getBookings($db, $_SESSION['current']->getID());	
							$recent = new DateTime($bookings[count($bookings)-1]['booking_date']);
							$diff2 = $time1->diff($recent);
							$when = '';
							$tense = 'in';

							if($recent < $time1){
								$when = 'ago';
								$tense = 'was';
							}else{
								echo 'nope';
							}
							

							$alert2 = new Alert();
							$alert2->setCategory(3);
							$alert2->setStatus(1);
							$alert2->setCode(0);
							$alert2->setMsg("Appointment Notice: Appointment " . $tense . " " . $diff2->days . " day(s) " . $when);
							$_SESSION['alerts']->addAlert($alert2);


						}
					}
					$_SESSION['page'] = 'dash.php';
					include("dash.php");

					exit;

				}
				else{
				
					echo "<br>Failed to log in";
				}
			}


		case 'Booking':
			$_SESSION['page'] = 'booking.php';
			include("booking.php");
			exit;

		case 'Logging':
		//	$_SESSION['activities'] = getActivities($db, $_SESSION['current']->getID());
			
			$_SESSION['page'] = 'logging.php';
			include("logging.php");
			exit;
		
		case 'Search':

			$_SESSION['page'] = 'search.php';
			include("search.php");
			exit;

		case 'Monitoring':
			
			$_SESSION['page'] = 'monitor.php';
			include("monitor.php");
			exit;

		case 'Sign Out':

			session_destroy();
			include('login.php');
			break;

		case 'Clear':
			$_SESSION['alerts']->clearAlerts();
			$current = $_SESSION['page'];
			include($current);
			break;	


		case 'Dashboard':
			$_SESSION['page'] = 'dash.php';
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

			addActivity($db, $values, $_SESSION['date']);
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
			$_SESSION['page'] = 'profile.php';
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
			
	

		case 'Monitor': //yes this is confusing with the above case, will change eventually. Above is for Monitoring page, this is to monitor activity

			$toMonitor = filter_input(INPUT_POST, 'toMonitor');
			$threshold = filter_input(INPUT_POST, 'threshold') ?? 0;

			$selected;

			

			switch(strtolower($toMonitor)){

			case 'bmi':
				$selected = 0;
				break;

			case 'calories':
				$selected = 1;
				break;

			case 'sleep':
				$selected = 2;
				break;

			case 'water':
				$selected = 3;
				break;

			case 'exercise':
				$selected = 4;
				break;

			case 'medication':
				$selected = 5;
				break;
			}


			if($threshold === NULL){
				echo 'null';
			}

			
		
			setMonitor($db, $_SESSION['current']->getID(), $selected, $threshold, $_SESSION['date']); 
			echo "Monitoring set successfully";       
			include('monitor.php');

		case 'Add Booking':
        $date   = filter_input(INPUT_POST, 'booking_date');
        $desc   = filter_input(INPUT_POST, 'description');
        $userId = filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT);

        if ($date && $userId !== false) {
            addBooking($db, $userId, $date, $desc);
        }
        include('booking.php');
        exit;

    case 'Delete':
        $bid = filter_input(INPUT_POST, 'bookingID', FILTER_VALIDATE_INT);
        if ($bid) {
            deleteBooking($db, $bid);
        }
        include('booking.php');
        exit;

    case 'Edit':
        $bid = filter_input(INPUT_POST, 'bookingID', FILTER_VALIDATE_INT);
        if ($bid) {
            $_SESSION['editing_booking'] = getBooking($db, $bid);
            include('editbooking.php');
        } else {
            include('booking.php');
        }
        exit;

    case 'Update Booking':
        $bid  = filter_input(INPUT_POST, 'bookingID', FILTER_VALIDATE_INT);
        $date = filter_input(INPUT_POST, 'booking_date');
        $desc = filter_input(INPUT_POST, 'description');

        if ($bid && $date) {
            updateBooking($db, $bid, $date, $desc);
        }
        unset($_SESSION['editing_booking']);
        include('booking.php');
        exit;

    /* -------------------- FALLBACK -------------------- */
    default:
        include('dash.php');
        exit;
}

	

			

			

	
?>
