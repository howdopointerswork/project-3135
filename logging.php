
<!DOCTYPE html>
<?php
require_once('user.php');
require_once('db.php');
require_once('alerts.php');

//require('db.php');

if(session_status() === PHP_SESSION_NONE){
	session_start();
}

include('nav.php');
//$_SESSION['current']->getName();
//echo 'logged in as: ' . $_SESSION['current']->getName() . "<br> and" . $_SESSION['current']->getID() . " is ID<br>";
?>
<html>

    <head>
        <title>Log Activities</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/dash.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    </head>

    <body>
    
        
        
        <h1 id="log_title" style="text-align: center">Activities</h1>
        
            
        
        <div id="logging_system" style="display: none;">
            <form method='post' action='main.php'>
        
            <input type="hidden" name="userid" value=<?php echo $_SESSION['current']->getID() ?>>
            
            <label for="calories">Calorie Intake</label>
            <input type="text" name="calories">
            
            <label for="sleep">Hours of Sleep</label>
            <input type="text" name="sleep">

            <label for="water">Water Intake (mL)</label>
            <input type="text" name="water">

            <label for="exercise">Hours of Exercise</label>
            <input type="text" name="exercise">

            <label for="meds">Medication Taken?</label>
            <input type="checkbox" name="meds">

            <input type="submit" name="action" value="Add Activity">



            </form>

            <button id="cancel">Cancel</button>

            </div>

        <?php
	    
        echo '<button id="add" name="add" style="font-Size: 24px;">Log Activity</button>';
        
$results = getActivities($db, $_SESSION['current']->getID());

	    $names = ['Log ID', 'User ID', 'Calories', 'Sleep', 'Water', 'Exercise', 'Medication', 'Date']; //remove ID


        if(!empty($results)){

            echo "<table style='display: flex; justify-content: center; align-items: center;'>";

            echo "<tr>";
		
	    echo "<td style='border: 2px solid black; text-align: center; padding: 0.5em; font-weight: bold;'>";
	    echo "Manage";
	    echo "</td>";
	    
	    foreach($names as $nam){
            
                echo "<td style='border: 2px solid black; text-align: center; font-weight: bold;'>";

                    echo $nam;

                echo "</td>";
	    }


			

            echo "</tr>";
	    //add date/time of logging
	    //
            foreach($results as $result){
                
                echo "<tr>";

		echo "<td style='border: 2px solid black;'>";

		echo "<form method='post' method='main.php'>";

		echo "<input type='submit' name='action' value='Delete Activity' style='font-size: 18px; padding: 0.5em;'>";
		echo "<input type='hidden' name='actID' value=$result[1]>";
		echo "<input type='hidden' name='logID' value=$result[0]>";		
		echo "<input type='submit' name='action' value='Edit Activity' style='font-size: 18px; padding: 0.5em;'>";
		echo "</form>";

		echo "</td>";

                foreach($result as $index => $val){
                     if(is_numeric($index)){
                        echo "<td style='border: solid 2px black; padding:2em; font-size: 18px;'>";
                        echo "$val";

                        //edit here
                        echo "</td>";
                     }
            }
                
		
		echo "</tr>";

            }


            echo "</table>";
    
        
        }else{

            echo "You have no activities";
        }

?>
        
      <!--  <button id="add" name="add">Log Activity</button> -->
        
<script>

document.getElementById('add').addEventListener('click', function(){
    document.getElementById('logging_system').style.display = '';



});

document.getElementById('cancel').addEventListener('click', function(){

    document.getElementById('logging_system').style.display = 'none';

});


</script>

    
    </body>

</html>

