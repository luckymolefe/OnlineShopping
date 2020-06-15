<?php
// require_once("../config/connect.php");
require_once("products.php");
/**
* @author: Lucky Molefe
*/
class Orders extends Shop {
	private $connect = null;
	protected $orderNumber;
	
	function __construct() {
		global $connect;
		$this->conn = $connect;
	}

	public function callKeyGen() {
		require_once('keygen.php');
		$keygen = new Generate();
		if(is_a($keygen, 'Generate')) {
			if(method_exists($keygen, 'randomGenerate')) {
				return call_user_method('randomGenerate', $keygen);
				// echo $keygen->randomGenerate();
			}
		}
	}

	public function setProductOrder($email, $productOrderID) {
		$this->productID = $productOrderID;
		$this->email = $email;
		// $this->id = $uid;
		return $this->createNewOrder();
	}

	private function createNewOrder() {
		$this->orderNumber = $this->callKeyGen();
		$stmt = $this->conn->prepare("INSERT INTO orders (customerID, orderNumber, productID) VALUES (:customer_id, :orderNumber, :product_id)");
		// $stmt->bindParam(':orderID', $this->id, PDO::PARAM_INT);
		$stmt->bindParam(':customer_id', $this->hash_data($this->email), PDO::PARAM_STR);
		$stmt->bindParam(':orderNumber', $this->orderNumber, PDO::PARAM_STR);
		$stmt->bindParam(':product_id', $this->productID, PDO::PARAM_INT);
		if($stmt->execute()) {
			return $this->orderNumber;
		}
		else {
			return false;
		}
	}

	public function checkOrder($email, $orderNumber) {
		$this->email = $email;
		$this->orderNumber = $orderNumber;
		
		$orders = $this->getOrders($this->email);
		foreach($orders as $order) {
			if($this->orderNumber == $order['orderNumber']) {
				return "exists";
			} else {
				return "null";
			}
		}
		
	}

	public function getOrders($email) {
		$this->email = $email;
		$stmt =  $this->conn->prepare("SELECT * FROM orders WHERE customerID = :email ORDER BY orderDate DESC");
		$stmt->bindValue(':email', $this->hash_data($this->email), PDO::PARAM_STR);
		$stmt->execute();
		if($stmt->rowCount() > 0) {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		else {
			return false;
		}
	}

	public function cancelOrder($email, $orderNum) {
		$this->email = $email;
		$this->orderNumber = $orderNum;
		return $this->removeOrder();
	}

	private function removeOrder() {
		$stmt = $this->conn->prepare("DELETE FROM orders WHERE customerID = ? AND orderNumber = ?");
		$stmt->bindValue(1, $this->hash_data($this->email), PDO::PARAM_STR);
		$stmt->bindValue(2, $this->orderNumber, PDO::PARAM_STR);
		if($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

} /*END class Orders */

$initOrder = new Orders();
// echo $initOrder->checkOrder('luckmolf@company.com', 'K2hvWi');
?>