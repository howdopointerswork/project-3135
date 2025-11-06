<?php
require_once('db.php');
require('functions.php');               // for testing – can be ignored
require_once('user.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* --------------------------------------------------------------
   GET ACTION
   -------------------------------------------------------------- */
$action = filter_input(INPUT_POST, 'action');
if ($action === null) {
    $action = filter_input(INPUT_GET, 'action');
    if ($action === null) {
        $action = 'login';
    }
}

/* --------------------------------------------------------------
   DB CONNECTION
   -------------------------------------------------------------- */
$dsn = 'mysql:host=127.0.0.1;dbname=health_system';
$user = 'mgs_user';
$pw   = 'pa55word';

$u = new User();                     // user object

try {
    $db = new PDO($dsn, $user, $pw);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Database connection failed');
}

/* --------------------------------------------------------------
   SWITCH
   -------------------------------------------------------------- */
switch ($action) {

    /* -------------------- LOGIN / SIGNUP -------------------- */
    case 'login':
        include('login.php');
        exit;

    case 'Submit':
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        $stmt = $db->prepare('SELECT * FROM user WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $row = $stmt->fetch();

        if (!$row) {
            // New user – show signup
            include('signup.php');
            exit;
        }

        $result = authenticate($db, $username, $password);
        if ($result[3]) {                 // password correct
            $u->setID($result[0]);
            $u->setName($result[1]);
            $u->setAge($result[3]);
            $u->setHt($result[4]);
            $u->setWt($result[5]);
            $u->setGender($result[6]);
            $u->setImg($result[7] ?? '');

            $_SESSION['current'] = $u;
            $_SESSION['activities'] = getActivities($db, $u->getID());

            include('dash.php');
            exit;
        } else {
            echo '<p style="color:red;">Invalid password</p>';
            include('login.php');
            exit;
        }
        break;

    /* -------------------- NAVIGATION -------------------- */
    case 'Booking':
        include('booking.php');
        exit;

    case 'Logging':
        include('logging.php');
        exit;

    case 'Search':
        include('search.php');
        exit;

    case 'Monitoring':
        include('monitor.php');
        exit;

    case 'Sign Out':
        session_destroy();
        include('login.php');
        exit;

    case 'Alerts':
        include('alerts.php');
        exit;

    case 'Dashboard':
    case 'Back':
        include('dash.php');
        exit;

    /* -------------------- LOGGING ACTIONS -------------------- */
    case 'Add Activity':
        $values = [
            filter_input(INPUT_POST, 'calories'),
            filter_input(INPUT_POST, 'sleep'),
            filter_input(INPUT_POST, 'water'),
            filter_input(INPUT_POST, 'exercise'),
            filter_input(INPUT_POST, 'meds'),
            filter_input(INPUT_POST, 'userid', FILTER_VALIDATE_INT)
        ];
        addActivity($db, $values);
        include('logging.php');
        exit;

    case 'Delete Activity':
        $actID = filter_input(INPUT_POST, 'actID', FILTER_VALIDATE_INT);
        if ($actID) deleteActivity($db, $actID);
        include('logging.php');
        exit;

    case 'Edit Activity':
        $actID = filter_input(INPUT_POST, 'actID', FILTER_VALIDATE_INT);
        if ($actID) {
            $activity = getActivity($db, $actID);
            include('editactivity.php');
        } else {
            include('logging.php');
        }
        exit;

    case 'Update Activity':
        $data  = filter_input(INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        $actID = filter_input(INPUT_POST, 'actID', FILTER_VALIDATE_INT);
        if ($data && $actID) updateActivity($db, $data, $actID);
        include('logging.php');
        exit;

    /* -------------------- PROFILE -------------------- */
    case 'Profile':
        include('profile.php');
        exit;

    case 'Save':
        $vals = [
            filter_input(INPUT_POST, 'Username'),
            filter_input(INPUT_POST, 'Age'),
            filter_input(INPUT_POST, 'Height'),
            filter_input(INPUT_POST, 'Weight'),
            filter_input(INPUT_POST, 'Gender')
        ];

        foreach ($vals as $i => $val) {
            if (empty($val)) {
                switch ($i) {
                    case 0: $vals[$i] = $_SESSION['current']->getName(); break;
                    case 1: $vals[$i] = $_SESSION['current']->getAge(); break;
                    case 2: $vals[$i] = $_SESSION['current']->getHt(); break;
                    case 3: $vals[$i] = $_SESSION['current']->getWt(); break;
                    case 4: $vals[$i] = $_SESSION['current']->getGender(); break;
                }
            }
        }

        $_SESSION['current']->setName($vals[0]);
        $_SESSION['current']->setAge($vals[1]);
        $_SESSION['current']->setHt((float)$vals[2]);
        $_SESSION['current']->setWt((float)$vals[3]);
        $_SESSION['current']->setGender($vals[4]);

        updateProfile($db, $_SESSION['current']->getID(), $vals);
        include('profile.php');
        exit;

    /* -------------------- BOOKING ACTIONS -------------------- */

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