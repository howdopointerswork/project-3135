<?php

    function addUser($db, $username, $password){

    
        $qry = "INSERT INTO user (username, password)
            VALUES (:username, :password)";
               
        $stmnt = $db->prepare($qry);

        $stmnt->bindValue(':username', $username); //prevent duplicate usernames

        $stmnt->bindValue(':password', $password);

        $stmnt->execute();

    }

    
    function authenticate($db, $username, $password){

        $qry = "SELECT * FROM user
            WHERE username = :username;";
        
        $stmnt = $db->prepare($qry);

        $stmnt->bindValue(':username', $username);

        $stmnt->execute();

        $result = $stmnt->fetch();

        

        //check if empty first
    
        if($result[2] == $password){
    	    
            $result[] = true;
            return $result;
        }
        else{

        
            $result[] = false;
            return $result;
        }

    }


    function addActivity($db, $values){

        $qry = "INSERT INTO logging (calories, sleep, water, exercise, meds, userid) VALUES(:val0, :val1, :val2, :val3, :val4, :val5)";

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

        $stmnt->execute();

        include('logging.php');

    }


    function getActivities($db, $id){

        
        $qry = "SELECT * FROM logging WHERE userid = :id;";

        $stmnt = $db->prepare($qry);

        $stmnt->bindValue(":id", $id);

        $stmnt->execute();

        $results = $stmnt->fetchAll();

        return $results;

    }


    function deleteActivity($db, $id){

	$qry = 'DELETE FROM logging WHERE id = :id';

	$stmnt = $db->prepare($qry);
	
	$stmnt->bindValue(':id', $id);

	$stmnt->execute();

    }

    function getActivity($db, $id){

	$qry = 'SELECT * FROM logging WHERE id = :id';

	$stmnt = $db->prepare($qry);

	$stmnt->bindValue(':id', $id);

	$stmnt->execute();

	return $stmnt->fetch();

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
    

?>

