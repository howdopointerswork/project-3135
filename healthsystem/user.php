<?php

	class User{

		private int $id;

		private int $action;

		private string $name;

		
		public function __construct(int $id, string $name){
			
			$this->id = $id;
			$this->name = $name;
		}

		public function getID() : int{
	
			return $this->id;
		}


		public function setID(int $n){

			$this->id = $n;
		}


		public function getAction() : int{

			return $this->action;
		}


		public function setAction(int $n){

			$this->action = $n;
		}

		public function getName() : string{

			return $this->name;
		}



	}

?>
