<?php
/**
* @author: Lucky Molefe
*/
//class handling generating of random numbers
class Generate {
	private $length;
	private $chars; 
	//set length and string of values
	public function __construct() {
		$this->length = 6;
		$this->chars = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	}
	//return random generated strings
	public function randomGenerate() {
		return $this->genRndString();
	}
	//process the random strings
	private function genRndString() {
	    if($this->length > 0) {
	        $len_chars = (strlen($this->chars) - 1);
	        $the_chars = $this->chars{rand(0, $len_chars)};
	        for ($i = 1; $i < $this->length; $i = strlen($the_chars))
	        {
	            $r = $this->chars{rand(0, $len_chars)};
	            if ($r != $the_chars{$i - 1}) $the_chars .=  $r;
	        }
	        return $the_chars;
	    }
	}

	public function genProductOrder() {
		return rand(0, 50000);
	}
}

// $keygen = new Generate(); //instantiate object class
/*if(method_exists($keygen, "genProductOrder")) {
	echo $keygen->genProductOrder();
}*/

?>