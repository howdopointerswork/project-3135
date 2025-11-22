<!DOCTYPE html>
<?php
require_once('user.php');
require_once('db.php');
require_once('alerts.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('nav.php');
?>

<html>
<head>
    <title>Stress Tracker</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/main.css">
    <style>
        .container { max-width: 1000px; margin: 2em auto; padding: 1em; }
        .form-box { background: #f8f8f8; padding: 2em; border: 2px solid #333; border-radius: 10px; margin: 2em auto; max-width: 600px; }
        textarea { width: 100%; height: 180px; font-size: 16px; padding: 10px; }
        table { width: 90%; margin: 2em auto; border-collapse: collapse; }
        th, td { border: 2px solid black; padding: 12px; text-align: center; }
        th { background: #f0f0f0; font-weight: bold; }
        .compare-table th { background: #e0e0e0; }
        .compare-table td { vertical-align: top; text-align: left; padding: 15px; }
        .btn { font-size: 18px; padding: 12px 24px; margin: 10px; cursor: pointer; }
        .btn-primary { background: #2196F3; color: white; border: none; }
        .btn-danger { background: #f44336; color: white; border: none; }
        .btn-cancel { background: #999; color: white; }
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align: center;">Daily Stress Tracker</h1>

    <!-- Back Button -->
    <div style="text-align: center; margin: 1em;">
        <form method="post" action="main.php" style="display: inline;">
            <input type="submit" name="action" value="Back to Dashboard" class="btn">
        </form>
    </div>

    <!-- Log Today's Stress Form -->
    <div id="stress_form_box" class="form-box" style="display: none;">
        <h2 style="text-align: center;">Log Today's Stress</h2>
        <form method="post" action="main.php" id="stressForm">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['current']->getID(); ?>">

            <label><strong>Date:</strong> <?php echo date('l, F j, Y'); ?> (Today)</label><br><br>
            <input type="hidden" name="date" value="<?php echo date('Y-m-d'); ?>">

            <label><strong>Stress Level (1 = Very Relaxed, 10 = Extremely Stressed):</strong></label><br>
            <input type="number" name="stress_level" min="1" max="10" id="stress_level" style="font-size: 20px; width: 100px; padding: 10px;" required><br><br>

            <label><strong>Notes (optional):</strong></label><br>
            <textarea name="notes" id="notes" placeholder="How was your day? What caused stress or calm?"></textarea><br><br>

            <div style="text-align: center;">
                <input type="submit" name="action" value="Save Stress Log" class="btn btn-primary">
                <button type="button" id="cancelLog" class="btn btn-cancel">Cancel</button>
            </div>
        </form>
    </div>

    <!-- Open Log Button -->
    <div style="text-align: center; margin: 2em;">
        <button id="openLog" class="btn btn-primary" style="font-size: 22px; padding: 16px 32px;">
            Log Today's Stress
        </button>
    </div>

    <!-- Stress History -->
    <?php
    $results = getStressLevels($db, $_SESSION['current']->getID());
    if (!empty($results)):
    ?>
        <h2 style="text-align: center;">Your Stress History</h2>
        <form method="post" action="main.php" id="compareForm">
            <table>
                <tr>
                    <th>Select</th>
                    <th>Date</th>
                    <th>Stress Level</th>
                    <th>Notes</th>
                </tr>
                <?php foreach ($results as $r): 
                    $noteDisplay = $r['notes'] ? nl2br(htmlspecialchars($r['notes'])) : '<em>No notes</em>';
                ?>
                <tr>
                    <td><input type="checkbox" name="selected[]" value="<?= $r['id'] ?>"></td>
                    <td><?= date('M j, Y', strtotime($r['log_date'])) ?></td>
                    <td><strong><?= $r['stress_level'] ?>/10</strong></td>
                    <td style="text-align: left; max-width: 400px;"><?= $noteDisplay ?></td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div style="text-align: center; margin: 2em;">
                <input type="submit" name="action" value="Compare Selected Days" class="btn btn-primary">
            </div>
        </form>
    <?php else: ?>
        <p style="text-align: center; font-size: 20px; color: #666;">
            No stress entries yet. Click the button above to start logging!
        </p>
    <?php endif; ?>

    <!-- Side-by-Side Comparison -->
    <?php if (isset($_SESSION['compare_stress']) && !empty($_SESSION['compare_stress'])): ?>
        <h2 style="text-align: center; margin-top: 3em;">Side-by-Side Comparison</h2>
        <?php
        $ids = $_SESSION['compare_stress'];
        $entries = [];
        foreach ($ids as $id) {
            $e = getStressLevel($db, $id);
            if ($e && $e['user_id'] == $_SESSION['current']->getID()) {
                $entries[] = $e;
            }
        }
        if (count($entries) >= 2):
        ?>
            <table class="compare-table">
                <tr>
                    <th>Attribute</th>
                    <?php foreach ($entries as $e): ?>
                        <th><?= date('M j, Y', strtotime($e['log_date'])) ?><br><small><?= date('l', strtotime($e['log_date'])) ?></small></th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td><strong>Stress Level</strong></td>
                    <?php foreach ($entries as $e): ?>
                        <td style="font-size: 24px; font-weight: bold; color: <?= $e['stress_level'] >= 7 ? 'red' : ($e['stress_level'] <= 3 ? 'green' : 'orange') ?>;">
                            <?= $e['stress_level'] ?> / 10
                        </td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td><strong>Notes</strong></td>
                    <?php foreach ($entries as $e): ?>
                        <td style="text-align: left; line-height: 1.6;">
                            <?= $e['notes'] ? nl2br(htmlspecialchars($e['notes'])) : '<em>No notes</em>' ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            </table>

            <div style="text-align: center; margin: 2em;">
                <form method="post" action="main.php" style="display: inline;">
                    <input type="hidden" name="action" value="Clear Comparison">
                    <input type="submit" value="Clear Comparison" class="btn btn-danger">
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    const openBtn = document.getElementById('openLog');
    const formBox = document.getElementById('stress_form_box');
    const cancelBtn = document.getElementById('cancelLog');
    const stressForm = document.getElementById('stressForm');
    const compareForm = document.getElementById('compareForm');

    openBtn.addEventListener('click', () => {
        formBox.style.display = 'block';
        openBtn.style.display = 'none';
    });

    cancelBtn.addEventListener('click', () => {
        formBox.style.display = 'none';
        openBtn.style.display = '';
        stressForm.reset();
    });

    // Validate stress level form
    stressForm.addEventListener('submit', function(e) {
        const level = document.getElementById('stress_level').value;
        if (!level || level < 1 || level > 10) {
            alert('Please enter a stress level between 1 and 10.');
            e.preventDefault();
        }
    });

    // Validate compare form
    compareForm.addEventListener('submit', function(e) {
        const checked = document.querySelectorAll('input[name="selected[]"]:checked');
        if (checked.length < 2) {
            alert('Please select at least TWO days to compare.');
            e.preventDefault();
        }
    });
</script>

</body>
</html>