<!DOCTYPE html>
<?php
require_once('user.php');
require_once('db.php');
require_once('alerts.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('nav.php');

// Debug (remove in production)
?>

<html>
<head>
    <title>Bookings</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        table { border-collapse: collapse; width: 90%; margin: 2rem auto; }
        th, td { border: 2px solid black; padding: 1em; text-align: center; }
        th { font-weight: bold; background: #f0f0f0; }
        .manage-btn { font-size: 16px; padding: 0.4em 0.8em; margin: 0.2em; }
        .calendar { width: 90%; margin: 2em auto; }
        .calendar td { height: 80px; vertical-align: top; font-size: 14px; }
        .day-num { font-weight: bold; font-size: 16px; }
        .booking-text { font-size: 13px; margin-top: 4px; }
        .empty-day { background: #f9f9f9; }
    </style>
</head>
<body>

    <!-- Back Button -->
  <!--  <form method='post' action='main.php'>
        <input type='submit' name='action' value='Back' style='font-size: 18px; padding: 0.5em; margin: 1rem;'>
    </form> -->

    <h1 style="text-align: center;">Bookings</h1>

<?php 

//get user, get bookings, confirm button
if($_SESSION['current']->getPrivilege() > 0){
	
	echo '<div style="background: #fff; padding: 2em; margin: 2em auto; max-width: 900px; border-radius: 12px; box-shadow: 0 10px 28px rgba(0,0,0,0.12);">';
	echo '<h2 style="color: #1976D2; margin-bottom: 1.5em; display: flex; align-items: center; gap: 0.5em;"><i class="fas fa-calendar-check"></i> Admin: Confirm Appointment</h2>';
	echo '<form method="post" action="main.php" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.2em; align-items: end;">';
	
	// User selection
	echo '<div style="display: flex; flex-direction: column;">';
	echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;"><i class="fas fa-user"></i> Select User</label>';
	echo "<select name='user' style='padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;' onmouseover='this.style.borderColor=\"#1976D2\"' onmouseout='this.style.borderColor=\"#ddd\"'>";
	foreach(getUsers($db) as $user){
		echo "<option value=" . $user[0] . ">" . htmlspecialchars($user[1]) . "</option>";
	}
	echo '</select>';
	echo '</div>';
	
	// Booking selection
	echo '<div style="display: flex; flex-direction: column;">';
	echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;"><i class="fas fa-clipboard-list"></i> Select Booking</label>';
	echo "<select name='booking' style='padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;' onmouseover='this.style.borderColor=\"#1976D2\"' onmouseout='this.style.borderColor=\"#ddd\"'>";
	foreach(getAllBookings($db) as $booking){
		echo "<option value='" . $booking[0] . "|" . $booking[1] . "'>ID:" . $booking[0] . " - " . htmlspecialchars($booking[3]) . " (" . $booking[2] . ")</option>";
	}
	echo "</select>";
	echo '</div>';
	
	// Professional selection
	echo '<div style="display: flex; flex-direction: column;">';
	echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;"><i class="fas fa-user-md"></i> Select Professional</label>';
	echo "<select name='prof' style='padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;' onmouseover='this.style.borderColor=\"#1976D2\"' onmouseout='this.style.borderColor=\"#ddd\"'>";
	foreach(getProfs($db) as $prof){
		echo "<option value=" . $prof[0] . ">" . htmlspecialchars($prof[1]) . "</option>";
	}
	echo "</select>";
	echo '</div>';
	
	// Date input
	echo '<div style="display: flex; flex-direction: column;">';
	echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;"><i class="fas fa-calendar"></i> Appointment Date</label>';
	echo '<input type="date" name="app_date" required style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;" onmouseover="this.style.borderColor=\'#1976D2\'" onmouseout="this.style.borderColor=\'#ddd\'">';
	echo '</div>';
	
	// Time input
	echo '<div style="display: flex; flex-direction: column;">';
	echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;"><i class="fas fa-clock"></i> Appointment Time</label>';
	echo '<input type="time" name="app_time" required style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;" onmouseover="this.style.borderColor=\'#1976D2\'" onmouseout="this.style.borderColor=\'#ddd\'">';
	echo '</div>';
	
	// Submit button
	echo '<div style="display: flex; align-items: end;">';
	echo '<input type="submit" name="action" value="Confirm Appointment" style="padding: 0.75em 1.5em; background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(33,150,243,0.3); transition: all 0.2s; width: 100%;" onmouseover="this.style.transform=\'translateY(-2px)\'; this.style.boxShadow=\'0 6px 16px rgba(33,150,243,0.4)\'" onmouseout="this.style.transform=\'translateY(0)\'; this.style.boxShadow=\'0 4px 12px rgba(33,150,243,0.3)\'">';
	echo '</div>';
	
	echo '</form>';
	echo '</div>';
}


	
	?>

    <!-- Add Booking Form (Hidden by Default) -->
    <div id="booking_system" style="display: none; background: #f4f4f4; padding: 1.5rem; margin: 1rem auto; max-width: 500px; border: 1px solid #ccc;">
        <form method='post' action='main.php'>
            <input type="hidden" name="userid" value="<?php echo $_SESSION['current']->getID(); ?>">

            <label for="booking_date">Date</label><br>
            <input type="date" name="booking_date" required style="width: 100%; padding: 0.5em; margin-bottom: 1em;"><br>

            <label for="description">Description</label><br>
            <textarea name="description" rows="3" style="width: 100%; padding: 0.5em; margin-bottom: 1em;"></textarea><br>

            <input type="submit" name="action" value="Add Booking" class="manage-btn">
        </form>
        <button id="cancel" style="font-size: 16px; padding: 0.5em; margin-top: 0.5em;">Cancel</button>
    </div>

    <!-- Calendar Display -->
    <?php
    $bookings = getBookings($db, $_SESSION['current']->getID());

    // Build day → booking map
    $dayMap = [];
    foreach ($bookings as $b) {
        $day = (int)date('j', strtotime($b['booking_date'])); // 1–31
        $dayMap[$day] = [
            'id' => $b['id'],
            'desc' => htmlspecialchars($b['description'])
        ];
    }

    // Current month
    $today = getdate();
    $year = $today['year'];
    $month = $today['mon'];
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $startWeek = date('w', $firstDay); // 0=Sun, 6=Sat
    $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    ?>

    <div class="calendar">
        <table>
            <tr>
                <?php foreach ($dayNames as $dn): ?>
                    <th><?= $dn ?></th>
                <?php endforeach; ?>
            </tr>

            <?php
            $day = 1;
            $started = false;

            while ($day <= $daysInMonth) {
                if (!$started) {
                    echo '<tr>';
                    for ($i = 0; $i < $startWeek; $i++) {
                        echo '<td class="empty-day"></td>';
                    }
                    $started = true;
                }

                $booking = $dayMap[$day] ?? null;
                $cellClass = $booking ? '' : 'empty-day';

                echo "<td class='$cellClass'>";
                echo "<div class='day-num'>$day</div>";

                if ($booking) {
                    echo "<div class='booking-text'>{$booking['desc']}</div>";
                    echo "<div style='margin-top: 4px;'>";
			
		    if(!checkAppointments($db, $_SESSION['current']->getID(), $booking['id'])){
                    // Edit Form
                    echo "<form method='post' action='main.php' style='display:inline;'>";
                    echo "<input type='hidden' name='bookingID' value='{$booking['id']}'>";
                    echo "<input type='submit' name='action' value='Edit' class='manage-btn' style='background:#4CAF50;color:white;'>";
                    echo "</form> ";

                    // Delete Form
                    echo "<form method='post' action='main.php' style='display:inline;' onsubmit='return confirm(\"Delete this booking?\");'>";
                    echo "<input type='hidden' name='bookingID' value='{$booking['id']}'>";
                    echo "<input type='submit' name='action' value='Delete' class='manage-btn' style='background:#f44336;color:white;'>";
		    echo "</form>";
		    }else{

			echo '<p style="color: green">Appointment Confirmed</p>';
		    }


                    echo "</div>";
                } else {
                    echo "&nbsp;";
                }

                echo "</td>";

                // End of week
                if ((($day + $startWeek) % 7) === 0) {
                    echo '</tr><tr>';
                }

                $day++;
            }

            // Fill remaining cells
            $remaining = (7 - (($day + $startWeek - 1) % 7)) % 7;
            for ($i = 0; $i < $remaining; $i++) {
                echo '<td class="empty-day"></td>';
            }
            if ($remaining > 0) echo '</tr>';
            ?>
        </table>
    </div>

    <!-- Add Booking Button -->
    <div style="text-align: center; margin: 2rem;">
        <button id="add" style="font-size: 18px; padding: 0.7em 1.5em; background: #2196F3; color: white; border: none; cursor: pointer;">
            Add Booking
        </button>
    </div>

    <script>
        document.getElementById('add').addEventListener('click', function () {
            document.getElementById('booking_system').style.display = 'block';
        });

        document.getElementById('cancel').addEventListener('click', function () {
            document.getElementById('booking_system').style.display = 'none';
        });
    </script>

</body>
</html>
