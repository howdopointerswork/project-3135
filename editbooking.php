<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$bk = $_SESSION['editing_booking'] ?? null;
if (!$bk) { header('Location: booking.php'); exit; }
include('nav.php');
?>
<h2>Edit Booking</h2>
<form method="post" action="main.php">
    <input type="hidden" name="bookingID" value="<?= $bk['id'] ?>">
    <label>Date: <input type="date" name="booking_date" value="<?= $bk['booking_date'] ?>" required></label><br><br>
    <label>Description: <textarea name="description"><?= htmlspecialchars($bk['description']) ?></textarea></label><br><br>
    <input type="submit" name="action" value="Update Booking">
    <input type="submit" name="action" value="Back">
</form>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Booking</title>
    <link rel="stylesheet" href="css/dash.css">
</head>

</html>