<!DOCTYPE html>
<?php
require_once('user.php');
require_once('db.php');
require_once('alerts.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Establish database connection
$dsn = 'mysql:host=127.0.0.1;dbname=health_system_final';
$user = 'root';
$pw = '';

try {
    $db = new PDO($dsn, $user, $pw);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if user is logged in
if (!isset($_SESSION['current']) || !$_SESSION['current']) {
    header('Location: main.php?action=login');
    exit;
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
        
        /* Popup Modal Styles */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); }
        .modal-content { background-color: #fefefe; margin: 15% auto; padding: 20px; border-radius: 10px; width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.3); }
        #appointmentDetails { text-align: left; }
        .close { color: #aaa; float: right; font-size: 24px; font-weight: bold; cursor: pointer; }
        .close:hover { color: #000; }
        .details-btn { background: white; color: #2196F3; border: 2px solid #2196F3; padding: 0.4em 0.8em; border-radius: 4px; cursor: pointer; font-size: 16px; margin: 0.2em; }
        .details-btn:hover { background: #f0f8ff; }
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
	
	/*echo 'admin';
	echo '<form method="post" action="main.php">';
	echo "<select name='user'>";*/
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


	//	foreach(getBookings($db,
	
	   echo '<div style="display: flex; flex-direction: column;">';


    echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;"><i class="fas fa-clipboard-list"></i> Select Booking</label>';


    echo "<select name='booking' style='padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;' onmouseover='this.style.borderColor=\"#1976D2\"' onmouseout='this.style.borderColor=\"#ddd\"'>";

	foreach(getAllBookings($db) as $booking){

	echo "<option value='" . $booking[0] . "|" . $booking[1] . "'>ID:" . $booking[0] . " - " . htmlspecialchars($booking[3]) . " (" . $booking[2] . ")</option>";
	//	echo "<input type='hidden' name='match' value=" . $booking[1] . ">";

	}
	echo "</select>";

	echo "<select name='prof'>";
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


   


	echo '</form>';

     echo '</div>';
}


	
	?>

    <!-- Add Booking Form (Hidden by Default) -->
    <div id="booking_system" style="display: none; background: #fff; padding: 2em; margin: 2em auto; max-width: 600px; border-radius: 12px; box-shadow: 0 10px 28px rgba(0,0,0,0.12);">
        <h2 style="color: #1976D2; margin-bottom: 1.5em; display: flex; align-items: center; gap: 0.5em;">
            <i class="fas fa-calendar-plus"></i> Add New Booking
        </h2>
        
        <form method='post' action='main.php' style="display: grid; gap: 1.2em;">
            <input type="hidden" name="userid" value="<?php echo $_SESSION['current']->getID(); ?>">

            <div style="display: flex; flex-direction: column;">
                <label for="booking_date" style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;">
                    <i class="fas fa-calendar"></i> Select Date
                </label>
                <input type="date" name="booking_date" id="booking_date" required 
                       style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;" 
                       onmouseover="this.style.borderColor='#1976D2'" onmouseout="this.style.borderColor='#ddd'">
            </div>

            <div style="display: flex; flex-direction: column;">
                <label for="description" style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;">
                    <i class="fas fa-file-alt"></i> Description
                </label>
                <textarea name="description" id="description" rows="4" 
                          style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s; resize: vertical;" 
                          placeholder="Enter booking details..." 
                          onmouseover="this.style.borderColor='#1976D2'" onmouseout="this.style.borderColor='#ddd'"></textarea>
            </div>

            <div style="display: flex; gap: 1em; justify-content: flex-end; margin-top: 1em;">
                <button type="button" id="cancel" 
                        style="padding: 0.75em 1.5em; background: #f5f5f5; color: #666; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;" 
                        onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
                    <i class="fas fa-times"></i> Cancel
                </button>
                
                <button type="submit" name="action" value="Add Booking" 
                        style="padding: 0.75em 1.5em; background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); color: white; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(33,150,243,0.3); transition: all 0.2s;" 
                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(33,150,243,0.4)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(33,150,243,0.3)'">
                    <i class="fas fa-plus"></i> Add Booking
                </button>
            </div>
        </form>
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
                $isConfirmed = $booking && checkAppointments($db, $_SESSION['current']->getID(), $booking['id']);
                $cellClass = $booking ? '' : 'empty-day';
                $cellStyle = $isConfirmed ? 'background: #4CAF50; color: white;' : '';

                echo "<td class='$cellClass' style='$cellStyle'>";
                echo "<div class='day-num'>$day</div>";

                if ($booking) {
                    echo "<div class='booking-text'>{$booking['desc']}</div>";
                    echo "<div style='margin-top: 4px;'>";
			
		    if(!checkAppointments($db, $_SESSION['current']->getID(), $booking['id'])){
                    // Edit Form
                    echo "<form method='post' action='main.php' style='display: inline'>";
                    echo "<input type='hidden' name='bookingID' value='{$booking['id']}'>";
                    echo "<input type='submit' name='action' value='Edit' class='manage-btn' style='background:#4CAF50;color:white;'>";
                    echo "</form> ";

                    // Delete Form
                    echo "<form method='post' action='main.php' style='display:inline;' onsubmit='return confirm(\"Delete this booking?\");'>";
                    echo "<input type='hidden' name='bookingID' value='{$booking['id']}'>";
                    echo "<input type='submit' name='action' value='Delete' class='manage-btn' style='background:#f44336;color:white;'>";
		    echo "</form>";
		    }else{

			echo '<p style="color: white; font-weight: bold;">Appointment Confirmed</p>';
			echo '<button class="details-btn" onclick="showAppointmentDetails(' . $booking['id'] . ')">See Details</button>';
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
        <button id="add" style="font-size: 18px; padding: 0.7em 1.5em; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 5px;">
            <i class="fas fa-plus"></i> Add Booking
        </button>
    </div>

    <!-- Appointment Details Modal -->
    <div id="appointmentModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 style="color: #1976D2; margin-bottom: 1em;"><i class="fas fa-calendar-check"></i> Appointment Details</h2>
            <div id="appointmentDetails"></div>
        </div>
    </div>

    <script>
        document.getElementById('add').addEventListener('click', function () {
            document.getElementById('booking_system').style.display = 'block';
        });

        document.getElementById('cancel').addEventListener('click', function () {
            document.getElementById('booking_system').style.display = 'none';
        });

        function showAppointmentDetails(bookingId) {
            // AJAX call to get appointment details
            fetch('main.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_appointment_details&booking_id=' + bookingId
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('appointmentDetails').innerHTML = data;
                document.getElementById('appointmentModal').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading appointment details');
            });
        }

        function closeModal() {
            document.getElementById('appointmentModal').style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('appointmentModal')) {
                closeModal();
            }
        }
    </script>

</body>
</html>
