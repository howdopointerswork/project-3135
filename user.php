
<?php

    class User{

        private int $id;

        private int $action;

	private string $name;

	private int $age = 0;

	private float $ht = 0;

	private float $wt = 0;

	private float $bmi = 0;

	private string $gender = '';

	private string $img = '';



        
        public function __construct(string $name="Guest"){
            
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

	public function setName(string $name){

		$this->name = $name;
	}

	public function setAge(int $n){

		$this->age = $n;
	}


	public function getAge() : int{

		return $this->age;
	}


	public function setHt(float $n){

		$this->ht = $n;

	}

	public function getHt() : float{

		return $this->ht;
	}

	public function setWt(float $n){
		
		$this->wt = $n;
	}

	public function getWt() : float{

		return $this->wt;

	}

	public function setGender(string $n){

		$this->gender = $n;

	}

	public function getGender() : string{

		return $this->gender;
	}

	public function setImg(string $n){

		$this->img = $n;
	}

	public function getImg() : string{
	
		return $this->img;
	}





    }

?>
