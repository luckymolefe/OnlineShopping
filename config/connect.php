<?php

//connection script for DB
class Database {
	private $host = "localhost";
	private $DBName = "shop";
	private $DBUsername = "webusers";
	private $DBPassword = "edecobode";
	public $connect = null;

	private function getConnect() {
		try {
			$this->connect = new PDO("mysql:host=".$this->host."; dbname=".$this->DBName, $this->DBUsername, $this->DBPassword);
		}
		catch(PDOException $e) {
			echo "ERROR: ".$e->getMessage();
		}
		return $this->connect;
	}

	public function connect() {
		return $this->getConnect();
	}

}

if(class_exists('Database')) {
	$initDB = new Database();
	if(method_exists($initDB, 'connect')) {
		$connect = $initDB->connect();
	}
}


?>