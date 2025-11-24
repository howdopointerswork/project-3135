<?php

    function addUser($db, $username, $password, $age=0, $ht=0, $wt=0, $gen='', $prof='', $ca='', $p=0){

	    $qry = "SELECT * FROM user WHERE username = :username";
	    $stmnt = $db->prepare($qry);

	    $stmnt->bindValue(':username', $username);

	    $stmnt->execute();

	    if(!$stmnt->fetch()){

        	$qry = "INSERT INTO user (username, password, age, height, weight, gender, created_at, profile_img, privilege)
            		VALUES (:username, :password, :age, :height, :weight, :gender, :created_at, :profile_img, :privilege)";
               
        	$stmnt = $db->prepare($qry);

        	$stmnt->bindValue(':username', $username);
		$stmnt->bindValue(':password', $password);
		$stmnt->bindValue(':age', $age);
		$stmnt->bindValue(':height', $ht);
		$stmnt->bindValue(':weight', $wt);
		$stmnt->bindValue(':gender', $gen);
		$stmnt->bindValue(':created_at', $ca);
		$stmnt->bindValue(':profile_img', $prof);
		$stmnt->bindValue(':privilege', $p);


		$stmnt->execute();
	}

    }


    function getUser($db, $id){
	
	    $qry = "SELECT * FROM user WHERE user_id = :id";

	    $stmnt = $db->prepare($qry);
	    $stmnt->bindValue(':id', $id);
	    $stmnt->execute();

	    return $stmnt->fetch();
	
    } 


    function getUsers($db){
	
	    $qry = "SELECT * FROM user";

	    $stmnt = $db->prepare($qry);

	    $stmnt->execute();

	    return $stmnt->fetchAll(PDO::FETCH_BOTH);
    }


    function authenticate($db, $username, $password){

        $qry = "SELECT * FROM user
            WHERE username = :username;";
        
        $stmnt = $db->prepare($qry);

        $stmnt->bindValue(':username', $username);

        $stmnt->execute();

	$result = $stmnt->fetch();
        

        //check if empty first
    
        if(!empty($result[2]) && $result[2] == $password){
    	    
            $result[] = true;
            return $result;
        }
        else{

        
            $result = false;
            return $result;
        }

    }


    function addActivity($db, $values, $date){

        $qry = "INSERT INTO logging (calories, sleep, water, exercise, meds, userid, log_date) VALUES(:val0, :val1, :val2, :val3, :val4, :val5, :val6)";

        echo $values[4];

        $stmnt = $db->prepare($qry);

        $stmnt->bindValue(":val0", (int) $values[0]);
        $stmnt->bindValue(":val1", $values[1]);
        $stmnt->bindValue(":val2", $values[2]);
        $stmnt->bindValue(":val3", $values[3]);

        if($values[4] == "on"){

            $stmnt->bindValue(":val4", 1);
        }else{

            $stmnt->bindValue(":val4", 0);
        }

	$stmnt->bindValue(":val5", $values[5]);
	$stmnt->bindValue(":val6", $date);

        $stmnt->execute();

        include('logging.php');

    }


    function getActivities($db, $id){

        
        $qry = "SELECT * FROM logging WHERE userid = :id;";

        $stmnt = $db->prepare($qry);

        $stmnt->bindValue(":id", $id);

        $stmnt->execute();

        $results = $stmnt->fetchAll(PDO::FETCH_BOTH);

        return $results;

    }

    

    function getActivitiesByDate($db, $id, $d1, $d2){

	$qry = 'SELECT * FROM logging 
		WHERE log_date BETWEEN :d1 AND :d2
		&& userid = :id';

	$stmnt = $db->prepare($qry);

	$stmnt->bindValue(':d1', $d1);
	$stmnt->bindValue(':d2', $d2);
	$stmnt->bindValue(':id', $id);

	$stmnt->execute();

	return $stmnt->fetchAll();

		
    }


    function deleteActivity($db, $id, $logID){

	$qry = 'DELETE FROM logging WHERE userID = :id AND logID = :logID';

	$stmnt = $db->prepare($qry);
	
	$stmnt->bindValue(':id', $id);

	$stmnt->bindValue(':logID', $logID);

	$stmnt->execute();

    }

    function getActivity($db, $id, $logID){

	$qry = 'SELECT * FROM logging WHERE id = :id AND logID = :logID';

	$stmnt = $db->prepare($qry);

	$stmnt->bindValue(':id', $id);

	$stmnt->bindValue(':logID', $logID);

	$stmnt->execute();

	return $stmnt->fetch();

    }

    function getSum($db, $id, $field){


	    $qry = 'SELECT * FROM logging WHERE userID = :id';
	    $stmnt = $db->prepare($qry);

	    $stmnt->bindValue(':id', $id);

	    $stmnt->execute();

	    $result = $stmnt->fetchAll(PDO::FETCH_ASSOC);
	    $sum = 0;	

	    foreach($result as $res){

		$sum += (int) $res[$field];
	    }

	    return $sum;		
    }

    
    function updateActivity($db, $data, $id){

	    $qry = 'UPDATE logging SET 
		    calories = :calories,
		    sleep = :sleep,
		    water = :water,
		    exercise = :exercise,
		    meds = :meds
		    WHERE id = :id';

	    $stmnt = $db->prepare($qry);

	    $stmnt->bindValue(':calories', $data[0]);
	    $stmnt->bindValue(':sleep', $data[1]);
	    $stmnt->bindValue(':water', $data[2]);
	    $stmnt->bindValue(':exercise', $data[3]);
	    $stmnt->bindValue(':meds', $data[4]);

	    $stmnt->bindValue(':id', $id);

	    $stmnt->execute();


    }



	function updateProfile($db, $id, $vals){

		//add img
		$qry = 'UPDATE user SET
		        username = :username,	
			age = :age,
			height = :height,
			weight = :weight,
			gender = :gender
			WHERE user_id = :id';

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':username', $vals[0]);
		$stmnt->bindValue(':age', $vals[1]);
		$stmnt->bindValue(':height', (float) $vals[2]);
		$stmnt->bindValue(':weight', (float) $vals[3]);
		$stmnt->bindValue(':gender', $vals[4]);
		$stmnt->bindValue(':id', $id);
	
		
		$stmnt->execute();


		
	}



    function setMonitor($db, $id, $current, $amount, $date){

	    $qry = "SELECT * FROM monitor WHERE id = :id";

	    $stmnt = $db->prepare($qry);

	    $stmnt->bindValue(':id', $id);

	    $stmnt->execute(); 

	    $result = $stmnt->fetch();

	if(!$result){

		
		$qry = "INSERT INTO monitor (current, amount, id, date) VALUES(:current, :amount, :id, :date)";
		$stmnt = $db->prepare($qry);

		echo "id: $id";
		$stmnt->bindValue(':id', $id);
		$stmnt->bindValue(':current', $current);
		$stmnt->bindValue(':date', $date);
		
		if($amount == ''){

			$amount = 0;
		}
			
		$stmnt->bindValue(':amount', $amount);
		

		

			$stmnt->execute();
		       	

	}else{

		
		$qry = "UPDATE monitor SET current = :current, amount = :amount, date = :date WHERE id = :id";
		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':current', $current);
	
		
		

		if($amount == ''){

			$amount = 0;
		}
			
		$stmnt->bindValue(':amount', $amount);
		$stmnt->bindValue(':id', $id);
		$stmnt->bindValue(':date', $date);

		$stmnt->execute();
	}


    }


	function getMonitor($db, $id){

		$qry = 'SELECT * FROM monitor WHERE id = :id';

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':id', $id);

		$stmnt->execute();

		return $stmnt->fetch();



	}



    	function updateScore($db, $id, $newScore){

		$qry = "UPDATE monitor SET score = :newScore WHERE id = :id";

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':newScore', $newScore);
		$stmnt->bindValue(':id', $id);

		$stmnt->execute();
	}



	function compareScore($db, $id, $cmp){

		
		$qry = "SELECT amount FROM monitor WHERE id = :id";

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':id', $id);

		$stmnt->execute();

		$result = $stmnt->fetch();


		return $cmp > $result[0];
		
		
		
	}

	function getProfs($db){

		$qry = "SELECT * FROM professionals";
		$stmnt = $db->prepare($qry);

		$stmnt->execute();

		return $stmnt->fetchAll(PDO::FETCH_BOTH);
	}

	
	function addAppointment($db, $uid, $pid, $bid, $aptDate, $aptTime, $ca){

		$qry = "INSERT INTO appointments (user_id, professional_id, booking_id, appointment_date, appointment_time, created_at) VALUES (:uid, :pid, :bid, :aptDate, :aptTime, :createdAt)";

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':uid', $uid);
		$stmnt->bindValue(':pid', $pid);
		$stmnt->bindValue(':bid', $bid);
		$stmnt->bindValue(':aptDate', $aptDate);
		$stmnt->bindValue(':aptTime', $aptTime);
		$stmnt->bindValue(':createdAt', $ca);

		$stmnt->execute();
	}

	function getAppointments($db, $uid){

		$qry = "SELECT * FROM appointments WHERE user_id = :uid";

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':uid', $uid);

		$stmnt->execute();

		return $stmnt->fetchAll(PDO::FETCH_BOTH);
	}

	function getAllAppointments($db){
	
		$qry = "SELECT * FROM appointments";

		$stmnt = $db->prepare($qry);

		$stmnt->execute();

		return $stmnt->fetchAll();

	}

	
	function checkAppointments($db, $uid, $bid) : bool{

		$qry = 'SELECT * FROM appointments WHERE user_id = :uid AND booking_id = :bid';

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':uid', $uid);
		$stmnt->bindValue(':bid', $bid);

		$stmnt->execute();

		$apt = $stmnt->fetchAll();

		
		$qry = 'SELECT * FROM booking WHERE userid = :uid AND id = :bid';

		$stmnt = $db->prepare($qry);

		$stmnt->bindValue(':uid', $uid);
		$stmnt->bindValue(':bid', $bid);

		$stmnt->execute();

		$bkg = $stmnt->fetchAll();
		
	//	echo var_dump($apt);

		//	return $apt['booking_id'] === $bkg['id'] ? true : false;
		if(!empty($apt) && !empty($bkg)){
			if($apt[0][3] === $bkg[0][0]){
				return true;
			}
		}	
		return false;


	}	


