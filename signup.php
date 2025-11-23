
<?php
// testing if the header will work 
include 'inc/header.php';

?>

<div class="login-gradient-wrapper">
<?php

$time = date('YYYY-mm-dd');

echo "<h1 class='auth-title login-title'>Create Your WyseCare Account</h1>";
$fields = ['Username', 'Password', 'Age', 'Height', 'Weight', 'Gender'];

echo "<form class='signup-form' method='post' action='main.php'>";
$type = 'text';
foreach($fields as $field){

	if($field === 'Password'){

		$type = 'password';
	}else{
		$type = 'text';
	}

	echo "<div class='signup-row'>";
	echo "<label for='$field' class='signup-label'>$field</label>";
	echo "<input class='signup-input' type='" . $type . "' name='$field' id='$field'>";
	echo "</div>";
}
echo "<div class='signup-row signup-row--submit'>";
echo "<input class='signup-btn' type='submit' name='action' value='Add Account'>";
echo "</div>";
echo "</form>";

echo "<p style='text-align:center; color: #ffffff; margin-top: 1em;'>Already have an account? <a href='main.php?action=login' style='color: #ffd966; font-weight: 600;'>Log In</a></p>";

?>
</div>

<?php
// Calls footer
include 'inc/footer.php';

?>

