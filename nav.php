
<?php

require_once('alerts.php');
if(session_status() === PHP_SESSION_NONE){

	session_start();
}


	$names = ['', 'Profile', 'Booking', 'Logging', 'Monitoring', 'Stress Tracker', 'Sign Out', 'Dashboard', 'Search'];

	echo '<nav>
	<form method="post" action="main.php" style="display: flex; flex-direction: row; border-bottom: solid 2px black;>';
	
	

	foreach($names as $name){
			
			
		echo '<input type="submit" name="action" value="' . $name .  '" style="margin: 0.5em; font-size: 18px; padding: 0.2em;" id="' . $name . '">';		
		
		if($name == 'Search'){
			echo '<input type="text" name="query">';
		}	
	}


//	echo '<input type="submit" style="margin: 0.5em; font-size: 18px; padding: 0.2em;"  name="action" id="alerts" value="Alerts: ' . $_SESSION['alerts']->getSize() . '">';

	echo '</form></nav>';

//	$_SESSION['alerts']->display();

//	echo '<script> document.getElementById("display").style.visibility = "visible"; </script>';
//	echo '<form method="post" action="main.php">';
	echo '<div class="alerts-container">';
	echo '<button class="alerts-btn" id="alerts"><i class="fas fa-bell"></i> Alerts: <span class="alerts-count">' . $_SESSION['alerts']->getSize() . '</span></button>';
	//	echo '</form>'

	echo '<div id="alertTbl" class="alerts-dropdown">';
	echo '<div class="alerts-header">Notifications</div>';
	$category;
	$arr = $_SESSION['alerts']->getArray();

	if(empty($arr) || count($arr) == 0){
		echo '<div class="alerts-empty"><i class="fas fa-check-circle"></i> No alerts</div>';
	} else {
		forEach($arr as $i => $alert){	
			if(!is_array($alert)){
				echo '<div class="alert-item" data-status="' . $alert->getStatus() . '">';
				echo '<div class="alert-category"><i class="fas fa-info-circle"></i> ' . $_SESSION['alerts']->resolveCat($alert->getCategory()) . '</div>';
				echo '<div class="alert-message">' . $alert->getMsg() . '</div>';
				echo "<form method='post' action='main.php' class='alert-form'>";
				echo '<button type="submit" name="action" value="Delete Alert" class="alert-delete-btn"><i class="fas fa-times"></i></button>';
				echo "<input type='hidden' name='Category' value=" . $alert->getCategory() . ">";
				echo "<input type='hidden' name='Code' value=" . $alert->getCode() . ">";	
				echo "</form>";
				echo '</div>';
			}
		}
	}
	echo "</div>";
	echo "</div>"; // Close alerts-container	
?>

<script>
	document.getElementById("alerts").addEventListener('click', function(e){
		e.preventDefault();
		let element = document.getElementById("alertTbl");
		
		if(element.classList.contains("show")){
			element.classList.remove("show");
		} else {
			element.classList.add("show");
		}
	});

	// Close dropdown when clicking outside
	document.addEventListener('click', function(e) {
		let alertBtn = document.getElementById("alerts");
		let alertTbl = document.getElementById("alertTbl");
		if (!alertBtn.contains(e.target) && !alertTbl.contains(e.target)) {
			alertTbl.classList.remove("show");
		}
	});
</script>

<style>
/* Alerts container */
.alerts-container {
	position: relative;
	display: inline-block;
}

/* Alerts button styling */
.alerts-btn {
	margin: 0.5em;
	font-size: 16px;
	padding: 0.6em 1.2em;
	position: relative;
	background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
	border: none;
	border-radius: 8px;
	cursor: pointer;
	font-weight: 600;
	color: #fff;
	box-shadow: 0 4px 12px rgba(220,53,69,0.3);
	transition: transform 0.12s ease, box-shadow 0.12s ease;
	display: inline-flex;
	align-items: center;
	gap: 0.5em;
}

.alerts-btn:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 16px rgba(220,53,69,0.4);
}

.alerts-btn i {
	font-size: 18px;
}

.alerts-count {
	font-weight: bold;
}

/* Alerts dropdown */
.alerts-dropdown {
	display: none;
	position: absolute;
	top: calc(100% + 0.5em);
	left: 0;
	width: 400px;
	max-width: 90vw;
	max-height: 500px;
	overflow-y: auto;
	background: #ffffff;
	border-radius: 12px;
	box-shadow: 0 10px 28px rgba(0,0,0,0.15);
	border: 1px solid rgba(0,0,0,0.06);
	z-index: 1000;
}

.alerts-dropdown.show {
	display: block;
}

/* Alerts header */
.alerts-header {
	padding: 1em 1.2em;
	background: linear-gradient(180deg, #f8f9fa 0%, #e9ecef 100%);
	border-bottom: 1px solid rgba(0,0,0,0.06);
	font-weight: 700;
	font-size: 16px;
	color: #111;
	border-radius: 12px 12px 0 0;
}

/* Empty state */
.alerts-empty {
	padding: 2em;
	text-align: center;
	color: #6c757d;
	font-size: 14px;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 0.5em;
}

.alerts-empty i {
	color: #28a745;
	font-size: 20px;
}

/* Individual alert item */
.alert-item {
	padding: 1em 1.2em;
	border-bottom: 1px solid #f0f0f0;
	position: relative;
	transition: background-color 0.2s ease;
}

.alert-item:last-child {
	border-bottom: none;
	border-radius: 0 0 12px 12px;
}

.alert-item:hover {
	background-color: #f8f9fa;
}

/* Alert category */
.alert-category {
	font-weight: 600;
	font-size: 13px;
	color: #007BFF;
	margin-bottom: 0.4em;
	display: flex;
	align-items: center;
	gap: 0.4em;
}

.alert-category i {
	font-size: 14px;
}

/* Alert message */
.alert-message {
	font-size: 14px;
	color: #333;
	line-height: 1.4;
	padding-right: 2em;
}

/* Alert delete button */
.alert-form {
	position: absolute;
	top: 1em;
	right: 1em;
	margin: 0;
}

.alert-delete-btn {
	background: #dc3545;
	color: white;
	border: none;
	border-radius: 50%;
	width: 28px;
	height: 28px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 12px;
	transition: transform 0.12s ease, background-color 0.12s ease;
	box-shadow: 0 2px 6px rgba(220,53,69,0.3);
}

.alert-delete-btn:hover {
	background: #c82333;
	transform: scale(1.1);
}

/* Status-based styling */
.alert-item[data-status="0"] {
	border-left: 4px solid #28a745;
}

.alert-item[data-status="1"] {
	border-left: 4px solid #ffc107;
}

.alert-item[data-status="2"] {
	border-left: 4px solid #dc3545;
}
</style>

