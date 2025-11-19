
<?php

    class User{

        private int $id = 0;

        private int $action = 0;

	private string $name = "";

	private int $age = 0;

	private float $ht = 0;

	private float $wt = 0;

	private float $bmi = 0;

	private string $gender = '';

	private string $img = '';

	private int $privilege = 0;



        
        public function __construct(int $id=0, string $name="Guest", int $age=0, float $ht=0, float $wt=0, string $gender='', string $img='', int $privilege = 0){
            
		$this->id = $id;
		$this->name = $name;
		$this->age = $age;
		$this->ht = $ht;
		$this->wt = $wt;
		$this->gender = $gender;
		$this->privilege = $privilege;		
		
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

	public function setPrivilege($n){

		$this->privilege = $n;
	}

	public function getPrivilege() : int{
		
		return $this->privilege;

	}




    }

?>
