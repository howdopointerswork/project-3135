
<!DOCTYPE html>
<?php
require_once('user.php');
require_once('db.php');
require_once('alerts.php');

if(session_status() === PHP_SESSION_NONE){
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

//$_SESSION['current']->getName();
//echo 'logged in as: ' . $_SESSION['current']->getName() . "<br> and" . $_SESSION['current']->getID() . " is ID<br>";
?>
<html>

    <head>
        <title>Log Activities</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="css/dash.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <style>
            /* Back button styling matching stress tracker design */
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
            
            /* Delete Modal Styles */
            .delete-modal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0,0,0,0.5);
                backdrop-filter: blur(5px);
            }
            
            .delete-modal-content {
                background-color: #fff;
                margin: 15% auto;
                padding: 2em;
                border-radius: 12px;
                width: 400px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                text-align: center;
                animation: slideIn 0.3s ease-out;
            }
            
            @keyframes slideIn {
                from { transform: translateY(-50px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            
            .delete-icon {
                font-size: 48px;
                color: #f44336;
                margin-bottom: 1em;
            }
            
            .delete-title {
                color: #333;
                font-size: 24px;
                font-weight: 600;
                margin-bottom: 0.5em;
            }
            
            .delete-message {
                color: #666;
                font-size: 16px;
                margin-bottom: 2em;
                line-height: 1.5;
            }
            
            .delete-buttons {
                display: flex;
                gap: 1em;
                justify-content: center;
            }
            
            .delete-btn-cancel {
                padding: 0.75em 1.5em;
                background: #f5f5f5;
                color: #666;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .delete-btn-cancel:hover {
                background: #e0e0e0;
            }
            
            .delete-btn-confirm {
                padding: 0.75em 1.5em;
                background: #f44336;
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.2s;
            }
            
            .delete-btn-confirm:hover {
                background: #d32f2f;
                transform: translateY(-1px);
            }
        </style>
    </head>

    <body>
        <div style="display: flex; align-items: center; justify-content: flex-start; gap: 2em; max-width: 1200px; margin: 0 auto 2em auto; padding: 0 1em;">
            <form method='post' action='main.php' style='margin: 0;'>
                <button type='submit' name='action' value='Back' class='profile-back-btn'>
                    <i class="fas fa-arrow-left"></i> Back
                </button>
            </form>
            <h1 id="log_title" style="text-align: left; font-size: 36px; color: #111; margin: 0; font-weight: 600;">Activities</h1>
        </div>
        
            
        
        <!-- Add Activity Form (Hidden by Default) -->
        <div id="logging_system" style="display: none; background: #fff; padding: 2em; margin: 2em auto; max-width: 700px; border-radius: 12px; box-shadow: 0 10px 28px rgba(0,0,0,0.12);">
            <h2 style="color: #1976D2; margin-bottom: 1.5em; display: flex; align-items: center; gap: 0.5em;">
                <i class="fas fa-plus-circle"></i> Log New Activity
            </h2>
            
            <form method='post' action='main.php' style="display: grid; gap: 1.2em;">
                <input type="hidden" name="userid" value="<?php echo $_SESSION['current']->getID(); ?>">

                <?php
                    $categories = ['calories', 'sleep', 'water', 'exercise', 'meds'];
                    $labels = ['Calorie Intake', 'Hours of Sleep', 'Water Intake (mL)', 'Hours of Exercise', 'Medication Taken?'];
                    $icons = ['utensils', 'bed', 'tint', 'dumbbell', 'pills'];

                    foreach($categories as $index => $cat){
                        $type = $index === count($labels)-1 ? 'checkbox' : 'number';
                        $placeholder = $index === count($labels)-1 ? '' : 'placeholder="Enter value"';
                        $step = ($cat === 'sleep' || $cat === 'exercise') ? 'step="0.1"' : '';
                        
                        echo '<div style="display: flex; flex-direction: column;">';
                        echo '<label for="' . $cat . '" style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;">';
                        echo '<i class="fas fa-' . $icons[$index] . '"></i> ' . $labels[$index];
                        echo '</label>';
                        
                        if($type === 'checkbox') {
                            echo '<label style="display: flex; align-items: center; gap: 0.5em; font-size: 16px;">';
                            echo '<input type="checkbox" name="' . $cat . '" id="' . $cat . '" style="transform: scale(1.2);">';
                            echo 'Yes, I took my medication today';
                            echo '</label>';
                        } else {
                            echo '<input type="' . $type . '" name="' . $cat . '" id="' . $cat . '" ' . $placeholder . ' ' . $step . ' min="0"';
                            echo ' style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;"';
                            echo ' onmouseover="this.style.borderColor=\'#1976D2\'" onmouseout="this.style.borderColor=\'#ddd\'">';
                        }
                        echo '</div>';
                    }
                ?>
                
                <div style="display: flex; gap: 1em; justify-content: flex-end; margin-top: 1em;">
                    <button type="button" id="cancel" 
                            style="padding: 0.75em 1.5em; background: #f5f5f5; color: #666; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    
                    <button type="submit" name="action" value="Add Activity"
                            style="font-size: 18px; padding: 0.7em 1.5em; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 5px;">
                        <i class="fas fa-plus"></i> Add Activity
                    </button>
                </div>
            </form>
        </div>

        <!-- Add Activity Button -->
        <div style="text-align: center; margin: 2rem;">
            <button id="add" name="add" style="font-size: 18px; padding: 0.7em 1.5em; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 5px;">
                <i class="fas fa-plus"></i> Add Activity
            </button>
        </div>

        <?php
        $results = getActivities($db, $_SESSION['current']->getID());
        $names = ['Log ID', 'User ID', 'Calories', 'Sleep (hrs)', 'Water (mL)', 'Exercise (hrs)', 'Medication', 'Date'];
        $icons = ['hashtag', 'user', 'utensils', 'bed', 'tint', 'dumbbell', 'pills', 'calendar'];

        if(!empty($results)){
            echo '<div style="max-width: 1200px; margin: 0 auto; padding: 0 1em;">';
            echo '<div style="background: #fff; border-radius: 12px; box-shadow: 0 10px 28px rgba(0,0,0,0.12); overflow: hidden;">';
            echo '<div style="background: linear-gradient(135deg, #1976D2 0%, #2196F3 100%); color: white; padding: 1.5em; text-align: center;">';
            echo '<h2 style="margin: 0; display: flex; align-items: center; justify-content: center; gap: 0.5em;"><i class="fas fa-chart-line"></i> Activity History</h2>';
            echo '</div>';
            
            echo '<div style="overflow-x: auto;">';
            echo '<table style="width: 100%; border-collapse: collapse;">';
            
            // Header
            echo '<thead style="background: #f8f9fa;">';
            echo '<tr>';
            echo '<th style="padding: 1em; text-align: center; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;"><i class="fas fa-cog"></i> Actions</th>';
            foreach($names as $index => $name){
                echo '<th style="padding: 1em; text-align: center; font-weight: 600; color: #333; border-bottom: 2px solid #dee2e6;">';
                echo '<i class="fas fa-' . $icons[$index] . '"></i> ' . $name;
                echo '</th>';
            }
            echo '</tr>';
            echo '</thead>';
            
            // Body
            echo '<tbody>';
            foreach($results as $rowIndex => $result){
                $rowClass = $rowIndex % 2 === 0 ? 'background: white;' : 'background: #f8f9fa;';
                echo '<tr style="' . $rowClass . ' transition: all 0.2s;" onmouseover="this.style.background=\'#e3f2fd\'" onmouseout="this.style.background=\'' . ($rowIndex % 2 === 0 ? 'white' : '#f8f9fa') . '\'">';
                
                // Action buttons
                echo '<td style="padding: 1em; text-align: center; border-bottom: 1px solid #dee2e6;">';
                echo '<form method="post" action="main.php" style="display: flex; gap: 0.5em; justify-content: center;">';
                echo '<input type="hidden" name="actID" value="' . $result[1] . '">';
                echo '<input type="hidden" name="logID" value="' . $result[0] . '">';
                echo '<button type="submit" name="action" value="Edit Activity" style="background: #4CAF50; color: white; border: none; padding: 0.5em 1em; border-radius: 6px; cursor: pointer; font-size: 14px;" title="Edit Activity"><i class="fas fa-edit"></i></button>';
                echo '<button type="button" onclick="showDeleteModal(' . $result[1] . ', ' . $result[0] . ')" style="background: #f44336; color: white; border: none; padding: 0.5em 1em; border-radius: 6px; cursor: pointer; font-size: 14px;" title="Delete Activity"><i class="fas fa-trash"></i></button>';
                echo '</form>';
                echo '</td>';
                
                // Data cells
                foreach($result as $index => $val){
                    if(is_numeric($index)){
                        $cellStyle = 'padding: 1em; text-align: center; border-bottom: 1px solid #dee2e6; font-size: 15px;';
                        if($index === 6) { // Medication column
                            $val = $val ? '<span style="color: #4CAF50; font-weight: 600;"><i class="fas fa-check"></i> Yes</span>' : '<span style="color: #f44336; font-weight: 600;"><i class="fas fa-times"></i> No</span>';
                        } elseif($index === 7) { // Date column
                            $val = date('M j, Y', strtotime($val));
                        }
                        echo '<td style="' . $cellStyle . '">' . $val . '</td>';
                    }
                }
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div style="text-align: center; padding: 3em; max-width: 600px; margin: 0 auto;">';
            echo '<div style="background: #fff9e6; border-left: 4px solid #ffa500; padding: 2em; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">';
            echo '<h3 style="color: #ff8c00; margin-top: 0; display: flex; align-items: center; justify-content: center; gap: 0.5em;"><i class="fas fa-info-circle"></i> No Activities Yet</h3>';
            echo '<p style="color: #333; font-size: 16px;">Start tracking your health by logging your first activity!</p>';
            echo '<p style="color: #666; font-size: 14px;">Click the "Log New Activity" button above to get started.</p>';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="delete-modal">
            <div class="delete-modal-content">
                <div class="delete-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h2 class="delete-title">Delete Activity?</h2>
                <p class="delete-message">
                    Are you sure you want to delete this activity? This action cannot be undone.
                </p>
                <div class="delete-buttons">
                    <button type="button" class="delete-btn-cancel" onclick="closeDeleteModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="button" class="delete-btn-confirm" onclick="confirmDelete()">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>

        <!-- Hidden form for delete submission -->
        <form id="deleteForm" method="post" action="main.php" style="display: none;">
            <input type="hidden" name="actID" id="deleteActID">
            <input type="hidden" name="logID" id="deleteLogID">
            <input type="hidden" name="action" value="Delete Activity">
        </form>
        
<script>

let currentActID, currentLogID;

function showDeleteModal(actID, logID) {
    currentActID = actID;
    currentLogID = logID;
    document.getElementById('deleteModal').style.display = 'block';
}

function closeDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}

function confirmDelete() {
    document.getElementById('deleteActID').value = currentActID;
    document.getElementById('deleteLogID').value = currentLogID;
    document.getElementById('deleteForm').submit();
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target == document.getElementById('deleteModal')) {
        closeDeleteModal();
    }
}

document.getElementById('add').addEventListener('click', function(){
    

    let button = document.getElementById('logging_system');


    button.style.display = '';
    button.style.listStyleType = 'none';






});

document.getElementById('cancel').addEventListener('click', function(){

    document.getElementById('logging_system').style.display = 'none';

});


</script>

    
    </body>

</html>

