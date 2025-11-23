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
    <link rel="stylesheet" href="css/main.css">
    <style>
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
        .support-resources { background: #e3f2fd; border: 3px solid #1976D2; border-radius: 14px; padding: 1.8em; margin: 3em auto; max-width: 900px; text-align: center; box-shadow: 0 6px 16px rgba(0,0,0,0.1); }
        .support-resources h3 { margin: 0 0 1em; color: #0d47a1; font-size: 1.5em; }
        .support-resources p { font-size: 1.15em; line-height: 1.7; margin: 1em 0; }
        .support-resources .number { font-size: 2em; font-weight: bold; color: #d32f2f; margin: 0.5em 0; }
        .highlight { background: #fff3e0; padding: 1.5em; border-radius: 10px; margin: 1.5em 0; border-left: 6px solid #ef6c00; }

        @media (max-width: 768px) {
            .clean-table { font-size: 14px; }
            .clean-table thead th, .clean-table tbody td { padding: 12px 8px; }
            .big-number { font-size: 28px !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align: center; margin-bottom: 0.5em;">Daily Stress Tracker</h1>

    <!-- Back Button -->
    <div style="text-align: center; margin: 1.5em;">
        <form method="post" action="main.php">
            <input type="submit" name="action" value="Back to Dashboard" class="btn">
        </form>
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
    <div class="support-resources">
        <h3>Need to talk to someone?</h3>
        <p>You can always call these free, confidential services in British Columbia:</p>
        <p class="number">8-1-1</p>
        <p><strong>HealthLink BC</strong> – Free health advice 24/7</p>
        <p class="number">9-8-8</p>
        <p><strong>Suicide Crisis Helpline</strong> – Call or text 24/7</p>
        <p class="number">9-1-1</p>
        <p><strong>Emergency</strong> – For immediate danger</p>
        <p style="margin-top: 1.5em; font-size: 1em; color: #555;">
            Not medical advice.
        </p>
    </div>
</div>

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