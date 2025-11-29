<!DOCTYPE html>
<?php
if(session_status() === PHP_SESSION_NONE){
	session_start();
}
?>
<html>
<head>
	<title>Edit Activity</title>
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
		<form method='post' action='logging.php' style='margin: 0;'>
			<button type='submit' class='profile-back-btn'>
				<i class="fas fa-arrow-left"></i> Back
			</button>
		</form>
		<h1 style="text-align: left; font-size: 36px; color: #111; margin: 0; font-weight: 600;">Edit Activity</h1>
	</div>

	<!-- Edit Activity Form -->
	<div style="background: #fff; padding: 2em; margin: 2em auto; max-width: 700px; border-radius: 12px; box-shadow: 0 10px 28px rgba(0,0,0,0.12);">
		<h2 style="color: #1976D2; margin-bottom: 1.5em; display: flex; align-items: center; gap: 0.5em;">
			<i class="fas fa-edit"></i> Update Activity Details
		</h2>
		
		<form method='post' action='main.php' style="display: grid; gap: 1.2em;">
			<input type='hidden' value='<?php echo isset($activity[0]) ? $activity[0] : ''; ?>' name='logID'>
			
			<?php
				$names = ['Calories', 'Sleep (hrs)', 'Water (mL)', 'Exercise (hrs)', 'Medication'];
				$icons = ['utensils', 'bed', 'tint', 'dumbbell', 'pills'];
				
				// Safely get activity values
				$values = [];
				if(isset($activity) && is_array($activity)) {
					$values = array_slice($activity, 2, 5); // Get activity values (skip ID and user_id)
				} else {
					$values = [0, 0, 0, 0, 0]; // Default values
				}
				
				for($i = 0; $i < 5; $i++){
					echo '<div style="display: flex; flex-direction: column;">';
					echo '<label style="font-weight: 600; color: #333; margin-bottom: 0.5em; font-size: 14px;">';
					echo '<i class="fas fa-' . $icons[$i] . '"></i> ' . $names[$i];
					echo '</label>';
					
					if($i == 4) { // Medication checkbox
						$checked = (isset($values[$i]) && $values[$i]) ? 'checked' : '';
						echo '<label style="display: flex; align-items: center; gap: 0.5em; font-size: 16px;">';
						echo '<input type="checkbox" name="data[]" value="1" ' . $checked . ' style="transform: scale(1.2);">';
						echo 'Yes, I took my medication';
						echo '</label>';
						if(!isset($values[$i]) || !$values[$i]) {
							echo '<input type="hidden" name="data[]" value="0">';
						}
					} else {
						$type = ($i == 1 || $i == 3) ? 'number" step="0.1' : 'number';
						$value = isset($values[$i]) ? htmlspecialchars($values[$i]) : '0';
						echo '<input type="' . $type . '" name="data[]" value="' . $value . '" min="0"';
						echo ' style="padding: 0.75em; border: 2px solid #ddd; border-radius: 8px; font-size: 15px; background: #f8f9fa; transition: all 0.2s;"';
						echo ' onmouseover="this.style.borderColor=\'#1976D2\'" onmouseout="this.style.borderColor=\'#ddd\'">';
					}
					echo '</div>';
				}
			?>
			
			<div style="display: flex; gap: 1em; justify-content: flex-end; margin-top: 1em;">
				<button type="button" onclick="window.location.href='logging.php'" 
						style="padding: 0.75em 1.5em; background: #f5f5f5; color: #666; border: 2px solid #ddd; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
						onmouseover="this.style.background='#e0e0e0'" onmouseout="this.style.background='#f5f5f5'">
					<i class="fas fa-times"></i> Cancel
				</button>
				
				<button type="submit" name="action" value="Update Activity"
						style="font-size: 18px; padding: 0.7em 1.5em; background: #2196F3; color: white; border: none; cursor: pointer; border-radius: 5px;">
					<i class="fas fa-save"></i> Update Activity
				</button>
			</div>
		</form>
	</div>
</body>
</html>