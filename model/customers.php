<?php
// require_once("../config/connect.php");
require_once("products.php");
 /**
 * @author: Lucky Molefe
 */

 class Customer extends Shop {
 	private $connect = null;
 	private $uid;
 	private $password;
	protected $firstname;
	protected $lastname;
	protected $telephone;
	protected $email;
	protected $address;

 	function __construct() {
 		global $connect;
 		$this->conn=$connect;
 	}

 	public function userLogin($email, $password) {
		$this->email = $email;
		$this->password = $password;
		return $this->isLogged();
	}

	public function isLogged() {
		$stmt = $this->conn->prepare("SELECT * FROM customers WHERE email = ? AND password = ? LIMIT 0,1"); 
		$stmt->bindValue(1, $this->email, PDO::PARAM_STR);
		$stmt->bindValue(2, $this->password, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $rows = $stmt->fetch(PDO::FETCH_ASSOC);
			// return true;
		} else {
			return false;
		}
	}

	public function isUserExist($email) {
		$this->email = $email;
		$data = $this->validateAccount();
		if($data['email'] == $this->email) {
			return true;
		} else {
			return false;
		}
	}

	private function validateAccount() {
		$stmt = $this->conn->prepare("SELECT email FROM customers WHERE email = :email LIMIT 0,1");
		$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $row = $stmt->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function userRegister($fname, $lname, $emailAdd, $userPass) {
		$this->firstname = $fname;
		$this->lastname = $lname;
		$this->email = $emailAdd;
		$this->password = $userPass;
		return $this->setUserData();
	}

	private function setUserData() {
		$stmt = $this->conn->prepare(" INSERT INTO customers (firstname, lastname, email, password) VALUES (:firstname, :lastname, :email, :password) ");
		$stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':password', $this->password, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function updateUser($fname, $lname, $telephone, $emailAdd, $userPass, $homeAddress) {
		$this->firstname = $fname;
		$this->lastname = $lname;
		$this->telephone = $telephone;
		$this->email = $emailAdd;
		$this->password = $userPass;
		$this->address = $homeAddress;
		return $this->setUserUpdate();
	}

	private function setUserUpdate() {
		$stmt = $this->conn->prepare("UPDATE customers 
									SET firstname = :firstname, lastname = :lastname, telephone = :telephone, email = :email, password = :password, customer_address = :address 
									WHERE email = :email ");
		$stmt->bindParam(':firstname', $this->firstname, PDO::PARAM_STR);
		$stmt->bindParam(':lastname', $this->lastname, PDO::PARAM_STR);
		$stmt->bindParam(':telephone', $this->telephone, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		$stmt->bindParam(':password', $this->hash_data($this->password), PDO::PARAM_STR);
		$stmt->bindParam(':address', $this->address, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	public function getProfile($data=null, $email) {
		$this->uid = $data;
		$this->email = $email;
		return $this->getUserDetails(); //return the profile data
	}

	private function getUserDetails() {
		$stmt = $this->conn->prepare("SELECT * FROM customers WHERE email = :email "); // customer_id = ':id' OR
		// $stmt->bindParam(':id', $this->uid, PDO::PARAM_STR);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_INT);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $row = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function updateAddress($newAddress, $email, $telephone) {
		$this->address = $newAddress;
		$this->email = $email;
		$this->telephone = $telephone;
		return $this->setNewAddress();
	}

	private function setNewAddress() {
		$stmt = $this->conn->prepare("UPDATE customers SET customer_address = :address, telephone = :telephone WHERE email = :email");
		$stmt->bindParam(':address', $this->address, PDO::PARAM_STR);
		$stmt->bindParam(':telephone', $this->telephone, PDO::PARAM_INT);
		$stmt->bindParam(':email', $this->email, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		}
		else {
			return false;
		}
	}

	/*public function hash_data($data) {
		return sha1($this->password=$data);
	}*/
 } /* END class Customer */

$initCustomer = new Customer();

?>