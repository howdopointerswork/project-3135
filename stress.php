<!DOCTYPE html>
<?php
require_once('user.php');
require_once('db.php');
require_once('alerts.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('nav.php');

// Detect if user just logged high stress
$justLoggedHighStress = (isset($_POST['action']) && $_POST['action'] === 'Save Stress Log'
    && isset($_POST['stress_level']) && $_POST['stress_level'] >= 8);
?>

<html>
<head>
    <title>Stress Tracker</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/dash.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* ADDED: Back button styling matching profile design */
        .profile-back-btn {
            padding: 0.75em 1.5em;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(90deg, #6c757d 0%, #5a6268 100%);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(108,117,125,0.3);
            transition: transform 0.12s ease, box-shadow 0.12s ease;
            display: flex;
            align-items: center;
            gap: 0.5em;
        }
        .profile-back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(108,117,125,0.4);
        }
        .profile-back-btn i {
            font-size: 14px;
        }

        .container { max-width: 1100px; margin: 2em auto; padding: 1em; }
        .form-box { background: #f8f8f8; padding: 2em; border: 2px solid #333; border-radius: 12px; margin: 2em auto; max-width: 640px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        textarea { width: 100%; height: 180px; font-size: 16px; padding: 12px; border-radius: 8px; border: 1px solid #ccc; }
        .btn { font-size: 18px; padding: 14px 28px; margin: 10px; cursor: pointer; border: none; border-radius: 8px; transition: 0.3s; }
        .btn-primary { background: #2196F3; color: white; }
        .btn-primary:hover { background: #1976D2; }
        .btn-success { background: #4CAF50; color: white; }
        .btn-success:hover { background: #388E3C; }
        .btn-cancel { background: #999; color: white; }

        /* Table Format */
        .clean-table {
            width: 95%; max-width: 1100px; margin: 2.5em auto;
            border-collapse: separate; border-spacing: 0;
            background: white; box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            border-radius: 14px; overflow: hidden;
        }
        .clean-table thead th {
            background: #1a1a1a; color: white; padding: 18px 12px;
            font-size: 18px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .clean-table tbody td {
            padding: 16px 12px; text-align: center; border-bottom: 1px solid #e0e0e0;
            font-size: 16px; transition: background 0.2s;
        }
        .clean-table tbody tr:hover { background-color: #f8f9fa; }
        .clean-table tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .big-number { font-size: 32px !important; font-weight: bold; }

        /* Stress Scale */
        .stress-scale { background: white; padding: 1.8em; border-radius: 12px; border: 2px solid #333; margin: 1.8em 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .scale-row { display: flex; align-items: center; margin: 14px 0; flex-wrap: wrap; gap: 12px; }
        .scale-number { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 22px; color: white; flex-shrink: 0; }
        .scale-1-3 { background: #4CAF50; }
        .scale-4-6 { background: #FF9800; }
        .scale-7-10 { background: #f44336; }

        /* Comparison Cards */
        .comparison { display: flex; flex-wrap: wrap; gap: 1.8em; justify-content: center; margin: 3em 0; }
        .day-card { flex: 1 1 320px; max-width: 420px; background: #f8f8f8; border: 3px solid #333; border-radius: 14px; padding: 1.8em; box-shadow: 0 6px 16px rgba(0,0,0,0.12); transition: transform 0.2s; }
        .day-card:hover { transform: translateY(-4px); }
        .day-card h3 { margin: 0 0 1em; text-align: center; font-size: 1.4em; border-bottom: 2px solid #ccc; padding-bottom: 0.6em; }
        .stress-level-big { font-size: 3.2em; font-weight: bold; text-align: center; margin: 0.6em 0; }

        /* Support Resources */
        /* UPDATED: Changed from centered text-only layout to split half-text/half-image design */
        .support-resources { 
            background: #ffffff; 
            border: none; /* CHANGED: Removed dark blue border */
            border-radius: 0; /* CHANGED: Removed border-radius for full-width design */
            padding: 0; /* CHANGED: Removed padding to allow full-width image */
            margin: 3em 0; /* CHANGED: Removed auto margin and max-width for full browser width */
            max-width: 100%; /* CHANGED: Set to 100% for full width */
            width: 100%; /* ADDED: Ensures full width */
            box-shadow: none; /* CHANGED: Removed shadow */
            overflow: hidden; /* ADDED: Ensures image doesn't overflow rounded corners */
        }
        /* ADDED: New wrapper for flexbox layout */
        .support-content-wrapper {
            display: flex;
            align-items: stretch;
            min-height: 500px;
            background: #ffffff; /* ADDED: Ensure white background covers any gaps */
        }
        /* ADDED: Styles for text section (left half) */
        .support-text {
            flex: 1;
            padding: 2.5em;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            min-width: 0; /* ADDED: Allows flex item to shrink below content size */
        }
        .support-text h3 { 
            margin: 0 0 1em; 
            color: #0d47a1; 
            font-size: 1.8em;
            display: flex; /* ADDED: For icon alignment */
            align-items: center;
            gap: 0.5em;
            word-wrap: break-word; /* ADDED: Ensures text wraps */
            overflow-wrap: break-word; /* ADDED: Modern text wrapping */
        }
        .support-text h3 i {
            color: #1976D2;
            font-size: 1em;
            flex-shrink: 0; /* ADDED: Prevents icon from shrinking */
        }
        .support-text > p { 
            font-size: 1.1em; 
            line-height: 1.7; 
            margin: 1em 0;
            color: #333;
            word-wrap: break-word; /* ADDED: Ensures text wraps */
            overflow-wrap: break-word; /* ADDED: Modern text wrapping */
        }
        /* ADDED: Container for support contact items */
        .support-list {
            margin: 2em 0;
        }
        /* ADDED: Individual contact card styling */
        .support-item {
            display: flex;
            align-items: center;
            gap: 1em; /* CHANGED: Reduced from 1.5em to 1em for tighter spacing */
            margin: 1.5em 0;
            padding: 1em;
            background: rgba(255,255,255,0.7);
            border-radius: 10px;
            border-left: 5px solid #1976D2;
            flex-wrap: nowrap; /* CHANGED: Prevent wrapping on desktop/tablet */
        }
        .support-number {
            font-size: 2.2em;
            font-weight: bold;
            color: #d32f2f;
            min-width: 80px; /* CHANGED: Reduced from 100px for tighter layout */
            text-align: center;
            flex-shrink: 0; /* ADDED: Prevents number from shrinking */
        }
        .support-details {
            font-size: 1.05em;
            line-height: 1.5;
            word-wrap: break-word; /* ADDED: Ensures text wraps */
            overflow-wrap: break-word; /* ADDED: Modern text wrapping */
            min-width: 0; /* ADDED: Allows flex item to shrink */
            flex: 1; /* ADDED: Takes remaining space */
        }
        .support-details strong {
            color: #0d47a1;
            font-size: 1.15em;
            display: inline-block; /* CHANGED: Allow text to flow naturally */
        }
        /* ADDED: Styles for image section (right half) */
        .support-image {
            flex: 1;
            min-height: 500px;
            overflow: hidden;
        }
        .support-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        
        /* ADDED: Responsive stacking for mobile */
        @media (max-width: 900px) {
            .support-content-wrapper {
                flex-direction: column;
            }
            .support-image {
                min-height: 300px;
            }
            .support-text {
                padding: 2em; /* ADDED: Reduce padding on smaller screens */
            }
            .support-text h3 {
                font-size: 1.5em; /* ADDED: Smaller heading on mobile */
            }
            .support-text > p {
                font-size: 1em; /* ADDED: Smaller text on mobile */
            }
        }

        @media (max-width: 768px) {
            .clean-table { font-size: 14px; }
            .clean-table thead th, .clean-table tbody td { padding: 12px 8px; }
            .big-number { font-size: 28px !important; }
            .support-text {
                padding: 1.5em; /* ADDED: Further reduce padding on mobile */
            }
            .support-item {
                flex-direction: column; /* ADDED: Stack number and details vertically */
                align-items: flex-start;
                gap: 0.5em; /* CHANGED: Reduced gap for mobile */
            }
            .support-number {
                font-size: 1.8em; /* ADDED: Smaller numbers on mobile */
                min-width: auto; /* ADDED: Remove fixed width on mobile */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- UPDATED: Header container with back button matching profile design -->
    <div style="max-width: 1100px; margin: 2em auto 1em; display: flex; align-items: center; gap: 1em;">
        <form method="post" action="main.php" style="margin: 0;">
            <button type="submit" name="action" value="Back to Dashboard" class="profile-back-btn">
                <i class="fas fa-arrow-left"></i> Back
            </button>
        </form>
        <h1 style="text-align: left; font-size: 36px; color: #111; margin: 0; font-weight: 600;">Daily Stress Tracker</h1>
    </div>

    <!-- Log Form -->
    <div id="stress_form_box" class="form-box" style="display: none;">
        <h2 style="text-align: center;">How Are You Feeling Today?</h2>
        <form method="post" action="main.php" id="stressForm">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['current']->getID(); ?>">

            <p style="text-align: center; font-size: 20px; margin: 1em 0;">
                <strong>Today:</strong> <?php echo date('l, F j, Y'); ?>
            </p>

            <div class="stress-scale">
                <h3 style="text-align: center; margin: 0 0 1em;">Stress Level Guide</h3>
                <div class="scale-row"><div class="scale-number scale-1-3">1–3</div> Calm, relaxed, joyful</div>
                <div class="scale-row"><div class="scale-number scale-4-6">4–6</div> Moderate stress – tense but managing</div>
                <div class="scale-row"><div class="scale-number scale-7-10">7–10</div> High to extreme stress – panic or burnout</div>
            </div>

            <div style="text-align: center; margin: 2em 0;">
                <label style="font-size: 22px; display: block; margin-bottom: 1em;">Your Stress Level:</label>
                <input type="number" id="stress_level" name="stress_level" min="1" max="10" required 
                       style="font-size: 56px; width: 140px; text-align: center; padding: 10px; border-radius: 12px; border: 3px solid #333;">
            </div>

            <label for="notes">Notes (optional):</label><br>
            <textarea id="notes" name="notes" placeholder="What’s been on your mind? Any triggers or wins today?"></textarea><br><br>

            <div style="text-align: center;">
                <input type="submit" name="action" value="Save Stress Log" class="btn btn-primary">
            </div>
        </form>

        <div style="text-align: center; margin-top: 1em;">
            <button id="cancelLog" class="btn btn-cancel">Cancel</button>
        </div>
    </div>

    <div style="text-align: center; margin: 3em 0;">
        <button id="openLog" class="btn btn-primary" style="font-size: 22px; padding: 18px 40px;">
            Log Today's Stress Level
        </button>
    </div>

    <!-- High Stress Warning -->
    <?php if ($justLoggedHighStress): ?>
        <div class="highlight">
            <h3>You’re going through a really tough moment right now.</h3>
            <p>Please remember you don’t have to face this alone. Help is available 24/7:</p>
            <div class="number">9-8-8</div>
            <p><strong>9-8-8 Suicide Crisis Helpline</strong> – Call or text<br><strong>9-1-1</strong> for emergencies</p>
        </div>
    <?php endif; ?>

    <!-- Recent Logs + ONE-CLICK BUTTON -->
    <?php
    $userId = $_SESSION['current']->getID();
    $entries = getStressLevels($db, $userId);
    ?>

    <?php if (!empty($entries)): ?>
        <h2 style="text-align: center; margin: 2em 0;">Your Recent Stress Levels</h2>

        <!-- ONE-CLICK "Compare Last 7 Days" -->
        <div style="text-align: center; margin: 2em 0;">
            <button type="button" onclick="compareLast7Days()" class="btn btn-success"
                    style="font-size: 20px; padding: 16px 40px;">
                Compare Last 7 Days
            </button>
        </div>

        <form method="post" action="main.php" id="compareForm">
            <!-- This hidden field fixes the login redirect bug -->
            <input type="hidden" name="action" value="Compare Selected Days">

            <table class="clean-table">
                <thead>
                    <tr>
                        <th style="width:80px;">Select</th>
                        <th>Date</th>
                        <th>Stress Level</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($entries as $index => $entry): ?>
                        <?php 
                        $level = $entry['stress_level'];
                        $color = $level <= 3 ? '#4CAF50' : ($level <= 6 ? '#FF9800' : '#f44336');
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected[]" value="<?= $entry['id'] ?>"
                                       class="day-checkbox" <?= $index < 7 ? 'checked' : '' ?>>
                            </td>
                            <td data-label="Date">
                                <strong><?= date('M j, Y', strtotime($entry['log_date'])) ?></strong><br>
                                <small><?= date('l', strtotime($entry['log_date'])) ?></small>
                            </td>
                            <td data-label="Level">
                                <span class="big-number" style="color:<?= $color ?>;">
                                    <?= $level ?>/10
                                </span>
                            </td>
                            <td data-label="Notes" style="text-align:left; max-width:400px;">
                                <?= nl2br(htmlspecialchars($entry['notes'])) ?: '<em style="color:#999;">No notes</em>' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div style="text-align: center; margin: 2.5em 0;">
                <input type="submit" name="action" value="Compare Selected Days" class="btn btn-primary">
            </div>
        </form>
    <?php else: ?>
        <p style="text-align: center; font-size: 20px; color: #666; margin: 3em;">
            No stress logs yet. Start tracking how you feel today!
        </p>
    <?php endif; ?>

    <!-- Side-by-Side Comparison -->
    <?php if (isset($_SESSION['compare_stress']) && count($_SESSION['compare_stress']) >= 2): ?>
        <?php
        $ids = array_map('intval', $_SESSION['compare_stress']);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $stmt = $db->prepare("SELECT * FROM stress_levels WHERE id IN ($placeholders) ORDER BY log_date DESC");
        $stmt->execute($ids);
        $compareEntries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <h2 style="text-align: center; margin: 3em 0 1em;">Side-by-Side Comparison</h2>
        <div class="comparison">
            <?php foreach ($compareEntries as $e): ?>
                <?php 
                $lvl = $e['stress_level'];
                $color = $lvl <= 3 ? '#4CAF50' : ($lvl <= 6 ? '#FF9800' : '#f44336');
                ?>
                <div class="day-card">
                    <h3><?= date('F j, Y', strtotime($e['log_date'])) ?><br><small><?= date('l', strtotime($e['log_date'])) ?></small></h3>
                    <div class="stress-level-big" style="color: <?= $color ?>;"><?= $lvl ?> / 10</div>
                    <p><strong>Notes:</strong><br><?= nl2br(htmlspecialchars($e['notes'])) ?: '<em style="color:#888;">No notes</em>' ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin: 3em 0;">
            <form method="post" action="main.php">
                <input type="hidden" name="action" value="Clear Comparison">
                <input type="submit" value="Clear Comparison" class="btn btn-danger">
            </form>
        </div>
    <?php endif; ?>

    <!-- Support Resources -->
    <!-- UPDATED: Changed from simple centered text layout to split-screen design with image -->
</div> <!-- Close container to allow full-width section -->

<div class="support-resources">
        <div class="support-content-wrapper">
            <!-- ADDED: Text section on left side -->
            <div class="support-text">
                <h3><i class="fas fa-comment-medical"></i> Need to Talk to Someone?</h3>
                <p>You don't have to face this alone. Our professional healthcare team and support services are here for you 24/7.</p>
                <!-- ADDED: Structured list of support contacts -->
                <div class="support-list">
                    <div class="support-item">
                        <div class="support-number">8-1-1</div>
                        <div class="support-details">
                            <strong>HealthLink BC</strong><br>
                            Free health advice 24/7
                        </div>
                    </div>
                    <div class="support-item">
                        <div class="support-number">9-8-8</div>
                        <div class="support-details">
                            <strong>Suicide Crisis Helpline</strong><br>
                            Call or text anytime, day or night
                        </div>
                    </div>
                    <div class="support-item">
                        <div class="support-number">9-1-1</div>
                        <div class="support-details">
                            <strong>Emergency Services</strong><br>
                            For immediate danger
                        </div>
                    </div>
                </div>
                <p style="margin-top: 1.5em; font-size: 0.9em; color: #555; font-style: italic;">
                    This is not medical advice. Always consult healthcare professionals for guidance.
                </p>
            </div>
            <!-- ADDED: Image section on right side -->
            <div class="support-image">
                <img src="img/mental-health-support.jpg" alt="Mental Health Support" onerror="this.src='https://images.unsplash.com/photo-1573497491208-6b1acb260507?w=600&h=500&fit=crop'">
            </div>
        </div>
    </div>
</div> <!-- End support resources -->

<div class="container"> <!-- Reopen container for scripts -->

<script>
    // Open/Close form
    document.getElementById('openLog').onclick = () => {
        document.getElementById('stress_form_box').style.display = 'block';
        document.getElementById('openLog').style.display = 'none';
    };
    document.getElementById('cancelLog').onclick = () => {
        document.getElementById('stress_form_box').style.display = 'none';
        document.getElementById('openLog').style.display = '';
        document.getElementById('stressForm').reset();
    };

    // Validation
    document.getElementById('stressForm').onsubmit = (e) => {
        const level = document.getElementById('stress_level').value;
        if (!level || level < 1 || level > 10) {
            alert('Please choose a stress level between 1 and 10.');
            e.preventDefault();
        }
    };

    // Compare Last 7 Days of Logs Function
    function compareLast7Days() {
        document.querySelectorAll('.day-checkbox').forEach(cb => cb.checked = false);
        document.querySelectorAll('.day-checkbox').forEach((cb, i) => {
            if (i < 7) cb.checked = true;
        });
        document.getElementById('compareForm').submit();
    }
</script>

</body>
</html>
