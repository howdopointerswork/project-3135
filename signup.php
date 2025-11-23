<?php

$time = date('YYYY-mm-dd');

echo "<h1>Sign Up</h1>";
$fields = ['Username', 'Password', 'Age', 'Height', 'Weight', 'Gender'];

echo "<form method='post' action='main.php'>";
$type = 'text';
foreach($fields as $field){

	if($field === 'Password'){

		$type = 'password';
	}else{
		$type = 'text';
	}

	echo "<label for=$field>$field</label>";
	echo "<input type='" . $type . "' name=$field>";
}
echo "<input type='submit' name='action' value='Add Account'>";
echo "</form>";

?>

<!DOCTYPE HTML>
<html>

<head>
<link rel="stylesheet" href="css/main.css">
</head>

</html>

