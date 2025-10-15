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
	
			
			return true;
		}
		else{

		
			return false;
		}

	}


	function testing(){

		echo "Hello";
	}

?>
