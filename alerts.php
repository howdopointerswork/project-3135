<?php

	class Alert{

		private int $cat = 0; //which type of alert - monitor, logging, etc.
		
		private int $status = 0; //nature of the alert - is it good, bad, etc.

		private string $msg = ''; //what the alert displays

		private int $code = 0; //for determining which alerts to display

		public function __construct($cat = 0, $status = 0, $msg = '', $code = 0){ //allows for no-arg constructor

			$this->cat = $cat;

			$this->status = $status;

			$this->msg = $msg;

			$this->code = $code;
		}


		public function getCategory() : int{
			
			return $this->cat;
		}

		public function setCategory(int $n){

			$this->cat = $n;
		}


		public function getStatus() : int{

			return $this->status;
		}

		public function setStatus(int $n){

			$this->status = $n;
		}


		public function getMsg() : string{

			return $this->msg;
		}

		public function setMsg($msg){

			$this->msg = $msg;
		}

		public function setCode(int $n){

			$this->code = $n;
		}

		public function getCode() : int{

			return $this->code;
		}
		

	}

	class AlertSystem{

		private int $code;

		private $alerts = [];

		
		public function __construct(){}


		public function addAlert($alert){

			$this->alerts[] = $alert;
			//echo '<script> alert("Alert(' . $alert->getCode() . '): ' . $alert->getMsg() . '") </script>';
		}

		public function clearAlerts(){

			$this->alerts = [];
		}


		public function destroyAlert($cat, $code){

	
			foreach($this->getArray() as $i => $alert){
	
				if($alert->getCategory() == $cat && $alert->getCode() == $code){
									$this->alerts[$i] = [];
					$this->alerts = array_filter($this->alerts);
					
				}
			}

		}


		public function alert_monitor($alert, $mon){

			if($alert->getCategory() == 1){

				$hi_lo;
				$lvl;
				$check;	
				
				if($alert->getCode() % 2 == 0){

					$hi_lo = 'high';
					$check = 0;
				}else{

					$hi_lo = 'low';
					$check = -1;
				}

				$base = $check;
				$i = 0;
				$step = 2;
				$set;
				while($check != $alert->getCode()){

					
					$check += $step;
					
					if($check == ($base+(++$i*$step))){

						$set = true;

						switch($i){

						case 1:
							$lvl = 'normal';
							$hi_lo = '';
							//echo $alert->getCode();
							break;
						case 2:
							$lvl = 'moderately';
							break;
						case 3:
							$lvl = 'abnormally';							 
							break;
						}		
							
					}

					if($set){
						break;
					}
	

				}


				
				$txt = "Levels for $mon are $lvl $hi_lo";
				$alert->setMsg($txt);

				$this->addAlert($alert);
			}
		}



		public function getAlert(int $n){

			return $this->alerts[$n];
		}

		public function getSize() : int{
		
			return count($this->alerts);
		}


		public function findCat(int $n) : bool{


		//	echo "Searching " . $this->getSize() . " items...";
			foreach($this->alerts as $alert){


				if($alert->getCategory() == $n){

					return true;
				}
			}

			return false;
		}


		public function display(){

		//	echo '<table id="display" style="border: solid 2px black; position: absolute; visibility: hidden">';
			
			foreach($this->alerts as $index => $alert){
					
						
				//	echo '<tr style="border: solid 2px black">';
					echo '<script>alert("';
					echo "Category: " . $alert->getCategory();
					echo "Code: " . $alert->getCode();
					echo  $alert->getMsg();
					echo '");</script>';
			//	echo '</tr>';

			}
			echo '<table>';	
		}


		public function getArray(){
			
			return $this->alerts;
		}


		public function resolveCat($cat){

			switch($cat){

			case 1:
				return 'Monitor';
				
			case 2:
				return 'Logging';
				
			case 3:
				return 'Booking';
				
			case 4:
				return 'Profile';
				
			default:
				return '';
		}			

		}



		public function resolveStatus($status){

			switch($status){

				case 1:
					return 'green';
				case 2:
					return 'yellow';
				case 3:
					return 'red';
				default:
					return 'idk';
			}

		}	
		

	}

	 

?>


<!DOCTYPE html>



