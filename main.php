<?php
//	require('user.php')
	require_once('db.php');
	require_once('user.php');
	require_once('alerts.php');
	
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);


	function loopCheck($arr, $query){
	
		if(!empty($arr) && $query != null){
			echo '<table>';
			echo '<th style="colspan:' . count($arr) . '">Users</th>';	
			
			foreach($arr as $element){
						
				$contains = array_filter($element, function($item){
			
				return strpos($item, $query) !== false;	
				});
				
				if(!empty($contains)){
					echo '<tr>';
					foreach($element as $e){
						echo '<td>' . $e . '</td>';
					}
					echo '</tr>';
				}		
			}

			echo '</table>';
			}
	}
	

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
		$GLOBALS['db'] = $db;
	//	$_SESSION['db'] = $db;
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
		

	

		case 'Log In':
		
			
			//action can be used to check for signups, logins, etc.

			$query = 'SELECT * FROM user WHERE username = :username';

			$statement = $db->prepare($query);

			$statement->bindValue(':username', $username);

			$statement->execute();

			$name = $statement->fetch();
	
			if(empty($name)){
			
				include('signup.php');
				exit;
			
				//addUser($db, $username, $password);	

			} else{
				
				$result = authenticate($db, $username, $password);


				if(is_array($result) && end($result)){

					if(session_status() === PHP_SESSION_NONE){
						session_start();
					}


					$_SESSION['current'] = new User($result[0], $result[1], $result[3], $result[4], $result[5], $result[6], $result[7], $result[9]);

					$_SESSION['activities'] = getActivities($db, $_SESSION['current']->getID());
					$_SESSION['bookings'] = getBookings($db, $_SESSION['current']->getID());

					$apts = getAppointments($db, $_SESSION['current']->getID());

					if(!empty($apts)){

						foreach($apts as $apt){

							if(end($apt) > $_SESSION['date']){

								echo 'upcoming!';
								$apt_alert = new Alert();
							}
						}
					}	
				
					if(!empty($_SESSION['activities'])){
						
					

						if($_SESSION['activities'][count($_SESSION['activities'])-1] != $_SESSION['date']){

						//	echo 'logging alert<br>';
							$time1 = new DateTime($_SESSION['date']);
							$time2 = new DateTime($_SESSION['activities'][count($_SESSION['activities'])-1][7]);		
							//use constructor
							$diff = $time1->diff($time2);
							$alert = new Alert(2, 3, '', 0);
							$alert->setMsg("You haven't logged an activity in " . $diff->days . " day(s)!");

							

							$_SESSION['alerts']->addAlert($alert);

							$bookings = getBookings($db, $_SESSION['current']->getID());	
							$recent = new DateTime($bookings[count($bookings)-1]['booking_date']);
							$diff2 = $time1->diff($recent);

							if($recent >= $time1){

								$alert2 = new Alert(3, 1, '', 0);
								$alert2->setMsg("Appointment Notice: Appointment in " . $diff2->days . " day(s) ");
								$_SESSION['alerts']->addAlert($alert2);
							}


						}
					}
					
					
					$_SESSION['page'] = 'dash.php';
					include("dash.php");

					exit;

				}
				else{
				
					include('login.php');
					exit;

				}
			}
		

		case 'Sign Up':
			include ('signup.php');
			exit;

		case 'Add Account':

			$field1 = filter_input(INPUT_POST, 'Username');
			$field2 = filter_input(INPUT_POST, 'Password');
			$field3 = filter_input(INPUT_POST, 'Age');
			$field4 = filter_input(INPUT_POST, 'Height');
			$field5 = filter_input(INPUT_POST, 'Weight');
			$field6 = filter_input(INPUT_POST, 'Gender');

			addUser($db, $field1, $field2, $field3, $field4, $field5, $field6, '', $_SESSION['date'], 0);

			
				
			$result = authenticate($db, $field1, $field2);

				if(is_array($result) && end($result)){

					if(session_status() === PHP_SESSION_NONE){
						session_start();
					}


					$_SESSION['current'] = new User($result[0], $result[1], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8]);
					include('dash.php');
					exit;
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

		//	$query = filter_input(INPUT_POST, 'query') ?? "pp";
			$users = getUsers($db);
			$profs = getProfs($db);
			$acts = getActivities($db, $_SESSION['current']->getID());
			$books = getBookings($db, $_SESSION['current']->getID());
			$apts = getAppointments($db, $_SESSION['current']->getID());
			
		//	if($query != NULL){
			echo '<table>';
		//	echo '<th style="colspan:10">Header</th>';	
			$arrays = [$users, $profs, $acts, $books, $apts];
			$i = 0;
			$size = 0;
			$headers = ['Users', 'Doctors', 'Activities', 'Bookings', 'Appointments'];	
			
			foreach($arrays as $array){
				
	
				if(!empty($array)){
					echo '<table style="margin: 0 auto; text-align: center">';
					if($headers[$i] == 'Users'){
						$size = count($array)-1;
					}else{
						$size = count($array);
					}
				echo '<th style="colspan:' . $size . '; font-size: 24px">' . $headers[$i++] . '</th>';	
				//loop here for headers	
				$query = filter_input(INPUT_POST, 'query') ?? "";
				foreach($array as $index => $element){
					$contains = array_filter($element, function($item){
						global $query;
						
					return strpos($item, $query) !== false;	
					});
					
					if(!empty($contains)){
						echo '<tr>';
						$prev = '';
						$prevKey = '';
						$j = 0;	
						$keys = array_keys($element);
						$pass = true;
					//	echo var_dump($element);

						foreach($element as $ind => $e){											if($headers[$i-1] == 'Users' && $e == $element[2]){
						}else{	
							//loop
							$offset = 0;
							$pos = 0;
							$positions = [];
							

							if(str_contains($e, $query)){

								$e = str_replace($query, '<span style="background-color: yellow">' . $query . '</span>', $e); 
							}
							
							
							//no passwords shown
							if($headers[$i-1] == 'Users' && $e == $element[2]){
																										     $pass = true;
							}

							if($e !== 0 && $e == null){
								
								$e = ' ';
							}	
											

							
								if(!$pass && $e != $prev){
								
									echo '<td style="border: solid 2px black; font-size: 18px; padding: 1em">' . $e . '</td>';
									$pass = true;	
								}else{
									$pass = false;
								}
							}	
					
						}
						echo '</tr>';
					}	
				}

				echo '</table>';
				}
			
		}
			
		exit;

		case 'Monitoring':
			
			$_SESSION['page'] = 'monitor.php';
			include("monitor.php");
			exit;

		case 'Sign Out':

			session_destroy();
			include('login.php');
			break;

		case 'Delete Alert':
			
			//	$_SESSION['alerts']->clearAlerts();
			$deleteCat = filter_input(INPUT_POST,'Category');
			$deleteCode = filter_input(INPUT_POST, 'Code');
			
			$_SESSION['alerts']->destroyAlert($deleteCat, $deleteCode);
			echo 'size: ' . count($_SESSION['alerts']->getArray());
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


    case 'Confirm Appointment':

	$uid = filter_input(INPUT_POST, 'user');
	$bid = filter_input(INPUT_POST, 'booking');
	$pid = filter_input(INPUT_POST, 'prof');
	$date = filter_input(INPUT_POST, 'app_date');
	$time = filter_input(INPUT_POST, 'app_time');
	

	$booking = explode('|', $bid);

	if($uid === $booking[1]){

		//check prof availability?
		addAppointment($db, $uid, $pid, $booking[0], $date, $time, $_SESSION['date']); 

	}else{
		echo '<span style="color: red">Error: User ID mismatch</span>';
	}	
		
	    //include('booking.php');	    

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