function getBookings($db, $userId) {
    $qry = "SELECT * FROM booking WHERE userid = :userid ORDER BY booking_date ASC";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_BOTH);
	}

function getAllBookings($db){
	
	$qry = "SELECT * FROM booking";

	$stmnt = $db->prepare($qry);

	$stmnt->execute();

	return $stmnt->fetchAll();


}	

function addBooking($db, $userId, $date, $description) {
    $qry = "INSERT INTO booking (userid, booking_date, description) 
            VALUES (:userid, :date, :desc)";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':userid', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':desc', $description);
    $stmt->execute();
}

function deleteBooking($db, $bookingId) {
    $qry = "DELETE FROM booking WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
}

function getBooking($db, $bookingId) {
    $qry = "SELECT * FROM booking WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateBooking($db, $bookingId, $date, $description) {
    $qry = "UPDATE booking SET booking_date = :date, description = :desc WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':desc', $description);
    $stmt->bindValue(':id', $bookingId, PDO::PARAM_INT);
    $stmt->execute();
}
   
   
function addStressLevel($db, $userId, $date, $level, $notes = '') {
    $qry = "INSERT INTO stress_levels (user_id, log_date, stress_level, notes) 
            VALUES (:user_id, :date, :level, :notes)
            ON DUPLICATE KEY UPDATE stress_level = :level, notes = :notes";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':date', $date);
    $stmt->bindValue(':level', $level, PDO::PARAM_INT);
    $stmt->bindValue(':notes', $notes);
    $stmt->execute();
}

function getStressLevels($db, $userId) {
    $qry = "SELECT * FROM stress_levels WHERE user_id = :user_id ORDER BY log_date DESC";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getStressLevel($db, $id) {
    $qry = "SELECT * FROM stress_levels WHERE id = :id";
    $stmt = $db->prepare($qry);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>

