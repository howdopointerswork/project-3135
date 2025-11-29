<!DOCTYPE html>
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$bk = $_SESSION['editing_booking'] ?? null;
if (!$bk) { header('Location: booking.php'); exit; }
?>
<html>
<head>
    <title>Edit Booking</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Back button styling matching other pages */
        .profile-back-btn {
            background: linear-gradient(90deg, #6c757d 0%, #5a6268 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .profile-back-btn:hover {
            background: linear-gradient(90deg, #5a6268 0%, #495057 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.2);
        }
        
        .profile-back-btn i {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php include('nav.php'); ?>
    
    <!-- Header with Back Button -->
    <div style="display: flex; align-items: center; justify-content: flex-start; gap: 2em; max-width: 700px; margin: 0 auto 2em auto; padding: 0 1em;">
        <form method='post' action='booking.php' style='margin: 0;'>
            <button type='submit' class='profile-back-btn'>
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </form>
        <h1 style="text-align: left; font-size: 36px; color: #111; margin: 0; font-weight: 600;">Edit Booking</h1>
    </div>

    <!-- Edit Booking Form -->
    <div style="background: #fff; padding: 2em; margin: 2em auto; max-width: 700px; border-radius: 12px; box-shadow: 0 10px 28px rgba(0,0,0,0.12);">
        <h2 style="color: #1976D2; margin-bottom: 1.5em; display: flex; align-items: center; gap: 0.5em;">
            <i class="fas fa-calendar-edit"></i> Update Appointment Details
        </h2>
        
        <form method="post" action="main.php" style="display: grid; gap: 1.5em;">
            <input type="hidden" name="bookingID" value="<?= $bk['id'] ?>">
            
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;">
                    <i class="fas fa-calendar-day"></i> Appointment Date
                </label>
                <input type="date" name="booking_date" value="<?= $bk['booking_date'] ?>" required
                       style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;"
                       onmouseover="this.style.borderColor='#1976D2'" onmouseout="this.style.borderColor='#ddd'">
            </div>
            
            <div style="display: flex; flex-direction: column;">
                <label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;">
                    <i class="fas fa-comment-medical"></i> Description / Notes
                </label>
                <textarea name="description" rows="4" placeholder="Enter appointment details, symptoms, or notes..."
                          style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s; resize: vertical; font-family: inherit;"
                          onmouseover="this.style.borderColor='#1976D2'" onmouseout="this.style.borderColor='#ddd'"><?= htmlspecialchars($bk['description']) ?></textarea>
            </div>
            
            <div style="display: flex; gap: 1em; justify-content: flex-end; margin-top: 1em;">
                <button type="button" onclick="window.location.href='booking.php'" 
                        style="padding: 0.75em 1.5em; background: #f5f5f5; color: #666; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                        onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
                    <i class="fas fa-times"></i> Cancel
                </button>
                
                <button type="submit" name="action" value="Update Booking"
                        style="font-size: 18px; padding: 0.7em 1.5em; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 8px; font-weight: 600; transition: all 0.2s; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);"
                        onmouseover="this.style.background='#1976D2'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#2196F3'; this.style.transform='translateY(0)'">
                    <i class="fas fa-save"></i> Update Booking
                </button>
            </div>
        </form>
    </div>
</body>
</html>