case 'Stress Tracker':
    include('stress.php');
    exit;

case 'Stress Tracker':
    include('stress.php');
    exit;

case 'Save Stress Log':
    $userId = $_SESSION['current']->getID();
    $level = filter_input(INPUT_POST, 'stress_level', FILTER_VALIDATE_INT);
    $notes = trim(filter_input(INPUT_POST, 'notes'));

    if ($level === false || $level < 1 || $level > 10) {
        $alert = new Alert(2, 3, 'Stress level must be between 1 and 10.', 999);
        $_SESSION['alerts']->addAlert($alert);
    } else {
        addStressLevel($db, $userId, date('Y-m-d'), $level, $notes);
    }
    include('stress.php');
    exit;

case 'Compare Selected Days':
    $selected = filter_input(INPUT_POST, 'selected', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY) ?? [];
    if (count($selected) < 2) {
        $alert = new Alert(2, 3, 'Please select at least two days to compare.', 998);
        $_SESSION['alerts']->addAlert($alert);
    } else {
        $_SESSION['compare_stress'] = $selected;
    }
    include('stress.php');
    exit;

case 'Clear Comparison':
    unset($_SESSION['compare_stress']);
    include('stress.php');
    exit;

    /* -------------------- FALLBACK -------------------- */
    default:
        include('dash.php');
        exit;
}

	

			

			

	
?>
